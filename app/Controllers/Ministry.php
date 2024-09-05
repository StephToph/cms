<?php

namespace App\Controllers;

class Ministry extends BaseController {

	
	public function index($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'ministry';

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
       
		
		$table = 'ministry';
		$form_link = site_url($mod.'/index/');
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
						$code = $this->Crud->read_field('id', $del_id, 'ministry', 'name');
						$action = $by.' deleted Ministry ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Ministry Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
					}
				}
			} elseif($param2 == 'admin_send'){ 
				if($param3){
					$admin_id = $param3;
					if($admin_id){
						$surname = $this->Crud->read_field('id', $admin_id, 'user', 'surname');
						$firstname = $this->Crud->read_field('id', $admin_id, 'user', 'firstname');
						$othername = $this->Crud->read_field('id', $admin_id, 'user', 'othername');
						$user_no = $this->Crud->read_field('id', $admin_id, 'user', 'user_no');
						$email = $this->Crud->read_field('id', $admin_id, 'user', 'email');
						$phone = $this->Crud->read_field('id', $admin_id, 'user', 'phone');
						$ministry_id = $this->Crud->read_field('id', $admin_id, 'user', 'ministry_id');
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						
						$name = ucwords($firstname.' '.$othername.' '.$surname);
						$reset_link = site_url('auth/email_verify?uid='.$user_no);
						$link = '<p><a href="' . htmlspecialchars($reset_link) . '">Set Your Password</a></p>';
						$body = '
							Dear '.$name.', <br><br>
								<p>A Ministry Administrator account has been created for you on the ' . htmlspecialchars(ucwords($ministry)) . ' within the ' . htmlspecialchars(app_name) . ' platform.</p>
    							Below are your Account Details:<br><br>

								Website: '.site_url().'
								Membership ID: '.$user_no.'<br>
								Email: '.$email.'<br>
								Phone: '.$phone.'<br>
								
								<p>To ensure the security of your account, please set your password by clicking the link below:</p>
    

								'.$link.'

								<p>This link will direct you to a secure page where you can choose your own password. If you encounter any issues or have questions, please feel free to contact our support team.</p>
								<p><strong>Important:</strong> Do not disclose your login credentials to anyone to avoid unauthorized access.</p>
								<p>Welcome aboard, and we look forward to your participation!</p>
								<p>Best regards,<br>
								
						';
						if($this->request->getMethod() == 'post'){
							$head = 'Welcome to '.$ministry.' - Set Your Password';
							$email_status = $this->Crud->send_email($email, $head, $body);
							if($email_status > 0){
								echo $this->Crud->msg('success', 'Login Credential Sent to Email Successfully');
								echo '<script>
										load_admin('.$ministry_id.');
										$("#modal").modal("hide");
									</script>';
							} else {
								echo $this->Crud->msg('danger', 'Error Sending Email');
							}
							die;	
						}
						
					}
					
				}
			} elseif($param2 == 'admin'){
				if($param3) {
					$table = 'user';
					$ministry_id = $param3;
					$admin_role = $this->Crud->read_field('name', 'Ministry Administrator', 'access_role', 'id');
					$admin_id = $this->Crud->read_field2('ministry_id', $ministry_id, 'role_id', $admin_role, 'user', 'id');
					$edit = $this->Crud->read_single('id', $admin_id, 'user');
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
							$data['e_surname'] = $e->surname;
							$data['e_email'] = $e->email;
							$data['e_title'] = $e->title;
							$data['e_phone'] = $e->phone;
							$data['e_firstname'] = $e->firstname;
						}
					}

					if($this->request->getMethod() == 'post'){
						$ministry_id = $this->request->getVar('ministry_id');
						$surname = $this->request->getVar('surname');
						$email = $this->request->getVar('email');
						$phone = $this->request->getVar('phone');
						$title = $this->request->getVar('title');
						$firstname = $this->request->getVar('firstname');
						
						if(empty($title) || $title == ' '){
							echo $this->Crud->msg('danger', 'Select Title');
							die;
						}
						$ins_data['surname'] = $surname;
						$ins_data['title'] = $title;
						$ins_data['email'] = $email;
						$ins_data['firstname'] = $firstname;
						$ins_data['phone'] = $phone;

						// do create or update
						if($admin_id) {
							$upd_rec = $this->Crud->updates('id', $admin_id, $table, $ins_data);
							if($upd_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $admin_id, 'user', 'firstname');
								$action = $by.' updated Ministry Administrator ('.$code.') Record';
								$this->Crud->activity('user', $admin_id, $action);
	
								echo $this->Crud->msg('success', 'Administrator Updated');
								echo '<script>
									load_admin('.$ministry_id.');
									$("#modal").modal("hide");
								</script>';
							} else {
								echo $this->Crud->msg('info', 'No Changes');	
							}
						} else {
							if($this->Crud->check('email', $email, $table) > 0) {
								echo $this->Crud->msg('warning', 'Email Already Exist');
							} else {
								$ins_data['role_id'] = $admin_role;
								$ins_data['password'] = md5('admin');
								$ins_data['ministry_id'] = $ministry_id;
								$ins_data['activate'] = 1;
								$ins_data['is_admin'] = 1;
								$ins_data['reg_date'] = date(fdate);
								$ins_rec = $this->Crud->create($table, $ins_data);
								if($ins_rec > 0) {
									$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));
									///// store activities
									$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
									$code = $this->Crud->read_field('id', $ins_rec, 'user', 'firstname');
									$action = $by.' created Administrator ('.$code.') Record';
									$this->Crud->activity('user', $ins_rec, $action);
	
									echo $this->Crud->msg('success', 'Administrator Created');
									echo '<script>
										load_admin('.$ministry_id.');
										$("#modal").modal("hide");
									</script>';
								} else {
									echo $this->Crud->msg('danger', 'Please try later');	
								}	
							}
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
								$data['e_name'] = $e->name;
								$data['e_email'] = $e->email;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_img'] = $e->logo;
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$dept_id = $this->request->getVar('dept_id');
					$name = $this->request->getVar('name');
					$email = $this->request->getVar('email');
					$phone = $this->request->getVar('phone');
					$address = $this->request->getVar('address');
					$img_id =  $this->request->getVar('img');

					
					//// Image upload
					if (file_exists($this->request->getFile('pics'))) {
						if (!empty($img_id)) {
							unlink(FCPATH . $img_id);
						}
						$path = 'assets/images/ministry/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);

						if (!empty($getImg->path)) $img_id = $getImg->path;
					}

					if(empty($img_id)){
						echo $this->Crud->msg('warning', 'You must enter your Ministry Logo to Continue');
						die;
					}

					$ins_data['name'] = $name;
					$ins_data['email'] = $email;
					$ins_data['address'] = $address;
					$ins_data['phone'] = $phone;
					if (!empty($img_id) || !empty($getImg->path))  $ins_data['logo'] = $img_id;
					
					// do create or update
					if($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'ministry', 'name');
							$action = $by.' updated Ministry ('.$code.') Record';
							$this->Crud->activity('miinistry', $dept_id, $action);

							echo $this->Crud->msg('success', 'Ministry Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Ministry Already Exist');
						} else {
							
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'ministry', 'name');
								$action = $by.' created Ministry ('.$code.') Record';
								$this->Crud->activity('miinistry', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Ministry Created');
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
					<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Contact').'</span></div>
					<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Address').'</span></div>
					<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Date').'</span></div>
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
				
				$all_rec = $this->Crud->filter_ministry('', '', '', $log_id, $search);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_ministry($limit, $offset, '', $log_id, $search);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$email = $q->email;
						$phone = $q->phone;
						$logo = $q->logo;
						$address = $q->address;
						$reg_date = date('d/m/Y', strtotime($q->reg_date));

						if (!empty($logo)) {
							$img = '<img height="40px" width="50px"  src="' . site_url($logo) . '">';
						} else {
							$img = $this->Crud->image_name($name);
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/index/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/index/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" onclick="church_admin(' . (int)$id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>'.translate_phrase('Admin').'</span></a></li>
								
								
							';
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<div class="user-card">
								      	<div class="user-avatar">            
									  		'.$img.'      
										</div>        
										<div class="user-name">            
											<span class="tb-lead">' . ucwords($name) . '</span>        
										</div>    
									</div>  
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark">'.$email.'</span><br>
									<span class="text-dark">'.$phone.'</span>
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark">'.$address.'</span>
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark">'.$reg_date.'</span>
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
						<br/><br/><br/>
						<i class="ni ni-server" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Ministry Returned').'
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

		if($param1 == 'load_admin'){
			$ministry_id = $this->request->getPost('ministry_id');
			if($ministry_id){
				$admin_role = $this->Crud->read_field('name', 'Ministry Administrator', 'access_role', 'id');
				$admin_id = $this->Crud->read_field2('ministry_id', $ministry_id, 'role_id',  $admin_role, 'user', 'id');
				if(empty($admin_id))$admin_id = 0;
				$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
				if($this->Crud->check2('ministry_id', $ministry_id, 'role_id',  $admin_role, 'user') == 0){
					$status = 0;
					$title =  '<span class="text-danger">-</span>';
					$name =  '<span class="text-danger">-</span>';
					$surname =  '<span class="text-danger">-</span>';
					$firstname =  '<span class="text-danger">-</span>';
					$user_id =  '<span class="text-danger">-</span>';
					$last_log =  '<span class="text-danger">-</span>';
					$email =  '<span class="text-danger">-</span>';
					$phone =  '<span class="text-danger">-</span>';
					$user_role =  '<span class="text-danger">-</span>';

					$btn_text = 'Add Admin';
					$sends_text = '';
					$send_text = '<span class="text-danger">Click on the <b>Add Administrator</b> Button to add or edit the Ministry Adminstrator</span>';

				} else {
					$status = 1;
					
					$title = $this->Crud->read_field('id', $admin_id, 'user', 'title');
					$surname = $this->Crud->read_field('id', $admin_id, 'user', 'surname');
					$firstname = $this->Crud->read_field('id', $admin_id, 'user', 'firstname');
					$user_id = $this->Crud->read_field('id', $admin_id, 'user', 'user_no');
					$last_log = $this->Crud->read_field('id', $admin_id, 'user', 'last_log');
					$email = $this->Crud->read_field('id', $admin_id, 'user', 'email');
					$role_ids = $this->Crud->read_field('id', $admin_id, 'user', 'role_id');
					$user_role = $this->Crud->read_field('id', $role_ids, 'access_role', 'name');
					if(empty($last_log)){
						$last_log = 'Not Logged In';
					}
					$phone = $this->Crud->read_field('id', $admin_id, 'user', 'phone');
					$name = ucwords($firstname.' '.$surname);
					$btn_text = 'Edit Admin';$send_text = '';
					$sends_text = '';
				}

				$resp['status'] = $status;
				$resp['ministry'] = $ministry;
				$resp['fullname'] = $name;
				$resp['surname'] = $surname;
				$resp['firstname'] = $firstname;
				$resp['last_log'] = $last_log;
				$resp['user_id'] = $user_id;
				$resp['user_role'] = $user_role;
				$resp['email'] = $email;
				$resp['title'] = $title;
				$resp['phone'] = $phone;
				$resp['admin_id'] = $admin_id;
				$resp['send_text'] = $send_text;
				$resp['sends_text'] = $sends_text;
				$resp['btn_text'] = $btn_text;

				echo json_encode($resp);
				die;
			}
		}

		if($param1 == 'manage') { // view for form data posting
			return view($mod.'/form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Ministry').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod.'/list', $data);
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
       
		
		$table = 'cells';
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
							$code = $this->Crud->read_field('id', $cell_id, 'cells', 'name');
							$action = $by.' updated Cell ('.$code.') Record';
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
					<div class="nk-tb-col"><span class="sub-text text-dark">'.translate_phrase('Name').'</span></div>
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark">'.translate_phrase('Location').'</span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark">'.translate_phrase('Role(s)').'</span></div>
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark">'.('Day/Time').'</span></div>
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
				
				$all_rec = $this->Crud->filter_cell('', '', $search);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_cell($limit, $offset, $search);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$location = $q->location;
						$time = $q->time;
						$roles = $q->roles;
						$rolesa = json_decode($roles);
						$rols = '';
						if(!empty($rolesa)){
							foreach($rolesa as $r => $val){
								$rols .= $val.', ';
							}
						}

						$times = '<span class="text-danger">No Meeting Time</span>';
						if(!empty($time)){
							$times = '<a href="javascript:;" class="text-primary pop" pageTitle="View Time " pageName="' . site_url($mod . '/manage/view/' . $id) . '"><em class="icon ni ni-eye"></em> <span>'.translate_phrase('View Meeting Time').'</span></a>';
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								
								
							';
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">' . ucwords($name) . ' </span>
									</div>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">' . ucwords($location) . '</span>
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark"><b>' . ucwords(rtrim($rols, ', ')) . '</b></span>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">' . ($times) . '</span>
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
						<i class="ni ni-tranx" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Cell Returned').'
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
			
			$data['title'] = translate_phrase('Cells').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }
	public function creport($param1='', $param2='', $param3='', $param4='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'accounts/creport';

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
						$total = 0;
						
						if(empty($mark)){
							echo $this->Crud->msg('danger', 'Mark Meeting Attendance');
							die;
						} else{
							$total = count($mark);
							$this->session->set('cell_attendance', json_encode($mark));
							echo $this->Crud->msg('success', 'Meeting Attendance Submitted');
							// echo json_encode($mark);
							echo '<script> setTimeout(function() {
								var jsonData = ' . json_encode($mark) . ';
								var jsonString = JSON.stringify(jsonData);
								$("#converts").val(jsonString);
								$("#attendance").val('.$total.');
								$("#modal").modal("hide");
							}, 2000); </script>';
						}
						die;
					}
				}

			}  elseif($param2 == 'offering'){
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

			}elseif($param2 == 'first_timer'){
				
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
					
					// echo $date;die;
					$dates = date('y-m-d', strtotime($date));

					
					$ins_data['cell_id'] = $cell_id;
					$ins_data['type'] = $type;
					$ins_data['date'] = $dates;
					$ins_data['attendance'] = $attendance;
					$ins_data['new_convert'] = $new_convert;
					$ins_data['first_timer'] = $first_timer;
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
						if($this->Crud->check('date', $dates, $table) > 0) {
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
			$where = array('role_id' => $member_id, 'cell_id' => $cell_id);
			
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
        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 45;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			
			$items = '
				<div class="nk-tb-item nk-tb-head">
					<div class="nk-tb-col"><span class="sub-text text-dark">'.translate_phrase('Date').'</span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark">'.translate_phrase('Meeting').'</span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark">'.translate_phrase('Offering').'</span></div>
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark">'.translate_phrase('Attendance').'</span></div>
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark">'.('FT').'</span></div>
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark">'.('NC').'</span></div>
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
				
				$all_rec = $this->Crud->filter_cell_report('', '', $search);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_cell_report($limit, $offset, $search);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$type = $q->type;
						$cell_id = $q->cell_id;
						$attendance = $q->attendance;
						$offering = $q->offering;
						$new_convert = $q->new_convert;
						$first_timer = $q->first_timer;
						$date = date('d M Y', strtotime($q->date));
						$reg_date = $q->reg_date;

						$types = '';
						if($type == 'wk1')$types = 'WK1 - Prayer and Planning';
						if($type == 'wk2')$types = 'Wk2 - Bible Study';
						if($type == 'wk3')$types = 'Wk3 - Bible Study';
						if($type == 'wk4')$types = 'Wk4 - Fellowship / Outreach';
						
						$cell='';
						if($role == 'developer' || $role == 'administrator'){
							$cell = '<span class="text-info"><em class="icon ni ni-curve-down-right"></em> <span>'.strtoupper($this->Crud->read_field('id', $cell_id, 'cells', 'name')).'</span></span>';
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary" onclick="edit_report('.$id.')"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" class="text-success pop" pageTitle="View Report" pageName="' . site_url($mod . '/manage/report/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								
								
							';
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">' . ucwords($date) . ' </span>
										'.$cell.'
									</div>
								</div>
								<div class="nk-tb-col">
									<span class="text-dark">' . ucwords($types) . '</span>
								</div>
								<div class="nk-tb-col">
									<span class="text-dark">$' . number_format($offering,2) . '</span>
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark"><span>' . ucwords($attendance) . '</b></span>
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark"><span>' . ucwords($first_timer) . '</b></span>
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark"><span>' . ucwords($new_convert) . '</b></span>
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
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark"><b>'.('Date').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Member').'</b></span></div>
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark"><b>'.translate_phrase('Partnership').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Amount').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Status').'</b></span></div>
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
				
				$all_rec = $this->Crud->filter_givings('', '', $search, $log_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_givings($limit, $offset, $search, $log_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$member_id = $q->member_id;
						$partnership_id = $q->partnership_id;
						$amount_paid = $q->amount_paid;
						$status = $q->status;
						$reg_date = date('d M Y', strtotime($q->date_paid));
						$member = $this->Crud->read_field('id', $member_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $member_id, 'user', 'surname');
						$partnership = $this->Crud->read_field('id', $partnership_id, 'partnership', 'name');
						
						$st = '<span class="text-warning">Pending</span>';
						if($status > 0){
							$st = '<span class="text-success">Confirmed</span>';
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								
								
							';
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">' . ucwords($reg_date) . ' </span>
									</div>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">' . ucwords($member) . '</span>
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark">' . ucwords($partnership) . '</span>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">$' . number_format($amount_paid,2) . '</span>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">' . ($st) . '</span>
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
		}$data['p_start_date'] = $this->session->get('p_start_date');
		$data['p_end_date'] = $this->session->get('p_end_date');
		

        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			if(!empty($this->request->getVar('start_date'))) { $start_date = $this->request->getVar('start_date'); } else { $start_date = date('Y-01-01'); }
			if(!empty($this->request->getVar('end_date'))) { $end_date = $this->request->getVar('end_date'); } else { $end_date = date('Y-m-d'); }
			$this->session->set('p_start_date', $start_date);
			$this->session->set('p_end_date', $end_date);
			
			
			$items = '
				<div class="nk-tb-item nk-tb-head">
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Partnership').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Pledge').'</b></span></div>
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark"><b>'.translate_phrase('Participant').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Given').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Balance').'</b></span></div>
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark"><b>'.translate_phrase('Participant').'</b></span></div>
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
					
				$all_rec = $this->Crud->read_order('partnership', 'name', 'asc');
				// $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$query = $this->Crud->read_order('partnership', 'name', 'asc');
				$data['count'] = $counts;
				
				$roles_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$goal = 0;
						
						$balance = 0;
						$p_participant = 0;
						$g_participant = 0;

						$user = $this->Crud->read_single('role_id', $roles_id, 'user');
						if(!empty($user)){
							foreach($user as $u){$given = 0;
								$member_id = $u->id;
								$parts = $u->partnership;
								if(!empty($parts)){
									$partss = json_decode($parts);
									foreach($partss as $pa => $val){
										if($id == $pa){
											$goal += (float)$val;
											if($val > 0)$p_participant++;

											$g_participant = $this->Crud->date_check2($start_date, 'date_paid', $end_date, 'date_paid', 'status', 1, 'partnership_id', $pa, 'partners_history');
											$paids = $this->Crud->date_range2($start_date, 'date_paid', $end_date, 'date_paid','partnership_id', $pa, 'status', 1,  'partners_history');
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
						}
						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="View ' . $name . '" pageName="' . site_url($mod . '/manage/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								
								
							';
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead text-primary">' . ucwords($name) . '</span>
									</div>
								</div>
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">$' . number_format($goal,2) . ' </span>
									</div>
								</div>
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">' . number_format($p_participant) . ' </span>
									</div>
								</div>
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">$' . number_format($given,2) . '</span>
									</div>
								</div>
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">$' .number_format($balance,2) . '</span>
									</div>
								</div>
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">' . number_format($g_participant) . ' </span>
									</div>
								</div>
								<div class="nk-tb-col nk-tb-col-tools">
									' . $all_btn . '
													
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
			
			$data['title'] = translate_phrase('Parnership Analytics').' - '.app_name;
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
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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
			} elseif($param2 == 'upload'){ 
				if($param3 == 'download'){
					 // Path to the file (make sure this path is correct and accessible)
					$file_path = FCPATH . 'assets/product_template.xlsx'; 

					// Check if the file exists
					if (file_exists($file_path)) {
						// Get the file name from the path
						$file_name = basename($file_path);
						
						// Set headers to force download
						header('Content-Description: File Transfer');
						header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
						header('Content-Disposition: attachment; filename="' . $file_name . '"');
						header('Content-Transfer-Encoding: binary');
						header('Expires: 0');
						header('Cache-Control: must-revalidate');
						header('Pragma: public');
						header('Content-Length: ' . filesize($file_path));

						// Clear output buffering
						ob_clean();
						flush();

						// Read the file and output it
						readfile($file_path);
						exit();
					}
				}
				if($this->request->getMethod() == 'post'){
					$file = $this->request->getFile('csv_file');
					$records = $this->Crud->processFile($file);
					// $record = json_decode($records);

					if ($file->isValid()) {
						$filePath = WRITEPATH . 'assets/uploads/' . $file->getName();
						$file->move(WRITEPATH . 'assets/uploads/', $file->getName());
						
						// print_r($records);
						// die;
						// Read the file and insert records into the database
						if(!empty($records)){
							$member_email = [];

							$success= 0;$failed= 0;$exist=0;
							foreach($records as $dt => $val){
								$firstname = $val['firstname'];
								$othername = $val['othername'];
								$surname = $val['surname'];
								$email = strtolower($val['email']);
								$address = strtolower($val['address']);
								$phone = $val['phone'];
								$dob = $val['dob'];
								$marital_status = $val['marital_status'];
								$marriage_anniversary = $val['marriage_anniversary'];
								$title = $val['title'];
								$gender = $val['gender'];
								$chat_handle = $val['chat_handle'];
								$job = $val['job'];
								$foundation_school = $val['foundation_school'];
								$baptism = $val['baptism'];
								$employer_address = $val['employer_address'];

								$ins_data['title'] = ucwords($title);
								$ins_data['firstname'] = $firstname;
								$ins_data['othername'] = $othername;
								$ins_data['surname'] = $surname;
								$ins_data['email'] = $email;
								$ins_data['phone'] = '0'.$phone;
								$ins_data['gender'] = ucwords($gender);
								$ins_data['address'] = $address;
								$ins_data['marriage_anniversary'] = $marriage_anniversary;
								$ins_data['job_type'] = $job;
								$ins_data['employer_address'] = $employer_address;
								$ins_data['baptism'] = strtolower($baptism);
								$ins_data['foundation_school'] = strtolower($foundation_school);
								$ins_data['chat_handle'] = strtolower($chat_handle);
								$ins_data['dob'] = $dob;
								$ins_data['family_status'] = strtolower($marital_status);
								$ins_data['password'] = md5($surname);
					
								$role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
								$ins_data['role_id'] = $role_id;
								$ins_data['activate'] = 1;
								$ins_data['reg_date'] = date(fdate);
								$ins_rec = $this->Crud->create($table, $ins_data);
								if($ins_rec > 0) {
									///// store activities
									$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
									$code = $this->Crud->read_field('id', $ins_rec, 'user', 'surname');
									$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));

									$user_no = 'CEAM-00'.$ins_rec;

									$action = $by.' created Membership ('.$code.') Record';
									$this->Crud->activity('user', $ins_rec, $action);
									$name = ucwords($firstname.' '.$othername.' '.$surname);
									$body = '
										Dear '.$name.', <br><br>
											A Membership Account Has been Created with This Email on Chrsit Embassy Aurora Platform;<br>
											Below are your login Credentials:<br><br>

											Website: '.site_url().'
											Membership ID: '.$user_no.'<br>
											Email: '.$email.'<br>
											Phone: '.$phone.'<br>
											Password: '.$surname.'<br><br>
											Do not disclose your Login credentials with anyone to avoid unauthorized access.
											
									';
									$this->Crud->send_email($email, 'Membership Account', $body);
									$success++;
								} else {
									$failed++;
								}	
								
								
							} 
							$msg = '';
							if($success > 0)$msg .= $success.' Membership(s) Uploaded Successfully<br> ';
							// if($exist > 0)$msg .= $exist.' Product(s) Uploading Exist Already <br>';
							if($failed > 0)$msg .= $failed.' Membership(s) not Uploaded';

							
							$by = $this->Crud->read_field('id', $log_id, 'user', 'surname');
							$action = $msg.' by '.$by;
							$this->Crud->activity('membership', $log_id, $action, $log_id);
							// echo $val['name'];
							echo $this->Crud->msg('info', $msg);
							if($success > 0)echo '<script>window.location.replace("'.site_url('accounts/membership').'");</script>';
						} else {
							echo $this->Crud->msg('danger',' Error Uploading Membership! Check Excel File');
						}
					}
					die;
				}
			}else {
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
								$data['e_title'] = $e->title;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_gender'] = $e->gender;
								$data['e_marriage_anniversary'] = $e->marriage_anniversary;
								$data['e_job_type'] = $e->job_type;
								$data['e_employer_address'] = $e->employer_address;
								$data['e_baptism'] = $e->baptism;
								$data['e_foundation_school'] = $e->foundation_school;
								$data['e_chat_handle'] = $e->chat_handle;
								$data['e_dob'] = $e->dob;
								$data['e_family_status'] = $e->family_status;
								$data['e_family_position'] = $e->family_position;
								$data['e_cell_id'] = $e->cell_id;
								$data['e_cell_role'] = $e->cell_role;
								$data['e_dept_id'] = $e->dept_id;
								$data['e_dept_role'] = $e->dept_role;
								$data['e_parent_id'] = $e->parent_id;
								
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$membership_id = $this->request->getVar('membership_id');
					$firstname = $this->request->getVar('firstname');
					$lastname = $this->request->getVar('lastname');
					$othername = $this->request->getVar('othername');
					$gender = $this->request->getVar('gender');
					$email = $this->request->getVar('email');
					$phone = $this->request->getVar('phone');
					$dob = $this->request->getVar('dob');
					$chat_handle = $this->request->getVar('chat_handle');
					$address = $this->request->getVar('address');
					$family_status = $this->request->getVar('family_status');
					$family_position = $this->request->getVar('family_position');
					$parent_id = $this->request->getVar('parent_id');
					$dept_id = $this->request->getVar('dept_id');
					$dept_role_id = $this->request->getVar('dept_role_id');
					$cell_id = $this->request->getVar('cell_id');
					$cell_role_id = $this->request->getVar('cell_role_id');
					$title = $this->request->getVar('title');
					$password = $this->request->getVar('password');
					$marriage_anniversary = $this->request->getVar('marriage_anniversary');
					$job_type = $this->request->getVar('job_type');
					$employer_address = $this->request->getVar('employer_address');
					$baptism = $this->request->getVar('baptism');
					$foundation_school = $this->request->getVar('foundation_school');
					
					// echo $baptism;
					// die;
					$ins_data['title'] = $title;
					$ins_data['firstname'] = $firstname;
					$ins_data['othername'] = $othername;
					$ins_data['surname'] = $lastname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['gender'] = $gender;
					$ins_data['address'] = $address;
					$ins_data['marriage_anniversary'] = $marriage_anniversary;
					$ins_data['job_type'] = $job_type;
					$ins_data['employer_address'] = $employer_address;
					$ins_data['baptism'] = $baptism;
					$ins_data['foundation_school'] = $foundation_school;
					$ins_data['chat_handle'] = $chat_handle;
					$ins_data['dob'] = $dob;
					$ins_data['family_status'] = $family_status;
					$ins_data['family_position'] = $family_position;
					$ins_data['parent_id'] = $parent_id;
					$ins_data['dept_id'] = $dept_id;
					$ins_data['dept_role'] = $dept_role_id;
					$ins_data['cell_id'] = $cell_id;
					$ins_data['cell_role'] = $cell_role_id;
					if($password) { $ins_data['password'] = md5($password); }
					
					// do create or update
					if($membership_id) {
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
						$role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
						$ins_data['role_id'] = $role_id;
						$ins_data['activate'] = 1;
						$ins_data['reg_date'] = date(fdate);
						$ins_rec = $this->Crud->create($table, $ins_data);
						if($ins_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $ins_rec, 'user', 'surname');
							$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));

							$user_no = 'CEAM-00'.$ins_rec;

							$action = $by.' created Membership ('.$code.') Record';
							$this->Crud->activity('user', $ins_rec, $action);
							$name = ucwords($firstname.' '.$othername.' '.$lastname);
							$body = '
								Dear '.$name.', <br><br>
									A Membership Account Has been Created with This Email on Chrsit Embassy Aurora Platform;<br>
									Below are your login Credentials:<br><br>

									Website: '.site_url().'
									Membership ID: '.$user_no.'<br>
									Email: '.$email.'<br>
									Phone: '.$phone.'<br>
									Password: '.$password.'<br><br>
									Do not disclose your Login credentials with anyone to avoid unauthorized access.
									
							';
							$this->Crud->send_email($email, 'Membership Account', $body);


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

		if($param1 == 'get_dept_role'){
			if(!empty($param2)){
				
				$li = '';
				$dept = $this->Crud->read_field('id', $param2, 'dept', 'name');
				$dept_role = $this->Crud->read_field('id', $param2, 'dept', 'roles');
				if($dept != 'member'){
					$li = '<option value="">Select Deparment Role</option>';
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
				}
				$resp['list'] = $li;
				$resp['script'] = '<script>$("#dept_resp").show(500);</script>';

				echo json_encode($resp);
				die;
			}
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
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark"><b>'.translate_phrase('Title').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Name').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Member ID').'</b></span></div>
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark"><b>'.('Phone').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Email').'</b></span></div>
					<div class="nk-tb-col nk-tb-col-md"><span class="sub-text text-dark"><b>'.translate_phrase('Kingschat Handle').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Cell').'</b></span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('DOB').'</b></span></div>
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
				
				$all_rec = $this->Crud->filter_membership('', '', $log_id, $search);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$query = $this->Crud->filter_membership($limit, $offset, $log_id, $search);
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
						$chat_handle = $q->chat_handle;
						$dob = date('d M Y', strtotime($q->dob));
						if(empty($dob))$dob = '-';
						$cell_id = $q->cell_id;
						$title = $q->title;
						$activate = $q->activate;
						
						$cell = '-';
						if(!empty($q->cell_id)){
							$cell = $this->Crud->read_field('id', $q->cell_id, 'cells', 'name');
						}
						$name = $firstname.' '.$othername.' '.$surname;

						if(empty($phone))$phone = '-';
						if(empty($email))$email = '-';
						if(empty($title))$title = '-';
						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="' . site_url($mod . '/manages/edit/' . $id) . '" class="text-info" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manages/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="' . site_url($mod . '/view/' . $id) . '" class="text-success" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName=""><em class="icon ni ni-eye"></em><span>'.translate_phrase('View Records').'</span></a></li>
								<li><a href="' . site_url($mod . '/partnership/' . $id) . '" class="text-primary" pageTitle="View ' . $name . '" pageSize="modal-lg" pageName=""><em class="icon ni ni-link"></em><span>'.translate_phrase('Partnership Records').'</span></a></li>
								
								
							';
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">' . ucwords($title) . '</span>
								</div>
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead"><b>' . ucwords($name) . '</b> </span>
									</div>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">' . ($user_no) . '</span>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">' . ($phone) . '</span>
								</div>
								<div class="nk-tb-col tb-col">
									<span class="text-dark">' . ($email) .'</span>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">' . ucwords($chat_handle) . '</span>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">' . ($cell) . '</span>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="text-dark">' . ($dob) . '</span>
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
						<i class="ni ni-user" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Membership Returned').'
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
					<div class="nk-tb-item nk-tb-head">
						<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Partnership').'</b></span></div>
						<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Pledge').'</b></span></div>
						<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Given').'</b></span></div>
						<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Balance').'</b></span></div>
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
								<li><a href="javascript:;" class="text-primary pop" pageTitle="View ' . $name . '" pageName="' . site_url($mod . '/manage/view/' . $id.'/'.$member_id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								
									
								';
							

							$item .= '
								<div class="nk-tb-item">
									<div class="nk-tb-col">
										<div class="user-info">
											<span class="tb-lead text-primary">' . ucwords($name) . '</span>
										</div>
									</div>
									<div class="nk-tb-col">
										<div class="user-info">
											<span class="tb-lead">$' . number_format($goal,2) . ' </span>
										</div>
									</div>
									<div class="nk-tb-col">
										<div class="user-info">
											<span class="tb-lead">$' . number_format($given,2) . '</span>
										</div>
									</div>
									<div class="nk-tb-col">
										<div class="user-info">
											<span class="tb-lead">$' .number_format($balance,2) . '</span>
										</div>
									</div>
									<div class="nk-tb-col nk-tb-col-tools">' . $all_btn . '</div>
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
							<i class="ni ni-user" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Membership Returned').'
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

	
	public function customer_details($param1='', $param2='', $param3='', $param4='') {
        // record listing
		$log_id = $this->session->get('td_id');
       
		if($param1 == 'activity' && $param2 == 'load') {
			$limit = $param3;
			$offset = $param4;
			$rec_limit = 25;
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
				$query = $this->Crud->filter_activity($limit, $offset, $user_id, $search, $start_date, $end_date);
				$all_rec = $this->Crud->filter_activity('', '', $user_id, $search, $start_date, $end_date);
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

		if($param1 == 'notification' && $param2 == 'load') {
			$limit = $param3;
			$offset = $param4;
			$rec_limit = 25;
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
				$query = $this->Crud->read_single('to_id', $user_id, 'notify', $limit, $offset);
				$all_rec = $this->Crud->read_single('to_id', $user_id, 'notify', '', '');
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
							$from = $this->Crud->read_field('id', $from_id, 'user', 'fullname');
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

		if($param1 == 'order' && $param2 == 'load') {
			$limit = $param3;
			$offset = $param4;
			$rec_limit = 25;
			$item = '';
            if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}

			$user_id = $this->request->getVar('u_id');
			if(!empty($this->request->getVar('type'))) { $type = $this->request->getVar('type'); } else { $type = ''; }
			if(!empty($this->request->getVar('start_date'))) { $start_date = $this->request->getVar('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getVar('end_date'))) { $end_date = $this->request->getVar('end_date'); } else { $end_date = ''; }
			$search = $this->request->getVar('search');

			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$query = $this->Crud->filter_transaction($limit, $offset, $user_id, $type, $search, $start_date, $end_date);
				$all_rec = $this->Crud->filter_transaction('', '', $user_id, $type, $search, $start_date, $end_date);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$curr = '&#8358;';
				$items = '	
					<div class="nk-tb-item nk-tb-head">
						<div class="nk-tb-col"><span>'.translate_phrase('Transaction Code').'</span></div>
						<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Account').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Payment Type').'</span></div>
						<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Amount').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Status').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Date').'</span></div>
					</div><!-- .nk-tb-item -->
						';

				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$user_id = $q->user_id;
						$payment_type = $q->payment_type;
						$payment_method = $q->payment_method;
						$code = $q->code;
						$merchant_id = $q->merchant_id;
						$status = $q->status;
						$amount = number_format((float)$q->amount, 2);
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));


						// user 
						$user = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
						$user_role_id = $this->Crud->read_field('id', $user_id, 'user', 'role_id');
						$user_role = strtoupper($this->Crud->read_field('id', $user_role_id, 'access_role', 'name'));
						$user_image_id = $this->Crud->read_field('id', $user_id, 'user', 'img_id');
						$user_image = $this->Crud->image($user_image_id, 'big');

						// merchant 
						$merchant = $this->Crud->read_field('id', $merchant_id, 'user', 'fullname');
						$merchant_role_id = $this->Crud->read_field('id', $merchant_id, 'user', 'role_id');
						$merchant_role = strtoupper($this->Crud->read_field('id', $merchant_role_id, 'access_role', 'name'));
						$merchant_image_id = $this->Crud->read_field('id', $merchant_id, 'user', 'img_id');
						$merchant_image = $this->Crud->image($merchant_image_id, 'big');

						$mer = '';
						if(!empty($merchant_id)){
							$mer = '<span class="text-danger">'.$merchant.'</span>';
						}
						$act = $mer.'<br><span class="text-primary">&rarr;'.$user.'</span>';


						if($payment_type == 'transact'){
							$payment_type = 'Transaction Code';

							$act = '<span class="text-info">'.$user.'</span>';
						} 

						if($payment_type == 'sms'){
							$payment_type = 'SMS Charge';

							$act = '<span class="text-info">'.$user.'</span>';
						} 

						
						// currency
						$curr = '&#8358;';

						// color
						$color = 'success';
						if($payment_type == 'debit') { $color = 'danger'; }

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<small class="text-muted d-md-none d-sm-block">'.strtoupper($reg_date).'</small><br>
									<span class="fw-bold text-success pop">
										<a href="javascript:;" class="text-success pop" pageTitle="View" pageName="'.site_url('wallets/transaction/view/'.$id).'" pageSize="modal-lg">
											<i class="ni ni-edit"></i> <span class="m-l-3 m-r-10"><b>'.$code.'</b></span>
										</a></span><br>
									<span class="fw-bold text-secondary d-md-none d-sm-block">'.strtoupper($payment_type).'</span>
								</div>
								<div class="nk-tb-col">
									<div class="user-card">
										<div class="user-info">
											'.$act.'
										</div>
									</div>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="fw-bold text-secondary">'.strtoupper($payment_type).'</span>
								</div>
								<div class="nk-tb-col">
									<span>'.$curr.$amount.'</span><br>
									<span class="badge badge-dot text-success d-md-none d-sm-block">Success</span>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span class="badge badge-dot text-success">Success</span><br>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span>'.$reg_date.'</span>
								</div>
							</div>
							
						';
					}
				}
			}
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-tranx" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Transaction Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
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
			
		}

		if($param1 == 'wallet' && $param2 == 'load') {
			$limit = $param3;
			$offset = $param4;
			$rec_limit = 25;
			$item = '';
            if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}

			$user_id = $this->request->getVar('u_id');
			
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$query = $this->Crud->filter_wallet($limit, $offset, $user_id);
				$all_rec = $this->Crud->filter_wallet('', '', $user_id);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$curr = '&#8358;';
				$items = '	
					<div class="nk-tb-item nk-tb-head">
						<div class="nk-tb-col"><span>'.translate_phrase('Wallet Type').'</span></div>
						<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Amount').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Date').'</span></div>
					</div><!-- .nk-tb-item -->
						';

				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$user_id = $q->user_id;
						$type = $q->type;
						$itema = $q->item;
						$amount = number_format((float)$q->amount, 2);
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));

						
						// currency
						$curr = '&#8358;';

						// color
						$color = 'success';
						if($type == 'debit') { $color = 'danger'; }

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<span class="fw-bold text-'.$color.'">'.strtoupper($type).'</span><br>
									<span class="fw-bold text-dark">'.strtoupper($itema).'</span>
									<small class="text-muted d-md-none d-sm-block">'.strtoupper($reg_date).'</small><br>
									<span class="fw-bold text-'.$color.' d-md-none d-sm-block">'.strtoupper($type).'</span>
								</div>
								<div class="nk-tb-col">
									<span>'.$curr.$amount.'</span><br>
									<span class="fw-bold text-dark d-md-none d-sm-block">'.strtoupper($itema).'</span>
								</div>
								<div class="nk-tb-col tb-col-md">
									<span>'.$reg_date.'</span>
								</div>
							</div>
							
						';
					}
				}
			}
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-tranx" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Transaction Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
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

	
	public function announcement($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

		$log_id = $this->session->get('td_id');

		$mod = 'ministry/announcement';

		$data['current_language'] = $this->session->get('current_language');

		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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

		$table = 'announcement';
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
						$del_id = $this->request->getPost('d_announcement_id');
						if ($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', 'Record Deleted');
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
								$data['e_to_id'] = $e->to_id;
								$data['e_role_id'] = json_decode($e->role_id);
								$data['e_from_id'] = $e->from_id;
								$data['e_title'] = $e->title;
								$data['e_content'] = $e->content;
								$data['e_dept_id'] = $e->dept_id;
								$data['e_type'] = $e->type;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_id'] = ($e->church_id);
								$data['e_level'] = $e->level;
								$data['e_send_type'] = $e->send_type;
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
								$data['e_role_id'] = $e->role_id;
								$data['e_to_id'] = $e->to_id;
								$data['e_from_id'] = $e->from_id;
								$data['e_title'] = $e->title;
								$data['e_content'] = $e->content;
								$data['e_dept_id'] = $e->dept_id;
								$data['e_type'] = $e->type;
								$data['e_reg_date'] = date('M d, Y h:i A', strtotime($e->reg_date));
							}
						}
					}
				}


				if ($_POST) {
					$announcement_id = $this->request->getPost('announcement_id');
					$title = $this->request->getPost('title');
					$content = $this->request->getPost('content');
					$type = $this->request->getPost('type');
					$level = $this->request->getPost('level');
					$send_type = $this->request->getPost('send_type');
					$church_id = $this->request->getPost('church_id');
					$ministry_id = $this->request->getPost('ministry_id');
					$dept_id = $this->request->getPost('dept_id');
					$roles_id = $this->request->getPost('roles_id');

					if(empty($ministry_id)){
						echo $this->Crud->msg('warning', 'Select  Ministry');
						die;
					}

					$recps = [];
					$recps_church = [];
					
					if($level == 'all'){
						$church_id = 0;
						if($type == 'general'){
							$dept_id = 0;
							$user = $this->Crud->read_single('ministry_id', $ministry_id, 'user');
							if($user){
								foreach($user as $u){
									$recps[] = $u->id;
								}
							}

						} else{
							if($dept_id){
								$dept = $this->Crud->read2('dept_id', $dept_id, 'ministry_id', $ministry_id, 'user');
								if($dept){
									foreach($dept as $d){
										$recps[] = $d->id;
									}
								}
							}

						}
						$recps_church[] = $church_id;
					} else{
						if($send_type == 'individual'){
							for($i=0;$i<count($church_id);$i++){

								if($type == 'general'){
									$dept_id = 0;
									$user = $this->Crud->read_single('church_id',  $church_id[$i], 'user');
									if($user){
										foreach($user as $u){
											$recps[] = $u->id;
										}
									}
		
								} else{
									if($dept_id){
										$dept = $this->Crud->read2('dept_id', $dept_id, 'church_id',  $church_id[$i], 'user');
										if($dept){
											foreach($dept as $d){
												$recps[] = $d->id;
											}
										}
									}
		
								}
								
								$recps_church[] = $church_id[$i];
							}
						}

						if($send_type == 'general'){
							for($i=0;$i<count($church_id);$i++){
								if($type == 'general'){
									$dept_id = 0;
									$user = $this->Crud->read_single('church_id',  $church_id[$i], 'user');
									if($user){
										foreach($user as $u){
											$recps[] = $u->id;
										}
									}
		
								} else{
									if($dept_id){
										$dept = $this->Crud->read2('dept_id', $dept_id, 'church_id',  $church_id[$i], 'user');
										if($dept){
											foreach($dept as $d){
												$recps[] = $d->id;
											}
										}
									}
		
								}

								$recps_church[] = $church_id[$i];


								//Ge the church type and loop thru their hierarchy
								$church_type = $this->Crud->read_field('id', $church_id[$i], 'church', 'type');

								if($church_type ==  'region'){
									$types = $this->Crud->read_single('regional_id', $church_id[$i], 'church');
								}
								if($church_type ==  'zone'){
									$types = $this->Crud->read_single('zonal_id', $church_id[$i], 'church');
								}
								if($church_type ==  'group'){
									$types = $this->Crud->read_single('group_id', $church_id[$i], 'church');
								}

								if($types){
									foreach($types as $t){

										if($type == 'general'){
											$dept_id = 0;
											$user = $this->Crud->read_single('church_id',  $t->id, 'user');
											if($user){
												foreach($user as $u){
													$recps[] = $u->id;
												}
											}
				
										} else{
											if($dept_id){
												$dept = $this->Crud->read2('dept_id', $dept_id, 'church_id',  $t->id, 'user');
												if($dept){
													foreach($dept as $d){
														$recps[] = $d->id;
													}
												}
											}
				
										}

										
										$recps_church[] = $t->id;
									}
								}

								
							}
						}
					
					}

					if(empty($recps)){
						echo $this->Crud->msg('warning', 'No recipients in this Category');
						die;
					}

					$ins_data['title'] = $title;
					$ins_data['content'] = $content;
					$ins_data['to_id'] = json_encode($recps);
					$ins_data['type'] = $type;
					$ins_data['level'] = $level;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = json_encode($recps_church);
					$ins_data['send_type'] = $send_type;
					$ins_data['dept_id'] = $dept_id;

					// print_r($recps);
					// do create or update
					if ($announcement_id) {
						$upd_rec = $this->Crud->updates('id', $announcement_id, $table, $ins_data);
						if ($upd_rec > 0) {
							echo $this->Crud->msg('success', 'Record Updated');
							foreach (json_decode($recps) as $re => $val) {
								$in_data['from_id'] = $log_id;
								$in_data['to_id'] = $val;
								$in_data['content'] = $content;
								$in_data['item'] = 'announcement';
								$in_data['new'] = 1;
								$in_data['reg_date'] = date(fdate);
								$in_data['item_id'] = $announcement_id;
								$this->Crud->create('notify', $in_data);
							}
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
							$code = $this->Crud->read_field('id', $announcement_id, 'announcement', 'title');
							$action = $by . ' updated (' . $code . ') Announcement ';
							$this->Crud->activity('announcement', $announcement_id, $action, $log_id);
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						if ($this->Crud->check2('to_id', $log_id, 'title', $title, $table) > 0) {
							echo $this->Crud->msg('warning', 'Announcement Already Exist');
						} else {
							$ins_data['reg_date'] = date(fdate);
							$ins_data['from_id'] = $log_id;
							// print_r($ins_data);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								foreach ($recps as $re => $val) {
									$in_data['from_id'] = $log_id;
									$in_data['to_id'] = $val;
									$in_data['content'] = $content;
									$in_data['item'] = 'announcement';
									$in_data['new'] = 0;
									$in_data['reg_date'] = date(fdate);
									$in_data['item_id'] = $ins_rec;
									$this->Crud->create('notify', $in_data);
								}

								
								echo $this->Crud->msg('success', 'Announcement Sent Successfully');
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'announcement', 'title');
								$action = $by . ' created (' . $code . ') Announcement ';
								$this->Crud->activity('announcement', $ins_rec, $action, $log_id);
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', 'Please try later');
							}
						}
					}
					exit;
				}
			}
		}

		//Get Recipient
		if ($param1 == 'recipient') {
			$sel = '';
			$selected = $this->request->getPost('selected');
			if (!empty($selected)) {
				$mem = explode(",", $selected);
				$i = 0;
				foreach ($mem as $me => $val) {
					$sele = $this->Crud->read_single_order('role_id', $val, 'user', 'fullname', 'asc');
					$rol = $this->Crud->read_field('id', $val, 'access_role', 'name');
					$sel .= '<div class="col-sm-4 m-b-5"><b>' . strtoupper($rol) . '</b><br><div class="row">';
					if (!empty($sele)) {
						foreach ($sele as $selec) {
							$id = $this->Crud->read_field('id', $selec->id, 'user', 'fullname');
							$sel .= '<div class="col-sm-12 m-b-5 checkbox">
                                        <input name="recipients[]"  id="recipients' . $selec->id . '"  class="recipients" value="' . $selec->id . '"  type="checkbox" checked>
                                        <label for="recipients' . $selec->id . '">' . ucwords($id) . '</label>
                                    </div>';
						}
					}
					$sel .= '</div></div>';
					$i++;
				}
			}
			$res['item'] = $sel;
			echo json_encode($res);
			die;
		}

		//Get Church From the ministry
		if($param1 == 'get_church'){
			$ministry_id = $this->request->getPost('ministry_id');
			$level = $this->request->getPost('level');
			if ($ministry_id) {
				$church = $this->Crud->read_single_order('ministry_id', $ministry_id, 'church', 'type', 'asc');
				if(!empty($level) && $level !=  'all'){
					$church = $this->Crud->read2_order('type', $level, 'ministry_id', $ministry_id, 'church', 'name', 'asc');
				}

				$churches = [];
				$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
				$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
				$church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
				$roles = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
						
				if (!empty($church)) {
					foreach ($church as $c) {
						if($church_type == 'region' && $c->type == 'region')continue;
						
						if($church_type == 'zone'){
							if($c->type == 'region'  || $c->type == 'zone')continue;

						}
						if($church_type == 'group'){
							if($c->type == 'region' ||  $c->type == 'zone' || $c->type == 'group')continue;

						}

						if($church_type == 'church'){
							if($c->type == 'region' || $c->type == 'zone' || $c->type == 'group' || $c->type == 'church')continue;
						}
						
						$churches[] = [
							'id' => $c->id,
							'name' => $c->name,
							'type' => $c->type
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

		// record listing
		if ($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 150;
			$item = '';

			if ($limit == '') {
				$limit = $rec_limit;
			}
			if ($offset == '') {
				$offset = 0;
			}

			$search = $this->request->getPost('search');
			$todo = $param1;

			if (!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$query = $this->Crud->filter_announcement($limit, $offset, $log_id, $search);
				$all_rec = $this->Crud->filter_announcement('', '', $log_id, $search);
				if (!empty($all_rec)) {
					$count = count($all_rec);
				} else {
					$count = 0;
				}
				$data['count'] = $count;

				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$title = $q->title;
						$content = $q->content;
						$to_id = $q->to_id;
						$team = $q->role_id;
						$dept_id = $q->dept_id;
						$type = $q->type;
						$user_i = $q->from_id;

						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
						$user = $this->Crud->read_field('id', $user_i, 'user', 'firstname');

						$teams = '';
						if ($role == 'developer' || $role == 'administrator' || $user_i == $log_id) {
							if (!empty($team)) {
								$t_count = 0;
								foreach (json_decode($team) as $te => $value) {


									$names = $this->Crud->read_field('id', $value, 'access_role', 'name');
									$teams .= '<span class="badge badge-dim rounded-pill bg-primary mb-1">' . strtoupper($names) . '</span>';
									$t_count++;
									if ($t_count > 4) {
										$remaining_count = count(json_decode($team)) - $t_count;
										if ($remaining_count > 0) {
											$teams .= '<span class="badge badge-dim rounded-pill bg-secondary mb-1">+' . $remaining_count . ' more</span>';
										}
										break; // Break the loop if $t_count exceeds 4
									}
								}


							}
						}
						if ($type == 'department') {
							$teams = $this->Crud->read_field('id', $dept_id, 'dept', 'name') . ' Department';
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
                                    <a href="javascript:;" class="text-primary pop" pageTitle="Manage ' . $title . '" pageName="' . site_url('ministry/announcement/manage/edit/' . $id) . '" pageSize="modal-lg">
										<i class="ni ni-edit-alt"></i> Edit
                                    </a> ||  
                                    <a href="javascript:;" class="text-success pop" pageTitle="View ' . $title . '" pageName="' . site_url('ministry/announcement/manage/view/' . $id) . '" pageSize="modal-xl">
										<i class="ni ni-eye"></i> View
                                    </a>
                            ';
						}

						if ($role == 'developer' || $role == 'administrator') {
							$item .= '
								<tr>
									<td><span class="text-muted small">' . $reg_date . '</span></td>
									<td><span class="tb-lead">' . ucwords($title) . '</span> </td>
									<td>' . ucwords($user) . '</td>
									<td>' . ucwords($type) . ' Announcement</td>
									<td>' . $teams . '</td>
									<td>' . $all_btn . '</td>
								</tr>
								
							';
						} else {
							if ($user_i == $log_id) {
								$item .= '
									<tr>
										<td><span class="text-muted small">' . $reg_date . '</span></td>
										<td><span class="tb-lead">' . ucwords($title) . '</span> </td>
										<td>' . ucwords($user) . '</td>
										<td>' . ucwords($type) . ' Announcement</td>
										<td>' . $teams . '</td>
										<td>' . $all_btn . '</td>
									</tr>
									
                                ';
							} else {
								if (!empty($team)) {
									if (in_array($log_id, json_decode($to_id), true)) {
										$item .= '
											<tr>
												<td><span class="text-muted small">' . $reg_date . '</span></td>
												<td><span class="tb-lead">' . ucwords($title) . '</span> </td>
												<td>' . ucwords($user) . '</td>
												<td>' . ucwords($type) . ' Announcement</td>
												<td>' . $teams . '</td>
												<td>' . $all_btn . '</td>
											</tr>
												
                                        ';
									}
								}
							}
						}
					}
				}
			}
			if (empty($item)) {
				$resp['item'] = '
					<tr><td colspan="8">
					<div class="text-center text-muted col-sm-12">
						<br/><br/>
						<i class="icon ni ni-vol" style="font-size:120px;"></i><br/>No Annoucement Returned
					</div></td></tr>
				';
			} else {
				$resp['item'] = $item;
			}

			$more_record = $count - ($offset + $rec_limit);
			$resp['left'] = $more_record;

			$resp['count'] = $count;
			if ($count > ($offset + $rec_limit)) { // for load more records
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

			$data['title'] = 'Announcement  | ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
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


}