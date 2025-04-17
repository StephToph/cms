<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    
    <!-- Delete View -->
    <?php if($param2 == 'delete') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b>Are you sure you want to delete this tour step?</b></h3>
                <input type="hidden" name="d_step_id" value="<?php echo esc($d_id ?? ''); ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="anticon anticon-delete"></i> Yes - Delete
                </button>
            </div>
        </div>
    <?php } ?>

    <!-- Insert/Edit View -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <input type="hidden" name="step_id" value="<?php echo esc($e_id ?? ''); ?>" />

            <div class="col-sm-12ß mb-2">
                <div class="form-group">
                    <label for="title" class="form-label">Step Title</label>
                    <input class="form-control" type="text" id="title" name="title" value="<?php echo esc($e_title ?? ''); ?>" required>
                </div>
            </div>

            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="content" class="form-label">Step Content</label>
                    <textarea class="form-control" id="content" name="content" rows="3" required><?php echo esc($e_content ?? ''); ?></textarea>
                </div>
            </div>


            <div class="col-sm-6 mb-2">
                <div class="form-group">
                    <label for="selector" class="form-label">Selector (e.g., #start-tour)</label>
                    <input class="form-control" type="text" id="selector" name="selector" value="<?php echo esc($e_selector ?? ''); ?>" required>
                </div>
            </div>

            <div class="col-sm-6 mb-2">
                <div class="form-group">
                    <label for="placement" class="form-label">Tooltip Placement</label>
                    <select class="form-control select2" name="placement" id="placement" required>
                        <?php
                            $placements = ['top', 'bottom', 'left', 'right'];
                            foreach ($placements as $place) {
                                $selected = (!empty($e_placement) && $e_placement == $place) ? 'selected' : '';
                                echo "<option value=\"$place\" $selected>".ucfirst($place)."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-6 mb-2">
                <div class="form-group">
                    <label for="page" class="form-label">Page (e.g., dashboard, settings)</label>
                    <input class="form-control" type="text" id="page" name="page" value="<?php echo esc($e_page ?? ''); ?>" required>
                </div>
            </div>

            <div class="col-sm-6 mb-2">
                <div class="form-group">
                    <label for="step_order" class="form-label">Step Order</label>
                    <input class="form-control" type="number" id="step_order" name="step_order" value="<?php echo esc($e_order ?? 1); ?>" required>
                </div>
            </div>

            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="allowed_roles" class="form-label">Allowed Roles</label>
                    <select class="form-control select2" id="allowed_roles" name="allowed_roles[]" multiple="multiple" required>
                        <?php
                            if (!empty($all_roles)) {
                                $selected_roles = explode(',', $e_roles ?? '');
                                foreach ($all_roles as $role) {
                                    $selected = in_array($role->name, $selected_roles) ? 'selected' : '';
                                    echo '<option value="'.esc($role->name).'" '.$selected.'>'.ucfirst($role->name).'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>

            </div>

            <div class="col-sm-12 mb-2 text-center">
                <hr />
                <button class="btn btn-primary bb_form_btn" type="submit">
                    <i class="anticon anticon-save"></i> Save Tour Step
                </button>
            </div>
        </div>
    <?php } ?>

<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script>
    $('.select2').select2();
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Select roles',
            allowClear: true
        });
    });
</script>
