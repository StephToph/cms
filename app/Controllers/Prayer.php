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
		
		if ($param1 == 'get_content') {
			// Get and validate POST parameters
			$day = $this->request->getPost('day');
			$date = $this->request->getPost('date');
			$tabz = $this->request->getPost('tabz');

			// Ensure inputs are provided
			if (empty($day) || empty($date)) {
				echo $this->Crud->msg('danger', 'Missing parameters');
				die;
			}
			
			$dateParts = explode('/', $date); 
			if (count($dateParts) == 3) {
				$datez = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
			} else {
				echo $this->Crud->msg('danger', 'Invalid date format');
				die;
			}
			
			$startOfWeek = date('Y-m-d', strtotime('Sunday', strtotime($datez)));  
			if (date('l', strtotime($datez)) == 'Saturday') {
				$startOfWeek = date('Y-m-d', strtotime('last Sunday', strtotime($datez)));  
			}

			$endOfWeek = date('Y-m-d', strtotime('Saturday', strtotime($datez))); 
			$dayName = date('l', strtotime($datez));
			// echo $datez;
			$query = $this->Crud->prayer_range($datez, 'start_date', $datez, 'end_date', 'prayer');
			// print_r($query);
			$timez = '<div role="tabpanel" class="tab-pane fade in active" id="'.$tabz.'">
                        <div class="content">';
				
				if (!empty($query)) {
					// Loop through each result in the query
					foreach ($query as $q) {
						$assignment = json_decode($q->assignment, true); // Decode JSON assignment
				
						// Check if the date exists in the assignment
					    if (is_array($assignment) && array_key_exists($datez, $assignment)) {
							// Extract records for the specified date
							$records = $assignment[$datez];
				
							// Create a new array with start_time, end_time, church_id, and prayer
							$formattedRecords = '';
				
							// Initialize index for each record
							$index = 1;
				
							foreach ($records as $record) {
								$time = $record['start_time'] . ' ' . $record['end_time'];
								$room = 'Room 1'; 
								$church = $this->Crud->read_field('id', $record['church_id'], 'church', 'name'); // Fetch church name
								$title = $record['prayer'];
				
								// Generate a unique ID for each event using the index
								$uniqueId = strtolower($dayName) . '-event-' . $index;
				
								// Build the HTML content for each event
								$formattedRecords .= '
									<div class="single-content">
										<p class="time">' . date('h:i A', strtotime($record['start_time'])) . ' - ' . date('h:i A', strtotime($record['end_time'])) . ' / ' . $room . '</p>
										<h4 data-toggle="collapse" data-target="#' . $uniqueId . '" aria-expanded="true" aria-controls="' . $uniqueId . '">' . ucwords($title) . '</h4>
										<div class="box collapse" id="' . $uniqueId . '">
											<div class="bottom-content clearfix">
												<div class="img-holder">
													<img src="' . site_url() . 'assets/prayer/img/2.png" alt="Speaker">
												</div>
												<div class="speaker-name">
													<p><span>Church:</span> ' . $church . '</p>
												</div>
												<div class="see-details">
													<a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Join Prayer</a>
												</div>
											</div>
										</div>
									</div>
								';
				
								// Increment the index for the next event
								$index++;
							}
				
							// Add formatted records to the timez variable
							$timez .= $formattedRecords;
						} else {
							// If no records found for the date, add a message
							$timez .= '<div class="single-content"><h4>No records found for the provided date</h4></div>';
						}
					}
				} else {
					$timez .= '<div class="single-content"><h4>No records found for the provided date</h4></div>';
				}
						

			$timez .= '</div></div>';

			
			echo $timez;
			die;
		
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

				if (!empty($assignment) && is_array($assignment)) {
					// Initialize an empty array to hold the results for each date
					$calendar_events = [];

					// Loop through the assignment array
					foreach ($assignment as $date => $records) {
						// Initialize an empty array to hold events for each date
						$calendar_events[$date] = [];

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


							$cal_events[$record_key] = [
								'id' => $value->id,
								'title' => strtoupper(($prayer_title)),
								'start' => date('Y-m-d H:i', strtotime($start)),
								'end' => date('Y-m-d H:i', strtotime($end)),
								'extendedProps' => ['church' => ucwords($church)],
								'publicId' => $value->id,
								'description' => ucwords($this->Crud->convertText($prayer)),
								'className' => $class,
							];
						}
					}

				}
				
				
			}
		}
		

		$data['cal_events'] = (array_values($cal_events));
		// print_r(array_values($cal_events));
	
		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Prayer Cloud').' - '.app_name;
			$data['page_active'] = $mod;

			return view('prayer/list', $data);
		}
	
	}

}
