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
				<tr>
					<td><span class="sub-text">'.translate_phrase('Name').'</span></td>
					<td><span class="sub-text">'.translate_phrase('Contact').'</span></td>
					<td><span class="sub-text">'.translate_phrase('Address').'</span></td>
					<td><span class="sub-text">'.translate_phrase('Date').'</span></td>
					<td><span class="sub-text">Actions</span>
					</td>
				</tr><!-- .nk-tb-item -->
		
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_ministry('', '', '', $log_id, $search, $switch_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_ministry($limit, $offset, '', $log_id, $search, $switch_id);
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
							$img = '<img height="40px" width="60px"  src="' . site_url($logo) . '">';
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
							<tr>
								<td>
									<div class="user-card">
								      	<div class="user-avatar">            
									  		'.$img.'      
										</div>        
										<div class="user-name">            
											<span class="tb-lead">' . ucwords($name) . '</span>        
										</div>    
									</div>  
								</td>
								<td>
									<span class="text-dark">'.$email.'</span><br>
									<span class="text-dark">'.$phone.'</span>
								</td>
								<td>
									<span class="text-dark">'.$address.'</span>
								</td>
								<td>
									<span class="text-dark">'.$reg_date.'</span>
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
						<br/><br/><br/>
						<i class="ni ni-server" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Ministry Returned').'
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
						$del_id = $this->request->getPost('d_id');
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
									if ($u->church_id > 0) {
										// Check if church_id is not already in the array
										if (!in_array($u->church_id, $recps_church)) {
											$recps_church[] = $u->church_id;
										}
									}
								}
							}

						} else{
							if($dept_id){
								$dept = $this->Crud->read2('dept_id', $dept_id, 'ministry_id', $ministry_id, 'user');
								if($dept){
									foreach($dept as $d){
										$recps[] = $d->id;
										if ($d->church_id > 0) {
											// Check if church_id is not already in the array
											if (!in_array($d->church_id, $recps_church)) {
												$recps_church[] = $d->church_id;
											}
										}
									}
								}
							}

						}
						
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
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
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
				$church = $this->Crud->read_single_order('ministry_id', $ministry_id, 'church', 'name', 'asc');
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
			$switch_id = $this->session->get('switch_church_id');

			$search = $this->request->getPost('search');
			$todo = $param1;

			if (!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$query = $this->Crud->filter_announcement($limit, $offset, $log_id, $search, $switch_id);
				$all_rec = $this->Crud->filter_announcement('', '', $log_id, $search, $switch_id);
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
						$send_type = $q->send_type;
						$level = $q->level;
						$ministry_id = $q->ministry_id;
						$church_id = json_decode($q->church_id);

						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
						$user = $this->Crud->read_field('id', $user_i, 'user', 'firstname').' '.$this->Crud->read_field('id', $user_i, 'user', 'surname');

						$depts = '';
						if ($type == 'department') {
							$depts = '<span class="small">'.$this->Crud->read_field('id', $dept_id, 'dept', 'name') . ' Department</span>';
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							// echo $switch_id;
							if(!empty($switch_id)){
								$all_btn = '
									 
									<a href="javascript:;" class="text-success pop" pageTitle="View ' . $title . '" pageName="' . site_url('ministry/announcement/manage/view/' . $id) . '" pageSize="modal-xl">
										<i class="ni ni-eye"></i> View
									</a>
								';
							} else{
								$all_btn = '
									<a href="javascript:;" class="text-primary pop mx-2" pageTitle="Manage ' . $title . '" pageName="' . site_url('ministry/announcement/manage/edit/' . $id) . '" pageSize="modal-lg">
										<i class="ni ni-edit-alt"></i> Edit
									</a>  
									<a href="javascript:;" class="text-success pop mx-2" pageTitle="View ' . $title . '" pageName="' . site_url('ministry/announcement/manage/view/' . $id) . '" pageSize="modal-xl">
										<i class="ni ni-eye"></i> View
									</a>
								';
							}
							
						}

						$church = '';
						if ($role == 'developer' || $role == 'administrator' || $user_i == $log_id) {
							if (!empty($church_id)) {
								$t_count = 0;
								foreach (($church_id) as $te => $value) {


									$names = $this->Crud->read_field('id', $value, 'church', 'name');
									$church .= '<span class="badge badge-dim rounded-pill bg-primary mb-1">' . strtoupper($names) . '</span>';
									$t_count++;
									if ($t_count > 1) {
										$remaining_count = count(($church_id)) - $t_count;
										if ($remaining_count > 0) {
											$church .= '<span class="badge badge-dim rounded-pill bg-secondary mb-1">+' . $remaining_count . ' more</span>';
										}
										break; // Break the loop if $t_count exceeds 4
									}
								}


							}
						}
						

						if ($role == 'developer' || $role == 'administrator') {
							$item .= '
								<tr>
									<td><span class="text-muted small">' . $reg_date . '</span></td>
									<td><span class="tb-lead small">' . ucwords($title) . '</span> </td>
									<td><span class="tb-lead small">' . ucwords($user) . '</span></td>
									<td><span class="tb-lead small">' . ucwords($type) . ' Announcement<br>'.$depts.'</span></td>
									<td><span class="tb-lead small">' . ucwords($level) . ' Church(es)<br><span class="small text-info"><b>' . ucwords($send_type) . '</b></span></span></td>
									<td><span class="tb-lead small">' . $church . '</span></td>
									<td><span class="tb-lead small">' . $all_btn . '</span></td>
								</tr>
								
							';
						} else {
							if ($user_i == $log_id) {
								$item .= '
									<tr>
										<td><span class="text-muted small">' . $reg_date . '</span></td>
										<td><span class="tb-lead small">' . ucwords($title) . '</span> </td>
										<td><span class="tb-lead small">' . ucwords($user) . '</span></td>
										<td><span class="tb-lead small">' . ucwords($type) . ' Announcement<br>'.$depts.'</span></td>
										<td><span class="tb-lead small">' . ucwords($level) . ' Church(es)<br><span class="small text-info"><b>' . ucwords($send_type) . '</b></span></span></td>
										<td><span class="tb-lead small">' . $church . '</span></td>
										<td><span class="tb-lead small">' . $all_btn . '</span></td>
									</tr>
								
									
                                ';
							} else {
								if (!empty($team)) {
									if (in_array($log_id, json_decode($to_id), true)) {
										$item .= '
											<tr>
												<td><span class="text-muted small">' . $reg_date . '</span></td>
												<td><span class="tb-lead small">' . ucwords($title) . '</span> </td>
												<td><span class="tb-lead small">' . ucwords($user) . '</span></td>
												<td><span class="tb-lead small">' . ucwords($type) . ' Announcement<br>'.$depts.'</span></td>
												<td><span class="tb-lead small">' . ucwords($level) . ' Church(es)<br><span class="small text-info"><b>' . ucwords($send_type) . '</b></span></span></td>
												<td><span class="tb-lead small">' . $church . '</span></td>
												<td><span class="tb-lead small">' . $all_btn . '</span></td>
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

	
	public function prayer($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

		$mod = 'ministry/prayer';

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
		
		$table = 'prayer';
		
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
			} else {
				// prepare for edit
				if($param2 == 'edit') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_title'] = $e->title;
								$data['e_start_date'] = $e->start_date;
								$data['e_end_date'] = $e->end_date;
								$data['e_duration'] = $e->duration;
								$data['e_church_id'] = json_decode($e->churches,true);
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_type'] = $e->church_type;
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
								$data['e_title'] = $e->title;
								$data['e_start_date'] = $e->start_date;
								$data['e_end_date'] = $e->end_date;
								$data['e_duration'] = $e->duration;
								$data['e_church_id'] = json_decode($e->churches,true);
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_type'] = $e->church_type;
							}
						}
					}
				}
				
				if($this->request->getMethod() == 'post'){
					$e_id =  $this->request->getVar('e_id');
					$title =  $this->request->getVar('title');
					$start_date =  $this->request->getVar('start_date');
					$end_date =  $this->request->getVar('end_date');
					$duration =  $this->request->getVar('duration');
					$ministry_id =  $this->request->getVar('ministry_id');
					$level =  $this->request->getVar('level');
					$church_id =  $this->request->getVar('church_id');
					
					$log_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
					if(empty($church_id)){
						$church_id[] = $log_church_id;
					}
					
					
					$ins_data['title'] = $title;
					$ins_data['start_date'] = $start_date;
					$ins_data['end_date'] = $end_date;
					$ins_data['duration'] = $duration;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_type'] = $level;
					$ins_data['churches'] = json_encode($church_id);
					
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
						
						if($this->Crud->check2('title', $title, 'ministry_id', $ministry_id, $table) > 0) {
							echo $this->Crud->msg('warning', ('Event Already Exist'));
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Prayer Cloud Created'));
								
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'events', 'title');
								$action = $by.' created Prayer Cloud ('.$code.')';
								$this->Crud->activity('event', $ins_rec, $action);

								
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

			$rec_limit = 50;
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
				$all_rec = $this->Crud->filter_prayer('', '', $log_id, $status, $search, $switch_id);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$query = $this->Crud->filter_prayer($limit, $offset, $log_id, $status, $search, $switch_id);

				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$reg_date =  date('M d, Y h:i A', strtotime($q->reg_date));
						$title = $q->title;
						$church_id = json_decode($q->churches,true);
						$ministry_id = $q->ministry_id;
						$church_type = $q->church_type;
						$start_date = $q->start_date;
						$end_date = $q->end_date;
						$duration = $q->duration;
						$church = '';
						if(!empty($church_id)){
							foreach($church_id as $ch){
								$church .= '<small class="badge badge-dim bg-primary mx-1">'.$this->Crud->read_field('id', $ch, 'church', 'name').'</small>';
							}
							
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
									<li><a href="javascript:;" class="text-info" onclick="time('.$id.')" pageTitle="Time Table for ' . $title . '" ><em class="icon ni ni-users"></em><span>'.translate_phrase('Time Management').'</span></a></li>
									
								';

							}
							
						}

						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');

						$start = date('Y-m-d H:i', strtotime($q->start_date));
						$end = date('Y-m-d H:i', strtotime($q->end_date));
				
						$item .= '
							<tr>
								<td>
									<div class="user-card">
										       
										<div class="user-name">            
											<span class="tb-lead">' . ucwords($title) . '</span>
										               
										</div>    
									</div>  
								</td>
								<td>
									<span class="small text-dark">'.($church).'</span>
								</td>
								<td><span class="small text-dark">'.$start.' <b>&#8594;</b> '.$end.'</span></td>
								<td>
									<span class="small text-dark">'.number_format($duration).' Minute(s)</span>
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

						
					}
				}
			}
			
			if(empty($item)) {
				$resp['item'] = '
					<Tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/>
						<i class="ni ni-hot" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Prayer Cloud Returned').'
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

		if($param1 == 'time_load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';

			if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			$search = $this->request->getPost('search');
			$id = $this->request->getPost('id');

			//echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$query = json_decode($this->Crud->read_field('id', $id, 'prayer', 'assignment'), true);
				$start_date = $this->Crud->read_field('id', $id, 'prayer', 'start_date');
				$end_date = $this->Crud->read_field('id', $id, 'prayer', 'end_date');

				$current_date = strtotime($start_date);
				$end_date_timestamp = strtotime($end_date);

				if (!empty($query)) {
					$counts = count($query);
				} else {
					$counts = 0;
				}

				// echo $current_date;
				while ($current_date <= $end_date_timestamp) {
					$formatted_date = date('Y-m-d', $current_date);

					// Define time slots for the day (Example: 6 AM to 6 PM with 1-hour intervals)
					$time_slots = [];
					$time_start = strtotime('06:00:00');
					$time_end = strtotime('18:00:00');
					while ($time_start <= $time_end) {
						$time_slots[] = date('h:i A', $time_start);
						$time_start = strtotime('+1 hour', $time_start);
					}

					$time_rows = '';
					if (!empty($query)) {
						foreach ($query as $slot) {
							$time_slot = $slot['time_slot']; // Assuming 'time_slot' is the key in the query array
							$time_rows .= '
								<tr>
									<td>' . $time_slot . '</td>
									<td>
										<div class="btn-group">
											<a href="javascript:;" class="btn btn-sm btn-outline-primary pop" pageTitle="Add Info for ' . $formatted_date . ' ' . $time_slot . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/add/' . $id . '/' . $formatted_date . '/' . $time_slot) . '"><em class="icon ni ni-plus"></em> Add</a>
											<a href="javascript:;" class="btn btn-sm btn-outline-warning pop" pageTitle="Edit Info for ' . $formatted_date . ' ' . $time_slot . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/edit/' . $id . '/' . $formatted_date . '/' . $time_slot) . '"><em class="icon ni ni-edit-alt"></em> Edit</a>
											<a href="javascript:;" class="btn btn-sm btn-outline-danger pop" pageTitle="Delete Info for ' . $formatted_date . ' ' . $time_slot . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/delete/' . $id . '/' . $formatted_date . '/' . $time_slot) . '"><em class="icon ni ni-trash-alt"></em> Delete</a>
										</div>
									</td>
								</tr>
							';
						}
					} else {
						$time_rows = '
							<tr>
								<td colspan="2">
									<div class="text-center">
										<p>No time slots available for this date.</p>
										<a href="javascript:;" class="btn btn-sm btn-outline-primary pop" pageTitle="Add New Time Slot for ' . $formatted_date . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/add/' . $id . '/' . $formatted_date) . '"><em class="icon ni ni-plus"></em> Add New Time Slot</a>
									</div>
								</td>
							</tr>
						';
					}

					// Add a row for the date with all time slots
					$item .= '
						<tr>
							<td class="date-column">
								<div class="date-wrapper">
									<strong class="date-text">' . date('l, F j, Y', strtotime($formatted_date)) . '</strong>
								</div>
							</td>
							<td class="">
								<table class="table table-stripped ">
									
									<tbody>
										' . $time_rows . '
									</tbody>
								</table>
							</td>
						</tr>
					';

					// Move to the next day
					$current_date = strtotime('+1 day', $current_date);
				}

			}
			
			if(empty($item)) {
				$resp['item'] = '
					<Tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/>
						<i class="ni ni-hot" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Prayer Cloud Returned').'
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
		$cal_ass = $this->Crud->read('prayer');
		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		
		$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
		if(!empty($switch_id)){
			$church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
			$church_id = $switch_id;
			$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
		}
		if($role != 'developer' && $role != 'administrator'){
			$cal_ass = $this->Crud->read_single('ministry_id', $ministry_id, 'prayer');
		}
		if(!empty($cal_ass)){
			foreach($cal_ass as $key => $value){
				if($value->church_type != 'all' && $role != 'ministry administrator' && $role != 'developer' && $role != 'adminstrator'){
					if(!in_array($church_id, json_decode($value->churches))){
						continue;
					}
				}
				$start = date('Y-m-d H:i', strtotime($value->start_date));
				$end = date('Y-m-d H:i', strtotime($value->end_date));
				
				$class = 'fc-event-warning';
				if($value->church_type == 'all') $class = 'fc-event-primary';
				if($value->church_type == 'region') $class = 'fc-event-info';
				if($value->church_type == 'zone') $class = 'fc-event-indigo';
				if($value->church_type == 'group') $class = 'fc-event-danger';
				if($value->church_type == 'church') $class = 'fc-event-success';
				$cal_events[$key]['id'] = $value->id;
				$cal_events[$key]['title'] = strtoupper($this->Crud->convertText($value->title));
				$cal_events[$key]['start'] = $start;
				$cal_events[$key]['end'] = $end;
				$cal_events[$key]['description'] = ucwords($this->Crud->convertText($value->title));
				$cal_events[$key]['className'] = $class;
			}
			
		}


		$data['cal_events'] = array_values($cal_events);
		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = 'Prayer Cloud - '.app_name;
			$data['page_active'] = $mod;

			return view($mod, $data);
		}
	}

	public function knowledge($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

		$log_id = $this->session->get('td_id');

		$mod = 'ministry/knowledge';

		$data['current_language'] = $this->session->get('current_language');

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
		if ($role_r == 0) {
			return redirect()->to(site_url('dashboard'));
		}
		$data['log_id'] = $log_id;
		$data['role'] = $role;
		$data['role_c'] = $role_c;

		$table = 'knowledge';
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
						$del_id = $this->request->getPost('d_id');
						
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'knowledge', 'title');
						if ($this->Crud->deletes('id', $del_id, $table) > 0) {
							///// store activities
							$action = $by . ' deleted (' . $code . ') Knowledge Base ';
							$this->Crud->activity('knowledge', $del_id, $action, $log_id);
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
								$data['e_title'] = $e->title;
								
								$data['e_file'] = $e->file;
							}
						}
					}
				}


				if ($_POST) {
					$e_id = $this->request->getPost('e_id');
					$title = $this->request->getPost('title');
					$img_id =  $this->request->getVar('img');
					
					
					//// Image upload
					if (file_exists($this->request->getFile('pics'))) {
						if (!empty($img_id)) {
							unlink(FCPATH . $img_id);
						}
						$path = 'assets/uploads/knowledge/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->file_upload($path, $file);

						if (!empty($getImg->path)) $img_id = $getImg->path;
					}


					if(empty($img_id)){
						echo $this->Crud->msg('warning', 'Select a File');
						die;
					}

					$ins_data['title'] = $title;
					$ins_data['file'] = $img_id;

					// print_r($recps);
					// do create or update
					if ($e_id) {
						$upd_rec = $this->Crud->updates('id', $e_id, $table, $ins_data);
						if ($upd_rec > 0) {
							echo $this->Crud->msg('success', 'Knowledge Base Updated');
							
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $e_id, 'knowledge', 'title');
							$action = $by . ' updated (' . $code . ') Knowledge Base ';
							$this->Crud->activity('knowledge', $e_id, $action, $log_id);
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						if ($this->Crud->check('title', $title, $table) > 0) {
							echo $this->Crud->msg('warning', 'Knowledge Base Already Exist');
						} else {
							$ins_data['reg_date'] = date(fdate);
							// print_r($ins_data);
							echo $this->Crud->msg('success', 'Knowledge Base Created');
							
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'knowledge', 'title');
								$action = $by . ' created (' . $code . ') Knowledge Base ';
								$this->Crud->activity('knowledge', $ins_rec, $action, $log_id);
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
				$query = $this->Crud->filter_knowledge($limit, $offset, $log_id, $search);
				$all_rec = $this->Crud->filter_knowledge('', '', $log_id, $search);
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
						$file = $q->file;
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
						
						// Use pathinfo to get details about the file
						$fileInfo = pathinfo($file);

						// Get the full name of the file (with extension)
						$fileNameWithExtension = $fileInfo['basename']; // e.g., 1726092389_cd83293f4b1b45080bc1.sql

						// Get the name of the file without the extension
						$fileName = $fileInfo['filename']; // e.g., 1726092389_cd83293f4b1b45080bc1

						// Get the file extension
						$fileExtension = $fileInfo['extension']; // e.g., sql

						$extensions = '<em class="icon ni ni-file-docs"></em>';
						if ($fileExtension == 'pdf') {
							$extensions = '<em class="icon ni ni-file-pdf"></em>';
						}
						if ($fileExtension == 'txt') {
							$extensions = '<em class="icon ni ni-file-text"></em>';
						}

						if($fileExtension == 'doc'  || $fileExtension == 'docx'){
							$extensions = '<em class="icon ni ni-file-doc"></em>';
						}

						if($fileExtension == 'csv' ||  $fileExtension == 'xls' || $fileExtension == 'xlsx'){
							$extensions = '<em  class="icon ni ni-file-xls"></em>';
						}

						if($fileExtension == 'zip' ||  $fileExtension == 'rar'){
							$extensions = '<em  class="icon ni ni-file-zip"></em>';
						}

						if ($fileExtension == 'jpg' || $fileExtension == 'png' ||  $fileExtension == 'jpeg' || $fileExtension == 'gif') {

							$extensions = '<em class="icon ni ni-file-img"></em>';
						}

						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<a href="javascript:;" class="text-primary pop mx-2" pageTitle="Manage ' . $title . '" pageName="' . site_url('ministry/knowledge/manage/edit/' . $id) . '" pageSize="modal-sm">
									<i class="ni ni-edit-alt"></i> Edit
								</a>  
								<a href="javascript:;" class="text-danger pop mx-2" pageTitle="View ' . $title . '" pageName="' . site_url('ministry/knowledge/manage/delete/' . $id) . '" pageSize="modal-sm">
									<i class="ni ni-trash"></i> Delete
								</a>
                            ';
						}


						$item .= '
							<tr>
								<td><span class="text-muted small">' . $reg_date . '</span></td>
								<td><span class="tb-lead small">' . ucwords($title) . '</span> </td>
								<td><span class="tb-lead small">' . ucwords($fileName) . '</span></td>
								<td><span class="tb-lead small">' . ($extensions) . ' .'.$fileExtension.'</span></td>
								<td><span class="tb-lead small">' . $all_btn . '</span></td>
							</tr>
							
						';
					
					}
				}
			}
			if (empty($item)) {
				$resp['item'] = '
					<tr><td colspan="8">
					<div class="text-center text-muted col-sm-12">
						<br/><br/>
						<i class="icon ni ni-archived" style="font-size:120px;"></i><br/>No Knowledge Base Returned
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

			$data['title'] = 'Knowledge Base  | ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}
	public function baptism($param1 = '', $param2 = '', $param3 = '')
	{
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

		$log_id = $this->session->get('td_id');

		$mod = 'ministry/baptism';

		$data['current_language'] = $this->session->get('current_language');

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
		if ($role_r == 0) {
			return redirect()->to(site_url('dashboard'));
		}
		$data['log_id'] = $log_id;
		$data['role'] = $role;
		$data['role_c'] = $role_c;

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
				$query = $this->Crud->filter_baptism($limit, $offset, $log_id, $search, $switch_id);
				$all_rec = $this->Crud->filter_baptism('', '', $log_id, $search, $switch_id);
				if (!empty($all_rec)) {
					$count = count($all_rec);
				} else {
					$count = 0;
				}
				$data['count'] = $count;

				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$church = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
						$surname = $q->surname;
						$firstname = $q->firstname;
						$othername = $q->othername;
						$gender = strtolower($q->gender);
						$phone = $q->phone;
						$user_no = $q->user_no;
						$baptism = strtolower($q->baptism);
						
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
						
						$bap = '<span class="text-danger">Not Baptised</span>';
						if($baptism == 'yes'){
							$bap = '<span class="text-success">Baptised</span>';
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								
                            ';
						}


						$item .= '
							<tr>
								<td><span class="text-muted small">' . ucwords($church) . '</span></td>
								<td><span class="tb-lead small">' . ucwords($surname.' '.$firstname.' '.$othername) . '</span> </td>
								<td><span class="tb-lead small">' . ucwords($user_no) . '</span></td>
								<td><span class="tb-lead small">' . ucwords($gender) .'</span></td>
								<td><span class="tb-lead small">' . $phone . '</span></td>
								<td><span class="tb-lead small">' . $bap . '</span></td>
							</tr>
							
						';
					
					}
				}
			}
			if (empty($item)) {
				$resp['item'] = '
					<tr><td colspan="8">
					<div class="text-center text-muted col-sm-12">
						<br/><br/>
						<i class="icon ni ni-archived" style="font-size:120px;"></i><br/>No Knowledge Base Returned
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

			$data['title'] = 'Baptism | ' . app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
	}

	  //////// Schedule  ////////
	public function calendar($param1='', $param2='', $param3='') {
	// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

		$mod = 'ministry/calendar';

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
		
		$table = 'events';
		
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
			} else {
				// prepare for edit
				if($param2 == 'edit') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_title'] = $e->title;
								$data['e_description'] = $e->description;
								$data['e_start_date'] = $e->start_date;
								$data['e_start_time'] = $e->start_time;
								$data['e_end_date'] = $e->end_date;
								$data['e_end_time'] = $e->end_time;
								$data['e_location'] = $e->location;
								$data['e_venue'] = $e->venue;
								$data['e_event_type'] = $e->event_type;
								$data['e_recurrence_pattern'] = $e->recurrence_pattern;
								$data['e_pattern'] = $e->pattern;
								$data['e_image'] = $e->image;
								$data['e_event_for'] = $e->event_for;
								$data['e_church_id'] = $e->church_id;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_type'] = $e->church_type;
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
								$data['e_title'] = $e->title;
								$data['e_description'] = $e->description;
								$data['e_start_date'] = $e->start_date;
								$data['e_start_time'] = $e->start_time;
								$data['e_end_date'] = $e->end_date;
								$data['e_end_time'] = $e->end_time;
								$data['e_location'] = $e->location;
								$data['e_venue'] = $e->venue;
								$data['e_event_type'] = $e->event_type;
								$data['e_recurrence_pattern'] = $e->recurrence_pattern;
								$data['e_pattern'] = $e->pattern;
								$data['e_image'] = $e->image;
								$data['e_event_for'] = $e->event_for;
								$data['e_church_id'] = $e->church_id;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_created_at'] = $e->created_at;
								$data['e_updated_at'] = $e->updated_at;
								$data['e_church_type'] = $e->church_type;
							}
						}
					}
				}
				
				if($this->request->getMethod() == 'post'){
					$e_id =  $this->request->getVar('e_id');
					$title =  $this->request->getVar('title');
					$content =  $this->request->getVar('content');
					$start_date =  $this->request->getVar('start_date');
					$start_time =  $this->request->getVar('start_time');
					$end_date =  $this->request->getVar('end_date');
					$end_time =  $this->request->getVar('end_time');
					$event_type =  $this->request->getVar('event_type');
					$recurring_pattern =  $this->request->getVar('recurring_pattern');
					$week_day =  $this->request->getVar('week_day');
					$month_day =  $this->request->getVar('month_day');
					$year =  $this->request->getVar('year');
					$location =  $this->request->getVar('location');
					$venue =  $this->request->getVar('venue');
					$ministry_id =  $this->request->getVar('ministry_id');
					$level =  $this->request->getVar('level');
					$send_type =  $this->request->getVar('send_type');
					$church_id =  $this->request->getVar('church_id');
					$img_id =  $this->request->getVar('img');
					if(empty($church_id)){
						$church_id = array();
					}
					
					//// Image upload
					if (file_exists($this->request->getFile('pics'))) {
						if (!empty($img_id)) {
							unlink(FCPATH . $img_id);
						}
						$path = 'assets/images/events/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);

						if (!empty($getImg->path)) $img_id = $getImg->path;
					}

					if($event_type == 'one-time'){
						$recurring_pattern = '';
					}
					$pattern = '';
					if($event_type == 'recurring'){
						if($recurring_pattern == 'weekly'){
							$pattern = $week_day;
						}
						if($recurring_pattern == 'monthly'){
							$pattern = $month_day;
						}
						if($recurring_pattern == 'yearly'){
							$pattern = $year;
						}
						
					
					}
					$ins_data['title'] = $title;
					$ins_data['description'] = $content;
					$ins_data['start_date'] = $start_date;
					$ins_data['start_time'] = date('H:i', strtotime($start_time));
					$ins_data['end_date'] = $end_date;
					$ins_data['end_time'] = date('H:i', strtotime($end_time));
					$ins_data['event_type'] = $event_type;
					$ins_data['recurrence_pattern'] = $recurring_pattern;
					$ins_data['pattern'] = $pattern;
					$ins_data['location'] = $location;
					$ins_data['venue'] = $venue;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['event_for'] = $send_type;
					$ins_data['church_type'] = $level;
					$ins_data['church_id'] = json_encode($church_id);
					if (!empty($img_id) || !empty($getImg->path))  $ins_data['image'] = $img_id;
					
					$ins_data['updated_at'] = date(fdate);
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
						
						$ins_data['created_at'] = date(fdate);
						
						if($this->Crud->check2('title', $title, 'ministry_id', $ministry_id, $table) > 0) {
							echo $this->Crud->msg('warning', ('Event Already Exist'));
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Event Created'));
								
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'events', 'title');
								$action = $by.' created Event ('.$code.')';
								$this->Crud->activity('event', $ins_rec, $action);

								
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
		$cal_ass = $this->Crud->read('events');
		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		
		$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
		if(!empty($switch_id)){
            $church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
            $church_id = $switch_id;
			$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
        }
		if($role != 'developer' && $role != 'administrator'){
			$cal_ass = $this->Crud->read_single('ministry_id', $ministry_id, 'events');
		}
		
		if (!empty($cal_ass)) {
			foreach ($cal_ass as $key => $value) {
				if ($value->church_type != 'all' && $role != 'ministry administrator' && $role != 'developer' && $role != 'administrator') {
					if (!in_array($church_id, json_decode($value->church_id, true))) {
						continue;
					}
				}
		
				$start = date('Y-m-d H:i', strtotime($value->start_date . ' ' . $value->start_time));
				$end = date('Y-m-d H:i', strtotime($value->end_date . ' ' . $value->end_time));
		
				// Assign event class using switch
				$class = 'fc-event-warning';
				switch ($value->church_type) {
					case 'all':
						$class = 'fc-event-primary';
						break;
					case 'region':
						$class = 'fc-event-info';
						break;
					case 'zone':
						$class = 'fc-event-indigo';
						break;
					case 'group':
						$class = 'fc-event-danger';
						break;
					case 'church':
						$class = 'fc-event-success';
						break;
				}
		
				$cal_events[$key] = [
					'id' => $value->id,
					'title' => strtoupper($this->Crud->convertText($value->title)),
					'start' => $start,
					'end' => $end,
					'description' => ucwords($this->Crud->convertText($value->description)),
					'className' => $class,
				];
			}
		}
		
		// Output the JSON
		$cal_event = !empty($cal_events) ? json_encode(array_values($cal_events)) : '[]';
		header('Content-Type: application/json');
		echo $cal_event;
		

		$data['cal_eventz'] = $cal_event;
		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = 'Events - '.app_name;
			$data['page_active'] = $mod;

			return view($mod, $data);
		}
	}
	
	
	public function invitation($param1='', $param2='', $param3='', $param4='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

		$mod = 'ministry/invitation';

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
		
		$table = 'form';
		
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
							$action = $by.' deleted Form ('.$code.')';
							$this->Crud->activity('form', $del_id, $action);

							echo $this->Crud->msg('success', 'Form Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						die;	
					}
				}
			} elseif($param2 == 'extension'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
							$data['e_title'] = $e->name;
							$data['e_description'] = $e->description;
							$data['e_fields'] = json_decode($e->fields);
							$data['e_event_id'] = $e->event_id;
							$data['e_send_type'] = $e->send_type;
							$data['e_church_id'] = $e->church_id;
							$data['e_ministry_id'] = $e->ministry_id;
							$data['e_church_type'] = $e->church_type;
							$data['e_reg_date'] = $e->reg_date;
						}
					}
				}
			}else {
				// prepare for edit
				if($param2 == 'edit') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_title'] = $e->name;
								$data['e_description'] = $e->description;
								$data['e_fields'] = json_decode($e->fields);
								$data['e_event_id'] = $e->event_id;
								$data['e_send_type'] = $e->send_type;
								$data['e_church_id'] = $e->church_id;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_type'] = $e->church_type;
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
								$data['e_title'] = $e->name;
								$data['e_description'] = $e->description;
								$data['e_fields'] = json_decode($e->fields);
								$data['e_event_id'] = $e->event_id;
								$data['e_send_type'] = $e->send_type;
								$data['e_church_id'] = $e->church_id;
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_church_type'] = $e->church_type;
								$data['e_reg_date'] = $e->reg_date;
							}
						}
					}
				}
				
				if($this->request->getMethod() == 'post'){
					$e_id =  $this->request->getVar('e_id');
					$title =  $this->request->getVar('title');
					$description =  $this->request->getVar('description');
					$label =  $this->request->getVar('label');
					$type =  $this->request->getVar('type');
					$options =  $this->request->getVar('options');
					$ministry_id =  $this->request->getVar('ministry_id');
					$event_id =  $this->request->getVar('event_id');
					$level =  $this->request->getVar('level');
					$send_type =  $this->request->getVar('send_type');
					$church_id =  $this->request->getVar('church_id');
					if(empty($church_id)){
						$church_id = array();
					}
					
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
						echo $this->Crud->msg('warning', 'Enter the Field of the Form yo want to Create');
						die;
					}


					
					if(empty($send_type))$send_type = 'individual';
					if(empty($church_type))$church_type = 'specific';
					if(empty($level) && $church_type == 'specific'){
						$level = '';
					}
					$ins_data['name'] = $title;
					$ins_data['description'] = $description;
					$ins_data['fields'] = json_encode($fields);
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['send_type'] = $send_type;
					$ins_data['event_id'] = $event_id;
					$ins_data['church_type'] = $level;
					$ins_data['church_id'] = json_encode($church_id);
					
					// do create or update
					if($e_id) {
						$upd_rec = $this->Crud->updates('id', $e_id, $table, $ins_data);
						if($upd_rec > 0) {
							
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $e_id, 'form', 'name');
							$action = $by.' updated Form ('.$code.')';
							$this->Crud->activity('form', $e_id, $action);

							echo $this->Crud->msg('success', 'Form Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
						
					} else{
						
						$ins_data['reg_date'] = date(fdate);
						
						if($this->Crud->check2('name', $title, 'ministry_id', $ministry_id, $table) > 0) {
							echo $this->Crud->msg('warning', ('Form Already Exist'));
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Form Created'));
								
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'form', 'name');
								$action = $by.' created Form ('.$code.')';
								$this->Crud->activity('form', $ins_rec, $action);

								
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

		
		// manage record
		if($param1 == 'share') {
			$table = 'form_link';
			// prepare for delete
			if($param3 == 'share_link') {
				if($param4) {
					$edit = $this->Crud->read_single('id', $param4, $table);
					//echo var_dump($edit);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
							$data['e_unique_link'] = $e->unique_link;
						}
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
					$table = 'form_response';
					if($param4) {
						$edit = $this->Crud->read_single('id', $param4, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_email'] = $e->email;
								$data['e_phone'] = $e->phone;
								$data['e_response'] = json_decode($e->response,true);
								$data['e_reg_date'] = $e->reg_date;
							}
						}
					}
				}
				
				if($this->request->getMethod() == 'post'){
					$e_id =  $this->request->getVar('e_id');
					$form_id =  $this->request->getVar('form_id');
					$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
					$ministry_id = $this->Crud->read_field('id', $form_id, 'form', 'ministry_id');
					$type = 0;
					$uniqueLink = uniqid();

					$ins_data['form_id'] = $form_id;
					$ins_data['user_id'] = $log_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['type'] = $type;
					$ins_data['unique_link'] = $uniqueLink;
					$ins_data['reg_date'] = date(fdate);
						
					$churchs = json_decode($this->Crud->read_field('id', $form_id, 'form', 'church_id'));
					if(!empty($churchs)){
						if(!in_array($church_id, $churchs)){
							echo $this->Crud->msg('danger', translate_phrase('You are not authorized to generate a link for this form as you are not a member of the church associated with it.'));
							die;
						}
					}

					if($this->Crud->check3('user_id', $log_id, 'type', $type, 'form_id', $form_id, 'form_link') > 0) {
						echo $this->Crud->msg('warning', translate_phrase('Your shareable Link has been generated already'));
					} else {
						$ins_rec = $this->Crud->create('form_link', $ins_data);
						if($ins_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('Form Link Generated'));
							
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $ins_rec, 'form', 'name');
							$action = $by.' genearted a Form Link for Form ('.$code.')';
							$this->Crud->activity('form_link', $ins_rec, $action);

							echo '<script>
								load_share("","",'.$form_id.');
								$("#modal").modal("hide");
							</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));	
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
				$all_rec = $this->Crud->filter_forms('', '', $log_id, $status, $search,$switch_id);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$query = $this->Crud->filter_forms($limit, $offset, $log_id, $status, $search,$switch_id);

				$log_church_id = $this->Crud->read_field('id',  $log_id, 'user', 'church_id');
				if(!empty($switch_id)){
					$log_church_id = $switch_id;
				}
				$log_region_id = $this->Crud->read_field('id',  $log_church_id, 'church', 'regional_id');
				$log_zone_id = $this->Crud->read_field('id',  $log_church_id, 'church', 'zonal_id');
				$log_group_id = $this->Crud->read_field('id',  $log_church_id, 'church', 'group_id');

				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$reg_date =  date('M d, Y h:i A', strtotime($q->reg_date));
						$title = $q->name;
						$church_id = $q->church_id;
						$ministry_id = $q->ministry_id;
						$church_type = $q->church_type;
						$send_type = $q->send_type;
						$event_id = $q->event_id;

						$feed_btn = '';
						if($event_id > 0){
							if($role == 'developer' || $role =='administrator'){
								$feed_btn = '<li><a href="javascript:;" class="text-dark" onclick="extension('.$id.');"><em class="icon ni ni-list-index-fill"></em><span>'.translate_phrase('Form Extension').'</span></a></li>';
							}
							if($church_type != 'all'){

								if($church_type == 'region' && $send_type == 'general'){
									if(in_array($log_region_id, $church_id)){
										$feed_btn = '<li><a href="javascript:;" class="text-dark" onclick="extension('.$id.');"><em class="icon ni ni-list-index-fill"></em><span>'.translate_phrase('Form Extension').'</span></a></li>';
									}
								} 

								if($church_type == 'zone' && $send_type == 'general'){
									if(in_array($log_zone_id, $church_id)){
										$feed_btn = '<li><a href="javascript:;" class="text-dark" onclick="extension('.$id.');"><em class="icon ni ni-list-index-fill"></em><span>'.translate_phrase('Form Extension').'</span></a></li>';
									}
								} 

								if($church_type == 'group' && $send_type == 'general'){
									if(in_array($log_group_id, $church_id)){
										$feed_btn = '<li><a href="javascript:;" class="text-dark" onclick="extension('.$id.');"><em class="icon ni ni-list-index-fill"></em><span>'.translate_phrase('Form Extension').'</span></a></li>';
									}
								} 

							}
						}
						// add manage buttons
						if($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
									<li><a href="javascript:;" class="text-success pop" pageTitle="View ' . $title . '" pageSize="modal-xl" pageName="' . site_url($mod . '/manage/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
									<li><a href="javascript:;" class="text-secondary" onclick="form_share('.$id.', 0);"><em class="icon ni ni-share-alt"></em><span>'.translate_phrase('Form Share').'</span></a></li>
								'.$feed_btn;
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $title . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $title . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" class="text-success pop" pageTitle="View ' . $title . '" pageSize="modal-xl" pageName="' . site_url($mod . '/manage/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								<li><a href="javascript:;" class="text-secondary" onclick="form_share('.$id.', 0);"><em class="icon ni ni-share-alt"></em><span>'.translate_phrase('Form Share').'</span></a></li>
							'.$feed_btn;

							}
							
						}

						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$events = $this->Crud->read_field('id', $q->event_id, 'events', 'title');
						if($q->event_id == 0){
							$events = '-';
						}
						$link = $this->Crud->check2('form_id', $id, 'type', 'form', 'form_link');
						$item .= '
							<tr>
								<td>
									<div class="user-card">
										<div class="user-name">            
											<span class="tb-lead">' . ucwords($title) . '</span> <br>
											<span class="tb-lead text-primary small">' . ucwords($ministry) . '</span> 
										</div>    
									</div>  
								</td>
								<td>
									<span class="small text-dark">'.ucwords($church_type).' Churches</span>
								</td>
								<td>'.ucwords($events).'</td>
								<td>'.number_format($link).'</td>
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
		if($param1 == 'extension_load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';

			if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			
			$form_id = $this->request->getPost('id');

			//echo $status;
			$log_id = $this->session->get('td_id');
			$log_church_id = $this->Crud->read_field('id',  $log_id, 'user', 'church_id');
				
			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$all_rec = $this->Crud->filter_form_extension('', '', $log_id, $form_id,$switch_id);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$query = $this->Crud->filter_form_extension($limit, $offset, $log_id, $form_id,$switch_id);

				$log_region_id = $this->Crud->read_field('id',  $log_church_id, 'church', 'region_id');
				$log_zone_id = $this->Crud->read_field('id',  $log_church_id, 'church', 'zone_id');
				$log_group_id = $this->Crud->read_field('id',  $log_church_id, 'church', 'group_id');

				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$reg_date =  date('M d, Y h:i A', strtotime($q->reg_date));
						$title = $this->Crud->read_field('id', $q->form_id, 'form', 'name');
						$church = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
						

						// add manage buttons
						if($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								<li><a href="javascript:;" class="text-success pop" pageTitle="View ' . $title . '" pageSize="modal-xl" pageName="' . site_url($mod . '/extension/'.$q->form_id.'/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								<li><a href="javascript:;" class="text-secondary" onclick="form_share('.$id.', 1);"><em class="icon ni ni-share-alt"></em><span>'.translate_phrase('Form Share').'</span></a></li>
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit " pageSize="modal-lg" pageName="' . site_url($mod . '/extension/'.$q->form_id.'/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete " pageSize="modal-lg" pageName="' . site_url($mod . '/extension/'.$q->form_id.'/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" class="text-success pop" pageTitle="View ' . $title . '" pageSize="modal-xl" pageName="' . site_url($mod . '/extension/'.$q->form_id.'/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								<li><a href="javascript:;" class="text-secondary" onclick="form_share('.$id.', 1);"><em class="icon ni ni-share-alt"></em><span>'.translate_phrase('Form Share').'</span></a></li>
							';

							}
							
						}

						
						$item .= '
							<tr>
								<td>
									<span class="tb-lead small">' . ucwords($title) . '</span>  
								</td>
								<td>
									<span class="small text-dark">'.ucwords($church).' </span>
								</td>
								<td><span class="small text-dark">'.ucwords($reg_date).'</span></td>
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
						<br/>
						<i class="ni ni-list-index" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Form Extension Returned').'
					</div></td></tr>
				';
			} else {
				$resp['item'] = $item;
				if($offset >= 25){
					$resp['item'] = $item;
				}
				
			}

			if($this->Crud->check2('church_id', $log_church_id, 'form_id', $form_id, 'form_extension') == 0){
				$statuses = false;
			} else {
				$statuses = true;
			} 
			$resp['statuses'] = $statuses;

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
		if($param1 == 'share_load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';

			if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			
			$form_id = $this->request->getPost('id');
			$type = $this->request->getPost('type');

			//echo $status;
			$log_id = $this->session->get('td_id');
			$log_church_id = $this->Crud->read_field('id',  $log_id, 'user', 'church_id');
				
			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$all_rec = $this->Crud->filter_form_link('', '', $log_id, $form_id,$switch_id, $type);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$query = $this->Crud->filter_form_link($limit, $offset, $log_id, $form_id,$switch_id, $type);

				$log_region_id = $this->Crud->read_field('id',  $log_church_id, 'church', 'regional_id');
				$log_zone_id = $this->Crud->read_field('id',  $log_church_id, 'church', 'zonal_id');
				$log_group_id = $this->Crud->read_field('id',  $log_church_id, 'church', 'group_id');

				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$unique_link = $q->unique_link;
						$reg_date =  date('M d, Y h:i A', strtotime($q->reg_date));
						$title = $this->Crud->read_field('id', $q->form_id, 'form', 'name');
						$member = $this->Crud->read_field('id', $q->user_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $q->user_id, 'user', 'surname');
						$church = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
						
						$response = $this->Crud->check('link_id', $id, 'form_response');

						// add manage buttons
						if($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Share Link ' . $title . '" pageSize="modal-md" pageName="' . site_url($mod . '/share/'.$q->form_id.'/share_link/' . $id) . '"><em class="icon ni ni-share"></em><span>'.translate_phrase('Share Form').'</span></a></li>
								<li><a href="' . site_url('ministry/forms/'.$q->unique_link) . '" class="text-dark" target="_blank"><em class="icon ni ni-card-view"></em><span>'.translate_phrase('Preview Form').'</span></a></li>
								<li><a href="javascript:;" class="text-success pop" pageTitle="View Response ' . $title . '" pageSize="modal-xl" pageName="' . site_url($mod . '/share/'.$q->form_id.'/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View Response').'</span></a></li>
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Share Link ' . $title . '" pageSize="modal-md" pageName="' . site_url($mod . '/share/'.$q->form_id.'/share_link/' . $id) . '"><em class="icon ni ni-share"></em><span>'.translate_phrase('Share Form').'</span></a></li>
								<li><a href="' . site_url('ministry/forms/'.$q->unique_link) . '" class="text-dark" target="_blank"><em class="icon ni ni-card-view"></em><span>'.translate_phrase('Preview Form').'</span></a></li>
								<li><a href="javascript:;" class="text-success pop" pageTitle="View Response ' . $title . '" pageSize="modal-xl" pageName="' . site_url($mod . '/share/'.$q->form_id.'/view/' . $id) . '"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View Response').'</span></a></li>
							';

							}
							
						}

						
						$item .= '
							<tr>
								<td>
									<span class="tb-lead small">' . ucwords($member) . '</span>  
								</td>
								<td>
									<span class="small text-dark">'.ucwords($church).' </span>
								</td>
								<td>
									<span class="small text-dark">'.ucwords($unique_link).' </span>
								</td>
								<td>
									<span class="small text-dark">'.ucwords($response).' </span>
								</td>
								<td><span class="small text-dark">'.ucwords($reg_date).'</span></td>
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
					<tr><td colspan="8"><div class="text-center text-muted">
						<br/>
						<i class="ni ni-share-alt" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Form Link Returned').'
					</div></td></tr>
				';
			} else {
				$resp['item'] = $item;
				if($offset >= 25){
					$resp['item'] = $item;
				}
				
			}

			if($this->Crud->check3('user_id', $log_id, 'type', $type, 'form_id', $form_id, 'form_link') == 0){
				$statuses = false;
			} else {
				$statuses = true;
			} 
			$resp['statuses'] = $statuses;

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
		} elseif($param1 == 'extension'){ 
			return view('ministry/extension_form', $data);
		} elseif($param1 == 'share'){ 
			return view('ministry/share_form', $data);
		}else { // view for main page
			
			$data['title'] = 'Form - '.app_name;
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

	public function forms($param1='', $param2='', $param3='', $param4='') {

		$mod = 'ministry/forms';

		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['param4'] = $param4;
		$data['current_language'] = $this->session->get('current_language');
	
		if($param1 == 'validate'){
			if($this->request->getMethod() == 'post'){
				$form_id = $this->request->getPost('form_id');
				$link = $this->request->getPost('link');
				$email = $this->request->getPost('email');
				$phone = $this->request->getPost('phone');
				$link_id = $this->Crud->read_field('unique_link', $link, 'form_link', 'id');
				if($this->Crud->check2( 'phone', $phone, 'link_id', $link_id, 'form_response') > 0){
					echo $this->Crud->msg('warning', 'You are already submitted this Form');
				} else {
					$this->session->set('form_email', $email);
					$this->session->set('form_phone', $phone);
					
					echo $this->Crud->msg('success', 'Welcome!!');
					echo '<script>$("#validate_view").hide(500);$("#form_resp").show(500);</script>';
				}
				die;
			}
		}

		if($param1 == 'submit'){
			if($this->request->getMethod() == 'post'){
				$form_id = $this->request->getPost('form_id');
				$link = $this->request->getPost('link');
				$email = $this->session->get('form_email');
				$phone = $this->session->get('form_phone');
				$formData = $this->request->getPost();
    
				// Filter out empty or undefined values
				$filteredData = array_filter($formData, function($value) {
					return !is_null($value) && $value !== '' && $value !== 'undefined';
				});
				
				// Convert the filtered form data to JSON format
				$response = json_encode($filteredData);
			
				$link_id = $this->Crud->read_field('unique_link', $link, 'form_link', 'id');
				
				$ins['link_id'] = $link_id;
				$ins['response'] = $response;
				$ins['email'] = $email;
				$ins['phone'] = $phone;
				$ins['reg_date'] = date(fdate);
				
				if($this->Crud->check2('link_id', $link_id, 'phone', $phone, 'form_response') > 0){
					echo $this->Crud->msg('danger', 'You have Already Filled this Form. Thank You.');
				} else {
					$ins_rec = $this->Crud->create('form_response', $ins);
					if($ins_rec > 0){
						echo $this->Crud->msg('success', 'Form Submitted Successfully.<br> Thank you For Filling this form.');
						echo '<script>location.reload(false);</script>';
					} else{
						echo $this->Crud->msg('danger', 'Try Again Later.');
					}
				}

				die;
			}
		}
		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = 'Form - '.app_name;
			$data['page_active'] = $mod;

			return view($mod, $data);
		}
	}


}