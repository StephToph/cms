<?php 

namespace App\Controllers;

class Api extends BaseController {
	private $token;
	private $db;
    
   
    
	public function __construct() {
		$this->db = \Config\Database::connect();
		

		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization");
		header("Content-Type: application/json; charset=UTF-8");

		$this->token = getenv('api_key');

		// check token
		$token = null;
		$headers = apache_request_headers();
		if(isset($headers['Authorization'])){
			$token = $headers['Authorization'];
			$token = explode(' ', $token)[1];
		}

		if($this->token != $token) {
			echo json_encode(array('status' => false, 'msg' => 'Invalid Token'));
			die;
		}
    }
	
	public function index() { 
		echo 'Api Working';
		die;
	}
	
	// register
	public function register() {
	    $status = false;
		$data = array();
		$msg = '';
		$code = 'info';
		// collect call paramters
		$call = json_decode(file_get_contents("php://input"));
		$fullname = $call->fullname;
		$username = $call->username;
		$email = $call->email;
		$password = $call->password;
		$state_id = $call->state_id;
		$referral_id = $call->referral_id;
		$country_id = $call->country_id;
		$role_id = $call->role_id;
		$phone = $call->phone;
		
		if($username && $email && $password && $phone) {
			// check if email already exists
			if($this->Crud->check('email', $email, 'user') > 0 || $this->Crud->check('phone', $phone, 'user') > 0) {
				$msg = 'Email and/or Phone Taken! Please choose another.';
			} else {
				if(str_word_count($fullname) < 2){
					$code ='danger';
					$msg = 'Fullname must contain surname and first name.';
				} else {
					if(!empty($referral_id)){
						if( strpos( strtoupper($referral_id), 'FNM' ) === false ) {
							$msg = 'Referral Not Found';
						} else {
							$user_code = explode('-', $referral_id);
							$user_ids = $user_code[1];
							if(!empty($this->Crud->check('id', $user_ids, 'user'))){
								$role_id = $this->Crud->read_field('name', $role_id, 'access_role', 'id');
								$ins['username'] = $username;
								$ins['fullname'] = $fullname;
								$ins['email'] = $email;
								$ins['state_id'] = $state_id;
								$ins['country_id'] = $country_id;
								$ins['password'] = md5($password);
								$ins['phone'] = $phone;
								$ins['role_id'] = $role_id;
								$ins['referral_id'] = $referral_id;
								$ins['reg_date'] = date(fdate);
								$user_id = $this->Crud->create('user', $ins);
								if($user_id > 0) {
									$r_data['user_id'] = $user_ids;
									$r_data['referral_id'] = $user_id;
									$r_data['reg_date'] = date(fdate);
									$this->Crud->create('referral', $r_data);
	
									$link = site_url('auth/profile_view/') .$user_id;
									$qr = $this->qrcode($link);
									$path = $qr['file'];
									$this->Crud->updates('id', $user_id, 'user', array('qrcode'=>$path));
									///// store activities
									$code = $this->Crud->read_field('id', $user_id, 'user', 'username');
									$action = $code.' registered on ';
									$this->Crud->activity('authentication', $user_id, $action);
	
									$status = true;
									$msg = 'Successful!';
									$datas['email'] = $email;
									$code = 'success';
								
									$data['id'] = $user_id;
								} else {
									$msg = 'Oops! Try later';
								}
							} else {
								$msg = 'Referral Not Found';
							}
						}
					} else {
						$role_id = $this->Crud->read_field('name', $role_id, 'access_role', 'id');
						$ins['username'] = $username;
						$ins['fullname'] = $fullname;
						$ins['email'] = $email;
						$ins['state_id'] = $state_id;
						$ins['country_id'] = $country_id;
						$ins['password'] = md5($password);
						$ins['phone'] = $phone;
						$ins['role_id'] = $role_id;
						$ins['referral_id'] = $referral_id;
						$ins['reg_date'] = date(fdate);
						$user_id = $this->Crud->create('user', $ins);
						if($user_id > 0) {
							
							$link = site_url('auth/profile_view/') .$user_id;
							$qr = $this->qrcode($link);
							$path = $qr['file'];
							$this->Crud->updates('id', $user_id, 'user', array('qrcode'=>$path));
							///// store activities
							$code = $this->Crud->read_field('id', $user_id, 'user', 'username');
							$action = $code.' registered on ';
							$this->Crud->activity('authentication', $user_id, $action);
	
							$status = true;
							$msg = 'Successful!';
							$datas['email'] = $email;
							$code = 'success';
						
							$data['id'] = $user_id;
						} else {
							$msg = 'Oops! Try later';
						}
					}
				}
				
				
			}
		} else {
			// $msg = 'Missing field.';
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}

	// OTP
	public function otp($method='get') {
        $status = false;
        $data = array();
        $msg = '';
		$code = 'info';
		
		if($method == 'post') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$email = $call->email;
			$phone = $call->phone;

		
			if($this->Crud->check('phone', $phone, 'user') == 0){
				$msg = 'Invalid Account';

			} else{
				// generate or pick OTP
				$otp = $this->Crud->read_field('phone', $phone, 'user', 'otp');
				if(empty($otp)) {
					// renew otp
					$otp = substr(rand(), 0, 4);
					$otp_id = $this->Crud->updates('phone', $phone, 'user', array('otp'=>$otp));
					$send = true;
				}
				
				
				$api_key = $this->Crud->read_field('name', 'termil_api', 'setting', 'value'); // pick from DB
						
				if($phone) {
					$phone = '234'.substr($phone,1);
					$datass['to'] = $phone;
					$datass['from'] = 'N-Alert';
					$datass['sms'] = 'TIDREM OTP Code '.$otp.'. Do not share with anyone. ';
					$datass['api_key'] = $api_key;
					$datass['type'] = 'plain';
					$datass['channel'] = 'dnd';
					$this->Crud->termii('post', 'sms/send', $datass);
				}
				// send email
				if($email) {
					$data['email_address'] = $email;
					
					$msgs = 'TIDREM OTP Code '.$otp.'. Do not share with anyone.';
					$this->Crud->send_email($email, 'OTP Code', $msgs);
					
				}

				$status = true;
				$msg = 'OTP sent. Check your SMS';


			}
			
		}

		// verify OTP
		if($method == 'verify') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$phone = $call->phone;
			$otp = $call->otp;

			if($this->Crud->check2('phone', $phone, 'otp', $otp, 'user') > 0) {
				$status = true;
				$code = 'success';
				
                $msg = 'Successful!';
				$this->Crud->updates('phone', $phone, 'user', array('otp'=>'', 'activate'=>1));
			} else {
				$msg = 'Invalid OTP';
			}
		}

		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}

	// login
	public function login() {
	    $status = false;
		$data = array();
		$msg = '';
		$code = 'info';
		// collect call paramters
		$call = json_decode(file_get_contents("php://input"));
		$email = $call->email;
		$password = $call->password;
		
		if($email && $password) {
			$password = md5($call->password);
			$type = 'email';
		    $query = $this->Crud->read2('email', $email, 'password', $password, 'user');
			if(empty($query)) {
				$type = 'phone';
				$query = $this->Crud->read2('phone', $email, 'password', $password, 'user');

				
			}

		    if(empty($query)) {
		        $msg = 'Invalid Authentication!';
			} else {
				$act = $this->Crud->check2($type, $email, 'activate', 0, 'user');
				if ($act > 0) {
					$msg = 'Account not Activated, Please validate account';
				} else {
					$status = true;
					$msg = 'Login Successful!';
					$code = 'success';
					$id = $this->Crud->read_field($type, $email, 'user', 'id');

					$this->Crud->updates('id', $id, 'user', array('last_log'=> date(fdate)));
					///// store activities
					$codes = $this->Crud->read_field('id', $id, 'user', 'fullname');
					$action = $codes . ' logged in ';
					$this->Crud->activity('authentication', $id, $action);

					$data = $this->user_data($id);
				}
			}
		} else {
			$msg = 'Missing field(s).';
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}

	// reset code
	public function reset_code() {
		$status = false;
		$data = array();
		$msg = '';
		$code = 'info';
		
		// collect call paramters
		$call = json_decode(file_get_contents("php://input"));
		$email = $call->email;
		
		if($email) {
		    $user_id = $this->Crud->read_field('email', $email, 'user', 'id');
		    if(empty($user_id)) {
		        $msg = 'Invalid Email!';
		    } else {
				$reset = substr(md5(time().rand()), 0, 6);
				if($this->Crud->updates('id', $user_id, 'user', array('reset'=>$reset)) > 0) {
					
					$fullname = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
					$phone = $this->Crud->read_field('email', $email, 'user', 'phone');
		    
					// send email
					if($email) {
						$datas['email_address'] = $email;
						$datas['code'] = $reset;
						$reset_msg = 'Your Reset Code is '.$reset;
						$this->Crud->send_email($email, 'Reset Code', $reset_msg, $bcc='');
					}

					//Send SMS
						
					$api_key = $this->Crud->read_field('name', 'termil_api', 'setting', 'value'); // pick from DB
						
					if($phone) {
						$phone = '234'.substr($phone,1);
						$datass['to'] = $phone;
						$datass['from'] = 'N-Alert';
						$datass['sms'] = 'This is your Reset Code '.$reset.'. Do not share with anyone.';
						$datass['api_key'] = $api_key;
						$datass['type'] = 'plain';
						$datass['channel'] = 'dnd';
						$this->Crud->termii('post', 'sms/send', $datass);
					}

					$status = true;
					$msg = 'Reset Code sent to your Phone/Email!';
					$data['code'] = $reset;

				}
		    }
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg, 'code'=>$code, 'data'=>$data));
		die;
	}

	// reset password
	public function reset_password() {
		$status = false;
		$data = array();
		$msg = '';
		$code = 'info';
		
		// collect call paramters
		$call = json_decode(file_get_contents("php://input"));
		$email = $call->email;
		$password = $call->password;

		if(!empty($email) && !empty($password)) {
		    $user_id = $this->Crud->read_field('email', $email, 'user', 'id');
			if(empty($user_id)) {
		        $msg = 'Invalid Email!';
		    
		    } else {
				$msg = $user_id;
				if($this->Crud->updates('id', $user_id, 'user', array('password'=>md5($password))) > 0) {
					$status = true;
					$code = 'success';
					$msg = 'Password Reset Successfully!';

					///// store activities
					$code = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
					$action = $code.' reset Password ';
					$this->Crud->activity('authentication', $user_id, $action);

				} else {
					$msg = 'No Changes..';
				}
		    }
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}

	// profile
	public function profile( $type='get',$id) {
		$status = false;
		$data = array();
		$code = 'info';
		$msg = '';

		/// GET
		if($type == 'get') {
			$status = true;
			$data = $this->user_data($id);
			$msg = 'Successful';
		}

		/// UPDATE
		if($type == 'update') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$address = $call->address;
			$img_id = $call->img_id;
			$lga_id = $call->lga_id;
			if (!empty($call->password))
				$password = $call->password;

			// update profile
			$upd_data['address'] = $address;
			$upd_data['lga_id'] = $lga_id;
			$upd_data['passport'] = $img_id;
			if(!empty($password))$upd_data['password'] = $password;

			if($this->Crud->updates('id', $id, 'user', $upd_data) > 0) {
				$status = true;
				$code = 'success';
				$msg = 'Successful!';
				///// store activities
				$codes = $this->Crud->read_field('id', $id, 'user', 'fullname');
				$action = $codes . ' Updated Profile ';
				$this->Crud->activity('authentication', $id, $action);

				$data = $this->user_data($id);
			} else {
				$msg =  'No Changes';
			
			}
		}

		/// UPDATE
		if($type == 'bank') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$bank_code = $call->bank_code;
			$account = $call->account;
			$account_name = $call->account_name;
			
			if(empty($account_name)){
				$msg =  $this->Crud->web_msg('danger', 'Account Not Verified! Try Again');
				
			} else {
				$ins['code'] = $bank_code;
				$ins['bank'] = $this->Crud->read_field('code', $bank_code, 'bank', 'name');
				$ins['account'] = $account;
				$ins['name'] = $account_name;

				if($this->Crud->check('user_id', $id, 'account') > 0) {
					// update
					$this->Crud->updates('user_id', $id, 'account', $ins);
					$status = true;
					$code = 'success';
					$msg = 'Bank Account Updated Successfully!';

					///// store activities
					$codes = $this->Crud->read_field('id', $id, 'user', 'fullname');
					$action = $codes . ' Updated Bank Account Information ';
					$this->Crud->activity('authentication', $id, $action);

				} else {
					// create
					$ins['user_id'] = $id;
					$status = true;
					$code = 'success';
					$this->Crud->create('account', $ins);
					$msg =  'Bank Account Created Successfully!';

					///// store activities
					$codes = $this->Crud->read_field('id', $id, 'user', 'fullname');
					$action = $codes . ' Created Bank Account Information ';
					$this->Crud->activity('authentication', $id, $action);

				}
				
				
				//$this->Crud->updates('id', $id, 'user', array('otp'=>null));
				
			}
		}

		/// Security
		if($type == 'security') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$answer1 = $call->answer1;
			$answer2 = $call->answer2;
			$pin = $call->pin;
			
			// update profile
			$upd_data['answer1'] = $answer1;
			$upd_data['answer2'] = $answer2;
			$upd_data['pin'] = $pin;

			if($this->Crud->updates('id', $id, 'user', $upd_data) > 0) {
				$status = true;
				$code = 'success';
				$msg = 'Successful!';
				///// store activities
				$codes = $this->Crud->read_field('id', $id, 'user', 'fullname');
				$action = $codes . ' Updated Secuirty Pin ';
				$this->Crud->activity('authentication', $id, $action);

				$data = $this->user_data($id);
			} else {
				$msg =  'No Changes';
			
			}
		}

		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}

	// profile
	public function collections( $type='get',$id) {
		$status = false;
		$data = array();
		$code = 'info';
		$msg = '';

		/// GET
		if($type == 'get') {
			$status = true;
			$data = $this->user_data($id);
			$msg = 'Successful';
		}

		/// UPDATE
		if($type == 'health') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$middle = $call->middle;
			$dob = $call->dob;
			$gender = $call->gender;
			$plan = $call->plan;
			$price = $call->price;
			$duration = $call->duration;
			$price_duration = $call->price_duration;
			$pay_type = $call->pay_type;
			$passport = $call->passport;
			$family_info = $call->family_info;
			$family_no = $call->family_no;
			
			if($this->Crud->check('id', $id, 'user') == 0){
				$msg = 'Invalid User Id. Try again later';
			} else {
				// update profile
				$upd_data['middle_name'] = $middle;
				$upd_data['dob'] = $dob;
				$upd_data['gender'] = $gender;
				$upd_data['img_id'] = $passport;

				$up_data['user_id'] = $id;
				$up_data['sub_plan'] = $plan;
				$up_data['price'] = $price;
				$up_data['family_no'] = $family_no;
				$up_data['family_info'] = json_encode($family_info);
				$up_data['duration'] = $duration;
				$up_data['price_duration'] = $price_duration;
				$up_data['pay_type'] = $pay_type;

				if($this->Crud->check('user_id', $id, 'collection') == 0){
					$up_data['reg_date'] = date(fdate);
					if($this->Crud->create('collection', $up_data) > 0){
						$status = true;
						$code = 'success';
						$msg = 'Successful!';
						///// store activities
						$codes = $this->Crud->read_field('id', $id, 'user', 'fullname');
						$action = $codes . ' Subscribed to Health Insurance. ';
						$this->Crud->activity('authentication', $id, $action);

					}
				}

				if($this->Crud->updates('id', $id, 'user', $upd_data) > 0 || $this->Crud->updates('user_id', $id, 'collection', $up_data) > 0) {
						$status = true;
						$code = 'success';
						$msg = 'Successful!';
						///// store activities
						$codes = $this->Crud->read_field('id', $id, 'user', 'fullname');
						$action = $codes . ' Updated Health Insurance Application Information. ';
						$this->Crud->activity('authentication', $id, $action);
					
				} else {
					$msg =  'No Changes';
				
				}
			}
			
		}

		if($type == 'environment') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$address_no = $call->address_no;
			$environment_info = json_encode($call->environment_info);

			
			if($this->Crud->check('id', $id, 'user') == 0){
				$msg = 'Invalid User Id. Try again later';
			} else {
				// update profile
				$up_data['address_no'] = $address_no;
				$up_data['environment_info'] = $environment_info;

				if($this->Crud->check('user_id', $id, 'environment') == 0){
					$up_data['user_id'] = $id;
					$up_data['reg_date'] = date(fdate);
					if($this->Crud->create('environment', $up_data) > 0){
						$status = true;
						$code = 'success';
						$msg = 'Successful!';
						///// store activities
						$codes = $this->Crud->read_field('id', $id, 'user', 'fullname');
						$action = $codes . ' Subscribed to Environment Levy Service. ';
						$this->Crud->activity('collections', $id, $action);

					}
				} else{
					if($this->Crud->updates('user_id', $id, 'environment', $up_data) > 0) {
						$status = true;
						$code = 'success';
						$msg = 'Successful!';
						///// store activities
						$codes = $this->Crud->read_field('id', $id, 'user', 'fullname');
						$action = $codes . ' Updated Environment Levy Application Information. ';
						$this->Crud->activity('collections', $id, $action);
					
					} else {
						$msg =  'No Changes';
					
					}

				}

				
			}
			
		}

		

		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}

	// transaction
	public function transaction($type='get', $id = 0) {
	    $status = false;
		$msg = '';
		$code = 'info';
		$data = array();
		
		// get 
		if($type == 'get') {
			$limit = $this->request->getGet('limit');
			$offset = $this->request->getGet('offset');
			$start_date = $this->request->getGet('start_date');
			$end_date = $this->request->getGet('end_date');
			$search = $this->request->getGet('search');
			$log_id = $this->request->getGet('log_id');
			$type = $this->request->getGet('type');
			
			$query = $this->Crud->filter_transaction($limit, $offset, $log_id, $type, $search, $start_date, $end_date);
				
			if(!empty($query)) {
				$status = true;
				$msg = 'Successful';
				foreach($query as $q) {
					$item = array();

					$item['id'] = $q->id;
					$item['user_id'] = $q->user_id;
					$item['merchant_id'] = $q->merchant_id;
					$item['country_id'] = $q->country_id;
					$item['state_id'] = $q->state_id;
					$item['code'] = $q->code;
					$item['ref'] = $q->ref;
					$item['amount'] = $q->amount;
					$item['status'] = $q->status;
					$item['payment_type'] = $q->payment_type;
					$item['payment_method'] = $q->payment_method;
					$item['transaction_code'] = $q->transaction_code;
					$item['remark'] = $q->remark;
					$item['reg_date'] = $q->reg_date;
					
					$data[] = $item;
				}
			}
		}
		
		// send code
		if($type == 'sms') {
		    $call = json_decode(file_get_contents("php://input"));
		    $user_id = $call->user_id;
		    $code_id = $call->code_id;
			$phones = $call->phone;
			$balance = 0;
			$earnings = 0;
			$withdrawns = 0;
			
		    if(!empty($code_id)) {
				if($this->Crud->check2('id', $code_id, 'used_by >', 0, 'voucher') > 0){
					$msg = 'Cash Code is already Used';
				} else{
					$query = $this->Crud->read_single('user_id', $id, 'wallet');
					if(!empty($query)) {
						foreach($query as $q) {
							if($q->type == 'credit') {
								$earnings += (float)$q->amount;
							} else {
								$withdrawns += (float)$q->amount;
							}
						}
						$balance = $earnings - $withdrawns;
					}
					if($balance < 5){
						$msg = 'Minimum of N5 in wallet balance to send this SMS.';
					} else {
						$code = $this->Crud->read_field('id', $code_id, 'voucher', 'code');
						
						$user_country = $this->Crud->read_field('id', $id,'user', 'country_id');
						$user_state = $this->Crud->read_field('id', $id,'user', 'state_id');
						$codes = 'REF-'.substr(rand(),0,3).substr(rand(),0,4).substr(rand(),0,3);
			
						//Debits Sender Wallet
						$v_ins['user_id'] = $id;
						$v_ins['type'] = 'debit';
						$v_ins['amount'] = 5;
						$v_ins['item'] = 'transact';
						$v_ins['item_id'] = $id;
						$v_ins['country_id'] = $user_country;
						$v_ins['state_id'] = $user_state;
						$v_ins['remark'] = 'Cash Code Sms Charge';
						$v_ins['reg_date'] = date(fdate);
						$w_id = $this->Crud->create('wallet', $v_ins);

						$ins['user_id'] = $id;
						$ins['merchant_id'] = 0;
						$ins['amount'] = 5;
						$ins['country_id'] = $user_country;
						$ins['remark'] = 'Debit Cash Code Sms Charge. Sent to '.$phones;
						$ins['state_id'] = $user_state;
						$ins['code'] = $codes;
						$ins['transaction_code'] = $code;
						$ins['payment_type'] = 'sms';
						$ins['status'] = $status;
						$ins['reg_date'] = date(fdate);
						$ins_id = $this->Crud->create('transaction', $ins);
							
					
						$codesa = $this->Crud->read_field('id', $id, 'user', 'username');
						$action = $codesa.' Sent SMS of Cash Code';
						$this->Crud->activity('transaction', $w_id, $action);

						//Credit TiDREM Cash Wallet
						$admin = $this->Crud->read_field('username', 'TiDREM CASH ADMIN', 'user', 'id');
						$v_ins['user_id'] = $admin;
						$v_ins['type'] = 'credit';
						$v_ins['amount'] = 5;
						$v_ins['item'] = 'transact';
						$v_ins['item_id'] = $id;
						$v_ins['country_id'] = $user_country;
						$v_ins['state_id'] = $user_state;
						$v_ins['remark'] = 'Cash Code Sms Charge';
						$v_ins['reg_date'] = date(fdate);
						$v_id = $this->Crud->create('wallet', $v_ins);

						$ins['user_id'] = $admin;
						$ins['merchant_id'] = 0;
						$ins['amount'] = 5;
						$ins['country_id'] = $user_country;
						$ins['remark'] = 'Credit Cash Code Sms Charge';
						$ins['state_id'] = $user_state;
						$ins['code'] = $codes;
						$ins['transaction_code'] = $code;
						$ins['payment_type'] = 'sms';
						$ins['status'] = $status;
						$ins['reg_date'] = date(fdate);
						$ins_id = $this->Crud->create('transaction', $ins);
					
						$codesa = $this->Crud->read_field('id', $admin, 'user', 'username');
						$action = $codesa.' Received SMS Charge on Cash Code Sent';
						$this->Crud->activity('transaction', $v_id, $action);


						$amount = $this->Crud->read_field('id', $code_id, 'voucher', 'amount');
						$phone = $this->Crud->read_field('id', $user_id, 'user', 'phone');
						$remark = $this->Crud->read_field('code', $code, 'transaction', 'remark');
			
						if(empty($phone)){
							$phone = $phones;
						}
						$user = $this->Crud->read_field('id', $id, 'user', 'username');
						$api_key = $this->Crud->read_field('name', 'termil_api', 'setting', 'value'); // pick from DB
						
						if($phone) {
							$phone = '234'.substr($phone,1);
							$link = site_url('payments/index/deposit/code/'.$code);
							$datass['to'] = $phone;
							$datass['from'] = 'N-Alert';
							$datass['sms'] = $user.' just sent you a TiDREM Cash Code valued at N'.number_format($amount,2).'. Your Cash Code is '.$code.'.'.ucwords($remark).' Click '.$link.' to Cash Code';
							$datass['api_key'] = $api_key;
							$datass['type'] = 'plain';
							$datass['channel'] = 'dnd';
							$this->Crud->termii('post', 'sms/send', $datass);
						}
		
						$status = true;
						$code = 'success';
						$msg = 'Cash Code Sent';
					}
					
				}
		      
			}
		}
		// recall code
		if($type == 'recall') {
		    $call = json_decode(file_get_contents("php://input"));
		    $code_id = $call->code_id;
		    
		    if(!empty($code_id)) {
				if($this->Crud->check2('id', $code_id, 'used_by >', 0, 'voucher') > 0){
					$msg = 'Cash Code is already Used';
				} else{
					$user_country = $this->Crud->read_field('id', $id,'user', 'country_id');
					$user_state = $this->Crud->read_field('id', $id,'user', 'state_id');

					$amount = $this->Crud->read_field('id', $code_id, 'voucher', 'amount');

					//Credit wallet of user 
					$user_ins['user_id'] = $id;
					$user_ins['type'] = 'credit';
					$user_ins['amount'] = $amount;
					$user_ins['item'] = 'Cash Code';
					$user_ins['country_id'] = $user_country;
					$user_ins['state_id'] = $user_state;
					$user_ins['item_id'] = $id;
					$user_ins['remark'] = 'Transactin Code Recalled';
					$user_ins['reg_date'] = date(fdate);
					$user_ids = $this->Crud->create('wallet', $user_ins);

					$del_id = $this->Crud->deletes('id', $code_id, 'voucher');
					if($del_id > 0){
						$ref = 'RF-'.substr(rand(),0,3).substr(rand(),0,4).substr(rand(),0,3);
						$ins['user_id'] = $id;
						$ins['merchant_id'] = 0;
						$ins['amount'] = $amount;
						$ins['country_id'] = $user_country;
						$ins['state_id'] = $user_state;
						$ins['code'] = $ref;
						$ins['payment_type'] = 'transact';
						$ins['status'] = $status;
						$ins['reg_date'] = date(fdate);
						$ins_id = $this->Crud->create('transaction', $ins);

						if($ins_id > 0){
							$status = true;
							$code = 'success';
							$msg = 'Cash Code Recalled Successfully';
						} else{
							$msg = 'PLease Try Again';
							}

					}
					
				}
		      
			}
		}
		
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg, 'code'=>$code, 'data'=>$data));
		die;
	}

	// wallet
	public function wallet($type='get', $id = 0) {
	    $status = false;
		$msg = '';
		$code = 'info';
		$data = array();
		
		// get 
		if($type == 'get') {
			$limit = $this->request->getGet('limit');
			$offset = $this->request->getGet('offset');
			$start_date = $this->request->getGet('start_date');
			$end_date = $this->request->getGet('end_date');
			$search = $this->request->getGet('search');
			$log_id = $this->request->getGet('log_id');
			$type = $this->request->getGet('type');
			$transact = $this->request->getGet('transact');
			
			$query = $this->Crud->filter_wallet($limit, $offset, $log_id, $type,$transact, $search, $start_date, $end_date);
				
			if(!empty($query)) {
				$status = true;
				$msg = 'Successful';
				foreach($query as $q) {
					$item = array();

					$item['id'] = $q->id;
					$item['user_id'] = $q->user_id;
					$item['type'] = $q->type;
					$item['item'] = $q->item;
					$item['remark'] = $q->remark;
					$item['amount'] = $q->amount;
					$item['reg_date'] = $q->reg_date;
					
					$data[] = $item;
				}
			}
		}
		
		// send code
		if($type == 'sms') {
		    $call = json_decode(file_get_contents("php://input"));
		    $user_id = $call->user_id;
		    $code_id = $call->code_id;
			$phones = $call->phone;
			$balance = 0;
			$earnings = 0;
			$withdrawns = 0;
			
		    if(!empty($code_id)) {
				if($this->Crud->check2('id', $code_id, 'used_by >', 0, 'voucher') > 0){
					$msg = 'Cash Code is already Used';
				} else{
					$query = $this->Crud->read_single('user_id', $id, 'wallet');
					if(!empty($query)) {
						foreach($query as $q) {
							if($q->type == 'credit') {
								$earnings += (float)$q->amount;
							} else {
								$withdrawns += (float)$q->amount;
							}
						}
						$balance = $earnings - $withdrawns;
					}
					if($balance < 5){
						$msg = 'Minimum of N5 in wallet balance to send this SMS.';
					} else {
						$code = $this->Crud->read_field('id', $code_id, 'voucher', 'code');
						
						$user_country = $this->Crud->read_field('id', $id,'user', 'country_id');
						$user_state = $this->Crud->read_field('id', $id,'user', 'state_id');
						$codes = 'REF-'.substr(rand(),0,3).substr(rand(),0,4).substr(rand(),0,3);
			
						//Debits Sender Wallet
						$v_ins['user_id'] = $id;
						$v_ins['type'] = 'debit';
						$v_ins['amount'] = 5;
						$v_ins['item'] = 'transact';
						$v_ins['item_id'] = $id;
						$v_ins['country_id'] = $user_country;
						$v_ins['state_id'] = $user_state;
						$v_ins['remark'] = 'Cash Code Sms Charge';
						$v_ins['reg_date'] = date(fdate);
						$w_id = $this->Crud->create('wallet', $v_ins);

						$ins['user_id'] = $id;
						$ins['merchant_id'] = 0;
						$ins['amount'] = 5;
						$ins['country_id'] = $user_country;
						$ins['remark'] = 'Debit Cash Code Sms Charge. Sent to '.$phones;
						$ins['state_id'] = $user_state;
						$ins['code'] = $codes;
						$ins['transaction_code'] = $code;
						$ins['payment_type'] = 'sms';
						$ins['status'] = $status;
						$ins['reg_date'] = date(fdate);
						$ins_id = $this->Crud->create('transaction', $ins);
							
					
						$codesa = $this->Crud->read_field('id', $id, 'user', 'username');
						$action = $codesa.' Sent SMS of Cash Code';
						$this->Crud->activity('transaction', $w_id, $action);

						//Credit TiDREM Cash Wallet
						$admin = $this->Crud->read_field('username', 'TiDREM CASH ADMIN', 'user', 'id');
						$v_ins['user_id'] = $admin;
						$v_ins['type'] = 'credit';
						$v_ins['amount'] = 5;
						$v_ins['item'] = 'transact';
						$v_ins['item_id'] = $id;
						$v_ins['country_id'] = $user_country;
						$v_ins['state_id'] = $user_state;
						$v_ins['remark'] = 'Cash Code Sms Charge';
						$v_ins['reg_date'] = date(fdate);
						$v_id = $this->Crud->create('wallet', $v_ins);

						$ins['user_id'] = $admin;
						$ins['merchant_id'] = 0;
						$ins['amount'] = 5;
						$ins['country_id'] = $user_country;
						$ins['remark'] = 'Credit Cash Code Sms Charge';
						$ins['state_id'] = $user_state;
						$ins['code'] = $codes;
						$ins['transaction_code'] = $code;
						$ins['payment_type'] = 'sms';
						$ins['status'] = $status;
						$ins['reg_date'] = date(fdate);
						$ins_id = $this->Crud->create('transaction', $ins);
					
						$codesa = $this->Crud->read_field('id', $admin, 'user', 'username');
						$action = $codesa.' Received SMS Charge on Cash Code Sent';
						$this->Crud->activity('transaction', $v_id, $action);


						$amount = $this->Crud->read_field('id', $code_id, 'voucher', 'amount');
						$phone = $this->Crud->read_field('id', $user_id, 'user', 'phone');
						$remark = $this->Crud->read_field('code', $code, 'transaction', 'remark');
			
						if(empty($phone)){
							$phone = $phones;
						}
						$user = $this->Crud->read_field('id', $id, 'user', 'username');
						$api_key = $this->Crud->read_field('name', 'termil_api', 'setting', 'value'); // pick from DB
						
						if($phone) {
							$phone = '234'.substr($phone,1);
				
							$datass['to'] = $phone;
							$datass['from'] = 'N-Alert';
							$datass['sms'] = $user.' just sent you a TiDREM Cash CC valued at N'.number_format($amount,2).'. Your Cash Code is '.$code.'.'.ucwords($remark);
							$datass['api_key'] = $api_key;
							$datass['type'] = 'plain';
							$datass['channel'] = 'dnd';
							$this->Crud->termii('post', 'sms/send', $datass);
						}
		
						$status = true;
						$code = 'success';
						$msg = 'Cash Code Sent';
					}
					
				}
		      
			}
		}
		// recall code
		if($type == 'recall') {
		    $call = json_decode(file_get_contents("php://input"));
		    $code_id = $call->code_id;
		    
		    if(!empty($code_id)) {
				if($this->Crud->check2('id', $code_id, 'used_by >', 0, 'voucher') > 0){
					$msg = 'Cash Code is already Used';
				} else{
					$user_country = $this->Crud->read_field('id', $id,'user', 'country_id');
					$user_state = $this->Crud->read_field('id', $id,'user', 'state_id');

					$amount = $this->Crud->read_field('id', $code_id, 'voucher', 'amount');

					//Credit wallet of user 
					$user_ins['user_id'] = $id;
					$user_ins['type'] = 'credit';
					$user_ins['amount'] = $amount;
					$user_ins['item'] = 'Cash Code';
					$user_ins['country_id'] = $user_country;
					$user_ins['state_id'] = $user_state;
					$user_ins['item_id'] = $id;
					$user_ins['remark'] = 'Transactin Code Recalled';
					$user_ins['reg_date'] = date(fdate);
					$user_ids = $this->Crud->create('wallet', $user_ins);

					$del_id = $this->Crud->deletes('id', $code_id, 'voucher');
					if($del_id > 0){
						$ref = 'RF-'.substr(rand(),0,3).substr(rand(),0,4).substr(rand(),0,3);
						$ins['user_id'] = $id;
						$ins['merchant_id'] = 0;
						$ins['amount'] = $amount;
						$ins['country_id'] = $user_country;
						$ins['state_id'] = $user_state;
						$ins['code'] = $ref;
						$ins['payment_type'] = 'transact';
						$ins['status'] = $status;
						$ins['reg_date'] = date(fdate);
						$ins_id = $this->Crud->create('transaction', $ins);

						if($ins_id > 0){
							$status = true;
							$code = 'success';
							$msg = 'Cash Code Recalled Successfully';
						} else{
							$msg = 'PLease Try Again';
							}

					}
					
				}
		      
			}
		}
		
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg, 'code'=>$code, 'data'=>$data));
		die;
	}

	// wallet
	public function payments($type='get', $id=0) {
	    $status = false;
		$msg = '';
		$cod = 'info';
		$data = array();
		$admin = $this->Crud->read_field('fullname', 'TiDREM ADMIN', 'user', 'id');
			
		$phone = $this->Crud->read_field('id', $id, 'user', 'phone');
		$email = $this->Crud->read_field('id', $id, 'user', 'email');

		
		if($type == 'sms'){
			$call = json_decode(file_get_contents("php://input"));
			// 	
			$api_key = $call->api_key;
			$to = $call->to;
			$from = $call->from;
			$sms = $call->sms;
			$type = $call->type;
			$channel = $call->channel;
			// $phone = '07031549500';
            // $api_key = $this->Crud->read_field('name', 'termil_api', 'setting', 'value'); // pick from DB
						
            if($to) {
                $phone = '234'.substr($to,1);
                $datass['to'] = $phone;
                $datass['from'] = $from;
                $datass['sms'] = $sms;
                $datass['api_key'] = $api_key;
                $datass['type'] = $type;
                $datass['channel'] = $channel;
              	$msg =  $this->Crud->termii('post', 'sms/send', $datass);
            }
		}
		// get 
		if($type == 'get') {
		    $call = json_decode(file_get_contents("php://input"));
		    $user_id = $call->user_id;
		    
		    if(!empty($user_id)) {
		        $query = $this->Crud->read_single('user_id', $user_id, 'wallet');
		        if(!empty($query)) {
		            $status = true;
		            $msg = 'Successful';
		            foreach($query as $q) {
		                $item = array();
		                
		                if($q->type == 'credit') {
		                    $earnings += (float)$q->amount;
		                } else {
		                    $withdrawns += (float)$q->amount;
		                }
		                
		                $item['id'] = $q->id;
		                $item['type'] = $q->type;
		                $item['remark'] = $q->remark;
		                $item['amount'] = number_format((float)$q->amount, 2);
		                $item['date'] = date('M d, Y h:s A', strtotime($q->reg_date));
		                
		                $data[] = $item;
		            }
		            
		            $balance = $earnings - $withdrawns;
		            
		            $earnings = number_format($earnings, 2);
		            $withdrawns = number_format($withdrawns, 2);
					$balance = number_format($balance, 2);
		        }
		    }
		    
		}
		// deposit
		if($type == 'deposit') {
		    $call = json_decode(file_get_contents("php://input"));
			$payment_method = $call->payment_method;
		    $ref = $call->ref;
			$remark = $call->remark;
		    $amount = $call->amount;
						

		    if(!empty($id) && !empty($amount)) {
	
				if($payment_method == 'bank'){
					$user_country = $this->Crud->read_field('id', $id,'user', 'country_id');
					$user_territory = $this->Crud->read_field('id', $id,'user', 'territory');
					$user_lga = $this->Crud->read_field('id', $id,'user', 'lga_id');

					

					//Save in Transaction Table
					if($id > 0){
						$ins['user_id'] = $id;
						$ins['amount'] = $amount;
						$ins['territory'] = $user_territory;
						$ins['remark'] = $remark;
						$ins['lga_id'] = $user_lga;
						$ins['ref'] = $ref;
						$ins['payment_type'] = 'wallet';
						$ins['payment_method'] = $payment_method;
						$ins['status'] = 'completed';
						$ins['reg_date'] = date(fdate);
						$ins_id = $this->Crud->create('transaction', $ins);

						//Credit wallet of user
						$user_ins['user_id'] = $id;
						$user_ins['type'] = 'credit';
						$user_ins['amount'] = $amount;
						$user_ins['item'] = 'wallet_fund';
						$user_ins['lga_id'] = $user_lga;
						$user_ins['territory'] = $user_territory;
						$user_ins['item_id'] = $ins_id;
						$user_ins['remark'] = 'Wallet Credited using Bank Transfer';
						$user_ins['reg_date'] = date(fdate);
						$user_ids = $this->Crud->create('wallet', $user_ins);

						
						$codesa = $this->Crud->read_field('id', $id, 'user', 'fullname');
						$action = $codesa.' Account Deposited with N'.number_format($amount,2).' using Bank Transfer';
						$this->Crud->activity('transaction', $id, $action);

						$content = 'Wallet Credited with N'.number_format($amount);
						$this->notify($admin, $id, $content, $type, $ins_id);
						
						
						if($ins_id > 0){
							$status = true;
							$code = 'success';
							$msg = 'Transaction Successful';
						} else{
							$msg = 'Please Try Again';
						}

					}
					
				
				}
		        
		    }
		}

		if($type == 'transaction'){
			$call = json_decode(file_get_contents("php://input"));
			$payment_method = $call->payment_method;
		    $ref = rand();
			$remark = $call->remark;
			$session_id = $call->session_id;
		    $amount = $call->amount;
		    $trans_date = $call->trans_date;

			// echo $amount.' ';

			$msg = ' ';
			if(!empty($id) && !empty($amount)){
				if($this->Crud->check('id', $id, 'user') > 0){
					if($this->Crud->check('session_id', $session_id, 'history') == 0){
						$t_data['user_id'] = $id;
						$t_data['territory'] = $this->Crud->read_field('id', $id, 'user', 'territory');
						$t_data['lga_id'] = $this->Crud->read_field('id', $id, 'user', 'lga_id');
						$t_data['payment_method'] = $payment_method;
						$t_data['remark'] = $remark;
						$t_data['ref'] = $ref;
						$t_data['session_id'] = $session_id;
						$t_data['amount'] = $amount;
						$t_data['reg_date'] = $trans_date;
						$msg .= $this->Crud->create('history', $t_data);
					}
				}
			}
		}
		
		if($type == 'pay_tax'){
			$call = json_decode(file_get_contents("php://input"));
			$payment_method = $call->payment_method;
		    $ref = $call->ref;
			$remark = $call->remark;
		    $total_amount = $call->amount;

			$msg = ' ';
			if(!empty($id) && !empty($total_amount)){
				if(empty($this->Crud->read_field('id', $id, 'user', 'duration'))){
					$trade = $this->Crud->read_field('id', $id, 'user', 'trade');
					$duration = 'daily';

					$datas['duration'] = $duration;
					if(!empty($trade))$datas['trade'] = $trade;
					
					$add = $this->Crud->updates('id', $id, 'user', $datas);
					if($add > 0){
						$id = $id;
						$tax_data['user_id'] = $id;
						$tax_data['territory'] = $this->Crud->read_field('id', $id, 'user', 'territory');
						$tax_data['lga_id'] = $this->Crud->read_field('id', $id, 'user', 'lga_id');
						$trade = $this->Crud->read_field('id', $id, 'user', 'trade');
						$trade_type = $this->Crud->read_field('id', $trade, 'trade', 'medium');
						$duration = $this->Crud->read_field('id', $id, 'user', 'duration');
						$tax_data['amount'] = $this->Crud->trade_duration($trade_type, $duration);
						$tax_data['balance'] = $this->Crud->trade_duration($trade_type, $duration);	
						$tax_data['reg_date'] = date(fdate);
						$tax_data['payment_method'] = 'bank';
						$tax_data['remark'] = 'Tax Payment';
						$tax_data['payment_type'] = 'tax';
						$days = "day"; $durs = '365';
						if($duration == 'weekly')$days = "week";$durs = '52';
						if($duration == 'monthly')$days = "month";$durs = '12';
		
						// if($role == 'personal' || $role == 'business'){
						for ($i = 0; $i < $durs; $i++) {
							$tax_data['payment_date'] = date('Y-m-d', strtotime(date(fdate).'+'.$i.' '.$days));
							$ins = $this->Crud->create('transaction', $tax_data);
						}
						// }
						///// store activities
						$code = $this->Crud->read_field('id', $id, 'user', 'fullname');
						$action = $code.' created Payment Profile and Updated Profile ';
						$this->Crud->activity('profile', $id, $action);
		
		
						echo $this->Crud->msg('success', translate_phrase('Profile Settings Updated'));
						// echo '<script>window.location.replace("'.site_url('dashboard').'");</script>';
						
					} 
				}	
				if($this->Crud->check('id', $id, 'user') > 0){
					$phone = $this->Crud->read_field('id', $id, 'user', 'phone');
					$email = $this->Crud->read_field('id', $id, 'user', 'email');
					$fullname = $this->Crud->read_field('id', $id, 'user', 'fullname');
					
					$tax = $this->Crud->reads3('user_id', $id, 'payment_type', 'tax', 'status', 'pending', 'transaction');
					
					if(!empty($tax)){
						$total_bal = $call->amount;
						//Pay Pending Payment First
						foreach($tax as $t){
							$tax_id = $t->id;
							$pays_date = $t->payment_date;
							$bal = $t->balance;
							$ref = rand();
							

							if($total_bal > 0 ){
								if($total_amount >= $bal){

									$total_amount -= (float)$bal;
									$bala = $bal;
									$bals= '';
									$t_data['balance'] = 0;
									$t_data['status'] = 'paid';
									$t_data['paid_date'] = date(fdate);
								} else {
									$bala = $total_amount;
									$bal = $bal - $total_amount;
									$t_data['balance'] = $bal;
									$bals = 'Balance of N'.number_format($bal,2);
									$t_data['status'] = 'pending';
									$t_data['paid_date'] = date(fdate);
									
								}
								$t_data['payment_method'] = $payment_method;
								$upda = $this->Crud->updates('id', $tax_id, 'transaction', $t_data);

								//Create payment in payment table
								
								$pay_data['amount'] = $bala;
								$pay_data['reference'] = $ref;
								$pay_data['transaction_id'] = $tax_id;
								$pay_data['reg_date'] = date(fdate);
								$this->Crud->create('payment', $pay_data);

								$first_msg = '';
								//Send Notification to the Tax Payer
								if($upda > 0){
									$first_msg .= 'Dear '.ucwords($fullname).', your tax payment of N'.number_format($bala,2).' to the Delta State Government for '.$pays_date.' was successful. Your Payment Reference is {'.$ref.'}. '.$bals;
									$api_key = $this->Crud->read_field('name', 'termil_api', 'setting', 'value'); // pick from DB
							
									if($phone) {
										$phone = '234'.substr($phone,1);
										$datass['to'] = $phone;
										$datass['from'] = 'N-Alert';
										$datass['sms'] = $first_msg;
										$datass['api_key'] = $api_key;
										$datass['type'] = 'plain';
										$datass['channel'] = 'dnd';
										$this->Crud->termii('post', 'sms/send', $datass);
									}
									// send email
									if($email) {
										$data['email_address'] = $email;
										$this->Crud->send_email($email, 'Tax Payments', $first_msg);
									}
									$this->notify('0', $id, $first_msg, 'payment', $upda);

								}
							}

							$total_bal -= (float)$t->balance;
						}

					}
				}
			}
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg, 'code'=>$cod, 'data'=>$data));
		die;
	}

	

	// virtual account
	public function virutal_account($type='get', $id) {
		$status = false;
		$msg = '';
		$code = 'info';
		$data = array();

		if($type == 'get') {
			if($id) {
				$check = $this->Crud->check('user_id', $id, 'virtual_account');
				if($check <= 0) {
					$fullname = $this->Crud->read_field('id', $id, 'user', 'fullname');
					$phone = $this->Crud->read_field('id', $id, 'user', 'phone');
					$bvn = $this->Crud->read_field('name', 'squad_bvn', 'setting', 'value');
					$acc = $this->Crud->read_field('name', 'squad_account', 'setting', 'value');

					// create virtual account
					$apiData['bvn'] = $bvn;
					$apiData['business_name'] = $fullname;
					$apiData['customer_identifier'] = $id;
					$apiData['mobile_num'] = $phone;
					// $apiData['beneficiary_account'] = $acc;
					$resp = $this->Crud->squad('post', 'virtual-account/business', $apiData);
					// $msg = $resp;
					$res = json_decode($resp);
					if($res->success == true) {
						$ins_data['user_id'] = $id;
						$ins_data['acc_no'] = $res->data->virtual_account_number;
						$ins_data['response'] = $resp;
						$ins_data['reg_date'] = date(fdate);
						$this->Crud->create('virtual_account', $ins_data);
					}
				}
				

				// read virtual account
				$acc_no = $this->Crud->read_field('user_id', $id, 'virtual_account', 'acc_no');
				if(!empty($acc_no)) {
					$status = true;
					$msg = 'Successful';
					$code = 'success';
					$data = $acc_no;
				} else {
					$msg = 'Failed';
					$code = 'danger';
					$data = '';
				}
			}
		}

		echo json_encode(array('status'=>$status, 'msg'=>$msg, 'code'=>$code, 'data'=>$data));
		die;
	}

	// activity
	public function activity($type='get', $id=0) {
	    $status = false;
		$msg = '';
		$code = '';
		$data = array();
		
		// get 
		if($type == 'get') {
		    if(!empty($id)) {
				$limit = $this->request->getGet('limit');
				$offset = $this->request->getGet('offset');
				$search = $this->request->getGet('search');
				
				$query = $this->Crud->filter_activity($limit, $offset, $id, $search);
				
				if(!empty($query)) {
		            $status = true;
		            $msg = 'Successful';
		            foreach($query as $q) {
		                $item = array();

		                $item['id'] = $q->id;
						$item['item'] = $q->item;
		                $item['item_id'] = $q->item_id;
		                $item['action'] = $q->action;
		                $item['reg_date'] = $q->reg_date;
		                
		                $data[] = $item;
		            }
		        }
		    }
		    
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}

	// voucher
	public function voucher($type='get', $id=0) {
	    $status = false;
		$msg = '';
		$code = '';
		$data = array();
		// get 
		if($type == 'get') {
			$limit = $this->request->getGet('limit');
			$offset = $this->request->getGet('offset');
			$start_date = $this->request->getGet('start_date');
			$end_date = $this->request->getGet('end_date');
			$search = $this->request->getGet('search');
			$log_id = $this->request->getGet('log_id');
			
			$query = $this->Crud->filter_voucher($limit, $offset, $log_id, $search, $start_date, $end_date) ;
			
			if(!empty($query)) {
				$status = true;
				$msg = 'Successful';
				foreach($query as $q) {
					$item = array();

					$item['id'] = $q->id;
					$item['code'] = $q->code;
					$item['amount'] = $q->amount;
					$item['user_id'] = $q->user_id;
					$item['used_date'] = $q->used_date;
					$item['used_by'] = $q->used_by;
					$item['reg_date'] = $q->reg_date;
					
					$data[] = $item;
				}
			}
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}

	public function zend($type='get'){
		$status = false;
		$msg = '';
		$code = '';
		$data = array();
		
		// get 
		if($type == 'validate_account') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$account_number = $call->account_number;
			
			if(empty($account_number)){
				$msg = 'Account Number is Empty';
				$code = 'danger';
			} else {
				if($this->Crud->check('acc_no', $account_number, 'virtual_account') == 0){
					$code = 'danger';
					$msg = 'Invalid Account Number';

				} else {
					$user_id = $this->Crud->read_field('acc_no', $account_number, 'virtual_account', 'user_id');
					// echo $user_id;
					if($this->Crud->check('id', $user_id, 'user') == 0){
						$code = 'danger';
						$msg = 'Account Number does not Exist';
					} else {
						$status = true;
						$msg = 'Account Number Validated';

						$item = array();

						$item['account_name'] = strtoupper($this->Crud->read_field('id', $user_id, 'user', 'fullname'));
		                $item['account_number'] = $account_number;
		                
		                $data[] = $item;

					}
				}
				
			}
		}

		if($type == 'tax_status') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$account_number = $call->account_number;
			
			if(empty($account_number)){
				$msg = 'Account Number is Empty';
				$code = 'danger';
			} else {
				if($this->Crud->check('acc_no', $account_number, 'virtual_account') == 0){
					$code = 'danger';
					$msg = 'Invalid Account Number';

				} else {
					$user_id = $this->Crud->read_field('acc_no', $account_number, 'virtual_account', 'user_id');
					// echo $user_id;
					if($this->Crud->check('id', $user_id, 'user') == 0){
						$code = 'danger';
						$msg = 'Account Number does not Exist';
					} else {
						$status = true;
						$next_pay = $this->Crud->read_fields2('user_id', $user_id, 'payment_type', 'tax', 'transaction', 'payment_date');
						$last_paid = $this->Crud->read_fields2('user_id', $user_id, 'status', 'paid', 'transaction', 'id');
						$transact = $this->Crud->reads3('user_id', $user_id, 'payment_type', 'tax', 'status', 'pending', 'transaction', 5);
						$amount = 0;$trans =0;$date='';

						$pends = [];
						if(!empty($last_paid)){
							$pend['amount'] = $this->Crud->read_field('id', $last_paid, 'transaction', 'amount');
							$pend['balance'] = $this->Crud->read_field('id', $last_paid, 'transaction', 'balance');
							$pend['payment_date'] = $this->Crud->read_field('id', $last_paid, 'transaction', 'payment_date');

							$pends[] = $pend;
							
						}
						if(!empty($transact)){
							foreach($transact as $t){
								
								$pend = array();
								$amount += (int)$t->amount;
								$trans++;
								$date .= $t->payment_date.',';

								$pend['amount'] = $t->amount;
								$pend['balance'] = $t->balance;
								$pend['payment_date'] = $t->payment_date;
								
								$pends[] = $pend;
							}
						}
						if(date(fdate) > $next_pay){

							$code = 'info';
							$msg = 'You Have '.$trans.' Outstanding Tax Payment of N'.number_format($amount,2);
						} else {
							$code = 'success';
							$msg = 'Your Tax Payment is up to Date. ';
						}
						

						$item = array();
						
						$img = ($this->Crud->read_field('id', $user_id, 'user', 'passport'));
						if(empty($img) && !file_exists($img)){
						    $img = 'assets/images/avatar.png';
						}

						$item['account_name'] = strtoupper($this->Crud->read_field('id', $user_id, 'user', 'fullname'));
						$item['account_passport'] = site_url($img);
		                $item['tax_id'] = $account_number;
		                $item['pend_payment'] = $pends;

		                $data[] = $item;

					}
				}
				
			}
		}


		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}

	public function zends($type='get'){
		$data = array();
		$data['responseCode'] = "001";
		$data['responseMessage'] = "failed";
		
		
		if($type == 'account_check') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$account_number = $call->referenceNo;
			
			if(empty($account_number)){
				$data['messageDesc'] = "Tax ID is Empty";
			} else {
				if($this->Crud->check('acc_no', $account_number, 'virtual_account') == 0){
					$data['messageDesc'] = "Invalid Tax ID";
				} else {
					$user_id = $this->Crud->read_field('acc_no', $account_number, 'virtual_account', 'user_id');
					// echo $user_id;
					if($this->Crud->check('id', $user_id, 'user') == 0){
						$data['messageDesc'] = "Tax ID does not Exist";
					} else {
						$total_amount = 0;
						$outstand_amount = 0;
						
						$p_date = [];
						$outstand_details = [];
						$pays = $this->Crud->reads2('user_id', $user_id, 'status', 'pending', 'transaction');
						if($pays){
							foreach($pays as $p){
								$outstand = [];
								if(date(fdate) > $p->payment_date){
									$outstand_amount += (float)$p->balance;
									$outstand['amount'] = number_format($p->balance, 2);
									$outstand['dueDate'] = date('d M Y', strtotime($p->payment_date));
								} else {
									$p_date[] = $p->payment_date;
									continue;
								}

								$outstand_details[] = $outstand;
							}
						}
						
						$next_payment = $this->Crud->getClosestFutureDate($p_date);
						$next_amount = $this->Crud->read_fields3('user_id', $user_id, 'payment_date', $next_payment, 'status', 'pending', 'transaction', 'balance');
						$total_amount += (float)$outstand_amount;

						$data['responseCode'] = "000";
						$data['oustandingPayment'] = $outstand_details;
						$data['responseMessage'] = "success";
						$data['messageDesc'] = "Tax ID Validated";
		                $data['customerId'] = $account_number;
						$data['customerName'] = strtoupper($this->Crud->read_field('id', $user_id, 'user', 'fullname'));
		                $data['amountToPay'] = number_format(((float)$next_amount + (float)$outstand_amount),2);
						$data['paymentDetails'] = "Tax Payments";
						$data['customerMobile'] = strtoupper($this->Crud->read_field('id', $user_id, 'user', 'phone'));
						$data['customerEmail'] = strtolower($this->Crud->read_field('id', $user_id, 'user', 'email'));
						$data['nextPaymentDate'] = $next_payment;
						$data['nextPaymentAmount'] = number_format((float)$next_amount,2);
						
		                
					}
				}
				
			}
		}

		if($type == 'transaction'){
			// Retrieve the request's body
			$input = @file_get_contents("php://input");
			$body = json_decode($input);
			
			if(empty($body)){
				//rejected Transaction
				$data['responseCode'] = '002';
				$data['responseMessage'] = 'failed';
				$data['messageDescription'] = 'Invalid Body Request';
			} else {
				$this->Crud->create('api_response', array('response'=>$input, 'reg_date'=>date(fdate)));
				
				$transactionReference = $body->transactionReference;
				$referenceNo = $body->referenceNo;
				$amount = $body->amountPaid;
				$pay_method = $body->methodOfPayment;

				if($this->Crud->check('reference', $transactionReference, 'pos') > 0){
					//Duplicate Transaction
					$data['responseCode'] = "001";
					$data['responseMessage'] = "Transaction Reference Already Exist";

				} else {
					
					$this->Crud->create('pos', array('response'=>$input, 'reference' => $body->transactionReference, 'account'=>$body->referenceNo, 'reg_date'=>date(fdate)));

					//Check if virtual Account number exist on platform
					if($this->Crud->check('acc_no', $referenceNo, 'virtual_account') == 0){
						//Reject Transaction
						$data['responseCode'] = "001";
						$data['responseMessage'] = "Account Does not Exist";

					} else {
						// FOR POS

						$data['responseMessage'] = 'success';
						$data['responseCode'] = '000';

						//Fund Wallet

						$user_id = $this->Crud->read_field('acc_no', $referenceNo, 'virtual_account', 'user_id');

						// if($this->Crud->check('id', $user_id, 'user') <= 0) die;

						// echo $user_id;
						$post_datas['payment_method'] = $pay_method;
						$post_datas['remark'] = 'POS Transaction';
						$post_datas['ref'] = $transactionReference;
						$post_datas['amount'] = $amount;
						$post_datas['session_id'] =$transactionReference ;
						$post_datas['trans_date'] = date(fdate);

						//Save transaction in transaction table
						$this->Crud->api('post', 'payments/transaction/'.$user_id, $post_datas);

						$postData['payment_method'] = $pay_method;
						$postData['remark'] = '';
						$postData['ref'] = $transactionReference;
						$postData['amount'] = $amount;

						//Perform operation on the ttransaction and pay tax
						$this->Crud->api('post', 'payments/pay_tax/'.$user_id, $postData);

					}


				}
			}

		}

		if($type == 'verify'){
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			$ref = $call->transactionReference;
			
			if(empty($ref)){
				$data['messageDesc'] = "Transaction Reference is Empty";
			} else {
				
				if($this->Crud->check('reference', $ref, 'pos') == 0){
					//Duplicate Transaction
					$data['responseCode'] = "001";
					$data['responseMessage'] = "Invalid Transaction Reference";

				} else {
					$data['responseCode'] = "000";
					$data['responseMessage'] = "Success";

				}

			}

		}



		echo json_encode($data);
		die;
	}

	// notification
	public function notification($type='get',$id='') {
	    $status = false;
		$msg = '';
		$data = array();
		
		// collect call paramters
		
		$user_id = $this->request->getGet('log_id');
		$limit = $this->request->getGet('limit');
		$offset = $this->request->getGet('offset');
		
		if(empty($limit)) {$limit = 50;}
		if(empty($offset)) {$offset = 0;}
		
		// count total unread notification
		if($type == 'count') {
		    $status = true;
		    $msg = 'Successful';
			$data['count'] = $this->db->table('notify')->where('to_id', $user_id)->where('new', 1)->countAllResults();
		}
		
		// read all notification
		if($type == 'get') {
		    $query = $this->Crud->read_single('to_id', $user_id, 'notify', $limit, $offset);
		    if(!empty($query)) {
		        $status = true;
		        $msg = 'Successful';
		        foreach($query as $q) {
		            $item = array();
		            
		            $isNew = true;
		            if($q->new == 0) { $isNew = false; }
		            
		            $item['id'] = $q->id;
		            $item['content'] = $q->content;
		            $item['item'] = $q->item;
		            $item['item_id'] = $q->item_id;
		            $item['reg_date'] = $q->reg_date;
		            $item['new'] = $isNew;
		            $item['date'] = $this->timeago(strtotime($q->reg_date));
		            
		            $data[] = $item;
		        }
		    }
		}
		
		// push notification
		if($type == 'push') {
		    $query = $this->Crud->read2('to_id', $user_id, 'new', 1, 'notify', 'id', 'DESC', $limit, $offset);
		    if(!empty($query)) {
		        $status = true;
		        $msg = 'Successful';
		        foreach($query as $q) {
		            $item = array();
		            
		            $item['id'] = $q->id;
		            $item['content'] = $q->content;
		            $item['item'] = $q->item;
		            $item['item_id'] = $q->item_id;
					$item['orderId'] = $q->orderId;
		            $item['date'] = $this->timeago(strtotime($q->reg_date));
		            
		            $data[] = $item;
		        }
		    }
		}
		
		// update notification
		if($type == 'update') {
		    if($id && $user_id) {
		        $status = true;
		        $msg = 'Successful';
		        $this->Crud->updates('id', $id, 'notify', array('new'=>0));
		    }
		}

		// delete notification
		if($type == 'delete') {
		    if($id && $user_id) {
		        $status = true;
		        $msg = 'Successful';
		        $this->Crud->deletes('id', $id, 'notify');
		    }
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg, 'data'=>$data));
		die;
	}


	public function users($type='get', $id=0) {
		$status = false;
		$msg = '';
		$code = 'info';
		$data = array();

		// CREATE
		if($type == 'post') { 
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			// 	
			$name = $call->name;
			$state_id = $call->state_id;
			$log_id = $call->log_id;
			$img_id = $call->img_id;

			// echo 'teydjx';
			// echo $img_id;
			
			if($this->Crud->check2('name', $name, 'state_id', $state_id, 'market') > 0){
				$msg = 'Record Already Exist';
				$code = 'warning';
			} else{
				
				$ins_data = array(
					'name' => $name,
					'img' => $img_id,
					'state_id' => $state_id,
					'reg_date' => date(fdate)
					
				);
				$ins_rec = $this->Crud->create('market', $ins_data);
				if($ins_rec > 0) {
					///// store activities
					$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
					$code = $this->Crud->read_field('id', $ins_rec, 'market', 'name');
					$action = $by.' created Markets/Mall ('.$code.') Record';
					$this->Crud->activity('user', $ins_rec, $action);

					$status = true;
					$code = 'success';
					$msg = 'Record Created Successfully';

				} else {
					$code = 'danger';
					$msg = 'Try Again Later';


				}
			}

		}

		// READ
		if($type == 'get') {
			// collect parameters
			$state_id = $this->request->getGet('state_id');
			$search = $this->request->getGet('search');
			$log_id = $this->request->getGet('log_id');
			if(!empty($this->request->getGet('status'))){
				$status = $this->request->getGet('status');
			} else {
				$status = 'all';
			}
			if(!empty($this->request->getGet('ref_status'))){
				$ref_status = $this->request->getGet('ref_status');
			} else {
				$ref_status = 'all';
			}
			if(!empty($this->request->getGet('verify'))){
				$verify = $this->request->getGet('verify');
			} else {
				$verify = 'all';
			}
			$limit = $this->request->getGet('limit');
			$offset = $this->request->getGet('offset');
			$start_date = $this->request->getGet('start_date');
			$end_date = $this->request->getGet('end_date');
			
			
			
			$query = array();
			if($id == 'others'){
				$roles = $this->Crud->read_single('name !=', 'Developer', 'access_role');
				if(!empty($roles)){
					foreach($roles as $r){
						if($r->name == 'Administrator' || $r->name == 'Personal' || $r->name == 'Business')continue;
						$others = $this->Crud->filter_users($limit, $offset, $log_id, $r->id, $state_id, $status, $search, $verify, $ref_status, $start_date, $end_date);
						$query = array_merge($query, $others);
					}
				}
			} else {
				$query =  $this->Crud->filter_users($limit, $offset, $log_id, $id, $state_id, $status, $search, $verify, $ref_status, $start_date, $end_date);
			}

			if(!empty($query)) {
				$status = true;
				$code = 'success';
				$msg = 'Successful';
				foreach($query as $q) {
					$item = array();
					$item['id'] = $q->id; 
					$item['fullname'] = $q->fullname; 
					$item['username'] = $q->username; 
					$item['state_id'] = $q->state_id; 
					$item['email'] = $q->email;
					$item['phone'] = $q->phone; 
					$item['role_id'] = $q->role_id; 
					$item['address'] = $q->address; 
					$item['activate'] = $q->activate;
					$item['referral_id'] = $q->referral_id;
					$item['country_id'] = $q->country_id;
					$item['role_id'] = $q->role_id; 
					$item['reg_date'] = $q->reg_date; 
					$item['img_id'] = $q->img_id;

					$data[] = $item;
				}
			}
		}

		// UPDATE
		if($type == 'update') {
			// collect call paramters
			$call = json_decode(file_get_contents("php://input"));
			// 	
			$log_id = $call->log_id;
			$role_id = $call->role_id;
			$trade = $call->trade;
			$roles = $this->Crud->read_field('id', $role_id, 'access_role', 'name');

			//Update for customer
			if($roles == 'Personal'){
				
				$role = $role_id;
				if(!empty($call->role)){
					$role = $call->role;
				}
				$fullname = $call->fullname;
				$email = $call->email;
				$phone = $call->phone;
				$ban = $call->ban;
				$trade = $call->trade;
				$password = $this->Crud->read_field('id', $id, 'user', 'password');
				if(!empty($call->password)){
					$password = md5($call->password);
				}

				$upd_data = array(
					'role_id' => $role,
					'activate' => $ban,
					'trade' => $trade,
					'fullname' => $fullname,
					'email' => $email,
					'phone' => $phone,
					'password' => $password
					
				);
				
			}

			//Update for vendor
			if($roles == 'Business'){
				 
				$role = $call->role;
				$set_activate = $call->activate;
				$password = md5($call->password);
				if(empty($password)){
					$password = $this->Crud->read_field('id', $id, 'user', 'password');
				}

				$upd_data = array(
					'role_id' => $role,
					'activate' => $set_activate,
					'trade' => $trade,
					'password' => $password
					
				);

				$user_id = $id;
				
			}
			
			$upd_rec = $this->Crud->updates('id', $id, 'user', $upd_data);
			if($upd_rec > 0) {

				///// store activities
				$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
				$code = $this->Crud->read_field('id', $id, 'user', 'fullname');
				$action = $by.' updated '.$roles.' ('.$code.') Record';
				$this->Crud->activity('user', $id, $action);

				$status = true;
				$code = 'success';
				$msg = 'Record Updated Successfully';

			} else {
				$code = 'danger';
				$msg = 'Try Again Later';


			}

		 }

		// DELETE
		if($type == 'delete') { 
			$call = json_decode(file_get_contents("php://input"));
			// 	
			$log_id = $call->log_id;
			$role_id = $call->role_id;
			
			$msg = $role_id;
			$code = $role_id;

			$role = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
			$code = $this->Crud->read_field('id', $id, 'user', 'fullname');
			$by = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
			$action = $by.' deleted '.$role.' ('.$code.')';

			if($this->Crud->deletes('id', $id, 'user') > 0) {

				///// store activities
				$this->Crud->activity('user', $id, $action);
				$msg = 'Record deleted successfully';
				$code = 'success';
				$status = true;
			} else {
				$msg = 'Try Again';
				$code = 'danger';
			}
						
		}

		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}
	// get settings
	public function setting() {
	    $status = false;
		$data = array();
		$msg = '';
		
		$sandbox = true;
		$getsandbox = $this->Crud->read_field('name', 'sandbox', 'setting', 'value');
		if($getsandbox == 'no') { $sandbox = false; }
		
		if($sandbox == true) {
		    $pkey = $this->Crud->read_field('name', 'test_pkey', 'setting', 'value');
		    $ekey = $this->Crud->read_field('name', 'test_ekey', 'setting', 'value');
		} else {
		    $pkey = $this->Crud->read_field('name', 'live_pkey', 'setting', 'value');
		    $ekey = $this->Crud->read_field('name', 'live_ekey', 'setting', 'value');
		}
		
		// responses
		$status = true;
		$msg = 'Successful';
        $data['sandbox'] = $sandbox;
        $data['public_key'] = $pkey;
        $data['encryption_key'] = $ekey;
        
		echo json_encode(array('status'=>$status, 'msg'=>$msg, 'data'=>$data));
		die;
	}

	//// others //////
	private function user_data($id) {
		$query = $this->Crud->read_single('id', $id, 'user');
		if(!empty($query)) {
			foreach($query as $q) {
				$data['id'] = $q->id;
				$data['username'] = $q->business_name;
				$data['email'] = $q->email;
				$data['phone'] = $q->phone;
				$data['country'] = $this->Crud->read_field('id', $q->country_id, 'country', 'name');
				$data['curr'] = $this->Crud->read_field('id', $q->country_id, 'country', 'currency');
				$data['curr_symbol'] = $this->Crud->read_field('id', $q->country_id, 'country', 'currency_symbol');
				$data['role_id'] = $q->role_id;
				$data['role'] = $this->Crud->read_field('id', $q->role_id, 'access_role', 'name');
				$data['reg_date'] = date('M d, Y h:i A', strtotime($q->reg_date));
				return $data;
			}
		} else {
			return false;
		}
	}

	private function get_balance($id) {
		$balance = 0; $earnings = 0; $withdrawns = 0;
		$wallets = $this->Crud->read_single('user_id', $id, 'wallet');
		if(!empty($wallets)) {
			foreach($wallets as $w) {
				if($w->type == 'credit') {
					$earnings += (float)$w->amount;
				} else {
					$withdrawns += (float)$w->amount;
				}
			}
			$balance = $earnings - $withdrawns;
		}
		return $balance;
	}

	private function orderDetails($id) {
		$data = array();

		$query = $this->Crud->read_single('id', $id, 'order');
		if(!empty($query)) {
			foreach($query as $q) {
				$data['id'] = $q->id;
				$data['ref'] = $q->ref;
				$data['code'] = $q->code;
				$data['category'] = $this->Crud->read_field('id', $q->category_id, 'category', 'name');
				$data['amount'] = number_format((float)$q->amount, 2);
				$data['comm'] = number_format((float)$q->comm, 2);
				$data['vat'] = number_format((float)$q->vat, 2);
				$data['total'] = number_format((float)$q->total, 2);
				$data['status'] = $q->status;
				$data['litre'] = $q->litre;
				$data['partner'] = $this->Crud->read_field('id', $q->partner_id, 'user', 'fullname');
				$data['partner_address'] = $this->Crud->read_field('id', $q->partner_id, 'user', 'address');
				$data['city'] = $this->Crud->read_field('id', $q->city_id, 'city', 'name');
				$data['state'] = $this->Crud->read_field('id', $q->state_id, 'state', 'name');
				$data['country'] = $this->Crud->read_field('id', $q->country_id, 'country', 'name');
				$data['used_date'] = date('M d, Y h:iA', strtotime($q->used_date));
				$data['date'] = date('M d, Y h:iA', strtotime($q->reg_date));
			}
		}

		return $data;
	}

	private function notify($from, $to, $content, $item, $item_id) {
	    $ins['from_id'] = $from;
	    $ins['to_id'] = $to;
	    $ins['content'] = $content;
	    $ins['item'] = $item;
	    $ins['item_id'] = $item_id;
	    $ins['new'] = 1;
	    $ins['reg_date'] = date(fdate);
	    
	    $this->Crud->create('notify', $ins);
	}
	
	private function send_email($to, $subject, $body) {
		$from = push_email;
		$name = app_name;
		$subhead = 'Notification';
		$this->Crud->send_email($to, $from, $subject, $body, $name, $subhead);
	}

	private function timeago($ptime) {
		$estimate_time = time() - $ptime;
		if( $estimate_time < 1 ) {
			return 'less than 1 second ago';
		}
	
		$condition = array(
			12 * 30 * 24 * 60 * 60  =>  'year',
			30 * 24 * 60 * 60       =>  'month',
			24 * 60 * 60            =>  'day',
			60 * 60                 =>  'hour',
			60                      =>  'minute',
			1                       =>  'second'
		);
	
		foreach($condition as $secs => $str) {
			$d = $estimate_time / $secs;
		
			if($d >= 1) {
				$r = round( $d );
				return 'about ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
			}
		}
	}

	private function getIPAddress() {  
		//whether ip is from the share internet  
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) { 
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} //whether ip is from the proxy  
		else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
		} //whether ip is from the remote address  
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}  
		return $ip;  
	} 



	//Qr code
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

}
