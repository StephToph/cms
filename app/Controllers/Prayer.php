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
			$week_start = $this->request->getPost('week_start');
			$date = $this->request->getPost('date');
			
			// Ensure inputs are provided
			if (empty($day) || empty($week_start) || empty($date)) {
				return $this->response->setJSON(['error' => 'Missing parameters']);
			}
			
			$week_start = date('Y-m-d', strtotime($week_start)); 
			$date = date('Y-m-d', strtotime($date));
			$week_end = date('Y-m-d', strtotime($week_start . ' +6 days'));  
		
			$query = $this->Crud->date_range($week_start, 'start_date', $week_end, 'end_date','prayer');
			$timez = [];
			if (!empty($query)) {
				// Loop through each result in the query
				foreach ($query as $q) {
					$assignment = json_decode($q->assignment, true); // Decode JSON assignment
			
					// Check if the date exists in the assignment
					if (array_key_exists($date, $assignment)) {
						// Extract records for the specified date
						$records = $assignment[$date];
			
						// Create a new array with start_time, end_time, church_id, and prayer
						$formattedRecords = '';
			
						foreach ($records as $record) {
							
							$time = $record['start_time'] . ' ' . $record['end_time'];
							$room = 'Room 1'; 
							$church = $this->Crud->read_field('id', $record['church_id'], 'church', 'name'); // Fetch church name
							$title = $record['prayer'];
							
							$formattedRecords .= '
								<div class="single-content">
									<p class="time">'.$record['start_time'] . ' ' . $record['end_time'].' / Room 1</p>
									<h4 data-toggle="collapse" data-target="#${dayName.toLowerCase()}-event-${index + 1}" aria-expanded="true" aria-controls="${dayName.toLowerCase()}-event-${index + 1}">'.$title.'</h4>
									<div class="box collapse" id="${dayName.toLowerCase()}-event-${index + 1}">
										<div class="bottom-content clearfix">
											<div class="img-holder">
												<img src="'.site_url().'assets/prayer/img/event/single-event/speaker.png" alt="Speaker">
											</div>
											<div class="speaker-name">
												<p><span>Speaker:</span> '.$church.'</p>
											</div>
											<div class="see-details">
												<a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> see details</a>
											</div>
										</div>
									</div>
								</div>
							';
						}
			
			
						// Return the sorted array as JSON
						return $formattedRecords;
					}
				}
			
				// If the loop completes without finding the date
				return '<div class="content">No records found for the provided date</div>';
			} else {
				// If the query result is empty
				return '<div class="content"><h3>No data available</h3></div>';
			}
			
		
		}
		
		
		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Prayer Cloud').' - '.app_name;
			$data['page_active'] = $mod;

			return view('prayer/list', $data);
		}
	
	}

}
