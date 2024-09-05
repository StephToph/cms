<?php
use App\Models\Crud;

$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id' => 'bb_ajax_form', 'class' => '')); ?>
<!-- delete view -->
<?php if ($param2 == 'delete') { ?>
    <div class="row">
        <div class="col-sm-12">
            <div id="bb_ajax_msg"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 text-center">
            <h3><b>Are you sure?</b></h3>
            <input type="hidden" name="d_announcement_id" value="<?php if (!empty($d_id)) {
                echo $d_id;
            } ?>" />
        </div>

        <div class="col-sm-12 text-center">
            <button class="btn btn-danger text-uppercase" type="submit">
                <i class="icon ni ni-trash"></i> Yes - Delete
            </button>
        </div>
    </div>
<?php } ?>

<?php if ($param2 == 'view') { ?>
    <div class="row">
        <div class="col-sm-6 mb-3">
            <div class="user-card">
                <div class="user-avatar">
                    <?php $img = $this->Crud->read_field('id', $e_from_id, 'user', 'img_id');
                    $src = base_url($this->Crud->image($img, 'big')); ?>
                    <img src="<?= $src; ?>" alt="">
                </div>
                <div class="user-info">
                    <span
                        class="lead-text"><?= ucwords($this->Crud->read_field('id', $e_from_id, 'user', 'fullname')); ?></span>
                    <span class="sub-text"><?= $e_reg_date; ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 mb-3">
            <h5><?=ucwords($e_type); ?> Announcement</h5>
        </div>
        
        <div class="col-sm-12 mb-3">
            <h5 class="text-center text-info"><?= ucwords($e_title); ?></h5>
            <div class="my-1">
                <p><?= ucwords(($e_content)); ?></p>
            </div>
        </div>
        
        <div class="mb-3">
            <h6>Recipient</h6>
            <div class="mt-2 row">
                <?php 
                    if($e_type == 'department'){?>
                        <div class="col-sm-6 mb-2">
                            <div class="user-card">
                                <div class="user-info">
                                    <span class="lead-text"><?= ucwords($this->Crud->read_field('id', $e_dept_id, 'dept', 'name')); ?> Department</span>
                                </div>
                            </div>
                        </div>

                    <?php } else{
                        echo '<p class="text-info">Users with</p>';
                        if (!empty($e_role_id)) {
                        foreach (json_decode($e_role_id) as $rec => $va) {;
                            $role = $this->Crud->read_field('id', $va, 'access_role', 'name');
                        
                            $wors = $this->Crud->image_name($role);
                            $img = '<span>' . $wors . '</span>';
                            
                            ?>
                            <div class="col-sm-4 mb-2">
                                <div class="user-card">
                                    <div class="user-avatar"><?= $img; ?></div>
                                    <div class="user-info">
                                        <span class="lead-text"><?= ucwords($role); ?> Role</span>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } ?>

                    <?php } ?>
                    
            </div>
        </div>
    </div>

<?php } ?>

<!-- insert/edit view -->
<?php if ($param2 == 'edit' || $param2 == '') { ?>
    <div class="row">
        <div class="col-sm-12">
            <div id="bb_ajax_msg"></div>
        </div>
    </div>

    <div class="row">
        <input type="hidden" name="announcement_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name">Title</label>
                <input class="form-control" type="text" id="title" name="title" value="<?php if (!empty($e_title)) {
                    echo $e_title;
                } ?>" required>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name">Content</label>
                <textarea id="summernote" class="form-control" name="content" rows="5" required><?php if (!empty($e_content)) {
                    echo $e_content;
                } ?></textarea>
            </div>
        </div>

        <?php
        if ($role_c == 0) {
             ?>
                <div class="col-sm-12 mb-3">
                    <h5 class="text-danger text-center">Sending Announcement to Department Members</h5>
                    <input type="hidden" name="dept_id" value="<?= $this->Crud->read_field('id', $log_id, 'user', 'dept_id'); ?>">
                    <input type="hidden" name="type" value="department">
                </div>
            <?php 
        } else { ?>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label>Announcement Type</label>
                    <select class="js-select2" data-search="on" name="type" id="type" required>
                        <option value="">Select Announcement Type</option>
                        <option value="department" <?php if (!empty($e_type)) {
                            if ($e_type == 'department') {
                                echo 'selected';
                            }
                        }
                        ; ?>>
                            Department Announcement</option>
                        <option value="general" <?php if (!empty($e_type)) {
                            if ($e_type == 'general') {
                                echo 'selected';
                            }
                        }
                        ; ?>>General
                            Announcement</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3" id="dept_resp" style="display: none;">
                <div class="form-group">
                    <label>Department</label>
                    <select class="js-select2" data-search="on" name="dept_id" id="dept_id">
                        <option value="">Select Department</option>
                        <?php

                        $dept = $this->Crud->read_order('dept', 'name', 'asc');
                        if (!empty($dept)) {
                            foreach ($dept as $d) {
                                $sel = '';
                                if (!empty($e_dept_id)) {
                                    if ($e_dept_id == $d->id) {
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

            <div class="col-sm-12 mb-3" id="role_resp" style="display: none;">
                <div class="form-group">
                    <label>User Roles</label>
                    <select class="js-select2" data-search="on" multiple name="roles_id[]" id="roles_id">
                        <option value="everybody">Everybody</option>
                        <?php

                        $dept = $this->Crud->read_single_order('name !=', 'Developer', 'access_role', 'name', 'asc');
                        if (!empty($dept)) {
                            foreach ($dept as $d) {
                                $sel = '';
                                if (!empty($e_role_id)) {
                                    if (in_array($d->id, $e_role_id)) {
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
        <div class="col-sm-12 text-center">
            <hr />
            <button class="btn btn-primary bb_form_btn" id="bt" type="submit">
                <i class="icon ni ni-save"></i> Save
            </button>
        </div>

    </div>
<?php } ?>
<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>

<script>

    $(function () {
        $('#summernote').summernote({
            height: 300, // Set the height of the editor
            tabsize: 2,
            focus: true
        });
    });


    var base_url = '<?php echo site_url(); ?>';

    $(document).ready(function () {
        // Function to show/hide fields based on selected announcement type
        function toggleFields(selectedType) {
            if (selectedType == 'department') {
                $('#dept_resp').show(500);
                $('#role_resp').hide(500);
            } else if (selectedType == 'general') {
                $('#dept_resp').hide(500);
                $('#role_resp').show(500);
            } else {
                $('#dept_resp').hide(500);
                $('#role_resp').hide(500);
            }
        }

        // Trigger on page load to initialize based on current value
        var initialType = $('#type').val();
        toggleFields(initialType);

        // Trigger on change of announcement type select
        $('#type').on('change', function () {
            var selectedType = $(this).val();
            toggleFields(selectedType);
        });

        $('#roles_id').on('change', function() {
            var selectedValues = $(this).val();

            // Check if "Everybody" is selected
            if (selectedValues && selectedValues.includes('everybody')) {
                // Select all options except "everybody"
                $('#roles_id option').prop('selected', true);
                $('#roles_id option[value="everybody"]').prop('selected', false);
            } else {
                // Unselect "everybody" if it was selected previously
                $('#roles_id option[value="everybody"]').prop('selected', false);
            }

            // Trigger Select2 to update the view
            $('#roles_id').trigger('change.select2');
        });

    });

</script>