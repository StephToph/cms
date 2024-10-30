
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
                <input type="hidden" name="d_dept_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete');?>
                </button>
            </div>
        </div>
    <?php } ?>

    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        
        
        <div class="row">
            <input type="hidden" name="dept_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Name'); ?></label>
                    <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_name)) {echo $e_name;} ?>" required>
                </div>
            </div>
        </div>
        <label for="name">*<?=translate_phrase('Role');?></label>
        <div class="row" id="container">
            <?php if(!empty($e_roles)){$a = 0;
                foreach($e_roles as $k => $val){
                    $r_val = 'style="display:none;"';$req = 'required';
                    if($a > 0){
                        $r_val = 'style="display:display;"';$req = '';
                    }
                    ?>
                <div class="col-sm-12 mb-3 ">
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="role" placeholder="Enter Department Roles" name="roles[]" value="<?php if(!empty($val)) {echo $val;} ?>" <?=$req; ?>>
                        <button <?=$r_val; ?>  class="btn btn-icon btn-outline-danger deleteBtn" type="button"><i class="icon ni ni-trash"></i> </button>
                    </div>
                    
                </div>
           <?php $a++; }} else {?>
                <div class="col-sm-12 mb-3 ">
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="role" placeholder="Enter Department Roles" name="roles[]" value="<?php if(!empty($val)) {echo $val;} ?>" required>
                        <button style="display:none;" class="btn btn-icon btn-outline-danger deleteBtn" type="button"><i class="icon ni ni-trash"></i> </button>
                    </div>
                    
                </div>
           <?php }?>
        </div>

        <div class="row" >
            <div class="col-sm-12 mb-3 text-center">
                <button id="addMore" class="btn btn-ico btn-outline-primary" type="button"><i class="icon ni ni-plus"></i> <?=translate_phrase('Add More Roles');?></button>
            </div>
            <div class="col-sm-12 text-center mt-3">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

    <?php } ?>

    <?php if($param2 == 'message') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <?php
            $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
            $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
            $church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
            
            if ($ministry_id > 0) { ?>
                <input type="hidden" name="ministry_id" id="ministry_id" value="<?php echo $ministry_id; ?>">
                <input type="hidden" name="church_id[]" value="<?php echo $church_id; ?>">
            <?php } else { ?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label  class="form-label">Ministry</label>
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
            
            <input type="hidden" id="log_church_id" value="<?php echo $church_id; ?>">
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label  class="form-label" for="activate"><?=translate_phrase('Message all Members?');?></label>
                    <select class="form-control js-select2" data-toggle="select2" id="type" name="type">
                        <option value="true"><?=translate_phrase('Yes - All Members'); ?></option>
                        <option value="false"><?=translate_phrase('No - Select Department Role'); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3" id="dept_role_container" style="display: none;">
                <div class="form-group">
                    <label  class="form-label" for="dept_role"><?= translate_phrase('Department Role'); ?></label>
                    <select class="form-control js-select2" multiple id="dept_role" name="dept_role[]">
                        <?php
                            $roles = json_decode($this->Crud->read_field('id', $param3, 'dept', 'roles'));
                            if(!empty($roles)){
                                foreach($roles as $r){
                                    echo '<option value="'.$r.'">'.ucwords($r).'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>

            <?php if ($role != 'church leader') { ?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label  class="form-label">Send Type</label>
                        <select class="js-select2" data-search="on" name="send_type" id="send_type" required>
                            <option value="individual"> Individual Church</option>
                            <option value="general" >General Church</option>
                        </select>
                    </div>
                </div>
        
                <div class="col-sm-6 mb-3" id="level_div" >
                    <div class="form-group">
                        <label class="form-label">Church Level</label>
                        <select class="js-select2" data-search="on" name="level" id="level">
                            <option value="">Select Church Level</option>
                            <?php

                            $log_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                            $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

                            if ($log_church_type == 'region') {

                                ?>

                                <option value="zone" <?php if (!empty($e_level)) {
                                    if ($e_level == 'zone') {
                                        echo 'selected';
                                    }
                                } ?>>Zonal Church
                                </option>
                                <option value="group" <?php if (!empty($e_level)) {
                                    if ($e_level == 'group') {
                                        echo 'selected';
                                    }
                                } ?>>Group
                                    Church</option>
                                <option value="church" <?php if (!empty($e_level)) {
                                    if ($e_level == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>Church
                                    Assembly</option>
                            <?php } elseif ($log_church_type == 'zone') { ?>

                                <option value="group" <?php if (!empty($e_level)) {
                                    if ($e_level == 'group') {
                                        echo 'selected';
                                    }
                                } ?>>Group
                                    Church</option>
                                <option value="church" <?php if (!empty($e_level)) {
                                    if ($e_level == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>Church
                                    Assembly</option>

                            <?php } elseif ($log_church_type == 'group') { ?>

                                <option value="church" <?php if (!empty($e_level)) {
                                    if ($e_level == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>Church
                                    Assembly</option>

                            <?php } else { ?>
                                <option value="region" <?php if (!empty($e_level)) {
                                    if ($e_level == 'region') {
                                        echo 'selected';
                                    }
                                } ?>>Regional
                                    Church</option>
                                <option value="zone" <?php if (!empty($e_level)) {
                                    if ($e_level == 'zone') {
                                        echo 'selected';
                                    }
                                } ?>>Zonal Church
                                </option>
                                <option value="group" <?php if (!empty($e_level)) {
                                    if ($e_level == 'group') {
                                        echo 'selected';
                                    }
                                } ?>>Group
                                    Church</option>
                                <option value="church" <?php if (!empty($e_level)) {
                                    if ($e_level == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>Church
                                    Assembly</option>
                            <?php } ?>

                        </select>
                    </div>
                </div>


                <div class="col-sm-12 mb-3" id="church_div" >
                    <div class="form-group">
                        <label class="form-label">Church</label>
                        <select class="js-select2" data-search="on" multiple name="church_id[]" id="church_id">
                            <option value="">Select</option>

                        </select>
                    </div>
                </div>

            <?php } ?>
            
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label  class="form-label" for="activate"><?=translate_phrase('Subject');?></label>
                    <input type='text' class="form-control" name='subject' id='subject' required>
                </div>
            </div>
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label  class="form-label" for="activate"><?=translate_phrase('Message');?></label>
                    <textarea class="form-control"name='message' id='message' rows="5" required></textarea>
                </div>
            </div>


            <div class="col-sm-12 mt-3 text-center">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-send"></i> <?=translate_phrase('Send Message');?>
                </button>
            </div>
        </div>
    <?php } ?>

    
    <?php if($param2 == 'bulk_message') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label  class="form-label">Department</label>
                    <select class="js-select2" data-search="on" multiple name="dept_id[]" id="dept_id">
                        <?php

                        $ministry = $this->Crud->read_order('dept', 'name', 'asc');
                        if (!empty($ministry)) {
                            foreach ($ministry as $d) {
                                $sel = '';
                               
                                echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <?php
            $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
            $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
            $church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
            
            if ($ministry_id > 0) { ?>
                <input type="hidden" name="ministry_id" id="ministry_id" value="<?php echo $ministry_id; ?>">
                <input type="hidden" name="church_id[]" value="<?php echo $church_id; ?>">
            <?php } else { ?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label  class="form-label">Ministry</label>
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
            
            <input type="hidden" id="log_church_id" value="<?php echo $church_id; ?>">
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label  class="form-label" for="activate"><?=translate_phrase('Message all Members?');?></label>
                    <select class="form-control js-select2" data-toggle="select2" id="type" name="type">
                        <option value="true"><?=translate_phrase('Yes - All Members'); ?></option>
                        <option value="false"><?=translate_phrase('No - Select Department Role'); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3" id="dept_role_container" style="display: none;">
                <div class="form-group">
                    <label  class="form-label" for="dept_role"><?= translate_phrase('Department Role'); ?></label>
                    <select class="form-control js-select2" multiple id="dept_role" name="dept_role[]">
                        <?php
                            $roles = json_decode($this->Crud->read_field('id', $param3, 'dept', 'roles'));
                            if(!empty($roles)){
                                foreach($roles as $r){
                                    echo '<option value="'.$r.'">'.ucwords($r).'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>

            <?php if ($role != 'church leader') { ?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label  class="form-label">Send Type</label>
                        <select class="js-select2" data-search="on" name="send_type" id="send_type" required>
                            <option value="individual"> Individual Church</option>
                            <option value="general" >General Church</option>
                        </select>
                    </div>
                </div>
        
                <div class="col-sm-6 mb-3" id="level_div" >
                    <div class="form-group">
                        <label class="form-label">Church Level</label>
                        <select class="js-select2" data-search="on" name="level" id="level">
                            <option value="">Select Church Level</option>
                            <?php

                            $log_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                            $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

                            if ($log_church_type == 'region') {

                                ?>

                                <option value="zone" <?php if (!empty($e_level)) {
                                    if ($e_level == 'zone') {
                                        echo 'selected';
                                    }
                                } ?>>Zonal Church
                                </option>
                                <option value="group" <?php if (!empty($e_level)) {
                                    if ($e_level == 'group') {
                                        echo 'selected';
                                    }
                                } ?>>Group
                                    Church</option>
                                <option value="church" <?php if (!empty($e_level)) {
                                    if ($e_level == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>Church
                                    Assembly</option>
                            <?php } elseif ($log_church_type == 'zone') { ?>

                                <option value="group" <?php if (!empty($e_level)) {
                                    if ($e_level == 'group') {
                                        echo 'selected';
                                    }
                                } ?>>Group
                                    Church</option>
                                <option value="church" <?php if (!empty($e_level)) {
                                    if ($e_level == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>Church
                                    Assembly</option>

                            <?php } elseif ($log_church_type == 'group') { ?>

                                <option value="church" <?php if (!empty($e_level)) {
                                    if ($e_level == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>Church
                                    Assembly</option>

                            <?php } else { ?>
                                <option value="region" <?php if (!empty($e_level)) {
                                    if ($e_level == 'region') {
                                        echo 'selected';
                                    }
                                } ?>>Regional
                                    Church</option>
                                <option value="zone" <?php if (!empty($e_level)) {
                                    if ($e_level == 'zone') {
                                        echo 'selected';
                                    }
                                } ?>>Zonal Church
                                </option>
                                <option value="group" <?php if (!empty($e_level)) {
                                    if ($e_level == 'group') {
                                        echo 'selected';
                                    }
                                } ?>>Group
                                    Church</option>
                                <option value="church" <?php if (!empty($e_level)) {
                                    if ($e_level == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>Church
                                    Assembly</option>
                            <?php } ?>

                        </select>
                    </div>
                </div>


                <div class="col-sm-12 mb-3" id="church_div" >
                    <div class="form-group">
                        <label class="form-label">Church</label>
                        <select class="js-select2" data-search="on" multiple name="church_id[]" id="church_id">
                            <option value="">Select</option>

                        </select>
                    </div>
                </div>

            <?php } ?>
            
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label  class="form-label" for="activate"><?=translate_phrase('Subject');?></label>
                    <input type='text' class="form-control" name='subject' id='subject' required>
                </div>
            </div>
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label  class="form-label" for="activate"><?=translate_phrase('Message');?></label>
                    <textarea class="form-control"name='message' id='message' rows="5" required></textarea>
                </div>
            </div>


            <div class="col-sm-12 mt-3 text-center">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-send"></i> <?=translate_phrase('Send Message');?>
                </button>
            </div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>
<script>
    $('.js-select2').select2();
    $(document).ready(function() {
        // Listen for changes on the type select element
        $('#type').change(function() {
            // Show dept_role_container if 'No - Select Department Role' is selected
            if ($(this).val() === "false") {
                $('#dept_role_container').show(500);
            } else {
                $('#dept_role_container').hide(500);
            }
        });
        
        $('#ministry_id, #level').change(function() {
            // Update the stored values
            var ministry_id = $('#ministry_id').val();
            var level = $('#level').val();
            var log_church_id = $('#log_church_id').val();
            
            // Check if both ministry_id and level are not empty
            if (ministry_id && level) {
                $('#church_id').empty();
                
                $.ajax({
                    url: site_url + 'accounts/dept/getChurch',
                    type: 'post',
                    data: { ministry_id: ministry_id, level: level, log_church_id: log_church_id },
                    success: function(data) {
                        $('#church_id').html(data);
                    },
                    error: function(error) {
                        console.error("Error fetching church data:", error);
                    }
                });
            }
        });

        let roleIndex = $("#container .input-group").length; // Tracks number of roles

        // Add more roles button functionality
        $('#addMore').on('click', function() {
            roleIndex++; // Increment role index
            const newRole = `
                <div class="col-sm-12 mb-3">
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="role" placeholder="Enter Department Roles" name="roles[]" required>
                        <button class="btn btn-icon btn-outline-danger deleteBtn" type="button"><i class="icon ni ni-trash"></i></button>
                    </div>
                </div>`;
            
            $('#container').append(newRole); // Append new role input group
        });

        // Remove role input functionality
        $('#container').on('click', '.deleteBtn', function() {
            $(this).closest('.col-sm-12').remove(); // Remove the closest role container
        });
    });
</script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script