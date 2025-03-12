<?php

namespace App\Controllers;

class Dashboard extends BaseController {

 
    public function index($param1='', $param2='', $param3='') {
        // check login
        $log_id = $this->session->get('td_id');
       if(empty($log_id)) return redirect()->to(site_url('auth'));

    
        $mod = 'dashboard';
        
        $switch_id = $this->session->get('switch_church_id');
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
        
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $role_c = $this->Crud->module($role_id, $mod, 'create');
        $role_r = $this->Crud->module($role_id, $mod, 'read');
        $role_u = $this->Crud->module($role_id, $mod, 'update');
        $role_d = $this->Crud->module($role_id, $mod, 'delete');
        if($role_r == 0){
            return redirect()->to(site_url('auth/profile'));	
        }
        $username = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
        
        $data['log_id'] = $log_id;
        $data['param1'] = $param1;
        $data['param2'] = $param2;
        $data['param3'] = $param3;

        $data['switch_id'] = $switch_id;
       // record listing
		if($param1 == 'activity_load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 50;
			$item = '';
            $timer_item = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			$search = $this->request->getVar('search');
			if(!empty($this->request->getPost('start_date'))) { $start_date = $this->request->getPost('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getPost('end_date'))) { $end_date = $this->request->getPost('end_date'); } else { $end_date = ''; }
			
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$all_rec = $this->Crud->filter_membership('', '', $log_id, '', $switch_id, false);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_membership('', '', $log_id, '', $switch_id, false);
				$data['count'] = $counts;

				
                
				if (!empty($query)) {
                    usort($query, function($a, $b) {
                        return strcmp(date('m-d', strtotime($a->dob)), date('m-d', strtotime($b->dob)));
                    });
                    
                    $a = 0;
					foreach($query as $q) {
                        
						$id = $q->id;
						$firstname = $q->firstname;
						$surname = $q->surname;
                        $dobs = date('m-d', strtotime($q->dob)); // Extract month and day of birth

						$dob = date('M d', strtotime($q->dob));
                         // Step 3: Skip past birthdays (earlier than today's date)
                        if ($dobs < date('m-d')) {
                            continue; // Skip if the birthday has passed this year
                        } else{
                            $a++;
                        }
						$church_id = $q->church_id;
						$cell = $this->Crud->read_field('id', $q->church_id, 'cells', 'name');
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));

                        if($a > 6)continue;
						
						$item .= '
                            <div class="card-inner card-inner-md">
                                <div class="user-card">
                                    
                                    <div class="user-info"><span class="lead-text">'.ucwords($firstname.' '.$surname ).'</span>
                                    <span class="sub-text text-info">'.strtoupper($cell).'</span></div>
                                    <div class="user-action">
                                        <span class="sub-text">'.$dob.'</span>
                                    </div>
                                </div>
                            </div> 
						';
                        
					}
				}

                if(!empty($switch_id)){
                    $church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
                    if($church_type == 'region'){
                        $role_ids = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');
                        $role = 'regional manager';
                    }
                    if($church_type == 'zone'){
                        $role_ids = $this->Crud->read_field('name', 'Zonal Manager', 'access_role', 'id');
                        $role = 'zonal manager';
                    }
                    if($church_type == 'group'){
                        $role_ids = $this->Crud->read_field('name', 'Group Manager', 'access_role', 'id');
                        $role = 'group manager';
                    }
                    if($church_type == 'church'){
                        $role_ids = $this->Crud->read_field('name', 'Church Leader', 'access_role', 'id');
                        $role = 'church leader';
                    }
                    $ministry_id = $this->Crud->read_field('id', $switch_id, 'church', 'ministry_id');
                    $church_id = $switch_id;
                
                }
                //timer Records
                $timer_data = [];
                $service_query = $this->Crud->read_order('service_report', 'id', 'desc',7);
				
                if($role != 'developer' && $role != 'administrator'){
                    $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                    if($role == 'ministry administrator'){
                        $service_query = $this->Crud->read_single_order('ministry_id', $ministry_id, 'service_report', 'id', 'desc',7);
                    } else {
                        $service_query = $this->Crud->read_single_order('church_id', $church_id, 'service_report', 'id', 'desc',7);
                    }
                } else {
                    $service_query = $this->Crud->read_order('service_report', 'id', 'desc',7);
                }


                
				if (!empty($service_query)) {
					foreach($service_query as $q) {
						$datas['type'] = 'service';
						$datas['id'] = $q->id;
						$datas['date'] = $q->date;
						$datas['timers'] = $q->timers;

                        $timer_data[] = $datas;

                    }
                }

                if($role != 'developer' && $role != 'administrator'){
                    $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                    if($role == 'ministry administrator'){
                        $cell_query = $this->Crud->read_single_order('ministry_id', $ministry_id, 'cell_report', 'id', 'desc',7);
                    } else {
                        $cell_query = $this->Crud->read_single_order('church_id', $church_id, 'cell_report', 'id', 'desc',7);
                    }
                } else {
                    $cell_query = $this->Crud->read_order('cell_report', 'id', 'desc',7);
                }
                
				if (!empty($cell_query)) {
					foreach($cell_query as $q) {
						$datas['type'] = 'cell';
						$datas['id'] = $q->id;
						$datas['date'] = $q->date;
						$datas['timers'] = $q->timers;
                        $timer_data[] = $datas;
                    }
                }

                // Sort array by 'date' in descending order
                usort($timer_data, function($a, $b) {
                    return strtotime($b['date']) - strtotime($a['date']);
                });

                // print_r($timer_data);
                if(!empty($timer_data)){
                    $tco = 0;
                    foreach($timer_data as $td){
                        if(empty($td['timers']))continue;
                        $timer = $td['timers'];
                        $date = date('d F Y', strtotime($td['date']));
                        if(!empty($timer)){
                            $timer = json_decode($timer);
                            if(is_array($timer) && !empty($timer)){
                               
                                foreach($timer as $val){
                                    $time = (array)$val;
                                    
                                    foreach($time as $t=> $vals){
                                        if($t == 'fullname'){
                                            if($tco > 5)continue;
                                            $timer_item .= '
                                                <div class="card-inner card-inner-md">
                                                    <div class="user-card">
                                                        
                                                        <div class="user-info"><span class="lead-text">'.ucwords($vals).'</span>
                                                        <span class="sub-text text-info">'.strtoupper($td['type']).'</span></div>
                                                        <div class="user-action">
                                                            <span class="sub-text">'.$date.'</span>
                                                        </div>
                                                    </div>
                                                </div>  
                                            ';
                                            $tco++;
                                        }
                                    }
                                }
                            }
                        }
					}
				}
				
			}
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<em class="icon ni ni-property" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Upcoming Birthday Returned').'
					</div>
				';
			} else {
				$resp['item'] = $item;
			}

            if(empty($timer_item)) {
				$resp['timer_item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<em class="icon ni ni-users" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No First Timer Returned').'
					</div>
				';
			} else {
				$resp['timer_item'] = $timer_item;
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


        $data['log_id'] = $log_id;
        $data['log_name'] = $username;
        $data['current_language'] = $this->session->get('current_language');
        $data['role'] = $role;
        $data['role_c'] = $role_c;
        $data['title'] = translate_phrase('Dashboard').' - '.app_name;
        $data['page_active'] = $mod;
        return view('dashboard', $data);
    }
    
    

    ///// LOGIN
    public function land() {
        $log_id = 1;
        $data['log_id'] = 1;
        $data['current_language'] = $this->session->get('current_language');
        $data['page_active'] = 'Welcome | '.app_name;
        $data['title'] = 'Welcome | '.app_name;
        return view('land', $data);
    }


    public function email_test() {
        $api_key = $this->Crud->read_field('name', 'termil_api', 'setting', 'value'); // pick from DB
		$email_template = $this->Crud->read_field('name', 'termil_email_template', 'setting', 'value'); // pick from DB
        $email = 'tofunmi015@gmail.com';

        // echo $this->Crud->send_email($email, 'Test', 'Testing', $bcc='');
        // send email
    		if($email) {
    			$dataa['email_address'] = $email;
    			$dataa['code'] = '1546';
    			$dataa['api_key'] = $api_key;
    			$dataa['email_configuration_id'] = $email_template;
    			// $this->Crud->termii('post', 'email/otp/send', $dataa);
    		}
            
            
            $phone = '09068308070';
            $api_key = $this->Crud->read_field('name', 'termil_api', 'setting', 'value'); // pick from DB
						
            if($phone) {
                $phone = '234'.substr($phone,1);echo $phone;
                $datass['to'] = $phone;
                $datass['from'] = 'N-Alert';
                $datass['sms'] = 'Testing Message';
                $datass['api_key'] = $api_key;
                $datass['type'] = 'plain';
                $datass['channel'] = 'dnd';
              echo  $this->Crud->termii('post', 'sms/send', $datass);
            }
    
    }

    public function lang_code(){
        $language = file_get_contents(base_url('assets/js/language.js'));
        $language = json_decode($language);
        // print_r($language);
        $ct = 1;
        foreach($language->data->languages as $lang) {
            $langName = $lang->name;
            $langCode = $lang->language;
            
            $langs['name'] = $langName;
            $langs['code'] = $langCode;
            
            $this->Crud->create('language_code', $langs);
            // if($text == strtolower($langName)) {
            //     $result = $langCode;
            //     break; // stop the loop, one code is retrieved 
            // }
            $ct += 1;
        }


    }

    public function metric(){
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

        $prospective = 0;
        $student = 0;
        $graduate = 0;
        $tithe = 0;
        $tithe_part = 0;
        $partnership = 0;
        $membership = 0;
        $first_timer = 0;
        $new_convert = 0;
        $partnership_part = 0;
        $offering = 0;
        $cell_offering = 0;
        $partnership_list = '';
        $cell_data = '';
        $date_type = $this->request->getPost('date_type');
		$date_type = $this->request->getPost('date_type');
		if(!empty($this->request->getPost('start_date'))) { $start_dates = $this->request->getPost('start_date'); } else { $start_dates = ''; }
		if(!empty($this->request->getPost('end_date'))) { $end_dates = $this->request->getPost('end_date'); } else { $end_dates = ''; }
		if($date_type == 'Today'){
			$start_date = date('Y-m-d');
			$end_date = date('Y-m-d');
		} elseif($date_type == 'Yesterday'){
			$start_date = date('Y-m-d', strtotime( '-1 days' ));
			$end_date = date('Y-m-d', strtotime( '-1 days' ));
		} elseif($date_type == 'Last_Week'){
			$start_date = date('Y-m-d', strtotime( '-7 days' ));
			$end_date = date('Y-m-d');
		} elseif($date_type == 'Last_Month'){
			$start_date = date('Y-m-d', strtotime( '-30 days' ));
			$end_date = date('Y-m-d');
		} elseif($date_type == 'Date_Range'){
			$start_date = $start_dates;
			$end_date = $end_dates;
		} elseif($date_type == 'This_Year'){
			$start_date = date('Y-01-01');
			$end_date = date('Y-m-d');
		} else {
			$start_date = date('Y-m-01');
			$end_date = date('Y-m-d');
		}

        $this->session->set('dashboard_start_date', $start_date);
        $this->session->set('dashboard_end_date', $end_date);
        
        // echo $switch_id;
        $membership = $this->Crud->filter_members($log_id, '', '', $switch_id);
        $celss = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');

        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
        if(!empty($switch_id)){
            $church_id = $switch_id;
            $ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
        }


        if($role == 'developer' || $role == 'administrator'){
            $service_report = $this->Crud->date_range($start_date, 'date', $end_date, 'date', 'service_report');
            $partners = $this->Crud->date_range1($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'partners_history');
           
            $cells = $this->Crud->read('cells', 7);
        } else {
            if($ministry_id > 0 && $church_id <= 0){
                $service_report = $this->Crud->date_range1($start_date, 'date', $end_date, 'date', 'ministry_id', $ministry_id, 'service_report');
                $partners = $this->Crud->date_range2($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'ministry_id', $ministry_id,'partners_history');
           
                $cells = $this->Crud->read_single('ministry_id', $ministry_id,'cells', 7);
            } else {
                $cells = $this->Crud->read_single('church_id', $church_id,'cells', 7);
                $service_report = $this->Crud->date_range1($start_date, 'date', $end_date, 'date', 'church_id', $church_id, 'service_report');
                $partners = $this->Crud->date_range2($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'church_id', $church_id,'partners_history');
            }
        }

        // print_r($service_report);

        if($role == 'cell leader' || $role == 'cell executive' || $role == 'assistant cell leader'){
            $membership = $this->Crud->date_check1($start_date, 'reg_date', $end_date, 'reg_date', 'cell_id', $celss, 'user');
        }

        
        $member_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
        
        $partnership = 0;
        if(!empty($partners)){
            foreach($partners as $u){
                $partnership += (float)$u->amount_paid;
            }
            $partnership_part = count($partners);
        }

        if(!empty($cells)){
            $cell_data .= '
                    <div class="nk-tb-item nk-tb-head">
                    <div class="nk-tb-col nk-tb-channel"><span>Cell</span></div>
                    <div class="nk-tb-col nk-tb-channel"><span>Date</span></div>
                        <div class="nk-tb-col nk-tb-sessions"><span>ATT</span>
                        </div>
                        <div class="nk-tb-col nk-tb-prev-sessions"><span>N.C</span></div>
                        <div class="nk-tb-col nk-tb-change"><span>F.T</span></div>
                        
                    </div>
            ';
            foreach($cells as $u){
                $c_id = $u->id;
                if($role == 'cell leader' || $role == 'assistant cell leader' || $role == 'cell executive'){
                    $c_id = $celss;
                }
                $cell_report = $this->Crud->date_range1($start_date, 'date', $end_date, 'date', 'cell_id', $c_id, 'cell_report');
                // print_r($cell_report);
                $date = '-';
                $attendance = 0;$attends = 0;
                $new_convert = 0;$converts = 0;
                $first_timer = 0;
                $cell_offering = 0;
                
                $timers = 0;
                $ca = count($cell_report);$ca--;
                $i = 1;

                $attend_stat = ''; $convert_stat = ''; $timer_stat = '';
                if(!empty($cell_report)){
                    foreach($cell_report as $cs){
                        if($i == $ca){
                            $attendances = $cs->attendance;
                            $new_converts = $cs->new_convert;
                            $first_timers = $cs->first_timer;
                        }
                        $cell_offering += (float)$cs->offering;

                        // echo $cs->id.' ';
                        $i++;
                    }
                    
                    $date = $cs->date;
                    $attendance = $cs->attendance;
                    $new_convert = $cs->new_convert;
                    $first_timer = $cs->first_timer;
                    $date = date('d F Y', strtotime($date));

                    if(count($cell_report) >=2){
                        if($attendances > 0){
                            $attend = ((int)$attendance - (int)$attendances)/(int)$attendances;
                            $attends = $attend * 100;
                            if($attends > 0){
                                $attend_stat = '<span class="change up"><em class="icon ni ni-arrow-long-up"></em></span>';
                            } else {
                                $attend_stat = '<span class="change down"><em class="icon ni ni-arrow-long-down"></em></span>';
                            }
                        }

                        if($new_converts > 0){
                            $convert = ((int)$new_convert - (int)$new_converts)/(int)$new_converts;
                            $converts = $convert * 100;
                            if($converts > 0){
                                $convert_stat = '<span class="change up"><em class="icon ni ni-arrow-long-up"></em></span>';
                            } else {
                                $convert_stat = '<span class="change down"><em class="icon ni ni-arrow-long-down"></em></span>';
                            }
                        }

                        if($first_timers > 0){
                            $timer = ((int)$first_timer - (int)$first_timers)/(int)$first_timers;
                            $timers = $timer * 100;
                            if($timers > 0){
                                $timer_stat = '<span class="change up"><em class="icon ni ni-arrow-long-up"></em></span>';
                            } else {
                                $timer_stat = '<span class="change down"><em class="icon ni ni-arrow-long-down"></em></span>';
                            }
                        }
                    }

                }
                
                $cell_data .= '
                    
                        <div class="nk-tb-item">
                            <div class="nk-tb-col nk-tb-channel"><span class="tb-lead">'.ucwords($u->name).'</span></div>
                            <div class="nk-tb-col nk-tb-channel"><span class="tb-lead">'.$date.'</span></div>
                            <div class="nk-tb-col nk-tb-sessions"><span class="tb-sub tb-amount"><span>'.number_format((int)$attendance).'</span>'.$attend_stat.'</span></div>
                            <div class="nk-tb-col nk-tb-prev-sessions"><span class="tb-sub tb-amount"><span>'.number_format((int)$new_convert).'</span>'.$convert_stat.'</span></div>
                            <div class="nk-tb-col nk-tb-change"><span class="tb-sub"><span>'.number_format((int)$first_timer).'</span>'.$timer_stat.'</span>
                            </div>
                            
                        </div>
                ';

            }
        } else {
            $cell_data .= '<div class="text-center text-muted">
                <br/><br/><br/><br/>
                <em class="icon ni ni-property" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Cell Report Returned').'
            </div>';
        }

        if (!empty($service_report)) {
            foreach ($service_report as $u) {
                $offering += (float)$u->offering;
                $tithe += (float)$u->tithe;
                $first_timer += $u->first_timer;
                $new_convert += $u->new_convert;
                $partnership += (float)$u->partnership;
                // Decode tithers JSON
                $convertsa = json_decode($u->tithers);
                
                // Ensure $converts is initialized as an array
                $converts = [];
                
                if (!empty($convertsa) && is_object($convertsa) && isset($convertsa->list)) {
                    // Check if the list is an array before assigning
                    if (is_array($convertsa->list)) {
                        $converts = $convertsa->list;
                    } else {
                        // If it's not an array, you can decide how to handle it (e.g., log an error, etc.)
                        // For example, if it's a single item, you could convert it to an array:
                        $converts = [$convertsa->list];
                    }
                }
                
                // Only count if $converts is an array
                if (is_array($converts)) {
                    $tithe_part += count($converts);
                }
            }
        }
        

        $parts = $this->Crud->read_order('partnership', 'name', 'asc');
        $col = array('success', 'primary', 'danger', 'info', 'warning', 'azure', 'gray','blue', 'indigo', 'orange', 'teal', 'purple');
        if(!empty($parts)){
            $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
            $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
            if(!empty($switch_id)){
                $church_id = $switch_id;
                $ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
            }
            foreach($parts as $p){
                $paid = 0;
                if($role !=  'administrator' && $role != 'developer'){
                    if($church_id > 0){
                        $partners = $this->Crud->date_range3($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'partnership_id', $p->id, 'church_id', $church_id, 'partners_history');
               
                    } else {
                        $partners = $this->Crud->date_range3($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'partnership_id', $p->id, 'ministry_id', $ministry_id, 'partners_history');
               
                    }
                } else {
                    $partners = $this->Crud->date_range2($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'partnership_id', $p->id, 'partners_history');
                }

                if(!empty($partners)){
                    foreach($partners as $u){
                        $paid += (float)$u->amount_paid;
                    }
                }
                
                
                if($partnership > 0){
                    $paids = ((float)$paid * 100)/(float)$partnership;
                } else {
                    $paids = 0;
                }
                // Select a random key
                $col = array('success', 'primary', 'danger', 'info', 'warning', 'azure', 'gray','blue', 'indigo', 'orange', 'teal', 'purple');
                $random_key = array_rand($col);

                // Get the value at the random key
                $cols = $col[$random_key];

                $partnership_list .= '
                    <div class="progress-wrap">
                        <div class="progress-text">
                            <div class="progress-label">'.ucwords($p->name).' <b>('.$this->session->get('currency').number_format($this->Crud->cur_exchange($paid),2).')</b></div>
                            <div class="progress-amount">'.number_format($paids,1).'%</div>
                        </div>
                        <div class="progress ">
                            <div class="progress-bar bg-'.$cols.' progress-bar-striped progress-bar-animated" data-progress="'.$paids.'"></div>
                        </div>
                    </div>
                ';
                // Remove the element from the array
                unset($col[$random_key]);

                // Re-index the array if needed
                $col = array_values($col);
            }
        }

        $student_array = array();
        if($role != 'developer' && $role != 'administrator'){
            if($church_id > 0){
                $pmembers = $this->Crud->date_check3($start_date, 'reg_date', $end_date, 'reg_date', 'is_member', 1, 'foundation_school', 0, 'church_id', $church_id, 'user');
                $pvisitors = $this->Crud->date_check2($start_date, 'reg_date', $end_date, 'reg_date', 'foundation_school', 0, 'church_id', $church_id, 'visitors');
                $smembers = $this->Crud->date_check3($start_date, 'reg_date', $end_date, 'reg_date', 'is_member', 1, 'foundation_school', 1, 'church_id', $church_id, 'user');
                $svisitors = $this->Crud->date_check2($start_date, 'reg_date', $end_date, 'reg_date', 'foundation_school', 1, 'church_id', $church_id, 'visitors');
                $gmembers = $this->Crud->date_check3($start_date, 'reg_date', $end_date, 'reg_date', 'is_member', 1, 'foundation_school', 2, 'church_id', $church_id, 'user');
                $gvisitors = $this->Crud->date_check2($start_date, 'reg_date', $end_date, 'reg_date', 'foundation_school', 2, 'church_id', $church_id, 'visitors');
                
            } else {
                $pmembers = $this->Crud->date_check3($start_date, 'reg_date', $end_date, 'reg_date', 'is_member', 1, 'foundation_school', 0, 'ministry_id', $ministry_id, 'user');
                $pvisitors = $this->Crud->date_check2($start_date, 'reg_date', $end_date, 'reg_date', 'foundation_school', 0, 'ministry_id', $ministry_id, 'visitors');
                $smembers = $this->Crud->date_check3($start_date, 'reg_date', $end_date, 'reg_date', 'is_member', 1, 'foundation_school', 1, 'ministry_id', $ministry_id, 'user');
                $svisitors = $this->Crud->date_check2($start_date, 'reg_date', $end_date, 'reg_date', 'foundation_school', 1, 'ministry_id', $ministry_id, 'visitors');
                $gmembers = $this->Crud->date_check3($start_date, 'reg_date', $end_date, 'reg_date', 'is_member', 1, 'foundation_school', 2, 'ministry_id', $ministry_id, 'user');
                $gvisitors = $this->Crud->date_check2($start_date, 'reg_date', $end_date, 'reg_date', 'foundation_school', 2, 'ministry_id', $ministry_id, 'visitors');
        
            }
        } else {
            $pmembers = $this->Crud->date_check2($start_date, 'reg_date', $end_date, 'reg_date', 'is_member', 1, 'foundation_school', 0, 'user');
            $pvisitors = $this->Crud->date_check1($start_date, 'reg_date', $end_date, 'reg_date', 'foundation_school', 0, 'visitors');
            $smembers = $this->Crud->date_check2($start_date, 'reg_date', $end_date, 'reg_date', 'is_member', 1, 'foundation_school', 1, 'user');
            $svisitors = $this->Crud->date_check1($start_date, 'reg_date', $end_date, 'reg_date', 'foundation_school', 1, 'visitors');
            $gmembers = $this->Crud->date_check2($start_date, 'reg_date', $end_date, 'reg_date', 'is_member', 1, 'foundation_school', 2, 'user');
            $gvisitors = $this->Crud->date_check1($start_date, 'reg_date', $end_date, 'reg_date', 'foundation_school', 2, 'visitors');
        
        }

        $prospective += $pmembers;
        $prospective += $pvisitors;
        $student += $smembers;
        $student += $svisitors;
        $graduate += $gmembers;
        $graduate += $gvisitors;
           
       
        $resp['tithe'] = $this->session->get('currency').number_format($this->Crud->cur_exchange($tithe),2);
        $resp['tithe_part'] = number_format($tithe_part);
        $resp['prospective'] = number_format($prospective);
        $resp['student'] = number_format($student);
        $resp['graduate'] = number_format($graduate);
        $resp['membership'] = number_format($membership);
        $resp['first_timer'] = number_format($first_timer);
        $resp['new_convert'] = number_format($new_convert);
        $resp['offering'] = $this->session->get('currency').number_format($this->Crud->cur_exchange($offering),2);
        $resp['cell_offering'] = $this->session->get('currency').number_format($this->Crud->cur_exchange($cell_offering),2);
        $resp['partnership'] = $this->session->get('currency').number_format($this->Crud->cur_exchange($partnership),2);
        $resp['partnership_part'] = number_format($partnership_part);
        $resp['partnership_list'] = ($partnership_list);
        $resp['cell_data'] = ($cell_data);

        
        echo json_encode($resp);
        die;
    }

    public function service_metric(){
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

        $service_date = '';
        $service_key = '';
      
        
        $date_type = $this->request->getPost('date_type');
		$date_type = $this->request->getPost('date_type');
		if(!empty($this->request->getPost('start_date'))) { $start_dates = $this->request->getPost('start_date'); } else { $start_dates = ''; }
		if(!empty($this->request->getPost('end_date'))) { $end_dates = $this->request->getPost('end_date'); } else { $end_dates = ''; }
		if($date_type == 'Today'){
			$start_date = date('Y-m-d');
			$end_date = date('Y-m-d');
		} elseif($date_type == 'Yesterday'){
			$start_date = date('Y-m-d', strtotime( '-1 days' ));
			$end_date = date('Y-m-d', strtotime( '-1 days' ));
		} elseif($date_type == 'Last_Week'){
			$start_date = date('Y-m-d', strtotime( '-7 days' ));
			$end_date = date('Y-m-d');
		} elseif($date_type == 'Last_Month'){
			$start_date = date('Y-m-d', strtotime( '-30 days' ));
			$end_date = date('Y-m-d');
		} elseif($date_type == 'Date_Range'){
			$start_date = $start_dates;
			$end_date = $end_dates;
		} elseif($date_type == 'This_Year'){
			$start_date = date('Y-01-01');
			$end_date = date('Y-m-d');
		} else {
			$start_date = date('Y-m-01');
			$end_date = date('Y-m-d');
		}
        
        $male = 0;$female = 0;$children=0;$ft=0;$nc=0;
        $male_per = 0;$female_per = 0;$children_per=0;$ft_per=0;$nc_per=0;
        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
        if(!empty($switch_id)){
            $church_id = $switch_id;
            $ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
        }
        if($role == 'developer' || $role == 'administrator'){
            $service = $this->Crud->read_order('service_report', 'date', 'asc');
        } else {
            if($role == 'ministry administrator'){
                $service = $this->Crud->read_single_order('ministry_id', $ministry_id, 'service_report', 'date', 'asc');
            } else {
                
                $service = $this->Crud->read_single_order('church_id', $church_id, 'service_report', 'date', 'asc');
            }
        }

       
            $total_per = 0;
            $total_s = 0;
            if(!empty($service)){
                foreach($service as $u){
                   
                }
                $type = $this->Crud->read_field('id', $u->type, 'service_type', 'name');
                $service_date = $type.' - '.date('d F Y', strtotime($u->date));
                $attend = $u->attendance;
                $attendance = $u->attendant;
                $attendant = json_decode($attendance, true);  // Decode JSON as an associative array

                if (!empty($attendant)) {
                    // Check if 'male' index exists
                    if (isset($attendant['male'])) {
                        $male = $attendant['male'];
                    } else {
                        $male = 0;  // Default value if 'male' index does not exist
                    }
                
                    // Check if 'female' index exists
                    if (isset($attendant['female'])) {
                        $female = $attendant['female'];
                    } else {
                        $female = 0;  // Default value if 'female' index does not exist
                    }
                
                    // Check if 'children' index exists
                    if (isset($attendant['children'])) {
                        $children = $attendant['children'];
                    } else {
                        $children = 0;  // Default value if 'children' index does not exist
                    }
                
                    $ft = $u->first_timer;
    
                    $male_per = ((int)$male * 100)/(int)$attend;
                    $female_per = ((int)$female * 100)/(int)$attend;
                    $children_per = ((int)$children * 100)/(int)$attend;
                    $ft_per = ((int)$ft * 100)/(int)$attend;
                    $total_per = $male_per + $female_per + $children_per + $ft_per;
                    $total_s = $male + $female + $children + $ft;
                }
                

                // $female = 110;$children = 11;
            }
           
            $service_key .= '
            <div class="traffic-channel-data">
                <div class="title"><span class="dot dot-lg sq bg-info" data-bg="#ffa353"></span><span>Male</span></div>
                <div class="amount">'.number_format($male).' <small>'.number_format($male_per,2).'%</small></div>
            </div>
            <div class="traffic-channel-data">
                <div class="title"><span class="dot dot-lg sq  bg-teal" data-bg="#ffa353"></span><span>Female</span></div>
                <div class="amount">'.number_format($female).' <small>'.number_format($female_per,2).'%</small></div>
            </div>
            <div class="traffic-channel-data">
                <div class="title"><span class="dot dot-lg sq  bg-warning" data-bg="#ffa353"></span><span>Children</span></div>
                <div class="amount">'.number_format($children).' <small>'.number_format($children_per,2).'%</small></div>
            </div>
            <div class="traffic-channel-data">
                <div class="title"><span class="dot dot-lg sq  bg-danger" data-bg="#ffa353"></span><span>First Timer</span></div>
                <div class="amount">'.number_format($ft).' <small>'.number_format($ft_per,2).'%</small></div>
            </div>
           <div class="traffic-channel-data">
                <div class="title"><span class="dot dot-lg sq  bg-success" data-bg="#ffa353"></span><span>Total</span></div>
                <div class="amount">'.number_format($total_s).' <small>'.number_format($total_per,2).'%</small></div>
            </div>
           

            ';
           
       
        // print_r($service);
        // echo $offering.' e';

        
        $service_data = array((int)$male, (int)$female, (int)$children, (int)$ft);
        $resp['service_date'] = ($service_date);
        $resp['service_key'] = ($service_key);
        $resp['service_data'] = json_encode($service_data);

        
        echo json_encode($resp);
        die;
    }
    public function finance_metric(){
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

        $finance_wednesday = [];
      
        
        $finance_type = strtolower($this->request->getPost('finance_type'));
		$current_year = $this->request->getPost('current_year');
		$start_date = date($current_year.'-01-01');
        $end_date = date($current_year.'-12-31');
        // Get the first day of the current year
        $startDate = strtotime("first Sunday of January $current_year");

        // Get the last day of the current year
        $endDate = strtotime("last day of December $current_year");
        $celss = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
        if(!empty($switch_id)){
            $church_id = $switch_id;
            $ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
        }
        $sunday_id = $this->Crud->read_field('name', 'Sunday Service', 'service_type', 'id');
        $wednesday_id = $this->Crud->read_field('name', 'Wednesday Service', 'service_type', 'id');
            
        if($role == 'developer' || $role == 'administrator'){
            $sunday = $this->Crud->date_range1($start_date, 'date', $end_date,'date', 'type', $sunday_id, 'service_report');
            $wednesday = $this->Crud->date_range1($start_date, 'date', $end_date, 'date','type', $wednesday_id, 'service_report');
              
        } else {
            if($role == 'ministry administrator'){
                $sunday = $this->Crud->date_range2($start_date, 'date', $end_date,'date', 'type', $sunday_id, 'ministry_id', $ministry_id, 'service_report');
                $wednesday = $this->Crud->date_range2($start_date, 'date', $end_date, 'date','type', $wednesday_id, 'ministry_id', $ministry_id, 'service_report');
                 
            } else {

                $sunday = $this->Crud->date_range2($start_date, 'date', $end_date,'date', 'type', $sunday_id, 'church_id', $church_id, 'service_report');
                $wednesday = $this->Crud->date_range2($start_date, 'date', $end_date, 'date','type', $wednesday_id, 'church_id', $church_id, 'service_report');
                 
            }
        }
        // print_r($wednesday);
        while ($startDate <= $endDate) {
            // Initialize amounts for the week
            $amountSunday = 0;
            $amountWednesday = 0;
        
            // Process Sundays
            if (!empty($sunday)) {
                foreach ($sunday as $s) {
                    if ($s->date == date('Y-m-d', $startDate)) {
                        if ($finance_type == 'offering') {
                            $amountSunday = (float)$s->offering;
                        } elseif ($finance_type == 'tithe') {
                            $amountSunday = (float)$s->tithe;
                        } elseif ($finance_type == 'partnership') {
                            $amountSunday = (float)$s->partnership;
                        }
                    }
                }
            }
            $finance_sunday[] = $amountSunday; // Add amount for Sunday
        
            // Process Wednesdays
            if (!empty($wednesday)) {
                foreach ($wednesday as $s) {
                    if ($s->date == date('Y-m-d', strtotime('next Wednesday', $startDate))) {
                        if ($finance_type == 'offering') {
                            $amountWednesday = (float)$s->offering;
                        } elseif ($finance_type == 'tithe') {
                            $amountWednesday = (float)$s->tithe;
                        } elseif ($finance_type == 'partnership') {
                            $amountWednesday = (float)$s->partnership;
                        }
                    }
                }
            }
            $finance_wednesday[] = $amountWednesday; // Add amount for Wednesday
        
            // Move to the next week
            $startDate = strtotime('+1 week', $startDate);
        }
        
        
        $resp['finance_sunday'] = json_encode($finance_sunday);
        $resp['finance_wednesday'] = json_encode($finance_wednesday);

        
        echo json_encode($resp);
        die;
    }

    public function records($param1='', $param2=''){
        $start_date = $this->session->get('dashboard_start_date');
        $end_date = $this->session->get('dashboard_end_date');
        
        $log_id = $this->session->get('td_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
       
        $celss = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');

        $data['log_id'] = $log_id;
        $data['param1'] = $param1;
        $data['param2'] = $param2;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        if($param1 == 'finance'){
            if($param2 == 'service_offering'){
                if($role == 'developer' || $role == 'administrator'){
                    $service_report = $this->Crud->date_range($start_date, 'date', $end_date, 'date', 'service_report');
                    $partners = $this->Crud->date_range1($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'partners_history');
                   
                    $cells = $this->Crud->read('cells', 7);
                } else {
                    if($ministry_id > 0 && $church_id <= 0){
                        $service_report = $this->Crud->date_range1($start_date, 'date', $end_date, 'date', 'ministry_id', $ministry_id, 'service_report');
                        $partners = $this->Crud->date_range2($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'ministry_id', $ministry_id,'partners_history');
                   
                        $cells = $this->Crud->read_single('ministry_id', $ministry_id,'cells', 7);
                    } else {
                        $cells = $this->Crud->read_single('church_id', $church_id,'cells', 7);
                        $service_report = $this->Crud->date_range1($start_date, 'date', $end_date, 'date', 'church_id', $church_id, 'service_report');
                        $partners = $this->Crud->date_range2($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'church_id', $church_id,'partners_history');
                    }
                }
                $total_offering = 0;
                $list = '';
                if (!empty($service_report)) {
                    foreach ($service_report as $u) {
                        $total_offering += (float)$u->offering;
                        $givers = $u->offering_givers;
                        $church = $this->Crud->read_field('id', $u->church_id, 'church', 'name');
                        $service = $this->Crud->read_field('id', $u->type, 'service_type', 'name');
                        // Decode the JSON data to an associative array
                        if(!empty($givers)){
                           
                            $offeringData = json_decode($givers, true);

                            // Extract member and guest contributions
                            $memberContributions = $offeringData["list"];
                            $guestContributions = $offeringData["guest_list"];

                            // Display member contributions
                            if(!empty($memberContributions)){
                                foreach ($memberContributions as $memberID => $amount) {
                                    $list .= '<tr>
                                        <td>'.$u->date.'</td>
                                        <td>'.ucwords($church).'</td>
                                        <td>'.ucwords($service).'</td>
                                    ';
                                    $member = $this->Crud->read_field('id', $memberID, 'user', 'firstname').' '.$this->Crud->read_field('id', $memberID, 'user', 'surname');
                                    $list .= '
                                        <td>'.ucwords($member).'</td>
                                        <td>Member</td>
                                        <td>'.$this->session->get('currency').number_format($amount,2).'</td>
                                        </tr>
                                    ';
                                    
                                }
                            }

                            // Display guest contributions;
                            if(!empty($guestContributions)){
                                foreach ($guestContributions as $guestName => $amount) {
                                    $list .= '<tr>
                                        <td>'.$u->date.'</td>
                                        <td>'.ucwords($church).'</td>
                                        <td>'.ucwords($service).'</td>
                                   
                                        <td>'.ucwords($guestName).'</td>
                                        <td>Visitor</td>
                                        <td>'.$this->session->get('currency').number_format($amount,2).'</td>
                                        </tr>
                                ';
                                }
                            }
                        } else{
                            continue;
                        }
                                                
                    }
                }
                if(empty($list)){
                    $data['offering_list'] = '<tr><td colspan="8"><h4 class="text-center">No Record Found</h4></td></tr>';
                } else{
                    $data['offering_list'] = $list;
                }

                $data['offering'] = $this->session->get('currency').number_format($total_offering,2);
                

            }
            if($param2 == 'cell_offering'){
                if($role == 'developer' || $role == 'administrator'){
                    $cells = $this->Crud->read('cells');
                } else {
                    if($ministry_id > 0 && $church_id <= 0){
                        $cells = $this->Crud->read_single('ministry_id', $ministry_id,'cells');
                    } else {
                        $cells = $this->Crud->read_single('church_id', $church_id,'cells');
                    }
                }
                
                $list = '';
                $cell_offering = 0;
              
               
                if(!empty($cells)){
                    foreach($cells as $u){
                        $c_id = $u->id;
                        if($role == 'cell leader' || $role == 'assistant cell leader' || $role == 'cell executive'){
                            $c_id = $celss;
                        }
                        $cell_report = $this->Crud->date_range1($start_date, 'date', $end_date, 'date', 'cell_id', $c_id, 'cell_report');
                       
                        if(!empty($cell_report)){
                            foreach($cell_report as $cs){
                                $date = $cs->date;
                                $date = date('d M Y', strtotime($date));
        
                                $cell_offering += (float)$cs->offering;
                                $givers = $cs->offering_givers;
                                $church = $this->Crud->read_field('id', $cs->church_id, 'church', 'name');
                                $cell = $this->Crud->read_field('id', $cs->cell_id, 'cells', 'name');
                                $type = $cs->type;
                                if($type == 'wk1')$types = 'WK1 - Prayer and Planning';
                                if($type == 'wk2')$types = 'Wk2 - Bible Study';
                                if($type == 'wk3')$types = 'Wk3 - Bible Study';
                                if($type == 'wk4')$types = 'Wk4 - Fellowship / Outreach';
                                if($type == 'wk5')$types = 'Wk5 - Fellowship';
                                // Decode the JSON data to an associative array
                                if(!empty($givers)){
                                   
                                    $offeringData = json_decode($givers, true);
        
                                    // Extract member and guest contributions
                                    $memberContributions = $offeringData["list"];
                                    $guestContributions = $offeringData["guest_list"];
        
                                    // Display member contributions
                                    if(!empty($memberContributions)){
                                        foreach ($memberContributions as $memberID => $amount) {
                                            $list .= '<tr>
                                                <td>'.$date.'</td>
                                                <td>'.ucwords($church).'</td>
                                                <td>'.ucwords($cell).'</td>
                                                <td>'.ucwords($types).'</td>
                                            ';
                                            $member = $this->Crud->read_field('id', $memberID, 'user', 'firstname').' '.$this->Crud->read_field('id', $memberID, 'user', 'surname');
                                            $list .= '
                                                <td>'.ucwords($member).'</td>
                                                <td>Member</td>
                                                <td>'.$this->session->get('currency').number_format($amount,2).'</td>
                                                </tr>
                                            ';
                                            
                                        }
                                    }
        
                                    // Display guest contributions;
                                    if(!empty($guestContributions)){
                                        foreach ($guestContributions as $guestName => $amount) {
                                            $list .= '<tr>
                                                <td>'.$date.'</td>
                                                <td>'.ucwords($church).'</td>
                                                <td>'.ucwords($cell).'</td>
                                                <td>'.ucwords($types).'</td>
                                           
                                                <td>'.ucwords($guestName).'</td>
                                                <td>Visitor</td>
                                                <td>'.$this->session->get('currency').number_format($amount,2).'</td>
                                                </tr>
                                        ';
                                        }
                                    }
                                } else{
                                    continue;
                                }
                            }
                            
                           
                        }
                        
                    }
                } 

               
                if(empty($list)){
                    $data['cell_list'] = '<tr><td colspan="8"><h4 class="text-center">No Record Found</h4></td></tr>';
                } else{
                    $data['cell_list'] = $list;
                }

                $data['offering'] = $this->session->get('currency').number_format($cell_offering,2);
                

            }
            if($param2 == 'service_tithe'){
                if($role == 'developer' || $role == 'administrator'){
                    $service_report = $this->Crud->date_range($start_date, 'date', $end_date, 'date', 'service_report');
                   
                } else {
                    if($ministry_id > 0 && $church_id <= 0){
                        $service_report = $this->Crud->date_range1($start_date, 'date', $end_date, 'date', 'ministry_id', $ministry_id, 'service_report');
                       
                    } else {
                        $service_report = $this->Crud->date_range1($start_date, 'date', $end_date, 'date', 'church_id', $church_id, 'service_report');
                    }
                }

                $total_offering = 0;
                $list = '';
                if (!empty($service_report)) {
                    foreach ($service_report as $u) {
                        $total_offering += (float)$u->tithe;
                        $givers = $u->tithers;
                        $church = $this->Crud->read_field('id', $u->church_id, 'church', 'name');
                        $service = $this->Crud->read_field('id', $u->type, 'service_type', 'name');
                        // Decode the JSON data to an associative array
                        if(!empty($givers)){
                           
                            $offeringData = json_decode($givers, true);

                            // Extract member and guest contributions
                            $memberContributions = $offeringData["list"];
                            $guestContributions = $offeringData["guest_list"];

                            // Display member contributions
                            if(!empty($memberContributions)){
                                foreach ($memberContributions as $memberID => $amount) {
                                    $list .= '<tr>
                                        <td>'.$u->date.'</td>
                                        <td>'.ucwords($church).'</td>
                                        <td>'.ucwords($service).'</td>
                                    ';
                                    $member = $this->Crud->read_field('id', $memberID, 'user', 'firstname').' '.$this->Crud->read_field('id', $memberID, 'user', 'surname');
                                    $list .= '
                                        <td>'.ucwords($member).'</td>
                                        <td>Member</td>
                                        <td>'.$this->session->get('currency').number_format($amount,2).'</td>
                                        </tr>
                                    ';
                                    
                                }
                            }

                            // Display guest contributions;
                            if(!empty($guestContributions)){
                                foreach ($guestContributions as $guestName => $amount) {
                                    $list .= '<tr>
                                        <td>'.$u->date.'</td>
                                        <td>'.ucwords($church).'</td>
                                        <td>'.ucwords($service).'</td>
                                   
                                        <td>'.ucwords($guestName).'</td>
                                        <td>Visitor</td>
                                        <td>'.$this->session->get('currency').number_format($amount,2).'</td>
                                        </tr>
                                ';
                                }
                            }
                        } else{
                            continue;
                        }
                                                
                    }
                }
                if(empty($list)){
                    $data['offering_list'] = '<tr><td colspan="8"><h4 class="text-center">No Record Found</h4></td></tr>';
                } else{
                    $data['offering_list'] = $list;
                }

                $data['offering'] = $this->session->get('currency').number_format($total_offering,2);
                

            }

            if($param2 == 'partnership'){
                if($role == 'developer' || $role == 'administrator'){
                    $service_report = $this->Crud->date_range($start_date, 'date', $end_date, 'date', 'service_report');
                    $partners = $this->Crud->date_range1($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'partners_history');
                   
                } else {
                    if($ministry_id > 0 && $church_id <= 0){
                        $service_report = $this->Crud->date_range1($start_date, 'date', $end_date, 'date', 'ministry_id', $ministry_id, 'service_report');
                        $partners = $this->Crud->date_range2($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'ministry_id', $ministry_id,'partners_history');
                   
                    } else {
                        $service_report = $this->Crud->date_range1($start_date, 'date', $end_date, 'date', 'church_id', $church_id, 'service_report');
                        $partners = $this->Crud->date_range2($start_date, 'reg_date', $end_date, 'reg_date', 'status', 1, 'church_id', $church_id,'partners_history');
                    }
                }

                $total_offering = 0;
                $list = '';
                
                $partners_history = array();

                if(!empty($partners)){
                    foreach($partners as $u){
                        $history['member_id'] = $u->member_id;
                        $history['partnership'] = $u->partnership_id;
                        $history['amount_paid'] = $u->amount_paid;
                        $history['date_paid'] = $u->date_paid;
                        $history['church_id'] = $u->church_id;
                        $history['type'] = 'Member';
                        $history['is_service'] = 0;
                        
                        $partners_history[] = $history;
                    }
                }
                
                if (!empty($service_report)) {
                    foreach ($service_report as $u) {
                        $total_offering += (float)$u->partnership;
                        $givers = $u->partners;

                        // Decode the JSON data to an associative array
                        if(!empty($givers)){
                           
                            $offeringData = json_decode($givers, true);

                            // Extract member and guest contributions

                            // Display member contributions
                            if(!empty($offeringData['partnership']["member"])){
                                $memberContributions = $offeringData['partnership']["member"];
                                foreach ($memberContributions as $memberId => $partnerships) {
                                    foreach ($partnerships as $partnershipId => $amount) {
                                        $history['member_id'] = $memberId;
                                        $history['partnership'] = $partnershipId;
                                        $history['amount_paid'] = $amount;
                                        $history['date_paid'] = $u->date;
                                        $history['church_id'] = $u->church_id;
                                        $history['type'] = 'Member';
                                        

                                        $partners_history[] = $history;
                                    }
                                }
                            }

                            // Display guest contributions;
                            if(!empty($offeringData['partnership']["guest"])){
                                $guestContributions = $offeringData['partnership']["guest"];
                                foreach ($guestContributions as $memberId => $partnerships) {
                                    foreach ($partnerships as $partnershipId => $amount) {
                                        $history['member_id'] = $memberId;
                                        $history['partnership'] = $partnershipId;
                                        $history['amount_paid'] = $amount;
                                        $history['date_paid'] = $u->date;
                                        $history['church_id'] = $u->church_id;
                                        $history['type'] = 'Guest';
                                        

                                        $partners_history[] = $history;
                                    }
                                }
                            }
                        }
                                              
                    }
                }

               
                if(!empty($partners_history)){
                    foreach($partners_history as $ua) {
                        $member = $this->Crud->read_field('id', $ua['member_id'], 'user', 'firstname').' '.$this->Crud->read_field('id', $ua['member_id'], 'user', 'surname');
                        $partnership = $this->Crud->read_field('id', $ua['partnership'], 'partnership', 'name');
                        $church = $this->Crud->read_field('id', $ua['church_id'], 'church', 'name');
                        $service = $this->Crud->read_field('id', $ua['is_service'], 'service_type', 'name');
                        
                        $list .= '<tr>
                            <td>'.$ua['date_paid'].'</td>
                            <td>'.ucwords($church).'</td>
                            <td>'.ucwords($service).'</td>
                            <td>'.ucwords($member).'</td>
                            <td>'.ucwords($partnership).'</td>
                            <td>'.ucwords($ua['type']).'</td>
                            <td>'.$this->session->get('currency').number_format($ua['amount_paid'], 2).'</td>
                        </tr>';
                    }
                     
                }
                if(empty($list)){
                    $data['offering_list'] = '<tr><td colspan="8"><h4 class="text-center">No Record Found</h4></td></tr>';
                } else{
                    $data['offering_list'] = $list;
                }

                $data['offering'] = $this->session->get('currency').number_format($total_offering,2);
                

            }
           
        }

        if($param1 == 'finance'){
            return view('dashboard_form', $data);
        }
    }


    public function map (){

    }
}
