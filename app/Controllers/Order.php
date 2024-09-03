<?php

namespace App\Controllers;

class Order extends BaseController {

    
    //Orders
	public function list($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('fls_id') == ''){
			$request_uri = uri_string();
			$this->session->set('fls_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'order/list';

        $log_id = $this->session->get('fls_id');
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
       
		$table = 'order';
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
								$data['e_code'] = $e->code;
								$data['e_state'] = $this->Crud->read_field('id', $e->state_id, 'state', 'name');

								// currency
								$curr = '&#8358;';
								$data['e_curr'] = $curr;

								$category_id = $e->category_id;
								$vendor_id = $e->partner_id;
								$customer_id = $e->user_id;

								// category data
								$category = $this->Crud->read_field('id', $category_id, 'category', 'name');


								// vendor data
								$vendor = $this->Crud->read_field('id', $vendor_id, 'user', 'fullname');
								$vendor_address = $this->Crud->read_field('id', $vendor_id, 'user', 'address');
								$vendor_phone = $this->Crud->read_field('id', $vendor_id, 'user', 'phone');
								$vendor_email = $this->Crud->read_field('id', $vendor_id, 'user', 'email');
								$vendor_img_id = $this->Crud->read_field('id', $vendor_id, 'user', 'img_id');
								$vendor_img = $this->Crud->image($vendor_img_id, 'big');

								// customer data
								$customer = $this->Crud->read_field('id', $customer_id, 'user', 'fullname');
								$customer_address = $this->Crud->read_field('id', $customer_id, 'user', 'address');
								$customer_phone = $this->Crud->read_field('id', $customer_id, 'user', 'phone');
								$customer_email = $this->Crud->read_field('id', $customer_id, 'user', 'email');
								$customer_img_id = $this->Crud->read_field('id', $customer_id, 'user', 'img_id');
								$customer_img = $this->Crud->image($customer_img_id, 'big');

								$item_name = '';
								if($e->litre < 2) {
									$item_name = $e->litre.' litres of '.$category;
								} else {
									$item_name = $e->litre.' litres  of '.$category;
								}
								
								$data['e_category'] = $category;
								$data['e_vendor'] = $vendor;
								$data['e_vendor_address'] = $vendor_address;
								$data['e_vendor_phone'] = $vendor_phone;
								$data['e_vendor_email'] = $vendor_email;
								$data['e_vendor_img'] = $vendor_img;
								$data['e_customer'] = $customer;
								$data['e_customer_address'] = $customer_address;
								$data['e_customer_phone'] = $customer_phone;
								$data['e_customer_email'] = $customer_email;
								$data['e_customer_img'] = $customer_img;
								$data['e_delivery_date'] = date('M d, Y h:i A', strtotime($e->used_date));
								$data['e_item_name'] = $item_name;
								$data['e_quantity'] = number_format((float)$e->litre);
								$data['e_sub_total'] = number_format((float)$e->amount, 2);
								$data['e_vat'] = number_format((float)$e->vat, 2);
								$data['e_total'] = number_format((float)$e->total, 2);

								if($e->status) { $status = 'Approved'; } else { $status = $e->status; }
								$data['e_status'] = $status;
								$data['e_approved'] = $e->status;
								$data['e_reg_date'] = date('M d, Y h:i A', strtotime($e->reg_date));

								
								$comms = '';
								// vendor commission details
								$v_acc = $this->Crud->read_field('user_id', $vendor_id, 'account', 'name').'<br/>'.$this->Crud->read_field('user_id', $vendor_id, 'account', 'account').' - '.$this->Crud->read_field('user_id', $vendor_id, 'account', 'bank');
								$comms .= '
									<tr>
										<td>'.$vendor.'<div class="small">'.$v_acc.'</div></td>
										<td><b>FUELING STATION</b></td>
										<td class="text-right">'.number_format((float)$e->amount, 2).'</td>
									</tr>
								';

								$data['comms'] = $comms;
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

        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 25;
			$item = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			if(!empty($this->request->getVar('status'))) { $status = $this->request->getVar('status'); } else { $status = ''; }
			if(!empty($this->request->getVar('product_id'))) { $product_id = $this->request->getVar('product_id'); } else { $product_id = ''; }
			if(!empty($this->request->getVar('station_id'))) { $station_id = $this->request->getVar('station_id'); } else { $station_id = ''; }
			if(!empty($this->request->getVar('country_id'))) { $country_id = $this->request->getVar('country_id'); } else { $country_id = ''; }
			if(!empty($this->request->getVar('state'))) { $state = $this->request->getVar('state'); } else { $state = ''; }
			if(!empty($this->request->getVar('lga'))) { $lga = $this->request->getVar('lga'); } else { $lga = ''; }
			if(!empty($this->request->getVar('branch'))) { $branch = $this->request->getVar('branch'); } else { $branch = ''; }
			if(!empty($this->request->getVar('start_date'))) { $start_date = $this->request->getVar('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getVar('end_date'))) { $end_date = $this->request->getVar('end_date'); } else { $end_date = ''; }
			$search = $this->request->getVar('search');

			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$query = $this->Crud->filter_order($limit, $offset, $log_id, $status, $product_id, $station_id,$country_id, $state,$lga, $branch, $search, $start_date, $end_date);
				$all_rec = $this->Crud->filter_order('', '', $log_id, $status, $product_id, $station_id,$country_id, $state,$lga, $branch, $search, $start_date, $end_date);
				if(!empty($all_rec)) { $count = count($all_rec); } else { $count = 0; }
				$curr = '&#8358;';
				$p = array();$to = array();
				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$code = $q->code;
						$state_id = $q->state_id;
						$user_id = $q->user_id;
						$category_id = $q->category_id;
						$quantity = $q->litre;
						$status = $q->status;
						$used = $q->used;
						$total = number_format((float)$q->total, 2);
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
                        // category
						$category = $this->Crud->read_field('id', $category_id, 'category', 'name');

						// state
						$state = $this->Crud->read_field('id', $state_id, 'state', 'name');

						// user 
						$user = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
						$user_role_id = $this->Crud->read_field('id', $user_id, 'user', 'role_id');
						$user_role = strtoupper($this->Crud->read_field('id', $user_role_id, 'access_role', 'name'));
						$user_image_id = $this->Crud->read_field('id', $user_id, 'user', 'img_id');
						$user_image = $this->Crud->image($user_image_id, 'big');

                        // format category quantity
						$cat_q = '';
                        if($category == 'Gas'){
                            $cat = 'KG';
                        } else {
                            $cat = 'Litre';
                        }
						if($quantity <= 1) {
							$cat_q = $quantity.' '.$cat.' of';
						} else {
							$cat_q = $quantity.' '.$cat.'s of';
						}

                        // if can edit
						if($role_u) {
						    
							$code = '
								<a href="javascript:;" class="text-success pop" pageTitle="Order Statement" pageName="'.base_url('order/list/manage/edit/'.$id).'" pageSize="modal-lg">
									<i class="ni ni-edit"></i> <span class="m-l-3 m-r-10"><b>'.$code.'</b></span>
								</a>
							';
						}
						$item .= '
                            <li class="list-group-item">
                                <div class="row pt-10">
                                    <div class="col-8 col-md-3 mb-10">
                                        <div class="single">
                                            <div class="text-muted font-size-12">'.strtoupper($cat_q).'</div>
                                            <b class="font-size-16">'.$category.'</b>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-5">
                                        <div class="single">
                                            <div class="text-muted font-size-12">'.$reg_date.'</div>
                                            <div class="font-size-14">'.$code.'</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="single">
                                            <div class="text-muted font-size-12">'.$status.'</div>
                                            <div class="font-size-14">'.strtoupper($state).'</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2 text-right">
                                        <div class="single">
                                            <div class="text-muted font-size-12">AMOUNT</div>
                                            <b class="font-size-16 text-success">'.$curr.$total.'</b>
                                        </div>
                                    </div>
                                </div>
                            </li>
						';
					}
				}
			}

			$total = 0;
			//print_r($to);
			foreach($to as $val){
				$total += $val;
			}
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-wallet" style="font-size:150px;"></i><br/><br/>No Order Returned
					</div>
				';
			} else {
				$resp['item'] = $item;
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

		if($param1 == 'manage' || $param1 == 'fund' || $param1 == 'statement') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = 'Orders  | '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }

	//Orders
	public function sales($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('fls_id') == ''){
			$request_uri = uri_string();
			$this->session->set('fls_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'order/sales';

        $log_id = $this->session->get('fls_id');
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
       
		$table = 'order';
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
								$data['e_code'] = $e->code;
								$data['e_state'] = $this->Crud->read_field('id', $e->state_id, 'state', 'name');

								// currency
								$curr = '&#8358;';
								$data['e_curr'] = $curr;

								$category_id = $e->category_id;
								$vendor_id = $e->partner_id;
								$customer_id = $e->user_id;

								// category data
								$category = $this->Crud->read_field('id', $category_id, 'category', 'name');


								// vendor data
								$vendor = $this->Crud->read_field('id', $vendor_id, 'user', 'fullname');
								$vendor_address = $this->Crud->read_field('id', $vendor_id, 'user', 'address');
								$vendor_phone = $this->Crud->read_field('id', $vendor_id, 'user', 'phone');
								$vendor_email = $this->Crud->read_field('id', $vendor_id, 'user', 'email');
								$vendor_img_id = $this->Crud->read_field('id', $vendor_id, 'user', 'img_id');
								$vendor_img = $this->Crud->image($vendor_img_id, 'big');

								// customer data
								$customer = $this->Crud->read_field('id', $customer_id, 'user', 'fullname');
								$customer_address = $this->Crud->read_field('id', $customer_id, 'user', 'address');
								$customer_phone = $this->Crud->read_field('id', $customer_id, 'user', 'phone');
								$customer_email = $this->Crud->read_field('id', $customer_id, 'user', 'email');
								$customer_img_id = $this->Crud->read_field('id', $customer_id, 'user', 'img_id');
								$customer_img = $this->Crud->image($customer_img_id, 'big');

								$item_name = '';
								if($e->litre < 2) {
									$item_name = $e->litre.' litres of '.$category;
								} else {
									$item_name = $e->litre.' litres  of '.$category;
								}
								
								$data['e_category'] = $category;
								$data['e_vendor'] = $vendor;
								$data['e_vendor_address'] = $vendor_address;
								$data['e_vendor_phone'] = $vendor_phone;
								$data['e_vendor_email'] = $vendor_email;
								$data['e_vendor_img'] = $vendor_img;
								$data['e_customer'] = $customer;
								$data['e_customer_address'] = $customer_address;
								$data['e_customer_phone'] = $customer_phone;
								$data['e_customer_email'] = $customer_email;
								$data['e_customer_img'] = $customer_img;
								$data['e_delivery_date'] = date('M d, Y h:i A', strtotime($e->used_date));
								$data['e_item_name'] = $item_name;
								$data['e_quantity'] = number_format((float)$e->litre);
								$data['e_sub_total'] = number_format((float)$e->amount, 2);
								$data['e_vat'] = number_format((float)$e->vat, 2);
								$data['e_total'] = number_format((float)$e->total, 2);

								if($e->status) { $status = 'Approved'; } else { $status = $e->status; }
								$data['e_status'] = $status;
								$data['e_approved'] = $e->status;
								$data['e_reg_date'] = date('M d, Y h:i A', strtotime($e->reg_date));

								
								$comms = '';
								// vendor commission details
								$v_acc = $this->Crud->read_field('user_id', $vendor_id, 'account', 'name').'<br/>'.$this->Crud->read_field('user_id', $vendor_id, 'account', 'account').' - '.$this->Crud->read_field('user_id', $vendor_id, 'account', 'bank');
								$comms .= '
									<tr>
										<td>'.$vendor.'<div class="small">'.$v_acc.'</div></td>
										<td><b>FUELING STATION</b></td>
										<td class="text-right">'.number_format((float)$e->amount, 2).'</td>
									</tr>
								';

								$data['comms'] = $comms;
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

        // record listing
		if($param1 == 'load') {
			$limit = $param2;
			$offset = $param3;

			$count = 0;
			$rec_limit = 25;
			$item = '';

			if($limit == '') {$limit = $rec_limit;}
			if($offset == '') {$offset = 0;}
			
			if(!empty($this->request->getVar('status'))) { $status = $this->request->getVar('status'); } else { $status = ''; }
			if(!empty($this->request->getVar('product_id'))) { $product_id = $this->request->getVar('product_id'); } else { $product_id = ''; }
			if(!empty($this->request->getVar('station_id'))) { $station_id = $this->request->getVar('station_id'); } else { $station_id = ''; }
			if(!empty($this->request->getVar('country_id'))) { $country_id = $this->request->getVar('country_id'); } else { $country_id = ''; }
			if(!empty($this->request->getVar('state'))) { $state = $this->request->getVar('state'); } else { $state = ''; }
			if(!empty($this->request->getVar('lga'))) { $lga = $this->request->getVar('lga'); } else { $lga = ''; }
			if(!empty($this->request->getVar('branch'))) { $branch = $this->request->getVar('branch'); } else { $branch = ''; }
			if(!empty($this->request->getVar('start_date'))) { $start_date = $this->request->getVar('start_date'); } else { $start_date = ''; }
			if(!empty($this->request->getVar('end_date'))) { $end_date = $this->request->getVar('end_date'); } else { $end_date = ''; }
			$search = $this->request->getVar('search');

			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$query = $this->Crud->filter_sales($limit, $offset, $log_id, $status, $product_id, $station_id,$country_id, $state,$lga, $branch, $search, $start_date, $end_date);
				$all_rec = $this->Crud->filter_sales('', '', $log_id, $status, $product_id, $station_id,$country_id, $state,$lga, $branch, $search, $start_date, $end_date);
				if(!empty($all_rec)) { $count = count($all_rec); } else { $count = 0; }
				$curr = '&#8358;';
				$p = array();$to = array();$i = 1;
				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$code = $q->code;
						$state_id = $q->state_id;
						$user_id = $q->user_id;
						$category_id = $q->category_id;
						$quantity = $q->litre;
						$status = $q->status;
						$used = $q->used;
						$total = number_format((float)$q->total, 2);
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));
                        // category
						$category = $this->Crud->read_field('id', $category_id, 'category', 'name');

						// state
						$state = $this->Crud->read_field('id', $state_id, 'state', 'name');

						// user 
						$user = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
						$user_role_id = $this->Crud->read_field('id', $user_id, 'user', 'role_id');
						$user_role = strtoupper($this->Crud->read_field('id', $user_role_id, 'access_role', 'name'));
						$user_image_id = $this->Crud->read_field('id', $user_id, 'user', 'img_id');
						$user_image = $this->Crud->image($user_image_id, 'big');

                        // format category quantity
						$cat_q = '';
                        if($category == 'Gas'){
                            $cat = 'KG';
                        } else {
                            $cat = 'Litre';
                        }
						if($quantity <= 1) {
							$cat_q = $quantity.' '.$cat.' of';
						} else {
							$cat_q = $quantity.' '.$cat.'s of';
						}

                        // if can edit
						if($role_u) {
						    
							$code = '
								<a href="javascript:;" class="text-success pop" pageTitle="Order Statement" pageName="'.base_url('order/list/manage/edit/'.$id).'" pageSize="modal-lg">
									<i class="ni ni-edit"></i> <span class="m-l-3 m-r-10"><b>'.$code.'</b></span>
								</a>
							';
						}
						$item .= '
                            <li class="list-group-item">
                                <div class="row pt-10">
                                    <div class="col-2  col-md-1">
                                        <div class="single">
											<div class="text-muted font-size-12">S/N</div>
                                            <div class="text-dark font-size-16">'.strtoupper($i).'</div>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <div class="single">
											<div class="text-muted font-size-12">Date</div>	
                                            <div class="text-dark font-size-16">'.($reg_date).'</div>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <div class="single">
											<div class="text-muted font-size-12">Code</div>	
                                            <div class="font-size-16">'.$code.'</div>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-1 mb-2">
                                        <div class="single">
											<div class="text-muted font-size-12">Category</div>
                                            <div class="text-dark font-size-16">'.$category.'</div>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2 text-right">
                                        <div class="single">
                                            <div class="text-muted font-size-12">Amount</div>
                                            <b class="font-size-16 text-dark">'.$curr.$total.'</b>
                                        </div>
                                    </div>
									<div class="col-4 col-md-2 text-right">
                                        <div class="single">
                                            <div class="text-muted font-size-12">Quantity</div>
                                            <b class="font-size-16 text-dark">'.$quantity.'</b>
                                        </div>
                                    </div>
									<div class="col-4 col-md-2 text-right">
                                        <div class="single">
                                            <div class="text-muted font-size-12">Pump Used</div>
                                            <b class="font-size-16 text-dark">'.$q->pump.'</b>
                                        </div>
                                    </div>
                                </div>
                            </li>
						';
						$i++;
					}
				}
			}

			$total = 0;
			//print_r($to);
			foreach($to as $val){
				$total += $val;
			}
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="icon ni ni-wallet" style="font-size:150px;"></i><br/><br/>No Sales Returned
					</div>
				';
			} else {
				$resp['item'] = $item;
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

		if($param1 == 'manage' || $param1 == 'statement') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = 'Sales  | '.app_name;
			$data['page_active'] = $mod;
			return view($mod, $data);
		}
    }
}
