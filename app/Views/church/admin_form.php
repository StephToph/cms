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
                    <i class="anticon anticon-delete"></i> <?=translate_phrase('Yes - Delete'); ?>
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

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Active Status');?></label>
                    <select class="select2" id="activate" name="activate" required>
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
<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script>
    $(function() {
        $('.select2').select2();
    });
</script>