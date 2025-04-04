<?php

namespace App\Controllers;

class Church extends BaseController{


	public function regional($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'church/regional';

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


		$table = 'church';
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
						$del_id = $this->request->getVar('d_dept_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'church', 'name');
						$action = $by . ' deleted Church (' . $code . ') Record';

						if ($this->Crud->deletes('id', $del_id, $table) > 0) {

							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Church Deleted');
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
								$data['e_name'] = $e->name;
								$data['e_email'] = $e->email;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_country_id'] = $e->country_id;
								$data['e_img'] = $e->logo;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$dept_id = $this->request->getVar('dept_id');
					$name = $this->request->getVar('name');
					$email = $this->request->getVar('email');
					$phone = $this->request->getVar('phone');
					$address = $this->request->getVar('address');
					$img_id = $this->request->getVar('img');
					$ministry_id = $this->request->getVar('ministry_id');
					$country_id = $this->request->getVar('country_id');


					//// Image upload
					if (file_exists($this->request->getFile('pics'))) {
						if (!empty($img_id)) {
							unlink(FCPATH . $img_id);
						}
						$path = 'assets/images/ministry/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);

						if (!empty($getImg->path))
							$img_id = $getImg->path;
					}


					$ins_data['name'] = $name;
					$ins_data['email'] = $email;
					$ins_data['address'] = $address;
					$ins_data['phone'] = $phone;
					$ins_data['type'] = 'region';
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['country_id'] = $country_id;
					if (!empty($img_id) || !empty($getImg->path))
						$ins_data['logo'] = $img_id;

					// do create or update
					if ($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if ($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'church', 'name');
							$action = $by . ' updated Church (' . $code . ') Record';
							$this->Crud->activity('church', $dept_id, $action);

							echo $this->Crud->msg('success', 'Church Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						if ($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Church Already Exist');
						} else {

							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'church', 'name');
								$action = $by . ' created Church (' . $code . ') Record';
								$this->Crud->activity('church', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Church Created');
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

			//echo $status;
			$log_id = $this->session->get('td_id');
			if (!$log_id) {
				$item = '<div class="text-center text-muted">' . translate_phrase('Session Timeout! - Please login again') . '</div>';
			} else {
				$type = 'region';
				$all_rec = $this->Crud->filter_church('', '', $log_id, $search, $type, $switch_id);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_church($limit, $offset, $log_id, $search, $type, $switch_id);
				$data['count'] = $counts;

				$switch_id = $this->session->get('switch_church_id');

				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$email = $q->email;
						$phone = $q->phone;
						$logo = $q->logo;
						$ministry_id = $q->ministry_id;
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$country_id = $q->country_id;
						$country = $this->Crud->read_field('id', $country_id, 'country', 'name');
						$address = $q->address;
						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));

						if (!empty($logo)) {
							$img = '<img height="40px" src="' . site_url($logo) . '">';
						} else {
							$img = $this->Crud->image_name($name);
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if (!empty($switch_id)) {
								$all_btn = '
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Admin') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_pastor(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Pastors') . '</span></a></li>
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Admin') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_pastor(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Pastors') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_login(' . (int) $id . ');" class="text-secondary" ><em class="icon ni ni-signin"></em><span>' . translate_phrase('Login to Church') . '</span></a></li>
								
								
							';

							}

						}

						$item .= '
							<tr>
								<td>
									<div class="user-card">
								      	<div class="user-avatar">            
									  		' . $img . '      
										</div>        
										<div class="user-name">            
											<span class="tb-lead">' . ucwords($name) . '</span> <br>
											<span class="">' . ucwords($ministry) . '</span>        
										</div>    
									</div>  
								</td>
								<td>
									<span class="small ">' . $email . '</span><br>
									<span class="small ">' . $phone . '</span>
								</td>
								<td><span class="small ">' . $address . '</span><br><span class=" small">'.$country.'</span></td>
								<td><span class="small ">' . $reg_date . '</span></td>
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
						<i class="ni ni-home-alt" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Regional Church Returned') . '
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

			$data['title'] = translate_phrase('Regional Church') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}


	public function zonal($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'church/zonal';
		$switch_id = $this->session->get('switch_church_id');
		//    echo $switch_id;
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


		$table = 'church';
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
						$del_id = $this->request->getVar('d_dept_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'church', 'name');
						$action = $by . ' deleted Church (' . $code . ') Record';

						if ($this->Crud->deletes('id', $del_id, $table) > 0) {

							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Church Deleted');
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
								$data['e_name'] = $e->name;
								$data['e_email'] = $e->email;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_country_id'] = $e->country_id;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_regional_id'] = $e->regional_id;
								$data['e_img'] = $e->logo;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$dept_id = $this->request->getVar('dept_id');
					$name = $this->request->getVar('name');
					$email = $this->request->getVar('email');
					$phone = $this->request->getVar('phone');
					$address = $this->request->getVar('address');
					$regional_id = $this->request->getVar('region_id');
					$img_id = $this->request->getVar('img');
					$ministry_id = $this->request->getVar('ministry_id');
					$country_id = $this->request->getVar('country_id');


					//// Image upload
					if (file_exists($this->request->getFile('pics'))) {
						if (!empty($img_id)) {
							unlink(FCPATH . $img_id);
						}
						$path = 'assets/images/ministry/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);

						if (!empty($getImg->path))
							$img_id = $getImg->path;
					}


					$ins_data['name'] = $name;
					$ins_data['email'] = $email;
					$ins_data['address'] = $address;
					$ins_data['phone'] = $phone;
					$ins_data['type'] = 'zone';
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['country_id'] = $country_id;
					$ins_data['regional_id'] = $regional_id;
					if (!empty($img_id) || !empty($getImg->path))
						$ins_data['logo'] = $img_id;

					// do create or update
					if ($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if ($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'church', 'name');
							$action = $by . ' updated Church (' . $code . ') Record';
							$this->Crud->activity('church', $dept_id, $action);

							echo $this->Crud->msg('success', 'Church Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						if ($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Church Already Exist');
						} else {

							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'church', 'name');
								$action = $by . ' created Church (' . $code . ') Record';
								$this->Crud->activity('church', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Church Created');
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

			//echo $status;
			$log_id = $this->session->get('td_id');
			if (!$log_id) {
				$item = '<div class="text-center text-muted">' . translate_phrase('Session Timeout! - Please login again') . '</div>';
			} else {
				$type = 'zone';
				$all_rec = $this->Crud->filter_church('', '', $log_id, $search, $type, $switch_id);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_church($limit, $offset, $log_id, $search, $type, $switch_id);
				$data['count'] = $counts;
				$switch_id = $this->session->get('switch_church_id');


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$email = $q->email;
						$phone = $q->phone;
						$logo = $q->logo;
						$address = $q->address;
						$ministry_id = $q->ministry_id;
						$country_id = $q->country_id;
						$country = $this->Crud->read_field('id', $country_id, 'country', 'name');
						$regional_id = $q->regional_id;
						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$mins = '';

						if (!empty($regional_id))
							$mins .= ' ' . ucwords(strtolower($this->Crud->read_field('id', $regional_id, 'church', 'name'))) . ' Region';
						if (!empty($logo)) {
							$img = '<img height="40px" src="' . site_url($logo) . '">';
						} else {
							$img = $this->Crud->image_name($name);
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if (!empty($switch_id)) {
								$all_btn = '
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . ' Zone\', ' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Admin') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_pastor(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Pastors') . '</span></a></li>
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . ' Zone\', ' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Admin') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_pastor(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Pastors') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_login(' . (int) $id . ');" class="text-secondary" ><em class="icon ni ni-signin"></em><span>' . translate_phrase('Login to Church') . '</span></a></li>
								
								
							';
							}

						}

						$item .= '
						<tr>
							<td>
								<div class="user-card">
									<div class="user-avatar">            
										' . $img . '      
									</div>        
									<div class="user-name">            
										<span class="tb-lead">' . ucwords($name) . '</span>   <br>
										<span class="tb-lead">' . ucwords($ministry) . '</span>              
									</div>    
								</div>  
							</td>
							<td>
								<span class="small">' . $email . '</span><br>
								<span class="small">' . $phone . '</span>
							</td>
							<td><span class="small">' . $address . '</span><br>
								<span class="small">' . $country . '</span></td>
							<td><span class="small">' . $mins . '</span></td>
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
				<tr><td colspan="8"><div class="text-center text-muted">
					<br/><br/><br/>
					<i class="ni ni-home-alt" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Zonal Church Returned') . '
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

			$data['title'] = translate_phrase('Zonal Church') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}


	public function group($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'church/group';
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


		$table = 'church';
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
						$del_id = $this->request->getVar('d_dept_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'church', 'name');
						$action = $by . ' deleted Church (' . $code . ') Record';

						if ($this->Crud->deletes('id', $del_id, $table) > 0) {

							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Church Deleted');
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
								$data['e_name'] = $e->name;
								$data['e_email'] = $e->email;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_regional_id'] = $e->regional_id;
								$data['e_zonal_id'] = $e->zonal_id;
								$data['e_country_id'] = $e->country_id;
								$data['e_img'] = $e->logo;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$dept_id = $this->request->getVar('dept_id');
					$name = $this->request->getVar('name');
					$email = $this->request->getVar('email');
					$phone = $this->request->getVar('phone');
					$address = $this->request->getVar('address');
					$regional_id = $this->request->getVar('regional_id');
					$zonal_id = $this->request->getVar('zonal_id');
					$img_id = $this->request->getVar('img');
					$ministry_id = $this->request->getVar('ministry_id');
					$country_id = $this->request->getVar('country_id');


					//// Image upload
					if (file_exists($this->request->getFile('pics'))) {
						if (!empty($img_id)) {
							unlink(FCPATH . $img_id);
						}
						$path = 'assets/images/ministry/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);

						if (!empty($getImg->path))
							$img_id = $getImg->path;
					}

					if (empty($regional_id)) {
						echo $this->Crud->msg('warning', 'Please select Regional Church');
						die;
					}

					if (empty($zonal_id)) {
						echo $this->Crud->msg('warning', 'Please select Zonal Church');
						die;
					}


					$ins_data['name'] = $name;
					$ins_data['email'] = $email;
					$ins_data['address'] = $address;
					$ins_data['phone'] = $phone;
					$ins_data['type'] = 'group';
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['regional_id'] = $regional_id;
					$ins_data['zonal_id'] = $zonal_id;
					$ins_data['country_id'] = $country_id;
					if (!empty($img_id) || !empty($getImg->path))
						$ins_data['logo'] = $img_id;

					// do create or update
					if ($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if ($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'church', 'name');
							$action = $by . ' updated Church (' . $code . ') Record';
							$this->Crud->activity('church', $dept_id, $action);

							echo $this->Crud->msg('success', 'Church Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						if ($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Church Already Exist');
						} else {

							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'church', 'name');
								$action = $by . ' created Church (' . $code . ') Record';
								$this->Crud->activity('church', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Church Created');
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
				$type = 'group';
				$all_rec = $this->Crud->filter_church('', '', $log_id, $search, $type);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_church($limit, $offset, $log_id, $search, $type);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$email = $q->email;
						$phone = $q->phone;
						$logo = $q->logo;
						$address = $q->address;
						$ministry_id = $q->ministry_id;
						$country_id = $q->country_id;
						$regional_id = $q->regional_id;
						$zonal_id = $q->zonal_id;
						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$country = $this->Crud->read_field('id', $country_id, 'country', 'name');
						$mins = '';

						if (!empty($regional_id))
							$mins .= ' ' . ucwords(strtolower($this->Crud->read_field('id', $regional_id, 'church', 'name'))) . ' Region';
						if (!empty($zonal_id))
							$mins .= '<br>&#8594; ' . ucwords(strtolower($this->Crud->read_field('id', $zonal_id, 'church', 'name'))) . ' Zone';

						if (!empty($logo)) {
							$img = '<img height="40px" src="' . site_url($logo) . '">';
						} else {
							$img = $this->Crud->image_name($name);
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if (!empty($switch_id)) {
								$all_btn = '
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . ' Group\', ' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Admin') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_pastor(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Pastors') . '</span></a></li>
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . ' Group\', ' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Admin') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_pastor(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Pastors') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_login(' . (int) $id . ');" class="text-secondary" ><em class="icon ni ni-signin"></em><span>' . translate_phrase('Login to Church') . '</span></a></li>
								
								
							';

							}

						}


						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-avatar">            
											' . $img . '      
										</div>        
										<div class="user-name">            
											<span class="tb-lead">' . ucwords($name) . '</span> <br>
										<span class="tb-lead">' . ucwords($ministry) . '</span>                   
										</div>    
									</div>  
								</td>
								<td>
									<span class="small">' . $email . '</span><br>
									<span class="small">' . $phone . '</span>
								</td>
								<td><span class="small">' . $address . '</span><br>
								<span class="small">' . $country . '</span></td>
								<td><span class="small">' . $mins . '</span></td>
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
						<i class="ni ni-home-alt" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Group Church Returned') . '
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

			$data['title'] = translate_phrase('Group Church') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}


	public function church($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'church/church';
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


		$table = 'church';
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
						$del_id = $this->request->getVar('d_dept_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'church', 'name');
						$action = $by . ' deleted Church (' . $code . ') Record';

						if ($this->Crud->deletes('id', $del_id, $table) > 0) {

							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Church Deleted');
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
								$data['e_name'] = $e->name;
								$data['e_email'] = $e->email;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_regional_id'] = $e->regional_id;
								$data['e_zonal_id'] = $e->zonal_id;
								$data['e_group_id'] = $e->group_id;
								$data['e_country_id'] = $e->country_id;
								$data['e_img'] = $e->logo;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$dept_id = $this->request->getVar('dept_id');
					$name = $this->request->getVar('name');
					$email = $this->request->getVar('email');
					$phone = $this->request->getVar('phone');
					$address = $this->request->getVar('address');
					$regional_id = $this->request->getVar('regional_id');
					$zonal_id = $this->request->getVar('zonal_id');
					$group_id = $this->request->getVar('group_id');
					$img_id = $this->request->getVar('img');
					$ministry_id = $this->request->getVar('ministry_id');
					$country_id = $this->request->getVar('country_id');


					//// Image upload
					if (file_exists($this->request->getFile('pics'))) {
						if (!empty($img_id)) {
							unlink(FCPATH . $img_id);
						}
						$path = 'assets/images/ministry/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);

						if (!empty($getImg->path))
							$img_id = $getImg->path;
					}

					if (empty($regional_id)) {
						echo $this->Crud->msg('warning', 'Please select Regional Church');
						die;
					}

					if (empty($zonal_id)) {
						echo $this->Crud->msg('warning', 'Please select Zonal Church');
						die;
					}
					if (empty($group_id)) {
						// echo $this->Crud->msg('warning', 'Please select Group Church');
						// die;
					}


					$ins_data['name'] = $name;
					$ins_data['email'] = $email;
					$ins_data['address'] = $address;
					$ins_data['phone'] = $phone;
					$ins_data['type'] = 'church';
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['regional_id'] = $regional_id;
					$ins_data['zonal_id'] = $zonal_id;
					$ins_data['group_id'] = $group_id;
					$ins_data['country_id'] = $country_id;
					if (!empty($img_id) || !empty($getImg->path))
						$ins_data['logo'] = $img_id;

					// do create or update
					if ($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if ($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'church', 'name');
							$action = $by . ' updated Church (' . $code . ') Record';
							$this->Crud->activity('church', $dept_id, $action);

							echo $this->Crud->msg('success', 'Church Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						if ($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Church Already Exist');
						} else {

							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'church', 'name');
								$action = $by . ' created Church (' . $code . ') Record';
								$this->Crud->activity('church', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Church Created');
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

			//echo $status;
			$log_id = $this->session->get('td_id');
			if (!$log_id) {
				$item = '<div class="text-center text-muted">' . translate_phrase('Session Timeout! - Please login again') . '</div>';
			} else {
				$type = 'church';
				$all_rec = $this->Crud->filter_church('', '', $log_id, $search, $type);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_church($limit, $offset, $log_id, $search, $type);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$email = $q->email;
						$phone = $q->phone;
						$logo = $q->logo;
						$address = $q->address;
						$ministry_id = $q->ministry_id;
						$regional_id = $q->regional_id;
						$zonal_id = $q->zonal_id;
						$group_id = $q->group_id;
						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$country = $this->Crud->read_field('id', $q->country_id, 'country', 'name');
						$mins = '';

						if (!empty($regional_id))
							$mins .= ' ' . ucwords(strtolower($this->Crud->read_field('id', $regional_id, 'church', 'name'))) . ' Region';
						if (!empty($zonal_id))
							$mins .= '<br>&#8594; ' . ucwords(strtolower($this->Crud->read_field('id', $zonal_id, 'church', 'name'))) . ' Zone';
						if (!empty($group_id))
							$mins .= '<br>&#8594; ' . ucwords(strtolower($this->Crud->read_field('id', $group_id, 'church', 'name') )). ' Group';

						if (!empty($logo)) {
							$img = '<img height="40px" src="' . site_url($logo) . '">';
						} else {
							$img = $this->Crud->image_name($name);
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if (!empty($switch_id)) {
								$all_btn = '
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . '\', ' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Admin') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_pastor(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Pastors') . '</span></a></li>
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . '\', ' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Admin') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_pastor(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Pastors') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_login(' . (int) $id . ');" class="text-secondary" ><em class="icon ni ni-signin"></em><span>' . translate_phrase('Login to Church') . '</span></a></li>
								
								
							';
							}

						}

						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-avatar">            
											' . $img . '      
										</div>        
										<div class="user-name">            
											<span class="tb-lead">' . ucwords($name) . '</span>    <br>
										<span class="tb-lead">' . ucwords($ministry) . '</span>                        
										</div>    
									</div>  
								</td>
								<td>
									<span class="small">' . $email . '</span><br>
									<span class="small">' . $phone . '</span>
								</td>
								<td><span class="small">' . $address . '</span><br>
								<span class="small">' . $country . '</span></td>
								<td><span class="small">' . $mins . '</span></td>
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
						<i class="ni ni-home-alt" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Church Assembly Returned') . '
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

			$data['title'] = translate_phrase('Church Assembly') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	

	public function center($param1 = '', $param2 = '', $param3 = ''){
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'church/center';
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


		$table = 'church';
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
						$del_id = $this->request->getVar('d_dept_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'church', 'name');
						$action = $by . ' deleted Service Center (' . $code . ') Record';

						if ($this->Crud->deletes('id', $del_id, $table) > 0) {

							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Service Center Deleted');
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
								$data['e_name'] = $e->name;
								$data['e_email'] = $e->email;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_regional_id'] = $e->regional_id;
								$data['e_zonal_id'] = $e->zonal_id;
								$data['e_group_id'] = $e->group_id;
								$data['e_church_id'] = $e->church_id;
								$data['e_country_id'] = $e->country_id;
								$data['e_img'] = $e->logo;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$dept_id = $this->request->getVar('dept_id');
					$name = $this->request->getVar('name');
					$email = $this->request->getVar('email');
					$phone = $this->request->getVar('phone');
					$address = $this->request->getVar('address');
					$regional_id = $this->request->getVar('regional_id');
					$zonal_id = $this->request->getVar('zonal_id');
					$group_id = $this->request->getVar('group_id');
					$church_id = $this->request->getVar('church_id');
					$img_id = $this->request->getVar('img');
					$ministry_id = $this->request->getVar('ministry_id');
					$country_id = $this->request->getVar('country_id');


					//// Image upload
					if (file_exists($this->request->getFile('pics'))) {
						if (!empty($img_id)) {
							unlink(FCPATH . $img_id);
						}
						$path = 'assets/images/ministry/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);

						if (!empty($getImg->path))
							$img_id = $getImg->path;
					}

					if (empty($regional_id)) {
						echo $this->Crud->msg('warning', 'Please select Regional Church');
						die;
					}

					if (empty($zonal_id)) {
						echo $this->Crud->msg('warning', 'Please select Zonal Church');
						die;
					}
					if (empty($group_id)) {
						// echo $this->Crud->msg('warning', 'Please select Group Church');
						// die;
					}
					if (empty($church_id)) {
						// echo $this->Crud->msg('warning', 'Please select Church Assembly');
						// die;
					}


					$ins_data['name'] = $name;
					$ins_data['email'] = $email;
					$ins_data['address'] = $address;
					$ins_data['phone'] = $phone;
					$ins_data['type'] = 'center';
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['regional_id'] = $regional_id;
					$ins_data['zonal_id'] = $zonal_id;
					$ins_data['group_id'] = $group_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['country_id'] = $country_id;
					if (!empty($img_id) || !empty($getImg->path))
						$ins_data['logo'] = $img_id;

					// do create or update
					if ($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if ($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'church', 'name');
							$action = $by . ' updated Service Center (' . $code . ') Record';
							$this->Crud->activity('church', $dept_id, $action);

							echo $this->Crud->msg('success', 'Service Center Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						if ($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Service Center Already Exist');
						} else {

							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'church', 'name');
								$action = $by . ' created Service Center (' . $code . ') Record';
								$this->Crud->activity('church', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Service Center Created');
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

			//echo $status;
			$log_id = $this->session->get('td_id');
			if (!$log_id) {
				$item = '<div class="text-center text-muted">' . translate_phrase('Session Timeout! - Please login again') . '</div>';
			} else {
				$type = 'center';
				$all_rec = $this->Crud->filter_church('', '', $log_id, $search, $type);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_church($limit, $offset, $log_id, $search, $type);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$email = $q->email;
						$phone = $q->phone;
						$logo = $q->logo;
						$address = $q->address;
						$ministry_id = $q->ministry_id;
						$regional_id = $q->regional_id;
						$zonal_id = $q->zonal_id;
						$group_id = $q->group_id;
						$church_id = $q->church_id;
						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$country = $this->Crud->read_field('id', $q->country_id, 'country', 'name');
						$mins = '';

						if (!empty($regional_id))
							$mins .= ' ' . ucwords(strtolower($this->Crud->read_field('id', $regional_id, 'church', 'name'))) . ' Region';
						if (!empty($zonal_id))
							$mins .= '<br>&#8594; ' . ucwords(strtolower($this->Crud->read_field('id', $zonal_id, 'church', 'name'))) . ' Zone';
						if (!empty($group_id))
							$mins .= '<br>&#8594; ' . ucwords(strtolower($this->Crud->read_field('id', $group_id, 'church', 'name') )). ' Group';
						if (!empty($church_id))
							$mins .= '<br>&#8594; ' . ucwords(strtolower($this->Crud->read_field('id', $church_id, 'church', 'name') )). ' Church';

						if (!empty($logo)) {
							$img = '<img height="40px" src="' . site_url($logo) . '">';
						} else {
							$img = $this->Crud->image_name($name);
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if (!empty($switch_id)) {
								$all_btn = '
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . '\', ' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Admin') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_pastor(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Pastors') . '</span></a></li>
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . '\', ' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Admin') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_pastor(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Pastors') . '</span></a></li>
								<li><a href="javascript:;" onclick="church_login(' . (int) $id . ');" class="text-secondary" ><em class="icon ni ni-signin"></em><span>' . translate_phrase('Login to Church') . '</span></a></li>
								
							';
							}

						}

						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-avatar">            
											' . $img . '      
										</div>        
										<div class="user-name">            
											<span class="tb-lead">' . ucwords($name) . '</span>    <br>
										<span class="tb-lead">' . ucwords($ministry) . '</span>                        
										</div>    
									</div>  
								</td>
								<td>
									<span class="small">' . $email . '</span><br>
									<span class="small">' . $phone . '</span>
								</td>
								<td><span class="small">' . $address . '</span><br>
								<span class="small">' . $country . '</span></td>
								<td><span class="small">' . $mins . '</span></td>
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
						<i class="ni ni-home-alt" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Service Center Returned') . '
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

			$data['title'] = translate_phrase('Service Center') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}


	public function administrator($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'church/administrator';
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
			// return redirect()->to(site_url('dashboard'));	
		}
		$data['log_id'] = $log_id;
		$data['role'] = $role;
		$data['role_c'] = $role_c;

		$data['current_language'] = $this->session->get('current_language');
		$table = 'user';
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

		$church_id = $this->session->get('church_id');
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

					if ($_POST) {
						$del_id = $this->request->getPost('d_user_id');
						$code = $this->Crud->read_field('id', $del_id, 'user', 'firstname');
						if ($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Deleted'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by . ' deleted Administrator (' . $code . ')';
							$this->Crud->activity('user', $del_id, $action);
							echo '<script>
								load_admin("","",' . $church_id . ');
								$("#modal").modal("hide");
							</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
						}
						exit;
					}
				}
			} elseif ($param2 == 'admin_send') {
				if ($param3) {
					$admin_id = $param3;
					if ($admin_id) {
						$surname = $this->Crud->read_field('id', $admin_id, 'user', 'surname');
						$firstname = $this->Crud->read_field('id', $admin_id, 'user', 'firstname');
						$role_id = $this->Crud->read_field('id', $admin_id, 'user', 'role_id');
						$roles = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
						$othername = $this->Crud->read_field('id', $admin_id, 'user', 'othername');
						$user_no = $this->Crud->read_field('id', $admin_id, 'user', 'user_no');
						$email = $this->Crud->read_field('id', $admin_id, 'user', 'email');
						$phone = $this->Crud->read_field('id', $admin_id, 'user', 'phone');
						$ministry_id = $this->Crud->read_field('id', $admin_id, 'user', 'ministry_id');
						$church_id = $this->Crud->read_field('id', $admin_id, 'user', 'church_id');
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');

						$name = ucwords($firstname . ' ' . $othername . ' ' . $surname);
						$reset_link = site_url('auth/email_verify?uid=' . $user_no);
						$link = '<p><a href="' . htmlspecialchars($church) . '">Set Your Password</a></p>';
						$body = '
							Dear ' . esc($name) . ', <br><br>

							<p>A ' . esc(ucwords($roles)) . ' account has been created for you on the <strong>' . esc(ucwords($church)) . '</strong> New Digital platform.</p>

							<p><strong>Below are your Account Details:</strong></p>

							Website: <a href="' . site_url() . '" target="_blank">' . site_url() . '</a><br>
							Membership ID: ' . esc($user_no) . '<br>
							Email: ' . esc($email) . '<br>
							Phone: ' . esc($phone) . '<br>
							Reset Link: <a href="' . $reset_link . '" target="_blank">' . $reset_link . '</a><br><br>

							<p>To ensure the security of your account, please set your password by clicking the button below:</p>

							<p style="margin: 20px 0;">
								<a href="' . $reset_link . '" style="background-color: #1E90FF; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px;" target="_blank">Set Password</a>
							</p>

							<p>If the button above doesn\'t work, you can copy and paste the link below into your browser:</p>

							<div style="padding:10px; background:#f5f5f5; border:1px solid #ddd; word-break:break-all; font-family:monospace; font-size:14px;">
								' . $link . '
							</div>

							<p>This link will direct you to a secure page where you can choose your own password.</p>

							<p><strong>Important:</strong> Do not disclose your login credentials to anyone to avoid unauthorized access.</p>

							<p>Welcome aboard, and we look forward to your participation!</p>

							<p>Best regards,<br>
							The Digital Team</p>
						';


						$data['body'] = $body;
						if ($this->request->getMethod() == 'post') {
							$head = 'Welcome to ' . $ministry . ' - Set Your Password';
							$email_status = $this->Crud->send_email($email, $head, $body);
							if ($email_status > 0) {
								echo $this->Crud->msg('success', 'Login Credential Sent to Email Successfully');
								echo '<script>
										load_admin("","",' . $church_id . ');
										$("#modal").modal("hide");
									</script>';
							} else {
								echo $this->Crud->msg('danger', 'Error Sending Email');
							}
							die;
						}

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
								$data['e_surname'] = $e->surname;
								$data['e_firstname'] = $e->firstname;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_activate'] = $e->activate;
								$data['e_title'] = $e->title;
								$data['e_email'] = $e->email;
								$data['e_role_id'] = $e->role_id;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$user_id = $this->request->getPost('user_id');
					$surname = $this->request->getPost('surname');
					$firstname = $this->request->getPost('firstname');
					$phone = $this->request->getPost('phone');
					$email = $this->request->getPost('email');
					$title = $this->request->getPost('title');
					$address = $this->request->getPost('address');
					$activate = $this->request->getPost('activate');
					$password = $this->request->getPost('password');


					$church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
					$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
					if ($church_type == 'region') {
						$urole_id = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');

					}
					if ($church_type == 'zone') {
						$urole_id = $this->Crud->read_field('name', 'Zonal Manager', 'access_role', 'id');

					}
					if ($church_type == 'group') {
						$urole_id = $this->Crud->read_field('name', 'Group Manager', 'access_role', 'id');

					}
					if ($church_type == 'church') {
						$urole_id = $this->Crud->read_field('name', 'Church Leader', 'access_role', 'id');

					}
					if ($church_type == 'center') {
						$urole_id = $this->Crud->read_field('name', 'Center Manager', 'access_role', 'id');

					}

					if (empty($title) || $title == ' ') {
						echo $this->Crud->msg('danger', 'Select Title');
						die;
					}

					$ins_data['surname'] = $surname;
					$ins_data['firstname'] = $firstname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['activate'] = $activate;
					$ins_data['title'] = $title;
					$ins_data['role_id'] = $urole_id;
					if ($password) {
						$ins_data['password'] = md5($password);
					}

					// do create or update
					if ($user_id) {
						$upd_rec = $this->Crud->updates('id', $user_id, $table, $ins_data);
						if ($upd_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Updated'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
							$action = $by . ' updated Administrator (' . $code . ') Record';
							$this->Crud->activity('user', $user_id, $action);
							echo '<script>
									load_admin("","",' . $church_id . ');
									$("#modal").modal("hide");
								</script>';
						} else {
							echo $this->Crud->msg('info', translate_phrase('No Changes'));
						}
					} else {
						// if ($this->Crud->check('email', $email, $table) > 0 || $this->Crud->check('phone', $phone, $table) > 0) {
						// 	echo $this->Crud->msg('warning', ('Email and/or Phone Already Exist'));
						// } else {
							$ins_data['ministry_id'] = $ministry_id;
							$ins_data['church_id'] = $church_id;
							$ins_data['church_type'] = $church_type;
							$ins_data['is_admin'] = 1;
							$ins_data['reg_date'] = date(fdate);

							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Record Created'));
								$this->Crud->updates('id', $ins_rec, 'user', array('user_no' => 'CEAM-00' . $ins_rec));

								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'user', 'firstname');
								$action = $by . ' created Administrator (' . $code . ')';
								$this->Crud->activity('user', $ins_rec, $action);

								echo '<script>
									load_admin("","",' . $church_id . ');
									$("#modal").modal("hide");
								</script>';
							} else {
								echo $this->Crud->msg('danger', translate_phrase('Please try later'));
							}
						// }
					}
					exit;
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


			if (!empty($this->request->getPost('status'))) {
				$status = $this->request->getPost('status');
			} else {
				$status = '';
			}
			$search = $this->request->getPost('search');
			$church_id = $this->request->getPost('id');
			$this->session->set('church_id', $church_id);

			if (empty($ref_status))
				$ref_status = 0;
			$items = '
					
			';
			$a = 1;

			//echo $status;
			$log_id = $this->session->get('td_id');
			if (!$log_id) {
				$item = '<div class="text-center text-muted">' . translate_phrase('Session Timeout! - Please login again') . '</div>';
			} else {
				$role_ids = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');

				$all_rec = $this->Crud->filter_church_admin('', '', $log_id, $status, $search, $church_id);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_church_admin($limit, $offset, $log_id, $status, $search, $church_id);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$fullname = $q->firstname . ' ' . $q->surname;
						$email = $q->email;
						$phone = $q->phone;
						$address = $q->address;
						$img = $this->Crud->image($q->img_id, 'big');
						$activate = $q->activate;
						$u_role = $this->Crud->read_field('id', $q->role_id, 'access_role', 'name');
						$reg_date = date('M d, Y h:ia', strtotime($q->reg_date));

						$referral = '';

						$approved = '';
						if ($activate == 1) {
							$a_color = 'success';
							$approve_text = 'Account Activated';
							$approved = '<span class="text-primary"><i class="ri-check-circle-line"></i></span> ';
						} else {
							$a_color = 'danger';
							$approve_text = 'Account Deactivated';
							$approved = '<span class="text-danger"><i class="ri-check-circle-line"></i></span> ';
						}

						// add manage buttons

						if (!empty($switch_id)) {
							$all_btn = '
								<li><a href="javascript:;"  pageSize="modal-lg" pageTitle="Send Login" id="send_btn"  class="text-success pop" pageName="' . site_url($mod . '/manage/admin_send/' . $id) . '"><em class="icon ni ni-share"></em> <span>Send Login</span></a></li>
								
							';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $fullname . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $fullname . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
								<li><a href="javascript:;" pageSize="modal-lg" pageTitle="Send Login" id="send_btn"  class="text-success pop" pageName="' . site_url($mod . '/manage/admin_send/' . $id) . '"><em class="icon ni ni-share"></em> <span>Send Login</span></a></li>
								
							';

						}




						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-avatar ">
											<img alt="" src="' . site_url($img) . '" height="40px"/>
										</div>
										<div class="user-info">
											<span class="tb-lead"><b>' . ucwords($fullname) . ' </b><span class="dot dot-' . $a_color . ' ms-1"></span></span>
											<br>
											
										</div>
									</div>
								</td>
								<td><span class=" ">' . $email . '</span></td>
								<td><span class=" ">' . $phone . '</span></td>
								<td><span class=" ">' . $u_role . '</span></td>
								<td><span class="tb-amount ">' . $reg_date . ' </span></td>
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
					<tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-users" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Administrator Account Returned') . '
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
			return view('church/admin_form', $data);
		}

	}

	public function pastor($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'church/pastor';
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
			// return redirect()->to(site_url('dashboard'));	
		}
		$data['log_id'] = $log_id;
		$data['role'] = $role;
		$data['role_c'] = $role_c;

		$data['current_language'] = $this->session->get('current_language');
		$table = 'user';
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

		$church_id = $this->session->get('church_id');
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

					if ($_POST) {
						$del_id = $this->request->getPost('d_user_id');
						$code = $this->Crud->read_field('id', $del_id, 'user', 'firstname');
						if ($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Deleted'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by . ' deleted Pastor (' . $code . ')';
							$this->Crud->activity('user', $del_id, $action);
							echo '<script>
								load_pastor("","",' . $church_id . ');
								$("#modal").modal("hide");
							</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
						}
						exit;
					}
				}
			} elseif ($param2 == 'admin_send') {
				if ($param3) {
					$admin_id = $param3;
					if ($admin_id) {
						$surname = $this->Crud->read_field('id', $admin_id, 'user', 'surname');
						$firstname = $this->Crud->read_field('id', $admin_id, 'user', 'firstname');
						$role_id = $this->Crud->read_field('id', $admin_id, 'user', 'role_id');
						$roles = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
						$othername = $this->Crud->read_field('id', $admin_id, 'user', 'othername');
						$user_no = $this->Crud->read_field('id', $admin_id, 'user', 'user_no');
						$email = $this->Crud->read_field('id', $admin_id, 'user', 'email');
						$phone = $this->Crud->read_field('id', $admin_id, 'user', 'phone');
						$ministry_id = $this->Crud->read_field('id', $admin_id, 'user', 'ministry_id');
						$church_id = $this->Crud->read_field('id', $admin_id, 'user', 'church_id');
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');

						$name = ucwords($firstname . ' ' . $othername . ' ' . $surname);
						$reset_link = site_url('auth/email_verify?uid=' . $user_no);
						$link = '<p><a href="' . htmlspecialchars($reset_link) . '">Set Your Password</a></p>';
						$body = '
							Dear ' . $name . ', <br><br>
								<p>A ' . ucwords($roles) . ' account has been created for you on the ' . htmlspecialchars(ucwords($ministry)) . ' within the ' . htmlspecialchars(app_name) . ' platform.</p>
    							Below are your Account Details:<br><br>

								Website: ' . site_url() . '
								Membership ID: ' . $user_no . '<br>
								Email: ' . $email . '<br>
								Phone: ' . $phone . '<br>
								
								<p>To ensure the security of your account, please set your password by clicking the link below:</p>
    

								' . $link . '

								<p>This link will direct you to a secure page where you can choose your own password. If you encounter any issues or have questions, please feel free to contact our support team.</p>
								<p><strong>Important:</strong> Do not disclose your login credentials to anyone to avoid unauthorized access.</p>
								<p>Welcome aboard, and we look forward to your participation!</p>
								<p>Best regards,<br>
								
						';
						if ($this->request->getMethod() == 'post') {
							$head = 'Welcome to ' . $ministry . ' - Set Your Password';
							$email_status = $this->Crud->send_email($email, $head, $body);
							if ($email_status > 0) {
								echo $this->Crud->msg('success', 'Login Credential Sent to Email Successfully');
								echo '<script>
										load_pastor("","",' . $church_id . ');
										$("#modal").modal("hide");
									</script>';
							} else {
								echo $this->Crud->msg('danger', 'Error Sending Email');
							}
							die;
						}

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
								$data['e_surname'] = $e->surname;
								$data['e_firstname'] = $e->firstname;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_activate'] = $e->activate;
								$data['e_title'] = $e->title;
								$data['e_email'] = $e->email;
								$data['e_role_id'] = $e->role_id;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$user_id = $this->request->getPost('user_id');
					$surname = $this->request->getPost('surname');
					$firstname = $this->request->getPost('firstname');
					$phone = $this->request->getPost('phone');
					$email = $this->request->getPost('email');
					$title = $this->request->getPost('title');
					$address = $this->request->getPost('address');
					$activate = $this->request->getPost('activate');
					$urole_id = $this->request->getPost('role_id');
					$password = $this->request->getPost('password');


					$church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
					$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
					$member_role = $this->Crud->read_field('name', 'Pastor', 'access_role', 'id');
					$urole = $this->Crud->read_field('id', $urole_id, 'access_role', 'name');
					if ($urole == 'Pastor-in-Charge') {
						$rolesa = $this->Crud->read2('role_id', $urole_id, 'church_id', $church_id, 'user');
						if (!empty($rolesa)) {
							foreach ($rolesa as $r) {
								$this->Crud->updates('id', $r->id, 'user', array('role_id' => $member_role));
							}
						}

					}


					if (empty($title) || $title == ' ') {
						echo $this->Crud->msg('danger', 'Select Title');
						die;
					}

					$ins_data['surname'] = $surname;
					$ins_data['firstname'] = $firstname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['activate'] = $activate;
					$ins_data['title'] = $title;
					$ins_data['address'] = $address;
					$ins_data['role_id'] = $urole_id;
					if ($password) {
						$ins_data['password'] = md5($password);
					}

					// do create or update
					if ($user_id) {
						$upd_rec = $this->Crud->updates('id', $user_id, $table, $ins_data);
						if ($upd_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('Pastor Record Updated'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
							$action = $by . ' updated Pastor (' . $code . ') Record';
							$this->Crud->activity('user', $user_id, $action);
							echo '<script>
									load_pastor("","",' . $church_id . ');
									$("#modal").modal("hide");
								</script>';
						} else {
							echo $this->Crud->msg('info', translate_phrase('No Changes'));
						}
					} else {
						if ($this->Crud->check('email', $email, $table) > 0 || $this->Crud->check('phone', $phone, $table) > 0) {
							echo $this->Crud->msg('warning', ('Email and/or Phone Already Exist'));
						} else {
							$ins_data['ministry_id'] = $ministry_id;
							$ins_data['church_id'] = $church_id;
							$ins_data['is_pastor'] = 1;
							$ins_data['church_type'] = $church_type;
							$ins_data['reg_date'] = date(fdate);

							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Pastor Record Created'));
								$this->Crud->updates('id', $ins_rec, 'user', array('user_no' => 'CEAM-00' . $ins_rec));

								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'user', 'firstname');
								$action = $by . ' created Pastor (' . $code . ')';
								$this->Crud->activity('user', $ins_rec, $action);

								echo '<script>
									load_pastor("","",' . $church_id . ');
									$("#modal").modal("hide");
								</script>';
							} else {
								echo $this->Crud->msg('danger', translate_phrase('Please try later'));
							}
						}
					}
					exit;
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


			if (!empty($this->request->getPost('status'))) {
				$status = $this->request->getPost('status');
			} else {
				$status = '';
			}
			$search = $this->request->getPost('search');
			$church_id = $this->request->getPost('id');
			$this->session->set('church_id', $church_id);

			if (empty($ref_status))
				$ref_status = 0;
			$items = '
					
			';
			$a = 1;

			//echo $status;
			$log_id = $this->session->get('td_id');
			if (!$log_id) {
				$item = '<div class="text-center text-muted">' . translate_phrase('Session Timeout! - Please login again') . '</div>';
			} else {
				$role_ids = $this->Crud->read_field('name', 'Pastor', 'access_role', 'id');

				$all_rec = $this->Crud->filter_church_pastor('', '', $log_id, $status, $search, $church_id);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_church_pastor($limit, $offset, $log_id, $status, $search, $church_id);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$fullname = $q->firstname . ' ' . $q->surname;
						$email = $q->email;
						$phone = $q->phone;
						$address = $q->address;
						$img = $this->Crud->image($q->img_id, 'big');
						$activate = $q->activate;
						$u_role = $this->Crud->read_field('id', $q->role_id, 'access_role', 'name');
						$reg_date = date('M d, Y h:ia', strtotime($q->reg_date));

						$referral = '';

						$approved = '';
						if ($activate == 1) {
							$a_color = 'success';
							$approve_text = 'Account Activated';
							$approved = '<span class="text-primary"><i class="ri-check-circle-line"></i></span> ';
						} else {
							$a_color = 'danger';
							$approve_text = 'Account Deactivated';
							$approved = '<span class="text-danger"><i class="ri-check-circle-line"></i></span> ';
						}

						// add manage buttons
						if (!empty($switch_id)) {
							$all_btn = '
								<li><a href="javascript:;" pageTitle="Send Login" id="send_btn"  class="text-success pop" pageName="' . site_url($mod . '/manage/admin_send/' . $id) . '"><em class="icon ni ni-share"></em> <span>Send Login</span></a></li>
								
							';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $fullname . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $fullname . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
								<li><a href="javascript:;" pageTitle="Send Login" id="send_btn"  class="text-success pop" pageName="' . site_url($mod . '/manage/admin_send/' . $id) . '"><em class="icon ni ni-share"></em> <span>Send Login</span></a></li>
								
							';

						}



						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-avatar ">
											<img alt="" src="' . site_url($img) . '" height="40px"/>
										</div>
										<div class="user-info">
											<span class="tb-lead"><b>' . ucwords($fullname) . '</b> <span class="dot dot-' . $a_color . ' ms-1"></span></span>
										</div>
									</div>
								</td>
								<td><span class=" ">' . $email . '</span></td>
								<td><span class=" ">' . $phone . '</span></td>
								<td><span class=" ">' . $u_role . '</span></td>
								<td><span class=" ">' . ucwords($address) . '</span></td>
								<td><span class="tb-amount ">' . $reg_date . ' </span></td>
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
					<tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-users" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Pastor Account Returned') . '
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
			return view('church/pastor_form', $data);
		}

	}

	
	public function form($param1='', $param2='', $param3='', $param4='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

		$mod = 'church/form';

		$log_id = $this->session->get('td_id');
		$switch_id = $this->session->get('switch_church_id');
        
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        if(!empty($switch_id)){
            $church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
            if($church_type == 'region'){
                $role_id = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');
            }
            if($church_type == 'zone'){
                $role_id = $this->Crud->read_field('name', 'Zonal Manager', 'access_role', 'id');
            }
            if($church_type == 'group'){
                $role_id = $this->Crud->read_field('name', 'Group Manager', 'access_role', 'id');
            }
            if($church_type == 'church'){
                $role_id = $this->Crud->read_field('name', 'Church Leader', 'access_role', 'id');
            }
        }
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		$role_c = $this->Crud->module($role_id, $mod, 'create');
		$role_r = $this->Crud->module($role_id, $mod, 'read');
		$role_u = $this->Crud->module($role_id, $mod, 'update');
		$role_d = $this->Crud->module($role_id, $mod, 'delete');
		if($role_r == 0){
			return redirect()->to(site_url('dashboard'));	
		}
		$data['log_id'] = $log_id;
		$data['role'] = $role;
		$data['role_c'] = $role_c;
		
		$table = 'formfields';
		
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= '/'.$param3.'/';}
		if($param4){$form_link .= $param4;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['param4'] = $param4;
		$data['form_link'] = rtrim($form_link, '/');
		$data['current_language'] = $this->session->get('current_language');
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					//echo var_dump($edit);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$del_id =  $this->request->getVar('d_id');
						
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'form', 'name');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							///// store activities
							$action = $by.' deleted Form Field ('.$code.')';
							$this->Crud->activity('form', $del_id, $action);

							echo $this->Crud->msg('success', 'Form Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						die;	
					}
				}
			} else {
				// prepare for edit
				if($param2 == 'edit') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_field_name'] = $e->field_name;
								$data['e_field_type'] = $e->field_type;
								$data['e_field_options'] = $e->field_options;
								$data['e_is_required'] = $e->is_required;
							}
						}
					}
				}

				
				if($this->request->getMethod() == 'post'){
					$e_id =  $this->request->getVar('e_id');
					$field_name =  $this->request->getVar('field_name');
					$field_type =  $this->request->getVar('field_type');
					$field_options =  $this->request->getVar('field_options');
					$is_required =  $this->request->getVar('is_required');
					

					$church_id = $this->session->get('form_church_id');
					$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');

					$ins_data['field_name'] = $field_name;
					$ins_data['field_type'] = $field_type;
					$ins_data['field_options'] = rtrim($field_options, ',');
					$ins_data['is_required'] = $is_required;
					$ins_data['church_id'] = $church_id;
					$ins_data['ministry_id'] = $ministry_id;
					
					// do create or update
					if($e_id) {
						$upd_rec = $this->Crud->updates('id', $e_id, $table, $ins_data);
						if($upd_rec > 0) {
							
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $e_id, $table, 'field_name');
							$action = $by.' updated Form Field ('.$code.')';
							$this->Crud->activity('form', $e_id, $action);

							echo $this->Crud->msg('success', 'Form Updated');
							echo '<script>
								load("","");
								$("#modal").modal("hide");
							</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
						
					} else{
						
						$ins_data['created_at'] = date(fdate);
						
						if($this->Crud->check2('field_name', $field_name, 'church_id', $church_id, $table) > 0) {
							echo $this->Crud->msg('warning', ('Form Field Already Exist'));
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Form Created'));
								
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $e_id, $table, 'field_name');
								$action = $by.' created Form Field ('.$code.')';
								$this->Crud->activity('form', $ins_rec, $action);

								echo '<script>
								load("","");
								$("#modal").modal("hide");
							</script>';
							} else {
								echo $this->Crud->msg('danger', translate_phrase('Please try later'));	
							}	
						}
					}
					die;	
				}
			}
		}

		// manage record
		if($param1 == 'extension') {
			$table = 'form_extension';
			// prepare for delete
			if($param3 == 'delete') {
				if($param4) {
					$edit = $this->Crud->read_single('id', $param4, $table);
					//echo var_dump($edit);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$del_id =  $this->request->getVar('d_id');
						$form_id =  $this->request->getVar('form_id');
						
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'form', 'name');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							///// store activities
							$action = $by.' deleted Form ('.$code.')';
							$this->Crud->activity('form', $del_id, $action);

							echo $this->Crud->msg('success', 'Form Deleted');
							echo '<script>
								load_extension("","",'.$form_id.');
								$("#modal").modal("hide");
							</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						die;	
					}
				}
			} else {
				// prepare for edit
				if($param3 == 'edit') {
					if($param4) {
						$edit = $this->Crud->read_single('id', $param4, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_fields'] = json_decode($e->fields);
								
							}
						}
					}
				}

				// prepare for view
				if($param3 == 'view') {
					if($param4) {
						$edit = $this->Crud->read_single('id', $param4, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_fields'] = json_decode($e->fields);
								$data['e_reg_date'] = $e->reg_date;
							}
						}
					}
				}
				
				if($this->request->getMethod() == 'post'){
					$e_id =  $this->request->getVar('e_id');
					$form_id =  $this->request->getVar('form_id');
					$label =  $this->request->getVar('label');
					$type =  $this->request->getVar('type');
					$options =  $this->request->getVar('options');
					
					$fields = [];
					if (!empty($label)) {
						for ($i = 0; $i < count($label); $i++) {
							$field = [
								'label' => $label[$i],
								'type' => $type[$i],
							];
							if (in_array($type[$i], ['single_choice', 'multiple_choice'])) {
								$option = $options[$i + 1] ?? []; 
								$field['options'] = $option;
							}
							$fields[] = $field;
						}
					}
					
					if(empty($fields)){
						echo $this->Crud->msg('warning', 'Enter the Field of the Form you want to Create');
						die;
					}
					
					$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

					$ins_data['form_id'] = $form_id;
					$ins_data['user_id'] = $log_id;
					$ins_data['fields'] = json_encode($fields);
					$ins_data['church_id'] = $church_id;
					
					// do create or update
					if($e_id) {
						$upd_rec = $this->Crud->updates('id', $e_id, 'form_extension', $ins_data);
						if($upd_rec > 0) {
							
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $e_id, 'form', 'name');
							$action = $by.' updated Form Extension for Form ('.$code.')';
							$this->Crud->activity('form', $e_id, $action);

							echo $this->Crud->msg('success', 'Form Updated');
							echo '<script>
									load_extension("","",'.$form_id.');
									$("#modal").modal("hide");
								</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
						
					} else{
						
						$ins_data['reg_date'] = date(fdate);
						
						if($this->Crud->check2('church_id', $church_id, 'form_id', $form_id, 'form_extension') > 0) {
							echo $this->Crud->msg('warning', ('A Form Extension has been created for this Form Already'));
						} else {
							$ins_rec = $this->Crud->create('form_extension', $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Form Extension Created'));
								
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'form', 'name');
								$action = $by.' created a Form Extension for Form ('.$code.')';
								$this->Crud->activity('form', $ins_rec, $action);

								echo '<script>
									load_extension("","",'.$form_id.');
									$("#modal").modal("hide");
								</script>';
							} else {
								echo $this->Crud->msg('danger', translate_phrase('Please try later'));	
							}	
						}
					}
					die;	
				}
			}
		}

		if($param1 == 'get_church'){
			$ministry_id = $this->request->getPost('ministry_id');
			$level = $this->request->getPost('level');
			
			if($ministry_id && $level){
				$churchz = $this->Crud->read2_order('ministry_id', $ministry_id, 'type', $level, 'church', 'name', 'asc');
				if(!empty($churchz)){
					$church = '<option value="all">All '.ucwords($level).' Church</option>';
					foreach($churchz as $ch){
						$church .= '<option value="'.$ch->id.'">'.ucwords($ch->name).'</option>';
					}
				} else {
					$church = '<option value="">No Record Found</option>';
				}
			} else{
				$church = '<option value="">No Record Found</option>';
			}

			$item['churches'] = $church;

			echo json_encode($item);
			die;
		}



		// record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';

			if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$church_id = $this->request->getPost('church_id');
			$this->session->set('form_church_id', $church_id);
			//echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$query = $this->Crud->read_single_order('church_id', $church_id, 'formfields', 'display_order', 'asc');

				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$reg_date =  date('M d, Y h:i A', strtotime($q->created_at));
						$field_name = $q->field_name;
						$field_type = $q->field_type;
						$field_options = $q->field_options;
						$church_id = $q->church_id;
						$is_required = $q->is_required;
						$display_order = $q->display_order;
						$ministry_id = $q->ministry_id;
						
						// add manage buttons
						if($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
									
								';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $field_name . '" pageSize="modal-md" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $field_name . '" pageSize="modal-md" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
							';

							}
							
						}

						$required = '<span class="text-success">Required</span>';
						if($is_required == 0){
							$required = '<span class="text-danger">Not Required</span>';

						}

						$opt = 'No Options';
						if(!empty($field_options)){
							$opt = $field_options;
						}
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
						
						$item .= '
							<tr>
								<td>          
									<span class="tb-lead">' . ucwords($field_name) . '</span> 
								</td>
								<td>
									<span class="tb-lead text-dark">'.ucwords($field_type).' </span>
								</td>
								<td>'.($opt).'</td>
								<td>'.($required).'</td>
								<td>
									<div class="drodown">
										<a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
										<div class="dropdown-menu dropdown-menu-end">
											<ul class="link-list-opt no-bdr">
												' . $all_btn . '
											</ul>
										</div>
									</div>
								</td>
							</tr>
						';

						
					}
				}
			}
			
			if(empty($item)) {
				$resp['item'] = '
					<Tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/>
						<i class="ni ni-cc-alt2" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Form Returned').'
					</div></td></tr>
				';
			} else {
				$resp['item'] = $item;
				if($offset >= 25){
					$resp['item'] = $item;
				}
				
			}



			echo json_encode($resp);
			die;
		}
	

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		}  else { // view for main page
			
			$data['title'] = 'First Timer Form - '.app_name;
			$data['page_active'] = $mod;

			return view($mod, $data);
		}
	}
	
	public function switch_church()
	{
		$log_id = $this->session->get('td_id');


		$church_id = $this->request->getPost('church_id');
		if (empty($church_id)) {
			echo $this->Crud->msg('danger', 'Invalid Church');
		} else {
			echo $this->Crud->msg('success', 'Switching Church.. Do not Reload');
			///// store activities
			$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
			$code = $this->Crud->read_field('id', $church_id, 'church', 'name');
			$action = $by . ' Logged into Church (' . $code . ') Account';
			$this->Crud->activity('user', $church_id, $action);
			$this->session->set('switch_church_id', $church_id);
			echo '<script>window.location.replace("' . site_url('dashboard') . '");</script>';
		}
	}

	public function back_church()
	{
		$log_id = $this->session->get('td_id');
		$church_id = $this->session->get('switch_church_id');
		if (!empty($church_id)) {
			$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
			$code = $this->Crud->read_field('id', $church_id, 'church', 'name');
			$action = $by . ' Logged out of Church (' . $code . ') Account';
			$this->Crud->activity('user', $church_id, $action);

			
		}
		$this->session->set('switch_church_id', '');
		echo '<script>window.location.replace("' . site_url('dashboard') . '");</script>';
	}

	public function get_region()
	{
		$ministry_id = $this->request->getPost('ministry_id');
		if (empty($ministry_id)) {
			echo json_encode(array()); // return an empty array if ministry_id is empty
			return;
		} else {
			$regions = $this->Crud->read2_order('type', 'region', 'ministry_id', $ministry_id, 'church', 'name', 'asc');
			$data = array();
			foreach ($regions as $region) {
				$data[] = array('id' => $region->id, 'name' => $region->name);
			}
			echo json_encode($data);
		}
	}

	public function get_zone()
	{
		$regional_id = $this->request->getPost('regional_id');
		if (empty($regional_id)) {
			echo json_encode(array()); // return an empty array if ministry_id is empty
			return;
		} else {
			$regions = $this->Crud->read2_order('type', 'zone', 'regional_id', $regional_id, 'church', 'name', 'asc');
			$data = array();
			foreach ($regions as $region) {
				$data[] = array('id' => $region->id, 'name' => $region->name);
			}
			echo json_encode($data);
		}
	}


	public function get_group()
	{
		$zonal_id = $this->request->getPost('zonal_id');
		if (empty($zonal_id)) {
			echo json_encode(array()); // return an empty array if ministry_id is empty
			return;
		} else {
			$regions = $this->Crud->read2_order('type', 'group', 'zonal_id', $zonal_id, 'church', 'name', 'asc');
			$data = array();
			foreach ($regions as $region) {
				$data[] = array('id' => $region->id, 'name' => $region->name);
			}
			echo json_encode($data);
		}
	}

	public function get_church()
	{
		$zonal_id = $this->request->getPost('zonal_id');
		if (empty($zonal_id)) {
			echo json_encode(array()); // return an empty array if ministry_id is empty
			return;
		} else {
			$regions = $this->Crud->read2_order('type', 'church', 'zonal_id', $zonal_id, 'church', 'name', 'asc');
			$data = array();
			foreach ($regions as $region) {
				$data[] = array('id' => $region->id, 'name' => $region->name);
			}
			echo json_encode($data);
		}
	}

	public function get_state($country)
	{
		if (empty($country)) {
			echo '<label for="activate">' . translate_phrase('State') . '</label>
			<input type="text" class="form-control" name="state" id="state" readonly placeholder="Select Country First">';
		} else {
			$state = $this->Crud->read_single_order('country_id', $country, 'state', 'name', 'asc');
			echo '<label for="activate">' . translate_phrase('State') . '</label>
				<select class="form-select js-select2" data-search="on" id="state" name="state" onchange="lgaa();">
					<option value="">' . translate_phrase('Select') . '</option>
			';
			foreach ($state as $qr) {
				$hid = '';
				$sel = '';
				echo '<option value="' . $qr->id . '" ' . $sel . '>' . $qr->name . '</option>';
			}
			echo '</select>
			<script> $(".js-select2").select2();</script>';
		}
	}

	public function get_lga($state)
	{
		if (empty($state)) {
			echo '<label for="activate">' . translate_phrase('Local Goverment Area') . '</label>
			<input type="text" class="form-control" name="lga" id="lga" readonly placeholder="' . translate_phrase('Select State First') . '">';
		} else {
			$state = $this->Crud->read_single_order('state_id', $state, 'city', 'name', 'asc');
			echo '<label for="activate">' . translate_phrase('Local Goverment Area') . '</label>
				<select class="form-select js-select2" data-search="on" id="lga" name="lga" onchange="branc();">
					<option value="">' . translate_phrase('Select') . '</option>
			';
			foreach ($state as $qr) {
				$hid = '';
				$sel = '';
				echo '<option value="' . $qr->id . '" ' . $sel . '>' . $qr->name . '</option>';
			}
			echo '</select>
			<script> $(".js-select2").select2();</script>';
		}
	}

	public function qrcode($data = '')
	{

		/* Data */
		$hex_data = bin2hex($data);
		$save_name = $hex_data . '.png';

		/* QR Code File Directory Initialize */
		$dir = 'assets/images/qr/profile/';
		if (!file_exists($dir)) {
			mkdir($dir, 0775, true);
		}

		/* QR Configuration  */
		$config['cacheable'] = true;
		$config['imagedir'] = $dir;
		$config['quality'] = true;
		$config['size'] = '1024';
		$config['black'] = [255, 255, 255];
		$config['white'] = [255, 255, 255];
		$this->ciqrcode->initialize($config);

		/* QR Data  */
		$params['data'] = $data;
		$params['level'] = 'L';
		$params['size'] = 10;
		$params['savename'] = FCPATH . $config['imagedir'] . $save_name;

		$this->ciqrcode->generate($params);

		/* Return Data */
		return [
			'content' => $data,
			'file' => $dir . $save_name,
		];
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
											<span class="tb- text"><em class="icon ni ni-curve-down-right"></em>' . ucwords($church) . '</span>                   
										</div>    
									</div>  
								</td>
								<td>
									<span class="small text">' . ucwords($type) . '</span>
								</td>
								<td><span class="small text">' . ucwords($description) . '</span></td>
								<td><span class="small text">' . $update_date . '</span></td>
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


	public function service($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'church/service';
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


		$table = 'service_order';
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
						$action = $by . ' deleted Order of Service ';

						if ($this->Crud->deletes('id', $del_id, $table) > 0) {

							$this->Crud->activity('service_order', $del_id, $action);
							echo $this->Crud->msg('success', 'Order of Service Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;
					}
				}
			} elseif ($param2 == 'send_email') {
				if ($param3) {
					$order_id = $param3;
					$template_id = $this->Crud->read_field('id', $order_id, 'service_order', 'template_id');
					$service_id = $this->Crud->read_field('id', $order_id, 'service_order', 'service_id');
					$start_time = $this->Crud->read_field('id', $order_id, 'service_order', 'start_time');
					$notes = $this->Crud->read_field('id', $order_id, 'service_order', 'notes');
					$anchorsa = $this->Crud->read_field('id', $order_id, 'service_order', 'anchors');
					$durationsa = $this->Crud->read_field('id', $order_id, 'service_order', 'durations');
					$service_date = $this->Crud->read_field('id', $service_id, 'service_report', 'date');
					$church_id = $this->Crud->read_field('id', $service_id, 'service_report', 'church_id');

					$sections = json_decode($this->Crud->read_field('id', $template_id, 'service_template', 'sections'), true);
					usort($sections, function ($a, $b) {
						return $a['priority'] - $b['priority'];
					});
					$anchors = json_decode($anchorsa);
					$durations = json_decode($durationsa);
					$total_duration = 0;
					if (!empty($durations)) {
						foreach ($durations as $key => $value) {
							if ($value->section) {
								$total_duration += (int) $value->duration;

							}
						}
					}
					$totals = $this->Crud->convertMinutesToTime($total_duration);

					$body = '
						<div class="row" id="content">
							<div class="col-sm-12 mb-3">
								<h5 class="text-center text-dark mb-2">' . ucwords($this->Crud->read_field('id', $church_id, 'church', 'name') . ' Service Program - ') . strtoupper(date('l jS M Y', strtotime($service_date))) . ' {' . $totals . '}' . '</h5>
								
								<div class="my-2">
									<div class="col-12 table-responsive">
										<table class="table table-borderless table-hover">
											<thead>
												<tr>
													<th>S/N</th>
													<th>ACTIVITY</th>
													<th>TIME (' . $totals . ')</th>
													<th>COORDINATOR</th>
												</tr>
											</thead>
											<tbody>';
					if (!empty($sections)) {
						$current_time = strtotime($start_time);

						foreach ($sections as $sect) {
							$dur = 0;
							$coord = '';

							// Search for matching section in anchors (coordinator)
							if (!empty($anchors)) {
								foreach ($anchors as $key => $value) {
									if ($value->section === $sect['section']) {
										$coord = $value->anchor;
										break;
									}
								}
							}

							// Search for matching section in durations
							if (!empty($durations)) {
								foreach ($durations as $key => $value) {
									if ($value->section === $sect['section']) {
										$dur = $value->duration;
										break;
									}
								}
							}

							// Convert minutes to seconds and add to current time
							$duration_in_seconds = $dur * 60;
							// Calculate the end time by adding duration to current start time
							$end_time = $current_time + $duration_in_seconds;

							// Format the start and end times
							$formatted_start_time = date('h:i A', $current_time);
							$formatted_end_time = date('h:i A', $end_time);

							// Output the row
							$body .= '
															<tr>
																<td>' . ucwords($sect['priority']) . '</td>
																<td>' . ucwords($sect['section']) . '</td>
																<td>' . $formatted_start_time . ' - ' . $formatted_end_time . ' (' . $this->Crud->convertMinutesToTime($dur) . ')</td>
																<td>' . ucwords($coord) . '</td>
															</tr>
														';

							$current_time = $end_time;
						}
					} else {

						$body .= '
														<tr><td colspan="5">NO ACTIVITY</td></tr>
													';
					}


					$body .= '</tbody>
										</table>
									</div>
								

									<p>' . ucwords(($notes)) . '</p>
								</div>
							</div>
						</div>
					';

					if ($this->request->getMethod() == 'post') {
						$email = $this->request->getPost('emails');

						if (empty($email)) {
							echo $this->Crud->msg('warning', 'Enter Emails');
							die;
						}
						$sent = 0;
						$failed = 0;
						foreach ($email as $emails) {
							$head = 'Order of Program';
							$email_status = $this->Crud->send_email($emails, $head, $body);
							if ($email_status > 0) {
								$sent++;
							} else {
								$failed++;
							}

						}
						if ($sent == 0) {
							echo $this->Crud->msg('danger', 'Order of Service Email Failed to Send. Try Again Later');
						} else {
							echo $this->Crud->msg('info', 'Order of Service Sent to ' . $sent . ' Emails Successfully.' . $failed . ' Failed');
							echo '<script>location.reload(false);</script>';
						}

						die;
					}
				}
			} else {
				// prepare for edit
				if ($param2 == 'view' || $param2 == 'download') {
					if ($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if (!empty($edit)) {
							foreach ($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_template_id'] = $e->template_id;
								$data['e_service_id'] = $e->service_id;
								$data['e_start_time'] = $e->start_time;
								$data['e_durations'] = $e->durations;
								$data['e_anchors'] = $e->anchors;
								$data['e_notes'] = $e->notes;
								$data['e_church_id'] = $e->church_id;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_reg_date'] = $e->reg_date;
							}
						}
					}
				}

				if ($param2 == 'edit') {
					if ($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if (!empty($edit)) {
							foreach ($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_template_id'] = $e->template_id;
								$data['e_service_id'] = $e->service_id;
								$data['e_service_type'] = $this->Crud->read_field('id', $e->service_id, 'service_report', 'type');
								$data['e_service_date'] = $this->Crud->read_field('id', $e->service_id, 'service_report', 'date');
								$data['e_start_time'] = $e->start_time;
								$data['e_durations'] = $e->durations;
								$data['e_anchors'] = $e->anchors;
								$data['e_notes'] = $e->notes;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$edit_id = $this->request->getVar('edit_id');
					$template_id = $this->request->getVar('template_id');
					$service_id = $this->request->getVar('service_id');
					$service_type = $this->request->getVar('service_type');
					$service_dates = $this->request->getVar('service_dates');
					$coordinator = $this->request->getVar('coordinator');
					$durations = $this->request->getVar('duration');
					$start_time = $this->request->getVar('start_time');
					$notes = $this->request->getVar('notes');
					$section = $this->request->getVar('section_name');
					$ministry_id = $this->request->getVar('ministry_id');
					$church_id = $this->request->getVar('church_id');
					
					$dates = date('y-m-d', strtotime($service_dates));

					$anchors = [];
					$duration = [];

					if (!empty($durations) && !empty($coordinator)) {
						if (count($durations) === count($coordinator)) {  // Ensure both arrays have the same length
							for ($i = 0; $i < count($durations); $i++) {
								// Trim to remove any extra spaces and validate each section
								$current_durations = trim($durations[$i]);
								$current_anchors = trim($coordinator[$i]);

								// Validate that the section is not empty and the priority is a number between 1 and 20
								if (!empty($current_anchors) && !empty($current_durations) && is_numeric($current_durations) && $current_durations >= 1 && $current_durations <= 200) {
									$dur = [];
									$dur['section'] = trim($section[$i]);
									$dur['duration'] = $current_durations;
									$duration[] = $dur;

									$coord = [];
									$coord['section'] = trim($section[$i]);
									$coord['anchor'] = $current_anchors;
									$anchors[] = $coord;
								} else {
									// Return a specific error message for each failed condition
									if (empty($current_anchors)) {
										echo $this->Crud->msg('danger', "Coordinator at index $i cannot be empty.");
									} elseif (empty($current_durations) || !is_numeric($current_durations)) {
										echo $this->Crud->msg('danger', "Duration at index $i must be a valid number.");
									} elseif ($current_durations < 1 || $current_durations > 200) {
										echo $this->Crud->msg('danger', "Duration at index $i must be between 1 and 200.");
									}
									die;
								}
							}
						} else {
							echo $this->Crud->msg('danger', 'Mismatch between the number of Duration and Coordinator.');
							die;
						}
					} else {
						echo $this->Crud->msg('danger', 'Please enter both Duration and Coordinator.');
						die;
					}

					// print_r($duration);
					// die;

					if (empty($start_time)) {
						echo $this->Crud->msg('danger', 'Please enter time that the Service Starts.');
						die;
					}


					$ins_data['template_id'] = $template_id;
					$ins_data['durations'] = json_encode($duration);
					$ins_data['anchors'] = json_encode($anchors);
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['notes'] = $notes;
					$ins_data['start_time'] = date('h:i:s', strtotime($start_time));

					$ser_data['type'] = $service_type;
					$ser_data['date'] = $dates;
					$ser_data['church_id'] = $church_id;
					$ser_data['ministry_id'] = $ministry_id;
					
					$ser_update = 0;
					if(!empty($service_id)){
						$ser_update = $this->Crud->updates('id', $service_id, 'service_report', $ser_data);
					} else{
						if($this->Crud->check2('type', $service_type, 'date', $dates, 'service_report') == 0) {
							$service_id = $this->Crud->create('service_report', $ser_data);
						} else {
							$service_id = $this->Crud->read_field2('type', $service_type, 'date', $dates, 'service_report', 'id');
							$ser_update = $this->Crud->updates('id', $service_id, 'service_report', $ser_data);
						}
					}
					$ins_data['service_id'] = $service_id;
							
					// do create or update
					if ($edit_id) {

						$upd_rec = $this->Crud->updates('id', $edit_id, $table, $ins_data);
						if ($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by . ' updated Order of Program  Record';
							$this->Crud->activity('service_order', $edit_id, $action);

							echo $this->Crud->msg('success', 'Order of Service Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							if($ser_update > 0){
								echo $this->Crud->msg('success', 'Service Report Updated');
								echo '<script>location.reload(false);</script>';
							} else{
								echo $this->Crud->msg('danger', 'Failed to update Order of Service');
							}
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {

						if ($this->Crud->check3('service_id', $service_id, 'template_id', $template_id, 'church_id', $church_id, $table) > 0) {
							echo $this->Crud->msg('warning', 'Order of Service Already Exist Already Exist');
						} else {

							//Frist Create the Service First					
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$action = $by . ' created Order of Service for a Program';
								$this->Crud->activity('service_order', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Order of Service Created');
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

		if ($param1 == 'get_sections_by_template') {
			// Get template ID from the request
			$template_id = $this->request->getPost('template_id');
			$edit_id = $this->request->getPost('edit_id');
			// echo $edit_id;
			if ($template_id) {
				// Fetch sections for the template from the database
				$sections = json_decode($this->Crud->read_field('id', $template_id, 'service_template', 'sections'));

				// Prepare the response (e.g., name, priority, duration, coordinator)
				$anchors = [];
				$durations = [];
				if (!empty($edit_id)) {
					$anchors = json_decode($this->Crud->read_field('id', $edit_id, 'service_order', 'anchors'));
					$durations = json_decode($this->Crud->read_field('id', $edit_id, 'service_order', 'durations'));
				}
				$response = [];

				foreach ($sections as $section) {
					$duration = '';
					$anchor = '';

					// Search for matching section in anchors
					if (!empty($anchors)) {
						// Match anchor where the section name aligns
						foreach ($anchors as $key => $value) {
							if ($value->section === $section->section) {
								$anchor = $value->anchor;
								break;
							}
						}
					}

					// Search for matching section in durations
					if (!empty($durations)) {
						// print_r($durations);
						foreach ($durations as $key => $value) {
							if ($value->section === $section->section) {
								$duration = $value->duration;
								break;
							}
						}
					}

					$response[] = [
						'name' => $section->section,
						'priority' => $section->priority,
						'duration' => $duration,
						'anchor' => $anchor,


					];
				}
				// print_r($response);
				// Sort the response by priority using usort
				usort($response, function ($a, $b) {
					return $a['priority'] <=> $b['priority']; // Ascending order
				});
				// Return the response as JSON
				echo json_encode($response);
			} else {
				echo json_encode([]);
			}

			die;
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

				$all_rec = $this->Crud->filter_service_order('', '', $log_id, $search, $switch_id);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_service_order($limit, $offset, $log_id, $search, $switch_id);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$service_id = $q->service_id;
						$church_id = $q->church_id;
						$template_id = $q->template_id;
						$notes = $q->notes;
						$ministry_id = $q->ministry_id;

						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));
						$start_time = date('h:iA', strtotime($q->start_time));

						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
						$service_type = $this->Crud->read_field('id', $service_id, 'service_report', 'type');
						$service_date = $this->Crud->read_field('id', $service_id, 'service_report', 'date');
						$service = $this->Crud->read_field('id', $service_type, 'service_type', 'name');
						$template = $this->Crud->read_field('id', $template_id, 'service_template', 'name');


						$all_btn = '';
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if (!empty($switch_id)) {
								$all_btn = ' ';
							} else {
								$all_btn = '
									<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit " pageName="' . site_url($mod . '/manage/edit/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
									<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete " pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
									<li><a href="javascript:;" pageSize="modal-xl" class="text-success pop" pageTitle="View " pageName="' . site_url($mod . '/manage/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>' . translate_phrase('View') . '</span></a></li>
									<li><a href="javascript:;" pageSize="modal-lg" class="text-dark pop" pageTitle="Download Service " pageName="' . site_url($mod . '/manage/download/' . $id) . '"><em class="icon ni ni-download"></em><span>' . translate_phrase('Download Service') . '</span></a></li>
									<li><a href="javascript:;" pageSize="modal-" class="text-info pop" pageTitle="Send to Email " pageName="' . site_url($mod . '/manage/send_email/' . $id) . '"><em class="icon ni ni-share-alt"></em><span>' . translate_phrase('Send to Email') . '</span></a></li>
									
								';


							}

						}


						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-name">            
											<span class="tb-lead">' . ucwords($service . ' - ' . $service_date) . '</span> 
										</div>    
									</div>  
								</td>
								<td>
									<span class="small ">' . ucwords($template) . '</span>
								</td>
								<td><span class="small ">' . ucwords($start_time) . '</span></td>
								<td><span class="small ">' . ucwords($church) . '</span></td>
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
						<i class="ni ni-folder-list" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Order of Service Returned') . '
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

			$data['title'] = translate_phrase('Order of Service') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	public function activity($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

		$mod = 'church/activity';

		$log_id = $this->session->get('td_id');
		$switch_id = $this->session->get('switch_church_id');
		
		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		if(!empty($switch_id)){
			$church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
			if($church_type == 'region'){
				$role_id = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');
			}
			if($church_type == 'zone'){
				$role_id = $this->Crud->read_field('name', 'Zonal Manager', 'access_role', 'id');
			}
			if($church_type == 'group'){
				$role_id = $this->Crud->read_field('name', 'Group Manager', 'access_role', 'id');
			}
			if($church_type == 'church'){
				$role_id = $this->Crud->read_field('name', 'Church Leader', 'access_role', 'id');
			}
		}
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		$role_c = $this->Crud->module($role_id, $mod, 'create');
		$role_r = $this->Crud->module($role_id, $mod, 'read');
		$role_u = $this->Crud->module($role_id, $mod, 'update');
		$role_d = $this->Crud->module($role_id, $mod, 'delete');
		if($role_r == 0){
			return redirect()->to(site_url('dashboard'));	
		}
		$data['log_id'] = $log_id;
		$data['role'] = $role;
		$data['role_c'] = $role_c;
		
		$table = 'church_activity';
		
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = rtrim($form_link, '/');
		$data['current_language'] = $this->session->get('current_language');
		
			// manage record
			if($param1 == 'manage') {
				// prepare for delete
				if($param2 == 'delete') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						//echo var_dump($edit);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['d_id'] = $e->id;
							}
						}
						
						if($this->request->getMethod() == 'post'){
							$del_id =  $this->request->getVar('d_id');
							if($this->Crud->deletes('id', $del_id, $table) > 0) {
								echo $this->Crud->msg('success', 'Record Deleted');
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', 'Please try later');
							}
							die;	
						}
					}
				} elseif($param2 == 'generate'){
					if($this->request->getMethod() == 'post'){
						$category_id =  $this->request->getVar('category_id');
						$ministry_id =  $this->request->getVar('ministry_id');
						$church_id =  $this->request->getVar('church_id');
						$member_id =  $this->request->getVar('member_id');
						$start_date =  $this->request->getVar('start_date');
						$end_date =  $this->request->getVar('end_date');

						$cal_ass = $this->Crud->filter_church_activity('', '', $log_id, $start_date, $end_date, $category_id, $member_id, $church_id, $ministry_id);
						$cal_events = array();
						if (!empty($cal_ass)) {
							foreach ($cal_ass as $key => $value) {
								// Handle the event's basic details
								$start = strtotime($value->start_datetime);
								$end = strtotime($value->end_datetime);
								$members = json_decode($value->members, true);
								
								if (!empty($members) && is_array($members)) {
									$firstMember = reset($members);
									$member = $this->Crud->read_field('id', $firstMember, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $firstMember, 'user', 'surname');
								} else {
									$member = "No members found.";
								}
						
								$class = 'fc-event-primary';
								$name = $this->Crud->read_field('id', $value->category_id, 'activity_category', 'name');
						
								// Check if the event is recurring
								if ($value->recurrence) {
									// Get recurrence parameters
									$frequency = $value->frequency; // daily, weekly, monthly
									$interval = $value->intervals; // e.g., every 1 day
									$recurrence_end = $value->recurrence_end; // 'after', 'by date', or 'indefinite'
									$occurrences = $value->occurrences; // number of occurrences if 'after' is selected
									$recurrence_end_date = strtotime($value->end_dates); // date if 'by' is selected
						
									// Generate occurrences based on frequency and interval
									$currentStart = $start;
									$i = 0; // Occurrence counter
						
									// Loop until we reach the defined limits based on the recurrence settings
									while (true) {
										// Set the current occurrence end datetime
										$occurrenceStart = $currentStart;
										$occurrenceEnd = $end + ($i * ($frequency === 'daily' ? 86400 * $interval : ($frequency === 'weekly' ? 604800 * $interval : 2592000 * $interval)));
						
										// Check for end scenarios based on the recurrence end type
										if ($recurrence_end === 'after' && $i >= $occurrences) {
											break; // Stop if we've reached the specified number of occurrences
										}
										if ($recurrence_end === 'by' && $occurrenceStart > $recurrence_end_date) {
											break; // Stop if the occurrence start exceeds the end date
										}
										if ($recurrence_end === 'never' && $i > 500) { // Arbitrary limit to prevent infinite loop, can be adjusted
											break; // Break after a certain number of iterations to avoid infinite loops
										}
						
										// Prepare the event data for the calendar
										$cal_events[] = [
											'id' => $value->id,
											'title' => strtoupper(($name)),
											'start' => date('Y-m-d H:i', $occurrenceStart),
											'end' => date('Y-m-d H:i', $occurrenceEnd),
											'extendedProps' => ['category' => ucwords($member)],
											'publicId' => $value->id,
											'description' => ucwords($this->Crud->convertText($value->description)),
											'className' => $class,
										];
						
										// Move to the next occurrence date based on the frequency
										if ($frequency === 'daily') {
											$currentStart += 86400 * $interval; // Increment by days
										} elseif ($frequency === 'weekly') {
											$currentStart += 604800 * $interval; // Increment by weeks
										} elseif ($frequency === 'monthly') {
											// Add months using DateTime to handle month-end correctly
											$dateTime = new DateTime();
											$dateTime->setTimestamp($currentStart);
											$dateTime->modify("+{$interval} month");
											$currentStart = $dateTime->getTimestamp();
										}
										$i++; // Increment the occurrence counter
									}
								} else {
									// For non-recurring events
									$cal_events[$key] = [
										'id' => $value->id,
										'title' => strtoupper(($name)),
										'start' => date('Y-m-d H:i', $start),
										'end' => date('Y-m-d H:i', $end),
										'extendedProps' => ['category' => ucwords($member)],
										'publicId' => $value->id,
										'description' => ucwords($this->Crud->convertText($value->description)),
										'className' => $class,
									];
								}
							}
						}
						
						if(empty($cal_events)){
							echo $this->Crud->msg('danger', 'No Record Returned');
							die;
						} else {
							echo $this->Crud->msg('success', count($cal_events).' Record Returned');

						}



						die;
					}
				} else {
					// prepare for edit
					if($param2 == 'edit') {
						if($param3) {
							$edit = $this->Crud->read_single('id', $param3, $table);
							if(!empty($edit)) {
								foreach($edit as $e) {
									$data['e_id'] = $e->id;
									$data['e_name'] = $e->name;
									$data['e_description'] = $e->description;
									$data['e_start_date'] = date('m/d/Y', strtotime($e->start_datetime));
									$data['e_start_time'] = date('H:iA', strtotime($e->start_datetime));
									$data['e_end_date'] = date('m/d/Y', strtotime($e->end_datetime));
									$data['e_end_time'] = date('H:iA', strtotime($e->end_datetime));
									$data['e_category_id'] = $e->category_id;
									$data['e_recurrence'] = $e->recurrence;
									$data['e_frequency'] = $e->frequency;
									$data['e_intervals'] = $e->intervals;
									$data['e_by_day'] = json_decode($e->by_day);
									$data['e_recurrence_end'] = $e->recurrence_end;
									$data['e_occurrences'] = $e->occurrences;
									$data['e_end_dates'] = $e->end_dates;
									$data['e_church_id'] = $e->church_id;
									$data['e_church_type'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
									$data['e_ministry_id'] = $e->ministry_id;
									$data['e_member_id'] = $e->members;
								}
							}
						}
					}
	
					// prepare for view
					if($param2 == 'view') {
						if($param3) {
							$edit = $this->Crud->read_single('id', $param3, $table);
							if(!empty($edit)) {
								foreach($edit as $e) {
									$data['e_id'] = $e->id;
									$data['e_name'] = $e->name;
									$data['e_description'] = $e->description;
									$data['e_start_date'] = date('m/d/Y', strtotime($e->start_datetime));
									$data['e_start_time'] = date('H:iA', strtotime($e->start_datetime));
									$data['e_end_date'] = date('m/d/Y', strtotime($e->end_datetime));
									$data['e_end_time'] = date('H:iA', strtotime($e->end_datetime));
									$data['e_category_id'] = $e->category_id;
									$data['e_recurrence'] = $e->recurrence;
									$data['e_frequency'] = $e->frequency;
									$data['e_intervals'] = $e->intervals;
									$data['e_by_day'] = json_decode($e->by_day);
									$data['e_recurrence_end'] = $e->recurrence_end;
									$data['e_occurrences'] = $e->occurrences;
									$data['e_end_dates'] = $e->end_dates;
									$data['e_church_id'] = $e->church_id;
									$data['e_church_type'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
									$data['e_ministry_id'] = $e->ministry_id;
									$data['e_member_id'] = json_decode($e->members);
									$data['e_reg_date'] = $e->reg_date;
								}
							}
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$e_id =  $this->request->getVar('e_id');
						$name =  $this->request->getVar('name');
						$description =  $this->request->getVar('description');
						$start_date =  $this->request->getVar('start_date');
						$start_time =  $this->request->getVar('start_time');
						$end_date =  $this->request->getVar('end_date');
						$end_time =  $this->request->getVar('end_time');
						$category_id =  $this->request->getVar('category_id');
						$category =  $this->request->getVar('category');
						$is_recurring =  $this->request->getVar('is_recurring');
						$frequency =  $this->request->getVar('frequency');
						$interval =  $this->request->getVar('interval');
						$by_day =  $this->request->getVar('by_day');
						$recurrence_end =  $this->request->getVar('recurrence_end');
						$occurrences =  $this->request->getVar('occurrences');
						$recurrence_end_dates =  $this->request->getVar('end_dates');
						$member_id =  $this->request->getVar('member_id');
						$ministry_id =  $this->request->getVar('ministry_id');
						$church_id =  $this->request->getVar('church_id');
						if(empty($member_id)){
							$member_id = array();
						}
						

						// 2. Recurring Event Validation
						if ($is_recurring == 1) {
							// Validate frequency (daily, weekly, monthly, yearly)
							if (empty($frequency) || !in_array($frequency, ['daily', 'weekly', 'monthly', 'yearly'])) {
								$errors[] = 'Invalid recurrence frequency.';
							}

							// Validate interval (e.g., repeat every X days/weeks/months)
							if (empty($interval) || !is_numeric($interval) || $interval <= 0) {
								$errors[] = 'Recurrence interval must be a positive number.';
							}

							// Validate recurrence_end (never, after, by)
							if (empty($recurrence_end) || !in_array($recurrence_end, ['never', 'after', 'by'])) {
								$errors[] = 'Invalid recurrence end type.';
							}

							// If recurrence ends 'after' a certain number of occurrences
							if ($recurrence_end == 'after' && (empty($occurrences) || !is_numeric($occurrences) || $occurrences <= 0)) {
								$errors[] = 'Occurrences must be a valid positive number if recurrence ends after a set number of occurrences.';
							}

							// If recurrence ends 'by' a certain date
							if ($recurrence_end == 'by' && (empty($recurrence_end_dates) || !strtotime($recurrence_end_dates))) {
								$errors[] = 'A valid end date is required if recurrence ends by a specific date.';
							}

							// Example: Recurrence days for weekly recurrence
							if ($frequency == 'weekly' && empty($by_day)) {
								$errors[] = 'Please select at least one day for weekly recurrence.';
							}
						}

						// 3. If there are errors, display them and stop the process
						if (!empty($errors)) {
							// Show errors to the user (this could be done with session flash data or directly returning the errors)
							foreach ($errors as $error) {
								echo $this->Crud->msg('danger', $error);
							}
							die; // Stop the process if there are errors
						}

						if($category_id == 'new'){
							$cate_data['ministry_id'] = $ministry_id;
							$cate_data['church_id'] = $church_id;
							$cate_data['name'] = $category;

							if($this->Crud->check3('ministry_id', $ministry_id, 'church_id', $church_id, 'name', $category, 'activity_category') > 0){
								$category_id = $this->Crud->read_field3('ministry_id', $ministry_id, 'church_id', $church_id, 'name', $category, 'activity_category', 'id');
							} else {
								$category_id = $this->Crud->create('activity_category', $cate_data);
							}
							
						}

						if($is_recurring == 0){
							$frequency = '';
							$interval = 0;
							$occurrences = 0;
							$by_day = [];
							$recurrence_end = '';
							$recurrence_end_dates = null;
						} else {
							if($frequency != 'weekly'){
								$by_day = [];
							}
							if($recurrence_end == 'never'){
								$occurrences = 0;
								$recurrence_end_dates = null;
							}
							if($recurrence_end == 'after'){
								$recurrence_end_dates = null;
							}
							if($recurrence_end == 'by'){
								$occurrences = 0;
							}
						}

						$sDate = date('Y-m-d', strtotime($start_date)).'  '. date('h:i:s', strtotime($start_time));
						$eDate = date('Y-m-d', strtotime($end_date)).' '.date('h:i:s', strtotime($end_time));
						
						// echo json_encode($by_day);
						// die;

						// $ins_data['name'] = $name;
						$ins_data['description'] = $description;
						$ins_data['start_datetime'] = $sDate;
						$ins_data['end_datetime'] = $eDate;
						$ins_data['category_id'] = $category_id;
						$ins_data['recurrence'] = $is_recurring;
						$ins_data['frequency'] = $frequency;
						$ins_data['intervals'] = $interval;
						$ins_data['by_day'] = json_encode($by_day);
						$ins_data['ministry_id'] = $ministry_id;
						$ins_data['occurrences'] = $occurrences;
						$ins_data['recurrence_end'] = $recurrence_end;
						$ins_data['end_dates'] = $recurrence_end_dates;
						$ins_data['church_id'] = ($church_id);
						$ins_data['members'] = json_encode($member_id);
						
						// do create or update
						if($e_id) {
							$upd_rec = $this->Crud->updates('id', $e_id, $table, $ins_data);
							if($upd_rec > 0) {
								echo $this->Crud->msg('success', 'Updated');
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('info', 'No Changes');	
							}
							
						} else{
							
							$ins_data['reg_date'] = date(fdate);
							
							if($this->Crud->check3('category_id', $category_id, 'reg_date', date(fdate), 'ministry_id', $ministry_id, $table) > 0) {
								echo $this->Crud->msg('warning', ('Church Activity Already Exist'));
							} else {
								$ins_rec = $this->Crud->create($table, $ins_data);
								if($ins_rec > 0) {
									echo $this->Crud->msg('success', translate_phrase('Church Activity Created'));
									
									///// store activities
									$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
									$code = $this->Crud->read_field('id', $ins_rec, 'church_activity', 'name');
									$action = $by.' created Church Activity ('.$code.')';
									$this->Crud->activity('church_activity', $ins_rec, $action);
	
									
									echo '<script>location.reload(false);</script>';
								} else {
									echo $this->Crud->msg('danger', translate_phrase('Please try later'));	
								}	
							}
						}
						die;	
					}
				}
			}
	
			// record listing
			if($param1 == 'load') {
				$limit = $param2;
				$offset = $param3;
	
				$rec_limit = 25;
				$item = '';
	
				if(empty($limit)) {$limit = $rec_limit;}
				if(empty($offset)) {$offset = 0;}
				
				
				if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
				$search = $this->request->getPost('search');
	
				//echo $status;
				$log_id = $this->session->get('td_id');
				if(!$log_id) {
					$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
				} else {
					$all_rec = $this->Crud->filter_events('', '', $log_id, $status, $search, $switch_id);
					if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
					$query = $this->Crud->filter_events($limit, $offset, $log_id, $status, $search, $switch_id);
	
					if(!empty($query)) {
						foreach($query as $q) {
							$id = $q->id;
							$reg_date =  date('M d, Y h:i A', strtotime($q->created_at));
							$title = $q->title;
							$church_id = $q->church_id;
							$img = $q->image;
							$ministry_id = $q->ministry_id;
							$church_type = $q->church_type;
							$start_date = $q->start_date;
							$end_date = $q->end_date;
							$event_type = $q->event_type;
							$recurrence_pattern = $q->recurrence_pattern;
							$status = $q->status;
							$images = '';
							if(!empty($img))$images = '<img  src="' . site_url($img) . '" height="40px" width="40px" class="img-responsive">';
							//$approve = '';
							if($status == 1) { 
								$colors = 'success';
								$approve_text = 'Approved';
								$approved = '<span class="text-primary"><i class="ri-check-circle-line"></i></span> '; 
							} else {
								$colors = 'danger';
								$approve_text = 'Not Approved';
							}
	
							// add manage buttons
							if($role_u != 1) {
								$all_btn = '';
							} else {
								if(!empty($switch_id)){
	
									$all_btn = '
										<li><a href="javascript:;" class="text-success pop" pageTitle="View ' . $title . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
									
									';
								} else {
	
									$all_btn = '
										<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $title . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
										<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $title . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
										<li><a href="javascript:;" class="text-success pop" pageTitle="View ' . $title . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
										
									';
	
								}
								
							}
	
							$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
	
							$start = date('Y-m-d', strtotime($q->start_date)).' '.date('H:i', strtotime($q->start_time));
							$end = date('Y-m-d', strtotime($q->end_date)).' '.date('H:i', strtotime($q->end_time));
					
							$item .= '
								<tr>
									<td>
										<div class="user-card">
											<div class="user-avatar">            
												'.$images.'      
											</div>        
											<div class="user-name">            
												<span class="tb-lead">' . ucwords($title) . '</span> <br>
											<span class="tb-lead text-primary">' . ucwords($ministry) . '</span>                   
											</div>    
										</div>  
									</td>
									<td>
										<span class="small text-dark">'.ucwords($church_type).' Churches</span>
									</td>
									<td><span class="small text-dark">'.$start.' <b>&#8594;</b> '.$end.'</span></td>
									<td><span class="small text-dark">'.ucwords($event_type).'</span></td>
									<td><span class="small text-dark">'.ucwords($q->location).'</span></td>
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
	
							
						}
					}
				}
				
				if(empty($item)) {
					$resp['item'] = '
						<Tr><td colspan="8"><div class="text-center text-muted">
							<br/><br/><br/>
							<i class="ni ni-calendar-alt" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Events Returned').'
						</div></td></tr>
					';
				} else {
					$resp['item'] = $item;
					if($offset >= 25){
						$resp['item'] = $item;
					}
					
				}
	
	
				$resp['count'] = $counts;
	
				$more_record = $counts - ($offset + $rec_limit);
				$resp['left'] = $more_record;
	
				if($counts > ($offset + $rec_limit)) { // for load more records
					$resp['limit'] = $rec_limit;
					$resp['offset'] = $offset + $limit;
				} else {
					$resp['limit'] = 0;
					$resp['offset'] = 0;
				}
	
				echo json_encode($resp);
				die;
			}
	
			$cal_events = array();
			$cal_ass = $this->Crud->read('church_activity');
			$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
			$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
			
			$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
			$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
			if(!empty($switch_id)){
				$church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
				$church_id = $switch_id;
				$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
			}
			if($ministry_id > 0 && $church_id <= 0){
				$cal_ass = $this->Crud->read_single('ministry_id', $ministry_id, 'church_activity');
			}
			if($church_id > 0){
				$cal_ass = $this->Crud->read_single('church_id', $church_id, 'church_activity');
			}
			if (!empty($cal_ass)) {
				foreach ($cal_ass as $key => $value) {
					// Handle the event's basic details
					$start = strtotime($value->start_datetime);
					$end = strtotime($value->end_datetime);
					$members = json_decode($value->members, true);
					
					if (!empty($members) && is_array($members)) {
						$firstMember = reset($members);
						$member = $this->Crud->read_field('id', $firstMember, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $firstMember, 'user', 'surname');
					} else {
						$member = "No members found.";
					}
			
					$class = 'fc-event-primary';
					$name = $this->Crud->read_field('id', $value->category_id, 'activity_category', 'name');
			
					// Check if the event is recurring
					if ($value->recurrence) {
						// Get recurrence parameters
						$frequency = $value->frequency; // daily, weekly, monthly
						$interval = $value->intervals; // e.g., every 1 day
						$recurrence_end = $value->recurrence_end; // 'after', 'by date', or 'indefinite'
						$occurrences = $value->occurrences; // number of occurrences if 'after' is selected
						$recurrence_end_date = strtotime($value->end_dates); // date if 'by' is selected
			
						// Generate occurrences based on frequency and interval
						$currentStart = $start;
						$i = 0; // Occurrence counter
			
						// Loop until we reach the defined limits based on the recurrence settings
						while (true) {
							// Set the current occurrence end datetime
							$occurrenceStart = $currentStart;
							$occurrenceEnd = $end + ($i * ($frequency === 'daily' ? 86400 * $interval : ($frequency === 'weekly' ? 604800 * $interval : 2592000 * $interval)));
			
							// Check for end scenarios based on the recurrence end type
							if ($recurrence_end === 'after' && $i >= $occurrences) {
								break; // Stop if we've reached the specified number of occurrences
							}
							if ($recurrence_end === 'by' && $occurrenceStart > $recurrence_end_date) {
								break; // Stop if the occurrence start exceeds the end date
							}
							if ($recurrence_end === 'never' && $i > 500) { // Arbitrary limit to prevent infinite loop, can be adjusted
								break; // Break after a certain number of iterations to avoid infinite loops
							}
			
							// Prepare the event data for the calendar
							$cal_events[] = [
								'id' => $value->id,
								'title' => strtoupper(($name)),
								'start' => date('Y-m-d H:i', $occurrenceStart),
								'end' => date('Y-m-d H:i', $occurrenceEnd),
								'extendedProps' => ['category' => ucwords($member)],
								'publicId' => $value->id,
								'description' => ucwords($this->Crud->convertText($value->description)),
								'className' => $class,
							];
			
							// Move to the next occurrence date based on the frequency
							if ($frequency === 'daily') {
								$currentStart += 86400 * $interval; // Increment by days
							} elseif ($frequency === 'weekly') {
								$currentStart += 604800 * $interval; // Increment by weeks
							} elseif ($frequency === 'monthly') {
								// Add months using DateTime to handle month-end correctly
								$dateTime = new DateTime();
								$dateTime->setTimestamp($currentStart);
								$dateTime->modify("+{$interval} month");
								$currentStart = $dateTime->getTimestamp();
							}
							$i++; // Increment the occurrence counter
						}
					} else {
						// For non-recurring events
						$cal_events[$key] = [
							'id' => $value->id,
							'title' => strtoupper(($name)),
							'start' => date('Y-m-d H:i', $start),
							'end' => date('Y-m-d H:i', $end),
							'extendedProps' => ['category' => ucwords($member)],
							'publicId' => $value->id,
							'description' => ucwords($this->Crud->convertText($value->description)),
							'className' => $class,
						];
					}
				}
			}
			
	
			$data['cal_events'] = (array_values($cal_events));
			// print_r($cal_events);
			if($param1 == 'manage') { // view for form data posting
				return view($mod.'_form', $data);
			} else { // view for main page
				
				$data['title'] = 'Church Activity - '.app_name;
				$data['page_active'] = $mod;
	
				return view($mod, $data);
			}
		}
		

	public function currency($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 
	
		$mod = 'church/currency';

		$log_id = $this->session->get('td_id');
		$switch_id = $this->session->get('switch_church_id');
		
		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		if(!empty($switch_id)){
			$church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
			if($church_type == 'region'){
				$role_id = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');
			}
			if($church_type == 'zone'){
				$role_id = $this->Crud->read_field('name', 'Zonal Manager', 'access_role', 'id');
			}
			if($church_type == 'group'){
				$role_id = $this->Crud->read_field('name', 'Group Manager', 'access_role', 'id');
			}
			if($church_type == 'church'){
				$role_id = $this->Crud->read_field('name', 'Church Leader', 'access_role', 'id');
			}
		}
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		$role_c = $this->Crud->module($role_id, $mod, 'create');
		$role_r = $this->Crud->module($role_id, $mod, 'read');
		$role_u = $this->Crud->module($role_id, $mod, 'update');
		$role_d = $this->Crud->module($role_id, $mod, 'delete');
		if($role_r == 0){
			return redirect()->to(site_url('dashboard'));	
		}
		$data['log_id'] = $log_id;
		$data['role'] = $role;
		$data['role_c'] = $role_c;
		
		$table = 'currency';
		
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = rtrim($form_link, '/');
		$data['current_language'] = $this->session->get('current_language');
			
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					//echo var_dump($edit);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$del_id =  $this->request->getVar('d_id');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', 'Record Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						die;	
					}
				}
			} elseif($param2 == 'generate'){
				if($this->request->getMethod() == 'post'){
					$category_id =  $this->request->getVar('category_id');
					$ministry_id =  $this->request->getVar('ministry_id');
					$church_id =  $this->request->getVar('church_id');
					$member_id =  $this->request->getVar('member_id');
					$start_date =  $this->request->getVar('start_date');
					$end_date =  $this->request->getVar('end_date');

					$cal_ass = $this->Crud->filter_church_activity('', '', $log_id, $start_date, $end_date, $category_id, $member_id, $church_id, $ministry_id);
					$cal_events = array();
					if (!empty($cal_ass)) {
						foreach ($cal_ass as $key => $value) {
							// Handle the event's basic details
							$start = strtotime($value->start_datetime);
							$end = strtotime($value->end_datetime);
							$members = json_decode($value->members, true);
							
							if (!empty($members) && is_array($members)) {
								$firstMember = reset($members);
								$member = $this->Crud->read_field('id', $firstMember, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $firstMember, 'user', 'surname');
							} else {
								$member = "No members found.";
							}
					
							$class = 'fc-event-primary';
							$name = $this->Crud->read_field('id', $value->category_id, 'activity_category', 'name');
					
							// Check if the event is recurring
							if ($value->recurrence) {
								// Get recurrence parameters
								$frequency = $value->frequency; // daily, weekly, monthly
								$interval = $value->intervals; // e.g., every 1 day
								$recurrence_end = $value->recurrence_end; // 'after', 'by date', or 'indefinite'
								$occurrences = $value->occurrences; // number of occurrences if 'after' is selected
								$recurrence_end_date = strtotime($value->end_dates); // date if 'by' is selected
					
								// Generate occurrences based on frequency and interval
								$currentStart = $start;
								$i = 0; // Occurrence counter
					
								// Loop until we reach the defined limits based on the recurrence settings
								while (true) {
									// Set the current occurrence end datetime
									$occurrenceStart = $currentStart;
									$occurrenceEnd = $end + ($i * ($frequency === 'daily' ? 86400 * $interval : ($frequency === 'weekly' ? 604800 * $interval : 2592000 * $interval)));
					
									// Check for end scenarios based on the recurrence end type
									if ($recurrence_end === 'after' && $i >= $occurrences) {
										break; // Stop if we've reached the specified number of occurrences
									}
									if ($recurrence_end === 'by' && $occurrenceStart > $recurrence_end_date) {
										break; // Stop if the occurrence start exceeds the end date
									}
									if ($recurrence_end === 'never' && $i > 500) { // Arbitrary limit to prevent infinite loop, can be adjusted
										break; // Break after a certain number of iterations to avoid infinite loops
									}
					
									// Prepare the event data for the calendar
									$cal_events[] = [
										'id' => $value->id,
										'title' => strtoupper(($name)),
										'start' => date('Y-m-d H:i', $occurrenceStart),
										'end' => date('Y-m-d H:i', $occurrenceEnd),
										'extendedProps' => ['category' => ucwords($member)],
										'publicId' => $value->id,
										'description' => ucwords($this->Crud->convertText($value->description)),
										'className' => $class,
									];
					
									// Move to the next occurrence date based on the frequency
									if ($frequency === 'daily') {
										$currentStart += 86400 * $interval; // Increment by days
									} elseif ($frequency === 'weekly') {
										$currentStart += 604800 * $interval; // Increment by weeks
									} elseif ($frequency === 'monthly') {
										// Add months using DateTime to handle month-end correctly
										$dateTime = new DateTime();
										$dateTime->setTimestamp($currentStart);
										$dateTime->modify("+{$interval} month");
										$currentStart = $dateTime->getTimestamp();
									}
									$i++; // Increment the occurrence counter
								}
							} else {
								// For non-recurring events
								$cal_events[$key] = [
									'id' => $value->id,
									'title' => strtoupper(($name)),
									'start' => date('Y-m-d H:i', $start),
									'end' => date('Y-m-d H:i', $end),
									'extendedProps' => ['category' => ucwords($member)],
									'publicId' => $value->id,
									'description' => ucwords($this->Crud->convertText($value->description)),
									'className' => $class,
								];
							}
						}
					}
					
					if(empty($cal_events)){
						echo $this->Crud->msg('danger', 'No Record Returned');
						die;
					} else {
						echo $this->Crud->msg('success', count($cal_events).' Record Returned');

					}



					die;
				}
			} else {
				// prepare for edit
				if($param2 == 'edit') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_name'] = $e->name;
								$data['e_description'] = $e->description;
								$data['e_start_date'] = date('m/d/Y', strtotime($e->start_datetime));
								$data['e_start_time'] = date('H:iA', strtotime($e->start_datetime));
								$data['e_end_date'] = date('m/d/Y', strtotime($e->end_datetime));
								$data['e_end_time'] = date('H:iA', strtotime($e->end_datetime));
								$data['e_category_id'] = $e->category_id;
								$data['e_recurrence'] = $e->recurrence;
								$data['e_frequency'] = $e->frequency;
								$data['e_intervals'] = $e->intervals;
								$data['e_by_day'] = json_decode($e->by_day);
								$data['e_recurrence_end'] = $e->recurrence_end;
								$data['e_occurrences'] = $e->occurrences;
								$data['e_end_dates'] = $e->end_dates;
								$data['e_church_id'] = $e->church_id;
								$data['e_church_type'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_member_id'] = $e->members;
							}
						}
					}
				}

				// prepare for view
				if($param2 == 'view') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_name'] = $e->name;
								$data['e_description'] = $e->description;
								$data['e_start_date'] = date('m/d/Y', strtotime($e->start_datetime));
								$data['e_start_time'] = date('H:iA', strtotime($e->start_datetime));
								$data['e_end_date'] = date('m/d/Y', strtotime($e->end_datetime));
								$data['e_end_time'] = date('H:iA', strtotime($e->end_datetime));
								$data['e_category_id'] = $e->category_id;
								$data['e_recurrence'] = $e->recurrence;
								$data['e_frequency'] = $e->frequency;
								$data['e_intervals'] = $e->intervals;
								$data['e_by_day'] = json_decode($e->by_day);
								$data['e_recurrence_end'] = $e->recurrence_end;
								$data['e_occurrences'] = $e->occurrences;
								$data['e_end_dates'] = $e->end_dates;
								$data['e_church_id'] = $e->church_id;
								$data['e_church_type'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_member_id'] = json_decode($e->members);
								$data['e_reg_date'] = $e->reg_date;
							}
						}
					}
				}
				
				if($this->request->getMethod() == 'post'){
					$e_id =  $this->request->getVar('e_id');
					$name =  $this->request->getVar('name');
					$description =  $this->request->getVar('description');
					$start_date =  $this->request->getVar('start_date');
					$start_time =  $this->request->getVar('start_time');
					$end_date =  $this->request->getVar('end_date');
					$end_time =  $this->request->getVar('end_time');
					$category_id =  $this->request->getVar('category_id');
					$category =  $this->request->getVar('category');
					$is_recurring =  $this->request->getVar('is_recurring');
					$frequency =  $this->request->getVar('frequency');
					$interval =  $this->request->getVar('interval');
					$by_day =  $this->request->getVar('by_day');
					$recurrence_end =  $this->request->getVar('recurrence_end');
					$occurrences =  $this->request->getVar('occurrences');
					$recurrence_end_dates =  $this->request->getVar('end_dates');
					$member_id =  $this->request->getVar('member_id');
					$ministry_id =  $this->request->getVar('ministry_id');
					$church_id =  $this->request->getVar('church_id');
					if(empty($member_id)){
						$member_id = array();
					}
					

					// 2. Recurring Event Validation
					if ($is_recurring == 1) {
						// Validate frequency (daily, weekly, monthly, yearly)
						if (empty($frequency) || !in_array($frequency, ['daily', 'weekly', 'monthly', 'yearly'])) {
							$errors[] = 'Invalid recurrence frequency.';
						}

						// Validate interval (e.g., repeat every X days/weeks/months)
						if (empty($interval) || !is_numeric($interval) || $interval <= 0) {
							$errors[] = 'Recurrence interval must be a positive number.';
						}

						// Validate recurrence_end (never, after, by)
						if (empty($recurrence_end) || !in_array($recurrence_end, ['never', 'after', 'by'])) {
							$errors[] = 'Invalid recurrence end type.';
						}

						// If recurrence ends 'after' a certain number of occurrences
						if ($recurrence_end == 'after' && (empty($occurrences) || !is_numeric($occurrences) || $occurrences <= 0)) {
							$errors[] = 'Occurrences must be a valid positive number if recurrence ends after a set number of occurrences.';
						}

						// If recurrence ends 'by' a certain date
						if ($recurrence_end == 'by' && (empty($recurrence_end_dates) || !strtotime($recurrence_end_dates))) {
							$errors[] = 'A valid end date is required if recurrence ends by a specific date.';
						}

						// Example: Recurrence days for weekly recurrence
						if ($frequency == 'weekly' && empty($by_day)) {
							$errors[] = 'Please select at least one day for weekly recurrence.';
						}
					}

					// 3. If there are errors, display them and stop the process
					if (!empty($errors)) {
						// Show errors to the user (this could be done with session flash data or directly returning the errors)
						foreach ($errors as $error) {
							echo $this->Crud->msg('danger', $error);
						}
						die; // Stop the process if there are errors
					}

					if($category_id == 'new'){
						$cate_data['ministry_id'] = $ministry_id;
						$cate_data['church_id'] = $church_id;
						$cate_data['name'] = $category;

						if($this->Crud->check3('ministry_id', $ministry_id, 'church_id', $church_id, 'name', $category, 'activity_category') > 0){
							$category_id = $this->Crud->read_field3('ministry_id', $ministry_id, 'church_id', $church_id, 'name', $category, 'activity_category', 'id');
						} else {
							$category_id = $this->Crud->create('activity_category', $cate_data);
						}
						
					}

					if($is_recurring == 0){
						$frequency = '';
						$interval = 0;
						$occurrences = 0;
						$by_day = [];
						$recurrence_end = '';
						$recurrence_end_dates = null;
					} else {
						if($frequency != 'weekly'){
							$by_day = [];
						}
						if($recurrence_end == 'never'){
							$occurrences = 0;
							$recurrence_end_dates = null;
						}
						if($recurrence_end == 'after'){
							$recurrence_end_dates = null;
						}
						if($recurrence_end == 'by'){
							$occurrences = 0;
						}
					}

					$sDate = date('Y-m-d', strtotime($start_date)).'  '. date('h:i:s', strtotime($start_time));
					$eDate = date('Y-m-d', strtotime($end_date)).' '.date('h:i:s', strtotime($end_time));
					
					// echo json_encode($by_day);
					// die;

					// $ins_data['name'] = $name;
					$ins_data['description'] = $description;
					$ins_data['start_datetime'] = $sDate;
					$ins_data['end_datetime'] = $eDate;
					$ins_data['category_id'] = $category_id;
					$ins_data['recurrence'] = $is_recurring;
					$ins_data['frequency'] = $frequency;
					$ins_data['intervals'] = $interval;
					$ins_data['by_day'] = json_encode($by_day);
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['occurrences'] = $occurrences;
					$ins_data['recurrence_end'] = $recurrence_end;
					$ins_data['end_dates'] = $recurrence_end_dates;
					$ins_data['church_id'] = ($church_id);
					$ins_data['members'] = json_encode($member_id);
					
					// do create or update
					if($e_id) {
						$upd_rec = $this->Crud->updates('id', $e_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', 'Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
						
					} else{
						
						$ins_data['reg_date'] = date(fdate);
						
						if($this->Crud->check3('category_id', $category_id, 'reg_date', date(fdate), 'ministry_id', $ministry_id, $table) > 0) {
							echo $this->Crud->msg('warning', ('Church Activity Already Exist'));
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Church Activity Created'));
								
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'church_activity', 'name');
								$action = $by.' created Church Activity ('.$code.')';
								$this->Crud->activity('church_activity', $ins_rec, $action);

								
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', translate_phrase('Please try later'));	
							}	
						}
					}
					die;	
				}
			}
		}

		if($param1 == 'update_rate'){
			$country_id = $this->request->getPost('id');
			$rate = $this->request->getPost('rate');
			$ministry_id = $this->request->getPost('ministry_id');
			
			if($this->Crud->check('id', $country_id, 'country')==0){
				echo $this->Crud->msg('warning', 'Invalid Country');
				die;
			}

			$currency_name = $this->Crud->read_field('id', $country_id, 'country', 'currency_name');
			$symbol = $this->Crud->read_field('id', $country_id, 'country', 'currency_symbol');
			
			$ins['country_id'] = $country_id;
			$ins['ministry_id'] = $ministry_id;
			$ins['symbol'] = $symbol;
			$ins['rate'] = $rate;
			$ins['currency_name'] = $currency_name;
			
			if($this->Crud->check2('country_id', $country_id, 'ministry_id', $ministry_id, 'currency')> 0){
				// Record exists, get the current data
				$currency_data = $this->Crud->read_field2('country_id', $country_id, 'ministry_id', $ministry_id, 'currency', 'date_rate_change');
				$rec_id = $this->Crud->read_field2('country_id', $country_id, 'ministry_id', $ministry_id, 'currency', 'id');

				// Decode the existing rate changes
				$existing_rate_changes = json_decode($currency_data, true);
				
				// Check if $existing_rate_changes is an array
				if (!is_array($existing_rate_changes)) {
					$existing_rate_changes = []; // Initialize as an empty array if not
				}

				/// Initialize variables to store the latest rate information
				$latest_rate_info = null;
				$latest_date = null;

				// Iterate through the existing rate changes to find the latest date
				foreach ($existing_rate_changes as $rate_change) {
					$current_date = strtotime($rate_change['date']);
					
					if ($latest_date === null || $current_date > $latest_date) {
						$latest_date = $current_date;
						$latest_rate_info = $rate_change;
					}
				}


				if ($latest_rate_info) {
					$recent_rate = $latest_rate_info['rate']; // Get the most recent rate

					// Check if the new rate is different from the most recent one
					if ($recent_rate !== (string)$rate) {
						// If the rate has changed, add the new rate to the rate change array
						$rate_change = array('rate' => (string)$rate, 'date' => date('Y-m-d H:i:s'));

						// Add the new rate change to the existing changes array
						$existing_rate_changes[] = $rate_change;

						// Update the `date_rate_change` field with the new array
						$ins['date_rate_change'] = json_encode($existing_rate_changes);

						// Update the record with the new rate and rate change array
						$update_rec = $this->Crud->updates('id', $rec_id, 'currency', $ins);

						if ($update_rec > 0) {
							echo $this->Crud->msg('success', 'Rate Updated Successfully');
						} else {
							echo $this->Crud->msg('danger', 'Update Failed. Try Again Later');
						}
					} else {
						echo $this->Crud->msg('info', 'No Rate Change Detected');
					}
				} else {
					echo "No rate changes found.";
				}

			} else{
				$change['rate'] = $rate;
				$change['date'] = date(fdate);

				$rate_change[] = $change;
				$ins['date_rate_change'] = json_encode($rate_change);
				$ins['reg_date'] = date(fdate);
				$ins_rec = $this->Crud->create('currency', $ins);
				if($ins_rec > 0){
					echo $this->Crud->msg('success', 'Rate Update');
				} else{
					echo $this->Crud->msg('danger', 'Try Again Later');
				}
			}

			die;
		}

		

		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 75;
			$item = '';

			if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			
			if(!empty($this->request->getPost('ministry_id'))) { $ministry_id = $this->request->getPost('ministry_id'); } else { $ministry_id = 0; }
			$search = $this->request->getPost('search');

			//echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<tr><td colspan="7><div class="text-center text-muted">Session Timeout! - Please login again</div></td></tr>';
			} else {
				$counts = 0;
				if(!$ministry_id){
					$item = '<tr><td colspan="7"><div class="text-center text-muted m-2"><br/><br/>'.translate_phrase('Select Ministry First').'</div></td></tr>';
				} else{
					$all_rec = $this->Crud->read_order_like('country', 'name', 'asc', 'name', $search, '', '');
					if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
					$query = $this->Crud->read_order_like('country', 'name', 'asc', 'name', $search, $limit, $offset);

					if(!empty($query)) {
						foreach($query as $q) {
							$id = $q->id;
							$name = $q->name;
							$currency = $q->currency;
							$currency_name = $q->currency_name;
							$currency_symbol = $q->currency_symbol;
							$logo = ltrim($q->tld, '.');
							
							$images = '<img  src="' . site_url('assets/images/flags/'.$logo.'.png') . '" height="40px" width="40px" class="img-responsive">';
							
							$rate = $this->Crud->read_field2('ministry_id', $ministry_id, 'country_id', $id, 'currency', 'rate');
							if(empty($rate)){
								$rate = 0;
							}


							$item .= '
								<tr>
									<td>
										<div class="user-card">
											<div class="user-avatar">            
												'.$images.'      
											</div> 
										</div>  
									</td>
									<td>
										<div class="user-card">
											<div class="user-name">            
												<span class="tb-lead">' . ucwords($name) . '</span> 
											</div>    
										</div> 
									</td>
									<td><span class="small text-dark">'.ucwords($currency_name).' <b>&#8594;</b> '.$currency.'</span></td>
									<td><span class="small text-dark">'.($currency_symbol).'</span></td>
									<td><input id="value'.$id.'" type="text" value="'.$rate.'" class="form-control update_rates" oninput="update_rate('.$id.');" />
									<span class="small text-danger" id="rate_resp'.$id.'"></span>
									</td>
									
								</tr>
							';

							
						}
					}
				}
			}
			
			if(empty($item)) {
				$resp['item'] = '
					<tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/>
						<i class="ni ni-calendar-alt" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Events Returned').'
					</div></td></tr>
				';
			} else {
				$resp['item'] = $item;
				if($offset >= 75){
					$resp['item'] = $item;
				}
				
			}


			$resp['count'] = $counts;

			$more_record = $counts - ($offset + $rec_limit);
			$resp['left'] = $more_record;

			if($counts > ($offset + $rec_limit)) { // for load more records
				$resp['limit'] = $rec_limit;
				$resp['offset'] = $offset + $limit;
			} else {
				$resp['limit'] = 0;
				$resp['offset'] = 0;
			}

			echo json_encode($resp);
			die;
		}
	
		// print_r($cal_events);
		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = 'Currency Setup - '.app_name;
			$data['page_active'] = $mod;

			return view($mod, $data);
		}
	}
	
	public function updateDefaultCurrency(){
		$log_id = $this->session->get('td_id');
		$switch_id = $this->session->get('switch_church_id');
		
		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		if(!empty($switch_id)){
			$church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
			if($church_type == 'region'){
				$role_id = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');
			}
			if($church_type == 'zone'){
				$role_id = $this->Crud->read_field('name', 'Zonal Manager', 'access_role', 'id');
			}
			if($church_type == 'group'){
				$role_id = $this->Crud->read_field('name', 'Group Manager', 'access_role', 'id');
			}
			if($church_type == 'church'){
				$role_id = $this->Crud->read_field('name', 'Church Leader', 'access_role', 'id');
			}
		}
		$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
		$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
		$country_id = $this->Crud->read_field('id', $church_id, 'church', 'country_id');
		$country_currency = $this->Crud->read_field('id', $country_id, 'country', 'currency_symbol');
		$cur = $this->request->getPost('defaultCurrency');
		
		if($cur == 'country_currency'){
			$this->Crud->updates('id', $church_id, 'church', array('default_currency'=>1));
			$this->session->set('currency', $country_currency);
		} else{
			$this->Crud->updates('id', $church_id, 'church', array('default_currency'=>0));
			$this->session->set('currency', 'ESP ');
		}
		
		echo '<script>location.reload(false);</script>';
		die;
	}

	public function update_state(){
		$log_id = $this->session->get('td_id');
		$state_id = $this->request->getPost('state_id');
		$country_id = $this->request->getPost('country_id');
		
		$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
		if($state_id){
			$in = $this->Crud->updates('id', $church_id, 'church', ['state_id'=>$state_id, 'country_id'=>$country_id]);
			if($in > 0){
				echo $this->Crud->msg('success', 'Church Profile Updated');
				///// store activities
				$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
				$code = $this->Crud->read_field('id', $church_id, 'church', 'name');
				$action = $by . ' updated Church (' . $code . ') State';
				$this->Crud->activity('church', $church_id, $action);

				echo '<script>location.reload(false);</script>';
			} else {
				echo $this->Crud->msg('info', 'No Changes');
			}
		}
	}

	public function get_states_by_country($country_id)
	{
		$states = $this->Crud->read_single_order('country_id', $country_id, 'state', 'name', 'asc');
		echo json_encode($states);
	}


}