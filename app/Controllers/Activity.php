<?php

namespace App\Controllers;

class Activity extends BaseController {

    /////// ACTIVITIES
	public function index($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('fls_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 
		$log_id = $this->session->get('td_id');
		
	   
        $mod = 'activity';

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
		
        $data['current_language'] = $this->session->get('current_language');
        $data['fullname'] = $this->Crud->read_field('id', $log_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $log_id, 'user', 'surname');
        $data['email'] = $this->Crud->read_field('id', $log_id, 'user', 'email');
       $data['phone'] = $this->Crud->read_field('id', $log_id, 'user', 'phone');
        $data['reg_date'] = $this->Crud->read_field('id', $log_id, 'user', 'reg_date');
		$table = 'activity';

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
				$all_rec = $this->Crud->filter_activity('', '', $log_id, $search);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_activity($limit, $offset, $log_id, $search);
				$data['count'] = $counts;
				
				if (!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$type = $q->item;
						$type_id = $q->item_id;
						$action = $q->action;
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));

						$timespan = $this->Crud->timespan(strtotime($q->reg_date));

						$icon = 'article';
						if($type == 'orders') $icon = 'template';
						if($type == 'branch') $icon = 'reports-alt';
						if($type == 'business') $icon = 'briefcase';
						if($type == 'order') $icon = 'bag';
						if($type == 'user') $icon = 'users';
						if($type == 'pump') $icon = 'cc-secure';
						if($type == 'authentication') $icon = 'article';
						if($type == 'enrolment') $icon = 'property-add';
						if($type == 'scholarship') $icon = 'award';

						$item .= '
							<tr class="nk-tb-item">
								<td class="nk-tb-col">
									<a href="javascript:;" class="project-title">
										<div class=""><em class="icon ni ni-'.$icon.' text-muted" style="font-size:30px;"></em></div>
										<div class="project-info">
											<h6 class="title"> '.translate_phrase($action).'<small> on '.$reg_date.'</small></h6>
										</div>
									</a>
								</td>
								<td class="nk-tb-col tb-col-lg">
									<span>'.$timespan.'</span>
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
						<em class="icon ni ni-property" style="font-size:150px;"></em><br/><br/>'.translate_phrase('No Activity Returned').'
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

		
		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			// for datatable
			//$data['table_rec'] = $mod.'/list'; // ajax table
			//$data['order_sort'] = '0, "asc"'; // default ordering (0, 'asc')
			//$data['no_sort'] = '5,6'; // sort disable columns (1,3,5)
		
			$data['title'] = translate_phrase('Activity').' - '.app_name;
			$data['page_active'] = $mod;

			return view($mod.'/list', $data);
		}
	
	}

}
