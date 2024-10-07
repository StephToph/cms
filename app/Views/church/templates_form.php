
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
                <input type="hidden" name="del_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
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
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <?php
                $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

                if ($ministry_id > 0) { ?>
                <input type="hidden" name="ministry_id" value="<?php echo $ministry_id; ?>">
                <input type="hidden" name="church_id" value="<?php echo $church_id; ?>">
                <div class="col-sm-12 mb-3">
                    <div class="form-group">
                        <label for="name">*<?=translate_phrase('Name'); ?></label>
                        <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_name)) {echo $e_name;} ?>" required>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-sm-4 mb-3">
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
                <div class="col-sm-8 mb-3">
                    <div class="form-group">
                        <label for="name">*<?=translate_phrase('Name'); ?></label>
                        <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_name)) {echo $e_name;} ?>" required>
                    </div>
                </div>
            <?php } ?>

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">Description</label>
                    <textarea id="summernote" class="form-control" name="description" rows="5"><?php if (!empty($e_description)) {
                        echo $e_description;
                    } ?></textarea>
                </div>
            </div>
           
            <div class="col-sm-12 mb-3 table-responsive" >
                <label>Section</label><br>
                <span class="text-danger">Please enter the section of the service, such as the Opening Prayer, Worship, etc., and specify the corresponding priority based on the order of events.<br><b>Ensure that each priority is unique and not repeated.</b></span>
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Priority</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="section[]" placeholder="Opening Prayer">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input class="form-control priority-input" type="number" name="priority[]" placeholder="1"  min="1" max="20">
                                </div>
                            </td>
                            <td align="right">
                                <button class="btn btn-outline-danger btn-sm ms-2 delete-btn" type="button" data-bs-toggle="tooltip" data-bs-placement="top"  title="Delete Section" disabled>
                                    <i class="icon ni ni-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <button class="btn btn-info btn-block bb_for_btn" id="add_field" type="button">
                                    <i class="icon ni ni-plus-c"></i> <span>Add Field</span>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                    
                </table>

            </div>
        </div>
        

        <div class="row" >
            
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

    
    <!-- insert/edit view -->
    <?php if($param2 == 'extend') { ?>
        
        
        <div class="row">
            <input type="hidden" name="template_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <?php
            
                $ministry_id = $e_ministry_id;
                $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                echo '<input type="hidden" id="ministry_id" name="ministry_id" value="'.$ministry_id.'">
                ';
                if ($ministry_id > 0 && $church_id > 0) { ?>
                <input type="hidden" name="church_id" value="<?php echo $church_id; ?>">
                
            <?php } else { ?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Church Level</label>
                        <select class="js-select2" data-search="on" name="level" id="level">
                            <option value=" ">Select Church Level</option>
                            <?php

                            $log_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                            $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

                            if ($log_church_type == 'region') {

                                ?>

                                <option value="zone" <?php if (!empty($e_church_type)) {
                                    if ($e_church_type == 'zone') {
                                        echo 'selected';
                                    }
                                } ?>>
                                    Zonal
                                    Church</option>
                                <option value="group" <?php if (!empty($e_church_type)) {
                                    if ($e_church_type == 'group') {
                                        echo 'selected';
                                    }
                                } ?>>Group
                                    Church</option>
                                <option value="church" <?php if (!empty($e_church_type)) {
                                    if ($e_church_type == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>
                                    Church Assembly</option>
                            <?php } elseif ($log_church_type == 'zone') { ?>

                                <option value="group" <?php if (!empty($e_church_type)) {
                                    if ($e_church_type == 'group') {
                                        echo 'selected';
                                    }
                                } ?>>Group
                                    Church</option>
                                <option value="church" <?php if (!empty($e_church_type)) {
                                    if ($e_church_type == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>
                                    Church Assembly</option>

                            <?php } elseif ($log_church_type == 'group') { ?>

                                <option value="church" <?php if (!empty($e_church_type)) {
                                    if ($e_church_type == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>
                                    Church Assembly</option>

                            <?php } else { ?>
                                <option value="region" <?php if (!empty($e_church_type)) {
                                    if ($e_church_type == 'region') {
                                        echo 'selected';
                                    }
                                } ?>>
                                    Regional Church</option>
                                <option value="zone" <?php if (!empty($e_church_type)) {
                                    if ($e_church_type == 'zone') {
                                        echo 'selected';
                                    }
                                } ?>>
                                    Zonal
                                    Church</option>
                                <option value="group" <?php if (!empty($e_church_type)) {
                                    if ($e_church_type == 'group') {
                                        echo 'selected';
                                    }
                                } ?>>Group
                                    Church</option>
                                <option value="church" <?php if (!empty($e_church_type)) {
                                    if ($e_church_type == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>
                                    Church Assembly</option>
                            <?php } ?>

                        </select>
                    </div>
                </div>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Church</label>
                        <select class="js-select2" data-search="on" name="church_id" id="church_ids">
                            <option value="">Select</option>

                        </select>
                    </div>
                </div>
            <?php } ?>

           
            <div class="col-sm-12 mb-3 table-responsive" >
                <label>Section</label><br>
                <span class="text-danger">Please enter personalised section of the service for your church, such as the Opening Prayer, Worship, etc., and specify the corresponding priority based on the order of service.<br><b>Ensure that each priority is unique and not repeated.</b></span>
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Priority</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="section[]" placeholder="Opening Prayer">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input class="form-control priority-input" type="number" name="priority[]" placeholder="1"  min="1" max="20">
                                </div>
                            </td>
                            <td align="right">
                                <button class="btn btn-outline-danger btn-sm ms-2 delete-btn" type="button" data-bs-toggle="tooltip" data-bs-placement="top"  title="Delete Section" disabled>
                                    <i class="icon ni ni-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <button class="btn btn-info btn-block bb_for_btn" id="add_field" type="button">
                                    <i class="icon ni ni-plus-c"></i> <span>Add Field</span>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                    
                </table>

            </div>
        </div>
        

        <div class="row" >
            
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
<?php echo form_close(); ?>
<script>
    $('.js-select2').select2();
    $(function () {
        // $('#summernote').summernote({
        //     height: 300, // Set the height of the editor
        //     tabsize: 2,
        //     focus: true
        // });

    });

    $(document).ready(function() {
        <?php
            $e_sections = !empty($e_section) ? json_encode($e_section) : '[]';
        ?>
        var sections = <?php echo $e_sections; ?>;
        if (typeof sections === 'string') {
            sections = JSON.parse(sections); // Parse JSON string to array
        }
        

        // Function to populate the table with initial data from the DB
        function populateTable() {
            $('#table-body').empty();  // Clear any existing rows
            sections.forEach(function(item, index) {
                var disabled = index === 0 ? 'disabled' : '';  // Disable delete for the first row
                var newRow = `<tr>
                    <td>
                        <div class="form-group">
                            <input class="form-control" type="text" name="section[]" readonly value="${item.section}" placeholder="Enter Section">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input class="form-control priority-input" type="number" name="priority[]" value="${item.priority}" placeholder="Enter Priority" min="1" max="20">
                        </div>
                    </td>
                    <td align="right">
                        <button class="btn btn-outline-danger btn-sm ms-2 delete-btn" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Section" disabled>
                            <i class="icon ni ni-trash"></i>
                        </button>
                    </td>
                </tr>`;
                $('#table-body').append(newRow);
            });
        }

        // Call the function to populate the table when the page loads
        populateTable();

        // Restrict input to numbers between min and max
         // Delegate input validation for dynamically added rows
         $(document).on('input', '.priority-input', function() {
            var min = parseInt($(this).attr('min'));
            var max = parseInt($(this).attr('max'));
            var value = $(this).val();

            // Ensure value stays within the valid range
            if (value < min || value > max || isNaN(value)) {
                $(this).val(''); // Clear the field if invalid input
            }
        });

        // Function to add a new row
        $('#add_field').click(function() {
            var newRow = `<tr>
                <td>
                    <div class="form-group">
                        <input class="form-control" type="text" name="section[]" placeholder="Worship">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                       <input class="form-control priority-input" type="text" name="priority[]" placeholder="1"  min="1" max="20">
                    </div>
                </td>
                <td align="right">
                    <button class="btn btn-outline-danger btn-sm ms-2 delete-btn" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Section">
                        <i class="icon ni ni-trash"></i>
                    </button>
                </td>
            </tr>`;

            $('#table-body').append(newRow);
        });

        // Function to delete the closest row
        $(document).on('click', '.delete-btn', function() {
            $(this).closest('tr').remove();
        });

        // Disable the delete button on the first row
        $('#table-body').find('tr:first-child .delete-btn').prop('disabled', true);
    });

    
    $(document).ready(function () {
        <?php
            $e_church_ids = !empty($e_church_id) ? $e_church_id : 0;
        ?>
        var eChurchId = <?php echo $e_church_ids; ?>;
    
        // Function to load churches based on selected ministry ID and/or level
        function loadChurches(ministryId, level) {
            // Clear the Church dropdown
            $('#church_ids').empty();
            $('#church_ids').append(new Option('Loading...', '', false, false));

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
                        $('#church_ids').empty(); // Clear 'Loading...' option

                        if (response.success) {
                            // Populate the Church dropdown with the data received
                             $.each(response.data, function (index, church) {
                                var selected = '';
                                if (church.id === eChurchId) {
                                    selected = 'selected';
                                }
                                var churchName = toTitleCase(church.name); // Convert name to title case
                                var churchType = toTitleCase(church.type); // Convert type to title case
                                $('#church_ids').append(new Option(churchName + ' - ' + churchType, church.id, selected, selected));
                             });
                         } else {
                             $('#church_ids').append(new Option('No churches available', '', false, false));
                         }
                    },
                    error: function () {
                        $('#church_ids').append(new Option('Error fetching churches', '', false, false));
                    }
                });
            } else {
                $('#church_ids').append(new Option('Please select a ministry or level', '', false, false));
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
            loadChurches(ministryId, initialLevel);
        }
        
        // Handle the change event of the Church Level dropdown
        $('#level').change(function () {
            var selectedLevel = $(this).val();
            var selectedMinistryId = $('#ministry_id').val();

            if (selectedLevel === 'all' || selectedLevel === ' ') {
               
            } else {
                loadChurches(selectedMinistryId, selectedLevel); // Load churches based on selected level
            }
        });
    });


</script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
