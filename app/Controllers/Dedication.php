<?php

namespace App\Controllers;

class Dedication extends BaseController{


	public function list($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'dedication/list';

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


		$table = 'dedication';
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
						$action = $by . ' deleted Child Dedication Record';

						if ($this->Crud->deletes('id', $del_id, $table) > 0) {

							$this->Crud->activity('dedication', $del_id, $action);
							echo $this->Crud->msg('success', 'Child Dedication Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;
					}
				}
			} elseif($param2 == 'done'){
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
						$action = $by . ' updated Child Dedication Record';

						if ($this->Crud->updates('id', $del_id, $table, array('status'=>1)) > 0) {

							//Add to Member
							$record =  $this->Crud->read_single('id', $del_id, $table);
							if (!empty($record)) {
								foreach ($record as $e) {
									$gender = $e->gender;
									$ministry_id = $e->ministry_id;
									$church_id = $e->church_id;
									$surname = $e->surname;
									$firstname = $e->firstname;
									$othername = $e->othername;
									$dob = $e->dob;
									$father_id = $e->father_id;
									$mother_id = $e->mother_id;
								}
							}

							$title = 'Brother';
							if($gender == 'Female') $title = 'Sister';

							$parent_id = $father_id;
							if(empty($parent_id)) $parent_id = $mother_id;

							$cell_id = $this->Crud->read_field('id', $parent_id, 'user', 'cell_id');
							$address = $this->Crud->read_field('id', $parent_id, 'user', 'address');
							
							$ins_dat['surname'] = $surname;
							$ins_dat['firstname'] = $firstname;
							$ins_dat['othername'] = $othername;
							$ins_dat['gender'] = $gender;
							$ins_dat['dob'] = $dob;
							$ins_dat['ministry_id'] = $ministry_id;
							$ins_dat['church_id'] = $church_id;
							$ins_dat['title'] = $title;
							$ins_dat['role_id'] = 4;
							$ins_dat['activate'] = 1;
							$ins_dat['family_status'] = 'single';
							$ins_dat['parent_id'] = $parent_id;
							$ins_dat['address'] = $address;
							$ins_dat['is_member'] = 1;
							$ins_dat['cell_id'] = $cell_id;
							$ins_dat['reg_date'] = date(fdate);
							$ins_dat['family_position'] = 'child';
							$ins_dat['password'] = md5($surname);
							
							$ins_rec = $this->Crud->create('user', $ins_dat);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'user', 'surname');
								$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));
							}

							$this->Crud->activity('dedication', $del_id, $action);
							echo $this->Crud->msg('success', 'Child Dedication Updated.<br>Moved to Membership');
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
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_id'] = $e->church_id;
								$data['e_level'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
								$data['e_surname'] = $e->surname;
								$data['e_firstname'] = $e->firstname;
								$data['e_othername'] = $e->othername;
								$data['e_gender'] = $e->gender;
								$data['e_dob'] = $e->dob;
								$data['e_date'] = $e->date;
								$data['e_father_id'] = $e->father_id;
								$data['e_mother_id'] = $e->mother_id;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$edit_id = $this->request->getVar('edit_id');
					$ministry_id = $this->request->getVar('ministry_id');
					$church_id = $this->request->getVar('church_id');
					$date = $this->request->getVar('date');
					$surname = $this->request->getVar('surname');
					$firstname = $this->request->getVar('firstname');
					$othername = $this->request->getVar('othername');
					$dob = $this->request->getVar('dob');
					$gender = $this->request->getVar('gender');
					$father_id = $this->request->getVar('father_id');
					$mother_id = $this->request->getVar('mother_id');
					

					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['date'] = $date;
					$ins_data['surname'] = $surname;
					$ins_data['firstname'] = $firstname;
					$ins_data['othername'] = $othername;
					$ins_data['dob'] = $dob;
					$ins_data['father_id'] = $father_id;
					$ins_data['mother_id'] = $mother_id;
					$ins_data['gender'] = $gender;

					// do create or update
					if ($edit_id) {
						$upd_rec = $this->Crud->updates('id', $edit_id, $table, $ins_data);
						if ($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $edit_id, 'dedication', 'surname').' '.$this->Crud->read_field('id', $edit_id, 'dedication', 'firstname');
							$action = $by . ' updated Dedication Record for (' . $code . ')';
							$this->Crud->activity('dedication', $edit_id, $action);

							echo $this->Crud->msg('success', 'Child Dedication Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						
						if ($this->Crud->check3('church_id', $church_id, 'surname', $surname, 'firstname', $firstname, $table) > 0) {
							echo $this->Crud->msg('warning', 'Child Dedication Already Exist');
						} else {
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'dedication', 'surname').' '.$this->Crud->read_field('id', $ins_rec, 'dedication', 'firstname');
								$action = $by . ' created Dedication (' . $code . ') Record';
								$this->Crud->activity('dedication', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Child Dedication Created');
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

		if($param1 == 'record'){
			if($param2 == 'get_parent'){
				$church_id = $this->request->getPost('church_id');
				$mother_id = $this->request->getPost('mother_id');
				$father_id = $this->request->getPost('father_id');
				
				$parent = array();
				$mother = '';
				$father = '';
				
				$mothers = $this->Crud->read2_order('church_id', $church_id, 'gender', 'Female', 'user', 'surname', 'asc');
				if(!empty($mothers)){
					$mother .= '<option value="">Select Mother</option>';
					foreach($mothers as $mom){
						$sel = '';
						if(!empty($mother_id)){
							if($mother_id == $mom->id){
								$sel = 'selected';
							}
						}

						$mother .= '<option value="'.$mom->id.'" '.$sel.'>'.ucwords($mom->surname.' '.$mom->firstname).' - '.$mom->phone.'</option>';
					}
				} else {
					$mother .= '<option value="">No Record Found</option>';
				}

				$fathers = $this->Crud->read2_order('church_id', $church_id, 'gender', 'Male', 'user', 'surname', 'asc');
				if(!empty($fathers)){
					$father .= '<option value="">Select Father</option>';
					foreach($fathers as $dad){
						$sel = '';
						if(!empty($father_id)){
							if($father_id == $dad->id){
								$sel = 'selected';
							}
						}

						$father .= '<option value="'.$dad->id.'" '.$sel.'>'.ucwords($dad->surname.' '.$dad->firstname).' - '.$dad->phone.'</option>';
					}
				} else {
					$father .= '<option value="">No Record Found</option>';
				}


				$parent['mother'] = $mother;
				$parent['father'] = $father;
				
				echo json_encode($parent);
				die;
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
				
				$switch_id = $this->session->get('switch_church_id');
				$all_rec = $this->Crud->filter_dedication('', '', $log_id, $search, $switch_id, 0);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_dedication($limit, $offset, $log_id, $search, $switch_id, 0);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$surname = $q->surname;
						$firstname = $q->firstname;
						$othername = $q->othername;
						$gender = $q->gender;
						$ministry = $this->Crud->read_field('id', $q->ministry_id, 'ministry', 'name');
						$church = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
						$father = $this->Crud->read_field('id', $q->father_id, 'user', 'surname').' '.$this->Crud->read_field('id', $q->father_id, 'user', 'firstname');
						$mother = $this->Crud->read_field('id', $q->mother_id, 'user', 'surname').' '.$this->Crud->read_field('id', $q->mother_id, 'user', 'firstname');
						
						$date = date('d/m/Y', strtotime($q->date));
						$dob = date('d/m/Y', strtotime($q->dob));
						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));

						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if (!empty($switch_id)) {
								$all_btn = '
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageSize="modal-lg" pageTitle="Edit " pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete " pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>' . translate_phrase('Delete') . '</span></a></li>
								<li><a href="javascript:;" class="text-info pop" pageTitle="Mark as Done " pageName="' . site_url($mod . '/manage/done/' . $id) . '"><em class="icon ni ni-check-round-cut"></em><span>' . translate_phrase('Mark as Done') . '</span></a></li>
								
							';

							}

						}

						$item .= '
							<tr>
								<td>
									<div class="user-card">
								      	<div class="user-name">            
											<span class="">' . ($date) .'</span>    
										</div>    
									</div>  
								</td>
								<td>
									<span class="small text ">' . $church . '</span>
								</td>
								<td><span class="small text "><b>' . ucwords($surname.' '.$firstname.' '.$othername) . '</b></span></td>
								<td><span class="small text ">' . $dob . '</span></td>
								<td><span class="small text ">' . ucwords($gender) . '</span></td>
								<td><span class="small text ">' . $father . '</span></td>
								<td><span class="small text ">' . $mother . '</span></td>
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
						$a++;
					}
				}

			}

			if (empty($item)) {
				$resp['item'] = $items . '
					<tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/>
						<i class="ni ni-user-add" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Child Dedication Returned') . '
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

			$data['title'] = translate_phrase('Dedication List') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	

	public function archive($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'dedication/archive';

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


		$table = 'dedication';
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
						$action = $by . ' deleted Child Dedication Record';

						if ($this->Crud->deletes('id', $del_id, $table) > 0) {

							$this->Crud->activity('dedication', $del_id, $action);
							echo $this->Crud->msg('success', 'Child Dedication Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;
					}
				}
			} elseif($param2 == 'done'){
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
						$action = $by . ' updated Child Dedication Record';

						if ($this->Crud->updates('id', $del_id, $table, array('status'=>1)) > 0) {

							//Add to Member
							$record =  $this->Crud->read_single('id', $del_id, $table);
							if (!empty($record)) {
								foreach ($record as $e) {
									$gender = $e->gender;
									$ministry_id = $e->ministry_id;
									$church_id = $e->church_id;
									$surname = $e->surname;
									$firstname = $e->firstname;
									$othername = $e->othername;
									$dob = $e->dob;
									$father_id = $e->father_id;
									$mother_id = $e->mother_id;
								}
							}

							$title = 'Brother';
							if($gender == 'Female') $title = 'Sister';

							$parent_id = $father_id;
							if(empty($parent_id)) $parent_id = $mother_id;

							$cell_id = $this->Crud->read_field('id', $parent_id, 'user', 'cell_id');
							$address = $this->Crud->read_field('id', $parent_id, 'user', 'address');
							
							$ins_dat['surname'] = $surname;
							$ins_dat['firstname'] = $firstname;
							$ins_dat['othername'] = $othername;
							$ins_dat['gender'] = $gender;
							$ins_dat['dob'] = $dob;
							$ins_dat['ministry_id'] = $ministry_id;
							$ins_dat['church_id'] = $church_id;
							$ins_dat['title'] = $title;
							$ins_dat['role_id'] = 4;
							$ins_dat['activate'] = 1;
							$ins_dat['family_status'] = 'single';
							$ins_dat['parent_id'] = $parent_id;
							$ins_dat['address'] = $address;
							$ins_dat['is_member'] = 1;
							$ins_dat['cell_id'] = $cell_id;
							$ins_dat['reg_date'] = date(fdate);
							$ins_dat['family_position'] = 'child';
							$ins_dat['password'] = md5($surname);
							
							$ins_rec = $this->Crud->create($table, $ins_dat);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'user', 'surname');
								$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));
							}

							$this->Crud->activity('dedication', $del_id, $action);
							echo $this->Crud->msg('success', 'Child Dedication Updated.<br>Moved to Membership');
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
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_id'] = $e->church_id;
								$data['e_level'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
								$data['e_surname'] = $e->surname;
								$data['e_firstname'] = $e->firstname;
								$data['e_othername'] = $e->othername;
								$data['e_gender'] = $e->gender;
								$data['e_dob'] = $e->dob;
								$data['e_date'] = $e->date;
								$data['e_father_id'] = $e->father_id;
								$data['e_mother_id'] = $e->mother_id;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$edit_id = $this->request->getVar('edit_id');
					$ministry_id = $this->request->getVar('ministry_id');
					$church_id = $this->request->getVar('church_id');
					$date = $this->request->getVar('date');
					$surname = $this->request->getVar('surname');
					$firstname = $this->request->getVar('firstname');
					$othername = $this->request->getVar('othername');
					$dob = $this->request->getVar('dob');
					$gender = $this->request->getVar('gender');
					$father_id = $this->request->getVar('father_id');
					$mother_id = $this->request->getVar('mother_id');
					

					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['date'] = $date;
					$ins_data['surname'] = $surname;
					$ins_data['firstname'] = $firstname;
					$ins_data['othername'] = $othername;
					$ins_data['dob'] = $dob;
					$ins_data['father_id'] = $father_id;
					$ins_data['mother_id'] = $mother_id;
					$ins_data['gender'] = $gender;

					// do create or update
					if ($edit_id) {
						$upd_rec = $this->Crud->updates('id', $edit_id, $table, $ins_data);
						if ($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $edit_id, 'dedication', 'surname').' '.$this->Crud->read_field('id', $edit_id, 'dedication', 'firstname');
							$action = $by . ' updated Dedication Record for (' . $code . ')';
							$this->Crud->activity('dedication', $edit_id, $action);

							echo $this->Crud->msg('success', 'Child Dedication Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						
						if ($this->Crud->check3('church_id', $church_id, 'surname', $surname, 'firstname', $firstname, $table) > 0) {
							echo $this->Crud->msg('warning', 'Child Dedication Already Exist');
						} else {
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'dedication', 'surname').' '.$this->Crud->read_field('id', $ins_rec, 'dedication', 'firstname');
								$action = $by . ' created Dedication (' . $code . ') Record';
								$this->Crud->activity('dedication', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Child Dedication Created');
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

		if($param1 == 'record'){
			if($param2 == 'get_parent'){
				$church_id = $this->request->getPost('church_id');
				$mother_id = $this->request->getPost('mother_id');
				$father_id = $this->request->getPost('father_id');
				
				$parent = array();
				$mother = '';
				$father = '';
				
				$mothers = $this->Crud->read2_order('church_id', $church_id, 'gender', 'Female', 'user', 'surname', 'asc');
				if(!empty($mothers)){
					$mother .= '<option value="">Select Mother</option>';
					foreach($mothers as $mom){
						$sel = '';
						if(!empty($mother_id)){
							if($mother_id == $mom->id){
								$sel = 'selected';
							}
						}

						$mother .= '<option value="'.$mom->id.'" '.$sel.'>'.ucwords($mom->surname.' '.$mom->firstname).' - '.$mom->phone.'</option>';
					}
				} else {
					$mother .= '<option value="">No Record Found</option>';
				}

				$fathers = $this->Crud->read2_order('church_id', $church_id, 'gender', 'Male', 'user', 'surname', 'asc');
				if(!empty($fathers)){
					$father .= '<option value="">Select Father</option>';
					foreach($fathers as $dad){
						$sel = '';
						if(!empty($father_id)){
							if($father_id == $dad->id){
								$sel = 'selected';
							}
						}

						$father .= '<option value="'.$dad->id.'" '.$sel.'>'.ucwords($dad->surname.' '.$dad->firstname).' - '.$dad->phone.'</option>';
					}
				} else {
					$father .= '<option value="">No Record Found</option>';
				}


				$parent['mother'] = $mother;
				$parent['father'] = $father;
				
				echo json_encode($parent);
				die;
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
				
				$switch_id = $this->session->get('switch_church_id');
				$all_rec = $this->Crud->filter_dedication('', '', $log_id, $search, $switch_id, 1);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_dedication($limit, $offset, $log_id, $search, $switch_id, 1);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$surname = $q->surname;
						$firstname = $q->firstname;
						$othername = $q->othername;
						$gender = $q->gender;
						$ministry = $this->Crud->read_field('id', $q->ministry_id, 'ministry', 'name');
						$church = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
						$father = $this->Crud->read_field('id', $q->father_id, 'user', 'surname').' '.$this->Crud->read_field('id', $q->father_id, 'user', 'firstname');
						$mother = $this->Crud->read_field('id', $q->mother_id, 'user', 'surname').' '.$this->Crud->read_field('id', $q->mother_id, 'user', 'firstname');
						
						$date = date('d/m/Y', strtotime($q->date));
						$dob = date('d/m/Y', strtotime($q->dob));
						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));

						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if (!empty($switch_id)) {
								$all_btn = '
								
								
							';
							} else {
								$all_btn = '
								
							';

							}

						}

						$item .= '
							<tr>
								<td>
									<span class="small text ">' . ($date) .'</span>
								</td>
								<td>
									<span class="small text ">' . $church . '</span>
								</td>
								<td><span class="small text "><b>' . ucwords($surname.' '.$firstname.' '.$othername) . '</b></span></td>
								<td><span class="small text ">' . $dob . '</span></td>
								<td><span class="small text ">' . ucwords($gender) . '</span></td>
								<td><span class="small text ">' . $father . '</span></td>
								<td><span class="small text ">' . $mother . '</span></td>
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
						<i class="ni ni-user-add" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Archive Returned') . '
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
				if ($offset >= 50) {
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

			$data['title'] = translate_phrase('Dedication Archive') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	

}