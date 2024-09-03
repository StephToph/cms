<?php

namespace App\Controllers;

class Wallets extends BaseController {

    
    //Transaction Code
	public function code($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('fls_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 
		$log_id = $this->session->get('td_id');
		if($this->Crud->check2('id', $log_id, 'pin', 0, 'user')> 0)return redirect()->to(site_url('auth/security'));
       
        $mod = 'wallets/code';

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
       
		$table = 'voucher';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'recall') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
                    //echo var_dump($edit);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$code_id =  $this->request->getVar('code_id');
						
						if($code_id ) {
							$datas['code_id'] = $code_id;
							
							$resp = $this->Crud->api('post', 'transaction/recall/'.$log_id, $datas);
				
							$resp = json_decode($resp);
				
							//print_r($resp);
							if($resp->status == true) {
								echo $this->Crud->msg($resp->code, translate_phrase($resp->msg));
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg($resp->code, translate_phrase($resp->msg));
								
							}
							die;
						} 	
					}
				}
			} else {
				// prepare for edit
				if($param2 == 'send') {
					if($param3) {
						$edit = $this->Crud->read_single('id', $param3, $table);
						if(!empty($edit)) {
							foreach($edit as $e) {
								$data['e_id'] = $e->id;
								$data['e_code'] = $e->code;
								
							}
						}
					}
				}

				
				if($this->request->getMethod() == 'post'){
					$code_id =  $this->request->getVar('code_id');
					$user_id =  $this->request->getVar('user_id');
					$phone =  $this->request->getVar('email');
					
					// do create or update
					if($code_id) {
						$datas['code_id'] = $code_id;
						$datas['user_id'] = $user_id;
						$datas['phone'] = $phone;
						$resp = $this->Crud->api('post', 'transaction/sms/'.$log_id, $datas);
            
						$resp = json_decode($resp);
			
						//print_r($resp);
						if($resp->status == true) {
							echo $this->Crud->msg($resp->code, translate_phrase($resp->msg));
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg($resp->code, translate_phrase($resp->msg));
							
						}
						die;
					} 
					die;
						
				}
			}
		}

        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 25;
			$item = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			if(!empty($this->request->getVar('start_date'))) { $start_date = $this->request->getVar('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getVar('end_date'))) { $end_date = $this->request->getVar('end_date'); } else { $end_date = ''; }
			$search = $this->request->getVar('search');

			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$a = 1;
				$all_rec = $this->Crud->api('get', 'voucher/get', '&log_id='.$log_id.'&start_date='.$start_date.'&end_date='.$end_date.'&search='.$search);
                $all_rec = json_decode($all_rec);
				if(!empty($all_rec->data)) { $counts = count($all_rec->data); } else { $counts = 0; }

				$query = $this->Crud->api('get', 'voucher/get', 'limit='.$limit.'&offset='.$offset.'&log_id='.$log_id.'&start_date='.$start_date.'&end_date='.$end_date.'&search='.$search);
				$data['count'] = $counts;
                $query = json_decode($query);

				//print_r($query);
				$items = '<div class="nk-tb-item nk-tb-head">
						<div class="nk-tb-col"><span>'.translate_phrase('Cash Code').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Account').'</span></div>
						<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Amount').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Status').'</span></div>
						<div class="nk-tb-col "><span class="sub-text"></span></div>
					</div><!-- .nk-tb-item -->
				';

				if ($query->status == true) {
					if (!empty($query->data)) {
						foreach($query->data as $q) {
							$id = $q->id;
							$user_id = $q->user_id;
							$used_by = $q->used_by;
							$code = $q->code;
							$amount = number_format((float)$q->amount, 2);
							$used_date = date('M d, Y h:i A', strtotime($q->used_date));
							$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));

							$used = '';
							$u_date = '';
							$us_date = '';
							$status = 'UNUSED';
							$st_code = 'danger';
							$btn = '
								<div class="dropdown">
									<a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
									<div class="dropdown-menu dropdown-menu-end dropdown-menu-xs">
										<ul class="link-list-plain">
											<li><a href="javascript:;" onclick="copys('.$a.')">'.translate_phrase('Copy Code').'</a></li>
											<li><a href="javascript:;" class="pop" pageTitle="'.translate_phrase('Send Transaction Code').'" pageName="'.site_url('wallets/code/manage/send/'.$id).'">'.translate_phrase('Send as SMS').'</a></li>
											<li><a href="javascript:;" class="pop" pageTitle="'.translate_phrase('Recall Transaction Code').'" pageName="'.site_url('wallets/code/manage/recall/'.$id).'">'.translate_phrase('Recall').'</a></li>
										</ul>
									</div>
									<span class="text-info" id="copy_resp'.$a.'"></span>
								</div>
							
							';
							if(!empty($used_by) && $used_date != 'null'){
								$used = $this->Crud->read_field('id', $used_by, 'user', 'fullname');
								$status = 'USED';
								$st_code = 'success';
								$u_date = 	'<span class="text-muted">'.$used_date.'</span><br>';
								$us_date = $used_date;
								$btn = '';
								$code = $this->Crud->read_field('transaction_code', $code, 'transaction', 'code');
							}
							// user 
							$user = $this->Crud->read_field('id', $user_id, 'user', 'fullname');

							
							// currency
							$curr = '&#8358;';

							$item .= '
								<div class="nk-tb-item">
									<div class="nk-tb-col">
										<small>'.$reg_date.'</small><br>
										<span class="fw-bold text-success">'.$code.'</span>
									</div>
									<div class="nk-tb-col tb-col-md">
										<div class="user-card">
											<div class="user-info">
												'.$user.'
											</div>
										</div>
									</div>
									<div class="nk-tb-col">
										<small class=" d-md-none d-sm-block">'.$us_date.'</small><br>
										<span class="text-dark fw-bold">'.$curr.$amount.'</span><br>
										<small class="badge badge-dot text-'.$st_code.' d-md-none d-sm-block">'.$status.'</small><br>
										<small class="fw-bold d-md-none d-sm-block">'.$used.'</small>
										
									</div>
									<div class="nk-tb-col tb-col-md">
										'.$u_date.'
										<span class="badge badge-dot text-'.$st_code.'">'.$status.'</span><br>
										<span class="text-primary fw-bold">'.$used.'</span>
									</div>
									<div class="nk-tb-col tb-col-m">
										'.$btn.'
									</div>
								</div><input type="hidden" id="copy_text'.$a.'" value="'.$code.'">
							';

							$a++;
						}

					}
				}
			}
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-tranx" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Transaction Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
				if($offset >= 25){
					$resp['item'] = $item;
				
				}
			}
			
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
			
			$data['title'] = translate_phrase('Transaction Code').'  | '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	public function transaction($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('fls_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 
		$log_id = $this->session->get('td_id');
		if($this->Crud->check2('id', $log_id, 'pin', 0, 'user')> 0)return redirect()->to(site_url('auth/security'));
       
        $mod = 'wallets/transaction';
        $data['current_language'] = $this->session->get('current_language');

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
       
		$table = 'transaction';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
                    //echo var_dump($edit);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$del_id =  $this->request->getVar('d_pump_id');
                        $code = $this->Crud->read_field('id', $del_id, 'pump', 'name');
						$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
						$action = $by.' deleted pump ('.$code.')';
							
                        if($this->Crud->deletes('id', $del_id, $table) > 0) {

							///// store activities
							$this->Crud->activity('pump', $del_id, $action);
							
							echo $this->Crud->msg('success', 'Record Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						die;	
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
								$data['e_product'] = $e->product;
								$data['e_price'] = $e->price;
								
							}
						}
					}
				}

				
				if($this->request->getMethod() == 'post'){
					$pump_id =  $this->request->getVar('pump_id');
					$name =  $this->request->getVar('name');
					$product =  $this->request->getVar('product');
					$price =  $this->request->getVar('price');
					
					// do create or update
					if($pump_id) {
						$upd_data['name'] = $name;
						$upd_data['product'] = $product;
						$upd_data['price'] = $price;
						
						$upd_rec = $this->Crud->updates('id', $pump_id, $table, $upd_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
							$code = $this->Crud->read_field('id', $pump_id, 'pump', 'name');
							$action = $by.' updated Pump ('.$code.') Record';
							$this->Crud->activity('pump', $pump_id, $action);

							echo $this->Crud->msg('success', 'Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
                        die;
					} else {
						if($this->Crud->check2('name', $name, 'user_id', $log_id, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_data['name'] = $name;
							$ins_data['product'] = $product;
							$ins_data['price'] = $price;
							$ins_data['user_id'] = $log_id;
							$ins_data['reg_date'] = date(fdate);
							
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', 'Record Created');
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
								$code = $this->Crud->read_field('id', $ins_rec, 'pump', 'name');
								$action = $by.' created Pump ('.$code.') Record';
								$this->Crud->activity('pump', $ins_rec, $action);
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', 'Please try later');	
							}	
						}

					}die;
						
				}
			}
		}


		if($param1 == 'view') {
			if($param2) {
				$edit = $this->Crud->read_single('id', $param2, $table);
				if(!empty($edit)) {
					foreach($edit as $e) {
						$data['e_id'] = $e->id;
						$data['e_code'] = $e->code;
						$data['e_state_id'] = $e->state_id;
						$data['e_country_id'] = $e->country_id;
						$data['e_ref'] = $e->ref;
						$data['e_amount'] = $e->amount;
						$data['e_reg_date'] = date('d F, Y H:iA', strtotime($e->reg_date));
						$data['e_user_id'] = $e->user_id;
						$data['e_merchant_id'] = $e->merchant_id;
						$data['e_payment_type'] = $e->payment_type;
						$data['e_payment_method'] = $e->payment_method;
						$data['e_transaction_code'] = $e->transaction_code;
						$data['e_remark'] = $e->remark;
						$data['e_state'] = $this->Crud->read_field('id', $e->state_id, 'state', 'name');

						// currency
						$curr = '&#8358;';
						$data['e_curr'] = $curr;

						$customer = $this->Crud->read_field('id', $e->user_id, 'user', 'fullname');
						$customer_phone = $this->Crud->read_field('id', $e->user_id, 'user', 'phone');
						$data['e_customer'] = $customer;
						$data['e_customer_phone'] = $customer_phone;
						$merchant = $this->Crud->read_field('id', $e->merchant_id, 'user', 'username');
						$merchant_phone = $this->Crud->read_field('id', $e->merchant_id, 'user', 'phone');
						$data['e_merchant_phone'] = $merchant_phone;
						$data['e_merchant'] = $merchant;
						
					}
				}
			}
		}


        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 25;
			$item = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			if(!empty($this->request->getVar('type'))) { $type = $this->request->getVar('type'); } else { $type = ''; }
			if(!empty($this->request->getVar('start_date'))) { $start_date = $this->request->getVar('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getVar('end_date'))) { $end_date = $this->request->getVar('end_date'); } else { $end_date = ''; }
			$search = $this->request->getVar('search');

			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$all_rec = $this->Crud->api('get', 'transaction/get', '&log_id='.$log_id.'&start_date='.$start_date.'&end_date='.$end_date.'&search='.$search.'&type='.$type);
                $all_rec = json_decode($all_rec);
				if(!empty($all_rec->data)) { $counts = count($all_rec->data); } else { $counts = 0; }

				$query = $this->Crud->api('get', 'transaction/get', 'limit='.$limit.'&offset='.$offset.'&log_id='.$log_id.'&start_date='.$start_date.'&end_date='.$end_date.'&search='.$search.'&type='.$type);
				$data['count'] = $counts;
                $query = json_decode($query);
				
				//print_r($query);
				$curr = '&#8358;';
				$items = '	
					<div class="nk-tb-item nk-tb-head">
						<div class="nk-tb-col"><span>'.translate_phrase('Transaction Code').'</span></div>
						<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Account').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Payment Type').'</span></div>
						<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Amount').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Status').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Date').'</span></div>
					</div><!-- .nk-tb-item -->
						';

				if ($query->status == true) {
					if (!empty($query->data)) {
						foreach($query->data as $q) {
							$id = $q->id;
							$user_id = $q->user_id;
							$payment_type = $q->payment_type;
							$payment_method = $q->payment_method;
							$code = $q->code;
							$merchant_id = $q->merchant_id;
							$status = $q->status;
							$amount = number_format((float)$q->amount, 2);
							$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));


							// user 
							$user = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
							$user_role_id = $this->Crud->read_field('id', $user_id, 'user', 'role_id');
							$user_role = strtoupper($this->Crud->read_field('id', $user_role_id, 'access_role', 'name'));
							$user_image_id = $this->Crud->read_field('id', $user_id, 'user', 'img_id');
							$user_image = $this->Crud->image($user_image_id, 'big');

							// merchant 
							$merchant = $this->Crud->read_field('id', $merchant_id, 'user', 'fullname');
							$merchant_role_id = $this->Crud->read_field('id', $merchant_id, 'user', 'role_id');
							$merchant_role = strtoupper($this->Crud->read_field('id', $merchant_role_id, 'access_role', 'name'));
							$merchant_image_id = $this->Crud->read_field('id', $merchant_id, 'user', 'img_id');
							$merchant_image = $this->Crud->image($merchant_image_id, 'big');

							$mer = '';
							if(!empty($merchant_id)){
								$mer = '<span class="text-danger">'.$merchant.'</span>';
							}
							
							$act = $mer.'<br><span class="text-primary">&rarr;'.$user.'</span>';
							if($payment_type == 'transport' || $payment_type == 'transfer' ){
								if($q->reg_date >= '2023-07-15'){
									$mer = '';
								
									if(!empty($merchant_id)){
										$mer = '<br><span class="text-primary">&rarr;'.$merchant.'</span>';
									}
									$act = '<span class="text-danger">'.$user.'</span>'.$mer;

								}
							}

							if($payment_type == 'environment' || $payment_type == 'bin' ){
									$mer = '';
								
									if(!empty($merchant_id)){
										$mer = '<br><span class="text-primary">&rarr;'.$merchant.'</span>';
									}
									$act = '<span class="text-danger">'.$user.'</span>'.$mer;

								
							}

							if($payment_type == 'transact'){
								$payment_type = 'Transaction Code';

								$act = '<span class="text-info">'.$user.'</span>';
							} 
							$admin = $this->Crud->read_field('username', 'FUNDME CASH ADMIN', 'user', 'id');
		
							if($payment_type == 'deposit'){
								$payment_type = 'Deposit via '.$payment_method;
								if($admin == $user_id){
									$payment_type = 'Deposit Commission';
								}
							} 

							if($payment_type == 'withdraw'){
								if($admin == $user_id){
									$payment_type = 'Withdrawal Commission';
								}
							} 

							if($payment_type == 'sms'){
								$payment_type = 'SMS Charge';

								$act = '<span class="text-info">'.$user.'</span>';
							} 

							if($payment_type == 'health'){
								$mer = '';
								
								if(!empty($merchant_id)){
									$mer = '<br><span class="text-primary">&rarr;'.$merchant.'</span>';
								}
								$act = '<span class="text-danger">'.$user.'</span>'.$mer;

							}
							
							
							// currency
							$curr = '&#8358;';

							// color
							$color = 'success';
							if($payment_type == 'debit') { $color = 'danger'; }

							$item .= '
								<div class="nk-tb-item">
									<div class="nk-tb-col">
										<small class="text-muted d-md-none d-sm-block">'.strtoupper($reg_date).'</small><br>
										<span class="fw-bold text-success pop">
											<a href="javascript:;" class="text-success pop" pageTitle="View" pageName="'.site_url('wallets/transaction/view/'.$id).'" pageSize="modal-lg">
												<i class="ni ni-edit"></i> <span class="m-l-3 m-r-10"><b>'.$code.'</b></span>
											</a></span><br>
										<span class="fw-bold text-secondary d-md-none d-sm-block">'.translate_phrase(strtoupper($payment_type)).'</span>
									</div>
									<div class="nk-tb-col">
										<div class="user-card">
											<div class="user-info">
												'.$act.'
											</div>
										</div>
									</div>
									<div class="nk-tb-col tb-col-md">
										<span class="fw-bold text-secondary">'.translate_phrase(strtoupper($payment_type)).'</span>
									</div>
									<div class="nk-tb-col">
										<span>'.$curr.$amount.'</span><br>
										<span class="badge badge-dot text-success d-md-none d-sm-block">Success</span>
									</div>
									<div class="nk-tb-col tb-col-md">
										<span class="badge badge-dot text-success">Success</span><br>
									</div>
									<div class="nk-tb-col tb-col-md">
										<span>'.$reg_date.'</span>
									</div>
								</div>
								
							';
						}
					}
				}
			}
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-tranx" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Transaction Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
				if($offset >= 25){
					$resp['item'] = $item;
				
				}
			}
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

		if($param1 == 'manage' || $param1 == 'view') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Transactions').' - '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	public function cashback($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('fls_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 
		$log_id = $this->session->get('td_id');
		if($this->Crud->check2('id', $log_id, 'pin', 0, 'user')> 0)return redirect()->to(site_url('auth/security'));
       
        $mod = 'wallets/cashback';

        $data['current_language'] = $this->session->get('current_language');
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
       
		$table = 'transaction';
		$form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
                    //echo var_dump($edit);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$del_id =  $this->request->getVar('d_pump_id');
                        $code = $this->Crud->read_field('id', $del_id, 'pump', 'name');
						$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
						$action = $by.' deleted pump ('.$code.')';
							
                        if($this->Crud->deletes('id', $del_id, $table) > 0) {

							///// store activities
							$this->Crud->activity('pump', $del_id, $action);
							
							echo $this->Crud->msg('success', 'Record Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						die;	
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
								$data['e_product'] = $e->product;
								$data['e_price'] = $e->price;
								
							}
						}
					}
				}

				
				if($this->request->getMethod() == 'post'){
					$pump_id =  $this->request->getVar('pump_id');
					$name =  $this->request->getVar('name');
					$product =  $this->request->getVar('product');
					$price =  $this->request->getVar('price');
					
					// do create or update
					if($pump_id) {
						$upd_data['name'] = $name;
						$upd_data['product'] = $product;
						$upd_data['price'] = $price;
						
						$upd_rec = $this->Crud->updates('id', $pump_id, $table, $upd_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
							$code = $this->Crud->read_field('id', $pump_id, 'pump', 'name');
							$action = $by.' updated Pump ('.$code.') Record';
							$this->Crud->activity('pump', $pump_id, $action);

							echo $this->Crud->msg('success', 'Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
                        die;
					} else {
						if($this->Crud->check2('name', $name, 'user_id', $log_id, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_data['name'] = $name;
							$ins_data['product'] = $product;
							$ins_data['price'] = $price;
							$ins_data['user_id'] = $log_id;
							$ins_data['reg_date'] = date(fdate);
							
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', 'Record Created');
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
								$code = $this->Crud->read_field('id', $ins_rec, 'pump', 'name');
								$action = $by.' created Pump ('.$code.') Record';
								$this->Crud->activity('pump', $ins_rec, $action);
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', 'Please try later');	
							}	
						}

					}die;
						
				}
			}
		}


		if($param1 == 'view') {
			if($param2) {
				$edit = $this->Crud->read_single('id', $param2, $table);
				if(!empty($edit)) {
					foreach($edit as $e) {
						$data['e_id'] = $e->id;
						$data['e_code'] = $e->code;
						$data['e_state_id'] = $e->state_id;
						$data['e_country_id'] = $e->country_id;
						$data['e_ref'] = $e->ref;
						$data['e_amount'] = $e->amount;
						$data['e_reg_date'] = date('d F, Y H:iA', strtotime($e->reg_date));
						$data['e_user_id'] = $e->user_id;
						$data['e_merchant_id'] = $e->merchant_id;
						$data['e_payment_type'] = $e->payment_type;
						$data['e_payment_method'] = $e->payment_method;
						$data['e_transaction_code'] = $e->transaction_code;
						$data['e_remark'] = $e->remark;
						$data['e_state'] = $this->Crud->read_field('id', $e->state_id, 'state', 'name');

						// currency
						$curr = '&#8358;';
						$data['e_curr'] = $curr;

						$customer = $this->Crud->read_field('id', $e->user_id, 'user', 'fullname');
						$customer_phone = $this->Crud->read_field('id', $e->user_id, 'user', 'phone');
						$data['e_customer'] = $customer;
						$data['e_customer_phone'] = $customer_phone;
						$merchant = $this->Crud->read_field('id', $e->merchant_id, 'user', 'username');
						$merchant_phone = $this->Crud->read_field('id', $e->merchant_id, 'user', 'phone');
						$data['e_merchant_phone'] = $merchant_phone;
						$data['e_merchant'] = $merchant;
						
					}
				}
			}
		}


        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 105;
			$item = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			if(!empty($this->request->getVar('type'))) { $type = $this->request->getVar('type'); } else { $type = ''; }
			if(!empty($this->request->getVar('start_date'))) { $start_date = $this->request->getVar('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getVar('end_date'))) { $end_date = $this->request->getVar('end_date'); } else { $end_date = ''; }
			$search = $this->request->getVar('search');
			$type = 'transport';
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				$all_rec = $this->Crud->api('get', 'transaction/get', '&log_id='.$log_id.'&start_date='.$start_date.'&end_date='.$end_date.'&search='.$search.'&type='.$type);
                $all_rec = json_decode($all_rec);
				if(!empty($all_rec->data)) { $counts = count($all_rec->data); } else { $counts = 0; }

				$query = $this->Crud->api('get', 'transaction/get', '&log_id='.$log_id.'&start_date='.$start_date.'&end_date='.$end_date.'&search='.$search.'&type='.$type);
				$data['count'] = $counts;
                $query = json_decode($query);
				
				//print_r($query);
				$curr = '&#8358;';
				$items = '	
					<div class="nk-tb-item nk-tb-head">
						<div class="nk-tb-col"><span>'.translate_phrase('Transaction Code').'</span></div>
						<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Account').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Payment Type').'</span></div>
						<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Amount').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Status').'</span></div>
						<div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Date').'</span></div>
					</div><!-- .nk-tb-item -->
						';
						$cashback = 0;
						$transport = 0;

				if ($query->status == true) {
					if (!empty($query->data)) {
						foreach($query->data as $q) {
							$id = $q->id;
							$user_id = $q->user_id;
							$payment_type = $q->payment_type;
							$payment_method = $q->payment_method;
							$code = $q->code;
							$merchant_id = $q->merchant_id;
							$status = $q->status;
							$amount = number_format((float)$q->amount, 2);
							$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));

							$cash = $this->Crud->read_field_like('reg_date', $q->reg_date, 'wallet', 'remark', 'cashback', 'amount');
							if(!empty($cash)){
								$cashback += (float)$cash;
								$cash = '<span class="text-danger fw-bold">N'.$cash.'</span>';
							}
							// user 
							$user = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
							$user_role_id = $this->Crud->read_field('id', $user_id, 'user', 'role_id');
							$user_role = strtoupper($this->Crud->read_field('id', $user_role_id, 'access_role', 'name'));
							$user_image_id = $this->Crud->read_field('id', $user_id, 'user', 'img_id');
							$user_image = $this->Crud->image($user_image_id, 'big');

							// merchant 
							$merchant = $this->Crud->read_field('id', $merchant_id, 'user', 'fullname');
							$merchant_role_id = $this->Crud->read_field('id', $merchant_id, 'user', 'role_id');
							$merchant_role = strtoupper($this->Crud->read_field('id', $merchant_role_id, 'access_role', 'name'));
							$merchant_image_id = $this->Crud->read_field('id', $merchant_id, 'user', 'img_id');
							$merchant_image = $this->Crud->image($merchant_image_id, 'big');

							$mer = '';
							if(!empty($merchant_id)){
								$mer = '<span class="text-danger">'.$merchant.'</span>';
							}
							
							$act = $mer.'<br><span class="text-primary">&rarr;'.$user.'</span>';
							if($payment_type == 'transport' || $payment_type == 'transfer' ){
								if($q->reg_date >= '2023-07-15'){
									$mer = '';
								
									if(!empty($merchant_id)){
										$mer = '<br><span class="text-primary">&rarr;'.$merchant.'</span>';
									}
									$act = '<span class="text-danger">'.$user.'</span>'.$mer;

								}
							}


							// currency
							$curr = '&#8358;';
							$transport += (float)$q->amount;
							// color
							$color = 'success';
							if($payment_type == 'debit') { $color = 'danger'; }

							$item .= '
								<div class="nk-tb-item">
									<div class="nk-tb-col">
										<small class="text-muted d-md-none d-sm-block">'.strtoupper($reg_date).'</small><br>
										<span class="fw-bold text-success pop">
											<a href="javascript:;" class="text-success pop" pageTitle="'.translate_phrase('View').'" pageName="'.site_url('wallets/transaction/view/'.$id).'" pageSize="modal-lg">
												<i class="ni ni-edit"></i> <span class="m-l-3 m-r-10"><b>'.$code.'</b></span>
											</a></span><br>
										<span class="fw-bold text-secondary d-md-none d-sm-block">'.strtoupper($payment_type).'</span>
									</div>
									<div class="nk-tb-col">
										<div class="user-card">
											<div class="user-info">
												'.$act.'
											</div>
										</div>
									</div>
									<div class="nk-tb-col tb-col-md">
										<span class="fw-bold text-secondary">'.strtoupper($payment_type).'</span>
									</div>
									<div class="nk-tb-col">
										<span class="text-dark">'.$curr.$amount.'</span><br>
										'.$cash.'
										<span class="badge badge-dot text-success d-md-none d-sm-block">Success</span>
									</div>
									<div class="nk-tb-col tb-col-md">
										<span class="badge badge-dot text-success">Success</span><br>
									</div>
									<div class="nk-tb-col tb-col-md">
										<span>'.$reg_date.'</span>
									</div>
								</div>
								
							';
						}
					}
				}
			}
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-tranx" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Transaction Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
				if($offset >= 25){
					$resp['item'] = $item;
				
				}
			}
			$more_record = $counts - ($offset + $rec_limit);
			$resp['left'] = $more_record;
			$resp['total_cashback'] = 'N'.number_format($cashback,2);
			$resp['total_transport'] = 'N'.number_format($transport,2);
			
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

		if($param1 == 'manage' || $param1 == 'view') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Cashback').'  | '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	public function list($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('fls_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 
		$log_id = $this->session->get('td_id');
		if($this->Crud->check2('id', $log_id, 'pin', 0, 'user')> 0)return redirect()->to(site_url('auth/security'));
       
        $mod = 'wallets/list';

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
       
		$table = 'transaction';
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
                    //echo var_dump($edit);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$del_id =  $this->request->getVar('d_pump_id');
                        $code = $this->Crud->read_field('id', $del_id, 'pump', 'name');
						$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
						$action = $by.' deleted pump ('.$code.')';
							
                        if($this->Crud->deletes('id', $del_id, $table) > 0) {

							///// store activities
							$this->Crud->activity('pump', $del_id, $action);
							
							echo $this->Crud->msg('success', 'Record Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						die;	
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
								$data['e_product'] = $e->product;
								$data['e_price'] = $e->price;
								
							}
						}
					}
				}

				
				if($this->request->getMethod() == 'post'){
					$pump_id =  $this->request->getVar('pump_id');
					$name =  $this->request->getVar('name');
					$product =  $this->request->getVar('product');
					$price =  $this->request->getVar('price');
					
					// do create or update
					if($pump_id) {
						$upd_data['name'] = $name;
						$upd_data['product'] = $product;
						$upd_data['price'] = $price;
						
						$upd_rec = $this->Crud->updates('id', $pump_id, $table, $upd_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
							$code = $this->Crud->read_field('id', $pump_id, 'pump', 'name');
							$action = $by.' updated Pump ('.$code.') Record';
							$this->Crud->activity('pump', $pump_id, $action);

							echo $this->Crud->msg('success', 'Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
                        die;
					} else {
						if($this->Crud->check2('name', $name, 'user_id', $log_id, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_data['name'] = $name;
							$ins_data['product'] = $product;
							$ins_data['price'] = $price;
							$ins_data['user_id'] = $log_id;
							$ins_data['reg_date'] = date(fdate);
							
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', 'Record Created');
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
								$code = $this->Crud->read_field('id', $ins_rec, 'pump', 'name');
								$action = $by.' created Pump ('.$code.') Record';
								$this->Crud->activity('pump', $ins_rec, $action);
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', 'Please try later');	
							}	
						}

					}die;
						
				}
			}
		}


		if($param1 == 'view') {
			if($param2) {
				$edit = $this->Crud->read_single('id', $param2, $table);
				if(!empty($edit)) {
					foreach($edit as $e) {
						$data['e_id'] = $e->id;
						$data['e_code'] = $e->code;
						$data['e_state_id'] = $e->state_id;
						$data['e_country_id'] = $e->country_id;
						$data['e_ref'] = $e->ref;
						$data['e_amount'] = $e->amount;
						$data['e_reg_date'] = date('d F, Y H:iA', strtotime($e->reg_date));
						$data['e_user_id'] = $e->user_id;
						$data['e_merchant_id'] = $e->merchant_id;
						$data['e_payment_type'] = $e->payment_type;
						$data['e_payment_method'] = $e->payment_method;
						$data['e_transaction_code'] = $e->transaction_code;
						$data['e_remark'] = $e->remark;
						$data['e_state'] = $this->Crud->read_field('id', $e->state_id, 'state', 'name');

						// currency
						$curr = '&#8358;';
						$data['e_curr'] = $curr;

						$customer = $this->Crud->read_field('id', $e->user_id, 'user', 'fullname');
						$customer_phone = $this->Crud->read_field('id', $e->user_id, 'user', 'phone');
						$data['e_customer'] = $customer;
						$data['e_customer_phone'] = $customer_phone;
						$merchant = $this->Crud->read_field('id', $e->merchant_id, 'user', 'username');
						$merchant_phone = $this->Crud->read_field('id', $e->merchant_id, 'user', 'phone');
						$data['e_merchant_phone'] = $merchant_phone;
						$data['e_merchant'] = $merchant;
						
					}
				}
			}
		}


        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 25;
			$item = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			if(!empty($this->request->getVar('type'))) { $type = $this->request->getVar('type'); } else { $type = ''; }
			if(!empty($this->request->getVar('role'))) { $roles = $this->request->getVar('role'); } else { $roles = ''; }
			if(!empty($this->request->getVar('transact'))) { $transact = $this->request->getVar('transact'); } else { $transact = ''; }
			if(!empty($this->request->getVar('start_date'))) { $start_date = $this->request->getVar('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getVar('end_date'))) { $end_date = $this->request->getVar('end_date'); } else { $end_date = ''; }
			$search = $this->request->getVar('search');

			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				if($roles == 'on')$log_id = 0;
				$all_rec = $this->Crud->api('get', 'wallet/get', '&log_id='.$log_id.'&start_date='.$start_date.'&end_date='.$end_date.'&search='.$search.'&type='.$type.'&type='.$type.'&transact='.$transact);
                $all_rec = json_decode($all_rec);
				if(!empty($all_rec->data)) { $counts = count($all_rec->data); } else { $counts = 0; }

				$query = $this->Crud->api('get', 'wallet/get', 'limit='.$limit.'&offset='.$offset.'&log_id='.$log_id.'&start_date='.$start_date.'&end_date='.$end_date.'&search='.$search.'&type='.$type.'&transact='.$transact);
				$data['count'] = $counts;
                $query = json_decode($query);
				
				//print_r($query);
				$curr = '&#8358;';
				$items = '	
					<div class="nk-tb-item nk-tb-head">
						<div class="nk-tb-col"><span>'.translate_phrase('Wallet').'</span></div>
						<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Account').'</span></div>
						<div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Amount').'</span></div>
					</div><!-- .nk-tb-item -->
						';

				$debit = 0;
				$credit = 0;
				if ($query->status == true) {
					if (!empty($query->data)) {
						foreach($query->data as $q) {
							$id = $q->id;
							$user_id = $q->user_id;
							$type = $q->type;
							$itema = $q->item;
							$remark = $q->remark;
							$amount = number_format((float)$q->amount, 2);
							$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));


							// user 
							$user = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
							$user_role_id = $this->Crud->read_field('id', $user_id, 'user', 'role_id');
							$user_role = strtoupper($this->Crud->read_field('id', $user_role_id, 'access_role', 'name'));
							$user_image_id = $this->Crud->read_field('id', $user_id, 'user', 'img_id');
							$user_image = $this->Crud->image($user_image_id, 'big');
							
							// currency
							$curr = '&#8358;';

							// color
							/* The above code is checking the value of the variable . If  is equal to 'debit', it
							sets the value of  to 'danger' and adds the amount from the variable ->amount to the
							variable . If  is equal to 'credit', it sets the value of  to 'success' and
							adds the amount from the variable ->amount to the variable . */
							if($type == 'debit') { $color = 'danger'; $debit += (float)$q->amount;}

							if($type == 'credit') { $color = 'success'; $credit += (float)$q->amount;}

							$item .= '
								<div class="nk-tb-item">
									<div class="nk-tb-col">
										<small class="text-muted">'.($reg_date).'</small><br>
										<span class="fw-bold text-dark">'.$remark.'</span><br>
										<span class="fw-bold text-info">'.strtoupper($itema).'</span>
									</div>
									<div class="nk-tb-col">
										<div class="user-card">
											<div class="user-avatar ">
												<img alt="" src="' . site_url($user_image) . '" height="40px"/>
											</div>
											<div class="user-info">
												<span class="tb-lead">' . ucwords($user) . '</span>
												<span>' . $user_role . '</span>
											</div>
										</div>
									</div>
									<div class="nk-tb-col">
										<span class="text-'.$color.'">'.strtoupper($type).'</span><br>
										<span class="fw-bold">'.$curr.$amount.'</span>
									</div>
								</div>
								
							';
						}
					}
				}
			}
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-tranx" style="font-size:150px;"></i><br/><br/>'.translate_phrase('No Wallets History Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
				if($offset >= 25){
					$resp['item'] = $item;
				
				}
			}
			$bal = $credit - $debit;
			if($bal < 0)$bal = 0;
			$resp['bal'] = $bal;
			$resp['credit'] = $credit;
			$resp['debit'] = $debit;
			
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

		if($param1 == 'manage' || $param1 == 'view') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Wallet').'  | '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

    ////// ACCOUNT STATEMENT
	public function account($id=0) {
	    $email = $this->request->getVar('email');
	    if(!empty($email)) { $id = $this->Crud->read_field('email', $email, 'user', 'id'); }
	    
	    if(empty($id)) { redirect(base_url('wallet')); }
	    
	    $items = '';
	    $total_credit = 0;
	    $total_debit = 0;
	    
	    $name = $this->Crud->read_field('id', $id, 'user', 'fullname');
	    $query = $this->Crud->read_single_order('user_id', $id, 'wallet', 'id', 'asc');
	    if(!empty($query)) {
	        foreach($query as $q) {
	            $date = date('M d, Y h:iA', strtotime($q->reg_date));
	            
	            $credit = '-';
	            $debit = '-';
	            if($q->type == 'credit') {
	                $credit = '&#8358;'.number_format($q->amount, 2);
	                $total_credit += $q->amount;
	            } else {
	                $debit = '&#8358;'.number_format($q->amount, 2);
	                $total_debit += $q->amount;
	            }
	            
	            $items .= '
	                <tr>
	                    <td>'.$date.'</td>
	                    <td align="right">'.$credit.'</td>
	                    <td align="right">'.$debit.'</td>
	                </tr>
	            ';
	        }
	    }
	    
	    echo '
	        <h3>'.$name.' '.translate_phrase('Wallet Account Statement').'
	            <div style="font-size:small; color:#666;">as at '.date('M d, Y h:iA').'</div>
	        </h3>
	        <table class="table table-striped">
	            <thead>
	                <tr>
	                    <td><b>'.translate_phrase('DATE').'</b></td>
	                    <td width="200px" align="right"><b>CR</b></td>
	                    <td width="200px" align="right"><b>DR</b></td>
	                </tr>
	            </thead>
	            <tbody>'.$items.'</tbody>
	        </table>
	        <hr/>
	        <b>'.translate_phrase('TOTAL CREDIT').':</b> &#8358;'.number_format($total_credit, 2).'<br/>
	        <b>'.translate_phrase('TOTAL DEBIT').':</b> &#8358;'.number_format($total_debit, 2).'
	    ';
	}
	
	/////// CHECK ACCOUNT
	public function check_account() {
	    $status = false;
	    
	    $email = $this->request->getVar('email');
	    
		$id = $this->Crud->read_field('phone', $email, 'user', 'id');
		$fullname = $this->Crud->read_field('phone', $email, 'user', 'fullname');
			
	    
	    if(!empty($id)) {
	        $status = true;
	        $fullname = '<b class="text-success"><i class="anticon anticon-user"></i> '.strtoupper($fullname).'</b><hr/>';
	    } else {
	        $fullname = '<hr/>';
			$id = 0;
	    }
	    
	    echo json_encode(array('status'=>$status, 'id'=>$id, 'fullname'=>$fullname));
	    die;
	}

}