<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner mt-5">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm  mt-3">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?=translate_phrase('New Membership'); ?></h3>
                        </div>
                        <div class="nk-block-head-content">
                            <a class="btn btn-info" href="<?=site_url('accounts/membership'); ?>"><em class="icon ni ni-arrow-long-left"></em>Back to Membership</a>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                
                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            
                            <?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
                            <div class="row gy-4">
                                <input type="hidden" name="membership_id" value="<?php if(!empty($e_id)){echo $e_id;}?>">
                                <?php
                                    $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                                    $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                                    $roles_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
                                    $roles = $this->Crud->read_field('id', $roles_id, 'access_role', 'name');
                                    
                                    if ($ministry_id > 0) { ?>
                                        <input type="hidden" name="ministry_id"  id="ministry_id"  value="<?php echo $ministry_id; ?>">
                                        <input type="hidden" name="church_id" value="<?php echo $church_id; ?>">
                                <?php } else { ?>
                                    <div class="col-md-6 col-lg-4 col-xxl-3 ">
                                        <div class="form-group">
                                            <label class="form-label">Ministry</label>
                                            <select class="js-select2" data-search="on" name="ministry_id" id="ministry_id">
                                                <option value="">Select Ministry</option>
                                                <?php

                                                $ministry = $this->Crud->read_order('ministry', 'name', 'asc');
                                                if (!empty($ministry)) {
                                                    foreach ($ministry as $d) {
                                                        $sel = '';
                                                        if (!empty($e_ministry_id)) {
                                                            if ($e_ministry_id == $d->id) {
                                                                $sel = 'selected';
                                                            }
                                                        }
                                                        echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                <?php } ?>

                                <?php if ($roles != 'Church Leader') { ?>
                                    <div class="col-md-6 col-lg-4 col-xxl-3">
                                        <div class="form-group">
                                            <label  class="form-label">Church Level</label>
                                            <select class="js-select2" data-search="on" name="level" id="level">
                                                <option value="">Select Church Level</option>
                                                <?php
                                                    
                                                    $log_church_id = $this->Crud->read_field('id', $log_id, 'user',  'church_id');
                                                    $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

                                                    if($log_church_type == 'region'){
                                                        
                                                ?>
                                                
                                                    <option value="zone" <?php if(!empty($e_level)){if($e_level == 'zone'){echo 'selected';}} ?>>Zonal Church</option>
                                                    <option value="group" <?php if(!empty($e_level)){if($e_level == 'group'){echo 'selected';}} ?>>Group Church</option>
                                                    <option value="church" <?php if(!empty($e_level)){if($e_level == 'church'){echo 'selected';}} ?>>Church Assembly</option>
                                                <?php } elseif($log_church_type == 'zone'){?>
                                                
                                                    <option value="group" <?php if(!empty($e_level)){if($e_level == 'group'){echo 'selected';}} ?>>Group Church</option>
                                                    <option value="church" <?php if(!empty($e_level)){if($e_level == 'church'){echo 'selected';}} ?>>Church Assembly</option>

                                                <?php } elseif($log_church_type == 'group'){?>
                                                
                                                    <option value="church" <?php if(!empty($e_level)){if($e_level == 'church'){echo 'selected';}} ?>>Church Assembly</option>

                                                <?php } else{?>
                                                    <option value="region" <?php if(!empty($e_level)){if($e_level == 'region'){echo 'selected';}} ?>>Regional Church</option>
                                                    <option value="zone" <?php if(!empty($e_level)){if($e_level == 'zone'){echo 'selected';}} ?>>Zonal Church</option>
                                                    <option value="group" <?php if(!empty($e_level)){if($e_level == 'group'){echo 'selected';}} ?>>Group Church</option>
                                                    <option value="church" <?php if(!empty($e_level)){if($e_level == 'church'){echo 'selected';}} ?>>Church Assembly</option>
                                                <?php } ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    
                                
                                    <div class="col-md-6 col-lg-4 col-xxl-3" id="church_div" style="display:none;">
                                        <div class="form-group">
                                            <label  class="form-label">Church</label>
                                            <select class="js-select2" data-search="on" name="church_id" id="church_id">
                                                <option value="">Select</option>

                                            </select>
                                        </div>
                                    </div>

                                <?php } ?>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label">Title</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select js-select2" name="title" id="title" data-placeholder="Select Title" required>
                                                <option value="">Select Title</option>
                                                <option value="Brother" <?php if(!empty($e_title)){if($e_title ==  'Brother'){echo 'selected';}}; ?>>Brother</option>
                                                <option value="Sister" <?php if(!empty($e_title)){if($e_title ==  'Sister'){echo 'selected';}}; ?>>Sister</option>
                                                <option value="Pastor" <?php if(!empty($e_title)){if($e_title ==  'Pastor'){echo 'selected';}}; ?>>Pastor</option>
                                                <option value="Elder" <?php if(!empty($e_title)){if($e_title ==  'Elder'){echo 'selected';}}; ?>>Elder</option>
                                                <option value="Deacon" <?php if(!empty($e_title)){if($e_title ==  'Deacon'){echo 'selected';}}; ?>>Deacon</option>
                                                <option value="Deaconess" <?php if(!empty($e_title)){if($e_title ==  'Deaconess'){echo 'selected';}}; ?>>Deaconess</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label"
                                            for="last-name">Last Name</label><input type="text"
                                            class="form-control" name="lastname" id="last-name" value="<?php if(!empty($e_lastname)){echo $e_lastname;} ?>" placeholder="Last Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label"
                                            for="first-name">First Name</label><input type="text"
                                            class="form-control" name="firstname" id="first-name"
                                            placeholder="First Name" value="<?php if(!empty($e_firstname)){echo $e_firstname;} ?>" required></div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label"
                                            for="last-name">Other Name</label><input type="text"
                                            class="form-control" name="othername" id="last-name" value="<?php if(!empty($e_othername)){echo $e_othername;} ?>" placeholder="Last Name">
                                    </div>
                                </div>
                               <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label">Gender</label>
                                        <div class="form-control-wrap">
                                            <select
                                                class="form-select js-select2" name="gender" required
                                                data-placeholder="Select Gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male" <?php if(!empty($e_gender)){if($e_gender == 'Male'){echo 'selected';}}?>>Male</option>
                                                <option value="Female" <?php if(!empty($e_gender)){if($e_gender == 'Female'){echo 'selected';}}?>>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label"
                                            for="phone-no">Phone</label><input type="number"
                                            class="form-control" name="phone" id="phone-no" value="<?php if(!empty($e_phone)){echo $e_phone;} ?>" placeholder="Phone no">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label" for="email">Email
                                            Address</label><input type="email" name="email" class="form-control"
                                            id="email" value="<?php if(!empty($e_email)){echo $e_email;} ?>" placeholder="Email Address"></div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label" for="email">Kingchat Handle</label><input type="text" name="chat_handle" class="form-control" value="<?php if(!empty($e_chat_handle)){echo $e_chat_handle;} ?>" id="email" placeholder="Kingchat Handle"></div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label">Birth Date</label>
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-right"><em
                                                    class="icon ni ni-calendar"></em></div><input
                                                type="text" name="dob" value="<?php if(!empty($e_dob)){echo $e_dob;} ?>" class="form-control date-picker"
                                                data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label"
                                            for="address">Adddress</label><input type="text" value="<?php if(!empty($e_address)){echo $e_address;} ?>"
                                            class="form-control" name="address" id="address" placeholder="Address">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label">Family Status</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select js-select2" id="family_status" name="family_status"
                                                data-placeholder="Select Status" onchange="">
                                                <option value="">Select</option>
                                                <option value="single" <?php if(!empty($e_family_status)){if($e_family_status == 'single'){echo 'selected';}} ?>>Single </option>
                                                <option value="married" <?php if(!empty($e_family_status)){if($e_family_status == 'married'){echo 'selected';}} ?>>Married </option>
                                                <option value="sepreated" <?php if(!empty($e_family_status)){if($e_family_status == 'seperated'){echo 'selected';}} ?>>Seperated </option>
                                                <option value="divorced" <?php if(!empty($e_family_status)){if($e_family_status == 'divorced'){echo 'selected';}} ?>>Divorced </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3" id="marriedDiv" style="display: <?php echo (!empty($e_family_status) && $e_family_status == 'married') ? 'block' : 'none'; ?>">
                                    <div class="form-group"><label class="form-label">Marriage Anniverary</label>
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-right"><em
                                                    class="icon ni ni-calendar"></em></div><input
                                                type="text" name="marriage_anniversary" value="<?php if(!empty($e_marriage_anniversary)){echo $e_marriage_anniversary;} ?>" class="form-control date-picker"
                                                data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label">Family Unit Position</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select js-select2" id="family_position" name="family_position"
                                                data-placeholder="Select Position" onchange="posit();">
                                                <option value="">Select</option>
                                                <option value="Child" <?php if(!empty($e_family_position)){if($e_family_position == 'Child'){echo 'selected';}} ?>>Child </option>
                                                <option value="Parent" <?php if(!empty($e_family_position)){if($e_family_position == 'Parent'){echo 'selected';}} ?>>Parent </option>
                                                <option value="Other" <?php if(!empty($e_family_position)){if($e_family_position == 'Other'){echo 'selected';}} ?>>Other </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $disp = 'none';
                                    if(!empty($e_family_position)){
                                        if($e_family_position == 'Child'){
                                            $disp = 'block';
                                        }
                                    }
                                ?>
                                <div class="col-md-6 col-lg-4 col-xxl-3" id="parent_resp" style="display:<?=$disp;?>;">
                                    <div class="form-group"><label class="form-label">Parent</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select js-select2" name="parent_id"
                                                data-placeholder="Select Parent">
                                                <option value="">Select</option>
                                                <?php
                                                    $parent  = $this->Crud->read_single_order('family_position', 'Parent', 'user', 'surname', 'asc');
                                                    if(!empty($parent)){
                                                        foreach($parent as $p){
                                                            $sel = '';
                                                            if(!empty($e_parent_id)){
                                                                if($e_parent_id == $p->id)$sel = 'selected';
                                                            }
                                                            echo '<option value="'.$p->id.'">'.ucwords($p->surname.' '.$p->firstname).'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label">Department</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select js-select2" id="dept_id" name="dept_id"
                                                data-placeholder="Select Department" onchange="dept_role();">
                                                <option value="">Select</option>
                                                <?php
                                                    $parent  = $this->Crud->read_order('dept', 'name', 'asc');
                                                    if(!empty($parent)){
                                                        foreach($parent as $p){
                                                            $sel = '';
                                                            if(!empty($e_dept_id)){
                                                                if($e_dept_id == $p->id){
                                                                    $sel = 'selected';
                                                                }
                                                            }
                                                            echo '<option value="'.$p->id.'" '.$sel.'>'.ucwords($p->name).'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $dept_roles = 'none';
                                    $depts_roles = '';
                                    if(!empty($e_dept_id)){
                                        $dept_roles = 'block';
                                        
                                    }
                                    $cell_roles = 'none';
                                    if(!empty($e_cell_id)){
                                        $cell_roles = 'block';
                                        
                                    }
                                ?>
                                <div class="col-md-6 col-lg-4 col-xxl-3" id="dept_resp" style="display:<?=$dept_roles;?>;">
                                    <div class="form-group"><label class="form-label">Department Role</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select js-select2" id="dept_role_id" name="dept_role_id"
                                                data-placeholder="Select Role">
                                                <option value="">Select</option>
                                               
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group"><label class="form-label">Cell</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select js-select2" id="cell_id" name="cell_id"
                                                data-placeholder="Select Cell">
                                                <option value="">Select</option>
                                                <?php
                                                    if($ministry_id == 0){
                                                        $parent  = $this->Crud->read_order('cells', 'name', 'asc');
                                                    }
                                                    if($ministry_id > 0 && $church_id <= 0){
                                                        $parent  = $this->Crud->read_single_order('ministry_id',  $ministry_id, 'cells', 'name', 'asc');

                                                    }
                                                    if($ministry_id > 0 && $church_id > 0){
                                                        $parent  = $this->Crud->read_single_order('church_id',  $church_id, 'cells', 'name', 'asc');

                                                    }
                                                    if(!empty($parent)){
                                                        foreach($parent as $p){
                                                            $sel = '';
                                                            if(!empty($e_cell_id)){
                                                                if($e_cell_id == $p->id){
                                                                    $sel = 'selected';
                                                                }
                                                            }
                                                            echo '<option value="'.$p->id.'" '.$sel.'>'.ucwords($p->name).'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3" id="cell_resp">
                                    <div class="form-group">
                                        <label class="form-label">Cell Role</label>
                                        <div class="form-control-wrap">
                                            <select class="form-select js-select2" id="cell_role_i" name="cell_role_id"
                                                data-placeholder="Select Cell Role">
                                                <option value="">Select</option>
                                                <?php
                                                    $allowed = ['Cell Leader', 'Cell Executive', 'Assistant Cell Leader', 'Cell Member'];

                                                    $parent  = $this->Crud->read_order('access_role', 'name', 'asc');
                                                    if(!empty($parent)){
                                                        foreach($parent as $p){
                                                            if(!in_array($p->name, $allowed))continue;
                                                            $sel = '';
                                                            if(!empty($e_cell_role)){
                                                                if($e_cell_role == $p->id){
                                                                    $sel = 'selected';
                                                                }
                                                            }
                                                            echo '<option value="'.$p->id.'" '.$sel.'>'.ucwords($p->name).'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group">
                                        <label class="form-label" for="password">Job type</label>
                                        <input class="form-control" type="text" id="job_type" name="job_type" value="<?php if(!empty($e_job_type)){echo $e_job_type;}?>" >
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group">
                                        <label class="form-label" for="password">Employer Name</label>
                                        <input class="form-control" type="text" id="employer_address" name="employer_address" value="<?php if(!empty($e_employer_address)){echo $e_employer_address;}?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group">
                                        <label>Foundation School</label>
                                        <select class="js-select2" name="foundation_school" id="foundation_school" data-placeholder="Select" >
                                            <option value="0" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '0'){echo 'selected';}}; ?>>Prospective Student</option>
                                            <option value="1" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '1'){echo 'selected';}}; ?>>Student</option>
                                            <option value="2" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '2'){echo 'selected';}}; ?>>Graduate</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-lg-4 col-xxl-3" style="display:none;" id="foundation_resp">
                                    <div class="form-group">    
                                        <label class="form-label">Foundation Week</label>    
                                        <div class="input-group mb-3">
                                            <button class="btn btn-outline-secondary decrease" type="button" id="button-minus">
                                                <em class="icon ni ni-minus"></em>
                                            </button>
                                            
                                            <input type="number" class="form-control text-center" name="foundation_weeks" id="spinner" max="7" min="1" step="1" value="<?php if(!empty($e_foundation_weeks)){echo $e_foundation_weeks;}else{echo '1';}; ?>">
                                            
                                            <button class="btn btn-outline-secondary increase" type="button" id="button-plus">
                                                <em class="icon ni ni-plus"></em>
                                            </button>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group">
                                        <div class="col-sm-12 mb-2">
                                            <label>Are you Baptised?</label><br>
                                            <div class="custom-control custom-radio">    
                                                <input type="radio" id="customRadio12" value="yes" name="baptism"  <?php if(!empty($e_baptism)){if($e_baptism == 'yes'){echo 'checked';}} ?> class="custom-control-input">    
                                                <label class="custom-control-label" for="customRadio12">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio">     
                                                <input type="radio" id="customRadio22" value="no" name="baptism"  <?php if(!empty($e_baptism)){if($e_baptism == 'no'){echo 'checked';}} ?> class="custom-control-input">    
                                                <label class="custom-control-label" for="customRadio22">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <div class="form-group">
                                        <label class="form-label" for="password"><?php if(!empty($e_id)) { echo translate_phrase('Reset Password'); } else { echo translate_phrase('*Password'); } ?></label>
                                        <input class="form-control" type="text" id="password" name="password" <?php if(empty($e_id)) { echo 'required'; } ?>>
                                    </div>
                                </div>
                                <div class="mb-2 col-md-6 col-lg-4 col-xxl-3">
                                    <label for="img-upload" class="pointer text-center" style="width:50%;">
                                        <input type="hidden" name="img_id" value="<?php if(!empty($e_img_id)){echo $e_img_id;} ?>" />
                                        <img id="img0" src="<?php if(!empty($e_img_id) && file_exists($e_img_id)){echo site_url($e_img_id);} else {echo site_url('assets/images/avatar.png');} ?>" style="max-width:100%;" />
                                        <span class="btn btn-default btn-block no-mrg-btm d-grid btn btn-secondary waves-effect"><i class="mdi mdi-cloud-upload me-1"></i><?=translate_phrase('Choose Image'); ?></span>
                                        <input class="d-none" type="file" name="pics" id="img-upload" accept="image/*">
                                        
                                    </label>
                                </div>
                                <div class="col-sm-12 mb-3 text-center">
                                    <div class="form-group  mt-4"><button type="submit"
                                            class="btn btn-primary">Save Membership</button></div>
                                </div>
                            </div>
                            <div class="row gy-4">
                                <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
                            </div>

                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>var site_url = '<?php echo site_url(); ?>';</script>
<script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script>
    function readURL(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                if(id != 'vid') {
                    $('#' + id).attr('src', e.target.result);
                } else {
                    $('#' + id).show(500);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#img-upload").change(function(){
        readURL(this, 'img0');
    });
    $(function() {
        $('#family_status').on('change', function(){
            var selectedValue = $(this).val();
            if(selectedValue === 'married') {
                $('#marriedDiv').show(500);
            } else {
                $('#marriedDiv').hide(500);
            }
        });
        $('#foundation_school').on('change', function () {
            var selectedType = $(this).val();
            if (selectedType == '1') {
                $('#foundation_resp').show(500);
            } else{
                $('#foundation_resp').hide(500);
            }
            
        });
        $('#foundation_school').trigger('change');

        $('.increase').on('click', function() {
            let spinner = $('#spinner');
            let currentValue = parseInt(spinner.val());
            let max = parseInt(spinner.attr('max'));

            if (!isNaN(currentValue) && currentValue < max) {
                spinner.val(currentValue + 1);
            }
        });

        // Decrease button functionality
        $('.decrease').on('click', function() {
            let spinner = $('#spinner');
            let currentValue = parseInt(spinner.val());
            let min = parseInt(spinner.attr('min'));

            if (!isNaN(currentValue) && currentValue > min) {
                spinner.val(currentValue - 1);
            }
        });

        $('#spinner').on('input', function() {
            let value = parseInt($(this).val());

            // If input is not a number, set it to 0
            if (isNaN(value)) {
                $(this).val(0);
                return;
            }

            // Ensure the value is between 0 and 7
            if (value < 0) {
                $(this).val(0);
            } else if (value > 7) {
                $(this).val(7);
            }
        });

        // Prevent entering non-numeric characters
        $('#spinner').on('keypress', function(e) {
            let keyCode = e.which;

            // Allow only numbers (keycode for 0-9 is between 48 and 57)
            if (keyCode < 48 || keyCode > 57) {
                e.preventDefault();
            }
        });
    });
    <?php 
        if(!empty($e_dept_role)){?>
            var dept = '<?=$e_dept_role; ?>';
            setTimeout(dept_role(dept), 2000);
        <?php }
    ?>
     <?php 
        if(!empty($e_cell_role)){?>
            var cell = '<?=$e_cell_role; ?>';
            setTimeout(cell_role(cell), 2000);
        <?php }
    ?>
    function dept_role(dept){
        var dept_id = $('#dept_id').val();
        $.ajax({
            url: site_url + 'accounts/membership/get_dept_role/' + dept_id + '/'+ dept,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#dept_role_id').html(dt.list);
                $('#bb_ajax_msg').html(dt.script);
                
            }
        });
    }

    function cell_role(cell){
        var cell_id = $('#cell_id').val();
        $.ajax({
            url: site_url + 'accounts/membership/get_cell_role/' + cell_id+ '/'+ cell,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#cell_role_id').html(dt.list);
                $('#bb_ajax_msg').html(dt.script);
                
            }
        });
    }

    function posit(){
        var position = $('#family_position').val();
        if(position == 'Child'){
            $('#parent_resp').show(500);
        } else{
            $('#parent_resp').hide(500);
        }
    }
    function loads() {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        if(!start_date || !end_date){
            $('#date_resul').css('color', 'Red');
            $('#date_resul').html('Enter Start and End Date!!');
        } else if(start_date > end_date){
            $('#date_resul').css('color', 'Red');
            $('#date_resul').html('Start Date cannot be greater!');
        } else {
            $('#date_resul').html('');
            load('', '');
        }
    }

    
    $(document).ready(function () {
        <?php
           $e_church_ids = !empty($e_church_id) ? $e_church_id : 0;
        ?>
        var eChurchId = <?php echo $e_church_ids; ?>;
        // Function to load churches based on selected ministry ID and/or level
        function loadChurches(ministryId, level) {
            // Clear the Church dropdown
            $('#church_id').empty();
            $('#church_id').append(new Option('Loading...', '', false, false));

            // Construct data object based on provided parameters
            var data = {};
            if (ministryId) {
                data.ministry_id = ministryId;
            }
            if (level) {
                data.level = level;
            }

            // Proceed if there's data to be sent
            if (Object.keys(data).length > 0) {
                $.ajax({
                    url: site_url + 'ministry/announcement/get_church', // Update this to the path of your API endpoint
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function (response) {
                        $('#church_id').empty(); // Clear 'Loading...' option

                        if (response.success) {
                            
                           // Populate the Church dropdown with the data received
                           $.each(response.data, function (index, church) {
                                var selected = (eChurchId === church.id); // Check if the ID matches
                                var churchName = toTitleCase(church.name); // Convert name to title case
                                var churchType = toTitleCase(church.type); // Convert type to title case
                                $('#church_id').append(new Option(churchName + ' - ' + churchType, church.id, selected, selected));
                            });
                        } else {
                            $('#church_id').append(new Option('No churches available', '', false, false));
                        }
                    },
                    error: function () {
                        $('#church_id').append(new Option('Error fetching churches', '', false, false));
                    }
                });
            } else {
                $('#church_id').append(new Option('Please select a ministry or level', '', false, false));
            }
        }

        // Helper function to convert strings to title case
        function toTitleCase(str) {
            return str.toLowerCase().replace(/(?:^|\s)\S/g, function (a) { return a.toUpperCase(); });
        }

        // Auto-load churches if ministry_id or level is already set
        var ministryId = $('#ministry_id').val();
        var initialLevel = $('#level').val();
        if (ministryId || initialLevel) {
            if (initialLevel === 'all') {
                $('#church_div').hide(600);
                $('#send_resp').hide(600); // Hide the Church dropdown
            } else {
                $('#send_resp').show(600);
                $('#church_div').show(600); // Show the Church dropdown
            }
            loadChurches(ministryId, initialLevel);
        }

        // Load churches on ministry selection change
        $('#ministry_id').change(function () {
            var selectedMinistryId = $(this).val();
            var selectedLevel = $('#level').val();
            loadChurches(selectedMinistryId, selectedLevel);
        });

        // Handle the change event of the Church Level dropdown
        $('#level').change(function() {
            var selectedLevel = $(this).val();
            var selectedMinistryId = $('#ministry_id').val();

            if (selectedLevel === 'all') {
                $('#church_div').hide(600);
                $('#send_resp').hide(600); // Hide the Church dropdown
            } else {
                $('#send_resp').show(600);
                $('#church_div').show(600); // Show the Church dropdown
                loadChurches(selectedMinistryId, selectedLevel); // Load churches based on selected level
            }
        });

        // Initial check to handle the case when the page loads with a preset level
        if (initialLevel !== 'all') {
            $('#church_div').show(600); // Ensure the Church dropdown is shown if a level is selected
        } else {
            $('#church_div').hide(600); // Hide the Church dropdown if the level is 'all'
        }
    });


    function load(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#load_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>Loading Please Wait</div>');
        } else {
            $('#loadmore').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>Loading Please Wait</div>');
        }

        
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var state_id = $('#state_id').val();
        var status = $('#status').val();
        var ref_status = $('#ref_status').val();
        var verify = $('#verify').val();
        var search = $('#search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'accounts/business/load' + methods,
            type: 'post',
            data: { state_id: state_id, search: search,start_date: start_date,end_date: end_date , status: status, verify: verify, ref_status: ref_status },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_data').html(dt.item);
                } else {
                    $('#load_data').append(dt.item);
                }
                $('#counta').html(dt.count);
                if (dt.offset > 0) {
                    $('#loadmore').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#loadmore').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }
</script>   

<?=$this->endSection();?>