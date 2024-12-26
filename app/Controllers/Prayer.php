<?php

namespace App\Controllers;

class Prayer extends BaseController {

    /////// ACTIVITIES
	public function index($param1='', $param2='', $param3='') {
		
        $mod = 'activity';
		
        $data['current_language'] = $this->session->get('current_language');
		$table = 'prayer';

        $form_link = site_url($mod);
		if($param1){$form_link .= '/'.$param1;}
		if($param2){$form_link .= '/'.$param2.'/';}
		if($param3){$form_link .= $param3;}
		
		
		// pass parameters to view
		$data['param1'] = $param1;
		$data['param2'] = $param2;
		$data['param3'] = $param3;
		$data['form_link'] = $form_link;
		
		
		
		if($param1 == 'manage') { // view for form data posting
			return view($mod.'_form', $data);
		} else { // view for main page
			
			$data['title'] = translate_phrase('Prayer Cloud').' - '.app_name;
			$data['page_active'] = $mod;

			return view('prayer/list', $data);
		}
	
	}

}
