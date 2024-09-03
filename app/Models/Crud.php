<?php

namespace App\Models;

use CodeIgniter\Model;

class Crud extends Model {

    public function __construct() {
        $this->session = \Config\Services::session();
        $this->session->start();
		
        
    }

    //////////////////// C - CREATE ///////////////////////
	public function create($table, $data) {
		$db = db_connect();
        $builder = $db->table($table);

        $builder->insert($data);

        return $db->InsertID();
        $db->close();
	}
	
	//////////////////// R - READ /////////////////////////
	public function read($table, $limit='', $offset='') {
        $db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function read_like($table, $or_field, $or_value, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->like($or_field, $or_value);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

    public function read_order($table, $field, $type, $limit='', $offset='') {
        $db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy($field, $type);
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

    public function read_single($field, $value, $table, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->where($field, $value);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function read_singles($field, $value, $table, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'asc');
        $builder->where($field, $value);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

    public function read_single_order($field, $value, $table, $or_field, $or_value, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy($or_field, $or_value);
        $builder->where($field, $value);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function read_single_like($field, $value, $table, $or_field, $or_value, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->like($or_field, $or_value);
        $builder->where($field, $value);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function read2_like($field, $value, $field2, $value2, $table, $or_field, $or_value, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->like($or_field, $or_value);
        $builder->where($field, $value);
        $builder->where($field2, $value2);
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function read3_like($field, $value, $field2, $value2,$field3, $value3, $table, $or_field, $or_value, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->like($or_field, $or_value);
        $builder->where($field, $value);
        $builder->where($field2, $value2);
		$builder->where($field3, $value3);
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	
    public function read2($field, $value, $field2, $value2, $table, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->where($field, $value);
        $builder->where($field2, $value2);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function reads2($field, $value, $field2, $value2, $table, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'asc');
        $builder->where($field, $value);
        $builder->where($field2, $value2);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function reads3($field, $value, $field2, $value2, $field3, $value3,$table, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'asc');
        $builder->where($field, $value);
        $builder->where($field2, $value2);
        $builder->where($field3, $value3);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function reads3_group($field, $value, $field2, $value2, $field3, $value3,$table, $group, $limit='', $offset='') {
		$db = db_connect();
		$db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $builder = $db->table($table);

		$builder->orderBy('id', 'asc');
        $builder->where($field, $value);
        $builder->where($field2, $value2);
        $builder->where($field3, $value3);

		$builder->groupBy($group);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}


	public function read2_order($field, $value, $field2, $value2, $table, $or_field='id', $or_value='DESC', $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy($or_field, $or_value);
        $builder->where($field, $value);
        $builder->where($field2, $value2);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

    public function read3($field, $value, $field2, $value2, $field3, $value3, $table, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->where($field, $value);
        $builder->where($field2, $value2);
        $builder->where($field3, $value3);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}
    

    public function read_field_like($field, $value, $table,$or_field, $or_value, $call) {
		$return_call = '';
		$getresult = $this->read_single_like($field, $value, $table, $or_field, $or_value);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}

	public function read_field($field, $value, $table, $call) {
		$return_call = '';
		$getresult = $this->read_single($field, $value, $table);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}

	public function read_fields($field, $value, $table, $call) {
		$return_call = '';
		$getresult = $this->read_singles($field, $value, $table);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}

    public function read_field2($field, $value, $field2, $value2, $table, $call) {
		$return_call = '';
		$getresult = $this->read2($field, $value, $field2, $value2, $table);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}

	public function read_fields2($field, $value, $field2, $value2, $table, $call) {
		$return_call = '';
		$getresult = $this->reads2($field, $value, $field2, $value2, $table);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}

	

    public function read_field3($field, $value, $field2, $value2, $field3, $value3, $table, $call) {
		$return_call = '';
		$getresult = $this->read3($field, $value, $field2, $value2, $field3, $value3, $table);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}

	public function read_fields3($field, $value, $field2, $value2, $field3, $value3, $table, $call) {
		$return_call = '';
		$getresult = $this->reads3($field, $value, $field2, $value2, $field3, $value3, $table);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}

	public function read_group($table, $group, $limit='', $offset='') {
		$db = db_connect();
		$db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
		$builder->groupBy($group);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function read_single_group($field, $value, $table, $group, $limit='', $offset='') {
		$db = db_connect();
		$db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->where($field, $value);
		$builder->groupBy($group);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function read_single_group_order($field, $value, $table, $group,$or_field='id', $or_value='DESC', $limit='', $offset='') {
		$db = db_connect();
		$db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $builder = $db->table($table);

		$builder->orderBy($or_field, $or_value);
        $builder->where($field, $value);
		$builder->groupBy($group);

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

    public function check0($table){
		$db = db_connect();
        $builder = $db->table($table);
        
        return $builder->countAllResults();
        $db->close();
	}

	public function check($field, $value, $table){
		$db = db_connect();
        $builder = $db->table($table);
        
        $builder->where($field, $value);

        return $builder->countAllResults();
        $db->close();
	}

    public function check2($field, $value, $field2, $value2, $table){
		$db = db_connect();
        $builder = $db->table($table);
        
        $builder->where($field, $value);
        $builder->where($field2, $value2);

        return $builder->countAllResults();
        $db->close();
	}

    public function check3($field, $value, $field2, $value2, $field3, $value3, $table){
		$db = db_connect();
        $builder = $db->table($table);
        
        $builder->where($field, $value);
        $builder->where($field2, $value2);
        $builder->where($field3, $value3);

        return $builder->countAllResults();
        $db->close();
	}

	public function checks($field, $value, $field2, $value2, $field3, $value3, $field4, $value4, $field5, $value5, $table){
		$db = db_connect();
        $builder = $db->table($table);
        
        $builder->where($field, $value);
        $builder->where($field2, $value2);
        $builder->where($field3, $value3);
		$builder->where($field4, $value4);
        $builder->where($field5, $value5);

        return $builder->countAllResults();
        $db->close();
	}

    //////////////////// U - UPDATE ///////////////////////
	public function updates($field, $value, $table, $data) {
		$db = db_connect();
        $builder = $db->table($table);

        $builder->where($field, $value);
        $builder->update($data);
        
        return $db->affectedRows();
        $db->close();
	}
	
	//////////////////// D - DELETE ///////////////////////
	public function deletes($field, $value, $table) {
		$db = db_connect();
        $builder = $db->table($table);

        $builder->where($field, $value);
        $builder->delete();
        
        return $db->affectedRows();
        $db->close();
	}
	public function deletes2($field, $value, $field2, $value2, $table) {
		$db = db_connect();
        $builder = $db->table($table);

        $builder->where($field, $value);
        $builder->where($field2, $value2);
        $builder->delete();
        
        return $db->affectedRows();
        $db->close();
	}
	//////////////////// END DATABASE CRUD ///////////////////////

   //////////////////// DATATABLE AJAX CRUD ///////////////////////
	public function datatable_query($builder, $table, $column_order, $column_search, $order, $where='') {
		// where clause
		if(!empty($where)) {
			foreach($where as $key=>$value) {
		        $builder->where($key, $value);
		    }
		}
 
		// here combine like queries for search processing
		$i = 0;
		if($_POST['search']['value']) {
			foreach($column_search as $item) {
				if($i == 0) {
					$builder->like($item, $_POST['search']['value']);
				} else {
					$builder->orLike($item, $_POST['search']['value']);
				}
				
				$i++;
			}
		}
		 
		// here order processing
		if(isset($_POST['order'])) { // order by click column
			$builder->orderBy($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else { // order by default defined
			$builder->orderBy(key($order), $order[key($order)]);
		}
	}
 
	public function datatable_load($table, $column_order, $column_search, $order, $where='') {
        $db = db_connect();
        $builder = $db->table($table);

		$this->datatable_query($builder, $table, $column_order, $column_search, $order, $where);
		
		if($_POST['length'] != -1) {
			$builder->limit($_POST['length'], $_POST['start']);
		}
		
		$query = $builder->get();
		return $query->getResult();
        $db->close();
	}
 
	public function datatable_filtered($table, $column_order, $column_search, $order, $where='') {
        $db = db_connect();
        $builder = $db->table($table);

		$this->datatable_query($builder, $table, $column_order, $column_search, $order, $where);
		// $query = $builder->get();
		// return $query->num_rows();
        return $builder->countAllResults();
        $db->close();
	}
 
	public function datatable_count($table, $where='') {
		$db = db_connect();
        $builder = $db->table($table);
        
		// where clause
		// if(!empty($where)) {
		// 	$builder->where($field, $value);
		// }

        return $builder->countAllResults();
        $db->close();
	} 
	//////////////////// END DATATABLE AJAX CRUD ///////////////////


    //////////////////// NOTIFICATION CRUD ///////////////////////
	public function notify($from, $to, $content, $item, $item_id) {
	    $ins['from_id'] = $from;
	    $ins['to_id'] = $to;
	    $ins['content'] = $content;
	    $ins['item'] = $item;
	    $ins['item_id'] = $item_id;
	    $ins['new'] = 1;
	    $ins['reg_date'] = date(fdate);
	    
	    $this->create('notify', $ins);
	}
	
	public function msg($type = '', $text = ''){
		if($type == 'success'){
			$icon = 'ni ni-check-c';
			$icon_text = 'Successful!';
		} else if($type == 'info'){
			$icon = 'icon ni ni-info';
			$icon_text = 'Head up!';
		} else if($type == 'warning'){
			$icon = 'icon ni ni-alert-fill-c';
			$icon_text = 'Please check!';
		} else if($type == 'danger'){
			$icon = 'con ni ni-alert-fill-c';
			$icon_text = 'Oops!';
		}
		
		return '
			<div class="alert alert-'.$type.' alert-dismissible fade show" role="alert">
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				<div class="d-flex justify-content-start align-items-center">
					<i class="'.$icon.'" style="font-size:50px; margin-right:10px;"></i>
					<div>'.$text.'</div>
				</div>
			</div>
		';	
	}
	//////////////////// END NOTIFICATION CRUD ///////////////////////

	/////////////////// API CRUD /////////////////////////
	public function api($method='get', $endpoint, $param='') {
		$curl = curl_init();

		$link = site_url('api/').$endpoint;
		
		if($method == 'get') {
			if(!empty($param)) $link .= '?'.$param;
		}

		$key = getenv('api_key');
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		$chead[] = 'Authorization: Bearer '.$key;

		curl_setopt($curl, CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		if($method == 'post') {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param));
		}
		if($method == 'delete') {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param));
		}
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		$result = curl_exec($curl);
		curl_close($curl);

		return $result;

	}

	
	//Check Account Balance
   
	//////////////////// FLUTTERWAVE //////////////////
	public function rave_url($server='') {
		if($server == 'test') return 'https://api.flutterwave.com/v3/';
		return 'https://api.flutterwave.com/v3/';
	}
	
	public function rave_key($type='skey') {
	    $sandbox = $this->read_field('name', 'sandbox', 'setting', 'value');
	    if($sandbox == 'yes') {
	        return $this->read_field('name', 'test_'.$type, 'setting', 'value');
	    } else {
	        return $this->read_field('name', 'live_'.$type, 'setting', 'value');
	    }
	}
	
	public function rave_balance($data='NGN') {
		// create a new cURL resource
		$curl = curl_init();

		// parameters
		$key = $this->rave_key('skey');
		$api_link = 'https://api.flutterwave.com/v3/balances/'.$data;
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		$chead[] = 'Authorization: Bearer '.$key;

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}
	
	public function rave_bvn($bvn) {
		// create a new cURL resource
		$curl = curl_init();

		// parameters
		$key = $this->rave_key('skey');
		$api_link = 'https://api.flutterwave.com/v3/kyc/bvns/'.$bvn;
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		$chead[] = 'Authorization: Bearer '.$key;

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}

	public function google_translate($text = '', $src = '', $target = '') {
		// create a new cURL resource
		$curl = curl_init();

		// parameters
		// $key = $this->rave_key('skey');
		$apiKey = 'AIzaSyAFtOhTbXBh7kfjokt9agzlE8TotA2Al3w'; 
		
		$api_link = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=' . $src . '&target=' . $target;
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		// $chead[] = 'Authorization: Bearer '.$key;

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}
	
	public function rave_withdraw($data) {
		// create a new cURL resource
		$curl = curl_init();

		// parameters
		$key = $this->rave_key('skey');
		$api_link = 'https://api.flutterwave.com/v3/transfers/';
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		$chead[] = 'Authorization: Bearer '.$key;

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_POST, 1);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}

	public function rave_inline($code='', $redir='', $customize='', $amount=0, $customer='', $meta='', $sub='',  $options='card,account,ussd', $curr='NGN') {
		$publicKey = $this->rave_key('pkey');
		$txref = $code;
		$amount = $this->to_number($amount);

		return '
			<script src="https://checkout.flutterwave.com/v3.js"></script>
			<script>
				function ravePay() {
					FlutterwaveCheckout({
						public_key: "'.$publicKey.'",
						tx_ref: "'.$txref.'",
						amount: '.$amount.',
						currency: "'.$curr.'",
						payment_options: "'.$options.'",
						redirect_url: "'.$redir.'",
						customer: '.json_encode($customer).',
						meta: '.json_encode($meta).',
						subaccounts: '.json_encode($sub).',
						customizations: '.json_encode($customize).',
					});
				}
			</script>
		';
	}

	public function rave_get($link, $server='') {
		// create a new cURL resource
		$curl = curl_init();

		$link = $this->rave_url($server).$link;
		$secretKey = $this->rave_key('secret', $server);
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		$chead[] = 'Authorization: Bearer '.$secretKey;

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}

	public function rave_save($user_id, $tnx_id, $item_id='', $item='') {
		$trans_id = 0;
		$status = '';

		$resp = $this->rave_get('transactions/'.$tnx_id.'/verify', pay_server);
		$resp = json_decode($resp);
		if(!empty($resp->status) && $resp->status == 'success') {
			$message = $resp->message;

			$code = $resp->data->tx_ref;
			$tnx_id = $resp->data->id;
			$tnx_ref = $resp->data->flw_ref;
			$status = $resp->data->status;

			$ins['amount'] = $resp->data->amount;
			$ins['app_fee'] = $resp->data->app_fee;
			$ins['payment_type'] = $resp->data->payment_type;
			$ins['card'] = json_encode($resp->data->card);
			$ins['customer'] = json_encode($resp->data->customer);
			$ins['status'] = $status;
			$ins['message'] = $message;

			// check transaction
			if($this->check('tnx_ref', $tnx_ref, 'transaction') > 0) {
				$trans_id = $this->read_field('tnx_ref', $tnx_ref, 'transaction', 'id');
				$this->updates('tnx_ref', $tnx_ref, 'transaction', $ins);
			} else {
				if(!empty($user_id)) $ins['user_id'] = $user_id;
				$ins['code'] = $code;
				if(!empty($item_id)) $ins['item_id'] = $item_id;
				if(!empty($item)) $ins['item'] = json_encode($item);
				$ins['tnx_id'] = $tnx_id;
				$ins['tnx_ref'] = $tnx_ref;
				$ins['reg_date'] = date(fdate);
				$trans_id = $this->create('transaction', $ins);
			}
		}

		return (object)array('id'=>$trans_id, 'status'=>$status);
	}

	public function validate_account($acc_no, $bank_code) {
		// create a new cURL resource
		$curl = curl_init();

		// parameters
		$api_link = 'https://api.flutterwave.com/v3/accounts/resolve';
		$curl_data = array('account_number'=>$acc_no, 'account_bank'=>$bank_code);
		$curl_data = json_encode($curl_data);
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		$chead[] = 'Authorization: Bearer FLWSECK-d4fe580c24ad58ccfd5354f3edab9250-X';
		$chead[] = 'Content-Length: '.strlen($curl_data);

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}

	public function get_bank() {
		// create a new cURL resource
		$curl = curl_init();

		// parameters
		$api_link = 'https://api.flutterwave.com/v3/banks/NG';
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		$chead[] = 'Authorization: Bearer FLWSECK-d4fe580c24ad58ccfd5354f3edab9250-X';

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);


		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}
	//////////////////// END FLUTTERWAVE //////////////////

	///////// TERMII API //////////////////
	public function termii($method='', $endpoint='', $data=[]) {
		$curl = curl_init();
		// $data = array("api_key" => "TL0k0TFm6yJHrHO9hR7cSWH1JoMsZ7bzTNbkve9lVo9zLYyfY81cINAqtS9GOM", "to" => "2347880234567",  "from" => "tidrem","sms" => "Hi there, testing Termii ",  "type" => "plain",  "channel" => "generic" );

		$post_data = json_encode($data);

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.ng.termii.com/api/sms/send",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $post_data,
			CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		
		return $response;
	}

	
	public function bulk_sms($method='', $endpoint='', $data=[]) {
		// create a new cURL resource
		$curl = curl_init();

		$link = 'https://www.bulksmsnigeria.com/api/v2/sms';
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		if($method == 'post') {
			curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}
	//////////////////////////////////////

	///////// SQUAD API //////////////////
	public function providus($method='', $endpoint='', $data=[]) {
		// create a new cURL resource
		$curl = curl_init();
		$sandbox = $this->read_field('name', 'sandbox', 'setting', 'value');
	    if($sandbox == 'yes') {
	        $key = $this->read_field('name', 'test_key', 'setting', 'value');
			$url = $this->read_field('name', 'test_url', 'setting', 'value');
			$sign = $this->token = getenv('X-Auth-Signature');
			$client_id = 'dGVzdF9Qcm92aWR1cw==';
	    } else {
	        $key = $this->read_field('name', 'live_key', 'setting', 'value');
			$url = $this->read_field('name', 'live_url', 'setting', 'value');

			$client_id = $this->read_field('name', 'client_id', 'setting', 'value');
			$client_secret = $this->read_field('name', 'client_secret', 'setting', 'value');

			$clients = $client_id.':'.$client_secret;
			$sign = $key;
	    }
		

		$link = $url.$endpoint;
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		$chead[] = 'Client-Id: '.$client_id;
		$chead[] = 'X-Auth-Signature: '.$sign;

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		if($method == 'post') {
			curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}
	public function update_squad($method='', $endpoint='', $data=[]) {
		// create a new cURL resource
		$curl = curl_init();

		$key = $this->read_field('name', 'squad_secret', 'setting', 'value');

		$link = 'https://api-d.squadco.com/'.$endpoint;
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		$chead[] = 'Authorization: Bearer '.$key;

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		if($method == 'patch') {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}
	//////////////////////////////////////

    //////////////////// FILE UPLOAD //////////////////
    public function file_validate() {
        $validationRule = [
            'pics' => [
                'rules' => 'uploaded[pics]'
                    . '|is_image[pics]'
                    . '|mime_in[pics,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                    . '|max_size[pics,100]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            return false;
        } else {
            return true;
        }
    }

    public function img_upload($path, $file, $width=0, $height=0, $ratio=true, $ration_by='width') {
        // file data
        $name = $file->getName();
        $type = $file->getClientMimeType();
        $filename = $file->getRandomName();

        if(empty($width)) $width = 400;
        if(empty($height)) $height = 400;

        // if directory not exit
        if (!is_dir($path)) mkdir($path, 0755);

        $image = \Config\Services::image()
            ->withFile($file)
            ->resize($width, $height, $ratio, $ration_by)
            ->save($path.$filename);

        $resp_data['path'] = $path.$filename;
        $resp_data['name'] = $name;
        $resp_data['type'] = $type;
        return (object)$resp_data;
    }

    public function save_image($log_id, $path, $name='', $type='') {
        $reg_data['user_id'] = $log_id;
        $reg_data['path'] = $path;
        $reg_data['pics_small'] = $path;
        $reg_data['pics_square'] = $path;
        $reg_data['reg_date'] = date(fdate);
        return $this->create('file', $reg_data);
    }

	public function file_upload($path, $file, $width=0, $height=0, $ratio=true, $ration_by='width') {
        // file data
        $name = $file->getName();
        $type = $file->getClientMimeType();
        $filename = $file->getRandomName();
		$size = $file->getSize();
		$ext = $file->guessExtension();

        // if directory not exit
        if (!is_dir($path)) mkdir($path, 0755);
		$file->move($path, $filename);

        $resp_data['path'] = $path.$filename;
        $resp_data['name'] = $name;
        $resp_data['type'] = $type;
		$resp_data['size'] = $size;
		$resp_data['ext'] = $ext;
        return (object) $resp_data;
    }

	public function save_file($log_id, $path, $ext='txt', $size=0) {
        $reg_data['user_id'] = $log_id;
        $reg_data['path'] = $path;
        $reg_data['ext'] = $ext;
        $reg_data['reg_date'] = date(fdate);
        return $this->create('file', $reg_data);
    }
    //////////////////// END FILE UPLOAD //////////////////

    //////////////////// DATETIME ///////////////////////
	public function date_diff($now, $end, $type='days') {
		$now = new \DateTime($now);
		$end = new \DateTime($end);
		$date_left = $end->getTimestamp() - $now->getTimestamp();
		
		if($type == 'seconds') {
			if($date_left <= 0){$date_left = 0;}
		} else if($type == 'minutes') {
			$date_left = $date_left / 60;
			if($date_left <= 0){$date_left = 0;}
		} else if($type == 'hours') {
			$date_left = $date_left / (60*60);
			if($date_left <= 0){$date_left = 0;}
		} else if($type == 'days') {
			$date_left = $date_left / (60*60*24);
			if($date_left <= 0){$date_left = 0;}
		} else {
			$date_left = $date_left / (60*60*24*365);
			if($date_left <= 0){$date_left = 0;}
		}	
		
		return $date_left;
	}

	
	public function date_range1($firstDate, $col1, $secondDate, $col2,$col3, $val3, $table, $limit='', $offset=''){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where($col3, $val3);
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		 
		 $builder->orderBy('id', 'DESC');
		// limit query
		if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}

		// return query
		return $query->getResult();
		$db->close();
	}

	public function date_range($firstDate, $col1, $secondDate, $col2, $table, $limit='', $offset=''){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		   $builder->orderBy('id', 'DESC');
		   // limit query
		   if($limit && $offset) {
			   $query = $builder->get($limit, $offset);
		   } else if($limit) {
			   $query = $builder->get($limit);
		   } else {
			   $query = $builder->get();
		   }
   
		   // return query
		   return $query->getResult();
		   $db->close();
	}

	public function date_range2($firstDate, $col1, $secondDate, $col2, $col3, $val3, $col4, $val4, $table, $limit='', $offset=''){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where($col3, $val3);
		$builder->where($col4, $val4);		
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		   $builder->orderBy('id', 'DESC');
		   // limit query
		   if($limit && $offset) {
			   $query = $builder->get($limit, $offset);
		   } else if($limit) {
			   $query = $builder->get($limit);
		   } else {
			   $query = $builder->get();
		   }
   
		   // return query
		   return $query->getResult();
		   $db->close();
	}

	public function date_range3($firstDate, $col1, $secondDate, $col2, $col3, $val3, $col4, $val4, $col5, $val5, $table, $limit='', $offset=''){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where($col3, $val3);
		$builder->where($col4, $val4);		
		$builder->where($col5, $val5);		
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		   $builder->orderBy('id', 'DESC');
		   // limit query
		   if($limit && $offset) {
			   $query = $builder->get($limit, $offset);
		   } else if($limit) {
			   $query = $builder->get($limit);
		   } else {
			   $query = $builder->get();
		   }
   
		   // return query
		   return $query->getResult();
		   $db->close();
	}

	public function date_check($firstDate, $col1, $secondDate, $col2, $table){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		$builder->orderBy('id', 'DESC');

        return $builder->countAllResults();
        $db->close();
	}

	

	public function date_group_check1($firstDate, $col1, $secondDate, $col2, $col3, $val3, $group, $table){
		$db = db_connect();
		$db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $builder = $db->table($table);

		$builder->groupBy($group);
		$builder->where($col3, $val3);
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		$builder->orderBy('id', 'DESC');

        return $builder->countAllResults();
        $db->close();
	}

	public function date_check1($firstDate, $col1, $secondDate, $col2, $col3, $val3, $table){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where($col3, $val3);
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		   $builder->orderBy('id', 'DESC');

		   return $builder->countAllResults();
		   $db->close();
	}

	public function date_check2($firstDate, $col1, $secondDate, $col2, $col3, $val3, $col4, $val4, $table){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where($col3, $val3);
		$builder->where($col4, $val4);		
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		$builder->orderBy('id', 'DESC');

		return $builder->countAllResults();
		$db->close();
	}

	public function date_check3($firstDate, $col1, $secondDate, $col2, $col3, $val3, $col4, $val4, $col5, $val5, $table){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where($col3, $val3);
		$builder->where($col4, $val4);		
		$builder->where($col5, $val5);		
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		   $builder->orderBy('id', 'DESC');

		   return $builder->countAllResults();
		   $db->close();
	}

	public function date_check4($firstDate, $col1, $secondDate, $col2, $col3, $val3, $col4, $val4, $col5, $val5,$col6, $val6, $table){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where($col3, $val3);
		$builder->where($col4, $val4);		
		$builder->where($col5, $val5);
		$builder->where($col6, $val6);		
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		   $builder->orderBy('id', 'DESC');

		   return $builder->countAllResults();
		   $db->close();
	}
	//////////////////// END DATETIME ///////////////////////

	//////////////////// IMAGE DATA //////////////////
	public function image($id, $size='small') {
		if($id) {
			if($size == 'small') {
				$path = $this->read_field('id', $id, 'file', 'pics_small');
			} else if($size == 'big') {
				$path = $this->read_field('id', $id, 'file', 'path');
			} else {
				$path = $this->read_field('id', $id, 'file', 'pics_square');
			}
		} 

		if(empty($path) || !file_exists($path)) {
			$path = 'assets/images/avatar.png';
		}

		return $path;
	}
	//////////////////// END /////////////////

	//////////////////// IMAGE DATA //////////////////
	public function file($id) {
		if($id) {
			$ext = $this->read_field('id', $id, 'file', 'ext');
			$ext = str_replace('x', '', $ext);
			$path = 'assets/backend/images/docs/'.$ext.'-128.png';
		} 

		if(empty($path) || !file_exists($path)) {
			$path = 'assets/backend/images/docs/txt-128.png';
		} 

		return $path;
	}
	//////////////////// END /////////////////

	//////////////////// SEND EMAIL //////////////////
	public function send_email($to, $subject, $body, $bcc='') {
		$emailServ = \Config\Services::email();

		$config['charset']  = 'iso-8859-1';
		$config['mailType'] = 'html';
		$config['wordWrap'] = true;
		$emailServ->initialize($config);

		$emailServ->setFrom(push_email, app_name);
		$emailServ->setTo($to);
		if(!empty($bcc)) $emailServ->setBCC($bcc);

		$emailServ->setSubject($subject);
		$temp['body'] = $body;

		$template = view('designs/email', $temp);
		$emailServ->setMessage($template);

		if($emailServ->send()) return true;
		return false;
	}

	public function send_email2($from, $from_name,$to, $subject, $body, $bcc='') {
		$emailServ = \Config\Services::email();

		$config['charset']  = 'iso-8859-1';
		$config['mailType'] = 'html';
		$config['wordWrap'] = true;
		$emailServ->initialize($config);

		$emailServ->setFrom($from, $from_name);
		$emailServ->setTo($to);
		if(!empty($bcc)) $emailServ->setBCC($bcc);

		$emailServ->setSubject($subject);
		$temp['body'] = $body;

		$template = view('designs/email', $temp);
		$emailServ->setMessage($template);

		if($emailServ->send()) return true;
		return false;
	}
	//////////////////// END SEND EMAIL //////////////////

	public function title() {
		return array(
			'Mr',
			'Mrs',
			'Miss',
			'Chief',
			'Engineer',
			'Doctor',
			'Barrister',
			'Pastor',
			'Alhaji',
			'Alhaja',
			'Otunba',
			'Junior',
		);
	}

    public function to_number($text) {
		$number = preg_replace('/\s+/', '', $text); // remove all in between white spaces
		$number = str_replace(',', '', $number); // remove money format
		$number = floatval($number);
		return $number;
	}

	public function to_word(float $number) {
		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen($no);
		$i = 0;
		$str = array();

		$words = array(0 => '', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty', 40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
	
		$digits = array('', 'hundred', 'thousand', 'million', 'billion');
		while( $i < $digits_length ) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
			} else $str[] = null;
		}
		
		$naira = implode('', array_reverse($str));
		$kobo = ($decimal > 0) ? " " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' kobo' : '';
	
		return ($naira ? $naira . 'naira' : '') . $kobo;
	}

	///Name to Image
	public function image_name($fullname){
		$str_cou = str_word_count($fullname);
		if($str_cou == 1){
			$wors = substr($fullname, 0, 1);
		} else {
			$wors = '';
			$wor = explode(' ', $fullname);
			$i = 0;
			foreach($wor as $words){
				if($i < 2){$wors .= substr($words, 0, 1);}
				$i++;
			}
			
		}

		return $wors;
	}

    /// filter user
    public function filter_membership($limit='', $offset='', $log_id, $search='') {
        $db = db_connect();
        $builder = $db->table('user');

        // build query
		$builder->orderBy('id', 'DESC');
		
		$role_id = $this->read_field('name', 'Member', 'access_role', 'id');

		$builder->where('role_id', $role_id);
        if(!empty($search)) {
            $builder->like('fullname', $search);
			$builder->orLike('email', $search);
			$builder->orLike('chat_handle', $search);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	
	public function filter_dept($limit='', $offset='', $search='') {
        $db = db_connect();
        $builder = $db->table('dept');

        // build query
		$builder->orderBy('id', 'asc');
		
        if(!empty($search)) {
            $builder->like('name', $search);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }
	
	public function filter_ministry($limit='', $offset='', $search='') {
        $db = db_connect();
        $builder = $db->table('ministry');

        // build query
		$builder->orderBy('id', 'desc');
		
        if(!empty($search)) {
            $builder->like('name', $search);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }


	public function filter_church($limit='', $offset='', $log_id='', $search='', $type) {
        $db = db_connect();
        $builder = $db->table('church');

        // build query
		$builder->orderBy('id', 'desc');
		
		$builder->where('type', $type);
        if(!empty($search)) {
            $builder->like('name', $search);
        }
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }
	public function filter_service_type($limit='', $offset='', $search='') {
        $db = db_connect();
        $builder = $db->table('service_type');

        // build query
		$builder->orderBy('name', 'asc');
		
        if(!empty($search)) {
            $builder->like('name', $search);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	
	public function filter_cell($limit='', $offset='', $search='') {
        $db = db_connect();
        $builder = $db->table('cells');

        // build query
		$builder->orderBy('id', 'asc');
		
        if(!empty($search)) {
            $builder->like('name', $search);
            $builder->orLike('location', $search, 'both');
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	public function filter_cell_report($limit='', $offset='', $search='') {
        $db = db_connect();
        $builder = $db->table('cell_report');

        // build query
		$builder->orderBy('id', 'desc');
		
        if(!empty($search)) {
            $builder->like('meeting', $search);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	public function filter_service_report($limit='', $offset='', $search='') {
        $db = db_connect();
        $builder = $db->table('service_report');

        // build query
		$builder->orderBy('id', 'desc');
		
        if(!empty($search)) {
            $builder->like('type', $search);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	public function filter_partnership($limit='', $offset='', $search='') {
        $db = db_connect();
        $builder = $db->table('partnership');

        // build query
		$builder->orderBy('id', 'asc');
		
        if(!empty($search)) {
            $builder->like('name', $search);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	public function filter_givings($limit='', $offset='', $search='', $log_id) {
        $db = db_connect();
        $builder = $db->table('partners_history');

		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if($role != 'developer' && $role != 'administrator'){
			$builder->where('member_id', $log_id);
		} 
        // build query
		$builder->orderBy('id', 'desc');
		
        if(!empty($search)) {
            $builder->like('name', $search);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	public function filter_analytics($limit='', $offset='', $search='', $log_id) {
        $db = db_connect();
        $builder = $db->table('partners_history');

		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if($role != 'developer' && $role != 'administrator'){
			$builder->where('member_id', $log_id);
		} 
        // build query
		$builder->orderBy('id', 'asc');
		
        if(!empty($search)) {
            $builder->like('name', $search);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	public function filter_territory($limit='', $offset='', $territory='',$search='') {
        $db = db_connect();
        $builder = $db->table('territory');

        // build query
		$builder->orderBy('id', 'asc');
		// $builder->where('state_id', 316);
		
        if(!empty($search)) {
            $builder->like('name', $search);
        }
		if(!empty($territory) && $territory != 'all') {
            $builder->like('lga_id', $territory);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	public function filter_users($limit='', $offset='', $territory='all',$log_id, $id, $state_id='0', $status='all', $search='', $approve='all', $ref_status='all', $start_date='', $end_date='') {
        $db = db_connect();
        $builder = $db->table('user');
		$builder->where('role_id', $id);
        // build query
		$builder->orderBy('id', 'DESC');
		if(!empty($search)) {
            $builder->like('fullname', $search);
			$builder->orLike('email', $search);
			$builder->orLike('phone', $search);
        }

		if($status != 'all') { 
			$builder->where('activate', $status);
		}
		
		if($territory != 'all' && !empty($territory)) { 
			$builder->where('territory', $territory);
		}
		
		if(!empty($state_id) && $state_id != 0){
			$builder->where('state_id', $state_id);
		}
        

		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	
	public function filter_admins($limit='', $offset='',$log_id, $status='all', $search='', $start_date='', $end_date='') {
        $db = db_connect();
        $builder = $db->table('user');
		$builder->where('is_staff', 1);
        // build query
		$builder->orderBy('id', 'DESC');
		if(!empty($search)) {
            $builder->like('fullname', $search);
			$builder->orLike('email', $search);
			$builder->orLike('phone', $search);
        }

		

		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	public function filter_field($limit='', $offset='', $territory='all',$log_id, $id, $state_id='0', $status='all', $search='', $approve='all', $ref_status='all', $start_date='', $end_date='') {
        $db = db_connect();
        $builder = $db->table('user');
		$builder->where('role_id', 15);
        // build query
		$builder->orderBy('id', 'DESC');


		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if($role != 'developer' && $role != 'administrator'){
			$builder->where('master_id', $log_id);
		} 

		if(!empty($search)) {
            $builder->like('fullname', $search);
			$builder->orLike('email', $search);
			$builder->orLike('phone', $search);
        }

		if($status != 'all') { 
			$builder->where('activate', $status);
		}
		
		if($territory != 'all' && !empty($territory)) { 
			$builder->where('territory', $territory);
		}
		
		if(!empty($state_id) && $state_id != 0){
			$builder->where('state_id', $state_id);
		}
        

		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	//////////////////////////filter admin//////////////////////////////////
    public function filter_admin($limit='', $offset='', $log_id, $state_id='', $status='', $search='') {
        $db = db_connect();
        $builder = $db->table('user');

        // build query
		$builder->orderBy('id', 'DESC');
		$builder->where('role_id', 2);

		if(!empty($status)){
			if($status != 'all') { 
				if($status == 'activated')$builder->where('activate', 1);
				if($status == 'pending')$builder->where('activate', 0);
			}
		} 
		
		if(!empty($search)) {
            $builder->like('fullname', $search);
			$builder->orLike('email', $search);
			$builder->orLike('phone', $search);
        }

		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	//////////////////////////filter support//////////////////////////////////
    public function filter_support($limit='', $offset='', $log_id, $status='', $search='') {
        $db = db_connect();
        $builder = $db->table('support');

        // build query
		$builder->orderBy('id', 'DESC');
		// build query
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if($role != 'developer' && $role != 'administrator'){
			$builder->where('user_id', $log_id);
		} 

			if($status != 'all') { 
				$builder->where('status', $status);
			}
		
		
        if(!empty($search)) {
            $builder->like('title', $search);
			$builder->orLike('details', $search);
        }
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
		
    }

	public function filter_comment($limit='', $offset='', $log_id, $support, $search='') {
        $db = db_connect();
        $builder = $db->table('support_comment');

        // build query
		$builder->orderBy('id', 'asc');

		$builder->where('support_id', $support);
		
		
        if(!empty($search)) {
            $builder->like('title', $search);
			$builder->orLike('details', $search);
        }
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();

	}

	public function filter_referral($limit='', $offset='', $user_id, $search='', $start_date='', $end_date='') {
		$db = db_connect();
		$db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        
        $builder = $db->table('referral');
		
		// build query
		$builder->orderBy('id', 'DESC');
		
		$builder->groupBy('user_id');
		
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function filter_collection($limit='', $offset='', $user_id, $search='', $start_date='', $end_date='') {
		$db = db_connect();
		 
        $builder = $db->table('collection');
		
		// build query
		$builder->orderBy('id', 'DESC');
		
		
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function filter_invoice($limit='', $offset='', $user_id, $search='', $role_id='',$state_id='', $territory='', $master_id='', $operative_id='',$start_date='', $end_date='') {
		$db = db_connect();
		 
        $builder = $db->table('user');
		
		// build query
		$builder->orderBy('id', 'DESC');
		
		
		// filter
		if(!empty($search)) {
            $builder->like('fullname', $search);
        }
		if(!empty($role_id) && $role_id !='all') { $query = $builder->where('role_id', $role_id); }
		if(!empty($state_id) && $state_id !='all') { $query = $builder->where('lga_id', $state_id); }
		if(!empty($territory) && $territory !='all') { $query = $builder->where('territory', $territory); }
		if(!empty($master_id) && $master_id !='all') { $query = $builder->where('master_id', $master_id); }
		if(!empty($operative_id) && $operative_id !='all') {
			$query = $builder->where('referral', $operative_id);
		
		}

		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}


	

	public function filter_referrals($limit='', $offset='', $user_id, $search='', $start_date='', $end_date='') {
		$db = db_connect();
		// $db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        
        $builder = $db->table('referral');
		
		// build query
		$builder->orderBy('id', 'DESC');
		
		// $builder->groupBy('user_id');
		
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}


	//// filter wallet
	public function filter_wallet($limit='', $offset='', $user_id, $type='', $transact='',$search='', $start_date='', $end_date='') {
		$db = db_connect();
        $builder = $db->table('wallet');

        // build query
		$builder->orderBy('id', 'DESC');

		// build query

		if(!empty($user_id)) { $query = $builder->where('user_id', $user_id); }
	
		
		if(!empty($type) && $type != 'all') { $query = $builder->where('item', $type); }
		if(!empty($transact) && $transact != 'all') { $query = $builder->where('type', $transact); }
	
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}
		// limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}
	
	//// filter wallet
	public function filter_transaction($limit='', $offset='', $user_id, $type='', $search='', $lga='', $territory='',$start_date='', $end_date='') {
		$db = db_connect();
        $builder = $db->table('transaction');

        // build query
		$builder->orderBy('id', 'asc');

		// build query
		$role_id = $this->read_field('id', $user_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if($role != 'developer' && $role != 'administrator'){
			$builder->where('user_id', $user_id);
		} 
		// filter
		if(!empty($search)) {
            $builder->like('amount', $search);
        }
		if(!empty($type) && $type != 'all') { $query = $builder->where('payment_type', $type); }
		if(!empty($territory) && $territory != 'all') { $query = $builder->where('territory', $territory); }
		if(!empty($lga) && $lga != 'all') { $query = $builder->where('lga_id', $lga); }
	
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

		// limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function filter_transact($limit='', $offset='', $user_id, $type='', $search='', $lga='', $territory='',$start_date='', $end_date='') {
		$db = db_connect();
        $builder = $db->table('transaction');

        // build query
		$builder->orderBy('id', 'asc');

		// build query
		$builder->where('user_id', $user_id);
		
		// filter
		if(!empty($search)) {
            $builder->like('amount', $search);
        }
		if(!empty($type) && $type != 'all') { $query = $builder->where('payment_type', $type); }
		if(!empty($territory) && $territory != 'all') { $query = $builder->where('territory', $territory); }
		if(!empty($lga) && $lga != 'all') { $query = $builder->where('lga_id', $lga); }
	
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

		// limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function filter_history($limit='', $offset='', $user_id, $search='', $lga='', $territory='',$start_date='', $end_date='') {
		$db = db_connect();
        $builder = $db->table('history');

        // build query
		$builder->orderBy('id', 'DESC');

		// build query
		$role_id = $this->read_field('id', $user_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if($role != 'developer' && $role != 'administrator'){
			$builder->where('user_id', $user_id);
		} 
		// filter
		if(!empty($search)) {
            $builder->like('amount', $search);
        }
		
		if(!empty($territory) && $territory != 'all') { $query = $builder->where('territory', $territory); }
		if(!empty($lga) && $lga != 'all') { $query = $builder->where('lga_id', $lga); }
	
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

		// limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}
	
	public function filter_transactions($limit='', $offset='', $user_id, $type='', $search='', $start_date='', $end_date) {
		$db = db_connect();
        $builder = $db->table('transaction');

        // build query
		$builder->orderBy('id', 'DESC');

		
		// filter
		if(!empty($search)) {
            $builder->like('amount', $search);
			$builder->orLike('code', $search);
			
        }
		if(!empty($type) && $type != 'all') { $query = $builder->where('payment_type', $type); }
	
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

		// limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}
	
	public function filter_voucher($limit='', $offset='', $user_id, $search='', $start_date='', $end_date='') {
		$db = db_connect();
        $builder = $db->table('voucher');

        // build query
		$builder->orderBy('id', 'DESC');

		// build query
		$role_id = $this->read_field('id', $user_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if($role != 'developer' && $role != 'administrator'){
			$builder->where('user_id', $user_id);
		} 
		// filter
		if(!empty($search)) {
            $builder->like('amount', $search);
			$builder->orLike('code', $search);
			
        }
	
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}

		// limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}
	

	//////////////////////////filter pump//////////////////////////////////
    
	public function filter_promotion($limit='', $offset='', $log_id, $partner='', $search='') {
        $db = db_connect();
        $builder = $db->table('promotion');

		
		if(!empty($partner) && $partner != 'all') $builder->where('user_id', $partner);
		
        if(!empty($search)) {
            $builder->like('name', $search);
        }
		// build query
		$builder->orderBy('id', 'DESC');
		
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }
	//////////////////////////filter branch//////////////////////////////////
    
	public function filter_branch($limit='', $offset='', $log_id, $partner='', $search='') {
        $db = db_connect();
        $builder = $db->table('branch');

		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = $this->read_field('id', $role_id, 'access_role', 'name');
		if($role != 'Developer' && $role != 'Administrator'){
			$builder->where('partner_id', $log_id);
		}

		if(!empty($partner) && $partner != 'all') $builder->where('partner_id', $partner);
		
        if(!empty($search)) {
            $builder->like('name', $search);
			$builder->orLike('address', $search);
        }
		// build query
		$builder->orderBy('id', 'DESC');
		
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	//////////////////////////filter admin//////////////////////////////////
    public function filter_customers($limit='', $offset='', $log_id, $state_id='', $status='', $search='') {
        $db = db_connect();
        $builder = $db->table('user');

        // build query
		$builder->orderBy('id', 'DESC');
		$builder->where('role_id', 4);

		if(!empty($status)){
			if($status != 'all') { 
				if($status == 'activated')$builder->where('activate', 1);
				if($status == 'pending')$builder->where('activate', 0);
			}
		} 
		
        if(!empty($search)) {
            $builder->like('fullname', $search);
			$builder->orLike('email', $search);
        }
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	//////////////////////////filter partner//////////////////////////////////
    public function filter_partner($limit='', $offset='', $log_id, $state_id='', $status='', $search='') {
        $db = db_connect();
        $builder = $db->table('user');

        // build query
		$builder->orderBy('id', 'DESC');
		//$builder->where('role_id', 3);
		$builder->where('is_partner', 1);

		if(!empty($status)){
			if($status != 'all') { 
				if($status == 'activated')$builder->where('activate', 1);
				if($status == 'pending')$builder->where('activate', 0);
			}
		} 
		
        if(!empty($search)) {
            $builder->like('fullname', $search);
			$builder->like('email', $search);
        }
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	//////////////////////////filter partner//////////////////////////////////
    public function filter_staff($limit='', $offset='', $log_id, $state_id='', $status='', $search='') {
        $db = db_connect();
        $builder = $db->table('user');

		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$branch_id = $this->read_field('id', $log_id, 'user', 'branch_id');
		$role = $this->read_field('id', $role_id, 'access_role', 'name');
		if($role != 'Developer' && $role != 'Administrator'){
			if($role == 'Manager'){
				$builder->where('branch_id', $branch_id);
			} else {
				$builder->where('partner_id', $log_id);
			}
		}

        // build query
		$builder->orderBy('id', 'DESC');
		$builder->where('is_staff', 1);
		//$builder->where('is_partner', 1);

		if(!empty($status)){
			if($status != 'all') { 
				if($status == 'activated')$builder->where('activate', 1);
				if($status == 'pending')$builder->where('activate', 0);
			}
		} 
		
        if(!empty($search)) {
            $builder->like('fullname', $search);
			$builder->like('email', $search);
        }
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	
	public function filter_quiz($limit='', $offset='', $log_id, $search='') {
        $db = db_connect();
        $builder = $db->table('quiz');

		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = $this->read_field('id', $role_id, 'access_role', 'name');
		if($role == 'Instructor'){
			$builder->where('instructor', $log_id);
		}
        if(!empty($search)) {
            $builder->like('name', $search);
			$builder->orLike('instruction', $search);
        }
		// build query
		$builder->orderBy('id', 'DESC');
		
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }

	
	/// timspan
	public function timespan($datetime) {
        $difference = time() - $datetime;
        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");

        if ($difference > 0) { 
            $ending = 'ago';
        } else { 
            $difference = -$difference;
            $ending = 'to go';
        }
		
		for($j = 0; $difference >= $lengths[$j]; $j++) {
            $difference /= $lengths[$j];
        } 
        $difference = round($difference);

        if($difference != 1) { 
            $period = strtolower($periods[$j].'s');
        } else {
            $period = strtolower($periods[$j]);
        }

        return "$difference $period $ending";
	}

	//////// Location Distance
	public function getDistance($addressFrom, $addressTo, $unit = ''){
		// Google API key
		$apiKey = 'AIzaSyAx0GVgtUc8BYdE7Vd4ijUW2n0786pwCSo';
		
		// Change address format
		$formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
		$formattedAddrTo     = str_replace(' ', '+', $addressTo);
		
		// Geocoding API request with start address
		$geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
		$outputFrom = json_decode($geocodeFrom);
		
		// Geocoding API request with end address
		$geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
		$outputTo = json_decode($geocodeTo);
		if(!empty($outputTo->error_message)){
			return $outputTo->error_message;
		}

		if(!empty($outputFrom->error_message) || !empty($outputTo->error_message)){
			return 0;
		}
		
		// Get latitude and longitude from the geodata
		if(!empty($outputFrom->results[0]) && !empty($outputTo->results[0])){
			$latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
			$longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
			$latitudeTo        = $outputTo->results[0]->geometry->location->lat;
			$longitudeTo    = $outputTo->results[0]->geometry->location->lng;
			
			// Calculate distance between latitude and longitude
			$theta    = $longitudeFrom - $longitudeTo;
			$dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
			$dist    = acos($dist);
			$dist    = rad2deg($dist);
			$miles    = $dist * 60 * 1.1515;
			
			// Convert unit and return distance
			$unit = strtoupper($unit);
			if($unit == "K") {
				return round($miles * 1.609344, 2);
			} elseif($unit == "M") {
				return round($miles * 1609.344, 2);
			} else {
				return round($miles, 2);
			}
		} else {
			// return 0 if distance not found
			return 0;
		}
	}

	////// store activities
	public function activity($item, $item_id, $action) {
		$ins['item'] = $item;
		$ins['item_id'] = $item_id;
		$ins['action'] = $action;
		$ins['reg_date'] = date(fdate);
		return $this->create('activity', $ins);
	}


	//// filter activities
	public function filter_activity($limit='', $offset='', $user_id, $search='', $start_date='', $end_date='') {
		$db = db_connect();
        $builder = $db->table('activity');
		// build query
		$builder->orderBy('id', 'DESC');
		
		$role_id = $this->read_field('id', $user_id, 'user', 'role_id');
		$role = $this->read_field('id', $role_id, 'access_role', 'name');

		if($role != 'Developer' && $role !='Administrator'){
			if($user_id != 0 ){
				$builder->where("item_id", $user_id);
			}
		}
		
		if(!empty($search)) {
			$builder->like('action', $search);
        }
		
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}
		
		 // limit query
		 if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}

	public function filter_notification($limit='', $offset='', $user_id, $search='', $start_date='', $end_date='') {
		$db = db_connect();
        $builder = $db->table('notify');
		// build query
		$builder->orderBy('id', 'DESC');
		
		$role_id = $this->read_field('id', $user_id, 'user', 'role_id');
		$role = $this->read_field('id', $role_id, 'access_role', 'name');

		if($role != 'Developer' && $role !='Administrator'){
			if($user_id != 0 ){
				$builder->where("item_id", $user_id);
			}
		}
		
		if(!empty($search)) {
			$builder->like('content', $search);
        }
		
		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}
		
		 // limit query
		 if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
	}


    /// filter customers
    public function filter_customer($limit='', $offset='', $log_id='', $search='') {
        $db = db_connect();
        $builder = $db->table('user');

        // build query
		$builder->orderBy('id', 'DESC');
        //$builder->where('is_customer', 1);
		$builder->where('role_id', 4);

        if(!empty($search)) {
            $builder->like('firstname', $search);
            $builder->orLike('lastname', $search);
            $builder->orLike('email', $search);
        }
		
        // limit query
        if($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if($limit) {
			$query = $builder->get($limit);
		} else {
            $query = $builder->get();
        }

        // return query
        return $query->getResult();
        $db->close();
    }
	

    //////////////////// MODULE ///////////////////////
	public function module($role, $module, $type) {
		$result = 0;
		
		$mod_id = $this->read_field('link', $module, 'access_module', 'id');
		
		$crud = $this->read_field('role_id', $role, 'access', 'crud');
		if($mod_id) {
			if(!empty($crud)) {
				$crud = json_decode($crud);
				foreach($crud as $cr) {
					$cr = explode('.', $cr);
					if($mod_id == $cr[0]) {
						if($type == 'create'){$result = $cr[1];}
						if($type == 'read'){$result = $cr[2];}
						if($type == 'update'){$result = $cr[3];}
						if($type == 'delete'){$result = $cr[4];}
						break;
					}
				}
			}
		}
		
		return $result;
	}
	public function mod_read($role, $module) {
		$rs = $this->module($role, $module, 'read');
		return $rs;
	}
	//////////////////// END MODULE ///////////////////////

	//Qr code///
	public function qrcode($data=''){
       
        /* Data */
        $hex_data   = bin2hex($data);
        $save_name  = $hex_data . '.png';

        /* QR Code File Directory Initialize */
        $dir = 'assets/images/qr/profile';
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

	public function trade_duration($amount=0, $duration=''){
		$pay = '0';
		if($amount > 0 && !empty($duration)){
			if($duration == 'daily'){
				$pay = (int)$amount / 365;
			}
			if($duration == 'weekly'){
				$pay = (int)$amount / 52;
			}
			if($duration == 'monthly'){
				$pay = (int)$amount / 12;
			}
		}
		return round($pay, 2);
	}

	public function autoRound($value) {
		return ($value >= 0) ? ceil($value) : floor($value);
	}

	public function tax_code($length = 10) {
		$randomInteger = random_int(1000000000, 9999999999);

		return $randomInteger;
	}

	public function getClosestFutureDate(array $dates) {
		// Get the current timestamp
		$currentTimestamp = time();
	
		// Initialize variables
		$closestDate = null;
		$closestTimestampDiff = PHP_INT_MAX;
	
		// Loop through each date in the array
		foreach ($dates as $date) {
			// Convert the date to a timestamp
			$dateTimestamp = strtotime($date);
	
			// Ensure the date is in the future
			if ($dateTimestamp > $currentTimestamp) {
				// Calculate the absolute difference between current timestamp and date timestamp
				$timestampDiff = abs($dateTimestamp - $currentTimestamp);
	
				// Check if the current date is closer than the previously closest date
				if ($timestampDiff < $closestTimestampDiff) {
					$closestTimestampDiff = $timestampDiff;
					$closestDate = $date;
				}
			}
		}
	
		return $closestDate;
	}
	
	public function captureDivAsImage($divId, $outputPath) {
		ob_start();  // Start output buffering
		include 'auth/profile_view';  // Replace with the actual path to your page
		$html = ob_get_clean();  // Get the buffered output and clean the buffer
	
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
	
	
	
	public function processFile($file)
	{
		// Load the Excel reader library
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

		// Load the spreadsheet from the uploaded file
		$spreadsheet = $reader->load($file->getTempName());

		// Get the active sheet
		$sheet = $spreadsheet->getActiveSheet();

		// Get the highest row number (assuming data starts from the first row)
		$highestRow = $sheet->getHighestRow();

		// Get the data from each row

		$data = [];
		$status = false;
		for ($row = 2; $row <= $highestRow; ++$row) {
			
			$firstname = $sheet->getCellByColumnAndRow(1, $row)->getValue();
			$othername = $sheet->getCellByColumnAndRow(2, $row)->getValue();
			$surname = $sheet->getCellByColumnAndRow(3, $row)->getValue();
			$email = $sheet->getCellByColumnAndRow(4, $row)->getValue();
			$phone = $sheet->getCellByColumnAndRow(5, $row)->getValue();
			$address = $sheet->getCellByColumnAndRow(6, $row)->getValue();
			$dobCellValue = $sheet->getCellByColumnAndRow(7, $row)->getValue();
			$marital_status = $sheet->getCellByColumnAndRow(8, $row)->getValue();
			$marriage_anniversary = $sheet->getCellByColumnAndRow(9, $row)->getValue();
			$title = $sheet->getCellByColumnAndRow(10, $row)->getValue();
			$gender = $sheet->getCellByColumnAndRow(11, $row)->getValue();
			$chat_handle = $sheet->getCellByColumnAndRow(12, $row)->getValue();
			$job = $sheet->getCellByColumnAndRow(13, $row)->getValue();
			$foundation_school = $sheet->getCellByColumnAndRow(14, $row)->getValue();
			$baptism = $sheet->getCellByColumnAndRow(15, $row)->getValue();
			$employer_address = $sheet->getCellByColumnAndRow(16, $row)->getValue();
			// echo $firstname.' '.$othername.'<br>';

			$dobFormatted = $this->convertExcelDate($dobCellValue);

			// Add the data to the array
			$data[] = [
				'firstname' => $firstname,
				'othername' => $othername,
				'surname' => $surname,
				'email' => $email,
				'phone' => $phone,
				'address' => $address,
				'dob' => $dobFormatted,
				'marital_status' => $marital_status,
				'marriage_anniversary' => $this->convertExcelDate($marriage_anniversary),
				'title' => $title,
				'gender' => $gender,
				'chat_handle' => $chat_handle,
				'job' => $job,
				'foundation_school' => $foundation_school,
				'baptism' => $baptism,
				'employer_address' => $employer_address,
			];
		
		}

		// Your logic to upload the data to the database
		// $this->uploadToDatabase($data);

		return $data;
	}

	public function convertExcelDate($serialDate){
		// Base date for Excel serial dates (adjust for the 1900 leap year bug)
		$baseDate = strtotime('1900-01-01');
	
		// Check if the value is a numeric date
		if (is_numeric($serialDate)) {
			// Convert Excel serial date to Unix timestamp
			// Excel incorrectly considers 1900 as a leap year, so adjust for it
			$days = $serialDate - 2; // Adjust for the leap year bug and the offset of January 1, 1900
			$timestamp = $baseDate + ($days * 86400); // 86400 seconds in a day
			// Format the date
			return date('Y-m-d', $timestamp);
		} else {
			// Handle non-numeric date values or invalid data
			return $serialDate;
		}
	}
}