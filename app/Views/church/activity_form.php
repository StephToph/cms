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
    <div class="row gy-3 py-1">
        <div class="col-sm-6 mb-3">
            <h6 class="overline-title">Category</h6>
            <p id="preview-event-start"><?=ucwords($this->Crud->read_field('id', $e_category_id, 'activity_category', 'name')); ?></p>
        </div>
        <div class="col-sm-6 mb-3" id="preview-event-end-check">
            <h6 class="overline-title">Church</h6>
            <p id="preview-event-end"><?=ucwords($this->Crud->read_field('id', $e_church_id, 'church', 'name')); ?></p>
        </div>
        <div class="col-sm-4 mb-3">
            <h6 class="overline-title">Start Time</h6>
            <p id="preview-event-start"><?=$e_start_date.' '.$e_start_time; ?></p>
        </div>
        <div class="col-sm-4 mb-3" id="preview-event-end-check">
            <h6 class="overline-title">End Time</h6>
            <p id="preview-event-end"><?=$e_end_date.' '.$e_end_time; ?></p>
        </div>
        <div class="col-sm-4 mb-3" id="preview-event-end-check">
            <h6 class="overline-title">Recurring</h6>
            <p id="preview-event-end"><?php
                if($e_recurrence == 0){
                    echo 'One Time';
                } else{
                    echo 'Recurring Activity';
                }
            ?></p>
        </div>
        <?php
            if($e_recurrence > 0){?>
            <div class="col-sm-4 mb-3">
                <h6 class="overline-title">Frequency</h6>
                <p id="preview-event-start"><?=ucwords($e_frequency); ?></p>
            </div>
            <div class="col-sm-4 mb-3">
                <h6 class="overline-title">Interval</h6>
                <p id="preview-event-start"><?php
                    $fre = 'day';
                    $days = '';$daysa ='';
                    if($e_frequency == 'weekly'){
                        $fre = 'week';
                        if(!empty($e_by_day)){
                            foreach($e_by_day as $day){
                                $daysa .= ucwords($day).' ';
                            }
                        }
                    }
                    if($e_frequency == 'monthly'){
                        $fre = 'month';
                    }
                    if(!empty($daysa)){
                        $days .= '- On '.$daysa;
                    }
                   echo ucwords('Every '.$e_intervals.' '.$fre.' '.$days); ?></p>
            </div>
            <div class="col-sm-4 mb-3">
                <h6 class="overline-title">Recurrence End</h6>
                <p id="preview-event-start"><?php
                    if($e_recurrence_end == 'never'){
                        echo ucwords('Indefinitely');
                    } 
                    if($e_recurrence_end == 'after'){
                        echo ucwords('After '.$e_occurrences.' Occurrences'); 
                    }
                    if($e_recurrence_end == 'by'){
                        echo ucwords('Ends By '.date('d F, Y', strtotime($e_end_dates))); 
                    }
                 ?></p>
            </div>
        <?php } ?>


        <div class="col-sm-10" id="preview-event-description-check">
            <h6 class="overline-title">Description</h6>
            <p id="preview-event-description"><?=$e_description; ?></p>
        </div>
        <div class="col-sm-10" id="preview-event-description-check">
            <h6 class="overline-title">Members</h6>
            <div class="row" id="preview-event-description"><?php 
                if(!empty($e_member_id)){
                    foreach($e_member_id as $members){
                        $name = $this->Crud->read_field('id', $members, 'user', 'firstname').' '.$this->Crud->read_field('id', $members, 'user', 'surname');
                        $phone = $this->Crud->read_field('id', $members, 'user', 'phone');

                        echo '
                            <div class="user-toggle col-sm-3 mb-3">
                                <div class="user-avatar">
                                    <em class="icon ni ni-user-alt"></em>    
                                </div>    
                                <div class="user-info">     
                                    <div class="user-name">'.ucwords($name).'</div>    
                                    <div class="user-status text-primary">'.$phone.'</div> 
                                </div>
                            </div>
                        ';
                    }
                }
             ?>
            </div>
        </div>
    </div>
<?php } ?>

<!-- insert/edit view -->
<?php if ($param2 == 'edit' || $param2 == '') {
     $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
     $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

     ?>

    <div class="row">
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />

        <!-- <div class="col-sm-8 mb-3">
            <div class="form-group">
                <label  class="form-label" for="name">Activity Name</label>
                <input class="form-control" type="text" id="name" name="name" value="<?php if (!empty($e_name)) {
                    echo $e_name;
                } ?>" required>
            </div>
        </div> -->
        
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label" >Category</label><br>
                <select id="category_ids" name="category_id" class="js-select2" required>
                    <option value="">-- Select --</option>
                    <?php
                        $category = $this->Crud->read_order('activity_category', 'name', 'asc');
                        if($ministry_id > 0 && $church_id <= 0){
                            $category = $this->Crud->read_single_order('ministry_id', $ministry_id, 'activity_category', 'name', 'asc');
                        }
                        if($church_id > 0){
                            $category = $this->Crud->read_single_order('church_id', $church_id, 'activity_category', 'name', 'asc');
                        }
                        if (!empty($category)) {
                            foreach ($category as $d) {
                                $sel = '';
                                if (!empty($e_category_id)) {
                                    if ($e_category_id == $d->id) {
                                        $sel = 'selected';
                                    }
                                }
                                if($church_id > 0){
                                    echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name) . '</option>';
                                } else{
                                    $church = $this->Crud->read_field('id', $d->church_id, 'church', 'name');
                                    echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name.' - '.$church) . '</option>';
                                }
                                
                            }
                        }
                        ?>
                    <option value="new"> New Category</option>
                </select>
                
            </div>
           
        </div>
        <div class="col-sm-6 mb-3" id="category_resp" style="display:none;">
            <div class="form-group">
                <label class="form-label">New Category</label>
                <input type="text"  name="category" id="category" class="form-control">
                
            </div>
        </div>
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label  class="form-label" for="name">Description</label>
                <textarea id="summernote" class="form-control" name="description" rows="5" required><?php if (!empty($e_description)) {
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
                    <input type="text" data-date-format="yyyy-mm-dd" name="end_date" id="end_dates"
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
                <label  class="form-label" for="is_recurring">Is this activity recurring?</label>
                <select class="js-select2" data-search="on" id="is_recurring" name="is_recurring" required>
                    <option value="0"<?php if (!empty($e_recurrence)) {
                        if ($e_recurrence == 0) {
                            echo 'selected';
                        }
                    }
                    ; ?>>No (One-time event)</option>
                    <option value="1"<?php if (!empty($e_recurrence)) {
                        if ($e_recurrence == 1) {
                            echo 'selected';
                        }
                    }
                    ; ?>>Yes (Recurring event)</option>
                </select>
            </div>
            
        </div>

        <div class="col-sm-6 mb-3 recurring_options" id="" style="display:none;">
            <div class="form-group">
                <label class="form-label">Recurring Frequency</label>
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
                <label  class="form-label" for="interval">Recurrence Interval (e.g., every 2 weeks)</label>
                <input type="number" class="form-control" id="interval" name="interval" value="<?php if(!empty($e_intervals)){echo $e_intervals;} else{echo '1';} ?>" min="1">
            </div>
        </div>

        <div class="col-sm-6 mb-3" id="weekly_days" style="display: none;">
            <div class="form-group">
                <label class="form-label">Select Days (for Weekly Recurrence)</label><br>
                <select id="days_of_week" multiple name="by_day[]" class="js-select2">
                    <option value="">-- Select Days --</option>
                    <option value="monday" <?php if (!empty($e_by_day)) {
                        if (in_array('monday', $e_by_day)) {
                            echo 'selected';
                        }
                    } ?>>
                        Monday</option>
                    <option value="tuesday" <?php if (!empty($e_by_day)) {
                        if (in_array('tuesday', $e_by_day)) {
                            echo 'selected';
                        }
                    } ?>>
                        Tuesday</option>
                    <option value="wednesday" <?php if (!empty($e_by_day)) {
                        if (in_array('wednesday', $e_by_day)) {
                            echo 'selected';
                        }
                    } ?>>Wednesday</option>
                    <option value="thursday" <?php if (!empty($e_by_day)) {
                        if (in_array('thursday', $e_by_day)) {
                            echo 'selected';
                        }
                    } ?>>
                        Thursday</option>
                    <option value="friday" <?php if (!empty($e_by_day)) {
                        if (in_array('friday', $e_by_day)) {
                            echo 'selected';
                        }
                    } ?>>
                        Friday</option>
                    <option value="saturday" <?php if (!empty($e_by_day)) {
                        if (in_array('saturday', $e_by_day)) {
                            echo 'selected';
                        }
                    } ?>>
                        Saturday</option>
                    <option value="sunday" <?php if (!empty($e_by_day)) {
                        if (in_array('sunday', $e_by_day)) {
                            echo 'selected';
                        }
                    } ?>>
                        Sunday</option>
                </select>
                
            </div>
           
        </div>

        <div class="col-sm-6 mb-3 recurring_options" id="" style="display:none;">
            
            <label class="form-label">Recurrence End</label>
             <!-- Recurrence End -->
            <div class="g-4 align-center flex-wrap">
                <div class="g">
                    <div class="custom-control custom-control-sm custom-radio">
                        <input type="radio" class="custom-control-input" name="recurrence_end" id="end_never" value="never" <?php if (!empty($e_recurrence_end )) {
                        if ( $e_recurrence_end == 'never') {
                            echo 'checked';
                        }
                    } else{echo 'checked';} ?>>
                        <label class="custom-control-label" for="end_never">Never (Indefinite)</label>
                    </div>
                </div>
                <div class="g">
                    <div class="custom-control custom-control-sm custom-radio">
                        <input type="radio" class="custom-control-input" name="recurrence_end" id="end_after" value="after" <?php if (!empty($e_recurrence_end )) {
                        if ( $e_recurrence_end == 'after') {
                            echo 'checked';
                        }
                    } ?>>
                        <label class="custom-control-label" for="end_after">End after</label>
                        
                    </div>
                </div>
                <div class="g">
                    <div class="custom-control custom-control-sm custom-radio">
                        <input type="radio" class="custom-control-input" name="recurrence_end" id="end_by" value="by" <?php if (!empty($e_recurrence_end )) {
                        if ( $e_recurrence_end == 'by') {
                            echo 'checked';
                        }
                    }  ?>>
                        <label class="custom-control-label" for="end_by">End by</label>
                        
                    </div>
                </div>
               
            </div>
        </div>
        <div class="col-sm-6 mb-3 recurring_option" id="" style="display:none;">
            <div class="form-group" style="display: none;" id="occurrences">
                <label class="form-label">Number of Occurrences</label>
                <input type="number" class="form-control"  name="occurrences" placeholder="Enter number of occurrences" value="<?php if (!empty($e_occurrences )) {  echo $e_occurrences;  } ?>">
            </div>
            <div class="form-group" style="display: none;" id="end_date">
                <label class="form-label">Date Activity will End</label>
                <input type="text" data-date-format="yyyy-mm-dd" class="form-control  date-picker" id="" name="end_dates" value="<?php if (!empty($e_end_dates)) { echo date('Y-m-d', strtotime($e_end_dates));  } ?>">
            </div>
        </div>
        <?php
       
        if ($ministry_id > 0) { ?>
            <input type="hidden" id="ministry_id" name="ministry_id" value="<?php echo $ministry_id; ?>">
           <?php if($church_id > 0){?>

                <input type="hidden" id="church_id" name="church_id" value="<?php echo $church_id; ?>">
            <?php } ?>
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

        <?php if ($church_id == 0) { ?>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label  class="form-label">Church Level</label>
                    <select class="js-select2" data-search="on" name="level" id="level">
                        <option value=" ">Select Church Level</option>
                        <?php

                        $log_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                        $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

                        ?>
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
                        

                    </select>
                </div>
            </div>


            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label  class="form-label">Church</label>
                    <select class="js-select2" data-search="on" name="church_id" id="church_id">
                        <option value="">Select</option>

                    </select>
                </div>
            </div>

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label  class="form-label">Members</label>
                    <select class="js-select2" data-search="on" multiple name="member_id[]" id="member_id">
                        <option value="">Select</option>

                    </select>
                </div>
            </div>

           

        <?php }else{ ?>
            
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label  class="form-label">Members</label>
                    <select class="js-select2" data-search="on" multiple name="member_id[]" >
                        <option value="">Select</option>
                        <?php

                        $members = $this->Crud->read2_order('is_member', 1, 'church_id', $church_id, 'user', 'firstname', 'asc');
                        if (!empty($members)) {
                            foreach ($members as $d) {
                                $sel = '';
                                if (!empty($e_member_id)) {
                                    if ($e_member_id == $d->id) {
                                        $sel = 'selected';
                                    }
                                }
                                echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->firstname.' '.$d->surname.' - '.$d->phone) . '</option>';
                            }
                        }
                        ?>
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

       
    });

    $('input[name="recurrence_end"]').on('change', function () {
        var selected = $(this).val();
        if (selected === 'after') {
            $('#occurrences').show(500);
            $('#end_date').hide(500);
            $('.recurring_option').show(500);
        } else if (selected === 'by') {
            $('#occurrences').hide(500);
            $('#end_date').show(500);
            $('.recurring_option').show(500);
        } else {
            $('#occurrences').hide(500);
            $('#end_date').hide(500);
            $('.recurring_option').hide(500);
        }
    });
    
    $('.recurring_option').hide(500);
    // Initially hide the fields for occurrences and end date
    $('#occurrences').hide(500);
    $('#end_date').hide(500);
    $('input[name="recurrence_end"]:checked').trigger('change');
    $('#is_recurring').change(function () {
        if ($(this).val() == '1') {
            $('.recurring_options').show(500);
        } else {
            $('.recurring_options').hide(500);
        }
    });
    $('#is_recurring').trigger('change');
    $('#category_ids').change(function () {
        if ($(this).val() == 'new') {
            console.log($(this).val());
            $('#category_resp').show(500);
        } else {
            $('#category_resp').hide(500);
        }
    });
    $('#frequency').change(function () {
        if ($(this).val() == 'weekly') {
            $('#weekly_days').show(500);
        } else {
            $('#weekly_days').hide(500);
        }
    });
    $('#frequency').trigger('change');
    
    $('#member_id').select2({
        placeholder: 'Select members',
        allowClear: true,
        multiple: true,
        width: '100%'
    });
    var site_url = '<?php echo site_url(); ?>';

    $(document).ready(function () {
        $('.time-picker').timepicker({});
        <?php
            $e_church_ids = !empty($e_church_id) ? json_encode($e_church_id) : '[]';
            $e_member_ids = !empty($e_member_id) ? json_encode($e_member_id) : '[]';
            
        ?>
        var eChurchId = <?php echo $e_church_ids; ?>;
        var eMemberId = <?php echo $e_member_ids; ?>;

  
        if (typeof eChurchId === 'string') {
            eChurchId = JSON.parse(eChurchId); // Parse JSON string to array
        }
        if (typeof eMemberId === 'string') {
            eMemberId = JSON.parse(eMemberId); // Parse JSON string to array
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
                                var selected = '';
                                if (church.id === eChurchId) {
                                    selected = 'selected';
                                }
                                var churchName = toTitleCase(church.name); // Convert name to title case
                                var churchType = toTitleCase(church.type); // Convert type to title case
                                $('#church_id').append(new Option(churchName + ' - ' + churchType, church.id, selected, selected));
                            });
                            var selectedChurchId = $('#church_id').val();
                            loadMembers(selectedChurchId); // Load members when a church is selected
                        
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
               
            } else {
                loadChurches(selectedMinistryId, selectedLevel); // Load churches based on selected level
            }
        });

                // Function to load members based on selected church ID
        function loadMembers(churchId) {
            // Clear the Member dropdown
            $('#member_id').empty();
            $('#member_id').append(new Option('Loading...', '', false, false)).trigger('change');;

            // Proceed only if a churchId is provided
            if (churchId) {
                $.ajax({
                    url: site_url + 'ministry/announcement/get_members', // Update this to the path of your API endpoint
                    type: 'POST',
                    dataType: 'json',
                    data: { church_id: churchId }, // Send the selected church ID
                    success: function (response) {
                        $('#member_id').empty(); // Clear 'Loading...' option

                        if (response.success) {
                            // Populate the Member dropdown with the data received
                            $.each(response.data, function (index, member) {
                                
                                var selected = eMemberId.includes(member.id); // Pre-select if necessary
                                var memberName = toTitleCase(member.name);
                                var memberPhone = member.phone || 'N/A';      // Show phone number or 'N/A' if missing
                                // Append the member's name and phone number to the select box
                                $('#member_id').append(new Option(memberName + ' (' + memberPhone + ')', member.id, selected, selected));

                            });
                        } else {
                            $('#member_id').append(new Option('No members available', '', false, false)).trigger('change');;
                        }
                    },
                    error: function () {
                        $('#member_id').append(new Option('Error fetching members', '', false, false)).trigger('change');;
                    }
                });
            } else {
                $('#member_id').append(new Option('Please select a church', '', false, false)).trigger('change');;
            }
        }

        // Helper function to convert strings to title case
        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function (txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        // Example: You might call loadMembers when the church dropdown changes
        $('#church_id').change(function() {
            var selectedChurchId = $(this).val();
            loadMembers(selectedChurchId); // Load members when a church is selected
        });


    });

</script>