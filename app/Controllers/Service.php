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
							echo '<script>
								load("","");
								$("#modal").modal("hide");
							</script>';
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
							echo '<script>
								load("","");
								$("#modal").modal("hide");
							</script>';
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
								echo '<script>
									load("","");
									$("#modal").modal("hide");
								</script>';
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
							if(!empty($switch_id)){
								$all_btn = '
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								
								
							';
							}
							
						}

						$item .= '
							<tr>
								<td>' . ucwords($name) . ' </td>	
								<td class="text-end">
									<div class="drodown">
										<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
										<div class="dropdown-menu dropdown-menu-end">
											<ul class="link-list-opt no-bdr">
												' . $all_btn . '
											</ul>
										</div>
									</div>
								</td>
							</tr>
							
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<tr><td colspan="4"><div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-building" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Service Type Returned').'<br/>
					</div></td></tr>
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

	public function schedule($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'service/schedule';

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
        $role_c = $this->Crud->module($role_id, $mod, 'create');
        $role_r = $this->Crud->module($role_id, $mod, 'read');
        $role_u = $this->Crud->module($role_id, $mod, 'update');
        $role_d = $this->Crud->module($role_id, $mod, 'delete');
        // if($role_r == 0){
        //     return redirect()->to(site_url('dashboard'));	
        // }
        $data['log_id'] = $log_id;
        $data['role'] = $role;
        $data['role_c'] = $role_c;
       
		
		$table = 'service_schedule';
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
						$type_id = $this->Crud->read_field('id', $del_id, $table, 'type_id');
						$code = $this->Crud->read_field('id', $type_id, 'service_type', 'name');
						$action = $by.' deleted Service Schedule for Service ('.$code.')';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Service Schedule Deleted');
							echo '<script>
								load_schedule("","");
								$("#modal").modal("hide");
							</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
					}
				}
			} else {
				// Prepare for edit
				if ($param2 == 'edit') {
					if ($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if (!empty($edit)) {
							foreach ($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_type_id'] = $e->type_id;
								$data['e_type'] = $e->type;
								$data['e_church_id'] = $e->church_id;
								$data['e_recurrence_pattern'] = $e->recurrence_pattern;
								$data['e_service_date'] = $e->service_date;
								$data['e_start_date'] = $e->start_date;
								$data['e_occurrences'] = $e->occurrences;
								$data['e_weekly_days'] = $e->weekly_days;
								$data['e_monthly_type'] = $e->monthly_type;
								$data['e_monthly_dates'] = $e->monthly_dates;
								$data['e_monthly_weeks'] = $e->monthly_weeks;
								$data['e_monthly_weekdays'] = $e->monthly_weekdays;
								$data['e_yearly_date'] = $e->yearly_date;
								$data['e_start_time'] = $e->start_time;
								$data['e_end_time'] = $e->end_time;
							}
						}
					}
				}

				// Handle form post
				if ($this->request->getMethod() == 'post') {
					$type_id = $this->request->getVar('type_id');

					$church_id = $this->request->getVar('church_id');
					// Common fields
					$ins_data['ministry_id'] = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
					$ins_data['church_id'] = $this->request->getVar('church_id');
					$ins_data['type_id'] = $this->request->getVar('type');
					$ins_data['type'] = $this->request->getVar('service_type');
					$ins_data['recurrence_pattern'] = $this->request->getVar('recurring_pattern');
					$ins_data['service_date'] = $this->request->getVar('service_date');
					$ins_data['start_date'] = $this->request->getVar('start_date');
					$ins_data['occurrences'] = $this->request->getVar('occurrences');
					$ins_data['start_time'] = $this->request->getVar('start_time');
					$ins_data['end_time'] = $this->request->getVar('end_time');

					// Weekly recurrence (checkbox array to CSV)
					$weekly_days = $this->request->getVar('weekly_days');
					$ins_data['weekly_days'] = is_array($weekly_days) ? implode(',', $weekly_days) : null;

					// Monthly recurrence
					$ins_data['monthly_type'] = $this->request->getVar('monthly_type');
					$ins_data['monthly_dates'] = $this->request->getVar('monthly_dates');

					$monthly_weeks = $this->request->getVar('monthly_weeks');
					$monthly_weekdays = $this->request->getVar('monthly_weekdays');
					$ins_data['monthly_weeks'] = is_array($monthly_weeks) ? implode(',', $monthly_weeks) : null;
					$ins_data['monthly_weekdays'] = is_array($monthly_weekdays) ? implode(',', $monthly_weekdays) : null;

					// Yearly
					$ins_data['yearly_date'] = $this->request->getVar('yearly_date');

					// CREATE or UPDATE
					if ($type_id) {
						$upd_rec = $this->Crud->updates('id', $type_id, $table, $ins_data);
						if ($upd_rec > 0) {
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$type_idz = $this->Crud->read_field('id', $type_id, $table, 'type_id');
							$code = $this->Crud->read_field('id', $type_idz, 'service_type', 'name');
							$action = $by . ' updated Service Schedule for Service (' . $code . ')';
							$this->Crud->activity('service', $type_id, $action);

							echo $this->Crud->msg('success', 'Service Schedule Updated');
							echo '<script>
								load_schedule("","");
								$("#modal").modal("hide");
							</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}
					} else {
						if ($this->Crud->check3('start_time', $ins_data['start_time'], 'church_id', $ins_data['church_id'],'type_id', $ins_data['type_id'], $table) > 0) {
							echo $this->Crud->msg('warning', 'Service Schedule Already Exists');
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if ($ins_rec > 0) {
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$type_id = $this->Crud->read_field('id', $ins_rec, $table, 'type_id');
								$code = $this->Crud->read_field('id', $type_id, 'service_type', 'name');
								$action = $by . ' created Service Schedule for Service(' . $code . ')';
								$this->Crud->activity('service', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Service Schedule Created');
								echo '<script>
								load_schedule("","");
								$("#modal").modal("hide");
							</script>';
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
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_service_schedule('', '', $search, $log_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_service_schedule($limit, $offset, $search, $log_id);
				$data['count'] = $counts;

				if (!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $this->Crud->read_field('id',$q->type_id, 'service_type', 'name');
						$type = ucfirst($q->type);
						$pattern = !empty($q->recurrence_pattern) ? ucfirst($q->recurrence_pattern) : 'N/A';
						$church_name = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
				
						$start_time = !empty($q->start_time) ? date('h:i A', strtotime($q->start_time)) : '-';
						$end_time = !empty($q->end_time) ? date('h:i A', strtotime($q->end_time)) : '-';
				
						if ($q->type == 'recurring') {
							if ($q->recurrence_pattern == 'weekly') {
								$summary = 'Occurs on: ' . str_replace(',', ', ', $q->weekly_days);
							} elseif ($q->recurrence_pattern == 'monthly') {
								if ($q->monthly_type == 'dates') {
									$summary = 'Monthly on dates: ' . $q->monthly_dates;
								} else {
									$summary = 'Monthly: ' . str_replace(',', ', ', $q->monthly_weeks) . ' ' . str_replace(',', ', ', $q->monthly_weekdays);
								}
							} elseif ($q->recurrence_pattern == 'yearly') {
								$summary = 'Yearly on: ' . date('F j', strtotime($q->yearly_date));
							} else {
								$summary = 'Recurring: ' . ucfirst($q->recurrence_pattern);
							}
						} else {
							$summary = 'Scheduled for: ' . date('F j, Y', strtotime($q->service_date));
						}
				
						// Buttons
						$action_btns = '';
						if ($role_u == 1 && empty($switch_id)) {
							$action_btns = '
								<div class="drodown">
									<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
										<div class="dropdown-menu dropdown-menu-end">
											<ul class="link-list-opt no-bdr">
											<li><a href="javascript:;" class="text-primary pop" pageTitle="Edit ' . $name . '" pageSize="modal-lg" pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em> Edit</a></li>
											<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em> Delete</a></li>
										</ul>
									</div>
								</div>';
						}
				
						$item .= '
						<tr>
							<td>
								<strong>' . ucwords($name) . '</strong><br/>
								<small class="text-muted">' . $type . ' | ' . $pattern . '</small>
							</td>
							<td>' . $summary . '</td>
							<td>' . ucwords($church_name) . '</td>
							<td>' . $start_time . ' - ' . $end_time . '</td>
							<td>' . $action_btns . '</td>
						</tr>';
						$a++;
					}
				} 
				
			}
			
			if(empty($item)) {
				$resp['item'] = '
					<tr><td colspan="9"><div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-clipboard" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Service Schedule Returned').'<br/>
					</div></td></tr>
				';
			} else {
				$resp['item'] = $item;
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
			
			$data['title'] = translate_phrase('Service Schedule').' - '.app_name;
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
		if($param2){$form_link .= '/'.$param2;}
		if($param3){$form_link .= '/'.$param3;}
		if($param4){$form_link .= '/'.$param4;}
		
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
						$edata['e_ministry_id'] = $e->ministry_id;
						$edata['e_church_id'] = $e->church_id;
						$edata['e_level'] = $this->Crud->read_field('id', $e->church_id, 'church', 'type');
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
				if($param3) {
					$edit = $this->Crud->read2('status', 'present', 'service_id', $param3, 'service_attendance');
					$total =0;$guest=0;$member=0;
					$male=0;$female=0;$children=0;
					if(!empty($edit)) {
						foreach($edit as $e){
							$total++;$member++;
							$gender = strtolower($this->Crud->read_field('id', $e->member_id, 'user', 'gender'));
							$family_position = strtolower($this->Crud->read_field('id', $e->member_id, 'user', 'family_position'));
							if($gender == 'male')$male++;
							if($gender == 'female')$female++;
							if($family_position == 'child')$children++;
							

						}
						
						$guest = $this->Crud->check3('category','first_timer', 'source_type', 'service', 'source_id', $param3, 'visitors');
						$total += (int)$guest;

						$head = $this->Crud->read_field('id', $param3, 'service_report', 'attendance');
						if(empty($head)){
							$head = $total;
						}

						$resp['attendance_id'] = $param3;
						$resp['head_count'] = $head;
						$resp['total_attendance'] = $total;
						$resp['guest_attendance'] = $guest;
						$resp['member_attendance'] = $member;
						$resp['male_attendance'] = $male;
						$resp['female_attendance'] = $female;
						$resp['children_attendance'] = $children;
						echo json_encode($resp);
						die;
					}
					
				}
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$attendance_id = $this->request->getPost('attendance_id');
					$total = $this->request->getPost('total');
					
					$in_data['attendance'] = $total; 
					
					if(empty($data)){
						echo $this->Crud->msg('danger', 'Mark Service Attendance');
					
					} else{
						if($this->Crud->updates('id', $attendance_id, 'service_report', $in_data) > 0){
							echo $this->Crud->msg('success', 'Service Attendance Submitted');
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$service_date = $this->Crud->read_field('id', $attendance_id, 'service_report', 'date');
							$action = $by.' updated Service Report for '.$service_date;
							$this->Crud->activity('service', $attendance_id, $action);

							// echo json_encode($data);
							echo '<script> setTimeout(function() {
								$("#show").show(500);
									$("#form").hide(500);
									$("#attendance_view").hide(500);
									$("#attendance_prev").hide(500);
									$("#add_btn").show(500);
									
									$("#prev").hide(500);
									load();
									$("#attendance_msg").html("");
							}, 2000); </script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes to Service Attendance');
						
						}
						
					}
					die;
				}

			} elseif($param2 == 'mark_attendance'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					if(!empty($edit)) {
						foreach($edit as $e){
							$attendant = $e->attendant;
							$attendants = json_decode($attendant);
							$total =0;$guest=0;$member=0;
							$male=0;$female=0;$children=0;
							if(!empty($attendants)){
								foreach($attendants as $at => $ats){
									if($at == 'total'){
										$total = $ats;
									}
									if($at == 'guest'){
										$guest = $ats;
									}
									if($at == 'member'){
										$member = $ats;
									}
									if($at == 'male'){
										$male = $ats;
									}
									if($at == 'female'){
										$female = $ats;
									}
									if($at == 'children'){
										$children = $ats;
									}
								}
							}
						}
						$resp['attendance_id'] = $param3;
						$resp['total_attendance'] = $total;
						$resp['guest_attendance'] = $guest;
						$resp['member_attendance'] = $member;
						$resp['male_attendance'] = $male;
						$resp['female_attendance'] = $female;
						$resp['children_attendance'] = $children;
						echo json_encode($resp);
						die;
					}
					
				}
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$attendance_id = $this->request->getPost('attendance_id');
					$present_members = $this->request->getPost('present_member_id');
					$absent_members = $this->request->getPost('absent_members');
					$reasons = $this->request->getPost('reasons');

					if(empty($present_members)){
						echo $this->Crud->msg('danger', 'Select Members Present in Service');
						die;
					}


					$attendant = json_decode($this->Crud->read_field('id', $attendance_id, 'service_report', 'attendant'));
					if (isset($attendant->attendant) && !empty($attendant->attendant)) {
						$indexToRemove = 'attendant'; // Change this to the actual index you want to delete
					
						if (isset($attendant[$indexToRemove])) {
							unset($attendant[$indexToRemove]);
						}
					}
					
					$ats = [];
					$present = [];
					foreach($present_members as $pre => $pmembers){
						$presents['id'] = $pmembers;
						$presents['status'] = 'present';
						$presents['reason'] = '';
						$present[] = $presents;
					}

					$ats['present'] = $present;

					$absent = [];
					if(!empty($absent_members)){
						foreach($absent_members as $ab => $amembers){
							$absents['id'] = $amembers;
							$absents['status'] = 'absent';
							$absents['reason'] = $reasons[$ab];
							$absent[] = $absents;
						}
					}
					$ats['absent'] = $absent;

					if(empty($attendant)){
						$attendant['list'] = $ats;
					} else{
						$attendant->list = $ats;
					}
					
					// print_r($attendant);
					// die;
					$in_data['attendant'] = json_encode($attendant); 
					
					
					if($this->Crud->updates('id', $attendance_id, 'service_report', $in_data) > 0){
						echo $this->Crud->msg('success', 'Service Attendance Submitted');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$service_date = $this->Crud->read_field('id', $attendance_id, 'service_report', 'date');
						$action = $by.' updated Service Attendane Report for '.$service_date;
						$this->Crud->activity('service', $attendance_id, $action);

						// echo json_encode($data);
						echo '<script> setTimeout(function() {
							$("#show").show(500);
								$("#form").hide(500);
								$("#mark_attendance_view").hide(500);
								$("#attendance_prev").hide(500);
								$("#add_btn").show(500);
								
								$("#prev").hide(500);
								load();
								$("#mark_attendance_msg").html("");
						}, 2000); </script>';
					} else {
						echo $this->Crud->msg('info', 'No Changes to Service Attendance');
					
					}
					
					
					die;
				}

			} elseif($param2 == 'partnership'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$total_part = 0;
					$member_part = 0;
					$guest_part = 0;
					$id = 0;
					$guest_partners = [];
					$member_partners = [];
					if(!empty($edit)) {
						foreach($edit as $e) {
							$id = $e->id;
							$partners = ($e->partners);
							if(empty($partners)){
								$partners = "[]";
							} else{
								$parts = json_decode($partners);
								$total_part = $parts->total_part;
								$member_part = $parts->member_part;
								$guest_part = $parts->guest_part;
								if(!empty($parts->partnership)){
									foreach($parts->partnership as $p => $pval) {
										if($p == 'guest'){
											$guest_partners = $pval;
										}
										if($p == 'member'){
											$member_partners = $pval;
										}
									}
								}
							}
						}
					}
					
					$resp['id'] = $id;
					$resp['guest_partners'] = json_encode($guest_partners);
					$resp['member_partners'] = json_encode($member_partners);
					$resp['total_part'] = $total_part;
					$resp['member_part'] = $member_part;
					$resp['guest_part'] = $guest_part;
					echo json_encode($resp);
					die;
				}
				//When Adding Save in Session
				if ($this->request->getMethod() == 'post') {
					$finance_id = $this->request->getPost('finance_id');
					$church_id = $this->request->getPost('first_church_id');
				
					// Fetch form data
					$total_part = $this->request->getPost('total_part');
					$member_part = $this->request->getPost('member_part');
					$guest_part = $this->request->getPost('guest_part');
				
					$total_offering = $this->request->getPost('total_offering');
					$member_offering = $this->request->getPost('member_offering');
					$guest_offering = $this->request->getPost('guest_offering');
				
					$total_tithe = $this->request->getPost('total_tithe');
					$member_tithe = $this->request->getPost('member_tithe');
					$guest_tithe = $this->request->getPost('guest_tithe');
				
					$total_thanksgiving = $this->request->getPost('total_thanksgiving');
					$member_thanksgiving = $this->request->getPost('member_thanksgiving');
					$guest_thanksgiving = $this->request->getPost('guest_thanksgiving');
				
					$total_seed = $this->request->getPost('total_seed');
					$member_seed = $this->request->getPost('member_seed');
					$guest_seed = $this->request->getPost('guest_seed');
				
					$members = $this->request->getPost('members');
					$offerings = $this->request->getPost('offering');
					$tithes = $this->request->getPost('tithe');
					$thanksgivings = $this->request->getPost('thanksgiving');
					$seeds = $this->request->getPost('seed');
				
					$guests = $this->request->getPost('guests');
					$guest_offerings = $this->request->getPost('guest_offering');
					$guest_tithes = $this->request->getPost('guest_tithe');
					$guest_thanksgivings = $this->request->getPost('guest_thanksgiving');
					$guest_seeds = $this->request->getPost('guest_seed');
				
					// Fetch Partnerships
					$partnerships = $this->Crud->read_order('partnership', 'name', 'asc');
				
					// Initialize Financial Data Structures with guest_list
					$offering_data = [
						"total" => $total_offering,
						"member" => $member_offering,
						"guest" => $guest_offering,
						"list" => [],
						"guest_list" => []
					];
				
					$tithe_data = [
						"total" => $total_tithe,
						"member" => $member_tithe,
						"guest" => $guest_tithe,
						"list" => [],
						"guest_list" => []
					];
				
					$thanksgiving_data = [
						"total" => $total_thanksgiving,
						"member" => $member_thanksgiving,
						"guest" => $guest_thanksgiving,
						"list" => [],
						"guest_list" => []
					];
				
					$seed_data = [
						"total" => $total_seed,
						"member" => $member_seed,
						"guest" => $guest_seed,
						"list" => [],
						"guest_list" => []
					];
				
					// Initialize Partnership Data
					$partnership_data = [
						"partnership" => [
							"guest" => [],
							"member" => []
						],
						"guest_part" => $guest_part,
						"total_part" => $total_part,
						"member_part" => $member_part
					];
				
					// Process Guest Contributions First
					if (!empty($guests)) {
						foreach ($guests as $index => $guest_name) {
							$offering_data['guest_list'][$guest_name] = !empty($guest_offerings[$index]) ? (float)$guest_offerings[$index] : 0;
							$tithe_data['guest_list'][$guest_name] = !empty($guest_tithes[$index]) ? (float)$guest_tithes[$index] : 0;
							$thanksgiving_data['guest_list'][$guest_name] = !empty($guest_thanksgivings[$index]) ? (float)$guest_thanksgivings[$index] : 0;
							$seed_data['guest_list'][$guest_name] = !empty($guest_seeds[$index]) ? (float)$guest_seeds[$index] : 0;
				
							// Process Guest Partnerships
							foreach ($partnerships as $p => $pa) {
								$key = $pa->id . '_first[' . $p . ']';
								$guest_partnership_value = $this->request->getPost($key);
								
								// echo $key.' - ';
								if (!empty($guest_partnership_value) && is_numeric($guest_partnership_value)) {
									$partnership_data["partnership"]["guest"][$guest_name][$pa->id] = (float)$guest_partnership_value;
								}
								// echo $guest_partnership_value.' ';
							}
						}
					}
					// Process Member Contributions
					if (!empty($members)) {
						foreach ($members as $index => $member_id) {
							$offering_data['list'][$member_id] = !empty($offerings[$index]) ? (float)$offerings[$index] : 0;
							$tithe_data['list'][$member_id] = !empty($tithes[$index]) ? (float)$tithes[$index] : 0;
							$thanksgiving_data['list'][$member_id] = !empty($thanksgivings[$index]) ? (float)$thanksgivings[$index] : 0;
							$seed_data['list'][$member_id] = !empty($seeds[$index]) ? (float)$seeds[$index] : 0;
				
							$member_partnerships = [];
				
							// Process Member Partnerships
							foreach ($partnerships as $p) {
								$key = $p->id . '_member';
								$member_partnership_value = $this->request->getPost($key)[$index] ?? 0;
				
								if (!empty($member_partnership_value) && is_numeric($member_partnership_value)) {
									$member_partnerships[$p->id] = (float)$member_partnership_value;
								}
							}
				
							if (!empty($member_partnerships)) {
								$partnership_data["partnership"]["member"][$member_id] = $member_partnerships;
							}
						}
					}
					// print_r($partnership_data);
					// die;
				
					// Prepare Data for Database Update
					$finance_update = [
						'tithe' => $total_tithe,
						'offering' => $total_offering,
						'partnership' => $total_part,
						'thanksgiving' => $total_thanksgiving,
						'seed' => $total_seed,
						'partners' => json_encode($partnership_data),
						'offering_givers' => json_encode($offering_data),
						'tithers' => json_encode($tithe_data),
						'thanksgiving_record' => json_encode($thanksgiving_data),
						'seed_record' => json_encode($seed_data)
					];
				
					// Update Database
					if ($this->Crud->updates('id', $finance_id, 'service_report', $finance_update) > 0) {
						echo $this->Crud->msg('success', 'Financial Report Submitted');
				
						// Store activity logs
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$service_date = $this->Crud->read_field('id', $finance_id, 'service_report', 'date');
						$action = "$by updated Service Finance Report for $service_date";
						$this->Crud->activity('service', $finance_id, $action);
				
						echo '<script>
							setTimeout(function() {
								$("#show").show(500);
								$("#form").hide(500);
								$("#finance_view").hide(500);
								$("#attendance_prev").hide(500);
								$("#add_btn").show(500);
								$("#prev").hide(500);
								load();
								$("#tithe_msg").html("");
							}, 2000);
						</script>';
					} else {
						echo $this->Crud->msg('info', 'No Changes');
					}
				
					die;
				}
				
				

			} elseif($param2 == 'finance'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$total_part = 0;
					$member_part = 0;
					$guest_part = 0;
					$total_tithe = 0;
					$member_tithe = 0;
					$guest_tithe = 0;
					$total_offering = 0;
					$member_offering = 0;
					$guest_offering = 0;
					$total_seed = 0;
					$member_seed = 0;
					$guest_seed = 0;
					$total_thanksgiving = 0;
					$member_thanksgiving = 0;
					$guest_thanksgiving = 0;


					$id = 0;
					$guest_partners = [];
					$member_partners = [];
					if (!empty($edit)) {
						foreach ($edit as $e) {
							$id = $e->id;
							
							// Decode JSON fields safely
							$partners = !empty($e->partners) ? json_decode($e->partners) : (object)[];
							$offering_givers = !empty($e->offering_givers) ? json_decode($e->offering_givers) : (object)[];
							$tithers = !empty($e->tithers) ? json_decode($e->tithers) : (object)[];
							$thanksgiving_record = !empty($e->thanksgiving_record) ? json_decode($e->thanksgiving_record) : (object)[];
							$seed_record = !empty($e->seed_record) ? json_decode($e->seed_record) : (object)[];
					
							// Process partnerships
							if (!empty($partners)) {
								$total_part = $partners->total_part ?? 0;
								$member_part = $partners->member_part ?? 0;
								$guest_part = $partners->guest_part ?? 0;
					
								if (!empty($partners->partnership)) {
									foreach ($partners->partnership as $p => $pval) {
										if ($p === 'guest') {
											$guest_partners = is_array($pval) ? $pval : [];
										}
										if ($p === 'member') {
											$member_partners = is_array($pval) ? $pval : [];
										}
									}
								}
							}
					
							// Process offering records
							if (!empty($offering_givers)) {
								$total_offering = $offering_givers->total ?? 0;
								$member_offering = $offering_givers->member ?? 0;
								$guest_offering = $offering_givers->guest ?? 0;
							}
					
							// Process tithe records
							if (!empty($tithers)) {
								$total_tithe = $tithers->total ?? 0;
								$member_tithe = $tithers->member ?? 0;
								$guest_tithe = $tithers->guest ?? 0;
							}
					
							// Process thanksgiving records
							if (!empty($thanksgiving_record)) {
								$total_thanksgiving = $thanksgiving_record->total ?? 0;
								$member_thanksgiving = $thanksgiving_record->member ?? 0;
								$guest_thanksgiving = $thanksgiving_record->guest ?? 0;
							}
					
							// Process seed records
							if (!empty($seed_record)) {
								$total_seed = $seed_record->total ?? 0;
								$member_seed = $seed_record->member ?? 0;
								$guest_seed = $seed_record->guest ?? 0;
							}
						}
					}
					
					$resp['id'] = $id;
					$resp['guest_partners'] = json_encode($guest_partners);
					$resp['member_partners'] = json_encode($member_partners);
					$resp['total_part'] = $total_part;
					$resp['member_part'] = $member_part;
					$resp['guest_part'] = $guest_part;
					$resp['total_offering'] = $total_offering;
					$resp['member_offering'] = $member_offering;
					$resp['guest_offering'] = $guest_offering;
					$resp['total_thanksgiving'] = $total_thanksgiving;
					$resp['member_thanksgiving'] = $member_thanksgiving;
					$resp['guest_thanksgiving'] = $guest_thanksgiving;
					$resp['total_seed'] = $total_seed;
					$resp['total_tithe'] = $total_tithe;
					$resp['member_tithe'] = $member_tithe;
					$resp['guest_tithe'] = $guest_tithe;
					$resp['member_seed'] = $member_seed;
					$resp['guest_seed'] = $guest_seed;
					echo json_encode($resp);
					die;
				}
				//When Adding Save in Session
				if ($this->request->getMethod() == 'post') {
					$service_id = $this->request->getPost('finance_id');
					$church_id = $this->Crud->read_field('id', $service_id, 'service_report', 'church_id');
					$country_id = $this->Crud->read_field('id', $church_id, 'church', 'country_id');
					$currency_id = $this->Crud->read_field('country_id', $country_id, 'currency', 'id');
					$ministry_id = $this->Crud->read_field('id', $service_id, 'service_report', 'ministry_id');
					$service_date = $this->Crud->read_field('id', $service_id, 'service_report', 'date');

					// Fetch form data
					$total_part = $this->request->getPost('total_part');
					$member_part = $this->request->getPost('member_part');
					$guest_part = $this->request->getPost('guest_part');
				
					$total_offering = $this->request->getPost('total_offering');
					$member_offering = $this->request->getPost('member_offering');
					$guest_offering = $this->request->getPost('guest_offering');
				
					$total_tithe = $this->request->getPost('total_tithe');
					$member_tithe = $this->request->getPost('member_tithe');
					$guest_tithe = $this->request->getPost('guest_tithe');
				
					$total_thanksgiving = $this->request->getPost('total_thanksgiving');
					$member_thanksgiving = $this->request->getPost('member_thanksgiving');
					$guest_thanksgiving = $this->request->getPost('guest_thanksgiving');
				
					$total_seed = $this->request->getPost('total_seed');
					$member_seed = $this->request->getPost('member_seed');
					$guest_seed = $this->request->getPost('guest_seed');
				
					$guest_currency = $this->request->getPost('guest_currency');
					$currency = $this->request->getPost('currency');
					$members = $this->request->getPost('members');
					$offerings = $this->request->getPost('offering');
					$tithes = $this->request->getPost('tithe');
					$thanksgivings = $this->request->getPost('thanksgiving');
					$seeds = $this->request->getPost('seed');
				
					$guests = $this->request->getPost('guests');
					$guest_offerings = $this->request->getPost('guestz_offering');
					$guest_tithes = $this->request->getPost('guestz_tithe');
					$guest_thanksgivings = $this->request->getPost('guestz_thanksgiving');
					$guest_seeds = $this->request->getPost('guestz_seed');
				
					// Fetch Partnerships
					$partnerships = $this->Crud->read_order('partnership', 'name', 'asc');
				
					// Initialize Financial Data Structures with guest_list
					$offering_data = [
						"total" => $total_offering,
						"member" => $member_offering,
						"guest" => $guest_offering
					];
				
					$tithe_data = [
						"total" => $total_tithe,
						"member" => $member_tithe,
						"guest" => $guest_tithe
					];
				
					$thanksgiving_data = [
						"total" => $total_thanksgiving,
						"member" => $member_thanksgiving,
						"guest" => $guest_thanksgiving
					];
				
					$seed_data = [
						"total" => $total_seed,
						"member" => $member_seed,
						"guest" => $guest_seed
					];
				
					// Initialize Partnership Data
					$partnership_data = [
						"guest_part" => $guest_part,
						"total_part" => $total_part,
						"member_part" => $member_part
					];

					// Process Guest Contributions First
					if (!empty($guests)) {
						foreach ($guests as $index => $guest_name) {
							// Ensure guest_id is uniquely formatted for database consistency
							$guest_id = "guest_" . str_replace(' ', '_', strtolower($guest_name)); // Convert spaces to underscores
					
							// Define finance types and their respective values
							$financeTypes = [
								'offering' => !empty($guest_offerings[$index]) ? (float)$guest_offerings[$index] : 0,
								'tithe' => !empty($guest_tithes[$index]) ? (float)$guest_tithes[$index] : 0,
								'thanksgiving' => !empty($guest_thanksgivings[$index]) ? (float)$guest_thanksgivings[$index] : 0,
								'seed' => !empty($guest_seeds[$index]) ? (float)$guest_seeds[$index] : 0
							];
					
							// Set user type
							$user_type = 'guest';
					
							// Insert or update general finance contributions
							foreach ($financeTypes as $type => $amount) {
								// Ensure only valid amounts are processed
								$g_existingFinance = $this->Crud->read_field4(
									'church_id', $church_id,
									'finance_type', $type,
									'user_id', $guest_id,
									'service_id', $service_id,
									'service_finance', 'id'
								);
								if($guest_currency[$index] <= 0){
									$amount = $this->Crud->finance_exchange($amount, $currency_id);
								}
								$gs_fin['amount'] = $amount;
				
								if ($g_existingFinance) {
									// Update existing record
									$this->Crud->updates('id', $g_existingFinance, 'service_finance', $gs_fin);
								} else {
									$gs_fin['church_id'] = $church_id;
									$gs_fin['finance_type'] = $type;
									$gs_fin['user_id'] = $guest_id; // Store guest name uniquely
									$gs_fin['user_type'] = $user_type;
									$gs_fin['service_id'] = $service_id;
									$gs_fin['ministry_id'] = $ministry_id;
									$gs_fin['reg_date'] = date('Y-m-d H:i:s');
									if ($amount > 0) { 
										// Insert new record
										$this->Crud->create('service_finance', $gs_fin);
										
									}
								}
							}
					
							// Process and insert/update Guest Partnerships
							foreach ($partnerships as $p) {
								$key = $p->id . '_guest'; // Ensure correct input name format
								$guest_partnership_values = $this->request->getPost($key) ?? [];
					
								// Ensure it's an array to avoid processing errors
								if (!is_array($guest_partnership_values)) {
									$guest_partnership_values = [$guest_partnership_values];
								}
					
								foreach ($guest_partnership_values as $index => $guest_partnership_value) {
									$partnership_id = $p->id;
									$amount = (float)$guest_partnership_value;
									
									// Ensure only valid amounts are processed
									// Retrieve existing finance record
									$g_existingPartnership = $this->Crud->read_field5(
										'church_id', $church_id,
										'finance_type', 'partnership',
										'guest', $guest_id,
										'service_id', $service_id,
										'partnership_id', $partnership_id,
										'service_finance', 'id'
									);
									
									if($guest_currency[$index] <= 0){
										$amount = $this->Crud->finance_exchange($amount, $currency_id);
									}

									// Prepare data for insertion/update
									$gs_part['amount'] = $amount;
				
									if ($g_existingPartnership) {
										// Update existing partnership finance record
										$this->Crud->updates('id', $g_existingPartnership, 'service_finance', $gs_part);
									} else {
										$gs_part['church_id'] = $church_id;
										$gs_part['finance_type'] = 'partnership';
										$gs_part['guest'] = $guest_id; // Store guest ID correctly
										$gs_part['user_type'] = $user_type;
										$gs_part['partnership_id'] = $partnership_id;
										$gs_part['service_id'] = $service_id;
										$gs_part['ministry_id'] = $ministry_id;
										$gs_part['reg_date'] = date('Y-m-d H:i:s');
										if ($amount > 0) { 
											// Insert new partnership finance record
											$this->Crud->create('service_finance', $gs_part);
										}
									}

									// Retrieve existing finance record in partners_history
									$g_existingPartnershipz = $this->Crud->read_field3(
										'guest', $guest_id,
										'service_id', $service_id,
										'partnership_id', $partnership_id,
										'partners_history', 'id'
									);
				
									$gh_part['amount_paid'] = $amount;
				
									if ($g_existingPartnershipz) {
										// Update existing partnership finance record
										$this->Crud->updates('id', $g_existingPartnershipz, 'partners_history', $gh_part);
									} else {
										// Add to the Partners History
										$gh_part['guest'] = $guest_id; // Store guest ID correctly
										$gh_part['church_id'] = $church_id;
										$gh_part['ministry_id'] = $ministry_id;
										$gh_part['service_id'] = $service_id;
										$gh_part['partnership_id'] = $partnership_id;
										$gh_part['status'] = 1;
										$gh_part['date_paid'] = $service_date;
										$gh_part['reg_date'] = date('Y-m-d H:i:s');
				
										$this->Crud->create('partners_history', $gh_part);
									}
								}
							}
						}
					}
					
					// Process Member Contributions
					if (!empty($members)) {
						foreach ($members as $index => $member_id) {
							// Define finance types and their respective values
							$financeTypes = [
								'offering' => !empty($offerings[$index]) ? (float)$offerings[$index] : 0,
								'tithe' => !empty($tithes[$index]) ? (float)$tithes[$index] : 0,
								'thanksgiving' => !empty($thanksgivings[$index]) ? (float)$thanksgivings[$index] : 0,
								'seed' => !empty($seeds[$index]) ? (float)$seeds[$index] : 0
							];

							
							// Set user type
							$user_type = 'member';
					
							// Insert or update general finance contributions
							foreach ($financeTypes as $type => $amount) {
								if($currency[$index] <= 0){
									$amount = $this->Crud->finance_exchange($amount, $currency_id);
								}
						
								$existingFinance = $this->Crud->read_field3(
									'finance_type', $type,
									'user_id', $member_id,
									'service_id', $service_id,
									'service_finance', 'id'
								);
				
								$s_fin['amount'] = $amount;
								$s_fin['currency'] = $currency[$index];
				
								if ($existingFinance) {
									// Update existing record
									$this->Crud->updates('id', $existingFinance, 'service_finance', $s_fin);
								} else {
									
									$s_fin['church_id'] = $church_id;
									$s_fin['finance_type'] = $type;
									$s_fin['user_id'] = $member_id;
									$s_fin['user_type'] = $user_type;
									$s_fin['service_id'] = $service_id;
									$s_fin['ministry_id'] = $ministry_id;
									$s_fin['reg_date'] = date('Y-m-d H:i:s');
									if ($amount > 0) {
										// Insert new record
										$this->Crud->create('service_finance', $s_fin);
									}
								}
							
							}
					
							// Process and insert/update Member Partnerships
							foreach ($partnerships as $p) {
								if (!isset($p->id) || empty($p->id)) {
									continue; // Skip if the partnership ID is not valid
								}
					
								$key = $p->id . '_member';
								$member_partnership_value = $this->request->getPost($key)[$index] ?? 0;
								// echo $member_partnership_value;
								// Ensure valid partnership value
							
								$partnership_id = $p->id;
								$amount = (float)$member_partnership_value;
				
								// Retrieve existing finance record
								$existingPartnership = $this->Crud->read_field5(
									'church_id', $church_id,
									'finance_type', 'partnership',
									'user_id', $member_id,
									'service_id', $service_id,
									'partnership_id', $partnership_id,
									'service_finance', 'id'
								);
								if($currency[$index] <= 0){
									$amount = $this->Crud->finance_exchange($amount, $currency_id);
								}
				
								// Prepare data for insertion/update
								$s_part['amount'] = $amount;
				
								if ($existingPartnership) {
									// Update existing partnership finance record
									$this->Crud->updates('id', $existingPartnership, 'service_finance', $s_part);
								} else {
									$s_part['church_id'] = $church_id;
									$s_part['finance_type'] = 'partnership';
									$s_part['user_id'] = $member_id;
									$s_part['user_type'] = $user_type;
									$s_part['partnership_id'] = $partnership_id;
									$s_part['service_id'] = $service_id;
									$s_part['ministry_id'] = $ministry_id;
									$s_part['reg_date'] = date('Y-m-d H:i:s'); // Correct format for date
									if (!empty($member_partnership_value) && is_numeric($member_partnership_value)) {
										// Insert new partnership finance record
										$this->Crud->create('service_finance', $s_part);
									}
								}

								// Retrieve existing finance record
								$existingPartnershipz = $this->Crud->read_field3(
									'member_id', $member_id,
									'service_id', $service_id,
									'partnership_id', $partnership_id,
									'partners_history', 'id'
								);
								
								$h_part['amount_paid'] = $amount;
								if ($existingPartnershipz) {
									// Update existing partnership finance record
									$this->Crud->updates('id', $existingPartnershipz, 'partners_history', $h_part);
								} else {

									//Add to the Partners History
									$h_part['member_id'] = $member_id;
									$h_part['church_id'] = $church_id;
									$h_part['ministry_id'] = $ministry_id;
									$h_part['service_id'] = $service_id;
									$h_part['partnership_id'] = $partnership_id;
									$h_part['status'] = 1;
									$h_part['date_paid'] = $service_date;
									$h_part['reg_date'] = date(fdate);
									if($amount > 0)$this->Crud->create('partners_history', $h_part);
								}
								
							}
						}
					}
					
					
					// print_r($partnership_data);
					// die;
				
					// Prepare Data for Database Update
					$finance_update = [
						'tithe' => $total_tithe,
						'offering' => $total_offering,
						'partnership' => $total_part,
						'thanksgiving' => $total_thanksgiving,
						'seed' => $total_seed,
						'partners' => json_encode($partnership_data),
						'offering_givers' => json_encode($offering_data),
						'tithers' => json_encode($tithe_data),
						'thanksgiving_record' => json_encode($thanksgiving_data),
						'seed_record' => json_encode($seed_data)
					];
				
					// Update Database
					if ($this->Crud->updates('id', $service_id, 'service_report', $finance_update) > 0) {
						echo $this->Crud->msg('success', 'Financial Report Submitted');
				
						// Store activity logs
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$service_date = $this->Crud->read_field('id', $service_id, 'service_report', 'date');
						$action = "$by updated Service Finance Report for $service_date";
						$this->Crud->activity('service', $service_id, $action);
				
						echo '<script>
							setTimeout(function() {
								$("#show").show(500);
								$("#form").hide(500);
								$("#finance_view").hide(500);
								$("#attendance_prev").hide(500);
								$("#add_btn").show(500);
								$("#prev").hide(500);
								load();
								$("#tithe_msg").html("");
							}, 2000);
						</script>';
					} else {
						echo $this->Crud->msg('info', 'No Changes');
					}
				
					die;
				}
				
				

			} elseif($param2 == 'media'){
				if($param3) {
					//When Adding Save in Session
					if($this->request->getMethod() == 'post'){
						$service_id = $param3;
						$church_id = $this->Crud->read_field('id', $service_id, 'service_report',  'church_id');

							//// Image upload
						if (file_exists($this->request->getFile('file'))) {
							
							$path = 'assets/uploads/gallery/church/'.$church_id.'/';
							$file = $this->request->getFile('file');
							if (!is_dir($path)) {
								// Create the directory
								if (mkdir($path, 0755, true)) {
								}
							} 
							$getImg = $this->Crud->file_upload($path, $file);

							if (!empty($getImg->path)) $img_id = $getImg->path;
						}


						if(empty($img_id)){
							echo $this->Crud->msg('warning', 'Select a File');
							die;
						}
						
						
							
						$ins_data['type_id'] = $service_id;
						$ins_data['type'] = 'service';
						$ins_data['user_id'] = $log_id;
						$ins_data['path'] = $img_id;
						$ins_data['reg_date'] = date(fdate);

						if($this->Crud->create('service_media', $ins_data) > 0){

							echo $this->Crud->msg('success', 'Service Media Uploaded');
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$service_date = $this->Crud->read_field('id', $service_id, 'service_report', 'date');
							$action = $by.' Uploaded a Media for Service '.$service_date;
							$this->Crud->activity('service', $service_id, $action);
	
							// echo json_encode($data);
							echo '<script> setTimeout(function() {
								media_report('.$service_id.')
							}, 2000); </script>';
						} else{
							echo $this->Crud->msg('info', 'No Changes');
							
						}
						die;
					}
					
				}
				
			

			} elseif($param2 == 'tithe'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
					$this->session->set('service_church_id', $church_id);
							
					if(!empty($edit)) {
						$total_tithe = 0;
						$member_tithe = 0;
						$guest_tithe = 0;
						$tithe_list = 0;
						
						foreach($edit as $e) {
							$tithers = json_decode($e->tithers);
							if(!empty($tithers)){
								foreach($tithers as $ti => $tv){
									if($ti == 'total'){
										$total_tithe = $tv;
									}
									if($ti == 'member'){
										$member_tithe = $tv;
									}
									if($ti == 'guest'){
										$guest_tithe = $tv;
									}
									if($ti == 'list'){
										$tithe_list = $tv;
									}
								}
							}
						}

						$resp['tithe_id'] = $param3;
						$resp['tithe_list'] = $tithe_list;
						$resp['total_tithe'] = $total_tithe;
						$resp['member_tithe'] = $member_tithe;
						$resp['guest_tithe'] = $guest_tithe;
						
						echo json_encode($resp);
						die;
					}
					
				}
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$tithe_id = $this->request->getPost('tithe_id');
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
					 
					
					$tithers =  json_encode($tithe_list);
					$ins['tithers'] = $tithers;
					$ins['tithe'] = $total_tithe;

					if($this->Crud->updates('id', $tithe_id, 'service_report', $ins) > 0){
						echo $this->Crud->msg('success', 'Service Tithe Report Submitted');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$service_date = $this->Crud->read_field('id', $tithe_id, 'service_report', 'date');
						$action = $by.' updated Service Tithe Report for '.$service_date;
						$this->Crud->activity('service', $tithe_id, $action);

						// echo json_encode($data);
						echo '<script> setTimeout(function() {
							$("#show").show(500);
								$("#form").hide(500);
								$("#tithe_view").hide(500);
								$("#attendance_prev").hide(500);
								$("#add_btn").show(500);
								
								$("#prev").hide(500);
								load();
								$("#tithe_msg").html("");
						}, 2000); </script>';
					} else {
						echo $this->Crud->msg('info', 'No Changes');
					
					}
					die;
				}

			} elseif($param2 == 'offering'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
					$this->session->set('service_church_id', $church_id);
							
					if(!empty($edit)) {
						$total_offering = 0;
						$member_offering = 0;
						$guest_offering = 0;
						$offering_list = 0;
						$total_seed = 0;
						$member_seed = 0;
						$guest_seed = 0;
						$seed_list = 0;
						$total_thanksgiving = 0;
						$member_thanksgiving = 0;
						$guest_thanksgiving = 0;
						$thanksgiving_list = 0;
						
						foreach($edit as $e) {
							$tithers = json_decode($e->offering_givers);
							if(!empty($tithers)){
								foreach($tithers as $ti => $tv){
									if($ti == 'total'){
										$total_offering = $tv;
									}
									if($ti == 'member'){
										$member_offering = $tv;
									}
									if($ti == 'guest'){
										$guest_offering = $tv;
									}
									if($ti == 'list'){
										$offering_list = $tv;
									}
								}
							}
							$thanksgiving_record = json_decode($e->thanksgiving_record);
							if(!empty($thanksgiving_record)){
								foreach($thanksgiving_record as $ti => $tv){
									if($ti == 'total'){
										$total_thanksgiving = $tv;
									}
									if($ti == 'member'){
										$member_thanksgiving = $tv;
									}
									if($ti == 'guest'){
										$guest_thanksgiving = $tv;
									}
									if($ti == 'list'){
										$thanksgiving_list = $tv;
									}
								}
							}
							$seed_record = json_decode($e->seed_record);
							if(!empty($seed_record)){
								foreach($seed_record as $ti => $tv){
									if($ti == 'total'){
										$total_seed = $tv;
									}
									if($ti == 'member'){
										$member_seed = $tv;
									}
									if($ti == 'guest'){
										$guest_seed = $tv;
									}
									if($ti == 'list'){
										$seed_list = $tv;
									}
								}
							}
						}

						$resp['offering_id'] = $param3;
						$resp['offering_list'] = $offering_list;
						$resp['total_offering'] = $total_offering;
						$resp['member_offering'] = $member_offering;
						$resp['guest_offering'] = $guest_offering;
						$resp['thanksgiving_id'] = $param3;
						$resp['thanksgiving_list'] = $thanksgiving_list;
						$resp['total_thanksgiving'] = $total_thanksgiving;
						$resp['member_thanksgiving'] = $member_thanksgiving;
						$resp['guest_thanksgiving'] = $guest_thanksgiving;
						$resp['seed_id'] = $param3;
						$resp['seed_list'] = $seed_list;
						$resp['total_seed'] = $total_seed;
						$resp['member_seed'] = $member_seed;
						$resp['guest_seed'] = $guest_seed;
						echo json_encode($resp);
						die;
					}
					
				}
				
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$offering_id = $this->request->getPost('offering_id');
					$guest_offering = $this->request->getPost('guest_offering');
					$total_offering = $this->request->getPost('total_offering');
					$member_offering = $this->request->getPost('member_offering');

					$guest_thanksgiving = $this->request->getPost('guest_thanksgiving');
					$total_thanksgiving = $this->request->getPost('total_thanksgiving');
					$member_thanksgiving = $this->request->getPost('member_thanksgiving');
					$thanksgiving = $this->request->getPost('thanksgiving');

					$guest_seed = $this->request->getPost('guest_seed');
					$total_seed = $this->request->getPost('total_seed');
					$member_seed = $this->request->getPost('member_seed');
					$seed = $this->request->getPost('seed');

					$member = $this->request->getPost('members');
					$offering = $this->request->getPost('offering');
					
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

					$tithe_list['total'] = $total_offering;
					$tithe_list['member'] = $member_offering;
					$tithe_list['guest'] = $guest_offering;
					$tithe_list['list'] = $tither;
					 
					// echo json_encode($tithe_list);
					// die;
					
					$tithers =  json_encode($tithe_list);
					$ins['offering_givers'] = $tithers;
					$ins['offering'] = $total_offering;

					
					$record = [];
					if (!empty($member) && !empty($thanksgiving)) {
						$count = count($thanksgiving); 
						for ($i = 0; $i < $count; $i++) {
							if ($thanksgiving[$i] <= 0) {
								continue; 
							}
							
							if (!isset($record[$member[$i]])) {
								$record[$member[$i]] = $thanksgiving[$i];
							}
							
						}
					}

					$thanks_list['total'] = $total_thanksgiving;
					$thanks_list['member'] = $member_thanksgiving;
					$thanks_list['guest'] = $guest_thanksgiving;
					$thanks_list['list'] = $record;
					 
					// echo json_encode($tithe_list);
					// die;
					
					$thanks_record =  json_encode($thanks_list);
					$ins['thanksgiving_record'] = $thanks_record;
					$ins['thanksgiving'] = $total_thanksgiving;

					$record = [];
					if (!empty($member) && !empty($seed)) {
						$count = count($seed); 
						for ($i = 0; $i < $count; $i++) {
							if ($seed[$i] <= 0) {
								continue; 
							}
							
							if (!isset($record[$member[$i]])) {
								$record[$member[$i]] = $seed[$i];
							}
							
						}
					}

					$seed_list['total'] = $total_seed;
					$seed_list['member'] = $member_seed;
					$seed_list['guest'] = $guest_seed;
					$seed_list['list'] = $record;
					 
					// echo json_encode($tithe_list);
					// die;
					
					$seed_record =  json_encode($seed_list);
					$ins['seed_record'] = $seed_record;
					$ins['seed'] = $total_seed;


					if($this->Crud->updates('id', $offering_id, 'service_report', $ins) > 0){
						echo $this->Crud->msg('success', 'Service Report for Offering, Thanksgiving and Special Seed Updated');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$service_date = $this->Crud->read_field('id', $offering_id, 'service_report', 'date');
						$action = $by.' updated Service Offering Report for '.$service_date;
						$this->Crud->activity('service', $offering_id, $action);

						// echo json_encode($data);
						echo '<script> setTimeout(function() {
							$("#show").show(500);
								$("#form").hide(500);
								$("#offering_view").hide(500);
								$("#attendance_prev").hide(500);
								$("#add_btn").show(500);
								
								$("#prev").hide(500);
								load();
								$("#offering_msg").html("");
						}, 2000); </script>';
					} else {
						echo $this->Crud->msg('info', 'No Changes');
					
					}
					die;
				}

			} elseif($param2 == 'thanksgiving'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
					$this->session->set('service_church_id', $church_id);
							
					if(!empty($edit)) {
						$total_thanksgiving = 0;
						$member_thanksgiving = 0;
						$guest_thanksgiving = 0;
						$thanksgiving_list = 0;
						
						foreach($edit as $e) {
							$tithers = json_decode($e->thanksgiving_record);
							if(!empty($tithers)){
								foreach($tithers as $ti => $tv){
									if($ti == 'total'){
										$total_thanksgiving = $tv;
									}
									if($ti == 'member'){
										$member_thanksgiving = $tv;
									}
									if($ti == 'guest'){
										$guest_thanksgiving = $tv;
									}
									if($ti == 'list'){
										$thanksgiving_list = $tv;
									}
								}
							}
						}

						$resp['thanksgiving_id'] = $param3;
						$resp['thanksgiving_list'] = $thanksgiving_list;
						$resp['total_thanksgiving'] = $total_thanksgiving;
						$resp['member_thanksgiving'] = $member_thanksgiving;
						$resp['guest_thanksgiving'] = $guest_thanksgiving;
						
						echo json_encode($resp);
						die;
					}
					
				}
				
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$thanksgiving_id = $this->request->getPost('thanksgiving_id');
					$guest_thanksgiving = $this->request->getPost('guest_thanksgiving');
					$total_thanksgiving = $this->request->getPost('total_thanksgiving');
					$member_thanksgiving = $this->request->getPost('member_thanksgiving');

					$member = $this->request->getPost('members');
					$thanksgiving = $this->request->getPost('thanksgiving');
					
					$record = [];
					if (!empty($member) && !empty($thanksgiving)) {
						$count = count($thanksgiving); 
						for ($i = 0; $i < $count; $i++) {
							if ($thanksgiving[$i] <= 0) {
								continue; 
							}
							
							if (!isset($record[$member[$i]])) {
								$record[$member[$i]] = $thanksgiving[$i];
							}
							
						}
					}

					$tithe_list['total'] = $total_thanksgiving;
					$tithe_list['member'] = $member_thanksgiving;
					$tithe_list['guest'] = $guest_thanksgiving;
					$tithe_list['list'] = $record;
					 
					// echo json_encode($tithe_list);
					// die;
					
					$tithers =  json_encode($tithe_list);
					$ins['thanksgiving_record'] = $tithers;
					$ins['thanksgiving'] = $total_thanksgiving;

					if($this->Crud->updates('id', $thanksgiving_id, 'service_report', $ins) > 0){
						echo $this->Crud->msg('success', 'Service Thanksgiving Offering Report Submitted');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$service_date = $this->Crud->read_field('id', $thanksgiving_id, 'service_report', 'date');
						$action = $by.' updated Service Thanksgiving Offering Report for '.$service_date;
						$this->Crud->activity('service', $thanksgiving_id, $action);

						// echo json_encode($data);
						echo '<script> setTimeout(function() {
								$("#show").show(500);
								$("#form").hide(500);
								$("#thanksgiving_view").hide(500);
								$("#attendance_prev").hide(500);
								$("#add_btn").show(500);
								
								$("#prev").hide(500);
								load();
								$("#thanksgiving_msg").html("");
						}, 2000); </script>';
					} else {
						echo $this->Crud->msg('info', 'No Changes');
					
					}
					die;
				}

			} elseif($param2 == 'seed'){
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
					$this->session->set('service_church_id', $church_id);
							
					if(!empty($edit)) {
						$total_seed = 0;
						$member_seed = 0;
						$guest_seed = 0;
						$seed_list = 0;
						
						foreach($edit as $e) {
							$tithers = json_decode($e->seed_record);
							if(!empty($tithers)){
								foreach($tithers as $ti => $tv){
									if($ti == 'total'){
										$total_seed = $tv;
									}
									if($ti == 'member'){
										$member_seed = $tv;
									}
									if($ti == 'guest'){
										$guest_seed = $tv;
									}
									if($ti == 'list'){
										$seed_list = $tv;
									}
								}
							}
						}

						$resp['seed_id'] = $param3;
						$resp['seed_list'] = $seed_list;
						$resp['total_seed'] = $total_seed;
						$resp['member_seed'] = $member_seed;
						$resp['guest_seed'] = $guest_seed;
						
						echo json_encode($resp);
						die;
					}
					
				}
				
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$seed_id = $this->request->getPost('seed_id');
					$guest_seed = $this->request->getPost('guest_seed');
					$total_seed = $this->request->getPost('total_seed');
					$member_seed = $this->request->getPost('member_seed');

					$member = $this->request->getPost('members');
					$seed = $this->request->getPost('seed');
					
					$record = [];
					if (!empty($member) && !empty($seed)) {
						$count = count($seed); 
						for ($i = 0; $i < $count; $i++) {
							if ($seed[$i] <= 0) {
								continue; 
							}
							
							if (!isset($record[$member[$i]])) {
								$record[$member[$i]] = $seed[$i];
							}
							
						}
					}

					$tithe_list['total'] = $total_seed;
					$tithe_list['member'] = $member_seed;
					$tithe_list['guest'] = $guest_seed;
					$tithe_list['list'] = $record;
					 
					// echo json_encode($tithe_list);
					// die;
					
					$tithers =  json_encode($tithe_list);
					$ins['seed_record'] = $tithers;
					$ins['seed'] = $total_seed;

					if($this->Crud->updates('id', $seed_id, 'service_report', $ins) > 0){
						echo $this->Crud->msg('success', 'Service Special Seed Offering Report Submitted');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$service_date = $this->Crud->read_field('id', $seed_id, 'service_report', 'date');
						$action = $by.' updated Service Special Seed Offering Report for '.$service_date;
						$this->Crud->activity('service', $seed_id, $action);

						// echo json_encode($data);
						echo '<script> setTimeout(function() {
								$("#show").show(500);
								$("#form").hide(500);
								$("#seed_view").hide(500);
								$("#attendance_prev").hide(500);
								$("#add_btn").show(500);
								
								$("#prev").hide(500);
								load();
								$("#seed_msg").html("");
						}, 2000); </script>';
					} else {
						echo $this->Crud->msg('info', 'No Changes');
					
					}
					die;
				}

			} elseif($param2 == 'new_convert'){
				if($param3){
					$resp = [];
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$partners = [];
					$id = 0;
					if(!empty($edit)) {
						foreach($edit as $e) {
							$converts = $e->converts;
							if(empty($converts)){
								$converts = "[]";
							}
							$id = $e->id;
						}
						
					}

					$resp['convert_list'] =  $converts;
					$resp['id'] = $id;

					echo json_encode($resp);
					die;
				}
				//When Adding Save in Session
				if($this->request->getMethod() == 'post'){
					$first_name = $this->request->getPost('first_name');
					$new_convert_id = $this->request->getPost('new_convert_id');
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
						$ins_data['converts'] = json_encode($convert);
						$ins_data['new_convert'] = count($first_name);

						if($this->Crud->updates('id', $new_convert_id, 'service_report', $ins_data) > 0){
							$ministry_id = $this->Crud->read_field('id', $new_convert_id, 'service_report', 'ministry_id');
							$church_id = $this->Crud->read_field('id', $new_convert_id, 'service_report', 'church_id');
							$dates = $this->Crud->read_field('id', $new_convert_id, 'service_report', 'date');
							
							//Create Follow up
							$converts = ($convert);
							
							$ins['source_type'] = 'service';
							$ins['source_id'] = $new_convert_id;
							$ins['ministry_id'] = $ministry_id;
							$ins['church_id'] = $church_id;
							$ins['visit_date'] = $dates;
							

							if(!empty($converts)){
								$ins['category'] = 'new_convert';
								foreach($converts as $f => $f_value){
									$ins['fullname'] = $f_value['fullname'];
									$ins['email'] = $f_value['email'];
									$ins['phone'] = $f_value['phone'];
									$ins['dob'] = $f_value['dob'];
									$ins['reg_date'] = date(fdate);
									
									if(!empty($first[$f]['id'])){
										$this->Crud->updates('id', $first[$f]['id'], 'visitors', $ins);
										$ins_recs = $first[$f]['id'];
									} else{
										$ins_recs = $this->Crud->create('visitors', $ins);
									}
									if ($ins_recs) {
										// Add the new ID to the array
										$converts[$f]['id'] = $ins_recs;
										$this->Crud->updates('id', $new_convert_id, 'service_report', array('converts'=>json_encode($converts)));
									}
								}
							}
							echo $this->Crud->msg('success', 'New Convert List Submitted');
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$service_date = $this->Crud->read_field('id', $new_convert_id, 'service_report', 'date');
							$action = $by.' updated Service New Convert Report for '.$service_date;
							$this->Crud->activity('service', $new_convert_id, $action);

							// echo json_encode($data);
							echo '<script> setTimeout(function() {
								$("#show").show(500);
									$("#form").hide(500);
									$("#new_convert_view").hide(500);
									$("#attendance_prev").hide(500);
									$("#add_btn").show(500);
									
									$("#prev").hide(500);
									load();
									$("#new_convert_msg").html("");
							}, 2000); </script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');
						}

					}
					die;
				}
			

			} elseif($param2 == 'timers'){
				if($param3){
					$resp = [];
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$timers = [];
					$id = 0;
					if(!empty($edit)) {
						foreach($edit as $e) {
							$timers = $e->timers;
							if(empty($timers)){
								$timers = "[]";
							}
							$id = $e->id;
						}
						
					}

					
				}
				
				if($this->request->getMethod() == 'post'){
					
					$occurrence = 0;
					$church_id = $this->request->getPost('church_id');
					$ministry_id = $this->request->getPost('ministry_id');
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
					
					
					$ins_data = [
						'ministry_id'        => $ministry_id,
						'channel'          	 => $channel,
						'church_id'          => $church_id,
						'title'              => $this->request->getPost('title'),
						'invited_by'         => $this->request->getPost('invited_by'),
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
			

					$upd_rec = $this->Crud->create('visitors', $ins_data);
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
				
			} elseif($param2 == 'report'){
				if($param3){
					$resp = [];
					$edit = $this->Crud->read_single('id', $param3, 'service_report');
					$timers = [];
					$id = 0;
					if(!empty($edit)) {
						foreach($edit as $e) {
							$id = $e->id;
							$note = $e->note;
							$date = $e->date;
							$reg_date = $e->reg_date;
							$type = $this->Crud->read_field('id', $e->type, 'service_type', 'name');
							$total_part = 0;
							$member_part = 0;
							$guest_part = 0;
							$total_tithe = 0;
							$member_tithe = 0;
							$guest_tithe = 0;
							$total_offering = 0;
							$member_offering = 0;
							$guest_offering = 0;
							$total_seed = 0;
							$member_seed = 0;
							$guest_seed = 0;
							$total_thanksgiving = 0;
							$member_thanksgiving = 0;
							$guest_thanksgiving = 0;

							$timer = $this->Crud->check3('source_id', $e->id, 'source_type', 'service', 'category', 'first_timer', 'visitors');
							
							$convert = $this->Crud->check4('new_convert', 1, 'source_id', $e->id, 'source_type', 'service', 'category', 'first_timer', 'visitors');
							$conv = $this->Crud->check3('service_id', $e->id, 'new_convert', 1, 'status', 'present', 'service_attendance');

							$convert += (int)$conv;

							$attendance = $e->attendance;
							if(empty($attendance)){
								$attend = $this->Crud->check2('service_id', $e->id, 'status', 'present', 'service_attendance');
								$attendance = (int)$attend + (int)$timer;
							}


							$finance = $this->Crud->read_single('service_id', $id, 'service_finance');
							if(!empty($finance)){
								foreach($finance as $f){
									if($f->finance_type == 'offering'){
										$total_offering += (float)$f->amount;
									}
									if($f->finance_type == 'tithe'){
										$total_tithe += (float)$f->amount;
									}
									if($f->finance_type == 'partnership'){
										$total_part += (float)$f->amount;
									}
									if($f->finance_type == 'seed'){
										$total_seed += (float)$f->amount;
									}
									if($f->finance_type == 'thanksgiving'){
										$total_thanksgiving += (float)$f->amount;
									}
								}
							}
							
							$data['id'] = $id;
							$data['service_report_id'] = $id;
							$data['note'] = $note;
							$data['date'] = $date;
							$data['types'] = $type;
							$data['total_part'] = $total_part;
							$data['total_tithe'] = $total_tithe;
							$data['total_offering'] = $total_offering;
							$data['total_seed'] = $total_seed;
							$data['total_thanksgiving'] = $total_thanksgiving;
							$data['convert'] = $convert;
							$data['timer'] = $timer;
							$data['attendance'] = $attendance;
							$data['reg_date'] = $reg_date;
							$data['finance'] = $this->Crud->read_single('service_id', $id, 'service_finance');
							$data['member_attendance'] = $this->Crud->read_single('service_id', $id, 'service_attendance');
							$data['convert_member'] = $this->Crud->read2('new_convert', 1,'service_id', $id, 'service_attendance');
							$data['guest_attendance'] = $this->Crud->read3('source_id', $e->id, 'source_type', 'service', 'category', 'first_timer', 'visitors');
							$data['convert_guest'] = $this->Crud->read4('new_convert', 1, 'source_id', $e->id, 'source_type', 'service', 'category', 'first_timer', 'visitors');
							
						}
						
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
					$ministry_id = $this->request->getVar('ministry_id');
					$church_id = $this->request->getVar('church_id');
					if(empty($church_id) || empty($ministry_id)){
						$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
						$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
						
					}
					// echo $date;die;
					$dates = date('y-m-d', strtotime($date));

					
					$ins_data['type'] = $type;
					$ins_data['date'] = $dates;
					$ins_data['attendance'] = $attendance;
					$ins_data['new_convert'] = $new_convert;
					$ins_data['first_timer'] = $first_timer;
					$ins_data['attendant'] = $attendant;
					$ins_data['converts'] = $converts;
					$ins_data['timers'] = $timers;
					$ins_data['ministry_id'] = $ministry_id;
					$ins_data['church_id'] = $church_id;
			
					

					// do create or update
					if($report_id) {
								
						$upd_rec = $this->Crud->updates('id', $report_id, $table, $ins_data);
						if($upd_rec > 0) {
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
														
													}
													
												}
												
											}
										}
									}
								}

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

		if($param1 == 'church_select'){
			$church_id = $this->request->getPost('church_id');
			if($church_id){
				$this->session->set('service_church_id', $church_id);
			}
			die;
		}

		if($param1 == 'records'){
			if($param2 == 'get_ministry'){
				$e_ministry_id = $this->request->getPost('ministry_id');
				$ministry = $this->Crud->read_order('ministry', 'name', 'asc');
				if(!empty($ministry)){
					$data = [];
					foreach($ministry as $min){
						$selected = '';
						if(!empty($e_ministry_id)){
							if($min->id == $e_ministry_id){
								$selected = 'selected';
							}
						}
						$data[] = array('id' => $min->id, 'name' => $min->name, 'selected' => $selected);
					}
				}
				echo json_encode($data);
				die;
			}

			if($param2 == 'get_church_level'){
				$e_level = $this->request->getPost('level');
				$log_church_id = $this->Crud->read_field('id', $log_id, 'user',  'church_id');
				$data = [];
				$selected = '';
				if($log_church_id > 0){
					$data[] = array('id' => $log_church_id, 'name' => 'My Church', 'selected' => $selected);
				}
				$log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

				if($log_church_type == 'region'){
					$data[] = array('id' => 'zone', 'name' => 'Zonal Church', 'selected' => $selected);
					$data[] = array('id' => 'group', 'name' => 'Group Church', 'selected' => $selected);
					$data[] = array('id' => 'church', 'name' => 'Church Assembly', 'selected' => $selected);
					
				} elseif($log_church_type == 'zone'){
					$data[] = array('id' => 'group', 'name' => 'Group Church', 'selected' => $selected);
					$data[] = array('id' => 'church', 'name' => 'Church Assembly', 'selected' => $selected);
					
				} elseif($log_church_type == 'group'){
					$data[] = array('id' => 'church', 'name' => 'Church Assembly', 'selected' => $selected);
					
				} else{
					$data[] = array('id' => 'region', 'name' => 'Regional Church', 'selected' => $selected);
					$data[] = array('id' => 'zone', 'name' => 'Zonal Church', 'selected' => $selected);
					$data[] = array('id' => 'group', 'name' => 'Group Church', 'selected' => $selected);
					$data[] = array('id' => 'church', 'name' => 'Church Assembly', 'selected' => $selected);
					
				}
					
				echo json_encode($data);
				die;
			}

			if($param2 == 'get_church'){
				
				if($param3){
					$data = [];
					$church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
					$timers = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'timers'));
					$members = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');
					if(!empty($members)){
						foreach($members as $member){
							$selected = '';
							if(!empty($timers)){
								foreach($timers as $time => $val){
									if($val->channel == $member->id){
										$selected = 'selected';
									}
								}
							}
							$data[] = array('id' => $member->id, 'name' => $member->firstname.' '.$member->surname, 'selected' => $selected);
					

						}
					}
					echo json_encode($data);
					die;
				}
					
				
			}

			if($param2 == 'getFirstTimers'){
				
				if ($param3) {
					$data = [];
				
					// Fetch and decode timers data from the database
					$timersJson = $this->Crud->read_field('id', $param3, 'service_report', 'timers');
					$timers = json_decode($timersJson, true);
				
					if (!empty($timers) && is_array($timers)) {
						foreach ($timers as $val) {
							if (isset($val['firstname'], $val['phone'])) {
								$data[] = [
									'id' => $val['firstname'].' '.$val['surname'],
									'phone' => $val['phone']
								];
							}
						}
					}
				
					// Return the JSON response
					echo json_encode($data);
					die;
				}
				
					
				
			}

				
			if ($param2 == 'get_service_partnership') {
				if ($param3) {
					$data = [];
					$name = strtolower(str_replace(' ', '_', $this->request->getPost('name'))); // Convert name to lowercase and replace spaces with underscores
					$guest_id = "guest_" . $name; // Ensure guest ID is formatted correctly
				
					// Fetch financial records directly from service_finance table
					$financial_records = $this->Crud->read2('service_id', $param3, 'guest', $guest_id, 'service_finance');
				
					// Initialize guest financial contributions
					$guest_offering = $guest_tithe = $guest_thanksgiving = $guest_seed = "0";
				
					// Process financial records
					if (!empty($financial_records)) {
						foreach ($financial_records as $record) {
							if ($record->finance_type == 'offering') {
								$guest_offering = $record->amount;
							} elseif ($record->finance_type == 'tithe') {
								$guest_tithe = $record->amount;
							} elseif ($record->finance_type == 'thanksgiving') {
								$guest_thanksgiving = $record->amount;
							} elseif ($record->finance_type == 'seed') {
								$guest_seed = $record->amount;
							}
						}
					}
				
					// Retrieve partnerships
					$partnerships = $this->Crud->read_order('partnership', 'name', 'asc');
				
					if (!empty($partnerships)) {
						foreach ($partnerships as $p) {
							$pid = $p->id;
							$amount = "0"; // Default amount
				
							// Fetch guest partnership contribution from service_finance
							$guest_partnership = $this->Crud->read_field4('service_id', $param3, 'guest', $guest_id, 'finance_type',  'partnership', 'partnership_id', $pid, 'service_finance', 'amount');
				
							if (!empty($guest_partnership)) {
								$amount = $guest_partnership;
							}
				
							$data[] = ['id' => $pid, 'amount' => $amount];
						}
					}
					
					$church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
					$country_id =  $this->Crud->read_field('id', $church_id, 'church', 'country_id');
					$currency_id =  $this->Crud->read_field('country_id', $country_id, 'currency', 'id');
					$currency_name =  $this->Crud->read_field('country_id', $country_id, 'currency', 'currency_name');
					$curz = [
						$currency_id => ucwords($currency_name)

					];
					// Include guest financial records in response
					$response = [
						"partners" => $data,
						"guest_offering" => $guest_offering,
						"guest_tithe" => $guest_tithe,
						"guest_thanksgiving" => $guest_thanksgiving,
						"guest_seed" => $guest_seed,
						"currency" => $curz
					];
				
					echo json_encode($response);
					die;
				}
				
			}
			
			if ($param2 == 'get_members_finance') {
				if ($param3) {
					$data = [];
			
					// Fetch Church Members
					$church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
					$country_id =  $this->Crud->read_field('id', $church_id, 'church', 'country_id');
					$currency_id =  $this->Crud->read_field('country_id', $country_id, 'currency', 'id');
					$currency_name =  $this->Crud->read_field('country_id', $country_id, 'currency', 'currency_name');
					$church = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');
					// echo $church_id;
					$curz = [
						$currency_id => ucwords($currency_name)

					];

					$church_memberss = [];
					if(!empty($church)){
						foreach($church as $c){
							$church_members['id'] = $c->id;
							$church_members['phone'] = $c->phone;
							$church_members['fullname'] = strtoupper($c->firstname.' '.$c->surname);

							$church_memberss[] = $church_members;
						}
					}
					
					// Fetch financial records directly from service_finance table
					$financial_records = $this->Crud->read_single('service_id', $param3, 'service_finance');

					// Initialize financial lists
					$offerings_list = [];
					$tithes_list = [];
					$thanksgiving_list = [];
					$seeds_list = [];
					$partnerships_list = [];

					// Process financial records from database
					if (!empty($financial_records)) {
						foreach ($financial_records as $record) {
							$member_id = $record->user_id;
							$finance_type = $record->finance_type;
							$amount = (float)$record->amount;

							if ($finance_type == 'offering') {
								$offerings_list[$member_id] = $amount;
							} elseif ($finance_type == 'tithe') {
								$tithes_list[$member_id] = $amount;
							} elseif ($finance_type == 'thanksgiving') {
								$thanksgiving_list[$member_id] = $amount;
							} elseif ($finance_type == 'seed') {
								$seeds_list[$member_id] = $amount;
							} elseif ($finance_type == 'partnership') {
								$partnership_id = $record->partnership_id;
								$user_type = $record->user_type;
								$partnerships_list[$user_type][$member_id][$partnership_id] = $amount;
							}
						}
					}

					// Fetch partnership types
					$partnerships = $this->Crud->read_order('partnership', 'name', 'asc');
					$partnership_types = [];
					if (!empty($partnerships)) {
						foreach ($partnerships as $p) {
							$partnership_types[$p->id] = strtoupper($p->name);
						}
					}

					// Merge all unique member IDs
					$all_member_ids = array_unique(array_merge(
						array_keys($offerings_list),
						array_keys($tithes_list),
						array_keys($thanksgiving_list),
						array_keys($seeds_list),
						isset($partnerships_list['member']) ? array_keys($partnerships_list['member']) : [],
						isset($partnerships_list['guest']) ? array_keys($partnerships_list['guest']) : []
					));

					$table = '';
					// Construct table rows dynamically
					foreach ($all_member_ids as $member_id) {
						$is_guest = strpos($member_id, 'guest_') !== false;
						if ($member_id == 'member' || $member_id == 'guest') {
							continue;
						}

						// Fetch user details
						$fullname = $is_guest ? 'GUEST' : $this->Crud->read_field('id', $member_id, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $member_id, 'user', 'surname');
						$phone = $is_guest ? '-' : $this->Crud->read_field('id', $member_id, 'user', 'phone');

						// Fetch financial contributions
						$offering = $offerings_list[$member_id] ?? "0";
						$tithe = $tithes_list[$member_id] ?? "0";
						$thanksgiving = $thanksgiving_list[$member_id] ?? "0";
						$seed = $seeds_list[$member_id] ?? "0";

						// Define member type
						$member_type = $is_guest ? "guest" : "member";

						// Construct table row
						$table .= '<tr>
									<td>
										<input type="hidden" readonly class="form-control members" name="members[]" value="' . htmlspecialchars($member_id) . '">
										<span class="small">' . htmlspecialchars(strtoupper($fullname)) . ' - ' . htmlspecialchars($phone) . '</span>
									</td>
									<td>
										<input type="text" style="width:100px;" class="form-control offering" name="offering[]" 
											oninput="calculateTotalz(); this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');" 
											value="' . htmlspecialchars($offering) . '">
									</td>
									<td>
										<input type="text" style="width:100px;" class="form-control tithe" name="tithe[]" 
											oninput="calculateTotal(); this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');" 
											value="' . htmlspecialchars($tithe) . '">
									</td>
									<td>
										<input type="text" style="width:100px;" class="form-control thanksgiving" name="thanksgiving[]" 
											oninput="calculateTotalz_thanksgiving();this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');" 
											value="' . htmlspecialchars($thanksgiving) . '">
									</td>
									<td>
										<input type="text" style="width:100px;" class="form-control seed" name="seed[]" 
											oninput="calculateTotalz_seed();this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');" 
											value="' . htmlspecialchars($seed) . '">
									</td>';

						// Process Partnership Contributions
						foreach ($partnership_types as $p_id => $p_name) {
							$amount = $partnerships_list[$member_type][$member_id][$p_id] ?? "0";
							$table .= '<td>
										<input type="text" style="width:100px;" class="form-control members_amount partnerships" 
											name="' . htmlspecialchars($p_id) . '_member[]" 
											oninput="bindInputEvents(); this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');" 
											value="' . htmlspecialchars($amount) . '">
									</td>';
						}

						// Delete button
						$table .= '
									<td>
										<select class="js-select2" name="currency[]">
											<option value="0">Espees</option>
											<option value="'.$currency_id.'" selected>'.ucwords($currency_name).'</option>
										</select>
									</td>
									<td>
									<button type="button" class="btn btn-danger btn-sm deleteRow" onclick="deleteRowz(this)">
										<i class="icon ni ni-trash"></i>
									</button>
								</td>';
						$table .= '</tr>';
					}


					// echo $table;
					$data['partnerships'] = ($partnerships);
					$data['members'] = ($church_memberss);
					$data['members_part'] = $table;
					$data['currency'] = $curz;

					echo json_encode($data);
					die;
				}
			}
			
			if($param2 == 'get_members_tithe'){
				if($param3){
					$data = [];
					$tithersa = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'tithers'));
					$church_id = ($this->Crud->read_field('id', $param3, 'service_report', 'church_id'));
					$church = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');

					$church_memberss = [];
					$count = 0;
					if(!empty($church)){
						foreach($church as $c){
							$church_members['id'] = $c->id;
							$church_members['phone'] = $c->phone;
							$church_members['fullname'] = strtoupper($c->firstname.' '.$c->surname);

							$church_memberss[] = $church_members;
						}
					}
					

					
					$table = '';
					$tithers = [];
					if(!empty($tithersa) && isset($tithersa->list)){
						$tithers = (array)$tithersa->list;
					}
					

					
					// print_r($tithers);
					if(!empty($tithers)){
						$tither_ids = array_keys($tithers);

						// Filter out church members who are also tithers
						$church_memberss = array_filter($church_memberss, function($member) use ($tither_ids) {
							return !in_array($member['id'], $tither_ids);
						});

						$church_memberss = array_values($church_memberss);

						foreach($tithers as $tither => $tithe){
							$fullname = $this->Crud->read_field('id', $tither, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $tither, 'user', 'surname');
							$phone = $this->Crud->read_field('id', $tither, 'user', 'phone');
	
							$table .= '<tr>
								<td>
									<input type="hidden" readonly class="form-control members" name="members[]" value="' . htmlspecialchars($tither) . '">
									<span class="small">' . htmlspecialchars(strtoupper($fullname)) . ' - ' . htmlspecialchars($phone) . '</span>
								</td>

								<td>
									<input type="text" class="form-control tithes" name="tithe[]" 
										oninput="calculateTotal(); this.value = this.value.replace(/[^0-9]/g, \'\');" 
										value="' . htmlspecialchars($tithe) . '">
								</td>
							</tr>';

						}
					}
								
					$data['members'] = ($church_memberss);
					$data['members_part'] = $table;



					echo json_encode($data);
					die;
				}
					
				
			}

			if($param2 == 'get_members_offering'){
				if ($param3) {
					$data = [];
				
					// Fetch records
					$tithersa = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'offering_givers'), true);
					$thanksgiving_record = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'thanksgiving_record'), true);
					$seed_record = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'seed_record'), true);
					$church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
				
					// Fetch church members
					$church = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');
					
			
					$church_memberss = [];
					if (!empty($church)) {
						foreach ($church as $c) {
							$church_memberss[$c->id] = [
								'id' => $c->id,
								'phone' => $c->phone,
								'fullname' => strtoupper($c->firstname . ' ' . $c->surname)
							];
						}
					}


					$table = '';
				
					// Convert JSON lists to arrays
					$tithers = isset($tithersa['list']) ? (array)$tithersa['list'] : [];
					$thanksgiving = isset($thanksgiving_record['list']) ? (array)$thanksgiving_record['list'] : [];
					$seeds = isset($seed_record['list']) ? (array)$seed_record['list'] : [];
				
					// Merge all unique member IDs
					$all_member_ids = array_unique(array_merge(array_keys($tithers), array_keys($thanksgiving), array_keys($seeds)));
				
					if (!empty($all_member_ids)) {
						// Filter out church members who are in any of the records
						$church_memberss = array_values(array_filter($church_memberss, function ($member) use ($all_member_ids) {
							return !in_array($member['id'], $all_member_ids);
						}));

						$church_memberss = array_values($church_memberss);
				
						foreach ($all_member_ids as $member_id) {
							$fullname = $this->Crud->read_field('id', $member_id, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $member_id, 'user', 'surname');
							$phone = $this->Crud->read_field('id', $member_id, 'user', 'phone');
				
							// Get values from each record, default to '0' if not present
							$tithe_amount = isset($tithers[$member_id]) ? $tithers[$member_id] : '0';
							$thanksgiving_amount = isset($thanksgiving[$member_id]) ? $thanksgiving[$member_id] : '0';
							$seed_amount = isset($seeds[$member_id]) ? $seeds[$member_id] : '0';
				
							$table .= '<tr>
								<td>
									<input type="hidden" readonly class="form-control members" name="members[]" value="' . htmlspecialchars($member_id) . '">
									<span class="small">' . htmlspecialchars(strtoupper($fullname)) . ' - ' . htmlspecialchars($phone) . '</span>
								</td>
				
								<td>
									<input type="text" class="form-control offering" name="offering[]" 
										oninput="calculateTotalz(); this.value = this.value.replace(/[^0-9]/g, \'\');" 
										value="' . htmlspecialchars($tithe_amount) . '">
								</td>
								<td>
									<input type="text" class="form-control thanksgiving" name="thanksgiving[]" 
										oninput="calculateTotalz_thanksgiving(); this.value = this.value.replace(/[^0-9]/g, \'\');" 
										value="' . htmlspecialchars($thanksgiving_amount) . '">
								</td>
								<td>
									<input type="text" class="form-control seed" name="seed[]" 
										oninput="calculateTotalz_seed(); this.value = this.value.replace(/[^0-9]/g, \'\');" 
										value="' . htmlspecialchars($seed_amount) . '">
								</td>
							</tr>';
						}
					}
				
					$data['members'] = $church_memberss;
					$data['members_part'] = $table;
				
					echo json_encode($data);
					die;
				}
				
					
				
			}

			if($param2 == 'get_members_thanksgiving'){
				if($param3){
					$data = [];
					$tithersa = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'thanksgiving_record'));
					$church_id = ($this->Crud->read_field('id', $param3, 'service_report', 'church_id'));
					$church = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');

					$church_memberss = [];
					$count = 0;
					if(!empty($church)){
						foreach($church as $c){
							$church_members['id'] = $c->id;
							$church_members['phone'] = $c->phone;
							$church_members['fullname'] = strtoupper($c->firstname.' '.$c->surname);

							$church_memberss[] = $church_members;
						}
					}
					

					
					$table = '';
					$tithers = [];
					if(!empty($tithersa) && isset($tithersa->list)){
						$tithers = (array)$tithersa->list;
					}
					

					
					// print_r($tithers);
					if(!empty($tithers)){
						$tither_ids = array_keys($tithers);

						// Filter out church members who are also tithers
						$church_memberss = array_filter($church_memberss, function($member) use ($tither_ids) {
							return !in_array($member['id'], $tither_ids);
						});

						$church_memberss = array_values($church_memberss);

						foreach($tithers as $tither => $tithe){
							$fullname = $this->Crud->read_field('id', $tither, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $tither, 'user', 'surname');
							$phone = $this->Crud->read_field('id', $tither, 'user', 'phone');
	
							$table .= '<tr>
								<td>
									<input type="hidden" readonly class="form-control members" name="members[]" value="' . htmlspecialchars($tither) . '">
									<span class="small">' . htmlspecialchars(strtoupper($fullname)) . ' - ' . htmlspecialchars($phone) . '</span>
								</td>

								<td>
									<input type="text" class="form-control tithes" name="thanksgiving[]" 
										oninput="calculateTotal(); this.value = this.value.replace(/[^0-9]/g, \'\');" 
										value="' . htmlspecialchars($tithe) . '">
								</td>
							</tr>';

						}
					}
								
					$data['members'] = ($church_memberss);
					$data['members_part'] = $table;



					echo json_encode($data);
					die;
				}
					
				
			}

			if($param2 == 'get_members_seed'){
				if($param3){
					$data = [];
					$tithersa = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'seed_record'));
					$church_id = ($this->Crud->read_field('id', $param3, 'service_report', 'church_id'));
					$church = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');

					$church_memberss = [];
					$count = 0;
					if(!empty($church)){
						foreach($church as $c){
							$church_members['id'] = $c->id;
							$church_members['phone'] = $c->phone;
							$church_members['fullname'] = strtoupper($c->firstname.' '.$c->surname);

							$church_memberss[] = $church_members;
						}
					}
					

					
					$table = '';
					$tithers = [];
					if(!empty($tithersa) && isset($tithersa->list)){
						$tithers = (array)$tithersa->list;
					}
					

					
					// print_r($tithers);
					if(!empty($tithers)){
						$tither_ids = array_keys($tithers);

						// Filter out church members who are also tithers
						$church_memberss = array_filter($church_memberss, function($member) use ($tither_ids) {
							return !in_array($member['id'], $tither_ids);
						});

						$church_memberss = array_values($church_memberss);

						foreach($tithers as $tither => $tithe){
							$fullname = $this->Crud->read_field('id', $tither, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $tither, 'user', 'surname');
							$phone = $this->Crud->read_field('id', $tither, 'user', 'phone');
	
							$table .= '<tr>
								<td>
									<input type="hidden" readonly class="form-control members" name="members[]" value="' . htmlspecialchars($tither) . '">
									<span class="small">' . htmlspecialchars(strtoupper($fullname)) . ' - ' . htmlspecialchars($phone) . '</span>
								</td>

								<td>
									<input type="text" class="form-control tithes" name="seed[]" 
										oninput="calculateTotal(); this.value = this.value.replace(/[^0-9]/g, \'\');" 
										value="' . htmlspecialchars($tithe) . '">
								</td>
							</tr>';

						}
					}
								
					$data['members'] = ($church_memberss);
					$data['members_part'] = $table;



					echo json_encode($data);
					die;
				}
					
				
			}

			
			if($param2 == 'get_members_attendance'){
				if($param3){
					$data = [];
					$attendant = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'attendant'));
					$church_id = ($this->Crud->read_field('id', $param3, 'service_report', 'church_id'));
					// echo $church_id;
					if(empty($church_id)){
						$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
					}
					$church = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'firstname', 'asc');


					$church_memberss = [];
					$count = 0;
					if(!empty($church)){
						foreach($church as $c){
							$church_members['id'] = $c->id;
							$church_members['phone'] = $c->phone;
							$church_members['fullname'] = strtoupper($c->firstname.' '.$c->surname);

							$church_memberss[] = $church_members;
						}
					}
					
					// Extract IDs from the attendant list
					$attendant_ids = [];
					if (isset($attendant->list) && !empty($attendant->list)) {
						foreach ($attendant->list as $at => $attend) {
							if (!empty($attend)) {
								foreach ($attend as $at_val) {
									$attendant_ids[] = $at_val->id; // Collecting all IDs
								}
							}
						}
					}

					// Remove church members whose IDs are in the attendant list
					foreach ($church_memberss as $key => $member) {
						if (in_array($member['id'], $attendant_ids)) {
							unset($church_memberss[$key]); // Remove the member
						}
					}

					// Generate the table
					$table = '';

					if (isset($attendant->list) && !empty($attendant->list)) {
						$attendants = $attendant->list;

						foreach ($attendants as $at => $attend) {
							if ($at == 'present' && !empty($attend)) {
								foreach ($attend as $at_index => $at_val) {
									$id = $at_val->id;
									$name = $this->Crud->read_field('id', $id, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $id, 'user', 'surname');
									$reason = $at_val->reason;
									$to = 'data-bs-toggle="collapse" ';
									$ico = '<span class="accordion-icon"></span>';
									if (empty($reason)) {
										$reason = '';
										$to = '';
										$ico = '';
									}
									$table .= '
										<div class="col-sm-4 mb-3">
											<div id="accordion-' . $id . '" class="accordion accordion-s3">
												<div class="accordion-item"> <a href="javascript:;" class="accordion-head collapsed" ' . $to . '
														data-bs-target="#accordion-item-' . $id . '-1">
														<h6 class="title">' . ucwords($name) . ' <span class="badge bg-success">Present</span></h6>' . $ico . '
													</a>
													<div class="accordion-body collapse" id="accordion-item-' . $id . '-1"
														data-bs-parent="#accordion-' . $id . '">
														<div class="accordion-inner">
															<p>' . ucwords($reason) . '</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									';
								}
							}

							if ($at == 'absent' && !empty($attend)) {
								foreach ($attend as $at_index => $at_val) {
									$id = $at_val->id;
									$name = $this->Crud->read_field('id', $id, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $id, 'user', 'surname');
									$reason = $at_val->reason;
									$to = 'data-bs-toggle="collapse" ';
									$ico = '<span class="accordion-icon"></span>';
									if (empty($reason)) {
										$reason = '';
										$to = '';
										$ico = '';
									}
									$table .= '
										<div class="col-sm-4 mb-3">
											<div id="accordion-' . $id . '" class="accordion accordion-s3">
												<div class="accordion-item"> <a href="javascript:;" class="accordion-head collapsed" ' . $to . '
														data-bs-target="#accordion-item-' . $id . '-1">
														<h6 class="title">' . ucwords($name) . ' <span class="badge bg-danger">Absent</span></h6>' . $ico . '
													</a>
													<div class="accordion-body collapse" id="accordion-item-' . $id . '-1"
														data-bs-parent="#accordion-' . $id . '">
														<div class="accordion-inner">
															<p>' . ucwords($reason) . '</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									';
								}
							}
						}
					}

								
					$data['members'] = ($church_memberss);
					$data['members_part'] = $table;



					echo json_encode($data);
					die;
				}
					
				
			}

			if($param2 == 'service_media'){
				if($param3){
					$data = [];
					$url = '';
					$urls = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'video_url'));
					$media = $this->Crud->read2('type_id', $param3, 'type', 'service', 'service_media');
					$medias = '<div class="row g-gs">';
					if(!empty($media)){
						foreach($media as $m){
							$medias .= '
								
								<div class="col-sm-6 col-lg-4 col-xxl-3">
									<div class="gallery card card-bordered"><a class="gallery-image popup-image"
											href="'.site_url($m->path).'"><img class="w-100 rounded-top" height="200px"
												src="'.site_url($m->path).'" alt=""></a>
										<div
											class="gallery-body card-inner align-center justify-between flex-wrap g-2">
											<div class="user-card">
												<div class="user-avatar">CA</div>
												<div class="user-info"><span class="lead-text">CHURCH ADMIN</span></div>
											</div>
											<div>
											 	<button type="button" onclick="delete_media('.$m->id.', '.$param3.')" class="btn btn-outline-danger btn-icon"><em  class="icon ni ni-trash"></em></button>
                                            </div>
										</div>
									</div>
								</div>
							
							';
						}
					}

					
					$medias .= '</div>';

					if(!empty($urls)){
						foreach($urls as $link => $linka){
							
							$url .=  '
								<span class="tag">
									<a href="'.$linka.'" target="_blank" style="color: white;">'.$linka.'</a><span class="remove_url remove">×</span>
									<input type="hidden" value="'.$linka.'" class="video_link">
								</span>
								
							';
						}
					}

					$data['medias'] = ($medias);
					$data['url'] = $url;



					echo json_encode($data);
					die;
				}
					
				
			}
			
			if($param2 == 'delete_media'){
				if($param3){
					$this->Crud->deletes('id', $param3, 'service_media');
					die;
				}
			}

			
			if($param2 == 'add_url'){
				if ($param3) {
					// Retrieve the existing URLs from the database
					$existing = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'video_url'), true) ?: [];
				
					// Get the new URL from the request
					$url = $this->request->getPost('url');
				
					// Ensure the URL is not empty before adding
					if (!empty($url)) {
						// Add the new URL to the existing array if it doesn't already exist
						if (!in_array($url, $existing)) {
							$existing[] = $url; 
						}
					}
				
					// Prepare the updated data for the database
					$ins['video_url'] = json_encode($existing);
					
					// Update the record in the database
					$this->Crud->updates('id', $param3, 'service_report', $ins);
					die;
				}
				
				
			}

			if ($param2 == 'delete_url') {
				if ($param3) {
					// Retrieve the existing URLs from the database
					$existing = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'video_url'), true) ?: [];
			
					// Get the URL to delete from the request
					$urlToDelete = $this->request->getPost('url');
			
					// Ensure the URL to delete is not empty
					if (!empty($urlToDelete)) {
						// Remove the URL from the existing array if it exists
						$existing = array_filter($existing, function($url) use ($urlToDelete) {
							return $url !== $urlToDelete; // Keep URLs that are not equal to the one to delete
						});
					}
			
					// Prepare the updated data for the database
					$ins['video_url'] = json_encode(array_values($existing)); // Re-index the array
			
					// Update the record in the database
					$this->Crud->updates('id', $param3, 'service_report', $ins);
					
					// Optionally return a response
					echo json_encode(['success' => true, 'message' => 'URL successfully deleted']);
					die;
				}
			}
			
		}

		if($param1 == 'load_churches'){
			$level = $this->request->getPost('level');
			$ministry_id = $this->request->getPost('ministry_id');
			$church_list = array();
			
			$church = $this->Crud->read2_order('ministry_id', $ministry_id, 'type', $level, 'church', 'name', 'asc');
			if(!empty($church)){
				foreach($church as $ch){
					$list['id'] = $ch->id;
					$list['name'] = ucwords($ch->name.' - '.$ch->type);
					$church_list[] = $list;
				}
			}


			$resp['churches'] = $church_list;
			echo json_encode($resp);
			die;
		}

		if($param1 == 'getFormFields'){
			$church_id = $this->request->getPost('church_id'); // Get the dynamic parameter
	
			// Fetch the form fields based on the church_id
			$formFields = $this->Crud->check('church_id', $church_id, 'formfields');
			$formFieldz = $this->Crud->read_single('church_id', $church_id, 'formfields');
			
			$fz = array();
			if(!empty($formFieldz)){
				foreach($formFieldz as $fm){
					$fmz['label'] = ucwords($fm->field_name);
					$fmz['type'] = $fm->field_type;
					$fmz['options'] = explode(',', $fm->field_options);
					$name = strtolower($fm->field_name);
					$fz[$name] = $fmz;
				}
			}
	
			// Default form fields if none are found in the database
			$defaultFields = [
				'firstname' => ['label' => 'Firstname', 'type' => 'text', 'options' => []],
				'surname' => ['label' => 'Surname', 'type' => 'text', 'options' => []],
				'email' => ['label' => 'Email', 'type' => 'email', 'options' => []],
				'phone' => ['label' => 'Phone', 'type' => 'text', 'options' => []],
				'gender' => ['label' => 'Gender', 'type' => 'select', 'options' => ['Male', 'Female']],
				'family_position' => ['label' => 'Family Position', 'type' => 'select', 'options' => ['Child', 'Parent', 'Other']],
				'dob' => ['label' => 'Date of Birth', 'type' => 'date', 'options' => []],
				'invited_by' => ['label' => 'Invited By', 'type' => 'select', 'options' => ['Member', 'Online', 'Other']],
			];
			
			// If fields exist in the database, use them, otherwise use defaults
			$formFields = ($formFields > 0) ? $fz : $defaultFields;
	
			// Return the form fields as a JSON response
			return $this->response->setJSON($formFields);
			die;
		}
		
		
		if($param1 == 'get_attendance_metrics'){
			$service = $this->request->getPost('service'); // e.g. 1st, 2nd, 3rd occurrence
			$church_id = $this->Crud->read_field('id', $service, 'service_report', 'church_id');
			
			$query = $this->Crud->read2_order('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'date', 'asc');
		
			$service_report_id = $service;
			$metric_response = '';

			
			// echo $cell_id;
			$query = $this->Crud->read2('status', 'present', 'service_id', $service, 'service_attendance');
			$timer_query = $this->Crud->read3_order('source_type', 'service', 'source_id', $service_report_id,  'church_id', $church_id, 'visitors', 'fullname', 'asc');
		
			$response = '';
			$response .= '<div class="table-responsive"><table class="table table-hover">';
					
			if (!empty($query)) {
				foreach ($query as $q) {
					
					$status = strtolower($this->Crud->read_field2('member_id', $q->member_id, 'service_id', $service_report_id, 'service_attendance', 'status'));
					$new_convert = ($this->Crud->read_field2('member_id', $q->member_id, 'service_id', $service_report_id, 'service_attendance', 'new_convert'));
					
						// If absent, fetch the reason (optional)
						$absent_reason = '';
						if ($status == 'absent') {
							$absent_reason = $this->Crud->read_field2('member_id', $q->member_id, 'service_id', $service_report_id, 'service_attendance', 'reason');
						}
						
						$firstname = $this->Crud->read_field('id', $q->member_id, 'user', 'firstname');
						$surname = $this->Crud->read_field('id', $q->member_id, 'user', 'surname');
						$othername = $this->Crud->read_field('id', $q->member_id, 'user', 'othername');
						$email = $this->Crud->read_field('id', $q->member_id, 'user', 'email');
						$phone = $this->Crud->read_field('id', $q->member_id, 'user', 'phone');
						
						$response .= '
						<tr>
							<td>' . ucwords(strtolower($firstname . ' ' . $surname . ' ' . $othername)) . '</td>
							<td>'.$this->Crud->mask_email($email).'</td>
							<td>'.$this->Crud->mask_phone($phone).'</td>
							<td style="vertical-align: top;">
								<div class="d-flex justify-content-between align-items-start">
									<div class="w-100">
										<div id="switches_wrapper_'.$q->member_id.'" style="display: none;">
											<div class="mb-2">
												<div class="custom-control custom-switch">
													<input type="checkbox" class="custom-control-input mark-present-switch"
														id="presentSwitch_'.$q->member_id .'"
														data-member-id="'.$q->member_id.'" '.($status == 'present' ? 'checked' : '').'>
													<label class="custom-control-label" for="presentSwitch_'.$q->member_id.'">Mark Present</label>
												</div>
											</div>

											<div class="mb-2">
												<div class="custom-control custom-switch">
													<input type="checkbox" class="custom-control-input mark-absent-switch"
														id="absentSwitch_'.$q->member_id.'"
														data-member-id="'.$q->member_id.'" '.($status == 'absent' ? 'checked' : '').'>
													<label class="custom-control-label" for="absentSwitch_'.$q->member_id.'">Absent</label>
												</div>
											</div>

											<div id="absent_reason_wrapper_'.$q->member_id.'" class="form-group mb-2"
												style="display: '.($status == 'absent' ? 'block' : 'none').';">
												<label for="absent_reason_'.$q->member_id.'" class="form-label">Reason for Absence</label>
												<select class="form-select reason-select" name="absent_reason"
													id="absent_reason_'.$q->member_id.'" data-member-id="'.$q->member_id.'">
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
												<input type="text" class="form-control form-control-sm mt-2 other-reason-input"
													id="other_reason_'.$q->member_id.'"
													placeholder="Please specify"
													style="display: '.(stripos($absent_reason, 'Other') !== false ? 'block' : 'none').';"
													value="'.(stripos($absent_reason, 'Other') !== false ? $absent_reason : '').'" />
											</div>

											<div class="custom-control custom-switch mb-2">
												<input type="checkbox" class="custom-control-input mark-convert-switch"
													id="convertSwitch_'.$q->member_id.'"
													data-type="member"
													data-member-id="'.$q->member_id.'" '.($new_convert == '1' ? 'checked' : '').'>
												<label class="custom-control-label" for="convertSwitch_'.$q->member_id.'">New Convert</label>
											</div>

											<span id="resp_'.$q->member_id.'"></span>
											<span id="con_resp_'.$q->member_id.'"></span>
										</div>
									</div>

									<div>
										<button type="button" class="btn btn-sm btn-outline-primary toggle-switches-btn" data-member-id="'.$q->member_id.'">
											 Action
										</button>
									</div>
								</div>
							</td>

						</tr>';
					
					
				}
				
			} else {
				$response .='<tr><td><div class="text-center text-muted"><em class="icon ni ni-user" style="font-size:150px;"></em><br><br>No Record Found</div></td></tr>';
			}
			if (!empty($timer_query)) {
				foreach ($timer_query as $q) {
				
					$status ='present';
					$response .= '
					<tr>
						<td>' . ucwords(strtolower($q->fullname)).'<br><span class="small text-info">FT</span></td>
						<td>'.$this->Crud->mask_email($q->email).'</td>
						<td>'.$this->Crud->mask_phone($q->phone).'</td>
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
			$response .= '</table></div>';

		
			
			return $this->response->setJSON([
				'metric_response' => $response
			]);
		}

		if($param1 == 'attendance'){
			if($param2 == 'mark_present'){
				$member_id = $this->request->getPost('member_id');
				$service = $this->request->getPost('service_id'); // e.g., "Sunday Service"
				$mark = $this->request->getPost('mark'); // e.g., "Sunday Service"
				$church_id = $this->Crud->read_field('id', $service, 'service_report', 'church_id');
				$service_report_id = $service;

				if(empty($service_report_id)){
					return $this->response->setJSON([
						'status' => 'warning',
						'message' => 'Service report not found.'
					]);
				}

				$exists = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_report_id, 'service_attendance', 'id');
				if($exists == 0){
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
					if(empty($mark)){
						$this->Crud->deletes('id', $exists, 'service_attendance');
						return $this->response->setJSON([
							'status' => 'success',
							'message' => 'Member Unmarked'
						]);
					} else {

						$this->Crud->updates('id', $exists, 'service_attendance', ['status' => 'present']);
						return $this->response->setJSON([
							'status' => 'success',
							'message' => 'Marked as present.'
						]);
					}
					
				}
			
			}

			if($param2 == 'mark_convert'){
				$member_id = $this->request->getPost('member_id');
				$type = $this->request->getPost('type'); // e.g., "Sunday Service"
				$service = $this->request->getPost('service_id'); // e.g., "Sunday Service"
				$mark = $this->request->getPost('mark'); // e.g., "Sunday Service"
				$church_id = $this->Crud->read_field('id', $service, 'service_report', 'church_id');
				$service_report_id = $service;

				if(empty($service_report_id)){
					return $this->response->setJSON([
						'status' => 'warning',
						'message' => 'Service report not found.'
					]);
				}

				if($type == 'member'){
					$exists = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_report_id, 'service_attendance', 'id');
					
					$this->Crud->updates('id', $exists, 'service_attendance', ['new_convert'=>$mark]);
				}

				if($type == 'ft'){
					$this->Crud->updates('id', $member_id, 'visitors', ['new_convert'=>$mark]);
				}
				return $this->response->setJSON([
					'status' => 'success',
					'message' => 'Member Neww Convert Updated'
				]);
				
			
			}

			if ($param2 === 'mark_absent') {
				$member_id = $this->request->getPost('member_id');
				$service = $this->request->getPost('service_id'); // e.g., "Sunday Service"
				$mark = $this->request->getPost('mark'); // e.g., "Sunday Service"
				$church_id = $this->Crud->read_field('id', $service, 'service_report', 'church_id');
				$reason = $this->request->getPost('reason');
				
				// 🛡️ Validation
				if (!$member_id || !$service || !$church_id || !$reason) {
					return $this->response->setJSON([
						'status' => 'error',
						'message' => 'All fields are required.'
					]);
				}
			
				$service_report_id = $service;
			
				// 🛑 If not found
				if (empty($service_report_id)) {
					return $this->response->setJSON([
						'status' => 'warning',
						'message' => 'Service report not found.'
					]);
				}
			
				// ✅ Check if already marked
				$exists = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_report_id, 'service_attendance', 'id');
				
				if(empty($exists)) {
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
					if(empty($mark)){
						$this->Crud->deletes('id', $exists, 'service_attendance');
						return $this->response->setJSON([
							'status' => 'success',
							'message' => 'Member Unmarked'
						]);
					} else {
						$this->Crud->updates('id', $exists, 'service_attendance', ['status' => 'absent','reason' => $reason]);
						return $this->response->setJSON([
							'status' => 'success',
							'message' => 'Marked as absent.'
						]);
					}
				}
			}

			if($param2 == 'get_member'){
				if($_POST){
					$member_id = $this->request->getPost('member_id');
					$service = $this->request->getPost('service');
					$church_id = $this->Crud->read_field('id', $service, 'service_report', 'church_id');
					$response = '';
	
					$mem_couunt = strlen($member_id);
					if($mem_couunt < 3){
						$response =  $this->Crud->msg('danger', 'Enter More than 3 Characters!');
						
					} else {
						if(empty($member_id)){
							$response = $this->Crud->msg('danger', 'Field Cannot be Empty!!');
							
						} else {
							
							$service_report_id = $service;
	
							$query = $this->Crud->filter_member_attendance($member_id, $church_id);
							if(!empty($query)){
								$response .= '<div class="table-responsive"><table class="table table-hover">';
								foreach($query as $q){
										$status = strtolower($this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'status'));
									
										// If absent, fetch the reason (optional)
										$absent_reason = '';
										if ($status == 'absent') {
											$absent_reason = $this->Crud->read_field2('member_id', $q->id, 'service_id', $service_report_id, 'service_attendance', 'reason');
										}

										if ($status != '') continue;
									
										$response .= '
										<tr>
											<td>' . ucwords(strtolower($q->firstname . ' ' . $q->surname . ' ' . $q->othername)) . '</td>
											<td>'.$this->Crud->mask_email($q->email).'</td>
											<td>'.$this->Crud->mask_phone($q->phone).'</td>
											<td>
												<div class="custom-control custom-switch">
													<input type="checkbox"
														class="custom-control-input mark-present-switch"
														id="presentSwitchz_'.$q->id.'"
														data-member-id="'.$q->id.'"
														'.($status == 'present' ? 'checked' : '').'>
													<label class="custom-control-label" for="presentSwitchz_'.$q->id.'">Mark Present</label>
												</div>
									
												<div class="custom-control custom-switch mb-1">
													<input type="checkbox"
														class="custom-control-input mark-absent-switch"
														id="absentSwitchz_'.$q->id.'"
														data-member-id="'.$q->id.'"
														'.($status == 'absent' ? 'checked' : '').'>
													<label class="custom-control-label" for="absentSwitchz_'.$q->id.'">Absent</label>
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
	
								$response .= '</table></div>';
							} else {
								$response = '<div class="text-center text-muted">
									<br/>
									<em class="icon ni ni-user" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Record Found').'
								</div>';
							}
						}
					}
	
					$item['response'] = $response.'<hr>';
					echo json_encode($item);
				}
				die;
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
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 200;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			$search = $this->request->getPost('search');
			$service_id = $this->request->getPost('service_id');
			$church_id = $this->Crud->read_field('id', $service_id, 'service_report', 'church_id');
			// DataTable parameters
			$table = 'user';
		
			// load data into table
			$list = $this->Crud->read2_order('is_member', 1,'church_id', $church_id, $table, 'firstname', 'asc', $limit, $offset);
			if(!empty($search)){
				$list = $this->Crud->read2_order_like('is_member', 1,'church_id', $church_id, $table, 'firstname', 'asc', 'firstname', $search, $limit, $offset);
			}
			$counts = 1;
			foreach ($list as $itema) {
				$id = $itema->id;
				$name = $itema->firstname.' '.$itema->surname;
				$surname = $itema->surname;
				$phone = $itema->phone;
				$church = $this->Crud->read_field('id', $itema->church_id, 'church', 'name');

				$img = $this->Crud->image($itema->img_id, 'big');
				// add manage buttons
				$value = '0';
				if($service_id){
					$convertsa = json_decode($this->Crud->read_field('id', $service_id, 'service_report', 'tithers'));

					$converts =(array) $convertsa->list;
					if(!empty($converts)){
						foreach($converts as $co => $val){
							if($id == $co){
								$value = $val;
							}
						}
					
					}	
				} else {
					$session_tithe = $this->session->get('service_tithe');
					if(!empty($session_tithe)){
						$convertsa = json_decode($session_tithe);
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
						<input type="text" class="form-control tithes" name="tithe[]" id="tithe_'.$itema->id.'" value="'.$value.'" oninput="calculateTotal();this.value = this.value.replace(/[^\d.]/g,\'\');this.value = this.value.replace(/(\..*)\./g,\'$1\')">
					</div>
				';

				
				$item .= '
					<tr>
						<td>

							<div class="user-card">
								<div class="user-avatar ">
									<img alt="" src="'.site_url($img).'" height="40px"/>
								</div>
								<div class="user-info">
									<span class="tb-lead small">'.ucwords($name).'</span><br>
									<span class="small text-info"><em class="icon ni ni-curve-down-right"></em>'.ucwords($church).'</span>
								</div>
								<input type="hidden" name="members[]" value="'.$itema->id.'">
							</div>
						</td>
						<td><span class="small">'.$phone.'</span></td>
						<td>'.$all_btn.'</td>
					</tr>	
				';
						
				$counts += 1;
			}
	
			if(empty($item)) {
				$resp['item'] = '
					
				';
			} else {
				$resp['item'] = $item;
				
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
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_service_report('', '', $search, $log_id);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_service_report($limit, $offset, $search, $log_id);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$type = $q->type;
						$tithe = $q->tithe;
						$seed = $q->seed;
						$thanksgiving = $q->thanksgiving;
						$partnership = $q->partnership;
						$offering = $q->offering;
						$first_timer = $this->Crud->check3('category','first_timer', 'source_type', 'service', 'source_id', $q->id, 'visitors');
						$date = date('d M Y', strtotime($q->date));
						$reg_date = $q->reg_date;
						
						$attend = $this->Crud->check2('status', 'present', 'service_id', $q->id, 'service_attendance');
						$nattend = $this->Crud->check2('new_convert', 1, 'service_id', $q->id, 'service_attendance');

						$attendance = $q->attendance;
						if(empty($attendance)){
							$attendance = (int)$attend + (int)$first_timer;
						}
						$convert = $this->Crud->check4('new_convert', 1, 'category','first_timer', 'source_type', 'service', 'source_id', $q->id, 'visitors');
						$new_convert = (int)$convert + (int)$nattend;


						$types = $this->Crud->read_field('id', $type, 'service_type', 'name');
						
						$cell='';
						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							if(!empty($switch_id)){
								$all_btn = '
								<li><a href="javascript:;" class="text-success pop" pageTitle="View Report" pageName="' . site_url($mod . '/manage/report/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								
								
							';
							} else {
								$all_btn = '
								<li><a href="javascript:;" class="text-primary" onclick="edit_report('.$id.')"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a></li>
								<li><a href="javascript:;" class="text-danger pop" pageTitle="Delete" pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a></li>
								<li><a href="javascript:;" class="text-success pop" pageTitle="View Report" pageName="' . site_url($mod . '/manage/report/' . $id) . '" pageSize="modal-xl"><em class="icon ni ni-eye"></em><span>'.translate_phrase('View').'</span></a></li>
								<li><a href="javascript:;" class="text-secondary" onclick="attendance_report('.$id.')"><em class="icon ni ni-users"></em><span>'.translate_phrase('Attendance').'</span></a></li>
								<li><a href="javascript:;" class="text-warning"  onclick="finance_report('.$id.')"><em class="icon ni ni-money"></em><span>'.translate_phrase('Finance Details').'</span></a></li>
								<li><a href="javascript:;" class="text-info" onclick="media_report('.$id.')"><em class="icon ni ni-img"></em><span>'.translate_phrase('Media').'</span></a></li>
								
								
							';

							}
							
						}

						$item .= '
							<tr>
								<td class="small">' . ucwords($date) . '</td>
								<td class="small">' . ucwords($types) . '</td>
								<td class="small">' . $this->session->get('currency') . number_format((float)$offering, 2) . '</td>
								<td class="small">' . $this->session->get('currency') . number_format((float)$tithe, 2) . '</td>
								<td class="small">' . $this->session->get('currency') . number_format((float)$partnership, 2) . '</td>
								<td class="small">' . $this->session->get('currency') . number_format((float)$thanksgiving, 2) . '</td>
								<td class="small">' . $this->session->get('currency') . number_format((float)$seed, 2) . '</td>
								<td class="small">' . ucwords($attendance) . '</td>
								<td class="small">' . ucwords($first_timer) . '</td>
								<td class="small">' . ucwords($new_convert) . '</td>
								<td class="text-center">
									<div class="dropdow">
										<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown">
											<em class="icon ni ni-more-h"></em>
										</a>
										<div class="dropdown-menu dropdown-menu-end">
											<ul class="link-list-opt no-bdr">
												' . $all_btn . '
											</ul>
										</div>
									</div>
								</td>
							</tr>';

						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'<tr><td colspan="8">
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-linux-server" style="font-size:100px;"></i><br/><br/>'.translate_phrase('No Report Returned').'
					</div></td></tr>
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

	public function update(){
		$service = $this->Crud->read('service_report');
		if(!empty($service)){
			foreach ($service as $s) {
				$church_id = $s->church_id;
				$ministry_id = $s->ministry_id;
				$service_id = $s->id;
				$service_date = $s->date;
				
				// Extract and merge firstname & surname into fullname
				$all_guests = json_decode($s->timers, true);
				$guests = [];

				if (!empty($all_guests)) {
					foreach ($all_guests as $person => $value) {
						if(!empty($value["firstname"]) || !empty($value['surname'])){
							$guests[] = $value["firstname"] . " " . $value["surname"]; // Corrected array access
						}
					}
				}
			
				// Decode finance records
				$offering_data = json_decode($s->offering_givers, true);
				$tithe_data = json_decode($s->tithers, true);
				$thanksgiving_data = json_decode($s->thanksgiving_record, true);
				$seed_data = json_decode($s->seed_record, true);
				$partnership_data = json_decode($s->partners, true);
				
				// Process Guest Contributions
				if (!empty($guests)) {
					foreach ($guests as $guest_name) {
						// echo $s->id.' '.$guest_name.' ';
						// Ensure guest_id is formatted correctly
						$guest_id = "guest_" . str_replace(' ', '_', strtolower($guest_name));
			
						// Define finance types and their respective values
						$financeTypes = [
							'offering' => $offering_data['guest_list'][strtolower($guest_name)] ?? 0,
							'tithe' => $tithe_data['guest_list'][strtolower($guest_name)] ?? 0,
							'thanksgiving' => $thanksgiving_data['guest_list'][strtolower($guest_name)] ?? 0,
							'seed' => $seed_data['guest_list'][strtolower($guest_name)] ?? 0
						];
			
						// Set user type
						$user_type = 'guest';
			
						// Insert or update general finance contributions
						foreach ($financeTypes as $type => $amount) {
							if ($amount > 0) {
								$existingFinance = $this->Crud->read_field4(
									'church_id', $church_id,
									'finance_type', $type,
									'guest', $guest_id,
									'service_id', $service_id,
									'service_finance', 'id'
								);
			
								$gs_fin['amount'] = $amount;
			
								if ($existingFinance) {
									// Update existing record
									$this->Crud->updates('id', $existingFinance, 'service_finance', $gs_fin);
								} else {
									// Insert new record
									$gs_fin['church_id'] = $church_id;
									$gs_fin['finance_type'] = $type;
									$gs_fin['guest'] = $guest_id;
									$gs_fin['user_type'] = $user_type;
									$gs_fin['service_id'] = $service_id;
									$gs_fin['ministry_id'] = $ministry_id;
									$gs_fin['reg_date'] = date('Y-m-d H:i:s');
			
									$this->Crud->create('service_finance', $gs_fin);
								}
							}
						}
			
						// Process Guest Partnerships
						if (!empty($partnership_data['partnership']['guest'][strtolower($guest_name)])) {
							foreach ($partnership_data['partnership']['guest'][strtolower($guest_name)] as $partnership_id => $amount) {
								if ($amount > 0) {
									$existingPartnership = $this->Crud->read_field5(
										'church_id', $church_id,
										'finance_type', 'partnership',
										'guest', $guest_id,
										'service_id', $service_id,
										'partnership_id', $partnership_id,
										'service_finance', 'id'
									);
			
									$gs_part['amount'] = $amount;
			
									if ($existingPartnership) {
										$this->Crud->updates('id', $existingPartnership, 'service_finance', $gs_part);
									} else {
										$gs_part['church_id'] = $church_id;
										$gs_part['finance_type'] = 'partnership';
										$gs_part['guest'] = $guest_id;
										$gs_part['user_type'] = $user_type;
										$gs_part['partnership_id'] = $partnership_id;
										$gs_part['service_id'] = $service_id;
										$gs_part['ministry_id'] = $ministry_id;
										$gs_part['reg_date'] = date('Y-m-d H:i:s');
			
										$this->Crud->create('service_finance', $gs_part);
									}

									// Retrieve existing finance record in partners_history
									$g_existingPartnershipz = $this->Crud->read_field3(
										'guest', $guest_id,
										'service_id', $service_id,
										'partnership_id', $partnership_id,
										'partners_history', 'id'
									);
				
									$gh_part['amount_paid'] = $amount;
				
									if ($g_existingPartnershipz) {
										// Update existing partnership finance record
										$this->Crud->updates('id', $g_existingPartnershipz, 'partners_history', $gh_part);
									} else {
										// Add to the Partners History
										$gh_part['guest'] = $guest_id; // Store guest ID correctly
										$gh_part['church_id'] = $church_id;
										$gh_part['ministry_id'] = $ministry_id;
										$gh_part['service_id'] = $service_id;
										$gh_part['partnership_id'] = $partnership_id;
										$gh_part['status'] = 1;
										$gh_part['date_paid'] = $service_date;
										$gh_part['reg_date'] = date('Y-m-d H:i:s');
				
										$this->Crud->create('partners_history', $gh_part);
									}
								}
							}
						}
					}
				}
			
				// Merge all unique member IDs from all finance lists
				$all_member_ids = array_unique(array_merge(
					array_keys($offering_data['list'] ?? []),
					array_keys($tithe_data['list'] ?? []),
					array_keys($thanksgiving_data['list'] ?? []),
					array_keys($seed_data['list'] ?? []),
					array_keys($partnership_data['partnership']['member'] ?? [])
				));

				// print_r($all_member_ids);
				if(!empty($all_member_ids)){
					// Process Member Contributions
					foreach ($all_member_ids as $member_id) {
						// Define finance types and their respective values
						$financeTypes = [
							'offering' => $offering_data['list'][$member_id] ?? 0,
							'tithe' => $tithe_data['list'][$member_id] ?? 0,
							'thanksgiving' => $thanksgiving_data['list'][$member_id] ?? 0,
							'seed' => $seed_data['list'][$member_id] ?? 0
						];
			
						// Set user type
						$user_type = 'member';
			
						// Insert or update general finance contributions
						foreach ($financeTypes as $type => $amount) {
							if ($amount > 0) {
								$existingFinance = $this->Crud->read_field3('finance_type', $type,'user_id', $member_id,'service_id',$service_id,'service_finance', 'id'
								);
			
								$s_fin['amount'] = $amount;
			
								if ($existingFinance) {
									$this->Crud->updates('id', $existingFinance, 'service_finance', $s_fin);
								} else {
									$s_fin['church_id'] = $church_id;
									$s_fin['finance_type'] = $type;
									$s_fin['user_id'] = $member_id;
									$s_fin['user_type'] = $user_type;
									$s_fin['service_id'] = $service_id;
									$s_fin['ministry_id'] = $ministry_id;
									$s_fin['reg_date'] = date('Y-m-d H:i:s');
			
									$this->Crud->create('service_finance', $s_fin);
								}
							}
						}

						
			
						// Process Member Partnerships
						if (!empty($partnership_data['partnership']['member'][$member_id])) {
							foreach ($partnership_data['partnership']['member'][$member_id] as $partnership_id => $amount) {
								if ($amount > 0) {
									$existingPartnership = $this->Crud->read_field4('finance_type', 'partnership','user_id', $member_id,'service_id', $service_id,'partnership_id', $partnership_id,'service_finance', 'id');
			
									$s_part['amount'] = $amount;
			
									if ($existingPartnership) {
										$this->Crud->updates('id', $existingPartnership, 'service_finance', $s_part);
									} else {
										$s_part['church_id'] = $church_id;
										$s_part['finance_type'] = 'partnership';
										$s_part['user_id'] = $member_id;
										$s_part['user_type'] = $user_type;
										$s_part['partnership_id'] = $partnership_id;
										$s_part['service_id'] = $service_id;
										$s_part['ministry_id'] = $ministry_id;
										$s_part['reg_date'] = date('Y-m-d H:i:s');
			
										$this->Crud->create('service_finance', $s_part);
									}

									// Retrieve existing finance record
									$existingPartnershipz = $this->Crud->read_field3('member_id', $member_id,'service_id', $service_id,'partnership_id', $partnership_id,'partners_history', 'id');
									
									$h_part['amount_paid'] = $amount;
									if ($existingPartnershipz) {
										// Update existing partnership finance record
										$this->Crud->updates('id', $existingPartnershipz, 'partners_history', $h_part);
									} else {
										$church_id = $s->church_id;
										$ministry_id = $s->ministry_id;
										$service_id = $s->id;
										$service_date = $s->date;
										//Add to the Partners History
										$h_part['member_id'] = $member_id;
										$h_part['church_id'] = $church_id;
										$h_part['ministry_id'] = $ministry_id;
										$h_part['service_id'] = $service_id;
										$h_part['partnership_id'] = $partnership_id;
										$h_part['status'] = 1;
										$h_part['date_paid'] = $service_date;
										$h_part['reg_date'] = date(fdate);
										if($amount > 0)$this->Crud->create('partners_history', $h_part);
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