<?php

namespace App\Controllers;

class Foundation extends BaseController{


	public function setup($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'foundation/setup';

		$log_id = $this->session->get('td_id');
		$switch_id = $this->session->get('switch_church_id');

		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		if (!empty($switch_id)) {
			$church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
			if ($church_type == 'region') {
				$role_id = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');
			}
			if ($church_type == 'zone') {
				$role_id = $this->Crud->read_field('name', 'Zonal Manager', 'access_role', 'id');
			}
			if ($church_type == 'group') {
				$role_id = $this->Crud->read_field('name', 'Group Manager', 'access_role', 'id');
			}
			if ($church_type == 'church') {
				$role_id = $this->Crud->read_field('name', 'Church Leader', 'access_role', 'id');
			}
		}
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		$role_c = $this->Crud->module($role_id, $mod, 'create');
		$role_r = $this->Crud->module($role_id, $mod, 'read');
		$role_u = $this->Crud->module($role_id, $mod, 'update');
		$role_d = $this->Crud->module($role_id, $mod, 'delete');
		if ($role_r == 0) {
			return redirect()->to(site_url('dashboard'));
		}
		$data['log_id'] = $log_id;
		$data['role'] = $role;
		$data['role_c'] = $role_c;


		$table = 'foundation_setup';
		$form_link = site_url($mod);
		if ($param1) {
			$form_link .= '/' . $param1;
		}
		if ($param2) {
			$form_link .= '/' . $param2 . '/';
		}
		if ($param3) {
			$form_link .= $param3;
		}

		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		$data['current_language'] = $this->session->get('current_language');

		// manage record
		if ($param1 == 'manage') {
			// prepare for delete
			if ($param2 == 'delete') {
				if ($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if (!empty($edit)) {
						foreach ($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if ($this->request->getMethod() == 'post') {
						$del_id = $this->request->getVar('d_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'foundation_setup', 'quarter');
						$action = $by . ' deleted Foundation Setup (' . $code . ') Record';

						if ($this->Crud->deletes('id', $del_id, $table) > 0) {

							$this->Crud->activity('foundation_setup', $del_id, $action);
							echo $this->Crud->msg('success', 'Foundation Setup Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;
					}
				}
			} else {
				// prepare for edit
				if ($param2 == 'edit') {
					if ($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if (!empty($edit)) {
							foreach ($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_year'] = $e->year;
								$data['e_quarter'] = $e->quarter;
								$data['e_start_date'] = $e->start_date;
								$data['e_end_date'] = $e->end_date;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_location'] = $e->location;
								$data['e_is_joint'] = $e->is_joint;
								$data['e_church_id'] = $e->church_id;
								$data['e_weekly_time'] = $e->weekly_time;
								$data['e_active'] = $e->active;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$edit_id = $this->request->getVar('e_id');
					$quarter = $this->request->getVar('quarter');
					$year = $this->request->getVar('year');
					$start_date = $this->request->getVar('start_date');
					$end_date = $this->request->getVar('end_date');
					$location = $this->request->getVar('location');
					$days = $this->request->getVar('days');
					$times = $this->request->getVar('times');
					$is_joint = $this->request->getVar('is_joint');
					$ministry_id = $this->request->getVar('ministry_id');
					$church_id = $this->request->getVar('church_id');
					$active = $this->request->getVar('active');

					$weekly_time = [];
					if (!empty($days) && !empty($times)) {
						foreach ($days as $index => $day) {
							// Ensure the corresponding time exists
							if (!empty($day) && isset($times[$index])) {
								$weekly_time[] = [
									'day' => $day,
									'time' => $times[$index]
								];
							}
						}
					}

					$ins_data['quarter'] = $quarter;
					$ins_data['year'] = $year;
					$ins_data['start_date'] = $start_date;
					$ins_data['end_date'] = $end_date;
					$ins_data['location'] = $location;
					$ins_data['weekly_time'] = json_encode($weekly_time);
					$ins_data['is_joint'] = $is_joint;
					$ins_data['church_id'] = json_encode($church_id);
					$ins_data['active'] = 1;
					$ins_data['ministry_id'] = $ministry_id;

					// do create or update
					if ($edit_id) {
						$upd_rec = $this->Crud->updates('id', $edit_id, $table, $ins_data);
						if ($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $edit_id, 'foundation_setup', 'quarter');
							$action = $by . ' updated Foundation Setup (' . $code . ') Record';
							$this->Crud->activity('foundation_setup', $edit_id, $action);

							echo $this->Crud->msg('success', 'Foundation Setup Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						if ($this->Crud->check3('church_id', json_encode($church_id), 'year', $year, 'quarter', $quarter, $table) > 0) {
							echo $this->Crud->msg('warning', 'Foundation Setup Already Exist');
						} else {

							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'foundation_setup', 'quarter');
								$action = $by . ' created Foundation Setup (' . $code . ') Record';
								$this->Crud->activity('foundation_setup', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Foundation Setup Created');
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', 'Please try later');
							}
						}
					}

					die;
				}
			}
		}

		// record listing
		if ($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';
			if (empty($limit)) {
				$limit = $rec_limit;
			}
			if (empty($offset)) {
				$offset = 0;
			}

			$search = $this->request->getPost('search');
			$start_date = $this->request->getPost('start_date');
			$end_date = $this->request->getPost('end_date');

			$items = '
				
				
			';
			$a = 1;

			//echo $status;
			$log_id = $this->session->get('td_id');
			if (!$log_id) {
				$item = '<div class="text-center text-muted">' . translate_phrase('Session Timeout! - Please login again') . '</div>';
			} else {
				$type = 'region';
				$all_rec = $this->Crud->filter_foundation_setup('', '', $log_id, $search, $switch_id, $start_date, $end_date);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_foundation_setup($limit, $offset, $log_id, $search, $switch_id, $start_date, $end_date);
				$data['count'] = $counts;

				$switch_id = $this->session->get('switch_church_id');

				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$year = $q->year;
						$quarter = $q->quarter;
						$start_date = $q->start_date;
						$end_date = $q->end_date;
						$location = $q->location;
						$principals = $this->Crud->read_field('id', $q->principal, 'user', 'firstname').' '.$this->Crud->read_field('id', $q->principal, 'user', 'surname');
						$ministry = $this->Crud->read_field('id', $q->ministry_id, 'ministry', 'name');
						$is_joint = $q->is_joint;
						$active = $q->active;
						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));

						$joint = '
							<span class="text-info">Personal Church</span>
						';
						$actives = '
								<span class="text-danger">Disabled</span>
							';
						if($is_joint > 0){
							$joint = '
								<span class="text-success">Joint School</span>
							';
						}
						if($active > 0){
							$actives = '
								<span class="text-success">Active</span>
							';
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if (!empty($switch_id)) {
								$all_btn = '
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit " pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete " pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
								
							';

							}

						}

						$item .= '
							<tr>
								<td>
									<div class="user-card">
								      	<div class="user-name">            
											<span class="tb-lead">' . ucwords($quarter) . '- '.$year.'</span> <br>
											<span class="tb-lead text-primary">' . ucwords($principals) . '</span>        
										</div>    
									</div>  
								</td>
								<td>
									<span class="small text-dark ">' . $start_date . ' &rarr; '.$end_date.'</span>
								</td>
								<td><span class="small text-dark ">' . $joint . '</span></td>
								<td><span class="small text-dark ">' . $actives . '</span></td>
								<td>
									<div class="drodown">
										<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
										<div class="dropdown-menu dropdown-menu-end">
											<ul class="link-list-opt no-bdr">
												' . $all_btn . '
											</ul>
										</div>
									</div>
								</td>
							</tr>
							
						';
						$a++;
					}
				}

			}

			if (empty($item)) {
				$resp['item'] = $items . '
					<Tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/>
						<i class="ni ni-gear" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Foundation Setup Returned') . '
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
				if ($offset >= 25) {
					$resp['item'] = $item;
				}

			}

			$resp['count'] = $counts;

			$more_record = $counts - ($offset + $rec_limit);
			$resp['left'] = $more_record;

			if ($counts > ($offset + $rec_limit)) { // for load more records
				$resp['limit'] = $rec_limit;
				$resp['offset'] = $offset + $limit;
			} else {
				$resp['limit'] = 0;
				$resp['offset'] = 0;
			}

			echo json_encode($resp);
			die;
		}

		if ($param1 == 'manage') { // view for form data posting
			return view($mod . '_form', $data);
		} else { // view for main page

			$data['title'] = translate_phrase('Foundation Setup') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}


	public function templates($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'church/templates';
		$switch_id = $this->session->get('switch_church_id');

		$log_id = $this->session->get('td_id');
		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		if (!empty($switch_id)) {
			$church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
			if ($church_type == 'region') {
				$role_id = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');
			}
			if ($church_type == 'zone') {
				$role_id = $this->Crud->read_field('name', 'Zonal Manager', 'access_role', 'id');
			}
			if ($church_type == 'group') {
				$role_id = $this->Crud->read_field('name', 'Group Manager', 'access_role', 'id');
			}
			if ($church_type == 'church') {
				$role_id = $this->Crud->read_field('name', 'Church Leader', 'access_role', 'id');
			}
		}

		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		$role_c = $this->Crud->module($role_id, $mod, 'create');
		$role_r = $this->Crud->module($role_id, $mod, 'read');
		$role_u = $this->Crud->module($role_id, $mod, 'update');
		$role_d = $this->Crud->module($role_id, $mod, 'delete');
		if ($role_r == 0) {
			return redirect()->to(site_url('dashboard'));
		}
		$data['log_id'] = $log_id;
		$data['role'] = $role;
		$data['role_c'] = $role_c;


		$table = 'service_template';
		$form_link = site_url($mod);
		if ($param1) {
			$form_link .= '/' . $param1;
		}
		if ($param2) {
			$form_link .= '/' . $param2 . '/';
		}
		if ($param3) {
			$form_link .= $param3;
		}

		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		$data['current_language'] = $this->session->get('current_language');

		// manage record
		if ($param1 == 'manage') {
			// prepare for delete
			if ($param2 == 'delete') {
				if ($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if (!empty($edit)) {
						foreach ($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if ($this->request->getMethod() == 'post') {
						$del_id = $this->request->getVar('del_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, $table, 'name');
						$action = $by . ' deleted Service Template (' . $code . ')';

						if ($this->Crud->deletes('id', $del_id, $table) > 0) {

							$this->Crud->activity('service_template', $del_id, $action);
							echo $this->Crud->msg('success', 'Service Template Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;
					}
				}
			} elseif ($param2 == 'extend') {
				if ($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if (!empty($edit)) {
						foreach ($edit as $e) {
							$data['e_id'] = $e->id;
							$data['e_name'] = $e->name;
							$data['e_description'] = $e->description;
							$data['e_section'] = $e->sections;
							$data['e_ministry_id'] = $e->ministry_id;
							$data['e_church_id'] = $e->church_id;
							$data['e_church_type'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');

						}
					}
				}


				if ($this->request->getMethod() == 'post') {
					$template_id = $this->request->getVar('template_id');
					$name = $this->Crud->read_field('id', $template_id, 'service_template', 'name');
					$description = $this->Crud->read_field('id', $template_id, 'service_template', 'description');
					$section = $this->request->getVar('section');
					$priority = $this->request->getVar('priority');
					$ministry_id = $this->request->getVar('ministry_id');
					$church_id = $this->request->getVar('church_id');

					if (empty($church_id)) {

						echo $this->Crud->msg('warning', 'Select a Church');
						die;
					}

					$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
					$name .= ucwords(' for ' . $church);
					$sections = [];

					if (!empty($section) && !empty($priority)) {
						if (count($section) === count($priority)) {  // Ensure both arrays have the same length
							for ($i = 0; $i < count($section); $i++) {
								// Trim to remove any extra spaces and validate each section
								$current_section = trim($section[$i]);
								$current_priority = trim($priority[$i]);

								// Validate that the section is not empty and the priority is a number between 1 and 20
								if (!empty($current_section) && !empty($current_priority) && is_numeric($current_priority) && $current_priority >= 1 && $current_priority <= 20) {
									$sect = [];
									$sect['section'] = $current_section;
									$sect['priority'] = $current_priority;
									$sections[] = $sect;
								} else {
									// Return a specific error message for each failed condition
									if (empty($current_section)) {
										echo $this->Crud->msg('danger', "Section at index $i cannot be empty.");
									} elseif (empty($current_priority) || !is_numeric($current_priority)) {
										echo $this->Crud->msg('danger', "Priority at index $i must be a valid number.");
									} elseif ($current_priority < 1 || $current_priority > 20) {
										echo $this->Crud->msg('danger', "Priority at index $i must be between 1 and 20.");
									}
									die;
								}
							}
						} else {
							echo $this->Crud->msg('danger', 'Mismatch between the number of sections and priorities.');
							die;
						}
					} else {
						echo $this->Crud->msg('danger', 'Please enter both Sections and Priorities.');
						die;
					}


					if (empty($sections)) {
						echo $this->Crud->msg('danger', 'Enter Sections and their Priority');
						die;
					}

					$ins_data['name'] = $name;
					$ins_data['description'] = $description;
					$ins_data['sections'] = json_encode($sections);
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['type'] = 'personal';

					$ins_data['is_extended'] = $template_id;


					// do create or update

					if ($this->Crud->check3('name', $name, 'ministry_id', $ministry_id, 'church_id', $church_id, $table) > 0) {
						echo $this->Crud->msg('warning', 'Service Template Already Exist Already Exist');
					} else {

						$ins_data['reg_date'] = date(fdate);
						$ins_data['update_date'] = date(fdate);
						$ins_rec = $this->Crud->create($table, $ins_data);
						if ($ins_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $ins_rec, $table, 'name');
							$action = $by . ' created an Extended Service Template (' . $code . ')';
							$this->Crud->activity('service_template', $ins_rec, $action);

							echo $this->Crud->msg('success', 'Service Template Extended Successfully');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
					}
					die;
				}
			} else {
				// prepare for edit
				if ($param2 == 'edit') {
					if ($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if (!empty($edit)) {
							foreach ($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_name'] = $e->name;
								$data['e_description'] = $e->description;
								$data['e_section'] = $e->sections;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_is_sharing'] = $e->is_sharing;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$edit_id = $this->request->getVar('edit_id');
					$name = $this->request->getVar('name');
					$description = $this->request->getVar('description');
					$section = $this->request->getVar('section');
					$priority = $this->request->getVar('priority');
					$is_sharing = $this->request->getVar('is_sharing');
					$ministry_id = $this->request->getVar('ministry_id');
					$church_id = $this->request->getVar('church_id');

					$sections = [];

					if (!empty($section) && !empty($priority)) {
						if (count($section) === count($priority)) {  // Ensure both arrays have the same length
							for ($i = 0; $i < count($section); $i++) {
								// Trim to remove any extra spaces and validate each section
								$current_section = trim($section[$i]);
								$current_priority = trim($priority[$i]);

								// Validate that the section is not empty and the priority is a number between 1 and 20
								if (!empty($current_section) && !empty($current_priority) && is_numeric($current_priority) && $current_priority >= 1 && $current_priority <= 20) {
									$sect = [];
									$sect['section'] = $current_section;
									$sect['priority'] = $current_priority;
									$sections[] = $sect;
								} else {
									// Return a specific error message for each failed condition
									if (empty($current_section)) {
										echo $this->Crud->msg('danger', "Section at index $i cannot be empty.");
									} elseif (empty($current_priority) || !is_numeric($current_priority)) {
										echo $this->Crud->msg('danger', "Priority at index $i must be a valid number.");
									} elseif ($current_priority < 1 || $current_priority > 20) {
										echo $this->Crud->msg('danger', "Priority at index $i must be between 1 and 20.");
									}
									die;
								}
							}
						} else {
							echo $this->Crud->msg('danger', 'Mismatch between the number of sections and priorities.');
							die;
						}
					} else {
						echo $this->Crud->msg('danger', 'Please enter both Sections and Priorities.');
						die;
					}


					if (empty($sections)) {
						echo $this->Crud->msg('danger', 'Enter Sections and their Priority');
						die;
					}

					$ins_data['name'] = $name;
					$ins_data['description'] = $description;
					if ($is_sharing > 0) {
						$ins_data['is_sharing'] = $is_sharing;
						$ins_data['type'] = 'personal';
						$ins_data['church_id'] = $church_id;
					}
					$ins_data['sections'] = json_encode($sections);
					$ins_data['ministry_id'] = $ministry_id;

					// do create or update
					if ($edit_id) {

						$ins_data['update_date'] = date(fdate);
						$upd_rec = $this->Crud->updates('id', $edit_id, $table, $ins_data);
						if ($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $edit_id, $table, 'name');
							$action = $by . ' updated Service Template (' . $code . ') Record';
							$this->Crud->activity('service_template', $edit_id, $action);

							echo $this->Crud->msg('success', 'Service Template Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						if ($this->Crud->check3('name', $name, 'ministry_id', $ministry_id, 'church_id', $church_id, $table) > 0) {
							echo $this->Crud->msg('warning', 'Service Template Already Exist Already Exist');
						} else {

							$ins_data['reg_date'] = date(fdate);
							$ins_data['update_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, $table, 'name');
								$action = $by . ' created Service Template (' . $code . ')';
								$this->Crud->activity('service_template', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Service Template Created');
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', 'Please try later');
							}
						}
					}

					die;
				}
			}
		}

		// record listing
		if ($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
			if (empty($limit)) {
				$limit = $rec_limit;
			}
			if (empty($offset)) {
				$offset = 0;
			}

			$search = $this->request->getPost('search');

			$items = '
				
				
			';
			$a = 1;
			$switch_id = $this->session->get('switch_church_id');

			//echo $status;
			$log_id = $this->session->get('td_id');
			if (!$log_id) {
				$item = '<div class="text-center text-muted">' . translate_phrase('Session Timeout! - Please login again') . '</div>';
			} else {

				$all_rec = $this->Crud->filter_templates('', '', $log_id, $search, $switch_id);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_templates($limit, $offset, $log_id, $search, $switch_id);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$church_id = $q->church_id;
						$type = $q->type;
						$description = $q->description;
						$is_extended = $q->is_extended;
						$ministry_id = $q->ministry_id;

						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));
						$update_date = date('d/m/Y h:iA', strtotime($q->update_date));
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');


						if (empty($church)) {
							$church = 'All Churches';
						}

						$all_btn = '';
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if (!empty($switch_id)) {
								$all_btn = ' ';
							} else {
								if ($role != 'developer' && $role != 'administrator') {
									if ($role == 'ministry administrator') {
										if ($type == 'all') {
											$all_btn = '
												<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '" pageSize="modal-lg"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
												<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
												
											';
										}

									} else {
										if ($type == 'all') {
											$all_btn = '
												<li><a href="javascript:;" pageTitle="Extend Template ' . $name . '" pageName="' . site_url($mod . '/manage/extend/' . $id) . '" pageSize="modal-lg" class="text-info pop" ><em class="icon ni ni-file-plus"></em><span>' . translate_phrase('Extend Template') . '</span></a></li>
												
											';
										} else {
											$all_btn = '
												<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '" pageSize="modal-lg"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
												<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
												
											';

										}

									}
								} else {

									$all_btn = '
										<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '" pageSize="modal-lg"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
										<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
										<li><a href="javascript:;" pageTitle="Extend Template ' . $name . '" pageName="' . site_url($mod . '/manage/extend/' . $id) . '" pageSize="modal-lg" class="text-info pop" ><em class="icon ni ni-file-plus"></em><span>' . translate_phrase('Extend Template') . '</span></a></li>
										
									';
								}


							}

						}


						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-name">            
											<span class="tb-lead">' . ucwords($name) . '</span> <br>
											<span class="tb-lead text-primary"><em class="icon ni ni-curve-down-right"></em>' . ucwords($church) . '</span>                   
										</div>    
									</div>  
								</td>
								<td>
									<span class="small text-dark">' . ucwords($type) . '</span>
								</td>
								<td><span class="small text-dark">' . ucwords($description) . '</span></td>
								<td><span class="small text-dark">' . $update_date . '</span></td>
								<td>
									<div class="drodown">
										<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
										<div class="dropdown-menu dropdown-menu-end">
											<ul class="link-list-opt no-bdr">
												' . $all_btn . '
											</ul>
										</div>
									</div>
								</td>
							</tr>
							
						';
						$a++;
					}
				}

			}

			if (empty($item)) {
				$resp['item'] = $items . '
					<Tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/>
						<i class="ni ni-template" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Service Template Returned') . '
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
				if ($offset >= 25) {
					$resp['item'] = $item;
				}

			}


			$resp['count'] = $counts;

			$more_record = $counts - ($offset + $rec_limit);
			$resp['left'] = $more_record;

			if ($counts > ($offset + $rec_limit)) { // for load more records
				$resp['limit'] = $rec_limit;
				$resp['offset'] = $offset + $limit;
			} else {
				$resp['limit'] = 0;
				$resp['offset'] = 0;
			}

			echo json_encode($resp);
			die;
		}

		if ($param1 == 'manage') { // view for form data posting
			return view($mod . '_form', $data);
		} else { // view for main page

			$data['title'] = translate_phrase('Service Template') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	public function records($param1='', $param2=''){
		$log_id = $this->session->get('td_id');
		
		if($param1 == 'get_church'){
			$ministry_id = $this->request->getPost('ministry_id');
			$level = $this->request->getPost('level');
			$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
			$church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
			if($church_type == 'region'){
				$type_id = 'regional_id';
			}
			if($church_type == 'zone'){
				$type_id = 'zonal_id';
			}
			if($church_type == 'group'){
				$type_id = 'group_id';
			}

			if ($ministry_id && $level) {
				if($church_id > 0){
					$church = $this->Crud->read3_order($type_id, $church_id, 'type', $level, 'ministry_id', $ministry_id, 'church', 'name', 'asc');
				} else{
					$church = $this->Crud->read2_order('type', $level, 'ministry_id', $ministry_id, 'church', 'name', 'asc');
				}

				$churches = [];
				$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
				$church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
						
				if (!empty($church)) {
					foreach ($church as $c) {
						
						$churches[] = [
							'id' => $c->id,
							'name' => $c->name,
							'type' => $c->type
						];
					}
				}

				return json_encode($churches);
			}
			
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Invalid Ministry ID'
			]);
		}

		//Get Members From the Church
		if($param1 == 'get_members'){
			$church_id = $this->request->getPost('church_id');
			if ($church_id) {
				$church = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');
				

				$churches = [];
						
				if (!empty($church)) {
					foreach ($church as $c) {
						
						$churches[] = [
							'id' => $c->id,
							'name' => $c->firstname.' '.$c->surname,
							'phone' => $c->phone
						];
					}
				}

				return $this->response->setJSON([
					'success' => true,
					'data' => $churches
				]);
			}
			
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Invalid Ministry ID'
			]);
		}
	}

}