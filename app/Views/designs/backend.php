<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
    
    $this->session = \Config\Services::session();

    $switch_id = $this->session->get('switch_church_id');
    $username = $this->Crud->read_field('id', $log_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $log_id, 'user', 'surname');
    $log_name = $this->Crud->read_field('id', $log_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $log_id, 'user', 'surname');
    $email = $this->Crud->read_field('id', $log_id, 'user', 'email');
    $log_user_img = $this->Crud->read_field('id', $log_id, 'user', 'img_id');
    if(empty($switch_id)){
        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
    } else{
        $ministry_id = $this->Crud->read_field('id', $switch_id, 'church',  'ministry_id');
    }
    
    $ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
    $ministry_logo = $this->Crud->read_field('id', $ministry_id, 'ministry', 'logo');
    $log_role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
    if(!empty($switch_id)){
        $church_type = $this->Crud->read_field('id', $switch_id, 'church', 'type');
        if($church_type == 'region'){
            $log_role_id = $this->Crud->read_field('name', 'Regional Manager', 'access_role', 'id');
        }
        if($church_type == 'zone'){
            $log_role_id = $this->Crud->read_field('name', 'Zonal Manager', 'access_role', 'id');
        }
        if($church_type == 'group'){
            $log_role_id = $this->Crud->read_field('name', 'Group Manager', 'access_role', 'id');
        }
        if($church_type == 'church'){
            $log_role_id = $this->Crud->read_field('name', 'Church Leader', 'access_role', 'id');
        }
        $church_id = $switch_id;
    }
    $log_role = strtolower($this->Crud->read_field('id', $log_role_id, 'access_role', 'name'));
    if (empty($log_user_img) && !file_exists($log_user_img)) {
        $log_user_img = 'assets/images/avatar.png';
    }
    $logo = 'assets/new_logo1.png';
    $min_title = $title;
    if($ministry_id > 0){
        $logo = $ministry_logo;
        $min_title = str_replace('C M S', $ministry, $title);
        // define('app_name', $ministry);
    }

    $currence = 'ESP ';
    $default_cur = $this->Crud->read_field('id', $church_id, 'church', 'default_currency');
    $country_id = $this->Crud->read_field('id', $church_id, 'church', 'country_id');
    if($country_id > 0 && $default_cur > 0){
        $currence = $this->Crud->read_field('id', $country_id, 'country', 'currency_symbol');
    }
    $this->session->set('currency', $currence);

    if($this->Crud->read_field('id', $id, 'user', 'church_id') > 0){
        $timezone = $this->Crud->getUserTimezone($id); // e.g. "+01:00" or "Africa/Lagos"
        session()->set('user_timezone', $timezone);

        // Optional: apply it immediately
        date_default_timezone_set($timezone);
    }

    
    header("Access-Control-Allow-Origin: *");  // Replace * with the specific origin(s) you want to allow
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
?>

<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../../">
    <meta charset="utf-8">
    <meta name="author" content="Angel Church Management System">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="Church Performnce Tracking">
    <meta name="theme-color" content="blue">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?=site_url($logo); ?>">
    <title><?=$min_title; ?></title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?=site_url(); ?>assets/css/dashlitee5ca.css?ver=3.2.3">
    <link id="skin-default" rel="stylesheet" href="<?=site_url(); ?>assets/css/skins/theme-egyptian.css?ver=3.2.3">
    
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/editors/summernote.css">
    
</head>


<body class="nk-body bg-lighter npc-general has-sidebar ">
    <style>
        td, th {
            white-space: nowrap;           /* Prevent text from wrapping to the next line */
            overflow: hidden;              /* Hide any overflow content */
            text-overflow: ellipsis;       /* Display ellipsis (...) for overflowed content */
        }
       /* Styles for chat icon */
        #chat-icon {
            width: 50px;
            height: 50px;
            bottom: 20px;
            right: 20px;
            cursor: pointer;
        }

        /* Chat window custom styles */
        #chat-window {
            display: none;
            bottom: 80px;
            right: 20px;
            width: 300px;
            max-height: 400px;
        }

        /* Message bubbles */
        .bot-message {
            background-color: #f1f1f1;
            padding: 8px;
            border-radius: 5px;
            margin: 5px 0;
        }

        .user-message {
            background-color: #007bff;
            color: white;
            padding: 8px;
            border-radius: 5px;
            margin: 5px 0;
            text-align: right;
        }


    </style>
    <div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-sidebar nk-sidebar-fixed is-compact is-dark" data-content="sidebarMenu">
                <div class="nk-sidebar-element nk-sidebar-head">
                    <div class="nk-menu-trigger"><a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none"
                        data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a><a href="#"
                        class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex"
                        data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                    </div>
                    <div class="nk-header-brand">
                       
                        <a href="<?=site_url(); ?>" class="logo-link">
                            <img class="logo-light logo-img logo-img-lg" style="max-height:50px" src="<?=site_url($logo); ?>" srcset="<?=site_url($logo); ?>" alt="logo">
                            <img class="logo-dark logo-img logo-img-lg" style="max-height:50px" src="<?=site_url($logo); ?>" srcset="<?=site_url($logo); ?>" alt="logo-dark">
                        </a>
                    </div>
                </div>
                <div class="nk-sidebar-element nk-sidebar-body">
                    <div class="nk-sidebar-content">
                        <div class="nk-sidebar-menu" data-simplebar>
                            <ul class="nk-menu">
                                <!-- Dynamic Menu Items --> 
                                <?php
                                    $menu = '';
                                    $modules = $this->Crud->read_single_order('parent', 0, 'access_module', 'priority', 'asc');
                                    if(!empty($modules)) {
                                        foreach($modules as $mod) {
                                            // get level 2
                                            $level2 = '';
                                            if($this->Crud->mod_read($log_role_id, $mod->link) == 1) {
                                                $mod_level2 = $this->Crud->read_single_order('parent', $mod->id, 'access_module', 'priority', 'asc');
                                                if(!empty($mod_level2)) {
                                                    $open = false;
                                                    foreach($mod_level2 as $mod2) {
                                                        if($this->Crud->mod_read($log_role_id, $mod2->link) == 1) {
                                                            // add parent to first
                                                            if(empty($level2)) {
                                                                // $level2 = '
                                                                //     <li>
                                                                //         <a href="'.site_url($mod->link).'">'.$mod->name.'</a>
                                                                //     </li>
                                                                // ';
                                                            }
                                                            if($page_active == $mod2->link){$open = true; $a_active = 'active';} else {$a_active = '';}
                                                            
                                                            // add the rest
                                                            $level2 .= '
                                                                <li class="nk-menu-item '.$a_active.'">
                                                                    <a href="'.site_url($mod2->link).'" class="nk-menu-link">'.translate_phrase($mod2->name).'</a>
                                                                </li>
                                                            '; 
                                                        }
                                                    }
                                                    
                                                    $level2 = '
                                                        <ul class="nk-menu-sub">
                                                            '.$level2.'
                                                        </ul>
                                                    ';
                                                }

                                                if($page_active == $mod->link){$a_active = 'active';} else {$a_active = '';}
                                                if($level2){
                                                    $topmenu = 'has-sub';
                                                    $submenu = 'nk-menu-toggle';
                                                    $dlink = 'javascript:;';
                                                    $menu_arrow = '<span class="arrow"><i class="arrow-icon"></i></span>';
                                                } else {
                                                    $topmenu = '';
                                                    $submenu = ''; 
                                                    $dlink = site_url($mod->link);
                                                    $menu_arrow = '';
                                                }

                                                $menu .= '
                                                    <li class="nk-menu-item '.$topmenu .' '.$a_active.'">
                                                        <a class="nk-menu-link '.$submenu.'" href="'.$dlink.'">
                                                            <span class="nk-menu-icon">
                                                                <em class="'.$mod->icon.'"></em>
                                                            </span>
                                                            <span class="nk-menu-text">'.translate_phrase($mod->name).'</span>
                                                            '.$menu_arrow.'
                                                        </a>
                                                        '.$level2.'
                                                    </li>
                                                ';
                                            }
                                        }
                                    }

                                    echo $menu;
                                ?>
                                
                                <!-- Modules and Roles -->
                                <?php if($log_role == 'developer') { ?>
                                <li class="nk-menu-item has-sub">
                                    <a class="nk-menu-link nk-menu-toggle" href="javascript:;">
                                        <span class="nk-menu-icon"><em class="icon ni ni-setting-alt-fill"></em></span>
                                        <span class="nk-menu-text"><?=translate_phrase('Access Roles'); ?></span>
                                    </a>
                                    <ul class="nk-menu-sub">
                                        
                                        <li class="nk-menu-item <?php if($page_active=='module') {echo 'active';} ?>">
                                            <a href="<?php echo site_url('settings/modules'); ?>" class="nk-menu-link"><?=translate_phrase('Modules'); ?></a>
                                        </li>
                                        <li class="nk-menu-item <?php if($page_active=='role') {echo 'active';} ?>">
                                            <a href="<?php echo site_url('settings/roles'); ?>" class="nk-menu-link"><?=translate_phrase('Roles'); ?></a>
                                        </li>
                                        <li class="nk-menu-item <?php if($page_active=='access') {echo 'active';} ?>">
                                            <a href="<?php echo site_url('settings/access'); ?>" class="nk-menu-link"><?=translate_phrase('Access CRUD'); ?></a>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if(!empty($switch_id)){?>
                                    <li class="nk-menu-item">
                                        <a class="nk-menu-link" href="<?=site_url('church/back_church'); ?>">
                                            <span class="nk-menu-icon"><em class="icon ni ni-signout"></em></span>
                                            <span class="nk-menu-text"><?=translate_phrase('Back to My Account');?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-wrap ">
                <div class="nk-header nk-header-fixed bg-white">
                    <div class="container-fluid">
                        <div class="nk-header-wrap">
                            <div class="nk-menu-trigger d-xl-none ms-n1">
                                <a href="javascript:;" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                            </div>
                            <div class="nk-header-brand d-xl-none">
                                <a href="<?=site_url(); ?>" class="logo-link">
                                    <img class="logo-light logo-img logo-img-lg" style="max-height:50px" src="<?=site_url($logo); ?>" srcset="<?=site_url($logo); ?>" alt="logo">
                                    <img class="logo-dark logo-img logo-img-lg" style="max-height:50px" src="<?=site_url($logo); ?>" srcset="<?=site_url($logo); ?>" alt="logo-dark">
                                </a>
                            </div>
                            <div class="nk-header-news d-none d-xl-block">
                                <div class="nk-news-list"><a class="nk-news-item" href="javascript:;">
                                        <div class="nk-news-icon"><em class="icon ni ni-card-view"></em></div>
                                        <div class="nk-news-text">
                                            <span class="small"><?php echo date('d F, Y h:i A ');?></span>
                                        </div>
                                    </a></div>
                            </div>
                            <div class="nk-header-tools">
                                <ul class="nk-quick-nav">

                                    <li class="dropdown language-dropdown d-sm-block me-n1">
                                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                                            <div class="quick-icon border border-light">
                                                <?php
                                                    $flags = $current_language;
                                                    // echo $flags;
                                                    if($current_language == 'Hausa' || $current_language == 'Igbo' || $current_language == 'Yoruba')$flags = 'Nigerian';

                                                ?>
                                                <img class="icon" src="<?=site_url(); ?>assets/images/flags/<?=strtolower($flags); ?>.png" alt="">
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-s1">
                                            <ul class="language-list">
                                            <?php
                                                $lang = $this->Crud->read_single_order('status', 1,'language_code', 'name', 'asc');
                                                if(!empty($lang)){
                                                    foreach($lang as $l){
                                                        $l_name = $l->name;
                                                        if($l->name == 'Hausa' || $l->name == 'Igbo' || $l->name == 'Yoruba')$l_name = 'Nigerian';
                                                
                                            ?>
                                                <li>
                                                    <a href="javascript:;" onclick="lang_session(<?=$l->id; ?>)" class="language-item">
                                                        <img src="<?=site_url(); ?>assets/images/flags/<?=strtolower($l_name); ?>.png" alt="" class="language-flag">
                                                        <span class="language-name"><?=$l->name; ?></span>
                                                    </a>
                                                </li>
                                                <?php

                                                    }
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                    </li><!-- .dropdown -->
                                    <li class="dropdown user-dropdown">
                                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                            <div class="user-toggle">
                                                <div class="user-avatar sm">
                                                    <img src="<?=site_url($log_user_img); ?>">
                                                </div>
                                                <div class="user-info d-none d-md-block">
                                                    <div class="user-status user-status-verified">
                                                        <?=translate_phrase(ucwords($log_role).' Account'); ?></div>
                                                    <div class="user-name dropdown-indicator"><?=ucwords($username); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end dropdown-menu-s1 ">
                                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                                <div class="user-card">
                                                    <div class="user-avatar">
                                                        <img src="<?=site_url($log_user_img); ?>">
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="lead-text"><?=ucwords($username); ?></span>
                                                        <span class="sub-text"><?=ucwords($email); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="dropdown-inner">
                                                <ul class="link-list">
                                                    <li><a href="<?=site_url('profile'); ?>"><em class="icon ni ni-user-alt"></em><span><?=translate_phrase('View Profile');?></span></a></li>
                                                    <li><a href="<?=site_url('activity'); ?>"><em class="icon ni ni-activity-alt"></em><span><?=translate_phrase('My Activity') ;?></span></a></li>
                                                </ul>
                                            </div>
                                            <div class="dropdown-inner">
                                                <ul class="link-list">
                                                    <li><a href="<?=site_url('auth/logout'); ?>"><em class="icon ni ni-signout"></em><span><?=translate_phrase('Sign out'); ?></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown notification-dropdown me-n1">
                                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                                            <?php 
                                                $sta = '';
                                                if($this->Crud->check2('to_id', $log_id, 'new', '1', 'notify') > 0){
                                                    $sta = 'icon-status-info';
                                                }
                                            
                                            ?>
                                            <div class="icon-status <?=$sta; ?>"><em class="icon ni ni-bell"></em></div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end dropdown-menu-s1">
                                            <div class="dropdown-head">
                                                <span class="sub-title nk-dropdown-title"><?=translate_phrase('Notifications'); ?></span>
                                            </div>
                                            <div class="dropdown-body">
                                                <div class="nk-notification">
                                                    <?php 
                                                        $notify = $this->Crud->read2('to_id', $log_id, 'new', 1, 'notify');
                                                        if(!empty($notify)){
                                                           
                                                            $a = 0;
                                                            foreach($notify as $n){
                                                                if($a>5) continue;
                                                                $pos = 'left';
                                                                $code = 'success';

                                                                if($n->item == 'withdraw' || $n->item == 'transact'){
                                                                    $pos = 'right';
                                                                    $code = 'danger';
                                                                }
                                                           
                                                    ?><a href="javascript:;" onclick="mark_read(<?=$n->id; ?>)">
                                                        <div class="nk-notification-item dropdown-inner">

                                                            <div class="nk-notification-icon">
                                                                <em
                                                                    class="icon icon-circle bg-<?=$code; ?>-dim ni ni-curve-down-<?=$pos; ?>"></em>
                                                            </div>
                                                            <div class="nk-notification-content">
                                                                <div class="nk-notification-text">
                                                                    <?=translate_phrase(ucwords($n->content)); ?></div>
                                                                <div class="nk-notification-time">
                                                                    <?=$this->Crud->timespan(strtotime($n->reg_date));; ?>
                                                                </div>
                                                            </div>

                                                        </div><!-- .dropdown-inner -->
                                                    </a>
                                                    <?php 
                                                        $a++;
                                                        }

                                                    } else {
                                                        echo '<div class="text-center">'.translate_phrase('No Notification').'</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div><!-- .nk-dropdown-body -->
                                            <div class="dropdown-foot center">
                                                <a href="<?=site_url('notification/list'); ?>"><?=translate_phrase('View All'); ?></a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
               <div class="col-12 my-2">.</div>
                
                <?=$this->renderSection('content');?>
        
                <!-- content @e -->
                <div class="nk-footer nk-footer-fluid bg-lighter">
                    <div class="container-xl wide-lg">
                        <div class="nk-footer-wrap">
                            <div class="nk-footer-copyright">&copy;<?=date('Y'); ?> <?=app_name;?>. <?=translate_phrase('All Rights Reserved.'); ?>
                            </div>
                            <div class="nk-footer-links">
                                <ul class="nav nav-sm">
                                    <li class="nav-item dropup">
                                        <a class="dropdown-toggle dropdown-indicator has-indicator nav-link" data-bs-toggle="dropdown" data-offset="0,10"><small><?=$current_language; ?></small></a>
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                            <ul class="language-list">
                                                <?php
                                                    $lang = $this->Crud->read_single_order('status', 1,'language_code', 'name', 'asc');
                                                    if(!empty($lang)){
                                                        foreach($lang as $l){
                                                            $l_name = $l->name;
                                                            if($l->name == 'Hausa' || $l->name == 'Igbo' || $l->name == 'Yoruba')$l_name = 'Nigerian';
                                                    
                                                ?>
                                                <li>
                                                    <a href="javascript:;" onclick="lang_session(<?=$l->id; ?>)" class="language-item">
                                                        <img src="<?=site_url(); ?>assets/images/flags/<?=strtolower($l_name); ?>.png" alt="" class="language-flag">
                                                        <span class="language-name"><?=$l->name; ?></span>
                                                    </a>
                                                </li>

                                                <?php
                                                    }
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="bb_ajax_msgs"></div>
                    <!-- Chat icon at the bottom-right corner -->
                <div id="chat-icon" class="position-fixed text-white d-flex justify-content-center align-items-center rounded-circle shadow"  onclick="toggleChat()"  style="width: 60px; height: 60px; bottom: 20px; right: 20px; cursor: pointer;   background-image: url('<?=site_url('assets/angel-logo.png'); ?>');  background-size: 50%;background-repeat: no-repeat; background-position: center; background-color: #eee; border: 2px solid blue;">
                </div>
                <!-- Chat window -->
                <div id="chat-window" class="position-fixed shadow-lg bg-white rounded" 
                    style="display: none; bottom: 80px; right: 20px; width: 300px;">
                    <div class="d-flex justify-content-between align-items-center p-2 bg-primary text-white rounded-top">
                        <h5 class="mb-0">Ask Angel</h5>
                        <button type="button" class="btn btn-sm btn-light" onclick="toggleChat()">✖</button>
                    </div>
                    <div id="chat-body" class="p-3" style="max-height: 300px; overflow-y: auto;">
                        <div id="chat-messages">
                            <p class="bg-light p-2 rounded bot-message">Welcome to Angel CMS.</p>
                        </div>
                    </div>
                    <div class="input-group p-2 border-top">
                        <input type="text" id="user-input" class="form-control" placeholder="Type your message here...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" onclick="sendMessage()">Send</button>
                        </div>
                    </div>
                </div>


                <!-- wrap @e -->
            </div>
        <!-- wrap @e -->
    </div>
    <div class="modal modal-center fade" tabindex="-1" id="modal" role="dialog" data-keyboard="false"
        data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="javascript:;" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                <div class="modal-header">
                    <h6 class="modal-title"></h6>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <?php 
        $is_admin = $this->Crud->read_field('id', $log_id, 'user', 'is_admin');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
        $state_id = $this->Crud->read_field('id', $church_id, 'church', 'state_id');
        $country_id = $this->Crud->read_field('id', $church_id, 'church', 'country_id');?>


    <div class="modal fade" id="pinModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="pinModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pinModalLabel">Update Church Profile</h5>
                </div>
                <div class="modal-body">
                    <p>You need to select the state your church is located to proceed.</p>
                    <div id="transaction_form">
                        <?php 
                        $is_admin = $this->Crud->read_field('id', $log_id, 'user', 'is_admin');
                        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                        $country_id = $this->Crud->read_field('id', $church_id, 'church', 'country_id');
                        $state_id = $this->Crud->read_field('id', $church_id, 'church', 'state_id');
                        ?>

                        <div class="form-group mb-5">
                            <?php if (empty($country_id) || $country_id == 0): ?>
                                <!-- Show Country Selector -->
                                <label class="mb-2" for="country_id">Country</label>
                                <select class="js-select2" data-search="on" name="country_id" id="country_id" required>
                                    <option value="">Select Country</option>
                                    <?php 
                                    $countries = $this->Crud->read_order('country', 'name', 'asc');
                                    foreach ($countries as $c) {
                                        echo '<option value="' . $c->id . '">' . ucwords($c->name) . '</option>';
                                    }
                                    ?>
                                </select>

                                <!-- State will be loaded dynamically -->
                                <label class="mb-2 mt-4" for="state_id">State</label>
                                <select class="js-select2" data-search="on" name="state_id" id="state_id" required>
                                    <option value="">Select State</option>
                                </select>
                            <?php else: ?>
                                <input type="hidden" name="country_id" id="country_id" value="<?=$country_id; ?>">
                                <!-- Show State only based on fixed Country -->
                                <label class="mb-2" for="state_id">State</label>
                                <select class="js-select2" data-search="on" name="state_id" id="state_id" required>
                                    <option value="">Select State</option>
                                    <?php 
                                    $states = $this->Crud->read_single_order('country_id', $country_id, 'state', 'name', 'asc');
                                    foreach ($states as $s) {
                                        echo '<option value="' . $s->id . '">' . ucwords($s->name) . '</option>';
                                    }
                                    ?>
                                </select>
                            <?php endif; ?>
                        </div>
                        <div id="bb_ajax_msg2" class="mt-2"></div>
                        <button id="state_btn" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    <script>
        function validateState() {
            const state = document.querySelector('[name="state"]').value;
            if (!state) {
                alert("Please select a state.");
                return false;
            }
            return true;
        }
        </script>

  
    <script src="<?=site_url(); ?>assets/js/bundle.js"></script>
    <script src="<?=site_url(); ?>assets/js/scriptse5ca.js?v=<?=time(); ?>"></script>
    
    <script>
      
         var site_url = '<?=site_url(); ?>';
        function lang_session(lang_id){
            if(lang_id !== ''){
                $.ajax({
                    url: site_url + 'auth/language/' + lang_id,
                    success: function (data) {
                        $('#bb_ajax_msgs').html(data);                   
                    }
                });
            }
        }
                // Toggle chat visibility
        // Toggle chat visibility
        function toggleChat() {
            const chatWindow = document.getElementById('chat-window');
            chatWindow.style.display = chatWindow.style.display === 'none' || chatWindow.style.display === '' ? 'block' : 'none';
        }

        // Send a message and respond intelligently to basic conversations
        function sendMessage() {
            const input = document.getElementById('user-input');
            const message = input.value.trim().toLowerCase(); // Convert input to lowercase for easier matching

            if (message !== '') {
                const chatMessages = document.getElementById('chat-messages');

                // Display the user's message
                const userMessage = document.createElement('p');
                userMessage.className = 'user-message bg-primary text-white p-2 rounded';
                userMessage.textContent = input.value; // Display original case-sensitive input
                chatMessages.appendChild(userMessage);

                // Scroll to the bottom of the chat
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Clear input
                input.value = '';

                // Define response rules for basic conversations
                let botResponse = 'Coming soon'; // Default response

                // Basic conversation patterns
                const greetings = ['hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening', 'Hi Angel', 'hi angel', 'Hello Angel', 'hello angel', 'hey there'];
                const howAreYou = ['how are you', 'how are you doing', 'how’s it going'];
                const timeRelated = ['what time is it', 'current time', 'tell me the time'];

                // Respond to greetings
                if (greetings.includes(message)) {
                    botResponse = 'Greetings, How can i help you today?';
                }
                // Respond to "how are you" questions
                else if (howAreYou.some(phrase => message.includes(phrase))) {
                    botResponse = 'I’m doing great, thank you! How can I assist you today?';
                }
                // Respond to time-related queries
                else if (timeRelated.some(phrase => message.includes(phrase))) {
                    const currentTime = new Date().toLocaleTimeString();
                    botResponse = `The current time is ${currentTime}.`;
                }
                // Respond to general "thank you"
                else if (message.includes('thank you') || message.includes('thanks')) {
                    botResponse = 'You’re welcome! Let me know if you need anything else.';
                }
                // Respond to goodbye messages
                else if (message.includes('bye') || message.includes('goodbye')) {
                    botResponse = 'Goodbye! Have a great day!';
                }
                // Catch-all response
                else {
                    botResponse = 'Coming soon! I’m still learning, so stay tuned for more features!';
                }

                // Respond after a short delay
                setTimeout(() => {
                    const botMessage = document.createElement('p');
                    botMessage.className = 'bot-message bg-light p-2 rounded';
                    botMessage.textContent = botResponse;
                    chatMessages.appendChild(botMessage);

                    // Scroll to the bottom of the chat
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 1000);
            }
        }

    </script>
    
       
    <script>
        $(document).ready(function () {
            $('#country_id').on('change', function () {
                var countryId = $(this).val();
                var stateDropdown = $('#state_id');

                stateDropdown.html('<option value="">Loading states...</option>');

                $.ajax({
                    url: '<?= site_url("church/get_states_by_country/") ?>' + countryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        stateDropdown.empty();
                        stateDropdown.append('<option value="">Select State</option>');
                        $.each(response, function (index, state) {
                            stateDropdown.append('<option value="' + state.id + '">' + state.name + '</option>');
                        });
                    },
                    error: function () {
                        stateDropdown.html('<option value="">Error loading states</option>');
                    }
                });
            });
        });
        $(document).ready(function () {
            <?php 
            $hasTransactionPin = ($is_admin > 0 && $state_id == 0) ? 'true' : 'false'; 
            ?>
            var hasTransactionPin = <?= $hasTransactionPin ?>;
            console.log(hasTransactionPin);
            
            
            if (hasTransactionPin) {
                // Show the modal and prevent it from being closed
                var pinModal = new bootstrap.Modal(document.getElementById('pinModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                pinModal.show(500);
            }

            $('#state_btn').on('click', function () {
                // Display a loading spinner
                $('#bb_ajax_msg2').html(
                    '<div class="col-sm-12 text-center">' +
                    '<div class="spinner-border" role="status">' +
                    '<span class="visually-hidden">Loading...</span>' +
                    '</div><br>Processing Please Wait..</div>'
                );

                // Get the PIN value
                var state_id = $('#state_id').val();
                var country_id = $('#country_id').val();

                // Validate the PIN
                if (state_id === '') {
                    $('#bb_ajax_msg2').html('<div class="text-danger">Church State is required.</div>');
                    return;
                }

                // Submit the form via AJAX
                $.ajax({
                    url: '<?= site_url('church/update_state') ?>',
                    type: 'POST',
                    data: { state_id: state_id, country_id: country_id },
                    success: function (response) {
                        $('#bb_ajax_msg2').html(response);

                        // Clear the input field
                        $('#state_id').val('');
                    },
                    error: function () {
                        $('#bb_ajax_msg2').html('<div class="text-danger">An error occurred. Please try again.</div>');
                    }
                });
            });

        });
    </script>

    <?=$this->renderSection('scripts');?>
    <script>
       
    function mark_read(id) {
        $.ajax({
            url: site_url + 'notification/mark_read/' + id,
            type: 'post',
            success: function(data) {
                window.location.replace("<?=site_url('notification/list'); ?>");

            }
        });
    }
    
    </script>
    <?php 
            $notify = $this->Crud->read2('to_id', $log_id, 'new', 1, 'notify');
            if(!empty($notify)){?>
    <script>
    $(function() {
        plays();
    });
    </script>
    <?php }
        
        ?>
    <script>
    function plays() {
        var src = '<?=site_url(); ?>' + 'assets/audio/2.wav';
        var audio = new Audio(src);
        audio.play();
    }
    
    </script>
    
    <?php if($page_active == 'ministry/calendar'){?>
        <script src="<?php echo base_url(); ?>/assets/js/libs/fullcalendar.js"></script>
        <script src="<?php echo base_url(); ?>/assets/js/apps/calendar.js?v=<?=time();?>"></script>
    <?php } ?>
    <?php if($page_active == 'ministry/prayer'){?>
        <script src="<?php echo base_url(); ?>/assets/js/libs/fullcalendar.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js"></script>
        <script src="<?php echo base_url(); ?>/assets/js/apps/calendar.js?v=<?=time();?>"></script>
    <?php } ?>
    <?php if($page_active == 'church/activity'){?>
        <script src="<?php echo base_url(); ?>/assets/js/libs/fullcalendar.js"></script>
        <script src="<?php echo base_url(); ?>/assets/js/apps/activity_calendar.js?v=<?=time();?>"></script>
    <?php } ?>
    <script src="<?php echo site_url(); ?>assets/js/libs/editors/summernote.js"></script>
    <script src="<?php echo site_url(); ?>assets/js/editors.js"></script>
    <?php if(!empty($table_rec)){ ?>

        <script type="text/javascript">
        $(document).ready(function() {
            //datatables
            var table = $('#dtable').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [<?php if(!empty($order_sort)){echo '['.$order_sort.']';} ?>], //Initial order.
                "language": {
                    "processing": "<i class='icon ni ni-loader' aria-hidden='true'></i> <?=translate_phrase('Processing... please wait'); ?>"
                },
                // "pagingType": "full",

                // Load data for the table's content from an Ajax source
                "ajax": {
                    url: "<?php echo site_url($table_rec); ?>",
                    type: "POST",
                    complete: function() {
                        $.getScript('<?php echo site_url(); ?>assets/js/jsmodal.js');
                    }
                },

                //Set column definition initialisation properties.
                "columnDefs": [{
                    "targets": [
                    <?php if(!empty($no_sort)){echo $no_sort;} ?>], //columns not sortable
                    "orderable": false, //set not orderable
                }, ],

            });

        });
        </script>
    <?php } ?>
    <div class="js-preloader">    <div class="loading-animation tri-ring"></div></div>
</html>