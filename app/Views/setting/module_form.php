<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param2 == 'delete') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b>Are you sure?</b></h3>
                <input type="hidden" name="d_module_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="anticon anticon-delete"></i> Yes - Delete
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
            <input type="hidden" name="module_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="parent_id">Parent</label>
                    <?php if(!empty($load_select_module)){echo $load_select_module;} ?>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">Module</label>
                    <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_name)){echo $e_name;} ?>" required>
                </div>
            </div>

            <div class="col-sm-5">
                <div class="form-group">
                    <label for="link">Link</label>
                    <input class="form-control" type="text" id="link" name="link" value="<?php if(!empty($e_link)){echo $e_link;} ?>" required>
                </div>
            </div>

            <div class="col-sm-5">
                <div class="form-group">
                    <label for="icon">Icon</label>
                    <input class="form-control" type="text" id="icon" name="icon" value="<?php if(!empty($e_icon)){echo $e_icon;} ?>">
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group">
                    <label for="priority">Priority</label>
                    <input class="form-control" type="text" id="priority" name="priority" value="<?php if(!empty($e_priority)){echo $e_priority;} ?>">
                </div>
            </div>
            
            <div class="col-sm-12 text-center">
                <hr />
                <button class="btn btn-primary bb_form_btn" type="submit">
                    <i class="anticon anticon-save"></i> Save Record
                </button>
            </div>

        </div>
    <?php } ?>
<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script>
    $('.select2').select2();
</script>