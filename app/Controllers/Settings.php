<?php 

namespace App\Controllers;

class Settings extends BaseController {
	public function index() {
		return $this->modules();
	}
	
	/////// MODULES
	public function modules($param1='', $param2='', $param3='') {
		// check login
        $log_id = $this->session->get('td_id');
        if(empty($log_id)) return redirect()->to(site_url('auth'));

		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		$permit = array('developer');
		if(!in_array(strtolower($role), $permit)) return redirect()->to(site_url('dashboard'));

        $data['log_id'] = $log_id;
		
		$table = 'access_module';
		
        $data['current_language'] = $this->session->get('current_language');
		$form_link = site_url('settings/modules/');
		if($param1){$form_link .= $param1.'/';}
		if($param2){$form_link .= $param2.'/';}
		if($param3){$form_link .= $param3.'/';}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = rtrim($form_link, '/');
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_module_id');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', 'Record Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
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
								$data['e_link'] = $e->link;
								$data['e_icon'] = $e->icon;
								$data['e_priority'] = $e->priority;
								$data['load_select_module'] = $this->load_select_module($e->parent);
							}
						}
					}
				} else {
					$data['load_select_module'] = $this->load_select_module();
				}

				if($this->request->getMethod() == 'post'){
					$module_id = $this->request->getVar('module_id');
					$parent_id = $this->request->getVar('parent_id');
					$name = $this->request->getVar('name');
					$link = $this->request->getVar('link');
					$icon = $this->request->getVar('icon');
					$priority = $this->request->getVar('priority');

					$ins_data['parent'] = $parent_id;
					$ins_data['name'] = $name;
					$ins_data['link'] = $link;
					$ins_data['icon'] = $icon;
					$ins_data['priority'] = $priority;
					
					// do create or update
					if($module_id) {
						$upd_rec = $this->Crud->updates('id', $module_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', 'Record Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('link', $link, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', 'Record Created');
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', 'Please try later');	
							}	
						}
					}

					die;	
				}
			}
		}
		
		// record listing
		if($param1 == 'list') {
			// DataTable parameters
			$table = 'access_module';
			$column_order = array('parent', 'name', 'link', 'icon', 'priority');
			$column_search = array('parent', 'name', 'link', 'icon', 'priority');
			$order = array('id' => 'desc');
			$where = '';
			
			// load data into table
			$list = $this->Crud->datatable_load($table, $column_order, $column_search, $order, $where);
			$data = array();
			// $no = $_POST['start'];
			$count = 1;
			foreach ($list as $item) {
				$id = $item->id;
				$parent_id = $item->parent;
				$name = $item->name;
				$link = $item->link;
				$icon = $item->icon;
				$priority = $item->priority;

				$parent = '';
				if($parent_id > 0) {
					$parent_name = $this->Crud->read_field('id', $parent_id, 'access_module', 'name');
					$parent_parent_id = $this->Crud->read_field('id', $parent_id, 'access_module', 'parent');
					$parent = $parent_name.' <i class="anticon anticon-arrow-right"></i>';

					$parent_parent_name = '';
					if($parent_parent_id > 0) {
						$parent_parent_name = $this->Crud->read_field('id', $parent_parent_id, 'access_module', 'name');
						$parent = $parent_parent_name.' <i class="fa fa-arrow-right"></i> '.$parent;
					}
				}
				
				if($parent) {
					$parent = '<span class="small"><b>'.$parent.'</b></span><br/>';
				}

				if($icon) {
					$icon = '<i class="'.$icon.'"></i> ';
				}

				if($link){$link = '/'.$link;}
				
				// add manage buttons
				$all_btn = '
					<div class="text-center">
						<a href="javascript:;" class="text-primary pop" pageTitle="Manage '.$name.'" pageName="'.site_url('settings/modules/manage/edit/'.$id).'">
							<em class="icon ni ni-edit"></em>
						</a>&nbsp;
						<a href="javascript:;" class="text-danger pop" pageTitle="Delete '.$name.'" pageName="'.site_url('settings/modules/manage/delete/'.$id).'">
							<em class="icon ni ni-trash-alt"></em>
						</a>
					</div>
				';
				
				$row = array();
				$row[] = $parent.$priority.' - '.$icon.$name.'<br/><span class="small text-muted">'.$link.'</span>';
				$row[] = $all_btn;
	
				$data[] = $row;
				$count += 1;
			}
	
			$output = array(
				"draw" => intval($_POST['draw']),
				"recordsTotal" => $this->Crud->datatable_count($table, $where),
				"recordsFiltered" => $this->Crud->datatable_filtered($table, $column_order, $column_search, $order, $where),
				"data" => $data,
			);
			
			//output to json format
			echo json_encode($output);
			exit;
		}
		
		if($param1 == 'manage') { // view for form data posting
			return view('setting/module_form', $data);
		} else { // view for main page
			// for datatable
			$data['table_rec'] = 'settings/modules/list'; // ajax table
			$data['order_sort'] = '0, "asc"'; // default ordering (0, 'asc')
			$data['no_sort'] = '1'; // sort disable columns (1,3,5)
		
			$data['title'] = 'Modules | '.app_name;
			$data['page_active'] = 'module';
			
			return view('setting/module', $data);
		}
	}
	
	/////// ROLES
	public function roles($param1='', $param2='', $param3='') {
		// check login
        $log_id = $this->session->get('td_id');
        if(empty($log_id)) return redirect()->to(site_url('auth'));

		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		$permit = array('developer');
		if(!in_array(strtolower($role), $permit)) return redirect()->to(site_url('dashboard'));

        $data['log_id'] = $log_id;
		
		$table = 'access_role';

        $data['current_language'] = $this->session->get('current_language');
		$form_link = site_url('settings/roles/');
		if($param1){$form_link .= $param1.'/';}
		if($param2){$form_link .= $param2.'/';}
		if($param3){$form_link .= $param3.'/';}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = rtrim($form_link, '/');
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_role_id');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', 'Record Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
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
							}
						}
					}
				}
				
				if($this->request->getMethod() == 'post'){
					$role_id = $this->request->getVar('role_id');
					$name = $this->request->getVar('name');

					$ins_data['name'] = $name;
					
					// do create or update
					if($role_id) {
						$upd_rec = $this->Crud->updates('id', $role_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', 'Record Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								echo $this->Crud->msg('success', 'Record Created');
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', 'Please try later');	
							}	
						}
					}
					exit;	
				}
			}
		}
		
		// record listing
		if($param1 == 'list') {
			// DataTable parameters
			$table = 'access_role';
			$column_order = array('name');
			$column_search = array('name');
			$order = array('id' => 'desc');
			$where = '';
			
			// load data into table
			$list = $this->Crud->datatable_load($table, $column_order, $column_search, $order, $where);
			$data = array();
			// $no = $_POST['start'];
			$count = 1;
			foreach ($list as $item) {
				$id = $item->id;
				$name = $item->name;
				
				// add manage buttons
				$all_btn = '
					<div class="text-center">
						<a class="text-primary pop" href="javascript:;" pageTitle="Manage '.$name.'" pageName="'.site_url('settings/roles/manage/edit/'.$id).'">
							<em class="icon ni ni-edit"></em>
						</a>&nbsp;
						<a class="text-danger pop" href="javascript:;" pageTitle="Delete '.$name.'" pageName="'.site_url('settings/roles/manage/delete/'.$id).'">
							<em class="icon ni ni-trash"></em>
						</a>
					</div>
				';
				
				$row = array();
				$row[] = $name;
				$row[] = $all_btn;
	
				$data[] = $row;
				$count += 1;
			}
	
			$output = array(
				"draw" => intval($_POST['draw']),
				"recordsTotal" => $this->Crud->datatable_count($table, $where),
				"recordsFiltered" => $this->Crud->datatable_filtered($table, $column_order, $column_search, $order, $where),
				"data" => $data,
			);
			
			//output to json format
			echo json_encode($output);
			exit;
		}
		
		if($param1 == 'manage') { // view for form data posting
			return view('setting/role_form', $data);
		} else { // view for main page
			// for datatable
			$data['table_rec'] = 'settings/roles/list'; // ajax table
			$data['order_sort'] = '0, "asc"'; // default ordering (0, 'asc')
			$data['no_sort'] = '1'; // sort disable columns (1,3,5)
		
			$data['title'] = 'Roles | '.app_name;
			$data['page_active'] = 'role';
			
			return view('setting/role', $data);
		}
	
	}

	public function partnership($param1='', $param2='', $param3='') {
		// check session login
		if($this->session->get('td_id') == ''){
			$request_uri = uri_string();
			$this->session->set('td_redirect', $request_uri);
			return redirect()->to(site_url('auth'));
		} 

        $mod = 'settings/partnership';

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
       
		
		$table = 'partnership';
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
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}

					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_partnership_id');
						///// store activities
						$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
						$code = $this->Crud->read_field('id', $del_id, 'partnership', 'name');
						$action = $by.' deleted Partnership ('.$code.') Record';

						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							
							$this->Crud->activity('user', $del_id, $action);
							echo $this->Crud->msg('success', 'Partnership Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
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
							}
						}
					}
				} 

				if($this->request->getMethod() == 'post'){
					$partnership_id = $this->request->getVar('partnership_id');
					$name = $this->request->getVar('name');
				
					$ins_data['name'] = $name;
					
					// do create or update
					if($partnership_id) {
						$upd_rec = $this->Crud->updates('id', $partnership_id, $table, $ins_data);
						if($upd_rec > 0) {
							///// store activities
							$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
							$code = $this->Crud->read_field('id', $partnership_id, 'partnership', 'name');
							$action = $by.' updated Partnership ('.$code.') Record';
							$this->Crud->activity('user', $partnership_id, $action);

							echo $this->Crud->msg('success', 'Record Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} else {
						if($this->Crud->check('name', $name, $table) > 0) {
							echo $this->Crud->msg('warning', 'Record Already Exist');
						} else {
							$ins_rec = $this->Crud->create($table, $ins_data);
							if($ins_rec > 0) {
								///// store activities
								$by = $this->Crud->read_field('id', $log_id, 'user', 'firstname');
								$code = $this->Crud->read_field('id', $ins_rec, 'partnership', 'name');
								$action = $by.' created Partnership ('.$code.') Record';
								$this->Crud->activity('user', $ins_rec, $action);

								echo $this->Crud->msg('success', 'Record Created');
								echo '<script>location.reload(false);</script>';
							} else {
								echo $this->Crud->msg('danger', 'Please try later');	
							}	
						}
					}

					die;	
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
			
			$search = $this->request->getPost('search');
			
			$items = '
				<div class="nk-tb-item nk-tb-head">
					<div class="nk-tb-col"><span class="sub-text text-dark"><b>'.translate_phrase('Name').'</b></span></div>
					<div class="nk-tb-col text-center">
						<b>Actions</b>
					</div>
				</div><!-- .nk-tb-item -->
		
				
			';
			$a = 1;

            //echo $status;
			$log_id = $this->session->get('td_id');
			if(!$log_id) {
				$item = '<div class="text-center text-muted">'.translate_phrase('Session Timeout! - Please login again').'</div>';
			} else {
				
				$all_rec = $this->Crud->filter_partnership('', '', $search);
                // $all_rec = json_decode($all_rec);
				if(!empty($all_rec)) { $counts = count($all_rec); } else { $counts = 0; }

				$query = $this->Crud->filter_partnership($limit, $offset, $search);
				$data['count'] = $counts;
				

				if(!empty($query)) {
					foreach ($query as $q) {
						$id = $q->id;
						$name = $q->name;
						
						// add manage buttons
						if ($role_u != 1) {
							$all_btn = '';
						} else {
							$all_btn = '<div class="text-center">
								<a href="javascript:;" class="text-primary pop m-3 mr-3" pageTitle="Edit ' . $name . '" pageSize="modal-sm"  pageName="' . site_url($mod . '/manage/edit/' . $id) . '"><em class="icon ni ni-edit-alt"></em><span>'.translate_phrase('Edit').'</span></a> <a href="javascript:;" class="text-danger pop" pageTitle="Delete ' . $name . '" pageSize="modal-sm"  pageName="' . site_url($mod . '/manage/delete/' . $id) . '"><em class="icon ni ni-trash-alt"></em><span>'.translate_phrase('Delete').'</span></a>
								</div>
								
							';
						}

						$item .= '
							<div class="nk-tb-item">
								<div class="nk-tb-col">
									<div class="user-info">
										<span class="tb-lead">' . ucwords($name) . ' </span>
									</div>
								</div>
								<div class="nk-tb-col nk-tb-col-tools">
									' . $all_btn . '
								</div>
							</div><!-- .nk-tb-item -->
						';
						$a++;
					}
				}
				
			}
			
			if(empty($item)) {
				$resp['item'] = $items.'
					<div class="text-center text-muted">
						<br/><br/><br/><br/>
						<i class="ni ni-link" style="font-size:100px;"></i><br/><br/>'.translate_phrase('No Partnership Returned').'
					</div>
				';
			} else {
				$resp['item'] = $items . $item;
				if($offset >= 25){
					$resp['item'] = $item;
				}
				
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
			return view('setting/partnership_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Partnership').' - '.app_name;
			$data['page_active'] = $mod;
			return view('setting/partnership', $data);
		}
    }
	/////// language
	public function language($param1='', $param2='', $param3='') {
		// check login
        $log_id = $this->session->get('td_id');
        if(empty($log_id)) return redirect()->to(site_url('auth'));

		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		$permit = array('developer', 'administrator');
		if(!in_array(strtolower($role), $permit)) return redirect()->to(site_url('dashboard'));

        $data['log_id'] = $log_id;
		
		$table = 'language_code';

        $data['current_language'] = $this->session->get('current_language');
		$form_link = site_url('settings/language/');
		if($param1){$form_link .= $param1.'/';}
		if($param2){$form_link .= $param2.'/';}
		if($param3){$form_link .= $param3.'/';}
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = rtrim($form_link, '/');
		
		// manage record
		if($param1 == 'manage') {
			// prepare for delete
			if($param2 == 'delete') {
				if($param3) {
					$edit = $this->Crud->read_single('id', $param3, $table);
					if(!empty($edit)) {
						foreach($edit as $e) {
							$data['d_id'] = $e->id;
						}
					}
					
					if($this->request->getMethod() == 'post'){
						$del_id = $this->request->getVar('d_role_id');
						if($this->Crud->deletes('id', $del_id, $table) > 0) {
							echo $this->Crud->msg('success', 'Record Deleted');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('danger', 'Please try later');
						}
						exit;	
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
								$data['e_status'] = $e->status;
							}
						}
					}
				}
				
				if($this->request->getMethod() == 'post'){
					$language_id = $this->request->getVar('language_id');
					$status = $this->request->getVar('status');

					$ins_data['status'] = $status;
					
					// do create or update
					if($language_id) {
						$upd_rec = $this->Crud->updates('id', $language_id, $table, $ins_data);
						if($upd_rec > 0) {
							echo $this->Crud->msg('success', 'Status Updated');
							echo '<script>location.reload(false);</script>';
						} else {
							echo $this->Crud->msg('info', 'No Changes');	
						}
					} 
					exit;	
				}
			}
		}
		
		// record listing
		if($param1 == 'list') {
			// DataTable parameters
			$table = 'language_code';
			$column_order = array('name',  'code', 'flag', 'status');
			$column_search = array('name', 'code', 'flag', 'status');
			$order = array('status' => 'desc');
			$where = '';
			
			// load data into table
			$list = $this->Crud->datatable_load($table, $column_order, $column_search, $order, $where);
			$data = array();
			// $no = $_POST['start'];
			$count = 1;
			foreach ($list as $item) {
				$id = $item->id;
				$name = $item->name;
				$code = $item->code;
				$flag = $item->flag;
				$status = $item->status;

				if(empty($flag)){
					$flags = '<em class="icon ni ni-flag"></em>';
				}
				
				$st = '<span class="text-danger"> Disabled</span>';
				if($status > 0)$st = '<span class="text-success">Active</span>';
				// add manage buttons
				$all_btn = '
					<div class="text-center">
						<a class="text-primary pop" href="javascript:;" pageTitle="Manage '.$name.'" pageName="'.site_url('settings/language/manage/edit/'.$id).'">
							<em class="icon ni ni-edit"></em>
						</a>
					</div>
				';
				
				$row = array();
				$row[] = $name;
				$row[] = $code;
				$row[] = $flags;
				$row[] = $st;
				$row[] = $all_btn;
	
				$data[] = $row;
				$count += 1;
			}
	
			$output = array(
				"draw" => intval($_POST['draw']),
				"recordsTotal" => $this->Crud->datatable_count($table, $where),
				"recordsFiltered" => $this->Crud->datatable_filtered($table, $column_order, $column_search, $order, $where),
				"data" => $data,
			);
			
			//output to json format
			echo json_encode($output);
			exit;
		}
		
		if($param1 == 'manage') { // view for form data posting
			return view('setting/language_form', $data);
		} else { // view for main page
			// for datatable
			$data['table_rec'] = 'settings/language/list'; // ajax table
			$data['order_sort'] = '3, "desc"'; // default ordering (0, 'asc')
			$data['no_sort'] = '1,2,4'; // sort disable columns (1,3,5)
		
			$data['title'] = 'Language Settings | '.app_name;
			$data['page_active'] = 'language';
			
			return view('setting/language', $data);
		}
	
	}
	
	/////// ACCESS CRUD
	public function access() {
		// check login
        $log_id = $this->session->get('td_id');
        if(empty($log_id)) return redirect()->to(site_url('auth'));

		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		$permit = array('developer');
		if(!in_array(strtolower($role), $permit)) return redirect()->to(site_url('dashboard'));

        $data['log_id'] = $log_id;
        $data['current_language'] = $this->session->get('current_language');

		$data['allrole'] = $this->Crud->read('access_role');
			
		$data['title'] = 'Access CRUD | '.app_name;
		$data['page_active'] = 'access';
		
		return view('setting/access', $data);
	
	}

	/////// APP SETTINGS
	public function app() {
		// check login
        $log_id = $this->session->get('td_id');
        if(empty($log_id)) return redirect()->to(site_url('auth'));

		$role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
		$role = strtolower($this->Crud->read_field('id', $role_id, 'access_role', 'name'));
		$permit = array('developer');
		if(!in_array(strtolower($role), $permit)) return redirect()->to(site_url('dashboard'));

        $data['log_id'] = $log_id;

		$data['settings'] = $this->Crud->read_order('setting', 'name', 'asc');
			
		$data['title'] = 'Application Settings | '.app_name;
		$data['page_active'] = 'app';
		
        $data['current_language'] = $this->session->get('current_language');
		return view('setting/app', $data);
	
	}

	public function load_select_module($edit_id='') {
		$parent = '';
		$parents = $this->Crud->read_order('access_module', 'name', 'asc');
		if(!empty($parents)) {
			foreach($parents as $pt) {
				if($edit_id == $pt->id){$sel = 'selected';} else {$sel = '';}
				$parent .= '<option value="'.$pt->id.'" '.$sel.'>'.$pt->name.'</option>';
			}
		}

		$parent = '
			<select id="parent_id" name="parent_id" class="form-select select2" required>
				<option value="0">None</option>
				'.$parent.'
			</select>
		';

		return $parent;
	}

	public function get_module() {
		$mod_list = '';
		$role_id = 0;

		if($this->request->getMethod() == 'post') {
			$role_id = $this->request->getVar('role_id');

			if($role_id) {
				$log_id = $this->session->get('td_id');
				$log_role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
				$log_role = $this->Crud->read_field('id', $log_role_id, 'access_role', 'name');

				$modules = $this->Crud->read_single_order('parent', 0, 'access_module', 'priority', 'asc');
				
				// load modules
				$ct = 0;
				$mlevel1 = '';
				if(!empty($modules)) {
					foreach($modules as $mod) {
						$mod_id = $mod->id;	
						$mod_name = $mod->name;
						$mod_link = $mod->link;		

						if($this->Crud->mod_read($log_role_id, $mod->link) == 1 || strtolower($log_role) == 'developer') {
							// get level 2
							$mlevel2 = '';
							$modules2 = $this->Crud->read_single_order('parent', $mod->id, 'access_module', 'priority', 'asc');
							if(!empty($modules2)) {
								foreach($modules2 as $mod2) {
									if($this->Crud->mod_read($log_role_id, $mod2->link) == 1 || strtolower($log_role) == 'developer') {
										// get level 3
										$mlevel3 = '';
										$modules3 = $this->Crud->read_single_order('parent', $mod2->id, 'access_module', 'priority', 'asc');
										if(!empty($modules3)) {
											foreach($modules3 as $mod3) {
												if($this->Crud->mod_read($log_role_id, $mod3->link) == 1 || strtolower($log_role) == 'developer') {
													$mlevel3 .= $this->format_module($role_id, $mod3->id, $mod3->name, $mod3->link, '45', $ct);
													$ct += 1;
												}
											}
										}
										
										$mlevel2 .= $this->format_module($role_id, $mod2->id, $mod2->name, $mod2->link, '30', $ct);

										if($mlevel3) {
											$mlevel2 .= $mlevel3;
										} 
										
										$ct += 1;
									}
								}
							}
							
							$mlevel1 = $this->format_module($role_id, $mod_id, $mod_name, $mod_link, '15', $ct);
							
							if($mlevel2) {
								$mod_list .= $mlevel1.$mlevel2;
							} else {
								$mod_list .= $mlevel1;
							}

							$ct += 1;
						}
					}
				}
			}
		}
		
		echo '<input type="hidden" id="rol" value="'.$role_id.'" />'.$mod_list;
		die;
	}

	private function format_module($role_id, $mod_id, $name, $link, $level, $index) {
		// crud check status
		$c_chk = '';
		$r_chk = '';
		$u_chk = '';
		$d_chk = '';
		
		// load crud
		$gmod = $this->Crud->read_field('role_id', $role_id, 'access', 'crud');
		if(!empty($gmod)) {
			$gmod = json_decode($gmod);
			foreach($gmod as $gm) {
				$gm = explode('.', $gm);
				if($mod_id == $gm[0]) {
					if($gm[1] == 1){$c_chk = 'checked';} // create status
					if($gm[2] == 1){$r_chk = 'checked';} // read status
					if($gm[3] == 1){$u_chk = 'checked';} // update status
					if($gm[4] == 1){$d_chk = 'checked';} // delete status
					break;
				}
			}
		}
		
		// create
		$c = '	
			<span class="custom-checkbox">
				<input id="c'.$index.'" type="checkbox" class="minimal-red" oninput="saveModule('.$index.')" '.$c_chk.'><label></label>
			</span>
		';
		
		// read
		$r = '
			<span class="custom-checkbox">
				<input id="r'.$index.'" type="checkbox" class="minimal-red" oninput="saveModule('.$index.')" '.$r_chk.'><label></label>
			</span>
		';
		
		// update
		$u = '
			<span class="custom-checkbox">
				<input id="u'.$index.'" type="checkbox" class="minimal-red" oninput="saveModule('.$index.')" '.$u_chk.'><label></label>
			</span>
		';
		
		// delete
		$d = '
			<span class="custom-checkbox">
				<input id="d'.$index.'" type="checkbox" oninput="saveModule('.$index.')" '.$d_chk.'><label></label>
			</span>
		';
		
		$mod = '
			<tr>
				<td style="padding-left: '.$level.'px;">'.ucwords($name).'<br/><span class="small text-muted">/'.$link.'</span> <input type="hidden" id="mod'.$index.'" value="'.$mod_id.'" /></td>
				<td>'.$c.'</td>
				<td>'.$r.'</td>
				<td>'.$u.'</td>
				<td>'.$d.'</td>
			</tr>
		';

		return $mod;
	}

	public function save_module() {
		if($this->request->getMethod() == 'post') {
			$rol = $this->request->getVar('rol');
			$mod = $this->request->getVar('mod');
			$c = $this->request->getVar('c');
			$r = $this->request->getVar('r');
			$u = $this->request->getVar('u');
			$d = $this->request->getVar('d');
			
			$crud = array();
			if($this->Crud->check('role_id', $rol, 'access') > 0) {
				// get module crud in access
				$ct = 0;
				$gmod = $this->Crud->read_field('role_id', $rol, 'access', 'crud');
				$gmod = json_decode($gmod);
				foreach($gmod as $gm) {
					$gm = explode('.', $gm); // break crud
					if($mod == $gm[0]) {
						unset($gmod[$ct]); // first remove module
						break;
					}
					$ct += 1;
				}
				$crud[] = $mod.'.'.$c.'.'.$r.'.'.$u.'.'.$d; // recreate module crud
				$new_crud = array_merge($gmod, $crud); // add new to existing crud
				$upd['crud'] = json_encode($new_crud);
				$this->Crud->updates('role_id', $rol, 'access', $upd);
			} else {
				$crud[] = $mod.'.'.$c.'.'.$r.'.'.$u.'.'.$d;
				
				$reg['role_id'] = $rol;
				$reg['crud'] = json_encode($crud);
				$this->Crud->create('access', $reg);
			}
		}
	}
	
	public function update_app() {
	    if($this->request->getMethod() == 'post') {
	        $id = $this->request->getVar('id');
	        $value = $this->request->getVar('value');
	        
	        if(!empty($id)) {
	            $this->Crud->updates('id', $id, 'setting', array('value'=>$value));
	        }
	        
	        die;
	    }
	}
}
