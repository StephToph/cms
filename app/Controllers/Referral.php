<?php

namespace App\Controllers;

class Referral extends BaseController {

	public function list($param1='', $param2='', $param3='', $param4='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('sh_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'referral/list';

        $log_id = $this->session->get('td_id');
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
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
       
		$table = 'referral';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= '/'.$param3;}
		if($param4){$form_link .= '/'.$param4;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['param4'] = $param4;
		$data['form_link'] = $form_link;
		
        if($param1 == 'view') {
			if($param2) {
				$edit = $this->Crud->read_single('id', $param2, $table);
				
				$data['e_start_date'] = $this->session->get('ref_start_date');
				$data['e_end_date'] = $this->session->get('ref_end_date');
				if(!empty($edit)) {
					foreach($edit as $e) {
						$data['e_id'] = $e->id;
						$data['e_user_id'] = $e->user_id;
						$data['e_referral_id'] = $e->referral_id;
						$data['e_reg_date'] = date('d F, Y H:iA', strtotime($e->reg_date));
						
					}
				}
			} 
		}

        	// record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$rec_limit = 50;
			$item = '';
            if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
            $items = '';
            $counts = 0;

			$log_id = $this->session->get('td_id');
            $search = $this->request->getVar('search');
			if(!empty($this->request->getVar('start_date'))){$start_date = $this->request->getVar('start_date');}else{$start_date = date('Y-m-01');}
			if(!empty($this->request->getVar('end_date'))){$end_date = $this->request->getVar('end_date');}else{$end_date = date('Y-m-d');}

			$this->session->set('ref_start_date', $start_date);
			$this->session->set('ref_end_date', $end_date);
			
            $a = 0;
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {

				$query = $this->Crud->filter_referral($limit, $offset, $log_id, $search, $start_date, $end_date);
				$all_rec = $this->Crud->filter_referrals('', '', $log_id, $search, $start_date, $end_date);
				if(!empty($all_rec)) { $count = count($all_rec); } else { $count = 0; }

				$counts = count($all_rec);
                $items .= '
                <div class="nk-tb-item nk-tb-head">
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('User').'</span></div>
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Referral').'</span></div>
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Status').'</span></div>
                </div><!-- .nk-tb-item -->
            
                ';
                // if($query->status == true){
                    if(!empty($query)) {
                        foreach($query as $q) {
							$vendor_id = $q->user_id;
							$referral_id = $q->referral_id;
							$referral_status = $q->referral_status;
							$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));

							// vendor 
							$vendor = $this->Crud->read_field('id', $vendor_id, 'user', 'fullname');
							$vendor_email = $this->Crud->read_field('id', $vendor_id, 'user', 'email');
							$vendor_image_id = $this->Crud->read_field('id', $vendor_id, 'user', 'img_id');
							$vendor_image = $this->Crud->image($vendor_image_id, 'big');
							
							// referral
							$referral = $this->Crud->read_field('id', $referral_id, 'user', 'fullname');
							$referral_email = $this->Crud->read_field('id', $referral_id, 'user', 'email');
							$referral_image_id = $this->Crud->read_field('id', $referral_id, 'user', 'img_id');
							$referral_image = $this->Crud->image($referral_image_id, 'big');

							// color
							$color = 'success';
							if(empty($referral_status)) $referral_status = 'Pending';
							if($referral_status == 'Pending') { $color = 'danger'; }
                            
                            $item .= '
                            <div class="nk-tb-item">
                                <div class="nk-tb-col">
                                    <div class="user-card">
                                        <div class="user-avatar ">
                                            <img alt="" src="' . site_url($vendor_image) . '" height="40px"/>
                                        </div>
                                        <div class="user-info">
                                            <span class="tb-lead">'.strtoupper($vendor).'</span></span>
											<span class="tb-lead text-danger">'.ucwords($vendor_email).'</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-tb-col tb-col">
									<div class="user-card">
                                        <div class="user-info">
											<span class="tb-lead">'.strtoupper($this->Crud->date_check1($start_date, 'reg_date', $end_date, 'reg_date', 'user_id', $vendor_id, 'referral'). ' Referral').'</span></span>
										</div>
									</div>
                                </div>
								<div class="nk-tb-col tb-col">
                                    <a href="javascript:;" class="text- btn btn-primary pop" pageTitle="View '.$vendor.' Referral List" pageName="'.site_url($mod.'/view/'.$vendor_id).'">
                                <i class="ni ni-eye"></i> '.translate_phrase('View').'
                            </a>
								</div>
                            </div><!-- .nk-tb-item -->
                        
                            ';
							$cou = $this->Crud->check('user_id', $vendor_id, 'referral');
							$a += $cou;
                        }
                    }
                }
				
			// }
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-users" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Referral Returned').'
					</div>
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
        $data['current_language'] = $this->session->get('current_language');

        if($param1 == 'view') { // view for form data posting
			return view('referral/form', $data);
        } else { // view for main page

            $data['title'] = translate_phrase('Referral').' - ' . app_name;
            $data['page_active'] = $mod;
            return view($mod, $data);
        }
    }


	
}
