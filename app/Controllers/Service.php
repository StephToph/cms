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
				$timer_count = $this->session->get('service_timer_count');
				
				$timer_total = 0;$timer_male = 0;$timer_female =0;$timer_child = 0;
				$timer_counts = json_decode($timer_count);
				$counts = (array)$timer_counts;
				if(!empty($counts)){
					$timer_total = $counts['total'];
					$timer_male = $counts['male'];
					$timer_child = $counts['child'];
					$timer_female = $counts['female'];
				}

				$data['timer_count'] = $timer_total;
				$data['timer_male'] = $timer_male;
				$data['timer_female'] = $timer_female;
				$data['timer_child'] = $timer_child;
				$data['table_rec'] = 'service/report/list'; // ajax table
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
					$guest = $this->request->getPost('guest');
					$total = $this->request->getPost('total');
					
					$mark = $this->session->get('service_attendance');

					
					// Decode the JSON string
					$data = json_decode($mark, true);

					// Change the values of "total" and "guest"
					$data['total'] = $total; // Change the value of "total"
					$data['guest'] = $guest; // Change the value of "guest"
					
					if(empty($data)){
						echo $this->Crud->msg('danger', 'Mark Service Attendance');
					
					} else{
						echo $this->Crud->msg('success', 'Service Attendance Submitted');
						// echo json_encode($data);
						echo '<script> setTimeout(function() {
							var jsonData = ' . json_encode($data) . ';
							var jsonString = JSON.stringify(jsonData);
							$("#attendant").val(jsonString);
							$("#attendance").val('.$total.');
							$("#modal").modal("hide");
						}, 2000); </script>';
					}
					die;
				}

			}  elseif($param2 == 'partnership'){
				$timer_count = $this->session->get('service_timers');
				// $first = json_decode($timer_count);
				// echo $timer_count;
				$data['first'] = $timer_count;
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
					
					$first_timer = $this->request->getPost('first_timer');
					$member = $this->request->getPost('members');
					$partner = [];
					$partss = $this->Crud->read_order('partnership', 'name', 'asc');

					if(!empty($first_timer)){
						for($i=0;$i<count($first_timer);$i++){
							$name = $first_timer[$i];
							
							if(!empty($partss)){
								foreach($partss as $index => $pp){
									
									$amount = $this->request->getPost($index.'_first'); //Guest Partners
									if($amount[$i] <= 0)continue;
									$parts[$pp->id] = $amount[$i];
									
									
								}
							}
							
							$partner[$name] = $parts;
						}
					}

					$partnerships['guest'] = $partner;
					
					$pmember = [];$par = [];
					if(count($member) == 0){
						echo $this->Crud->msg('danger', 'Select a Member and Enter the Partnership Amount');
						die;
					} else {
						for($i=0;$i<count($member);$i++){
							$name = $member[$i];
							
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
					
					
					$partnership = json_encode($partnerships);
					$guest_part = $this->request->getPost('guest_part');
					$total_part = $this->request->getPost('total_part');
					$member_part = $this->request->getPost('member_part');

					$partners['partnership'] = $partnerships;
					$partners['guest_part'] = $guest_part;
					$partners['total_part'] = $total_part;
					$partners['member_part'] = $member_part;
					
					$this->session->set('service_partnership', json_encode($partners));
					
					$mark = $this->session->get('service_attendance');

					// Decode the JSON string
					$data = json_decode($mark, true);

					// Change the values of "total" and "guest"
					$data['total'] = $total_part; // Change the value of "total"
					$data['guest'] = $guest_part; // Change the value of "total"
					$data['member'] = $member_part; // Change the value of "guest"
					
					if(empty($partnership)){
						echo $this->Crud->msg('danger', 'Enter Partnerships');
					
					} else{
						echo $this->Crud->msg('success', 'Partnership List Submitted');
						// echo json_encode($data);
						echo '<script> setTimeout(function() {
							var jsonData = ' . json_encode($partners) . ';
							var jsonString = JSON.stringify(jsonData);
							$("#partners").val(jsonString);
							$("#partnership").val('.($total_part).');
							$("#modal").modal("hide");
						}, 2000); </script>';
					}
					die;
				}

			} elseif($param2 == 'tithe'){
				if($param3){
					$data['table_rec'] = 'service/report/tithe_list/'.$param3; // ajax table
				
				} else {
					$data['table_rec'] = 'service/report/tithe_list'; // ajax table
				
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
					 
					// print_r($tithe_list);
					$this->session->set('service_tithe', json_encode($tithe_list));
					
					echo $this->Crud->msg('success', 'Service Tithe Report Submitted');
					// echo json_encode($mark);
					echo '<script> setTimeout(function() {
						var jsonData = ' . json_encode($tithe_list) . ';
						var jsonString = JSON.stringify(jsonData);
						$("#tither").val(jsonString);
						$("#tithe").val('.number_format($total_tithe,2).');
						$("#modal").modal("hide");
					}, 2000); </script>';
					
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
							$this->session->set('service_convert', json_encode($convert));
							echo $this->Crud->msg('success', 'New Convert List Submitted');
							// echo json_encode($mark);
							
							echo '<script> setTimeout(function() {
								var jsonData = ' . json_encode($convert) . ';
								var jsonString = JSON.stringify(jsonData);
								$("#converts").val(jsonString);
								$("#new_convert").val('.count($first_name).');
								$("#modal").modal("hide");
							}, 2000); </script>';
						}
						die;
					}
				

			}elseif($param2 == 'first_timer'){
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
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
					
					$timers['male'] = $male;
					$timers['female'] = $female;
					$timers['child'] = $children;
					$timers['total'] = count($first_name);
					
					if(empty($convert)){
						echo $this->Crud->msg('danger', 'Enter the First Timer Details');
						
					} else{
						$this->session->set('service_timers', json_encode($convert));
						$this->session->set('service_timer_count', json_encode($timers));
						
						echo $this->Crud->msg('success', 'First Timer List Submitted');
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
					$convertsa = json_decode($this->Crud->read_field('id', $param2, 'service_report', 'tithers'));
					$converts =(array) $convertsa->list;
					if(!empty($converts)){
						foreach($converts as $co => $val){
							if($id == $co){
								$value = $val;
							}
						}
					
					}	
				}
				
				$all_btn = '
					<div class="text-center">
						<input type="text" class="form-control tithes" name="tithe[]" id="tithe_'.$item->id.'" value="'.$value.'" oninput="calculateTotal();this.value = this.value.replace(/[^\d.]/g,\'\');this.value = this.value.replace(/(\..*)\./g,\'$1\')">
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
									<span class="text-dark">$' . number_format((float)$offering,2) . '</span>
								</div>
								<div class="nk-tb-col">
									<span class="text-dark">$' . number_format((float)$tithe,2) . '</span>
								</div>
								<div class="nk-tb-col">
									<span class="text-dark">$' . number_format((float)$partnership,2) . '</span>
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