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
				echo $this->Crud->msg('warning', 'Service not Found');
				die;
			}


			if($this->Crud->check2('member_id', $member_id, 'service_id', $service_report_id, 'service_attendance') == 0){
				// Do DB logic here, example:
				$this->Crud->create('service_attendance',[
					'member_id' => $member_id,
					'service_id' => $service_report_id,
					'church_id' => $church_id,
					'status' => 'present'
				]);
			}
		
			return $this->response->setJSON(['status' => 'success']);
			die;
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
									<div class="text-success">Attendance Marked</div>
								</td>
							</tr>';
					}
				}
				$response .= '</table></div>';
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
			$present = 0;
			// Count present for selected service
			$mem_query = $this->Crud->read2('service_id', $service_report_id, 'church_id', $church_id, 'service_attendance');
			if(!empty($mem_query)){
				foreach($mem_query as $mq){
					$present++;
					if(strtolower($this->Crud->read_field('id', $mq->member_id, 'user', 'gender')) == 'male'){
						$male++;
					}

					if(strtolower($this->Crud->read_field('id', $mq->member_id, 'user', 'gender')) == 'female'){
						$female++;
					}
				}
			}
			// Absent = total - present
			$absent = $total_members - $present;
		
			return $this->response->setJSON([
				'membership' => $total_members,
				'present' => $present,
				'absent' => $absent,
				'male' => $male,
				'female' => $female,
				'children' => $children
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

        $data['log_id'] = $log_id;
        $data['log_name'] = $username;
        $data['current_language'] = $this->session->get('current_language');
		$data['attend_type'] = $this->session->get('td_attend_type');
		$data['church_id'] = $this->session->get('td_church_id');
		$data['cell_id'] = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
		
        $data['role'] = $role;
        $data['title'] = translate_phrase('Attendance Dashboard').' - '.app_name;
        $data['page_active'] = $mod;
        return view('attendance/dashboard', $data);
    }


    ///// LOGOUT
    public function logout() {
		$user_id = $this->session->get('td_attend_id');
		if(!empty($this->session->get('td_attend_id'))){
			///// store activities
			$code = $this->Crud->read_field('id', $user_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $user_id, 'user', 'surname');
			$action = $code.translate_phrase(' logged out of Attendance Platform ');
			
			$this->Crud->activity('authentication', $user_id, $action);

			$this->session->remove('td_attend_id');
		}
        return redirect()->to(site_url('attendance'));
    }

	

}
