<?php

namespace App\Controllers;

use App\Libraries\Multilingual;

class Auth extends BaseController {
	
    public function index($param1='') {
		if(empty($this->session->get('current_language')))$this->session->set('current_language', 'English');
		$multilingual = new Multilingual();
        // check login
        $log_id = $this->session->get('td_id');
        if(!empty($log_id)) return redirect()->to(site_url('dashboard'));
		$msg = $this->session->get('td_auth_message');

		if($this->request->getMethod() == 'post') {
            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

			$data['email'] = $email;
			$data['password'] = $password;
            
			$resp = $this->Crud->api('post', 'login', $data);
			// echo $resp;
			$resp = json_decode($resp);
			if($resp->status == true) {
				$this->session->set('td_id', $resp->data->id);
				echo $this->Crud->msg($resp->code, translate_phrase($resp->msg));
				echo '<script>window.location.replace("'.site_url('dashboard').'");</script>';
				$this->session->set('td_auth_message', '');
				
			} else {
				if($resp->msg == translate_phrase('Account not Activated, Please validate account')){
					echo $this->Crud->msg($resp->code, translate_phrase($resp->msg));
					$id = $this->Crud->read_field('email', $email, 'user', 'id');
					$this->session->set('td_id', $id);
					echo '<script>window.location.replace("'.site_url('auth/otp').'");</script>';
				} else {
					echo $this->Crud->msg($resp->code, translate_phrase($resp->msg));
				}
				
			}
            die;
        }
        
        $data['current_language'] = $this->session->get('current_language');
        $data['title'] =  $multilingual->_ph('Sign In').' - '.app_name;
        $data['msg'] = $msg;
        return view('auth/login', $data);
    }

    ///// LOGIN
    public function login() {
		if(empty($this->session->get('current_language')))$this->session->set('current_language', 'English');
		$multilingual = new Multilingual();
        // check login
        $log_id = $this->session->get('td_id');
        if(!empty($log_id)) return redirect()->to(site_url('dashboard'));
		$msg = $this->session->get('td_auth_message');

		if($this->request->getMethod() == 'post') {
            $email = $this->request->getVar('membership_id');
            $password = $this->request->getVar('password');

			if($email && $password) {
				$password = md5($password);
				$type = 'user_no';
				$query = $this->Crud->read2('user_no', $email, 'password', $password, 'user');
				
	
				if(empty($query)) {
					$msg = 'Invalid Authentication!';
					echo $this->Crud->msg('warning', translate_phrase($msg));
						
				} else {
					$act = $this->Crud->check2($type, $email, 'activate', 0, 'user');
					if ($act > 0) {
						$msg = 'Account not Activated, Contact Administrator';
						echo $this->Crud->msg('danger', translate_phrase($msg));
						$id = $this->Crud->read_field('email', $email, 'user', 'id');
						$this->session->set('td_id', $id);
						echo '<script>window.location.replace("'.site_url('auth/otp').'");</script>';
					} else {
						$status = true;
						$msg = 'Login Successful!';
						$code = 'success';
						$id = $this->Crud->read_field($type, $email, 'user', 'id');
	
						$this->Crud->updates('id', $id, 'user', array('last_log'=> date(fdate)));
						///// store activities
						$codes = $this->Crud->read_field('id', $id, 'user', 'firstname').' '.$this->Crud->read_field('id', $id, 'user', 'surname');
						$action = $codes . ' logged in ';
						$this->Crud->activity('authentication', $id, $action);
						$this->session->set('td_id', $id);
						echo $this->Crud->msg('success', translate_phrase($msg));
						echo '<script>window.location.replace("'.site_url('dashboard').'");</script>';
						$this->session->set('td_auth_message', '');
					}
				}
			}
			
            die;
        }
        
        $data['current_language'] = $this->session->get('current_language');
        $data['title'] =  $multilingual->_ph('Sign In').' - '.app_name;
        $data['msg'] = $msg;
        return view('auth/login', $data);
    }

	public function forgot($param1="", $param2="") {
		// check login
        $log_id = $this->session->get('td_id');

		//send reset code
		if($param1 == 'code'){
			if($this->request->getMethod() == 'post') {
				$email = $this->request->getVar('email');
	
				$data['email'] = $email;
				
				$resp = $this->Crud->api('post', 'reset_code', $data);
				$resp = json_decode($resp);
				
				if($resp->status == true) {
					$this->session->set('td_code', $resp->data->code);
					$this->session->set('td_email', $email);
					echo '<script>
						$("#bb_ajax_form").hide(500);
						$("#bb_ajax_form3").hide();
						$("#bb_ajax_form2").show(500);
						$("#bb_ajax_msg2").html("<h5 class=text-success>'.translate_phrase('Reset Code Sent!').'</h5>");
					</script>';
					
				} else {
					echo $this->Crud->msg($resp->code, translate_phrase($resp->msg));
					
				}
				die;
			}

		}

		//confrim reset code
		if($param1 == 'confirm_code'){
			if($this->request->getMethod() == 'post') {
				$code = $this->request->getVar('code');
				
				if(!empty($code)){
					$reset = $this->session->get('td_code');
					if($reset != $code){
						echo $this->Crud->msg('danger', translate_phrase('Invalid Reset Code!'));
					} else {
						echo '<script>
							$("#bb_ajax_form2").hide(500);
							$("#bb_ajax_form3").show(500);
							$("#bb_ajax_msg3").html("<h5 class=text-success>'.translate_phrase('Reset Code Confirmed!').'</h5>");
						</script>';
					
					}
				}
				die;
			}

		}

        //new password
		if($param1 == 'password'){
			if($this->request->getMethod() == 'post') {
				$password = $this->request->getVar('password');
				$email = $this->session->get('td_email');
					
				$data['email'] = $email;
				$data['password'] = $password;
				
				$resp = $this->Crud->api('post', 'reset_password', $data);
				$resp = json_decode($resp);

				if($resp->status == true) {
					echo $this->Crud->msg('success', translate_phrase($resp->msg));
					$this->session->set('td_code', '');
					$this->session->set('td_email', '');
					echo '<script>window.location.replace("'.site_url('auth').'");</script>';
					
				} else {
					echo $this->Crud->msg($resp->code, translate_phrase($resp->msg));
					
				}
				die;
			}

		}
        
        $data['current_language'] = $this->session->get('current_language');
        $data['title'] = translate_phrase('Reset Password').' - '.app_name;
        return view('auth/forgot', $data);
    }
	
	///// otp
    public function otp($param1='', $param2='') {
        // check login
        $log_id = $this->session->get('td_id');
        
		if($param1 == 'resend'){
			$email = $this->Crud->read_field('id', $log_id, 'user', 'email');
			$phone = $this->Crud->read_field('id', $log_id, 'user', 'phone');

			if(empty($log_id)){
				$phone = $this->request->getPost('phone');
				$email = $this->request->getPost('email');
				
			}
			
			$otp = $this->Crud->api('post', 'otp/post', array('email' => $email, 'phone'=>$phone));
			$otp = json_decode($otp);
			if($otp->status == true){
				echo $this->Crud->msg('success', translate_phrase($otp->msg));
				
			} else {
				echo $this->Crud->msg('danger', translate_phrase($otp->msg));
			}
			die;
		}
        if($this->request->getMethod() == 'post') {
            $email = $this->request->getVar('email');
            $phone = $this->request->getVar('phone');
            $otp = $this->request->getVar('otp');

			$name = $this->Crud->read_field('phone', $phone, 'user', 'fullname');
			$user_id = $this->Crud->read_field('phone', $phone, 'user', 'id');
			$p_data['account_name']= $name;
			$p_data['bvn']= "";
			
			

            if(!$phone || !$otp) {
                echo $this->Crud->msg('danger', translate_phrase('Please provide Phone Number and OTP'));
            } else {
                $data['phone'] = $phone;
				$data['otp'] = $otp;

				
				$add = $this->Crud->api('post', 'otp/verify', $data);
				
				$add = json_decode($add);
				if($add->status == true){
					//Create Virtual Account
					if($this->Crud->check('user_id', $user_id, 'virtual_account') == 0){
						$virtual = $this->Crud->providus('post', 'PiPCreateReservedAccountNumber', $p_data);
						$virtuals =json_decode($virtual);
		
						if($virtuals->requestSuccessful == true){
							$v_data['acc_no'] = $virtuals->account_number;
							$v_data['user_id'] = $user_id;
							$v_data['response'] = $virtual;
							$v_data['reg_date'] = date(fdate);
							$this->Crud->create('virtual_account',  $v_data);

							$fullname  = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
							$phone  = $this->Crud->read_field('id', $user_id, 'user', 'phone');
							$email  = $this->Crud->read_field('id', $user_id, 'user', 'email');
							


							//Send Notification
							$first_msg = 'Hi '.ucwords($fullname).', Welcome to ZEND-TIDREMS. Your Tax ID is '.$virtuals->account_number.'. Kindly make your allocated tax payment to Account No: '.$virtuals->account_number.' (Providus Bank). Thank you. TIDREM Team';
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
								$this->Crud->send_email($email, 'Welcome Message', $first_msg);
							}
							$this->Crud->notify('0', $user_id, $first_msg, 'authentication', $user_id);


						}
					}

					
					echo $this->Crud->msg('success', translate_phrase('OTP Confirmed! You can now Login.'));
					$this->session->set('td_id', '');
					echo '<script>window.location.replace("'.site_url('auth/login').'");</script>';
					
				} else {
					echo $this->Crud->msg($add->code, translate_phrase($add->msg));	
				}
            }

            die;
        }
		
        $data['current_language'] = $this->session->get('current_language');
		$data['user_id'] = $log_id;
        $data['title'] = translate_phrase('One Time Password').' - '.app_name;
        return view('auth/otp', $data);
    }

    ///// REGISTER//////////////////////////
    public function register() {
        if($this->request->getMethod() == 'post') {
            $name = $this->request->getPost('fullname');
			$business_name = $this->request->getPost('business_name');
            $email = $this->request->getPost('email');
            $phone = $this->request->getPost('phone');
            $password = $this->request->getPost('password');
            $address = $this->request->getPost('address');
            $lga_id = $this->request->getPost('lga_id');
			$territory = $this->request->getPost('territory');
            $agree = $this->request->getPost('agree');
            $confirm = $this->request->getPost('confirm');
            $role_ids = $this->request->getPost('role_ids');
			$trade = $this->request->getPost('trade');
			$referral = $this->request->getPost('referral');
            $business_name = $this->request->getPost('business_name');
			$business_address = $this->request->getPost('business_address');

			$referrals = 0;
			if(!empty($referral)){
				if($this->Crud->check('phone', $referral, 'user') > 0){
					$role_id = $this->Crud->read_field('phone', $referral, 'user', 'role_id');
					$role = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
					if($role == 'Field Operative' || $role == 'Tax Master'){
						$referrals = $this->Crud->read_field('phone', $referral, 'user', 'id');
					}
				}
			}
			$ins_data['fullname'] = $name;
			$ins_data['business_name'] = $business_name;
			$ins_data['role_id'] = $this->Crud->read_field('name', strtolower($role_ids), 'access_role', 'id');
			$ins_data['email'] = $email;
			$ins_data['country_id'] = $this->Crud->read_field('id', $lga_id, 'city', 'country_id');
			$ins_data['state_id'] = $this->Crud->read_field('id', $lga_id, 'city', 'state_id');
			$ins_data['phone'] = $phone;
			$ins_data['referral'] = $referrals;
			$ins_data['password'] = md5($password);
			$ins_data['lga_id'] = $lga_id;
			$ins_data['territory'] = $territory;
			$ins_data['address'] = $address;
			$ins_data['trade'] = $trade;
			$ins_data['business_name'] = $business_name;
			$ins_data['business_address'] = $business_address;

			if($password != $confirm){
				echo $this->Crud->msg('danger', translate_phrase('Password Does Not Match!'));
				die;
			}

			// //// ID Card upload
			// if(file_exists($this->request->getFile('pics'))) {
			// 	$path = 'assets/images/users/';
			// 	$file = $this->request->getFile('pics');
			// 	$getImg = $this->Crud->img_upload($path, $file);
				
			// 	if(!empty($getImg->path)) $id_card =  $getImg->path;
			// }

			// //// Utility Bill upload
			// if(file_exists($this->request->getFile('utility'))) {
			// 	$path = 'assets/images/users/';
			// 	$file = $this->request->getFile('utility');
			// 	$getImg = $this->Crud->img_upload($path, $file);
				
			// 	if(!empty($getImg->path)) $utility =  $getImg->path;
			// }

			//// Passport upload
			if(file_exists($this->request->getFile('pasport'))) {
				$path = 'assets/images/users/';
				$file = $this->request->getFile('pasport');
				$getImg = $this->Crud->img_upload($path, $file);
				
				if(!empty($getImg->path)) $passport =  $getImg->path;
			}
			
			// if(empty($id_card) || empty($utility) || empty($passport)){
			// 	echo $this->Crud->msg('danger', translate_phrase('Please Upload Passport Photograph, Valid ID Card or Utility Bill'));
			// 	die;
			// }

			if($this->Crud->check('phone', $phone, 'user') > 0){
				echo $this->Crud->msg('warning', translate_phrase(' Phone Number Already Exist'));
				die;
			}

			
			$ins_data['activate'] = 1;
			$ins_data['reg_date'] = date(fdate);
			// $ins_data['utility'] = $utility;
			$ins_data['passport'] = $passport;
			// $ins_data['id_card'] = $id_card;


			$ins = $this->Crud->create('user', $ins_data);
			if($ins > 0){
				//Create Virtual Account

				///// store activities
				$code = $this->Crud->read_field('id', $ins, 'user', 'fullname');
				$action = $code.' registered on the Platform';
				$this->Crud->activity('authentication', $ins, $action);

				echo $this->Crud->msg('success', translate_phrase('Account Created'));
				$otp = $this->Crud->api('post', 'otp/post', array('email' => $email,'phone'=>$phone));
				// echo $otp;
				$otp = json_decode($otp);
				if($otp->status == true){
					echo $this->Crud->msg('success', translate_phrase($otp->msg));


					$user_id = $ins;
					if(!empty($user_id)){
						$link = site_url('auth/profile_view/') .$user_id;
						$qr = $this->qrcode($link);
						$path = $qr['file'];
						$this->Crud->updates('id', $user_id, 'user', array('qrcode'=>$path));
					}
					$this->session->set('td_id', $user_id);

					//Cretae Virtual Account
					if($this->Crud->check('user_id', $user_id, 'virtual_account') == 0){
						$name = $this->Crud->read_field('phone', $phone, 'user', 'fullname');
						$user_id = $this->Crud->read_field('phone', $phone, 'user', 'id');
						$p_data['account_name']= $name;
						$p_data['bvn']= "";
			
						$virtual = $this->Crud->providus('post', 'PiPCreateReservedAccountNumber', $p_data);
						$virtuals =json_decode($virtual);
		
						if($virtuals->requestSuccessful == true){
							$v_data['acc_no'] = $virtuals->account_number;
							$v_data['user_id'] = $user_id;
							$v_data['response'] = $virtual;
							$v_data['reg_date'] = date(fdate);
							$this->Crud->create('virtual_account',  $v_data);

							$fullname  = $this->Crud->read_field('id', $user_id, 'user', 'fullname');
							$phone  = $this->Crud->read_field('id', $user_id, 'user', 'phone');
							$email  = $this->Crud->read_field('id', $user_id, 'user', 'email');
							


							//Send Notification
							$first_msg = 'Dear '.ucwords($fullname).', you have successfully registered as a Tax Payer with Delta State Government. Your Tax ID is '.$virtuals->account_number.'. Kindly make your allocated tax payment to Account No: '.$virtuals->account_number.' (Providus Bank). Congratulations.';
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
								$this->Crud->send_email($email, 'Welcome Message', $first_msg);
							}
							$this->Crud->notify('0', $user_id, $first_msg, 'authentication', $user_id);


						}
					}
					// echo '<script> onSuccessEvent();</script>'; 
					
					// echo $this->Crud->msg('success', translate_phrase('You can now Login.'));
					$this->session->set('td_id', '');
					// echo '<script>window.location.replace("'.site_url('auth/login').'");</script>';
					echo '<script>window.location.replace("'.site_url('auth/otp').'");</script>';
				}
			} else {
				
				echo '<script> onfailedEvent();</script>';
				echo $this->Crud->msg('danger', 'Please Try Again Later');	
			}
			die;
        }
        
        $data['current_language'] = $this->session->get('current_language');
        $data['title'] = translate_phrase('Register').' - '.app_name;
        return view('auth/register', $data);
    }

    /////////////Check if Email Exist////////////////////
    public function check_email($email) {
		if($email) {
			if($this->Crud->check('email', $email, 'user') <= 0) {
				echo '<span class="text-success small">'.translate_phrase('Email Accepted').'!</span>';
			} else {
				echo '<span class="text-danger small">'.translate_phrase('Email Taken').'</span>';
			}
			die;
		}
	}

	/////////////Check if Phone Number Exist////////////////////
    public function check_phone($phone) {
		if($phone) {
			if($this->Crud->check('phone', $phone, 'user') <= 0) {
				echo '<span class="text-success small">'.translate_phrase('Phone Number Accepted').'</span>';
			} else {
				echo '<span class="text-danger small">'.translate_phrase('Phone Number Taken').'</span>';
			}
			die;
		}
	}

	public function check_ref($referral) {
		if($referral) {
			if($this->Crud->check('phone', $referral, 'user') > 0) {
				if($this)
				$name = $this->Crud->read_field('phone', $referral, 'user', 'fullname');
				$role_id = $this->Crud->read_field('phone', $referral, 'user', 'role_id');
				$role = $this->Crud->read_field('id', $role_id, 'access_role', 'name');
				if($role != 'Field Operative' && $role != 'Tax Master'){
					echo '<span class="text-danger ">Account is not Authorised as Reference</span>';
				} else {
					echo '<span class="text-success ">'.strtoupper($name).'</span>';
				}
				
			} else {
				echo '<span class="text-danger ">'.translate_phrase('Invalid Referrence Phone').'</span>';
			}
			die;
		}
	}

    //////////////Check if Password Matchs////////////////////////////
	public function check_password($param1 = '', $param2 = '') {
		if($param1 && $param2) {
			if($param1 == $param2) {
				echo '<span class="text-success small">'.translate_phrase('Password Matched').'</span>';
			} else {
				echo '<span class="text-danger small">'.translate_phrase('Password Not Matched').'</span>';
			}
			die;
		}
	}


    ///// LOGOUT
    public function logout() {
		$user_id = $this->session->get('td_id');
		if(!empty($this->session->get('td_id'))){
			///// store activities
			$code = $this->Crud->read_field('id', $user_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $user_id, 'user', 'surname');
			$action = $code.translate_phrase(' logged out ');
			
			$this->Crud->activity('authentication', $user_id, $action);

			$this->session->remove('td_id');
		}
        return redirect()->to(site_url('login'));
    }

    ////////////Profile////////////////////////////
    public function profile($param1 = '', $param2 = '', $param3='') {
        // check login
        $log_id = $this->session->get('td_id');
        if(empty($log_id)) return redirect()->to(site_url('auth'));

        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $main_email = $this->Crud->read_field('id', $log_id, 'user', 'email');

        $data['log_id'] = $log_id;
        $data['role'] = $role;
		$data['param1'] = $param1;
        $data['param2'] = $param2;

		$form_link = site_url('auth/profile');
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2;}
		if($param3){$form_link .= '/'.$param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		

		if($param1 == 'manage' && $param2 == 'personal'){
			if($this->request->getMethod() == 'post') {
				$lga_id = $this->request->getVar('lga_id');
				$address = $this->request->getVar('address');
				$password = $this->request->getVar('password');
				$img_id = $this->request->getVar('img_id');
	
				//// Image upload
				if(file_exists($this->request->getFile('pics'))) {
					$path = 'assets/images/users/'.$log_id.'/';
					$file = $this->request->getFile('pics');
					$getImg = $this->Crud->img_upload($path, $file);
					
					if(!empty($getImg->path)) $img_id = $getImg->path;
				}
	
				$datas['lga_id'] = $lga_id;
				$datas['address'] = $address;
				$datas['img_id'] = $img_id;
				if(!empty($password))$datas['password'] = md5($password);
				
				$add = $this->Crud->api('post', 'profile/update/'.$log_id, $datas);
				// echo $add;
				$add = json_decode($add);
				if($add->status == true){
					echo $this->Crud->msg('success', translate_phrase($add->msg));
					echo '<script>window.location.replace("'.site_url('auth/profile').'");</script>';
					
				} else {
					echo $this->Crud->msg($add->code, translate_phrase($add->msg));	
				}
				
				die;
			}
		}
		


		if($param1 == 'message'){
			$this->Crud->updates('id', $log_id, 'user', array('receive_message' => $param2));
			echo $this->Crud->msg('success', translate_phrase('Profile Updated'));
			die;
		}


		$data['email'] = $this->Crud->read_field('id', $log_id, 'user', 'email');
		$data['address'] = $this->Crud->read_field('id', $log_id, 'user', 'address');
		$data['chat_handle'] = $this->Crud->read_field('id', $log_id, 'user', 'chat_handle');
		$data['fullname'] = $this->Crud->read_field('id', $log_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $log_id, 'user', 'surname');
       	$data['phone'] = $this->Crud->read_field('id', $log_id, 'user', 'phone');
		

        $data['current_language'] = $this->session->get('current_language');
		if($param1 == 'manage'){
			return view('auth/profile_form', $data);
		} else {
			$data['title'] = translate_phrase('Profile').' - '.app_name;
			$data['page_active'] = 'profile';
			return view('auth/profile', $data);

		}
        
    }


	public function profile_view($param1 = '', $param2 = '', $param3='') {
		$this->session->set('td_merchant', '');
        // check login
        $log_id = $this->session->get('td_id');
        if(empty($log_id)) return redirect()->to(site_url('auth'));


        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $main_email = $this->Crud->read_field('id', $log_id, 'user', 'email');

        $data['log_id'] = $log_id;
        $data['role'] = $role;
		$data['param1'] = $param1;
        $data['param2'] = $param2;

		if(!empty($param1)){
			$id = $param1;
			$this->session->set('td_merchant', $id);;
			return redirect()->to(site_url('payments/index/transfer'));

			die;
		}
        
		
        $data['current_language'] = $this->session->get('current_language');
		$data['email'] = $this->Crud->read_field('id', $log_id, 'user', 'email');
		$tax_id = $this->Crud->read_field('user_id', $log_id, 'virtual_account', 'acc_no');
		if(empty($tax_id)){
			$tax_id = '##########';
		}
		$data['tax_id'] = $tax_id;
		$data['fullname'] = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
        $data['sms'] = $this->Crud->read_field('id', $log_id, 'user', 'receive_message');
        $data['address'] = $this->Crud->read_field('id', $log_id, 'user', 'address');
        $data['territory'] = str_replace('_', ' ',$this->Crud->read_field('id', $log_id, 'user', 'territory'));
        $utilitys = $this->Crud->read_field('id', $log_id, 'user', 'utility');
        $id_cards = $this->Crud->read_field('id', $log_id, 'user', 'id_card');
		$trade_id = $this->Crud->read_field('id', $log_id, 'user', 'trade');
		$data['trade'] = $this->Crud->read_field('id', $trade_id, 'trade', 'name');
		$passports = $this->Crud->read_field('id', $log_id, 'user', 'passport');
        $data['phone'] = $this->Crud->read_field('id', $log_id, 'user', 'phone');
		$data['duration'] = $this->Crud->read_field('id', $log_id, 'user', 'duration');
		$data['lga_id'] = $this->Crud->read_field('id', $log_id, 'user', 'lga_id');
		$data['state_id'] = $this->Crud->read_field('id', $log_id, 'user', 'state_id');
		$data['country_id'] = $this->Crud->read_field('id', $log_id, 'user', 'country_id');
		$qrcodes = $this->Crud->read_field('id', $log_id, 'user', 'qrcode');
		$img_id = $this->Crud->read_field('id', $log_id, 'user', 'img_id');


		$qrcode = '<img height="150" src="'.site_url('assets/qrcode.png').'"> ';
		// echo $qrcodes;
		if(!empty($qrcodes) && file_exists($qrcodes)){
			$qrcode = '<img height="150" src="'.site_url($qrcodes).'"> ';
		}

		$utility = 'No Utility Document Uploaded';
		if(!empty($utilitys) && file_exists($utilitys)){
			$utility = '<img height="150" src="'.site_url($utilitys).'"> ';
		}

		$id_card = 'No Valid ID Card Document Uploaded';
		if(!empty($id_cards) && file_exists($id_cards)){
			$id_card = '<img height="150" src="'.site_url($id_cards).'"> ';
		}
		
		$passport = '<img height="150" src="'.site_url('assets/user.png').'"> ';
		// echo $qrcodes;
		if(!empty($passports) && file_exists($passports)){
			$passport = '<img height="350" src="'.site_url($passports).'"> ';
		}

		$data['utility'] = $utility;
		$data['id_card'] = $id_card;
		$data['passport'] = $passport;
		$data['qrcode'] = $qrcode;
		

		$data['title'] = translate_phrase('Profile View').' - '.app_name;
		$data['page_active'] = 'auth/profile_view';
		return view('auth/profile_view', $data);

		
        
    }

	////////////Profile////////////////////////////
    public function security($param1 = '', $param2 = '', $param3='') {
        // check login
        $log_id = $this->session->get('td_id');
        if(empty($log_id)) return redirect()->to(site_url('auth'));

        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $main_email = $this->Crud->read_field('id', $log_id, 'user', 'email');

		
		$form_link = site_url('auth/profile');
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
        $data['log_id'] = $log_id;
        $data['role'] = $role;

        if($this->request->getMethod() == 'post') {
			$duration = $this->request->getVar('duration');
			$trade = $this->request->getVar('trade');
			if(empty($duration) || empty($trade)){
				echo $this->Crud->msg('danger', 'Please Fill all Fields');
				// die;
			}
			$datas['duration'] = $duration;
			if(!empty($trade))$datas['trade'] = $trade;
			$datas['setup'] = 1;
			
			$add = $this->Crud->updates('id', $log_id, 'user', $datas);
			
			if($add > 0){
				$id = $log_id;
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
				echo '<script>window.location.replace("'.site_url('dashboard').'");</script>';
				
			} else {
				echo $this->Crud->msg('danger', translate_phrase('No Changes'));	
			}
			die;
		}

		if($param1 == 'get_trade'){
			$price = 0;
			if(!empty($param2)){
				
				if($this->Crud->check('id', $param2, 'trade') > 0){
					$price = $this->Crud->read_field('id', $param2, 'trade', 'medium');
				}
				
			}

			echo $price;
		}
		$data['duration'] = $this->Crud->read_field('id', $log_id, 'user', 'duration');
		$data['username'] = $this->Crud->read_field('id', $log_id, 'user', 'business_name');
		$data['fullname'] = $this->Crud->read_field('id', $log_id, 'user', 'fullname');
        $data['address'] = $this->Crud->read_field('id', $log_id, 'user', 'address');
        $data['phone'] = $this->Crud->read_field('id', $log_id, 'user', 'phone');
		$data['sms'] = $this->Crud->read_field('id', $log_id, 'user', 'receive_message');
		$data['state_id'] = $this->Crud->read_field('id', $log_id, 'user', 'state_id');
		$data['country_id'] = $this->Crud->read_field('id', $log_id, 'user', 'country_id');
		$img_id = $this->Crud->read_field('id', $log_id, 'user', 'img_id');
		$data['img_id'] = $img_id;
		$data['img'] = $this->Crud->image($img_id, 'big');
        
        $data['current_language'] = $this->session->get('current_language');
		if($param1 == 'manage'){
			return view('auth/profile_form', $data);
		} else {
			$data['title'] = translate_phrase('Payment Setup').' - '.app_name;
			$data['page_active'] = 'auth/security';
			return view('auth/security', $data);

		}
        

    }
		////////////Profile////////////////////////////
    public function bank($param1 = '', $param2 = '', $param3='') {
        // check login
        $log_id = $this->session->get('td_id');
        if(empty($log_id)) return redirect()->to(site_url('auth'));

        $role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
        $role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
        $main_email = $this->Crud->read_field('id', $log_id, 'user', 'email');

		
		$form_link = site_url('auth/bank');
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2;}
		if($param3){$form_link .= '/'.$param3;}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
        $data['log_id'] = $log_id;
        $data['role'] = $role;

		if($this->request->getMethod() == 'post') {
			$user_id = $log_id;
			
			$bank_code = $this->request->getPost('bank');
			$account = $this->request->getPost('account');
			$account_name = $this->request->getPost('account_name');

			$datas['bank_code'] = $bank_code;
			$datas['account'] = $account;
			$datas['account_name'] = $account_name;
			
			$add = $this->Crud->api('post', 'profile/bank/'.$log_id, $datas);
			
			$add = json_decode($add);
			if($add->status == true){
				echo $this->Crud->msg('success', translate_phrase($add->msg));
				echo '<script>window.location.replace("'.site_url('auth/bank').'");</script>';
				
			} else {
				echo $this->Crud->msg($add->code, translate_phrase($add->msg));	
			}
			die;
		}
		
        $data['current_language'] = $this->session->get('current_language');

		if($param1 == 'manage'){
			return view('auth/profile_form', $data);
		} else {
			$data['title'] = translate_phrase('Bank Account Setup').' - '.app_name;
			$data['page_active'] = 'auth/bank';
			return view('auth/bank', $data);

		}
        

    }

	////////////Change Password////////////////////////////
	public function password() {
		// check login
        $log_id = $this->session->get('td_id');
        if(empty($log_id)) return redirect()->to(site_url('auth'));

        $data['log_id'] = $log_id;

		if($this->request->getMethod() == 'post') {
			$old = $this->request->getVar('old');
			$new = $this->request->getVar('new');
			$confirm = $this->request->getVar('confirm');

			if($this->Crud->check2('id', $log_id, 'password', md5($old), 'user') <= 0) {
				echo $this->Crud->msg('danger', translate_phrase('Current Password not correct'));
			} else {
				if($new != $confirm) {
					echo $this->Crud->msg('info', translate_phrase('New and Confirm Password not matched'));
				} else {
					if($this->Crud->updates('id', $log_id, 'user', array('password'=>md5($new))) > 0) {
						echo $this->Crud->msg('success', translate_phrase('Password changed successfully'));
					} else {
						echo $this->Crud->msg('danger', translate_phrase('Please try later'));
					}
				}
			}

			die;
		}
		
        $data['current_language'] = $this->session->get('current_language');

		$data['title'] =  translate_phrase('Change Password').' - '.app_name;
		$data['page_active'] = 'profile';

		return view('profile/password', $data);
	}

    /////////////Get state from Country////////////////////
    public function get_state($country_id) {
        $states = '';

		$state_id = $this->request->getGet('state_id');

		$all_states = $this->Crud->read_single_order('country_id', $country_id, 'state', 'name', 'asc');
		if(!empty($all_states)) {
			foreach($all_states as $as) {
				$s_sel = '';
				if(!empty($state_id)) if($state_id == $as->id) $s_sel = 'selected';
				$states .= '<option value="'.$as->id.'" '.$s_sel.'>'.$as->name.'</option>';
			}
		}

		echo $states;
		die;
	}

    //////////////////////Manage Users///////////////////////////
    public function users($param1='', $param2='', $param3='') {
        // check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('kgf_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} else {
			$log_id = $this->session->get('td_id');
			$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
			$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
            $role_c = $this->Crud->module($role_id, 'users', 'create');
			$role_r = $this->Crud->module($role_id, 'users', 'read');
			$role_u = $this->Crud->module($role_id, 'users', 'update');
			$role_d = $this->Crud->module($role_id, 'users', 'delete');
            if($role_r == 0){
				return redirect()->to(site_url('dashboard'));
			}
			$data['role'] = $role;
			$data['role_c'] = $role_c;
		}
		
		$table = 'user';

        $form_link = site_url('auth/users');
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		$data['user_id'] = $log_id;
        $data['role_id'] = $role_id;
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
						$del_id =  $this->request->getVar('d_user_id');
                        if($this->Crud->deletes('id', $del_id, $table) > 0) {
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
								$data['e_activate'] = $e->activate;
								$data['e_role_id'] = $e->role_id;

                                
							
							}
						}
					}
				}

				//profile view
				if($param2 == 'profile') {
					$vendor_id = $param3;
					$data['v_id'] = $vendor_id;
					$data['v_name'] = $this->Crud->read_field('id', $vendor_id, 'user', 'fullname');
					$data['v_phone'] = $this->Crud->read_field('id', $vendor_id, 'user', 'phone');
					$data['v_email'] = $this->Crud->read_field('id', $vendor_id, 'user', 'email');

					$v_img_id = $this->Crud->read_field('id', $vendor_id, 'user', 'img_id');
					$data['v_img'] = base_url($this->Crud->image($v_img_id, 'big'));

					$v_status = $this->Crud->read_field('id', $vendor_id, 'user', 'activate');
					if(!empty($v_status)) { $v_status = '<span class="text-success">VERIFIED</span>'; } else { $v_status = '<span class="text-danger">UNVERIFIED</span>'; }
					$data['v_status'] = $v_status;

					$data['v_address'] = $this->Crud->read_field('id', $vendor_id, 'user', 'address');

					$v_state_id = $this->Crud->read_field('id', $vendor_id, 'user', 'state_id');
					$data['v_state'] = $this->Crud->read_field('id', $v_state_id, 'state', 'name');

					$v_country_id = $this->Crud->read_field('id', $vendor_id, 'user', 'country_id');
					$data['v_country'] = $this->Crud->read_field('id', $v_country_id, 'country', 'name');
					
				}
				
				if($this->request->getMethod() == 'post'){
					$user_i =  $this->request->getVar('user_id');
					$activate =  $this->request->getVar('activate');
					$role_id =  $this->request->getVar('role_id');
					
					// do create or update
					if($user_i) {
						$upd_data = array(
							'activate' => $activate,
							'role_id' => $role_id
							
						);
						$upd_rec = $this->Crud->updates('id', $user_i, $table, $upd_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', 'Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
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

			$rec_limit = 25;
			$item = '';

			if(empty($limit)) {$limit = $rec_limit;}
			if(empty($offset)) {$offset = 0;}
			
			if(!empty($this->request->getPost('state_id'))) { $state_id = $this->request->getPost('state_id'); } else { $state_id = ''; }
			if(!empty($this->request->getPost('status'))) { $status = $this->request->getPost('status'); } else { $status = ''; }
			$search = $this->request->getPost('search');

            //echo $search;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">Session Timeout! - Please login again</div>';
			} else {
				$all_rec = $this->Crud->filter_user('', '', $log_id, $state_id, $status, $search);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }
				$query = $this->Crud->filter_user($limit, $offset, $log_id, $state_id, $status, $search);

				if(!empty($query)) {
					foreach($query as $q) {
						$id = $q->id;
						$fullname = $q->fullname;
						$email = $q->email;
						$phone = $q->phone;
						$address = $q->address;
						$state = $this->Crud->read_field('id', $q->state_id, 'state', 'name');
						$img = $this->Crud->image($q->img_id, 'big');
						$activate = $q->activate;
						$u_role = $this->Crud->read_field('id', $q->role_id, 'access_role', 'name');
						$reg_date = date('M d, Y h:i A', strtotime($q->reg_date));

						$approved = '';
						if($activate == 1) { 
							$color = 'success';
							$approve_text = 'Account Activated';
							$approved = '<span class="text-primary"><i class="ri-check-circle-line"></i></span> '; 
						} else {
							$color = 'danger';
							$approve_text = 'Account Deactivated';
						}

						// add manage buttons
						if($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '
								<div class="text-right">
									<a href="javascript:;" class="text-primary pop" pageTitle="Manage '.$fullname.'" pageName="'.base_url('auth/users/manage/edit/'.$id).'">
										<i class="ri-ball-pen-line"></i> Edit
									</a><br/><br/>
									<a href="javascript:;" class="text-danger pop" pageTitle="Delete '.$fullname.'" pageName="'.base_url('auth/users/manage/delete/'.$id).'">
										<i class="ri-delete-bin-4-line"></i> Delete
									</a>
								</div>
							';
						}

						$item .= '
							<li class="list-group-item">
								<div class="row pt-7">
									<div class="col-3 col-md-1">
										<img alt="" src="'.site_url($img).'" class="avatar-md rounded-circle img-thumbnail" />
									</div>
									<div class="col-9 col-sm-3 col-md-3 mb-2" >
										<div class="single">
											<div class="text-muted" style="font-size: 12px;">'.$reg_date.'</div>
											<b class="text-primary" style="font-size: 16px;"><a href="javascript:;" class="text-primary pop" pageTitle="'.$fullname.' Profile" pageName="'.base_url('auth/users/manage/profile/'.$id).'" pageSize="modal-lg">'.strtoupper($fullname).'</a>
											</b>
										</div>
									</div>
									<div class="col-12 col-sm-4 col-md-4 mb-1">
										<div class="text-muted font-size-12">'.strtoupper($u_role).'</div>
										<div class="font-size-14" style="font-size:14px">
											'.$email.'<br>
											<span class="text-danger font-size-12">'.$phone.'</span>
										</div>
									</div>
									
									<div class="col-12 col-sm-3 col-md-3 mb-1">
										<div class="font-size-14" style="font-size:14px">
											'.$address.'
											<div><b>'.$state.'</b></div>
										</div>
									</div>
									<div class="col-12 col-sm-1 col-md-1" align="right">
										<b class="font-size-12">'.$all_btn.'</b>
									</div>
								</div>
							</li>
						';
					}
				}
			}
			
			if(empty($item)) {
				$resp['item'] = '
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ri-team-line" style="font-size:150px;"></i><br/><br/>No User Returned
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
            $data['page_active'] = 'users';
			return view('auth/user_form', $data);
		} else { // view for main page
			// for datatable
			//$data['table_rec'] = 'auth/users/list'; // ajax table
			//$data['order_sort'] = '0, "asc"'; // default ordering (0, 'asc')
			//$data['no_sort'] = '5,6'; // sort disable columns (1,3,5)
		
			$data['title'] = 'Users | '.app_name;
			$data['page_active'] = 'users';
			
			return view('auth/user', $data);
		}
    }

	public function register_success() {
        
        
        $data['current_language'] = $this->session->get('current_language');
        return view('payments/success', $data);
    }
	public function register_failed() {
        $data['current_language'] = $this->session->get('current_language');
        return view('payments/failed', $data);
    }

	public function email() {
		$api_key = $this->Crud->read_field('name', 'termil_api', 'setting', 'value'); // pick from DB
		$email_template = $this->Crud->read_field('name', 'termil_email_template', 'setting', 'value'); // pick from DB
		$email = 'tofunmi015@gmail.com';
		// send email
		if($email) {
			$dataa['email_address'] = $email;
			$dataa['code'] = '10000';
			$dataa['api_key'] = $api_key;
			$dataa['email_configuration_id'] = $email_template;
			$resp = $this->Crud->termii('post', 'email/otp/send', $dataa);
		}
		print_r($resp);
	}

	// validate account
	public function validate_account($account='', $bank_id='') {
		if($bank_id && $account) {
		    $resp = $this->Crud->validate_account($account, $bank_id);
		    $resp = json_decode($resp);
			if($resp->status == 'success') {
		        $status = true;
		        $msg = 'Successful';
		        $data = $resp->data->account_name;
				echo  '<span class="text-success font-weight-bold">'.ucwords($data).'</span>';
				echo '<input type="hidden" name="account_name" value="'.$data.'">';
		    } else {
				echo '<span class="text-danger">'.translate_phrase('Account not Found').'</span>';
				echo '<input type="hidden" name="account_name" required>';
			}
		} else {
			echo '<span class="text-warning">'.translate_phrase('All Fields Required').'</span>';
		}
		
	}

	//bank account

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

	public function test(){
		$user = $this->Crud->read('user');
		if(!empty($user)){
			foreach($user as $u){
				$id= $u->id;
				if(!empty($u->qrcode))unlink($u->qrcode);
				$link = site_url('auth/profile_view/') .$id;
				$qr = $this->qrcode($link);
				$path = $qr['file'];
				$this->Crud->updates('id', $id, 'user', array('qrcode'=>$path));
			}
		}
	}

	public function language($lang_id){
		if(!empty($lang_id)){
			$lang = $this->Crud->read_field('id', $lang_id, 'language_code', 'name');
			$this->session->set('current_language', $lang);
			echo '<script>location.reload(false);</script>';
		}
	}
	

}
