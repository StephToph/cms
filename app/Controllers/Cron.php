<?php

namespace App\Controllers;

class Cron extends BaseController {
	 // Example method 1
	public function message_run (){
		$now = date('Y-m-d H:i:s');
		$prayer = $this->Crud->prayer_range(date('Y-m-d'), 'start_date', date('Y-m-d'), 'end_date', 'prayer');
		if(!empty($prayer)){
			foreach($prayer as $p){
				$reminder = $p->reminder;
				$reminder2 = $p->reminder2;
				$time_zone = $p->time_zone;
				$ministry_id = $p->ministry_id;
				$churches = json_decode($p->churches, true);
				$timez = json_decode($p->assignment, true);
				if(!empty($timez) && is_array($timez)){
					
					// Iterate through each date
					foreach ($timez as $tim => $tval) {
						// Iterate through each record (e.g., record_1, record_2)
						foreach ($tval as $record_key => $record) {
							// Extract the start time and prayer title
							$start_time = isset($record['start_time']) ? $record['start_time'] : ''; 
							$prayer_title = isset($record['prayer_title']) ? $record['prayer_title'] : '';
							$prayer = isset($record['prayer']) ? $record['prayer'] : '';
							$church_idz = isset($record['church_id']) ? $record['church_id'] : '';
							$end_time = isset($record['end_time']) ? $record['end_time'] : '';
							
							
							// Calculate reminder time if start_time exists
							if (!empty($start_time)) {
								// Combine the date with start_time to create a full datetime string
								$meetingStartTime = $tim . " " . $start_time;
								
								if($reminder > 0){
									// Calculate the reminder time dynamically (e.g., 30 minutes before the start time)
									$reminderTime = date('Y-m-d H:i:s', strtotime($meetingStartTime . ' - '.$reminder.' minutes'));
									
									echo $reminder.' ';
									echo $this->isReminderTime($reminderTime, date('Y-m-d H:i:s'));
									// Check if the reminder should be sent
									if ($this->isReminderTime($reminderTime, date('Y-m-d H:i:s'))) {
										
										$head = 'Reminder: '.strtoupper($prayer_title).' - '.date(' h:iA', strtotime($start_time)).' '.strtoupper($time_zone).' starts in '.$reminder.' Minutes';

										$body = $this->reminder_body($prayer_title, $start_time, $end_time, $time_zone, $church_idz, $prayer).'
										
										';
										echo $body.'<br>';
										
										if(!empty($churches)){
											foreach($churches as $ch){
												$member = $this->Crud->read_single('church_id', $ch, 'user');
												if(!empty($member)){
													foreach($member as $mem){
														$email = $mem->email;
														$email_status = $this->Crud->send_email($email, $head, $body);
														if ($email_status > 0) {
															
				
														}


													}
												}

											}
										}
										$email_status = $this->Crud->send_email($email, $head, $body);
										if ($email_status > 0) {
											

										}

									}
								}
								if($reminder2 > 0){
									// Calculate the reminder time dynamically (e.g., 30 minutes before the start time)
									$reminderTime = date('Y-m-d H:i:s', strtotime($meetingStartTime . ' - '.$reminder2.' minutes'));
								
									// Check if the reminder should be sent
									if ($this->isReminderTime($reminderTime, date('Y-m-d H:i:s'))) {
										echo "Reminder should be sent for: " . $prayer_title . "\n";
										// You can call your email function here to send the reminder
									}
								}
								
							}
						}
					}


				}

			}
		}
		
	}

	private function isReminderTime($reminderTime, $currentTime)
    {
        // If the reminder time is exactly 30 minutes (or other time) before the meeting time
        $timeDiff = strtotime($reminderTime) - strtotime($currentTime);
		// echo $timeDiff.' ';
        return ($timeDiff <= 0 && $timeDiff > -300); // within the same minute range
    }


	private function reminder_body($prayer_title='',  $start_time, $end_time, $time_zone, $church_idz, $prayer){
		// Define the array of time zones (name => value)
		$timeZones = [
			"EST" => "Eastern Standard Time (EST)",
			"CST" => "Central Standard Time (CST)",
			"MST" => "Mountain Standard Time (MST)",
			"PST" => "Pacific Standard Time (PST)",
			"AKST" => "Alaska Standard Time (AKST)"
		];
		$body = '
			<div class="row gy-3 py-1">
				<!-- Event Name -->
				<div class="col-sm-12 mb-3">
					<h6 class="overline-title">Prayer Title</h6>
					<p id="preview-event-name">'.ucwords($prayer_title).'</p>
				</div>

				<!-- Start Time -->
				<div class="col-sm-6 mb-3">
					<h6 class="overline-title">Start Time</h6>
					<p id="preview-event-start">'.date('h:iA',strtotime($start_time)).'</p>
				</div>

				<!-- End Time -->
				<div class="col-sm-6 mb-3">
					<h6 class="overline-title">End Time</h6>
					<p id="preview-event-end">'.date('h:iA',strtotime($end_time)).'</p>
				</div>
				
				<div class="col-sm-6 mb-3">
					<h6 class="overline-title">Time Zone</h6>
					
					<p id="preview-event-reminder">';
					
						// Check if the time_zone is set and exists in the array, then display the full meaning
						if (!empty($time_zone) && isset($timeZones[$time_zone])) {
							$body .= $timeZones[$time_zone];  // Show the full meaning of the selected time zone
						} else {
							echo '';  // Display an empty string if no valid time zone is selected
						}
						$body .= '
					</p>
				</div>

				<!-- Church -->
				<div class="col-sm-5 mb-3">
					<h6 class="overline-title">Church</h6>
					<p id="preview-event-church">'.ucwords($this->Crud->read_field('id', $church_idz, 'church', 'name')).'</p>
				</div>


				<!-- Prayer Description -->
				<div class="col-sm-12 mb-3">
					<h6 class="overline-title">Prayer Point</h6>
					<p id="preview-event-prayer">'.$prayer ? $prayer : 'No description provided'.'</p>
				</div>

			</div>
		
		
		';




		return $body;
	} 
	

}
