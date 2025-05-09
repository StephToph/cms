<?php

namespace App\Controllers;

class Accounts extends BaseController {


	/////// ADMINISTRATORS
	public function administrator($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/administrator';

        $log_id = $this->session->get('td_id');
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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
       
        $data['current_language'] = $this->session->get('current_language');
		$table = 'user';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($_POST){
						$del_id = $this->request->getPost('d_user_id');
						$code = $this->Crud->read_field('id', $del_id, 'user', 'fullname');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Deleted'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
							$action = $by.' deleted Administrator ('.$code.')';
							$this->Crud->activity('user', $del_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
						}
						exit;	
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
								$data['e_fullname'] = $e->fullname;
								$data['e_phone'] = $e->phone;
								$data['e_state_id'] = $e->state_id;
								$data['e_lga_id'] = $e->lga_id;
								$data['e_email'] = $e->email;
								$data['e_role_id'] = $e->role_id;
							}
						}
					}
				} 
				
				if($this->request->getMethod() == 'post'){
					$user_id = $this->request->getPost('user_id');
					$fullname = $this->request->getPost('name');
					$phone = $this->request->getPost('phone');
					$email = $this->request->getPost('email');
					$state_id = $this->request->getPost('state_id');
					$lga_id = $this->request->getPost('lga_id');
					$urole_id = $this->request->getPost('role_id');
					$password = $this->request->getPost('password');

					$ins_data['fullname'] = $fullname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['country_id'] = 161;
					$ins_data['state_id'] = 316;
					$ins_data['lga_id'] = $lga_id;
					$ins_data['role_id'] = $urole_id;
					if($password) { $ins_data['password'] = md5($password); }
					
					// do create or update
					if($user_id) {
						$upd_rec = $this->Crud->updates('id', $user_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Updated'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
							$code = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
							$action = $by.' updated Administrator ('.$code.') Record';
							$this->Crud->activity('user', $user_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', translate_phrase('No Changes'));	
						}
					} else {
						if($this->Crud->check('email', $email, $table) > 0 || $this->Crud->check('phone', $phone, $table) > 0) {
							echo $this->Crud->msg('warning', translate_phrase('Email and/or Phone Already Exist'));
						} else {
							$ins_data['activate'] = 1;
							$ins_data['is_staff'] = 1;
							$ins_data['reg_date'] = date(fdate);

							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Record Created'));

								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
								$code = $this->Crud->read_field('id', $ins_rec, 'user', 'fullname');
								$action = $by.' created Administrator ('.$code.')';
								$this->Crud->activity('user', $user_id, $action);

								echo '<script>location.reload(false);</script>';
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
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			if(!empty($this->request->getVar('start_date'))){$start_date = $this->request->getVar('start_date');}else{$start_date = '';}
			if(!empty($this->request->getVar('end_date'))){$end_date = $this->request->getVar('end_date');}else{$end_date = '';}

			if(!empty($this->request->getPost('state_id'))) { $state_id = $this->request->getPost('state_id'); } else { $state_id = ''; }
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			$search = $this->request->getPost('search');

			if(empty($ref_status))$ref_status = 0;
			$items = '
				<div class="nk-tb-item nk-tb-head">
					<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Accounts').'</span></div>
					<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Contact').'</span></div>
					<div class="nk-tb-col tb-col-mb"><span class="sub-text">'.translate_phrase('Address').'</span></div>
					<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Date Joined').'</span></div>
					<div class="nk-tb-col nk-tb-col-tools">
						<ul class="nk-tb-actions gx-1 my-n1">
							
						</ul>
					</div>
				</div><!-- .nk-tb-item -->
		
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$role_id = $this->Crud->read_field('name', 'Administrator', 'access_role', 'id');

				$all_rec = $this->Crud->filter_admins('', '', $log_id, $status, $search, $start_date, $end_date);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_admins($limit, $offset, $log_id, $status, $search, $start_date, $end_date);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$fullname = $q->fullname;
						$email = $q->email;
						$phone = $q->phone;
						$address = $q->address;
						$city = $this->Crud->read_field('id', $q->lga_id, 'city', 'name');
						$country = $this->Crud->read_field('id', $q->country_id, 'country', 'name');
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
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								<li><a href="' . site_url($mod . '/view/' . $id) . '" class="text-success" pageTitle="View ' . $fullname . '" pageName=""><em class="icon ni ni-eye"></em><span>'.translate_phrase('View Details').'</span></a></li>
								
							';
							} else{
								$all_btn = '
								<li><a href="' . site_url($mod . '/view/' . $id) . '" class="text-success" pageTitle="View ' . $fullname . '" pageName=""><em class="icon ni ni-eye"></em><span>'.translate_phrase('View Details').'</span></a></li>
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $fullname . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $fullname . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								
								
							';
							}
							
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<div class="user-card">
										<div class="user-avatar ">
											<img alt="" src="' . site_url($img) . '" height="40px"/>
										</div>
										<div class="user-info">
											<span class="tb-lead">' . ucwords($fullname) . ' <span class="dot dot-' . $a_color . ' ms-1"></span></span>
											<span>' . $email . '</span><br>
											<span>' . $u_role . '</span>
										</div>
									</div>
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark"><b>' . $phone . '</b></span><br>
									'.$referral.'
								</div>
								<div class="nk-tb-col tb-col-mb">
									<span>' . ucwords($address) . '</span><br>
									<span class="text-info">' . $city. '</span>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="tb-amount">' . $reg_date . ' </span>
								</div>
								<div class="nk-tb-col nk-tb-col-tools">
									<ul class="nk-tb-actions gx-1">
										<li>
											<div class="drodown">
												<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
												<div class="dropdown-menu dropdown-menu-end">
													<ul class="link-list-opt no-bdr">
														' . $all_btn . '
													</ul>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div><!-- .nk-tb-item -->
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-users" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Administrator Account Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			// for datatable
			$data['table_rec'] = 'accounts/administrator/list'; // ajax table
			$data['order_sort'] = '0, "asc"'; // default ordering (0, 'asc')
			$data['no_sort'] = '1,6'; // sort disable columns (1,3,5)
		
			$data['title'] = translate_phrase('Administrators').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	/////// ADMINISTRATORS
	public function leadership($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/leadership';

        $log_id = $this->session->get('td_id');
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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
       
        $data['current_language'] = $this->session->get('current_language');
		$table = 'user';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($_POST){
						$del_id = $this->request->getPost('d_user_id');
						$code = $this->Crud->read_field('id', $del_id, 'user', 'fullname');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Deleted'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
							$action = $by.' deleted Administrator ('.$code.')';
							$this->Crud->activity('user', $del_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
						}
						exit;	
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
								$data['e_fullname'] = $e->fullname;
								$data['e_phone'] = $e->phone;
								$data['e_state_id'] = $e->state_id;
								$data['e_lga_id'] = $e->lga_id;
								$data['e_email'] = $e->email;
								$data['e_role_id'] = $e->role_id;
							}
						}
					}
				} 
				
				if($this->request->getMethod() == 'post'){
					$user_id = $this->request->getPost('user_id');
					$fullname = $this->request->getPost('name');
					$phone = $this->request->getPost('phone');
					$email = $this->request->getPost('email');
					$state_id = $this->request->getPost('state_id');
					$lga_id = $this->request->getPost('lga_id');
					$urole_id = $this->request->getPost('role_id');
					$password = $this->request->getPost('password');

					$ins_data['fullname'] = $fullname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['country_id'] = 161;
					$ins_data['state_id'] = 316;
					$ins_data['lga_id'] = $lga_id;
					$ins_data['role_id'] = $urole_id;
					if($password) { $ins_data['password'] = md5($password); }
					
					// do create or update
					if($user_id) {
						$upd_rec = $this->Crud->updates('id', $user_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Updated'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
							$code = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
							$action = $by.' updated Administrator ('.$code.') Record';
							$this->Crud->activity('user', $user_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', translate_phrase('No Changes'));	
						}
					} else {
						if($this->Crud->check('email', $email, $table) > 0 || $this->Crud->check('phone', $phone, $table) > 0) {
							echo $this->Crud->msg('warning', translate_phrase('Email and/or Phone Already Exist'));
						} else {
							$ins_data['activate'] = 1;
							$ins_data['is_staff'] = 1;
							$ins_data['reg_date'] = date(fdate);

							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Record Created'));

								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
								$code = $this->Crud->read_field('id', $ins_rec, 'user', 'fullname');
								$action = $by.' created Administrator ('.$code.')';
								$this->Crud->activity('user', $user_id, $action);

								echo '<script>location.reload(false);</script>';
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
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			if(!empty($this->request->getVar('start_date'))){$start_date = $this->request->getVar('start_date');}else{$start_date = '';}
			if(!empty($this->request->getVar('end_date'))){$end_date = $this->request->getVar('end_date');}else{$end_date = '';}

			if(!empty($this->request->getPost('role_id'))) { $role_id = $this->request->getPost('role_id'); } else { $role_id = ''; }
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			$search = $this->request->getPost('search');

			if(empty($ref_status))$ref_status = 0;
			$items = '
				
		
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$role_id = $this->Crud->read_field('name', 'Administrator', 'access_role', 'id');

				$all_rec = $this->Crud->filter_admins('', '', $log_id, $status, $search, $start_date, $end_date);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_admins($limit, $offset, $log_id, $status, $search, $start_date, $end_date);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$fullname = $q->fullname;
						$email = $q->email;
						$phone = $q->phone;
						$address = $q->address;
						$city = $this->Crud->read_field('id', $q->lga_id, 'city', 'name');
						$country = $this->Crud->read_field('id', $q->country_id, 'country', 'name');
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
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="' . site_url($mod . '/view/' . $id) . '" class="text-success" pageTitle="View ' . $fullname . '" pageName=""><em class="icon ni ni-eye"></em><span>'.translate_phrase('View Details').'</span></a></li>
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $fullname . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $fullname . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								
								
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
											<span class="tb-lead">' . ucwords($fullname) . ' <span class="dot dot-' . $a_color . ' ms-1"></span></span>
											<br>
											<span></span>
										</div>
									</div>
								</td>
								<td><span class="tb-lead small"><span>' . $email . '</span></span> </td>
								<td><span class="tb-lead small">' . $u_role . '</span></td>
								<td><span class="tb-lead small">'.$phone.'</span></td>
								<td><span class="tb-lead small">' . $all_btn . '</span></td>
							</tr>
							
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="8">
					<div class="text-center text-muted col-sm-12">
						<br/><br/>
						<i class="icon ni ni-users " style="font-size:120px;"></i><br/>No Leadership Returned
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Leadership').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	public function timers($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/timers';
		$switch_id = $this->session->get('switch_church_id');
        $log_id = $this->session->get('td_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
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
            $church_id = $switch_id ;
        }
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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
       
        $data['current_language'] = $this->session->get('current_language');
		$table = 'visitors';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($_POST){
						$del_id = $this->request->getPost('d_id');
						$code = $this->Crud->read_field('id', $del_id, 'visitors', 'fullname');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Deleted'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by.' deleted First Timer ('.$code.')';
							$this->Crud->activity('user', $del_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
						}
						exit;	
					}
				}
			} elseif($param2 == 'link'){ 
				if($param3 == 'generate') {
					$churchId = $this->request->getPost('church_id');

					if (!$churchId) {
						return $this->output->set_content_type('application/json')
							->set_output(json_encode(['success' => false, 'message' => 'Church ID required']));
					}
				
					$link = $this->Crud->read_field('id', $churchId, 'church', 'first_timer_link');
					
					if(empty($link)){
						// Generate new unique code (e.g., random 8-character slug)
						$link = $this->Crud->generateUniqueCode();
					
						// Save in database
						$this->Crud->updates('id', $churchId, 'church', ['first_timer_link' => $link]);
					
					}
					
					if ($link) {
						echo json_encode(['success' => true, 'url' => site_url('first-timer/' . $link)]);
					} else {
						echo json_encode(['success' => false, 'message' => 'Failed to generate link']);
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
								$data['e_fullname'] = $e->fullname;
								$data['e_phone'] = $e->phone;
								$data['e_email'] = $e->email;
								$data['e_dob'] = $e->dob;
								$data['e_gender'] = $e->gender;
								$data['e_church_id'] = $e->church_id;
								$data['e_title'] = $e->title;
								$data['e_is_member'] = $e->is_member;
								$data['e_is_visitor'] = $e->is_visitor;
								$data['e_follow_status'] = $e->follow_status;
								$data['e_assigned_to'] = $e->assigned_to;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_foundation_weeks'] = $e->foundation_weeks;
							}
						}
					}
				} 

				if($param2 == 'view') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_fullname'] = $e->fullname;
								$data['e_phone'] = $e->phone;
								$data['e_email'] = $e->email;
								$data['e_dob'] = $e->dob;
								$data['e_gender'] = $e->gender;
								$data['e_church_id'] = $e->church_id;
								$data['e_title'] = $e->title;
								$data['e_is_member'] = $e->is_member;
								$data['e_follow_status'] = $e->follow_status;
								$data['e_user_no'] = $e->user_no;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_id'] = $e->church_id;
								$data['e_source_type'] = $e->source_type;
								$data['e_source_id'] = $e->source_id;
								$data['e_invited_by'] = $e->invited_by;
								$data['e_channel'] = $e->channel;
								$data['e_assigned_to'] = $e->assigned_to;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_foundation_weeks'] = $e->foundation_weeks;
								$data['e_visit_date'] = $e->visit_date;
								$data['e_reg_date'] = $e->reg_date;
							}
						}
					}
				} 
				
				if($this->request->getMethod() == 'post'){
					$edit_id = $this->request->getPost('edit_id');
					$fullname = $this->request->getPost('fullname');
					$phone = $this->request->getPost('phone');
					$email = $this->request->getPost('email');
					$gender = $this->request->getPost('gender');
					$dob = $this->request->getPost('dob');
					$title = $this->request->getPost('title');
					$foundation_school = $this->request->getPost('foundation_school');
					$foundation_weeks = $this->request->getPost('foundation_weeks');
					$is_member = $this->request->getPost('is_member');
					$follow_status = $this->request->getPost('follow_status');
					$is_visitor = $this->request->getPost('is_visitor');
					$is_assign = $this->request->getPost('is_assign');
					$assigned_to = $this->request->getPost('assigned_to');

					if(empty($is_assign)){
						$assigned_to = [];
					}

					if($is_member){
						$nameParts = explode(' ', trim($fullname));
						$firstname = isset($nameParts[0]) ? $nameParts[0] : ''; // First part is the first name
						$lastname = isset($nameParts[1]) ? $nameParts[1] : ''; 

						$role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
						$source_type = $this->Crud->read_field('id', $edit_id, 'visitors', 'source_type');
						$source_id = $this->Crud->read_field('id', $edit_id, 'visitors', 'source_id');
						$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
						if($source_type == 'cell'){
							$mem_data['cell_id'] = $cell_id;
						}
						$mem_data['title'] = $title;
						$mem_data['firstname'] = $firstname;
						$mem_data['surname'] = $lastname;
						$mem_data['email'] = $email;
						$mem_data['phone'] = $phone;
						$mem_data['gender'] = $gender;
						$mem_data['foundation_school'] = $foundation_school;
						$mem_data['dob'] = $dob;
						$mem_data['ministry_id'] = $this->Crud->read_field('id', $edit_id, 'visitors', 'ministry_id');
						$mem_data['church_id'] = $this->Crud->read_field('id', $edit_id, 'visitors', 'church_id');
						$mem_data['is_member'] = 1;
						$mem_data['role_id'] = $role_id;
						$user_nos =  $this->Crud->read_field('id', $edit_id, 'visitors', 'user_no');
						$mem_data['activate'] = 1;
						$mem_data['reg_date'] = date(fdate);
						if(empty($user_nos)){
							$mem_rec = $this->Crud->create('user', $mem_data);
						}
						if($mem_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $mem_rec, 'user', 'surname');
							$this->Crud->updates('id', $mem_rec, 'user', array('user_no'=>'CEAM-00'.$mem_rec));
							$password = '12345';
							$user_no = 'CEAM-00'.$mem_rec;

							$action = $by.' created Membership ('.$code.') Record';
							$this->Crud->activity('user', $mem_rec, $action);
							$name = ucwords($firstname.' '.$lastname);
							$body = '
								Dear '.$name.', <br><br>
									A Membership Account Has been Created with This Email on Chrsit Embassy  Platform;<br>
									Below are your login Credentials:<br><br>

									Website: '.site_url().'
									Membership ID: '.$user_no.'<br>
									Email: '.$email.'<br>
									Phone: '.$phone.'<br>
									Password: '.$password.'<br><br>
									Do not disclose your Login credentials with anyone to avoid unauthorized access.
									
							';
							$this->Crud->send_email($email, 'Membership Account', $body);
							$ins_data['user_no'] = $user_no;
						} 	
					}
					$ins_data['fullname'] = $fullname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['title'] = $title;
					$ins_data['gender'] = $gender;
					$ins_data['dob'] = $dob;
					$ins_data['foundation_school'] = $foundation_school;
					$ins_data['foundation_weeks'] = $foundation_weeks;
					$ins_data['assigned_to'] = json_encode($assigned_to);
					$ins_data['is_member'] = $is_member;
					$ins_data['is_visitor'] = $is_visitor;
					$ins_data['follow_status'] = $follow_status;

					// do create or update
					if($edit_id) {
						$upd_rec = $this->Crud->updates('id', $edit_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('First Timer Record Updated'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $edit_id, 'visitors', 'fullname');
							$action = $by.' updated First Timer ('.$code.') Record';
							$this->Crud->activity('first_timer', $edit_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', translate_phrase('No Changes'));	
						}
					} 
					exit;	
				}
			}
		}
		

		// record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			if(!empty($this->request->getVar('start_date'))){$start_date = $this->request->getVar('start_date');}else{$start_date = '';}
			if(!empty($this->request->getVar('end_date'))){$end_date = $this->request->getVar('end_date');}else{$end_date = '';}

			if(!empty($this->request->getPost('role_id'))) { $role_id = $this->request->getPost('role_id'); } else { $role_id = ''; }
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			$search = $this->request->getPost('search');

			if(empty($ref_status))$ref_status = 0;
			$items = ' ';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$role_id = $this->Crud->read_field('name', 'Administrator', 'access_role', 'id');

				$all_rec = $this->Crud->filter_visitors('', '', $log_id, $search, 'first_timer', 0, 0, 0, $switch_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_visitors($limit, $offset, $log_id, $search, 'first_timer', 0, 0, 0, $switch_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$fullname = $q->fullname;
						$email = $q->email;
						$phone = $q->phone;
						$invited_by = $q->invited_by;
						$source_type = $q->source_type;
						$source_id = $q->source_id;
						$invited_by = $q->invited_by;
						$assigned_to = $q->assigned_to;
						$is_member = $q->is_member;
						$foundation_school = $q->foundation_school;
						$channel = $q->channel;
						$church = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
						$ministry = $this->Crud->read_field('id', $q->ministry_id, 'ministry', 'name');
						
						if($church_id > 0){
							$church = '';
						}
						if($source_type == 'cell'){
							$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
							$cell = '-'.$this->Crud->read_field('id', $cell_id, 'cells', 'name');
							$type = $this->Crud->read_field('id', $source_id, 'cell_report', 'type');
							$source = '';
							if($type == 'wk1')$source = 'WK1 - Prayer and Planning';
							if($type == 'wk2')$source = 'Wk2 - Bible Study';
							if($type == 'wk3')$source = 'Wk3 - Bible Study';
							if($type == 'wk4')$source = 'Wk4 - Fellowship / Outreach';
							if($type == 'wk5')$source = 'Wk5 - Fellowship';
							
						}

						$foundation_btn = '';
						if($foundation_school == 0){
							$foundation_btn = '<li><a href="javascript:;" class="text-secondary pop" pageTitle="Enroll ' . $fullname . ' to Foundation School" pageName="' . site_url($mod . '/manage/enroll/' . $id) . '"><em class="icon ni ni-user-list"></em><span>'.translate_phrase('Enroll to Foundation School').'</span></a></li>
								';
						}

						$follow_up = $this->Crud->check('visitor_id', $id, 'follow_up');

						if($source_type == 'service'){
							$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
							$cell = '';
							$type = $this->Crud->read_field('id', $source_id, 'service_report', 'type');
							$source = $this->Crud->read_field('id', $type, 'service_type', 'name');
							
						}

						if($invited_by == 'Member'){
							$channel = $this->Crud->read_field('id', $q->channel, 'user', 'firstname').' '.$this->Crud->read_field('id', $q->channel, 'user', 'surname');
						}
						$reg_date = date('M d, Y h:ia', strtotime($q->reg_date));
						$visit_date = date('M d, Y', strtotime($q->visit_date));
						if(empty($q->visit_date)){
							$visit_date = 'Not Visited';
						}

						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $fullname . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-success pop" pageTitle="View ' . $fullname . '" pageName="' . site_url($mod . '/manage/view/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $fullname . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" onclick="follow_up(' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Follow Up') . '</span></a></li>
							';
						}

						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-info">
											<span class="tb-lead">' . ucwords($fullname) . ' </span><br>
											<span class="small ">' . ucwords(strtolower($church)) . ' <b>'.$cell.'</b> </span>
										</div>
									</div>
								</td>
								<td><span class="tb-lead small">' . $email . '</span><br><span class="tb-lead small">' . $phone . '</span> </td>
								<td><span class="tb-lead small">' . ucwords($source_type) . '</span><br><span class="tb-lead small">' . ucwords($source) . '</span></td>
								<td><span class="tb-lead small">' . $invited_by . '</span><br><span class="tb-lead small">' . ucwords($channel) . '</span></td>
								<td><span class="tb-lead small">' . $follow_up . '</span></td>
								<td><span class="tb-lead small">' . $visit_date . '</span></td>
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
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="8">
					<div class="text-center text-muted col-sm-12">
						<br/><br/>
						<i class="icon ni ni-users " style="font-size:120px;"></i><br/>No Leadership Returned
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('First Timers').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	
	public function monitoring($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/monitoring';
		$switch_id = $this->session->get('switch_church_id');
        $log_id = $this->session->get('td_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
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
            $church_id = $switch_id ;
        }
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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
       
        $data['current_language'] = $this->session->get('current_language');
		$table = 'user';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'remove') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($_POST){
						$del_id = $this->request->getPost('d_id');
						$code = $this->Crud->read_field('id', $del_id, 'user', 'firstname');
						if($this->Crud->updates('id', $del_id, $table, ['is_monitoring'=>0]) > 0) {
							echo $this->Crud->msg('success', translate_phrase('Monitor Removed'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by.' removed Attendance Monitor ('.$code.')';
							$this->Crud->activity('user', $del_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
						}
						exit;	
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
								
							}
						}
					}
				} 

				if ($this->request->getMethod() === 'post') {
					$edit_id = $this->request->getPost('edit_id'); // visitor ID
					$monitor_ids = $this->request->getPost('monitor'); // array of member IDs
				
					$ins_data['is_monitoring'] = 1;
				
					if (is_array($monitor_ids)) {
						foreach ($monitor_ids as $member_id) {
							$upd_rec = $this->Crud->updates('id', $member_id, 'user', $ins_data);
							// Log activity
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by . ' is made an Attendance Monitor';
							$this->Crud->activity('user', $edit_id, $action);
		
						}
					}
						
					if($upd_rec > 0) {
						echo $this->Crud->msg('success', translate_phrase('Attendance Monitoring Record Updated'));
						echo '<script>location.reload(false);</script>';
					} else {
						echo $this->Crud->msg('info', translate_phrase('No Changes'));
					}
					
				
					exit;
				}
				
				
			}
		}

		// record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');

			if(empty($ref_status))$ref_status = 0;
			$items = ' ';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$role_id = $this->Crud->read_field('name', 'Administrator', 'access_role', 'id');

				$all_rec = $this->Crud->filter_monitoring('', '', $log_id, $search, $switch_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_monitoring($limit, $offset, $log_id, $search, $switch_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$firstname = $q->firstname;
						$othername = $q->othername;
						$surname = $q->surname;
						$is_monitoring = $q->is_monitoring;
						$church = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
						
						
						$fullname = $firstname.' '.$othername.' '.$surname;
						$reg_date = date('M d, Y h:ia', strtotime($q->reg_date));
						
						

						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<a href="javascript:;" class="btn btn-primary pop" pageTitle="Remove ' . $fullname . '" pageName="' . site_url($mod . '/manage/remove/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Remove from Monitoring').'</span></a>
							';
						}

						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-info">
											<span class="tb-lead">' . ucwords($fullname) . ' </span>
										</div>
									</div>
								</td>
								<td>' . ucwords(strtolower($church)) . '</td>
								<td align="right">
									' . $all_btn . '
										
								</td>
							</tr>
							
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="8">
					<div class="text-center text-muted col-sm-12">
						<br/><br/>
						<i class="icon ni ni-users " style="font-size:120px;"></i><br/>No Monitoring Returned
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Attendance Monitoring').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	
	public function converts($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/converts';
		$switch_id = $this->session->get('switch_church_id');
        $log_id = $this->session->get('td_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
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
            $church_id = $switch_id ;
        }
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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
       
        $data['current_language'] = $this->session->get('current_language');
		$table = 'visitors';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($_POST){
						$del_id = $this->request->getPost('d_id');
						$code = $this->Crud->read_field('id', $del_id, 'visitors', 'fullname');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Deleted'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by.' deleted New Convert ('.$code.')';
							$this->Crud->activity('user', $del_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
						}
						exit;	
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
								$data['e_fullname'] = $e->fullname;
								$data['e_phone'] = $e->phone;
								$data['e_email'] = $e->email;
								$data['e_dob'] = $e->dob;
								$data['e_gender'] = $e->gender;
								$data['e_church_id'] = $e->church_id;
								$data['e_title'] = $e->title;
								$data['e_is_member'] = $e->is_member;
								$data['e_is_visitor'] = $e->is_visitor;
								$data['e_follow_status'] = $e->follow_status;
								$data['e_assigned_to'] = $e->assigned_to;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_foundation_weeks'] = $e->foundation_weeks;
							}
						}
					}
				} 

				if($param2 == 'view') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_fullname'] = $e->fullname;
								$data['e_phone'] = $e->phone;
								$data['e_email'] = $e->email;
								$data['e_dob'] = $e->dob;
								$data['e_gender'] = $e->gender;
								$data['e_church_id'] = $e->church_id;
								$data['e_title'] = $e->title;
								$data['e_is_member'] = $e->is_member;
								$data['e_is_visitor'] = $e->is_visitor;
								$data['e_follow_status'] = $e->follow_status;
								$data['e_user_no'] = $e->user_no;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_id'] = $e->church_id;
								$data['e_source_type'] = $e->source_type;
								$data['e_source_id'] = $e->source_id;
								$data['e_invited_by'] = $e->invited_by;
								$data['e_channel'] = $e->channel;
								$data['e_assigned_to'] = $e->assigned_to;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_foundation_weeks'] = $e->foundation_weeks;
								$data['e_visit_date'] = $e->visit_date;
								$data['e_reg_date'] = $e->reg_date;
							}
						}
					}
				} 
				
				if($this->request->getMethod() == 'post'){
					$edit_id = $this->request->getPost('edit_id');
					$fullname = $this->request->getPost('fullname');
					$phone = $this->request->getPost('phone');
					$email = $this->request->getPost('email');
					$gender = $this->request->getPost('gender');
					$dob = $this->request->getPost('dob');
					$title = $this->request->getPost('title');
					$foundation_school = $this->request->getPost('foundation_school');
					$foundation_weeks = $this->request->getPost('foundation_weeks');
					$is_visitor = $this->request->getPost('is_visitor');
					$is_member = $this->request->getPost('is_member');
					$is_assign = $this->request->getPost('is_assign');
					$follow_status = $this->request->getPost('follow_status');
					$assigned_to = $this->request->getPost('assigned_to');

					if(empty($is_assign)){
						$assigned_to = [];
					}

					if($is_member){
						$nameParts = explode(' ', trim($fullname));
						$firstname = isset($nameParts[0]) ? $nameParts[0] : ''; // First part is the first name
						$lastname = isset($nameParts[1]) ? $nameParts[1] : ''; 

						$role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
						$source_type = $this->Crud->read_field('id', $edit_id, 'visitors', 'source_type');
						$source_id = $this->Crud->read_field('id', $edit_id, 'visitors', 'source_id');
						$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
						if($source_type == 'cell'){
							$mem_data['cell_id'] = $cell_id;
						}
						$mem_data['title'] = $title;
						$mem_data['firstname'] = $firstname;
						$mem_data['surname'] = $lastname;
						$mem_data['email'] = $email;
						$mem_data['phone'] = $phone;
						$mem_data['gender'] = $gender;
						$mem_data['foundation_school'] = $foundation_school;
						$mem_data['dob'] = $dob;
						$mem_data['ministry_id'] = $this->Crud->read_field('id', $edit_id, 'visitors', 'ministry_id');
						$mem_data['church_id'] = $this->Crud->read_field('id', $edit_id, 'visitors', 'church_id');
						$mem_data['is_member'] = 1;
						$mem_data['role_id'] = $role_id;
						$user_nos =  $this->Crud->read_field('id', $edit_id, 'visitors', 'user_no');
						$mem_data['activate'] = 1;
						$mem_data['reg_date'] = date(fdate);
						if(empty($user_nos)){
							$mem_rec = $this->Crud->create('user', $mem_data);
						}
						if($mem_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $mem_rec, 'user', 'surname');
							$this->Crud->updates('id', $mem_rec, 'user', array('user_no'=>'CEAM-00'.$mem_rec));
							$password = '12345';
							$user_no = 'CEAM-00'.$mem_rec;

							$action = $by.' created Membership ('.$code.') Record';
							$this->Crud->activity('user', $mem_rec, $action);
							$name = ucwords($firstname.' '.$lastname);
							$body = '
								Dear '.$name.', <br><br>
									A Membership Account Has been Created with This Email on Chrsit Embassy  Platform;<br>
									Below are your login Credentials:<br><br>

									Website: '.site_url().'
									Membership ID: '.$user_no.'<br>
									Email: '.$email.'<br>
									Phone: '.$phone.'<br>
									Password: '.$password.'<br><br>
									Do not disclose your Login credentials with anyone to avoid unauthorized access.
									
							';
							$this->Crud->send_email($email, 'Membership Account', $body);
							$ins_data['user_no'] = $user_no;
						} 	
					}
					$ins_data['fullname'] = $fullname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['title'] = $title;
					$ins_data['gender'] = $gender;
					$ins_data['dob'] = $dob;
					$ins_data['foundation_school'] = $foundation_school;
					$ins_data['foundation_weeks'] = $foundation_weeks;
					$ins_data['assigned_to'] = json_encode($assigned_to);
					$ins_data['is_member'] = $is_member;
					$ins_data['is_visitor'] = $is_visitor;
					$ins_data['follow_status'] = $follow_status;

					// do create or update
					if($edit_id) {
						$upd_rec = $this->Crud->updates('id', $edit_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('New Convert Record Updated'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $edit_id, 'visitors', 'fullname');
							$action = $by.' updated New Convert ('.$code.') Record';
							$this->Crud->activity('first_timer', $edit_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', translate_phrase('No Changes'));	
						}
					} 
					exit;	
				}
			}
		}
		

		// record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			if(!empty($this->request->getVar('start_date'))){$start_date = $this->request->getVar('start_date');}else{$start_date = '';}
			if(!empty($this->request->getVar('end_date'))){$end_date = $this->request->getVar('end_date');}else{$end_date = '';}

			if(!empty($this->request->getPost('role_id'))) { $role_id = $this->request->getPost('role_id'); } else { $role_id = ''; }
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			$search = $this->request->getPost('search');

			if(empty($ref_status))$ref_status = 0;
			$items = ' ';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$role_id = $this->Crud->read_field('name', 'Administrator', 'access_role', 'id');

				$all_rec = $this->Crud->filter_visitors('', '', $log_id, $search, 'new_convert', 0, 0, 0, $switch_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_visitors($limit, $offset, $log_id, $search, 'new_convert', 0, 0, 0, $switch_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$fullname = $q->fullname;
						$email = $q->email;
						$phone = $q->phone;
						$invited_by = $q->invited_by;
						$source_type = $q->source_type;
						$source_id = $q->source_id;
						$invited_by = $q->invited_by;
						$assigned_to = $q->assigned_to;
						$is_member = $q->is_member;
						$foundation_school = $q->foundation_school;
						$channel = $q->channel;
						$church = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
						$ministry = $this->Crud->read_field('id', $q->ministry_id, 'ministry', 'name');
						
						$foundation_btn = '';
						if($foundation_school == 0){
							$foundation_btn = '<li><a href="javascript:;" class="text-secondary pop" pageTitle="Enroll ' . $fullname . ' to Foundation School" pageName="' . site_url($mod . '/manage/enroll/' . $id) . '"><em class="icon ni ni-user-list"></em><span>'.translate_phrase('Enroll to Foundation School').'</span></a></li>
								';
						}

						if($church_id > 0){
							$church = '';
						}
						if($source_type == 'cell'){
							$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
							$cell = '-'.$this->Crud->read_field('id', $cell_id, 'cells', 'name');
							$type = $this->Crud->read_field('id', $source_id, 'cell_report', 'type');
							$source = '';
							if($type == 'wk1')$source = 'WK1 - Prayer and Planning';
							if($type == 'wk2')$source = 'Wk2 - Bible Study';
							if($type == 'wk3')$source = 'Wk3 - Bible Study';
							if($type == 'wk4')$source = 'Wk4 - Fellowship / Outreach';
							if($type == 'wk5')$source = 'Wk5 - Fellowship';
							
						}

						$follow_up = $this->Crud->check('visitor_id', $id, 'follow_up');

						if($source_type == 'service'){
							$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
							$cell = '';
							$type = $this->Crud->read_field('id', $source_id, 'service_report', 'type');
							$source = $this->Crud->read_field('id', $type, 'service_type', 'name');
							
						}

						if($invited_by == 'Member'){
							$channel = $this->Crud->read_field('id', $q->channel, 'user', 'firstname').' '.$this->Crud->read_field('id', $q->channel, 'user', 'surname');
						}
						$reg_date = date('M d, Y h:ia', strtotime($q->reg_date));
						$visit_date = date('M d, Y', strtotime($q->visit_date));


						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $fullname . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-success pop" pageTitle="View ' . $fullname . '" pageName="' . site_url($mod . '/manage/view/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $fullname . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" onclick="follow_up(' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Follow Up') . '</span></a></li>
								
								
							';
						}

						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-info">
											<span class="tb-lead">' . ucwords($fullname) . ' </span><br>
											<span class="small ">' . ucwords($church) . ' <b>'.$cell.'</b> </span>
										</div>
									</div>
								</td>
								<td><span class="tb-lead small">' . $email . '</span><br><span class="tb-lead small">' . $phone . '</span> </td>
								<td><span class="tb-lead small">' . ucwords($source_type) . '</span><br><span class="tb-lead small">' . ucwords($source) . '</span></td>
								<td><span class="tb-lead small">' . $invited_by . '</span><br><span class="tb-lead small">' . ucwords($channel) . '</span></td>
								<td><span class="tb-lead small">' . $follow_up . '</span></td>
								<td><span class="tb-lead small">' . $visit_date . '</span></td>
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
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="8">
					<div class="text-center text-muted col-sm-12">
						<br/><br/>
						<i class="icon ni ni-users " style="font-size:120px;"></i><br/>No New Convert Returned
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('New Convert').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	
	public function visitors($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/visitors';
		$switch_id = $this->session->get('switch_church_id');
        $log_id = $this->session->get('td_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
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
            $church_id = $switch_id ;
        }
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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
       
        $data['current_language'] = $this->session->get('current_language');
		$table = 'visitors';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($_POST){
						$del_id = $this->request->getPost('d_id');
						$code = $this->Crud->read_field('id', $del_id, 'visitors', 'fullname');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Deleted'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by.' deleted New Convert ('.$code.')';
							$this->Crud->activity('user', $del_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
						}
						exit;	
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
								$data['e_fullname'] = $e->fullname;
								$data['e_phone'] = $e->phone;
								$data['e_email'] = $e->email;
								$data['e_dob'] = $e->dob;
								$data['e_gender'] = $e->gender;
								$data['e_church_id'] = $e->church_id;
								$data['e_title'] = $e->title;
								$data['e_is_member'] = $e->is_member;
								$data['e_assigned_to'] = $e->assigned_to;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_foundation_weeks'] = $e->foundation_weeks;
							}
						}
					}
				} 

				if($param2 == 'view') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_fullname'] = $e->fullname;
								$data['e_phone'] = $e->phone;
								$data['e_email'] = $e->email;
								$data['e_dob'] = $e->dob;
								$data['e_gender'] = $e->gender;
								$data['e_church_id'] = $e->church_id;
								$data['e_title'] = $e->title;
								$data['e_is_member'] = $e->is_member;
								$data['e_user_no'] = $e->user_no;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_id'] = $e->church_id;
								$data['e_source_type'] = $e->source_type;
								$data['e_source_id'] = $e->source_id;
								$data['e_invited_by'] = $e->invited_by;
								$data['e_channel'] = $e->channel;
								$data['e_assigned_to'] = $e->assigned_to;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_foundation_weeks'] = $e->foundation_weeks;
								$data['e_visit_date'] = $e->visit_date;
								$data['e_reg_date'] = $e->reg_date;
							}
						}
					}
				} 
				
				if($this->request->getMethod() == 'post'){
					$edit_id = $this->request->getPost('edit_id');
					$fullname = $this->request->getPost('fullname');
					$phone = $this->request->getPost('phone');
					$email = $this->request->getPost('email');
					$gender = $this->request->getPost('gender');
					$dob = $this->request->getPost('dob');
					$title = $this->request->getPost('title');
					$foundation_school = $this->request->getPost('foundation_school');
					$foundation_weeks = $this->request->getPost('foundation_weeks');
					$is_member = $this->request->getPost('is_member');
					$is_assign = $this->request->getPost('is_assign');
					$assigned_to = $this->request->getPost('assigned_to');

					if(empty($is_assign)){
						$assigned_to = [];
					}

					if($is_member){
						$nameParts = explode(' ', trim($fullname));
						$firstname = isset($nameParts[0]) ? $nameParts[0] : ''; // First part is the first name
						$lastname = isset($nameParts[1]) ? $nameParts[1] : ''; 

						$role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
						$source_type = $this->Crud->read_field('id', $edit_id, 'visitors', 'source_type');
						$source_id = $this->Crud->read_field('id', $edit_id, 'visitors', 'source_id');
						$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
						if($source_type == 'cell'){
							$mem_data['cell_id'] = $cell_id;
						}
						$mem_data['title'] = $title;
						$mem_data['firstname'] = $firstname;
						$mem_data['surname'] = $lastname;
						$mem_data['email'] = $email;
						$mem_data['phone'] = $phone;
						$mem_data['gender'] = $gender;
						$mem_data['foundation_school'] = $foundation_school;
						$mem_data['dob'] = $dob;
						$mem_data['ministry_id'] = $this->Crud->read_field('id', $edit_id, 'visitors', 'ministry_id');
						$mem_data['church_id'] = $this->Crud->read_field('id', $edit_id, 'visitors', 'church_id');
						$mem_data['is_member'] = 1;
						$mem_data['role_id'] = $role_id;
						$user_nos =  $this->Crud->read_field('id', $edit_id, 'visitors', 'user_no');
						$mem_data['activate'] = 1;
						$mem_data['reg_date'] = date(fdate);
						if(empty($user_nos)){
							$mem_rec = $this->Crud->create('user', $mem_data);
						}
						if($mem_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $mem_rec, 'user', 'surname');
							$this->Crud->updates('id', $mem_rec, 'user', array('user_no'=>'CEAM-00'.$mem_rec));
							$password = '12345';
							$user_no = 'CEAM-00'.$mem_rec;

							$action = $by.' created Membership ('.$code.') Record';
							$this->Crud->activity('user', $mem_rec, $action);
							$name = ucwords($firstname.' '.$lastname);
							$body = '
								Dear '.$name.', <br><br>
									A Membership Account Has been Created with This Email on Chrsit Embassy  Platform;<br>
									Below are your login Credentials:<br><br>

									Website: '.site_url().'
									Membership ID: '.$user_no.'<br>
									Email: '.$email.'<br>
									Phone: '.$phone.'<br>
									Password: '.$password.'<br><br>
									Do not disclose your Login credentials with anyone to avoid unauthorized access.
									
							';
							$this->Crud->send_email($email, 'Membership Account', $body);
							$ins_data['user_no'] = $user_no;
						} 	
					}
					$ins_data['fullname'] = $fullname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['title'] = $title;
					$ins_data['gender'] = $gender;
					$ins_data['dob'] = $dob;
					$ins_data['foundation_school'] = $foundation_school;
					$ins_data['foundation_weeks'] = $foundation_weeks;
					$ins_data['assigned_to'] = json_encode($assigned_to);
					$ins_data['is_member'] = $is_member;

					// do create or update
					if($edit_id) {
						$upd_rec = $this->Crud->updates('id', $edit_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('New Convert Record Updated'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $edit_id, 'visitors', 'fullname');
							$action = $by.' updated New Convert ('.$code.') Record';
							$this->Crud->activity('first_timer', $edit_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', translate_phrase('No Changes'));	
						}
					} 
					exit;	
				}
			}
		}
		

		// record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			if(!empty($this->request->getVar('start_date'))){$start_date = $this->request->getVar('start_date');}else{$start_date = '';}
			if(!empty($this->request->getVar('end_date'))){$end_date = $this->request->getVar('end_date');}else{$end_date = '';}

			if(!empty($this->request->getPost('role_id'))) { $role_id = $this->request->getPost('role_id'); } else { $role_id = ''; }
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			$search = $this->request->getPost('search');

			if(empty($ref_status))$ref_status = 0;
			$items = ' ';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$role_id = $this->Crud->read_field('name', 'Administrator', 'access_role', 'id');

				$all_rec = $this->Crud->filter_visitors('', '', $log_id, $search, '', '', 1, $switch_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_visitors($limit, $offset, $log_id, $search, '', '', 1, $switch_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$fullname = $q->fullname;
						$email = $q->email;
						$phone = $q->phone;
						$invited_by = $q->invited_by;
						$source_type = $q->source_type;
						$source_id = $q->source_id;
						$invited_by = $q->invited_by;
						$assigned_to = $q->assigned_to;
						$is_member = $q->is_member;
						$is_visitor = $q->is_visitor;
						$category = ucwords(str_replace('_', ' ', $q->category));
						$foundation_school = $q->foundation_school;
						$channel = $q->channel;
						$church = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
						$ministry = $this->Crud->read_field('id', $q->ministry_id, 'ministry', 'name');
						
						if($church_id > 0){
							$church = '';
						}
						if($source_type == 'cell'){
							$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
							$cell = '-'.$this->Crud->read_field('id', $cell_id, 'cells', 'name');
							$type = $this->Crud->read_field('id', $source_id, 'cell_report', 'type');
							$source = '';
							if($type == 'wk1')$source = 'WK1 - Prayer and Planning';
							if($type == 'wk2')$source = 'Wk2 - Bible Study';
							if($type == 'wk3')$source = 'Wk3 - Bible Study';
							if($type == 'wk4')$source = 'Wk4 - Fellowship / Outreach';
							if($type == 'wk5')$source = 'Wk5 - Fellowship';
							
						}

						

						$follow_up = $this->Crud->check('visitor_id', $id, 'follow_up');
						$cell = '';
						if($source_type == 'service'){
							$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
							$cell = '';
							$type = $this->Crud->read_field('id', $source_id, 'service_report', 'type');
							$source = $this->Crud->read_field('id', $type, 'service_type', 'name');
							
						}

						if($invited_by == 'Member'){
							$channel = $this->Crud->read_field('id', $q->channel, 'user', 'firstname').' '.$this->Crud->read_field('id', $q->channel, 'user', 'surname');
						}
						$reg_date = date('M d, Y h:ia', strtotime($q->reg_date));
						$visit_date = date('M d, Y', strtotime($q->visit_date));

						$status = '';
						if($is_member){
							$status = 'Member';
						}
						if($is_visitor){
							$status = 'Visitor';
						}

						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-success pop" pageTitle="View ' . $fullname . '" pageName="' . site_url($mod . '/manage/view/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								
								
							';
						}

						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-info">
											<span class="tb-lead">' . ucwords($fullname) . ' </span><br>
											<span class="small">' . ucwords(strtolower($church)) . ' <b>'.$cell.'</b> </span>
										</div>
									</div>
								</td>
								<td><span class="tb-lead small">' . $email . '</span><br><span class="tb-lead small">' . $phone . '</span> </td>
								<td><span class="tb-lead small">' . ucwords($source_type) . '</span><br><span class="tb-lead small">' . ucwords($source) . '</span></td>
								<td><span class="tb-lead small">' . $invited_by . '</span><br><span class="tb-lead small">' . ucwords($channel) . '</span></td>
								<td><span class="tb-lead small">' . $follow_up . '</span></td>
								<td><span class="tb-lead small">' . $category . '</span><br><span class="badge rounded-pill bg-outline-primary">'.$status.'</span></td>
								<td><span class="tb-lead small">' . $visit_date . '</span></td>
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
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="8">
					<div class="text-center text-muted col-sm-12">
						<br/><br/>
						<i class="icon ni ni-users " style="font-size:120px;"></i><br/>No Visitors Returned
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Visitors').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	public function archive($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/archive';
		$switch_id = $this->session->get('switch_church_id');
        $log_id = $this->session->get('td_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
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
            $church_id = $switch_id ;
        }
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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
       
        $data['current_language'] = $this->session->get('current_language');
		$table = 'visitors';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($_POST){
						$del_id = $this->request->getPost('d_id');
						$code = $this->Crud->read_field('id', $del_id, 'visitors', 'fullname');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Deleted'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by.' deleted New Convert ('.$code.')';
							$this->Crud->activity('user', $del_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
						}
						exit;	
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
								$data['e_fullname'] = $e->fullname;
								$data['e_phone'] = $e->phone;
								$data['e_email'] = $e->email;
								$data['e_dob'] = $e->dob;
								$data['e_gender'] = $e->gender;
								$data['e_church_id'] = $e->church_id;
								$data['e_title'] = $e->title;
								$data['e_is_member'] = $e->is_member;
								$data['e_assigned_to'] = $e->assigned_to;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_foundation_weeks'] = $e->foundation_weeks;
							}
						}
					}
				} 

				if($param2 == 'view') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_fullname'] = $e->fullname;
								$data['e_phone'] = $e->phone;
								$data['e_email'] = $e->email;
								$data['e_dob'] = $e->dob;
								$data['e_gender'] = $e->gender;
								$data['e_church_id'] = $e->church_id;
								$data['e_title'] = $e->title;
								$data['e_is_member'] = $e->is_member;
								$data['e_user_no'] = $e->user_no;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_id'] = $e->church_id;
								$data['e_source_type'] = $e->source_type;
								$data['e_source_id'] = $e->source_id;
								$data['e_invited_by'] = $e->invited_by;
								$data['e_channel'] = $e->channel;
								$data['e_assigned_to'] = $e->assigned_to;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_foundation_weeks'] = $e->foundation_weeks;
								$data['e_visit_date'] = $e->visit_date;
								$data['e_reg_date'] = $e->reg_date;
							}
						}
					}
				} 
				
				if($this->request->getMethod() == 'post'){
					$edit_id = $this->request->getPost('edit_id');
					$fullname = $this->request->getPost('fullname');
					$phone = $this->request->getPost('phone');
					$email = $this->request->getPost('email');
					$gender = $this->request->getPost('gender');
					$dob = $this->request->getPost('dob');
					$title = $this->request->getPost('title');
					$foundation_school = $this->request->getPost('foundation_school');
					$foundation_weeks = $this->request->getPost('foundation_weeks');
					$is_member = $this->request->getPost('is_member');
					$is_assign = $this->request->getPost('is_assign');
					$assigned_to = $this->request->getPost('assigned_to');

					if(empty($is_assign)){
						$assigned_to = [];
					}

					if($is_member){
						$nameParts = explode(' ', trim($fullname));
						$firstname = isset($nameParts[0]) ? $nameParts[0] : ''; // First part is the first name
						$lastname = isset($nameParts[1]) ? $nameParts[1] : ''; 

						$role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
						$source_type = $this->Crud->read_field('id', $edit_id, 'visitors', 'source_type');
						$source_id = $this->Crud->read_field('id', $edit_id, 'visitors', 'source_id');
						$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
						if($source_type == 'cell'){
							$mem_data['cell_id'] = $cell_id;
						}
						$mem_data['title'] = $title;
						$mem_data['firstname'] = $firstname;
						$mem_data['surname'] = $lastname;
						$mem_data['email'] = $email;
						$mem_data['phone'] = $phone;
						$mem_data['gender'] = $gender;
						$mem_data['foundation_school'] = $foundation_school;
						$mem_data['dob'] = $dob;
						$mem_data['ministry_id'] = $this->Crud->read_field('id', $edit_id, 'visitors', 'ministry_id');
						$mem_data['church_id'] = $this->Crud->read_field('id', $edit_id, 'visitors', 'church_id');
						$mem_data['is_member'] = 1;
						$mem_data['role_id'] = $role_id;
						$user_nos =  $this->Crud->read_field('id', $edit_id, 'visitors', 'user_no');
						$mem_data['activate'] = 1;
						$mem_data['reg_date'] = date(fdate);
						if(empty($user_nos)){
							$mem_rec = $this->Crud->create('user', $mem_data);
						}
						if($mem_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $mem_rec, 'user', 'surname');
							$this->Crud->updates('id', $mem_rec, 'user', array('user_no'=>'CEAM-00'.$mem_rec));
							$password = '12345';
							$user_no = 'CEAM-00'.$mem_rec;

							$action = $by.' created Membership ('.$code.') Record';
							$this->Crud->activity('user', $mem_rec, $action);
							$name = ucwords($firstname.' '.$lastname);
							$body = '
								Dear '.$name.', <br><br>
									A Membership Account Has been Created with This Email on Chrsit Embassy  Platform;<br>
									Below are your login Credentials:<br><br>

									Website: '.site_url().'
									Membership ID: '.$user_no.'<br>
									Email: '.$email.'<br>
									Phone: '.$phone.'<br>
									Password: '.$password.'<br><br>
									Do not disclose your Login credentials with anyone to avoid unauthorized access.
									
							';
							$this->Crud->send_email($email, 'Membership Account', $body);
							$ins_data['user_no'] = $user_no;
						} 	
					}
					$ins_data['fullname'] = $fullname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['title'] = $title;
					$ins_data['gender'] = $gender;
					$ins_data['dob'] = $dob;
					$ins_data['foundation_school'] = $foundation_school;
					$ins_data['foundation_weeks'] = $foundation_weeks;
					$ins_data['assigned_to'] = json_encode($assigned_to);
					$ins_data['is_member'] = $is_member;

					// do create or update
					if($edit_id) {
						$upd_rec = $this->Crud->updates('id', $edit_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('New Convert Record Updated'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $edit_id, 'visitors', 'fullname');
							$action = $by.' updated New Convert ('.$code.') Record';
							$this->Crud->activity('first_timer', $edit_id, $action);

							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', translate_phrase('No Changes'));	
						}
					} 
					exit;	
				}
			}
		}
		

		// record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			if(!empty($this->request->getVar('start_date'))){$start_date = $this->request->getVar('start_date');}else{$start_date = '';}
			if(!empty($this->request->getVar('end_date'))){$end_date = $this->request->getVar('end_date');}else{$end_date = '';}

			if(!empty($this->request->getPost('role_id'))) { $role_id = $this->request->getPost('role_id'); } else { $role_id = ''; }
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			$search = $this->request->getPost('search');

			if(empty($ref_status))$ref_status = 0;
			$items = ' ';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$role_id = $this->Crud->read_field('name', 'Administrator', 'access_role', 'id');

				$all_rec = $this->Crud->filter_visitors('', '', $log_id, $search, '', 0, 0, 1, $switch_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_visitors($limit, $offset, $log_id, $search, '', 0, 0, 1, $switch_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$fullname = $q->fullname;
						$email = $q->email;
						$phone = $q->phone;
						$invited_by = $q->invited_by;
						$source_type = $q->source_type;
						$source_id = $q->source_id;
						$invited_by = $q->invited_by;
						$assigned_to = $q->assigned_to;
						$is_member = $q->is_member;
						$is_visitor = $q->is_visitor;
						$category = ucwords(str_replace('_', ' ', $q->category));
						$foundation_school = $q->foundation_school;
						$channel = $q->channel;
						$church = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
						$ministry = $this->Crud->read_field('id', $q->ministry_id, 'ministry', 'name');
						
						if($church_id > 0){
							$church = '';
						}
						if($source_type == 'cell'){
							$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
							$cell = '-'.$this->Crud->read_field('id', $cell_id, 'cells', 'name');
							$type = $this->Crud->read_field('id', $source_id, 'cell_report', 'type');
							$source = '';
							if($type == 'wk1')$source = 'WK1 - Prayer and Planning';
							if($type == 'wk2')$source = 'Wk2 - Bible Study';
							if($type == 'wk3')$source = 'Wk3 - Bible Study';
							if($type == 'wk4')$source = 'Wk4 - Fellowship / Outreach';
							if($type == 'wk5')$source = 'Wk5 - Fellowship';
							
						}

						$follow_up = $this->Crud->check('visitor_id', $id, 'follow_up');

						if($source_type == 'service'){
							$cell_id = $this->Crud->read_field('id', $source_id, 'cell_report', 'cell_id');
							$cell = '';
							$type = $this->Crud->read_field('id', $source_id, 'service_report', 'type');
							$source = $this->Crud->read_field('id', $type, 'service_type', 'name');
							
						}

						if($invited_by == 'Member'){
							$channel = $this->Crud->read_field('id', $q->channel, 'user', 'firstname').' '.$this->Crud->read_field('id', $q->channel, 'user', 'surname');
						}
						$reg_date = date('M d, Y h:ia', strtotime($q->reg_date));
						$visit_date = date('M d, Y', strtotime($q->visit_date));

						$status = '';
						if($is_member){
							$status = 'Member';
						}
						if($is_visitor){
							$status = 'Visitor';
						}

						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-success pop" pageTitle="View ' . $fullname . '" pageName="' . site_url($mod . '/manage/view/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								<li><a href="javascript:;" onclick="follow_up(' . (int) $id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>' . translate_phrase('Follow Up') . '</span></a></li>
								
								
							';
						}

						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-info">
											<span class="tb-lead">' . ucwords($fullname) . ' </span><br>
											<span class="small text">' . ucwords(strtolower($church)) . ' <b>'.$cell.'</b> </span>
										</div>
									</div>
								</td>
								<td><span class="tb-lead small">' . $email . '</span><br><span class="tb-lead small">' . $phone . '</span> </td>
								<td><span class="tb-lead small">' . ucwords($source_type) . '</span><br><span class="tb-lead small">' . ucwords($source) . '</span></td>
								<td><span class="tb-lead small">' . $invited_by . '</span><br><span class="tb-lead small">' . ucwords($channel) . '</span></td>
								<td><span class="tb-lead small">' . $follow_up . '</span></td>
								<td><span class="tb-lead small">' . $category . '</span><br><span class="badge rounded-pill bg-outline-primary">'.$status.'</span></td>
								<td><span class="tb-lead small">' . $visit_date . '</span></td>
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
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="8">
					<div class="text-center text-muted col-sm-12">
						<br/><br/>
						<i class="icon ni ni-users " style="font-size:120px;"></i><br/>No Archive Returned
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Archive').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	public function follows($param1 = '', $param2 = '', $param3 = ''){
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'accounts/follows';
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
		$table = 'follow_up';
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

		$visitor_id = $this->session->get('visitor_id');
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
								load_pastor("","",' . $visitor_id . ');
								$("#modal").modal("hide");
							</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
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
								$data['e_type'] = $e->type;
								$data['e_notes'] = $e->notes;
								$data['e_date'] = $e->date;
							}
						}
					}
				}

				if ($param2 == 'view') {
					if ($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if (!empty($edit)) {
							foreach ($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_type'] = $e->type;
								$data['e_notes'] = $e->notes;
								$data['e_date'] = $e->date;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_id'] = $e->church_id;
								$data['e_visitor_id'] = $e->visitor_id;
								$data['e_member_id'] = $e->member_id;
								$data['e_reg_date'] = $e->reg_date;
							}
						}
					}
				}

				if ($this->request->getMethod() == 'post') {
					$user_id = $this->request->getPost('user_id');
					$type = $this->request->getPost('type');
					$date = $this->request->getPost('date');
					$notes = $this->request->getPost('notes');


					if (empty($type) || $type == ' ') {
						echo $this->Crud->msg('danger', 'Select Foolow Up Type');
						die;
					}

					$ins_data['type'] = $type;
					$ins_data['date'] = $date;
					$ins_data['notes'] = $notes;
					$ins_data['visitor_id'] = $visitor_id;
					$ins_data['member_id'] = $log_id;
					$ins_data['church_id'] = $this->Crud->read_field('id', $visitor_id, 'visitors', 'church_id') ;
					$ins_data['ministry_id'] = $this->Crud->read_field('id', $visitor_id, 'visitors', 'ministry_id') ;

					// do create or update
					if ($user_id) {
						$upd_rec = $this->Crud->updates('id', $user_id, $table, $ins_data);
						if ($upd_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('Follow Up Record Updated'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $visitor_id, 'visitors', 'fullname');
							$action = $by . ' updated Follow Up (' . $code . ') Record';
							$this->Crud->activity('follow_up', $user_id, $action);
							echo '<script>
									load_follow("","",' . $visitor_id . ');
									$("#modal").modal("hide");
								</script>';
						} else {
							echo $this->Crud->msg('info', translate_phrase('No Changes'));
						}
					} else {
						if ($this->Crud->check2('visitor_id', $visitor_id, 'notes', $notes, $table) > 0) {
							echo $this->Crud->msg('warning', ('Follow Up Record Already Exist'));
						} else {
							$ins_data['reg_date'] = date(fdate);

							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Follow Up Record Created'));
								
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $visitor_id, 'visitors', 'fullname');
								$action = $by . ' created Follow Up for (' . $code . ')';
								$this->Crud->activity('follow_up', $ins_rec, $action);

								echo '<script>
									load_follow("","",' . $visitor_id . ');
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
			$visitor_id = $this->request->getPost('id');
			$this->session->set('visitor_id', $visitor_id);

			
			$items = '
					
			';
			$a = 1;

			//echo $status;
			$log_id = $this->session->get('td_id');
			if (!$log_id) {
				$item = '<div class="text-center text-muted">' . translate_phrase('Session Timeout! - Please login again') . '</div>';
			} else {
				
				$all_rec = $this->Crud->filter_follow_up('', '', $log_id, $search, $visitor_id);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_follow_up($limit, $offset, $log_id, $search, $visitor_id);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$date = $q->date;
						$type = $q->type;
						$notes = $q->notes;
						$member_id = $q->member_id;
						$member = $this->Crud->read_field('id', $q->member_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $q->member_id, 'user', 'surname');
						$reg_date = date('M d, Y h:ia', strtotime($q->reg_date));
						$date = date('M d, Y', strtotime($q->date));

				

						// add manage buttons
						if (!empty($switch_id)) {
							$all_btn = '
								
								
							';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit " pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>' . translate_phrase('Edit') . '</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="View " pageName="' . site_url($mod . '/manage/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>' . translate_phrase('View') . '</span></a></li>
								
							';

						}



						$item .= '
							<tr>
								
								<td><span class=" small">' . $date . '</span></td>
								<td><span class=" small">' . ucwords($type) . '</span></td>
								<td>
									<div class="user-card">
										<div class="user-info">
											<span class="tb-lead small">' . ucwords($member) . ' </span>
										</div>
									</div>
								</td>
								<td style="word-wrap: break-word; max-width: 300px;"><span class=" small">' . ucwords($notes) . '</span></td>
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
						<i class="ni ni-user-add" style="font-size:150px;"></i><br/><br/>' . translate_phrase('No Follow Up Returned') . '
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
			return view('accounts/follows_form', $data);
		}

	}

	//Customer
	public function dept($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/dept';

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
       
		
		$table = 'dept';
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
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_dept_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'dept', 'name');
						$action = $by.' deleted Department ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Department Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
					}
				}
			} elseif($param2 == 'message'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
						}
					}
				}
				if($this->request->getMethod() == 'post'){
					$dept_id = $this->request->getVar('edit_id');
					$message = $this->request->getVar('message');
					$dept_role = $this->request->getVar('dept_role');
					$type = $this->request->getVar('type');
					$subject = $this->request->getVar('subject');
					$ministry_id = $this->request->getVar('ministry_id');
					$send_type = $this->request->getVar('send_type');
					$church_id = $this->request->getVar('church_id');
					
					if (empty($church_id) || !is_array($church_id)) {
						echo $this->Crud->msg('warning', 'Select valid Church ID(s)');
						die;
					}
					
					$memberss = $this->Crud->read_single_order('dept_id', $dept_id, 'user', 'firstname', 'asc');
					$filtered_members = [];

					if($send_type == 'general'){
						//Means sending to Selected Role
						if (!empty($church_id) && is_array($church_id)) {
							// Loop through each selected church ID
							foreach ($church_id as $id) {
								// Get the church type for the current church ID
								$church_type = $this->Crud->read_field('id', $id, 'church', 'type');
								
								// If the church type is not "church assembly," get all sub-churches under this church
								if ($church_type != 'church') {
									$sub_churches = $this->Crud->get_sub_churches($id); // Assuming a method to get sub-church IDs
									$all_church_ids = array_merge([$id], $sub_churches);
								} else {
									$all_church_ids = [$id];
								}
								// print_r($all_church_ids);
								// Retrieve members from the department in each church in all_church_ids
								foreach ($all_church_ids as $church) {
									$members = $this->Crud->read2_order('church_id', $church, 'dept_id', $dept_id, 'user', 'firstname', 'asc');
									 // Assuming a method to get members
									$filtered_members = array_merge($filtered_members, $members);
								}
							}
						}
						$memberss = $filtered_members;

					}


					if($type == 'true'){
						//Means all members
						
						$filtered_members = array_filter($memberss, function($member) use ($church_id) {
							// Check if member's church_id exists in the selected church_id array
							return isset($member->church_id) && in_array($member->church_id, $church_id);
						});

						
						
						if (empty($filtered_members)) {
							echo $this->Crud->msg('warning', 'No members found for the selected church ID(s).');
							die;
						}
					}

					if ($type == 'false') {
						// Means sending to Selected Role
						if (empty($dept_role) || !is_array($dept_role)) {
							echo $this->Crud->msg('warning', 'Select Department Role(s)');
							die;
						}
						
						$church_id = $this->request->getVar('church_id');
						if (empty($church_id) || !is_array($church_id)) {
							echo $this->Crud->msg('warning', 'Select valid Church ID(s)');
							die;
						}
						
						
						// Step 1: Filter members based on selected church_id array
						$church_filtered_members = array_filter($memberss, function($member) use ($church_id) {
							return isset($member->church_id) && in_array($member->church_id, $church_id);
						});
						
						// Step 2: Filter the church-filtered members based on dept_role array
						$filtered_members = array_filter($church_filtered_members, function($member) use ($dept_role) {
							return isset($member->dept_role) && in_array($member->dept_role, $dept_role);
						});
						
						// Check if there are any members left after filtering
						if (empty($filtered_members)) {
							echo $this->Crud->msg('warning', 'No members found with the selected church ID(s) and department role(s).');
							die;
						}
						
					}
					
					
					$memb = $filtered_members;
					

					$ins_data['subject'] = $subject;
					$ins_data['message'] = $message;
					$ins_data['dept_id'] = $dept_id;
					$ins_data['from_id'] = $log_id;
					$ins_data['reg_date'] = date(fdate);
					$scount = 0;
					$fcount = 0;

					if(!empty($memb)){
						foreach($memb as $mem){
							$ins_data['church_id'] = $mem->church_id;
							$ins_data['ministry_id'] = $mem->ministry_id;
							
							$to_id = $mem->id;
							$ins_data['to_id'] = $to_id;
							$firstname = $mem->firstname;
							$surname = $mem->surname;
							$email = $mem->email;
							
							// do create or update
							$upd_rec = $this->Crud->create('message', $ins_data);
							if($upd_rec > 0) {
								$scount++;
								$this->Crud->notify($log_id, $to_id, $message, 'message', $upd_rec);
								$name = ucwords($firstname.' '.$surname);
									$body = '
										Dear '.$name.', <br><br>
									'.$message;
									$this->Crud->send_email($email, ucwords($subject), $body);

								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $to_id, 'user', 'firstname');
								$action = $by.' Sent a message to User ('.$code.')';
								$this->Crud->activity('user', $to_id, $action);
							} else {
								$scount++;	
							}
							
							
						}
					}

					
					if($scount == 0){
						echo $this->Crud->msg('info', 'Try Again Later');
					} else {
						echo $this->Crud->msg('success', $scount.' Message Sent.<br>'.$fcount.' Message Failed');
						echo '<script>location.reload(false);</script>';
						
					}
					die;	
				}
			} elseif($param2 == 'bulk_message'){
				
				if($this->request->getMethod() == 'post'){
					$dept_ids = $this->request->getVar('dept_id');
					$message = $this->request->getVar('message');
					$dept_role = $this->request->getVar('dept_role');
					$type = $this->request->getVar('type');
					$subject = $this->request->getVar('subject');
					$ministry_id = $this->request->getVar('ministry_id');
					$send_type = $this->request->getVar('send_type');
					$church_id = $this->request->getVar('church_id');
					
					if (empty($church_id) || !is_array($church_id)) {
						echo $this->Crud->msg('warning', 'Select valid Church ID(s)');
						die;
					}

					if (empty($dept_ids) || !is_array($dept_ids)) {
						echo $this->Crud->msg('warning', 'Select valid Department(s)');
						die;
					}
					$filtered_members = [];

					$memberss = []; // Initialize to store all members based on dept_id

					// Loop through each department ID to retrieve members
					foreach ($dept_ids as $dept_id) {
						$dept_members = $this->Crud->read_single_order('dept_id', $dept_id, 'user', 'firstname', 'asc');
						$memberss = array_merge($memberss, $dept_members); // Combine members from all departments
					}

					if ($send_type == 'general') {
						// Means sending to Selected Role
						if (!empty($church_id) && is_array($church_id)) {
							// Loop through each selected church ID
							foreach ($church_id as $id) {
								// Get the church type for the current church ID
								$church_type = $this->Crud->read_field('id', $id, 'church', 'type');

								// If the church type is not "church assembly," get all sub-churches under this church
								if ($church_type != 'church') {
									$sub_churches = $this->Crud->get_sub_churches($id); // Assuming a method to get sub-church IDs
									$all_church_ids = array_merge([$id], $sub_churches);
								} else {
									$all_church_ids = [$id];
								}

								// Retrieve members from the department in each church in all_church_ids
								foreach ($all_church_ids as $church) {
									foreach ($dept_ids as $dept_id) {
										$members = $this->Crud->read2_order('church_id', $church, 'dept_id', $dept_id, 'user', 'firstname', 'asc');
										$filtered_members = array_merge($filtered_members, $members);
									}
								}
							}
						}
						$memberss = $filtered_members;
					}

					if ($type == 'true') {
						// Means all members
						$filtered_members = array_filter($memberss, function($member) use ($church_id) {
							// Check if member's church_id exists in the selected church_id array
							return isset($member->church_id) && in_array($member->church_id, $church_id);
						});

						if (empty($filtered_members)) {
							echo $this->Crud->msg('warning', 'No members found for the selected church ID(s).');
							die;
						}
					}

					if ($type == 'false') {
						// Means sending to Selected Role
						if (empty($dept_role) || !is_array($dept_role)) {
							echo $this->Crud->msg('warning', 'Select Department Role(s)');
							die;
						}

						$church_id = $this->request->getVar('church_id');
						if (empty($church_id) || !is_array($church_id)) {
							echo $this->Crud->msg('warning', 'Select valid Church ID(s)');
							die;
						}

						// Step 1: Filter members based on selected church_id array
						$church_filtered_members = array_filter($memberss, function($member) use ($church_id) {
							return isset($member->church_id) && in_array($member->church_id, $church_id);
						});

						// Step 2: Filter the church-filtered members based on dept_role array
						$filtered_members = array_filter($church_filtered_members, function($member) use ($dept_role) {
							return isset($member->dept_role) && in_array($member->dept_role, $dept_role);
						});

						// Check if there are any members left after filtering
						if (empty($filtered_members)) {
							echo $this->Crud->msg('warning', 'No members found with the selected church ID(s) and department role(s).');
							die;
						}
					}

					$memb = $filtered_members;
					
					$ins_data['subject'] = $subject;
					$ins_data['message'] = $message;
					$ins_data['from_id'] = $log_id;
					$ins_data['reg_date'] = date(fdate);
					$scount = 0;
					$fcount = 0;

					if(!empty($memb)){
						foreach($memb as $mem){
							
							$ins_data['dept_id'] = $mem->dept_id;
							$ins_data['church_id'] = $mem->church_id;
							$ins_data['ministry_id'] = $mem->ministry_id;
							
							$to_id = $mem->id;
							$ins_data['to_id'] = $to_id;
							$firstname = $mem->firstname;
							$surname = $mem->surname;
							$email = $mem->email;
							
							// do create or update
							$upd_rec = $this->Crud->create('message', $ins_data);
							if($upd_rec > 0) {
								$scount++;
								$this->Crud->notify($log_id, $to_id, $message, 'message', $upd_rec);
								$name = ucwords($firstname.' '.$surname);
									$body = '
										Dear '.$name.', <br><br>
									'.$message;
									$this->Crud->send_email($email, ucwords($subject), $body);

								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $to_id, 'user', 'firstname');
								$action = $by.' Sent a message to User ('.$code.')';
								$this->Crud->activity('user', $to_id, $action);
							} else {
								$scount++;	
							}
							
							
						}
					}

					
					if($scount == 0){
						echo $this->Crud->msg('info', 'Try Again Later');
					} else {
						echo $this->Crud->msg('success', $scount.' Message Sent.<br>'.$fcount.' Message Failed');
						echo '<script>location.reload(false);</script>';
						
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
								$data['e_roles'] = json_decode($e->roles);
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$dept_id = $this->request->getVar('dept_id');
					$name = $this->request->getVar('name');
					$roles = $this->request->getVar('roles');

					$ins_data['name'] = $name;
					$ins_data['roles'] = json_encode($roles);
					// print_r($roles);
					// die;
					// do create or update
					if($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'dept', 'name');
							$action = $by.' updated Department ('.$code.') Record';
							$this->Crud->activity('user', $dept_id, $action);

							echo $this->Crud->msg('success', 'Record Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'dept', 'name');
								$action = $by.' created Department ('.$code.') Record';
								$this->Crud->activity('user', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Record Created');
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

		if($param1 == 'getChurch'){
			$ministry_id = $this->request->getPost('ministry_id');
			$level = $this->request->getPost('level');
			$log_church_id = $this->request->getPost('log_church_id');
			
			$options = '';
			if($log_church_id > 0){
				$log_church = $this->Crud->read_field('id', $log_church_id, 'church', 'name');
				$options .= '<option value="'.$log_church_id.'" selected>'.ucwords($log_church).'</option>';
			}
			$church = $this->Crud->read2_order('type', $level, 'ministry_id', $ministry_id, 'church', 'name');
			if(!empty($church)){
				foreach($church as $c){
					$options .= '<option value="'.$c->id.'">'.ucwords($c->name).'</option>';
				}
			}
			echo $options;
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
			
			$search = $this->request->getPost('search');
			
			$items = '
				<div class="nk-tb-item nk-tb-head">
					<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Name').'</span></div>
					<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Role(s)').'</span></div>
					<div class="nk-tb-col nk-tb-col-tools">
						<ul class="nk-tb-actions gx-1 my-n1">
							
						</ul>
					</div>
				</div><!-- .nk-tb-item -->
		
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_dept('', '', '', $log_id, $search);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_dept($limit, $offset, '', $log_id, $search);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$roles = $q->roles;
						$rolesa = json_decode($roles);
						$rols = '';
						if(!empty($rolesa)){
							foreach($rolesa as $r => $val){
								$rols .= $val.', ';
							}
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								
								
							';
							} else{

								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Send Message to ' . $name . ' Department" pageName="' . site_url($mod . '/manage/message/' . $id) . '" pageSize="modal-lg"><em class="icon ni ni-chat-circle"></em><span>'.translate_phrase('Send Message').'</span></a></li>
								
								
							';
							}
							
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">' . ucwords($name) . ' </span>
									</div>
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark"><b>' . ucwords(rtrim($rols, ', ')) . '</b></span>
								</div>
								<div class="nk-tb-col nk-tb-col-tools">
									<ul class="nk-tb-actions gx-1">
										<li>
											<div class="drodown">
												<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
												<div class="dropdown-menu dropdown-menu-end">
													<ul class="link-list-opt no-bdr">
														' . $all_btn . '
													</ul>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div><!-- .nk-tb-item -->
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-building" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Department Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Departments').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	//Customer
	public function cell_role($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/cell_role';

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
       
		
		$table = 'cell_role';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
        $data['current_language'] = $this->session->get('current_language');
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_dept_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'cell_role', 'name');
						$action = $by.' deleted Cell Role ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Cell Role Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
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
								$data['e_name'] = $e->name;
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$dept_id = $this->request->getVar('dept_id');
					$name = $this->request->getVar('name');

					$ins_data['name'] = $name;
					
					if($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'cell_role', 'name');
							$action = $by.' updated Cell Role ('.$code.') Record';
							$this->Crud->activity('user', $dept_id, $action);

							echo $this->Crud->msg('success', 'Cell Role Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'cell_role', 'name');
								$action = $by.' created Cell Role ('.$code.') Record';
								$this->Crud->activity('user', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Cell Role Created');
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
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			
			$items = '
				<div class="nk-tb-item nk-tb-head">
					<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Name').'</span></div>
					<div class="nk-tb-col nk-tb-col-tools">
						<ul class="nk-tb-actions gx-1 my-n1">
							
						</ul>
					</div>
				</div><!-- .nk-tb-item -->
		
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_cell_role('', '', '', $log_id, $search);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_cell_role($limit, $offset, '', $log_id, $search);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								
								
							';
							}
							
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">' . ucwords($name) . ' </span>
									</div>
								</div>
								<div class="nk-tb-col nk-tb-col-tools">
									<ul class="nk-tb-actions gx-1">
										<li>
											<div class="drodown">
												<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
												<div class="dropdown-menu dropdown-menu-end">
													<ul class="link-list-opt no-bdr">
														' . $all_btn . '
													</ul>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div><!-- .nk-tb-item -->
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-building" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Cell Role Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Cell Roles').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	//Customer
	public function cell($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/cell';

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
       
		
		$table = 'cells';
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
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_cell_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'cells', 'name');
						$action = $by.' deleted Cell ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Cell Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
					}
				}
			} elseif($param2 == 'cell_message'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
						}
					}
				}
				if($this->request->getMethod() == 'post'){
					$cell_id = $this->request->getVar('edit_id');
					$message = $this->request->getVar('message');
					$type = $this->request->getVar('type');
					$subject = $this->request->getVar('subject');
					
					$ministry_id = $this->Crud->read_field('id', $cell_id, 'cells', 'ministry_id');
					$church_id = $this->Crud->read_field('id', $cell_id, 'cells', 'church_id');
					$name = $this->Crud->read_field('id', $cell_id, 'cells', 'name');
					
					$members = [];
					$query = $this->Crud->filter_cell_members('', '',$log_id, 'all', '', $cell_id);

					$cell_member = $this->Crud->read_field('name', 'Cell Member', 'access_role', 'id');
					$cell_leader = $this->Crud->read_field('name', 'Cell Leader', 'access_role', 'id');
					$cell_leader_assist = $this->Crud->read_field('name', 'Assistant Cell Leader', 'access_role', 'id');
					$cell_executive = $this->Crud->read_field('name', 'Cell Executive', 'access_role', 'id');
					foreach($query as $q){
						if($type == 'false'){
							if($q->cell_role == $cell_member)continue;
						}
						$members[] = $q->id;

					}
					
					if(empty($members)){
						echo $this->Crud->msg('danger', 'No User Found for this Selection');
						die;
					}

					$ins_data['subject'] = $subject;
					$ins_data['message'] = $message;
					$ins_data['church_id'] = $church_id;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['cell_id'] = $cell_id;
					$ins_data['from_id'] = $log_id;
					$ins_data['reg_date'] = date(fdate);
					$scount = 0;
					$fcount = 0;

					if(!empty($members)){
						foreach($members as $to_id){
							$ins_data['to_id'] = $to_id;
							$firstname = $this->Crud->read_field('id', $to_id, 'user', 'firstname');
							$surname = $this->Crud->read_field('id', $to_id, 'user', 'surname');
							$email = $this->Crud->read_field('id', $to_id, 'user', 'email');
							
							// do create or update
							$upd_rec = $this->Crud->create('message', $ins_data);
							if($upd_rec > 0) {
								$scount++;
								$this->Crud->notify($log_id, $to_id, $message, 'message', $upd_rec);
								$name = ucwords($firstname.' '.$surname);
									$body = '
										Dear '.$name.', <br><br>
									'.$message;
									$this->Crud->send_email($email, ucwords($subject), $body);

								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $to_id, 'user', 'firstname');
								$action = $by.' Sent a message to User ('.$code.')';
								$this->Crud->activity('user', $to_id, $action);
							} else {
								$scount++;	
							}
							
							
						}
					}

					
					if($scount == 0){
						echo $this->Crud->msg('info', 'Try Again Later');
					} else {
						echo $this->Crud->msg('success', $scount.' Message Sent.<br>'.$fcount.' Message Failed');
						echo '<script>location.reload(false);</script>';
						
					}
					die;	
				}
			} elseif($param2 == 'bulk_message'){
				// prepare for edit
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
							$data['e_is_leader'] = $e->is_leader;
						}
					}
				}
			

				if($this->request->getMethod() == 'post'){
					$user_id = $this->request->getVar('edit_id');
					$message = $this->request->getVar('message');
					$member_id = $this->request->getVar('member_id');
					$cell_id = $this->request->getVar('cell_id');
					$type = $this->request->getVar('type');
					$subject = $this->request->getVar('subject');
					
					if(empty($cell_id)){
						echo $this->Crud-msg('danger', 'Select Cell you want to send Message to');
						die;
					}

					
					$scount = 0;
					$fcount = 0;
					
					$ins_data['subject'] = $subject;
					$ins_data['message'] = $message;
					$ins_data['from_id'] = $log_id;
					$ins_data['reg_date'] = date(fdate);

					$cell_member = $this->Crud->read_field('name', 'Cell Member', 'access_role', 'id');
					$cell_leader = $this->Crud->read_field('name', 'Cell Leader', 'access_role', 'id');
					$cell_leader_assist = $this->Crud->read_field('name', 'Assistant Cell Leader', 'access_role', 'id');
					$cell_executive = $this->Crud->read_field('name', 'Cell Executive', 'access_role', 'id');

					if(!empty($cell_id)){
						foreach($cell_id as $cell){
							$ministry_id = $this->Crud->read_field('id', $cell, 'cells', 'ministry_id');
							$church_id = $this->Crud->read_field('id', $cell, 'cells', 'church_id');

							// echo $cell.' ';
							$all_members = [];
							$executives = [];
							$memberss = $this->Crud->filter_cell_members('', '',$log_id, 'all', '', $cell);

							if(!empty($memberss)){
								foreach($memberss as $mem){
									$name = $mem->firstname.' '.$mem->surname;
									$all_members[] = $mem->id;
									if($mem->cell_role != $cell_member){
										$executives[] = $mem->id;
									}
									
								}
							}
							
							$ins_data['cell_id'] = $cell;
							$ins_data['church_id'] = $church_id;
							$ins_data['ministry_id'] = $ministry_id;

							if($type == 'false'){
								$members = $executives;
							}
							if($type == 'true'){
								$members = $all_members;
							}

							if(!empty($members)){
								foreach($members as $member){
									// echo $member.' ';
									$firstname = $this->Crud->read_field('id', $member, 'user', 'firstname');
									$surname = $this->Crud->read_field('id', $member, 'user', 'surname');
									$email = $this->Crud->read_field('id', $member, 'user', 'email');
									
									$ins_data['to_id'] = $member;
								
									
									// do create or update
									$upd_rec = $this->Crud->create('message', $ins_data);
									if($upd_rec > 0) {
										$scount++;
										$this->Crud->notify($log_id, $member, $message, 'message', $upd_rec);
										$name = ucwords($firstname.' '.$surname);
											$body = '
												Dear '.$name.', <br><br>
											'.$message;
											$this->Crud->send_email($email, ucwords($subject), $body);

										///// store activities
										$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
										$code = $this->Crud->read_field('id', $member, 'user', 'firstname');
										$action = $by.' Sent a message to User ('.$code.')';
										$this->Crud->activity('user', $member, $action);
									} else {
										$scount++;	
									}
								}
							}

						}
					}
					
					if($scount == 0){
						echo $this->Crud->msg('info', 'Try Again Later');
						echo $this->Crud->msg('danger', $fcount.' Message Failed');
					} else {
						echo $this->Crud->msg('success', $scount.' Message Sent.<br>'.$fcount.' Message Failed');
						echo '<script>location.reload(false);</script>';
						
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
								$data['e_location'] = $e->location;
								$data['e_name'] = $e->name;
								$data['e_phone'] = $e->phone;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_id'] = $e->church_id;
								$data['e_level'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
								$data['e_time'] = json_decode($e->time);
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$cell_id = $this->request->getVar('cell_id');
					$name = $this->request->getVar('name');
					$phone = $this->request->getVar('phone');
					$location = $this->request->getVar('location');
					$times = $this->request->getVar('times');
					$days = $this->request->getVar('days');
					$church_id = $this->request->getVar('church_id');
					$ministry_id = $this->request->getVar('ministry_id');
					
					$time = [];
					for($i=0;$i < count($days);$i++ ){
						$day = $days[$i];
						// echo $day;
						$time[$day] = $times[$i];
					}
					
					// $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
					// $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
					

					$ins_data['name'] = $name;
					$ins_data['location'] = $location;
					$ins_data['phone'] = $phone;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['time'] = json_encode($time);
					
					// do create or update
					if($cell_id) {
						$upd_rec = $this->Crud->updates('id', $cell_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $cell_id, 'cells', 'name');
							$action = $by.' updated Cell ('.$code.') Record';
							$this->Crud->activity('user', $cell_id, $action);

							echo $this->Crud->msg('success', 'Record Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check2('name', $name, 'church_id', $church_id, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'cells', 'name');
								$action = $by.' created Cell ('.$code.') Record';
								$this->Crud->activity('user', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Record Created');
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

		if($param1 == 'getMembers'){
			// Example controller method for fetching members dynamically
			$cell_id = $this->session->get('cell_id'); 
			$cell_member = $this->Crud->read_field('name', 'Cell Member', 'access_role', 'id');
			$cell_leader = $this->Crud->read_field('name', 'Cell Leader', 'access_role', 'id');
			$cell_leader_assist = $this->Crud->read_field('name', 'Assistant Cell Leader', 'access_role', 'id');
			$cell_executive = $this->Crud->read_field('name', 'Cell Executive', 'access_role', 'id');
			$type = $param2;

			$all_members = [];
			$executives = [];
			$member = $this->Crud->filter_cell_members('', '',$log_id, 'all', '', $cell_id);
			if(!empty($member)){
				foreach($member as $mem){
					$name = $mem->firstname.' '.$mem->surname;
					$all_member['id'] = $mem->id;
					$all_member['name'] = $name;
					if($mem->cell_role != $cell_member){
						$executive['id'] = $mem->id;
						$executive['name'] = $name;
						
						$executives[] = $executive;
					}
					$all_members[] = $all_member;

				}
			}// Assuming you're passing the cell ID for filtering
			if ($type === "executives") {
				$members = $executives;
			} else {
				$members = $all_members;
			}
			echo json_encode($members);
			die;

		}

		// manage record
		if($param1 == 'manage_member') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_cell_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'cells', 'name');
						$action = $by.' deleted Cell ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Cell Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
					}
				}
			} elseif($param2 == 'message'){
				
				if($this->request->getMethod() == 'post'){
					$user_id = $this->request->getVar('edit_id');
					$message = $this->request->getVar('message');
					$subject = $this->request->getVar('subject');
					
					$ministry_id = $this->Crud->read_field('id', $user_id, 'user', 'ministry_id');
					$church_id = $this->Crud->read_field('id', $user_id, 'user', 'church_id');
					$cell_id = $this->Crud->read_field('id', $user_id, 'user', 'cell_id');
					$firstname = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
					$surname = $this->Crud->read_field('id', $user_id, 'user', 'surname');
					$email = $this->Crud->read_field('id', $user_id, 'user', 'email');

				
					$ins_data['subject'] = $subject;
					$ins_data['message'] = $message;
					$ins_data['church_id'] = $church_id;
					$ins_data['cell_id'] = $cell_id;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['from_id'] = $log_id;
					$ins_data['to_id'] = $user_id;
					$ins_data['reg_date'] = date(fdate);
					
					// do create or update
					$upd_rec = $this->Crud->create('message', $ins_data);
					if($upd_rec > 0) {
						$this->Crud->notify($log_id, $user_id, $message, 'message', $upd_rec);
						$name = ucwords($firstname.' '.$surname);
							$body = '
								Dear '.$name.', <br><br>
							'.$message;
							$this->Crud->send_email($email, ucwords($subject), $body);

						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
						$action = $by.' Sent a message to User ('.$code.')';
						$this->Crud->activity('user', $user_id, $action);

						echo $this->Crud->msg('success', 'Message Sent');
						echo '<script>
								load_leadership("","",'.$cell_id.');
								$("#modal").modal("hide");
							</script>';
					} else {
						echo $this->Crud->msg('info', 'Try Again Later');	
					}
				

					die;	
				}
			} elseif($param2 == 'bulk_message'){

				if($this->request->getMethod() == 'post'){
					$user_id = $this->request->getVar('edit_id');
					$cell_id = $this->request->getVar('cell_id');
					$message = $this->request->getVar('message');
					$member_id = $this->request->getVar('member_id');
					$subject = $this->request->getVar('subject');
					
					if(empty($member_id)){
						echo $this->Crud-msg('danger', 'Select Member you want to send Message to');
						die;
					}
					$scount = 0;
					$fcount = 0;
					$ins_data['subject'] = $subject;
					$ins_data['message'] = $message;
					$ins_data['from_id'] = $log_id;
					$ins_data['cell_id'] = $cell_id;
					$ins_data['reg_date'] = date(fdate);

					if(!empty($member_id)){
						foreach($member_id as $member){
							// echo $member.' ';

							$ministry_id = $this->Crud->read_field('id', $member, 'user', 'ministry_id');
							$church_id = $this->Crud->read_field('id', $member, 'user', 'church_id');
							$firstname = $this->Crud->read_field('id', $member, 'user', 'firstname');
							$surname = $this->Crud->read_field('id', $member, 'user', 'surname');
							$email = $this->Crud->read_field('id', $member, 'user', 'email');
							
							// echo $member.' '.$email;
							$ins_data['church_id'] = $church_id;
							$ins_data['ministry_id'] = $ministry_id;
							$ins_data['to_id'] = $member;
						
							
							// do create or update
							$upd_rec = $this->Crud->create('message', $ins_data);
							if($upd_rec > 0) {
								$scount++;
								$this->Crud->notify($log_id, $member, $message, 'message', $upd_rec);
								$name = ucwords($firstname.' '.$surname);
									$body = '
										Dear '.$name.', <br><br>
									'.$message;
									$this->Crud->send_email($email, ucwords($subject), $body);

								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $member, 'user', 'firstname');
								$action = $by.' Sent a message to User ('.$code.')';
								$this->Crud->activity('user', $member, $action);
							} else {
								$scount++;	
							}

						}
					}
					
					if($scount == 0){
						echo $this->Crud->msg('info', 'Try Again Later');
					} else {
						echo $this->Crud->msg('success', $scount.' Message Sent.<br>'.$fcount.' Message Failed');
						echo '<script>location.reload(false);</script>';
						
					}

					die;	
				}
			} else {
				// prepare for edit
				if($param2 == 'edit') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, 'user');
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_cell_id'] = $e->cell_id;
								$data['e_cell_role'] = $e->cell_role;
								$data['e_status'] = $e->activate;
								
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$member_ids = $this->request->getVar('member_id');
					$cell_id = $this->request->getVar('cell_id');
					$members = $this->request->getVar('members');
					$status = $this->request->getVar('status');
					$cell_role_id = $this->request->getVar('cell_role_id');
					$cell_member = $this->Crud->read_field('name', 'Cell Member', 'access_role', 'id');
					
					
					// do create or update
					if($member_ids) {
						$role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
						$member_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
						$cell_member = $this->Crud->read_field('name', 'Cell Member', 'access_role', 'id');
						$cell_role = $this->Crud->read_field('id', $cell_role_id, 'access_role', 'name');
						if($cell_role == 'Cell Leader' || $cell_role == 'Assistant Cell Leader'){
							$role_id = $cell_role_id;
							$cells = $this->Crud->read2('cell_id', $cell_id, 'cell_role !=', $cell_member, 'user');
							if(!empty($cells)){
								foreach($cells as $cm){
									if($cm->cell_role == $cell_role_id){
										$this->Crud->updates('id', $cm->id, 'user', array('role_id'=>$member_id, 'cell_role'=>$cell_member));
									}
								}
							}
						}
						if($cell_role == 'Cell Executive'){
							$role_id = $cell_role_id;
							$cells = $this->Crud->read2('cell_id', $cell_id, 'cell_role !=', $cell_member, 'user');
							if(!empty($cells)){
								$a = 0;
								foreach($cells as $cm){
									if($cm->cell_role == $cell_role_id){
										$a++;
										if($a > 5){
											$this->Crud->updates('id', $cm->id, 'user', array('role_id'=>$member_id, 'cell_role'=>$cell_member));
										}
										
									}

								}
							}
						}
						$ins_data['role_id'] = $role_id;
						$ins_data['activate'] = $status;
						$ins_data['cell_role'] = $cell_role_id;
						
						$upd_rec = $this->Crud->updates('id', $member_ids, 'user', $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $member_ids, 'user', 'firstname');
							$action = $by.' updated User ('.$code.') Record';
							$this->Crud->activity('user', $member_ids, $action);

							echo $this->Crud->msg('success', 'Record Updated');
							echo '<script>
								load_leadership("","",'.$cell_id.');
								$("#modal").modal("hide");
							</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if(empty($members)){
							echo $this->Crud->msg('warning', 'Select Members to assign to Cell');
							die;
						}

						foreach($members as $mem_index => $member){
							$this->Crud->updates('id', $member, 'user', array('cell_id'=>$cell_id, 'cell_role'=>$cell_member));
						
								///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$mems = $this->Crud->read_field('id', $member, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $cell_id, 'cells', 'name');
							$action = $by.' Assigned '.$mems.' to Cell ('.$code.')';
							$this->Crud->activity('user', $member, $action);
						
						}
						echo $this->Crud->msg('success', 'Members Assigned to Cell Successfully');
						echo '<script>
								load_leadership("","",'.$cell_id.');
								$("#modal").modal("hide");
							</script>';
					}

					die;	
				}
			}
		}

        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			
			$items = '
				<!-- .nk-tb-item -->
		
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_cell('', '', $search, $log_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_cell($limit, $offset, $search, $log_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$location = $q->location;
						$time = $q->time;
						$phone = $q->phone;
						$church_id = $q->church_id;
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
						$members = $this->Crud->check('cell_id', $id, 'user');
						$times = '<span class="text-danger">No Meeting Time</span>';
						if(!empty($time)){
							$times = '<a href="javascript:;" class="text-primary pop" pageTitle="View Time " pageName="' . site_url($mod . '/manage/view/' . $id) . '"><em class="icon ni ni-eye"></em> <span>'.translate_phrase('View Meeting Time').'</span></a>';
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								
								<li><a href="javascript:;" onclick="church_leadership(\'' . addslashes(ucwords($name)) . ' Cell\', ' . (int)$id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>'.translate_phrase('Members').'</span></a></li>
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Send Message to ' . $name . '" pageName="' . site_url($mod . '/manage/cell_message/' . $id) . '"><em class="icon ni ni-chat-circle"></em><span>'.translate_phrase('Send Message').'</span></a></li>
								
								
							';
							} else{
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" onclick="church_leadership(\'' . addslashes(ucwords($name)) . ' Cell\', ' . (int)$id . ');" class="text-dark" ><em class="icon ni ni-user-add"></em><span>'.translate_phrase('Members').'</span></a></li>
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Send Message to ' . $name . '" pageName="' . site_url($mod . '/manage/cell_message/' . $id) . '"><em class="icon ni ni-chat-circle"></em><span>'.translate_phrase('Send Message').'</span></a></li>
								
								
							';

							}
							
						}

						$item .= '
							<tr>
								<td>
									<div class="user-info">
										<span class="tb-lead small">' . ucwords($name) . ' </span><br>
										<span class="tb- small text-">&rarr; ' . ucwords(strtolower($church)) . ' </span>
									</div>
								</td>
								<td>
									<span class="small text-">' . ucwords($location) . '</span>
								</td>
								<td>
									<span class="small text-">' . ucwords($phone) . '</span>
								</td>
								<td>
									<span class="small text-">' . ucwords($members) . '</span>
								</td>
								<td>
									<span class="small text-">' . ($times) . '</span>
								</td>
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
							</tr><!-- .nk-tb-item -->
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-tranx" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Cell Returned').'
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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
		

		// record listing
		if($param1 == 'leadership_load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			$search = $this->request->getPost('search');
			$church_id = $this->request->getPost('id');
			$this->session->set('cell_id', $church_id);
			
			if(empty($ref_status))$ref_status = 0;
			$items = '
					
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$role_ids = $this->Crud->read_field('name', 'Pastor', 'access_role', 'id');

				$all_rec = $this->Crud->filter_cell_members('', '', $log_id, $status, $search, $church_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_cell_members($limit, $offset, $log_id, $status, $search, $church_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$fullname = $q->firstname.' '.$q->surname;
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

						$all_btn = '';
						// add manage buttons
						if(empty($switch_id)){
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $fullname . '" pageName="' . site_url($mod . '/manage_member/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-info pop" pageTitle="Send Message to ' . $fullname . '" pageName="' . site_url($mod . '/manage_member/message/' . $id) . '"><em class="icon ni ni-chat-circle"></em><span>'.translate_phrase('Send Message').'</span></a></li>
								
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
											<span class="tb-lead small">' . ucwords($fullname) . ' <span class="dot dot-' . $a_color . ' ms-1"></span></span>
											<br>
											
										</div>
									</div>
								</td>
								<td><span class=" small">' . $email . '</span></td>
								<td><span class=" small">' . $phone . '</span></td>
								<td><span class=" small">' . $u_role . '</span></td>
								<td><span class=" small">' . ucwords($address) . '</span></td>
								<td><span class="tb-amount small">' . $reg_date . ' </span></td>
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
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-users" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Cell Member Returned').'
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} elseif($param1 == 'manage_member'){ 
			return view($mod.'_manage_form', $data);
		}else { // view for main page
			
			$data['title'] = translate_phrase('Cells').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }
	public function creport($param1='', $param2='', $param3='', $param4=''){
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/creport';

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
       
		
		$table = 'cell_report';
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
		
		if($param1 == 'edit') {
			$edata = [];
			if($param2) {
				// echo $param2;
				$edit = $this->Crud->read_single('id', $param2, $table);
				if(!empty($edit)) {
					foreach($edit as $e) {
						$edata['e_id'] = $e->id;
						$edata['e_cell_id'] = $e->cell_id;
						$edata['e_type'] = $e->type;
						$edata['e_date'] = $e->date;
						$edata['e_attendance'] = $e->attendance;
						$edata['e_new_convert'] = $e->new_convert;
						$edata['e_first_timer'] = $e->first_timer;
						$edata['e_offering'] = $e->offering;
						$edata['e_note'] = $e->note;
						$edata['e_attendant'] = $e->attendant;
						$edata['e_timers'] = $e->timers;
						$edata['e_converts'] = $e->converts;
					}
				}
			}
			echo json_encode($edata);
			die;
		} 
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_cell_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$action = $by.' deleted Cell Report';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Report Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
					}
				}
			
			} elseif($param2 == 'attendance'){
				
				if($param3) {
					$edit = $this->Crud->read2('type_id', $param3, 'type', 'cell', 'attendance');
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
							$data['d_attendant'] = $e->attendant;
						}
					}
					//When Adding Save in Session
					if($this->request->getMethod() == 'post'){
						$mark = $this->request->getPost('mark');
						$convert = $this->request->getPost('convert');
						$total = 0;
						
						if(empty($mark)){
							echo $this->Crud->msg('danger', 'Mark Meeting Attendance');
							die;
						} else{
							$total = count($mark);
							 // Save attendance and converts to session
							 $this->session->set('cell_attendance', json_encode($mark));
							 $this->session->set('cell_converts', json_encode($convert)); // can be null or array
					 		echo $this->Crud->msg('success', 'Meeting Attendance Submitted');
							// echo json_encode($mark);
							echo '<script> setTimeout(function() {
								var jsonData = ' . json_encode($mark) . ';
								var jsonString = JSON.stringify(jsonData);
								$("#attendant").val(jsonString);
								var jsonData = ' . json_encode($convert) . ';
								var jsonString = JSON.stringify(jsonData);
								$("#converts").val(jsonString);
								
								$("#attendance").val('.$total.');
								$("#modal").modal("hide");
							}, 2000); </script>';
						}
						die;
					}
				}

			} elseif($param2 == 'offering'){
				$timer_count = $this->session->get('cell_timers');
				// $first = json_decode($timer_count);
				// echo $timer_count;
				$data['first'] = $timer_count;
				$data['table_rec'] = 'accounts/creport/offering_list/'.$param3; 
				if($param4){
					$data['table_rec'] = 'accounts/creport/offering_list/'.$param3.'/'.$param4; 
				}
				$data['order_sort'] = '0, "asc"'; // default ordering (0, 'asc')
				$data['no_sort'] = '1'; // sort disable columns (1,3,5)
		
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$guest_offering = $this->request->getPost('guest_offering');
					$total_offering = $this->request->getPost('total_offering');
					$member_offering = $this->request->getPost('member_offering');

					$member = $this->request->getPost('members');
					$offering = $this->request->getPost('offering');
					$guests = $this->request->getPost('guests');
					$guest_offerings = $this->request->getPost('guest_offerings');
					

					$tither = [];
					$tithers = [];
					if (!empty($member) && !empty($offering)) {
						$count = count($offering); 
						for ($i = 0; $i < $count; $i++) {
							if ($offering[$i] <= 0) {
								continue; 
							}
							
							if (!isset($tither[$member[$i]])) {
								$tither[$member[$i]] = $offering[$i];
							}
							
						}
					}

					if (!empty($guests) && !empty($guest_offerings)) {
						$count = count($guest_offerings); 
						for ($i = 0; $i < $count; $i++) {
							if ($guest_offerings[$i] <= 0) {
								continue; 
							}
							
							if (!isset($tithers[$guests[$i]])) {
								$tithers[$guests[$i]] = $guest_offerings[$i];
							}
							
						}
					}

					$offering_list['total'] = $total_offering;
					$offering_list['member'] = $member_offering;
					$offering_list['guest'] = $guest_offering;
					$offering_list['list'] = $tither;
					$offering_list['guest_list'] = $tithers;
					 
					
					$this->session->set('cell_offering', json_encode($offering_list));
					
					echo $this->Crud->msg('success', 'Cell Offering Report Submitted');
					// echo json_encode($mark);
					echo '<script> setTimeout(function() {
						var jsonData = ' . json_encode($offering_list) . ';
						var jsonString = JSON.stringify(jsonData);
						$("#offering_givers").val(jsonString);
						$("#offering").val('.($total_offering).');
						$("#modal").modal("hide");
					}, 2000); </script>';
					
					die;
				}

			} elseif($param2 == 'report'){
				if($param3){
					$resp = [];
					$edit = $this->Crud->read_single('id', $param3, 'cell_report');
					$timers = [];
					$id = 0;
					if(!empty($edit)) {
						foreach($edit as $e) {
							$id = $e->id;
							$note = $e->note;
							$reg_date = $e->reg_date;
							$type = $this->Crud->read_field('id', $e->type, 'service_type', 'name');
							$total_part = 0;
							$member_part = 0;
							$guest_part = 0;
							$total_tithe = 0;
							$member_tithe = 0;
							$guest_tithe = 0;
							$total_offering = 0;
							$member_offering = 0;
							$guest_offering = 0;
							$total_seed = 0;
							$member_seed = 0;
							$guest_seed = 0;
							$total_thanksgiving = 0;
							$member_thanksgiving = 0;
							$guest_thanksgiving = 0;

							$timer = $this->Crud->check3('source_id', $id, 'source_type', 'service', 'category', 'first_timer', 'visitors');
							
							$convert = $this->Crud->check4('new_convert', 1, 'source_id', $e->id, 'source_type', 'service', 'category', 'first_timer', 'visitors');
							$conv = $this->Crud->check3('service_id', $e->id, 'new_convert', 1, 'status', 'present', 'service_attendance');

							$convert += (int)$conv;

							$attendance = $e->attendance;
							if(empty($attendance)){
								$attend = $this->Crud->check2('service_id', $id, 'status', 'present', 'service_attendance');
								$attendance = (int)$attend + (int)$timer;
							}


							$finance = $this->Crud->read_single('service_id', $id, 'service_finance');
							if(!empty($finance)){
								foreach($finance as $f){
									if($f->finance_type == 'offering'){
										$total_offering += (float)$f->amount;
									}
									if($f->finance_type == 'tithe'){
										$total_tithe += (float)$f->amount;
									}
									if($f->finance_type == 'partnership'){
										$total_part += (float)$f->amount;
									}
									if($f->finance_type == 'seed'){
										$total_seed += (float)$f->amount;
									}
									if($f->finance_type == 'thanksgiving'){
										$total_thanksgiving += (float)$f->amount;
									}
								}
							}
							
							$data['id'] = $id;
							$data['service_report_id'] = $id;
							$data['note'] = $note;
							$data['types'] = $type;
							$data['total_part'] = $total_part;
							$data['total_tithe'] = $total_tithe;
							$data['total_offering'] = $total_offering;
							$data['total_seed'] = $total_seed;
							$data['total_thanksgiving'] = $total_thanksgiving;
							$data['convert'] = $convert;
							$data['timer'] = $timer;
							$data['attendance'] = $attendance;
							$data['reg_date'] = $reg_date;
							$data['finance'] = $this->Crud->read_single('service_id', $id, 'service_finance');
							$data['member_attendance'] = $this->Crud->read_single('service_id', $id, 'service_attendance');
							$data['convert_member'] = json_decode($e->converts,true);
							$data['guest_attendance'] = $this->Crud->read3('source_id', $e->id, 'source_type', 'cell', 'category', 'first_timer', 'visitors');
							$data['convert_guest'] = $this->Crud->read4('new_convert', 1, 'source_id', $e->id, 'source_type', 'cell', 'category', 'first_timer', 'visitors');
							
						}
						
					}

					
				}
				
			} elseif($param2 == 'new_convert'){
				
				if($param3) {
					$edit = $this->Crud->read2('type_id', $param3, 'type', 'cell', 'attendance');
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
							$data['d_attendant'] = $e->attendant;
						}
					}
					//When Adding Save in Session
					if($this->request->getMethod() == 'post'){
						$first_name = $this->request->getPost('first_name');
						$surname = $this->request->getPost('surname');
						$email = $this->request->getPost('email');
						$phone = $this->request->getPost('phone');
						$dob = $this->request->getPost('dob');

						$converts = [];
						if(!empty($first_name) || !empty($surname)){
							for($i=0;$i<count($first_name);$i++){
								$converts['fullname'] = $first_name[$i].' '.$surname[$i];
								$converts['email'] = $email[$i];
								$converts['phone'] = $phone[$i];
								$converts['dob'] = $dob[$i];
								
								$convert[] = $converts;
							}
						}
						// echo json_encode($convert);
						if(empty($convert)){
							echo $this->Crud->msg('danger', 'Enter the New Convert Details');
							
						} else{
							$this->session->set('cell_convert', json_encode($convert));
							echo $this->Crud->msg('success', 'New Convert List Submitted');
							// echo json_encode($mark);
							
							echo '<script> setTimeout(function() {
								var jsonData = ' . json_encode($convert) . ';
								var jsonString = JSON.stringify(jsonData);
								$("#converts").val(jsonString);
								$("#new_convert").val('.count($convert).');
								
								$("#modal").modal("hide");
							}, 2000); </script>';
						}
						die;
					}
				}

			} elseif($param2 == 'timers'){
				if($param3){
					$resp = [];
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$timers = [];
					$id = 0;
					if(!empty($edit)) {
						foreach($edit as $e) {
							$timers = $e->timers;
							if(empty($timers)){
								$timers = "[]";
							}
							$id = $e->id;
						}
						
					}

					
				}
				
				if($this->request->getMethod() == 'post'){
					
					$occurrence = 0;
					$church_id = $this->request->getPost('church_id');
					$ministry_id = $this->request->getPost('ministry_id');
					$service = $this->request->getPost('cell_id');
					$invited_by = $this->request->getPost('invited_by');
					$platform = $this->request->getPost('platform');
					$channel = $this->request->getPost('channel');
					$member_id = $this->request->getPost('member_id');
					if($invited_by == 'Member'){
						$channel = $member_id;
					}
					if($invited_by == 'Online'){
						$channel = $platform;
					}

					if(empty($service)){
						echo $this->Crud->msg('danger', 'Select Cell First!!');
						die;
					}
					
					$service_report_id = $service;
					
					
					$ins_data = [
						'ministry_id'        => $ministry_id,
						'channel'          	 => $channel,
						'church_id'          => $church_id,
						'title'              => $this->request->getPost('title'),
						'invited_by'         => $this->request->getPost('invited_by'),
						'fullname'           => $this->request->getPost('fullname'),
						'email'              => $this->request->getPost('email'),
						'phone'              => $this->request->getPost('phone'),
						'dob'                => $this->request->getPost('dob'),
						'gender'             => $this->request->getPost('gender'),
						'address'            => $this->request->getPost('address'),
						'city'               => $this->request->getPost('city'),
						'state'              => $this->request->getPost('state_id'),
						'postal_code'        => $this->request->getPost('postal'),
						'country'            => $this->request->getPost('country'),
						'marital_status'     => $this->request->getPost('marital'),
						'occupation'         => $this->request->getPost('occupation'),
						'connect_method'     => $this->request->getPost('connection'),
						'consider_joining'   => $this->request->getPost('joining') ? 1 : 0,
						'baptised'           => $this->request->getPost('baptised') ? 1 : 0,
						'wants_visit'        => $this->request->getPost('visit') ? 1 : 0,
						'visit_time'         => $this->request->getPost('visit_time'),
						'prayer_request'     => $this->request->getPost('prayer_request'),
						'category'         	=> 'first_timer',
						'source_type'         	=> 'cell',
						'source_id'         	=> $service_report_id,
						'reg_date'           => date('Y-m-d H:i:s'),
					];
			

					$upd_rec = $this->Crud->create('visitors', $ins_data);
					if($upd_rec > 0) {
						echo $this->Crud->msg('success', translate_phrase('First Timer Record Submitted'));

						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $upd_rec, 'visitors', 'fullname');
						$action = $by.' submitted First Timer ('.$upd_rec.') Record';
						$this->Crud->activity('first_timer', $upd_rec, $action, $log_id);

						echo '<script> setTimeout(function() {
							$("#modal").modal("hide");load();
						}, 2000); </script>';
					} else {
						echo $this->Crud->msg('info', translate_phrase('No Changes'));	
					}
					 
					exit;	
				}
				
			} elseif($param2 == 'first_timer'){
				
				if($param3) {
					
					//When Adding Save in Session
					if($this->request->getMethod() == 'post'){
						$first_name = $this->request->getPost('first_name');
						$surname = $this->request->getPost('surname');
						$email = $this->request->getPost('email');
						$phone = $this->request->getPost('phone');
						$dob = $this->request->getPost('dob');
						$invited_by = $this->request->getPost('invited_by');
						$channel = $this->request->getPost('channel');
						$member_id = $this->request->getPost('member_id');

						

						$converts = [];
						if(!empty($first_name) || !empty($surname)){
							for($i=0;$i<count($first_name);$i++){
								$invites = $member_id[$i];
								if($invited_by[$i] != 'Member'){
									$invites = $channel[$i];
								}
								$converts['fullname'] = $first_name[$i].' '.$surname[$i];
								$converts['email'] = $email[$i];
								$converts['phone'] = $phone[$i];
								$converts['dob'] = $dob[$i];
								$converts['invited_by'] = $invited_by[$i];
								$converts['channel'] = $invites;
								
								$convert[] = $converts;
							}
						}
						// echo json_encode($convert);
						// die;
						if(empty($convert)){
							echo $this->Crud->msg('danger', 'Enter the First Timer Details');
							
						} else{
							$this->session->set('cell_timers', json_encode($convert));
							echo $this->Crud->msg('success', 'First Timer List Submitted');
							// echo json_encode($mark);
							echo '<script> setTimeout(function() {
								var jsonData = ' . json_encode($convert) . ';
								var jsonString = JSON.stringify(jsonData);
								$("#timers").val(jsonString);
								$("#first_timer").val('.count($convert).');
								$("#modal").modal("hide");
							}, 2000); </script>';
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
								$data['e_cell_id'] = $e->cell_id;
								$data['e_type'] = $e->type;
								$data['e_date'] = $e->date;
								$data['e_attendance'] = $e->attendance;
								$data['e_new_convert'] = $e->new_convert;
								$data['e_first_timer'] = $e->first_timer;
								$data['e_offering'] = $e->offering;
								$data['e_note'] = $e->note;
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$creport_id = $this->request->getVar('creport_id');
					$cell_id = $this->request->getVar('cell_id');
					$type = $this->request->getVar('type');
					$attendance = $this->request->getVar('attendance');
					$new_convert = $this->request->getVar('new_convert');
					$first_timer = $this->request->getVar('first_timer');
					$offering = $this->request->getVar('offering');
					$note = $this->request->getVar('note');
					$date = $this->request->getVar('dates');
					$attendant = $this->request->getVar('attendant');
					$converts = $this->request->getVar('converts');
					$offering_givers = $this->request->getVar('offering_givers');
					$timers = $this->request->getVar('timers');
					$ministry_id = $this->Crud->read_field('id', $cell_id, 'cells', 'ministry_id');
					$church_id = $this->Crud->read_field('id', $cell_id, 'cells', 'church_id');
					
					// echo $date;die;
					$dates = date('y-m-d', strtotime($date));

					
					$ins_data['cell_id'] = $cell_id;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['type'] = $type;
					$ins_data['date'] = $dates;
					$ins_data['attendance'] = $attendance;
					$ins_data['offering'] = $offering;
					$ins_data['offering_givers'] = $offering_givers;
					$ins_data['note'] = $note;
					
					if(!empty($attendant)){$attend = $attendant;}else{$attend = $this->session->get('cell_attendance');}
					if(!empty($converts)){$conv = $converts;}else{$conv = $this->session->get('cell_convert');}
					if(!empty($timers)){$times = $timers;}else{$times = $this->session->get('cell_timers');}
					// do create or update
					if($creport_id) {
						
						$ins_data['attendant'] = $attend;
						$ins_data['converts'] = $conv;
						$ins_data['timers'] = $times;
								
						$upd_rec = $this->Crud->updates('id', $creport_id, $table, $ins_data);
						if($upd_rec > 0) {
							$at['attendant'] = $this->session->get('cell_attendance');
							$at_id = $this->Crud->read_field2('type_id', $creport_id, 'type', 'cell', 'attendance', 'id');
							$this->Crud->updates('id', $at_id, 'attendance', $at);

							$first = json_decode($times, true);
							$converts = json_decode($converts, true);
							
							$ins['source_type'] = 'cell';
							$ins['source_id'] = $creport_id;
							$ins['ministry_id'] = $ministry_id;
							$ins['church_id'] = $church_id;


							$this->session->set('cell_attendance', '');
							$this->session->set('cell_convert', '');
							
							$this->session->set('cell_timers', '');
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by.' updated Cell Meeting Report';
							$this->Crud->activity('user', $cell_id, $action);

							echo $this->Crud->msg('success', 'Report Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						// echo $date;
						if($this->Crud->check2('cell_id', $cell_id, 'date', $dates, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_data['attendant'] = $this->session->get('cell_attendance');
							$ins_data['converts'] = $this->session->get('cell_convert');
							$ins_data['timers'] = $this->session->get('cell_timers');
							
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								$at['type'] = 'cell';
								$at['type_id'] = $ins_rec;
								$at['attendant'] = $this->session->get('cell_attendance');
								$at['reg_date'] = date(fdate);
								$this->Crud->create('attendance', $at);

								//Create Follow up
								$first = json_decode($times, true);
								$converts = json_decode($converts, true);
								
								$ins['source_type'] = 'cell';
								$ins['source_id'] = $ins_rec;
								$ins['ministry_id'] = $ministry_id;
								$ins['church_id'] = $church_id;
								$ins['visit_date'] = $dates;

								
								$this->session->set('cell_attendance', '');
								$this->session->set('cell_convert', '');
								$this->session->set('cell_timers', '');
								
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$action = $by.' created a Cell Meeting Report for ('.$date.')';
								$this->Crud->activity('user', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Report Created');
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

		if($param1 == 'offering_list') {
			// DataTable parameters
			$cell_id = $param2;
			$table = 'user';
			$column_order = array('firstname', 'surname');
			$column_search = array('firstname', 'surname');
			$order = array('firstname' => 'asc');
			$member_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
			$where = array('cell_id' => $cell_id);
			
			// load data into table
			$list = $this->Crud->datatable_load($table, $column_order, $column_search, $order, $where);
			$data = array();
			// $no = $_POST['start'];
			
			$count = 1;
			foreach ($list as $item) {
				$id = $item->id;
				$name = $item->firstname;
				$surname = $item->surname;
				$img = $this->Crud->image($item->img_id, 'big');
				// add manage buttons
				$value = '0';
				if($param3){
					$convertsa = json_decode($this->Crud->read_field('id', $param3, 'cell_report', 'offering_givers'));
					if(!empty($convertsa)){
						$converts =(array) $convertsa->list;
						if(!empty($converts)){
							foreach($converts as $co => $val){
								if($id == $co){
									$value = $val;
								}
							}
						}
					}	
				}
				
				$all_btn = '
					<div class="text-center">
						<input type="text" class="form-control offerings" name="offering[]" id="offering_'.$item->id.'" value="'.$value.'" oninput="calculateTotals();this.value = this.value.replace(/[^\d.]/g,\'\');this.value = this.value.replace(/(\..*)\./g,\'$1\')">
					</div>
				';

				
				
				$row = array();
				$row[] = '<div class="user-card">
							<div class="user-avatar ">
								<img alt="" src="'.site_url($img).'" height="40px"/>
							</div>
							<div class="user-info">
								<span class="tb-lead">'.ucwords($item->firstname.' '.$item->surname).'</span>
							</div>
							<input type="hidden" name="members[]" value="'.$item->id.'">
						</div>';
				$row[] = $all_btn;
	
				$data[] = $row;
				$count += 1;
			}
	
			$output = array(
				"draw" => intval($_POST['draw']),
				"recordsTotal" => $this->Crud->datatable_count($table, $where),
				"recordsFiltered" => $this->Crud->datatable_filtered($table, $column_order, $column_search, $order, $where),
				"data" => $data,
			);
			
			//output to json format
			echo json_encode($output);
			exit;
		}

		if($param1 == 'records'){
			if($param2 == 'load_zones'){
				$region_id = $this->request->getPost('region_id');
				$region_list = array();
				$cell_list = array();
				
				$church = $this->Crud->read2_order('regional_id', $region_id, 'type', 'zone', 'church', 'name', 'asc');
				if(!empty($church)){
					foreach($church as $ch){
						$list['id'] = $ch->id;
						$list['name'] = $ch->name;
						$region_list[] = $list;
					}
				}

				$churchs = $this->Crud->read_single_order('regional_id', $region_id, 'church', 'name', 'asc');
				if(!empty($churchs)){
					foreach($churchs as $ch){
						$cel = $this->Crud->read_single_order('church_id', $ch->id, 'cells', 'name', 'asc');
						if(!empty($cel)){
							foreach($cel as $cl){
								$c_list['id'] = $cl->id;
								$c_list['name'] = ucwords($cl->name.' - '.$ch->name.'('.$ch->type.')');
								$cell_list[] = $c_list;
							}
						}
						
					}
				}

				$resp['zones'] = $region_list;
				$resp['cells'] = $cell_list;
				echo json_encode($resp);
				die;
			}
			if($param2 == 'load_groups'){
				$region_id = $this->request->getPost('zone_id');
				$region_list = array();
				$cell_list = array();
				
				$church = $this->Crud->read2_order('zonal_id', $region_id, 'type', 'group', 'church', 'name', 'asc');
				if(!empty($church)){
					foreach($church as $ch){
						$list['id'] = $ch->id;
						$list['name'] = $ch->name;
						$region_list[] = $list;
					}
				}

				$churchs = $this->Crud->read_single_order('zonal_id', $region_id, 'church', 'name', 'asc');
				if(!empty($churchs)){
					foreach($churchs as $ch){
						$cel = $this->Crud->read_single_order('church_id', $ch->id, 'cells', 'name', 'asc');
						if(!empty($cel)){
							foreach($cel as $cl){
								$c_list['id'] = $cl->id;
								$c_list['name'] = ucwords($cl->name.' - '.$ch->name.'('.$ch->type.')');
								$cell_list[] = $c_list;
							}
						}
						
					}
				}

				$resp['groups'] = $region_list;
				$resp['cells'] = $cell_list;
				echo json_encode($resp);
				die;
			}
			if($param2 == 'load_churches'){
				$region_id = $this->request->getPost('group_id');
				$region_list = array();
				$cell_list = array();
				
				$church = $this->Crud->read2_order('group_id', $region_id, 'type', 'church', 'church', 'name', 'asc');
				if(!empty($church)){
					foreach($church as $ch){
						$list['id'] = $ch->id;
						$list['name'] = $ch->name;
						$region_list[] = $list;
					}
				}

				$churchs = $this->Crud->read_single_order('group_id', $region_id, 'church', 'name', 'asc');
				if(!empty($churchs)){
					foreach($churchs as $ch){
						$cel = $this->Crud->read_single_order('church_id', $ch->id, 'cells', 'name', 'asc');
						if(!empty($cel)){
							foreach($cel as $cl){
								$c_list['id'] = $cl->id;
								$c_list['name'] = ucwords($cl->name.' - '.$ch->name.'('.$ch->type.')');
								$cell_list[] = $c_list;
							}
						}
						
					}
				}

				$resp['churches'] = $region_list;
				$resp['cells'] = $cell_list;
				echo json_encode($resp);
				die;
			}

			if($param2 == 'load_cells'){
				$region_id = $this->request->getPost('church_id');
				$region_list = array();
				$cell_list = array();
				$church_type = $this->Crud->read_field('id', $region_id, 'church', 'type');
				$church_name = $this->Crud->read_field('id', $region_id, 'church', 'name');
				
				$cel = $this->Crud->read_single_order('church_id', $region_id, 'cells', 'name', 'asc');
				if(!empty($cel)){
					foreach($cel as $cl){
						$c_list['id'] = $cl->id;
						$c_list['name'] = ucwords($cl->name.' - '.$church_name.'('.$church_type.')');
						$cell_list[] = $c_list;
					}
				}
						
					

				$resp['churches'] = $region_list;
				$resp['cells'] = $cell_list;
				echo json_encode($resp);
				die;
			}

			if($param2 == 'get_cells_by_scope'){
				$scope = $this->request->getPost('scope');
				$selected_churches = $this->request->getPost('selected_churches') ?? [];
				$log_id = $this->session->get('td_id');

				if (!$log_id) {
					return $this->response->setJSON(['status' => false, 'message' => 'Session expired']);
				}

				$cells = [];
				$church_ids = [];

				if ($scope == 'own') {
					$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
					$church_ids[] = $church_id;
				} elseif ($scope == 'all') {
					$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
					$type = $this->Crud->read_field('id', $church_id, 'church', 'type');

					switch ($type) {
						case 'region': $sub = $this->Crud->read_single('regional_id', $church_id, 'church'); break;
						case 'zone': $sub = $this->Crud->read_single('zonal_id', $church_id, 'church'); break;
						case 'group': $sub = $this->Crud->read_single('group_id', $church_id, 'church'); break;
						case 'church':
						default:
							$sub = [];
					}

					$church_ids[] = $church_id;
					foreach ($sub as $s) $church_ids[] = $s->id;

				} elseif ($scope == 'selected' && !empty($selected_churches)) {
					$church_ids = $selected_churches;
				}

				if (!empty($church_ids)) {
					foreach ($church_ids as $cid) {
						$cs = $this->Crud->read_single('church_id', $cid, 'cells');
						foreach ($cs as $c) {
							$cells[] = [
								'id' => $c->id,
								'name' => ucwords($c->name)
							];
						}
					}
				}

				return $this->response->setJSON(['status' => true, 'data' => $cells]);
			}

			if($param2 == 'get_churches'){
				$log_id = $this->session->get('td_id');
				$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
				$type = $this->Crud->read_field('id', $church_id, 'church', 'type');
				$churches = [];

				switch ($type) {
					case 'region': $records = $this->Crud->read_single('regional_id', $church_id, 'church'); break;
					case 'zone': $records = $this->Crud->read_single('zonal_id', $church_id, 'church'); break;
					case 'group': $records = $this->Crud->read_single('group_id', $church_id, 'church'); break;
					default: $records = [];
				}

				foreach ($records as $c) {
					$churches[] = ['id' => $c->id, 'name' => ucwords($c->name)];
				}

				return $this->response->setJSON(['status' => true, 'data' => $churches]);
			}


		}
		if($param1 == 'load_cells'){
			$level = $this->request->getPost('level');
			$ministry_id = $this->request->getPost('ministry_id');
			
			$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
			$type = $this->Crud->read_field('id', $church_id, 'church', 'type');

			$cells = array();
			$level_status = false;
			$region_list = array();
			$zone_list = array();
			$group_list = array();
			$church_list = array();
			if($role != 'church leader' && $role != 'cell leader'&& $role != 'cell executive' && $role != 'assistant cell leader'){
				$level_status = true;
			}

			if($ministry_id != 'all'){
				$church = $this->Crud->read_single_order('ministry_id', $ministry_id, 'church', 'name', 'asc');
				if(!empty($church)){
					foreach($church as $ch){
						$cell = $this->Crud->read_single('church_id', $ch->id, 'cells');
						if(!empty($cell)){
							foreach($cell as $ce){
								$cellsa['id'] = $ce->id;
								$cellsa['name'] = $ce->name;
								
								$cells[] = $cellsa;
							}
						}

					}
				}
				$regions = $this->Crud->read2_order('ministry_id', $ministry_id, 'type', 'region', 'church', 'name', 'asc');
				if(!empty($regions)){
					foreach($regions as $r){
						$region['id'] = $r->id;
						$region['name'] = ($r->name);

						$region_list[] = $region;
					}
				}
				$zones = $this->Crud->read2_order('ministry_id', $ministry_id, 'type', 'zone', 'church', 'name', 'asc');
				if(!empty($zones)){
					foreach($zones as $r){
						$zone['id'] = $r->id;
						$zone['name'] = ($r->name);

						$zone_list[] = $zone;
					}
				}
				$groups = $this->Crud->read2_order('ministry_id', $ministry_id, 'type', 'group', 'church', 'name', 'asc');
				if(!empty($groups)){
					foreach($groups as $r){
						$group['id'] = $r->id;
						$group['name'] = ($r->name);

						$group_list[] = $group;
					}
				}
				$churches = $this->Crud->read2_order('ministry_id', $ministry_id, 'type', 'church', 'church', 'name', 'asc');
				if(!empty($churches)){
					foreach($churches as $r){
						$church['id'] = $r->id;
						$church['name'] = ($r->name);

						$church_list[] = $church;
					}
				}
			} else{
				$church = $this->Crud->read_order('church', 'name', 'asc');
				if(!empty($church)){
					foreach($church as $ch){
						$cell = $this->Crud->read_single('church_id', $ch->id, 'cells');
						if(!empty($cell)){
							$cells = [];
							foreach($cell as $ce){
								$cellsa['id'] = $ce->id;
								$cellsa['name'] = ucwords($ce->name.' - '.$ch->name.'('.$ch->type.')');
								
								$cells[] = $cellsa;
							}
						}

					}
				}
				$regions = $this->Crud->read_single_order('type', 'region', 'church', 'name', 'asc');
				if(!empty($regions)){
					foreach($regions as $r){
						$region['id'] = $r->id;
						$region['name'] = ($r->name);

						$region_list[] = $region;
					}
				}
				$zones = $this->Crud->read_single_order( 'type', 'zone', 'church', 'name', 'asc');
				if(!empty($zones)){
					foreach($zones as $r){
						$zone['id'] = $r->id;
						$zone['name'] = ($r->name);

						$zone_list[] = $zone;
					}
				}
				
				$groups = $this->Crud->read_single_order('type', 'group', 'church', 'name', 'asc');
				if(!empty($groups)){
					foreach($groups as $r){
						$group['id'] = $r->id;
						$group['name'] = ($r->name);

						$group_list[] = $group;
					}
				}
				$churches = $this->Crud->read_single_order( 'type', 'church', 'church', 'name', 'asc');
				if(!empty($churches)){
					foreach($churches as $r){
						$church['id'] = $r->id;
						$church['name'] = ($r->name);

						$church_list[] = $church;
					}
				}

			}
			

			if($role != 'ministry administrator'){
				$level_status = true;
				$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
				$church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
				$church_name = $this->Crud->read_field('id', $church_id, 'church', 'name');
				$cells = [];
				$cel = $this->Crud->read_single_order('church_id', $church_id, 'cells', 'name', 'asc');
				if(!empty($cel)){
					foreach($cel as $cl){
						$c_list['id'] = $cl->id;
						$c_list['name'] = ucwords($cl->name.' - '.$church_name.'('.$church_type.')');
						$cells[] = $c_list;
					}
				}
					
			}

			$resp['cells'] = $cells;
			$resp['region_list'] = $region_list;
			$resp['zone_list'] = $zone_list;
			$resp['group_list'] = $group_list;
			$resp['church_list'] = $church_list;
			$resp['level_status'] = $level_status;
			echo json_encode($resp);
			die;
		}

        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 45;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			$meeting_type = $this->request->getVar('meeting_type') ?? '';
			$cell_id = $this->request->getVar('cell_id') ?? '';
			$start_date = $this->request->getVar('start_date') ?? '';
			$end_date = $this->request->getVar('end_date') ?? '';
			$church_scope = $this->request->getVar('church_scope') ?? 'own'; // can be own, selected, all
			$selected_churches = $this->request->getVar('selected_churches') ?? [];
			
			$items = '';
			$a = 1;
			
			$log_id = $this->session->get('td_id');
			if (!$log_id) {
				$item = '<div class="text-center text-muted">' . translate_phrase('Session Timeout! - Please login again') . '</div>';
			} else {
				$switch_id = $this->session->get('switch_church_id');
			
				// Count total records
				$all_rec = $this->Crud->filter_cell_report('', '', $search, $log_id, $start_date, $end_date, $cell_id, $meeting_type, $church_scope, $selected_churches, $switch_id);
				$counts = !empty($all_rec) ? count($all_rec) : 0;
			
				// Get paginated records
				$query = $this->Crud->filter_cell_report($limit, $offset, $search, $log_id, $start_date, $end_date, $cell_id, $meeting_type, $church_scope, $selected_churches, $switch_id);
				$data['count'] = $counts;

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$type = $q->type;
						$cell_id = $q->cell_id;
						$church_id = $q->church_id;
						$attendance = $q->attendance;
						$offering = $q->offering;
						$decodedConverts = json_decode($q->converts, true); // decode as array
						$nattend = is_array($decodedConverts) ? count($decodedConverts) : 0;
						
						// echo $nattend;
						$first_timer = $q->first_timer;
						$date = date('d M Y', strtotime($q->date));
						$reg_date = $q->reg_date;

						
						$first_timer = $this->Crud->check3('category','first_timer', 'source_type', 'cell', 'source_id', $q->id, 'visitors');
						$attendance = $q->attendance;
						if(empty($attendance)){
							$attendance = (int)$first_timer;
						}
						$convert = $this->Crud->check4('new_convert', 1, 'category','first_timer', 'source_type', 'cell', 'source_id', $q->id, 'visitors');
						$new_convert = (int)$convert + (int)$nattend;


						$types = '';
						if($type == 'wk1')$types = 'WK1 - Prayer and Planning';
						if($type == 'wk2')$types = 'Wk2 - Bible Study';
						if($type == 'wk3')$types = 'Wk3 - Bible Study';
						if($type == 'wk4')$types = 'Wk4 - Fellowship / Outreach';
						if($type == 'wk5')$types = 'Wk5 - Fellowship';
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
						$cell = '<br><span class="small text-"><em class="icon ni ni-curve-down-right"></em> <span>'.strtoupper($this->Crud->read_field('id', $cell_id, 'cells', 'name').' - '.$church).'</span></span>';
						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								<li><a href="javascript:;" class="text-success pop" pageTitle="View Report" pageName="' . site_url($mod . '/manage/report/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary" onclick="edit_report('.$id.')"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" class="text-success pop" pageTitle="View Report" pageName="' . site_url($mod . '/manage/report/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								<li> <a href="javascript:;"  id="firstTimerBtnz" data-bs-toggle="tooltip" data-bs-placement="top" title="Add First Timer" class="text-info pop"  pageTitle="'.translate_phrase('Add First Timer').'" pageName="'.site_url('accounts/creport/manage/timers/' . $id).'"  pageSize="modal-xl">
										<em class="icon ni ni-plus-c"></em><span> First Timer</span>
								</a>
								</li>
							';

							}
							
						}

						$item .= '
							<tr>
								<td>
									<span class="tb-lead small">' . ucwords($date) . '</span>
									' . $cell . '
								</td>
								<td>
									<span class="text- small">' . ucwords($types) . '</span>
								</td>
								<td>
									<span class="text- small">$' . number_format($offering, 2) . '</span>
								</td>
								<td>
									<span class="text- small">' . ucwords($attendance) . '</span>
								</td>
								<td>
									<span class="text- small">' . ucwords($first_timer) . '</span>
								</td>
								<td>
									<span class="text- small">' . ucwords($new_convert) . '</span>
								</td>
								<td class="text-center">
									<div class="dropdon">
										<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown">
											<em class="icon ni ni-more-h"></em>
										</a>
										<div class="dropdown-menu dropdown-menu-end">
											<ul class="link-list-opt no-bdr">
												' . $all_btn . '
											</ul>
										</div>
									</div>
								</td>
							</tr>';

						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-linux-server" style="font-size:100px;"></i><br/><br/>'.translate_phrase('No Report Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items . $item;
				if($offset >= 45){
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Cell Report').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }


	public function givings($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/givings';

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
       
		
		$table = 'partners_history';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
        $data['current_language'] = $this->session->get('current_language');
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_giving_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$action = $by.' deleted Giving Partnership Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Givings Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
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
								$data['e_member_id'] = $e->member_id;
								$data['e_church_id'] = $e->church_id;
								$data['e_level'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_partnership_id'] = $e->partnership_id;
								$data['e_amount_paid'] = $e->amount_paid;
								$data['e_date_paid'] = $e->date_paid;
								$data['e_status'] = $e->status;
								$data['e_img'] = $e->file;
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$giving_id = $this->request->getVar('giving_id');
					$member_id = $this->request->getVar('member_id');
					$partnership_id = $this->request->getVar('partnership_id');
					$amount = $this->request->getVar('amount');
					$status = $this->request->getVar('status');
					$date_paid = $this->request->getVar('date_paid');
					$currency = $this->request->getVar('currency');
					$img_id = $this->request->getVar('img');

					$church_id = $this->Crud->read_field('id', $member_id, 'user', 'church_id');
					$ministry_id = $this->Crud->read_field('id', $member_id, 'user', 'ministry_id');
					$country_id =  $this->Crud->read_field('id', $church_id, 'church', 'country_id');
					$currency_id =  $this->Crud->read_field('country_id', $country_id, 'currency', 'id');
					
					if($currency <= 0){
						$amount = $this->Crud->finance_exchange($amount,$currency_id);
					}
					
					 //// Image upload
					 if(file_exists($this->request->getFile('pics'))) {
						if(!empty($img_id)){
							unlink(FCPATH . $img_id);
						}

						$path = 'assets/images/givings/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);
						
						if(!empty($getImg->path)) $img_id = $getImg->path;
					}

					if($role == 'member'){
						if(empty($img_id)){
							echo $this->Crud->msg('danger', 'Upload Payment Receipt');
							die;
						}
					}
					
					$ins_data['member_id'] = $member_id;
					$ins_data['partnership_id'] = $partnership_id;
					$ins_data['amount_paid'] = $amount;
					$ins_data['status'] = $status;
					$ins_data['date_paid'] = $date_paid;
					$ins_data['file'] = $img_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['ministry_id'] = $ministry_id;
					
					// do create or update
					if($giving_id) {
						$upd_rec = $this->Crud->updates('id', $giving_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $partnership_id, 'partnership', 'name');
							$action = $by.' updated ('.$code.') Partnership Giving Record';
							$this->Crud->activity('user', $giving_id, $action);

							echo $this->Crud->msg('success', 'Record Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						
						$ins_data['reg_date'] = date(fdate);
						$ins_rec = $this->Crud->create($table, $ins_data);
						if($ins_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $member_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $partnership_id, 'partnership', 'name');
							$action = $by.' paid ('.$code.') Partnership';
							$this->Crud->activity('user', $ins_rec, $action);

							echo $this->Crud->msg('success', 'Giving Paid Successful');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');	
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

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			if(!empty($this->request->getPost('start_date'))) { $start_date = $this->request->getPost('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getPost('end_date'))) { $end_date = $this->request->getPost('end_date'); } else { $end_date = ''; }
			if(!empty($this->request->getPost('partnership'))) { $partnership_id = $this->request->getPost('partnership'); } else { $partnership_id = ''; }
			
			$items = '
				
				<!-- .nk-tb-item -->
		
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_givings('', '', $search, $log_id, $start_date, $end_date, $partnership_id, $switch_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_givings($limit, $offset, $search, $log_id, $start_date, $end_date, $partnership_id, $switch_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$member_id = $q->member_id;
						$church_id = $q->church_id;
						$partnership_id = $q->partnership_id;
						$amount_paid = $q->amount_paid;
						$status = $q->status;
						$reg_date = date('d M Y', strtotime($q->date_paid));
						$member = $this->Crud->read_field('id', $member_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $member_id, 'user', 'surname');
						$partnership = $this->Crud->read_field('id', $partnership_id, 'partnership', 'name');
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
						$cell_id = $this->Crud->read_field('id', $member_id, 'user', 'cell_id');
						$cell = $this->Crud->read_field('id', $cell_id, 'cells', 'name');

						$st = '<span class="text-warning">Pending</span>';
						if($status > 0){
							$st = '<span class="text-success">Confirmed</span>';
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								
								
							';
							}
							
						}

						if($role == 'church leader'){
							$church = $cell;
						}
						$item .= '
							<tr>
								<td>
									<div class="user-info">
										<span class="tb-lead">' . ucwords($reg_date) . ' </span>
									</div>
								</td>
								<td>
									<span class="text">' . ucwords($member) . '</span><br>
									<span class="text-"><em class="icon ni ni-curve-down-right"></em>'.ucwords($church).'</span>
								</td>
								<td>
									<span class="text">' . ucwords($partnership) . '</span>
								</td>
								<td>
									<span class="text">'.$this->session->get('currency') . number_format($this->Crud->cur_exchange($amount_paid),2) . '</span>
								</td>
								<td>
									<span class="text">' . ($st) . '</span>
								</td>
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
							</tr><!-- .nk-tb-item -->
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-cc-secure" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Givings Returned').'
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Givings').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	public function analytics($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/analytics';

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
       
		
		$table = 'partners_history';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
        $data['current_language'] = $this->session->get('current_language');
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_giving_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$action = $by.' deleted Giving Partnership Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Cell Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
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
								$data['e_member_id'] = $e->member_id;
								$data['e_partnership_id'] = $e->partnership_id;
								$data['e_amount_paid'] = $e->amount_paid;
								$data['e_status'] = $e->status;
								$data['e_img'] = $e->file;
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$giving_id = $this->request->getVar('giving_id');
					$member_id = $this->request->getVar('member_id');
					$partnership_id = $this->request->getVar('partnership_id');
					$amount = $this->request->getVar('amount');
					$status = $this->request->getVar('status');
					$img_id = $this->request->getVar('img');
					
					 //// Image upload
					 if(file_exists($this->request->getFile('pics'))) {
						if(!empty($img_id)){
							unlink(FCPATH . $img_id);
						}

						$path = 'assets/images/givings/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);
						
						if(!empty($getImg->path)) $img_id = $getImg->path;
					}
					
					$ins_data['member_id'] = $member_id;
					$ins_data['partnership_id'] = $partnership_id;
					$ins_data['amount_paid'] = $amount;
					$ins_data['status'] = $status;
					$ins_data['file'] = $img_id;
					
					// do create or update
					if($giving_id) {
						$upd_rec = $this->Crud->updates('id', $giving_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $partnership_id, 'partnership', 'name');
							$action = $by.' updated ('.$code.') Partnership Giving Record';
							$this->Crud->activity('user', $giving_id, $action);

							echo $this->Crud->msg('success', 'Record Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if(empty($this->Crud->read_field('id', $member_id, 'user', 'partnership'))){
							echo $this->Crud->msg('danger', 'This Member does not  have a Partnership Record');
						}else{
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $member_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $partnership_id, 'partnership', 'name');
								$action = $by.' paid ('.$code.') Partnership';
								$this->Crud->activity('user', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Giving Paid Successful');
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
		$data['p_start_date'] = $this->session->get('p_start_date');
		$data['p_end_date'] = $this->session->get('p_end_date');
		

        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			if(!empty($this->request->getVar('start_date'))) { $start_date = $this->request->getVar('start_date'); } else { $start_date = date('Y-01-01'); }
			if(!empty($this->request->getVar('end_date'))) { $end_date = $this->request->getVar('end_date'); } else { $end_date = date('Y-m-d'); }
			$this->session->set('p_start_date', $start_date);
			$this->session->set('p_end_date', $end_date);
		
			$church_scope = $this->request->getPost('church_scope');
			$selected_churches = $this->request->getPost('selected_churches'); // array from multi-select
			$cell_id = $this->request->getPost('cell_id');
		
			
			$items = '
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
					
				$all_rec = $this->Crud->read_order('partnership', 'name', 'asc');
				// $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$query = $this->Crud->read_order('partnership', 'name', 'asc');
				$data['count'] = $counts;
				
				$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
				$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
				
				$roles_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
				
						$given = $goal = $balance = 0;
						$p_participant = $g_participant = 0;
				
						// Step 1: Filter members based on church_scope and cell_id
						$users = $this->Crud->filter_membership('', '', $log_id, '', $switch_id, 'false', '', $church_scope, $selected_churches, $cell_id);
				
						$member_ids = [];
				
						if (!empty($users)) {
							foreach ($users as $u) {
								$member_ids[] = $u->id;
				
								$parts = json_decode($u->partnership ?? '{}', true);
								if (!empty($parts[$id])) {
									$amount = (float)$parts[$id];
									$goal += $amount;
									if ($amount > 0) {
										$p_participant++;
									}
								}

								$g_participant = $this->Crud->date_check3($start_date, 'date_paid', $end_date, 'date_paid', 'status', 1, 'partnership_id', $id, 'member_id', $u->id,'partners_history');
		
								// Get the total amount paid within the date range
								$paids = $this->Crud->date_range3($start_date, 'date_paid', $end_date, 'date_paid', 'partnership_id', $id, 'status', 1, 'member_id', $u->id, 'partners_history');
								
								// print_r($paids);
								foreach ($paids as $p) {
									$given += (float)$p->amount_paid;
								}
							}
						}
				
						
						
						// Step 3: Compute balance
						$balance = max(0, $goal - $given);
				
						// Step 4: Manage buttons
						$all_btn = ($role_u == 1) ? '
							<li><a href="javascript:;" class="text-primary pop" pageTitle="View ' . $name . '" pageName="' . site_url($mod . '/manage/view/' . $id) . '">
								<em class="icon ni ni-eye"></em><span>' . translate_phrase('View') . '</span>
							</a></li>' : '';
				
						// Step 5: Generate row
						$item .= '
							<tr>
								<td><span class="small tb-lead">' . ucwords($name) . '</span></td>
								<td><span class="small tb-lead">' . $this->session->get('currency') . number_format($goal, 2) . '</span></td>
								<td><span class="small tb-lead">' . number_format($p_participant) . '</span></td>
								<td><span class="small tb-lead">' . $this->session->get('currency') . number_format($given, 2) . '</span></td>
								<td><span class="small tb-lead">' . $this->session->get('currency') . number_format($balance, 2) . '</span></td>
								<td><span class="small tb-lead">' . number_format($g_participant) . '</span></td>
								<td class="text-center">' . $all_btn . '</td>
							</tr>';
					}
				}
				
				
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-cc-secure" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Givings Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Partnership Analytics').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	//Customer
	public function membership($param1='', $param2='', $param3='', $param4='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/membership';

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
            // return redirect()->to(site_url('dashboard'));	
        }
        $data['log_id'] = $log_id;
        $data['role'] = $role;
        $data['role_c'] = $role_c;
       
		
		$table = 'user';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2;}
		if($param2){$form_link .= '/'.$param3;}
		if($param3){$form_link .= '/'.$param4;}
		
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
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_membership_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'user', 'surname');
						$action = $by.' deleted Membership ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Membership Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
					}
				}
			}  elseif ($param2 == 'admin_send') {
				if ($param3) {
					$admin_id = $param3;
					if ($admin_id) {
						$surname = $this->Crud->read_field('id', $admin_id, 'user', 'surname');
						$firstname = $this->Crud->read_field('id', $admin_id, 'user', 'firstname');
						$role_id = $this->Crud->read_field('id', $admin_id, 'user', 'role_id');
						$roles = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
						$othername = $this->Crud->read_field('id', $admin_id, 'user', 'othername');
						$user_no = $this->Crud->read_field('id', $admin_id, 'user', 'user_no');
						$title = $this->Crud->read_field('id', $admin_id, 'user', 'title');
						$email = $this->Crud->read_field('id', $admin_id, 'user', 'email');
						$phone = $this->Crud->read_field('id', $admin_id, 'user', 'phone');
						$ministry_id = $this->Crud->read_field('id', $admin_id, 'user', 'ministry_id');
						$qrcode = $this->Crud->read_field('id', $admin_id, 'user', 'qrcode');
						$church_id = $this->Crud->read_field('id', $admin_id, 'user', 'church_id');
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');

						$name = ucwords($firstname . ' ' . $othername . ' ' . $surname);
						$reset_link = site_url('auth/email_verify?uid=' . $user_no);
						$name = ucwords($firstname . ' ' . $othername . ' ' . $surname);
							$churchName = ucwords($church);
							$userNo = 'CEAM-00' . $admin_id;
							$reset_link = site_url('auth/email_verify?uid=' . $userNo);
							$qr_code_url = site_url($qrcode);
			
							$subject = 'A Warm Welcome to '.ucwords($church).' - Your Membership & QR Code Access';
			
							$body = '
								Dear ' . ucwords(strtolower($title . ' ' . $firstname)) . ',<br><br>
			
								We are thrilled to welcome you to <strong>' . $churchName . '</strong> platform.<br><br>
			
								You have created an account successfully. Below are your account details:<br><br>
			
								<ul>
									<li><strong>Website:</strong> ' . site_url() . '</li>
									<li><strong>Membership ID:</strong> ' . $userNo . '</li>
									<li><strong>Email:</strong> ' . $email . '</li>
									<li><strong>Phone:</strong> ' . $phone . '</li>
								</ul>
			
								<p>To ensure the security of your account, please set your password by clicking the link below:</p>
								<p><a href="' . htmlspecialchars($reset_link) . '">🔐 Set Your Password</a></p>
			
								<hr>
			
								<h4 style="margin-top: 20px;">📲 Your Personalized QR Code</h4>
								<p>As part of our commitment to serving you better, we have introduced a smart way to stay connected using your personal QR Code.</p>
								
								<p>This code allows you to mark your attendance effortlessly at all our services and special events.</p>
								
								<p style="text-align: center;">
									<img src="' . $qr_code_url . '" alt="QR Code" width="180" height="180" style="margin-top: 10px;" />
								</p>
			
								<p>Every time you attend church, simply scan your code — its quick, easy, and ensures we stay connected and in sync as a family of faith.</p>
			
								<hr>
			
								<p><strong>Note:</strong> Never disclose your login credentials or QR code to anyone to avoid unauthorized access.</p>
								
								<p>If you have any questions, feel free to contact our support team.</p>
			
								<p>We are so glad you are here! 🎉</p>
			
								<br>
								With love and blessings,<br><br>
			
								<strong>' . $churchName . '</strong><br>
								Digital Team
							';
			
							
						$data['body'] = $body;
						if ($this->request->getMethod() == 'post') {
							$head = 'A Warm Welcome to '.ucwords($church).' - Your Membership & QR Code Access';
			
							// 🔁 Send Email
							$upd_rec = $this->Crud->mailgun($email, $head, $body, $church);
							
							
							if (!empty($upd_rec)) {
								$mailgun_response = json_decode($upd_rec, true); // Decode the JSON response

								if (isset($mailgun_response['message']) && stripos($mailgun_response['message'], 'Queued') !== false) {
									echo $this->Crud->msg('success', 'Login Credential Sent to Email Successfully');
									echo '<script>
											load("","");
											$("#modal").modal("hide");
										</script>';
								} else {
									echo $this->Crud->msg('danger', 'Error Sending Email');
								}
							} else {
								echo $this->Crud->msg('danger', 'Error Sending Email');
							}
							
							die;
						}

					}

				}
			} elseif($param2 == 'upload'){ 
				if ($this->request->getMethod() == 'post' && $param3 === 'download') {
					$filePath = FCPATH . 'assets/membership_template.xlsx';
				
					if (is_file($filePath)) {
						return $this->response->download($filePath, null)
							->setFileName('membership_upload_template.xlsx')
							->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					}
				
					return $this->response->setStatusCode(404)->setBody('File not found.');
				}
				
				
				if($this->request->getMethod() == 'post'){
					$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
					$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
					$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
					$cell_id = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
					$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
					$file = $this->request->getFile('csv_file');
					$records = $this->Crud->processFile($file);
					// $record = json_decode($records);
					
					$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
					$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
      

					function clean($input) {
						return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
					}

					if ($file->isValid()) {
						$filePath = WRITEPATH . 'assets/uploads/' . $file->getName();
						$file->move(WRITEPATH . 'assets/uploads/', $file->getName());
						
						// print_r($records);
						// die;
						// Read the file and insert records into the database
						if(!empty($records)){
							$member_email = [];

							$success = 0;
							$failed = 0;
							$exist = 0;

							foreach ($records as $dt => $val) {
								// Sanitize all fields
								$firstname            = clean($val['firstname'] ?? '');
								$othername            = clean($val['othername'] ?? '');
								$surname              = clean($val['surname'] ?? '');
								$email                = strtolower(clean($val['email'] ?? ''));
								$address              = strtolower(clean($val['address'] ?? ''));
								$phone                = preg_replace('/[^0-9]/', '', $val['phone'] ?? '');
								$dob                  = clean($val['dob'] ?? '');
								$marital_status       = strtolower(clean($val['marital_status'] ?? ''));
								$marriage_anniversary = clean($val['marriage_anniversary'] ?? '');
								$title                = ucwords(clean($val['title'] ?? ''));
								$gender               = ucwords(clean($val['gender'] ?? ''));
								$chat_handle          = strtolower(clean($val['chat_handle'] ?? ''));
								$job                  = clean($val['job'] ?? '');
								$foundation_school    = strtolower(clean($val['foundation_school'] ?? ''));
								$baptism              = strtolower(clean($val['baptism'] ?? ''));
								$employer_address     = clean($val['employer_address'] ?? '');
								$family_position      = strtolower(clean($val['family_position'] ?? 'other'));
								$cell      = strtolower(clean($val['cell'] ?? ''));
								$cell_id = $this->Crud->read_field('name', $cell, 'cells', 'id');
								// Check existing user by email
								$existingUser = $this->Crud->read_single('email', $email, 'user');

								// Prepare insert/update data
								$ins_data = [
									'title'              => $title,
									'firstname'          => $firstname,
									'othername'          => $othername,
									'surname'            => $surname,
									'email'              => $email,
									'is_member'          => 1,
									'phone'              => '0' . ltrim($phone, '0'),
									'gender'             => $gender,
									'address'            => $address,
									'marriage_anniversary' => $marriage_anniversary,
									'job_type'           => $job,
									'employer_address'   => $employer_address,
									'family_position'    => $family_position,
									'baptism'            => $baptism,
									'foundation_school'  => $foundation_school,
									'chat_handle'        => $chat_handle,
									'dob'                => $dob,
									'cell_id'       	 => (int)$cell_id,
									'church_id'          => (int)$church_id,
									'ministry_id'        => (int)$ministry_id,
									'family_status'      => $marital_status,
									'role_id'            => (int)$this->Crud->read_field('name', 'Member', 'access_role', 'id'),
									'activate'           => 1,
									'reg_date'           => date(fdate)
								];

								$ins_data['password'] = md5($surname);
								if ($role == 'cell leader' || $role == 'cell executive' || $role == 'assistant cell leader') {
									$ins_data['cell_id'] = $cell_id;
									$ins_data['cell_role'] = $this->Crud->read_field('name', 'Cell Member', 'access_role', 'id');
								}

								// CASE 1: Email exists
								if ($existingUser) {
									if (in_array($family_position, ['parent', 'other'])) {
										// Update the record
										$update = $this->Crud->updates('email', $email, 'user', $ins_data);
										$exist++;
										continue;
									}

									// If family_position is child
									if ($family_position === 'child') {
										$childCount = $this->Crud->check2('email', $email, 'family_position', 'child', 'user');

										// Check if name + dob match an existing child
										$childMatch = $this->Crud->read5('email', $email, 'family_position', 'child', 'firstname', $firstname, 'surname', $surname, 'dob', $dob , 'user');

										if ($childMatch) {
											// Update this specific child
											$update = $this->Crud->updates('id', $childMatch['id'], 'user', $ins_data);
											$exist++;
											continue;
										} elseif ($childCount >= 5) {
											// Skip record if child count exceeded and no match
											$failed++;
											continue;
										} else {
											$parentUser = $this->Crud->read_field2('email', $email, 'family_position', 'parent', 'user', 'id');
									
											// Assign parent_id if found
											if ($parentUser) {
												$ins_data['parent_id'] = $parentUser;
											}
									
										}
										// Else insert as a new child
									}
								}

								// CASE 2: Insert new
								$ins_rec = $this->Crud->create('user', $ins_data);
								if ($ins_rec > 0) {
									// Store activity
									$success++;
									$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
									$code = $this->Crud->read_field('id', $ins_rec, 'user', 'surname');
									$this->Crud->updates('id', $ins_rec, 'user', ['user_no' => 'CEAM-00' . $ins_rec]);

									// Generate QR code
									$qr = $this->Crud->qrcode('USER-00' . $ins_rec);
									$this->Crud->updates('id', $ins_rec, 'user', ['qrcode' => $qr['path']]);
									
									// Log activity
									$action = $by . ' created Membership (' . $code . ') Record';
									$this->Crud->activity('user', $ins_rec, $action);

									$name = ucwords($firstname . ' ' . $othername . ' ' . $surname);
									$churchName = ucwords($church);
									$userNo = 'CEAM-00' . $ins_rec;
									$reset_link = site_url('auth/email_verify?uid=' . $userNo);
									$qr_code_url = site_url($qr['path']);

									$subject = '🎉 Welcome to ' . $churchName . ' - Your Membership & QR Code Access';

									$body = '
										Dear ' . ucwords(strtolower($title . ' ' . $firstname)) . ',<br><br>

										We are thrilled to welcome you to <strong>' . $churchName . '</strong> platform.<br><br>

										An account has been successfully created for you. Below are your account details:<br><br>

										<ul>
											<li><strong>Website:</strong> ' . site_url() . '</li>
											<li><strong>Membership ID:</strong> ' . $userNo . '</li>
											<li><strong>Email:</strong> ' . $email . '</li>
											<li><strong>Phone:</strong> ' . $phone . '</li>
										</ul>

										<p>To ensure the security of your account, please set your password by clicking the link below:</p>
										<p><a href="' . htmlspecialchars($reset_link) . '"> Set Your Password</a></p>

										<hr>

										<h4 style="margin-top: 20px;">📲 Your Personalized QR Code</h4>
										<p>As part of our commitment to serving you better, we have introduced a smart way to stay connected using your personal QR Code.</p>
										
										<p>This code allows you to mark your attendance effortlessly at all our services and special events.</p>
										
										<p style="text-align: center;">
											<img src="' . $qr_code_url . '" alt="QR Code" width="180" height="180" style="margin-top: 10px;" />
										</p>

										<p>Every time you attend church, simply scan your code — its quick, easy, and ensures we stay connected and in sync as a family of faith.</p>

										<hr>

										<p><strong>Note:</strong> Never disclose your login credentials or QR code to anyone to avoid unauthorized access.</p>
										
										<p>If you have any questions, feel free to contact our support team.</p>

										<p>We are so glad you are here! 🎉</p>

										<br>
										With love and blessings,<br><br>

										<strong>' . $churchName . '</strong><br>
										Digital Team
									';

									$this->Crud->mailgun($email, $subject, $body, $church);

								}
							}

							$msg = '';
							if($success > 0)$msg .= $success.' Membership(s) Uploaded Successfully<br> ';
							if($exist > 0)$msg .= $exist.' Membership(s) Uploading Exist Already and Updated <br>';
							if($failed > 0)$msg .= $failed.' Membership(s) not Uploaded';

							
							$by = $this->Crud->read_field('id', $log_id, 'user', 'surname');
							$action = $msg.' by '.$by;
							$this->Crud->activity('membership', $log_id, $action, $log_id);
							// echo $val['name'];
							echo $this->Crud->msg('info', $msg);
							if ($success || $exist) {
								echo '
								<script>
									setTimeout(function() {
										window.location.replace("' . site_url('accounts/membership') . '");
									}, 3000); // 3000 milliseconds = 3 seconds
								</script>';
							}
							
						} else {
							echo $this->Crud->msg('danger',' Error Uploading Membership! Check Excel File');
						}
					}
					die;
				}
			} elseif($param2 == 'leaders'){
				// prepare for edit
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
							$data['e_is_leader'] = $e->is_leader;
						}
					}
				}
			

				if($this->request->getMethod() == 'post'){
					$edit_id = $this->request->getVar('edit_id');
					$is_leader = $this->request->getVar('is_leader');
					
					
					$ins_data['is_leader'] = $is_leader;
					
					// do create or update
					if($edit_id) {
						$upd_rec = $this->Crud->updates('id', $edit_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $edit_id, 'user', 'firstname');
							$action = $by.' updated User ('.$code.') Record';
							$this->Crud->activity('user', $edit_id, $action);

							echo $this->Crud->msg('success', 'Record Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} 

					die;	
				}
			} elseif($param2 == 'message'){
				// prepare for edit
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
							$data['e_is_leader'] = $e->is_leader;
						}
					}
				}
			

				if($this->request->getMethod() == 'post'){
					$user_id = $this->request->getVar('edit_id');
					$message = $this->request->getVar('message');
					$subject = $this->request->getVar('subject');
					
					$ministry_id = $this->Crud->read_field('id', $user_id, 'user', 'ministry_id');
					$church_id = $this->Crud->read_field('id', $user_id, 'user', 'church_id');
					$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
					$firstname = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
					$surname = $this->Crud->read_field('id', $user_id, 'user', 'surname');
					$email = $this->Crud->read_field('id', $user_id, 'user', 'email');
					

					
					$ins_data['subject'] = $subject;
					$ins_data['message'] = $message;
					$ins_data['church_id'] = $church_id;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['from_id'] = $log_id;
					$ins_data['to_id'] = $user_id;
					$ins_data['reg_date'] = date(fdate);
					
					// do create or update
					$upd_rec = $this->Crud->create('message', $ins_data);
					if($upd_rec > 0) {
						$this->Crud->notify($log_id, $user_id, $message, 'message', $upd_rec);
						$name = ucwords($firstname.' '.$surname);
							$body = '
								Dear '.$name.', <br><br>
							'.$message;
							$this->Crud->mailgun($email, $subject, $body, $church);

						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
						$action = $by.' Sent a message to User ('.$code.')';
						$this->Crud->activity('user', $user_id, $action);

						echo $this->Crud->msg('success', 'Message Sent');
						echo '<script>location.reload(false);</script>';
					} else {
						echo $this->Crud->msg('info', 'Try Again Later');	
					}
				

					die;	
				}
			} elseif($param2 == 'bulk_message'){
				// prepare for edit
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
							$data['e_is_leader'] = $e->is_leader;
						}
					}
				}
			

				if($this->request->getMethod() == 'post'){
					$user_id = $this->request->getVar('edit_id');
					$message = $this->request->getVar('message');
					$member_id = $this->request->getVar('member_id');
					$subject = $this->request->getVar('subject');
					
					if(empty($member_id)){
						echo $this->Crud-msg('danger', 'Select Member you want to send Message to');
						die;
					}
					$scount = 0;
					$fcount = 0;
					$ins_data['subject'] = $subject;
					$ins_data['message'] = $message;
					$ins_data['from_id'] = $log_id;
					$ins_data['reg_date'] = date(fdate);
					if(!empty($member_id)){
						foreach($member_id as $member){

							$ministry_id = $this->Crud->read_field('id', $member, 'user', 'ministry_id');
							$church_id = $this->Crud->read_field('id', $member, 'user', 'church_id');
							$firstname = $this->Crud->read_field('id', $member, 'user', 'firstname');
							$surname = $this->Crud->read_field('id', $member, 'user', 'surname');
							$email = $this->Crud->read_field('id', $member, 'user', 'email');
							$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
					
							$ins_data['church_id'] = $church_id;
							$ins_data['ministry_id'] = $ministry_id;
							$ins_data['to_id'] = $member;
						
							
							// do create or update
							$upd_rec = $this->Crud->create('message', $ins_data);
							if($upd_rec > 0) {
								$scount++;
								$this->Crud->notify($log_id, $user_id, $message, 'message', $upd_rec);
								$name = ucwords($firstname.' '.$surname);
									$body = '
										Dear '.$name.', <br><br>
									'.$message;
									$this->Crud->mailgun($email, $subject, $body, $church);

								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
								$action = $by.' Sent a message to User ('.$code.')';
								$this->Crud->activity('user', $user_id, $action);
							} else {
								$fcount++;	
							}

						}
					}
					
					if($scount == 0){
						echo $this->Crud->msg('info', 'Try Again Later');
					} else {
						echo $this->Crud->msg('success', $scount.' Message Sent.<br>'.$fcount.' Message Failed');
						echo '<script>location.reload(false);</script>';
						
					}

					die;	
				}
			} elseif($param2 == 'link'){ 
				if($param3 == 'generate') {
					$churchId = $this->request->getPost('church_id');

					if (!$churchId) {
						return $this->output->set_content_type('application/json')
							->set_output(json_encode(['success' => false, 'message' => 'Church ID required']));
					}
				
					$link = $this->Crud->read_field('id', $churchId, 'church', 'first_timer_link');
					
					if(empty($link)){
						// Generate new unique code (e.g., random 8-character slug)
						$link = $this->Crud->generateUniqueCode();
					
						// Save in database
						$this->Crud->updates('id', $churchId, 'church', ['first_timer_link' => $link]);
					
					}
					
					if ($link) {
						echo json_encode(['success' => true, 'url' => site_url('member/' . $link)]);
					} else {
						echo json_encode(['success' => false, 'message' => 'Failed to generate link']);
					}
					die;
				} 
			} elseif($param2 == 'bulk_qr'){
				// prepare for edit
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
						}
					}
				}
			

				if($this->request->getMethod() == 'post'){
					$user_id = $this->request->getVar('edit_id');
					$send_type = $this->request->getVar('send_type');
					$date_from = $this->request->getVar('date_from');
					$date_to = $this->request->getVar('date_to');
					
					
					$scount = 0;
					$fcount = 0;
					
					$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
					$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
					
					$smember = $this->Crud->filter_memberz($log_id, $date_from, $date_to);
					

					if (!empty($smember)) {
						foreach ($smember as $mem) {
							$firstname = $mem->firstname;
							$surname = $mem->surname;
							$othername = $mem->othername;
							$title = $mem->title;
							$church = $this->Crud->read_field('id', $mem->church_id, 'church', 'name');
							$email = $mem->email;
							$phone = $mem->phone;
							$name = ucwords($firstname . ' ' . $surname);
				
							// ✅ Get QR code (assuming it's stored or generated)
							$qr_code_url = site_url($mem->qrcode); // adjust as per actual path
							$name = ucwords($firstname . ' ' . $othername . ' ' . $surname);
							$churchName = ucwords($church);
							$userNo = 'CEAM-00' . $mem->id;
							$reset_link = site_url('auth/email_verify?uid=' . $userNo);
							$qr_code_url = site_url($mem->qrcode);
			
							$subject = 'A Warm Welcome to '.ucwords($church).' - Your Membership & QR Code Access';
			
							$body = '
								Dear ' . ucwords(strtolower($title . ' ' . $firstname)) . ',<br><br>
			
								We are thrilled to welcome you to <strong>' . $churchName . '</strong> platform.<br><br>
			
								You have created an account successfully. Below are your account details:<br><br>
			
								<ul>
									<li><strong>Website:</strong> ' . site_url() . '</li>
									<li><strong>Membership ID:</strong> ' . $userNo . '</li>
									<li><strong>Email:</strong> ' . $email . '</li>
									<li><strong>Phone:</strong> ' . $phone . '</li>
								</ul>
			
								<p>To ensure the security of your account, please set your password by clicking the link below:</p>
								<p><a href="' . htmlspecialchars($reset_link) . '">🔐 Set Your Password</a></p>
			
								<hr>
			
								<h4 style="margin-top: 20px;">📲 Your Personalized QR Code</h4>
								<p>As part of our commitment to serving you better, we have introduced a smart way to stay connected using your personal QR Code.</p>
								
								<p>This code allows you to mark your attendance effortlessly at all our services and special events.</p>
								
								<p style="text-align: center;">
									<img src="' . $qr_code_url . '" alt="QR Code" width="180" height="180" style="margin-top: 10px;" />
								</p>
			
								<p>Every time you attend church, simply scan your code — its quick, easy, and ensures we stay connected and in sync as a family of faith.</p>
			
								<hr>
			
								<p><strong>Note:</strong> Never disclose your login credentials or QR code to anyone to avoid unauthorized access.</p>
								
								<p>If you have any questions, feel free to contact our support team.</p>
			
								<p>We are so glad you are here! 🎉</p>
			
								<br>
								With love and blessings,<br><br>
			
								<strong>' . $churchName . '</strong><br>
								Digital Team
							';
			
							$upd_rec = $this->Crud->mailgun($email, $subject, $body, $church);
			
							// echo $upd_rec;
							if (!empty($upd_rec)) {
								$mailgun_response = json_decode($upd_rec, true); // Decode the JSON response

								if (isset($mailgun_response['message']) && stripos($mailgun_response['message'], 'Queued') !== false) {
									$scount++; // Success
								} else {
									$fcount++; // Failed or not properly queued
								}
							} else {
								$fcount++; // Failed (no response)
							}

							
							// Store activity
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by . ' sent QR code to ' . $name;
							$this->Crud->activity('user', $mem->id, $action);
						}
					}
				
					
					if($scount == 0){
						echo $this->Crud->msg('info', 'Try Again Later');
					} else {
						echo $this->Crud->msg('success', $scount.' Message Sent.<br>'.$fcount.' Message Failed');
						echo '<script>location.reload(false);</script>';
						
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
								$data['e_firstname'] = $e->firstname;
								$data['e_lastname'] = $e->surname;
								$data['e_gender'] = $e->gender;
								$data['e_othername'] = $e->othername;
								$data['e_email'] = $e->email;
								$data['e_archive'] = $e->is_archive;
								$data['e_title'] = $e->title;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_gender'] = $e->gender;
								$data['e_marriage_anniversary'] = $e->marriage_anniversary;
								$data['e_job_type'] = $e->job_type;
								$data['e_employer_address'] = $e->employer_address;
								$data['e_baptism'] = $e->baptism;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_foundation_weeks'] = $e->foundation_weeks;
								$data['e_chat_handle'] = $e->chat_handle;
								$data['e_dob'] = $e->dob;
								$data['e_family_status'] = $e->family_status;
								$data['e_family_position'] = $e->family_position;
								$data['e_parent_id'] = $e->parent_id;
								$data['e_spouse_id'] = $e->spouse_id;
								$data['e_cell_id'] = $e->cell_id;
								$data['e_cell_role'] = $e->cell_role;
								$data['e_dept_id'] = $e->dept_id;
								$data['e_dept_role'] = $e->dept_role;
								$data['e_parent_id'] = $e->parent_id;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_level'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
								$data['e_church_id'] = $e->church_id;
								$data['e_img_id'] = $e->img_id;
								
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$membership_id = htmlspecialchars(trim($this->request->getVar('membership_id')), ENT_QUOTES, 'UTF-8');
					$firstname = htmlspecialchars(trim($this->request->getVar('firstname')), ENT_QUOTES, 'UTF-8');
					$lastname  = htmlspecialchars(trim($this->request->getVar('lastname')), ENT_QUOTES, 'UTF-8');
					$othername  = htmlspecialchars(trim($this->request->getVar('othername')), ENT_QUOTES, 'UTF-8');
					$gender  = htmlspecialchars(trim($this->request->getVar('gender')), ENT_QUOTES, 'UTF-8');
					$email  = htmlspecialchars(trim($this->request->getVar('email')), ENT_QUOTES, 'UTF-8');
					$phone  = htmlspecialchars(trim($this->request->getVar('phone')), ENT_QUOTES, 'UTF-8');
					$dob  = htmlspecialchars(trim($this->request->getVar('dob')), ENT_QUOTES, 'UTF-8');
					$archive       = htmlspecialchars(trim($this->request->getVar('archive')), ENT_QUOTES, 'UTF-8');
					$chat_handle   = htmlspecialchars(trim($this->request->getVar('chat_handle')), ENT_QUOTES, 'UTF-8');
					$address       = htmlspecialchars(trim($this->request->getVar('address')), ENT_QUOTES, 'UTF-8');
					$family_status = htmlspecialchars(trim($this->request->getVar('family_status')), ENT_QUOTES, 'UTF-8');
					$family_position= htmlspecialchars(trim($this->request->getVar('family_position')), ENT_QUOTES, 'UTF-8');
					$parent_id     = htmlspecialchars(trim($this->request->getVar('parent_id')), ENT_QUOTES, 'UTF-8');
					$dept_id                = $this->request->getVar('dept_id'); // array, handle separately below
					$dept_role_id           = $this->request->getVar('dept_role_id'); // array, handle separately below
					$cell_id                = htmlspecialchars(trim($this->request->getVar('cell_id')), ENT_QUOTES, 'UTF-8');
					$spouse_id              = htmlspecialchars(trim($this->request->getVar('spouse_id')), ENT_QUOTES, 'UTF-8');
					$cell_role_id           = htmlspecialchars(trim($this->request->getVar('cell_role_id')), ENT_QUOTES, 'UTF-8');
					$title                  = htmlspecialchars(trim($this->request->getVar('title')), ENT_QUOTES, 'UTF-8');
					$password               = htmlspecialchars(trim($this->request->getVar('password')), ENT_QUOTES, 'UTF-8');
					$marriage_anniversary   = htmlspecialchars(trim($this->request->getVar('marriage_anniversary')), ENT_QUOTES, 'UTF-8');
					$job_type               = htmlspecialchars(trim($this->request->getVar('job_type')), ENT_QUOTES, 'UTF-8');
					$employer_address       = htmlspecialchars(trim($this->request->getVar('employer_address')), ENT_QUOTES, 'UTF-8');
					$baptism                = htmlspecialchars(trim($this->request->getVar('baptism')), ENT_QUOTES, 'UTF-8');
					$foundation_school      = htmlspecialchars(trim($this->request->getVar('foundation_school')), ENT_QUOTES, 'UTF-8');
					$foundation_weeks       = htmlspecialchars(trim($this->request->getVar('foundation_weeks')), ENT_QUOTES, 'UTF-8');
					$ministry_id            = htmlspecialchars(trim($this->request->getVar('ministry_id')), ENT_QUOTES, 'UTF-8');
					$church_id              = htmlspecialchars(trim($this->request->getVar('church_id')), ENT_QUOTES, 'UTF-8');
					$img_id                 = htmlspecialchars(trim($this->request->getVar('img_id')), ENT_QUOTES, 'UTF-8');

					

					$sanitized_dept_id = [];
					if (is_array($dept_id)) {
						foreach ($dept_id as $id) {
							$sanitized_dept_id[] = htmlspecialchars(trim($id), ENT_QUOTES, 'UTF-8');
						}
					}

					$sanitized_dept_role_id = [];
					if (is_array($dept_role_id)) {
						foreach ($dept_role_id as $key => $val) {
							$clean_key = htmlspecialchars(trim($key), ENT_QUOTES, 'UTF-8');
							$clean_val = htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
							$sanitized_dept_role_id[$clean_key] = $clean_val;
						}
					}

				
					$is_usher = 0;
					$usher_id = $this->Crud->read_field('name', 'Usher', 'dept', 'id');
					if(in_array($usher_id, $dept_id))$is_usher = 1;

					//// Image upload
					if(file_exists($this->request->getFile('pics'))) {
						$path = 'assets/images/users/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);
						
						if(!empty($getImg->path)) $img_id = $getImg->path;
					}
					$church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
					$regional_id = $this->Crud->read_field('id', $church_id, 'church', 'regional_id');
					$zonal_id = $this->Crud->read_field('id', $church_id, 'church', 'zonal_id');
					$group_id = $this->Crud->read_field('id', $church_id, 'church', 'group_id');
					
					// echo $baptism;
					// die;
					$ins_data['title'] = $title;
					$ins_data['firstname'] = $firstname;
					$ins_data['othername'] = $othername;
					$ins_data['surname'] = $lastname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['is_usher'] = $is_usher;
					$ins_data['gender'] = $gender;
					$ins_data['address'] = $address;
					$ins_data['is_archive'] = $archive;
					$ins_data['parent_id'] = $parent_id;
					$ins_data['img_id'] = $img_id;
					$ins_data['spouse_id'] = $spouse_id;
					$ins_data['marriage_anniversary'] = $marriage_anniversary;
					$ins_data['job_type'] = $job_type;
					$ins_data['is_member'] = 1;
					$ins_data['employer_address'] = $employer_address;
					$ins_data['baptism'] = $baptism;
					$ins_data['foundation_school'] = $foundation_school;
					$ins_data['foundation_weeks'] = $foundation_weeks;
					$ins_data['chat_handle'] = $chat_handle;
					$ins_data['dob'] = $dob;
					$ins_data['family_status'] = $family_status;
					$ins_data['family_position'] = $family_position;
					$ins_data['parent_id'] = $parent_id;
					$ins_data['dept_id'] = json_encode($dept_id);
					$ins_data['dept_role'] = json_encode($dept_role_id);
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['church_type'] = $church_type;
					$ins_data['regional_id'] = $regional_id;
					$ins_data['zonal_id'] = $zonal_id;
					$ins_data['group_id'] = $group_id;
					$ins_data['cell_id'] = $cell_id;
					$ins_data['cell_role'] = $cell_role_id;
					$ins_data['is_member'] = 1;
					$ins_data['profile_update'] = 1;
					if($password) { $ins_data['password'] = md5($password); }
					$role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
					$member_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
					$cell_member = $this->Crud->read_field('name', 'Cell Member', 'access_role', 'id');
					$cell_role = $this->Crud->read_field('id', $cell_role_id, 'access_role', 'name');
					if($cell_role == 'Cell Leader' || $cell_role == 'Assistant Cell Leader'){
						$role_id = $cell_role_id;
						$cells = $this->Crud->read2('cell_id', $cell_id, 'cell_role !=', $cell_member, 'user');
						if(!empty($cells)){
							foreach($cells as $cm){
								if($cm->cell_role == $cell_role_id){
									$this->Crud->updates('id', $cm->id, 'user', array('role_id'=>$member_id, 'cell_role'=>$cell_member));
								}
							}
						}
					}
					if($cell_role == 'Cell Executive'){
						$role_id = $cell_role_id;
						$cells = $this->Crud->read2('cell_id', $cell_id, 'cell_role !=', $cell_member, 'user');
						if(!empty($cells)){
							$a = 0;
							foreach($cells as $cm){
								if($cm->cell_role == $cell_role_id){
									$a++;
									if($a > 5){
										$this->Crud->updates('id', $cm->id, 'user', array('role_id'=>$member_id, 'cell_role'=>$cell_member));
									}
									
								}

							}
						}
					}
					$ins_data['role_id'] = $role_id;
						
					// do create or update
					if($membership_id) {
						if(!empty($spouse_id)){
							$this->Crud->updates('id', $spouse_id, $table, array('spouse_id'=>$membership_id, 'family_status'=>'married'));
						}
						$upd_rec = $this->Crud->updates('id', $membership_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $membership_id, 'user', 'firstname');
							$action = $by.' updated Membership ('.$code.') Record';
							$this->Crud->activity('user', $membership_id, $action);

							echo $this->Crud->msg('success', 'Membership Updated');
							echo '<script>window.location.replace("'.site_url('accounts/membership').'");</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						$ins_data['activate'] = 1;
						$ins_data['reg_date'] = date(fdate);
						$ins_rec = $this->Crud->create($table, $ins_data);
						if($ins_rec > 0) {
							if(!empty($spouse_id)){
								$this->Crud->updates('id', $spouse_id, $table, array('spouse_id'=>$ins_rec, 'family_status'=>'married'));
							}
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $ins_rec, 'user', 'surname');
							$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));

							$user_no = 'CEAM-00'.$ins_rec;
							$qr_content = 'USER-00' . $ins_rec;

							// Generate QR
							$qr = $this->Crud->qrcode($qr_content); // This should return an array

							// Save to DB
							$this->Crud->updates('id', $ins_rec, 'user', ['qrcode' => $qr['path']]);
									
							$action = $by.' created Membership ('.$code.') Record';
							$this->Crud->activity('user', $ins_rec, $action);
							$name = ucwords($firstname.' '.$othername.' '.$lastname);
							$body = '
								Dear '.$title.' '.$name.', <br><br>
									A Membership Account Has been Created with This Email on Chrsit Embassy  Platform;<br>
									Below are your login Credentials:<br><br>

									Website: '.site_url().'
									Membership ID: '.$user_no.'<br>
									Email: '.$email.'<br>
									Phone: '.$phone.'<br>
									Password: '.$password.'<br><br>
									Do not disclose your Login credentials with anyone to avoid unauthorized access.
									
							';
							// $this->Crud->send_email($email, 'Membership Account', $body);


							echo $this->Crud->msg('success', 'Membership Created');
							echo '<script>window.location.replace("'.site_url('accounts/membership').'");</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');	
						}	
					
					}

					die;	
				}
			}
		}

		// manage record
		if($param1 == 'manages') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_membership_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'user', 'surname');
						$action = $by.' deleted Membership ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Membership Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
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
								$data['e_firstname'] = $e->firstname;
								$data['e_lastname'] = $e->surname;
								$data['e_gender'] = $e->gender;
								$data['e_othername'] = $e->othername;
								$data['e_email'] = $e->email;
								$data['e_archive'] = $e->is_archive;
								$data['e_title'] = $e->title;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_gender'] = $e->gender;
								$data['e_marriage_anniversary'] = $e->marriage_anniversary;
								$data['e_job_type'] = $e->job_type;
								$data['e_employer_address'] = $e->employer_address;
								$data['e_baptism'] = $e->baptism;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_foundation_weeks'] = $e->foundation_weeks;
								$data['e_chat_handle'] = $e->chat_handle;
								$data['e_dob'] = $e->dob;
								$data['e_family_status'] = $e->family_status;
								$data['e_family_position'] = $e->family_position;
								$data['e_parent_id'] = $e->parent_id;
								$data['e_spouse_id'] = $e->spouse_id;
								$data['e_cell_id'] = $e->cell_id;
								$data['e_cell_role'] = $e->cell_role;
								$data['e_dept_id'] = $e->dept_id;
								$data['e_dept_role'] = $e->dept_role;
								$data['e_is_duplicate'] = $e->is_duplicate;
								$data['e_parent_id'] = $e->parent_id;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_level'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
								$data['e_church_id'] = $e->church_id;
								$data['e_img_id'] = $e->img_id;
								
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$membership_id = htmlspecialchars(trim($this->request->getVar('membership_id')), ENT_QUOTES, 'UTF-8');
					$firstname = htmlspecialchars(trim($this->request->getVar('firstname')), ENT_QUOTES, 'UTF-8');
					$lastname  = htmlspecialchars(trim($this->request->getVar('lastname')), ENT_QUOTES, 'UTF-8');
					$othername  = htmlspecialchars(trim($this->request->getVar('othername')), ENT_QUOTES, 'UTF-8');
					$gender  = htmlspecialchars(trim($this->request->getVar('gender')), ENT_QUOTES, 'UTF-8');
					$email  = strtolower(htmlspecialchars(trim($this->request->getVar('email')), ENT_QUOTES, 'UTF-8'));
					$phone  = htmlspecialchars(trim($this->request->getVar('phone')), ENT_QUOTES, 'UTF-8');
					$dob  = htmlspecialchars(trim($this->request->getVar('dob')), ENT_QUOTES, 'UTF-8');
					$archive       = htmlspecialchars(trim($this->request->getVar('archive')), ENT_QUOTES, 'UTF-8');
					$is_duplicate       = htmlspecialchars(trim($this->request->getVar('is_duplicate')), ENT_QUOTES, 'UTF-8');
					$chat_handle   = htmlspecialchars(trim($this->request->getVar('chat_handle')), ENT_QUOTES, 'UTF-8');
					$address       = htmlspecialchars(trim($this->request->getVar('address')), ENT_QUOTES, 'UTF-8');
					$family_status = htmlspecialchars(trim($this->request->getVar('family_status')), ENT_QUOTES, 'UTF-8');
					$family_position= htmlspecialchars(trim($this->request->getVar('family_position')), ENT_QUOTES, 'UTF-8');
					$parent_id     = htmlspecialchars(trim($this->request->getVar('parent_id')), ENT_QUOTES, 'UTF-8');
					$dept_id                = $this->request->getVar('dept_id'); // array, handle separately below
					$dept_role_id           = $this->request->getVar('dept_role_id'); // array, handle separately below
					$cell_id                = htmlspecialchars(trim($this->request->getVar('cell_id')), ENT_QUOTES, 'UTF-8');
					$spouse_id              = htmlspecialchars(trim($this->request->getVar('spouse_id')), ENT_QUOTES, 'UTF-8');
					$cell_role_id           = htmlspecialchars(trim($this->request->getVar('cell_role_id')), ENT_QUOTES, 'UTF-8');
					$title                  = htmlspecialchars(trim($this->request->getVar('title')), ENT_QUOTES, 'UTF-8');
					$password               = htmlspecialchars(trim($this->request->getVar('password')), ENT_QUOTES, 'UTF-8');
					$marriage_anniversary   = htmlspecialchars(trim($this->request->getVar('marriage_anniversary')), ENT_QUOTES, 'UTF-8');
					$job_type               = htmlspecialchars(trim($this->request->getVar('job_type')), ENT_QUOTES, 'UTF-8');
					$employer_address       = htmlspecialchars(trim($this->request->getVar('employer_address')), ENT_QUOTES, 'UTF-8');
					$baptism                = htmlspecialchars(trim($this->request->getVar('baptism')), ENT_QUOTES, 'UTF-8');
					$foundation_school      = htmlspecialchars(trim($this->request->getVar('foundation_school')), ENT_QUOTES, 'UTF-8');
					$foundation_weeks       = htmlspecialchars(trim($this->request->getVar('foundation_weeks')), ENT_QUOTES, 'UTF-8');
					$ministry_id            = htmlspecialchars(trim($this->request->getVar('ministry_id')), ENT_QUOTES, 'UTF-8');
					$church_id              = htmlspecialchars(trim($this->request->getVar('church_id')), ENT_QUOTES, 'UTF-8');
					$img_id                 = htmlspecialchars(trim($this->request->getVar('img_id')), ENT_QUOTES, 'UTF-8');

					if(empty($family_position)){
						echo $this->Crud->msg('danger', 'Select Family Position!');
						die;
					}
					if(empty($membership_id)){
						// Check if email already exists
						$emailExists = $this->Crud->check('email', $email, 'user');
						
						if ($emailExists) {
							$family_position = strtolower($family_position);
							if (in_array($family_position, ['parent', 'other'])) {
								// Email already used by a parent or other

								echo $this->Crud->msg('danger', 'Email already exists in the system.');
								die;
							}
						
							if ($family_position === 'child') {
								// Count how many children exist with this email
								$childCount = $this->Crud->check2('email', $email, 'family_position', 'child', 'user');
						
								if ($childCount >= 5) {
									echo $this->Crud->msg('danger', 'Only 5 children are allowed with the same email.');
									die;
								}
							}
						}
					
					}

					$sanitized_dept_id = [];
					if (is_array($dept_id) && !empty($dept_id)) {
						foreach ($dept_id as $id) {
							$sanitized_dept_id[] = htmlspecialchars(trim($id), ENT_QUOTES, 'UTF-8');
						}
					}

					$sanitized_dept_role_id = [];
					if (is_array($dept_role_id) && !empty($dept_role_id)) {
						foreach ($dept_role_id as $key => $val) {
							$clean_key = htmlspecialchars(trim($key), ENT_QUOTES, 'UTF-8');
							$clean_val = htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
							$sanitized_dept_role_id[$clean_key] = $clean_val;
						}
					}
					$usher_id = $this->Crud->read_field('name', 'Usher', 'dept', 'id');
					$is_usher = 0;
					
					if (!empty($usher_id) && is_array($dept_id) && in_array($usher_id, $dept_id)) {
						$is_usher = 1;
					}
					
					//// Image upload
					if(file_exists($this->request->getFile('pics'))) {
						$path = 'assets/images/users/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);
						
						if(!empty($getImg->path)) $img_id = $getImg->path;
					}
					$church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
					$regional_id = $this->Crud->read_field('id', $church_id, 'church', 'regional_id');
					$zonal_id = $this->Crud->read_field('id', $church_id, 'church', 'zonal_id');
					$group_id = $this->Crud->read_field('id', $church_id, 'church', 'group_id');
					

					// echo $baptism;
					// die;
					$ins_data['title'] = $title;
					$ins_data['firstname'] = $firstname;
					$ins_data['othername'] = $othername;
					$ins_data['surname'] = $lastname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['is_usher'] = $is_usher;
					$ins_data['gender'] = $gender;
					$ins_data['address'] = $address;
					$ins_data['is_archive'] = $archive;
					$ins_data['is_duplicate'] = $is_duplicate;
					$ins_data['parent_id'] = $parent_id;
					$ins_data['img_id'] = $img_id;
					$ins_data['spouse_id'] = $spouse_id;
					$ins_data['marriage_anniversary'] = $marriage_anniversary;
					$ins_data['job_type'] = $job_type;
					$ins_data['is_member'] = 1;
					$ins_data['employer_address'] = $employer_address;
					$ins_data['baptism'] = $baptism;
					$ins_data['foundation_school'] = $foundation_school;
					$ins_data['foundation_weeks'] = $foundation_weeks;
					$ins_data['chat_handle'] = $chat_handle;
					$ins_data['dob'] = $dob;
					$ins_data['family_status'] = $family_status;
					$ins_data['family_position'] = $family_position;
					$ins_data['parent_id'] = $parent_id;
					$ins_data['dept_id'] = json_encode($dept_id);
					$ins_data['dept_role'] = json_encode($dept_role_id);
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['church_type'] = $church_type;
					$ins_data['regional_id'] = $regional_id;
					$ins_data['zonal_id'] = $zonal_id;
					$ins_data['group_id'] = $group_id;
					$ins_data['cell_id'] = $cell_id;
					$ins_data['cell_role'] = $cell_role_id;
					$ins_data['is_member'] = 1;
					if($password) { $ins_data['password'] = md5($password); }
					$role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
					$member_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
					$cell_member = $this->Crud->read_field('name', 'Cell Member', 'access_role', 'id');
					$cell_role = $this->Crud->read_field('id', $cell_role_id, 'access_role', 'name');
					if($cell_role == 'Cell Leader' || $cell_role == 'Assistant Cell Leader'){
						$role_id = $cell_role_id;
						$cells = $this->Crud->read2('cell_id', $cell_id, 'cell_role !=', $cell_member, 'user');
						if(!empty($cells)){
							foreach($cells as $cm){
								if($cm->cell_role == $cell_role_id){
									$this->Crud->updates('id', $cm->id, 'user', array('role_id'=>$member_id, 'cell_role'=>$cell_member));
								}
							}
						}
					}
					if($cell_role == 'Cell Executive'){
						$role_id = $cell_role_id;
						$cells = $this->Crud->read2('cell_id', $cell_id, 'cell_role !=', $cell_member, 'user');
						if(!empty($cells)){
							$a = 0;
							foreach($cells as $cm){
								if($cm->cell_role == $cell_role_id){
									$a++;
									if($a > 5){
										$this->Crud->updates('id', $cm->id, 'user', array('role_id'=>$member_id, 'cell_role'=>$cell_member));
									}
									
								}

							}
						}
					}
					$ins_data['role_id'] = $role_id;
						
					// do create or update
					if($membership_id) {
						if (!empty($spouse_id)) {
							// Update the selected spouse (link back to this member)
							$this->Crud->updates('id', $spouse_id, $table, [
								'spouse_id'     => $membership_id,
								'family_status' => 'married'
							]);
						
							// Update the current member (link to selected spouse)
							$this->Crud->updates('id', $membership_id, $table, [
								'spouse_id'     => $spouse_id,
								'family_status' => 'married'
							]);
						}
						
						$upd_rec = $this->Crud->updates('id', $membership_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $membership_id, 'user', 'firstname');
							$action = $by.' updated Membership ('.$code.') Record';
							$this->Crud->activity('user', $membership_id, $action);

							echo $this->Crud->msg('success', 'Membership Updated');
							echo '<script>window.location.replace("'.site_url('accounts/membership').'");</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						$ins_data['activate'] = 1;
						$ins_data['reg_date'] = date(fdate);
						$ins_rec = $this->Crud->create($table, $ins_data);
						if($ins_rec > 0) {
							if (!empty($spouse_id)) {
								// Update the selected spouse (link back to this member)
								$this->Crud->updates('id', $spouse_id, $table, [
									'spouse_id'     => $ins_rec,
									'family_status' => 'married'
								]);
							
								// Update the current member (link to selected spouse)
								$this->Crud->updates('id', $ins_rec, $table, [
									'spouse_id'     => $spouse_id,
									'family_status' => 'married'
								]);
							}
							
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $ins_rec, 'user', 'surname');
							$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));

							$user_no = 'CEAM-00'.$ins_rec;
							$qr_content = 'CEAM-00' . $ins_rec;

							// Generate QR
							$qr = $this->Crud->qrcode($qr_content); // This should return an array

							// Save to DB
							$this->Crud->updates('id', $ins_rec, 'user', ['qrcode' => $qr['path']]);
									
							$action = $by.' created Membership ('.$code.') Record';
							$this->Crud->activity('user', $ins_rec, $action);
							$name = ucwords($firstname.' '.$othername.' '.$lastname);
							$body = '
								Dear '.$title.' '.$name.', <br><br>
									A Membership Account Has been Created with This Email on Chrsit Embassy  Platform;<br>
									Below are your login Credentials:<br><br>

									Website: '.site_url().'
									Membership ID: '.$user_no.'<br>
									Email: '.$email.'<br>
									Phone: '.$phone.'<br>
									Password: '.$password.'<br><br>
									Do not disclose your Login credentials with anyone to avoid unauthorized access.
									
							';
							// $this->Crud->send_email($email, 'Membership Account', $body);


							echo $this->Crud->msg('success', 'Membership Created');
							echo '<script>window.location.replace("'.site_url('accounts/membership').'");</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');	
						}	
					
					}

					die;	
				}
			}
		}

		if ($param1 == 'get_dept_role') {
			$dept_id = $this->request->getPost('dept_id');
		
			// Get the roles JSON from the 'dept' table
			$roles_json = $this->Crud->read_field('id', $dept_id, 'dept', 'roles');
			$department_name = $this->Crud->read_field('id', $dept_id, 'dept', 'name');
		
			$response = [];
		
			if (!empty($roles_json)) {
				$roles = json_decode($roles_json, true);
		
				if (is_array($roles)) {
					foreach ($roles as $role) {
						$response[] = [
							'name' => ucwords($role),
							'department_name' => ucwords($department_name)
						];
						
					}
				}
			}
		
			return $this->response->setJSON($response);
		}
		

		if($param1 == 'get_cell_role'){
			if(!empty($param2)){
				
				$li = '';
				$dept = $this->Crud->read_field('id', $param2, 'cells', 'name');
				$dept_role = $this->Crud->read_field('id', $param2, 'cells', 'roles');
				$li = '<option value="">Select Cell Role</option>';
				if(!empty($dept_role)){
					foreach(json_decode($dept_role) as $r => $val){
						$sel = '';
						if(!empty($param3)){
							if($param3 == $val){
								$sel = 'selected';
							}
						}
						$li .= '<option value="'.$val.'" '.$sel.'>'.ucwords($val).'</option>';
					}
				}
			
				$resp['list'] = $li;
				$resp['script'] = '<script>$("#cell_resp").show(500);</script>';

				echo json_encode($resp);
				die;
			}
		}

		if($param1 == 'get_member'){
			$includeChurch = $this->request->getGet('include_church') === 'true';

			$member = '';
			$members = $this->Crud->filter_membership('', '', $log_id, '', '', $includeChurch);
            if(!empty($members)){
				foreach($members as $mem){
					$church = '';
					if($includeChurch == 'true'){
						$church = ' - '.$this->Crud->read_field('id', $mem->church_id, 'church', 'name');
					}
					$member .= '
						<option value="'.$mem->id.'">'.ucwords($mem->firstname.' '.$mem->surname).' - '.$mem->phone.$church.'</option>';
				}
			}
			echo $member;
			die;
		}

		if($param1 == 'get_parents'){
			$church_id = $param2;
			$ministry_id = $param3;
			// Validate inputs
			if (empty($church_id) && empty($ministry_id)) {
				echo json_encode([]);
				return;
			}
		
			// Fetch parents based on church and ministry
			if(empty($church_id) && !empty(!$ministry_id)){
				$parents = $this->Crud->read2_order('family_position', 'Parent', 'ministry_id', $ministry_id, 'user', 'surname', 'asc');
			} else{
				$parents = $this->Crud->read2_order('family_position', 'Parent', 'church_id', $church_id, 'user', 'surname', 'asc');
			}
			// Format data for response
			$response = [];
			foreach ($parents as $parent) {
				$response[] = [
					'id' => $parent->id,
					'name' => ucwords($parent->surname . ' ' . $parent->firstname),
				];
			}
		
			echo json_encode($response);
			die;
		}

		
		if ($param1 == 'get_spouse') {
			$log_id = $this->session->get('td_id');
			$church_id = $param2;
			$ministry_id = $param3;
		
			// Validate inputs
			if (empty($church_id) && empty($ministry_id)) {
				echo json_encode([]);
				return;
			}
			$edit_id = $this->request->getPost('edit_id');

		
			// Load all spouse-eligible users filtered by church_id or ministry_id
			$spouses = [];
			if (!empty($church_id)) {
				$spouses = $this->Crud->read2('spouse_id', 0, 'church_id',$church_id, 'user');
			} elseif (!empty($ministry_id)) {
				$spouses = $this->Crud->read2('spouse_id', 0, 'ministry_id',$ministry_id, 'user');
			}
		
			// Format response
			$response = [];
			$response[] = [
				'id' => 0,
				'name' => ucwords('Not Available'),
			];
		
			if (!empty($spouses)) {
				foreach ($spouses as $spouse) {
					if ((int)$spouse->id === (int)$edit_id) continue; // exclude self
					if (strtolower($spouse->family_status) !== 'married') continue;
		
					$response[] = [
						'id' => $spouse->id,
						'name' => ucwords($spouse->surname . ' ' . $spouse->firstname),
					];
				}
			}
		
			echo json_encode($response);
			die;
		}
		

		
        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			$include = $this->request->getPost('include');
			$church_scope = $this->request->getPost('church_scope');
			$selected_churches = $this->request->getPost('selected_churches');
			$cell_id = $this->request->getPost('cell_id');

			$items = '
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_membership('', '', $log_id, $search, $switch_id, $include, 0, $church_scope, $selected_churches, $cell_id);
				$counts = !empty($all_rec) ? count($all_rec) : 0;
				$query = $this->Crud->filter_membership($limit, $offset, $log_id, $search, $switch_id, $include, 0, $church_scope, $selected_churches, $cell_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$firstname = $q->firstname;
						$othername = $q->othername;
						$surname = $q->surname;
						$user_no = $q->user_no;
						$phone = $q->phone;
						$email = $q->email;
						$church_id = $q->church_id;
						$family_position = strtolower($q->family_position);
						$chat_handle = $q->chat_handle;
						$dob = date('d M Y', strtotime($q->dob));
						if(empty($dob))$dob = '-';
						$cell_id = $q->cell_id;
						$is_duplicate = $q->is_duplicate;
						$title = $q->title;
						$activate = $q->activate;
						$img = $q->img_id;
						if (empty($img) && !file_exists($img)) {
							$img = 'assets/images/avatar.png';
						}
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
						$cell = '-';
						if(!empty($q->cell_id)){
							$cell = $this->Crud->read_field('id', $q->cell_id, 'cells', 'name');
						}
						$name = $firstname.' '.$othername.' '.$surname;
						$names = '<a href="' . site_url('accounts/membership/view/' . $id) . '" class="text-">
							' . ucwords(strtolower($firstname.' '.$othername.' '.$surname)) . '</span>
						</a>';

						$duplicate = '';

						if ($is_duplicate == 1) {
							// Count total records with this email
							$email_count = $this->Crud->check('email', $email, 'user');

							if ($email_count > 1) {
								if (in_array($family_position, ['parent', 'other'])) {
									$is_duplicate = 1;
									$duplicate = '<br><span class="small text-danger">Duplicate</span>';
								} elseif ($family_position === 'child') {
									// Count how many children exist with this email
									$child_count = $this->Crud->check2('email', $email, 'family_position', 'child', 'user');

									if ($child_count > 5) {
										$is_duplicate = 1;
										$duplicate = '<br><span class="small text-danger">Duplicate</span>';
									} else {
										$is_duplicate = 0;
										$duplicate = '';
										
									}
									// $this->Crud->updates('id', $id, 'user', array('is_duplicate' => $is_duplicate));
								}
							} else {
								$is_duplicate = 0;
								$duplicate = '';
							}
						}


						if(empty($phone))$phone = '-';
						if(empty($email))$email = '-';
						if(empty($title))$title = '-';
						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								<li><a href="' . site_url($mod . '/view/' . $id) . '" class="text-success" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName=""><em class="icon ni ni-eye"></em><span>'.translate_phrase('View Profile').'</span></a></li>
								<li><a href="' . site_url($mod . '/partnership/' . $id) . '" class="text-primary" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName=""><em class="icon ni ni-link"></em><span>'.translate_phrase('Partnership Records').'</span></a></li>
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Send Message to ' . $name . '" pageName="' . site_url($mod . '/manage/message/' . $id) . '"><em class="icon ni ni-chat-circle"></em><span>'.translate_phrase('Send Message').'</span></a></li>
								<li><a href="javascript:;" pageTitle="Send Login" id="send_btn"  class="text-success pop" pageName="' . site_url($mod . '/manage/admin_send/' . $id) . '"><em class="icon ni ni-share"></em> <span>Send Login</span></a></li>
								<li><a href="javascript:;" pageTitle="Send Login" id="send_btn"  class="text-success pop" pageName="' . site_url($mod . '/manage/admin_send/' . $id) . '"><em class="icon ni ni-qr"></em> <span>Send QR Code</span></a></li>
								
							';
							} else {
								$all_btn = '
								<li><a href="' . site_url($mod . '/manages/edit/' . $id) . '" class="text-info" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manages/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="' . site_url($mod . '/view/' . $id) . '" class="text-success" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName=""><em class="icon ni ni-eye"></em><span>'.translate_phrase('View Profile').'</span></a></li>
								<li><a href="' . site_url($mod . '/partnership/' . $id) . '" class="text-primary" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName=""><em class="icon ni ni-link"></em><span>'.translate_phrase('Partnership Records').'</span></a></li>
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Send Message to ' . $name . '" pageName="' . site_url($mod . '/manage/message/' . $id) . '"><em class="icon ni ni-chat-circle"></em><span>'.translate_phrase('Send Message').'</span></a></li>
								<li><a href="javascript:;" pageTitle="Send Login" id="send_btn"  class="text-success pop" pageName="' . site_url($mod . '/manage/admin_send/' . $id) . '"><em class="icon ni ni-share"></em> <span>Send Login</span></a></li>
								
							';
							}
							
						}

						$item .= '
							<tr>
								<td>
									<span class="small">' . ucwords(strtolower($title)) . '</span>'.$duplicate.'
								</td>
								<td>
									<div class="user-card">
										<div class="user-avatar ">
											<img alt="" src="' . site_url($img) . '" height="40px" width="50px"/>
										</div>
										<div class="user-info">
											<span class="tb-lead">' . (($names)) . '<br><span class="small">'.ucwords(strtolower($church)).'</span> </span>
										</div>
									</div>
								</td>
								<td>
									<span class="small">' . ($user_no) . '</span>
								</td>
								<td>
									<span class="small">' . ($phone) . '</span>
								</td>
								<td>
									<span class="small">' . ($email) .'</span>
								</td>
								<td>
									<span class="small">' . strtolower($chat_handle) . '</span>
								
								<td>
									<span class="small">' . ($cell) . '</span>
								</td>
								<td>
									<span class="small">' . ($dob) . '</span>
								</td>
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
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="10"><div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-user" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Membership Returned').'
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'view'){
			if($param2) {
				$user_id = $param2;
				$data['id'] = $user_id;
				$data['last_log'] = date('F, d Y h:ia',strtotime($this->Crud->read_field('id', $user_id, 'user', 'last_log')));
				if(empty($this->Crud->read_field('id', $user_id, 'user', 'last_log'))){
					$data['last_log'] = 'Not Logged In';
				}
				$data['fullname'] = $this->Crud->read_field('id', $user_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $user_id, 'user', 'surname').' '.$this->Crud->read_field('id', $user_id, 'user', 'othername');
				$role_id = $this->Crud->read_field('id', $user_id, 'user', 'role_id');
				$role = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
				$data['role'] = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
				$data['v_phone'] = $this->Crud->read_field('id', $user_id, 'user', 'phone');
				$data['v_church_id'] = $this->Crud->read_field('id', $user_id, 'user', 'church_id');
				$data['v_dob'] = $this->Crud->read_field('id', $user_id, 'user', 'dob');
				$data['v_user_no'] = $this->Crud->read_field('id', $user_id, 'user', 'user_no');
				$data['v_gender'] = $this->Crud->read_field('id', $user_id, 'user', 'gender');
				$data['v_title'] = $this->Crud->read_field('id', $user_id, 'user', 'title');
				$data['v_chat_handle'] = $this->Crud->read_field('id', $user_id, 'user', 'chat_handle');
				$data['v_family_status'] = $this->Crud->read_field('id', $user_id, 'user', 'family_status');
				$data['v_marriage_anniversary'] = $this->Crud->read_field('id', $user_id, 'user', 'marriage_anniversary');
				$data['v_family_position'] = $this->Crud->read_field('id', $user_id, 'user', 'family_position');
				$data['v_cell_id'] = $this->Crud->read_field('id', $user_id, 'user', 'cell_id');
				$data['v_cell_role'] = $this->Crud->read_field('id', $user_id, 'user', 'cell_role');
				$data['v_dept_id'] = $this->Crud->read_field('id', $user_id, 'user', 'dept_id');
				$data['v_dept_role'] = $this->Crud->read_field('id', $user_id, 'user', 'dept_role');
				$data['v_job_type'] = $this->Crud->read_field('id', $user_id, 'user', 'job_type');
				$data['v_employer_address'] = $this->Crud->read_field('id', $user_id, 'user', 'employer_address');
				$data['v_foundation_school'] = $this->Crud->read_field('id', $user_id, 'user', 'foundation_school');
				$data['v_foundation_weeks'] = $this->Crud->read_field('id', $user_id, 'user', 'foundation_weeks');
				$data['v_baptism'] = $this->Crud->read_field('id', $user_id, 'user', 'baptism');
				$data['reg_date'] = date('F, d Y h:ia',strtotime($this->Crud->read_field('id', $user_id, 'user', 'reg_date')));
				$data['v_email'] = $this->Crud->read_field('id', $user_id, 'user', 'email');
				$data['qrcode'] = $this->Crud->read_field('id', $user_id, 'user', 'qrcode');

				$v_img_id = $this->Crud->read_field('id', $user_id, 'user', 'img_id');
				if(!empty($v_img_id)){
					$img = '<img src="'.site_url($this->Crud->image($v_img_id, "big")).'">';
				} else {
					$img = $this->Crud->image_name($this->Crud->read_field('id', $user_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $user_id, 'user', 'othername').' '.$this->Crud->read_field('id', $user_id, 'user', 'surname'));
				}
				$data['v_img'] = $img;

				$v_status = $this->Crud->read_field('id', $user_id, 'user', 'activate');
				if(!empty($v_status)) { $v_status = '<span class="text-success">VERIFIED</span>'; } else { $v_status = '<span class="text-danger">UNVERIFIED</span>'; }
				$data['v_status'] = $v_status;

				$data['v_address'] = $this->Crud->read_field('id', $user_id, 'user', 'address');


			}
			$data['page_active'] = $mod;
			$data['title'] = translate_phrase('Membership View').' - '.app_name;
			return view($mod.'_view', $data);
		}

		if($param1 == 'partnership'){
			
			if($param2) {
				$user_id = $param2;
				$data['id'] = $user_id;
				$data['last_log'] = date('F, d Y h:ia',strtotime($this->Crud->read_field('id', $user_id, 'user', 'last_log')));
				if(empty($this->Crud->read_field('id', $user_id, 'user', 'last_log'))){
					$data['last_log'] = 'Not Logged In';
				}
				
				if($param3){
					if($param3 == 'edit'){
						$edit = $this->Crud->read_single('id', $param2, 'user');
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_partnership'] = $e->partnership;
							}
						}
					}
					if($this->request->getMethod() == 'post'){
						$part_id = $this->request->getPost('part_id');
						$partnership = $this->request->getPost('partnership');
						$goal = $this->request->getPost('goal');
						
						$parts = [];
						for($i=0;$i<count($partnership);$i++){
							$part_id = $this->Crud->read_field('name', $partnership[$i], 'partnership', 'id');
							$parts[$part_id] = $goal[$i];
						}
						
						$upd_rec = $this->Crud->updates('id', $user_id, 'user', array('partnership'=>json_encode($parts)));

						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $user_id, 'user', 'surname');
							$action = $by.' updated Partnership for Membership ('.$code.') Record';
							$this->Crud->activity('user', $user_id, $action);

							echo $this->Crud->msg('success', 'Partnership Updated');
							echo '<script>location.reload(false);</script>';

						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
						die;
					}
					return view($mod.'_partnership_form', $data);
				}
			}
			 // record listing
			if($param2 == 'load') {
				$limit = $param2;
				$offset = $param3;

				$rec_limit = 25;
				$item = '';
				if(empty($limit)) {$limit = $rec_limit;}
				if(empty($offset)) {$offset = 0;}
				
				$search = $this->request->getPost('search');
				$member_id = $this->request->getPost('member_id');
				
				$items = '
					<tr>
						<td><span class="sub-text text-dark"><b>'.translate_phrase('Partnership').'</b></span></td>
						<td><span class="sub-text text-dark"><b>'.translate_phrase('Pledge').'</b></span></td>
						<td><span class="sub-text text-dark"><b>'.translate_phrase('Given').'</b></span></td>
						<td><span class="sub-text text-dark"><b>'.translate_phrase('Balance').'</b></span></td>
						<td>
							
						</td>
					</tr><!-- .nk-tb-item -->
			
					
				';
				$a = 1;

				//echo $status;
				$log_id = $this->session->get('td_id');
				if(!$log_id) {
					$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
				} else {
					
					$all_rec = $this->Crud->read_order('partnership', 'name', 'asc');
					// $all_rec = json_decode($all_rec);
					if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
					$query = $this->Crud->read_order('partnership', 'name', 'asc');
					$data['count'] = $counts;
					

					if(!empty($query)) {
						foreach ($query as $q) {
							$id = $q->id;
							$name = $q->name;
							$goal = 0;
							$given = 0;
							$balance = 0;
							if(!empty($member_id)){
								$parts = $this->Crud->read_field('id', $member_id, 'user', 'partnership');
								if(!empty($parts)){
									$partss = json_decode($parts);
									foreach($partss as $pa => $val){
										if($id == $pa){
											$goal = (float)$val;

											$paids = $this->Crud->read2('member_id', $member_id, 'partnership_id', $pa, 'partners_history');
											if(!empty($paids)){
												foreach($paids as $p){
													$given += (float)$p->amount_paid;
												}
											}
										}
									}
								}
								$balance = (float)$goal - (float)$given;
								if($balance < 0)$balance = 0;
								
							}
							
							// add manage buttons
							
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/view/' . $id.'/'.$member_id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								
									
								';
							

							$item .= '
								<tr>
									<td>
										<span class="tb-lead text-primary">' . ucwords($name) . '</span>
									</td>
									<td>
										<span class="tb-lead">' .$this->session->get('currency'). number_format($goal,2) . ' </span>
									</td>
									<td>
										<span class="tb-lead">' .$this->session->get('currency'). number_format($given,2) . '</span>
									</td>
									<td>
										<span class="tb-lead">' .$this->session->get('currency').number_format($this->Crud->cur_exchange($balance),2) . '</span>
									</td>
									<td >' . $all_btn . '</td>
								</tr><!-- .nk-tb-item -->
							';
							$a++;
						}
					}
					
				}
				
				if(empty($item)) {
					$resp['item'] = $items.'
						<tr><td colspan="9"><div class="text-center text-muted">
							<br/><br/><br/><br/>
							<i class="ni ni-user" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Membership Returned').'
						</div></td></tr>
					';
				} else {
					$resp['item'] = $items . $item;
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
			$data['page_active'] = $mod;
			$data['title'] = translate_phrase('Partnership').' - '.app_name;
			return view($mod.'_partnership', $data);
		}

		

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		}elseif($param1 == 'manages'){
			
			$data['page_active'] = $mod;
			$data['title'] = translate_phrase('New Membership').' - '.app_name;
			if($param2 == 'edit')$data['title'] = translate_phrase('Edit Membership').' - '.app_name;
			return view($mod.'_edit', $data);
		}else { // view for main page
			
			$data['title'] = translate_phrase('Membership').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	public function get_spouse_name() {
		$id = $this->request->getPost('id');
		$name = $this->Crud->read_field('id', $id, 'user', "firstname").' '.$this->Crud->read_field('id', $id, 'user', "surname");
		echo ucwords($name ?: 'Unknown');
	}
	
	//Customer
	public function marchive($param1='', $param2='', $param3='', $param4='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/marchive';

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
            // return redirect()->to(site_url('dashboard'));	
        }
        $data['log_id'] = $log_id;
        $data['role'] = $role;
        $data['role_c'] = $role_c;
       
		
		$table = 'user';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param2){$form_link .= '/'.$param3.'/';}
		if($param3){$form_link .= $param4;}
		
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
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_membership_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'user', 'surname');
						$action = $by.' deleted Membership ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Membership Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
					}
				}
			} elseif($param2 == 'unarchive'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_membership_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'user', 'surname');
						$action = $by.' Moved '.$code.' to Active Membership Record';

						if($this->Crud->updates('id', $del_id, $table, array('is_archive'=>0)) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Membership Unarchived');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
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
								$data['e_location'] = $e->location;
								$data['e_name'] = $e->name;
								$data['e_roles'] = json_decode($e->roles);
								$data['e_time'] = json_decode($e->time);
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$cell_id = $this->request->getVar('cell_id');
					$name = $this->request->getVar('name');
					$roles = $this->request->getVar('roles');
					$location = $this->request->getVar('location');
					$times = $this->request->getVar('times');
					$days = $this->request->getVar('days');
					
					$time = [];
					for($i=0;$i < count($days);$i++ ){
						$day = $days[$i];
						// echo $day;
						$time[$day] = $times[$i];
					}
					// print_r($time);
					// print_r($days);
					// die;
					$ins_data['name'] = $name;
					$ins_data['roles'] = json_encode($roles);
					$ins_data['location'] = $location;
					$ins_data['time'] = json_encode($time);
					
					// do create or update
					if($cell_id) {
						$upd_rec = $this->Crud->updates('id', $cell_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $cell_id, 'dept', 'name');
							$action = $by.' updated Department ('.$code.') Record';
							$this->Crud->activity('user', $cell_id, $action);

							echo $this->Crud->msg('success', 'Record Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'dept', 'name');
								$action = $by.' created Department ('.$code.') Record';
								$this->Crud->activity('user', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Record Created');
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
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			$include = $this->request->getPost('include');
			
			$items = '
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_membership('', '', $log_id, $search, $switch_id,$include, 1);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$query = $this->Crud->filter_membership($limit, $offset, $log_id, $search, $switch_id,$include, 1);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$firstname = $q->firstname;
						$othername = $q->othername;
						$surname = $q->surname;
						$user_no = $q->user_no;
						$phone = $q->phone;
						$email = $q->email;
						$church_id = $q->church_id;
						$chat_handle = $q->chat_handle;
						$dob = date('d M Y', strtotime($q->dob));
						if(empty($dob))$dob = '-';
						$cell_id = $q->cell_id;
						$title = $q->title;
						$activate = $q->activate;
						$img = $q->img_id;
						if (empty($img) && !file_exists($img)) {
							$img = 'assets/images/avatar.png';
						}
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
						$cell = '-';
						if(!empty($q->cell_id)){
							$cell = $this->Crud->read_field('id', $q->cell_id, 'cells', 'name');
						}
						$name = $firstname.' '.$othername.' '.$surname;
						$names = '<a href="' . site_url('accounts/membership/view/' . $id) . '" class="text-primary">
							' . ucwords(strtolower($firstname.' '.$othername.' '.$surname)) . '</span>
						</a>';


						if(empty($phone))$phone = '-';
						if(empty($email))$email = '-';
						if(empty($title))$title = '-';
						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								<li><a href="' . site_url($mod . '/view/' . $id) . '" class="text-success" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName=""><em class="icon ni ni-eye"></em><span>'.translate_phrase('View Profile').'</span></a></li>
								<li><a href="' . site_url($mod . '/partnership/' . $id) . '" class="text-primary" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName=""><em class="icon ni ni-link"></em><span>'.translate_phrase('Partnership Records').'</span></a></li>
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Send Message to ' . $name . '" pageName="' . site_url($mod . '/manage/message/' . $id) . '"><em class="icon ni ni-chat-circle"></em><span>'.translate_phrase('Send Message').'</span></a></li>
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-info pop" pageTitle="Move to Membership " pageName="' . site_url($mod . '/manage/unarchive/' . $id) . '"><em class="icon ni ni-share"></em><span>'.translate_phrase('Move to Membership').'</span></a></li>
								<li><a href="' . site_url($mod . '/view/' . $id) . '" class="text-success" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName=""><em class="icon ni ni-eye"></em><span>'.translate_phrase('View Profile').'</span></a></li>
								<li><a href="' . site_url($mod . '/partnership/' . $id) . '" class="text-primary" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName=""><em class="icon ni ni-link"></em><span>'.translate_phrase('Partnership Records').'</span></a></li>
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Send Message to ' . $name . '" pageName="' . site_url($mod . '/manage/message/' . $id) . '"><em class="icon ni ni-chat-circle"></em><span>'.translate_phrase('Send Message').'</span></a></li>
								
								
							';
							}
							
						}

						$item .= '
							<tr>
								<td>
									<span class="text">' . ucwords(strtolower($title)) . '</span>
								</td>
								<td>
									<div class="user-card">
										<div class="user-avatar ">
											<img alt="" src="' . site_url($img) . '" height="40px" width="50px"/>
										</div>
										<div class="user-info">
											<span class="tb-lea">' . (($names)) . '<br><span class="small ">'.ucwords(strtolower($church)).'</span> </span>
										</div>
									</div>
								</td>
								<td>
									<span class="small">' . ($user_no) . '</span>
								</td>
								<td>
									<span class="small">' . ($phone) . '</span>
								</td>
								<td>
									<span class="small">' . ($email) .'</span>
								</td>
								<td>
									<span class="small">' . strtolower($chat_handle) . '</span>
								</div>
								<td>
									<span class="small">' . ($cell) . '</span>
								</td>
								<td>
									<span class="small">' . ($dob) . '</span>
								</td>
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
							</tr><!-- .nk-tb-item -->
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="10"><div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-user" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Member Archive Returned').'
					</div></td></tr>
				';
			} else {
				$resp['item'] = $items . $item;
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

		if($param1 == 'view'){
			if($param2) {
				$user_id = $param2;
				$data['id'] = $user_id;
				$data['last_log'] = date('F, d Y h:ia',strtotime($this->Crud->read_field('id', $user_id, 'user', 'last_log')));
				if(empty($this->Crud->read_field('id', $user_id, 'user', 'last_log'))){
					$data['last_log'] = 'Not Logged In';
				}
				$data['fullname'] = $this->Crud->read_field('id', $user_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $user_id, 'user', 'surname').' '.$this->Crud->read_field('id', $user_id, 'user', 'othername');
				$role_id = $this->Crud->read_field('id', $user_id, 'user', 'role_id');
				$role = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
				$data['role'] = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
				$data['v_phone'] = $this->Crud->read_field('id', $user_id, 'user', 'phone');
				$data['v_church_id'] = $this->Crud->read_field('id', $user_id, 'user', 'church_id');
				$data['v_dob'] = $this->Crud->read_field('id', $user_id, 'user', 'dob');
				$data['v_user_no'] = $this->Crud->read_field('id', $user_id, 'user', 'user_no');
				$data['v_gender'] = $this->Crud->read_field('id', $user_id, 'user', 'gender');
				$data['v_title'] = $this->Crud->read_field('id', $user_id, 'user', 'title');
				$data['v_chat_handle'] = $this->Crud->read_field('id', $user_id, 'user', 'chat_handle');
				$data['v_family_status'] = $this->Crud->read_field('id', $user_id, 'user', 'family_status');
				$data['v_marriage_anniversary'] = $this->Crud->read_field('id', $user_id, 'user', 'marriage_anniversary');
				$data['v_family_position'] = $this->Crud->read_field('id', $user_id, 'user', 'family_position');
				$data['v_cell_id'] = $this->Crud->read_field('id', $user_id, 'user', 'cell_id');
				$data['v_cell_role'] = $this->Crud->read_field('id', $user_id, 'user', 'cell_role');
				$data['v_dept_id'] = $this->Crud->read_field('id', $user_id, 'user', 'dept_id');
				$data['v_dept_role'] = $this->Crud->read_field('id', $user_id, 'user', 'dept_role');
				$data['v_job_type'] = $this->Crud->read_field('id', $user_id, 'user', 'job_type');
				$data['v_employer_address'] = $this->Crud->read_field('id', $user_id, 'user', 'employer_address');
				$data['v_foundation_school'] = $this->Crud->read_field('id', $user_id, 'user', 'foundation_school');
				$data['v_foundation_weeks'] = $this->Crud->read_field('id', $user_id, 'user', 'foundation_weeks');
				$data['v_baptism'] = $this->Crud->read_field('id', $user_id, 'user', 'baptism');
				$data['reg_date'] = date('F, d Y h:ia',strtotime($this->Crud->read_field('id', $user_id, 'user', 'reg_date')));
				$data['v_email'] = $this->Crud->read_field('id', $user_id, 'user', 'email');

				$v_img_id = $this->Crud->read_field('id', $user_id, 'user', 'img_id');
				if(!empty($v_img_id)){
					$img = '<img src="'.site_url($this->Crud->image($v_img_id, "big")).'">';
				} else {
					$img = $this->Crud->image_name($this->Crud->read_field('id', $user_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $user_id, 'user', 'othername').' '.$this->Crud->read_field('id', $user_id, 'user', 'surname'));
				}
				$data['v_img'] = $img;

				$v_status = $this->Crud->read_field('id', $user_id, 'user', 'activate');
				if(!empty($v_status)) { $v_status = '<span class="text-success">VERIFIED</span>'; } else { $v_status = '<span class="text-danger">UNVERIFIED</span>'; }
				$data['v_status'] = $v_status;

				$data['v_address'] = $this->Crud->read_field('id', $user_id, 'user', 'address');


			}
			$data['page_active'] = $mod;
			$data['title'] = translate_phrase('Membership View').' - '.app_name;
			return view($mod.'_view', $data);
		}

		if($param1 == 'partnership'){
			
			if($param2) {
				$user_id = $param2;
				$data['id'] = $user_id;
				$data['last_log'] = date('F, d Y h:ia',strtotime($this->Crud->read_field('id', $user_id, 'user', 'last_log')));
				if(empty($this->Crud->read_field('id', $user_id, 'user', 'last_log'))){
					$data['last_log'] = 'Not Logged In';
				}
				
				if($param3){
					if($param3 == 'edit'){
						$edit = $this->Crud->read_single('id', $param2, 'user');
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_partnership'] = $e->partnership;
							}
						}
					}
					if($this->request->getMethod() == 'post'){
						$part_id = $this->request->getPost('part_id');
						$partnership = $this->request->getPost('partnership');
						$goal = $this->request->getPost('goal');
						
						$parts = [];
						for($i=0;$i<count($partnership);$i++){
							$part_id = $this->Crud->read_field('name', $partnership[$i], 'partnership', 'id');
							$parts[$part_id] = $goal[$i];
						}
						
						$upd_rec = $this->Crud->updates('id', $user_id, 'user', array('partnership'=>json_encode($parts)));

						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $user_id, 'user', 'surname');
							$action = $by.' updated Partnership for Membership ('.$code.') Record';
							$this->Crud->activity('user', $user_id, $action);

							echo $this->Crud->msg('success', 'Partnership Updated');
							echo '<script>location.reload(false);</script>';

						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
						die;
					}
					return view($mod.'_partnership_form', $data);
				}
			}
			 // record listing
			if($param2 == 'load') {
				$limit = $param2;
				$offset = $param3;

				$rec_limit = 25;
				$item = '';
				if(empty($limit)) {$limit = $rec_limit;}
				if(empty($offset)) {$offset = 0;}
				
				$search = $this->request->getPost('search');
				$member_id = $this->request->getPost('member_id');
				
				$items = '
					<tr>
						<td><span class="sub-text text-dark"><b>'.translate_phrase('Partnership').'</b></span></td>
						<td><span class="sub-text text-dark"><b>'.translate_phrase('Pledge').'</b></span></td>
						<td><span class="sub-text text-dark"><b>'.translate_phrase('Given').'</b></span></td>
						<td><span class="sub-text text-dark"><b>'.translate_phrase('Balance').'</b></span></td>
						<td>
							
						</td>
					</tr><!-- .nk-tb-item -->
			
					
				';
				$a = 1;

				//echo $status;
				$log_id = $this->session->get('td_id');
				if(!$log_id) {
					$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
				} else {
					
					$all_rec = $this->Crud->read_order('partnership', 'name', 'asc');
					// $all_rec = json_decode($all_rec);
					if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
					$query = $this->Crud->read_order('partnership', 'name', 'asc');
					$data['count'] = $counts;
					

					if(!empty($query)) {
						foreach ($query as $q) {
							$id = $q->id;
							$name = $q->name;
							$goal = 0;
							$given = 0;
							$balance = 0;
							if(!empty($member_id)){
								$parts = $this->Crud->read_field('id', $member_id, 'user', 'partnership');
								if(!empty($parts)){
									$partss = json_decode($parts);
									foreach($partss as $pa => $val){
										if($id == $pa){
											$goal = (float)$val;

											$paids = $this->Crud->read2('member_id', $member_id, 'partnership_id', $pa, 'partners_history');
											if(!empty($paids)){
												foreach($paids as $p){
													$given += (float)$p->amount_paid;
												}
											}
										}
									}
								}
								$balance = (float)$goal - (float)$given;
								if($balance < 0)$balance = 0;
								
							}
							
							// add manage buttons
							
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/view/' . $id.'/'.$member_id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								
									
								';
							

							$item .= '
								<tr>
									<td>
										<span class="tb-lead text-primary">' . ucwords($name) . '</span>
									</td>
									<td>
										<span class="tb-lead">' .$this->session->get('currency'). number_format($goal,2) . ' </span>
									</td>
									<td>
										<span class="tb-lead">' .$this->session->get('currency'). number_format($given,2) . '</span>
									</td>
									<td>
										<span class="tb-lead">' .$this->session->get('currency').number_format($this->Crud->cur_exchange($balance),2) . '</span>
									</td>
									<td >' . $all_btn . '</td>
								</tr><!-- .nk-tb-item -->
							';
							$a++;
						}
					}
					
				}
				
				if(empty($item)) {
					$resp['item'] = $items.'
						<tr><td colspan="9"><div class="text-center text-muted">
							<br/><br/><br/><br/>
							<i class="ni ni-user" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Membership Returned').'
						</div></td></tr>
					';
				} else {
					$resp['item'] = $items . $item;
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
			$data['page_active'] = $mod;
			$data['title'] = translate_phrase('Partnership').' - '.app_name;
			return view($mod.'_partnership', $data);
		}

		

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Member Archive').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	
	public function customer_details($param1='', $param2='', $param3='', $param4='') {
        // record listing
		$log_id = $this->session->get('td_id');
       
		if($param1 == 'activity' && $param2 == 'load') {
			$limit = $param3;
			$offset = $param4;
			$rec_limit = 50;
			$item = '';
            if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			$search = $this->request->getVar('search');
			$user_id = $this->request->getVar('u_id');
			if(!empty($this->request->getPost('start_date'))) { $start_date = $this->request->getPost('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getPost('end_date'))) { $end_date = $this->request->getPost('end_date'); } else { $end_date = ''; }
			
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$query = $this->Crud->read_like2('activity', 'user_id', $user_id, 'item_id', $user_id, $limit, $offset);
				$all_rec = $this->Crud->read_like2('activity', 'user_id', $user_id, 'item_id', $user_id, '', '');
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				
				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$type = $q->item;
						$type_id = $q->item_id;
						$action = $q->action;
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));

						$timespan = $this->Crud->timespan(strtotime($q->reg_date));

						$icon = 'article';
						if($type == 'rhapsody') $icon = 'template';
						if($type == 'branch') $icon = 'reports-alt';
						if($type == 'business') $icon = 'briefcase';
						if($type == 'ecommerce') $icon = 'bag';
						if($type == 'user') $icon = 'users';
						if($type == 'pump') $icon = 'cc-secure';
						if($type == 'authentication') $icon = 'article';
						if($type == 'enrolment') $icon = 'property-add';
						if($type == 'scholarship') $icon = 'award';

						$item .= '
							<tr class="nk-tb-item">
								<td class="nk-tb-col">
									<a href="#" class="project-title">
										<div class=""><em class="icon ni ni-'.$icon.' text-muted" style="font-size:30px;"></em></div>
										<div class="project-info">
											<h6 class="title">'.$action.' <small>on '.$reg_date.'</small></h6>
										</div>
									</a>
								</td>
								<td class="nk-tb-col tb-col-lg">
									<span>'.$timespan.'</span>
								</td>
							</tr><!-- .nk-tb-item -->       
						';
					}
				}
			}
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<em class="icon ni ni-property" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Activity Returned').'
					</div>
				';
			} else {
				$resp['item'] = $item;
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
			
		}

		if($param1 == 'service' && $param2 == 'load') {
			$limit = $param3;
			$offset = $param4;
			$rec_limit = 150;
			$item = '';
            if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			$search = $this->request->getVar('search');
			$user_id = $this->request->getVar('u_id');
			$church_id = $this->Crud->read_field('id', $user_id, 'user', 'church_id');
			$counts = 0;
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$query = $this->Crud->read_single('member_id', $user_id, 'service_attendance', $limit, $offset);
				$all_rec = $this->Crud->read_single('member_id', $user_id, 'service_attendance');
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				
				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$service_id = $q->service_id;
						$church_id = $q->church_id;
						$status = $q->status;
						$reason = $q->reason;
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
						
						// Fetch service name using $service_id
						$service = $this->Crud->read_field('id', $service_id, 'service_report', 'type');
						$type = $this->Crud->read_field('id', $service, 'service_type', 'name');
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
					
						// Build the table row
						$item .= '
							<tr>
								<td>' . $reg_date . '</td>
								<td><span>' . ucwords($type) . '</span></td>
								<td><span>' . ucwords($church) . '</span></td>
								<td><span class="text-success">' . ucwords($status . ' ' . $reason) . '</span></td>
							</tr>
						';
					}
					
				}
			}
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<em class="icon ni ni-linux-server" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Service Record Returned').'
					</div>
				';
			} else {
				$resp['item'] = $item;
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
			
		}

		if($param1 == 'wallet' && $param2 == 'load') {
			$limit = $param3;
			$offset = $param4;
			$rec_limit = 150;
			$item = '';
            if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			$search = $this->request->getVar('search');
			$user_id = $this->request->getVar('u_id');
			$church_id = $this->Crud->read_field('id', $user_id, 'user', 'church_id');
			$cell_id = $this->Crud->read_field('id', $user_id, 'user', 'cell_id');

			$offering = 0;
			$tithe = 0;
			$partnership = 0;
			
			$counts = 0;
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$query = [];
				$cell_report = $this->Crud->read_single('cell_id', $cell_id, 'cell_report');
				if(!empty($cell_report)){
					foreach($cell_report as $cr){
						$c_offering = json_decode($cr->offering_givers, true);
						if (isset($c_offering['list'][$user_id])) {
							$coffering = $c_offering['list'][$user_id];
							$querys['type'] = 'offering';
							$querys['source'] = 'cell';
							$querys['date'] = $cr->date;
							$querys['amount'] = $coffering;
							$querys['id'] = $cr->id;
							
							$offering += $coffering;
							$query[] = $querys;
						} 
					}
				}

				$service_report = $this->Crud->read_single('church_id', $church_id, 'service_report');
				if(!empty($service_report)){
					foreach($service_report as $cr){
						$c_offering = json_decode($cr->offering_givers, true);
						if (isset($c_offering['list'][$user_id])) {
							$cofferings = $c_offering['list'][$user_id];
							$querys['type'] = 'offering';
							$querys['source'] = 'service';
							$querys['date'] = $cr->date;
							$querys['id'] = $cr->id;
							$querys['amount'] = $cofferings;
							
							$offering += $cofferings;
							$query[] = $querys;
						} 

						//Tithe
						$c_tithe = json_decode($cr->tithers, true);
						if (isset($c_tithe['list'][$user_id])) {
							$ctithes = $c_tithe['list'][$user_id];
							$querys['type'] = 'tithe';
							$querys['source'] = 'service';
							$querys['date'] = $cr->date;
							$querys['id'] = $cr->id;
							$querys['amount'] = $ctithes;
							
							
							$tithe += $ctithes;
							$query[] = $querys;
						} 

						//Partnership
						$c_partners = json_decode($cr->partners, true);
						if (isset($c_partners['partnership']['member'][$user_id]) && is_array($c_partners['partnership']['member'][$user_id])) {
							foreach ($c_partners['partnership']['member'][$user_id] as $offeringType => $amount) {
								$querys['type'] = 'partnership';
								$querys['partnership'] = $offeringType;
								$querys['source'] = 'service';
								$querys['date'] = $cr->date;
								$querys['id'] = $cr->id;
								$querys['amount'] = $amount;
								
							
								$partnership += $amount;
								$query[] = $querys;
							}
						} 
						
					}
				}


				if(!empty($query)){
					$filtered_records = array_slice($query, $offset, $limit);
					if(!empty($filtered_records)){
						foreach($filtered_records as $q){
							$source_id = $q['id'];
							$source = '';
							$partner = '';
							if($q['type'] == 'partnership'){
								$partner = $this->Crud->read_field('id', $q['partnership'], 'partnership', 'name');
							}
							if($q['source'] == 'cell'){
								$source_type = $this->Crud->read_field('id', $source_id, 'cell_report', 'type');
								
								if($source_type == 'wk1')$source = 'WK1 - Prayer and Planning';
								if($source_type == 'wk2')$source = 'Wk2 - Bible Study';
								if($source_type == 'wk3')$source = 'Wk3 - Bible Study';
								if($source_type == 'wk4')$source = 'Wk4 - Fellowship / Outreach';
								if($source_type == 'wk5')$source = 'Wk5 - Fellowship';
							}
							if($q['source'] == 'service'){
								$source_type = $this->Crud->read_field('id', $source_id, 'service_report', 'type');
								
								$source = $this->Crud->read_field('id', $source_type, 'service_type', 'name');
							}
							$item .= '
								<tr>
									<td>'.$q['date'].'</td>
									<td>'.ucwords($q['source']).'</td>
									<td>'.ucwords($source).'</td>
									<td>'.ucwords($partner.' '.$q['type']).'</td>
									<td>'.$this->session->get('currency').number_format($q['amount'],2).'</td>
									

								</tr>
							
							';
						}
					}
				}
				
			}
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<em class="icon ni ni-money" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Finance Record Returned').'
					</div>
				';
			} else {
				$resp['item'] = $item;
			}
			$resp['count'] = $counts;
			$resp['offering'] = $this->session->get('currency').number_format($offering, 2);
			$resp['tithe'] = $this->session->get('currency').number_format($tithe, 2);
			$resp['partnership'] = $this->session->get('currency').number_format($partnership, 2);

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
			
		}
		if($param1 == 'cell' && $param2 == 'load') {
			$limit = $param3;
			$offset = $param4;
			$rec_limit = 150;
			$item = '';
            if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			$search = $this->request->getVar('search');
			$user_id = $this->request->getVar('u_id');
			$cell_id = $this->Crud->read_field('id', $user_id, 'user', 'cell_id');
			$counts = 0;
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$query = $this->Crud->read_single('cell_id', $cell_id, 'cell_report', $limit, $offset);
				$all_rec = $this->Crud->read_single('cell_id', $cell_id, 'cell_report');
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				
				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$type = $q->type;
						$attendant = json_decode($q->attendant);
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
						$date = date('M d, Y', strtotime($q->date));

						if(!empty($attendant)){
							if(!in_array($user_id, $attendant))continue;
						}

						$types = '';
						if($type == 'wk1')$types = 'WK1 - Prayer and Planning';
						if($type == 'wk2')$types = 'Wk2 - Bible Study';
						if($type == 'wk3')$types = 'Wk3 - Bible Study';
						if($type == 'wk4')$types = 'Wk4 - Fellowship / Outreach';
						if($type == 'wk5')$types = 'Wk5 - Fellowship';
						$item .= '
							<tr >
								<td >
									'.$date.'
								</td>
								<td >
									<span>'.$types.'</span>
								</td>
								<td >
									<span class="text-success">Present</span>
								</td>
							</tr>    
						';
					}
				}
			}
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<em class="icon ni ni-cc-alt2" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Cell Record Returned').'
					</div>
				';
			} else {
				$resp['item'] = $item;
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
			
		}

		if($param1 == 'notification' && $param2 == 'load') {
			$limit = $param3;
			$offset = $param4;
			$rec_limit = 50;
			$item = '';
            if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			$search = $this->request->getVar('search');
			$user_id = $this->request->getVar('u_id');
			if(!empty($this->request->getPost('start_date'))) { $start_date = $this->request->getPost('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getPost('end_date'))) { $end_date = $this->request->getPost('end_date'); } else { $end_date = ''; }
			
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$query = $this->Crud->read_like2('notify', 'to_id', $user_id, 'from_id', $user_id, $limit, $offset);
				$all_rec = $this->Crud->read_like2('notify', 'to_id', $user_id, 'from_id', $user_id, '', '');
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				
				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$content = $q->content;
						$from_id = $q->from_id;
						$item_id = $q->item_id;
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
						$from = 'Admin';
						if($from_id != 0){
							$from = $this->Crud->read_field('id', $from_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $from_id, 'user', 'surname');
						}
						$item .= '
							<tr class="nk-tb-item">
								<td class="nk-tb-col">
									<div class="project-info">
										<p class="title text-dark">'.translate_phrase(ucwords($content)).' </p>
									</div>
								</td>
								<td class="nk-tb-col tb-col-lg">
									<div class="project-info">
										<h6 class="title"><small>From '.ucwords($from).'</small></h6>
									</div>
								</td>
								<td class="nk-tb-col tb-col">
									<span>'.ucwords($reg_date).'</span>
								</td>
							</tr><!-- .nk-tb-item -->       
						';
					}
				}
			}

			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<em class="icon ni ni-notify" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Notification Returned').'
					</div>
				';
			} else {
				$resp['item'] = $item;
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
			
		}

    }

	public function get_state($country){
		if(empty($country)){
			echo '<label for="activate">'.translate_phrase('State').'</label>
			<input type="text" class="form-control" name="state" id="state" readonly placeholder="Select Country First">';
		} else {
			$state = $this->Crud->read_single_order('country_id', $country, 'state', 'name', 'asc');
			echo '<label for="activate">'.translate_phrase('State').'</label>
				<select class="form-select js-select2" data-search="on" id="state" name="state" onchange="lgaa();">
					<option value="">'.translate_phrase('Select').'</option>
			';
			foreach($state as $qr) {
				$hid = '';
				$sel = '';
				echo '<option value="'.$qr->id.'" '.$sel.'>'.$qr->name.'</option>';
			}
			echo '</select>
			<script> $(".js-select2").select2();</script>';
		}
	}

	public function get_lga($state){
		if(empty($state)){
			echo '<label for="activate">'.translate_phrase('Local Goverment Area').'</label>
			<input type="text" class="form-control" name="lga" id="lga" readonly placeholder="'.translate_phrase('Select State First').'">';
		} else {
			$state = $this->Crud->read_single_order('state_id', $state, 'city', 'name', 'asc');
			echo '<label for="activate">'.translate_phrase('Local Goverment Area').'</label>
				<select class="form-select js-select2" data-search="on" id="lga" name="lga" onchange="branc();">
					<option value="">'.translate_phrase('Select').'</option>
			';
			foreach($state as $qr) {
				$hid = '';
				$sel = '';
				echo '<option value="'.$qr->id.'" '.$sel.'>'.$qr->name.'</option>';
			}
			echo '</select>
			<script> $(".js-select2").select2();</script>';
		}
	}

	public function get_territory($state){
		$lga = '';
		if(empty($state)){
			$lga .= '<option value="">Select LGA First</option>';
		} else {
			$state = $this->Crud->read_single_order('lga_id', $state, 'territory', 'name', 'asc');
			$lga .= '<option value="">'.translate_phrase('Select').'</option>';
			foreach($state as $qr) {
				$hid = '';
				$sel = '';
				$lga .= '<option value="'.$qr->id.'" '.$sel.'>'.$qr->name.'</option>';
			}
			
		}
		echo $lga;
	}


	//Get task master
	public function validate_field($territory){
		// echo $territory;
		$manager = $this->request->getVar('man');
		if(empty($territory)){
			echo '<option value=" ">'.translate_phrase('Select Territory First').'</option>';
		} else {
			$role_id = $this->Crud->read_field('name', 'Tax Master', 'access_role', 'id');

			$territorys = $this->Crud->read_single_order('role_id', $role_id, 'user', 'fullname', 'asc');
			echo '<option value=" ">'.translate_phrase('Select').'</option>';
			foreach($territorys as $qr) {
				$ter = json_decode($qr->territory);
				if(!empty($ter) && is_array($ter)){
					if(!in_array($territory, $ter))continue;
				}

				$sel = '';
				if(!empty($manager)){
					if($manager == $qr->id){
						$sel = 'selected';
					}
				}
				$hid = '';
				
				echo '<option value="'.$qr->id.'" '.$sel.'>'.$qr->fullname.'</option>';
			}
			
		}
	}

	public function qrcode($data=''){
       
        /* Data */
        $hex_data   = bin2hex($data);
        $save_name  = $hex_data . '.png';

        /* QR Code File Directory Initialize */
        $dir = 'assets/images/qr/profile/';
        if (! file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $config['cacheable']    = true;
        $config['imagedir']     = $dir;
        $config['quality']      = true;
        $config['size']         = '1024';
        $config['black']        = [255, 255, 255];
        $config['white']        = [255, 255, 255];
        $this->ciqrcode->initialize($config);

        /* QR Data  */
        $params['data']     = $data;
        $params['level']    = 'L';
        $params['size']     = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

        $this->ciqrcode->generate($params);

        /* Return Data */
        return [
            'content' => $data,
            'file'    => $dir . $save_name,
        ];
    }

	public function update_visitor(){
		$cells = $this->Crud->read_order('cell_report', 'date', 'asc');
		$service = $this->Crud->read_order('service_report', 'date', 'asc');

		if(!empty($cells)){
			foreach($cells as $cell){
				$first = json_decode($cell->timers, true);
				$converts = json_decode($cell->converts, true);
				
				$ins['source_type'] = 'cell';
				$ins['source_id'] = $cell->id;
				$ins['ministry_id'] = $cell->ministry_id;
				$ins['church_id'] = $cell->church_id;
				$ins['visit_date'] = $cell->date;

				if (!empty($first)) {
					$ins['category'] = 'first_timer';
				
					foreach ($first as $f => $f_value) {
						// Preparing data for insertion
						$ins['fullname'] = $f_value['fullname'];
						$ins['email'] = $f_value['email'];
						$ins['phone'] = $f_value['phone'];
						$ins['dob'] = $f_value['dob'];
						$ins['invited_by'] = isset($f_value['invited_by']) ? $f_value['invited_by'] : null;  // Preventing undefined index
						$ins['channel'] = isset($f_value['channel']) ? $f_value['channel'] : null;  // Preventing undefined index
						$ins['reg_date'] = date('Y-m-d H:i:s');  // Format date properly
				
						// Inserting the record into 'visitors' table
						$ins_rec = $this->Crud->create('visitors', $ins);
						
						// Assuming $ins_rec contains the newly inserted record ID
						if ($ins_rec) {
							// Add the new ID to the array
							$first[$f]['id'] = $ins_rec;
							$this->Crud->updates('id', $cell->id, 'cell_report', array('timers'=>json_encode($first)));
						}
					}
				}
				

				if(!empty($converts)){
					$ins['category'] = 'new_convert';
					foreach($converts as $f => $f_value){
						$ins['fullname'] = $f_value['fullname'];
						$ins['email'] = $f_value['email'];
						$ins['phone'] = $f_value['phone'];
						$ins['dob'] = $f_value['dob'];
						$ins['reg_date'] = date(fdate);

						$ins_rec = $this->Crud->create('visitors', $ins);
						if ($ins_rec) {
							// Add the new ID to the array
							$converts[$f]['id'] = $ins_rec;
							$this->Crud->updates('id', $cell->id, 'cell_report', array('converts'=>json_encode($converts)));
						}
					}
				}

			}
			// print_r($first);
		}

		if(!empty($service)){
			foreach($service as $cell){
				$first = json_decode($cell->timers, true);
				$converts = json_decode($cell->converts, true);
				
				$ins['source_type'] = 'service';
				$ins['source_id'] = $cell->id;
				$ins['ministry_id'] = $cell->ministry_id;
				$ins['church_id'] = $cell->church_id;
				$ins['visit_date'] = $cell->date;

				if(!empty($first)){
					$ins['category'] = 'first_timer';
					foreach($first as $f => $f_value){
						$ins['fullname'] = $f_value['fullname'];
						$ins['email'] = $f_value['email'];
						$ins['phone'] = $f_value['phone'];
						$ins['dob'] = $f_value['dob'];
						$ins['family_position'] = isset($f_value['family_position']) ? $f_value['family_position'] : null; 
						$ins['invited_by'] = isset($f_value['invited_by']) ? $f_value['invited_by'] : null; 
						$ins['channel'] = isset($f_value['channel']) ? $f_value['channel'] : null; 
						$ins['gender'] = isset($f_value['gender']) ? $f_value['gender'] : null; 
						$ins['reg_date'] = date(fdate);

						$ins_rec = $this->Crud->create('visitors', $ins);

						if ($ins_rec) {
							// Add the new ID to the array
							$first[$f]['id'] = $ins_rec;
							$this->Crud->updates('id', $cell->id, 'service_report', array('timers'=>json_encode($first)));
						}
					}
				}

				if(!empty($converts)){
					$ins['category'] = 'new_convert';
					foreach($converts as $f => $f_value){
						$ins['fullname'] = $f_value['fullname'];
						$ins['email'] = $f_value['email'];
						$ins['phone'] = $f_value['phone'];
						$ins['dob'] = $f_value['dob'];
						$ins['reg_date'] = date(fdate);

						$ins_rec = $this->Crud->create('visitors', $ins);
						if ($ins_rec) {
							// Add the new ID to the array
							$converts[$f]['id'] = $ins_rec;
							$this->Crud->updates('id', $cell->id, 'service_report', array('converts'=>json_encode($converts)));
						}
					}
				}

			}
			// print_r($first);
		}
	}

	public function update_church(){
		$mem = $this->Crud->read_single('church_id >', 0,'user');
		if(!empty($mem)){
			foreach($mem as $m){
				$church_id = $m->church_id;
				$inz['church_type'] = $this->Crud->read_field('id', $church_id, 'church', 'type');
				$inz['regional_id'] = $this->Crud->read_field('id', $church_id, 'church', 'regional_id');
				$inz['zonal_id'] = $this->Crud->read_field('id', $church_id, 'church', 'zonal_id');
				$inz['group_id'] = $this->Crud->read_field('id', $church_id, 'church', 'group_id');
				
				$this->Crud->updates('id', $m->id, 'user', $inz);

			}
		}

	}

	public function church_admin($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if ($this->session->get('td_id') == '') {
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		}

		$mod = 'accounts/church_admin';
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

		$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
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
						$title = $this->Crud->read_field('id', $admin_id, 'user', 'title');
						$email = $this->Crud->read_field('id', $admin_id, 'user', 'email');
						$phone = $this->Crud->read_field('id', $admin_id, 'user', 'phone');
						$ministry_id = $this->Crud->read_field('id', $admin_id, 'user', 'ministry_id');
						$church_id = $this->Crud->read_field('id', $admin_id, 'user', 'church_id');
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');

						$name = ucwords($firstname . ' ' . $othername . ' ' . $surname);
						$reset_link = site_url('auth/email_verify?uid=' . $user_no);
						$link = '<p><a href="' .$reset_link . '">Set Your Password</a></p>';
						$body = '
							Dear ' . esc($title.' '.$name) . ', <br><br>

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
							$head = 'Welcome to ' . $church . ' - Set Your Password';

							$upd_rec = $this->Crud->mailgun($email, $head, $body, $church);
			
							// echo $upd_rec;
							if (!empty($upd_rec)) {
								$mailgun_response = json_decode($upd_rec, true); // Decode the JSON response

								if (isset($mailgun_response['message']) && stripos($mailgun_response['message'], 'Queued') !== false) {
									echo $this->Crud->msg('success', 'Login Credential Sent to Email Successfully');
								echo '<script>
										load_admin("","",' . $church_id . ');
										$("#modal").modal("hide");
									</script>';
								} else {
									echo $this->Crud->msg('danger', 'Error Sending Email');
								}
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
					if(empty($ministry_id))$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
					
					$urole_id = $this->Crud->read_field('name', 'Ministry Administrator', 'access_role', 'id');
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
			$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
			$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
			$church_type = $this->Crud->read_field('id', $log_id, 'user', 'church_type');
			$rolz = 'Ministry Administrator';
			if($church_type == 'region')$rolz = 'Regional Manager';
			if($church_type == 'zone')$rolz = 'Zonal Manager';
			if($church_type == 'group')$rolz = 'Group Manager';
			if($church_type == 'church')$rolz = 'Church Leader';
			if($church_type == 'service')$rolz = 'Center Manager';
			
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
				$role_ids = $this->Crud->read_field('name', $rolz, 'access_role', 'id');

				$all_rec = $this->Crud->filter_church_admin('', '', $log_id, $status, $search, $church_id, $ministry_id);
				// $all_rec = json_decode($all_rec);
				if (!empty($all_rec)) {
					$counts = count($all_rec);
				} else {
					$counts = 0;
				}

				$query = $this->Crud->filter_church_admin($limit, $offset, $log_id, $status, $search, $church_id, $ministry_id);
				$data['count'] = $counts;


				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$fullname = $q->firstname . ' ' . $q->surname;
						$title = $q->title;
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
											<span class="tb-lead"><b>'.$title.' ' . ucwords($fullname) . ' </b><span class="dot dot-' . $a_color . ' ms-1"></span></span>
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
		} else {
			$data['title'] = translate_phrase('Church Administrator') . ' - ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}

	}


}