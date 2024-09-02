<?php

namespace App\Controllers;

class Payments extends BaseController {

    
    public function tax($param1='', $param2='', $param3='') {
        // check login
        $log_id = $this->session->get('td_id');
        
       if(empty($log_id)) return redirect()->to(site_url('auth'));
       $log_id = $this->session->get('td_id');
    //    if($this->Crud->check2('id', $log_id, 'pin', 0, 'user')> 0)return redirect()->to(site_url('auth/security'));
       if($this->Crud->check2('id', $log_id, 'state_id', 0, 'user')> 0)return redirect()->to(site_url('auth/profile'));
       if($this->Crud->check2('id', $log_id, 'country_id', 0, 'user')> 0)return redirect()->to(site_url('auth/profile'));
        $mod = 'payments/tax';

        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $username = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
        $email = $this->Crud->read_field('id', $log_id, 'user', 'email');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $role_c = $this->Crud->module($role_id, $mod, 'create');
        $role_r = $this->Crud->module($role_id, $mod, 'read');
        $role_u = $this->Crud->module($role_id, $mod, 'update');
        $role_d = $this->Crud->module($role_id, $mod, 'delete');
        
        
        $data['log_id'] = $log_id;
        $data['param1'] = $param1;
        $data['param2'] = $param2;
        $data['param3'] = $param3;
        $data['role'] = $role;
        $merchant = '';
        $form_link = site_url('payments/tax/');
		if($param1){$form_link .= $param1.'/';}
		if($param2){$form_link .= $param2.'/';}
		if($param3){$form_link .= $param3.'/';}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = rtrim($form_link, '/');
		
        $table = 'payment';
        // pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'sms') {
				if($param3){
                    if($this->request->getMethod() == 'post'){
                        $user_id = $this->request->getPost('user_id');
                        $message = $this->request->getPost('message');
                        $chanel = $this->request->getPost('chanel');
                        
                        if(empty($message)){
                            echo $this->Crud->msg('danger', 'Message Cannot be Empty');
                            die;
                        }

                        $email_status = false;
                        $phone_status = false;

                        if($chanel == 'both'){
                            $email_status = true;
                            $phone_status = true;
                        }
                        if($chanel == 'sms'){
                            $phone_status = true;
                        }
                        if($chanel == 'email'){
                            $email_status = true;
                        }
                        $reload = 0;
                        $email = $this->Crud->read_field('id', $user_id, 'user', 'email');
                        $phone = $this->Crud->read_field('id', $user_id, 'user', 'phone');
                        $email_resp = '0';
                        if(!empty($email) && $email_status == true){
                            $email_resp = $this->Crud->send_email($email, 'Notification', $message);
                            if($email_resp){
                                echo $this->Crud->msg('success', 'Email Sent Successfully');
                                $reload =1;
                            } else{
                                echo $this->Crud->msg('danger', 'Error Sending Email');
                                
                            }
                        }

                        $api_key = $this->Crud->read_field('name', 'termil_api', 'setting', 'value'); // pick from DB
						
                        if(!empty($phone) && $phone_status == true){
                            $phone = '234'.substr($phone,1);
                            $datass['to'] = $phone;
                            $datass['from'] = 'N-Alert';
                            $datass['sms'] = $message;
                            $datass['api_key'] = $api_key;
                            $datass['type'] = 'plain';
                            $datass['channel'] = 'dnd';
                            $phone_resp = $this->Crud->termii('post', 'sms/send', $datass);
                            $resps = json_decode($phone_resp);
                            if($resps->message == 'Successfully Sent'){
                                echo $this->Crud->msg('success', 'SMS Sent Successfully');
                                $reload = 1;
                            } else{
                                echo $this->Crud->msg('danger', 'Error Sending SMS');
                            }
                        }
                        
                       if($reload){
						    echo '<script>location.reload(false);</script>';
                       }

                        die;
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
			$items = '	
                <div class="nk-tb-item nk-tb-head">
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Tax Account').'</span></div>
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Amount').'</span></div>
                    <div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Status').'</span></div>
                    <div class="nk-tb-col tb-col-"><span class="sub-text">'.translate_phrase('Payment Due Date').'</span></div>
                </div><!-- .nk-tb-item -->
                    
                
            ';
            $item = '';
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 2;
			$item = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			if(!empty($this->request->getVar('status'))) { $status = $this->request->getVar('status'); } else { $status = ''; }
			if(!empty($this->request->getVar('lga'))) { $lga = $this->request->getVar('lga'); } else { $lga = ''; }
			if(!empty($this->request->getVar('start_date'))) { $start_date = $this->request->getVar('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getVar('end_date'))) { $end_date = $this->request->getVar('end_date'); } else { $end_date = ''; }
			$search = $this->request->getVar('search');

			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$query = $this->Crud->filter_transaction($limit, $offset, $log_id, 'tax', $search, $start_date, $end_date);
				$all_rec = $this->Crud->filter_transaction('', '', $log_id, 'tax', $search, $start_date, $end_date);
				if(!empty($all_rec)) { $count = count($all_rec); } else { $count = 0; }
                if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$curr = '&#8358;';
                
				if(!empty($query)) {
					foreach($query as $q) {
                        $id = $q->id;
                        $user_id = $q->user_id;

                        $tax_id = $this->Crud->read_field('user_id', $user_id, 'virtual_account', 'acc_no');
                        $payment_type = $q->payment_type.' Payments';
                        $payment_method = $q->payment_method;
                        $status = $q->status;
                        $ref = $q->ref;
                        $remark = $q->remark;
                        $amount = number_format((float)$q->amount, 2);
                        $balance = curr.number_format((float)$q->balance, 2);
                        $reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
                        $payment_date = date('d M, Y', strtotime($q->payment_date));

                        $st = '<span class="text-danger">'.ucwords($status).'</span>';
                        if($status != 'pending'){
                            $st = '<span class="text-success">'.ucwords($status).'</span>';
                        }
                        $rem = (float)$q->amount - (float)$q->balance;


                        // user 
                        $user = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
                        $user_image_id = $this->Crud->read_field('id', $user_id, 'user', 'img_id');
                        $user_image = $this->Crud->image($user_image_id, 'big');
                        $btn = '
                            <br><a href="javascript:;" class="btn btn-info pop mt-2" pageTitle="Tax Payment Statement" pageName="'.site_url('payments/tax/manage/view/'.$id).'" pageSize="modal-lg">
                                <i class="ni ni-eye"></i> <span class=""><b>View</b></span>
                            </a>';
                        $bal ='';$paid = '';

                        if($status == 'pending'){
                            $bal = '<br><span class="text-danger fw-bold">Bal: '.$balance.'</span>';
                            if($rem > 0){
                                $paid = '<br><span class="text-success fw-bold">PAID: '.curr.number_format((float)$rem, 2).'</span>';
                                $st = '<span class="text-warning">'.ucwords('Part Payment').'</span>';
                            } else {
                                $btn = '';
                            }
                        }
                        // currency

                        $item .= '
                            <div class="nk-tb-item">
                                <div class="nk-tb-col">
                                    <span class="fw-bold text-success">'.$tax_id.'</span><br>
                                    <span class="fw-bold text-secondary">'.translate_phrase(strtoupper($user)).'</span>
                                </div>
                                <div class="nk-tb-col">
                                    <span class="text-info">'.$curr.$amount.'</span>
                                    <div class="d-sm-none">
                                    '.$st.'
                                    '.$bal.$paid.'
                                    </div>
                                </div>
                                <div class="nk-tb-col tb-col-md">
                                    '.$st.'
                                    '.$bal.$paid.'
                                </div>
                                <div class="nk-tb-col tb-col-">
                                    <span class="text-dark">'.$payment_date.'</span>
                                    '.$btn.'
                                </div>
                            </div>
                            
                        ';
                    }
				}
			}

			$total = 0;
			
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-tranx" style="font-size:150px;"></i><br/><br/>No Tax Payment Returned
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
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
		}

        if($param1 == 'get_territory'){
            $query = $this->Crud->read_order('territory', 'name', 'asc');
            if(!empty($param2) && $param2 != 'all'){
                $query = $this->Crud->read_single_order('lga_id', $param2, 'territory', 'name', 'asc');
            }

            $sel = '<option value="all">Select</option>';
            if(!empty($query)){
                foreach($query as $q){
                    $sel .= '<option value="'.$q->id.'">'.ucwords($q->name).'</option>';
                }
            }
            echo $sel;
            die;
        }

        if($param1 == 'get_master'){
            $master = $this->Crud->read_field('name', 'Tax Master', 'access_role', 'id');
            $query = $this->Crud->read_single_order('role_id', $master, 'user', 'fullname', 'asc');
            if(!empty($param2) && $param2 != 'all'){
                $query = $this->Crud->read2_order('role_id', $master, 'territory', $param2, 'user', 'fullname', 'asc');
            }

            $sel = '<option value="all">Select</option>';
            if(!empty($query)){
                foreach($query as $q){
                    $sel .= '<option value="'.$q->id.'">'.ucwords($q->fullname).'</option>';
                }
            }
            echo $sel;
            die;
        }

        
        if($param1 == 'get_operative'){
            $master = $this->Crud->read_field('name', 'Field Operative', 'access_role', 'id');
            $query = $this->Crud->read_single_order('role_id', $master, 'user', 'fullname', 'asc');
            if(!empty($param2) && $param2 != 'all'){
                $query = $this->Crud->read2_order('role_id', $master, 'master_id', $param2, 'user', 'fullname', 'asc');
            }

            $sel = '<option value="all">Select</option>';
            if(!empty($query)){
                foreach($query as $q){
                    $sel .= '<option value="'.$q->id.'">'.ucwords($q->fullname).'</option>';
                }
            }
            echo $sel;
            die;
        }


        if($param1 == 'admin_load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 50;
			$items = '
                <tr class="nk-tb-item dnone">
                    <td class="nk-tb-col">
                        <div class="row">
                            <div class="col-sm-4">
                                <span class="sub-text">'.translate_phrase('Tax Account').'</span>
                            </div>
                            <div class="col-sm-3">
                                 <span class="sub-text">'.translate_phrase('Account Type').'</span>
                            </div>
                            <div class="col-sm-3">
                                 <span class="sub-text">'.translate_phrase('Payment').'</span>
                            </div>
                            <div class="col-sm-2">
                               <span class="sub-text">'.translate_phrase('').'</span>
                            </div>
                        </div>
                       
                    </td>
                </tr>	
                
            ';
            $item = '';
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 50;
			$item = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}

            $date_type = $this->request->getPost('date_type');
            if(!empty($this->request->getPost('start_date'))) { $start_dates = $this->request->getPost('start_date'); } else { $start_dates = ''; }
            if(!empty($this->request->getPost('end_date'))) { $end_dates = $this->request->getPost('end_date'); } else { $end_dates = ''; }
            if($date_type == 'Today'){
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d');
            } elseif($date_type == 'Yesterday'){
                $start_date = date('Y-m-d', strtotime( '-1 days' ));
                $end_date = date('Y-m-d', strtotime( '-1 days' ));
            } elseif($date_type == 'Last_Week'){
                $start_date = date('Y-m-d', strtotime( '-7 days' ));
                $end_date = date('Y-m-d');
            } elseif($date_type == 'Last_Month'){
                $start_date = date('Y-m-d', strtotime( '-30 days' ));
                $end_date = date('Y-m-d');
            } elseif($date_type == 'Date_Range'){
                $start_date = $start_dates;
                $end_date = $end_dates;
            } elseif($date_type == 'This_Year'){
                $start_date = date('Y-01-01');
                $end_date = date('Y-m-d');
            } elseif($date_type == 'All'){
                $start_date = date('2023-01-01');
                $end_date = date('Y-m-d');
            } else {
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-d');
            }
            
			
			if(!empty($this->request->getVar('status'))) { $status = $this->request->getVar('status'); } else { $status = ''; }
			if(!empty($this->request->getVar('role_id'))) { $role_ids = $this->request->getVar('role_id'); } else { $role_ids = ''; }
			if(!empty($this->request->getVar('state_id'))) { $state_ids = $this->request->getVar('state_id'); } else { $state_ids = ''; }
			if(!empty($this->request->getVar('search'))) { $search = $this->request->getVar('search'); } else { $search = ''; }
			if(!empty($this->request->getVar('territory'))) { $territory = $this->request->getVar('territory'); } else { $territory = ''; }
            
            $this->session->set('tde_role_id', $role_ids);
            $this->session->set('tde_state_id', $state_ids);
            $this->session->set('tde_search', $search);
            $this->session->set('tde_territory', $territory);
            $this->session->set('tde_start_date', $start_date);
            $this->session->set('tde_end_date', $end_date);
            
			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$all_rec = $this->Crud->filter_invoice('', '', $log_id, $search, $role_ids, $state_ids, $territory, $start_date, $end_date);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_invoice($limit, $offset, $log_id, $search, $role_ids, $state_ids, $territory, $start_date, $end_date);
				$data['count'] = $counts;
				$paid = 0;
                if(!empty($query)){
                    foreach($query as $u){
                        $id = $u->id;
                        $role_id = $u->role_id;
                        $roles = $this->Crud->read_field('id', $u->role_id, 'access_role', 'name');
                        $trade = $this->Crud->read_field('id', $u->trade, 'trade', 'name');
                        $trade_amount = $this->Crud->read_field('id', $u->trade, 'trade', 'medium');
                        $duration = '<span class="text-success">'.ucwords($u->duration).'</span>';
                        if(empty($u->duration))$duration = '<span class="text-danger">Not Selected</span>';
                        $tax_id = $this->Crud->read_field('user_id', $id, 'virtual_account', 'acc_no');
                        $reg_date = date('d M Y h:iA', strtotime($u->reg_date));
                        $user = $u->fullname;

                        $price = $this->Crud->trade_duration($trade_amount, $u->duration);
                        $btn = '
                            <a href="javascript:;" class="btn btn-primary mt-1" onclick="view_pay('.$id.')">
                                <i class="ni ni-eye" id="eye_'.$id.'"></i> 
                            </a>';

                        $bal ='';
                        $paid = 0;
                        $paids = $this->Crud->read_single('user_id', $id, 'history');
                        if(!empty($paids)){
                            foreach($paids as $p){
                                $paid += (float)$p->amount;
                            }
                        }
                        $unpaid = (float)$trade_amount - (float)$paid;
                        $item .= '
                                
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <span class="fw-bold text-success">'.$tax_id.'</span><br>
                                            <span class="fw-bold text-secondary">'.(strtoupper($user)).'</span><br>
                                            <span class="text-dark">
                                                '.$roles.'
                                            </span>
                                        </div>
                                        <div class="col-sm-3">
                                            <span class="text-dark">
                                                <bTrade>Trade Line:</b>  <span class="text-info">'.ucwords($trade).' </span><br>
                                               <b> Annual Remmittance:</b> '.curr.number_format((float)$trade_amount,2).'
                                            </span>
                                        </div>
                                        <div class="col-sm-3">
                                            <span class="text-dark">
                                                <span class="text-success"><b>Total Paid: </b>'.curr.number_format((float)$paid,2).'</span><br>
                                                <span class="text-danger"><b>Total Unpaid: </b> '.curr.number_format((float)$unpaid,2).'</span>
                                            </span>
                                        </div>
                                        <div class="col-sm-2 text-end">
                                            '.$btn.'
                                        </div>
                                    </div>
                                    <div class="row mt-2"  id="pays_'.$id.'" style="display:none;">
                                        <div class="col-sm-3 mb-3">
                                            <span class="text-dark">
                                                Trade Line:  <span class="text-info">'.ucwords($trade).' </span><br>
                                                Trade Amount: '.curr.number_format((float)$trade_amount,2).'<br>
                                                Duration: '.ucwords($duration).'<br>
                                                Duration Price: '.curr.number_format((float)$price).'
                                            </span>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <span class="text-dark">
                                                Annual Remmittance: '.curr.number_format((float)$trade_amount,2).'<br>
                                                Total Paid: '.curr.number_format((float)$paid,2).'<br>
                                                Total Unpaid:  '.curr.number_format((float)$unpaid,2).'
                                            </span>
                                        </div>
                                        <div class="col-sm-5 mb-3">
                                            <span class="btn-grou">
                                                <a href="javascript:;" class="btn btn-outline-primary mb-0 pop m-2" pageName="'.site_url('payments/tax/manage/invoice/'.$id).'" pageSize="modal-xl" pageTitle="Tax Invoice">Invoice</a>
                                                <a href="javascript:;" class="btn btn-outline-success mb-0 pop m-2" pageName="'.site_url('payments/tax/manage/history/'.$id).'" pageSize="modal-xl" pageTitle="Tax Payment History">Payment History</a><a href="javascript:;" class="btn btn-outline-info mb-0 pop m-2" pageName="'.site_url('payments/tax/manage/sms/'.$id).'" pageSize="modal-xl" pageTitle="Send SMS/Email">Send Notification</a>
                                            </span>
                                        </div>
                                    </div>
                                    
                                </td
                            </tr>	
                            
                        ';
                    }
                }
			}

			$total = 0;
			
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-tranx" style="font-size:150px;"></i><br/><br/>No Tax Invoices Returned
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
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

        //Pagination Tableu
        if($param1 == 'admin_loads') {
			
			$count = 0;
			$items = '
                <tr class="nk-tb-item dnone">
                    <td class="nk-tb-col">
                        <div class="row">
                            <div class="col-sm-4">
                                <span class="sub-text">'.translate_phrase('Tax Account').'</span>
                            </div>
                            <div class="col-sm-3">
                                 <span class="sub-text">'.translate_phrase('Account Type').'</span>
                            </div>
                            <div class="col-sm-3">
                                 <span class="sub-text">'.translate_phrase('Payment').'</span>
                            </div>
                            <div class="col-sm-2">
                               <span class="sub-text">'.translate_phrase('').'</span>
                            </div>
                        </div>
                       
                    </td>
                </tr>	
                
            ';
            $item = '';
			$page = isset($_GET['page']) ? $_GET['page'] : 1;
            $itemsPerPage = 50; // Adjust this based on your application's requirements
            $offset = ($page - 1) * $itemsPerPage;

              // Calculate total pages
          
			$count = 0;
			$rec_limit = 50;
			$item = '';

			$limit = $itemsPerPage;
			if($offset == '') {$offset = 0;}

            $date_type = $this->request->getVar('date_type');
            if(!empty($this->request->getVar('start_date'))) { $start_dates = $this->request->getVar('start_date'); } else { $start_dates = ''; }
            if(!empty($this->request->getVar('end_date'))) { $end_dates = $this->request->getVar('end_date'); } else { $end_dates = ''; }
            if($date_type == 'Today'){
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d');
            } elseif($date_type == 'Yesterday'){
                $start_date = date('Y-m-d', strtotime( '-1 days' ));
                $end_date = date('Y-m-d');
            } elseif($date_type == 'Last_Week'){
                $start_date = date('Y-m-d', strtotime( '-7 days' ));
                $end_date = date('Y-m-d');
            } elseif($date_type == 'Last_Month'){
                $start_date = date('Y-m-d', strtotime( '-30 days' ));
                $end_date = date('Y-m-d');
            } elseif($date_type == 'Date_Range'){
                $start_date = $start_dates;
                $end_date = $end_dates;
            } elseif($date_type == 'This_Year'){
                $start_date = date('Y-01-01');
                $end_date = date('Y-m-d');
            } elseif($date_type == 'All'){
                $start_date = date('2023-01-01');
                $end_date = date('Y-m-d');
            } else {
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-d');
            }
            
            // echo $start_date.' '.$end_date;
			
			if(!empty($this->request->getVar('status'))) { $status = $this->request->getVar('status'); } else { $status = ''; }
			if(!empty($this->request->getVar('role_id'))) { $role_ids = $this->request->getVar('role_id'); } else { $role_ids = ''; }
			if(!empty($this->request->getVar('state_id'))) { $state_ids = $this->request->getVar('state_id'); } else { $state_ids = ''; }
			if(!empty($this->request->getVar('search'))) { $search = $this->request->getVar('search'); } else { $search = ''; }
			if(!empty($this->request->getVar('territory'))) { $territory = $this->request->getVar('territory'); } else { $territory = 'all'; }
            if(!empty($this->request->getVar('master_id'))) { $master_id = $this->request->getVar('master_id'); } else { $master_id = 'all'; }
            if(!empty($this->request->getVar('operative_id'))) { $operative_id = $this->request->getVar('operative_id'); } else { $operative_id = 'all'; }
            
            $this->session->set('tde_role_id', $role_ids);
            $this->session->set('tde_state_id', $state_ids);
            $this->session->set('tde_search', $search);
            $this->session->set('tde_territory', $territory);
            $this->session->set('tde_operative_id', $operative_id);
            $this->session->set('tde_master_id', $master_id);
            $this->session->set('tde_start_date', $start_date);
            $this->session->set('tde_end_date', $end_date);
            
			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$all_rec = $this->Crud->filter_invoice('', '', $log_id, $search, $role_ids, $state_ids, $territory, $master_id, $operative_id, $start_date, $end_date);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_invoice($limit, $offset, $log_id, $search, $role_ids, $state_ids, $territory, $master_id, $operative_id, $start_date, $end_date);
				$data['count'] = $counts;
				$paid = 0;
                if(!empty($query)){
                    foreach($query as $u){
                        $id = $u->id;
                        $role_id = $u->role_id;
                        $roles = $this->Crud->read_field('id', $u->role_id, 'access_role', 'name');
                        $trade = $this->Crud->read_field('id', $u->trade, 'trade', 'name');
                        $trade_amount = $this->Crud->read_field('id', $u->trade, 'trade', 'medium');
                        $duration = '<span class="text-success">'.ucwords($u->duration).'</span>';
                        if(empty($u->duration))$duration = '<span class="text-danger">Not Selected</span>';
                        $tax_id = $this->Crud->read_field('user_id', $id, 'virtual_account', 'acc_no');
                        $reg_date = date('d M Y h:iA', strtotime($u->reg_date));
                        $user = $u->fullname;

                        $price = $this->Crud->trade_duration($trade_amount, $u->duration);
                        $btn = '
                            <a href="javascript:;" class="btn btn-primary mt-1" onclick="view_pay('.$id.')">
                                <i class="ni ni-eye" id="eye_'.$id.'"></i> 
                            </a>';

                        $bal ='';
                        $paid = 0;
                        $paids = $this->Crud->read_single('user_id', $id, 'history');
                        if(!empty($paids)){
                            foreach($paids as $p){
                                $paid += (float)$p->amount;
                            }
                        }
                        $unpaid = (float)$trade_amount - (float)$paid;
                        $item .= '
                                
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <span class="fw-bold text-success">'.$tax_id.'</span><br>
                                            <span class="fw-bold text-secondary">'.(strtoupper($user)).'</span><br>
                                            <span class="text-dark">
                                                '.$roles.'
                                            </span>
                                        </div>
                                        <div class="col-sm-3">
                                            <span class="text-dark">
                                                <bTrade>Trade Line:</b>  <span class="text-info">'.ucwords($trade).' </span><br>
                                               <b> Annual Remmittance:</b> '.curr.number_format((float)$trade_amount,2).'
                                            </span>
                                        </div>
                                        <div class="col-sm-3">
                                            <span class="text-dark">
                                                <span class="text-success"><b>Total Paid: </b>'.curr.number_format((float)$paid,2).'</span><br>
                                                <span class="text-danger"><b>Total Unpaid: </b> '.curr.number_format((float)$unpaid,2).'</span>
                                            </span>
                                        </div>
                                        <div class="col-sm-2 text-end">
                                            '.$btn.'
                                        </div>
                                    </div>
                                    <div class="row mt-2"  id="pays_'.$id.'" style="display:none;">
                                        <div class="col-sm-3 mb-3">
                                            <span class="text-dark">
                                                Trade Line:  <span class="text-info">'.ucwords($trade).' </span><br>
                                                Trade Amount: '.curr.number_format((float)$trade_amount,2).'<br>
                                                Duration: '.ucwords($duration).'<br>
                                                Duration Price: '.curr.number_format((float)$price).'
                                            </span>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <span class="text-dark">
                                                Annual Remmittance: '.curr.number_format((float)$trade_amount,2).'<br>
                                                Total Paid: '.curr.number_format((float)$paid,2).'<br>
                                                Total Unpaid:  '.curr.number_format((float)$unpaid,2).'
                                            </span>
                                        </div>
                                        <div class="col-sm-5 mb-3">
                                            <span class="btn-grou">
                                                <a href="javascript:;" class="btn btn-outline-primary mb-0 pop m-2" pageName="'.site_url('payments/tax/manage/invoice/'.$id).'" pageSize="modal-xl" pageTitle="Tax Invoice">Invoice</a>
                                                <a href="javascript:;" class="btn btn-outline-success mb-0 pop m-2" pageName="'.site_url('payments/tax/manage/history/'.$id).'" pageSize="modal-xl" pageTitle="Tax Payment History">Payment History</a><a href="javascript:;" class="btn btn-outline-info mb-0 pop m-2" pageName="'.site_url('payments/tax/manage/sms/'.$id).'" pageSize="modal-xl" pageTitle="Send SMS/Email">Send Notification</a>
                                            </span>
                                        </div>
                                    </div>
                                    
                                </td
                            </tr>	
                            
                        ';
                    }
                }
			}

            $totalPages = ceil($counts / $itemsPerPage);

			
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-tranx" style="font-size:150px;"></i><br/><br/>No Tax Invoices Returned
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
			}
            $resp['count'] = $counts;
            $resp['totalPages'] = $totalPages;

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
        
		if($param1 == 'export'){
			//Download main category
            $search = $this->session->get('tde_search');
            $role_ids = $this->session->get('tde_role_id');
            $start_date = $this->session->get('tde_start_date');
            $state_ids = $this->session->get('tde_state_id');
            $territory = $this->session->get('tde_territory');
            $master_id = $this->session->get('tde_master_id');
            $operative_id = $this->session->get('tde_operative_id');
            $end_date = $this->session->get('tde_end_date');
            
			$all_rec = $this->Crud->filter_invoice('', '', '', $search, $role_ids, $state_ids, $territory, $master_id, $operative_id, $start_date, $end_date);
				
			$codes = '';
			if(!empty($all_rec)) {
				$count = 1;
				foreach($all_rec as $co) {
					$item = array();
					
					$item[] = $count;
					$referral = '-';
                    $referral_contact = '-';
                    if($co->referral > 0){
                        $referral = $this->Crud->read_field('id', $co->referral, 'user', 'fullname');
                        $referral_contact = $this->Crud->read_field('id', $co->referral, 'user', 'phone');
                    }
                    $item[] = $referral;
                    $item[] = $referral_contact;

					$i = 0;
					
					$item[] = ucwords($co->fullname);
					$item[] = number_format((float)$this->Crud->read_field('id', $co->trade, 'trade' , 'medium'),2);
					$item[] = ucwords($this->Crud->read_field('id', $co->trade, 'trade' , 'name'));
					$item[] = ucwords($co->duration);

                    $paid = 0;
                    $history = $this->Crud->read_single('user_id', $co->id, 'history');
                    if(!empty($history)){
                        foreach($history as $h){
                            $paid += (float)$h->amount;
                        }
                    }
                    $remit = $this->Crud->read_field('id', $co->trade, 'trade' , 'medium');
                    $unpaid = 0;
                    if(!empty($remit)){
                        $unpaid = (float)$remit - (float)$paid;
                    }
					$item[] = number_format($paid,2);
					$item[] = number_format($unpaid,2);
					$item[] = $co->phone;
					$item[] = $co->address;
					$item[] = $this->Crud->read_field('user_id',$co->id, 'virtual_account', 'acc_no');
					
					$i++;
					$row[] = $item;
					$count += 1;
					
				}
			}

			// now export CSV
			$dfile_name = 'Invoice List';
			$fname = $dfile_name.'.csv';
			header( "Content-Type: text/csv;charset=utf-8" );
			header( "Content-Disposition: attachment;filename=$fname" );
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$output = fopen('php://output', 'w');
		
			// Column Title
			fputcsv($output, array('S/N', 'F.O NAMES', 'F.O CONTACT NO', 'NAME OF TAX PAYER UNDER F.O', 'ANNUAL REMITANCE OF TAX PAYER', 'TYPE OF BUSINESS', 'FREQUENCY MODE', 'AMOUNT PAID', 'AMOUNT OWING', 'PHONE NUMBER','ADDRESS', 'TAX ID'));
			
			// Column Items
			if(!empty($row)) {
				foreach($row as $fields) {
					fputcsv($output, $fields);
				}
			}
			
			fclose($output);
			die;
		}

        $data['current_language'] = $this->session->get('current_language');
        
        if($param1 == 'manage'){
            return view('payments/tax_form', $data);
        } else {
            $data['title'] = translate_phrase('Tax Invoices').' - '.app_name;
            $data['page_active'] = $mod;
            return view('payments/tax', $data);
        }
       
    }

    public function transaction($param1='', $param2='', $param3='') {
        // check login
        $log_id = $this->session->get('td_id');
        
       if(empty($log_id)) return redirect()->to(site_url('auth'));
       $log_id = $this->session->get('td_id');
    //    if($this->Crud->check2('id', $log_id, 'pin', 0, 'user')> 0)return redirect()->to(site_url('auth/security'));
       if($this->Crud->check2('id', $log_id, 'state_id', 0, 'user')> 0)return redirect()->to(site_url('auth/profile'));
       if($this->Crud->check2('id', $log_id, 'country_id', 0, 'user')> 0)return redirect()->to(site_url('auth/profile'));
        $mod = 'payments/transaction';

        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $username = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
        $email = $this->Crud->read_field('id', $log_id, 'user', 'email');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $role_c = $this->Crud->module($role_id, $mod, 'create');
        $role_r = $this->Crud->module($role_id, $mod, 'read');
        $role_u = $this->Crud->module($role_id, $mod, 'update');
        $role_d = $this->Crud->module($role_id, $mod, 'delete');
        
        
        $data['log_id'] = $log_id;
        $data['param1'] = $param1;
        $data['param2'] = $param2;
        $data['param3'] = $param3;
        $data['role'] = $role;
        $merchant = '';
        
        
       
        $data['merchant'] = $merchant;
        $data['role_c'] = $role_c;
        $data['username'] = $username;
        $data['email'] = $email; 
        
          // record listing
        if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 30;
			$items = '	
                <div class="nk-tb-item nk-tb-head">
                    <div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Date').'</span></div>
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Tax Account').'</span></div>
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Amount Paid').'</span></div>
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Reference').'</span></div>
                    <div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Method').'</span></div>
                </div><!-- .nk-tb-item -->
                    
                
            ';
            $item = '';
			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
            $date_type = $this->request->getPost('date_type');
            $lga_id = $this->request->getPost('lga_id');
            $territory = $this->request->getPost('territory');
            $date_type = $this->request->getPost('date_type');
            if(!empty($this->request->getPost('start_date'))) { $start_dates = $this->request->getPost('start_date'); } else { $start_dates = ''; }
            if(!empty($this->request->getPost('end_date'))) { $end_dates = $this->request->getPost('end_date'); } else { $end_dates = ''; }
            if($date_type == 'Today'){
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d');
            } elseif($date_type == 'Yesterday'){
                $start_date = date('Y-m-d', strtotime( '-1 days' ));
                $end_date = date('Y-m-d', strtotime( '-1 days' ));
            } elseif($date_type == 'Last_Week'){
                $start_date = date('Y-m-d', strtotime( '-7 days' ));
                $end_date = date('Y-m-d');
            } elseif($date_type == 'Last_Month'){
                $start_date = date('Y-m-d', strtotime( '-30 days' ));
                $end_date = date('Y-m-d');
            } elseif($date_type == 'Date_Range'){
                $start_date = $start_dates;
                $end_date = $end_dates;
            } elseif($date_type == 'This_Year'){
                $start_date = date('Y-01-01');
                $end_date = date('Y-m-d');
            } else {
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-d');
            }
            
            $search = $this->request->getVar('search');

			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$query = $this->Crud->filter_history($limit, $offset, $log_id, $search, $lga_id, $territory, $start_date, $end_date);
				$all_rec = $this->Crud->filter_history('', '', $log_id, $search, $lga_id, $territory, $start_date, $end_date);
				if(!empty($all_rec)) { $count = count($all_rec); } else { $count = 0; }
				$curr = '&#8358;';
                
				if(!empty($query)) {
					foreach($query as $q) {
                        $id = $q->id;
                        $user_id = $q->user_id;
                        $tax_id = $this->Crud->read_field('user_id', $user_id, 'virtual_account', 'acc_no');
                        $payment_method = $q->payment_method;
                        $ref = $q->ref;
                        $remark = $q->remark;
                        $amount = number_format((float)$q->amount, 2);
                        $reg_date = date('M d, Y h:i A', strtotime($q->reg_date));

                        // user 
                        $user = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
                        
                       

                        $item .= '
                            <div class="nk-tb-item">
                                <div class="nk-tb-col tb-col-md">
                                    <span class="text-dark">'.$reg_date.'</span>
                                </div>
                                <div class="nk-tb-col">
                                    <div class="d-md-none">'.$reg_date.'</div>
                                    <span class="fw-bold text-success">'.$tax_id.'</span><br>
                                    <span class="fw-bold text-secondary">'.translate_phrase(strtoupper($user)).'</span>
                                </div>
                                <div class="nk-tb-col">
                                    <span class="text-info">'.$curr.$amount.'</span>
                                    <div class="d-md-none">
                                        '.strtoupper($payment_method).'
                                    </div>
                                </div>
                                <div class="nk-tb-col">
                                    <span>'.$ref.'</span>
                                </div>
                                <div class="nk-tb-col tb-col-md">
                                    '.strtoupper($payment_method).'
                                </div>
                                
                            </div>
                            
                        ';
                    }
				}
			}

			$total = 0;
			
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-tranx" style="font-size:150px;"></i><br/><br/>No Tax Payment Returned
					</div>
				';
			} else {
				$resp['item'] = $items.$item;
			}

			$more_record = $count - ($offset + $rec_limit);
			$resp['left'] = $more_record;
			$resp['total'] = $curr . number_format($total, 2);

			if($count > ($offset + $rec_limit)) { // for load more records
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
        
        if($param1 == 'scan'){
            return view('payments/form', $data);
        } else {
            $data['title'] = translate_phrase('Transaction History').' - '.app_name;
            $data['page_active'] = $mod;
            return view('payments/transaction', $data);
        }
       
    }

    public function confirm_code(){
        $url = $this->request->getPost('search');
        $confirm = site_url('auth/profile_view');
        // Test if string contains the word 
        if(strpos($url, $confirm) !== false){
            echo $this->Crud->msg('success', translate_phrase('QR CODE CONFIRMED!'));
			echo '<script>window.location.replace("'.$url.'");</script>';
        } else{
            echo $this->Crud->msg('danger', translate_phrase('Error while scanning code.<br><b>INCORRECT FUNDME-CASH QR CODE.</b> <br>Try Again'));
        }
        die;
    }
    //Get Account
    public function get_account(){
        $log_id = $this->session->get('td_id');
        if(!empty($log_id)){
            $acc = $this->Crud->read_single('user_id', $log_id, 'account');
            if(empty($acc)){
                echo '<span class="coin-name">'.translate_phrase('No Account!. Setup Account to Proceed.').'</span>';
            } else{
                foreach($acc as $a){
                    echo '
                        <div class="coin-item coin-btc" >
                            <div class="coin-icon">
                                <em class="icon ni ni-user"></em>
                            </div>
                            <div class="coin-info" id="account_resp">
                                <span class="coin-name">'.ucwords($a->name).'</span>
                                <span class="coin-text">('.$a->bank.') '.$a->account.'</span>
                        
                            </div>
                        </div>
                        <script>$("#types").prop("disabled", false);</script>  
                    ';
                }
            }
        }
    }

    public function get_virtual_account() {
        $log_id = $this->session->get('td_id');
        if($log_id) {
            $resp = $this->Crud->api('get', 'virutal_account/get/'.$log_id);
            // echo $resp;
            $resp = json_decode($resp);
           
            if(empty($resp->data)) {
                echo '<div class="text-danger text-center">'.translate_phrase('Please try again').'</div>';
            } else {
                echo '<div class="row">
                    <div class="col-12 text-center">
                        <b>'.translate_phrase('ACCOUNT NUMBER').'</b>
                        <h2 class="text-success">'.$resp->data.'</h2>
                        <b>GTBank</b>
                    </div>
                </div>';
            }
        }
        die;
    }
    //Verify Code
    public function verify_code($param){
        $log_id = $this->session->get('td_id');
        if(!empty($param)){
            $acc = $this->Crud->read_single('code', $param, 'voucher');
            if(empty($acc)){
                echo '<span class="coin-name text-danger">'.translate_phrase('Invalid Transaction Code').'</span>
                 <script>$("#types").prop("disabled", true);</script> 
                ';
            } else {
                foreach($acc as $a){
                    if($this->Crud->check2('code', $param, 'used_date !=', 'null', 'voucher') > 0){
                        echo '<span class="coin-name text-danger">'.translate_phrase('Transaction Code has been Used').'</span>
                         <script>$("#types").prop("disabled", true);</script> 
                        ';
                    } else{
                        echo '<div class="coin-info" >
                            <span class="coin-name text-success">'.translate_phrase('Transaction Code is Valid').'</span>
                            <span class="coin-text text-dark fw-medium" style="font-size:18px;">&#8358;'.number_format($a->amount, 2).'</span>
                            </div>
                            <script>$("#types").prop("disabled", false);</script>  
                        ';
                    }
                   
                }
            }
        }
    }
    
    //Verify User
    public function user_verify($param){
        $log_id = $this->session->get('td_id');
        if(!empty($param)){
            $acc = $this->Crud->read_single('phone', $param, 'user');
            if(empty($acc)){
                echo '<span class="coin-name text-danger">'.translate_phrase('Invalid Phone Number').'</span>
                 <script>$("#types").prop("disabled", true);$("#save_beneficiary_card").hide(500);</script> 
                ';
            } else {
                foreach($acc as $a){
                    $user = $a->fullname;
                    $role = $this->Crud->read_field('name', 'Transporter', 'access_role', 'id');
                    if($a->role_id == 8){
                        $user = $a->username;
                    }
                    echo '<div class="coin-info" >
                        <span class="coin-name text-success">'.translate_phrase('Account Verified').'</span>
                        <span class="coin-text text-dark fw-medium" style="font-size:18px;">'.ucwords($user).'</span>
                        </div>
                        <script>$("#types").prop("disabled", false);
                       </script>  
                    ';
                    if($a->role_id == $role){
                        echo '<script> $("#pay_for_card").show(500);</script>';
                    } else {
                        echo '<script> $("#pay_for_card").hide(500);$("#type").html("'.translate_phrase('Transfer').'");</script>';
                    }
                    if($this->Crud->check2('user_id', $log_id, 'beneficiary', $param, 'beneficiary') == 0){
                        echo '<script> $("#save_beneficiary_card").show(500);</script>';
                    }
                }
            }
        }
    }
    public function user_verifys($param){
        $log_id = $this->session->get('td_id');
        if(!empty($param)){
            $acc = $this->Crud->read_single('phone', $param, 'user');
            if(empty($acc)){
                echo '<span class="coin-name text-danger">'.translate_phrase('Invalid Phone Number').'</span>
                ';
            } else {
                foreach($acc as $a){
                    $user = $a->fullname;
                    $user_id = $a->id;
                    $address = $a->address;
                    if(empty($address)){
                        $address = 'Palm Avenue';
                    }
                    $meter_no = $this->Crud->read_field('id', $user_id, 'electricity', 'meter_no');
                    if(empty($meter_no)){
                        $characters = '0123456789';

                        // Generate a random string of 11 characters
                        $generatedString = substr(str_shuffle($characters), 0, 11);

                        $meter_no = $generatedString;
                    }
                    echo '
                        <div class="form-label-group mb-2" >
                            <label class="form-label" for="buysell-amount">'.translate_phrase('Fullname').'</label>
                        </div>
                        <div class="form-control-group mb-2 ">
                            <input type="text" class="form-control form-control-nuber" id="name" name="name" value="'.$user.'" readonly>
                        </div> 

                        <div class="form-label-group mb-2 ">
                            <label class="form-label" for="buysell-amount">'.translate_phrase('Address').'</label>
                        </div>
                        <div class="form-control-group mb-2 ">
                            <input type="text" class="form-control form-control-nuber" id="address" readonly name="address" value="'.$address.'" >
                        </div> 

                        <div class="form-label-group mb-2 ">
                            <label class="form-label" for="buysell-amount">'.translate_phrase('Meter Number').'</label>
                        </div>
                        <div class="form-control-group mb-2 ">
                            <input type="text" class="form-control form-control-numer" id="meter_no" name="meter_no" value="'.$meter_no.'" readonly>
                        </div> 
                    ';
                   
                    
                }
            }
        }
    }

    public function amount_verifys($param){
        $log_id = $this->session->get('td_id');
        if(!empty($param)){
           if($param > 0){
                $unit = (float)$param / 100;
                echo '
                    <div class="form-label-group mb-2">
                        <label class="form-label" for="buysell-amount">'.translate_phrase('Number of Unit').' </label>
                    </div>
                    <div class="form-control-group mb-2">
                        <input type="text" class="form-control form-control-umber" id="unit" name="unit" value="'.$unit.'"  readonly>
                    </div>
                    <div class="form-label-group mb-2">
                        <label class="form-label" for="buysell-amount">'.translate_phrase('Service').'(NGN) </label>
                    </div>
                    <div class="form-control-group mb-2">
                        <input type="text" class="form-control form-control-umber" id="service" name="service" value="100"  readonly>
                    </div>
           
                ';
           }
        }
    }

    //Save Beneficiary
    public function save_beneficiary($param){
        $phone = $this->request->getPost('phone');

        echo $phone;
        $log_id = $this->session->get('td_id');
        if($this->Crud->check('phone', $phone, 'user') > 0){
            
            $name = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
            if($this->Crud->check2('user_id', $log_id, 'beneficiary', $phone, 'beneficiary') > 0){
                $id = $this->Crud->read_field2('user_id', $log_id, 'beneficiary', $phone, 'beneficiary', 'id');
                $this->Crud->updates('id', $id, 'beneficiary', array('status'=>$param));
                $action = $name.translate_phrase(' Updated Beneficiary List');
            } else {
                $in_data['user_id'] = $log_id;
                $in_data['beneficiary'] = $phone;
                $in_data['status'] = $param;
                $in_data['reg_date'] = date(fdate);
                $this->Crud->create('beneficiary', $in_data);
                
                $name = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
                $b_name = $this->Crud->read_field('phone', $param, 'user', 'fullname');
                
                $action = $name.translate_phrase(' Added ').$b_name.translate_phrase(' to Beneficiary List');
    			
            }
            $this->Crud->activity('user', $log_id, $action);
               
        }
        
    }

    ///// LOGIN
    public function success() {
        $log_id = $this->session->get('td_id');
       if(empty($log_id)) return redirect()->to(site_url('auth'));
        $mod = 'payments';

        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $username = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
        $email = $this->Crud->read_field('id', $log_id, 'user', 'email');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $role_c = $this->Crud->module($role_id, $mod, 'create');
        $role_r = $this->Crud->module($role_id, $mod, 'read');
        $role_u = $this->Crud->module($role_id, $mod, 'update');
        $role_d = $this->Crud->module($role_id, $mod, 'delete');
        

        $data['log_id'] = $log_id;
        $data['role'] = $role;
        $data['role_c'] = $role_c;
        $data['username'] = $username;
        $data['email'] = $email;

        
        $data['current_language'] = $this->session->get('current_language');
        $data['page_active'] = $mod;
        $data['title'] = translate_phrase('Payment Success').' - '.app_name;
        return view('payments/success', $data);
    }


    public function receipt($param1='') {
        $log_id = $this->session->get('td_id');
    //    if(empty($log_id)) return redirect()->to(site_url('auth'));
        $mod = 'payments';

        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $username = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
        $email = $this->Crud->read_field('id', $log_id, 'user', 'email');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $role_c = $this->Crud->module($role_id, $mod, 'create');
        $role_r = $this->Crud->module($role_id, $mod, 'read');
        $role_u = $this->Crud->module($role_id, $mod, 'update');
        $role_d = $this->Crud->module($role_id, $mod, 'delete');
        

        $data['log_id'] = $log_id;
        $data['trans_id'] = $param1;
        $data['role'] = $role;
        $data['role_c'] = $role_c;
        $data['username'] = $username;
        $data['email'] = $email;

        
        $data['current_language'] = $this->session->get('current_language');
        $data['page_active'] = $mod;
        $data['title'] = translate_phrase('Payment Receipt').' - '.app_name;
        return view('payments/receipt', $data);
    }

    public function confirm() {
        $log_id = $this->session->get('td_id');
       if(empty($log_id)) return redirect()->to(site_url('auth'));
        $mod = 'payments';

        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $username = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
        $email = $this->Crud->read_field('id', $log_id, 'user', 'email');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $role_c = $this->Crud->module($role_id, $mod, 'create');
        $role_r = $this->Crud->module($role_id, $mod, 'read');
        $role_u = $this->Crud->module($role_id, $mod, 'update');
        $role_d = $this->Crud->module($role_id, $mod, 'delete');
        

        $data['log_id'] = $log_id;
        $data['role'] = $role;
        $data['role_c'] = $role_c;
        $data['username'] = $username;
        $data['email'] = $email;
        
        
        $data['current_language'] = $this->session->get('current_language');
        $data['title'] = translate_phrase('Confirm Payment').' - '.app_name;
        return view('payments/confirm', $data);
    }

    public function captures($divId='', $outputPath='') {
		ob_start();  // Start output buffering
		// include 'app/controllers/auth/profile_view';  // Replace with the actual path to your page
		$html = ob_get_clean();  // Get the buffered output and clean the buffer
        $outputPath = 'assets/images';
        $divID = 'qrcode';
		// Create a new image from HTML
		$image = imagecreatetruecolor(800, 600);  // Set the width and height according to your needs
		$bgColor = imagecolorallocate($image, 255, 255, 255);  // Set background color (white)
		imagefill($image, 0, 0, $bgColor);
	
		// Convert HTML to image
		$domDocument = new DOMDocument();
		@$domDocument->loadHTML($html);
		$domElement = $domDocument->getElementById($divId);
	
		if ($domElement) {
			$imagePath = $outputPath;  // Replace with the desired output path and filename
			imagejpeg($image, $imagePath);  // Save the image as JPEG
			imagedestroy($image);
	
			echo "Image saved successfully: $imagePath";
		} else {
			echo "Error: Unable to find the specified div with ID $divId";
		}
	}
}