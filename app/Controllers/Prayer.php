<?php

namespace App\Controllers;

class Prayer extends BaseController {

    /////// ACTIVITIES
	public function index($param1='', $param2='', $param3='') {
		
        $mod = 'activity';
		
        $data['current_language'] = $this->session->get('current_language');
		$table = 'prayer';

        $form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		if ($param1 == 'manage') {
			if($param2 == 'view') {
				if($param3) {
					$parts = explode(' ', $param3);

					// Assign the parts to variables
					$id = $parts[0]; // The first part will be the date
					$date = $parts[1]; // The first part will be the date
					$record_key = $parts[2];
					$edit = $this->Crud->read_single('id', $id, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
							$data['e_name'] = $e->title;
							$data['reminder'] = isset($e->reminder) ? $e->reminder : '0';
							$data['reminder2'] = isset($e->reminder2) ? $e->reminder2 : '0';
							$data['time_zone'] = isset($e->time_zone) ? $e->time_zone : '';
								
							$assignment = json_decode($e->assignment,true);
							
							if (!empty($assignment) && isset($assignment[$date]) && isset($assignment[$date][$record_key])) {
								// Fetch the specific record using record_index ($param5)
								$record = $assignment[$date][$record_key];
								// print_r($record);
								// Populate data array with record details
								$data['record_key'] = $record_key; // Unique key for identification
								$data['start_time'] = isset($record['start_time']) ? $record['start_time'] : ''; 
								$data['end_time'] = isset($record['end_time']) ? $record['end_time'] : '';
								$data['prayer'] = isset($record['prayer']) ? $record['prayer'] : '';
								$data['prayer_title'] = isset($record['prayer_title']) ? $record['prayer_title'] : '';
								$data['church_idz'] = isset($record['church_id']) ? $record['church_id'] : '0';

							}
							

							$data['e_reg_date'] = $e->reg_date;
						}
					}
				}
			}

			if($param2 == 'join') {
				if($param3) {
					$parts = explode(' ', $param3);

					// Assign the parts to variables
					$id = $parts[0]; // The first part will be the date
					$date = $parts[1]; // The first part will be the date
					$record_key = $parts[2];
					$edit = $this->Crud->read_single('id', $id, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['e_id'] = $e->id;
							$data['e_name'] = $e->title;
							$data['e_date'] = $date;
							$data['e_church_type'] = $e->church_type;
							$data['e_link'] = json_decode($e->link, true);
							$data['e_churches'] = json_decode($e->churches, true);
							$data['reminder'] = isset($e->reminder) ? $e->reminder : '0';
							$data['reminder2'] = isset($e->reminder2) ? $e->reminder2 : '0';
							$data['duration'] = isset($e->duration) ? $e->duration : '0';
							$data['time_zone'] = isset($e->time_zone) ? $e->time_zone : '';
								
							$assignment = json_decode($e->assignment,true);
							
							if (!empty($assignment) && isset($assignment[$date]) && isset($assignment[$date][$record_key])) {
								// Fetch the specific record using record_index ($param5)
								$record = $assignment[$date][$record_key];
								// print_r($record);
								// Populate data array with record details
								$data['record_key'] = $record_key; // Unique key for identification
								$data['start_time'] = isset($record['start_time']) ? $record['start_time'] : ''; 
								$data['end_time'] = isset($record['end_time']) ? $record['end_time'] : '';
								$data['prayer'] = isset($record['prayer']) ? $record['prayer'] : '';
								$data['prayer_title'] = isset($record['prayer_title']) ? $record['prayer_title'] : '';
								$data['church_idz'] = isset($record['church_id']) ? $record['church_id'] : '0';

							}
							

							$data['e_reg_date'] = $e->reg_date;
						}
					}
				}

				if($_POST){
					$room_name = $this->request->getPost('room_name');
					$link = $this->request->getPost('link');
					$name = $this->request->getPost('name');
					$church = $this->request->getPost('church_idz');
					$duration = $this->request->getPost('duration');
					$record_key = $this->request->getPost('record_key');
					$date = $this->request->getPost('date');
					$start_time = $this->request->getPost('start_time');
					$church_id = $this->request->getPost('church_id');
					$prayer_id = $this->request->getPost('prayer_id');
					
					// die;
					$this->session->set('room_name', $room_name);
					$this->session->set('link', $link);
					$this->session->set('name', $name);
					$this->session->set('church', $church);
					$this->session->set('duration', $duration);
					$this->session->set('record_key', $record_key);
					$this->session->set('date', $date);
					$this->session->set('church_id', $church_id);
					$this->session->set('prayer_id', $prayer_id);
					$this->session->set('start_time', $start_time);

					echo $this->Crud->msg('success', 'Loading Information, Please wait');
					echo '<script>window.location.replace("'.site_url('prayer/room').'");</script>';
					exit;
				}
			}
		}

		$cal_events = array();
		$log_id = 0;
		$cal_ass = $this->Crud->read('prayer');
		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));

		if (!empty($cal_ass)) {
			foreach ($cal_ass as $key => $value) {
				$assignment = json_decode($value->assignment, true);

				$class = 'fc-event-primary';
				$name = $value->title;
				$reminder = $value->reminder;

				if (!empty($assignment) && is_array($assignment)) {
					// Loop through the assignment array
					foreach ($assignment as $date => $records) {
						// Loop through each record for the current date
						foreach ($records as $record_key => $record_val) {
							// Extract start time, end time, and other details
							$start_time = isset($record_val['start_time']) ? $record_val['start_time'] : '00:00';
							$end_time = isset($record_val['end_time']) ? $record_val['end_time'] : '00:00';
							$prayer = isset($record_val['prayer']) ? $record_val['prayer'] : 'No prayer description';
							$prayer_title = isset($record_val['prayer_title']) ? $record_val['prayer_title'] : 'No title';
							$church_id = isset($record_val['church_id']) ? $record_val['church_id'] : 'Unknown Church';
							$church = $this->Crud->read_field('id', $church_id, 'church', 'name');

							// Concatenate the date with start time and end time
							$start = $date . ' ' . $start_time;
							$end = $date . ' ' . $end_time;

							// Adjusted id logic: Create a globally unique event id by combining date and record_key
							$event_id = urlencode($value->id) . '%20' . urlencode($date) . '%20' . urlencode($record_key);
 							// Combine date and record_key to make it unique

							// Add event to the cal_events array without overwriting
							$cal_events[$event_id] = [
								'id' => $event_id,  // Set the event id
								'title' => strtoupper($prayer_title),
								'start' => date('Y-m-d H:i', strtotime($start)),
								'end' => date('Y-m-d H:i', strtotime($end)),
								'extendedProps' => ['church' => ucwords($church), 'reminder' => $reminder.' '],
								'publicId' => $event_id,  // Set publicId correctly (same as event_id in this case)
								'description' => ($prayer_title),
								'className' => $class,
							];

						}
					}
				}
			}
		}

		// Convert the $cal_events array to an indexed array (for easy output or JSON response)
		$data['cal_events'] = array_values($cal_events);

		// Output the calendar events
		// print_r($data['cal_events']);

		if($param1 == 'manage') { // view for form data posting
			return view('prayer/list_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Prayer Cloud').' - '.app_name;
			$data['page_active'] = $mod;

			return view('prayer/list', $data);
		}
	
	}

	public function room(){
		 // Check if all required session variables are empty
		 if (empty($this->session->get('room_name')) || empty($this->session->get('link')) || empty($this->session->get('name')) || empty($this->session->get('church'))) {
			// Redirect to the prayer page if any of the session variables are empty
			// return redirect()->to('prayer'); // Adjust the URL if necessary
		}
	
        $role = 'moderator';  // Role: 'moderator' or 'participant'

        // Generate the JWT token by calling the model function
        $jwtToken = $this->Crud->generateJaaSToken($this->session->get('room_name'), $role, $this->session->get('name'), $this->session->get('church'));

		$data['room_name'] = $this->session->get('room_name');
		$data['link'] = $this->session->get('link');
		$data['name'] = $this->session->get('name');
		$data['church'] = $this->session->get('church');
		$data['duration'] = $this->session->get('duration');
		$data['record_key'] = $this->session->get('record_key');
		$data['date'] = $this->session->get('date');
		$data['church_id'] = $this->session->get('church_id');
		$data['prayer_id'] = $this->session->get('prayer_id');
		$data['start_time'] = $this->session->get('start_time');
		$data['jwtToken'] = $jwtToken;
		
		
		$data['title'] = translate_phrase('Join Prayer Cloud').' - '.app_name;

		return view('prayer/room', $data);
	}

	public function report($param1 = ''){

		if($param1 == 'joined'){
			if($this->request->getMethod() == 'post'){
				$record_key = $this->request->getPost('record_key');
				$prayer_id = $this->request->getPost('prayer_id');
				$church = $this->request->getPost('church');
				$church_id = $this->request->getPost('church_id');
				$name = $this->request->getPost('name');
				$date = $this->request->getPost('date');
				$start_time = $this->request->getPost('start_time');


				$report_id = $this->session->get('report_id');
				if(!empty($report_id)){
					$report_id = $this->session->set('report_id', '');
				}


				$inz['prayer_id'] = $prayer_id;
				$inz['date'] = $date;
				$inz['record'] = $record_key;
				$inz['start_time'] = $start_time;
				$inz['participant'] = $name;
				$inz['participant_church'] = $church;
				$inz['church_id'] = $church_id;
				$inz['reg_date'] = date(fdate);
				$inz['join_time'] = date(fdate);

				$report_id = $this->Crud->create('prayer_report', $inz);
				if($report_id > 0){
					$report_id = $this->session->set('report_id', $report_id);
				}
				
			}
		}

		if($param1 == 'left'){
			if($this->request->getMethod() == 'post'){
				$report_id = $this->session->get('report_id');
				
				$inz['leave_time'] = date(fdate);

				$report_id = $this->Crud->updates('id', $report_id, 'prayer_report', $inz);
				if($report_id > 0){
					$this->session->set('report_id', '');
					$this->session->set('room_name', '');
					$this->session->set('link', '');
					$this->session->set('name', '');
					$this->session->set('church', '');
					$this->session->set('duration', '');
					$this->session->set('record_key', '');
					$this->session->set('date', '');
					$this->session->set('church_id', '');
					$this->session->set('prayer_id', '');
					$this->session->set('start_time', '');
				}
				
			}
		}
	}

	public function get_calendar(){
		
		$search = $this->request->getPost('searchTerm');
		$church_idz = $this->request->getPost('churchId');

		// Initialize an array to hold the events
		$cal_events = array();

		// Assuming you already have the events fetched and stored in $cal_ass
		$cal_ass = $this->Crud->filter_prayer_cloud($search, $church_idz); 

		if (!empty($cal_ass)) {
			foreach ($cal_ass as $key => $value) {
				$assignment = json_decode($value->assignment, true);

				if (!empty($assignment) && is_array($assignment)) {
					// Loop through the assignment array
					foreach ($assignment as $date => $records) {
						foreach ($records as $record_key => $record_val) {
							$start_time = isset($record_val['start_time']) ? $record_val['start_time'] : '00:00';
							$end_time = isset($record_val['end_time']) ? $record_val['end_time'] : '00:00';
							$prayer_title = isset($record_val['prayer_title']) ? $record_val['prayer_title'] : 'No title';
							$church_id = isset($record_val['church_id']) ? $record_val['church_id'] : 'Unknown Church';
							$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
							if(!empty($church_idz) && $church_idz != 'all'){
								if($church_idz != $church_id)continue;
							}
							// Concatenate the date with start time and end time
							$start = $date . ' ' . $start_time;
							$end = $date . ' ' . $end_time;

							// Create a globally unique event id
							$event_id = urlencode($value->id) . '%20' . urlencode($date) . '%20' . urlencode($record_key);

							// Add the event to the cal_events array
							$cal_events[] = [
								'id' => $event_id,
								'title' => strtoupper($prayer_title),
								'start' => date('Y-m-d\TH:i:s', strtotime($start)),  // ISO 8601 format
								'end' => date('Y-m-d\TH:i:s', strtotime($end)),  // ISO 8601 format
								'extendedProps' => [
									'church' => ucwords($church),
									'reminder' => isset($value->reminder) ? $value->reminder : ''
								],
								'description' => $prayer_title,
								'className' => 'fc-event-primary',  // You can customize the className based on your data
							];
						}
					}
				}
			}
		}

		// Send the events data as JSON
		echo json_encode($cal_events);

	}

	public function email($email=''){
		if(empty($email)){
			$email = 'tofunmi015@gmail.com';
		}
		$start_date = '2025-01-06';
		$start_time = '15:52 AM';
		$head = 'USA REGIONAL CAMP MEETING PRAYER POINT FOR TODAY';
		$body = '
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>Prayer Session Invitation</title>
				<style>
					/* Global styles for light theme */
					body {
						font-family: Arial, sans-serif;
						color: #333;
						line-height: 1.6;
						margin: 0;
						padding: 0;
						background-color: #f9f9f9;
					}
					.container {
						padding: 20px;
						background-color: #f9f9f9;
					}
					.header {
						text-align: center;
						font-size: 24px;
						color: #0056b3;
						margin-bottom: 20px;
					}
					.message {
						background-color: #ffffff;
						border-radius: 8px;
						padding: 20px;
						box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
					}
					.btn {
						display: inline-block;
						padding: 15px 25px;
						font-size: 18px;
						color: #fff;
						background-color: #007bff;
						text-decoration: none;
						border-radius: 5px;
						margin-top: 20px;
					}
					.footer {
						margin-top: 30px;
						text-align: center;
						font-size: 14px;
						color: #666;
					}

					/* Dark theme styles */
					@media (prefers-color-scheme: dark) {
						body {
							color: #fff;
							background-color: #1c1c1c;
						}
						.container {
							background-color: #333;
						}
						.header {
							color: #4db8ff;
						}
						.message {
							background-color: #2e2e2e;
							border-radius: 8px;
							padding: 20px;
							box-shadow: 0 4px 6px rgba(255, 255, 255, 0.1);
						}
						.btn {
							background-color: #4db8ff;
						}
						.footer {
							color: #aaa;
						}
					}
				</style>
			</head>
			<body>

				<div class="container">
					<div class="header">
						<p>Dear Esteemed Pastor,</p>
					</div>

					<div class="message">
						<p>Greetings in the matchless name of our Lord Jesus Christ.</p>
						<p>Welcome to our glorious year of completeness!</p>

						<p>Kindly note that the prayer session for your church is coming up on <strong>' . date('d F, Y', strtotime($start_date)) . '</strong> by <strong>' . date('h:iA', strtotime($start_time)) . ' UTC</strong>. Kindly promote maximum participation of your brethren.</p>

						<p>God bless you.</p>

						<a href="' . site_url('prayer/index/1-wzx-pm4d-g7q') . '" class="btn" target="_blank">Join Prayer</a>

						<p>In service,<br>The 2025 Regional Camp Meeting Prayer committee</p>
					</div>
				</div>

				<div class="footer">
					<p>&copy; 2025 The Regional Camp Meeting. All rights reserved.</p>
				</div>

			</body>
			</html>
			';

		echo $this->Crud->prayer_email($email, $head, $body);
	}
}
