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
            <input type="hidden" name="d_id" value="<?php if (!empty($d_id)) {
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
        
        <div class="col-sm-12 mb-3 table-responsive">
            <table class="table table-hovered">
                <?php if(!empty($e_image)){?>
                    <tr>
                        <td><img src="<?=site_url($e_image); ?>" alt=""> </td>
                    </tr>
                <?php }
                        ?>
                <tr>
                    <td><h5 class="text-center text-info"><?= ucwords($e_title); ?></h5></td>
                </tr>
                <tr>
                    <td><?= ucwords(($e_description)); ?></d></td>
                </tr>
            </table>
            <table class="table table-hovered">
                <tr>
                    <td><b>Minstry</b></td>
                    <td><?=$this->Crud->read_field('id', $e_ministry_id, 'ministry', 'name');?></td>
                </tr>
                <tr>
                    <td><b>Event is For</b></td>
                    <td><?=ucwords($e_event_for);?> Church</td>
                </tr>
                <tr>
                    <td><b>Church Level</b></td>
                    <td><?=ucwords($e_church_type);?> Level</td>
                </tr>
                <?php if($e_church_type != 'all'){?>
                <tr>
                   
                    <td><b>Church</b></td>
                    <td><?php 
                        $church = '';
                        if(!empty($e_church_id)){
                            $churches = json_decode($e_church_id);
                            if(!empty($churches)){
                                foreach($churches as $c => $val){
                                    $church .=  ucwords($this->Crud->read_field('id', $val, 'church', 'name')).', ';
                                }
                            }
                        }
                        echo rtrim($church,', ');
                       
                    ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td><b>Event Type</b></td>
                    <td><?=ucwords($e_event_type);?></td>
                </tr>
                <?php if($e_event_type == 'recurring'){?>
                    <tr>
                        <td><b>Recurrene Pattern</b></td>
                        <td><?=ucwords($e_recurrence_pattern);?></td>
                    </tr>
                    <tr>
                        <td><b>Pattern</b></td>
                        <td><?php 
                            $pattern = '';
                            if($e_recurrence_pattern == 'weekly'){
                                $pattern = 'Every '.$e_pattern;
                            }
                            if($e_recurrence_pattern == 'monthly'){
                                $pattern = 'Every '.$this->Crud->numberToOrdinal((int)$e_pattern).' of the Month';
                            }
                            if($e_recurrence_pattern == 'yearly'){
                                $pattern = 'Every '.$this->Crud->numberToMonth($e_pattern).' of the Year';
                            }
                            echo $pattern;?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><b>Start Date</b></td>
                    <td><?=date('d F Y', strtotime($e_start_date)).' '.date('h:i A', strtotime($e_start_time));?></td>
                </tr>
                <tr>
                    <td><b>End Date</b></td>
                    <td><?=date('d F Y', strtotime($e_end_date)).' '.date('h:i A', strtotime($e_end_time));?></td>
                </tr>
                <tr>
                    <td><b>Location</b></td>
                    <td><?=ucwords($e_location);?></td>
                </tr>
                <?php if($e_location == 'other'){?>
                <tr>
                    <td><b>Venue</b></td>
                    <td><?=ucwords($e_venue);?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td><b>Created At</b></td>
                    <td><?=date('d F Y h:i:sA', strtotime($e_created_at));?></td>
                </tr>
                <tr>
                    <td><b>Updated At</b></td>
                    <td><?=date('d F Y h:i:sA', strtotime($e_updated_at));?></td>
                </tr>
                

            </table>
        </div>

    </div>

<?php } ?>

<!-- insert/edit view -->
<?php if ($param2 == 'edit' || $param2 == '') { ?>

    <div class="row">
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name">Activity Name</label>
                <input class="form-control" type="text" id="name" name="name" value="<?php if (!empty($e_name)) {
                    echo $e_name;
                } ?>" required>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name">Description</label>
                <textarea id="summernote" class="form-control" name="content" rows="5" required><?php if (!empty($e_description)) {
                    echo $e_description;
                } ?></textarea>
            </div>
        </div>

        <div class="col-sm-3 mb-3">
            <div class="form-group">
                <label class="form-label">Start Date</label>
                <div class="form-control-wrap">
                    <input type="text" data-date-format="yyyy-mm-dd" name="start_date" id="start_date"
                        class="form-control date-picker" value="<?php if (!empty($e_start_date)) {
                            echo date('Y-m-d', strtotime($e_start_date));
                        } ?>">
                </div>

            </div>
        </div>

        <div class="col-sm-3 mb-3">
            <div class="form-group">
                <label class="form-label">Start Time</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control time-picker" name="start_time" id="start_time"
                        placeholder="Enter Time" value="<?php if (!empty($e_start_time)) {
                            echo date('h:i A', strtotime($e_start_time));
                        } ?>">
                </div>
            </div>
        </div>

        <div class="col-sm-3 mb-3">
            <div class="form-group">
                <label class="form-label">End Date</label>
                <div class="form-control-wrap">
                    <input type="text" data-date-format="yyyy-mm-dd" name="end_date" id="end_date"
                        class="form-control date-picker" value="<?php if (!empty($e_end_time)) {
                            echo date('Y-m-d', strtotime($e_end_time));
                        } ?>">
                </div>

            </div>
        </div>

        <div class="col-sm-3 mb-3">
            <div class="form-group">
                <label class="form-label">End Time</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control time-picker" name="end_time" id="end_time"
                        placeholder="Enter Time" value="<?php if (!empty($e_end_time)) {
                            echo date('h:i A', strtotime($e_end_time));
                        } ?>">
                </div>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="is_recurring">Is this activity recurring?</label>
                <select class="js-select2" data-search="on" id="is_recurring" name="is_recurring" required>
                    <option value="0">No (One-time event)</option>
                    <option value="1">Yes (Recurring event)</option>
                </select>
            </div>
            
        </div>

        <div class="col-sm-6 mb-3 recurring_options" id="" style="display:none;">
            <div class="form-group">
                <label>Recurring Frequency</label>
                <select class="js-select2" data-search="on" name="frequency" id="frequency">
                    <option value="daily" <?php if (!empty($e_frequency)) {
                        if ($e_frequency == 'daily') {
                            echo 'selected';
                        }
                    }
                    ; ?>>Daily</option>
                    </option>
                    <option value="weekly" <?php if (!empty($e_frequency)) {
                        if ($e_frequency == 'weekly') {
                            echo 'selected';
                        }
                    }
                    ; ?>>Weekly</option>
                    </option>
                    <option value="monthly" <?php if (!empty($e_frequency)) {
                        if ($e_frequency == 'monthly') {
                            echo 'selected';
                        }
                    }
                    ; ?>>Monthly</option>
                    </option>
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3 recurring_options" id="" style="display:none;">
            <div class="form-group">
                <label for="interval">Recurrence Interval (e.g., every 2 weeks)</label>
                <input type="number" class="form-control" id="interval" name="interval" value="1" min="1">
            </div>
        </div>



        <div class="col-sm-6 mb-3" id="weekly_days" style="display: none;">
            <div class="form-group">
                <label>Select Days (for Weekly Recurrence)</label><br>
                <select id="days_of_week" multiple name="by_day" class="js-select2">
                    <option value="">-- Select Days --</option>
                    <option value="monday" <?php if (!empty($e_by_day)) {
                        if ($e_by_day == 'monday') {
                            echo 'selected';
                        }
                    } ?>>
                        Monday</option>
                    <option value="tuesday" <?php if (!empty($e_by_day)) {
                        if ($e_by_day == 'tuesday') {
                            echo 'selected';
                        }
                    } ?>>
                        Tuesday</option>
                    <option value="wednesday" <?php if (!empty($e_by_day)) {
                        if ($e_by_day == 'wednesday') {
                            echo 'selected';
                        }
                    } ?>>Wednesday</option>
                    <option value="thursday" <?php if (!empty($e_by_day)) {
                        if ($e_by_day == 'thursday') {
                            echo 'selected';
                        }
                    } ?>>
                        Thursday</option>
                    <option value="friday" <?php if (!empty($e_by_day)) {
                        if ($e_by_day == 'friday') {
                            echo 'selected';
                        }
                    } ?>>
                        Friday</option>
                    <option value="saturday" <?php if (!empty($e_by_day)) {
                        if ($e_by_day == 'saturday') {
                            echo 'selected';
                        }
                    } ?>>
                        Saturday</option>
                    <option value="sunday" <?php if (!empty($e_by_day)) {
                        if ($e_by_day == 'sunday') {
                            echo 'selected';
                        }
                    } ?>>
                        Sunday</option>
                </select>
                
            </div>
           
        </div>

        <div class="col-sm-6 mb-3 recurring_options" id="" style="display:none;">
            <div class="form-group">
                <label for="until">Recurrence End Date</label>
                <input type="text" data-date-format="yyyy-mm-dd" name="until" id="until" class="form-control date-picker" value="<?php if (!empty($e_until)) { echo date('Y-m-d', strtotime($e_until));  } ?>">
            </div>
        </div>

        <?php
        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

        if ($ministry_id > 0) { ?>
            <input type="hidden" id="ministry_id" name="ministry_id" value="<?php echo $ministry_id; ?>">
            <input type="hidden" id="church_id" name="church_id" value="<?php echo $church_id; ?>">
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
                            <option value="all" <?php if (!empty($e_church_type)) {
                                if ($e_church_type == 'all') {
                                    echo 'selected';
                                }
                            } ?>>All Church Level</option>
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

            <div class="col-sm-6 mb-3" id="send_resp" style="display:none;">
                <div class="form-group">
                    <label>Event For</label>
                    <select class="js-select2" data-search="on" name="send_type" id="send_type" >
                        <option value="general" <?php if (!empty($e_event_for)) {
                            if ($e_event_for == 'general') {
                                echo 'selected';
                            }
                        }
                        ; ?>>General Church</option>
                        <option value="individual" <?php if (!empty($e_event_for)) {
                            if ($e_event_for == 'individual') {
                                echo 'selected';
                            }
                        }
                        ; ?>>
                            Individual Church</option>

                    </select>
                    <span id="send_text" class="text-danger small">
                        <?php if (!empty($e_event_for)) {
                            if ($e_event_for == 'general') {
                                echo 'This Event would apply to all Churches under the Selected  Region/Zone/Group/Church Assembly';

                            } else {
                                echo 'This  Event would apply to the Selected Church Only';

                            }
                        } else {
                            echo 'This Event would apply to all Churches under the Selected  Region/Zone/Group/Church Assembly';

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
        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd', // Set the date format
            autoclose: true
        });


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

    $('#is_recurring').change(function () {
        console.log($(this).val());
        if ($(this).val() == '1') {
            $('.recurring_options').show(500);
        } else {
            $('.recurring_options').hide(500);
        }
    });

    $('#frequency').change(function () {
        if ($(this).val() == 'weekly') {
            $('#weekly_days').show(500);
        } else {
            $('#weekly_days').hide(500);
        }
    });

    var site_url = '<?php echo site_url(); ?>';

    $(document).ready(function () {
        $('.time-picker').timepicker({});
        <?php
            $e_church_ids = !empty($e_church_id) ? json_encode($e_church_id) : '[]';
            
        ?>
        var eChurchId = <?php echo $e_church_ids; ?>;

    });


    $(document).ready(function () {
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
            // console.log(initialLevel);
            if (initialLevel !== ' ') {
                if (initialLevel === 'all') {
                    $('#church_div').hide(600);
                    $('#send_resp').hide(600); // Hide the Church dropdown
                } else {
                    $('#send_resp').show(600);
                    $('#church_div').show(600); // Show the Church dropdown
                }
            } else {
                $('#church_div').hide(600);
                $('#send_resp').hide(600);

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
        $('#level').change(function () {
            var selectedLevel = $(this).val();
            var selectedMinistryId = $('#ministry_id').val();

            if (selectedLevel === 'all' || selectedLevel === ' ') {
                $('#church_div').hide(600);
                $('#send_resp').hide(600); // Hide the Church dropdown
            } else {
                $('#send_resp').show(600);
                $('#church_div').show(600); // Show the Church dropdown
                loadChurches(selectedMinistryId, selectedLevel); // Load churches based on selected level
            }
        });

        // Initial check to handle the case when the page loads with a preset level
        if (initialLevel !== 'all' && initialLevel !== ' ') {
            $('#church_div').show(600); // Ensure the Church dropdown is shown if a level is selected
        } else {
            $('#church_div').hide(600); // Hide the Church dropdown if the level is 'all'
        }
    });

</script>