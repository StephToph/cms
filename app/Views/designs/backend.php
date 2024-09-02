<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
    
    $username = $this->Crud->read_field('id', $log_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $log_id, 'user', 'surname');
    $log_name = $this->Crud->read_field('id', $log_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $log_id, 'user', 'surname');
    $email = $this->Crud->read_field('id', $log_id, 'user', 'email');
    $log_role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
	$log_role = strtolower($this->Crud->read_field('id', $log_role_id, 'access_role', 'name'));
    $log_user_img_id = 0;
    $log_user_img = $this->Crud->image($log_user_img_id, 'big');
    $balance = 0;
    $earnings = 0;
    $withdrawns = 0;

    
    header("Access-Control-Allow-Origin: *");  // Replace * with the specific origin(s) you want to allow
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
?>

<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../../">
    <meta charset="utf-8">
    <meta name="author" content="TiDREM">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="Add Money, Make Transfers, Pay Bills">
    <meta name="theme-color" content="blue">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?=site_url(); ?>assets/fav.png">
    <title><?=$title; ?></title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?=site_url(); ?>assets/css/dashlitee5ca.css?ver=3.2.3">
    <link id="skin-default" rel="stylesheet" href="<?=site_url(); ?>assets/css/skins/theme-egyptian.css?ver=3.2.3">
    
</head>


<body class="nk-body bg-lighter npc-general has-sidebar ">
    <div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-sidebar nk-sidebar-fixed is-dark" data-content="sidebarMenu">
                <div class="nk-sidebar-element nk-sidebar-head">
                    <div class="nk-menu-trigger"><a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none"
                        data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a><a href="#"
                        class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex"
                        data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                    </div>
                    <div class="nk-header-brand">
                        <a href="<?=site_url(); ?>" class="logo-link">
                            <img class="logo-light logo-img logo-img-lg" src="<?=site_url(); ?>assets/logo.png" srcset="<?=site_url(); ?>assets/logo.png 2x" style="max-width:70%" alt="logo">
                            <img class="logo-dark logo-img logo-img-lg" src="<?=site_url(); ?>assets/logo.png" srcset="<?=site_url(); ?>assets/logo.png 2x" style="max-width:70%" alt="logo-dark">
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
                                                                    <a href="'.site_url($mod2->link).'" class="nk-menu-link">'.$mod2->name.'</a>
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
                                                            <span class="nk-menu-text">'.$mod->name.'</span>
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
                                        <span class="nk-menu-text">Access Roles</span>
                                    </a>
                                    <ul class="nk-menu-sub">
                                        <li class="nk-menu-item <?php if($page_active=='app') {echo 'active';} ?>">
                                            <a href="<?php echo site_url('settings/app'); ?>" class="nk-menu-link">Website Settings</a>
                                        </li>
                                        <li class="nk-menu-item <?php if($page_active=='module') {echo 'active';} ?>">
                                            <a href="<?php echo site_url('settings/modules'); ?>" class="nk-menu-link">Modules</a>
                                        </li>
                                        <li class="nk-menu-item <?php if($page_active=='role') {echo 'active';} ?>">
                                            <a href="<?php echo site_url('settings/roles'); ?>" class="nk-menu-link">Roles</a>
                                        </li>
                                        <li class="nk-menu-item <?php if($page_active=='access') {echo 'active';} ?>">
                                            <a href="<?php echo site_url('settings/access'); ?>" class="nk-menu-link">Access CRUD</a>
                                        </li>
                                    </ul>
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
                                <a href="<?=site_url('dashboard');?>" class="logo-link nk-sidebar-logo">
                                    <img class="logo-light logo-img logo-img-lg" src="<?=site_url();?>assets/logo.png?v=0" srcset="<?=site_url();?>assets/logo.png?v=0 2x" width="" alt="logo">
                                    <img class="logo-dark logo-img logo-img-lg" src="<?=site_url();?>assets/logo.png?v=0" srcset="<?=site_url();?>assets/logo.png?v=0 2x" width="" alt="logo-dark">
                                </a>
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
                                                    <em class="icon ni ni-user-alt"></em>
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
                                                        <em class="icon ni ni-user-alt"></em>
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
                              
</body>


    <script src="<?=site_url(); ?>assets/js/bundle.js"></script>
    <script src="<?=site_url(); ?>assets/js/scriptse5ca.js"></script>
    
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
    <?php if(!empty($table_rec)){ ?>
        <!-- <script src="<?=site_url();?>assets/backend/vendors/datatables/jquery.dataTables.min.js"></script>
            <script src="<?=site_url();?>assets/backend/vendors/datatables/dataTables.bootstrap.min.js"></script>
            <script src="<?=site_url();?>assets/backend/js/pages/datatables.js"></script> -->
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
</html>