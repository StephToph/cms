<?php

namespace App\Controllers;

class Notification extends BaseController {

    /////// ACTIVITIES
	public function list($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 
		$log_id = $this->session->get('td_id');
		
        $mod = 'notification';

        $log_id = $this->session->get('td_id');
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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
		
        $data['fullname'] = $this->Crud->read_field('id', $log_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $log_id, 'user', 'surname');
        $data['email'] = $this->Crud->read_field('id', $log_id, 'user', 'email');
       $data['phone'] = $this->Crud->read_field('id', $log_id, 'user', 'phone');
        $data['reg_date'] = $this->Crud->read_field('id', $log_id, 'user', 'reg_date');
		$table = 'notify';

        $form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		
		// record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 25;
			$item = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			$search = $this->request->getVar('search');
			if(!empty($this->request->getPost('start_date'))) { $start_date = $this->request->getPost('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getPost('end_date'))) { $end_date = $this->request->getPost('end_date'); } else { $end_date = ''; }
			
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$all_rec = $this->Crud->filter_notification('', '', $log_id, $search, $start_date, $end_date);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_notification($limit, $offset, $log_id, $search, $start_date, $end_date);
				$data['count'] = $counts;
                // $query = json_decode($query);
				//print_r($query);
				if (!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$type = $q->item;
						$type_id = $q->item_id;
						$content = $q->content;
						$new = $q->new;
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));

						$timespan = $this->Crud->timespan(strtotime($q->reg_date));

						$icon = 'vol';

						$st = '<span class="text-danger"  id="read_stat'.$id.'">'.translate_phrase('Unread').'</span> &nbsp; &nbsp;';
						$btn = '
						<span id="read_btn'.$id.'"><a href="javascript:;" class="text-primary" onclick="reads('.$id.')">
							<em class="icon ni ni-check-circle-cut"></em> '.translate_phrase('Mark as Read').'
							</a>&nbsp;
						</span><br>
						';
						if($new == 0){
							$st = '<span class="text-success" id="read_stat'.$id.'">'.translate_phrase('Read').'</span>';
							$btn = '

							';
						}
						
						$item .= '
							<tr class="nk-tb-item">
								<td class="nk-tb-col">
									<div class="row">
										<div class="col-sm-10">
											<div class="project-info">
												<h6 class="title">'.ucwords($content).' <small>on '.$reg_date.'</small></h6>
												
											</div>
										</div>
										<div class="col-sm-2">
											'.$st.'<br>
											<span>'.$timespan.'</span><br>
											'.$btn.'
											<span id="read_resp'.$id.'"></span>
										
										</div>
									</div>
								</td>
							</tr><!-- .nk-tb-item -->       
						';
					}
				}
			}
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<em class="icon ni ni-vol" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Notification Returned').'
					</div>
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
		
        $data['current_language'] = $this->session->get('current_language');
		
		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			// for datatable
			//$data['table_rec'] = $mod.'/list'; // ajax table
			//$data['order_sort'] = '0, "asc"'; // default ordering (0, 'asc')
			//$data['no_sort'] = '5,6'; // sort disable columns (1,3,5)
		
			$data['title'] = translate_phrase('Notification').' - '.app_name;
			$data['page_active'] = $mod;

			return view($mod.'/list', $data);
		}
	
	}

	public function mark_read($id){
		if($id){
			$upd = $this->Crud->updates('id', $id, 'notify', array('new'=>0));
			if($upd > 0){
				echo '<span class="text-success">'.translate_phrase('Marked').'</span>';
				echo '<script>$("#read_stat'.$id.'").html("Read");$("#read_btn'.$id.'").hide();</script>';
			} else{
				echo '<span class="text-danger">'.translate_phrase('Please Try Again').'</span>';
				
			}
		}
	}

}
