<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
<div class="nk-content" >
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body mt-3">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Membership');?> / <strong class="text-primary small"><?=ucwords($fullname); ?></strong></h3>
                            <div class="nk-block-des text-soft">
                                <ul class="list-inline">
                                    <li><?=translate_phrase('Last Login');?>: <span class="text-base"><?=$last_log; ?></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="<?=site_url('accounts/membership'); ?>" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span><?=translate_phrase('Back');?></span></a>
                            <a href="<?=site_url('accounts/membership'); ?>" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                        </div>
                    </div>
                    <input type="hidden" id="u_id" value="<?=$id; ?>">
                </div><!-- .nk-block-head -->
                <div class="nk-block nk-block-lg">
                    <div class="card card-bordered">
                        <div class="card-aside-wrap">
                            <div class="card-content">
                                <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#personal-info"><em class="icon ni ni-user-circle"></em><span><?=translate_phrase('Personal');?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#church"><em class="icon ni ni-home-alt"></em><span><?=translate_phrase('Church');?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#service"><em class="icon ni ni-linux-server"></em><span><?=translate_phrase('Service');?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#cell"><em class="icon ni ni-cc-alt2"></em><span><?=translate_phrase('Cell Ministry');?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#notification"><em class="icon ni ni-bell"></em><span><?=translate_phrase('Notification');?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#wallet"><em class="icon ni ni-wallet"></em><span><?=translate_phrase('Finance');?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"  data-bs-toggle="tab" href="#activity"><em class="icon ni ni-activity"></em><span><?=translate_phrase('Activities');?></span></a>
                                    </li>
                                    <li class="nav-item nav-item-trigger d-xxl-none">
                                        <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-user-list-fill"></em></a>
                                    </li>
                                </ul><!-- .nav-tabs -->
                                <div class="card-inner">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personal-info">
                                            <div class="nk-block">
                                                <div class="nk-block-head">
                                                    <h5 class="title"><?=translate_phrase('Personal Information');?></h5>
                                                </div><!-- .nk-block-head -->
                                                <div class="profile-ud-list">
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Title');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_title); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Membership ID');?></span>
                                                            <span class="profile-ud-value"><?=($v_user_no); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Full Name');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($fullname); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Mobile Number');?></span>
                                                            <span class="profile-ud-value"><?=$v_phone; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Email Address');?></span>
                                                            <span class="profile-ud-value"><?=$v_email; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Kingchat Handle');?></span>
                                                            <span class="profile-ud-value"><?=$v_chat_handle; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Gender');?></span>
                                                            <span class="profile-ud-value"><?=$v_gender; ?> </span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Birth Date');?></span>
                                                            <span class="profile-ud-value"><?=$v_dob; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Marital Status');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_family_status); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Address');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_address); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Marriage Anniversary');?></span>
                                                            <span class="profile-ud-value"><?=$v_marriage_anniversary; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Family Position');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_family_position); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Department');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($this->Crud->read_field('id', $v_dept_id, 'dept', 'name')); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Department Role');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_dept_role); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Cell');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($this->Crud->read_field('id', $v_cell_id, 'cells', 'name')); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Cell Role');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($this->Crud->read_field('id', $v_cell_role, 'access_role', 'name')); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Job Type');?></span>
                                                            <span class="profile-ud-value"><?=ucwords(strtolower($v_job_type)); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Employer Name');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_employer_address); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Employer Address');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_employer_address); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Foundation School');?></span>
                                                            <span class="profile-ud-value"><?php 
                                                                if($v_foundation_school == 0){
                                                                    echo 'Prospective Student';
                                                                }
                                                                if($v_foundation_school == 1){
                                                                    echo 'Foundation Student';
                                                                }
                                                                if($v_foundation_school == 2){
                                                                    echo 'Graduate';
                                                                }
                                                             ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Foundation Week');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_foundation_weeks); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Baptism');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_baptism); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Joining Date');?></span>
                                                            <span class="profile-ud-value"><?=$reg_date; ?></span>
                                                        </div>
                                                    </div>
                                                </div><!-- .profile-ud-list -->
                                            </div><!-- .nk-block -->
                                            
                                        </div>
                                        <div class="tab-pane" id="church">
                                            <div class="nk-block">
                                                <div class="nk-block-head">
                                                    <h5 class="title"><?=translate_phrase('Church Information');?></h5>
                                                </div><!-- .nk-block-head -->
                                                <div class="profile-ud-list">
                                                    <?php 
                                                        $church_id = $v_church_id; 
                                                        $ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
                                                        $ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
                                                        $church = $this->Crud->read_field('id', $church_id, 'church', 'name');
                                                        $type = $this->Crud->read_field('id', $church_id, 'church', 'type');
                                                        $email = $this->Crud->read_field('id', $church_id, 'church', 'email');
                                                        $phone = $this->Crud->read_field('id', $church_id, 'church', 'phone');
                                                        $address = $this->Crud->read_field('id', $church_id, 'church', 'address');
                                                        $regional_id = $this->Crud->read_field('id', $church_id, 'church', 'regional_id');
                                                        $zonal_id = $this->Crud->read_field('id', $church_id, 'church', 'zonal_id');
                                                        $group_id = $this->Crud->read_field('id', $church_id, 'church', 'group_id');
                                                        $pastor_id = $this->Crud->read_field('name', 'Pastor-in-Charge', 'access_role', 'id');
                                                        $pastor_title = $this->Crud->read_field2('role_id', $pastor_id, 'church_id', $church_id, 'user', 'title');
                                                        $pastor_firstname = $this->Crud->read_field2('role_id', $pastor_id, 'church_id', $church_id, 'user', 'firstname');
                                                        $pastor_surname = $this->Crud->read_field2('role_id', $pastor_id, 'church_id', $church_id, 'user', 'surname');
                                                        
                                                    ?>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Ministry');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($ministry); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Church');?></span>
                                                            <span class="profile-ud-value"><?=($church); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Type');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($type); ?></span>
                                                        </div>
                                                    </div>
                                                    <?php if($regional_id > 0){
                                                        $region = $this->Crud->read_field('id', $regional_id, 'church', 'name'); ?>
                                                        <div class="profile-ud-item">
                                                            <div class="profile-ud wider">
                                                                <span class="profile-ud-label"><?=translate_phrase('Region');?></span>
                                                                <span class="profile-ud-value"><?=ucwords($region); ?></span>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if($zonal_id > 0){
                                                        $zone = $this->Crud->read_field('id', $zonal_id, 'church', 'name'); ?>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Zone');?></span>
                                                            <span class="profile-ud-value"><?=$zone; ?></span>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                    <?php if($group_id > 0){
                                                        $group = $this->Crud->read_field('id', $group_id, 'church', 'name'); ?>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Group');?></span>
                                                            <span class="profile-ud-value"><?=$group; ?></span>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Email');?></span>
                                                            <span class="profile-ud-value"><?=$email; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Phone');?></span>
                                                            <span class="profile-ud-value"><?=$phone; ?> </span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Address');?></span>
                                                            <span class="profile-ud-value"><?=$address; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Pastor in Charge');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($pastor_title.' '.$pastor_firstname.' '.$pastor_surname); ?></span>
                                                        </div>
                                                    </div>
                                                    
                                                </div><!-- .profile-ud-list -->
                                            </div><!-- .nk-block -->
                                            
                                        </div>
                                        <div class="tab-pane" id="cell">
                                            <div class="nk-block">
                                                <div class="nk-block-head">
                                                    <h5 class="title"><?=translate_phrase('Cell Information');?></h5>
                                                </div><!-- .nk-block-head -->
                                                <div class="profile-ud-list">
                                                    <?php 
                                                        $cell_id = $v_cell_id; 
                                                        $cell = $this->Crud->read_field('id', $cell_id, 'cells', 'name');
                                                        $location = $this->Crud->read_field('id', $cell_id, 'cells', 'location');
                                                        $phone = $this->Crud->read_field('id', $cell_id, 'cells', 'phone');
                                                        $phone = $this->Crud->read_field('id', $cell_id, 'cells', 'phone');
                                                        $data = json_decode($this->Crud->read_field('id', $cell_id, 'cells', 'time'), true);
                                                        $cell_role = 'Cell Member';
                                                        if(!empty($v_cell_role)){
                                                            $cell_role = $this->Crud->read_field('id', $v_cell_role, 'access_role', 'name');
                                                        }
                                                        
                                                    ?>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Cell');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($cell); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Role');?></span>
                                                            <span class="profile-ud-value"><?=($cell_role); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Location');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($location); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Phone');?></span>
                                                            <span class="profile-ud-value"><?=$phone; ?> </span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Meeting Time');?></span>
                                                            <span class="profile-ud-value"><?php 
                                                                if ($data !== null) {
                                                                    echo "<ul>";
                                                                    foreach ($data as $day => $time) {
                                                                        $timestamp = strtotime($time);
        
                                                                        // Check if strtotime was successful
                                                                        if ($timestamp !== false) {
                                                                            // Format the time as desired (e.g., 12-hour format with AM/PM)
                                                                            echo "<li>$day: " . date('h:i A', $timestamp) . "</li>";
                                                                        } else {
                                                                            echo "<li>$day: Invalid time format</li>";
                                                                        }
                                                                    }
                                                                    echo "</ul>";
                                                                }
                                                            ?></span>
                                                        </div>
                                                    </div>
                                                    
                                                </div><!-- .profile-ud-list -->
                                                <div class="rounded table-responsive my-4">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <th>Date</th>
                                                            <th>Service</th>
                                                            <th>Status</th>
                                                        </thead>
                                                        <tbody  id="cell_data"></tbody>
                                                        <tfoot id="cell_more"></tfoot>
                                                    </table>
                                                </div>
                                            </div><!-- .nk-block -->
                                            
                                        </div>
                                        <div class="tab-pane" id="service">
                                            <div class="nk-block-head">
                                                <h5 class="title"><?=$fullname;?>'s <?=translate_phrase('Service History');?></h5>
                                            </div><!-- .nk-block-head -->
                                            <div class="rounded table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <th>Date</th>
                                                        <th>Service</th>
                                                        <th>Status</th>
                                                    </thead>
                                                    <tbody  id="service_data"></tbody>
                                                    <tfoot id="service_more"></tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="wallet">
                                            <div class="nk-block">
                                                <div class="nk-block-head">
                                                    <h5 class="title"><?=translate_phrase('Finance History');?></h5>
                                                </div><!-- .nk-block-head -->
                                                <div class="nk-block mb-3">
                                                    <div class="row g-gs">
                                                        <div class="col-md-4">
                                                            <div class="card card-bordered  card-full">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-0">
                                                                        <div class="card-title">
                                                                            <h6 class="title"><?=translate_phrase('Total Offering'); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-amount">
                                                                        <span class="amount" id="offering"> 0 <span class="currency currency-usd"></span></span>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card card-bordered  card-full">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-0">
                                                                        <div class="card-title">
                                                                            <h6 class="title"><?=translate_phrase('Total Tithe'); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-amount">
                                                                        <span class="amount" id="tithe"> 0 <span class="currency currency-usd"></span></span>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card card-bordered  card-full">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-0">
                                                                        <div class="card-title">
                                                                            <h6 class="title"><?=translate_phrase('Total Partnership'); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-amount">
                                                                        <span class="amount" id="partnership"> 0.00 <span class="currency currency-usd"></span></span>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- .row -->
                                                </div> 
                                                <div class="nk-tb-list border border-light rounded overflow-hidden is-compact" id="wallet_data">
                                                </div>
                                                <div id="wallet_more"></div>    
                                            </div><!-- .nk-block -->
                                            
                                        </div>
                                        <div class="tab-pane" id="notification">
                                            <div class="nk-block-head">
                                                <h5 class="title"><?=$fullname;?>'s <?=translate_phrase('Notification');?></h5>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-tb-list border border-light rounded overflow-hidden is-compact" id="notification_data">
                                            </div>
                                            <div id="notification_more"></div>
                                        </div>
                                        <div class="tab-pane" id="activity">
                                            <div class="nk-block-head">
                                                <h5 class="title"><?=$fullname;?>'s <?=translate_phrase('Activity Log');?></h5>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-tb-list border border-light rounded overflow-hidden is-compact" id="activity_data">
                                            </div>
                                            <div id="activity_more"></div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .card-content -->
                            <div class="card-aside card-aside-right user-aside toggle-slide toggle-slide-right toggle-break-xxl" data-content="userAside" data-toggle-screen="xxl" data-toggle-overlay="true" data-toggle-body="true">
                                <div class="card-inner-group" data-simplebar>
                                    <div class="card-inner">
                                        <div class="user-card user-card-s2">
                                            <div class="user-avatar lg bg-primary">
                                                <?=$v_img; ?>
                                            </div>
                                            <div class="user-info">
                                                <div class="badge bg-outline-light rounded-pill ucap"><?=translate_phrase($role); ?></div>
                                                <h5><?=ucwords($fullname); ?></h5>
                                                <span class="sub-text"><?=$v_email; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .card-inner -->
                            </div><!-- .card-aside -->
                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- content @e -->
<script>var site_url = '<?php echo site_url(); ?>';</script>
<script src="<?php echo site_url(); ?>/assets/js/jquery.min.js"></script>
<script>
    $(function() {
        notification('', '');
        wallet('', '');
        activity('', '');
        service('', '');
        cell('', '');
    });

    

    function cell(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#cell_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#cell_more').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

        var u_id = $('#u_id').val();
        $.ajax({
            url: site_url + 'accounts/customer_details/cell/load' + methods,
            type: 'post',
            data: {u_id: u_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#cell_data').html(dt.item);
                } else {
                    $('#cell_data').append(dt.item);
                }
                if (dt.offset > 0) {
                    $('#cell_more').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="activity(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#cell_more').html('');
                }
            }
        });
    }

    function service(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#service_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#service_more').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

        var u_id = $('#u_id').val();
        $.ajax({
            url: site_url + 'accounts/customer_details/service/load' + methods,
            type: 'post',
            data: {u_id: u_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#service_data').html(dt.item);
                } else {
                    $('#service_data').append(dt.item);
                }
                if (dt.offset > 0) {
                    $('#service_more').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="activity(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#service_more').html('');
                }
            }
        });
    }

    function activity(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#activity_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#activity_more').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

        var u_id = $('#u_id').val();
        $.ajax({
            url: site_url + 'accounts/customer_details/activity/load' + methods,
            type: 'post',
            data: {u_id: u_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#activity_data').html(dt.item);
                } else {
                    $('#activity_data').append(dt.item);
                }
                if (dt.offset > 0) {
                    $('#activity_more').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="activity(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#activity_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    function notification(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#notification_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#notification_more').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

        var u_id = $('#u_id').val();
        $.ajax({
            url: site_url + 'accounts/customer_details/notification/load' + methods,
            type: 'post',
            data: {u_id: u_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#notification_data').html(dt.item);
                } else {
                    $('#notification_data').append(dt.item);
                }
                if (dt.offset > 0) {
                    $('#notification_more').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="notification(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#notification_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    function order(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#transaction_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#transaction_more').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

        var u_id = $('#u_id').val();
        $.ajax({
            url: site_url + 'accounts/customer_details/order/load' + methods,
            type: 'post',
            data: {u_id: u_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#transaction_data').html(dt.item);
                } else {
                    $('#transaction_data').append(dt.item);
                }
                if (dt.offset > 0) {
                    $('#transaction_more').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="order(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#transaction_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    function wallet(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#wallet_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#wallet_more').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }
        $('#offering').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#tithe').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#partnership').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        var u_id = $('#u_id').val();
        $.ajax({
            url: site_url + 'accounts/customer_details/wallet/load' + methods,
            type: 'post',
            data: {u_id: u_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#wallet_data').html(dt.item);
                } else {
                    $('#wallet_data').append(dt.item);
                }
                $('#tithe').html(dt.tithe);
                $('#offering').html(dt.offering);
                $('#partnership').html(dt.partnership);
                if (dt.offset > 0) {
                    $('#wallet_more').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="order(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#wallet_more').html('');
                }
            }
        });
    }
</script>   

<?=$this->endSection();?>