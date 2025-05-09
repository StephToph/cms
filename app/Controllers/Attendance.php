<?php

namespace App\Controllers;

use App\Libraries\Multilingual;

class Attendance extends BaseController {
	
    public function index($param1='') {
		if(empty($this->session->get('current_language')))$this->session->set('current_language', 'English');
		$multilingual = new Multilingual();
        // check login
        $log_id = $this->session->get('td_attend_id');
        if(!empty($log_id)) return redirect()->to(site_url('attend'));
		

		if($this->request->getMethod() == 'post') {
            $email = $this->request->getVar('membership_id');
            $password = $this->request->getVar('password');

			if($email && $password) {
				$password = md5($password);
				$type = 'user_no';
				$query = $this->Crud->read2('user_no', $email, 'password', $password, 'user');
				
	
				if(empty($query)) {
					$msg = 'Invalid Authentication!';
					echo $this->Crud->msg('warning', translate_phrase($msg));
						
				} else {
					$act = $this->Crud->check2($type, $email, 'activate', 0, 'user');
					if ($act > 0) {
						$msg = 'Account not Activated, Contact Church Administrator';
						echo $this->Crud->msg('danger', translate_phrase($msg));
					} else {
						$id = $this->Crud->read_field($type, $email, 'user', 'id');
						$church_id = $this->Crud->read_field('id', $id, 'user', 'church_id');
						$is_admin = $this->Crud->read_field('id', $id, 'user', 'is_admin');
						$is_monitoring = $this->Crud->read_field('id', $id, 'user', 'is_monitoring');
						$role_id = $this->Crud->read_field('id', $id, 'user', 'role_id');
						$role = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
						
						$attend_type = '';
						

						if($this->Crud->read_field('id', $id, 'user', 'church_id') > 0){
							$timezone = $this->Crud->getUserTimezone($id); // e.g. "+01:00" or "Africa/Lagos"
							session()->set('user_timezone', $timezone);

							// Optional: apply it immediately
							date_default_timezone_set($timezone);
						}

						// echo date('y-m-d');
						if($is_admin > 0 || $role == 'Administrator' || $role == 'Developer'){
							$attend_type = 'admin';
						}
						
						if($is_monitoring > 0){
							$attend_type = 'monitoring';
						}

						if($role == 'Assistant Cell Leader' || $role == 'Cell Leader' || $role == 'Cell Executive'){
							$attend_type = 'cell';
						}

						// echo $attend_type;
						if(empty($attend_type)){
							echo $this->Crud->msg('danger', 'You dont have access to this Module.Thank you!');
							die;
						}

						$service_check = $this->Crud->read_field3('status', 0, 'date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'id');

						if($service_check <= 0){
							echo $this->Crud->msg('danger', 'No Active Service Today<br>Check Back Later!!');
							die;
						}

						
						$status = true;
						$msg = 'Login Successful!<br>Active Service..';
						$code = 'success';

						$id = $this->Crud->read_field($type, $email, 'user', 'id');
						$this->session->set('td_attend_id', $id);
						$this->session->set('td_attend_type', $attend_type);
						$this->session->set('td_church_id', $church_id);
						

						///// store activities
						$codes = $this->Crud->read_field('id', $id, 'user', 'firstname').' '.$this->Crud->read_field('id', $id, 'user', 'surname');
						$action = $codes . ' logged into the Attendance Platform ';
						$this->Crud->activity('authentication', $id, $action, $id);

						$this->session->set('timeout', $this->session->get('timeout'));
						$this->session->set('isLoggedIn', true);
						$this->session->set('logged_in', true);
						$this->session->set('last_activity', time()); 
				
						echo $this->Crud->msg('success', ($msg));
						$this->session->set('td_attend_id', $id);
						
						echo '<script>window.location.replace("'.site_url('attend').'");</script>';
					}
				}
			}
			
            die;
        }
        
        $data['current_language'] = $this->session->get('current_language');
        $data['title'] =  $multilingual->_ph('Attendance').' - '.app_name;
		
        return view('attendance/login', $data);
    }

	public function records($param1='', $param2='', $param3=''){
		$log_id = $this->session->get('td_attend_id');
		$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
	    $cell_id = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
		
		$data['log_id'] = $log_id;
        $data['param1'] = $param1;
        $data['param2'] = $param2;
        $data['param3'] = $param3;
		$form_link = site_url('attendance/records');
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}

		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = rtrim($form_link, '/');
		
		$attend_type = $this->session->get('td_attend_type');

		if($param1 == 'get_member'){
			if($_POST){
				$member_id = $this->request->getPost('member_id');
				$service = $this->request->getPost('service');
				$church_id = $this->request->getPost('church_id');
				$response = '';

				$mem_couunt = strlen($member_id);
				if($mem_couunt < 3){
					$response =  $this->Crud->msg('danger', 'Enter More than 3 Characters!');
					
				} else {
					if(empty($member_id)){
						$response = $this->Crud->msg('danger', 'Field Cannot be Empty!!');
						
					} else {
						$query = $this->Crud->read2_order('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'date', 'asc');
					
						$occurrence = 0;
						$service_report_id = 0;
					
						if (!empty($query)) {
							foreach ($query as $q) {
								$occurrence++;
								if ($occurrence == $service) {
									$service_report_id = $q->id;
									break;
								}
							}
						}
					
						$query = $this->Crud->filter_member_attendance($member_id, $church_id);
					
						if (!empty($query)) {
							$response .= '<div class="table-responsive"><table class="table table-hover">';
					
							foreach ($query as $q) {
								$status = strtolower($this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'status'));
								$absent_reason = '';
								if ($status == 'absent') {
									$absent_reason = $this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'reason');
								}
								if(empty($status))$status = 'absent';
					
								$email = !empty($q->email) ? $this->Crud->mask_email($q->email) : '';
								$phone = !empty($q->phone) ? $this->Crud->mask_phone($q->phone) : '';
					
								$response .= '
								<tr>
									<td>' . ucwords(strtolower($q->firstname . ' ' . $q->surname . ' ' . $q->othername)) . '</td>
									<td>' . $email . '</td>
									<td>' . $phone . '</td>
									<td>
										<div class="custom-control custom-switch mb-2">
											<input type="checkbox"
												class="custom-control-input mark-present-switch"
												id="presentSwitchz_' . $q->id . '"
												data-member-id="' . $q->id . '" 
												' . ($status == 'present' ? 'checked' : '') . '>
											<label class="custom-control-label" for="presentSwitchz_' . $q->id . '">Mark Present</label>
										</div>
					
										<div id="absent_reason_wrapper_' . $q->id . '" 
											 class="mt-2 form-group absent_reason_wrapper_' . $q->id . '" 
											 style="display: ' . ($status == 'absent' ? 'block' : 'none') . ';">
											<label for="absent_reason_' . $q->id . '" class="form-label">Reason for Absence</label><br>
											<select class="js-select2 reason-select w-100" data-search="on"
												name="absent_reason" id="absent_reason_' . $q->id . '" 
												data-member-id="' . $q->id . '">
												<option value="">-- Select Reason --</option>
												<option value="Out of Town" '.($absent_reason == 'Out of Town' ? 'selected' : '').'>Out of Town</option>
												<option value="Gone to School" '.($absent_reason == 'Gone to School' ? 'selected' : '').'>Gone to School</option>
												<option value="Health Challenges" '.($absent_reason == 'Health Challenges' ? 'selected' : '').'>Health Challenges</option>
												<option value="Challenges at Work" '.($absent_reason == 'Challenges at Work' ? 'selected' : '').'>Challenges at Work</option>
												<option value="Challenges at Home" '.($absent_reason == 'Challenges at Home' ? 'selected' : '').'>Challenges at Home</option>
												<option value="Financial Constraint" '.($absent_reason == 'Financial Constraint' ? 'selected' : '').'>Financial Constraint</option>
												<option value="Absent without reason" '.($absent_reason == 'Absent without reason' ? 'selected' : '').'>Absent without reason</option>
												<option value="Offence" '.($absent_reason == 'Offence' ? 'selected' : '').'>Offence</option>
												<option value="Irregular" '.($absent_reason == 'Irregular' ? 'selected' : '').'>Irregular</option>
												<option value="Not Yet Attending Church" '.($absent_reason == 'Not Yet Attending Church' ? 'selected' : '').'>Not Yet Attending Church</option>
												<option value="Other" '.(stripos($absent_reason, 'Other') !== false ? 'selected' : '').'>Other – Specify</option>
											</select>

					
											<input type="text" 
												class="form-control form-control-sm mt-2 other-reason-input"
												id="other_reason_' . $q->id . '" 
												placeholder="Please specify"
												style="display: ' . (stripos($absent_reason, 'Other') !== false ? 'block' : 'none') . ';"
												value="' . (stripos($absent_reason, 'Other') !== false ? $absent_reason : '') . '" />
										</div>
					
										<span id="resp_' . $q->id . '"></span>
									</td>
								</tr>';
							}
					
							$response .= '</table></div>';
						} else {
							$response = '<div class="text-center text-muted">
								<br/>
								<em class="icon ni ni-user" style="font-size:150px;"></em><br/><br/>' . translate_phrase('No Record Found') . '
							</div>';
						}
					}
					
				}

				$item['response'] = $response;
				echo json_encode($item);
			}
			die;
		}
		if ($param1 == 'mark_present') {
			$member_id = $this->request->getPost('member_id');
			$service_id = $this->request->getPost('service_id'); // service_report id
			$mark = (int) $this->request->getPost('mark'); // 1 = present, 0 = absent
			$church_id = $this->Crud->read_field('id', $service_id, 'service_report', 'church_id');
			
			if (empty($service_id) || empty($member_id)) {
				return $this->response->setJSON([
					'status' => 'error',
					'message' => 'Invalid parameters.'
				]);
			}
			
			
			
			$status_text = ($mark == 1) ? 'present' : 'absent';
			$existing_attendance_id = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_id, 'service_attendance', 'id');
		
			if (empty($existing_attendance_id)) {
				// ✅ No record — Insert new
				$insert = $this->Crud->create('service_attendance', [
					'member_id' => $member_id,
					'service_id' => $service_id,
					'church_id' => $church_id,
					'monitor_type' => $attend_type ?? 'admin', // in case monitor_type is set
					'monitor_id' => $log_id ?? 0,
					'status' => $status_text,
					'reg_date' => date('Y-m-d H:i:s')
				]);
		
				if ($insert > 0 && $mark == 1) {
					// Increment Attendance Count if Present
					$current = (int) $this->Crud->read_field('id', $service_id, 'service_report', 'attendance');
					$current++;
					$this->Crud->updates('id', $service_id, 'service_report', ['attendance' => $current]);
				}
		
				return $this->response->setJSON([
					'status' => 'success',
					'message' => 'Marked as ' . ucfirst($status_text) . '.'
				]);
		
			} else {
				// ✅ Record exists — Update status
				$current_status = strtolower($this->Crud->read_field('id', $existing_attendance_id, 'service_attendance', 'status'));
				$new_status = ($mark == 1) ? 'present' : 'absent';
		
				if ($current_status !== $new_status) {
					$update = $this->Crud->updates('id', $existing_attendance_id, 'service_attendance', ['status' => $new_status]);
		
					if ($update > 0) {
						$current = (int) $this->Crud->read_field('id', $service_id, 'service_report', 'attendance');
		
						if ($mark == 1 && $current_status == 'absent') {
							// Absent ➔ Present
							$current++;
						} elseif ($mark == 0 && $current_status == 'present' && $current > 0) {
							// Present ➔ Absent
							$current--;
						}
		
						$this->Crud->updates('id', $service_id, 'service_report', ['attendance' => $current]);
					}
				}
		
				return $this->response->setJSON([
					'status' => 'success',
					'message' => 'Marked as ' . ucfirst($new_status) . '.'
				]);
			}
		}
		
		
		if($param1 == 'mark_convert'){
			$member_id = $this->request->getPost('member_id');
			$service = $this->request->getPost('service_id'); // e.g., "Sunday Service"
			$mark = $this->request->getPost('mark'); // e.g., "Sunday Service"
			$type = $this->request->getPost('type'); // e.g., "Sunday Service"
			$church_id = $this->request->getPost('church_id');

			// Get all service reports for today for the specified church
			$query = $this->Crud->read2_order('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'date', 'asc');

			// Target occurrence number (you can calculate or pass it from frontend)
			$occurrence = 0;
			$service_report_id = $service;

			if(empty($service_report_id)){
				return $this->response->setJSON([
					'status' => 'warning',
					'message' => 'Service report not found.'
				]);
			}

			if($type == 'member'){
				$exists = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_report_id, 'service_attendance', 'id');
				
				$this->Crud->updates('id', $exists, 'service_attendance', ['new_convert'=>$mark,
				'monitor_type' => $attend_type,
				'monitor_id' => $log_id,]);
			}

			if($type == 'ft'){
				$this->Crud->updates('id', $member_id, 'visitors', ['new_convert'=>$mark]);
			}
			return $this->response->setJSON([
				'status' => 'success',
				'message' => 'Member Neww Convert Updated'
			]);
			
		
		}

		if ($param1 === 'mark_absent') {
			$member_id = $this->request->getPost('member_id');
			$service = $this->request->getPost('service_id');
			$reason = $this->request->getPost('reason');
			$church_id = $this->request->getPost('church_id');
		
			// 🛡️ Validation
			if (!$member_id || !$service || !$church_id || !$reason) {
				return $this->response->setJSON([
					'status' => 'error',
					'message' => 'All fields are required.'
				]);
			}
		
			$service_report_id = $service;
		
			if (empty($service_report_id)) {
				return $this->response->setJSON([
					'status' => 'warning',
					'message' => 'Service report not found.'
				]);
			}
		
			// ✅ Check if attendance record already exists
			$attendance_id = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_report_id, 'service_attendance', 'id');
		
			if (empty($attendance_id)) {
				// 💾 Insert new record as absent
				$this->Crud->create('service_attendance', [
					'member_id' => $member_id,
					'service_id' => $service_report_id,
					'church_id' => $church_id,
					'status' => 'absent',
					'reason' => $reason,
					'monitor_type' => $attend_type ?? 'admin',
					'monitor_id' => $log_id ?? 0,
					'reg_date' => date('Y-m-d H:i:s')
				]);
		
				return $this->response->setJSON([
					'status' => 'success',
					'message' => 'Marked as absent.'
				]);
		
			} else {
				// ✏️ Update existing record: status and reason
				$this->Crud->updates('id', $attendance_id, 'service_attendance', [
					'status' => 'absent',
					'reason' => $reason
				]);
		
				return $this->response->setJSON([
					'status' => 'success',
					'message' => 'Updated reason for absence.'
				]);
			}
		}
		
		

		if($param1 == 'get_attendance_by_service'){
			$service = $this->request->getPost('service'); // 1, 2, 3...
			$cell_id = $this->request->getPost('cell_id'); // 1, 2, 3...
			$church_id = session()->get('td_church_id');
		
			$cell_id = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
			$query = $this->Crud->read2_order('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'date', 'asc');
		
			$occurrence = 0;
			$service_report_id = $service;
			
			// echo $cell_id;
			$query = $this->Crud->read2_order('is_member', 1, 'cell_id', $cell_id, 'user', 'surname', 'asc');
		
			$response = '';
		
			if (!empty($query)) {
				$response .= '<div class="table-responsive"><table class="table table-hover">';
				foreach ($query as $q) {
					// echo $q->id.' '.$service_report_id;
					
						$status = strtolower($this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'status'));
					
						// If absent, fetch the reason (optional)
						$absent_reason = '';
						if ($status == 'absent') {
							$absent_reason = $this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'reason');
						}
						$email = '';
						if(!empty($q->email)){
							$email = $this->Crud->mask_email($q->email);
						}
						$phone = '';
						if(!empty($q->phone)){
							$phone = $this->Crud->mask_phone($q->phone);
						}
						$response .= '
						<tr>
							<td>' . ucwords(strtolower($q->firstname . ' ' . $q->surname . ' ' . $q->othername)) . '</td>
							<td>'.($email).'</td>
							<td>'.($phone).'</td>
							<td>
								<div class="custom-control custom-switch">
									<input type="checkbox"
										class="custom-control-input mark-present-switch"
										id="presentSwitchm_'.$q->id.'"
										data-member-id="'.$q->id.'"
										'.($status == 'present' ? 'checked' : '').'>
									<label class="custom-control-label" for="presentSwitchm_'.$q->id.'">Mark Present</label>
								</div>
					
								<div class="custom-control custom-switch mb-1">
									<input type="checkbox"
										class="custom-control-input mark-absent-switch"
										id="absentSwitchm_'.$q->id.'"
										data-member-id="'.$q->id.'"
										'.($status == 'absent' ? 'checked' : '').'>
									<label class="custom-control-label" for="absentSwitchm_'.$q->id.'">Absent</label>
								</div>
					
								<div id="absent_reason_wrapper_'.$q->id.'" style="display: '.($status == 'absent' ? 'block' : 'none').';" class="mt-2 form-group absent_reason_wrapper_'.$q->id.'">
									<label for="absent_reason_'.$q->id.'" class="form-label">Reason for Absence</label><br>
									<select class="js-select2 reason-select w-100" data-search="on" name="absent_reason" id="absent_reason_'.$q->id.'" data-member-id="'.$q->id.'">
										<option value="">-- Select Reason --</option>
										<option '.($absent_reason == 'Out of Town' ? 'selected' : '').'>Out of Town</option>
										<option '.($absent_reason == 'Gone to School' ? 'selected' : '').'>Gone to School</option>
										<option '.($absent_reason == 'Health Challenges' ? 'selected' : '').'>Health Challenges</option>
										<option '.($absent_reason == 'Challenges at Work' ? 'selected' : '').'>Challenges at Work</option>
										<option '.($absent_reason == 'Challenges at Home' ? 'selected' : '').'>Challenges at Home</option>
										<option '.($absent_reason == 'Financial Constraint' ? 'selected' : '').'>Financial Constraint</option>
										<option '.($absent_reason == 'Absent without reason' ? 'selected' : '').'>Absent without reason</option>
										<option '.($absent_reason == 'Offence' ? 'selected' : '').'>Offence</option>
										<option '.($absent_reason == 'Irregular' ? 'selected' : '').'>Irregular</option>
										<option '.($absent_reason == 'Not Yet Attending Church' ? 'selected' : '').'>Not Yet Attending Church</option>
										<option '.(stripos($absent_reason, 'Other') !== false ? 'selected' : '').'>Other – Specify</option>
									</select>
					
									<input type="text" class="form-control form-control-sm mt-2 other-reason-input" id="other_reason_'.$q->id.'" placeholder="Please specify" style="display: '.(stripos($absent_reason, 'Other') !== false ? 'block' : 'none').';" value="'.(stripos($absent_reason, 'Other') !== false ? $absent_reason : '').'" />
								</div>
					
								<span id="resp_'.$q->id.'"></span>
							</td>
						</tr>';
					
				}
				$response .= '</table></div>
				<script>$(".js-select2").select2();</script>
				';
			} else {
				$response .= '<div class="text-center text-muted"><em class="icon ni ni-user" style="font-size:150px;"></em><br><br>No Record Found</div>';
			}
		
			echo $response;
			die;
		}
		
		if($param1 == 'get_attendance_metrics'){
			$service = $this->request->getPost('service'); // e.g. 1st, 2nd, 3rd occurrence
			$church_id = session()->get('td_church_id');
			$log_id = session()->get('td_attend_id');
		
			$cell_id = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
			$query = $this->Crud->read2_order('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'date', 'asc');
		
			$occurrence = 0;
			$service_report_id = $service;
			$metric_response = '';
			$general_response = '';
		
			
		
			// Count members in the cell
			$total_members = $this->Crud->check2('church_id', $church_id, 'is_member', 1, 'user');
			$male = 0;$female = 0;$children = 0;
			$present = 0;$absent = 0;
			$firstTimer = 0;

			
			// Count present for selected service
			$mem_query = $this->Crud->read2('service_id', $service_report_id, 'church_id', $church_id, 'service_attendance');
			if(!empty($mem_query)){
				foreach($mem_query as $mq){
					if($mq->status == 'present')$present++;
					if($mq->status == 'absent')$absent++;
					
					if(strtolower($this->Crud->read_field('id', $mq->member_id, 'user', 'gender')) == 'male'){
						$male++;
					}

					if(strtolower($this->Crud->read_field('id', $mq->member_id, 'user', 'gender')) == 'female'){
						$female++;
					}
				}
			}
			$unmarked = $total_members - $present - $absent;

			// echo $cell_id;
			$query = $this->Crud->read2_order('is_member', 1, 'church_id', $church_id, 'user', 'surname', 'asc');
			$guest_query = $this->Crud->read2('guest', 1,  'service_id', $service_report_id,  'service_attendance');
			$timer_query = $this->Crud->read3_order('source_type', 'service', 'source_id', $service_report_id,  'church_id', $church_id, 'visitors', 'fullname', 'asc');
		
			$response = '';
			$response .= '<div class="table-responsive"><table class="table table-hover">';
					
			if (!empty($query)) {
				foreach ($query as $q) {
					
					$status = strtolower($this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'status')) ?? 'absent';
					$new_convert = $this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'new_convert') ?? 0;
			
					// Fetch reason only if absent
					$absent_reason = '';
					if ($status == 'absent') {
						$absent_reason = $this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'reason') ?? '';
					}
			
					$email = !empty($q->email) ? $this->Crud->mask_email($q->email) : '';
					$phone = !empty($q->phone) ? $this->Crud->mask_phone($q->phone) : '';
			
					$response .= '
					<tr>
						<td>' . ucwords(strtolower(trim($q->firstname . ' ' . $q->surname . ' ' . $q->othername))) . '</td>
						<td>' . $email . '</td>
						<td>' . $phone . '</td>
						<td>
							<div class="custom-control custom-switch mb-2">
								<input type="checkbox"
									class="custom-control-input mark-present-switch"
									id="presentSwitch_'.$q->id.'"
									data-member-id="'.$q->id.'"
									'.($status == 'present' ? 'checked' : '').'>
								<label class="custom-control-label" for="presentSwitch_'.$q->id.'">Mark Present</label>
							</div>';
			
					// Reason Wrapper
					$response .= '
							<div id="absent_reason_wrapper_'.$q->id.'" class="mt-2 form-group" style="display: '.($status == 'present' ? 'none' : 'block').';">
								<label for="absent_reason_'.$q->id.'" class="form-label">Reason for Absence</label>
								<select class="form-select reason-select" name="absent_reason" id="absent_reason_'.$q->id.'" data-member-id="'.$q->id.'">
									<option value="">-- Select Reason --</option>
									<option '.($absent_reason == 'Out of Town' ? 'selected' : '').'>Out of Town</option>
									<option '.($absent_reason == 'Gone to School' ? 'selected' : '').'>Gone to School</option>
									<option '.($absent_reason == 'Health Challenges' ? 'selected' : '').'>Health Challenges</option>
									<option '.($absent_reason == 'Challenges at Work' ? 'selected' : '').'>Challenges at Work</option>
									<option '.($absent_reason == 'Challenges at Home' ? 'selected' : '').'>Challenges at Home</option>
									<option '.($absent_reason == 'Financial Constraint' ? 'selected' : '').'>Financial Constraint</option>
									<option '.($absent_reason == 'Absent without reason' ? 'selected' : '').'>Absent without reason</option>
									<option '.($absent_reason == 'Offence' ? 'selected' : '').'>Offence</option>
									<option '.($absent_reason == 'Irregular' ? 'selected' : '').'>Irregular</option>
									<option '.($absent_reason == 'Not Yet Attending Church' ? 'selected' : '').'>Not Yet Attending Church</option>
									<option '.(stripos($absent_reason, 'Other') !== false ? 'selected' : '').'>Other – Specify</option>
								</select>
			
								<input type="text"
									class="form-control form-control-sm mt-2 other-reason-input"
									id="other_reason_'.$q->id.'"
									placeholder="Please specify"
									style="display: '.(stripos($absent_reason, 'Other') !== false ? 'block' : 'none').';"
									value="'.(stripos($absent_reason, 'Other') !== false ? $absent_reason : '').'" />
							</div>
			
							<span id="resp_'.$q->id.'"></span>
						</td>
						<td>
							<div class="custom-control custom-switch mb-1">
								<input type="checkbox"
									class="custom-control-input mark-convert-switch"
									id="convertSwitch_'.$q->id.'"
									data-type="member"
									data-member-id="'.$q->id.'"
									'.($new_convert == '1' ? 'checked' : '').'>
								<label class="custom-control-label" for="convertSwitch_'.$q->id.'">New Convert</label>
							</div>
							<span id="con_resp_'.$q->id.'"></span>
						</td>
					</tr>';
				}
			} else {
				$response .='<tr><td><div class="text-center text-muted"><em class="icon ni ni-user" style="font-size:150px;"></em><br><br>No Record Found</div></td></tr>';
			}
			if (!empty($timer_query)) {
				foreach ($timer_query as $q) {
					$email = '';
					if(!empty($q->email)){
						$email = $this->Crud->mask_email($q->email);
					}
					$phone = '';
					if(!empty($q->phone)){
						$phone = $this->Crud->mask_phone($q->phone);
					}
					$status ='present';
					$response .= '
					<tr>
						<td>' . ucwords(strtolower($q->fullname)).'<br><span class="small text-info">FT</span></td>
						<td>'.($email).'</td>
						<td>'.($phone).'</td>
						<td>Present</td>
						<td>
							<div class="custom-control custom-switch mb-1">
								<input type="checkbox"
									class="custom-control-input mark-convert-switch"
									id="convertSwitch_'.$q->id.'" data-type="ft"
									data-member-id="'.$q->id.'"
									'.($q->new_convert == '1' ? 'checked' : '').'>
								<label class="custom-control-label" for="convertSwitch_'.$q->id.'">New Convert</label>
							</div>
							<span id="con_resp_'.$q->id.'"></span>
						</td>
					</tr>';
				
					
				}
				
			} 
			if (!empty($guest_query)) {
				foreach ($guest_query as $q) {
					$firstname = $this->Crud->read_field('id', $q->member_id, 'user', 'firstname');
					$surname = $this->Crud->read_field('id', $q->member_id, 'user', 'surname');
					$othername = $this->Crud->read_field('id', $q->member_id, 'user', 'othername');
					$email = $this->Crud->read_field('id', $q->member_id, 'user', 'email');
					$phone = $this->Crud->read_field('id', $q->member_id, 'user', 'phone');
					$church_id = $this->Crud->read_field('id', $q->member_id, 'user', 'church_id');
					$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
					$emailz = '';
					if(!empty($email)){
						$emailz = $this->Crud->mask_email($email);
					}
					$phonez = '';
					if(!empty($phone)){
						$phonez = $this->Crud->mask_phone($phone);
					}
					$status ='present';
					$response .= '
					<tr>
						<td>' . ucwords(strtolower($firstname.' '.$othername.' '.$surname)).'<br><span class="small text-info">'.ucwords($church).'</span></td>
						<td>'.($emailz).'</td>
						<td>'.($phonez).'</td>
						<td>Present</td>
						<td>
							<div class="custom-control custom-switch mb-1">
								<input type="checkbox"
									class="custom-control-input mark-convert-switch"
									id="convertSwitch_'.$q->member_id.'" data-type="ft"
									data-member-id="'.$q->member_id.'"
									'.($q->new_convert == '1' ? 'checked' : '').'>
								<label class="custom-control-label" for="convertSwitch_'.$q->member_id.'">New Convert</label>
							</div>
							<span id="con_resp_'.$q->member_id.'"></span>
						</td>
					</tr>';
				
					
				}
				
			} 
			$response .= '</table></div>';



			$general_response .= '<div class="table-responsive"><table class="table table-hover">';
			if (!empty($query)) {
				$general_response .= '<tr><td colspan="8"><h6 class="text-center">Member</h6></td></tr>';
				foreach ($query as $q) {
					
					$status = strtolower($this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'status'));
					if ($status != 'present') continue;
					// If absent, fetch the reason (optional)
					$absent_reason = '';
					if ($status == 'absent') {
						$absent_reason = $this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'reason');
					}
					$email = '';
					if(!empty($q->email)){
						$email = $this->Crud->mask_email($q->email);
					}
					$phone = '';
					if(!empty($q->phone)){
						$phone = $this->Crud->mask_phone($q->phone);
					}
					$general_response .= '
					<tr>
						<td>' . ucwords(strtolower($q->firstname . ' ' . $q->surname . ' ' . $q->othername)) . '</td>
						<td>'.($email).'</td>
						<td>'.($phone).'</td>
						<td>
							'.ucwords($status).'
						</td>
					</tr>';
				
					
				}
				
			} else {
				$general_response .= '<tr><td><div class="text-center text-muted"><em class="icon ni ni-user" style="font-size:150px;"></em><br><br>No Record Found</div></td></tr>';
			}
			if (!empty($timer_query)) {
				$general_response .= '<tr><td colspan="8"><h6  class="text-center">First Timer</h6></td></tr>';
				
				foreach ($timer_query as $q) {
					$firstTimer++;
					$status = 'present';
					$email = '';
						if(!empty($email)){
							$email = $this->Crud->mask_email($email);
						}
						$phone = '';
						if(!empty($phone)){
							$phone = $this->Crud->mask_phone($phone);
						}
					$general_response .= '
					<tr>
						<td>' . ucwords(strtolower($q->fullname)).'</td>
						<td>'.($email).'</td>
						<td>'.($phone).'</td>
						<td>
							'.ucwords($status).'
						</td>
					</tr>';
				
					
				}
				
			}
			if (!empty($guest_query)) {
				$general_response .= '<tr><td colspan="8"><h6  class="text-center">Guest Member</h6></td></tr>';
				
				foreach ($guest_query as $q) {
					$firstname = $this->Crud->read_field('id', $q->member_id, 'user', 'firstname');
					$surname = $this->Crud->read_field('id', $q->member_id, 'user', 'surname');
					$othername = $this->Crud->read_field('id', $q->member_id, 'user', 'othername');
					$email = $this->Crud->read_field('id', $q->member_id, 'user', 'email');
					$phone = $this->Crud->read_field('id', $q->member_id, 'user', 'phone');
					$church_id = $this->Crud->read_field('id', $q->member_id, 'user', 'church_id');
					$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
					$email = '';
						if(!empty($email)){
							$email = $this->Crud->mask_email($email);
						}
						$phone = '';
						if(!empty($phone)){
							$phone = $this->Crud->mask_phone($phone);
						}
					$status ='present';
					$general_response .= '
					<tr>
						<td>' . ucwords(strtolower($firstname.' '.$othername.' '.$surname)).'<br><span class="small text-info">'.ucwords($church).'</span></td>
						<td>'.($email).'</td>
						<td>'.($phone).'</td>
						<td>Present</td>
						
					</tr>';
				
					
				}
				
			}

			$general_response .= '</table></div>
				
				';
		
			
			return $this->response->setJSON([
				'membership' => ($total_members + $firstTimer),
				'present' => $present,
				'absent' => $absent,
				'male' => $male,
				'female' => $female,
				'firstTimer' => $firstTimer,
				'unmarked' => $unmarked,
				'metric_response' => $response,
				'general_response' => $general_response
			]);
		}

		if($param1 == 'verify_password'){
			$entered = $this->request->getPost('password');
			$log_id = session()->get('td_attend_id');

			$actual_hashed = $this->Crud->read_field('id', $log_id, 'user', 'password');

			// Assuming the actual password is hashed with md5 (not recommended, but let's match your current setup)
			// echo md5($entered).' '.$actual_hashed;
			if (md5($entered) == $actual_hashed) {
				return $this->response->setJSON(['status' => 'success']);
			} else {
				return $this->response->setJSON(['status' => 'error']);
			}
		}
		if ($param1 == 'verify_member') {
			$member_id = $this->request->getPost('member_id');
			$church_id = $this->request->getPost('church_id');
			$service = $this->request->getPost('service_id');
		
			// Get all service reports for today for the specified church
			$query = $this->Crud->read2_order('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'date', 'asc');
		
			$occurrence = 0;
			$service_report_id = $service;

		
			if (empty($member_id) || empty($church_id) || empty($service_report_id)) {
				return $this->response->setJSON([
					'status' => 'error',
					'message' => 'Missing data'
				]);
			}
		
			// Check if user exists
			$member = $this->Crud->read_single('id', $member_id, 'user');
		
			if (empty($member)) {
				return $this->response->setJSON([
					'status' => 'error',
					'message' => 'Member not found'
				]);
			}
		
			$member = $member[0];
		
			// Check if member belongs to church
			$is_guest = ($member->church_id != $church_id) ? 1 : 0;
		
			// Check if already marked
			$attendance_id = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_report_id, 'service_attendance', 'id');
		
			if ($attendance_id > 0) {
				return $this->response->setJSON([
					'status' => 'warning',
					'message' => 'Already marked attendance.'
				]);
			}
		
			$photo = !empty($member->img_id) ? base_url($member->img_id) : base_url('assets/images/avatar.png');
		
			return $this->response->setJSON([
				'status' => 'ok',
				'member' => [
					'id' => $member->id,
					'name' => ucwords(trim($member->surname . ' ' . $member->firstname)),
					'email' => !empty($member->email) ? $this->Crud->mask_email($member->email) : '',
					'phone' => !empty($member->phone) ? $this->Crud->mask_email($member->phone) : '',

					'img' => $photo,
					'guest' => $is_guest
				]
			]);
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
			$church_id = $param2;
			$ministry_id = $param3;

			// Validate inputs
			if (empty($church_id) && empty($ministry_id)) {
				echo json_encode([]);
				return;
			}
			
			$spouses = $this->Crud->read2('spouse_id', 0, 'ministry_id', $ministry_id, 'user');
			if(!empty($church_id)){
			    $spouses = $this->Crud->read2('spouse_id', 0, 'church_id', $church_id, 'user');
			}

			// Format response
			$response = [];
			$response[] = [
				'id' => 0,
				'name' => ucwords('Not Available'),
			];
			if(!empty($spouses)){
    			foreach ($spouses as $spouse) {
    				if($spouse->id == $log_id)continue;
    				if($spouse->family_status != 'married')continue;
    				$response[] = [
    					'id' => $spouse->id,
    					'name' => ucwords($spouse->surname . ' ' . $spouse->firstname),
    				];
    			}
			}

			echo json_encode($response);
			die;
		}
		
		if ($param1 == 'mark_attendance') {
			$member_id = $this->request->getPost('member_id');
			$service = $this->request->getPost('service_id');
			$church_id = $this->request->getPost('church_id');
		
			// Validate inputs
			if (empty($member_id) || empty($service) || empty($church_id)) {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid data']);
			}
			
		
			
			$service_report_id = $service;
		
			if (empty($service_report_id)) {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Service report not found']);
			}
		
			// Check if already marked
			$exists = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_report_id, 'service_attendance', 'id');
		
			if ($exists > 0) {
				return $this->response->setJSON(['status' => 'warning', 'message' => 'Already marked.']);
			}
			
			// Check if user belongs to church
			$member = $this->Crud->read_single('id', $member_id, 'user');
			if (empty($member)) {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Member not found']);
			}
			$member = $member[0];
		
			$is_guest = ($member->church_id != $church_id) ? 1 : 0;
		
			// Insert attendance
			$this->Crud->create('service_attendance', [
				'member_id'  => $member_id,
				'service_id' => $service_report_id,
				'church_id'  => $church_id,
				'status'     => 'present',
				'monitor_type' => $attend_type,
				'monitor_id' => $log_id,
				'guest'      => $is_guest,
				'reg_date' => date(fdate)
			]);
		
			return $this->response->setJSON([
				'status' => 'success',
				'message' => $is_guest ? 'Marked as guest attendee' : 'Marked as present'
			]);
		}
		

		// manage record
		if($param1 == 'manage') {
			$table = 'visitors';
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
			} elseif($param2 == 'member'){
				
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
					$family_position = htmlspecialchars(trim($this->request->getVar('family_position')), ENT_QUOTES, 'UTF-8');
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
					$ins_data['gender'] = $gender;
					$ins_data['address'] = $address;
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
					$ins_data['dept_id'] = json_encode($sanitized_dept_id);
					$ins_data['dept_role'] = json_encode($sanitized_dept_role_id);
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
						
					
					$ins_data['activate'] = 1;
					$ins_data['reg_date'] = date(fdate);
					$ins_rec = $this->Crud->create('user', $ins_data);
					if($ins_rec > 0) {
						if(!empty($spouse_id)){
							$this->Crud->updates('id', $spouse_id, 'user', array('spouse_id'=>$ins_rec, 'family_status'=>'married'));
						}
						$email = strtolower(trim($email));
						$phone = preg_replace('/\D/', '', $phone); // clean phone
				
						$isDuplicate = false;

						// Check for duplicate email in the database
						if (!empty($email) && $this->Crud->check('email', $email, 'user') > 0) {
							$isDuplicate = true;
						}

						// Check for duplicate phone in the database
						if (!empty($phone) && $this->Crud->check('phone', $phone, 'user') > 0) {
							$isDuplicate = true;
						}


						// Final action: delete if duplicate, else mark as not duplicate
						if ($isDuplicate) {
							$this->Crud->updates('id', $ins_rec, 'user', ['is_duplicate' => 1]);
						}

						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $ins_rec, 'user', 'surname');
						$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));

						$user_no = 'CEAM-00'.$ins_rec;
						$qr_content = 'USER-00' . $ins_rec;

						// Generate QR
						$qr = $this->Crud->qrcode($qr_content); // This should return an array
						$qr_code_url = site_url($qr['path']); // adjust as per actual path
				
						// Save to DB
						$this->Crud->updates('id', $ins_rec, 'user', ['qrcode' => $qr['path']]);
						$church = $this->Crud->read_field('id', $church_id,'church', 'name');
						$action = $by.' created Membership ('.$code.') Record';
						$this->Crud->activity('user', $ins_rec, $action);
						$name = ucwords($firstname.' '.$othername.' '.$lastname);
						$body = '
							Dear '.ucwords(strtolower($title.' '.$firstname)).',<br><br>

								Grace and peace to you!<br><br>
								
								Welcome to '.ucwords($church).' - a place where love abounds, faith grows, and your walk with God is nurtured. We are truly excited to have you as a vital part of our family.<br><br>
								
								As part of our commitment to serving you better, we`ve introduced a smart and simple way to stay connected through our new digital platform. You now have access to your personalized QR Code, which you`ll use to easily mark your attendance during our services and special events.<br><br>
								
								Why this matters:
								Your presence matters deeply to us. This small step helps us shepherd you more effectively, stay in touch, and continue to build a strong, united family of faith.<br><br>
								
								Here is your personal QR Code:<br><br>
								<img src="' . $qr_code_url . '" alt="QR Code" width="150" height="150"><br><br>
								
								Every time you attend church, simply scan your code — it`s quick, easy, and ensures you never miss a moment of connection.<br><br>
								
								With love and blessings,<br><br>
								
								'.ucwords($church).'
								Digital Team
								
						';

						
						
						// $this->Crud->send_email($email, 'Membership Account', $body);

						//Mark Attendance 
						$service = $this->request->getPost('service'); // e.g., "Sunday Service"
						$service_report_id = $service;

						// Do DB logic here, example:
						$this->Crud->create('service_attendance',[
							'member_id' => $ins_rec,
							'service_id' => $service_report_id,
							'church_id' => $church_id,
							'monitor_type' => $attend_type,
							'monitor_id' => $log_id,
							'status' => 'present',
							'reg_date' => date(fdate)
						]);

						echo $this->Crud->msg('success', 'Membership Created!<br>Service Attendance!<br> Thank You!.');
						
						echo '<script>location.reload(false);</script>';
					} else {
						echo $this->Crud->msg('danger', 'Please try later');	
					}	
				
				

					die;	
				}
			} else {
				
				if($this->request->getMethod() == 'post'){
					$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                    $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                   
					$occurrence = 0;
					$service = $this->request->getPost('service');
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
					
					$service_report_id = $service;

					$name = $this->request->getPost('firstname').' '.$this->request->getPost('surname');
					$ins_data = [
						'ministry_id'        => $ministry_id,
						'channel'          	 => $channel,
						'church_id'          => $church_id,
						'title'              => $this->request->getPost('title'),
						'invited_by'         => $this->request->getPost('invited_by'),
						'fullname'           => $name,
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
						'source_type'         	=> 'service',
						'source_id'         	=> $service_report_id,
						'reg_date'           => date('Y-m-d H:i:s'),
					];
			

					$upd_rec = $this->Crud->create($table, $ins_data);
					if($upd_rec > 0) {
						echo $this->Crud->msg('success', translate_phrase('First Timer Record Submitted'));

						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $upd_rec, 'visitors', 'fullname');
						$action = $by.' submitted First Timer ('.$upd_rec.') Record';
						$this->Crud->activity('first_timer', $upd_rec, $action, $log_id);

						echo '<script>location.reload(false);</script>';
					} else {
						echo $this->Crud->msg('info', translate_phrase('No Changes'));	
					}
					 
					exit;	
				}
			}
		}
		
		$username = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        
		$data['log_id'] = $log_id;
        $data['log_name'] = $username;
        $data['current_language'] = $this->session->get('current_language');
		$data['attend_type'] = $this->session->get('td_attend_type');
		$data['church_id'] = $this->session->get('td_church_id');
		$data['cell_id'] = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
		
        $data['role'] = $role;
		
		$data['title'] = translate_phrase('Attendance Dashboard').' - '.app_name;
		return view('attendance/dashboard', $data);
	}

    public function dashboard($param1='', $param2='', $param3='') {
        // check login
        $log_id = $this->session->get('td_attend_id');
        // if(empty($log_id)) return redirect()->to(site_url('attendance'));
	    $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
	    $cell_id = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
      
		$active = $this->Crud->read2('date', date('Y-m-d'), 'church_id', $church_id, 'service_report');
		if(empty($active)){
			return redirect()->to(site_url('attendance'));
		}
		
        $mod = 'attendance/dashboard';
        
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        
        $username = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
        
        $data['log_id'] = $log_id;
        $data['param1'] = $param1;
        $data['param2'] = $param2;
        $data['param3'] = $param3;
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2;}
		if($param3){$form_link .= '/'.$param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = rtrim($form_link, '/');
		
		
		$attend_type = $this->session->get('td_attend_type');


		// manage record
		if($param1 == 'manage') {
			$table = 'visitors';
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
			} elseif($param2 == 'member'){
				
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
					$chat_handle   = htmlspecialchars(trim($this->request->getVar('chat_handle')), ENT_QUOTES, 'UTF-8');
					$address       = htmlspecialchars(trim($this->request->getVar('address')), ENT_QUOTES, 'UTF-8');
					$family_status = htmlspecialchars(trim($this->request->getVar('family_status')), ENT_QUOTES, 'UTF-8');
					$family_position = htmlspecialchars(trim($this->request->getVar('family_position')), ENT_QUOTES, 'UTF-8');
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
						// Check if email already exists
					$emailExists = $this->Crud->check('email', $email, 'user');
					$family_position = strtolower($family_position);
					if ($emailExists) {
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
					$ins_data['gender'] = $gender;
					$ins_data['address'] = $address;
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
					$ins_data['dept_id'] = json_encode($sanitized_dept_id);
					$ins_data['dept_role'] = json_encode($sanitized_dept_role_id);
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
						
					
					$ins_data['activate'] = 1;
					$ins_data['reg_date'] = date(fdate);
					$ins_rec = $this->Crud->create('user', $ins_data);
					if($ins_rec > 0) {
						if(!empty($spouse_id)){
							$this->Crud->updates('id', $spouse_id, 'user', array('spouse_id'=>$ins_rec, 'family_status'=>'married'));
						}
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $ins_rec, 'user', 'surname');
						$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));

						$user_no = 'CEAM-00'.$ins_rec;
						$qr_content = 'USER-00' . $ins_rec;

						// Generate QR
						$qr = $this->Crud->qrcode($qr_content); // This should return an array
						$qr_code_url = site_url($qr['path']); // adjust as per actual path
				
						// Save to DB
						$this->Crud->updates('id', $ins_rec, 'user', ['qrcode' => $qr['path']]);
						$church = $this->Crud->read_field('id', $church_id,'church', 'name');
						$action = $by.' created Membership ('.$code.') Record';
						$this->Crud->activity('user', $ins_rec, $action);
						$name = ucwords($firstname . ' ' . $othername . ' ' . $lastname);
						$churchName = ucwords($church);
						$userNo = 'CEAM-00' . $ins_rec;
						$reset_link = site_url('auth/email_verify?uid=' . $userNo);
						$qr_code_url = site_url($qr['path']);

						$subject = 'A Warm Welcome to '.ucwords($church).' - Your Membership & QR Code Access';

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

						$this->Crud->mailgun($email, $subject, $body, $church);

						//Mark Attendance 
						$service = $this->request->getPost('service'); // e.g., "Sunday Service"
						// Get all service reports for today for the specified church
						$query = $this->Crud->read2_order('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'date', 'asc');

						// Target occurrence number (you can calculate or pass it from frontend)
						$occurrence = 0;
						$service_report_id = 0;

						if (!empty($query)) {
							foreach ($query as $q) {
								$occurrence++;

								if ($occurrence == $service) {
									$service_report_id = $q->id; // grab the ID of the N-th occurrence
									break;
								}
							
							}
						}
						// Do DB logic here, example:
						$this->Crud->create('service_attendance',[
							'member_id' => $ins_rec,
							'service_id' => $service_report_id,
							'church_id' => $church_id,
							'monitor_type' => $attend_type,
							'monitor_id' => $log_id,
							'status' => 'present',
							'reg_date' => date(fdate)
						]);

						echo $this->Crud->msg('success', 'Membership Created!<br>Service Attendance!<br> Thank You!.');
						
						echo '<script>location.reload(false);</script>';
					} else {
						echo $this->Crud->msg('danger', 'Please try later');	
					}	
				
				

					die;	
				}
			} else {
				
				if($this->request->getMethod() == 'post'){
					$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                    $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                   
					$occurrence = 0;
					$service = $this->request->getPost('service');
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
					
					$service_report_id = 0;
					// Get all service reports for today for the specified church
					$query = $this->Crud->read2_order('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'date', 'asc');

					if (!empty($query)) {
						foreach ($query as $q) {
							$occurrence++;

							if ($occurrence == $service) {
								$service_report_id = $q->id; // grab the ID of the N-th occurrence
								break;
							}
						
						}
					}
					$name = $this->request->getPost('firstname').' '.$this->request->getPost('surname');
					$ins_data = [
						'ministry_id'        => $ministry_id,
						'channel'          	 => $channel,
						'church_id'          => $church_id,
						'title'              => $this->request->getPost('title'),
						'invited_by'         => $this->request->getPost('invited_by'),
						'fullname'           => $name,
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
						'source_type'         	=> 'service',
						'source_id'         	=> $service_report_id,
						'reg_date'           => date('Y-m-d H:i:s'),
					];
			

					$upd_rec = $this->Crud->create($table, $ins_data);
					if($upd_rec > 0) {
						echo $this->Crud->msg('success', translate_phrase('First Timer Record Submitted'));

						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $upd_rec, 'visitors', 'fullname');
						$action = $by.' submitted First Timer ('.$upd_rec.') Record';
						$this->Crud->activity('first_timer', $upd_rec, $action, $log_id);

						echo '<script>location.reload(false);</script>';
					} else {
						echo $this->Crud->msg('info', translate_phrase('No Changes'));	
					}
					 
					exit;	
				}
			}
		}
		

        $data['log_id'] = $log_id;
        $data['log_name'] = $username;
        $data['current_language'] = $this->session->get('current_language');
		$data['attend_type'] = $this->session->get('td_attend_type');
		$data['church_id'] = $this->session->get('td_church_id');
		$church_id = $this->session->get('td_church_id');
		$data['cell_id'] = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
		$data['type_id'] = $this->Crud->read_field2('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'type');
		$type_id = $this->Crud->read_field2('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'type');
		$data['service_count'] = $this->Crud->read3('status', 0, 'date', date('Y-m-d'), 'church_id', $church_id, 'service_report');
		$data['church'] = $this->Crud->read_field('id', $church_id, 'church', 'name');
		$data['type'] = $this->Crud->read_field('id', $type_id, 'service_type', 'name');
        $data['role'] = $role;
		if($param1 == 'manage') { // view for form data posting
			return view('attendance/dashboard_form', $data);
		} else { 
			$data['title'] = translate_phrase('Attendance Dashboard').' - '.app_name;
			$data['page_active'] = $mod;
			return view('attendance/dashboard', $data);
		}
    }

	public function timer($code='') {
        // check login
      
        $mod = 'attendance/timer';
        
		$data['church_id'] = '';
		$data['church'] = '';
		if ($code) {
            session()->set('invite_code', $code); // ✅ Save to session
			$data['church_id'] = $this->Crud->read_field('first_timer_link', $code, 'church', 'id');
			$data['church'] = $this->Crud->read_field('first_timer_link', $code, 'church', 'name');
        }
		$data['code'] = $code;

		if($this->request->getMethod() == 'post'){
			$church_id = $this->request->getPost('church_id');
			$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
		   

			// echo $church_id;
			// die;
			$occurrence = 0;
			$service = $this->request->getPost('service');
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
			
			$service_report_id = 0;
			// Get all service reports for today for the specified church
			$query = $this->Crud->read2_order('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'date', 'asc');

			if (!empty($query)) {
				foreach ($query as $q) {
					$occurrence++;

					if ($occurrence == $service) {
						$service_report_id = $q->id; // grab the ID of the N-th occurrence
						break;
					}
				
				}
			}
			$name = $this->request->getPost('firstname').' '.$this->request->getPost('surname');
			$ins_data = [
				'ministry_id'        => $ministry_id,
				'channel'          	 => $channel,
				'church_id'          => $church_id,
				'title'              => $this->request->getPost('title'),
				'invited_by'         => $this->request->getPost('invited_by'),
				'fullname'           => $name,
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
				'source_type'         	=> 'service',
				'source_id'         	=> $service_report_id,
				'reg_date'           => date('Y-m-d H:i:s'),
			];
	

			$upd_rec = $this->Crud->create('visitors', $ins_data);
			if($upd_rec > 0) {
				echo $this->Crud->msg('success', translate_phrase('First Timer Record Submitted'));

				///// store activities
				$by = $this->Crud->read_field('id', $upd_rec, 'user', 'firstname');
				$code = $this->Crud->read_field('id', $upd_rec, 'visitors', 'fullname');
				$action = $name.' submitted First Timer Record';
				$this->Crud->activity('first_timer', $upd_rec, $action, $upd_rec);

				echo '<script>location.reload(false);</script>';
			} else {
				echo $this->Crud->msg('info', translate_phrase('No Changes'));	
			}
			 
			exit;	
		}
		$data['attend_type'] = 'guest';
		$data['cell_id'] = '';
		
		$data['log_id'] = '0';

        $data['current_language'] = $this->session->get('current_language');
		$data['title'] = translate_phrase('First Timer').' - '.app_name;
		$data['page_active'] = $mod;
		return view('attendance/timer', $data);
	
    }

	public function member($code='') {
        // check login
      
        $mod = 'attendance/member';
        
		$data['church_id'] = '';
		$data['church'] = '';
		if ($code) {
            session()->set('invite_code', $code); // ✅ Save to session
			$data['church_id'] = $this->Crud->read_field('first_timer_link', $code, 'church', 'id');
			$data['church'] = $this->Crud->read_field('first_timer_link', $code, 'church', 'name');
        }
		$data['code'] = $code;

		
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
			$chat_handle   = htmlspecialchars(trim($this->request->getVar('chat_handle')), ENT_QUOTES, 'UTF-8');
			$address       = htmlspecialchars(trim($this->request->getVar('address')), ENT_QUOTES, 'UTF-8');
			$family_status = htmlspecialchars(trim($this->request->getVar('family_status')), ENT_QUOTES, 'UTF-8');
			$family_position = strtolower(htmlspecialchars(trim($this->request->getVar('family_position')), ENT_QUOTES, 'UTF-8'));
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
			$church_id              = htmlspecialchars(trim($this->request->getVar('church_id')), ENT_QUOTES, 'UTF-8');
			$img_id                 = htmlspecialchars(trim($this->request->getVar('img_id')), ENT_QUOTES, 'UTF-8');
			if(empty($family_position)){
				echo $this->Crud->msg('danger', 'Select Family Position!');
				die;
			}
			// Check if email already exists
			$emailExists = $this->Crud->check('email', $email, 'user');
			
			if ($emailExists) {
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
			$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
			
			
			

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
			$ins_data['dept_id'] = json_encode($sanitized_dept_id);
			$ins_data['dept_role'] = json_encode($sanitized_dept_role_id);
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
				
			
			$ins_data['activate'] = 1;
			$ins_data['reg_date'] = date(fdate);
			$ins_rec = $this->Crud->create('user', $ins_data);
			if($ins_rec > 0) {
				if (!empty($spouse_id)) {
					// Update the selected spouse (link back to this member)
					$this->Crud->updates('id', $spouse_id, 'user', [
						'spouse_id'     => $ins_rec,
						'family_status' => 'married'
					]);
				
					// Update the current member (link to selected spouse)
					$this->Crud->updates('id', $ins_rec, 'user', [
						'spouse_id'     => $spouse_id,
						'family_status' => 'married'
					]);
				}
				///// store activities
				$by = $this->Crud->read_field('id', $ins_rec, 'user', 'firstname');
				$code = $this->Crud->read_field('id', $ins_rec, 'user', 'surname');
				$this->Crud->updates('id', $ins_rec, 'user', array('user_no'=>'CEAM-00'.$ins_rec));

				$user_no = 'CEAM-00'.$ins_rec;
				$qr_content = 'USER-00' . $ins_rec;


				$isDuplicate = false;

				// Check for duplicate email in the database
				if (!empty($email) && $this->Crud->check('email', $email, 'user') > 0) {
					$isDuplicate = true;
				}

				// Check for duplicate phone in the database
				if (!empty($phone) && $this->Crud->check('phone', $phone, 'user') > 0) {
					$isDuplicate = true;
				}


				// Final action: delete if duplicate, else mark as not duplicate
				if ($isDuplicate) {
					$this->Crud->updates('id', $ins_rec, 'user', ['is_duplicate' => 1]);
				}

				// Generate QR
				$qr = $this->Crud->qrcode($qr_content); // This should return an array
				$qr_code_url = site_url($qr['path']); // adjust as per actual path
				
				// Save to DB
				$this->Crud->updates('id', $ins_rec, 'user', ['qrcode' => $qr['path']]);
				$church = $this->Crud->read_field('id', $church_id,'church', 'name');
				$action = $by.' created Membership ('.$code.') Record';
				$this->Crud->activity('user', $ins_rec, $action, $ins_rec);
				$subject = 'A Warm Welcome to '.ucwords($church).' - Here`s Your Access Pass';
				
				$name = ucwords($firstname . ' ' . $othername . ' ' . $lastname);
				$churchName = ucwords($church);
				$userNo = 'CEAM-00' . $ins_rec;
				$reset_link = site_url('auth/email_verify?uid=' . $userNo);
				$qr_code_url = site_url($qr['path']);

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

				$this->Crud->mailgun($email, $subject, $body, $church);

				$this->session->set('td_member_id', $ins_rec);

				echo $this->Crud->msg('success', 'Membership Created! Thank You!.');
				echo '<script>
					setTimeout(function() {
						$("#registration-form-wrapper").hide(500);
						$("#registration-form").hide(500);
						$("#user_id").val('.$ins_rec.');

						// Populate data
						let name = "' . addslashes($title . ' ' . $firstname . ' ' . $othername . ' ' . $lastname) . '";
						let qrUrl = "' . site_url($qr['path']) . '";
						let church = "' . addslashes($this->Crud->read_field('id', $church_id, 'church', 'name')) . '";
						let userNo = "CEAM-00' . $ins_rec . '";
						let shareMsg = encodeURIComponent("🎉 I\'m now a registered member at Christ Embassy!\\nChurch: " + church + "\\nMembership ID: " + userNo);

						$("#welcome-name").text(name);
						$("#welcome-church").text(church);
						$("#welcome-userno").text("Membership ID: " + userNo);
						$("#welcome-qr").attr("src", qrUrl);
						$("#download-qr").attr("href", qrUrl);

						// Share buttons
						$("#share-qr").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(qrUrl));
						$("#share-whatsapp").attr("href", "https://wa.me/?text=" + shareMsg + "%0A" + encodeURIComponent(qrUrl));
						$("#share-twitter").attr("href", "https://twitter.com/intent/tweet?text=" + shareMsg + "&url=" + encodeURIComponent(qrUrl));

						$("#welcome-card").fadeIn();
					}, 500);
				</script>';


				// echo '<script>location.reload(false);</script>';
			} else {
				echo $this->Crud->msg('danger', 'Please try later');	
			}	
			die;	
		}
		$data['attend_type'] = 'guest';
		$data['cell_id'] = '';
		
		$data['log_id'] = '0';

        $data['current_language'] = $this->session->get('current_language');
		$data['title'] = translate_phrase('Membership').' - '.app_name;
		$data['page_active'] = $mod;
		return view('attendance/member', $data);
	
    }

	public function check_email_full()	{
		$email = $this->request->getPost('email');
		$church_id = $this->request->getPost('church_id');

		if (!$email && !$church_id) {
			return $this->response->setJSON(['status' => 'error']);
		}

		if (!empty($email)) {
			// Get all matching users for the given email and church
			
			$users = $this->Crud->read_single('email', $email, 'user');
			
	
			if (!empty($users)) {
				$results = [];
		
				foreach ($users as $user) {
					$results[] = [
						'name' => ucwords($user->firstname . ' ' . $user->surname),
						'masked_email' => $this->Crud->mask_email($user->email),
						'masked_phone' => $this->Crud->mask_phone($user->phone),
						'user_id' => $user->id
					];
				}
		
				return $this->response->setJSON([
					'status' => 'exists',
					'data' => $results
				]);
			}
		}
		
		return $this->response->setJSON([
			'status' => 'not_found'
		]);
	}


	public function get_state($country=''){
		$country_id = $this->Crud->read_field('name', $country, 'country', 'id');
		$state = $this->Crud->read_single_order('country_id', $country, 'state', 'name', 'asc');
		$rezp = '';
		if(!empty($state)){
			foreach($state as $st){
				$rezp .= '<option value="'.$st->id.'">'.$st->name.'</option>';

			}
		}

		echo $rezp.'';
	}
	
	public function set_password()
	{
		$user_no = $this->request->getPost('user_no');
		$password = $this->request->getPost('password');

		$user = $this->Crud->read_single('id', $user_no, 'user');

		if (!$user) {
			return $this->response->setJSON(['status' => false, 'message' => 'User not found.']);
		}

		$update = $this->Crud->updates('id', $user_no, 'user', [
			'password' => md5($password) // or use password_hash for better security
		]);

		return $this->response->setJSON(['status' => true, 'message' => 'Password updated successfully.']);
	}

    ///// LOGOUT
    public function logout() {
		$user_id = $this->session->get('td_attend_id');
		if(!empty($this->session->get('td_attend_id'))){
			///// store activities
			$code = $this->Crud->read_field('id', $user_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $user_id, 'user', 'surname');
			$action = $code.translate_phrase(' logged out of Attendance Platform ');
			
			$this->Crud->activity('authentication', $user_id, $action, $user_id);

			$this->session->remove('td_attend_id');
		}
        return redirect()->to(site_url('attendance'));
    }

	

}
