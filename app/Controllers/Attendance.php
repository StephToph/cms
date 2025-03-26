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
						$is_usher = $this->Crud->read_field('id', $id, 'user', 'is_usher');
						$role_id = $this->Crud->read_field('id', $id, 'user', 'role_id');
						$role = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
						
						$attend_type = '';
						
						
						if($is_admin > 0 || $role == 'Administrator' || $role == 'Developer'){
							$attend_type = 'admin';
						}
						
						if($role == 'Assistant Cell Leader' || $role == 'Cell Leader' || $role == 'Cell Executive'){
							$attend_type = 'cell';
						}

						if($is_usher > 0){
							$attend_type = 'usher';
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

    public function dashboard($param1='', $param2='', $param3='') {
        // check login
        $log_id = $this->session->get('td_attend_id');
       if(empty($log_id)) return redirect()->to(site_url('attendance'));

    
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
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		

		if($param1 == 'get_member'){
			if($_POST){
				$member_id = $this->request->getPost('member_id');
				$service = $this->request->getPost('service');
				$church_id = $this->request->getPost('church_id');
				$response = '';

				$mem_couunt = strlen($member_id);
				if($mem_couunt < 5){
					$response =  $this->Crud->msg('danger', 'Enter More than 5 Characters!');
					
				} else {
					if(empty($member_id)){
						$response = $this->Crud->msg('danger', 'Field Cannot be Empty!!');
						
					} else {
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
						// echo $service_report_id;

						$query = $this->Crud->filter_member_attendance($member_id, $church_id);
						if(!empty($query)){
							$response .= '<div class="table-responsive"><table class="table table-hover">';
							foreach($query as $q){
								if($this->Crud->check2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance') == 0){
									
									$response .= '
										<tr>
											<td>'.ucwords(strtolower($q->firstname.' '.$q->surname.' '.$q->othername)).'</td>
											<td>'.$this->Crud->mask_email($q->email).'</td>
											<td>'.$this->Crud->mask_phone($q->phone).'</td>
											<td>
												<div class="custom-control custom-switch">
													<input type="checkbox"
														class="custom-control-input mark-present-switch"
														id="presentSwitch_'.$q->id.'"
														data-member-id="'.$q->id.'">
													<label class="custom-control-label" for="presentSwitch_'.$q->id.'">Mark Present</label>
												</div>
												<span id="resp_'.$q->id.'"></span>
											</td>

										</tr>
									
									';
								} else {
									$response .= '
										<tr>
											<td>'.ucwords(strtolower($q->firstname.' '.$q->surname.' '.$q->othername)).'</td>
											<td>'.$this->Crud->mask_email($q->email).'</td>
											<td>'.$this->Crud->mask_phone($q->phone).'</td>
											<td width="100px">
												<div class="text-success" role="alert">    
													Attendance Marked   
												</div>
											</td>
										</tr>
									
									';
								}
							}

							$response .= '</table></div>';
						} else {
							$response = '<div class="text-center text-muted">
								<br/>
								<em class="icon ni ni-user" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Record Found').'
							</div>';
						}
					}
				}

				$item['response'] = $response;
				echo json_encode($item);
			}
			die;
		}

		if($param1 == 'mark_present'){
			$member_id = $this->request->getPost('member_id');
			$service = $this->request->getPost('service_id'); // e.g., "Sunday Service"
			$church_id = $this->request->getPost('church_id');

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
			// echo $service_report_id;

			if(empty($service_report_id)){
				return $this->response->setJSON([
					'status' => 'warning',
					'message' => 'Service report not found.'
				]);
			}


			if($this->Crud->check2('member_id', $member_id, 'service_id', $service_report_id, 'service_attendance') == 0){
				// Do DB logic here, example:
				$this->Crud->create('service_attendance',[
					'member_id' => $member_id,
					'service_id' => $service_report_id,
					'church_id' => $church_id,
					'status' => 'present'
				]);
				return $this->response->setJSON([
					'status' => 'success',
					'message' => 'Marked as present.'
				]);
			} else {
				return $this->response->setJSON([
					'status' => 'info',
					'message' => 'Member already marked for this service.'
				]);
			}
		
		}

		if ($param1 === 'mark_absent') {
			$member_id = $this->request->getPost('member_id');
			$service_number = $this->request->getPost('service_id'); // index position of the service
			$reason = $this->request->getPost('reason');
			$church_id = $this->request->getPost('church_id');
		
			// 🛡️ Validation
			if (!$member_id || !$service_number || !$church_id || !$reason) {
				return $this->response->setJSON([
					'status' => 'error',
					'message' => 'All fields are required.'
				]);
			}
		
			// 🔍 Get service report ID based on today's services for this church
			$query = $this->Crud->read2_order('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'date', 'asc');
		
			$occurrence = 0;
			$service_report_id = 0;
		
			if (!empty($query)) {
				foreach ($query as $q) {
					$occurrence++;
					if ($occurrence == $service_number) {
						$service_report_id = $q->id;
						break;
					}
				}
			}
		
			// 🛑 If not found
			if (empty($service_report_id)) {
				return $this->response->setJSON([
					'status' => 'warning',
					'message' => 'Service report not found.'
				]);
			}
		
			// ✅ Check if already marked
			$exists = $this->Crud->check2('member_id', $member_id, 'service_id', $service_report_id, 'service_attendance');
		
			if ($exists == 0) {
				// 💾 Save absence with reason
				$this->Crud->create('service_attendance', [
					'member_id' => $member_id,
					'service_id' => $service_report_id,
					'church_id' => $church_id,
					'status' => 'absent',
					'reason' => $reason,
					'reg_date' => date('Y-m-d H:i:s')
				]);
		
				return $this->response->setJSON([
					'status' => 'success',
					'message' => 'Marked as absent.'
				]);
			} else {
				return $this->response->setJSON([
					'status' => 'info',
					'message' => 'Member already marked for this service.'
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
			
			// echo $cell_id;
			$query = $this->Crud->read2_order('is_member', 1, 'cell_id', $cell_id, 'user', 'surname', 'asc');
		
			$response = '';
		
			if (!empty($query)) {
				$response .= '<div class="table-responsive"><table class="table table-hover">';
				foreach ($query as $q) {
					// echo $q->id.' '.$service_report_id;
					if ($this->Crud->check2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance') == 0) {
						$response .= '
							<tr>
								<td>' . ucwords(strtolower($q->firstname . ' ' . $q->surname . ' ' . $q->othername)) . '</td>
								<td>'.$this->Crud->mask_email($q->email).'</td>
								<td>'.$this->Crud->mask_phone($q->phone).'</td>
								<td>
									<div class="custom-control custom-switch">
										<input type="checkbox"
											class="custom-control-input mark-present-switch"
											id="presentSwitch_'.$q->id.'"
											data-member-id="'.$q->id.'">
										<label class="custom-control-label" for="presentSwitch_'.$q->id.'">Mark Present</label>
									</div>

									<div class="custom-control custom-switch mb-1">
										<input type="checkbox"
											class="custom-control-input mark-absent-switch"
											id="absentSwitch_'.$q->id.'"
											data-member-id="'.$q->id.'">
										<label class="custom-control-label" for="absentSwitch_'.$q->id.'">Absent</label>
									</div>

									<div id="absent_reason_wrapper_'.$q->id.'" style="display: none;" class="mt-2 form-group">
										<label for="absent_reason_'.$q->id.'" class="form-label">Reason for Absence</label><br>
										<select class="js-select2 reason-select w-100" data-search="on" name="absent_reason" id="absent_reason_'.$q->id.'" data-member-id="'.$q->id.'">
											<option value="">-- Select Reason --</option>
											<option value="Out of Town">Out of Town</option>
											<option value="Gone to School">Gone to School</option>
											<option value="Health Challenges">Health Challenges</option>
											<option value="Challenges at Work">Challenges at Work</option>
											<option value="Challenges at Home">Challenges at Home</option>
											<option value="Financial Constraint">Financial Constraint</option>
											<option value="Absent without reason">Absent without reason</option>
											<option value="Offence">Offence</option>
											<option value="Irregular">Irregular</option>
											<option value="Not Yet Attending Church">Not Yet Attending Church</option>
											<option value="Other">Other – Specify</option>
										</select>

										<!-- Custom reason input -->
										<input type="text" class="form-control form-control-sm mt-2 other-reason-input" id="other_reason_'.$q->id.'" placeholder="Please specify" style="display: none;" />
									</div>
									<span id="resp_'.$q->id.'"></span>
								</td>
							</tr>';
					} else {
						$response .= '
							<tr>
								<td>' . ucwords(strtolower($q->firstname . ' ' . $q->surname . ' ' . $q->othername)) . '</td>
								<td>'.$this->Crud->mask_email($q->email).'</td>
								<td>'.$this->Crud->mask_phone($q->phone).'</td>
								<td>
									<div class="text-info">Attendance Marked '.ucwords($this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'status')).'</div>
								</td>
							</tr>';
					}
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
		
			// Count members in the cell
			$total_members = $this->Crud->check2('church_id', $church_id, 'is_member', 1, 'user');
			$male = 0;$female = 0;$children = 0;
			$present = 0;$absent = 0;
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
		
			return $this->response->setJSON([
				'membership' => $total_members,
				'present' => $present,
				'absent' => $absent,
				'male' => $male,
				'female' => $female,
				'unmarked' => $unmarked
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
			} else {
				
				if($this->request->getMethod() == 'post'){
					$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                    $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                   
					$occurrence = 0;
					$service = $this->request->getPost('service');
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
					$ins_data = [
						'ministry_id'        => $ministry_id,
						'church_id'          => $church_id,
						'title'              => $this->request->getPost('title'),
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
		$data['cell_id'] = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
		
        $data['role'] = $role;
		if($param1 == 'manage') { // view for form data posting
			return view('attendance/dashboard_form', $data);
		} else { 
			$data['title'] = translate_phrase('Attendance Dashboard').' - '.app_name;
			$data['page_active'] = $mod;
			return view('attendance/dashboard', $data);
		}
    }

	public function get_state($country=''){
		$country_id = $this->Crud->read_field('name', $country, 'country', 'id');
		$state = $this->Crud->read_single_order('country_id', $country_id, 'state', 'name', 'asc');
		$rezp = '';
		if(!empty($state)){
			foreach($state as $st){
				$rezp .= '<option value="'.$st->id.'">'.$st->name.'</option>';

			}
		}

		echo $rezp;
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
