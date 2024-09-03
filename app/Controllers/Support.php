<?php

namespace App\Controllers;

class Support extends BaseController {
	public function list($param1='', $param2='', $param3='') {
		$log_id = $this->session->get('td_id');
		if(empty($log_id)) return redirect()->to(site_url('auth'));
 
		if($this->Crud->check2('id', $log_id, 'setup', 0, 'user')> 0)return redirect()->to(site_url('auth/security'));
		if($this->Crud->check2('id', $log_id, 'state_id', 0, 'user')> 0)return redirect()->to(site_url('auth/profile'));
		if($this->Crud->check2('id', $log_id, 'country_id', 0, 'user')> 0)return redirect()->to(site_url('auth/profile'));
		
        $mod = 'support/list';

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
       
		$table = 'support';
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
                    //echo var_dump($edit);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$del_id =  $this->request->getVar('d_support_id');
                        $code = $this->Crud->read_field('id', $del_id, 'support', 'title');
						$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
						$action = $by.' deleted support ticket('.$code.')';
							
                        if($this->Crud->deletes('id', $del_id, $table) > 0) {

							///// store activities
							$this->Crud->activity('support', $del_id, $action);
							
							echo $this->Crud->msg('success', translate_phrase('Record Deleted'));
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', translate_phrase('Please try later'));
						}
						die;	
					}
				}
			} elseif($param2 == 'escalate'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
                    //echo var_dump($edit);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$del_id =  $this->request->getVar('e_support_id');
                        $code = $this->Crud->read_field('id', $del_id, 'support', 'title');
						$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
						$action = $by.' escalated support ticket('.$code.')';
						
						$te = $this->Crud->read_single('role_id', 1, 'user');
						foreach($te as $team){
							$in_data['from_id'] = $log_id;
							$in_data['to_id'] = $team->id;
							$in_data['content'] = $code;
							$in_data['item'] = 'support';
							$in_data['new'] = 0;
							$in_data['reg_date'] = date(fdate);
							$in_data['item_id'] = $del_id;
							$this->Crud->create('notify', $in_data);
							
						}
							///// store activities
						$this->Crud->activity('support', $del_id, $action);
						
						echo $this->Crud->msg('success', translate_phrase('Support Ticket Escalated'));
						echo '<script>location.reload(false);</script>';
						
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
								$data['e_details'] = $e->details;
								$data['e_role_id'] = $e->role_id;
								$data['e_img'] = $e->file;
								$data['e_status'] = $e->status;
							}
						}
					}
				}

                // prepare for edit
				if($param2 == 'view') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_user_id'] = $e->user_id;
								$data['e_title'] = $e->title;
								$data['e_details'] = $e->details;
								$data['e_reg_date'] = $e->reg_date;
								$data['e_img'] = $e->file;
								$data['e_status'] = $e->status;
							}
						}
					}
				}

				
				if($this->request->getMethod() == 'post'){
					$title =  $this->request->getVar('name');
					$details =  $this->request->getVar('details');
                    $img_id =  $this->request->getVar('img');
					
                    //// Image upload
					if(file_exists($this->request->getFile('pics'))) {
						$path = 'assets/images/support/'.$log_id.'/';
						$file = $this->request->getFile('pics');
						$getImg = $this->Crud->img_upload($path, $file);
						
						if(!empty($getImg->path)) $img_id = $getImg->path;
					}

					$name = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
					$teams = ["1","2"];
					$opt = json_encode($teams);
					
					if($this->Crud->check2('title', $title, 'user_id', $log_id, $table) > 0) {
						echo $this->Crud->msg('warning', translate_phrase('Record Already Exist'));
					} else {
						$ins_data['title'] = $title;
						if(!empty($img_id) || !empty($getImg->path))$ins_data['file'] = $img_id;
						$ins_data['details'] = $details;
						$ins_data['user_id'] = $log_id;
						$ins_data['role_id'] = $opt;
						$ins_data['reg_date'] = date(fdate);
						
						$ins_rec = $this->Crud->create($table, $ins_data);
						if($ins_rec > 0) {
							echo $this->Crud->msg('success', translate_phrase('Record Created'));
							$opt = json_decode($opt);
							foreach($opt as $o => $val){
							$team = $this->Crud->read_single('role_id', $val, 'user');
							if(!empty($team)){
								foreach($team as $t)
									$in_data['from_id'] = $log_id;
									$in_data['to_id'] = $t->id;
									$in_data['content'] = $name.' Created a Support Ticket';
									$in_data['item'] = 'support';
									$in_data['new'] = 1;
									$in_data['reg_date'] = date(fdate);
									$in_data['item_id'] = $ins_rec;
									$this->Crud->create('notify', $in_data);
								}
							}
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
							$code = $this->Crud->read_field('id', $ins_rec, 'support', 'title');
							$action = $by.' created Support Ticket ('.$code.') Record';
							$this->Crud->activity('support', $ins_rec, $action);
							echo '<script>location.reload(false);</script>';
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

			$rec_limit = 55;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			if(!empty($this->request->getPost('page_active'))) { $page_active = $this->request->getPost('page_active'); } else { $page_active = ''; }
			$search = $this->request->getPost('search');
			$active = '1';
			$close = '2';
			$all = '3';
			
			$items = '';
			$active_ac = '';
			$close_ac = '';
			$all_ac = '';
			if($status == '1'){
				$active_ac = 'active';
			}
			if(empty($status)){
				$close_ac = 'active';
			}
			if($status == 'all'){
				$all_ac = 'active';
			}
			
			$items
				.= '
				<div class="nk-msg-aside">
					<div class="nk-msg-nav">
						<ul class="nk-msg-menu">
							<li class="nk-msg-menu-item '.$active_ac.'"><a href="javascript:;" onclick="supports('.$active.')">'.translate_phrase('Active').'</a></li>
							<li class="nk-msg-menu-item '.$close_ac.'"><a href="javascript:;" onclick="supports('.$close.')">'.translate_phrase('Closed').'</a></li>
							<li class="nk-msg-menu-item '.$all_ac.'"><a href="javascript:;" onclick="supports('.$all.')">'.translate_phrase('All').'</a></li>
							<li class="nk-msg-menu-item ms-auto"><a href="javascript:;" class="search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a></li>
						</ul><!-- .nk-msg-menu -->
						<div class="search-wrap" data-search="search">
							<div class="search-content">
								<a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
								<input type="text" class="form-control border-transparent form-focus-none" oninput="load()" id="search" placeholder="'.translate_phrase('Search by message').'">
								<button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
							</div>
						</div>
					</div>
					<div class="nk-msg-list" data-simplebar>
					';
            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$all_rec = $this->Crud->filter_support('', '', $log_id, $status, $search);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$query = $this->Crud->filter_support($limit, $offset, $log_id, $status, $search);
				$data['count'] = $counts;
				//print_r($query);
				if(!empty($query)) {
					
					$a = 1;
					foreach($query as $q) {
						$id = $q->id;
						$title = $q->title;
						$user_i = $q->user_id;
						$status = $q->status;
						$details = $q->details;
						$reg_date = date('M d, Y h:ia', strtotime($q->reg_date));
						$file = $q->file;

						$img_id = $this->Crud->read_field('id', $user_i, 'user', 'img_id');
						$names = $this->Crud->read_field('id', $user_i, 'user', 'fullname');
						if(empty($names)){
							$names = 'Guest';
						}
						if(empty($img_id)){
							$wors = $this->Crud->image_name($names);
							$img = '<span>'.$wors.'</span>';
						} else {
							$image = $this->Crud->read_field('id', $img_id, 'file', 'path');
							$img = '<img height="38px" src="'.site_url($image).'">';
						}
						if($status == 0){
							$stat = 'Ticket Opened';
							$st = 'danger';
						} else {
							$stat = 'Ticket Closed';
							$st = 'success';
						}
						$cur = '';
						if($a == 1){
							$cur = 'current';
							$item .= '<script>page_active('.$a.', '.$id.')</script>';
						}

						$files = '<div class="attchment"><em class="icon ni ni-clip-h"></em></div>';
						if(empty($file)){
							$files = '';
						}
							$item .= '
								<div class="nk-msg-item '.$cur.'" id="support_id'.$a.'" data-msg-id="'.$a.'" onclick="page_active('.$a.', '.$id.')">
									<div class="nk-msg-media user-avatar">
										'.$img.'
									</div>
									<div class="nk-msg-info">
										<div class="nk-msg-from">
											<div class="nk-msg-sender">
												<div class="name">'.$names.'</div>
											</div>
											<div class="nk-msg-meta">
												'.$files.'
												<div class="date">'.$reg_date.'</div>
											</div>
										</div>
										<div class="nk-msg-context">
											<div class="nk-msg-text">
												<h6 class="title">'.ucwords($title).'</h6>
												<p>'.ucwords($details).'</p>
											</div>
										</div>
									</div>
								</div>
							';
						$a++;
					}
					$item .= '</div>
					</div>
					<div class="nk-msg-body bg-white profile-shown" id="support_body">
						
						
					</div>
					';
				}
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="nk-msg-item " data-msg-id="0">
						<div class="text-center text-muted"><br><br><br><br>
							<i class="ni ni-chat" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Support Ticket Returned').'
						</div>
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
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
			
			$data['title'] = translate_phrase('Support Ticket').'  | '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	public function comment($param1='', $param2='', $param3='', $param4='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'support/list';

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

		$data['file'] = $this->Crud->read_field('id', $param1, 'support', 'file');
		$data['titles'] = $this->Crud->read_field('id', $param1, 'support', 'title');
		$data['details'] = $this->Crud->read_field('id', $param1, 'support', 'details');
		$data['reg_date'] = $this->Crud->read_field('id', $param1, 'support', 'reg_date');
		$user = $this->Crud->read_field('id', $param1, 'support', 'user_id');
		$img_id = $this->Crud->read_field('id', $user, 'user', 'img_id');
		$names = $this->Crud->read_field('id', $user, 'user', 'fullname');
		if(empty($img_id)){
			$wors = $this->Crud->image_name($names);
			$img = '<span>'.$wors.'</span>';
		} else {
			$image = $this->Crud->read_field('id', $img_id, 'file', 'path');
			$img = '<img height="40px" src="'.site_url($image).'">';
		}
		$data['name'] = $names;
		$data['usr'] = $user;
		$data['image'] = $img;
		
        $data['current_language'] = $this->session->get('current_language');
		$table = 'branch';
		$form_link = site_url('support/comment');
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= '/'.$param3.'/';}
		if($param4){$form_link .= $param4;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['param4'] = $param4;
		$data['form_link'] = $form_link;

      
		// record listing
		if($param2 == 'load') {
			$limit = $param3;
			$offset = $param4;

			$count = 0;
			$rec_limit = 25;
			$item = '';
			$items = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			$search = $this->request->getVar('search');
			$todo = $param1;
			
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$query = $this->Crud->filter_comment($limit, $offset, $log_id, $todo, $search);
				$all_rec = $this->Crud->filter_comment('', '', $log_id, $todo, $search);
				if(!empty($all_rec)) { $count = count($all_rec); } else { $count = 0; }

				$support =  $this->Crud->read_field('id', $todo, 'support', 'title');
				$support_user =  $this->Crud->read_field('id', $todo, 'support', 'user_id');
				$support_details =  $this->Crud->read_field('id', $todo, 'support', 'details');
				$support_user =  $this->Crud->read_field('id', $todo, 'support', 'user_id');
				$support_dates =  $this->Crud->read_field('id', $todo, 'support', 'reg_date');
				$support_file =  $this->Crud->read_field('id', $todo, 'support', 'file');
				$support_status =  $this->Crud->read_field('id', $todo, 'support', 'status');
				$support_date =  date('F d, Y h:iA', strtotime($support_dates));
				
				$img_id = $this->Crud->read_field('id', $support_user, 'user', 'img_id');
				$names = $this->Crud->read_field('id', $support_user, 'user', 'fullname');
				
				$files = '';
				if(!empty($support_file)){
					$files = '
						<div class="attach-files">
							<ul class="attach-list">
								<li class="attach-item">
									<a class="download" href="javascript:;"><em class="icon ni ni-img"></em>
										<span>
											<img  style="width:300px; height:250px;" src="'.site_url($support_file).'">
										</span>
									</a>
								</li>
							</ul>
						</div>
					';
				}

				$btn ='';
				if(!empty($support_status)){
					$btn = '
						<li><a href="javascript:;" onclick="mark_reads('.$todo.')" class="btn btn-dim btn-sm btn-outline-light"><em class="icon ni ni-check"></em><span>'.translate_phrase('Mark as Closed').'</span></a></li>
					';
				}
				if(empty($names)){
					$names = 'Guest';
				}
				if(empty($img_id)){
					$wors = $this->Crud->image_name($names);
					$img = '<span>'.$wors.'</span>';
				} else {
					$image = $this->Crud->read_field('id', $img_id, 'file', 'path');
					$img = '<img height="35px" src="'.site_url($image).'">';
				}$first_name = array_slice(explode(' ', $names), -1)[0];

				$items .= '
				<div class="nk-msg-head">
					<h4 class="title d-none d-lg-block">'.ucwords($support).'</h4>
					<div class="nk-msg-head-meta">
						<div class="d-none d-lg-block">
							<ul class="nk-msg-tags">
								<li><span class="label-tag"><em class="icon ni ni-flag-fill"></em> <span>'.translate_phrase('Technical Problem').'</span></span></li>
							</ul>
						</div>
						<div class="d-lg-none"><a href="javascript:;"  class="btn btn-icon btn-trigger nk-msg-hide ms-n1"><em class="icon ni ni-arrow-left"></em></a></div>
						<ul class="nk-msg-actions" id="support_resp">
							'.$btn.'
						</ul>
					</div>
				</div><!-- .nk-msg-head -->
				<div class="nk-msg-reply nk-reply" data-simplebar>
					<div class="nk-msg-head py-4 d-lg-none">
						<h4 class="title">'.ucwords($support).'</h4>
						<ul class="nk-msg-tags">
							<li><span class="label-tag"><em class="icon ni ni-flag-fill"></em> <span>'.translate_phrase('Technical Problem').'</span></span></li>
						</ul>
					</div>
					<div class="nk-reply-item">
						<div class="nk-reply-header">
							<div class="user-card">
								<div class="user-avatar sm bg-blue">
									'.$img.'
								</div>
								<div class="user-name">'.ucwords($names).'</div>
							</div>
							<div class="date-time">'.$support_date.'</div>
						</div>
						<div class="nk-reply-body">
							<div class="nk-reply-entry entry">
								<p>'.ucwords($support_details).'</p>
								<p>'.translate_phrase('Thank you').' <br> '.ucwords($first_name).'</p>
							</div>'.$files.'
						</div>
					</div><!-- .nk-reply-item --><hr>
				';
				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$comment = $q->comment;
						$resp_id = $q->resp_id;
						$support_id = $q->support_id;
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
                        
						//$user = $this->Crud->read_field('id', $param1, 'support', 'user_id');
						$img_id = $this->Crud->read_field('id', $resp_id, 'user', 'img_id');
						$names = $this->Crud->read_field('id', $resp_id, 'user', 'fullname');
						if(empty($img_id)){
							$wors = $this->Crud->image_name($names);
							$img = '<span>'.$wors.'</span>';
						} else {
							$image = $this->Crud->read_field('id', $img_id, 'file', 'path');
							$img = '<img height="38px" src="'.site_url($image).'">';
						}
						$name = $names;
						$image = $img;

						
                        $item .= '
							<div class="nk-reply-item">
								<div class="nk-reply-header">
									<div class="user-card">
										<div class="user-avatar sm bg-blue">
											'.$img.'
										</div>
										<div class="user-name">'.ucwords($name).'</div>
									</div>
									<div class="date-time">'.$reg_date.'</div>
								</div>
								<div class="nk-reply-body">
									<div class="nk-reply-entry entry">
										<p>'.ucwords($comment).'</p>
									</div>'.$files.'
								</div>
							</div><!-- .nk-reply-item -->
							<hr>
								
						';
					}
				}
			}
			$names = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
			$wors = $this->Crud->image_name($names);

			$item_bottom = '';
			if(!empty($support_status)){
				
				$item_bottom = '
					<div class="nk-reply-form">
						<div class="nk-reply-form-header">
							<ul class="nav nav-tabs-s2 nav-tabs nav-tabs-sm">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#reply-form">'.translate_phrase('Reply').'</a>
								</li>
							</ul>
							<div class="nk-reply-form-title">
								<div class="title">Reply as:</div>
								<div class="user-avatar xs bg-danger">
									<span>'.$wors.'</span>
								</div>
							</div>
						</div>
						<div class="tab-content">
							<div class="tab-pane active" id="reply-form">
								<div class="nk-reply-form-editor">
									<div class="nk-reply-form-field">
										<textarea class="form-control form-control-simple no-resize" placeholder="'.translate_phrase('Hello').'" id="reply" name="reply"></textarea>
									</div>
									<div class="nk-reply-form-tools">
										<ul class="nk-reply-form-actions g-1">
											<li class="me-2"><button class="btn btn-primary" onclick="reply_btn('.$todo.')" type="submit">'.translate_phrase('Reply').'</button></li>
										</ul>
									</div><!-- .nk-reply-form-tools -->
								</div><!-- .nk-reply-form-editor -->
							</div>
						</div>
					</div><!-- .nk-reply-form -->
					
					</div><!-- .nk-reply -->
					
											
					<script src="'.site_url().'assets/js/bundle.js?ver=3.1.2"></script>
					<script src="'.site_url().'assets/js/scripts.js?ver=3.1.2"></script>
					<script src="'.site_url().'assets/js/apps/messages.js?ver=3.1.2"></script>			
				';
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="nk-reply-item">
						<div class="text-center text-muted">
							<i class="ni ni-chat-circle" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Reply Yet. Please Check Back').'
						</div>
					</div>
					
				'.$item_bottom;
			} else {
				$resp['item'] = $items.$item.$item_bottom;
			}

			$resp['count'] = $count;

			$more_record = $count - ($offset + $rec_limit);
			$resp['left'] = $more_record;

			if($count > ($offset + $rec_limit)) { // for load more records
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
			return view('support/comment_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Support Tickets').'  | '.app_name;
			$data['page_active'] = $mod;
			return view('support/comment', $data);
		}
    }

	public function save_comment(){
		$log_id = $this->session->get('td_id');
		$support_id = $this->request->getVar('id');
		$user_id = $this->Crud->read_field('id', $support_id, 'support', 'user_id');
		$comment = $this->request->getVar('reply');
		if(!empty($comment)){
			if($this->Crud->check3('resp_id', $log_id, 'comment', $comment, 'support_id', $support_id, 'support_comment') == 0){
				$ins_rec['user_id'] = $user_id;
				$ins_rec['support_id'] = $support_id;
				$ins_rec['resp_id'] = $log_id;
				$ins_rec['comment'] = $comment;
				$ins_rec['reg_date'] = date(fdate);
				$w_ins = $this->Crud->create('support_comment', $ins_rec);

				$name = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
				$names = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
				if($w_ins > 0){
					$in_data['from_id'] = $log_id;
					$in_data['to_id'] = $user_id;
					$in_data['content'] = 'Comment on Support Ticket';
					$in_data['item'] = 'support';
					$in_data['new'] = 1;
					$in_data['reg_date'] = date(fdate);
					$in_data['item_id'] = $w_ins;
					$this->Crud->create('notify', $in_data);
					
					$action = $names.' Replied to Support Ticket';
					$this->Crud->activity('support', $support_id, $action);
				}
			}
		}
		
	}

	public function mark($support_id){
		$log_id = $this->session->get('td_id');
		$user_id = $this->Crud->read_field('id', $support_id, 'support', 'user_id');
		
		if(!empty($support_id)){
			$ins_rec['status'] = 0;
			$w_ins = $this->Crud->updates('id', $support_id,'support', $ins_rec);

			$name = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
			if($w_ins > 0){
				$in_data['from_id'] = $log_id;
				$in_data['to_id'] = $user_id;
				$in_data['content'] = $name.' Closed Support Ticket';
				$in_data['item'] = 'support';
				$in_data['new'] = 1;
				$in_data['reg_date'] = date(fdate);
				$in_data['item_id'] = $w_ins;
				$this->Crud->create('notify', $in_data);

				$action = $name.' Closed Support Ticket';
				$this->Crud->activity('support', $support_id, $action);
				
				echo translate_phrase('Closed');
			}
		}
		
	}
}


