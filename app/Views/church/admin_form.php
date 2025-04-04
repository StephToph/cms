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

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Active Status');?></label>
                    <select class="js-select2" id="activate" name="activate" required>
                        <option value="1" <?php if(!empty($e_activate)){if($e_activate == 1){echo 'selected';}} ?>><?=translate_phrase('Active');?></option>
                        <option value="0" <?php if($param2 == 'edit' && empty($e_activate)){if($e_activate == 0){echo 'selected';}} ?>><?=translate_phrase('Disable');?></option>
                    </select>
                </div>
            </div>

            <div class="col-sm-12 text-center">
                <hr />
                <button class="btn btn-primary bb_frm_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
    <?php } ?>
    <?php if($param2 == 'admin_send') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?=translate_phrase('Are you sure?');?></b></h3>
                <input type="hidden" name="admin_id" value="<?php if(!empty($param3)){echo $param3;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-success text-uppercase" type="submit">
                    <em class="icon ni ni-share-alt"></em> <span><?=('Yes - Send Login Details');?></span>
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