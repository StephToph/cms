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
                        class="lead-text"><?= ucwords($this->Crud->read_field('id', $e_from_id, 'user', 'firstname')); ?></span>
                    <span class="sub-text"><?= $e_reg_date; ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 mb-3">
            <h5><?= ucwords($e_type); ?> Announcement</h5>
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
                if ($e_type == 'department') { ?>
                    <div class="col-sm-6 mb-2">
                        <div class="user-card">
                            <div class="user-info">
                                <span
                                    class="lead-text"><?= ucwords($this->Crud->read_field('id', $e_dept_id, 'dept', 'name')); ?>
                                    Department</span>
                            </div>
                        </div>
                    </div>

                <?php } else {
                    echo '<p class="text-info">Users with</p>';
                    if (!empty($e_role_id)) {
                        foreach (json_decode($e_role_id) as $rec => $va) {
                            ;
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
            
            <div class="col-sm-6 mb-3" id="send_resp" style="display:none;">
                <div class="form-group">
                    <label>Send Type</label>
                    <select class="js-select2" data-search="on" name="send_type" id="send_type" required>
                        <option value="general" <?php if (!empty($e_send_type)) {
                            if ($e_send_type == 'general') {
                                echo 'selected';
                            }
                        }
                        ; ?>>General Church</option>
                        <option value="individual" <?php if (!empty($e_send_type)) {
                            if ($e_send_type == 'individual') {
                                echo 'selected';
                            }
                        }
                        ; ?>>
                            Individual Church</option>
                        
                    </select>
                    <span id="send_text" class="text-danger small">
                        <?php  if (!empty($e_send_type)) {
                            if($e_send_type == 'general'){
                                echo 'You are Sending this Announcement to all churches under the Selected Churches';
                            } else {
                                echo 'You are Sending this Announcement to only Selected Churches';
                            }
                        } else{
                            echo 'You are Sending this Announcement to all churches under the Selected Churches';
                        }
                        ?>
                        </span>
                </div>
            </div>
        
            <div class="col-sm-12 mb-3" id="church_div" style="display:none;">
                <div class="form-group">
                    <label>Church</label>
                    <select class="js-select2" data-search="on" multiple name="church_id[]" id="church_id">
                        <option value="">Select</option>

                    </select>
                </div>
            </div>

        <?php } ?>


        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label>Announcement Type</label>
                <select class="js-select2" data-search="on" name="type" id="type" required>
                    <option value="general" <?php if (!empty($e_type)) {
                        if ($e_type == 'general') {
                            echo 'selected';
                        }
                    }
                    ; ?>>General
                        Announcement</option></option>
                    <option value="department" <?php if (!empty($e_type)) {
                        if ($e_type == 'department') {
                            echo 'selected';
                        }
                    }
                    ; ?>>
                        Department Announcement</option>
                    
                </select>
            </div>
        </div>
        <div class="col-sm-6 mb-3" id="dept_resp" style="display: none;">
            <div class="form-group">
                <label>Department</label>
                <select class="js-select2" data-search="on" name="dept_id" id="dept_id">
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
            <!-- <div class="form-group">
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
            </div> -->
        </div>

        <div class="col-sm-12 text-center">
            <hr />
            <button class="btn btn-primary bb_for_btn" id="bt" type="submit">
                <i class="icon ni ni-save"></i> Save
            </button>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-12 my-3">
            <div id="bb_ajax_msg"></div>
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

        $('#church_id').select2({
            placeholder: 'Select Church(s)',
            allowClear: true  // This allows clearing the selection if needed
        });
    });


    var site_url = '<?php echo site_url(); ?>';

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

        $('#send_type').on('change', function (){
            var  selectedType = $(this).val();
            if(selectedType == 'general'){
                $('#send_text').html('You are Sending this Announcement to all churches under the Selected Churches');  
            }
            if(selectedType == 'individual'){
                $('#send_text').html('You are Sending this Announcement to only Selected Churches');  
            }

        });

        $('#roles_id').on('change', function () {
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


    $(document).ready(function () {
        <?php
            $e_church_ids = !empty($e_church_id) ? json_encode(explode(',', $e_church_id)) : '[]';
        ?>
        var eChurchId = <?php echo $e_church_ids; ?>;
        if (typeof eChurchId === 'string') {
            eChurchId = JSON.parse(eChurchId); // Parse JSON string to array
        }
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
                                var selected = eChurchId.includes(church.id);
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

</script>