<?php

namespace App\Controllers;

class Cron extends BaseController {
	 // Example method 1
	public function message_run (){
		$now = date('Y-m-d H:i:s');
		$prayer = $this->Crud->prayer_range(date('Y-m-d'), 'start_date', date('Y-m-d'), 'end_date', 'prayer');
		if(!empty($prayer)){
			foreach($prayer as $p){
				$prayer_id = $p->id;
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
							$code = isset($record['code']) ? $record['code'] : '';
							
							
							// Calculate reminder time if start_time exists
							if (!empty($start_time)) {
								// Combine the date with start_time to create a full datetime string
								$meetingStartTime = $tim . " " . $start_time;
								
								if($reminder > 0){
									// Calculate the reminder time dynamically (e.g., 30 minutes before the start time)
									$reminderTime = date('Y-m-d H:i:s', strtotime($meetingStartTime . ' - '.$reminder.' minutes'));
									
									// echo $reminder.' ';
									// echo $this->isReminderTime($reminderTime, date('Y-m-d H:i:s'));
									// Check if the reminder should be sent
									if ($this->isReminderTime($reminderTime, date('Y-m-d H:i:s'))) {

										if (!isset($record['reminder_status']) || $record['reminder_status'] != 1) {
											// Add reminder_status to the record and set it to 1
											$timez[$tim][$record_key]['reminder_status'] = 1;
							
											
											$head = 'USA REGIONAL CAMP MEETING PRAYER POINT FOR TODAY.';

											$body = $this->reminder_body($prayer_id, $start_time, $tim, $time_zone, $code).'
											
											';
											
											if(!empty($church_idz)){
												$member = $this->Crud->read2('is_member', 0, 'church_id', $church_idz, 'user');
												if(!empty($member)){
													foreach($member as $mem){
														$email = $mem->email;
														$email_status = $this->Crud->prayer_email($email, $head, $body);
														if ($email_status > 0) {
															
															
														}


													}

												}
											}
											
											$this->Crud->updates('id', $p->id, 'prayer', array('assignment'=> json_encode($timez)));
										
										}


										
									}
								}

								if($reminder2 > 0){
									// Calculate the reminder time dynamically (e.g., 30 minutes before the start time)
									$reminderTime = date('Y-m-d H:i:s', strtotime($meetingStartTime . ' - '.$reminder2.' minutes'));
									
									// echo $reminder.' ';
									// echo $this->isReminderTime($reminderTime, date('Y-m-d H:i:s'));
									// Check if the reminder should be sent
									if ($this->isReminderTime($reminderTime, date('Y-m-d H:i:s'))) {

										if (!isset($record['reminder_status2']) || $record['reminder_status2'] != 1) {
											// Add reminder_status to the record and set it to 1
											$timez[$tim][$record_key]['reminder_status2'] = 1;
							
											
											$head = 'USA REGIONAL CAMP MEETING PRAYER POINT FOR TODAY.';
											$body = $this->reminder_body($prayer_id, $start_time, $tim, $time_zone, $code).'
											
											';
											
											if(!empty($church_idz)){
												$member = $this->Crud->read2('is_member', 0, 'church_id', $church_idz, 'user');
												if(!empty($member)){
													foreach($member as $mem){
														$email = $mem->email;
														$email_status = $this->Crud->prayer_email($email, $head, $body);
														if ($email_status > 0) {
															
															
														}


													}

												}
											}
											
											$this->Crud->updates('id', $p->id, 'prayer', array('assignment'=> json_encode($timez)));
										
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

	private function isReminderTime($reminderTime, $currentTime)
    {
        // If the reminder time is exactly 30 minutes (or other time) before the meeting time
        $timeDiff = strtotime($reminderTime) - strtotime($currentTime);
		// echo $timeDiff.' ';
        return ($timeDiff <= 0 && $timeDiff > -300); // within the same minute range
    }


	private function reminder_body($prayer_id='',  $start_time, $start_date, $time_zone, $code){
		// Define the array of time zones (name => value)
		$timeZones = [
			"EST" => "Eastern Standard Time (EST)",
			"CST" => "Central Standard Time (CST)",
			"MST" => "Mountain Standard Time (MST)",
			"PST" => "Pacific Standard Time (PST)",
			"AKST" => "Alaska Standard Time (AKST)"
		];
		if (!empty($time_zone) && isset($timeZones[$time_zone])) {
			$time = $timeZones[$time_zone];  // Show the full meaning of the selected time zone
		} else {
			$time ='';  // Display an empty string if no valid time zone is selected
		}
		$body = '
			 <div class="container">
				<div class="header">
					<p>Dear Esteemed Pastor,</p>
				</div>

				<div class="message">
					<p>Greetings in the matchless name of our Lord Jesus Christ.</p>
					<p>Welcome to our glorious year of completeness!</p>

					<p>Kindly note that the prayer session for your church is coming up on <strong>' . date('d F, Y', strtotime($start_date)) . '</strong> by <strong>' . date('h:iA', strtotime($start_time)) . ' ' . $time . '</strong>. Kindly promote maximum participation of your brethren.</p>

					<p>God bless you.</p>

					<a href="' . site_url('prayer/index/'.$prayer_id.'-'.$code) . '" class="btn" target="_blank">Join Prayer</a>

					<p>In service,<br>The 2025 Regional Camp Meeting Prayer committee</p>
				</div>
			</div>
		
		';

		return $body;
	} 
	
	public function auto_generate_service_reports()	{
		helper('date');
		$db = \Config\Database::connect();

		$schedule_table = 'service_schedule';
		$report_table = 'service_report';

		$logs = [];

		$schedules = $db->table($schedule_table)->get()->getResult();

		foreach ($schedules as $schedule) {
			// Set timezone per church
			$timezone = $this->Crud->getChurchTimezone($schedule->church_id); // 👈 NEW METHOD
			date_default_timezone_set($timezone); // Set before any time-based operations

			$run_today = false;
			$today = date('Y-m-d');
			$now = time(); // UNIX timestamp

			// 1. Determine if schedule should run today
			if ($schedule->type === 'one-time' && $schedule->service_date === $today) {
				$run_today = true;
			}

			if ($schedule->type === 'recurring') {
				$day = date('D'); // Mon, Tue, etc.

				if ($schedule->recurrence_pattern === 'weekly') {
					$weekly_days = explode(',', $schedule->weekly_days ?? '');
					$run_today = in_array($day, $weekly_days);
				}

				if ($schedule->recurrence_pattern === 'monthly') {
					if ($schedule->monthly_type === 'dates') {
						$monthly_dates = explode(',', $schedule->monthly_dates ?? '');
						$run_today = in_array(date('j'), $monthly_dates);
					}
				}

				if ($schedule->recurrence_pattern === 'yearly') {
					$run_today = (date('m-d') === date('m-d', strtotime($schedule->yearly_date)));
				}
			}

			if (!$run_today) continue;

			// 2. Time windows
			$start_timestamp         = strtotime("{$today} {$schedule->start_time}");
			$end_timestamp           = strtotime("{$today} {$schedule->end_time}");
			$create_window_timestamp = $start_timestamp - 7200; // 2 hours before
			$close_window_timestamp  = $end_timestamp + 7200;   // 2 hours after

			$create_window_dt = date('Y-m-d H:i:s', $create_window_timestamp);
			$close_window_dt  = date('Y-m-d H:i:s', $close_window_timestamp);

			// 3. Check for existing report
			$report = $db->table($report_table)
				->where('schedule_id', $schedule->id)
				->where('date >=', date('Y-m-d', strtotime('-1 day')))
				->where('date <=', date('Y-m-d', strtotime('+1 day')))
				->get()
				->getRow();

			// Get service type name
			$name = $this->Crud->read_field('id', $schedule->type_id, 'service_type', 'name');

			// 4. CREATE: New report
			if (!$report && $now >= $create_window_timestamp && $now <= $start_timestamp) {
				$db->table($report_table)->insert([
					'church_id'   => $schedule->church_id,
					'ministry_id' => $schedule->ministry_id,
					'schedule_id' => $schedule->id,
					'type'        => $schedule->type_id,
					'date'        => date('Y-m-d', $start_timestamp),
					'status'      => 0,
					'reg_date'    => date('Y-m-d H:i:s')
				]);

				$logs[] = "✅ Created service report for <strong>{$name}</strong> at {$create_window_dt} (Timezone: {$timezone})";
			}

			// 5. CLOSE: If past end time
			if ($report && $report->status == 0 && $now >= $close_window_timestamp) {
				$db->table($report_table)
					->where('id', $report->id)
					->update(['status' => 1]);

				$logs[] = "🔒 Closed service report for <strong>{$name}</strong> at {$close_window_dt} (Timezone: {$timezone})";
			}
		}

		echo "<h4>CRON Job Executed at " . date('Y-m-d H:i:s') . "</h4>";
		if (count($logs) > 0) {
			echo "<ul>";
			foreach ($logs as $log) {
				echo "<li>$log</li>";
			}
			echo "</ul>";
		} else {
			echo "<p>No services triggered at this time.</p>";
		}
	}

}
