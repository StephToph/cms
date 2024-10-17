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
        
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label">Year</label><br>
                <select id="year" name="year" class="js-select2" required>
                    <option value="">-- Select Year --</option>
                    <?php
                        $currentYear = date("Y"); // Get the current year
                        for ($year = 2023; $year <= $currentYear; $year++) {
                            $sel = '';
                            if (!empty($e_year) && $e_year == $year) {
                                $sel = 'selected';
                            }
                            echo '<option value="' . $year . '" ' . $sel . '>' . $year . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label">Quarter</label><br>
                <select id="quarter" name="quarter" class="js-select2" required>
                    <option value="">-- Select Quarter --</option>
                    <?php
                        $quarters = ['Q1' => 'January - March', 'Q2' => 'April - June', 'Q3' => 'July - September', 'Q4' => 'October - December'];
                        foreach ($quarters as $key => $value) {
                            $sel = '';
                            if (!empty($e_quarter) && $e_quarter == $key) {
                                $sel = 'selected';
                            }
                            echo '<option value="' . $key . '" ' . $sel . '>' . $value . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>


        <div class="col-sm-6 mb-3">
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


        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label">End Date</label>
                <div class="form-control-wrap">
                    <input type="text" data-date-format="yyyy-mm-dd" name="end_date" id="end_dates"
                        class="form-control date-picker" value="<?php if (!empty($e_end_date)) {
                            echo date('Y-m-d', strtotime($e_end_date));
                        } ?>">
                </div>

            </div>
        </div>
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label class="form-label">Location</label>
                <div class="form-control-wrap">
                    <input type="text"  name="location" id="location" class="form-control" value="<?php if (!empty($e_location)) {  echo $e_location;  } ?>">
                </div>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label class="form-label">Day(s) of the Week and Time</label>
                <div id="day-time-fields">
                    <div class="row day-time-field mb-2">
                        <!-- Day Select Field -->
                        <div class="col-sm-6">
                            <select name="days[]" class="js-select2" required>
                                <option value="">-- Select Day --</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>

                        <!-- Time Input Field -->
                        <div class="col-sm-6">
                            <input type="text" class="form-control time-picker" name="times[]" placeholder="Enter Time" required
                                value="<?php if (!empty($e_times)) {
                                    echo date('h:i A', strtotime($e_times));
                                } ?>">
                        </div>
                    </div>
                </div>

                <!-- Button to add more day-time fields -->
                <button type="button" id="add-day-time" class="btn btn-primary mt-2">Add Another Day & Time</button>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label  class="form-label" for="is_joint">Is it a Joint Class?</label>
                <select class="js-select2" data-search="on" id="is_joint" name="is_joint" required>
                    <option value="0"<?php if (!empty($e_is_joint)) {
                        if ($e_is_joint == 0) {
                            echo 'selected';
                        }
                    }
                    ; ?>>No (My Church Only)</option>
                    <option value="1"<?php if (!empty($e_is_joint)) {
                        if ($e_is_joint == 1) {
                            echo 'selected';
                        }
                    }
                    ; ?>>Yes (Joint Class)</option>
                </select>
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

        <div class="col-sm-6 mb-3 joint_resp" style="display:none;">
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

        <div class="col-sm-6 mb-3 joint_resp" style="display:none;">
            <div class="form-group">
                <label  class="form-label">Church</label>
                <select class="js-select2" data-search="on" name="church_id" id="church_id">
                    <option value="">Select</option>

                </select>
            </div>
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
        $('.js-select2').select2();
        $('.time-picker').timepicker({});
        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd', // Set the date format
            autoclose: true
        });

        $('.input-daterange').datepicker({
            format: 'yyyy-mm-dd',  // Format for the date
            autoclose: true,       // Automatically close the picker when a date is selected
            todayHighlight: true,  // Highlight today's date
        });

        $('#summernote').summernote({
            height: 300, // Set the height of the editor
            tabsize: 2,
            focus: true
        });

       
    });
    
    var site_url = '<?php echo site_url(); ?>'; 

    $('#is_joint').on('change', function () {
        var selectedType = $(this).val();
        if (selectedType == '1') {
            $('.joint_resp').show(500);
        }else{
            $('.joint_resp').hide(500);
        }
    });
    $('#is_joint').trigger('change');
    $(document).ready(function() {
        // Event listener for adding a new day-time field
        $('#add-day-time').click(function() {
            // Create a new day-time field container
            var newField = `
                <div class="row day-time-field mb-2">
                    <!-- Day Select Field -->
                    <div class="col-sm-6">
                        <select name="days[]" class="js-select2" required>
                            <option value="">-- Select Day --</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
            
                    <!-- Time Input Field -->
                    <div class="col-sm-6">
                        <div class="form-control-wrap">    
                            <div class="input-group">        
                                <input type="text" class="form-control time-picker" name="times[]" placeholder="Enter Time" required>      
                                <div class="input-group-append">            
                                    <button type="button" class="btn btn-danger btn-icon remove-day-time"><em class="icon ni ni-trash"></em></button>       
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Append the new field to the container
            $('#day-time-fields').append(newField);

            // Reinitialize plugins
            $('.time-picker').timepicker({});
            $('.js-select2').select2();
        });

        // Event listener to remove a day-time field
        $(document).on('click', '.remove-day-time', function() {
            // Traverse up to the parent with class 'day-time-field' and remove it
            $(this).closest('.day-time-field').remove();
        });
    });


</script>