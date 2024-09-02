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

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Full Name'); ?></label>
                    <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_fullname)) {echo $e_fullname;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Email');?></label>
                    <input class="form-control" type="text" id="email" name="email" value="<?php if(!empty($e_email)) {echo $e_email;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Phone');?></label>
                    <input class="form-control" type="text" id="phone" name="phone" value="<?php if(!empty($e_phone)) {echo $e_phone;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Country');?></label>
                    <input class="form-control" type="text" id="country_id" name="country_id" value="Nigeria" readonly required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('State');?></label>
                    <input class="form-control" type="text" id="state_id" name="state_id" value="Delta" readonly required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('LGA');?></label>
                    <?php $country_id = 316; $states = $this->Crud->read_single_order('state_id', $country_id, 'city', 'name', 'asc'); ?>
                    <select id="lga_id" name="lga_id" class="select2"  data-search="on">
                        <option value="0" selected><?=translate_phrase('All LGA'); ?>...</option>
                        <?php 
                            foreach($states as $s) {
                                $s_sel = '';
                                if(!empty($e_lga_id)) {
                                    if($e_lga_id == $s->id) { $s_sel = 'selected'; }
                                }
                                echo '<option value="'.$s->id.'" '.$s_sel.'>'.$s->name.'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-6">
                <?php $roles = $this->Crud->read_single_order('name!=', 'developer', 'access_role', 'name', 'asc'); ?>
                <div class="form-group">
                    <label for="role_id"><?=translate_phrase('Set Role');?></label>
                    <select id="role_id" name="role_id" class="select2" required>
                        <?php 
                            if(!empty($roles)) {
                                foreach($roles as $r) {
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
                    <label for="password"><?php if(!empty($e_id)) { echo translate_phrase('Reset Password'); } else { echo translate_phrase('*Password'); } ?></label>
                    <input class="form-control" type="text" id="password" name="password" <?php if(empty($e_id)) { echo 'required'; } ?>>
                </div>
            </div>

            <div class="col-sm-12 text-center">
                <hr />
                <button class="btn btn-primary bb_form_btn" type="submit">
                    <i class="anticon anticon-save"></i> <?=translate_phrase('Save Record');?>
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