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
                <h3><b><?=translate_phrase('Are you sure?'); ?></b></h3>
                <input type="hidden" name="d_user_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete'); ?>
                </button>
            </div>
        </div>
    <?php } ?>

    <!-- insert/edit view -->
    
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <input type="hidden" name="user_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label>Title</label>
                    <select class="js-select2" name="title" id="title" data-placeholder="Select Title" required>
                        <option value=" ">Select Title</option>
                        <option value="Mr." <?php if(!empty($e_title)){if($e_title ==  'Mr.'){echo 'selected';}}; ?>>Mr.</option>
                        <option value="Mrs." <?php if(!empty($e_title)){if($e_title ==  'Mrs.'){echo 'selected';}}; ?>>Mrs.</option>
                        <option value="Ms." <?php if(!empty($e_title)){if($e_title ==  'Ms.'){echo 'selected';}}; ?>>Ms.</option>
                        <option value="Brother" <?php if(!empty($e_title)){if($e_title ==  'Brother'){echo 'selected';}}; ?>>Brother</option>
                        <option value="Sister" <?php if(!empty($e_title)){if($e_title ==  'Sister'){echo 'selected';}}; ?>>Sister</option>
                        <option value="Evang." <?php if(!empty($e_title)){if($e_title ==  'Evang.'){echo 'selected';}}; ?>>Evang.</option>
                        <option value="Deacon" <?php if(!empty($e_title)){if($e_title ==  'Deacon'){echo 'selected';}}; ?>>Deacon</option>
                        <option value="Deaconess" <?php if(!empty($e_title)){if($e_title ==  'Deaconess'){echo 'selected';}}; ?>>Deaconess</option>
                        <option value="Pastor" <?php if(!empty($e_title)){if($e_title ==  'Pastor'){echo 'selected';}}; ?>>Pastor</option>
                        <option value="Rev." <?php if(!empty($e_title)){if($e_title ==  'Rev.'){echo 'selected';}}; ?>>Rev.</option>
                        
                    </select>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('First Name'); ?></label>
                    <input class="form-control" type="text" id="firstname" name="firstname" value="<?php if(!empty($e_firstname)) {echo $e_firstname;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Surname'); ?></label>
                    <input class="form-control" type="text" id="surname" name="surname" value="<?php if(!empty($e_surname)) {echo $e_surname;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name"><?=translate_phrase('Other Name'); ?></label>
                    <input class="form-control" type="text" id="othername" name="othername" value="<?php if(!empty($e_othername)) {echo $e_othername;} ?>">
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Email');?></label>
                    <input class="form-control" type="text" id="email" name="email" value="<?php if(!empty($e_email)) {echo $e_email;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Phone');?></label>
                    <input class="form-control" type="text" id="phone" name="phone" value="<?php if(!empty($e_phone)) {echo $e_phone;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <?php $roles = $this->Crud->read_single_order('name!=', 'developer', 'access_role', 'name', 'asc'); ?>
                <div class="form-group">
                    <label for="role_id"><?=translate_phrase('Set Role');?></label>
                    <select id="role_id" name="role_id" class="js-select2" required>
                        <?php 
                            if(!empty($roles)) {
                                foreach($roles as $r) {
                                    if($r->name == 'Member' || $r->name == 'Administrator' || $r->name == 'Ministry Administrator')continue;
                                    $r_sel = '';
                                    if(!empty($e_role_id)) {
                                        if($e_role_id == $r->id) { $r_sel = 'selected'; }
                                    }
                                    echo '<option value="'.$r->id.'" '.$r_sel.'>'.$r->name.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>

            
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Active Status');?></label>
                    <select class="js-select2" id="activate" name="activate" required>
                        <option value="1" <?php if(!empty($e_activate)){if($e_activate == 1){echo 'selected';}} ?>><?=translate_phrase('Active');?></option>
                        <option value="0" <?php if($param2 == 'edit' && empty($e_activate)){if($e_activate == 0){echo 'selected';}} ?>><?=translate_phrase('Disable');?></option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Address');?></label>
                    <input class="form-control" type="text" id="address" name="address" value="<?php if(!empty($e_address)) {echo $e_address;} ?>">
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="password"><?php if(!empty($e_id)) { echo translate_phrase('Reset Password'); } else { echo translate_phrase('*Password'); } ?></label>
                    <input class="form-control" type="text" id="password" name="password" <?php if(empty($e_id)) { echo 'required'; } ?>>
                </div>
            </div>
            <?php
                $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

                if ($ministry_id > 0) { ?>
                    <input type="hidden" name="ministry_id" value="<?php echo $ministry_id; ?>">
                    <input type="hidden" name="church_id" value="<?php echo $church_id; ?>">
            <?php } else { ?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Ministry</label>
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

            <?php if ($role != 'Church Leader') { ?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Church Level</label>
                        <select class="js-select2" data-search="on" name="level" id="level">
                            <option value="all">All Church Level</option>
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
                
            
                <div class="col-sm-6 mb-3" id="church_div" style="display:none;">
                    <div class="form-group">
                        <label>Church</label>
                        <select class="js-select2" data-search="on" name="church_id" id="church_id">
                            <option value="">Select</option>

                        </select>
                    </div>
                </div>

            <?php } ?>

            <div class="col-sm-12 text-center">
                <hr />
                <button class="btn btn-primary bb_form_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script>
    $(function() {
        $('.js-select2').select2();
    });
</script>