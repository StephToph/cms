<?php

namespace App\Models;

use CodeIgniter\Model;

use Firebase\JWT\JWT;
use App\Libraries\Ciqrcode;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Crud extends Model {
	protected $privateKeyPath = APPPATH . 'Keys/jaasauth.key';  // Adjust the path as needed

    
    public function __construct() {
        $this->session = \Config\Services::session();
        $this->session->start();
		$this->ciqrcode = new Ciqrcode();
        
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

	public function read_like2($table, $or_field, $or_value,$or_field2, $or_value2, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->like($or_field, $or_value);
        $builder->orLike($or_field2, $or_value2);

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

    public function read_where_in($column, $values = [], $table, $order_by = '', $order_dir = 'asc', $limit = '', $offset = '')
    {
        if (empty($values)) {
            return [];
        }
    
        $db = db_connect();
        $builder = $db->table($table);
    
        // Apply whereIn condition
        $builder->whereIn($column, $values);
    
        // Optional ordering
        if (!empty($order_by)) {
            $builder->orderBy($order_by, $order_dir);
        }
    
        // Apply limits if provided
        if ($limit && $offset) {
            $query = $builder->get($limit, $offset);
        } elseif ($limit) {
            $query = $builder->get($limit);
        } else {
            $query = $builder->get();
        }
    
        // Return result
        $result = $query->getResult();
        $db->close();
        return $result;
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

	public function read3_order($field, $value, $field2, $value2,$field3, $value3, $table, $or_field='id', $or_value='DESC', $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy($or_field, $or_value);
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

	public function read2_order_like($field, $value, $field2, $value2, $table, $or_field='id', $or_value='DESC', $search_field, $search_value, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy($or_field, $or_value);
		$builder->like($search_field, $search_value);
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

	public function read_order_like($table, $or_field='id', $or_value='DESC', $search_field, $search_value, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy($or_field, $or_value);
		$builder->like($search_field, $search_value);

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
    
	public function read4($field, $value, $field2, $value2, $field3, $value3,$field4, $value4, $table, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->where($field, $value);
        $builder->where($field2, $value2);
        $builder->where($field3, $value3);
        $builder->where($field4, $value4);

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

	public function read_in($field, $values, $table) {
		// Ensure the values are passed as an array
		if (!is_array($values)) {
			$values = explode(',', $values);
		}
	
		$db = db_connect();
        $builder = $db->table($table);
		// Add the WHERE IN condition
		$builder->whereIn($field, $values);
	
		// Execute the query and return the result
		return $builder->get()->getResultArray();
	}
	
    
	public function read5($field, $value, $field2, $value2, $field3, $value3,$field4, $value4,$field5, $value5, $table, $limit='', $offset='') {
		$db = db_connect();
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->where($field, $value);
        $builder->where($field2, $value2);
        $builder->where($field3, $value3);
        $builder->where($field4, $value4);
        $builder->where($field5, $value5);

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
    public function read_field4($field, $value, $field2, $value2, $field3, $value3,$field4, $value4, $table, $call) {
		$return_call = '';
		$getresult = $this->read4($field, $value, $field2, $value2, $field3, $value3,$field4, $value4, $table);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}
	
    public function read_field5($field, $value, $field2, $value2, $field3, $value3,$field4, $value4,$field5, $value5, $table, $call) {
		$return_call = '';
		$getresult = $this->read5($field, $value, $field2, $value2, $field3, $value3,$field4, $value4, $field5, $value5, $table);
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

	public function read2_group($field, $value,$field2, $value2, $table, $group, $limit='', $offset='') {
		$db = db_connect();
		$db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
        $builder->where($field, $value);
        $builder->where($field2, $value2);
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

	public function read3_group($field, $value,$field2, $value2,$field3, $value3, $table, $group, $limit='', $offset='') {
		$db = db_connect();
		$db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $builder = $db->table($table);

		$builder->orderBy('id', 'DESC');
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

	public function check4($field, $value, $field2, $value2, $field3, $value3, $field4, $value4, $table){
		$db = db_connect();
        $builder = $db->table($table);
        
        $builder->where($field, $value);
        $builder->where($field2, $value2);
        $builder->where($field3, $value3);
        $builder->where($field4, $value4);

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

	public function prayer_msg($type = '', $text = '') {
		// Set default values for icon and icon text based on alert type
		if ($type == 'success') {
			$icon = 'fas fa-check-circle';  // Font Awesome check circle icon
			$icon_text = 'Successful!';
		} else if ($type == 'info') {
			$icon = 'fas fa-info-circle';  // Font Awesome info circle icon
			$icon_text = 'Heads up!';
		} else if ($type == 'warning') {
			$icon = 'fas fa-exclamation-circle';  // Font Awesome warning icon
			$icon_text = 'Please check!';
		} else if ($type == 'danger') {
			$icon = 'fas fa-exclamation-triangle';  // Font Awesome exclamation triangle icon
			$icon_text = 'Oops!';
		} else {
			// Default values if no valid type is provided
			$icon = 'fas fa-info-circle';  // Default to info circle icon
			$icon_text = 'Notice';
		}
	
		// Return the Bootstrap 5 compatible alert message HTML with Font Awesome icons
		return '
			<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				<div class="d-flex align-items-center">
					<i class="' . $icon . '" style="font-size:50px; margin-right:10px;"></i>
					<div>' . $text . '</div>
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

	
	public function date_range1($firstDate, $col1, $secondDate, $col2,$col3, $val3, $table, $limit='', $offset='', $or_field='', $or_value=''){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where($col3, $val3);
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		
		if(!empty($or_field) && !empty($or_value)){
			$builder->orderBy($or_field, $or_value);
		} else {
		 	$builder->orderBy('id', 'DESC');
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

	public function prayer_range($firstDate, $col1, $secondDate, $col2, $table, $limit = '', $offset = '') {
		// Connect to the database
		$db = db_connect();
		$builder = $db->table($table);
	
		// Format the first and second date
		$firstDate = date('Y-m-d', strtotime($firstDate));
		$secondDate = date('Y-m-d', strtotime($secondDate));
	
		// Build query: Check if the provided dates fall between start_date and end_date
		$builder->where("DATE_FORMAT(" . $col1 . ", '%Y-%m-%d') <= '" . $secondDate . "'", NULL, FALSE);
		$builder->where("DATE_FORMAT(" . $col2 . ", '%Y-%m-%d') >= '" . $firstDate . "'", NULL, FALSE);
	
		// Order the result by id descending
		$builder->orderBy('id', 'DESC');
	
		// Apply limits and offsets if provided
		if ($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if ($limit) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}
	
		// Return the query results
		return $query->getResult();
		$db->close();
	}
	

	public function date_range($firstDate, $col1, $secondDate, $col2, $table, $limit='', $offset='', $or_field='', $or_value=''){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col2.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		  
		if(!empty($or_field) && !empty($or_value)){
			$builder->orderBy($or_field, $or_value);
		} else {
		 	$builder->orderBy('id', 'DESC');
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

	public function date_range2($firstDate, $col1, $secondDate, $col2, $col3, $val3, $col4, $val4, $table, $limit='', $offset='', $or_field='', $or_value=''){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where($col3, $val3);
		$builder->where($col4, $val4);		
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		
		
		if(!empty($or_field) && !empty($or_value)){
			$builder->orderBy($or_field, $or_value);
		} else {
		 	$builder->orderBy('id', 'DESC');
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

	public function date_range3($firstDate, $col1, $secondDate, $col2, $col3, $val3, $col4, $val4, $col5, $val5, $table, $limit='', $offset='', $or_field='', $or_value=''){
		$db = db_connect();
        $builder = $db->table($table);

		$builder->where($col3, $val3);
		$builder->where($col4, $val4);		
		$builder->where($col5, $val5);		
		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') >= '".$firstDate."'",NULL,FALSE);
   		$builder->where("DATE_FORMAT(".$col1.",'%Y-%m-%d') <= '".$secondDate."'",NULL,FALSE);
		
		
		if(!empty($or_field) && !empty($or_value)){
			$builder->orderBy($or_field, $or_value);
		} else {
		 	$builder->orderBy('id', 'DESC');
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

	public function prayer_email($to, $subject, $body, $bcc='') {
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

		$template = view('designs/prayer_email', $temp);
		$emailServ->setMessage($template);

		if($emailServ->send()) return true;
		return false;
	}
	//////////////////// END SEND EMAIL //////////////////

	
	public function church_report($firstDate, $secondDate, $date_type, $church_id, $church_type, $limit='', $offset='') {
		$db = db_connect();
		$builder = $db->table('service_report');
	
		// Step 1: Determine church type if the type is 'general'
		$churchIds = [];
	
		if ($church_type == 'individual') {
			$churchIds[] = $church_id; // Only the specific church ID
		} else {
			// Fetch the church type based on the given church_id
			$churchBuilder = $db->table('church')->where('id', $church_id);
			$church = $churchBuilder->select('id, type, regional_id, zonal_id, group_id')->get()->getRow();
	
			if ($church) {
				// Step 2: Determine the related church IDs based on the type
				if ($church->type == 'region') {
					// Get all churches under this region
					$churchIds = $db->table('church')->where('regional_id', $church->regional_id)->select('id')->get()->getResultArray();
					$churchIds = array_column($churchIds, 'id');
				} elseif ($church->type == 'zone') {
					// Get all churches under this zone
					$churchIds = $db->table('church')->where('zonal_id', $church->zonal_id)->select('id')->get()->getResultArray();
					$churchIds = array_column($churchIds, 'id');
				}  elseif ($church->type == 'group') {
					// Get all churches under this group (adjust as needed)
					$churchIds = $db->table('church')->where('group_id', $church->church_level_id)->select('id')->get()->getResultArray();
					$churchIds = array_column($churchIds, 'id');
				}
			}
		}
	
		// Step 3: Filter the service_report based on the collected church IDs
		$builder->whereIn('church_id', $churchIds);
	
		// Date filtering
		if ($date_type == 'Two_Date') {
			$builder->where("DATE_FORMAT(date,'%Y-%m-%d')", $firstDate)
					->orWhere("DATE_FORMAT(date,'%Y-%m-%d')", $secondDate);
		} else {
			$builder->where("DATE_FORMAT(date,'%Y-%m-%d') >=", $firstDate)
					->where("DATE_FORMAT(date,'%Y-%m-%d') <=", $secondDate);
		}
	
		$builder->orderBy('id', 'DESC');
	
		// Limit query
		if ($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} else if ($limit) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}
	
		// Return query results
		return $query->getResult();
		$db->close();
	}
	
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
    public function filter_monitoring($limit='', $offset='', $log_id, $search='', $switch_id='') {
        $db = db_connect();
        $builder = $db->table('user');

        // build query
		$builder->orderBy('id', 'DESC');
		
		$role_id = $this->read_field('name', 'Member', 'access_role', 'id');
		$role_ids = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$cell_id = $this->read_field('id', $log_id, 'user', 'cell_id');
		$church_type = $this->read_field('id', $log_id, 'user', 'church_type');
		$role = strtolower($this->read_field('id', $role_ids, 'access_role', 'name'));


		if(!empty($switch_id)){
            $church_type = $this->read_field('id', $switch_id, 'church', 'type');
            if($church_type == 'region'){
                $role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
				$role = 'regional manager';
            }
            if($church_type == 'zone'){
                $role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
				$role = 'zonal manager';
            }
            if($church_type == 'group'){
                $role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
				$role = 'group manager';
            }
            if($church_type == 'church'){
                $role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
				$role = 'church leader';
            }
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		
        }


		if($role != 'developer' && $role != 'administrator'){
			if($role == 'ministry administrator'){
				$builder->where('ministry_id', $ministry_id);
			} else if($role == 'cell executive' || $role == 'cell leader' || $role == 'assistant cell leader'){
				$builder->where('cell_id', $cell_id);
			
			} else {
				if($church_type == 'region'){
					$builder->where('regional_id', $church_id);
					$builder->orWhere('church_id', $church_id);
				}
				if($church_type == 'zone'){
					$builder->where('zonal_id', $church_id);
					$builder->orWhere('church_id', $church_id);
				}
				if($church_type == 'group'){
					$builder->where('group_id', $church_id);
					$builder->orWhere('church_id', $church_id);
				}
				if($church_type == 'church'){
					$builder->where('church_id', $church_id);
				}
				
			}
			
		} 
		
		// $builder->where('role_id', $role_id);
		$builder->where('is_member', 1);
		$builder->where('is_monitoring', 1);
        
		if(!empty($search)) {
            $builder->groupStart()
				->like('surname', $search)
				->orLike('email', $search)
				->orLike('firstname', $search)
				->orLike('chat_handle', $search)
				->orLike('othername', $search)
				->orLike('user_no', $search)
				->orLike('phone', $search)
				->groupEnd();
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
	public function fetch_cells_by_scope($log_id, $church_id = null, $scope = 'own') {
		$cells = [];
	
		$logged_in_church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$logged_in_role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $logged_in_role_id, 'access_role', 'name'));
		$church_type = $this->read_field('id', $logged_in_church_id, 'church', 'type');
	
		if ($scope == 'own') {
			$church_id = $logged_in_church_id;
		}
	
		if (empty($church_id)) {
			$church_id = $logged_in_church_id;
		}
	
		if (!$church_type) {
			$church_type = $this->read_field('id', $church_id, 'church', 'type');
		}
	
		if ($role != 'developer' && $role != 'administrator') {
			if ($role == 'ministry administrator') {
				$ministry_id = $this->read_field('id', $church_id, 'church', 'ministry_id');
				$cells = $this->read_single_order('ministry_id', $ministry_id, 'cells', 'name', 'asc');
			} elseif (in_array($role, ['cell executive', 'cell leader', 'assistant cell leader'])) {
				$cells = $this->read_single_order('cell_id', $church_id, 'cells', 'name', 'asc');
			} else {
				$churches[] = (object)['id' => $church_id]; 
				if ($church_type == 'region') {
					$churches = $this->read_single('regional_id', $church_id, 'church');
				} elseif ($church_type == 'zone') {
					$churches = $this->read_single('zonal_id', $church_id, 'church');
				} elseif ($church_type == 'group') {
					$churches = $this->read_single('group_id', $church_id, 'church');
				} elseif ($church_type == 'church') {
					$churches = $this->read_single('church_id', $church_id, 'church'); // wrap in object to keep consistency
				} else {
					
				}
	
				foreach ($churches as $church) {
					$church_cells = $this->read_single_order('church_id', $church->id, 'cells', 'name', 'asc');
					foreach ($church_cells as $cell) {
						$cells[] = (object)[
							'church_id' => $church->id,
							'church' => $church->name,
							'cell_id'   => $cell->id,
							'cell_name' => $cell->name
						];
					}
				}
	
				return $cells;
			}
		} else {
			// Developer or admin
			$cells = $this->read_single_order('church_id', $church_id, 'cells', 'name', 'asc');
		}
	
		// If not already formatted, map it
		$formatted_cells = array_map(function ($cell) {
			return [
				'church_id' => $cell->church_id,
				'church' => $this->read_field('id', $cell->church_id, 'church', 'name'),
				'cell_id'   => $cell->id,
				'cell_name' => $cell->name
			];
		}, $cells);
	
		return $formatted_cells;
	}
	
	public function fetch_marked_by_users($log_id, $church_id = null, $marked_type = 'admin', $scope = 'own') {
		$users = [];
		
		// Fetch logged-in church and role info
		$logged_in_church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$logged_in_role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $logged_in_role_id, 'access_role', 'name'));
		$church_type = $this->read_field('id', $logged_in_church_id, 'church', 'type');
		
		// Determine church ID based on scope
		if ($scope == 'own') {
			$church_id = $logged_in_church_id;  // Use the logged-in church
		}$churches = [];
		
		if ($scope == 'selected' && !empty($church_id)) {
			// Use the provided church_id in case of 'selected'
			$churches = is_array($church_id) ? $church_id : [$church_id];
		} elseif ($scope == 'all') {
			// Fetch all churches under the logged-in church, including the logged-in church itself
			$churches = [];
			$churches[] = (object)[
				'id' => $logged_in_church_id,
				'name' => $this->read_field('id', $logged_in_church_id, 'church', 'name')
			];
	
			if ($church_type == 'region') {
				// Get all churches within the region
				$churches_in_region = $this->read_single('regional_id', $logged_in_church_id, 'church');
				$churches = array_merge($churches, $churches_in_region);
			} elseif ($church_type == 'zone') {
				// Get all churches within the zone
				$churches_in_zone = $this->read_single('zonal_id', $logged_in_church_id, 'church');
				$churches = array_merge($churches, $churches_in_zone);
			} elseif ($church_type == 'group') {
				// Get all churches within the group
				$churches_in_group = $this->read_single('group_id', $logged_in_church_id, 'church');
				$churches = array_merge($churches, $churches_in_group);
			} elseif ($church_type == 'church') {
				// Only the logged-in church
				$churches[] = (object)[
					'id' => $logged_in_church_id,
					'name' => $this->read_field('id', $logged_in_church_id, 'church', 'name')
				];
			}
		}
	
		// Determine role and fetch users based on marked_type
		if ($role != 'developer' && $role != 'administrator') {
			// Fetch users based on the marked type (monitoring, cell leader, or church admin)
			if ($marked_type == 'monitoring') {
				// Fetch users with the monitoring role
				$users = $this->read_single_order('is_monitoring', '1', 'user', 'firstname', 'asc');
			} elseif ($marked_type == 'cell') {
				// Fetch users with the cell leader role
				$users = $this->read_single_order('role', 'cell_leader', 'user', 'firstname', 'asc');
			} elseif ($marked_type == 'church_admin') {
				// Fetch users with the church admin role
				$users = $this->read_single_order('role', 'church_admin', 'user', 'firstname', 'asc');
			} else {
				// Return empty if role is not recognized
				return [];
			}
			
			// Combine the results from different churches
			foreach ($churches as $church) {
				$church_id = $church->id;
				$church_name = $church->name;
				$church_users = $this->read_single_order('church_id', $church_id, 'user', 'firstname', 'asc');
				
				foreach ($church_users as $user) {
					$users[] = (object)[
						'church_id' => $church_id,
						'church' => $church_name,
						'user_id' => $user->id,
						'user_name' => $user->firstname.' '.$user->surname,
						'role' => $user->role_id
					];
				}
			}
		} else {
			// For developers or administrators, fetch all users within the selected churches
			foreach ($churches as $church) {
				$church_id = $church->id;
				$church_name = $church->name;
				$church_users = $this->read_single_order('church_id', $church_id, 'user', 'name', 'asc');
				foreach ($church_users as $user) {
					$users[] = (object)[
						'church_id' => $church_id,
						'church' => $church_name,
						'user_id' => $user->id,
						'user_name' => $user->name,
						'role' => $user->role
					];
				}
			}
		}
		
		// If not already formatted, map the users to include church details
		$formatted_users = array_map(function ($user) {
			return [
				'church_id' => $user->church_id,
				'church' => $this->read_field('id', $user->church_id, 'church', 'name'),
				'user_id' => $user->user_id,
				'user_name' => $user->firstname.' '.$user->surname,
				'role' => $this->read_field('id', $user->role_id, 'access_role', 'name'),
			];
		}, $users);
	
		return $formatted_users;
	}
	

	public function filter_members($log_id, $start_date='', $end_date='', $switch_id='') {
        $db = db_connect();
        $builder = $db->table('user');

        // build query
		$builder->orderBy('id', 'DESC');
		
		$role_id = $this->read_field('name', 'Member', 'access_role', 'id');
		$role_ids = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$cell_id = $this->read_field('id', $log_id, 'user', 'cell_id');
		$church_type = $this->read_field('id', $log_id, 'user', 'church_type');
		$role = strtolower($this->read_field('id', $role_ids, 'access_role', 'name'));


		if(!empty($switch_id)){
            $church_type = $this->read_field('id', $switch_id, 'church', 'type');
            if($church_type == 'region'){
                $role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
				$role = 'regional manager';
            }
            if($church_type == 'zone'){
                $role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
				$role = 'zonal manager';
            }
            if($church_type == 'group'){
                $role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
				$role = 'group manager';
            }
            if($church_type == 'church'){
                $role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
				$role = 'church leader';
            }
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		
        }


		if($role != 'developer' && $role != 'administrator'){
			if($role == 'ministry administrator'){
				$builder->where('ministry_id', $ministry_id);
			} else if($role == 'cell executive' || $role == 'cell leader' || $role == 'assistant cell leader'){
				$builder->where('cell_id', $cell_id);
			
			} else {
				if($church_type == 'region'){
					$builder->where('regional_id', $church_id);
					$builder->orWhere('church_id', $church_id);
				}
				if($church_type == 'zone'){
					$builder->where('zonal_id', $church_id);
					$builder->orWhere('church_id', $church_id);
				}
				if($church_type == 'group'){
					$builder->where('group_id', $church_id);
					$builder->orWhere('church_id', $church_id);
				}
				if($church_type == 'church'){
					$builder->where('church_id', $church_id);
				}
				
			}
			
		} 
		
		// $builder->where('role_id', $role_id);
		$builder->where('is_member', 1);
        

		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}
        
		
		
        return $builder->countAllResults();
        $db->close();
    }

	public function filter_memberz($log_id, $start_date='', $end_date='', $switch_id='') {
        $db = db_connect();
        $builder = $db->table('user');

        // build query
		$builder->orderBy('id', 'DESC');
		
		$role_id = $this->read_field('name', 'Member', 'access_role', 'id');
		$role_ids = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$church_type = $this->read_field('id', $log_id, 'user', 'church_type');
		$role = strtolower($this->read_field('id', $role_ids, 'access_role', 'name'));


		if($role != 'developer' && $role != 'administrator'){
			if($role == 'ministry administrator'){
				$builder->where('ministry_id', $ministry_id);
			} else {
				if($church_type == 'region'){
					$builder->where('regional_id', $church_id);
					$builder->orWhere('church_id', $church_id);
				}
				if($church_type == 'zone'){
					$builder->where('zonal_id', $church_id);
					$builder->orWhere('church_id', $church_id);
				}
				if($church_type == 'group'){
					$builder->where('group_id', $church_id);
					$builder->orWhere('church_id', $church_id);
				}
				if($church_type == 'church'){
					$builder->where('church_id', $church_id);
				}
				
			}
			
		} 
		
		// $builder->where('role_id', $role_id);
		$builder->where('is_member', 1);
        

		if(!empty($start_date) && !empty($end_date)){
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') >= '".$start_date."'",NULL,FALSE);
			$builder->where("DATE_FORMAT(reg_date,'%Y-%m-%d') <= '".$end_date."'",NULL,FALSE); 
		}
        
		$query = $builder->get();
     
        // return query
        return $query->getResult();
        $db->close();
    }
	public function filter_membership($limit = '', $offset = '', $log_id, $search = '', $switch_id = '', $include_sub_churches = 'false', $archive = '', $church_scope = '', $selected_churches = [], $filter_cell_id = '') {
		$db = db_connect();
		$builder = $db->table('user');
		$builder->orderBy('id', 'DESC');
	
		$role_id = $this->read_field('name', 'Member', 'access_role', 'id');
		$role_ids = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$cell_id = $this->read_field('id', $log_id, 'user', 'cell_id');
		$church_type = $this->read_field('id', $log_id, 'user', 'church_type');
		$role = strtolower($this->read_field('id', $role_ids, 'access_role', 'name'));
	
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			$church_id = $switch_id;
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
	
			switch ($church_type) {
				case 'region':
					$role = 'regional manager';
					break;
				case 'zone':
					$role = 'zonal manager';
					break;
				case 'group':
					$role = 'group manager';
					break;
				case 'church':
					$role = 'church leader';
					break;
			}
		}
	
		// Scope filters
		if ($church_scope === 'selected' && !empty($selected_churches)) {
			$builder->whereIn('church_id', $selected_churches);
		} elseif ($church_scope === 'own' && !empty($church_id)) {
			$builder->where('church_id', $church_id);
		} elseif ($church_scope === 'all' && !empty($church_id)) {
			$church_type = $this->read_field('id', $church_id, 'church', 'type');
			$sub_churches = [$church_id];
	
			switch ($church_type) {
				case 'region':
					$records = $this->read_single('regional_id', $church_id, 'church');
					break;
				case 'zone':
					$records = $this->read_single('zonal_id', $church_id, 'church');
					break;
				case 'group':
					$records = $this->read_single('group_id', $church_id, 'church');
					break;
				default:
					$records = [];
					break;
			}
	
			if (!empty($records)) {
				$sub_ids = array_map(fn($r) => $r->id, $records);
				$sub_churches = array_merge($sub_churches, $sub_ids);
			}
	
			$builder->whereIn('church_id', $sub_churches);
		} else {
			if (!in_array($role, ['developer', 'administrator'])) {
				if ($role == 'ministry administrator') {
					$builder->where('ministry_id', $ministry_id);
				} elseif (in_array($role, ['cell leader', 'cell executive', 'assistant cell leader'])) {
					$builder->where('cell_id', $cell_id);
				} else {
					switch ($church_type) {
						case 'region':
							$builder->groupStart()
								->where('regional_id', $church_id)
								->orWhere('church_id', $church_id)
								->groupEnd();
							break;
						case 'zone':
							$builder->groupStart()
								->where('zonal_id', $church_id)
								->orWhere('church_id', $church_id)
								->groupEnd();
							break;
						case 'group':
							$builder->groupStart()
								->where('group_id', $church_id)
								->orWhere('church_id', $church_id)
								->groupEnd();
							break;
						case 'church':
							$builder->where('church_id', $church_id);
							break;
					}
				}
			}
		}
	
		// Cell filter override
		if (!empty($filter_cell_id) && $filter_cell_id != 'all') {
			$builder->where('cell_id', $filter_cell_id);
		}
	
		$builder->where('is_member', 1);
		$builder->where('is_archive', $archive);
	
		if (!empty($search)) {
			$builder->groupStart()
				->like('surname', $search)
				->orLike('email', $search)
				->orLike('firstname', $search)
				->orLike('chat_handle', $search)
				->orLike('othername', $search)
				->orLike('user_no', $search)
				->orLike('phone', $search)
				->groupEnd();
		}
	
		if ($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} elseif ($limit) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}
	
		return $query->getResult();
		$db->close();
	}
	
    public function filter_member_attendance($search='', $church_id='') {
        $db = db_connect();
        $builder = $db->table('user');

        // build query
		$builder->orderBy('id', 'DESC');
		
		$role_id = $this->read_field('name', 'Member', 'access_role', 'id');
		
		
		$builder->where('church_id', $church_id);

		
		$builder->where('is_member', 1);
        if(!empty($search)) {
            $builder->groupStart()
				->like('surname', $search, 'both')
				->orLike('email', $search, 'both')
				->orLike('firstname', $search, 'both')
				->orLike('othername', $search, 'both')
				->groupEnd();
        }

		
        // limit query
       
        $query = $builder->get();
     
        // return query
        return $query->getResult();
        $db->close();
    }

	// You will need to implement this function to fetch sub-churches based on the parent church ID
	private function get_sub_church_ids($parent_id, $type) {
		$db = db_connect();
		$builder = $db->table('church'); // Adjust to your actual table name
		
		if ($type == 'region') {
			$builder->select('id')->where('regional_id', $parent_id);
			$builder->where('type', $type); // Assuming you have a 'type' field
		}
		if ($type == 'zone') {
			$builder->select('id')->where('zonal_id', $parent_id);
			$builder->where('type', $type); // Assuming you have a 'type' field
		}
		if ($type == 'group') {
			$builder->select('id')->where('group_id', $parent_id);
			$builder->where('type', $type); // Assuming you have a 'type' field
		}
	
		$result = $builder->get()->getResultArray();
		return array_column($result, 'id');
	}

	public function numberToOrdinal($number) {
		// Ensure the input is an integer between 1 and 100
		if (!is_int($number)) {
			return '';
		}
	
		// Special cases for numbers ending in 11, 12, or 13
		$lastDigit = $number % 10;
		$lastTwoDigits = $number % 100;
	
		if ($lastTwoDigits >= 11 && $lastTwoDigits <= 13) {
			$suffix = 'th';
		} else {
			switch ($lastDigit) {
				case 1:
					$suffix = 'st';
					break;
				case 2:
					$suffix = 'nd';
					break;
				case 3:
					$suffix = 'rd';
					break;
				default:
					$suffix = 'th';
					break;
			}
		}
	
		return $number . $suffix;
	}

	public function convertText($html) {
		// Check if the input is empty
		if (empty($html)) {
			return '';
		}
		
		  // Step 1: Decode HTML entities (if any)
		  $text = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

		  // Step 2: Strip all HTML tags
		  $text = strip_tags($text);
	  
		  // Step 3: Replace non-breaking spaces (\u00a0) with regular spaces
		  $text = str_replace("\u00a0", " ", $text); // Unicode for non-breaking space
	  
		  // Step 4: Remove all non-alphanumeric characters (letters and numbers only)
		  // This regex will remove everything except letters (a-z, A-Z) and numbers (0-9)
		  $text = preg_replace('/[^a-zA-Z0-9\s]/', '', $text);
	  
		  // Step 5: Remove extra spaces and trim the string
		  $text = preg_replace('/\s+/', ' ', $text); // Replace multiple spaces with one space
		  $text = trim($text); // Trim leading and trailing spaces
		  return $text;
	}
	

	public function removeKeysFromJson($jsonData) {
		 // Decode JSON to PHP associative array
		 $data = json_decode($jsonData, true);

		 // Check for decoding errors
		 if (json_last_error() !== JSON_ERROR_NONE) {
			 return json_encode(["error" => "Invalid JSON data"]);
		 }
	 
		 // Extract only the values from the associative array
		 $values = array_values($data);
	
		// Encode PHP array back to JSON
		return json_encode($data);
	}
	
	public function numberToMonth($number) {
		// Array mapping numbers to month names
		$months = [
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December'
		];
	
		// Validate the number
		if (isset($months[$number])) {
			return $months[$number];
		} else {
			return ''; // Handle out of range numbers
		}
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
	
	public function filter_cell_role($limit='', $offset='', $search='') {
        $db = db_connect();
        $builder = $db->table('cell_role');

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
	
	
	public function filter_ministry($limit='', $offset='', $search='', $switch_id='') {
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


	public function filter_church($limit='', $offset='', $log_id='', $search='', $type, $switch_id='') {
        $db = db_connect();
        $builder = $db->table('church');

        // build query
		$builder->orderBy('id', 'desc');
		
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));

		if(!empty($switch_id)){
            $church_type = $this->read_field('id', $switch_id, 'church', 'type');
            if($church_type == 'region'){
                $role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
				$role = 'regional manager';
            }
            if($church_type == 'zone'){
                $role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
				$role = 'zonal manager';
            }
            if($church_type == 'group'){
                $role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
				$role = 'group manager';
            }
            if($church_type == 'church'){
                $role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
				$role = 'church leader';
            }
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		
        }

		if($role == 'ministry administrator'){
			$builder->where('ministry_id', $ministry_id);
		} else {
			if($role == 'regional manager'){
				$builder->where('regional_id', $church_id);
			}
			if($role == 'zonal manager'){
				$builder->where('zonal_id', $church_id);
			}
			if($role == 'group manager'){
				$builder->where('group_id', $church_id);
			}
			if($role == 'church leader'){
				$builder->where('church_id', $church_id);
			}
		} 

		
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


	public function filter_knowledge($limit='', $offset='', $log_id='', $search='') {
        $db = db_connect();
        $builder = $db->table('knowledge');

        // build query
		$builder->orderBy('id', 'desc');
		
        if(!empty($search)) {
            $builder->like('title', $search);
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

	public function filter_events($limit='', $offset='', $log_id='', $status= '', $search='', $type='', $switch_id='') {
        $db = db_connect();
        $builder = $db->table('events');

        // build query
		$builder->orderBy('id', 'desc');
		
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			
			switch ($church_type) {
				case 'region':
					$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
					$role = 'regional manager';
					break;
				case 'zone':
					$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
					$role = 'zonal manager';
					break;
				case 'group':
					$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
					$role = 'group manager';
					break;
				case 'church':
					$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
					$role = 'church leader';
					break;
			}
	
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		}

		if($role != 'developer' && $role != 'administrator'){
			$builder->where('ministry_id', $ministry_id);
		} 
		if ($role === 'regional manager') {
			$regional_id = $church_id;

			$churches = $db->table('church')
				->where('regional_id', $regional_id)
				->select('id')
				->get()
				->getResultArray();

			$church_ids = array_column($churches, 'id');
			$church_ids[] = $church_id;
			
		} elseif ($role === 'zonal manager') {
			$regional_id = $church_id;

			$churches = $db->table('church')
				->where('zonal_id', $regional_id)
				->select('id')
				->get()
				->getResultArray();

			$church_ids = array_column($churches, 'id');
			$church_ids[] = $church_id;
			
		} elseif ($role === 'group manager') {
			$regional_id = $church_id;

			$churches = $db->table('church')
				->where('group_id', $regional_id)
				->select('id')
				->get()
				->getResultArray();

			$church_ids = array_column($churches, 'id');
			$church_ids[] = $church_id;
		} elseif ($role === 'church leader') {
			$church_ids[] = $church_id;
		}
	
		// Check if the announcement is global or specific
		$builder->groupStart();
		$builder->where('church_type', 'all');

		if (!empty($church_ids)) {
			foreach ($church_ids as $id) {
				$builder->orWhere("JSON_CONTAINS(church_id, '\"$id\"')");
			}
		}
		
		$builder->groupEnd();


		// $builder->where('type', $type);
        if(!empty($search)) {
            $builder->like('title', $search);
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

	
	public function filter_prayer($limit = '', $offset = '', $log_id = '', $status = '', $search = '', $type = '', $switch_id = '') {
		$db = db_connect();
		$builder = $db->table('prayer');
		$builder->orderBy('id', 'desc'); // Default order
	
		// Retrieve user and role data
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
	
		$church_ids = []; // Initialize church IDs array
	
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
	
			// Determine role and related church IDs
			switch ($church_type) {
				case 'region':
					$role = 'regional manager';
					break;
				case 'zone':
					$role = 'zonal manager';
					break;
				case 'group':
					$role = 'group manager';
					break;
				case 'church':
					$role = 'church leader';
					break;
			}
		}
	
		// Restrict access for non-developer/admin roles
		if ($role !== 'developer' && $role !== 'administrator') {
			$builder->where('ministry_id', $ministry_id);
		}
	
		// Role-specific church filtering
		if (isset($church_id)) {
			switch ($role) {
				case 'regional manager':
				case 'zonal manager':
				case 'group manager':
					$column = $role === 'regional manager' ? 'regional_id' :
							  ($role === 'zonal manager' ? 'zonal_id' : 'group_id');
	
					$churches = $db->table('church')
						->where($column, $church_id)
						->select('id')
						->get()
						->getResultArray();
	
					$church_ids = array_column($churches, 'id');
					$church_ids[] = $church_id; // Include the current church
					break;
	
				case 'church leader':
					$church_ids[] = $church_id;
					break;
			}
		}
	
		// Apply church filter if applicable
		if (!empty($church_ids)) {
			$builder->groupStart();
			foreach ($church_ids as $id) {
				$builder->orWhere("JSON_CONTAINS(churches, '\"$id\"')");
			}
			$builder->groupEnd();
		}
	
		// Apply search filter
		if (!empty($search)) {
			$builder->like('title', $search);
		}
	
		// Apply limit and offset
		if ($limit) {
			$query = $offset ? $builder->get($limit, $offset) : $builder->get($limit);
		} else {
			$query = $builder->get();
		}
	
		$db->close();
		return $query->getResult();
	}
	
	public function filter_prayer_cloud($search = '', $church_id = '') {
		$db = db_connect();
		$builder = $db->table('prayer');
		$builder->orderBy('id', 'desc'); // Default order
	
	
		// Apply search filter
		if (!empty($search)) {
			$builder->like('title', $search);
		}
		
		
		$query = $builder->get();
		
	
		$db->close();
		return $query->getResult();
	}
	
	
	public function filter_forms($limit='', $offset='', $log_id='', $status= '', $search='', $type='', $switch_id='') {
        $db = db_connect();
        $builder = $db->table('form');

        // build query
		$builder->orderBy('id', 'desc');
		
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			
			switch ($church_type) {
				case 'region':
					$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
					$role = 'regional manager';
					break;
				case 'zone':
					$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
					$role = 'zonal manager';
					break;
				case 'group':
					$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
					$role = 'group manager';
					break;
				case 'church':
					$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
					$role = 'church leader';
					break;
			}
	
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		}

		if($role != 'developer' && $role != 'administrator'){
			$builder->where('ministry_id', $ministry_id);
		} 
		if ($role === 'regional manager') {
			$regional_id = $church_id;

			$churches = $db->table('church')
				->where('regional_id', $regional_id)
				->select('id')
				->get()
				->getResultArray();

			$church_ids = array_column($churches, 'id');
			$church_ids[] = $church_id;
			
		} elseif ($role === 'zonal manager') {
			$regional_id = $church_id;

			$churches = $db->table('church')
				->where('zonal_id', $regional_id)
				->select('id')
				->get()
				->getResultArray();

			$church_ids = array_column($churches, 'id');
			$church_ids[] = $church_id;
			
		} elseif ($role === 'group manager') {
			$regional_id = $church_id;

			$churches = $db->table('church')
				->where('group_id', $regional_id)
				->select('id')
				->get()
				->getResultArray();

			$church_ids = array_column($churches, 'id');
			$church_ids[] = $church_id;
		} elseif ($role === 'church leader') {
			$church_ids[] = $church_id;
		}
	
		// Check if the announcement is global or specific
		$builder->groupStart();
		$builder->where('church_type', 'all');

		if (!empty($church_ids)) {
			foreach ($church_ids as $id) {
				$builder->orWhere("JSON_CONTAINS(church_id, '\"$id\"')");
			}
		}
		
		$builder->groupEnd();


		// $builder->where('type', $type);
        if(!empty($search)) {
            $builder->like('name', $search);
			$builder->orLike('description', $search);
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
	
	public function filter_form_extension($limit='', $offset='', $log_id='', $form_id= '', $switch_id='') {
        $db = db_connect();
        $builder = $db->table('form_extension');

        // build query
		$builder->orderBy('id', 'desc');
		$builder->where('form_id', $form_id);

		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			
			switch ($church_type) {
				case 'region':
					$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
					$role = 'regional manager';
					break;
				case 'zone':
					$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
					$role = 'zonal manager';
					break;
				case 'group':
					$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
					$role = 'group manager';
					break;
				case 'church':
					$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
					$role = 'church leader';
					break;
			}
	
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		}
		if($role != 'developer' && $role != 'administrator' && $role != 'ministry administrator'){
			$builder->where('church_id', $church_id);
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


	public function filter_form_link($limit='', $offset='', $log_id='', $form_id= '', $switch_id='', $type='') {
        $db = db_connect();
        $builder = $db->table('form_link');

        // build query
		$builder->orderBy('id', 'desc');
		$builder->where('form_id', $form_id);

		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			
			switch ($church_type) {
				case 'region':
					$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
					$role = 'regional manager';
					break;
				case 'zone':
					$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
					$role = 'zonal manager';
					break;
				case 'group':
					$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
					$role = 'group manager';
					break;
				case 'church':
					$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
					$role = 'church leader';
					break;
			}
	
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		}
		if($role != 'developer' && $role != 'administrator'){
			if($role == 'ministry administrator'){
				$builder->where('ministry_id', $ministry_id);
			} else{
				$builder->where('church_id', $church_id);
			}
			
		} 
		$builder->where('type', $type);

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

	public function filter_service_schedule($limit='', $offset='', $search='', $log_id='', $church_idz='', $switch_id='') {
        $db = db_connect();
        $builder = $db->table('service_schedule');

        // build query
		$builder->orderBy('id', 'desc');
		
        if(!empty($search)) {
            $builder->like('name', $search);
        }
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));

		if(!empty($switch_id)){
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			if($church_type == 'region'){
				$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
				$role = 'regional manager';
			}
			if($church_type == 'zone'){
				$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
				$role = 'zonal manager';
			}
			if($church_type == 'group'){
				$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
				$role = 'group manager';
			}
			if($church_type == 'church'){
				$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
				$role = 'church leader';
			}
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		
		}
		if($role != 'developer' && $role != 'administrator'){
			$builder->where('church_id', $church_id);
			$builder->orWhere('link', $church_id);
		} else {
			if(!empty($church_idz) && $church_idz != 'all') $builder->where('church_id', $church_idz);
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

	public function get_all_churches_under($church_id, $church_type)	{
		$mapping = [
			'zone' => 'zonal_id',
			'region' => 'regional_id',
			'group' => 'group_id',
			'church' => 'church_id',
			'national' => 'national_id',
			'global' => 'global_id'
		];

		if (!isset($mapping[$church_type])) {
			return []; // No mapping for this type
		}

		$column = $mapping[$church_type];
		$db = db_connect();
        $builder = $db->table('church');
		$builder->select('id');
		$builder->where($column, $church_id);
		$query = $builder->get();

		$result = [];
		foreach ($query->getResult() as $row) {
			$result[] = $row->id;
		}

		return $result;
	}


	public function filter_service_analytics($limit = '', $offset = '', $log_id = '', $date = '', $service_type = '', $scope = '', $church_idz = '', $cell_id = '', $marked_type = '', $marked_by = '', $switch_id = '', $filter='all')	{
		$db = db_connect();
		$builder = $db->table('service_attendance');
		$builder->select('service_attendance.*, service_report.date, church.name as church_name, church.type as church_type');
		$builder->join('service_report', 'service_report.id = service_attendance.service_id', 'left');
		$builder->join('church', 'church.id = service_attendance.church_id', 'left');
		$builder->orderBy('service_attendance.id', 'desc');

		// Logged in user details
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));

		// Switch context if provided
		if (!empty($switch_id)) {
			$church_id = $switch_id;
		}

		// 📅 Filter by service date
		if (!empty($date)) {
			$builder->where('service_report.date', $date);
		}

		// 🔢 Filter by service type (replacing old occurrence logic)
		if (!empty($service_type) && $service_type != 'all') {
			$builder->where('service_report.type', $service_type);
		}

		// 🧠 Scope logic
		if ($scope == 'own') {
			$builder->where('church.id', $church_id);

		} elseif ($scope == 'selected' && !empty($church_idz)) {
			if (!is_array($church_idz)) {
				$church_idz = explode(',', $church_idz);
			}
			$builder->whereIn('church.id', $church_idz);

		} elseif ($scope == 'all') {
			// Get church type of current user's church
			$church_type = $this->read_field('id', $church_id, 'church', 'type');
			$church_ids = [$church_id];
			$sub_churches = [];

			// Fetch subordinate churches
			if ($church_type == 'region') {
				$sub_churches = $this->read_single('regional_id', $church_id, 'church');
			} elseif ($church_type == 'zone') {
				$sub_churches = $this->read_single('zonal_id', $church_id, 'church');
			} elseif ($church_type == 'group') {
				$sub_churches = $this->read_single('group_id', $church_id, 'church');
			} elseif ($church_type == 'church') {
				$sub_churches = $this->read_single('church_id', $church_id, 'church');
			}

			if (!empty($sub_churches)) {
				$sub_ids = array_map(function ($c) {
					return is_array($c) ? $c['id'] : $c->id;
				}, $sub_churches);

				$church_ids = array_merge($church_ids, $sub_ids);
			}

			$builder->whereIn('church.id', $church_ids);
		}

		// 🧩 Filter by cell members
		if (!empty($cell_id) && $cell_id !== 'all') {
			$member_ids = $db->table('user')
				->select('id')
				->where('cell_id', $cell_id)
				->get()
				->getResultArray();

			$member_ids = array_column($member_ids, 'id');

			if (!empty($member_ids)) {
				$builder->whereIn('service_attendance.member_id', $member_ids);
			} else {
				$builder->where('service_attendance.member_id', 0); // Return no results
			}
		}
		// 🎯 Apply direct filter (status or gender)
		if (!empty($filter) && $filter !== 'all') {
			// Filter by attendance status
			if (in_array($filter, ['present', 'absent'])) {
				$builder->where('LOWER(td_service_attendance.status)', strtolower($filter));

			}

			// Filter by gender
			if (in_array($filter, ['male', 'female'])) {
				$builder->join('td_user', 'td_user.id = service_attendance.member_id', 'left');
				$builder->where('LOWER(td_user.gender)', strtolower($filter));
			}

			// Note: 'first_timer' is handled separately outside this method
		}


		// ✅ Pagination
		if (is_numeric($limit) && is_numeric($offset)) {
			$query = $builder->get($limit, $offset);
		} elseif (is_numeric($limit)) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}

		$result = $query->getResult();
		$db->close();
		return $result;
	}

		public function filter_cell($limit='', $offset='', $search='', $log_id) {
        $db = db_connect();
        $builder = $db->table('cells');

        // build query
		$builder->orderBy('id', 'desc');
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if($role != 'developer' && $role != 'administrator'){
			if($role == 'ministry administrator'){
				$builder->where('ministry_id', $ministry_id);
			}else {
				$builder->where('church_id', $church_id);
			}
			
		} 
        if(!empty($search)) {
            $builder->groupStart()
				->like('name', $search)
				->orLike('location', $search)
				->orLike('phone', $search)
				->groupEnd();
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

	
	
	public function filter_visitors($limit='', $offset='', $log_id, $search='', $type='', $is_member=0, $is_visitor=0, $follow_status=0, $switch_id='') {
        $db = db_connect();
        $builder = $db->table('visitors');

        // build query
		$builder->orderBy('id', 'desc');
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if(!empty($switch_id)){
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			if($church_type == 'region'){
				$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
				$role = 'regional manager';
			}
			if($church_type == 'zone'){
				$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
				$role = 'zonal manager';
			}
			if($church_type == 'group'){
				$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
				$role = 'group manager';
			}
			if($church_type == 'church'){
				$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
				$role = 'church leader';
			}
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		
		}
		if($role != 'developer' && $role != 'administrator'){
			if($role == 'ministry administrator'){
				$builder->where('ministry_id', $ministry_id);
			}else {
				$builder->where('church_id', $church_id);
			}
			
		} 
		if(!empty($type))$builder->where('category', $type);
		$builder->where('is_member', $is_member);
		$builder->where('is_visitor', $is_visitor);
        $builder->where('follow_status', $follow_status);
        if(!empty($search)) {
            $builder->groupStart()
				->like('fullname', $search)
				->orLike('email', $search)
				->orLike('phone', $search)
				->groupEnd();
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

	public function filter_follow_up($limit='', $offset='', $log_id, $search='', $visitor_id='') {
        $db = db_connect();
        $builder = $db->table('follow_up');

        // build query
		$builder->orderBy('id', 'desc');
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if($role != 'developer' && $role != 'administrator'){
			if($role == 'ministry administrator'){
				$builder->where('ministry_id', $ministry_id);
			}else {
				$builder->where('church_id', $church_id);
			}
			
		} 
		$builder->where('visitor_id', $visitor_id);
        if(!empty($search)) {
            $builder->groupStart()
				->like('notes', $search)
				->groupEnd();
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

	public function mailgun($to,$subject,$msg,$church='') {
		$ch = curl_init();
		if(empty($church))$church = 'My Church Connect Pal';
		$body = [
            'from'    => $church.'<noreply@mg.mychurchconnectpal.com>', // ✅ Required
            'to'      => $to,                            // ✅ Required
            'subject' => $subject,
            'html'    => $msg
        ];

		$mailgun_domain = 'mg.mychurchconnectpal.com';

		$link = 'https://api.mailgun.net/v3/'.$mailgun_domain.'/messages';
		$mailgun_key = $this->read_field('name', 'mailgun', 'setting', 'value');

		
		curl_setopt($ch, CURLOPT_URL, $link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_USERPWD, 'api' . ':' . $mailgun_key);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		return $result;
	}

    public function filter_cell_report($limit = '', $offset = '', $search = '', $log_id, $start_date = '', $end_date = '', $cell_id = '', $meeting_type = '', $church_scope = '', $selected_churches = [], $switch_id = '')	{
		$db = db_connect();
		$builder = $db->table('cell_report');
		$builder->orderBy('date', 'desc');

		// Search filter
		if (!empty($search)) {
			$builder->groupStart()
				->like('type', $search)
				->orLike('attendance', $search)
				->groupEnd();
		}

		// User and role info
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id_user = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));

		// Handle switch logic
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id_user = $switch_id;

			switch ($church_type) {
				case 'region':
					$role = 'regional manager';
					break;
				case 'zone':
					$role = 'zonal manager';
					break;
				case 'group':
					$role = 'group manager';
					break;
				case 'church':
					$role = 'church leader';
					break;
			}
		}

		// Date filter
		if (!empty($start_date) && !empty($end_date)) {
			$builder->where("DATE(reg_date) >=", $start_date);
			$builder->where("DATE(reg_date) <=", $end_date);
		}

		// Type filter
		if (!empty($meeting_type) && $meeting_type != 'all') {
			$builder->where('type', $meeting_type);
		}

		// Cell filter
		if (!empty($cell_id) && $cell_id != 'all') {
			$builder->where('cell_id', $cell_id);
		}

		// Scope filter
		if ($church_scope === 'selected' && !empty($selected_churches)) {
			$builder->whereIn('church_id', $selected_churches);
		} elseif ($church_scope === 'own' && !empty($church_id_user)) {
			$builder->where('church_id', $church_id_user);
		} elseif ($church_scope === 'all' && !empty($church_id_user)) {
			$church_type = $this->read_field('id', $church_id_user, 'church', 'type');
			$sub_churches = [$church_id_user];

			// Fetch children churches
			switch ($church_type) {
				case 'region':
					$records = $this->read_single('regional_id', $church_id_user, 'church');
					break;
				case 'zone':
					$records = $this->read_single('zonal_id', $church_id_user, 'church');
					break;
				case 'group':
					$records = $this->read_single('group_id', $church_id_user, 'church');
					break;
				case 'church':
					$records = $this->read_single('church_id', $church_id_user, 'church');
					break;
				default:
					$records = [];
			}

			if (!empty($records)) {
				$sub_churches = array_merge($sub_churches, array_map(fn($rec) => $rec->id, $records));
			}

			$builder->whereIn('church_id', $sub_churches);
		} else {
			if (!in_array($role, ['developer', 'administrator'])) {
				if ($role === 'ministry administrator') {
					$builder->where('ministry_id', $ministry_id);
				}
			}
		}

		// Final execution
		if ($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} elseif ($limit) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}

		return $query->getResult();
	}


	public function filter_service_report($limit = '', $offset = '', $search = '', $log_id, $switch_id = '', $date = '', $type = '', $church_scope = '', $selected_churches = [], $cell_id = ''){
		$db = db_connect();
		$builder = $db->table('service_report');
		$builder->orderBy('date', 'desc');
	
		// Search filter
		if (!empty($search)) {
			$builder->groupStart()
				->like('type', $search)
				->orLike('attendance', $search)
				->groupEnd();
		}
	
		// User and role details
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id_user = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
	
		// If switch is used, override role context
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id_user = $switch_id;
	
			switch ($church_type) {
				case 'region':
					$role = 'regional manager';
					break;
				case 'zone':
					$role = 'zonal manager';
					break;
				case 'group':
					$role = 'group manager';
					break;
				case 'church':
					$role = 'church leader';
					break;
			}
		}
	
		// Date filter
		if (!empty($date)) {
			$builder->where('date', $date);
		}
	
		// Type filter
		if (!empty($type) && $type != 'all') {
			$builder->where('type', $type);
		}
	
		// Cell filter
		// if (!empty($cell_id) && $cell_id != 'all') {
		// 	$builder->where('cell_id', $cell_id);
		// }
	
		// Filter by church scope
		if ($church_scope === 'selected' && !empty($selected_churches)) {
			$builder->whereIn('church_id', $selected_churches);
		} elseif ($church_scope === 'own' && !empty($church_id_user)) {
			$builder->where('church_id', $church_id_user);
		} elseif ($church_scope === 'all' && !empty($church_id_user)) {
			$church_type = $this->read_field('id', $church_id_user, 'church', 'type');
			$sub_churches = [$church_id_user]; // Always include the user's own church
		
			// Fetch subordinate churches
			switch ($church_type) {
				case 'region':
					$records = $this->read_single('regional_id', $church_id_user, 'church');
					break;
				case 'zone':
					$records = $this->read_single('zonal_id', $church_id_user, 'church');
					break;
				case 'group':
					$records = $this->read_single('group_id', $church_id_user, 'church');
					break;
				case 'church':
					$records = $this->read_single('church_id', $church_id_user, 'church');
					break;
				case 'center':
				default:
					$records = []; // no children, just self
					break;
			}
		
			// Append subordinate IDs without overwriting
			if (!empty($records)) {
				$sub_churches = array_merge($sub_churches, array_map(fn($rec) => $rec->id, $records));
			}
		
			// Apply the final filter
			$builder->whereIn('church_id', $sub_churches);
		} else {
			// Default fallback if no scope is passed
			if (!in_array($role, ['developer', 'administrator'])) {
				if ($role === 'ministry administrator') {
					$builder->where('ministry_id', $ministry_id);
				} 
			}
		}
	
		// Execute the query
		if ($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} elseif ($limit) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}
	
		return $query->getResult();
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

	public function filter_dedication($limit='', $offset='', $log_id, $search='', $switch_id='', $status) {
        $db = db_connect();
        $builder = $db->table('dedication');

        // build query// Retrieve user details
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if(!empty($switch_id)){
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			if($church_type == 'region'){
				$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
				$role = 'regional manager';
			}
			if($church_type == 'zone'){
				$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
				$role = 'zonal manager';
			}
			if($church_type == 'group'){
				$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
				$role = 'group manager';
			}
			if($church_type == 'church'){
				$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
				$role = 'church leader';
			}
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		
		}
		 // Apply filters based on user role
		if ($role != 'developer' && $role != 'administrator') {
			if($role == 'ministry administrator'){
				$builder->where('ministry_id', $ministry_id);
			} else {
				$builder->where('church_id', $church_id);
			}
		
		}

        // build query
		$builder->orderBy('date', 'desc');
		$builder->where('status', $status);
		
        if(!empty($search)) {
            $builder->like('firstname', $search);
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


	public function filter_baptism($limit='', $offset='', $log_id, $search='', $switch_id='') {
        $db = db_connect();
        $builder = $db->table('user');

        // build query// Retrieve user details
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if(!empty($switch_id)){
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			if($church_type == 'region'){
				$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
				$role = 'regional manager';
			}
			if($church_type == 'zone'){
				$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
				$role = 'zonal manager';
			}
			if($church_type == 'group'){
				$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
				$role = 'group manager';
			}
			if($church_type == 'church'){
				$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
				$role = 'church leader';
			}
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		
		}
		 // Apply filters based on user role
		if ($role != 'developer' && $role != 'administrator') {
			if($role == 'ministry administrator'){
				$builder->where('ministry_id', $ministry_id);
			} else {
				$builder->where('church_id', $church_id);
			}
		
		}
		$builder->where('is_member', 1);
        // build query
		$builder->orderBy('id', 'desc');
		
        if(!empty($search)) {
            $builder->like('firstname', $search);
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

	public function filter_givings($limit='', $offset='', $search='', $log_id, $start_date = '', $end_date = '', $partnership_id = '', $switch_id ='') {
        $db = db_connect();
        $builder = $db->table('partners_history');

		// Retrieve user details
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if(!empty($switch_id)){
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			if($church_type == 'region'){
				$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
				$role = 'regional manager';
			}
			if($church_type == 'zone'){
				$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
				$role = 'zonal manager';
			}
			if($church_type == 'group'){
				$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
				$role = 'group manager';
			}
			if($church_type == 'church'){
				$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
				$role = 'church leader';
			}
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		
		}
		 // Apply filters based on user role
		 if ($role != 'developer' && $role != 'administrator') {
			if($role == 'ministry administrator'){
    			$builder->where('ministry_id', $ministry_id);
    		} else {
    		     // Determine the churches based on the user's role
				// $church_ids = [$church_id]; // Start with the user's church
				// if ($role == 'regional manager') {
				// 	// Fetch zonal and group churches under the regional church
				// 	$zonal_churches = $this->get_sub_church_ids($church_id, 'zone');
				// 	$group_churches = $this->get_sub_church_ids($church_id, 'group');
				// 	$assembly_churches = $this->get_sub_church_ids($church_id, 'church');
					
				// 	$church_ids = array_merge($church_ids, $zonal_churches, $group_churches, $assembly_churches);
				// } 
				
				// if ($role == 'zonal manager') {
				// 	// Fetch group churches under the zonal church
				// 	$group_churches = $this->get_sub_church_ids($church_id, 'group');
				// 	$assembly_churches = $this->get_sub_church_ids($church_id, 'church');
					
				// 	$church_ids = array_merge($church_ids, $group_churches, $assembly_churches);
				// }

				// if ($role == 'group manager') {
				// 	// Fetch group churches under the zonal church
				// 	$assembly_churches = $this->get_sub_church_ids($church_id, 'church');
					
				// 	$church_ids = array_merge($church_ids, $assembly_churches);
				// }
				
    			$builder->where('church_id', $church_id);
				// Filter by church IDs
				// if (!empty($church_ids)) {
				// 	$builder->whereIn('church_id', $church_ids);
				// }

    		}
			
		 }

        // build query
		$builder->orderBy('date_paid', 'desc');
		
        if(!empty($search)) {
            $builder->like('name', $search);
        }

		if(!empty($partnership_id) && $partnership_id != 'all') {
            $builder->like('partnership_id', $partnership_id);
        }
		if (!empty($start_date) && !empty($end_date)) {
            $builder->where("DATE(date_paid) >=", $start_date);
            $builder->where("DATE(date_paid) <=", $end_date);
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

	public function filter_church_activity($limit='', $offset='', $log_id, $start_date = '', $end_date = '', $category_id = '', $member_id ='', $church_id='', $ministry_id='') {
        $db = db_connect();
        $builder = $db->table('church_activity');

        // build query
		$builder->orderBy('start_datetime', 'desc');
		
        if(!empty($ministry_id)) {
            $builder->like('ministry_id', $ministry_id);
        }

		if(!empty($church_id) && $church_id != 'all') {
            $builder->like('church_id', $church_id);
        }
		if(!empty($member_id) && $member_id != 'all') {
			$builder->where("JSON_CONTAINS(members, '\"$member_id\"')");
        }
		if(!empty($category_id) && $category_id != 'all') {
            $builder->like('category_id', $category_id);
        }
		
		
		if (!empty($start_date) && !empty($end_date)) {
            $builder->where("DATE_FORMAT(start_datetime,'%Y-%m-%d') >=", $start_date);
            $builder->where("DATE_FORMAT(end_datetime,'%Y-%m-%d') <=", $end_date);
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


	public function filter_foundation_setup($limit='', $offset='', $log_id, $search='', $switch_id='', $start_date = '', $end_date = '') {
        $db = db_connect();
        $builder = $db->table('foundation_setup');

        // build query
		$builder->orderBy('id', 'desc');
		
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
	
		if($role != 'developer' && $role != 'administrator'){
			if($role == 'ministry_administrator'){
				$builder->like('ministry_id', $ministry_id);
			} else{
				$builder->where("JSON_CONTAINS(church_id, '\"$church_id\"')");
			}
		} 
		
		if (!empty($start_date) && !empty($end_date)) {
            $builder->where("DATE_FORMAT(start_date,'%Y-%m-%d') >=", $start_date);
            $builder->where("DATE_FORMAT(end_date,'%Y-%m-%d') <=", $end_date);
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

	
	//Filter Announements
	public function filter_announcement($limit = '', $offset = '', $user_id, $search = '', $switch_id = '') {
		$db = db_connect();
		$builder = $db->table('announcement');
	
		// Get user role
		$role_id = $this->read_field('id', $user_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		
		$ministry_id = $this->read_field('id', $user_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $user_id, 'user', 'church_id');
	
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
			
			switch ($church_type) {
				case 'region':
					$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
					$role = 'regional manager';
					break;
				case 'zone':
					$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
					$role = 'zonal manager';
					break;
				case 'group':
					$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
					$role = 'group manager';
					break;
				case 'church':
					$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
					$role = 'church leader';
					break;
			}
	
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		}
	
		if ($role != 'developer' && $role != 'administrator') {
			// Initialize the query based on user role
			if ($role === 'ministry administrator') {
				$builder->where('ministry_id', $ministry_id);
			} elseif ($role === 'regional manager') {
				$regional_id = $church_id;
	
				$churches = $db->table('church')
					->where('regional_id', $regional_id)
					->select('id')
					->get()
					->getResultArray();
	
				$church_ids = array_column($churches, 'id');
				$church_ids[] = $church_id;
				
			} elseif ($role === 'zonal manager') {
				$regional_id = $church_id;
	
				$churches = $db->table('church')
					->where('zonal_id', $regional_id)
					->select('id')
					->get()
					->getResultArray();
	
				$church_ids = array_column($churches, 'id');
				$church_ids[] = $church_id;
				
			} elseif ($role === 'group manager') {
				$regional_id = $church_id;
	
				$churches = $db->table('church')
					->where('group_id', $regional_id)
					->select('id')
					->get()
					->getResultArray();
	
				$church_ids = array_column($churches, 'id');
				$church_ids[] = $church_id;
			} elseif ($role === 'church leader') {
				$church_ids[] = $church_id;
			}
	
			// Check if the announcement is global or specific
			$builder->groupStart();
			$builder->where('level', 'all');
	
			if (!empty($church_ids)) {
				foreach ($church_ids as $id) {
					$builder->orWhere("JSON_CONTAINS(church_id, '\"$id\"')");
				}
			}
			
			$builder->groupEnd();
	
		}
	
		// Add search functionality
		if (!empty($search)) {
			$builder->like('title', $search);
		}
	
		// Order by announcement ID
		$builder->orderBy('id', 'DESC');
	
		// Handle limits and offsets
		if ($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} elseif ($limit) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}
		
		// Return the results
		return $query->getResult();
		
        $db->close();
	}

	public function filter_social($limit = '', $offset = '', $user_id, $search = '', $switch_id = '') {
		$db = db_connect();
		$builder = $db->table('social_post');
	
		// Get user role
		$role_id = $this->read_field('id', $user_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		
		$ministry_id = $this->read_field('id', $user_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $user_id, 'user', 'church_id');
	
	
		if ($role != 'developer' && $role != 'administrator') {
			// Initialize the query based on user role
			if ($role == 'ministry administrator') {
				$builder->where('ministry_id', $ministry_id);
			} else {
				$builder->where('church_id', $church_id);
			}
		}
	
		// Add search functionality
		if (!empty($search)) {
			$builder->like('title', $search);
			$builder->orLike('content', $search);
		}
	
		// Order by announcement ID
		$builder->orderBy('id', 'DESC');
	
		// Handle limits and offsets
		if ($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} elseif ($limit) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}
		
		// Return the results
		return $query->getResult();
		
        $db->close();
	}
	
	//Filter Announements
	public function filter_templates($limit = '', $offset = '', $user_id, $search = '', $switch_id = '') {
		$db = \Config\Database::connect();  // CI4 way of connecting to the database
		$builder = $db->table('service_template');
		
		// Get user role
		$role_id = $this->read_field('id', $user_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
	
		$ministry_id = $this->read_field('id', $user_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $user_id, 'user', 'church_id');
	
		// If switch_id is provided, change the ministry and church context based on the church type
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');
	
			switch ($church_type) {
				case 'region':
					$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
					$role = 'regional manager';
					break;
				case 'zone':
					$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
					$role = 'zonal manager';
					break;
				case 'group':
					$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
					$role = 'group manager';
					break;
				case 'church':
					$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
					$role = 'church leader';
					break;
			}
	
			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		}
	
		// Join with the church table to access church_type, region, zone, and group if needed
		
		// Filter based on role (if not developer or admin)
		if ($role != 'developer' && $role != 'administrator') {
			if ($role === 'ministry administrator') {
				// Ministry Administrator: Apply ministry_id filter
				$builder->where('ministry_id', $ministry_id);
				
			} else {
				$builder->join('church', 'church.id = service_template.church_id');
	
				// For all other non-developer/admin roles
				// 1. Return records where type is 'all' and ministry_id matches
				$builder->groupStart()
						->where('service_template.type', 'all')
						->where('service_template.ministry_id', $ministry_id)
						->groupEnd();
	
				// 2. Return records where type is not 'all' and match church_id
				$builder->orGroupStart()
						->where('service_template.type !=', 'all')
						->where('service_template.church_id', $church_id)
						->groupEnd();
	
				// 3. Additional logic: If type is not 'all', check is_sharing and church_type
				$builder->orGroupStart()
						->where('service_template.type !=', 'all')
						->groupStart()
							->where('church.type', 'region')
							->where('is_sharing', $this->read_field('id', $church_id, 'church', 'regional_id'))  // Compare with user's region
						->groupEnd()
						->orGroupStart()
							->where('church.type', 'zone')
							->where('is_sharing', $this->read_field('id', $church_id, 'church', 'zonal_id'))  // Compare with user's zone
						->groupEnd()
						->orGroupStart()
							->where('church.type', 'group')
							->where('is_sharing', $this->read_field('id', $church_id, 'church', 'group_id'))  // Compare with user's group
						->groupEnd()
					->groupEnd();
			}
		}
	
		// Search functionality
		if (!empty($search)) {
			$builder->like('service_template.name', $search);
		}
	
		// Order by ID in descending order
		$builder->orderBy('service_template.id', 'DESC');
	
		// Handle limits and offsets
		if ($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} elseif ($limit) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}
	
		// Return the result
		return $query->getResult();
	
		$db->close();  // Closing the database connection
	}
	

	
	public function filter_service_order($limit = null, $offset = null, $user_id, $search = '', $switch_id = null) {
		$db = \Config\Database::connect();  // CI4 way of connecting to the database
		$builder = $db->table('service_order');
		
		// Get user role
		$role_id = $this->read_field('id', $user_id, 'user', 'role_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));

		$ministry_id = $this->read_field('id', $user_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $user_id, 'user', 'church_id');

		// If switch_id is provided, change the ministry and church context based on the church type
		if (!empty($switch_id)) {
			$church_type = $this->read_field('id', $switch_id, 'church', 'type');

			switch ($church_type) {
				case 'region':
					$role_ids = $this->read_field('name', 'Regional Manager', 'access_role', 'id');
					$role = 'regional manager';
					break;
				case 'zone':
					$role_ids = $this->read_field('name', 'Zonal Manager', 'access_role', 'id');
					$role = 'zonal manager';
					break;
				case 'group':
					$role_ids = $this->read_field('name', 'Group Manager', 'access_role', 'id');
					$role = 'group manager';
					break;
				case 'church':
					$role_ids = $this->read_field('name', 'Church Leader', 'access_role', 'id');
					$role = 'church leader';
					break;
			}

			$ministry_id = $this->read_field('id', $switch_id, 'church', 'ministry_id');
			$church_id = $switch_id;
		}

		// Filter based on role (if not developer or admin)
		if ($role != 'developer' && $role != 'administrator') {
			if ($role === 'ministry administrator') {
				$builder->where('ministry_id', $ministry_id);
			} else {
				$builder->where('church_id', $church_id);
			}
		}

		// Search functionality
		if (!empty($search)) {
			$builder->like('name', $search);
		}

		// Order by ID in descending order
		$builder->orderBy('id', 'DESC');

		// Handle limits and offsets
		if ($limit && $offset) {
			$query = $builder->get($limit, $offset);
		} elseif ($limit) {
			$query = $builder->get($limit);
		} else {
			$query = $builder->get();
		}

		// Return the result
		return $query->getResult();

		$db->close();  // Closing the database connection
	}


	public function filter_church_admin($limit='', $offset='',$log_id, $status='all', $search='', $role_id, $ministry_id='') {
        $db = db_connect();
        $builder = $db->table('user');
		$builder->where('is_admin', 1);
		$builder->where('church_id', $role_id);
        // $builder->where('ministry_id', $ministry_id);
        // build query
		$builder->orderBy('id', 'DESC');
		if(!empty($search)) {
            $builder->groupStart()
				->like('surname', $search)
				->orLike('email', $search)
				->orLike('firstname', $search)
				->orLike('othername', $search)
				->orLike('phone', $search)
				->groupEnd();
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



	public function filter_cell_members($limit='', $offset='',$log_id, $status='all', $search='', $cell_id) {
        $db = db_connect();
        $builder = $db->table('user');
		$builder->where('cell_id', $cell_id);
        
        // build query
		$builder->orderBy('id', 'DESC');
		if(!empty($search)) {
            $builder->groupStart()
				->like('surname', $search)
				->orLike('email', $search)
				->orLike('firstname', $search)
				->orLike('othername', $search)
				->orLike('phone', $search)
				->groupEnd();
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


	public function filter_church_pastor($limit='', $offset='',$log_id, $status='all', $search='', $role_id) {
        $db = db_connect();
        $builder = $db->table('user');
		$builder->where('is_pastor', 1);
		$builder->where('church_id', $role_id);
        
        // build query
		$builder->orderBy('id', 'DESC');
		if(!empty($search)) {
            $builder->groupStart()
				->like('surname', $search)
				->orLike('email', $search)
				->orLike('firstname', $search)
				->orLike('othername', $search)
				->orLike('phone', $search)
				->groupEnd();
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
    public function filter_foundation_students($limit='', $offset='', $log_id, $foundation_id='', $search='', $status='') {
        $db = db_connect();
        $builder = $db->table('foundation_student');

        // build query
		$builder->orderBy('id', 'DESC');
		$role_id = $this->read_field('id', $log_id, 'user', 'role_id');
		$ministry_id = $this->read_field('id', $log_id, 'user', 'ministry_id');
		$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
		$role = strtolower($this->read_field('id', $role_id, 'access_role', 'name'));
		if($role != 'developer' && $role != 'administrator'){
			if($role == 'ministry administrator'){
				$builder->where('ministry_id', $ministry_id);
			} else {
				$builder->where('church_id', $church_id);
			}
		}

		//$builder->where('role_id', 3);
		if(!empty($foundation_id))$builder->where('foundation_id', $foundation_id);
		if(!empty($status)){
			$builder->where('status', $status);
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

	public function check_json($table, $church_ids_json, $year, $quarter){
		$db = db_connect();
        $builder = $db->table($table);

		// Check if the church_id already exists in the same year and quarter
		$builder->where("year", $year)
		->where("quarter", $quarter)
		->where("JSON_CONTAINS(church_id, '$church_ids_json')");
		// Use countAllResults to get the number of matching records
		$count = $builder->countAllResults();  // Store result in $count variable

		// Close the database connection
		$db->close();
	
		// Return the count
		return $count;
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
	public function activity($item, $item_id, $action, $user_id='')
	{
		if(empty($user_id))$user_id = session()->get('td_id');
		$ins['item'] = $item;
		$ins['item_id'] = $item_id;
		$ins['action'] = $action;
		$ins['user_id'] = $user_id;
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
				$builder->where("user_id", $user_id);
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
				$builder->where("from_id", $user_id);
				$builder->orWhere("to_id", $user_id);
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

	public function cur_exchange($amount=0){
		if(session()->get('td_id')){
			$log_id = session()->get('td_id');
			$church_id = $this->read_field('id', $log_id, 'user', 'church_id');
			$country_id = $this->read_field('id', $church_id, 'church', 'country_id');
			$rate = $this->read_field('country_id', $country_id, 'currency', 'rate');
			$default_currency = $this->read_field('id', $church_id, 'church', 'default_currency');
			
			if($default_currency > 0){
				$result = $amount;
			} else {
				if($rate){
					$result = (float)$amount / (float)$rate;
				} else{
					$result = (float)$amount / 1;
				}
				
			}
		} else {
			$result = 0;
		}

		return $result;
	}
	
	public function finance_exchange($amount=0,$currency_id){
		$result = 0;
			
		$rate = $this->read_field('id', $currency_id, 'currency', 'rate');

		if($rate && $amount > 0){
			$result = (float)$amount * (float)$rate;
		} else{
			$result = (float)$amount * 1;
		}
		
		

		return $result;
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
	public function qrcode($data = '')	{
		if (empty($data)) {
			return false;
		}

		// Encode data to use as filename
		$hex_data   = bin2hex($data);
		$save_name  = $hex_data . '.png';

		// QR Code save directory
		$dir = 'assets/images/qr/profile/';
		$full_path = FCPATH . $dir;
		if (!is_dir($full_path)) {
			mkdir($full_path, 0775, true);
		}

		// QR Code config
		$config['cacheable']    = true;
		$config['imagedir']     = $dir;
		$config['quality']      = true;
		$config['size']         = 1024;
		$config['black']        = [0, 0, 0];      // fix: black foreground
		$config['white']        = [255, 255, 255]; // white background

		$this->ciqrcode->initialize($config);

		// QR code params
		$params['data']     = $data;
		$params['level']    = 'L';
		$params['size']     = 10;
		$params['savename'] = FCPATH . $dir . $save_name;

		$this->ciqrcode->generate($params);

		// Return info
		return [
			'content' => $data,
			'path'    => $dir . $save_name,   // relative path
			'url'     => base_url($dir . $save_name), // public URL
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
		// Increase memory and execution time limits
		ini_set('memory_limit', '512M');      // or more if needed
		set_time_limit(300);                  // 5 minutes max execution time

		// Load the Excel reader
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$reader->setReadDataOnly(true); // ✅ Ignore styles, charts, etc.

		// Load spreadsheet from uploaded file
		$spreadsheet = $reader->load($file->getTempName());
		$sheet = $spreadsheet->getActiveSheet();
		$highestRow = $sheet->getHighestRow();

		$data = [];
		$status = false;

		for ($row = 1; $row <= $highestRow; ++$row) {
			$firstname            = $sheet->getCellByColumnAndRow(1, $row)->getValue();
			$othername            = $sheet->getCellByColumnAndRow(2, $row)->getValue();
			$surname              = $sheet->getCellByColumnAndRow(3, $row)->getValue();
			$email                = $sheet->getCellByColumnAndRow(4, $row)->getValue();
			$phone                = $sheet->getCellByColumnAndRow(5, $row)->getValue();
			$address              = $sheet->getCellByColumnAndRow(6, $row)->getValue();
			$dobCellValue         = $sheet->getCellByColumnAndRow(7, $row)->getValue();
			$marital_status       = $sheet->getCellByColumnAndRow(8, $row)->getValue();
			$marriage_anniversary = $sheet->getCellByColumnAndRow(9, $row)->getValue();
			$title                = $sheet->getCellByColumnAndRow(10, $row)->getValue();
			$gender               = $sheet->getCellByColumnAndRow(11, $row)->getValue();
			$chat_handle          = $sheet->getCellByColumnAndRow(12, $row)->getValue();
			$job                  = $sheet->getCellByColumnAndRow(13, $row)->getValue();
			$foundation_school    = $sheet->getCellByColumnAndRow(14, $row)->getValue();
			$baptism              = $sheet->getCellByColumnAndRow(15, $row)->getValue();
			$employer_address     = $sheet->getCellByColumnAndRow(16, $row)->getValue();
			$family_position      = $sheet->getCellByColumnAndRow(17, $row)->getValue();
			$cell			      = $sheet->getCellByColumnAndRow(18, $row)->getValue();

			// Header validation
			if ($row == 1) {
				$expectedHeaders = [
					'firstname', 'othername', 'surname', 'email', 'phone', 'address',
					'dob', 'marital-status', 'marriage-anniversary', 'title', 'gender',
					'chat-handle', 'job', 'foundation-school', 'baptism',
					'employer-address', 'family-position', 'cell'
				];

				$actualHeaders = [
					strtolower($firstname), strtolower($othername), strtolower($surname),
					strtolower($email), strtolower($phone), strtolower($address),
					strtolower($dobCellValue), strtolower($marital_status),
					strtolower($marriage_anniversary), strtolower($title),
					strtolower($gender), strtolower($chat_handle), strtolower($job),
					strtolower($foundation_school), strtolower($baptism),
					strtolower($employer_address), strtolower($family_position), strtolower($cell)
				];

				if ($actualHeaders !== $expectedHeaders) {
					return ['error' => 'Invalid Excel headers. Please use the correct template.'];
				}

				$status = true;
				continue;
			}

			if ($status) {
				$dobFormatted = $this->convertExcelDate($dobCellValue);
				$marriageFormatted = $this->convertExcelDate($marriage_anniversary);

				$data[] = [
					'firstname'            => trim($firstname),
					'othername'            => trim($othername),
					'surname'              => trim($surname),
					'email'                => strtolower(trim($email)),
					'phone'                => preg_replace('/[^0-9]/', '', $phone),
					'address'              => trim($address),
					'dob'                  => $dobFormatted,
					'marital_status'       => strtolower(trim($marital_status)),
					'marriage_anniversary' => $marriageFormatted,
					'title'                => ucwords(trim($title)),
					'gender'               => ucwords(trim($gender)),
					'chat_handle'          => strtolower(trim($chat_handle)),
					'job'                  => trim($job),
					'foundation_school'    => strtolower(trim($foundation_school)),
					'baptism'              => strtolower(trim($baptism)),
					'employer_address'     => trim($employer_address),
					'family_position'      => strtolower(trim($family_position)),
					'cell'   			   => strtolower(trim($cell)),
				];
			}
		}

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

	public function convertMinutesToTime($minutes) {
		// Check if the value is greater than or equal to 60
		if ($minutes >= 60) {
			// Calculate the number of hours and minutes
			$hours = floor($minutes / 60);
			$remainingMinutes = $minutes % 60;
	
			// Return formatted time with hours and minutes
			return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . ($remainingMinutes > 0 ? $remainingMinutes . ' min' : '');
		} else {
			// If less than 60, just return the minutes
			return $minutes . ' mins';
		}
	}

	public function generateExcelReport($headers, $content) {
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set dynamic headers for the Excel file
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $column++;
        }

        // Populate the data dynamically from the passed $content array
        $row = 2;
        foreach ($content as $data) {
            $column = 'A';
            foreach ($data as $value) {
                $sheet->setCellValue($column . $row, $value);
                $column++;
            }
            $row++;
        }

        // Create a writer instance and return the file content
        $writer = new Xlsx($spreadsheet);

        // Use output buffering to capture the generated file content
        ob_start();
        $writer->save('php://output');
        $excelContent = ob_get_contents();
        ob_end_clean();

        return $excelContent;
    }
	public function get_sub_churches($id)
	{
		$sub_churches = [];
	
		$church_type = $this->read_field('id', $id, 'church', 'type');
	
		switch (strtolower($church_type)) {
			case 'region':
				$churches = $this->read_single('regional_id', $id, 'church');
				break;
			case 'zone':
				$churches = $this->read_single('zonal_id', $id, 'church');
				break;
			case 'group':
				$churches = $this->read_single('group_id', $id, 'church');
				break;
			case 'center':
			case 'church':
				$churches = $this->read_single('church_id', $id, 'church');
				break;
			default:
				$churches = [];
		}
	
		if (!empty($churches)) {
			foreach ($churches as $c) {
				$sub_churches[] = $c->id;
			}
		}
	
		return $sub_churches;
	}
	


	////////////////////////JITZI/////////////////////////////////
	// Define a function to create the room and return the link
    public function createRoom($roomName=''){
        
        if (!$roomName) {
            return $this->failValidationError('Room name is required');
        }

        // Generate the room ID (could be based on the name, a UUID, or another method)
        $roomId = $this->generateRoomId($roomName);

        // Jitsi URL for creating and joining the room
        $jitsiBaseUrl = 'https://meet.jit.si'; // Or your own Jitsi server URL

        // Room link to join
        $roomLink = $jitsiBaseUrl . '/' . $roomId;
		// echo $roomLink;
        // Return the room details as a response
		return [
			'room_name' => $roomName,
			'room_id' => $roomId,
			'room_link' => $roomLink
		];
    }

    // Function to generate a unique room ID
    private function generateRoomId($roomName)
    {
        // Generate a unique ID for the room based on the room name
        // For example, we could use the room name and a timestamp to ensure uniqueness
        return strtolower(url_title($roomName)) . '-' . time();
    }

	public function generateJwt($roomName = 'TeamMeeting123', $role = 'participant', $name ='', $church ='')
    {
        // Load the private key from file
        $privateKey = file_get_contents($this->privateKeyPath);

        // Set JWT payload
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // Token valid for 1 hour
        $payload = [
            'room' => $roomName,
            'role' => $role,  // 'moderator' or 'participant'
            'exp' => $expirationTime,  // Expiration time
            'iat' => $issuedAt, 
			'context' => [              // Include context object in the payload
                'user' => [
                    'name' => 'John Doe',  // Example username (can be dynamic)
                    'email' => 'john.doe@example.com' // Example email
                ],
                'room' => $roomName // Room name context (to match the JWT's room name)
            ]        // Issued at time
        ];

        // Encode the JWT token using the Private Key
        $jwt = JWT::encode($payload, $privateKey, 'RS256');  // RS256 (RSA SHA256) algorithm

        return $jwt;  // Return the generated JWT token
    }

	public function generateJaaSToken($roomName, $role = 'participant', $name = '', $church = '') {
		// Path to your private key (replace with the actual path to your private key)
		$privateKey = file_get_contents($this->privateKeyPath);  // Update with your private key path
		
		// Set JWT claims (payload)
		$issuedAt = time();  // Current timestamp
		$expirationTime = $issuedAt + 3600;  // Token valid for 1 hour
		$notBeforeTime = $issuedAt;  // Not before time (current timestamp)
	
		// Header with `kid` (Key ID) set to the tenant ID
		$header = [
			'alg' => 'RS256',  // Algorithm used for signing
			'kid' => 'vpaas-magic-cookie-0ab53463adb9451ab8ddd32e5206ef9f/ea351f',  // Key ID (use the tenant ID here)
			'typ' => 'JWT'  // Token type
		];
	
		// Payload for JWT
		$payload = [
			'aud' => 'jitsi',  // Audience (should be 'jitsi')
			'exp' => $expirationTime,  // Expiration time
			'iss' => 'chat',  // Issuer (should be 'chat')
			'nbf' => $notBeforeTime,  // Not before time (timestamp)
			'room' => '*',  // Room (wildcard or specific room name)
			'sub' => 'vpaas-magic-cookie-0ab53463adb9451ab8ddd32e5206ef9f',  // Subject (tenant or user identifier)
			'context' => [  // Context (additional user data)
				'user' => [
					'id' => '0f8b7760-c17f-4a12-b134-c6ac37167144',  // Unique user ID
					'name' => $name,  // User name (pass as parameter)
					'avatar' => 'https://link.to/user/avatar/picture',  // Avatar URL (pass as needed)
					'email' => $church,  // User email (pass as parameter)
					'moderator' => 'false'  // Set to true if the user is a moderator
				],
				'features' => [  // Features object (additional meeting features)
					'livestreaming' => 'true',  // Enable livestreaming
					'recording' => 'true'  // Enable recording
				]
			]
		];
	
		// Encode the JWT token using the Private Key with RS256 algorithm
		$jwt = JWT::encode($payload, $privateKey, 'RS256', null, $header);
	
		return $jwt;  // Return the generated JWT token
	}
	////////////////////////////////////////////////////////////////////////////

	public function mask_email($email) {
		$parts = explode("@", $email);
		$name = $parts[0];
		$domain = $parts[1];
	
		$visible = substr($name, 0, 3);
		$masked = str_repeat('*', max(strlen($name) - 3, 1));
	
		return $visible . $masked . '@' . $domain;
	}
	
	public function mask_phone($phone) {
		$clean = preg_replace('/[^0-9]/', '', $phone); // remove any formatting
		$length = strlen($clean);
	
		if ($length <= 7) return str_repeat('*', $length); // fallback
	
		$first = substr($clean, 0, 5);
		$last = substr($clean, -2);
	
		return $first . str_repeat('*', $length - 7) . $last;
	}
	////////////////////////CRON JOB///////////////////////////
	// Method to create a cron job dynamically
	public function createCronJob($datetime, $functionName)
    {
        // Validate if the function name exists in the Cron controller
        if (!method_exists('App\Controllers\Cron', $functionName)) {
            return 'Invalid function name.';
        }

        // Convert the datetime string into a Unix timestamp
        $timestamp = strtotime($datetime);

        if ($timestamp === false) {
            // If strtotime fails, return an error
            return 'Invalid date/time format.';
        }

        // Check if the provided datetime is in the past
        $currentTimestamp = time(); // Get the current timestamp
        if ($timestamp < $currentTimestamp) {
            return 'The provided date and time have already passed. Please select a future date and time.';
        }

        // Generate the cron expression
        $minute = date('i', $timestamp);
        $hour = date('H', $timestamp);
        $day = date('d', $timestamp);
        $month = date('m', $timestamp);

        // Cron expression for the desired time
        $cronExpression = "$minute $hour $day $month *"; // Runs once at the specified time

        // Get the correct path to CI4's index.php file
        $projectPath = $_SERVER['DOCUMENT_ROOT'] . '/public/index.php'; // Adjust path as needed

        // Define the command to run the function dynamically
        $command = "/usr/bin/php $projectPath cron/$functionName";

        // Prepare the cron job entry
        $cronJob = "$cronExpression $command" . PHP_EOL;

        // Define the writable directory path directly
        $cronJobFilePath = FCPATH . 'writable/crontab.txt'; // Correct path to writable directory

        // Check if the writable directory exists and create it if it doesn't
        if (!is_dir(FCPATH . 'writable')) {
            // Attempt to create the directory if it doesn't exist
            if (!mkdir(FCPATH . 'writable', 0777, true)) {
                return 'Failed to create the writable directory.';
            }
        }

        // Check if the directory is writable
        if (!is_writable(FCPATH . 'writable')) {
            return 'The writable directory is not writable.';
        }

        // Append the cron job to the crontab.txt file
        file_put_contents($cronJobFilePath, shell_exec('crontab -l') . $cronJob);

        // Update the crontab with the new job
        shell_exec('crontab ' . $cronJobFilePath);

        log_message('info', 'Cron job scheduled: ' . $cronJob);

       // Display the list of all cron jobs after adding the new one
	   $this->showCronJobs();

		return 'Cron job scheduled for: ' . date('Y-m-d H:i:s', $timestamp);
	}

	// Method to show all the current cron jobs
	public function showCronJobs()
	{
		// Use `crontab -l` to list all cron jobs
		$cronJobs = shell_exec('crontab -l');

		// Log or display the cron jobs
		log_message('info', 'Current cron jobs: ' . $cronJobs);

		// Optionally, you can return the list of cron jobs to the user
		return $cronJobs;
	}


	// Method to execute the cron job task (this will be called when the cron job runs)
	public function runTask($functionName)	{
		// Make sure the function exists
		if (!method_exists('App\Controllers\Cron', $functionName)) {
			log_message('error', "The function $functionName does not exist.");
			return;
		}

		// Call the method in the Cron controller
		$cronController = new \App\Controllers\Cron();
		$cronController->$functionName(); // Dynamically call the method

		$this->create('cron', array('response' => 'Example task executed at ' . date('Y-m-d H:i:s')));
		// After the task is completed, delete the cron job
		$this->deleteCronJob($functionName);
	}

	// Method to delete the cron job after execution
	public function deleteCronJob($functionName){
		// Remove the specific cron job from crontab (by identifying the function name)
		$output = shell_exec("crontab -l | grep -v 'php /path/to/your/project/public/index.php cron/$functionName' | crontab -");
		log_message('info', 'Cron job deleted: ' . $output);
	}

	
	public function generateUniqueCode($length = 6){
		do {
			$code = strtoupper(bin2hex(random_bytes($length / 2)));
			$exists = $this->check('first_timer_link', $code, 'church') > 0;
		} while ($exists);

		return $code;
	}

	public function getGmtOffsetFromLocation($state, $country, $geonamesUsername='tophunmi') {
		// 1. Geocode the location (get lat/lng)
		$location = urlencode("$state, $country");
		$geoUrl = "https://nominatim.openstreetmap.org/search?q=$location&format=json&limit=1";
	
		$geoContext = stream_context_create([
			'http' => [
				'header' => "User-Agent: KemafyApp/1.0\r\n"
			]
		]);
	
		$geoResponse = file_get_contents($geoUrl, false, $geoContext);
		$geoData = json_decode($geoResponse, true);
	
		if (empty($geoData) || !isset($geoData[0]['lat'], $geoData[0]['lon'])) {
			return ['error' => "❌ Could not find coordinates for $state, $country"];
		}
	
		$lat = $geoData[0]['lat'];
		$lon = $geoData[0]['lon'];
	
		// 2. Get time zone using GeoNames
		$tzUrl = "http://api.geonames.org/timezoneJSON?lat={$lat}&lng={$lon}&username={$geonamesUsername}";
		$tzResponse = file_get_contents($tzUrl);
		$tzData = json_decode($tzResponse, true);
	
		// ✅ Check for expected fields and errors
		if (isset($tzData['status']['message'])) {
			return ['error' => "❌ GeoNames API error: " . $tzData['status']['message']];
		}
	
		if (!isset($tzData['gmtOffset']) || !isset($tzData['timezoneId'])) {
			return ['error' => "❌ Unexpected response structure from GeoNames."];
		}
	
	
		if (isset($tzData['gmtOffset'])) {
			return [
				'state' => $state,
				'country' => $country,
				'lat' => $lat,
				'lon' => $lon,
				'timezone' => $tzData['timezoneId'],
				'gmtOffset' => $tzData['gmtOffset'],
				'gmtLabel' => 'GMT' . ($tzData['gmtOffset'] >= 0 ? '+' : '') . $tzData['gmtOffset'],
				'localTime' => $tzData['time']
			];
		} else {
			return ['error' => "❌ Could not retrieve timezone info for $state, $country"];
		}
	}
	 
	public function getUserTimezone($userId){
		$db = \Config\Database::connect();
	
		// Step 1: Get the user's church_id
		$builder = $db->table('user');
		$builder->select('church_id');
		$builder->where('id', $userId);
		$user = $builder->get()->getRow();
	
		if (!$user) {
			throw new \Exception('User not found');
		}
	
		// Step 2: Get the state_id from the church
		$builder = $db->table('church');
		$builder->select('state_id');
		$builder->where('id', $user->church_id);
		$church = $builder->get()->getRow();
	
		if (!$church) {
			throw new \Exception('Church not found');
		}
	
		// Step 3: Get the timezone_id from the state
		$builder = $db->table('state');
		$builder->select('timezone_id');
		$builder->where('id', $church->state_id);
		$state = $builder->get()->getRow();
	
		// Step 4: Return timezone_id if valid
		if (!empty($state->timezone_id) && in_array($state->timezone_id, timezone_identifiers_list())) {
			return $state->timezone_id;
		}
	
		// Step 5: Fallback — return server's default timezone
		return date_default_timezone_get(); // e.g. 'UTC' or 'Africa/Lagos'
	}

	public function getChurchTimezone($churchId)	{
		$db = \Config\Database::connect();

		$builder = $db->table('church');
		$builder->select('state_id');
		$builder->where('id', $churchId);
		$church = $builder->get()->getRow();

		if (!$church) {
			return date_default_timezone_get();
		}

		$builder = $db->table('state');
		$builder->select('timezone_id');
		$builder->where('id', $church->state_id);
		$state = $builder->get()->getRow();

		if (!empty($state->timezone_id) && in_array($state->timezone_id, timezone_identifiers_list())) {
			return $state->timezone_id;
		}

		return date_default_timezone_get();
	}

	protected $apiKey = 'your_api_key_here'; // Replace this with your actual API key
    protected $endpoint = 'https://api.espees.org/agents/vending/createtoken';

	public function espees_token(){
		$walletAddress = '0x0bd3e40f8410ea473850db5479348f074d254ded';
		$walletPin = '1234';
		$vendingHash = bin2hex(random_bytes(8)); // exactly 16 alphanumeric characters

		
		$payload = json_encode([
            'vending_wallet_address' => $walletAddress,
            'vending_wallet_pin'     => $walletPin,
            'vending_hash'           => $vendingHash,
        ]);

        $ch = curl_init($this->endpoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-api-key: ' . $this->apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'status'  => 'error',
                'message' => $error
            ];
        }

        return [
            'status' => 'success',
            'data'   => json_decode($response, true)
        ];
    
	}

	//////////////////////////////////END///////////////////////////////////////////////////////
}