
<?php
use App\Models\Crud;
$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param2 == 'delete') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?=translate_phrase('Are you sure?');?></b></h3>
                <input type="hidden" name="d_type_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete');?>
                </button>
            </div>
        </div>
    <?php } ?>

    
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="type_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <?php if($role == 'developer' || $role == 'administrator' || $role == 'ministry administrator'){?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="name">*<?= translate_phrase('Church'); ?></label>
                        <select data-search="on" class=" js-select2" id="church_id" name="church_id" required>
                            <option value="">Select Church</option>
                            <?php
                            $type = $this->Crud->read_order('church', 'name', 'asc');
                            if (!empty($type)) {
                                foreach ($type as $t) {
                                    $sel='';
                                    if(!empty($e_church_id)){
                                        if($e_church_id == $t->id)$sel = 'selected';
                                    }
                                   
                                    echo '<option value="' . $t->id . '" '.$sel.'>' . ucwords($t->name.' - '.$t->type) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            <?php } else {?>
                <input type="hidden" name="church_id" value="<?=$this->Crud->read_field('id', $log_id, 'user', 'church_id'); ?>">
            <?php } ?>    
           
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?= translate_phrase('Service Type'); ?></label>
                    <select data-search="on" class=" js-select2" id="type" name="type" required>
                        <option value="0">Select</option>
                        <?php
                        $type = $this->Crud->read_order('service_type', 'name', 'asc');
                        if (!empty($type)) {
                            foreach ($type as $t) {
                                $sel='';
                                if(!empty($e_type_id)){
                                    if($e_type_id == $t->id)$sel = 'selected';
                                }
                               
                                echo '<option value="' . $t->id . '" '.$sel.'>' . ucwords($t->name) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

                         <!-- Service Type -->
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label>Service Type</label>
                    <select  data-search="on" class=" js-select2" name="service_type" id="service_type" required>
                        <option value="one-time" <?php if(!empty($e_type)){if($e_type == 'one-time'){echo 'selected';}} ?>>One Time</option>
                        <option value="recurring" <?php if(!empty($e_type)){if($e_type == 'recurring'){echo 'selected';}} ?>>Recurring</option>
                    </select>
                </div>
            </div>

            <!-- One-Time Date -->
            <div class="col-sm-6 mb-3 one-time-fields" style="display:none;">
                <div class="form-group">
                    <label>Service Date</label>
                    <input type="text" name="service_date" value="<?php if(!empty($e_service_date)){echo $e_service_date;} ?>" class="form-control date-picker">
                </div>
            </div>
             <!-- Time Pickers (Both) -->
             <div class="col-sm-3 mb-3 common-time-fields" style="display:none;">
                <label>Start Time</label>
                <input type="text" class="form-control time-picker" name="start_time" placeholder="Start Time"  value="<?php if(!empty($e_start_time)){echo $e_start_time;} ?>">
            </div>
            <div class="col-sm-3 mb-3 common-time-fields" style="display:none;">
                <label>End Time</label>
                <input type="text" class="form-control time-picker" name="end_time" placeholder="End Time"  value="<?php if(!empty($e_end_time)){echo $e_end_time;} ?>">
            </div>
            <!-- Recurring Pattern -->
            <div class="col-sm-6 mb-3 recurring-fields" style="display:none;">
                <div class="form-group">
                    <label>Recurring Pattern</label>
                    <select  data-search="on" class=" js-select2" name="recurring_pattern" id="recurring_pattern">
                        <option value="">Select Pattern</option>
                        <option value="weekly" <?php if(!empty($e_recurrence_pattern)){if($e_recurrence_pattern == 'weekly'){echo 'selected';}} ?>>Weekly</option>
                        <option value="monthly" <?php if(!empty($e_recurrence_pattern)){if($e_recurrence_pattern == 'monthly'){echo 'selected';}} ?>>Monthly</option>
                        <option value="yearly" <?php if(!empty($e_recurrence_pattern)){if($e_recurrence_pattern == 'yearly'){echo 'selected';}} ?>>Yearly</option>
                    </select>
                </div>
            </div>
                         <!-- Recurring Common Fields -->
            <div class="col-sm-6 mb-3 recurring-fields" style="display:none;">
                <label>Start Date</label>
                <input type="text" class="form-control date-picker" value="<?php if(!empty($e_start_date)){echo $e_start_date;} ?>" name="start_date">
            </div>
           
            <!-- Weekly Days -->
            <div class="col-sm-12 mb-3 recurring-details weekly-fields" style="display:none;">
                <?php
                    $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    $selected_days = [];

                    if (!empty($e_weekly_days)) {
                        $selected_days = explode(',', $e_weekly_days); // Convert to array
                    }
                ?>

                <label>Select Days of the Week</label>
                <div class="form-check form-check-inline">
                    <?php foreach ($days as $day): ?>
                        <label class="me-3">
                            <input type="checkbox" name="weekly_days[]" value="<?= $day ?>" 
                                <?= in_array($day, $selected_days) ? 'checked' : '' ?>> <?= $day ?>
                        </label>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- Monthly Pattern -->
            <div class="col-sm-12 mb-3 recurring-details monthly-fields" style="display:none;">
                <label>Monthly Recurrence Type</label>
                <select  data-search="on" class=" js-select2 mb-2" name="monthly_type" id="monthly_type">
                    <option value="">Select Recurrence Type</option>
                    <option value="dates" <?php if(!empty($e_monthly_type)){if($e_monthly_type == 'dates'){echo 'selected';}} ?>>On Specific Dates</option>
                    <option value="pattern" <?php if(!empty($e_monthly_type)){if($e_monthly_type == 'pattern'){echo 'selected';}} ?>>On Pattern (e.g. 1st Monday)</option>
                </select>

                <!-- Dates input -->
                <input type="text" class="form-control mb-2 monthly-dates" value="<?php if(!empty($e_monthly_dates)){echo $e_monthly_dates;} ?>" name="monthly_dates"
                    placeholder="Enter dates separated by commas (e.g. 5,15,30)" style="display:none;">

                <!-- Pattern-based -->
                <div class="monthly-patterns my-2 " style="display:none;">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <label>Select Weeks</label>
                            <select  data-search="on" class=" js-select2" name="monthly_weeks[]" multiple>
                                <option <?php if(!empty($e_monthly_weeks)){if($e_monthly_weeks == '1'){echo 'selected';}} ?> value="1">First</option>
                                <option <?php if(!empty($e_monthly_weeks)){if($e_monthly_weeks == '2'){echo 'selected';}} ?> value="2">Second</option>
                                <option <?php if(!empty($e_monthly_weeks)){if($e_monthly_weeks == '3'){echo 'selected';}} ?> value="3">Third</option>
                                <option <?php if(!empty($e_monthly_weeks)){if($e_monthly_weeks == '4'){echo 'selected';}} ?> value="4">Fourth</option>
                                <option <?php if(!empty($e_monthly_weeks)){if($e_monthly_weeks == '-1'){echo 'selected';}} ?> value="-1">Last</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <?php
                                $weekdays = ['Sun' => 'Sunday', 'Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday', 'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday'];
                                $selected_monthly_weekdays = [];

                                if (!empty($e_monthly_weekdays)) {
                                    $selected_monthly_weekdays = explode(',', $e_monthly_weekdays); // Convert to array
                                }
                            ?>

                            <label>Select Days</label>
                            <select data-search="on" class="js-select2 form-control" name="monthly_weekdays[]" multiple>
                                <?php foreach ($weekdays as $val => $label): ?>
                                    <option value="<?= $val ?>" <?= in_array($val, $selected_monthly_weekdays) ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Yearly -->
            <div class="col-sm-6 mb-3 recurring-details yearly-fields" style="display:none;">
                <label>Select Date in Year</label>
                <input type="text" class="form-control date-picker"  value="<?php if(!empty($e_yearly_date)){echo $e_yearly_date;} ?>" name="yearly_date">
            </div>

            <?php if($param2 == ''){ if($role != 'center manager'){?>
            <!-- Schedule Scope Selector -->
            <div class="col-sm-12 mb-3">
                <label><strong>Schedule Scope</strong></label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input  schedule-scope" type="radio" name="scope_type" value="own" checked>
                    <label class="form-check-label">Only My Church</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input  schedule-scope" type="radio" name="scope_type" value="all">
                    <label class="form-check-label">All Churches Under my Church</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input  schedule-scope" type="radio" name="scope_type" value="selected">
                    <label class="form-check-label">Select Churches</label>
                </div>
            </div>

            <!-- Selected Churches Multi-Select -->
            <div class="col-sm-12 mb-3" id="select_churches_wrapper" style="display: none;">
                <label>Select Churches</label>
                <?php 
                    $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                    $church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
                    $ty = 'regional_id';
                    if($church_type == 'zone'){
                        $ty = 'zonal_id';
                    }
                    if($church_type == 'group'){
                        $ty = 'group_id';
                    }
                    if($church_type == 'church'){
                        $ty = 'church_id';
                    }
                ?>
                <select class="form-control js-select2" name="selected_churches[]" multiple>
                    <?php foreach ($this->Crud->read_single_order($ty, $church_id, 'church', 'name', 'asc') as $ch): ?>
                        <option value="<?= $ch->id ?>"><?= ucwords($ch->name . ' - ' . $ch->type) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" name="my_church_id" value="<?= $this->Crud->read_field('id', $log_id, 'user', 'church_id') ?>">
            <?php } }?>
            <div class="col-sm-12 text-center mt-3">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
    <?php } ?>

    <?php if($param2 == 'view'){ ?>
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
                                        <a class="nav-link" data-bs-toggle="tab" href="#servicez"><em class="icon ni ni-linux-server"></em><span><?=translate_phrase('Service');?></span></a>
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
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('QR Code');?></span>
                                                            <span class="profile-ud-value"> <img src='<?= site_url($qrcode);?>' alt='QR Code' style='max-width:200px; margin-top:10px;' /></span>
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
                                        <div class="tab-pane" id="servicez">
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
                                                    <tbody  id="service_dataz"></tbody>
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
                                                <div class="border rounded table-responsive" >
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Source</th>
                                                                <th>Type</th>
                                                                <th>Giving</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="wallet_data"></tbody>
                                                        <tfoot id="wallet_more"></tfoot>
                                                    </table>
                                                </div>   
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
                            
                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
       

<?php } ?>
<?php echo form_close(); ?>
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
            $('#service_dataz').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
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
                    $('#service_dataz').html(dt.item);
                } else {
                    $('#service_dataz').append(dt.item);
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
<script>
    $(document).ready(function() {
        $('.schedule-scope').on('change', function() {
            const selectedScope = $(this).val();

            if (selectedScope === 'selected') {
                $('#select_churches_wrapper').slideDown(500);
            } else {
                $('#select_churches_wrapper').slideUp(500);
            }
        });

        // Optional: Trigger default state on load
        $('.schedule-scope:checked').trigger('change');
    
        function toggleServiceTypeFields() {
            const type = $('#service_type').val();

            if (type === 'one-time') {
                $('.one-time-fields').show(500);
                $('.recurring-fields, .recurring-details').hide(500);
            } else if (type === 'recurring') {
                $('.recurring-fields').show(500);
                $('.one-time-fields').hide(500);
                toggleRecurringPattern(); // also handle pattern details
            } else {
                $('.one-time-fields, .recurring-fields, .recurring-details').hide(500);
            }

            $('.common-time-fields').show(500);
        }

        function toggleRecurringPattern() {
            const pattern = $('#recurring_pattern').val();
            $('.recurring-details').hide();

            if (pattern === 'weekly') {
                $('.weekly-fields').show();
            } else if (pattern === 'monthly') {
                $('.monthly-fields').show();
                $('#monthly_type').trigger('change'); // show correct sub-option
            } else if (pattern === 'yearly') {
                $('.yearly-fields').show();
            }
        }

        // Monthly sub-options logic
        $('#monthly_type').on('change', function () {
            const selected = $(this).val();
            if (selected === 'dates') {
                $('.monthly-dates').show();
                $('.monthly-patterns').hide();
            } else if (selected === 'pattern') {
                $('.monthly-dates').hide();
                $('.monthly-patterns').show();
            }
        });

        // Bind change events
        $('#service_type').on('change', toggleServiceTypeFields);
        $('#recurring_pattern').on('change', toggleRecurringPattern);

        // Initialize on load
        toggleServiceTypeFields();

        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd', // Set the date format
            autoclose: true
        });
        $('.time-picker').timepicker({});
    });

</script>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script