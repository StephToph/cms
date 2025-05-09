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
	
	// login
	public function login() {
	    $status = false;
		$data = array();
		$msg = '';
		$code = 'info';
		// collect call paramters
		$call = json_decode(file_get_contents("php://input"));
		$user_no = $call->user_no;
		$password = $call->password;
		
		if($user_no && $password) {
			$password = md5($call->password);
			$query = $this->Crud->read2('user_no', $user_no, 'password', $password, 'user');
			
		    if(empty($query)) {
		        $msg = 'Invalid Authentication!';
			} else {
				$act = $this->Crud->check2('user_no', $user_no, 'activate', 0, 'user');
				if ($act > 0) {
					$msg = 'Account not Activated, Please validate account';
				} else {
					$status = true;
					$msg = 'Login Successful!';
					$code = 'success';
					$id = $this->Crud->read_field('user_no', $user_no, 'user', 'id');

					if($this->Crud->read_field('id', $id, 'user', 'church_id') > 0){
						$timezone = $this->Crud->getUserTimezone($id); // e.g. "+01:00" or "Africa/Lagos"
						session()->set('user_timezone', $timezone);
	
						// Optional: apply it immediately
						date_default_timezone_set($timezone);
					}

					$this->Crud->updates('id', $id, 'user', array('last_log'=> date(fdate)));
					///// store activities
					$codes = $this->Crud->read_field('id', $id, 'user', 'firstname').' '.$this->Crud->read_field('id', $id, 'user', 'surname');
					$action = $codes . ' logged in ';
					$this->Crud->activity('authentication', $id, $action, $id);

					$data = $this->user_data($id);
				}
			}
		} else {
			$msg = 'Missing field(s).';
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}


	// logout
	public function logout($user_id=0){
		$status = false;
		$code = 'error';
		$msg = 'Logout failed.';
		$data = [];

		if (!empty($user_id)) {
			$firstname = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
			$surname = $this->Crud->read_field('id', $user_id, 'user', 'surname');

			if ($firstname || $surname) {
				$full_name = $firstname . ' ' . $surname;
				$action = "$full_name logged out";

				// Log activity
				$this->Crud->activity('authentication', $user_id, $action, $user_id);

				$status = true;
				$code = 'success';
				$msg = 'Logout successful.';
			} else {
				$msg = 'User not found.';
			}
		} else {
			$msg = 'Missing user ID.';
		}

		return $this->response->setJSON([
			'status' => $status,
			'code' => $code,
			'msg' => $msg,
			'data' => $data
		]);
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
				if ($this->Crud->updates('id', $user_id, 'user', ['reset' => $reset]) > 0) {
    
					$first_name = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
					$surname = $this->Crud->read_field('id', $user_id, 'user', 'surname');
					$full_name = $first_name . ' ' . $surname;
					$phone = $this->Crud->read_field('email', $email, 'user', 'phone');
				
					// Send reset code via email
					if ($email) {
						$reset_msg = "Hi $first_name,\n\nYour password reset code is: $reset\n\nIf you did not request this, please ignore this message.";
				
						// Using Mailgun integration
						$this->Crud->mailgun($email, 'Password Reset Code', $reset_msg);
					}
				
					$status = true;
					$msg = 'A password reset code has been sent to your registered email address.';
					$data['code'] = $reset;
				}
				
		    }
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg, 'code'=>$code, 'data'=>$data));
		die;
	}

	// Verify the Code
	public function verify_code() {
        $status = false;
        $data = array();
        $msg = '';
		$code = 'info';
	
		// collect call paramters
		$call = json_decode(file_get_contents("php://input"));
		$email = $call->email;
		$otp = $call->otp;

		if($this->Crud->check2('email', $email, 'reset', $otp, 'user') > 0) {
			$status = true;
			$code = 'success';
			
			$msg = 'Successful!';
			$this->Crud->updates('email', $email, 'user', array('reset'=>'', 'activate'=>1));
		} else {
			$msg = 'Invalid OTP';
		}
		

		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
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
					$codez = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
					$action = $codez.' reset Password ';
					$this->Crud->activity('authentication', $user_id, $action, $user_id,);

				} else {
					$msg = 'No Changes..';
				}
		    }
		}
		
		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
		die;
	}

	// profile
	public function profile($type='get',$id=0) {
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
		$call = json_decode(file_get_contents("php://input"));

		// === PERSONAL PROFILE UPDATE ===
		if ($type === 'personal') {
			$surname     = $call->surname ?? '';
			$firstname   = $call->firstname ?? '';
			$othername   = $call->othernames ?? '';
			$chat_handle = $call->chat_handle ?? '';
			$address     = $call->address ?? '';
			$img_id      = $call->img_id ?? '';
			$password    = !empty($call->password) ? md5($call->password) : null;
	
			$datas = [
				'surname'     => $surname,
				'firstname'   => $firstname,
				'othername'   => $othername,
				'chat_handle' => $chat_handle,
				'address'     => $address,
				'img_id'      => $img_id
			];
			if (!empty($password)) {
				$datas['password'] = $password;
			}
	
			if ($this->Crud->updates('id', $id, 'user', $datas) > 0) {
				$status = true;
				$code = 'success';
				$msg = 'Personal profile updated successfully.';
				$data = $this->user_data($id);
			} else {
				$msg = 'No changes made.';
			}
		}
	
		// === CHURCH PROFILE UPDATE ===
		if ($type === 'church') {
			$church_id = $this->Crud->read_field('id', $id, 'user', 'church_id');
	
			$church_data = [
				'name'       => $call->church_name ?? '',
				'address'    => $call->church_address ?? '',
				'country_id' => $call->country_id ?? '',
				'email'      => $call->church_email ?? '',
				'phone'      => $call->church_phone ?? ''
			];
	
			if ($this->Crud->updates('id', $church_id, 'church', $church_data) > 0) {
				$status = true;
				$code = 'success';
				$msg = 'Church profile updated successfully.';
				$data = $this->user_data($id);
			} else {
				$msg = 'No changes made.';
			}
		}
	
		// === CELL PROFILE UPDATE ===
		if ($type === 'cell') {
			$cell_id = $this->Crud->read_field('id', $id, 'user', 'cell_id');
	
			$time_array = [];
			if (!empty($call->days) && !empty($call->times)) {
				foreach ($call->days as $i => $day) {
					$time_array[$day] = $call->times[$i];
				}
			}
	
			$cell_data = [
				'name'     => $call->name ?? '',
				'location' => $call->location ?? '',
				'phone'    => $call->phone ?? '',
				'time'     => json_encode($time_array)
			];
	
			if ($this->Crud->updates('id', $cell_id, 'cells', $cell_data) > 0) {
				$status = true;
				$code = 'success';
				$msg = 'Cell profile updated successfully.';
				$data = $this->user_data($id);
			} else {
				$msg = 'No changes made.';
			}
		}
	
		// === MESSAGE PREFERENCE UPDATE ===
		if ($type === 'message') {
			$value = $call->value ?? 1;
	
			if ($this->Crud->updates('id', $id, 'user', ['receive_message' => $value]) > 0) {
				$status = true;
				$code = 'success';
				$msg = 'Message preference updated.';
			} else {
				$msg = 'No changes made.';
			}
		}

		echo json_encode(array('status'=>$status, 'msg'=>$msg,'code'=>$code, 'data'=>$data));
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


	// ================= CELL MANAGEMENT =================
	private function getCell($log_id = null) {
		$status = false;
		$code = 'error';
		$msg = 'Invalid request';
		$data = [];


		// Example: Fetch all cells for the user
		$data = $this->Crud->filter_cell('', '', '', $log_id);
		$status = true;
		$code = 'success';
		$msg = 'Cells retrieved successfully.';
		

		return $this->response->setJSON(compact('status', 'code', 'msg', 'data'));
	}

	public function cell($type = ''){
        $input = $this->request->getJSON(true);
        $log_id = $input['log_id'] ?? null;

        switch ($type) {
            case 'create':
                return $this->createCell($input);

            case 'update':
                return $this->updateCell($input);

            case 'delete':
                return $this->deleteCell($input);

            case 'message':
                return $this->sendCellMessage($input, $log_id);

            case 'bulk_message':
                return $this->sendBulkMessage($input, $log_id);

            case 'manage_members':
                return $this->manageMembers($input);

            default:
                return $this->getCell($log_id);
        }
    }

    // Create Cell
    private function createCell($input){
        $data = [
            'name'        => $input['name'] ?? '',
            'location'    => $input['location'] ?? '',
            'phone'       => $input['phone'] ?? '',
            'church_id'   => $input['church_id'] ?? '',
            'ministry_id' => $input['ministry_id'] ?? '',
            'time'        => json_encode($input['time'] ?? [])
        ];
		if(empty($input['name']) || empty($input['church_id'])){
			echo json_encode(array('status' => false, 'message' => 'Enter Cell name and Church'));
			die;
		}

        if ($this->Crud->check2('name', $data['name'], 'church_id', $data['church_id'], 'cells') > 0) {
            echo json_encode(array('status' => false, 'message' => 'Cell already exists.'));
			die;
        }

        $id = $this->Crud->create('cells', $data);
		echo json_encode(array('status' => true, 'message' => 'Cell created.', 'cell_id' => $id));
		die;
    }

    // Update Cell
    private function updateCell($input){
        if (empty($input['cell_id'])) {
			echo json_encode(array('status' => false, 'message' => 'Missing cell ID.'));
			die;
        }

		if($this->Crud->check('id', $input['cell_id'], 'cells') == 0){
			echo json_encode(array('status' => false, 'message' => 'Cell ID not Found.'));
			die;
		}

        $data = [
            'name'        => $input['name'] ?? '',
            'location'    => $input['location'] ?? '',
            'phone'       => $input['phone'] ?? '',
            'church_id'   => $input['church_id'] ?? '',
            'ministry_id' => $input['ministry_id'] ?? '',
            'time'        => json_encode($input['time'] ?? [])
        ];

        $updated = $this->Crud->updates('id', $input['cell_id'], 'cells', $data);
        echo json_encode([
            'status' => $updated > 0,
            'message' => $updated > 0 ? 'Cell updated.' : 'No changes made.'
        ]);
		die;
    }

    // Delete Cell
    private function deleteCell($input)
	{
        if (empty($input['cell_id'])) {
			echo json_encode(array('status' => false, 'message' => 'Missing cell ID.'));
			die;
        }

		if($this->Crud->check('id', $input['cell_id'], 'cells') == 0){
			echo json_encode(array('status' => false, 'message' => 'Cell ID not Found.'));
			die;
		}

        $deleted = $this->Crud->deletes('id', $input['cell_id'], 'cells');
        echo json_encode([
            'status' => $deleted > 0,
            'message' => $deleted > 0 ? 'Cell deleted.' : 'Delete failed.'
        ]);
		die;
    }

    // Send Message to One Cell
    private function sendCellMessage($input, $log_id){
        $cell_id = $input['cell_id'];
        $type = $input['type']; // true = all, false = exec only
        $subject = $input['subject'];
        $message = $input['message'];

        $members = $this->filterCellMembers($cell_id, $log_id, $type);
        $sent = $this->sendMessageToUsers($members, $subject, $message, $log_id, $cell_id);

        echo json_encode(['status' => true, 'sent' => $sent, 'message' => "$sent message(s) sent."]);
		die;
	}

    // Bulk Message to Multiple Cells
    private function sendBulkMessage($input, $log_id)
    {
        $cell_ids = $input['cell_id']; // array
        $type = $input['type'];
        $subject = $input['subject'];
        $message = $input['message'];
        $total_sent = 0;

        foreach ($cell_ids as $cell_id) {
            $members = $this->filterCellMembers($cell_id, $log_id, $type);
            $total_sent += $this->sendMessageToUsers($members, $subject, $message, $log_id, $cell_id);
        }

        echo json_encode(['status' => true, 'sent' => $total_sent, 'message' => "$total_sent messages sent."]);
		die;
    }

    // Add/Remove Member from Cell
    private function manageMembers($input)
    {
        $cell_id = $input['cell_id'] ?? '';
        $log_id = $input['log_id'] ?? '';
        $member_id = $input['member_id'] ?? '';
        $action = strtolower($input['action'] ?? '');
		
		if(empty($action) || empty($cell_id)){
			echo json_encode([
				'status' => false,
				'message' => 'Invalid Payload Request'
			]);
			die;
		}
        if ($action === 'add') {
			// Fetch the church of the logged-in user and the member
			$user_church = $this->Crud->read_field('id', $cell_id, 'cells', 'church_id');
			$member_church = $this->Crud->read_field('id', $member_id, 'user', 'church_id');
		
			// Check church mismatch
			if ($user_church != $member_church) {
				echo json_encode([
					'status' => false,
					'message' => 'You can only add members from your church.'
				]);
				die;
			}
		
			// Check if already added to another cell
			if ($this->Crud->check2('cell_id', $cell_id, 'id', $member_id, 'user')) {
				echo json_encode([
					'status' => false,
					'message' => 'Member already added.'
				]);
				die;
			}
		
			// Update user's record with the new cell ID
			$this->Crud->updates('id', $member_id, 'user', [
				'cell_id' => $cell_id
			]);
		
			echo json_encode([
				'status' => true,
				'message' => 'Member added successfully.'
			]);
			die;
		}

		if($action === 'get') {
			$data = $this->filterCellMembers($cell_id, $log_id, 'all');
        
			$status = true;
			$code = 'success';
			$msg = 'Cells Members retrieved successfully.';
			
			return $this->response->setJSON(compact('status', 'code', 'msg', 'data'));
		}

        if ($action === 'remove') {
			// Step 2: Reset cell_id in user table
			$this->Crud->updates('id', $member_id, 'user', [
				'cell_id' => 0 // or 0 depending on your DB schema
			]);
		
			echo json_encode([
				'status' => true,
				'message' => 'Member removed and cell assignment cleared.'
			]);
			die;
		}
		

    }

    // Helper: Filter Cell Members by type
    private function filterCellMembers($cell_id, $log_id, $type)
    {
        $all = $this->Crud->filter_cell_members('', '', $log_id, 'all', '', $cell_id);
        $cell_member_id = $this->Crud->read_field('name', 'Cell Member', 'access_role', 'id');
        $members = [];

        foreach ($all as $m) {
            if ($type === 'false' && $m->cell_role == $cell_member_id) continue;
            $members[] = $m->id;
        }

        return $members;
    }

    // Helper: Send message to members
    private function sendMessageToUsers($user_ids, $subject, $message, $log_id, $cell_id){
        $sent = 0;

        foreach ($user_ids as $user_id) {
            $data = [
                'subject'      => $subject,
                'message'      => $message,
                'from_id'      => $log_id,
                'to_id'        => $user_id,
                'cell_id'      => $cell_id,
                'church_id'    => $this->Crud->read_field('id', $cell_id, 'cells', 'church_id'),
                'ministry_id'  => $this->Crud->read_field('id', $cell_id, 'cells', 'ministry_id'),
                'reg_date'     => date('Y-m-d H:i:s')
            ];

            $msg_id = $this->Crud->create('message', $data);
            if ($msg_id > 0) {
				$email = $this->Crud->read_field('id', $user_id, 'user', 'email');
				$firstname = $this->Crud->read_field('id', $user_id, 'user', 'firstname');
	
				$body = "Dear {$firstname},<br><br>{$message}";
				$head = $subject;
				$church_id = $this->Crud->read_field('id', $cell_id, 'cells', 'church_id');
				$church_name = $this->Crud->read_field('id', $church_id, 'church', 'name');
				// Replace with Mailgun
				$this->Crud->mailgun($email, $head, $body, $church_name);
	
				// Notification and count
				$this->Crud->notify($log_id, $user_id, $message, 'message', $msg_id);
				$sent++;
			}
        }

        return $sent;
    }

	// ================= MEMBERSHIP MANAGEMENT =================
	public function membership($type = 'get', $id = null)
	{
		$status = false;
		$code = 'error';
		$msg = 'Invalid request';
		$data = [];

		$input = json_decode(file_get_contents("php://input"), true);
		$db = db_connect();
		$table = 'user';

		
		if ($type === 'add' || $type === 'edit') {
			$email = strtolower(trim($input['email'] ?? ''));
			$family_position = strtolower(trim($input['family_position'] ?? 'parent'));
			$dept_id = $input['dept_id'] ?? [];
			$dept_role_id = $input['dept_role_id'] ?? [];
			$spouse_id = trim($input['spouse_id'] ?? '');
			$password = trim($input['password'] ?? '');
			$log_id = $input['log_id'] ?? 1;
	
			// Email check logic
			if ($type === 'add') {
				$emailExists = $db->table($table)->where('email', $email)->countAllResults();
				if ($emailExists) {
					if (in_array($family_position, ['parent', 'other'])) {
						return $this->response->setJSON(['status' => false, 'code' => 'danger', 'msg' => 'Email already exists.']);
					}
					if ($family_position == 'child') {
						$childCount = $db->table($table)->where(['email' => $email, 'family_position' => 'child'])->countAllResults();
						if ($childCount >= 5) {
							return $this->response->setJSON(['status' => false, 'code' => 'danger', 'msg' => 'Only 5 children allowed with same email.']);
						}
					}
				}
			}
	
			// Usher logic
			$usher_id = $db->table('dept')->select('id')->where('name', 'Usher')->get()->getRow('id');
			$is_usher = (!empty($usher_id) && in_array($usher_id, $dept_id)) ? 1 : 0;
	
			// Sanitize department/roles
			$sanitized_dept = array_map(fn($v) => htmlspecialchars(trim($v), ENT_QUOTES), $dept_id);
			$sanitized_dept_roles = [];
			foreach ($dept_role_id as $k => $v) {
				$sanitized_dept_roles[htmlspecialchars(trim($k))] = htmlspecialchars(trim($v));
			}
	
			// Get church mapping
			$church_id = trim($input['church_id'] ?? '');
			$church = $db->table('church')->where('id', $church_id)->get()->getRowArray();
			$church_type = $church['type'] ?? '';
			$regional_id = $church['regional_id'] ?? '';
			$zonal_id = $church['zonal_id'] ?? '';
			$group_id = $church['group_id'] ?? '';
	
			// Compose data
			$memberData = [
				'title' => trim($input['title'] ?? ''),
				'firstname' => trim($input['firstname'] ?? ''),
				'surname' => trim($input['lastname'] ?? ''),
				'othername' => trim($input['othername'] ?? ''),
				'gender' => trim($input['gender'] ?? ''),
				'email' => $email,
				'phone' => trim($input['phone'] ?? ''),
				'dob' => trim($input['dob'] ?? ''),
				'address' => trim($input['address'] ?? ''),
				'family_status' => trim($input['family_status'] ?? ''),
				'family_position' => $family_position,
				'parent_id' => trim($input['parent_id'] ?? ''),
				'marriage_anniversary' => trim($input['marriage_anniversary'] ?? ''),
				'baptism' => trim($input['baptism'] ?? ''),
				'foundation_school' => trim($input['foundation_school'] ?? ''),
				'foundation_weeks' => trim($input['foundation_weeks'] ?? ''),
				'chat_handle' => trim($input['chat_handle'] ?? ''),
				'cell_id' => trim($input['cell_id'] ?? ''),
				'cell_role' => trim($input['cell_role_id'] ?? ''),
				'dept_id' => json_encode($sanitized_dept),
				'dept_role' => json_encode($sanitized_dept_roles),
				'church_id' => $church_id,
				'ministry_id' => trim($input['ministry_id'] ?? ''),
				'job_type' => trim($input['job_type'] ?? ''),
				'employer_address' => trim($input['employer_address'] ?? ''),
				'is_archive' => trim($input['archive'] ?? 0),
				'is_duplicate' => trim($input['is_duplicate'] ?? 0),
				'img_id' => trim($input['img_id'] ?? ''),
				'is_member' => 1,
				'is_usher' => $is_usher,
				'church_type' => $church_type,
				'regional_id' => $regional_id,
				'zonal_id' => $zonal_id,
				'group_id' => $group_id
			];
	
			if ($password) {
				$memberData['password'] = md5($password);
			}
	
			// Role Logic
			$cell_role_id = $memberData['cell_role'];
			$cell_member_id = $db->table('access_role')->where('name', 'Cell Member')->get()->getRow('id');
			$role_id = $db->table('access_role')->where('name', 'Member')->get()->getRow('id');
			$cell_role_name = $db->table('access_role')->where('id', $cell_role_id)->get()->getRow('name');
	
			if (in_array($cell_role_name, ['Cell Leader', 'Assistant Cell Leader', 'Cell Executive'])) {
				$role_id = $cell_role_id;
			}
			$memberData['role_id'] = $role_id;
	
			if ($type === 'edit' && $id) {
				$updated = $db->table($table)->where('id', $id)->update($memberData);
				$msg = $updated ? 'Member updated' : 'No changes made';
				$status = $updated;
				$code = $updated ? 'success' : 'info';
				$data = ['id' => $id];
			} else {
				$memberData['reg_date'] = date('Y-m-d H:i:s');
				$inserted = $db->table($table)->insert($memberData);
				if ($inserted) {
					$ins_id = $db->insertID();
					$user_no = 'CEAM-00' . $ins_id;
					$qr_content = 'CEAM-00' . $ins_id;
					$qr =$this->Crud->qrcode($qr_content);
					$db->table($table)->where('id', $ins_id)->update(['user_no' => $user_no, 'qrcode' => $qr['path']]);
	
					// spouse update
					if (!empty($spouse_id)) {
						$db->table($table)->where('id', $spouse_id)->update(['spouse_id' => $ins_id, 'family_status' => 'married']);
					}
	
					// activity
					$by = $db->table($table)->select('firstname')->where('id', $log_id)->get()->getRow('firstname');

					$who = $memberData['surname'] ?? '';
					$action = "$by created Membership ($who)";
					$this->Crud->activity('user', $ins_id, $action, $ins_id);
					

					$status = true;
					$code = 'success';
					$msg = 'Member added successfully';
					$data = ['id' => $ins_id];
				}
			}
	
			return $this->response->setJSON([
				'status' => $status,
				'code'   => $code,
				'msg'    => $msg,
				'data'   => $data
			]);
		}

		if($type == 'get'){
			$log_id = $input['log_id'] ?? '0';
			$cell_id = $input['cell_id'] ?? '0';
			$church_scope = $input['church_scope'] ?? 'all';//own, selected
			$selected_churches = $input['selected_churches'] ?? [];

			$member = $this->Crud->filter_membership('', '', $log_id, '', '', 'false', 0, $church_scope, $selected_churches, $cell_id);
			echo json_encode([
				'status' => true,
				'code'   => 'success',
				'msg'    => 'Member details loaded',
				'member_count' => count($member),
				'data'   => $member
			]);
			die;
		}
	}

	public function view_member($id)
	{
		$db = db_connect();
		$builder = $db->table('user');
		$exists = $builder->where('id', $id)->countAllResults();

		if ($exists > 0) {
			$member = $builder->where('id', $id)->get()->getRow();

			echo json_encode([
				'status' => true,
				'code'   => 'success',
				'msg'    => 'Member details loaded',
				'data'   => $member
			]);
		} else {
			echo json_encode([
				'status' => false,
				'code'   => 'danger',
				'msg'    => 'Member not found'
			]);
		}
		die;
	}

	public function delete_member($id)
	{
		$db = db_connect();
		$builder = $db->table('user');
		$exists = $builder->where('id', $id)->countAllResults();

		if ($exists > 0) {
			$deleted = $builder->delete(['id' => $id]);

			if ($deleted) {
				echo json_encode([
					'status' => true,
					'code'   => 'success',
					'msg'    => 'Member deleted successfully',
					'data'   => []
				]);
			} else {
				echo json_encode([
					'status' => false,
					'code'   => 'danger',
					'msg'    => 'Failed to delete member'
				]);
			}
		} else {
			echo json_encode([
				'status' => false,
				'code'   => 'danger',
				'msg'    => 'Member does not exist'
			]);
		}
		die;
	}

	public function getPartnership($member_id = null){
		if (!$member_id) {
			return $this->response->setJSON([
				'status' => false,
				'message' => 'Member ID is required'
			]);
		}

		// Fetch all available partnerships
		$all_partnerships = $this->Crud->read_order('partnership', 'name', 'asc');

		// Fetch member's partnership goals (stored as JSON: {partnership_id: amount})
		$pledged_json = $this->Crud->read_field('id', $member_id, 'user', 'partnership');
		$pledged_data = !empty($pledged_json) ? json_decode($pledged_json, true) : [];

		$response = [];

		foreach ($all_partnerships as $part) {
			$part_id = $part->id;
			$part_name = $part->name;

			$goal = isset($pledged_data[$part_id]) ? (float)$pledged_data[$part_id] : 0.00;
			$given = 0.00;
			$payments = [];

			// Fetch contributions made to this partnership
			$contributions = $this->Crud->read2('member_id', $member_id, 'partnership_id', $part_id, 'partners_history');
			if (!empty($contributions)) {
				foreach ($contributions as $row) {
					$given += (float)$row->amount_paid;

					$payments[] = [
						'amount_paid' => number_format($row->amount_paid, 2),
						'currency' => $row->currency ?? 'ESP',
						'date' => $row->reg_date,
						'note' => $row->note ?? null
					];
				}
			}

			$balance = $goal - $given;
			if ($balance < 0) $balance = 0.00;

			$response[] = [
				'partnership' => $part_name,
				'pledge'      => number_format($goal, 2),
				'given'       => number_format($given, 2),
				'balance'     => number_format($balance, 2),
				'payments'    => $payments
			];
		}

		return $this->response->setJSON([
			'status' => true,
			'message' => 'Partnership summary retrieved successfully',
			'data' => $response
		]);
	}


	public function send_message($type = 'single', $id = null)
	{
		$status = false;
		$code = 'error';
		$msg = 'Invalid request';
		$data = [];

		$input = json_decode(file_get_contents("php://input"), true);
		$db = db_connect();

		$message = trim($input['message'] ?? '');
		$subject = trim($input['subject'] ?? '');
		$log_id = $input['log_id'] ?? null;

		if (empty($message) || empty($log_id)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'danger',
				'msg' => 'Message and sender are required',
				'data' => []
			]);
		}

		if ($type === 'single' && $id) {
			$member = $db->table('user')->where('id', $id)->get()->getRow();
			if ($member) {
				$church_id = $member->church_id;
				$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
				$name = ucwords($member->firstname . ' ' . $member->surname);
				
				if(empty($subject)){
					return $this->response->setJSON([
						'status' => false,
						'code' => 'danger',
						'msg' => 'Subject is required',
						'data' => []
					]);
				}
				// Store message
				$ins_data = [
					'subject' => $subject,
					'message' => $message,
					'from_id' => $log_id,
					'to_id' => $id,
					'church_id' => $church_id,
					'ministry_id' => $member->ministry_id,
					'reg_date' => date('Y-m-d H:i:s')
				];
				$msg_id = $this->Crud->create('message', $ins_data);

				if ($msg_id > 0) {
					$this->Crud->notify($log_id, $id, $message, 'message', $msg_id);
					$this->Crud->mailgun($member->email, $subject, 'Dear ' . $name . ',<br><br>' . $message, $church);

					// Log activity
					$sender = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
					$receiver = $member->firstname;
					$action = $sender . ' Sent a message to User (' . $receiver . ')';
					$this->Crud->activity('user', $id, $action, $log_id);

					$status = true;
					$code = 'success';
					$msg = 'Message sent to ' . $member->email;
					$data = ['to' => $member->email];
				}
			} else {
				$msg = 'Recipient not found';
			}
		} elseif ($type === 'bulk') {
			$member_ids = $input['member_ids'] ?? [];
			$scount = 0;
			$fcount = 0;

			if (!is_array($member_ids) || count($member_ids) == 0) {
				$msg = 'No recipient IDs provided';
			} else {
				foreach ($member_ids as $member_id) {
					$member = $db->table('user')->where('id', $member_id)->get()->getRow();
					if ($member) {
						$church_id = $member->church_id;
						$church = $this->Crud->read_field('id', $church_id, 'church', 'name');
						$name = ucwords($member->firstname . ' ' . $member->surname);

						$ins_data = [
							'subject' => $subject,
							'message' => $message,
							'from_id' => $log_id,
							'to_id' => $member_id,
							'church_id' => $church_id,
							'ministry_id' => $member->ministry_id,
							'reg_date' => date('Y-m-d H:i:s')
						];
						$msg_id = $this->Crud->create('message', $ins_data);

						if ($msg_id > 0) {
							$scount++;
							$this->Crud->notify($log_id, $member_id, $message, 'message', $msg_id);
							$this->Crud->mailgun($member->email, $subject, 'Dear ' . $name . ',<br><br>' . $message, $church);

							$sender = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$receiver = $member->firstname;
							$action = $sender . ' Sent a message to User (' . $receiver . ')';
							$this->Crud->activity('user', $member_id, $action, $log_id);
						} else {
							$fcount++;
						}
					}
				}

				$status = true;
				$code = 'success';
				$msg = "$scount Message(s) sent, $fcount failed";
				$data = ['sent' => $scount, 'failed' => $fcount];
			}
		}

		return $this->response->setJSON([
			'status' => $status,
			'code' => $code,
			'msg' => $msg,
			'data' => $data
		]);
	}


	public function generate_qr($id)
	{
		$db = db_connect();
		$qr_content = 'USER-00' . $id;
		$qr_path = 'assets/qr/qr_' . $id . '.png';

		// Simulate QR generation
		file_put_contents($qr_path, 'Fake QR for ' . $qr_content);

		$db->table('user')->where('id', $id)->update(['qrcode' => $qr_path]);

		return $this->respond([
			'status' => true,
			'code'   => 'success',
			'msg'    => 'QR generated',
			'data'   => ['qrcode' => $qr_path]
		]);
	}

	// ================= FOLLOW-UP FUNCTIONS =================
	public function follow_up($type = 'get',  $id = null ) {
		$status = false;
		$code = 'error';
		$msg = 'Invalid request';
		$data = [];
	
		$input = json_decode(file_get_contents("php://input"));
		$log_id = $input->log_id ?? null; 
		$category = $input->category ?? 'first_timer'; 

		// Validate category
		$valid_categories = ['first_timer', 'new_convert'];
		if (!in_array($category, $valid_categories)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'invalid_category',
				'msg' => 'Category must be either "first_timer" or "new_convert"',
				'data' => []
			]);
		}

		if ($type === 'visitor' && $id) {
			$data = $this->Crud->read_single('visitor_id', $id, 'follow_up');
			$status = true;
			$code = 'success';
			$msg = 'Visitor follow-up data retrieved.';
		}
	
		if ($type === 'get') {
			// if ID is passed, fetch follow-ups by user
			if ($id) {
				$data = $this->Crud->read_single('id', $id, 'visitors');
				$msg = 'Follow-up records by user retrieved.';
			} else {
				// else, fetch all visitors for the category
				$data = $this->Crud->filter_visitors('', '', $log_id, '', $category, 0, 0, 0, '');
				$msg = ucfirst(str_replace('_', ' ', $category)) . ' records retrieved.';
			}
		}
	
	
		if ($type === 'add' && !empty($input)) {
			$insert = [
				'visitor_id'     => $input->visitor_id ?? null,
				'member_id'      => $input->member_id ?? null,
				'church_id'      => $this->Crud->read_field('id', $input->member_id, 'user', 'church_id') ?? null,
				'ministry_id'      => $this->Crud->read_field('id', $input->member_id, 'user', 'ministry_id') ?? null,
				'date'      => $input->date ?? null,
				'notes'        => $input->notes ?? '',
				'reg_date'    => date('Y-m-d H:i:s'),
				'type'       => $input->type ?? null,
			];
	
			$this->Crud->create('follow_up', $insert);
			$status = true;
			$code = 'success';
			$msg = 'Follow-up added successfully.';
		}
	
		if ($type === 'update' && !empty($input) && $id) {
			$update = [
				'type'         => $input->type ?? null,
				'notes'        => $input->notes ?? null,
				'date' => $input->date ?? null
			];
	
			$this->Crud->updates('id', $id, 'follow_up', $update);
			$status = true;
			$code = 'success';
			$msg = 'Follow-up updated successfully.';
		}
	
		if ($type === 'delete' && $id) {
			$this->Crud->deletes('id', $id, 'follow_up');
			$status = true;
			$code = 'success';
			$msg = 'Follow-up deleted.';
		}
	
		return $this->response->setJSON(compact('status', 'code', 'msg', 'data'));
	}
	

	// ================= CELL ROLE MANAGEMENT =================
	public function cell_role($type = 'get', $id = null) {
		$status = false;
		$code = 'error';
		$msg = 'Invalid request';
		$data = [];

		$input = json_decode(file_get_contents("php://input"));

		if ($type == 'assign' && !empty($input)) {
			$update = [
				'cell_id' => $input->cell_id ?? '',
				'role_id' => $input->role_id ?? ''
			];
			$this->Crud->updates('id', $id, 'user', $update);
			$status = true;
			$code = 'success';
			$msg = 'Cell role assigned.';
		}

		return $this->response->setJSON(compact('status', 'code', 'msg', 'data'));
	}

	// ================= SERVICE MANAGEMENT =================
	public function service($module = '', $action = 'get', $id = null)	{
		$status = false;
		$code = 'error';
		$msg = 'Invalid request';
		$data = [];

		$input = json_decode(file_get_contents("php://input"));
		$log_id = $input->log_id ?? 0;
		$ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
		$church_type = $this->Crud->read_field('id', $log_id, 'user', 'church_type');
		

		if (!in_array($module, ['type', 'schedule'])) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'invalid_module',
				'msg' => 'Module must be "type" or "schedule".',
				'data' => []
			]);
		}

		// -------------------- SERVICE TYPE --------------------
		if ($module === 'type') {
			$table = 'service_type';

			if ($action === 'get') {
				$search = $input->search ?? '';
				$data = $this->Crud->filter_service_type('', '', $search);
				$status = true;
				$code = 'success';
				$msg = 'Service types retrieved.';
			}

			if ($action === 'add' && !empty($input)) {
				$ins_data = [
					'name' => trim($input->name ?? '')
				];

				if ($this->Crud->check('name', $ins_data['name'], $table) > 0) {
					$msg = 'Service type already exists.';
				} else {
					$rec_id = $this->Crud->create($table, $ins_data);
					if ($rec_id > 0) {
						$status = true;
						$code = 'success';
						$msg = 'Service type created.';
					}
				}
			}

			if ($action === 'update' && $id && !empty($input)) {
				$upd_data = [
					'name' => trim($input->name ?? '')
				];
				$this->Crud->updates('id', $id, $table, $upd_data);
				$status = true;
				$code = 'success';
				$msg = 'Service type updated.';
			}

			if ($action === 'delete' && $id) {
				$this->Crud->deletes('id', $id, $table);
				$status = true;
				$code = 'success';
				$msg = 'Service type deleted.';
			}
		}

		// -------------------- SERVICE SCHEDULE --------------------
		if ($module === 'schedule') {
			$table = 'service_schedule';

			if ($action === 'get') {
				$search = $input->search ?? '';
				$data = $this->Crud->filter_service_schedule('', '', $search, $log_id);
				$status = true;
				$code = 'success';
				$msg = 'Service schedules retrieved.';
			}

			if ($action === 'add' && !empty($input)) {
				$scope = $input->scope_type ?? 'own';
				$creator_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
			
				$churches = [];
			
				if ($scope === 'own') {
					$church_id = $input->my_church_id ?? $creator_church_id;
					$churches[] = $church_id;
				} elseif ($scope === 'all') {
					$church_type = $this->Crud->read_field('id', $creator_church_id, 'church', 'type');
					
					if ($church_type === 'region') {
						$ref_col = 'regional_id';
					} elseif ($church_type === 'zone') {
						$ref_col = 'zonal_id';
					} elseif ($church_type === 'group') {
						$ref_col = 'group_id';
					} else {
						$ref_col = 'church_id';
					}
				
					$all_churches = $this->Crud->read_single_order($ref_col, $creator_church_id, 'church', 'id', 'asc');
					$churches = array_column($all_churches, 'id');
				
					// Ensure the creator’s church is included
					if (!in_array($creator_church_id, $churches)) {
						$churches[] = $creator_church_id;
					}
				} elseif ($scope === 'selected') {
					$churches = isset($input->selected_churches) ? $input->selected_churches : [];
				}
				
			
				// prepare the common data
				$common_data = [
					'link' => (int)($input->link ?? 0),
					'type_id' => (int)($input->type_id ?? 0),
					'type' => $input->type ?? 'one-time',
					'recurrence_pattern' => $input->recurrence_pattern ?? 'none',
					'service_date' => $input->service_date ?? null,
					'start_date' => $input->start_date ?? null,
					'occurrences' => (int)($input->occurrences ?? 0),
					'weekly_days' => is_array($input->weekly_days ?? null) ? implode(',', $input->weekly_days) : ($input->weekly_days ?? null),
					'monthly_type' => $input->monthly_type ?? 'dates',
					'monthly_dates' => $input->monthly_dates ?? null,
					'monthly_weeks' => is_array($input->monthly_weeks ?? null) ? implode(',', $input->monthly_weeks) : ($input->monthly_weeks ?? null),
					'monthly_weekdays' => is_array($input->monthly_weekdays ?? null) ? implode(',', $input->monthly_weekdays) : ($input->monthly_weekdays ?? null),
					'yearly_date' => $input->yearly_date ?? null,
					'start_time' => $input->start_time ?? '00:00:00',
					'end_time' => $input->end_time ?? '00:00:00',
					'reg_date' => date('Y-m-d H:i:s')
				];
			
				$inserted = false;
				foreach ($churches as $church_id) {
					$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
			
					$data = $common_data;
					$data['church_id'] = $church_id;
					$data['ministry_id'] = $ministry_id;
			
					$exists = $this->Crud->check3('start_time', $data['start_time'], 'church_id', $church_id, 'type_id', $data['type_id'], $table);
					if ($exists > 0) continue;
			
					$rec_id = $this->Crud->create($table, $data);
					if ($rec_id > 0) {
						$inserted = true;
						$type_name = $this->Crud->read_field('id', $data['type_id'], 'service_type', 'name');
						$user = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$this->Crud->activity('service', $rec_id, "$user created schedule for $type_name (Church ID: $church_id)", $log_id);
					}
				}
			
				if ($inserted) {
					$status = true;
					$code = 'success';
					$msg = 'Service schedule(s) created successfully';
				} else {
					$code = 'warning';
					$msg = 'All schedules already existed';
				}
			}
			if ($action === 'update' && $id && !empty($input)) {
				$church_id = isset($input->church_id) ? (int)$input->church_id : $this->Crud->read_field('id', $log_id, 'user', 'church_id');
				$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
			
				$upd_data = [
					'ministry_id' => $ministry_id,
					'church_id' => $church_id,
					'link' => isset($input->link) ? (int)$input->link : 0,
					'type_id' => isset($input->type_id) ? (int)$input->type_id : 0,
					'type' => $input->type ?? 'one-time',
					'recurrence_pattern' => $input->recurrence_pattern ?? 'none',
					'service_date' => $input->service_date ?? null,
					'start_date' => $input->start_date ?? null,
					'occurrences' => isset($input->occurrences) ? (int)$input->occurrences : 0,
					'weekly_days' => is_array($input->weekly_days ?? null) ? implode(',', $input->weekly_days) : ($input->weekly_days ?? null),
					'monthly_type' => $input->monthly_type ?? 'dates',
					'monthly_dates' => $input->monthly_dates ?? null,
					'monthly_weeks' => is_array($input->monthly_weeks ?? null) ? implode(',', $input->monthly_weeks) : ($input->monthly_weeks ?? null),
					'monthly_weekdays' => is_array($input->monthly_weekdays ?? null) ? implode(',', $input->monthly_weekdays) : ($input->monthly_weekdays ?? null),
					'yearly_date' => $input->yearly_date ?? null,
					'start_time' => $input->start_time ?? '00:00:00',
					'end_time' => $input->end_time ?? '00:00:00'
				];
			
				$this->Crud->updates('id', $id, $table, $upd_data);
			
				// Optional: log the update
				$type_name = $this->Crud->read_field('id', $upd_data['type_id'], 'service_type', 'name');
				$actor = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
				$this->Crud->activity('service', $id, "$actor updated schedule for $type_name (Church ID: $church_id)", $log_id);
			
				$status = true;
				$code = 'success';
				$msg = 'Service schedule updated.';
			}
			

			if ($action === 'delete' && $id) {
				$this->Crud->deletes('id', $id, $table);
				$status = true;
				$code = 'success';
				$msg = 'Service schedule deleted.';
			}
		}

		return $this->response->setJSON(compact('status', 'code', 'msg', 'data'));
	}

	// ================= ATTENDANCE MANAGEMENT =================
	public function attendance($action = 'get', $id = '') {
		$status = false;
		$code = 'error';
		$msg = 'Invalid request';
		$data = [];
		$input = json_decode(file_get_contents("php://input"));
	
		$table = 'service_attendance';

		// GET ATTENDANCE RECORDS
		if ($action === 'get') {
			$service_id = $input->service_id ?? null;
			$church_id = $input->church_id ?? null;

			if (!$service_id || !$church_id) {
				$msg = 'Missing parameters.';
			} else {
				$data = $this->Crud->read2('service_id', $service_id, 'church_id', $church_id, $table);
				$status = true;
				$code = 'success';
				$msg = 'Attendance records retrieved.';
			}
		}

		// MARK AS PRESENT
		elseif ($action === 'mark_present') {
			$member_id = (int)($input->member_id ?? 0);
			$service_id = (int)($input->service_id ?? 0);
			$ministry_id = $this->Crud->read_field('id', $service_id, 'service_report', 'ministry_id');
			$church_id = $this->Crud->read_field('id', $service_id, 'service_report', 'church_id');
			
			$monitor_id = (int)($input->monitor_id ?? 0);
			$monitor_type = $input->monitor_type ?? 'admin';
		
			// ✅ Validate required fields
			if ($member_id === 0 || $service_id === 0) {
				$status = false;
				$code = 'error';
				$msg = 'Member ID and Service ID are required.';
			} else {
				$ins_data = [
					'member_id'     => $member_id,
					'service_id'    => $service_id,
					'church_id'     => $church_id,
					'status'        => 'present',
					'monitor_type'  => $monitor_type,
					'monitor_id'    => $monitor_id,
					'reg_date'      => date('Y-m-d H:i:s')
				];
		
				// 🔍 Check if already marked
				$exists = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_id, $table, 'id');
		
				if ($exists == 0) {
					$this->Crud->create($table, $ins_data);
					$msg = 'Marked as present.';
				} else {
					$this->Crud->updates('id', $exists, $table, ['status' => 'present']);
					$msg = 'Updated to present.';
				}
		
				$status = true;
				$code = 'success';
			}
		}
		

		// MARK AS ABSENT
		elseif ($action === 'mark_absent') {
			$member_id = (int)($input->member_id ?? 0);
			$service_id = (int)($input->service_id ?? 0);
			$church_id = $this->Crud->read_field('id', $service_id, 'service_report', 'church_id');
			$reason = trim($input->reason ?? '');
			$monitor_id = (int)($input->monitor_id ?? 0);
			$monitor_type = $input->monitor_type ?? 'admin';
			$mark = $input->mark ?? true;
		
			// 🛡️ Validate required fields
			if ($member_id === 0 || $service_id === 0 || $church_id === 0 || empty($reason)) {
				$status = false;
				$code = 'error';
				$msg = 'Member ID, Service ID, Church ID, and Reason are required.';
			} else {
				$ins_data = [
					'member_id'     => $member_id,
					'service_id'    => $service_id,
					'church_id'     => $church_id,
					'status'        => 'absent',
					'reason'        => $reason,
					'monitor_type'  => $monitor_type,
					'monitor_id'    => $monitor_id,
					'reg_date'      => date('Y-m-d H:i:s')
				];
		
				// ✅ Check if already marked
				$exists = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_id, $table, 'id');
		
				if (empty($exists)) {
					$this->Crud->create($table, $ins_data);
					$msg = 'Marked as absent.';
				} else {
					if (empty($mark)) {
						$this->Crud->deletes('id', $exists, $table);
						$msg = 'Member unmarked.';
					} else {
						$this->Crud->updates('id', $exists, $table, [
							'status' => 'absent',
							'reason' => $reason
						]);
						$msg = 'Updated to absent.';
					}
				}
		
				$status = true;
				$code = 'success';
			}
		}
		

		// TOGGLE NEW CONVERT
		elseif ($action === 'mark_convert') {
			$member_id = (int)($input->member_id ?? 0);
			$service_id = (int)($input->service_id ?? 0);
			$value = (int)($input->new_convert ?? 0);

			$exists = $this->Crud->read_field2('member_id', $member_id, 'service_id', $service_id, $table, 'id');
			if ($exists > 0) {
				$this->Crud->updates('id', $exists, $table, ['new_convert' => $value]);
				$msg = 'New convert status updated.';
				$status = true;
				$code = 'success';
			} else {
				$msg = 'Attendance record not found.';
			}
		}

		// DELETE ATTENDANCE RECORD
		elseif ($action === 'delete' && $id) {
			$this->Crud->deletes('id', $id, $table);
			$status = true;
			$code = 'success';
			$msg = 'Attendance record deleted.';
		}

		// GET ATTENDANCE METRICS
		elseif ($action === 'metrics') {
			$service_id = (int)($input->service_id ?? 0);
			$church_id = (int)($input->church_id ?? 0);

			$total_members = $this->Crud->check2('church_id', $church_id, 'is_member', 1, 'user');
			$present = $absent = $male = $female = 0;

			$members = $this->Crud->read2('service_id', $service_id, 'church_id', $church_id, $table);
			foreach ($members as $m) {
				if ($m->status == 'present') $present++;
				if ($m->status == 'absent') $absent++;

				$gender = strtolower($this->Crud->read_field('id', $m->member_id, 'user', 'gender'));
				if ($gender == 'male') $male++;
				if ($gender == 'female') $female++;
			}

			$unmarked = $total_members - ($present + $absent);

			$data = compact('total_members', 'present', 'absent', 'male', 'female', 'unmarked');
			$status = true;
			$code = 'success';
			$msg = 'Attendance metrics computed.';
		}
	
	
		return $this->response->setJSON(compact('status', 'code', 'msg', 'data'));
	}
	
	public function report($action = '', $id = null)
	{
		switch ($action) {
			case 'delete':
				return $this->deleteReport($id);
			case 'attendance':
				return $this->getAttendance($id);
			case 'finance':
				return $this->getFinance($id);
			case 'media':
				return $this->uploadMedia($id);
			case 'get-media':
				return $this->getMediaByService($id);
			case 'list-reports':
				return $this->listReports();
			case 'report':
				return $this->loadServiceReport($id);
			case 'attendance-status':
				return $this->getServiceAttendanceStatus($id);
			default:
				return $this->response->setJSON(['status' => false, 'message' => 'Invalid endpoint']);
		}
	}

	private function deleteReport($id)
	{
		$input = json_decode(file_get_contents("php://input"));
		$log_id = $input->log_id ?? null;

		if (!$id || !$log_id) {
			return $this->response->setJSON(['status' => false, 'code' => 'error', 'message' => 'Missing ID or log ID']);
		}

		$table = 'service_report';
		$edit = $this->Crud->read_single('id', $id, $table);

		if (!empty($edit)) {
			if ($this->Crud->deletes('id', $id, $table) > 0) {
				$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
				$this->Crud->activity('service', $id, "$by deleted a Service Report", $log_id);
				return $this->response->setJSON(['status' => true, 'code' => 'success', 'message' => 'Report deleted.']);
			}
		}

		return $this->response->setJSON(['status' => false, 'code' => 'error', 'message' => 'Report not found or deletion failed']);
	}

	private function getMediaByService($id){
		if (!$id) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'error',
				'message' => 'Service ID is required'
			]);
		}

		$media = $this->Crud->read2('type', 'service', 'type_id', $id, 'service_media');

		if (empty($media)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'empty',
				'message' => 'No media files found for this service',
				'data' => []
			]);
		}

		return $this->response->setJSON([
			'status' => true,
			'code' => 'success',
			'message' => 'Media files retrieved',
			'data' => $media
		]);
	}

	private function uploadMedia($id)	{
		helper('filesystem');
		$log_id = $this->request->getPost('log_id');
		$file = $this->request->getFile('file');

		if (!$file || !$id || !$log_id) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'missing_data',
				'message' => 'Missing media, service ID, or log ID.'
			]);
		}

		// ✅ Check if service report exists
		$service_exists = $this->Crud->check('id', $id, 'service_report');
		if (!$service_exists) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'invalid_id',
				'message' => 'Invalid service report ID.'
			]);
		}

		$church_id = $this->Crud->read_field('id', $id, 'service_report', 'church_id');
		$path = 'assets/uploads/gallery/church/' . $church_id . '/';

		if (!is_dir($path)) {
			mkdir($path, 0755, true);
		}

		$uploaded = $this->Crud->file_upload($path, $file);
		if (empty($uploaded->path)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'upload_failed',
				'message' => 'Upload failed. Please try again.'
			]);
		}

		$this->Crud->create('service_media', [
			'type_id'   => $id,
			'type'      => 'service',
			'user_id'   => $log_id,
			'path'      => $uploaded->path,
			'reg_date'  => date('Y-m-d H:i:s')
		]);

		return $this->response->setJSON([
			'status' => true,
			'code' => 'success',
			'message' => 'Media uploaded successfully.'
		]);
	}


	private function listReports()	{
		helper('text');
		$input = json_decode(file_get_contents("php://input"));
		
		$limit             = $input->limit ?? 10;
		$offset            = $input->offset ?? 0;
		$search            = $input->search ?? '';
		$date              = $input->date ?? '';
		$type              = $input->type ?? '';
		$church_scope      = $input->church_scope ?? 'own'; // own, zonal, regional, selected, all
		$selected_churches = $input->selected_churches ?? [];
		$cell_id           = $input->cell_id ?? '';
		$switch_id         = $input->switch_id ?? ''; // optional
		$log_id            = $input->log_id ?? 0;

		if (!$log_id) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'session_timeout',
				'message' => 'Session Timeout! Please log in again.'
			]);
		}

		// 🔄 Total count
		$all_records = $this->Crud->filter_service_report('', '', $search, $log_id, $switch_id, $date, $type, $church_scope, $selected_churches, $cell_id);
		$record_count = !empty($all_records) ? count($all_records) : 0;

		// 🔢 Paginated result
		$paginated_data = $this->Crud->filter_service_report($limit, $offset, $search, $log_id, $switch_id, $date, $type, $church_scope, $selected_churches, $cell_id);

		return $this->response->setJSON([
			'status' => true,
			'code' => 'success',
			'message' => 'Service reports loaded',
			'count' => $record_count,
			'results' => $paginated_data
		]);
	}

	private function getFinance($id)	{
		if (empty($id)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'missing_id',
				'message' => 'Service ID is required.'
			]);
		}

		// ✅ Check if service report exists
		$service_exists = $this->Crud->check('id', $id, 'service_report');
		if (!$service_exists) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'not_found',
				'message' => 'Service report does not exist.'
			]);
		}

		// 🔍 Fetch finance records
		$records = $this->Crud->read_single('service_id', $id, 'service_finance');

		if (empty($records)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'no_data',
				'message' => 'No finance records found for this service.',
				'data' => []
			]);
		}

		// 🔢 Initialize summary and breakdown
		$summary = [
			'tithe' => 0,
			'offering' => 0,
			'partnership' => 0,
			'thanksgiving' => 0,
			'seed' => 0
		];

		$grouped = [
			'tithe' => [],
			'offering' => [],
			'partnership' => [],
			'thanksgiving' => [],
			'seed' => []
		];

		foreach ($records as $record) {
			$type = strtolower($record->finance_type);
			$amount = floatval($record->amount);
			if (isset($summary[$type])) {
				$summary[$type] += $amount;
				$grouped[$type][] = $record;
			}
		}

		return $this->response->setJSON([
			'status' => true,
			'code' => 'success',
			'message' => 'Finance data retrieved successfully.',
			'summary' => $summary,
			'grouped' => $grouped
		]);
	}

	private function getAttendance($id)	{
		if (empty($id)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'missing_id',
				'message' => 'Service ID is required.'
			]);
		}

		// ✅ Check if service report exists
		$service_exists = $this->Crud->check('id', $id, 'service_report');
		if (!$service_exists) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'not_found',
				'message' => 'Service report does not exist.'
			]);
		}

		// 🔄 Initialize counters
		$total = 0;
		$member = 0;
		$guest = 0;
		$male = 0;
		$female = 0;
		$children = 0;

		// 🔍 Member Attendance
		$attended = $this->Crud->read2('status', 'present', 'service_id', $id, 'service_attendance');
		if (!empty($attended)) {
			foreach ($attended as $entry) {
				$total++;
				$member++;

				$gender = strtolower($this->Crud->read_field('id', $entry->member_id, 'user', 'gender'));
				$family_position = strtolower($this->Crud->read_field('id', $entry->member_id, 'user', 'family_position'));

				if ($gender === 'male') $male++;
				if ($gender === 'female') $female++;
				if ($family_position === 'child') $children++;
			}
		}

		// 🧍‍♂️ Guest Attendance
		$guest = $this->Crud->check3('category', 'first_timer', 'source_type', 'service', 'source_id', $id, 'visitors');
		$total += $guest;

		// 📌 Use existing head count if available
		$head_count = $this->Crud->read_field('id', $id, 'service_report', 'attendance');
		if (empty($head_count)) {
			$head_count = $total;
		}

		// ✅ Final Response
		return $this->response->setJSON([
			'status' => true,
			'code' => 'success',
			'message' => 'Attendance data retrieved.',
			'data' => [
				'attendance_id' => $id,
				'head_count' => $head_count,
				'total_attendance' => $total,
				'guest_attendance' => $guest,
				'member_attendance' => $member,
				'male_attendance' => $male,
				'female_attendance' => $female,
				'children_attendance' => $children
			]
		]);
	}

	private function getServiceAttendanceStatus($id)	{
		if (empty($id)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'missing_id',
				'message' => 'Service ID is required.'
			]);
		}

		// ✅ Check if service report exists
		$exists = $this->Crud->check('id', $id, 'service_report');
		if (!$exists) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'invalid_id',
				'message' => 'Service report not found.'
			]);
		}

		// 🔍 Get church ID
		$church_id = $this->Crud->read_field('id', $id, 'service_report', 'church_id');
		if (!$church_id) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'no_church',
				'message' => 'No church linked to this service.'
			]);
		}

		// 🔄 Get all members in the church
		$role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
		$members = $this->Crud->read2('is_member', 1, 'church_id', $church_id, 'user');

		// 🔍 Build member status list
		$list = [];
		foreach ($members as $member) {
			$attendance = $this->Crud->read_field2('member_id', $member->id, 'service_id', $id, 'service_attendance', 'status');
			$list[] = [
				'id' => $member->id,
				'fullname' => $member->firstname . ' ' . $member->surname,
				'gender' => $member->gender,
				'title' => $member->title,
				'status' => $attendance ?: 'not_marked'
			];
		}

		if (empty($list)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'no_member',
				'message' => 'No Member Found in this Church.'
			]);
		}
		return $this->response->setJSON([
			'status' => true,
			'code' => 'success',
			'message' => 'Attendance status loaded',
			'data' => $list
		]);
	}

	private function loadServiceReport($id)	{
		if (empty($id)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'missing_id',
				'message' => 'Service ID is required.'
			]);
		}

		$report = $this->Crud->read_single('id', $id, 'service_report');
		if (empty($report)) {
			return $this->response->setJSON([
				'status' => false,
				'code' => 'not_found',
				'message' => 'Service report not found.'
			]);
		}

		$service = $report[0];

		// 📌 Attendance counts
		$attendance_count = $this->Crud->check2('service_id', $id, 'status', 'present', 'service_attendance');
		$first_timers = $this->Crud->check3('category', 'first_timer', 'source_type', 'service', 'source_id', $id, 'visitors');
		$converts = $this->Crud->check4('new_convert', 1, 'source_type', 'service', 'source_id', $id, 'category', 'first_timer', 'visitors');

		$total_attendance = $service->attendance ?: $attendance_count + $first_timers;

		// 📌 Attendance List (present/absent)
		$attendance_list = [];
		// 📌 Load marked attendance from `service_attendance` table
		$attendance_records = $this->Crud->read_single('service_id', $id, 'service_attendance'); // true for multiple

		$attendance_list = [];

		$records = $this->Crud->read_single('service_id', $id, 'service_attendance');
		if (!empty($records)) {
			foreach ($records as $rec) {
				$status = strtolower($rec->status);
				$reason = $rec->reason ?? '';
				$type = $rec->guest == 1 ? 'guest' : 'member';
				$person_id = $rec->member_id;

				if ($type === 'guest') {
					$name = $this->Crud->read_field('id', $person_id, 'visitors', 'fullname');
				} else {
					$name = $this->Crud->read_field('id', $person_id, 'user', 'firstname') . ' ' .
							$this->Crud->read_field('id', $person_id, 'user', 'surname');
				}

				$attendance_list[] = [
					'id' => $person_id,
					'fullname' => $name,
					'status' => $status,
					'reason' => $status === 'absent' ? $reason : '',
					'type' => $type
				];
			}
		}



		// 📌 Finance Breakdown
		$finance = $this->Crud->read_single('service_id', $id, 'service_finance');
		$finance_summary = [
			'tithe' => 0,
			'offering' => 0,
			'partnership' => 0,
			'thanksgiving' => 0,
			'seed' => 0
		];
		$finance_grouped = [
			'tithe' => [],
			'offering' => [],
			'partnership' => [],
			'thanksgiving' => [],
			'seed' => []
		];

		foreach ($finance as $fin) {
			$type = strtolower($fin->finance_type);
			$amount = floatval($fin->amount);
			if (isset($finance_summary[$type])) {
				$finance_summary[$type] += $amount;
				$finance_grouped[$type][] = $fin;
			}
		}

		// 📌 Media Files
		$media = $this->Crud->read2('type', 'service', 'type_id', $id, 'service_media');

		// 📌 Service Type Name
		$type_name = $this->Crud->read_field('id', $service->type, 'service_type', 'name');

		// ✅ Final JSON Response
		return $this->response->setJSON([
			'status' => true,
			'code' => 'success',
			'message' => 'Service report loaded',
			'data' => [
				'id' => $service->id,
				'type' => $type_name,
				'date' => $service->date,
				'note' => $service->note,
				'reg_date' => $service->reg_date,
				'attendance' => [
					'total' => $total_attendance,
					'members_present' => $attendance_count,
					'first_timers' => $first_timers,
					'converts' => $converts,
					'list' => $attendance_list
				],
				'finance' => [
					'summary' => $finance_summary,
					'breakdown' => $finance_grouped
				],
				'media' => $media
			]
		]);
	}

	public function cell_report($action = '', $id = null)
	{
		switch ($action) {
			case 'delete':
				return $this->deleteCellReport($id);
			case 'attendance':
				return $this->getCellAttendance($id);
			case 'finance':
				return $this->getCellFinance($id);
			case 'list-reports':
				return $this->listCellReports();
			case 'report':
				return $this->loadCellReport($id);
			default:
				return $this->response->setJSON(['status' => false, 'message' => 'Invalid endpoint']);
		}
	}

	private function deleteCellReport($id)	{
		if (!$id) {
			return $this->response->setJSON(['status' => false, 'message' => 'Cell Report ID is required']);
		}

		$exists = $this->Crud->check('id', $id, 'cell_report');
		if (!$exists) {
			return $this->response->setJSON(['status' => false, 'message' => 'Report not found']);
		}

		$this->Crud->deletes('id', $id, 'cell_report');
		return $this->response->setJSON(['status' => true, 'message' => 'Cell report deleted']);
	}

	private function listCellReports()	{
		$input = json_decode(file_get_contents("php://input"));

		$search = $input->search ?? '';
		$start_date = $input->start_date ?? '';
		$end_date = $input->end_date ?? '';
		$cell_id = $input->cell_id ?? '';
		$meeting_type = $input->meeting_type ?? '';
		$region_id = $input->region_id ?? '';
		$zone_id = $input->zone_id ?? '';
		$group_id = $input->group_id ?? '';
		$church_id = $input->church_id ?? '';
		$level = $input->level ?? '';
		$limit = $input->limit ?? 45;
		$offset = $input->offset ?? 0;
		$log_id = $input->log_id ?? null;
		$switch_id = '';

		if (!$log_id) {
			return $this->response->setJSON([
				'status' => false,
				'message' => 'Session timeout or missing log_id'
			]);
		}

		$all_rec = $this->Crud->filter_cell_report('', '', $search, $log_id, $start_date, $end_date, $cell_id, $meeting_type, $region_id, $zone_id, $group_id, $church_id, $level, $switch_id);
		$counts = !empty($all_rec) ? count($all_rec) : 0;

		$query = $this->Crud->filter_cell_report($limit, $offset, $search, $log_id, $start_date, $end_date, $cell_id, $meeting_type, $region_id, $zone_id, $group_id, $church_id, $level, $switch_id);

		$data = [];
		if (!empty($query)) {
			foreach ($query as $q) {
				$decodedConverts = json_decode($q->converts ?? '[]', true);
				$nattend = is_array($decodedConverts) ? count($decodedConverts) : 0;

				$first_timer = $this->Crud->check3('category','first_timer', 'source_type', 'cell', 'source_id', $q->id, 'visitors');
				$attendance = $q->attendance ?? (int)$first_timer;

				$convert = $this->Crud->check4('new_convert', 1, 'category','first_timer', 'source_type', 'cell', 'source_id', $q->id, 'visitors');
				$new_convert = (int)$convert + (int)$nattend;

				// Human-readable service type
				$types = [
					'wk1' => 'WK1 - Prayer and Planning',
					'wk2' => 'WK2 - Bible Study',
					'wk3' => 'WK3 - Bible Study',
					'wk4' => 'WK4 - Fellowship / Outreach',
					'wk5' => 'WK5 - Fellowship'
				];
				$type_text = $types[$q->type] ?? ucfirst($q->type);

				$data[] = [
					'id' => $q->id,
					'date' => date('d M Y', strtotime($q->date)),
					'reg_date' => $q->reg_date,
					'type' => $q->type,
					'type_text' => $type_text,
					'attendance' => $attendance,
					'first_timer' => $first_timer,
					'new_convert' => $new_convert,
					'offering' => (float) $q->offering,
					'cell_id' => $q->cell_id,
					'cell_name' => $this->Crud->read_field('id', $q->cell_id, 'cells', 'name'),
					'church_id' => $q->church_id,
					'church_name' => $this->Crud->read_field('id', $q->church_id, 'church', 'name')
				];
			}
		}

		return $this->response->setJSON([
			'status' => true,
			'code' => 'success',
			'message' => 'Cell reports retrieved',
			'count' => $counts,
			'limit' => $limit,
			'offset' => $offset + $limit,
			'left' => $counts - ($offset + $limit),
			'data' => $data
		]);
	}

	private function loadCellReport($id)	{
		if (empty($id)) {
			return $this->response->setJSON([
				'status' => false,
				'message' => 'Cell report ID is required.'
			]);
		}

		$report = $this->Crud->read_single('id', $id, 'cell_report');
		if (empty($report)) {
			return $this->response->setJSON([
				'status' => false,
				'message' => 'Cell report not found.'
			]);
		}

		$data = [];
		foreach ($report as $e) {
			$type = $e->type;
			$types = '';
			if($type == 'wk1')$types = 'WK1 - Prayer and Planning';
			if($type == 'wk2')$types = 'Wk2 - Bible Study';
			if($type == 'wk3')$types = 'Wk3 - Bible Study';
			if($type == 'wk4')$types = 'Wk4 - Fellowship / Outreach';
			if($type == 'wk5')$types = 'Wk5 - Fellowship';
						
			$guest_timers = $this->Crud->check3('source_id', $e->id, 'source_type', 'cell', 'category', 'first_timer', 'visitors');
			$member_converts = $this->Crud->check3('service_id', $e->id, 'new_convert', 1, 'status', 'present', 'service_attendance');
			$guest_converts = $this->Crud->check4('new_convert', 1, 'source_id', $e->id, 'source_type', 'cell', 'category', 'first_timer', 'visitors');
			$finance_data = $this->Crud->read_single('service_id', $e->id, 'service_finance');

			// attendance fallback
			$attendance = $e->attendance;
			if (empty($attendance)) {
				$attendance = $this->Crud->check2('service_id', $e->id, 'status', 'present', 'service_attendance') + $guest_timers;
			}

			// financials
			$total_offering = 0;
			$total_tithe = 0;
			$total_seed = 0;
			$total_partnership = 0;
			$total_thanksgiving = 0;

			if (!empty($finance_data)) {
				foreach ($finance_data as $f) {
					switch ($f->finance_type) {
						case 'offering': $total_offering += $f->amount; break;
						case 'tithe': $total_tithe += $f->amount; break;
						case 'seed': $total_seed += $f->amount; break;
						case 'partnership': $total_partnership += $f->amount; break;
						case 'thanksgiving': $total_thanksgiving += $f->amount; break;
					}
				}
			}

			$data = [
				'id' => $e->id,
				'type' => $types,
				'date' => $e->date,
				'attendance' => $attendance,
				'note' => $e->note,
				'first_timer' => $guest_timers,
				'new_convert' => $member_converts + $guest_converts,
				'total_offering' => $total_offering,
				'total_tithe' => $total_tithe,
				'total_seed' => $total_seed,
				'total_partnership' => $total_partnership,
				'total_thanksgiving' => $total_thanksgiving,
				'finance' => $finance_data,
				'member_attendance' => $this->Crud->read_single('service_id', $e->id, 'service_attendance'),
				'guest_attendance' => $this->Crud->read3('source_id', $e->id, 'source_type', 'cell', 'category', 'first_timer', 'visitors'),
				'convert_member' => json_decode($e->converts, true),
				'convert_guest' => $this->Crud->read4('new_convert', 1, 'source_id', $e->id, 'source_type', 'cell', 'category', 'first_timer', 'visitors'),
			];
		}

		return $this->response->setJSON([
			'status' => true,
			'code' => 'success',
			'message' => 'Cell report loaded successfully',
			'data' => $data
		]);
	}

	private function getCellAttendance($id)	{
		if (empty($id)) {
			return $this->response->setJSON([
				'status' => false,
				'message' => 'Cell report ID is required.'
			]);
		}

		// Read from the attendance table
		$attendance_data = $this->Crud->read2('type_id', $id, 'type', 'cell', 'attendance');
		if (empty($attendance_data)) {
			return $this->response->setJSON([
				'status' => false,
				'message' => 'Attendance record not found.'
			]);
		}
		$attendance_list = [];

		foreach ($attendance_data as $record) {
			$attendants = json_decode($record->attendant, true);
		
			if (is_array($attendants)) {
				foreach ($attendants as $member_id) {
					$fullname = $this->Crud->read_field('id', $member_id, 'user', 'firstname') . ' ' .
								$this->Crud->read_field('id', $member_id, 'user', 'surname');
		
					$attendance_list[] = [
						'id' => $member_id,
						'fullname' => $fullname,
						'status' => 'present', // Default status
						'reason' => ''         // No reason stored in this format
					];
				}
			}
		}
		
		// Load guest attendance (first timers)
		$guest_attendance = $this->Crud->read3('source_id', $id, 'source_type', 'cell', 'category', 'first_timer', 'visitors');
		$guests = [];
		if (!empty($guest_attendance)) {
			foreach ($guest_attendance as $g) {
				$guests[] = [
					'id' => $g->id,
					'fullname' => $g->fullname,
					'email' => $g->email,
					'phone' => $g->phone,
					'status' => 'present', // guests are considered present unless otherwise tracked
				];
			}
		}

		return $this->response->setJSON([
			'status' => true,
			'message' => 'Cell attendance data loaded successfully.',
			'data' => [
				'member_attendance' => $attendance_list,
				'guest_attendance' => $guests
			]
		]);
	}

	private function getCellFinance($id){
		$report = $this->Crud->read_single('id', $id, 'cell_report');

		if (empty($report)) {
			return $this->response->setJSON([
				'status' => false,
				'message' => 'Cell Report not found.'
			]);
		}

		$entry = $report[0];
		$finance_data = [
			'total_offering' => (float)$entry->offering,
			'member_offering' => 0,
			'guest_offering' => 0,
			'items' => []
		];

		$offering_json = $entry->offering_givers ?? '';
		if (!empty($offering_json)) {
			$decoded = json_decode($offering_json, true);

			$finance_data['member_offering'] = isset($decoded['member']) ? (float)$decoded['member'] : 0;
			$finance_data['guest_offering'] = isset($decoded['guest']) ? (float)$decoded['guest'] : 0;

			if (isset($decoded['list']) && is_array($decoded['list'])) {
				foreach ($decoded['list'] as $user_id => $amount) {
					$fullname = $this->Crud->read_field('id', $user_id, 'user', 'firstname') . ' ' .
								$this->Crud->read_field('id', $user_id, 'user', 'surname');

					$finance_data['items'][] = [
						'id' => $user_id,
						'fullname' => $fullname,
						'amount' => (float)$amount,
						'type' => 'Member'
					];
				}
			}

			if (isset($decoded['guest_list']) && is_array($decoded['guest_list'])) {
				foreach ($decoded['guest_list'] as $guest_id => $amount) {
					$finance_data['items'][] = [
						'id' => $guest_id,
						'fullname' => 'Guest #' . $guest_id,
						'amount' => (float)$amount,
						'type' => 'Guest'
					];
				}
			}
		}

		return $this->response->setJSON([
			'status' => true,
			'message' => 'Finance data retrieved successfully.',
			'data' => $finance_data
		]);
	}

	public function event($action = '', $id = null)	{
		switch ($action) {
			case 'create':
				return $this->createEvent();
			case 'edit':
				return $this->editEvent($id);
			case 'delete':
				return $this->deleteEvent($id);
			case 'view':
				return $this->viewEvent($id);
			case 'list':
				return $this->listEvents();
			default:
				return $this->response->setJSON(['status' => false, 'message' => 'Invalid endpoint']);
		}
	}

	protected function viewEvent($id){
		$event = $this->Crud->read_single('id', $id, 'events');
		return $this->response->setJSON([
			'status' => !empty($event),
			'event' => $event[0] ?? null
		]);
	}

	protected function listEvents(){
		$events = $this->Crud->read_order('events', 'created_at', 'desc');
		return $this->response->setJSON([
			'status' => true,
			'count' => count($events),
			'data' => $events
		]);
	}

	protected function deleteEvent($id)	{
		if (!$id) return $this->response->setJSON(['status' => false, 'message' => 'Event ID required']);

		$deleted = $this->Crud->deletes('id', $id, 'events');
		return $this->response->setJSON([
			'status' => $deleted > 0,
			'message' => $deleted > 0 ? 'Event deleted' : 'Delete failed'
		]);
	}

	protected function editEvent($id)	{
		$data = json_decode(file_get_contents("php://input"));
		if (!$data || !$id) return $this->response->setJSON(['status' => false, 'message' => 'Invalid request']);

		$update_data = [
			'title' => $data->title ?? '',
			'description' => $data->description ?? '',
			'start_date' => $data->start_date ?? '',
			'start_time' => $data->start_time ?? '',
			'end_date' => $data->end_date ?? '',
			'end_time' => $data->end_time ?? '',
			'location' => $data->location ?? '',
			'venue' => $data->venue ?? '',
			'event_type' => $data->event_type ?? '',
			'recurrence_pattern' => $data->recurrence_pattern ?? '',
			'pattern' => $data->pattern ?? '',
			'church_id' => json_encode($data->church_id ?? []),
			'event_for' => $data->event_for ?? '',
			'church_type' => $data->church_type ?? '',
			'updated_at' => date('Y-m-d H:i:s')
		];

		$updated = $this->Crud->updates('id', $id, 'events', $update_data);
		return $this->response->setJSON([
			'status' => $updated > 0,
			'message' => $updated > 0 ? 'Event updated successfully' : 'No changes made'
		]);
	}


	protected function createEvent() {
		$data = json_decode(file_get_contents("php://input"));
		if (!$data) return $this->response->setJSON(['status' => false, 'message' => 'Invalid JSON']);

		$title = $data->title ?? '';
		$ministry_id = $data->ministry_id ?? 0;
		$table = 'events';

		// Check if event with same title already exists for the ministry
		if ($this->Crud->check2('title', $title, 'ministry_id', $ministry_id, $table) > 0) {
			return $this->response->setJSON(['status' => false, 'message' => 'Event Already Exists']);
		}

		$ins_data = [
			'title' => $title,
			'description' => $data->description ?? '',
			'start_date' => $data->start_date ?? '',
			'start_time' => $data->start_time ?? '',
			'end_date' => $data->end_date ?? '',
			'end_time' => $data->end_time ?? '',
			'location' => $data->location ?? '',
			'venue' => $data->venue ?? '',
			'event_type' => $data->event_type ?? 'one-time',
			'recurrence_pattern' => $data->recurrence_pattern ?? '',
			'pattern' => $data->pattern ?? '',
			'church_id' => json_encode($data->church_id ?? []),
			'ministry_id' => $ministry_id,
			'event_for' => $data->event_for ?? '',
			'church_type' => $data->church_type ?? '',
			'image' => $data->image ?? '',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		];

		$insert_id = $this->Crud->create($table, $ins_data);

		if ($insert_id > 0) {
			return $this->response->setJSON(['status' => true, 'message' => 'Event Created', 'event_id' => $insert_id]);
		} else {
			return $this->response->setJSON(['status' => false, 'message' => 'Event creation failed']);
		}
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
	public function user_data($id)	{
		$data = [];

		$query = $this->Crud->read_single('id', $id, 'user');
		if (!empty($query)) {
			foreach ($query as $q) {
				$data['id']          = $q->id;
				$data['user_no']     = $q->user_no;
				$data['email']       = $q->email;
				$data['phone']       = $q->phone;
				$data['qrcode']      = $q->qrcode;
				$data['surname']     = $q->surname;
				$data['firstname']   = $q->firstname;
				$data['othername']   = $q->othername;
				$data['address']     = $q->address;
				$data['chat_handle'] = $q->chat_handle;
				$data['role_id']     = $q->role_id;
				$data['is_admin']    = $q->is_admin;
				$data['cell_id']     = $q->cell_id;
				$data['church_id']   = $q->church_id;
				$data['ministry_id'] = $q->ministry_id;
				$data['reg_date']    = date('M d, Y h:i A', strtotime($q->reg_date));
				$data['fullname']    = $q->firstname . ' ' . $q->surname;

				// Related Data
				$data['role']          = $this->Crud->read_field('id', $q->role_id, 'access_role', 'name');

				// Cell Info
				$data['cell']          = $this->Crud->read_field('id', $q->cell_id, 'cells', 'name');
				$data['cell_phone']    = $this->Crud->read_field('id', $q->cell_id, 'cells', 'phone');
				$data['cell_location'] = $this->Crud->read_field('id', $q->cell_id, 'cells', 'location');
				$data['cell_time']     = $this->Crud->read_field('id', $q->cell_id, 'cells', 'time');

				// Church Info
				$data['church_email']    = $this->Crud->read_field('id', $q->church_id, 'church', 'email');
				$data['church_address']  = $this->Crud->read_field('id', $q->church_id, 'church', 'address');
				$data['church_phone']    = $this->Crud->read_field('id', $q->church_id, 'church', 'phone');
				$data['church_name']     = $this->Crud->read_field('id', $q->church_id, 'church', 'name');
				$data['church_region']   = $this->Crud->read_field('id', $q->church_id, 'church', 'regional_id');
				$data['church_zone']     = $this->Crud->read_field('id', $q->church_id, 'church', 'zonal_id');
				$data['church_group']    = $this->Crud->read_field('id', $q->church_id, 'church', 'group_id');
				$data['church_country']  = $this->Crud->read_field('id', $q->church_id, 'church', 'country_id');
				$data['church_currency'] = $this->Crud->read_field('id', $q->church_id, 'church', 'default_currency');

				// Pastor-in-Charge
				$pastor_role_id = $this->Crud->read_field('name', 'Pastor-in-Charge', 'access_role', 'id');
				$data['church_pastor'] = $this->Crud->read_field2('church_id', $q->church_id, 'role_id', $pastor_role_id, 'user', 'id');
			}
		}

		return $data;
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
