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
								$data['e_church_type'] = $e->church_type;
								$data['e_church_id'] = json_decode($e->church_id);
								$data['e_weekly_time'] = json_decode($e->weekly_time, true);
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
					$level = $this->request->getVar('level');
					$is_joint = $this->request->getVar('is_joint');
					$ministry_id = $this->request->getVar('ministry_id');
					$church_id = $this->request->getVar('church_id');
					$active = $this->request->getVar('active');
					$church_ids = $this->request->getVar('church_ids');
					if(empty($church_ids))$church_ids = 0;

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
					$churchs = ($church_id);
					if($is_joint == 0){
						$churchs[] = $church_ids;
					}

					$ins_data['quarter'] = $quarter;
					$ins_data['year'] = $year;
					$ins_data['start_date'] = $start_date;
					$ins_data['end_date'] = $end_date;
					$ins_data['church_type'] = $level;
					$ins_data['location'] = $location;
					$ins_data['weekly_time'] = json_encode($weekly_time);
					$ins_data['is_joint'] = $is_joint;
					$ins_data['church_id'] = json_encode($churchs);
					$ins_data['active'] = $active;
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
						$church_ids_json = json_encode($churchs);

						$result = $this->Crud->check_json('foundation_setup', $church_ids_json, $year, $quarter);
						if ($result > 0) {
							// If church_id exists for the same year and quarter, prevent insertion
							echo $this->Crud->msg('warning', 'Foundation Setup for the selected Church, Year, and Quarter Already Exists');
							die;
						} else {
							if ($this->Crud->check3('church_id', json_encode($church_id), 'year', $year, 'quarter', $quarter, $table) > 0) {
								echo $this->Crud->msg('warning', 'Foundation Setup for the selected Church, Year, and Quarter Already Exists');
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
								<li><a href="javascript:;" onclick="church_admin(' . (int) $id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Instructors') . '</span></a></li>
								
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

	

	public function instructor($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'foundation/instructor';
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

		$foundation_id = $this->session->get('foundation_id');
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

				}
			} else {
				// prepare for edit
				if ($param2 == 'edit') {
					if ($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if (!empty($edit)) {
							foreach ($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_teacher_course'] = json_decode($e->teacher_course);
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$user_id = $this->request->getPost('user_id');
					$foundation_id = $this->session->get('foundation_id');
					$weeks = $this->request->getPost('weeks');
					$courses = $this->request->getPost('courses');
					$new_courses = $this->request->getPost('new_courses');
					$instructors = $this->request->getPost('instructors');

					$new_course_ids = [];
					
					$instructor_assignments = [];
					// Handle new courses (if any)
					if (!empty($new_courses)) {
						foreach ($new_courses as $index => $new_course) {
							if (!empty($new_course)) {
								// Insert new course into the database
								$new_course_data = [
									'name' => $new_course,
									'reg_date' => date(fdate), // You may want to associate it with the foundation school
								];

								// Insert the new course and get the inserted course ID
								if($this->Crud->check('name', $new_course, 'foundation_courses') == 0){
									$new_course_id = $this->Crud->create('foundation_courses', $new_course_data);
								} else{
									$new_course_id = $this->Crud->read_field('name', $new_course, 'foundation_courses', 'id');
								}
								// Store the new course ID at the correct index to use later
								$new_course_ids[$index] = $new_course_id;
							}
						}
					}

					
					// Loop through the submitted weeks and assign instructors to courses
					if (!empty($weeks) && !empty($courses) && !empty($instructors)) {
						foreach ($weeks as $index => $week) {
							// If the course at this index is "new", use the corresponding new course ID
							if ($courses[$index] == 'new') {
								// Use the new course ID from the array we created earlier
								$course_id = isset($new_course_ids[$index]) ? $new_course_ids[$index] : null;
							} else {
								// Use the existing course ID from the dropdown
								$course_id = $courses[$index];
							}
							// Only proceed if we have a valid course ID and instructor for this entry
							if ($course_id && !empty($instructors[$index])) {
								// Prepare the data for the instructor assignment
								$assign_data = [
									'foundation_id' => $foundation_id,
									'week' => $week,
									'course_id' => $course_id,
									'instructor_id' => $instructors[$index],
								];

								// Add this data to the final array for json_encode
								$instructor_assignments[] = $assign_data;
							}
						}


						// Convert the final assignments array to JSON
						$json_data = json_encode($instructor_assignments);
						echo $json_data.' -';
						$ins_data['teacher_course'] = $json_data;

						if ($foundation_id) {
							$upd_rec = $this->Crud->updates('id', $foundation_id, $table, $ins_data);
							if ($upd_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Instructor Record Updated'));
	
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $foundation_id, 'foundation_setup', 'quarter');
								$action = $by . ' updated Instructor Record for (' . $code . ') Session';
								$this->Crud->activity('foundation_setup', $foundation_id, $action);
								echo '<script>
										load_admin("","",' . $foundation_id . ');
										$("#modal").modal("hide");
									</script>';
							} else {
								echo $this->Crud->msg('info', translate_phrase('No Changes'));
							}
						} 
					} else{
						echo $this->Crud->msg('danger', 'Enter all Details');
						die;
					}

					exit;
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


			if (!empty($this->request->getPost('status'))) {
				$status = $this->request->getPost('status');
			} else {
				$status = '';
			}
			$search = $this->request->getPost('search');
			$foundation_id = $this->request->getPost('id');
			$this->session->set('foundation_id', $foundation_id);

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

				$all_rec = $this->Crud->filter_church_pastor('', '', $log_id, $status, $search, '');
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = json_decode($this->Crud->read_field('id', $foundation_id, 'foundation_setup', 'teacher_course'));
				$data['count'] = $counts;

				// print_r($query);
				if (!empty($query)) {
					foreach ($query as $index => $entry) {
						$id = $index;
						$week = $entry->week;
						$course_id = $entry->course_id;
						$instructor_id = $entry->instructor_id;
					
						// Assuming you have functions to get course name and instructor name by ID
						$course_name = $this->Crud->read_field('id', $course_id, 'foundation_courses', 'name');  // Replace with the actual function to get course name
						$instructor_name = $this->Crud->read_field('id', $instructor_id, 'user', 'surname').' '.$this->Crud->read_field('id', $instructor_id, 'user', 'firstname');  // Replace with actual function to get instructor name
					
						
						// add manage buttons
						if (!empty($switch_id)) {
							$all_btn = '
								
								
							';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit " pageSize="modal-lg" pageName="' . site_url($mod . '/manage/edit/'.$foundation_id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								
							';

						}



						$item .= '
							<tr>
								<td>' . $week . '</td>
								<td>' . ucwords($course_name) . '</td>
								<td>' . ucwords($instructor_name) . '</td>
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
						<i class="ni ni-users" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Instructor Returned') . '
					</div></td></tr><script>$("#instructor_resp").show(500);</script>
				';
			} else {
				$resp['item'] = $items . $item.' <script>$("#instructor_resp").hide(500);</script>';
				

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
			return view('foundation/instructor_form', $data);
		}

	}

}