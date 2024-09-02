<?php

namespace App\Controllers;

class Homes extends BaseController {
    public function welcome($param1='', $param2='', $param3='') {

        if($param1 == 'email'){
            $email = $this->request->getPost('email');
            $name = $this->request->getPost('name');
            $question = $this->request->getPost('question');
            
            $email = $this->Crud->send_email2($email, $name, 'info@zend.ng', 'Enquiry', $question);
            if($email){
                echo $this->Crud->msg('success', 'Message Sent');
                echo '<script>location.reload(false);</script>';
            } else{
                echo $this->Crud->msg('danger', 'Error Sendng Message');
            }
            die;
        }
        
        $data['current_language'] = $this->session->get('current_language');
        $data['title'] = app_name;
        return view('home', $data);
    }
    
    
    
}
