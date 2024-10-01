<?php

namespace App\Controllers;

class Service extends BaseController {
	public function type($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'service/type';

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
       
		
		$table = 'service_type';
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
						$del_id = $this->request->getVar('d_type_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'service_type', 'name');
						$action = $by.' deleted Service Type ('.$code.')';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Service Type Deleted');
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
					$type_id = $this->request->getVar('type_id');
					$name = $this->request->getVar('name');

					$ins_data['name'] = $name;
					
					// do create or update
					if($type_id) {
						$upd_rec = $this->Crud->updates('id', $type_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $type_id, 'service_type', 'name');
							$action = $by.' updated Service Type ('.$code.')';
							$this->Crud->activity('service', $type_id, $action);

							echo $this->Crud->msg('success', 'Service Type Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Service Type Already Exist');
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'service_type', 'name');
								$action = $by.' created Service Type ('.$code.')';
								$this->Crud->activity('service', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Service Type Created');
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
					<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Type').'</span></div>
					<div class="nk-tb-col nk-tb-col-tools">
						
					</div>
				</div><!-- .nk-tb-item -->
		
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_service_type('', '', $search);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_service_type($limit, $offset, $search);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
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
						<i class="ni ni-building" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Service Type Returned').'<br/>
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
			
			$data['title'] = translate_phrase('Service Type').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	public function report($param1='', $param2='', $param3='', $param4='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'service/report';

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
       
		
		$table = 'service_report';
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
						$edata['e_type'] = $e->type;
						$edata['e_date'] = $e->date;
						$edata['e_tithe'] = $e->tithe;
						$edata['e_partnership'] = $e->partnership;
						$edata['e_date'] = $e->date;
						$edata['e_attendance'] = $e->attendance;
						$edata['e_new_convert'] = $e->new_convert;
						$edata['e_first_timer'] = $e->first_timer;
						$edata['e_offering'] = $e->offering;
						$edata['e_note'] = $e->note;
						$edata['e_attendant'] = $e->attendant;
						$edata['e_tithers'] = $e->tithers;
						$edata['e_partners'] = $e->partners;
						$edata['e_timers'] = $e->timers;
						$edata['e_converts'] = $e->converts;
						$edata['e_ministry_id'] = $e->ministry_id;
						$edata['e_church_id'] = $e->church_id;
						$edata['e_level'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
					}
				}
			}
			echo json_encode($edata);
			die;
		} 
		// manage record
		if($param1 == 'manage') {
			$data['first'] = [];
			$mem_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
			$mem = $this->Crud->read_single_order('role_id', $mem_id, 'user', 'firstname', 'asc');
			$data['mem'] = $mem;

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
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					if(!empty($edit)) {
						foreach($edit as $e){
							$attendant = $e->attendant;
							$attendants = json_decode($attendant);
							$total =0;$guest=0;$member=0;
							$male=0;$female=0;$children=0;
							if(!empty($attendants)){
								foreach($attendants as $at => $ats){
									if($at == 'total'){
										$total = $ats;
									}
									if($at == 'guest'){
										$guest = $ats;
									}
									if($at == 'member'){
										$member = $ats;
									}
									if($at == 'male'){
										$male = $ats;
									}
									if($at == 'female'){
										$female = $ats;
									}
									if($at == 'children'){
										$children = $ats;
									}
								}
							}
						}
						$resp['attendance_id'] = $param3;
						$resp['total_attendance'] = $total;
						$resp['guest_attendance'] = $guest;
						$resp['member_attendance'] = $member;
						$resp['male_attendance'] = $male;
						$resp['female_attendance'] = $female;
						$resp['children_attendance'] = $children;
						echo json_encode($resp);
						die;
					}
					
				}
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$attendance_id = $this->request->getPost('attendance_id');
					$total = $this->request->getPost('total');
					$member = $this->request->getPost('member');
					$guest = $this->request->getPost('guest');
					$male = $this->request->getPost('male');
					$female = $this->request->getPost('female');
					$children = $this->request->getPost('children');

					// Fetch the existing attendant data and decode it
					$existing_attendant = json_decode($this->Crud->read_field('id', $attendance_id, 'service_report', 'attendant'), true);

					// Check if the existing attendant data is not empty
					if (!empty($existing_attendant)) {
						// If it's not empty, merge the new attendant data with the existing data
						$existing_attendant['total'] = $total;
						$existing_attendant['member'] = $member;
						$existing_attendant['guest'] = $guest;
						$existing_attendant['male'] = $male;
						$existing_attendant['female'] = $female;
						$existing_attendant['children'] = $children;
					} else {
						// If it's empty, create a new attendant array
						$existing_attendant = [
							'total' => $total,
							'member' => $member,
							'guest' => $guest,
							'male' => $male,
							'female' => $female,
							'children' => $children,
						];
					}

					// Encode the attendant data back to JSON
					$in_data['attendant'] = json_encode($existing_attendant);
 
					$in_data['attendance'] = $total; 
					
					if(empty($data)){
						echo $this->Crud->msg('danger', 'Mark Service Attendance');
					
					} else{
						if($this->Crud->updates('id', $attendance_id, 'service_report', $in_data) > 0){
							echo $this->Crud->msg('success', 'Service Attendance Submitted');
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$service_date = $this->Crud->read_field('id', $attendance_id, 'service_report', 'date');
							$action = $by.' updated Service Report for '.$service_date;
							$this->Crud->activity('service', $attendance_id, $action);

							// echo json_encode($data);
							echo '<script> setTimeout(function() {
								$("#show").show(500);
									$("#form").hide(500);
									$("#attendance_view").hide(500);
									$("#attendance_prev").hide(500);
									$("#add_btn").show(500);
									
									$("#prev").hide(500);
									load();
									$("#attendance_msg").html("");
							}, 2000); </script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes to Service Attendance');
						
						}
						
					}
					die;
				}

			} elseif($param2 == 'mark_attendance'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					if(!empty($edit)) {
						foreach($edit as $e){
							$attendant = $e->attendant;
							$attendants = json_decode($attendant);
							$total =0;$guest=0;$member=0;
							$male=0;$female=0;$children=0;
							if(!empty($attendants)){
								foreach($attendants as $at => $ats){
									if($at == 'total'){
										$total = $ats;
									}
									if($at == 'guest'){
										$guest = $ats;
									}
									if($at == 'member'){
										$member = $ats;
									}
									if($at == 'male'){
										$male = $ats;
									}
									if($at == 'female'){
										$female = $ats;
									}
									if($at == 'children'){
										$children = $ats;
									}
								}
							}
						}
						$resp['attendance_id'] = $param3;
						$resp['total_attendance'] = $total;
						$resp['guest_attendance'] = $guest;
						$resp['member_attendance'] = $member;
						$resp['male_attendance'] = $male;
						$resp['female_attendance'] = $female;
						$resp['children_attendance'] = $children;
						echo json_encode($resp);
						die;
					}
					
				}
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$attendance_id = $this->request->getPost('attendance_id');
					$present_members = $this->request->getPost('present_member_id');
					$absent_members = $this->request->getPost('absent_members');
					$reasons = $this->request->getPost('reasons');

					if(empty($present_members)){
						echo $this->Crud->msg('danger', 'Select Members Present in Service');
						die;
					}


					$attendant = json_decode($this->Crud->read_field('id', $attendance_id, 'service_report', 'attendant'));
					if (isset($attendant->attendant) && !empty($attendant->attendant)) {
						$indexToRemove = 'attendant'; // Change this to the actual index you want to delete
					
						if (isset($attendant[$indexToRemove])) {
							unset($attendant[$indexToRemove]);
						}
					}
					
					$ats = [];
					$present = [];
					foreach($present_members as $pre => $pmembers){
						$presents['id'] = $pmembers;
						$presents['status'] = 'present';
						$presents['reason'] = '';
						$present[] = $presents;
					}

					$ats['present'] = $present;

					$absent = [];
					if(!empty($absent_members)){
						foreach($absent_members as $ab => $amembers){
							$absents['id'] = $amembers;
							$absents['status'] = 'absent';
							$absents['reason'] = $reasons[$ab];
							$absent[] = $absents;
						}
					}
					$ats['absent'] = $absent;

					if(empty($attendant)){
						$attendant['list'] = $ats;
					} else{
						$attendant->list = $ats;
					}
					
					// print_r($attendant);
					// die;
					$in_data['attendant'] = json_encode($attendant); 
					
					
					if($this->Crud->updates('id', $attendance_id, 'service_report', $in_data) > 0){
						echo $this->Crud->msg('success', 'Service Attendance Submitted');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$service_date = $this->Crud->read_field('id', $attendance_id, 'service_report', 'date');
						$action = $by.' updated Service Attendane Report for '.$service_date;
						$this->Crud->activity('service', $attendance_id, $action);

						// echo json_encode($data);
						echo '<script> setTimeout(function() {
							$("#show").show(500);
								$("#form").hide(500);
								$("#mark_attendance_view").hide(500);
								$("#attendance_prev").hide(500);
								$("#add_btn").show(500);
								
								$("#prev").hide(500);
								load();
								$("#mark_attendance_msg").html("");
						}, 2000); </script>';
					} else {
						echo $this->Crud->msg('info', 'No Changes to Service Attendance');
					
					}
					
					
					die;
				}

			} elseif($param2 == 'partnership'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$total_part = 0;
					$member_part = 0;
					$guest_part = 0;
					$id = 0;
					$guest_partners = [];
					$member_partners = [];
					if(!empty($edit)) {
						foreach($edit as $e) {
							$id = $e->id;
							$partners = ($e->partners);
							if(empty($partners)){
								$partners = "[]";
							} else{
								$parts = json_decode($partners);
								$total_part = $parts->total_part;
								$member_part = $parts->member_part;
								$guest_part = $parts->guest_part;
								if(!empty($parts->partnership)){
									foreach($parts->partnership as $p => $pval) {
										if($p == 'guest'){
											$guest_partners = $pval;
										}
										if($p == 'member'){
											$member_partners = $pval;
										}
									}
								}
							}
						}
					}
					
					$resp['id'] = $id;
					$resp['guest_partners'] = json_encode($guest_partners);
					$resp['member_partners'] = json_encode($member_partners);
					$resp['total_part'] = $total_part;
					$resp['member_part'] = $member_part;
					$resp['guest_part'] = $guest_part;
					echo json_encode($resp);
					die;
				}
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					
					$partnership_id = $this->request->getPost('partnership_id');
					$total_part = $this->request->getPost('total_part');
					$member_part = $this->request->getPost('member_part');
					$guest_part = $this->request->getPost('guest_part');
					$first_timer = $this->request->getPost('first_timer');
					$members = $this->request->getPost('members');
					
					$partner = [];
					$partss = $this->Crud->read_order('partnership', 'name', 'asc');

					if(!empty($first_timer)){
						for($i=0;$i<count($first_timer);$i++){
							$name = $first_timer[$i];
							
							if(!empty($partss)){
								foreach($partss as $index => $pp){
									
									$amount = $this->request->getPost($pp->id.'_first'); //Guest Partners
									if($amount[$i] <= 0)continue;
									$parts[$pp->id] = $amount[$i];
									
									
								}
							}
							
							$partner[$name] = $parts;
						}
					}

					$partnerships['guest'] = $partner;
					
					$pmember = [];$par = [];
					if(count($members) == 0){
						echo $this->Crud->msg('danger', 'Select a Member and Enter the Partnership Amount');
						die;
					} else {
						for($i=0;$i<count($members);$i++){
							$name = $members[$i];
							
							if(!empty($partss)){
								foreach($partss as $index => $pp){
									
									$member_amount = $this->request->getPost($index.'_member'); //Guest Partners
									// echo ($member_amount[$index].' ');
									
									if(!empty($member_amount[$i])){
										if($member_amount[$i] <= 0)continue;
										$par[$pp->id] = $member_amount[$i];
									}
									
								}
							}
							// 
							$pmember[$name] = $par;
						}
					}
					$partnerships['member'] = $pmember;
					// print_r($partnerships);
					
					$partnership = json_encode($partnerships);
					$guest_part = $this->request->getPost('guest_part');
					$total_part = $this->request->getPost('total_part');
					$member_part = $this->request->getPost('member_part');

					$partners['partnership'] = $partnerships;
					$partners['guest_part'] = $guest_part;
					$partners['total_part'] = $total_part;
					$partners['member_part'] = $member_part;
					
					
					$ins['partners'] = json_encode($partners);
					$ins['partnership'] = $total_part;

					if($this->Crud->updates('id',  $partnership_id, 'service_report', $ins) > 0){

						echo $this->Crud->msg('success', 'Partnership Report Submitted');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$service_date = $this->Crud->read_field('id', $partnership_id, 'service_report', 'date');
						$action = $by.' updated Service Partnership Report for '.$service_date;
						$this->Crud->activity('service', $partnership_id, $action);

						// echo json_encode($data);
						echo '<script> setTimeout(function() {
							$("#show").show(500);
								$("#form").hide(500);
								$("#partnership_view").hide(500);
								$("#attendance_prev").hide(500);
								$("#add_btn").show(500);
								
								$("#prev").hide(500);
								load();
								$("#tithe_msg").html("");
						}, 2000); </script>';
					} else{
						echo $this->Crud->msg('info', 'No Changes');
						
					}
					die;
				}

			} elseif($param2 == 'media'){
				if($param3) {
					//When Adding Save in Session
					if($this->request->getMethod() == 'post'){
						$service_id = $param3;
						$church_id = $this->Crud->read_field('id', $service_id, 'service_report',  'church_id');

							//// Image upload
						if (file_exists($this->request->getFile('file'))) {
							
							$path = 'assets/uploads/gallery/church/'.$church_id.'/';
							$file = $this->request->getFile('file');
							if (!is_dir($path)) {
								// Create the directory
								if (mkdir($path, 0755, true)) {
								}
							} 
							$getImg = $this->Crud->file_upload($path, $file);

							if (!empty($getImg->path)) $img_id = $getImg->path;
						}


						if(empty($img_id)){
							echo $this->Crud->msg('warning', 'Select a File');
							die;
						}
						
						
							
						$ins_data['type_id'] = $service_id;
						$ins_data['type'] = 'service';
						$ins_data['user_id'] = $log_id;
						$ins_data['path'] = $img_id;
						$ins_data['reg_date'] = date(fdate);

						if($this->Crud->create('service_media', $ins_data) > 0){

							echo $this->Crud->msg('success', 'Service Media Uploaded');
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$service_date = $this->Crud->read_field('id', $service_id, 'service_report', 'date');
							$action = $by.' Uploaded a Media for Service '.$service_date;
							$this->Crud->activity('service', $service_id, $action);
	
							// echo json_encode($data);
							echo '<script> setTimeout(function() {
								media_report('.$service_id.')
							}, 2000); </script>';
						} else{
							echo $this->Crud->msg('info', 'No Changes');
							
						}
						die;
					}
					
				}
				
			

			} elseif($param2 == 'tithe'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
					$this->session->set('service_church_id', $church_id);
							
					if(!empty($edit)) {
						$total_tithe = 0;
						$member_tithe = 0;
						$guest_tithe = 0;
						$tithe_list = 0;
						
						foreach($edit as $e) {
							$tithers = json_decode($e->tithers);
							if(!empty($tithers)){
								foreach($tithers as $ti => $tv){
									if($ti == 'total'){
										$total_tithe = $tv;
									}
									if($ti == 'member'){
										$member_tithe = $tv;
									}
									if($ti == 'guest'){
										$guest_tithe = $tv;
									}
									if($ti == 'list'){
										$tithe_list = $tv;
									}
								}
							}
						}

						$resp['tithe_id'] = $param3;
						$resp['tithe_list'] = $tithe_list;
						$resp['total_tithe'] = $total_tithe;
						$resp['member_tithe'] = $member_tithe;
						$resp['guest_tithe'] = $guest_tithe;
						
						echo json_encode($resp);
						die;
					}
					
				}
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$tithe_id = $this->request->getPost('tithe_id');
					$guest_tithe = $this->request->getPost('guest_tithe');
					$total_tithe = $this->request->getPost('total_tithe');
					$member_tithe = $this->request->getPost('member_tithe');

					$member = $this->request->getPost('members');
					$tithe = $this->request->getPost('tithe');
					

					$tither = [];
					if (!empty($member) && !empty($tithe)) {
						$count = count($tithe); 
						for ($i = 0; $i < $count; $i++) {
							if ($tithe[$i] <= 0) {
								continue; 
							}
							
							if (!isset($tither[$member[$i]])) {
								$tither[$member[$i]] = $tithe[$i];
							}
							
						}
					}

					$tithe_list['total'] = $total_tithe;
					$tithe_list['member'] = $member_tithe;
					$tithe_list['guest'] = $guest_tithe;
					$tithe_list['list'] = $tither;
					 
					
					$tithers =  json_encode($tithe_list);
					$ins['tithers'] = $tithers;
					$ins['tithe'] = $total_tithe;

					if($this->Crud->updates('id', $tithe_id, 'service_report', $ins) > 0){
						echo $this->Crud->msg('success', 'Service Tithe Report Submitted');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$service_date = $this->Crud->read_field('id', $tithe_id, 'service_report', 'date');
						$action = $by.' updated Service Tithe Report for '.$service_date;
						$this->Crud->activity('service', $tithe_id, $action);

						// echo json_encode($data);
						echo '<script> setTimeout(function() {
							$("#show").show(500);
								$("#form").hide(500);
								$("#tithe_view").hide(500);
								$("#attendance_prev").hide(500);
								$("#add_btn").show(500);
								
								$("#prev").hide(500);
								load();
								$("#tithe_msg").html("");
						}, 2000); </script>';
					} else {
						echo $this->Crud->msg('info', 'No Changes');
					
					}
					die;
				}

			} elseif($param2 == 'offering'){
				$timer_count = $this->session->get('service_timers');
				// $first = json_decode($timer_count);
				// echo $timer_count;
				$data['first'] = $timer_count;
				if($param3){
					$data['table_rec'] = 'service/report/offering_list/'.$param3; // ajax table
				
				} else {
					$data['table_rec'] = 'service/report/offering_list'; // ajax table
				
				}
				$data['order_sort'] = '0, "asc"'; // default ordering (0, 'asc')
				$data['no_sort'] = '1'; // sort disable columns (1,3,5)
		
				if($param3) {
					$edit = $this->Crud->read2('type_id', $param3, 'type', 'cell', 'attendance');
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
							$data['d_attendant'] = $e->attendant;
						}
					}
					
				}
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
					 
					
					$this->session->set('service_offering', json_encode($offering_list));
					
					echo $this->Crud->msg('success', 'Service Offering Report Submitted');
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
				if($param3){
					$resp = [];
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$partners = [];
					$id = 0;
					if(!empty($edit)) {
						foreach($edit as $e) {
							$converts = $e->converts;
							if(empty($converts)){
								$converts = "[]";
							}
							$id = $e->id;
						}
						
					}

					$resp['convert_list'] =  $converts;
					$resp['id'] = $id;

					echo json_encode($resp);
					die;
				}
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$first_name = $this->request->getPost('first_name');
					$new_convert_id = $this->request->getPost('new_convert_id');
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
						$ins['converts'] = json_encode($convert);
						$ins['new_convert'] = count($first_name);

						if($this->Crud->updates('id', $new_convert_id, 'service_report', $ins) > 0){
							
							echo $this->Crud->msg('success', 'New Convert List Submitted');
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$service_date = $this->Crud->read_field('id', $new_convert_id, 'service_report', 'date');
							$action = $by.' updated Service New Convert Report for '.$service_date;
							$this->Crud->activity('service', $new_convert_id, $action);

							// echo json_encode($data);
							echo '<script> setTimeout(function() {
								$("#show").show(500);
									$("#form").hide(500);
									$("#new_convert_view").hide(500);
									$("#attendance_prev").hide(500);
									$("#add_btn").show(500);
									
									$("#prev").hide(500);
									load();
									$("#new_convert_msg").html("");
							}, 2000); </script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}

					}
					die;
				}
			

			} elseif($param2 == 'first_timer'){
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

					$resp['timer_list'] =  $timers;
					$resp['id'] = $id;

					echo json_encode($resp);
					die;
				}
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$new_convert_id = $this->request->getPost('new_convert_id');
					$first_name = $this->request->getPost('first_name');
					$surname = $this->request->getPost('surname');
					$email = $this->request->getPost('email');
					$phone = $this->request->getPost('phone');
					$dob = $this->request->getPost('dob');
					$gender = $this->request->getPost('gender');
					$family_position = $this->request->getPost('family_position');
					$invited_by = $this->request->getPost('invited_by');
					$channel = $this->request->getPost('channel');
					$member_id = $this->request->getPost('member_id');
					
					$converts = [];
					$timers = [];
					$male = 0;$female = 0;$children = 0;
					if(!empty($first_name) || !empty($surname)){
						for($i=0;$i<count($first_name);$i++){
							$invites = $member_id[$i];
							if($invited_by[$i] != 'Member'){
								$invites = $channel[$i];
							}

							if($gender[$i] == 'Male')$male++;
							if($gender[$i] == 'Female')$female++;
							if($family_position[$i] == 'Child')$children++;
							
							$converts['fullname'] = $first_name[$i].' '.$surname[$i];
							$converts['email'] = $email[$i];
							$converts['phone'] = $phone[$i];
							$converts['gender'] = $gender[$i];
							$converts['family_position'] = $family_position[$i];
							$converts['dob'] = $dob[$i];
							$converts['invited_by'] = $invited_by[$i];
							$converts['channel'] = $invites;
							
							$convert[] = $converts;
						}
					}
					
					$timers['timers'] = json_encode($convert);
					$timers['first_timer'] = count($convert);
					
					if(empty($convert)){
						echo $this->Crud->msg('danger', 'Enter the First Timer Details');
						
					} else{
						if($this->Crud->updates('id', $new_convert_id, 'service_report', $timers) > 0){
							echo $this->Crud->msg('success', 'First Timer List Submitted');
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$service_date = $this->Crud->read_field('id', $new_convert_id, 'service_report', 'date');
							$action = $by.' updated Service First Timer Report for '.$service_date;
							$this->Crud->activity('service', $new_convert_id, $action);

							// echo json_encode($data);
							echo '<script> setTimeout(function() {
								$("#show").show(500);
									$("#form").hide(500);
									$("#first_timer_view").hide(500);
									$("#attendance_prev").hide(500);
									$("#add_btn").show(500);
									
									$("#prev").hide(500);
									load();
									$("#first_timer_msg").html("");
							}, 2000); </script>';
						} else {
							
							echo $this->Crud->msg('info', 'No Changes');

						}
						// echo json_encode($convert);
						echo '<script> setTimeout(function() {
							var jsonData = ' . json_encode($convert) . ';
							var jsonString = JSON.stringify(jsonData);
							$("#timers").val(jsonString);
							$("#first_timer").val('.count($first_name).');
							$("#modal").modal("hide");
						}, 2000); </script>';
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
					$report_id = $this->request->getVar('report_id');
					$type = $this->request->getVar('type');
					$attendance = $this->request->getVar('attendance');
					$new_convert = $this->request->getVar('new_convert');
					$first_timer = $this->request->getVar('first_timer');
					$tithe = $this->request->getVar('tithe');
					$partnership = $this->request->getVar('partnership');
					$tither = $this->request->getVar('tither');
					$offering_givers = $this->request->getVar('offering_givers');
					$partners = $this->request->getVar('partners');
					$offering = $this->request->getVar('offering');
					$note = $this->request->getVar('note');
					$date = $this->request->getVar('dates');
					$attendant = $this->request->getVar('attendant');
					$converts = $this->request->getVar('converts');
					$timers = $this->request->getVar('timers');
					$ministry_id = $this->request->getVar('ministry_id');
					$church_id = $this->request->getVar('church_id');
					if(empty($church_id) || empty($ministry_id)){
						$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
						$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
						
					}
					// echo $date;die;
					$dates = date('y-m-d', strtotime($date));

					
					$ins_data['tithers'] = $tither;
					$ins_data['type'] = $type;
					$ins_data['date'] = $dates;
					$ins_data['attendance'] = $attendance;
					$ins_data['new_convert'] = $new_convert;
					$ins_data['first_timer'] = $first_timer;
					$ins_data['offering'] = $offering;
					$ins_data['note'] = $note;
					$ins_data['partnership'] = $partnership;
					$ins_data['attendant'] = $attendant;
					$ins_data['converts'] = $converts;
					$ins_data['timers'] = $timers;
					$ins_data['tithe'] = $tithe;
					$ins_data['partners'] = $partners;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = $church_id;
					$ins_data['offering_givers'] = $offering_givers;
			
					

					// do create or update
					if($report_id) {
								
						$upd_rec = $this->Crud->updates('id', $report_id, $table, $ins_data);
						if($upd_rec > 0) {
							$this->session->set('service_attendance', '');
							$this->session->set('service_partnership', '');
							$this->session->set('service_converts', '');
							$this->session->set('service_timers', '');
							$this->session->set('service_tithe', '');
							$this->session->set('service_offering', '');
							$this->session->set('service_timer_count', '');

							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$action = $by.' updated Service Meeting Report';
							$this->Crud->activity('user', $report_id, $action);

							echo $this->Crud->msg('success', 'Report Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						// echo $date;
						if($this->Crud->check2('type', $type, 'date', $dates, $table) > 0) {
							echo $this->Crud->msg('warning', 'Report Already Exist');
						} else {
							
							$ins_data['reg_date'] = date(fdate);
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {

									
								if(!empty($timers)){
									//Create membership
									$first = json_decode($timers);
									if(!empty($first)){
										foreach ($first as $key => $value) {
											if(!empty($value)){
												foreach ($value as $keys => $values) {
													if($keys == 'fullname'){
														$words = explode(" ", $values);
														$fullname = $values;
														// Get the last word
														$surname = array_pop($words);
								
														// Reassemble the remaining words
														$first_name = implode(" ", $words);
														
													}
													
													if($keys == 'email')$email = $values;
													if($keys == 'phone')$phone = $values;
													if($keys == 'gender')$gender = $values;
													if($keys == 'family_position')$family_position = $values;
													if($keys == 'dob')$dob = $values;

												}
												$title = 'Brother';
												if($gender == 'Female')$title = 'Sister';
												$uData['firstname'] = $first_name;
												$uData['surname'] = $surname;
												$uData['email'] = $email;
												$uData['phone'] = $phone;
												
												$uData['gender'] = $gender;
												$uData['dob'] = $dob;
												$uData['activate'] = 0;
												$role_ids = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
												$uData['role_id'] = $role_ids;
												$uData['title'] = $title;
												$uData['reg_date'] = date(fdate);
												

												

												if($this->Crud->check('email', $email, 'user') > 0 || $this->Crud->check('phone', $phone, 'user') > 0){
													echo $this->Crud->msg('warning', 'Email/Phone Number Already Exisit');
												} else {
													$Urec = $this->Crud->create('user', $uData);
													if($Urec > 0) {

														///// store activities
														$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
														$code = $this->Crud->read_field('id', $Urec, 'user', 'surname');
														$this->Crud->updates('id', $Urec, 'user', array('user_no'=>'CEAM-00'.$Urec));

														$user_no = 'CEAM-00'.$Urec;

														if(!empty($partners)){
															$partners = (array)json_decode($partners);
															$part = $partners['partnership'];
															$part = ((array)$part);
															$guest = (array)($part['guest']);
															if(!empty($guest)){
																foreach ($guest as $Gkey => $Gvalue) {
																	if($Gkey == $fullname){
																		$uid = $this->Crud->read_field('email', $email, 'user', 'id');
																		if(!empty($Gvalue)){
																			foreach($Gvalue as $gb => $gamount){
																				$p_ins['member_id'] = $uid;
																				$p_ins['partnership_id'] = $gb;
																				$p_ins['amount_paid'] = $gamount;
																				$p_ins['reg_date'] = date(fdate);
																				$p_ins['status'] = 1;
																				$p_ins['date_paid'] = $dates;
					
																				if($this->Crud->check2('member_id', $uid, 'date_paid', $dates, 'partners_history') == 0){
																					$this->Crud->create('partners_history', $p_ins);
																				}
																			}
																		}
																		
																	}
																}
															}
														}
														
													}
													
												}
												
											}
										}
									}
								}

								//Make Partnership Payment
								if(!empty($partners)){
									$partners = (array)json_decode($partners);
									$part = $partners['partnership'];
									$part = ((array)$part);
									$member = (array)($part['member']);
									if(!empty($member)){
										foreach ($member as $Gkey => $Gvalue) {
											if(!empty($Gvalue)){
												foreach($Gvalue as $gb => $gamount){
													$p_ins['member_id'] = $Gkey;
													$p_ins['partnership_id'] = $gb;
													$p_ins['amount_paid'] = $gamount;
													$p_ins['reg_date'] = date(fdate);
													$p_ins['status'] = 1;
													$p_ins['date_paid'] = $dates;

													if($this->Crud->check3('member_id', $Gkey, 'partnership_id', $gb, 'date_paid', $dates, 'partners_history') == 0){
														$this->Crud->create('partners_history', $p_ins);
													}
												}
											}
												
											
										}
									}
								}
								$this->session->set('service_attendance', '');
								$this->session->set('service_partnership', '');
								$this->session->set('service_converts', '');
								$this->session->set('service_timers', '');
								$this->session->set('service_offering', '');
								$this->session->set('service_tithe', '');
								$this->session->set('service_timer_count', '');
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$action = $by.' created a Service Report for ('.$date.')';
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

		if($param1 == 'church_select'){
			$church_id = $this->request->getPost('church_id');
			if($church_id){
				$this->session->set('service_church_id', $church_id);
			}
		}

		if($param1 == 'records'){
			if($param2 == 'get_ministry'){
				$e_ministry_id = $this->request->getPost('ministry_id');
				$ministry = $this->Crud->read_order('ministry', 'name', 'asc');
				if(!empty($ministry)){
					$data = [];
					foreach($ministry as $min){
						$selected = '';
						if(!empty($e_ministry_id)){
							if($min->id == $e_ministry_id){
								$selected = 'selected';
							}
						}
						$data[] = array('id' => $min->id, 'name' => $min->name, 'selected' => $selected);
					}
				}
				echo json_encode($data);
				die;
			}

			if($param2 == 'get_church_level'){
				$e_level = $this->request->getPost('level');
				$log_church_id = $this->Crud->read_field('id', $log_id, 'user',  'church_id');
				$data = [];
				$selected = '';
				if($log_church_id > 0){
					$data[] = array('id' => $log_church_id, 'name' => 'My Church', 'selected' => $selected);
				}
				$log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

				if($log_church_type == 'region'){
					$data[] = array('id' => 'zone', 'name' => 'Zonal Church', 'selected' => $selected);
					$data[] = array('id' => 'group', 'name' => 'Group Church', 'selected' => $selected);
					$data[] = array('id' => 'church', 'name' => 'Church Assembly', 'selected' => $selected);
					
				} elseif($log_church_type == 'zone'){
					$data[] = array('id' => 'group', 'name' => 'Group Church', 'selected' => $selected);
					$data[] = array('id' => 'church', 'name' => 'Church Assembly', 'selected' => $selected);
					
				} elseif($log_church_type == 'group'){
					$data[] = array('id' => 'church', 'name' => 'Church Assembly', 'selected' => $selected);
					
				} else{
					$data[] = array('id' => 'region', 'name' => 'Regional Church', 'selected' => $selected);
					$data[] = array('id' => 'zone', 'name' => 'Zonal Church', 'selected' => $selected);
					$data[] = array('id' => 'group', 'name' => 'Group Church', 'selected' => $selected);
					$data[] = array('id' => 'church', 'name' => 'Church Assembly', 'selected' => $selected);
					
				}
					
				echo json_encode($data);
				die;
			}

			if($param2 == 'get_church'){
				
				if($param3){
					$data = [];
					$church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
					$timers = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'timers'));
					$members = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');
					if(!empty($members)){
						foreach($members as $member){
							$selected = '';
							if(!empty($timers)){
								foreach($timers as $time => $val){
									if($val->channel == $member->id){
										$selected = 'selected';
									}
								}
							}
							$data[] = array('id' => $member->id, 'name' => $member->firstname.' '.$member->surname, 'selected' => $selected);
					

						}
					}
					echo json_encode($data);
					die;
				}
					
				
			}

			if($param2 == 'getFirstTimers'){
				
				if($param3){
					$data = [];
					
					$timers = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'timers'));
					
					if(!empty($timers)){
						foreach($timers as $time => $val){
							$name = $val->fullname;
							$phone = $val->phone;
							
							$data[] = array('id' => $name, 'phone' => $phone);
						}
					}
					
					

					echo json_encode($data);
					die;
				}
					
				
			}

			if($param2 == 'get_service_partnership'){
				
				if($param3){
					$data = [];
					$name = $this->request->getPost('name');
					$partners = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'partners'));
					$partnership = $this->Crud->read_order('partnership', 'name', 'asc');
					if(!empty($partnership)){
						foreach($partnership as $p){
							$amount = 0;
							$pid = $p->id;
							if(!empty($partners)){
								foreach($partners as $time => $val){
									if($time == 'partnership'){
										$guest= $val->guest;
										if(!empty($guest)){
											foreach($guest as $g => $gpal){
												// echo strtoupper($g).' '.ucwords($name);
												if(strtoupper($g) == $name){
													
													$gpals = (array)$gpal;
													foreach($gpals as $gp => $gpl){
														if($p->id == $gp){
															$amount = $gpl;
														}
													}
													
												}
											}
										}
										
									}
									
								}
							}
							$data[] = array('id' => $pid,'amount'=> $amount);


						}
					}

					echo json_encode($data);
					die;
				}
					
				
			}
			
			if($param2 == 'get_members_partnership'){
				if($param3){
					$data = [];
					$name = $this->request->getPost('name');
					$partners = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'partners'));
					$church_id = ($this->Crud->read_field('id', $param3, 'service_report', 'church_id'));
					$church = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');

					$church_memberss = [];
					$partnerships = [];
					$count = 0;
					if(!empty($church)){
						foreach($church as $c){
							$church_members['id'] = $c->id;
							$church_members['phone'] = $c->phone;
							$church_members['fullname'] = strtoupper($c->firstname.' '.$c->surname);

							$church_memberss[] = $church_members;
						}
					}
					$partnership = $this->Crud->read_order('partnership', 'name', 'asc');
					if(!empty($partnership)){
						foreach($partnership as $p){
							$partnerships[] = $p->id;
						}
					}
					$table = '';
					if (!empty($partners)) {
						foreach ($partners as $time => $val) {
							if ($time === 'partnership') {

								$member = $val->member;
								if (!empty($member)) {
									foreach ($member as $g => $gpal) {
										$memberIds = array_column($church_memberss, 'id'); // Extract the IDs from the church members array
										// print_r($church_memberss);
										if (in_array($g, $memberIds)) {
											
											// Removing member with ID $g
											foreach ($church_memberss as $key => $member) {
												if ($member['id'] === $g) {
													unset($church_memberss[$key]); // Remove the member
													break; // Exit the loop after removal
												}
											}

											// Resetting array keys to ensure proper indexing (optional)
											$church_memberss = array_values($church_memberss);


											$fullname = $this->Crud->read_field('id', $g, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $g, 'user', 'surname');
											$phone = $this->Crud->read_field('id', $g, 'user', 'phone');
					
											$table .= '<tr class="original-rows">
												<td>
													<input type="hidden" readonly class="form-control members" name="members[]" value="' . htmlspecialchars($g) . '">
													<span class="small">' . htmlspecialchars(strtoupper($fullname)) . ' - ' . htmlspecialchars($phone) . '</span>
												</td>';
					
											$gpals = (array)$gpal;
					
											if (!empty($partnership)) {
												foreach ($partnership as $p) {
													// Initialize the amount to 0
													$amount = 0;
					
													// Check if the partnership ID exists in the gpals
													if (array_key_exists($p->id, $gpals)) {
														$amount = $gpals[$p->id]; // Get the corresponding amount
													}
					
													$table .= '
														<td>
															<input type="text" style="width:100px;" class="form-control members_amount" oninput="bindInputEvents();" name="' . htmlspecialchars($p->id) . '_member[]" value="' . htmlspecialchars($amount) . '">
														</td>
													';
												}
											}
					
											$table .= '</tr>'; // Close the table row
											$count++;
										}
									}
								}
							}
						}
					}
					
								
					$data['partnerships'] = ($partnerships);
					$data['members'] = ($church_memberss);
					$data['members_part'] = $table;



					echo json_encode($data);
					die;
				}
					
				
			}

			if($param2 == 'get_members_tithe'){
				if($param3){
					$data = [];
					$tithersa = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'tithers'));
					$church_id = ($this->Crud->read_field('id', $param3, 'service_report', 'church_id'));
					$church = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');

					$church_memberss = [];
					$count = 0;
					if(!empty($church)){
						foreach($church as $c){
							$church_members['id'] = $c->id;
							$church_members['phone'] = $c->phone;
							$church_members['fullname'] = strtoupper($c->firstname.' '.$c->surname);

							$church_memberss[] = $church_members;
						}
					}
					

					
					$table = '';
					$tithers = [];
					if(!empty($tithersa) && isset($tithersa->list)){
						$tithers = (array)$tithersa->list;
					}
					

					
					// print_r($tithers);
					if(!empty($tithers)){
						$tither_ids = array_keys($tithers);

						// Filter out church members who are also tithers
						$church_memberss = array_filter($church_memberss, function($member) use ($tither_ids) {
							return !in_array($member['id'], $tither_ids);
						});

					$church_memberss = array_values($church_memberss);

						foreach($tithers as $tither => $tithe){
							$fullname = $this->Crud->read_field('id', $tither, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $tither, 'user', 'surname');
							$phone = $this->Crud->read_field('id', $tither, 'user', 'phone');
	
							$table .= '<tr>
								<td>
									<input type="hidden" readonly class="form-control members" name="members[]" value="' . htmlspecialchars($tither) . '">
									<span class="small">' . htmlspecialchars(strtoupper($fullname)) . ' - ' . htmlspecialchars($phone) . '</span>
								</td>

								<td>
									<input type="text" class="form-control tithes" name="tithe[]" 
										oninput="calculateTotal(); this.value = this.value.replace(/[^0-9]/g, \'\');" 
										value="' . htmlspecialchars($tithe) . '">
								</td>
							</tr>';

						}
					}
								
					$data['members'] = ($church_memberss);
					$data['members_part'] = $table;



					echo json_encode($data);
					die;
				}
					
				
			}

			
			if($param2 == 'get_members_attendance'){
				if($param3){
					$data = [];
					$attendant = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'attendant'));
					$church_id = ($this->Crud->read_field('id', $param3, 'service_report', 'church_id'));
					$church = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');


					$church_memberss = [];
					$count = 0;
					if(!empty($church)){
						foreach($church as $c){
							$church_members['id'] = $c->id;
							$church_members['phone'] = $c->phone;
							$church_members['fullname'] = strtoupper($c->firstname.' '.$c->surname);

							$church_memberss[] = $church_members;
						}
					}
					

					// Extract IDs from the attendant list
					$attendant_ids = [];
					if (isset($attendant->list) && !empty($attendant->list)) {
						foreach ($attendant->list as $at => $attend) {
							if (!empty($attend)) {
								foreach ($attend as $at_val) {
									$attendant_ids[] = $at_val->id; // Collecting all IDs
								}
							}
						}
					}

					// Remove church members whose IDs are in the attendant list
					foreach ($church_memberss as $key => $member) {
						if (in_array($member['id'], $attendant_ids)) {
							unset($church_memberss[$key]); // Remove the member
						}
					}

					
					$table = '';

					if(isset($attendant->list) && !empty($attendant->list)){
						$attendants = $attendant->list;
						// print_r($attendants);

						foreach($attendants as $at => $attend){
							if($at == 'present'){
								if(!empty($attend)){
									foreach($attend as $at_index => $at_val){
										$id = $at_val->id;
										$name = $this->Crud->read_field('id', $id, 'user', 'firstname').' '.$this->Crud->read_field('id', $id, 'user', 'surname'); 
										$reason = $at_val->reason;
										$to = 'data-bs-toggle="collapse" ';
										$ico = '<span class="accordion-icon"></span>';
										if(empty($reason)){
											$reason = '';$to ='';$ico = '';
										}
										$table .= '
											<div class="col-sm-4 mb-3">
												<div id="accordion-'.$id.'" class="accordion accordion-s3">
													<div class="accordion-item"> <a href="javascript:;" class="accordion-head collapsed" '.$to.'
															data-bs-target="#accordion-item-'.$id.'-1">
															<h6 class="title">'.ucwords($name).' <span class="badge bg-success">Present</span></h6>'.$ico.' 
														</a>
														<div class="accordion-body collapse" id="accordion-item-'.$id.'-1"
															data-bs-parent="#accordion-'.$id.'">
															<div class="accordion-inner">
																<p>'.ucwords($reason).'</p>
															</div>
														</div>
													</div>
												</div>
											</div>
										';

									}
								}
								
							}

							if($at == 'absent'){
								if(!empty($attend)){
									foreach($attend as $at_index => $at_val){
										$id = $at_val->id;
										$name = $this->Crud->read_field('id', $id, 'user', 'firstname').' '.$this->Crud->read_field('id', $id, 'user', 'surname'); 
										$reason = $at_val->reason;
										$to = 'data-bs-toggle="collapse" ';
										$ico = '<span class="accordion-icon"></span>';
										if(empty($reason)){
											$reason = '';$to ='';$ico = '';
										}
										$table .= '
											<div class="col-sm-4 mb-3">
												<div id="accordion-'.$id.'" class="accordion accordion-s3">
													<div class="accordion-item"> <a href="javascript:;" class="accordion-head collapsed" '.$to.'
															data-bs-target="#accordion-item-'.$id.'-1">
															<h6 class="title">'.ucwords($name).' <span class="badge bg-danger">Absent</span></h6>'.$ico.' 
														</a>
														<div class="accordion-body collapse" id="accordion-item-'.$id.'-1"
															data-bs-parent="#accordion-'.$id.'">
															<div class="accordion-inner">
																<p>'.ucwords($reason).'</p>
															</div>
														</div>
													</div>
												</div>
											</div>
										';

									}
								}
							}
							
						}
					}
								
					$data['members'] = ($church_memberss);
					$data['members_part'] = $table;



					echo json_encode($data);
					die;
				}
					
				
			}

			if($param2 == 'service_media'){
				if($param3){
					$data = [];
					$url = '';
					$urls = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'video_url'));
					$media = $this->Crud->read2('type_id', $param3, 'type', 'service', 'service_media');
					$medias = '<div class="row g-gs">';
					if(!empty($media)){
						foreach($media as $m){
							$medias .= '
								
								<div class="col-sm-6 col-lg-4 col-xxl-3">
									<div class="gallery card card-bordered"><a class="gallery-image popup-image"
											href="'.site_url($m->path).'"><img class="w-100 rounded-top" height="200px"
												src="'.site_url($m->path).'" alt=""></a>
										<div
											class="gallery-body card-inner align-center justify-between flex-wrap g-2">
											<div class="user-card">
												<div class="user-avatar">CA</div>
												<div class="user-info"><span class="lead-text">CHURCH ADMIN</span></div>
											</div>
											<div>
											 	<button type="button" onclick="delete_media('.$m->id.', '.$param3.')" class="btn btn-outline-danger btn-icon"><em  class="icon ni ni-trash"></em></button>
                                            </div>
										</div>
									</div>
								</div>
							
							';
						}
					}

					
					$medias .= '</div>';

					if(!empty($urls)){
						foreach($urls as $link => $linka){
							
							$url .=  '
								<span class="tag">
									<a href="'.$linka.'" target="_blank" style="color: white;">'.$linka.'</a><span class="remove_url remove">×</span>
									<input type="hidden" value="'.$linka.'" class="video_link">
								</span>
								
							';
						}
					}

					$data['medias'] = ($medias);
					$data['url'] = $url;



					echo json_encode($data);
					die;
				}
					
				
			}
			
			if($param2 == 'delete_media'){
				if($param3){
					$this->Crud->deletes('id', $param3, 'service_media');
					die;
				}
			}

			
			if($param2 == 'add_url'){
				if ($param3) {
					// Retrieve the existing URLs from the database
					$existing = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'video_url'), true) ?: [];
				
					// Get the new URL from the request
					$url = $this->request->getPost('url');
				
					// Ensure the URL is not empty before adding
					if (!empty($url)) {
						// Add the new URL to the existing array if it doesn't already exist
						if (!in_array($url, $existing)) {
							$existing[] = $url; 
						}
					}
				
					// Prepare the updated data for the database
					$ins['video_url'] = json_encode($existing);
					
					// Update the record in the database
					$this->Crud->updates('id', $param3, 'service_report', $ins);
					die;
				}
				
				
			}

			if ($param2 == 'delete_url') {
				if ($param3) {
					// Retrieve the existing URLs from the database
					$existing = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'video_url'), true) ?: [];
			
					// Get the URL to delete from the request
					$urlToDelete = $this->request->getPost('url');
			
					// Ensure the URL to delete is not empty
					if (!empty($urlToDelete)) {
						// Remove the URL from the existing array if it exists
						$existing = array_filter($existing, function($url) use ($urlToDelete) {
							return $url !== $urlToDelete; // Keep URLs that are not equal to the one to delete
						});
					}
			
					// Prepare the updated data for the database
					$ins['video_url'] = json_encode(array_values($existing)); // Re-index the array
			
					// Update the record in the database
					$this->Crud->updates('id', $param3, 'service_report', $ins);
					
					// Optionally return a response
					echo json_encode(['success' => true, 'message' => 'URL successfully deleted']);
					die;
				}
			}
			
		}

		if($param1 == 'load_churches'){
			$level = $this->request->getPost('level');
			$ministry_id = $this->request->getPost('ministry_id');
			$church_list = array();
			
			$church = $this->Crud->read2_order('ministry_id', $ministry_id, 'type', $level, 'church', 'name', 'asc');
			if(!empty($church)){
				foreach($church as $ch){
					$list['id'] = $ch->id;
					$list['name'] = ucwords($ch->name.' - '.$ch->type);
					$church_list[] = $list;
				}
			}


			$resp['churches'] = $church_list;
			echo json_encode($resp);
			die;
		}
		
		//Get Role
		if($param1 == 'gets'){
			$total = $this->request->getPost('total');
			$member = $this->request->getPost('member');
			$guest = $this->request->getPost('guest');
			$male = $this->request->getPost('male');
			$female = $this->request->getPost('female');
			$children = $this->request->getPost('children');
			$vals = $this->request->getPost('vals');
			$applicants = $this->request->getPost('applicant');
			
			$applicant = json_decode($applicants);
			// print_r($applicant);
			$service = [];
			$service_total = [];
			if($vals){
				$total += 1;
				$member += 1;
				if($this->Crud->check2('id', $param2, 'gender', 'Male', 'user') > 0)$male += 1;
				if($this->Crud->check2('id', $param2, 'gender', 'Female', 'user') > 0)$female += 1;
				if($this->Crud->check2('id', $param2, 'family_position', 'Child', 'user') > 0)$children += 1;
				
				if(!empty($applicant)){
					$applicant[] = $param2;
				} else {
					$applicant[] = $param2;
				}
				
			} else {
				$total -= 1;
				$member -= 1;
				if($this->Crud->check2('id', $param2, 'gender', 'Male', 'user') > 0)$male -= 1;
				if($this->Crud->check2('id', $param2, 'gender', 'Female', 'user') > 0)$female -= 1;
				if($this->Crud->check2('id', $param2, 'family_position', 'Child', 'user') > 0)$children -= 1;
				
				$key = array_search($param2, $service);
				if ($key !== false) {
					unset($service[$key]);
				}

			}

			// print_r($applicant);
			$service_total['total'] = $total;
			$service_total['member'] = $member;
			$service_total['male'] = $male;
			$service_total['guest'] = $guest;
			$service_total['children'] = $children;
			$service_total['female'] = $female;
			$service_total['attendant'] = $applicant;
			

			$total = $guest + $member;
			$this->session->set('service_attendance', json_encode($service_total));
			// print_r($service_total);
			echo '
				<script>
					$("#total").val('.$total.');
					$("#member").val('.$member.');
					$("#guest").val('.$guest.');
					$("#male").val('.$male.');
					$("#female").val('.$female.');
					$("#children").val('.$children.');
					var jsonData = ' . json_encode($applicant) . ';
					var jsonString = JSON.stringify(jsonData);
					$("#applicant").val(jsonString);
					
				</script>
			';
			die;
		}
		// record listing
		if($param1 == 'list') {
			// DataTable parameters
			$table = 'user';
			$column_order = array('firstname', 'surname');
			$column_search = array('firstname', 'surname');
			$order = array('firstname' => 'asc');
			$member_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
			$where = array('role_id' => $member_id);
			
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

				$attend = $this->session->get('service_attendance');
				// print_r($attend);
				$sel = '';
				if(!empty($attend)){
					$attends = json_decode($attend);
					$ats = (array)$attends;
					foreach($ats as $a => $val){
						if($a == 'attendant'){
							// $vall = json_decode($val);
							if(in_array($item->id, (array)$val)){
								$sel = 'checked';
							}
						}
					}
					
					
				}
				$all_btn = '
					<div class="text-center">
						<div class="custom-control custom-switch">    
							<input type="checkbox" name="mark[]" class="custom-control-input" id="customSwitch'.$item->id.'" '.$sel.' onclick="marks('.$item->id.')"  value="'.$item->id.'">    
							<label class="custom-control-label" for="customSwitch'.$item->id.'">Mark</label>
						</div>
						
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
		
		if($param1 == 'tithe_list') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 200;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			$service_id = $this->request->getPost('service_id');
			$church_id = $this->Crud->read_field('id', $service_id, 'service_report', 'church_id');
			// DataTable parameters
			$table = 'user';
		
			// load data into table
			$list = $this->Crud->read2_order('is_member', 1,'church_id', $church_id, $table, 'firstname', 'asc', $limit, $offset);
			if(!empty($search)){
				$list = $this->Crud->read2_order_like('is_member', 1,'church_id', $church_id, $table, 'firstname', 'asc', 'firstname', $search, $limit, $offset);
			}
			$counts = 1;
			foreach ($list as $itema) {
				$id = $itema->id;
				$name = $itema->firstname.' '.$itema->surname;
				$surname = $itema->surname;
				$phone = $itema->phone;
				$church = $this->Crud->read_field('id', $itema->church_id, 'church', 'name');

				$img = $this->Crud->image($itema->img_id, 'big');
				// add manage buttons
				$value = '0';
				if($service_id){
					$convertsa = json_decode($this->Crud->read_field('id', $service_id, 'service_report', 'tithers'));

					$converts =(array) $convertsa->list;
					if(!empty($converts)){
						foreach($converts as $co => $val){
							if($id == $co){
								$value = $val;
							}
						}
					
					}	
				} else {
					$session_tithe = $this->session->get('service_tithe');
					if(!empty($session_tithe)){
						$convertsa = json_decode($session_tithe);
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
						<input type="text" class="form-control tithes" name="tithe[]" id="tithe_'.$itema->id.'" value="'.$value.'" oninput="calculateTotal();this.value = this.value.replace(/[^\d.]/g,\'\');this.value = this.value.replace(/(\..*)\./g,\'$1\')">
					</div>
				';

				
				$item .= '
					<tr>
						<td>

							<div class="user-card">
								<div class="user-avatar ">
									<img alt="" src="'.site_url($img).'" height="40px"/>
								</div>
								<div class="user-info">
									<span class="tb-lead small">'.ucwords($name).'</span><br>
									<span class="small text-info"><em class="icon ni ni-curve-down-right"></em>'.ucwords($church).'</span>
								</div>
								<input type="hidden" name="members[]" value="'.$itema->id.'">
							</div>
						</td>
						<td><span class="small">'.$phone.'</span></td>
						<td>'.$all_btn.'</td>
					</tr>	
				';
						
				$counts += 1;
			}
	
			if(empty($item)) {
				$resp['item'] = '
					
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
			die;

		}

		if($param1 == 'offering_list') {
			// DataTable parameters
			$table = 'user';
			$column_order = array('firstname', 'surname');
			$column_search = array('firstname', 'surname');
			$order = array('firstname' => 'asc');
			$member_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
			$where = array('role_id' => $member_id);
			
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
				if($param2){
					$convertsa = json_decode($this->Crud->read_field('id', $param2, 'service_report', 'offering_givers'));
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
					<div class="nk-tb-col"><span class="sub-text text-dark">'.translate_phrase('Service').'</span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark">'.translate_phrase('Offering').'</span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark">'.translate_phrase('Tithe').'</span></div>
					<div class="nk-tb-col"><span class="sub-text text-dark">'.translate_phrase('Partnership').'</span></div>
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
				
				$all_rec = $this->Crud->filter_service_report('', '', $search);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_service_report($limit, $offset, $search);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$type = $q->type;
						$tithe = $q->tithe;
						$partnership = $q->partnership;
						$attendance = $q->attendance;
						$offering = $q->offering;
						$new_convert = $q->new_convert;
						$first_timer = $q->first_timer;
						$date = date('d M Y', strtotime($q->date));
						$reg_date = $q->reg_date;

						$types = $this->Crud->read_field('id', $type, 'service_type', 'name');
						
						$cell='';
						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<li><a href="javascript:;" class="text-primary" onclick="edit_report('.$id.')"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" class="text-success pop" pageTitle="View Report" pageName="' . site_url($mod . '/manage/report/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								<li><a href="javascript:;" class="text-indigo" onclick="mark_attendance('.$id.')"><em class="icon ni ni-user-check"></em><span>'.translate_phrase('Mark Attendance').'</span></a></li>
								<li><a href="javascript:;" class="text-secondary" onclick="attendance_report('.$id.')"><em class="icon ni ni-users"></em><span>'.translate_phrase('Attendance Details').'</span></a></li>
								<li><a href="javascript:;" class="text-warning"  onclick="tithe_report('.$id.')"><em class="icon ni ni-money"></em><span>'.translate_phrase('Add Tithe Details').'</span></a></li>
								<li><a href="javascript:;" class="text-info" onclick="new_convert_report('.$id.')"><em class="icon ni ni-user-list"></em><span>'.translate_phrase('Add New Convert Details').'</span></a></li>
								<li><a href="javascript:;" class="text-dark" onclick="first_timer_report('.$id.')"><em class="icon ni ni-user-add"></em><span>'.translate_phrase('Add First Timer Details').'</span></a></li>
								<li><a href="javascript:;" class="text-indigo" onclick="partnership_report('.$id.')"><em class="icon ni ni-coins"></em><span>'.translate_phrase('Add Partnership Details').'</span></a></li>
								<li><a href="javascript:;" class="text-danger" onclick="media_report('.$id.')"><em class="icon ni ni-img"></em><span>'.translate_phrase('Media').'</span></a></li>
								
								
							';
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">' . ucwords($date) . ' </span>
										
									</div>
								</div>
								<div class="nk-tb-col">
									<span class="text-dark">' . ucwords($types) . '</span>
								</div>
								<div class="nk-tb-col">
									<span class="text-dark">' .curr. number_format((float)$offering,2) . '</span>
								</div>
								<div class="nk-tb-col">
									<span class="text-dark">' .curr. number_format((float)$tithe,2) . '</span>
								</div>
								<div class="nk-tb-col">
									<span class="text-dark">' .curr. number_format((float)$partnership,2) . '</span>
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
			$this->session->set('service_attendance', '');
			$data['title'] = translate_phrase('Service Report').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
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