<?php

namespace App\Controllers;

class Analytics extends BaseController {
    public function index($param1='', $param2='', $para3='') {
        // check login
        $log_id = $this->session->get('td_id');
       if(empty($log_id)) return redirect()->to(site_url('auth'));

       if($this->Crud->check2('id', $log_id, 'setup', 0, 'user')> 0)return redirect()->to(site_url('auth/security'));
       if($this->Crud->check2('id', $log_id, 'state_id', 0, 'user')> 0)return redirect()->to(site_url('auth/profile'));
       if($this->Crud->check2('id', $log_id, 'country_id', 0, 'user')> 0)return redirect()->to(site_url('auth/profile'));
        $mod = 'analytics';
        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $role_c = $this->Crud->module($role_id, $mod, 'create');
        $role_r = $this->Crud->module($role_id, $mod, 'read');
        $role_u = $this->Crud->module($role_id, $mod, 'update');
        $role_d = $this->Crud->module($role_id, $mod, 'delete');
        if($role_r == 0){
            return redirect()->to(site_url('profile'));	
        }
        $username = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
    
        $data['log_id'] = $log_id;
        $data['username'] = $username;
        $data['current_language'] = $this->session->get('current_language');
        $data['role'] = $role;
        $data['role_c'] = $role_c;
        $data['title'] = translate_phrase('Analytics').' - '.app_name;
        $data['page_active'] = $mod;
        return view('analytics', $data);
    }

    ///// LOGIN
    public function overview($param1='', $param2='', $param3='') {
        $log_id = $this->session->get('td_id');
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
        
        
       
        if($param1 == 'metric'){

            // $query = $this->Crud->filter_voucher('', '', $log_id, '', $start_date, $end_date) ;
			$resp = [];

            
            $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
            $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));

            $remittance = 0;
            $total_paid = 0;
            $total_unpaid = 0;
            $wallet = 0;
            
            if($role == 'developer' || $role == 'administrator'){
                $users = $this->Crud->date_range1($start_date, 'reg_date', $end_date, 'reg_date','trade >',0, 'user');
                if(!empty($users)){
                    foreach($users as $u){
                        $remittance += (int)$this->Crud->read_field('id', $u->trade, 'trade', 'medium');
                        $remits = $this->Crud->date_range2($start_date, 'reg_date', $end_date, 'reg_date','user_id', $u->id, 'payment_type', 'tax', 'transaction');
                        if(!empty($remits)){
                            foreach($remits as $w){
                                if($w->balance != $w->amount){
                                    $rem = (float)$w->amount - (float)$w->balance;                                
                                    $total_paid += (float)$rem;
                
                                }
                            }
                        
                        }
                    }
                }
            }

            if($role != 'developer' && $role != 'administrator'){
                $trade_id = $this->Crud->read_field('id', $log_id, 'user', 'trade');
                $duration = $this->Crud->read_field('id', $log_id, 'user', 'duration');
                if(!empty($trade_id)){
                    $remittance = $this->Crud->read_field('id', $trade_id, 'trade', 'medium');
                   $remits = $this->Crud->date_range2($start_date, 'reg_date', $end_date, 'reg_date','user_id', $log_id, 'payment_type', 'tax', 'transaction');
                    if(!empty($remits)){
                        foreach($remits as $w){
                            if($w->balance != $w->amount){
                                $rem = (float)$w->amount - (float)$w->balance;                                
                                $total_paid += (float)$rem;
            
                            }
                        }
                    
                    }
                    
                }
            }

            $total_unpaid = $remittance - $total_paid;


            
            $debit = 0;
            $credit = 0;

            $wallets = $this->Crud->read_single('user_id', $log_id, 'wallet');
            if(!empty($wallets)){
                foreach($wallets as $w){
                    if($w->type == 'debit') {$debit += (float)$w->amount;}

                    if($w->type == 'credit') {$credit += (float)$w->amount;}

                }
            }
            $bal = $credit - $debit;
            if($bal < 0)$bal = 0;
            $resp['wallet'] = number_format($bal,2);
            $resp['remittance'] = number_format($remittance,2);
            $resp['total_paid'] = number_format($total_paid,2);
            $resp['total_unpaid'] = number_format($total_unpaid,2);

            

			echo json_encode($resp);
			die;
        }

        if($param1 == 'tax_metric') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 8;
			$items = '	
                <div class="nk-tb-item nk-tb-head">
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Tax Account').'</span></div>
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Amount').'</span></div>
                    <div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Remark').'</span></div>
                    <div class="nk-tb-col"><span class="sub-text">'.translate_phrase('Reference').'</span></div>
                    <div class="nk-tb-col tb-col-md"><span class="sub-text">'.translate_phrase('Payment Date').'</span></div>
                </div><!-- .nk-tb-item -->
                    
                
            ';
            $item = '';
			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			if(!empty($this->request->getVar('status'))) { $status = $this->request->getVar('status'); } else { $status = ''; }
			if(!empty($this->request->getVar('lga'))) { $lga = $this->request->getVar('lga'); } else { $lga = ''; }
			if(!empty($this->request->getVar('territory'))) { $territory = $this->request->getVar('territory'); } else { $territory = ''; }
			$search = $this->request->getVar('search');

			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$query = $this->Crud->filter_transaction($limit, $offset, $log_id, 'tax', $search, $lga, $territory,$start_date, $end_date);
				$all_rec = $this->Crud->filter_transaction('', '', $log_id, 'tax', $search, $lga, $territory,$start_date, $end_date);
				if(!empty($all_rec)) { $count = count($all_rec); } else { $count = 0; }
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
                        $paid_date = date('M d, Y h:i A', strtotime($q->paid_date));
                        $payment_date = date('d M, Y', strtotime($q->payment_date));

                        $st = '<span class="text-danger">'.ucwords($status).'</span>';
                        if($status != 'pending'){
                            $st = '<span class="text-success">'.ucwords($status).'</span>';
                        }


                        // user 
                        $user = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
                        $user_image_id = $this->Crud->read_field('id', $user_id, 'user', 'img_id');
                        $user_image = $this->Crud->image($user_image_id, 'big');
                        $btn = '';$bal ='';$paid ='';
                        if($status == 'pending'){
                            $rem = (float)$q->amount - (float)$q->balance;
                            $bal = '<br><span class="text-danger fw-bold">Bal: '.$balance.'</span><br><span class="text-success fw-bold">Paid: '.curr.number_format($rem,2).'</span>';
                           
                        }
                        if(!empty($ref) && !empty($q->paid_date)){
                            $paid = '<br><span class="text-danger">Paid Date: <br>'.$paid_date.'</span>';
                        }
                        // currency

                        $item .= '
                            <div class="nk-tb-item">
                                <div class="nk-tb-col">
                                    <span class="text-muted">'.($reg_date).'</span><br>
                                    <span class="fw-bold text-success">'.$tax_id.'</span><br>
                                    <span class="fw-bold text-secondary">'.translate_phrase(strtoupper($user)).'</span>'.$paid.'
                                </div>
                                <div class="nk-tb-col">
                                    <span class="text-info">'.$curr.$amount.'</span>
                                </div>
                                <div class="nk-tb-col tb-col-md">
                                    <span class="fw-bold text-secondary">'.translate_phrase(ucwords($remark)).'</span><br>'.$st.'
                                    '.$bal.'
                                </div>
                                <div class="nk-tb-col">
                                    <span>'.$ref.'</span>
                                </div>
                                <div class="nk-tb-col tb-col-md">
                                    <span class="text-dark">'.$payment_date.'</span>
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


    }
}
