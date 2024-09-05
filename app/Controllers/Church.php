<?php

namespace App\Controllers;

class Church extends BaseController {

	
	public function regional($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'church/regional';

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
       
		
		$table = 'church';
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
						$code = $this->Crud->read_field('id', $del_id, 'church', 'name');
						$action = $by.' deleted Church ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
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
								$data['e_ministry_id'] = $e->ministry_id;
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
					$ministry_id =  $this->request->getVar('ministry_id');

					
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


					$ins_data['name'] = $name;
					$ins_data['email'] = $email;
					$ins_data['address'] = $address;
					$ins_data['phone'] = $phone;
					$ins_data['type'] = 'region';
					$ins_data['ministry_id'] = $ministry_id;
					if (!empty($img_id) || !empty($getImg->path))  $ins_data['logo'] = $img_id;
					
					// do create or update
					if($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'church', 'name');
							$action = $by.' updated Church ('.$code.') Record';
							$this->Crud->activity('church', $dept_id, $action);

							echo $this->Crud->msg('success', 'Church Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Church Already Exist');
						} else {
							
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'church', 'name');
								$action = $by.' created Church ('.$code.') Record';
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
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			
			$items = '
				
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$type = 'region';
				$all_rec = $this->Crud->filter_church('', '', $log_id, $search, $type);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_church($limit, $offset, $log_id, $search, $type);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$email = $q->email;
						$phone = $q->phone;
						$logo = $q->logo;
						$ministry_id = $q->ministry_id;
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
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
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . ' Region\', ' . (int)$id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>'.translate_phrase('Admin').'</span></a></li>
								
								
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
											<span class="tb-lead">' . ucwords($name) . '</span> <br>
											<span class="tb-lead text-primary">' . ucwords($ministry) . '</span>        
										</div>    
									</div>  
								</td>
								<td>
									<span class="text-dark">'.$email.'</span><br>
									<span class="text-dark">'.$phone.'</span>
								</td>
								<td><span class="text-dark">'.$address.'</span></td>
								<td><span class="text-dark">'.$reg_date.'</span></td>
								<td>
									<ul class="nk-tb-actions">
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
								</td>
							</tr>
							
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<Tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/>
						<i class="ni ni-home-alt" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Regional Church Returned').'
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
			
			$data['title'] = translate_phrase('Regional Church').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }
	
	
	public function zonal($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'church/zonal';

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
       
		
		$table = 'church';
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
						$code = $this->Crud->read_field('id', $del_id, 'church', 'name');
						$action = $by.' deleted Church ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
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
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_regional_id'] = $e->regional_id;
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
					$regional_id = $this->request->getVar('region_id');
					$img_id =  $this->request->getVar('img');
					$ministry_id =  $this->request->getVar('ministry_id');

					
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


					$ins_data['name'] = $name;
					$ins_data['email'] = $email;
					$ins_data['address'] = $address;
					$ins_data['phone'] = $phone;
					$ins_data['type'] = 'zone';
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['regional_id'] = $regional_id;
					if (!empty($img_id) || !empty($getImg->path))  $ins_data['logo'] = $img_id;
					
					// do create or update
					if($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'church', 'name');
							$action = $by.' updated Church ('.$code.') Record';
							$this->Crud->activity('church', $dept_id, $action);

							echo $this->Crud->msg('success', 'Church Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Church Already Exist');
						} else {
							
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'church', 'name');
								$action = $by.' created Church ('.$code.') Record';
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
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			
			$items = '
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$type = 'zone';
				$all_rec = $this->Crud->filter_church('', '', $log_id, $search, $type);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_church($limit, $offset, $log_id, $search, $type);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						$email = $q->email;
						$phone = $q->phone;
						$logo = $q->logo;
						$address = $q->address;
						$ministry_id = $q->ministry_id;
						$regional_id = $q->regional_id;
						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
						$mins = '';
						
						if(!empty($regional_id))$mins .= ' '.$this->Crud->read_field('id', $regional_id, 'church', 'name').' Region';
						if (!empty($logo)) {
							$img = '<img height="40px" src="' . site_url($logo) . '">';
						} else {
							$img = $this->Crud->image_name($name);
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . ' Zone\', ' . (int)$id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>'.translate_phrase('Admin').'</span></a></li>
								
								
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
										<span class="tb-lead">' . ucwords($name) . '</span>   <br>
										<span class="tb-lead text-primary">' . ucwords($ministry) . '</span>              
									</div>    
								</div>  
							</td>
							<td>
								<span class="text-dark">'.$email.'</span><br>
								<span class="text-dark">'.$phone.'</span>
							</td>
							<td><span class="text-dark">'.$address.'</span></td>
							<td><span class="text-dark">'.$mins.'</span></td>
							<td>
								<ul class="nk-tb-actions">
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
							</td>
						</tr>
						
					';
					$a++;
				}
			}
			
		}
		
		if(empty($item)) {
			$resp['item'] = $items.'
				<Tr><td colspan="8"><div class="text-center text-muted">
					<br/><br/><br/>
					<i class="ni ni-home-alt" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Zonal Church Returned').'
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
			
			$data['title'] = translate_phrase('Zonal Church').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }
	

	public function group($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'church/group';

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
       
		
		$table = 'church';
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
						$code = $this->Crud->read_field('id', $del_id, 'church', 'name');
						$action = $by.' deleted Church ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
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
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_regional_id'] = $e->regional_id;
								$data['e_zonal_id'] = $e->zonal_id;
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
					$regional_id = $this->request->getVar('regional_id');
					$zonal_id = $this->request->getVar('zonal_id');
					$img_id =  $this->request->getVar('img');
					$ministry_id =  $this->request->getVar('ministry_id');

					
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

					if(empty($regional_id)){
						echo $this->Crud->msg('warning',  'Please select Regional Church');
						die;
					}
						
					if(empty($zonal_id)){
						echo $this->Crud->msg('warning',  'Please select Zonal Church');
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
					if (!empty($img_id) || !empty($getImg->path))  $ins_data['logo'] = $img_id;
					
					// do create or update
					if($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'church', 'name');
							$action = $by.' updated Church ('.$code.') Record';
							$this->Crud->activity('church', $dept_id, $action);

							echo $this->Crud->msg('success', 'Church Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Church Already Exist');
						} else {
							
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'church', 'name');
								$action = $by.' created Church ('.$code.') Record';
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
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			
			$items = '
				
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$type = 'group';
				$all_rec = $this->Crud->filter_church('', '', $log_id, $search, $type);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_church($limit, $offset, $log_id, $search, $type);
				$data['count'] = $counts;
				

				if(!empty($query)) {
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
						$reg_date = date('d/m/Y h:iA', strtotime($q->reg_date));
						$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name'); 
						$mins = '';
						
						if(!empty($regional_id))$mins .= ' '.$this->Crud->read_field('id', $regional_id, 'church', 'name').' Region';
						if(!empty($zonal_id))$mins .= '&#8594; '.$this->Crud->read_field('id', $zonal_id, 'church', 'name').' Zone';

						if (!empty($logo)) {
							$img = '<img height="40px" src="' . site_url($logo) . '">';
						} else {
							$img = $this->Crud->image_name($name);
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . ' Group\', ' . (int)$id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>'.translate_phrase('Admin').'</span></a></li>
								
								
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
											<span class="tb-lead">' . ucwords($name) . '</span> <br>
										<span class="tb-lead text-primary">' . ucwords($ministry) . '</span>                   
										</div>    
									</div>  
								</td>
								<td>
									<span class="text-dark">'.$email.'</span><br>
									<span class="text-dark">'.$phone.'</span>
								</td>
								<td><span class="text-dark">'.$address.'</span></td>
								<td><span class="text-dark">'.$mins.'</span></td>
								<td>
									<ul class="nk-tb-actions">
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
								</td>
							</tr>
							
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<Tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/>
						<i class="ni ni-home-alt" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Group Church Returned').'
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
			
			$data['title'] = translate_phrase('Zonal Church').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }
	

	public function church($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'church/church';

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
       
		
		$table = 'church';
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
						$code = $this->Crud->read_field('id', $del_id, 'church', 'name');
						$action = $by.' deleted Church ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
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
								$data['e_ministry_id'] = $e->ministry_id;
								$data['e_regional_id'] = $e->regional_id;
								$data['e_zonal_id'] = $e->zonal_id;
								$data['e_group_id'] = $e->group_id;
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
					$regional_id = $this->request->getVar('regional_id');
					$zonal_id = $this->request->getVar('zonal_id');
					$group_id = $this->request->getVar('group_id');
					$img_id =  $this->request->getVar('img');
					$ministry_id =  $this->request->getVar('ministry_id');

					
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

					if(empty($regional_id)){
						echo $this->Crud->msg('warning',  'Please select Regional Church');
						die;
					}
						
					if(empty($zonal_id)){
						echo $this->Crud->msg('warning',  'Please select Zonal Church');
						die;
					}
					if(empty($group_id)){
						echo $this->Crud->msg('warning',  'Please select Group Church');
						die;
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
					if (!empty($img_id) || !empty($getImg->path))  $ins_data['logo'] = $img_id;
					
					// do create or update
					if($dept_id) {
						$upd_rec = $this->Crud->updates('id', $dept_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $dept_id, 'church', 'name');
							$action = $by.' updated Church ('.$code.') Record';
							$this->Crud->activity('church', $dept_id, $action);

							echo $this->Crud->msg('success', 'Church Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Church Already Exist');
						} else {
							
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'church', 'name');
								$action = $by.' created Church ('.$code.') Record';
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
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			
			$items = '
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$type = 'church';
				$all_rec = $this->Crud->filter_church('', '', $log_id, $search, $type);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_church($limit, $offset, $log_id, $search, $type);
				$data['count'] = $counts;
				

				if(!empty($query)) {
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
						$mins = '';
						
						if(!empty($regional_id))$mins .= ' '.$this->Crud->read_field('id', $regional_id, 'church', 'name').' Region';
						if(!empty($zonal_id))$mins .= '&#8594; '.$this->Crud->read_field('id', $zonal_id, 'church', 'name').' Zone';
						if(!empty($group_id))$mins .= '<br>&#8594; '.$this->Crud->read_field('id', $group_id, 'church', 'name').' Group';

						if (!empty($logo)) {
							$img = '<img height="40px" src="' . site_url($logo) . '">';
						} else {
							$img = $this->Crud->image_name($name);
						}
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" onclick="church_admin(\'' . addslashes(ucwords($name)) . '\', ' . (int)$id . ');" class="text-info" ><em class="icon ni ni-user-add"></em><span>'.translate_phrase('Admin').'</span></a></li>
								
								
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
											<span class="tb-lead">' . ucwords($name) . '</span>    <br>
										<span class="tb-lead text-primary">' . ucwords($ministry) . '</span>                        
										</div>    
									</div>  
								</td>
								<td>
									<span class="text-dark">'.$email.'</span><br>
									<span class="text-dark">'.$phone.'</span>
								</td>
								<td><span class="text-dark">'.$address.'</span></td>
								<td><span class="text-dark">'.$mins.'</span></td>
								<td>
									<ul class="nk-tb-actions">
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
								</td>
							</tr>
							
						';
						$a++;
					}
				}
				
			}
		
			if(empty($item)) {
				$resp['item'] = $items.'
					<Tr><td colspan="8"><div class="text-center text-muted">
						<br/><br/><br/>
						<i class="ni ni-home-alt" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Church Assembly Returned').'
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
			
			$data['title'] = translate_phrase('Church Assembly').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	
	public function administrator($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'church/administrator';

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
		
		$church_id = $this->session->get('church_id');
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
						$code = $this->Crud->read_field('id', $del_id, 'user', 'firstname');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Deleted'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by.' deleted Administrator ('.$code.')';
							$this->Crud->activity('user', $del_id, $action);
							echo '<script>
								load_admin("","",'.$church_id.');
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
				if($param2 == 'edit') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_surname'] = $e->surname;
								$data['e_firstname'] = $e->firstname;
								$data['e_phone'] = $e->phone;
								$data['e_address'] = $e->address;
								$data['e_activate'] = $e->activate;
								$data['e_email'] = $e->email;
								$data['e_role_id'] = $e->role_id;
							}
						}
					}
				} 
				
				if($this->request->getMethod() == 'post'){
					$user_id = $this->request->getPost('user_id');
					$surname = $this->request->getPost('surname');
					$firstname = $this->request->getPost('firstname');
					$phone = $this->request->getPost('phone');
					$email = $this->request->getPost('email');
					$address = $this->request->getPost('address');
					$activate = $this->request->getPost('activate');
					$password = $this->request->getPost('password');

					
					$church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
					$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
					if($church_type == 'region'){
						$urole_id = $this->Crud->read_field('name', 'Regional Manager',  'access_role', 'id');

					}
					if($church_type == 'zone'){
						$urole_id = $this->Crud->read_field('name', 'Zonal Manager',  'access_role', 'id');

					}
					if($church_type == 'group'){
						$urole_id = $this->Crud->read_field('name', 'Group Manager',  'access_role', 'id');

					}
					if($church_type == 'church'){
						$urole_id = $this->Crud->read_field('name', 'Church Leader',  'access_role', 'id');

					}

					$ins_data['surname'] = $surname;
					$ins_data['firstname'] = $firstname;
					$ins_data['email'] = $email;
					$ins_data['phone'] = $phone;
					$ins_data['activate'] = $activate;
					$ins_data['address'] = $address;
					$ins_data['role_id'] = $urole_id;
					if($password) { $ins_data['password'] = md5($password); }
					
					// do create or update
					if($user_id) {
						$upd_rec = $this->Crud->updates('id', $user_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Updated'));

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
							$action = $by.' updated Administrator ('.$code.') Record';
							$this->Crud->activity('user', $user_id, $action);
								echo '<script>
									load_admin("","",'.$church_id.');
									$("#modal").modal("hide");
								</script>';
						} else {
							echo $this->Crud->msg('info', translate_phrase('No Changes'));	
						}
					} else {
						if($this->Crud->check('email', $email, $table) > 0 || $this->Crud->check('phone', $phone, $table) > 0) {
							echo $this->Crud->msg('warning', ('Email and/or Phone Already Exist'));
						} else {
							$ins_data['ministry_id'] = $ministry_id;
							$ins_data['church_id'] = $church_id;
							$ins_data['is_admin'] = 1;
							$ins_data['reg_date'] = date(fdate);

							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', translate_phrase('Record Created'));
								$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));

								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'user', 'firstname');
								$action = $by.' created Administrator ('.$code.')';
								$this->Crud->activity('user', $ins_rec, $action);

								echo '<script>
									load_admin("","",'.$church_id.');
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
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 25;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			$search = $this->request->getPost('search');
			$church_id = $this->request->getPost('id');
			$this->session->set('church_id', $church_id);
			
			if(empty($ref_status))$ref_status = 0;
			$items = '
					
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$role_ids = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');

				$all_rec = $this->Crud->filter_church_admin('', '', $log_id, $status, $search, $church_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_church_admin($limit, $offset, $log_id, $status, $search, $church_id);
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

						// add manage buttons
						
							$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $fullname . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $fullname . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								
								
							';
						

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
											
										</div>
									</div>
								</td>
								<td><span>' . $email . '</span></td>
								<td><span class="text-dark"><b>' . $phone . '</b></span></td>
								<td><span>' . $u_role . '</span></td>
								<td><span>' . ucwords($address) . '</span></td>
								<td><span class="tb-amount">' . $reg_date . ' </span></td>
								<td>
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
						<i class="ni ni-users" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Administrator Account Returned').'
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
			return view('church/admin_form', $data);
		} 

	}
	
	public function get_region(){
		$ministry_id = $this->request->getPost('ministry_id');
		if(empty($ministry_id)){
			echo json_encode(array()); // return an empty array if ministry_id is empty
        	return;
		} else {
			$regions = $this->Crud->read2_order('type','region', 'ministry_id', $ministry_id, 'church', 'name', 'asc');
			$data = array();
			foreach ($regions as $region) {
				$data[] = array('id' => $region->id, 'name' => $region->name);
			}
			echo json_encode($data);
		}
	}

	public function get_zone(){
		$regional_id = $this->request->getPost('regional_id');
		if(empty($regional_id)){
			echo json_encode(array()); // return an empty array if ministry_id is empty
        	return;
		} else {
			$regions = $this->Crud->read2_order('type','zone', 'regional_id', $regional_id, 'church', 'name', 'asc');
			$data = array();
			foreach ($regions as $region) {
				$data[] = array('id' => $region->id, 'name' => $region->name);
			}
			echo json_encode($data);
		}
	}

	
	public function get_group(){
		$zonal_id = $this->request->getPost('zonal_id');
		if(empty($zonal_id)){
			echo json_encode(array()); // return an empty array if ministry_id is empty
        	return;
		} else {
			$regions = $this->Crud->read2_order('type','group', 'zonal_id', $zonal_id, 'church', 'name', 'asc');
			$data = array();
			foreach ($regions as $region) {
				$data[] = array('id' => $region->id, 'name' => $region->name);
			}
			echo json_encode($data);
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