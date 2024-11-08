
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

    
    <?php if ($param2 == 'view') { ?>
        <div class="row">
            <div class="col-sm-12 mb-3">
                <?php
                    
                    $service_date = $this->Crud->read_field('id',  $e_service_id, 'service_report', 'date');
                    $sections = json_decode($this->Crud->read_field('id',  $e_template_id, 'service_template', 'sections'), true);
                    usort($sections, function($a, $b) {
                        return $a['priority'] - $b['priority'];
                    });
                    $anchors = json_decode($e_anchors);
                    $durations = json_decode($e_durations);
                    $total_duration = 0;
                    if (!empty($durations)) {
                        foreach ($durations as $key => $value) {
                            if ($value->section) {
                                $total_duration += (int)$value->duration;
                                
                            }
                        }
                    }
                    $totals = $this->Crud->convertMinutesToTime($total_duration);
                    
                ?>
                <h5 class="text-center text-dark mb-2"><?= ucwords($this->Crud->read_field('id', $e_church_id, 'church', 'name').' Service Program - ').strtoupper(date('l jS M Y', strtotime($service_date))).' {'.$totals.'}'; ?></h5>
                <br>
                <div class="my-2">
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered  table-hover">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>ACTIVITY</th>
                                    <th>TIME (<?=$totals; ?>)</th>
                                    <th>COORDINATOR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if (!empty($sections)) {
                                        // Set initial start time for the program (e.g., 9 AM)
                                        // $start_time = '09:00 AM'; // Replace with your actual start time
                                        $current_time = strtotime($e_start_time); // Convert start time to a timestamp
                                    
                                        foreach ($sections as $sect) {
                                            $dur = 0;
                                            $coord = '';
                                    
                                            // Search for matching section in anchors (coordinator)
                                            if (!empty($anchors)) {
                                                foreach ($anchors as $key => $value) {
                                                    if ($value->section === $sect['section']) {
                                                        $coord = $value->anchor;
                                                        break;
                                                    }
                                                }
                                            }
                                    
                                            // Search for matching section in durations
                                            if (!empty($durations)) {
                                                foreach ($durations as $key => $value) {
                                                    if ($value->section === $sect['section']) {
                                                        $dur = $value->duration;
                                                        break;
                                                    }
                                                }
                                            }
                                    
                                            // Convert minutes to seconds and add to current time
                                            $duration_in_seconds = $dur * 60;
                                             // Calculate the end time by adding duration to current start time
                                            $end_time = $current_time + $duration_in_seconds;

                                             // Format the start and end times
                                            $formatted_start_time = date('h:i A', $current_time);
                                            $formatted_end_time = date('h:i A', $end_time);

                                            // Output the row
                                            echo '
                                                <tr>
                                                    <td>'.ucwords($sect['priority']).'</td>
                                                    <td style="white-space:normal;">'.ucwords($sect['section']).'</td>
                                                    <td>'.$formatted_start_time.' - '.$formatted_end_time.' ('.$this->Crud->convertMinutesToTime($dur).')</td>
                                                    <td>'.ucwords($coord).'</td>
                                                </tr>
                                            ';

                                            $current_time = $end_time;
                                        }
                                    } else{

                                        echo '
                                            <tr><td colspan="5">NO ACTIVITY</td></tr>
                                        ';
                                    }
                                
                                ?>
                            </tbody>
                        </table>
                    </div>
                   

                    <p><?= ucwords(($e_notes)); ?></p>
                </div>
            </div>

            <div class="mb-3">
                <h6></h6>
                <div class="mt-2 row">
                    
                </div>
            </div>
        </div>

    <?php } ?>

    <?php if ($param2 == 'download') { ?>
        <style>
            #content p, #content div {
                page-break-inside: avoid; /* Avoids breaking paragraphs and divs across pages */
            }
        </style>
        <div class="col-sm-12 my-2 text-center">
            <button class="btn btn-danger text-uppercase" id="downloadBtn" type="button">
                <i class="icon ni ni-download"></i> <?=translate_phrase('Download as PDF');?>
            </button>
        </div>
        <div id="msg"></div>
        <div class="row" id="content">
            <div class="col-sm-12 mb-3">
                <?php
                    
                    $service_date = $this->Crud->read_field('id',  $e_service_id, 'service_report', 'date');
                    $sections = json_decode($this->Crud->read_field('id',  $e_template_id, 'service_template', 'sections'), true);
                    usort($sections, function($a, $b) {
                        return $a['priority'] - $b['priority'];
                    });
                    $anchors = json_decode($e_anchors);
                    $durations = json_decode($e_durations);
                    $total_duration = 0;
                    if (!empty($durations)) {
                        foreach ($durations as $key => $value) {
                            if ($value->section) {
                                $total_duration += (int)$value->duration;
                                
                            }
                        }
                    }
                    $totals = $this->Crud->convertMinutesToTime($total_duration);
                    
                ?>
                <h5 class="text-center text-dark mb-2"><?= ucwords($this->Crud->read_field('id', $e_church_id, 'church', 'name').' Service Program - ').strtoupper(date('l jS M Y', strtotime($service_date))).' {'.$totals.'}'; ?></h5>
                <br>
                <div class="my-2 mb-5">
                    <div class="col-12 table-responsive">
                        <table class="table  table-bordered   table-hover">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>ACTIVITY</th>
                                    <th>TIME (<?=$totals; ?>)</th>
                                    <th>COORDINATOR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if (!empty($sections)) {
                                        // Set initial start time for the program (e.g., 9 AM)
                                        // $start_time = '09:00 AM'; // Replace with your actual start time
                                        $current_time = strtotime($e_start_time); // Convert start time to a timestamp
                                    
                                        foreach ($sections as $sect) {
                                            $dur = 0;
                                            $coord = '';
                                    
                                            // Search for matching section in anchors (coordinator)
                                            if (!empty($anchors)) {
                                                foreach ($anchors as $key => $value) {
                                                    if ($value->section === $sect['section']) {
                                                        $coord = $value->anchor;
                                                        break;
                                                    }
                                                }
                                            }
                                    
                                            // Search for matching section in durations
                                            if (!empty($durations)) {
                                                foreach ($durations as $key => $value) {
                                                    if ($value->section === $sect['section']) {
                                                        $dur = $value->duration;
                                                        break;
                                                    }
                                                }
                                            }
                                    
                                            // Convert minutes to seconds and add to current time
                                            $duration_in_seconds = $dur * 60;
                                             // Calculate the end time by adding duration to current start time
                                            $end_time = $current_time + $duration_in_seconds;

                                             // Format the start and end times
                                            $formatted_start_time = date('h:i A', $current_time);
                                            $formatted_end_time = date('h:i A', $end_time);

                                            // Output the row
                                            echo '
                                                <tr>
                                                    <td>'.ucwords($sect['priority']).'</td>
                                                    <td>'.ucwords($sect['section']).'</td>
                                                    <td>'.$formatted_start_time.' - '.$formatted_end_time.' ('.$this->Crud->convertMinutesToTime($dur).')</td>
                                                    <td>'.ucwords($coord).'</td>
                                                </tr>
                                            ';

                                            $current_time = $end_time;
                                        }
                                    } else{

                                        echo '
                                            <tr><td colspan="5">NO ACTIVITY</td></tr>
                                        ';
                                    }
                                
                                ?>
                            </tbody>
                        </table>
                    </div>
                   

                    <p class="mt-5"><?= ucwords(($e_notes)); ?></p>
                </div>
            </div>
        </div>
        
    <?php } ?>

    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
        <input type="hidden" name="edit_id" id="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
        <input type="hidden" name="service_id" id="service_id" value="<?php if(!empty($e_service_id)){echo $e_service_id;} ?>" />
            
            <?php
                $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                
                $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
        
                if ($ministry_id <= 0) { ?>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label class="name">Ministry </label>
                            <select id="ministry_id" name="ministry_id" class="js-select2 "
                                onchange="load_level();">
                                <option value=" ">Select Ministry</option>

                            </select>
                        </div>
                    </div>
                <?php } else { ?>
                    <input type="hidden" name="ministry_id" id="ministry_id" value="<?= $ministry_id; ?>">
                    <input type="hidden" name="church_id" value="<?php echo $church_id; ?>">
                <?php } ?>
                <?php if ($role != 'church leader') { ?>
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
                
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label>Church</label>
                            <select class="js-select2" data-search="on" name="church_id" id="church_ids">
                                <option value="">Select</option>

                            </select>
                        </div>
                    </div>

                <?php } ?>
            
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?= translate_phrase('Service Type'); ?></label>
                    <select data-search="on" class=" js-select2" id="type" name="service_type" required>
                        <option value="">Select</option>
                        <?php
                        $type = $this->Crud->read_order('service_type', 'name', 'asc');
                        if (!empty($type)) {
                            foreach ($type as $t) {
                                $sel = '';
                                if (!empty($e_service_type)) {
                                    if ($e_service_type == $t->id) {
                                        $sel = 'selected';
                                    }
                                }
                                echo '<option value="' . $t->id . '" '.$sel.'>' . ucwords($t->name) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label class="name">*Service Date</label>
                    <div class="form-control-wrap">
                        <input type="text" name="service_dates" id="dates"
                            class="form-control date-picker" required  value="<?php if (!empty($e_service_date)) {
                            echo date('m/d/y', strtotime($e_service_date));
                        } ?>">
                    </div>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label>Template</label>
                    <select class="js-select2" data-search="on" name="template_id" id="template_id" required>
                        <option value="">Select Template</option>
                        <?php

                            $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                            $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                            $templates = $this->Crud->read_order('service_template', 'name', 'asc');
                            if($ministry_id > 0){
                                $templates = $this->Crud->read_single_order('ministry_id', $ministry_id, 'service_template', 'name', 'asc');
                            }

                            if (!empty($templates)) {
                                foreach ($templates as $d) {
                                    if($role != 'developer' && $role !='administrator' && $role != 'ministry administrator'){
                                        if($d->type != 'all' && $d->church_id != $church_id){
                                            continue;
                                        }
                                    }
                                    $sel = '';
                                    if (!empty($e_template_id)) {
                                        if ($e_template_id == $d->id) {
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

            <div class="col-sm-6 mb-3">
                <div class="form-group">    
                    <label class="form-label">Start Time</label>    
                    <div class="form-control-wrap">        
                        <input type="text" class="form-control time-picker" name="start_time" placeholder="Enter Time" value="<?php if (!empty($e_start_time)) {
                            echo date('h:iA', strtotime($e_start_time));
                        } ?>">    
                    </div>
                </div>
            </div>
            
           
            <div class="col-sm-12 mb-3 table-responsive" style="display:none;" id="section_resp">
                <label>Section</label><br>
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Priority</th>
                            <th>Duration(min)</th>
                            <th>Coordinator</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        
                    </tbody>
                </table>

            </div>

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">Notes</label>
                    <textarea id="summernote" class="form-control" name="notes" rows="5"><?php if (!empty($e_notes)) {
                        echo $e_notes;
                    } ?></textarea>
                </div>
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

    <?php if($param2 == 'send_email') { ?>
        <input type="hidden" name="edit_id" id="edit_id" value="<?php if(!empty($param3)){echo $param3;} ?>" />
        <div class="row">
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label>Emails</label>
                    <div id="email-container">
                        <div class="input-group mb-2">
                            <input type="email" class="form-control" name="emails[]" placeholder="Enter email" required>
                            <button class="btn btn-outline-danger remove-email" type="button" style="display:none;">Remove</button>
                        </div>
                    </div>
                    <button class="btn btn-info" type="button" id="add-email">Add More Emails</button>
                </div>
            </div>
            
            <div class="col-sm-12 text-center mt-3">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-share"></i> <span><?=translate_phrase('Send');?></span>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

    <?php } ?>

    
<?php echo form_close(); ?>
<?php if ($param2 == 'download') { ?>
    
<?php } ?>
<script>
    $('.js-select2').select2();
    $(function () {
        $('#summernote').summernote({
            height: 300, // Set the height of the editor
            tabsize: 2,
            focus: true
        });
        $('.time-picker').timepicker({});
        $('.date-picker').datepicker({
            dateFormat: "mm/dd/yy", // You can customize the date format
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+10"
        });

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


    <?php if($param2 == 'download'){?>
        document.getElementById('downloadBtn').addEventListener('click', function() {
            $('#msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            var element = document.getElementById('content');
            html2pdf()
                .from(element)
                .set({
                    margin: 0.5, // Reduce margin to maximize page usage
                    filename: 'content.pdf',
                    html2canvas: {
                        scale: 1, // Keeps original quality without excessive scaling
                        useCORS: true // Handles cross-origin issues if images are present
                    },
                    jsPDF: {
                        orientation: 'portrait',
                        unit: 'in',
                        format: 'a4', // Use A4 for better international support; use 'letter' if needed
                        compressPDF: true // Enable compression for smaller file size
                    }
                })
                .save()
                .finally(() => {
                    $('#msg').html('');
                }
            );
        });
    <?php } ?>
    $(document).ready(function() {
        $('#add-email').click(function() {
            // Create a new input group for the email
            var newEmailGroup = `
                <div class="input-group mb-2">
                    <input type="email" class="form-control" name="emails[]" placeholder="Enter email" required>
                    <button class="btn btn-outline-danger remove-email" type="button">Remove</button>
                </div>
            `;
            // Append the new email input group to the container
            $('#email-container').append(newEmailGroup);
        });

        // Use event delegation to handle the remove button click
        $('#email-container').on('click', '.remove-email', function() {
            $(this).closest('.input-group').remove();
        });
    });


    $(document).ready(function() {
        // Function to handle the template change
        function handleTemplateChange() {
            let templateId = $('#template_id').val();
            let eid = $('#edit_id').val();
            $('#section_resp').hide(500);
            // Check if templateId is not empty
            if (templateId) {
                // Perform AJAX request to fetch sections
                $.ajax({
                    url: site_url + 'church/service/get_sections_by_template', // Replace with your actual URL
                    type: 'POST',
                    data: {
                        template_id: templateId,
                        edit_id: eid
                    },
                    success: function(response) {
                        // Parse the response (assuming JSON format)
                        let sections = JSON.parse(response);
                        
                        // Clear any existing rows in the table body
                        $('#table-body').empty();

                        if (sections.length > 0) {
                            // Check if it's in edit mode (you'll need a flag or data attribute for this)
                            let isEdit = false; // Change this based on your logic

                            // Loop through the fetched sections and populate the table
                            sections.forEach(function(section, index) {
                               
                                let row = `
                                    <tr>
                                        <td><input type="text" name="section_name[]" value="${section.name}" class="form-control" readonly></td>
                                        <td width="80px"><input type="number" name="priority[]" value="${section.priority}" class="form-control" readonly></td>
                                        <td width="70px"><input type="number" name="duration[]" class="form-control priority-input" value="${section.duration}" min="1" max="200"></td>
                                        <td><input type="text" name="coordinator[]" class="form-control" value="${section.anchor}"></td>
                                    </tr>
                                `;
                                $('#table-body').append(row);
                            });

                            // Show the section table
                            $('#section_resp').show(500);
                        } else {
                            // If no sections, hide the table
                            $('#section_resp').hide(500);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle the error
                        console.error("An error occurred: " + error);
                    }
                });
            } else {
                // Hide the section table if no template is selected
                $('#section_resp').hide(500);
            }
        }

        // Trigger the template change event on load
        $('#template_id').change(handleTemplateChange).trigger('change');

        $('#template_id').change(function() {
            handleTemplateChange();
        });
   


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

    });
</script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
