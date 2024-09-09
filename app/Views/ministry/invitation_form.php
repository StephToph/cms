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
                        class="lead-text"><?= ucwords($this->Crud->read_field('id', $e_from_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $e_from_id, 'user', 'surname')); ?></span>
                    <span class="sub-text"><?= $e_reg_date; ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 mb-3">
            <h5><?= ucwords($e_type); ?> Announcement to <?= ucwords($e_level); ?> Church (<?= ucwords($e_send_type); ?>)</h5>
        </div>

        <div class="col-sm-12 mb-3">
            <h5 class="text-center text-info"><?= ucwords($e_title); ?></h5>
            <div class="my-1">
                <p><?= ucwords(($e_content)); ?></p>
            </div>
        </div>

        <div class="mb-3">
            <h6></h6>
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
                    if($e_send_type == 'general'){
                        echo '<p class="text-info">Members In</p>';
                    }

                    if($e_send_type == 'individual'){
                        echo '<p class="text-info">Church</p>';
                    }
                    
                    
                    if (!empty($e_church_id)) {
                        foreach (json_decode($e_church_id) as $rec => $va) {
                            ;
                            $role = $this->Crud->read_field('id', $va, 'church', 'name');
                            $level = $this->Crud->read_field('id', $va, 'church', 'type');

                            $wors = $this->Crud->image_name($role);
                            $img = '<span>' . $wors . '</span>';

                            ?>
                            <div class="col-sm-4 mb-2">
                                <div class="user-card">
                                    <div class="user-avatar"><?= $img; ?></div>
                                    <div class="user-info">
                                        <span class="lead-text"><?= ucwords($role.' '.$level); ?> </span>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        
                    }?>

                <?php } ?>

            </div>
        </div>
    </div>

<?php } ?>

<!-- insert/edit view -->
<?php if ($param2 == 'edit' || $param2 == '') { ?>
    
    <div class="row">
        <input type="hidden" name="form_id" value="<?php if (!empty($e_id)) {
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
                <label for="name">Description</label>
                <textarea id="summernot" class="form-control" name="description" rows="5" required><?php if (!empty($e_description)) {
                    echo $e_description;
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
                                echo 'This form will apply to all churches under selected Church Level';
                            } else {
                                echo 'This form would apply to only selected Church Level';
                            }
                        } else{
                            echo 'This form will apply to all churches under selected Church Level';
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

        <div class="col-sm-12 mb-3 " >
            <div class="row card-bordered optionsa my-2 p-2">
                <h5>Field <span class="field-number">1</span></h5>
                <div class="col-sm-6 mb-3">
                    <label>Field Label</label>
                    <input class="form-control" type="text" id="label_1" name="label[]" value="<?php if (!empty($e_label)) { echo $e_label; } ?>" required>
                </div>
    
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Field Type</label>
                        <select class="form-select " name="type[]" id="type_1" required>
                            <option value="text" <?php if (!empty($e_type) && $e_type == 'text') { echo 'selected'; } ?>>Text</option>
                            <option value="single_choice" <?php if (!empty($e_type) && $e_type == 'single_choice') { echo 'selected'; } ?>>Single Choice</option>
                            <option value="multiple_choice" <?php if (!empty($e_type) && $e_type == 'multiple_choice') { echo 'selected'; } ?>>Multiple Choice</option>
                            <option value="true_false" <?php if (!empty($e_type) && $e_type == 'true_false') { echo 'selected'; } ?>>True or False</option>
                        </select>
                    </div>
                </div>
                
                <div id="options_container_1" style="display:none;">
                    <div class="col-sm-12 mb-3 option_resp" id="option_1">
                        <div class="d-flex align-items-center mb-2">
                            <label class="me-2">Option</label>
                            <input class="form-control" type="text" name="options[]" id="option_input_1">
                            <button class="btn btn-outline-danger btn-sm ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" id="delete_option_1" title="Delete Option" style="display:none;"> <i class="icon ni ni-trash"></i> </button>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3 mb-2" id="add_more_options_1" style="display:none;">
                    <label class="text-white">.</label>
                    <button class="btn btn-warning btn-dim btn-blck bb_for_btn" id="add_option_1" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Options" type="button">
                        <span>Add More Options</span><i class="icon ni ni-plus"></i>
                    </button>
                </div>
                <div class="col-sm-9 text-white my-2">.</div>
                <div class="col-sm-3 text-right my-2" id="delete_field_resp" style="display:none;">
                    <button class="btn btn-danger btn-block bb_for_btn" id="delete_field" type="button">
                        <i class="icon ni ni-trash"></i> <span>Delete Field</span>
                    </button>
                </div>
            </div>

            <div class="col-sm-6 text-center my-2">
                <hr />
                <button class="btn btn-info btn-block bb_for_btn" id="add_field" type="button">
                    <i class="icon ni ni-plus-c"></i> <span>Add More Field</span>
                </button>
            </div>

        </div>

        <div class="col-sm-12 text-center mt-4">
            <hr />
            <button class="btn btn-primary bb_for_btn" type="submit">
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

    var fieldCounter = 1;


    $(document).ready(function () {
       
        <?php
            $e_church_ids = !empty($e_church_id) ? json_encode($e_church_id) : '[]';
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
<script>
$(document).ready(function() {
    // Initialize a counter for the fields
    let fieldCounter = 1;

    // Add more field when button is clicked
    $('#add_field').click(function() {
        fieldCounter++;
        addField(fieldCounter);
    });

    // Function to add a new field
    function addField(counter) {
        const newField = `
        <div class="row card-bordered optionsa my-2 p-2">
            <h5>Field <span class="field-number">${counter}</span></h5>
            <div class="col-sm-6 mb-3">
                <label>Field Label</label>
                <input class="form-control" type="text" id="label_${counter}" name="label[]" required>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label>Field Type</label>
                    <select class="form-select" name="type[]" id="type_${counter}" required>
                        <option value="text">Text</option>
                        <option value="single_choice">Single Choice</option>
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="true_false">True or False</option>
                    </select>
                </div>
            </div>

            <div id="options_container_${counter}" style="display:none;">
                <div class="col-sm-12 mb-3 option_resp" id="option_${counter}">
                    <div class="d-flex align-items-center mb-2">
                        <label class="me-2">Option</label>
                        <input class="form-control" type="text" name="options[]" id="option_input_${counter}">
                        <button class="btn btn-outline-danger btn-sm ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" id="delete_option_${counter}" title="Delete Option" style="display:none;">
                            <i class="icon ni ni-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 mb-2" id="add_more_options_${counter}" style="display:none;">
                <label class="text-white">.</label>
                <button class="btn btn-warning btn-dim btn-blck bb_for_btn" id="add_option_${counter}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Options" type="button">
                    <span>Add More Options</span><i class="icon ni ni-plus"></i>
                </button>
            </div>
            <div class="col-sm-9 text-white my-2">.</div>
            <div class="col-sm-3 text-right my-2" id="delete_field_resp_${counter}" style="display:none;">
                <button class="btn btn-danger btn-block delete-field-btn" id="delete_field_${counter}" type="button">
                    <i class="icon ni ni-trash"></i> <span>Delete Field</span>
                </button>
            </div>
        </div>`;

        // Append the new field to the container
        $('.row.card-bordered').last().after(newField);

        // Show delete button if more than one field
        if (fieldCounter > 1) {
            $('.row.card-bordered').each(function() {
                $(this).find('[id^="delete_field_resp_"]').show(500);
            });
        }

    }

    // Delegate the click event for dynamically added delete buttons
    $(document).on('click', '.delete-field-btn', function() {
        const counter = $(this).data('counter');
        $(this).closest('.optionsa').remove();
        resetCounters();
        // Update fieldCounter to match the remaining fields
        fieldCounter = $('.optionsa').length;
        
        // Hide delete button if only one field is left
        if (fieldCounter <= 1) {
            $('.row.card-bordered').find('[id^="delete_field_resp_"]').hide(500);
        }
    });

    function resetCounters() {
        let newCounter = 1;

        $('.optionsa').each(function() {
            $(this).attr('data-counter', newCounter);
            $(this).find('.field-number').text(newCounter);

            $(this).find('[id^="label_"]').attr('id', `label_${newCounter}`);
            $(this).find('[id^="type_"]').attr('id', `type_${newCounter}`);
            $(this).find('[id^="options_container_"]').attr('id', `options_container_${newCounter}`);
            $(this).find('[id^="option_"]').attr('id', `option_${newCounter}`);
            $(this).find('[id^="option_input_"]').attr('id', `option_input_${newCounter}`);
            $(this).find('[id^="add_option_"]').attr('id', `add_option_${newCounter}`);
            $(this).find('[id^="delete_option_"]').attr('id', `delete_option_${newCounter}`);
            $(this).find('[id^="delete_field_resp_"]').attr('id', `delete_field_resp_${newCounter}`);
            $(this).find('[id^="delete_field_"]').attr('id', `delete_field_${newCounter}`);
            $(this).find('.delete-field-btn').attr('data-counter', newCounter);

            newCounter++;
        });
    }
});
</script>
