<?php

namespace App\Controllers;

class Webhook extends BaseController {
    public function settlement_notif() {

		//retrieve header body
		$headers = apache_request_headers();
		
		$sign = '';
		if(!empty($headers['X-Auth-Signature'])){
			$sign = $headers['X-Auth-Signature'];
		}
		// Retrieve the request's body
		$input = @file_get_contents("php://input");
		if(empty($sign)){
			//rejected Transaction
			$trans['requestSuccessful'] = true;
			$trans['sessionId'] = '';
			$trans['responseMessage'] = 'rejected transaction';
			$trans['responseCode'] = '02';
		} else{
			$body = json_decode($input);
			if(empty($body)){
				//rejected Transaction
				$trans['requestSuccessful'] = true;
				$trans['sessionId'] = '';
				$trans['responseMessage'] = 'rejected transaction';
				$trans['responseCode'] = '02';
			} else {
				// echo $body->sessionId;.
				$this->Crud->create('webhook_response', array('response'=>$input, 'reg_date'=>date(fdate)));


				$session_id = $body->sessionId;
				$settlement_id = $body->settlementId;
				$account_number = $body->accountNumber;
				$amount = $body->transactionAmount;
				$ref = $body->initiationTranRef;
				$remark = $body->tranRemarks;
				$trans_date = $body->tranDateTime;
				
				http_response_code(200);
				$sandbox = $this->Crud->read_field('name', 'sandbox', 'setting', 'value');
				if($sandbox == 'yes') {
					$signs = getenv('X-Auth-Signature');
				} else {
					$key = $this->Crud->read_field('name', 'live_key', 'setting', 'value');
					$signs = $key;
				}
				
				// The Webhook request is from PROVIDUS
				// echo $body->sessionId;
				$trans = [];
				if(!empty($session_id)) {
					//Check Authentication key
					if($signs != $sign){
						//rejected Transaction
						$trans['requestSuccessful'] = true;
						$trans['sessionId'] = $session_id;
						$trans['responseMessage'] = 'rejected transaction';
						$trans['responseCode'] = '02';
					} else {
						//Check session Id if Exist
						if($this->Crud->check('session_id', $session_id, 'webhook') > 0){
							//Duplicate Transaction
							$trans['requestSuccessful'] = true;
							$trans['sessionId'] = $session_id;
							$trans['responseMessage'] = 'duplicate transaction';
							$trans['responseCode'] = '01';
							

						} else {
							
							//Check if Settlment Id is in request body and 

							if(empty($body->settlementId)){
								$trans['requestSuccessful'] = true;
								$trans['sessionId'] = $session_id;
								$trans['responseMessage'] = 'rejected transaction';
								$trans['responseCode'] = '02';
							} else {
								// if Setlement ID exists already
								if($this->Crud->check('settlement_id', $body->settlementId, 'webhook') > 0) {
									$trans['requestSuccessful'] = true;
									$trans['sessionId'] = $session_id;
									$trans['responseMessage'] = 'rejected transaction';
									$trans['responseCode'] = '02';
								} else {

									$this->Crud->create('webhook', array('response'=>$input, 'session_id' => $body->sessionId, 'settlement_id' => $body->settlementId, 'accountNumber'=>$body->accountNumber, 'reg_date'=>date(fdate)));

									//Check if virtual Account number exist on platform
									if($this->Crud->check('acc_no', $account_number, 'virtual_account') == 0){
										//Reject Transaction
										$trans['requestSuccessful'] = true;
										$trans['sessionId'] = $session_id;
										$trans['responseMessage'] = 'rejected transaction';
										$trans['responseCode'] = '02';
									} else {
										// FOR VIRTUAL ACCOUNT

										$trans['requestSuccessful'] = true;
										$trans['sessionId'] = $session_id;
										$trans['responseMessage'] = 'success';
										$trans['responseCode'] = '00';

										//Fund Wallet

										$user_id = $this->Crud->read_field('acc_no', $account_number, 'virtual_account', 'user_id');

										// if($this->Crud->check('id', $user_id, 'user') <= 0) die;

										// echo $user_id;
										$post_datas['payment_method'] = 'bank';
										$post_datas['remark'] = $remark;
										$post_datas['ref'] = $ref;
										$post_datas['amount'] = $amount;
										$post_datas['session_id'] = $session_id;
										$post_datas['trans_date'] = $trans_date;

										//Save transaction in transaction table
										$this->Crud->api('post', 'payments/transaction/'.$user_id, $post_datas);

										$postData['payment_method'] = 'bank';
										$postData['remark'] = $remark;
										$postData['ref'] = $ref;
										$postData['amount'] = $amount;

										//Perform operation on the ttransaction and pay tax
										 $this->Crud->api('post', 'payments/pay_tax/'.$user_id, $postData);

									}

								}
								

							}

						}

					}

					
				
				} else{
					//No Session
					$trans['requestSuccessful'] = true;
					$trans['sessionId'] = $session_id;
					$trans['responseMessage'] = 'system failure, retry';
					$trans['responseCode'] = '03';
				}

			}
			
		}
		
		
		echo json_encode($trans);
		exit();
	}


}
