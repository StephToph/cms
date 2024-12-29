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
        <!-- Prayer Record Details -->
        <div class="col-sm-12 mb-3 table-responsive">
            <table class="table table-hovered">
                <tr>
                    <td><h5 class="text-center text-info"><?= ucwords($e_title); ?></h5></td>
                </tr>
            </table>
            <table class="table table-hovered">
                <tr>
                    <td><b>Church Level</b></td>
                    <td><?= ucwords($e_church_type); ?> Level</td>
                </tr>
                <?php if ($e_church_type != 'all') { ?>
                    <tr>
                        <td><b>Church</b></td>
                        <td>
                            <?php 
                            $church = '';
                            if (!empty($e_church_id)) {
                                foreach ($e_church_id as $id) {
                                    $church .= ucwords($this->Crud->read_field('id', $id, 'church', 'name')) . ', ';
                                }
                            }
                            echo rtrim($church, ', ');
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><b>Start Date</b></td>
                    <td><?= date('d F Y', strtotime($e_start_date)); ?></td>
                </tr>
                <tr>
                    <td><b>End Date</b></td>
                    <td><?= date('d F Y', strtotime($e_end_date)); ?></td>
                </tr>
                <tr>
                    <td><b>Duration</b></td>
                    <td><?= ($e_duration); ?> Minute(s)</td>
                </tr>
                <tr>
                    <td><b>Notification Reminder</b></td>
                    <td><?= ($e_reminder); ?> Minute(s)</td>
                </tr>
            </table>
        </div>

        <div class="col-sm-12 mb-3 table-responsive">
            <h5 class="text-info">Time Slot Management</h5>
            <table class="table table-hovered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Prayer Point</th>
                        <th>Church</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($e_assignment)) { ?>
                        <?php foreach ($e_assignment as $date => $records) { ?>
                            <?php foreach ($records as $record_key => $slot) { ?>
                                <tr>
                                    <td><?= date('d F Y', strtotime($date)); ?></td>
                                    <td><?= date('h:i A', strtotime($slot['start_time'])) . ' - ' . date('h:i A', strtotime($slot['end_time'])); ?></td>
                                    <td><?= ucfirst(strip_tags($slot['prayer'])); ?></td>
                                    <td><?= ucwords($this->Crud->read_field('id', $slot['church_id'], 'church', 'name')); ?></td>
                                    
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No time slots available.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>


    </div>
<?php } ?>

<?php if($param2 == 'time_add'){?>
    <div class="row">
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />
       <input type="hidden" name="date" value="<?php if (!empty($e_date)) {
            echo $e_date;
        } ?>" />
       
        <div class="col-sm-5 mb-3">
            <div class="form-group">
                <label class="form-label">Start Time</label>
                <div class="form-control-wrap d-flex align-items-center gap-2 mt-2">
                    <!-- Hour Dropdown -->
                    <select class="js-select2" name="hour" id="hour" style="width: 30%;">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                        <?php endfor; ?>
                    </select>

                    <!-- Minute Dropdown -->
                    <select class="js-select2" name="minute" id="minute" style="width: 30%;">
                        <?php for ($i = 0; $i < 60; $i += 1): ?>
                            <option value="<?php echo $i; ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                        <?php endfor; ?>
                    </select>

                    <!-- AM/PM Dropdown -->
                    <select class="js-select2" name="am_pm" id="am_pm" style="width: 30%;">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>

            </div>
        </div>
        
        <div class="col-sm-3 mb-3">
            <div class="form-group">
                <label class="form-label" for="name">Duration(min)</label>
                <input class="form-control" type="number" id="durationz" name="duration" 
                    value="<?php if (!empty($e_duration)) { echo $e_duration; } ?>" 
                    min="1" step="1" readonly>
            </div>
        </div>
        
        <div class="col-sm-4 mb-3">
            <div class="form-group">
                <label class="form-label">End Time</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="end_time" id="end_time"
                        readonly placeholder="Enter Time" value="<?php if (!empty($e_end_time)) {
                            echo date('h:i A', strtotime($e_end_time));
                        } ?>">
                </div>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="name">Title</label>
                <input class="form-control" type="text" id="prayer_title" name="prayer_title" 
                    value="<?php if (!empty($e_prayer_title)) { echo $e_prayer_title; } ?>" 
                    min="1" step="1">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name"  class="form-label">Prayer Point</label>
                <textarea id="summernote" class="form-control" name="prayer" rows="5"><?php if (!empty($e_prayer)) {
                    echo $e_prayer;
                } ?></textarea>
            </div>
        </div>
        <div class="col-sm-6 mb-3" >
            <div class="form-group">
                <label class="form-label">Church </label>
                <select class="js-select2" data-search="on" name="church_id" id="church_idz">
                    <?php 
                    // $ch_type = '';
                    if(!empty($e_church_id)){
                        if($e_church_type == 'region'){
                            $ch_type = 'regional_id';
                        }
                        if($e_church_type == 'zone'){
                            $ch_type = 'zonal_id';
                        }
                        if($e_church_type == 'group'){
                            $ch_type = 'group_id';
                        }
                        if($e_church_type == 'church'){
                            $ch_type = 'church_id';
                        }
                        foreach ($e_church_id as $ch) {
                            echo '<option value="'.$ch.'">'.ucwords($this->Crud->read_field('id', $ch,'church', 'name').' - '.$this->Crud->read_field('id', $ch,'church', 'type')).'</option>';

                            $load_church = $this->Crud->read_single_order($ch_type, $ch,'church', 'name','asc');
                            if(!empty($load_church)){
                                foreach($load_church as $lc){
                                    echo '<option value="'.$lc->id.'">'.ucwords($this->Crud->read_field('id', $lc->id,'church', 'name').' - '.$this->Crud->read_field('id', $lc->id,'church', 'type')).'</option>';
                                }
                            }
                        }
                    }
                    ?>

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

<?php if($param2 == 'time_edit'){?>
    
    <div class="row">
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />
       <input type="hidden" name="date" value="<?php if (!empty($e_date)) {
            echo $e_date;
        } ?>" />
         <input type="hidden" name="record_index" value="<?php if (!empty($param5)) {
            echo $param5;
        } ?>" />

        <div class="col-sm-5 mb-3">
            <div class="form-group">
                <label class="form-label">Start Time</label>
                <div class="form-control-wrap d-flex align-items-center gap-2 mt-2">
                    <!-- Hour Dropdown -->
                    <select class="js-select2" name="hour" id="hourz" style="width: 30%;">
                        <?php 
                        $start_hour = !empty($start_time) ? date('h', strtotime($start_time)) : '';
                        for ($i = 1; $i <= 12; $i++): 
                        ?>
                            <option value="<?php echo $i; ?>" <?php echo ($i == $start_hour) ? 'selected' : ''; ?>>
                                <?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                            </option>
                        <?php endfor; ?>
                    </select>

                    <!-- Minute Dropdown -->
                    <select class="js-select2" name="minute" id="minutez" style="width: 30%;">
                        <?php 
                        $start_minute = !empty($start_time) ? date('i', strtotime($start_time)) : '';
                        for ($i = 0; $i < 60; $i++): 
                        ?>
                            <option value="<?php echo $i; ?>" <?php echo ($i == $start_minute) ? 'selected' : ''; ?>>
                                <?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                            </option>
                        <?php endfor; ?>
                    </select>

                    <!-- AM/PM Dropdown -->
                    <select class="js-select2" name="am_pm" id="am_pmz" style="width: 30%;">
                        <?php 
                        $start_am_pm = !empty($start_time) ? date('A', strtotime($start_time)) : '';
                        ?>
                        <option value="AM" <?php echo ($start_am_pm == 'AM') ? 'selected' : ''; ?>>AM</option>
                        <option value="PM" <?php echo ($start_am_pm == 'PM') ? 'selected' : ''; ?>>PM</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-3 mb-3">
            <div class="form-group">
                <label class="form-label" for="name">Duration(min)</label>
                <input class="form-control" type="number" id="durationzz" name="duration" 
                    value="<?php if (!empty($e_duration)) { echo $e_duration; } ?>" 
                    min="1" step="1" readonly>
            </div>
        </div>
        <!-- End Time -->
        <div class="col-sm-4 mb-3">
            <div class="form-group">
                <label class="form-label">End Time</label>
                <input type="text" class="form-control" name="end_time" id="end_timez" 
                        value="<?php echo isset($end_time) ?  date('h:i A', strtotime($end_time)) : ''; ?>" readonly>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="name">Title</label>
                <input class="form-control" type="text" id="prayer_title" name="prayer_title" 
                    value="<?php if (!empty($prayer_title)) { echo $prayer_title; } ?>" 
                    min="1" step="1">
            </div>
        </div>
        <!-- Prayer Point -->
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label class="form-label">Prayer Point</label>
                <textarea class="form-control summernote" name="prayer" rows="4" readonly>
                    <?php echo isset($prayer) ? htmlspecialchars(strip_tags($prayer)) : ''; ?>
                </textarea>
            </div>
        </div>

        <!-- Church -->
        <div class="col-sm-6 mb-3" >
            <div class="form-group">
                <label class="form-label">Church</label>
                <select class="js-select2" data-search="on" name="church_id" id="church_idz">
                    
                    <?php 
                    // $ch_type = '';
                    if(!empty($e_church_id)){
                        if($e_church_type == 'region'){
                            $ch_type = 'regional_id';
                        }
                        if($e_church_type == 'zone'){
                            $ch_type = 'zonal_id';
                        }
                        if($e_church_type == 'group'){
                            $ch_type = 'group_id';
                        }
                        if($e_church_type == 'church'){
                            $ch_type = 'church_id';
                        }
                        foreach ($e_church_id as $ch) {
                            $select = '';
                            if($ch == $church_idz){
                                $select = 'selected';
                            }
                            echo '<option value="'.$ch.'" '.$select.'>'.ucwords($this->Crud->read_field('id', $ch,'church', 'name').' - '.$this->Crud->read_field('id', $ch,'church', 'type')).'</option>';
                           
                            $load_church = $this->Crud->read_single_order($ch_type,$ch,'church', 'name','asc');
                            if(!empty($load_church)){
                                foreach($load_church as $lc){
                                    $select = '';
                                    if($lc->id == $church_idz){
                                        $select = 'selected';
                                    }
                                    echo '<option value="'.$lc->id.'" '.$select.'>'.ucwords($this->Crud->read_field('id', $lc->id,'church', 'name').' - '.$this->Crud->read_field('id', $lc->id,'church', 'type')).'</option>';
                                }
                            }
                        }
                    }
                    ?>

                </select>
            </div>
        </div>
        

        <!-- Submit Button -->
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
<?php if ($param2 == 'time_delete') { ?>
    <div class="row">
        <div class="col-sm-12">
            <div id="bb_ajax_msg"></div>
        </div>
    </div>
    <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) {
        echo $e_id;
    } ?>" />
    <input type="hidden" name="date" value="<?php if (!empty($e_date)) {
        echo $e_date;
    } ?>" />
        <input type="hidden" name="record_index" value="<?php if (!empty($param5)) {
        echo $param5;
    } ?>" />
    
    <div class="row">
        <div class="col-sm-12 my-2 text-center">
            <h5><b>Are you sure?</b></h5>
            
        </div>

        <div class="col-sm-12 text-center">
            <button class="btn btn-danger text-uppercase" type="submit">
                <i class="icon ni ni-trash"></i> Yes - Delete
            </button>
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
                <label class="form-label" for="name">Title</label>
                <input class="form-control" type="text" id="title" name="title" value="<?php if (!empty($e_title)) {
                    echo $e_title;
                } ?>" required>
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
                    <input type="text" data-date-format="yyyy-mm-dd" name="end_date" id="end_date"
                        class="form-control date-picker" value="<?php if (!empty($e_end_date)) {
                            echo date('Y-m-d', strtotime($e_end_date));
                        } ?>">
                </div>

            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label" for="name">Prayer Duration (in minutes)</label>
                <input class="form-control" type="number" id="duration" name="duration" 
                    value="<?php if (!empty($e_duration)) { echo $e_duration; } ?>" 
                    min="1" step="1" required>
                <small class="form-text text-muted">Please enter the duration in minutes (e.g., 5, 10, 30).</small>
            </div>
        </div>
        
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label" for="name">First Reminder(min)</label>
                <input class="form-control" type="number" id="reminder" name="reminder" 
                    value="<?php if (!empty($e_reminder)) { echo $e_reminder; } ?>" 
                    min="1" step="1" required>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label" for="name">Second Reminder(min)</label>
                <input class="form-control" type="number" id="reminder2" name="reminder2" 
                    value="<?php if (!empty($e_reminder2)) { echo $e_reminder2; } ?>" 
                    min="1" step="1" required>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label">Time Zone</label>
                <?php
                    // Define the array of time zones (name => value)
                    $timeZones = [
                        "EST" => "Eastern Standard Time (EST)",
                        "CST" => "Central Standard Time (CST)",
                        "MST" => "Mountain Standard Time (MST)",
                        "PST" => "Pacific Standard Time (PST)",
                        "AKST" => "Alaska Standard Time (AKST)"
                    ];

                ?>
                <select class="js-select2" data-search="on" name="time_zone" id="time_zone">
                    <?php
                    // Loop through the $timeZones array and generate the <option> elements
                    foreach ($timeZones as $value => $label) {
                        $selected = ($e_time_zone == $value) ? 'selected' : '';
                        echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php
        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

        if ($ministry_id > 0) { ?>
            <input type="hidden" id="ministry_id" name="ministry_id" value="<?php echo $ministry_id; ?>">
        <?php } else { ?>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label class="form-label">Ministry</label>
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
                    <label class="form-label">Church Level</label>
                    <select class="js-select2" data-search="on" name="level" id="level">
                        <option value=" ">Select Church Level</option>
                        <?php

                        $log_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                        $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

                        if ($log_church_type == 'region') { ?>

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

            

            <div class="col-sm-12 mb-3" id="church_div" style="display:none;">
                <div class="form-group">
                    <label class="form-label">Church</label>
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
    $(document).ready(function () {
        $('#summernote').summernote({
            height: 200, // Set the height of the editor
            tabsize: 2,
            focus: true
        });
        $('.summernote').summernote({
            height: 200, // Set the height of the editor
            tabsize: 2,
            focus: true
        });
        $('.time-picker').timepicker({});
        <?php if($param2 == 'time_add'){?> 
            calculateEndTime();
        <?php } ?>
        <?php if($param2 == 'time_edit'){?> 
            calculateEndTimez();
        <?php } ?>
        function calculateEndTimez() {
            const hour = parseInt($('#hourz').val(), 10);
            const minute = parseInt($('#minutez').val(), 10);
            const amPm = $('#am_pmz').val();
            const duration = parseInt($('#durationzz').val(), 10);

            // Validate selections
            if (isNaN(hour) || isNaN(minute) || !amPm || isNaN(duration)) {
                showError('Please select a valid hour, minute, and AM/PM.');
                return;
            }

            // Convert to 24-hour format
            let convertedHour = hour;
            if (amPm === 'PM' && hour < 12) convertedHour += 12;
            if (amPm === 'AM' && hour === 12) convertedHour = 0;

            // Calculate the end time
            const endTime = calculateEnd(convertedHour, minute, duration);

            // Update the end time field
            $('#end_timez').val(endTime);

            // Clear error messages
            clearError();
        }

        function calculateEndTime() {
            const hour = parseInt($('#hour').val(), 10);
            const minute = parseInt($('#minute').val(), 10);
            const amPm = $('#am_pm').val();
            const duration = parseInt($('#durationz').val(), 10);

            // Validate selections
            if (isNaN(hour) || isNaN(minute) || !amPm || isNaN(duration)) {
                showError('Please select a valid hour, minute, and AM/PM.');
                return;
            }

            // Convert to 24-hour format
            let convertedHour = hour;
            if (amPm === 'PM' && hour < 12) convertedHour += 12;
            if (amPm === 'AM' && hour === 12) convertedHour = 0;

            // Calculate the end time
            const endTime = calculateEnd(convertedHour, minute, duration);

            // Update the end time field
            $('#end_time').val(endTime);

            // Clear error messages
            clearError();
        }

        function calculateEnd(hours, minutes, duration) {
            const startDate = new Date();
            startDate.setHours(hours, minutes, 0, 0);

            const endDate = new Date(startDate.getTime() + duration * 60000);
            const endHours = endDate.getHours();
            const endMinutes = endDate.getMinutes();
            const endMeridian = endHours >= 12 ? 'PM' : 'AM';

            const formattedEndHours = (endHours % 12 || 12).toString().padStart(2, '0');
            const formattedEndMinutes = endMinutes.toString().padStart(2, '0');

            return `${formattedEndHours}:${formattedEndMinutes} ${endMeridian}`;
        }

        function showError(message) {
            $('#bb_ajax_msg').html(message);
        }

        function clearError() {
            $('#bb_ajax_msg').html('');
        }

        // Attach event listeners
        $('#hour, #minute, #am_pm').on('change', calculateEndTime);
        $('#hourz, #minutez, #am_pmz').on('change', calculateEndTimez);
        $('#reminder, #reminderz').on('input', function () {
            let value = $(this).val();

            // Check if the value is a positive integer
            if (!/^\d+$/.test(value) || value < 1) {
                $('#bb_ajax_msg').html('Please enter a valid Notification Reminder in whole minutes.').show();
                $(this).val(''); // Clear invalid input
            } else {
                $('#bb_ajax_msg').html(''); // Hide the message if input is valid
            }
        });
        $('#duration').on('input', function () {
            let value = $(this).val();

            // Check if the value is a positive integer
            if (!/^\d+$/.test(value) || value < 1) {
                $('#bb_ajax_msg').html('Please enter a valid duration in whole minutes.').show();
                $(this).val(''); // Clear invalid input
            } else {
                $('#bb_ajax_msg').html(''); // Hide the message if input is valid
            }
        });

    });
    function readURL(input, id) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#' + id).attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#img-upload").change(function(){
		readURL(this, 'img');
	});
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


    var site_url = '<?php echo site_url(); ?>';

    $(document).ready(function () {
        $('.time-picker').timepicker({});
        <?php
            $e_church_ids = !empty($e_church_id) ? json_encode($e_church_id) : '[]';
            if(!empty($e_pattern) && $e_pattern != ' '){
                $e_patterns = $e_pattern;
            } else {
                $e_patterns = 0;
            }
        ?>
        var eChurchId = <?php echo $e_church_ids; ?>;
        var ePattern = <?php echo $e_patterns; ?>;
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

        $(document).ready(function () {
            // Function to initialize the display state based on current values
            function initializeDisplayState() {
                var eventType = $('#event_type').val();
                var recurringPattern = $('#recurring_pattern').val();
                var location = $('#location').val();

                // Handle event type
                if (eventType == 'one-time') {
                    $('#pattern_resp').hide(500);
                    $('#week_resp').hide(500);
                    $('#month_resp').hide(500);
                    $('#year_resp').hide(500);
                }
                if (eventType == 'recurring') {
                    $('#pattern_resp').show(500);

                    // Handle recurring pattern
                    if (recurringPattern == 'weekly') {
                        $('#week_resp').show(500);
                        $('#month_resp').hide(500);
                        $('#year_resp').hide(500);
                    }
                    if (recurringPattern == 'monthly') {
                        $('#week_resp').hide(500);
                        $('#month_resp').show(500);
                        $('#year_resp').hide(500);
                    }
                    if (recurringPattern == 'yearly') {
                        $('#week_resp').hide(500);
                        $('#month_resp').hide(500);
                        $('#year_resp').show(500);
                    }
                }

                // Handle location
                if (location == 'church') {
                    $('#venue_resp').hide(500);
                }
                if (location == 'other') {
                    $('#venue_resp').show(500);
                }
            }

            // Initialize display state on page load
            initializeDisplayState();

            // Handle changes for event type
            $('#event_type').on('change', function () {
                var selectedType = $(this).val();
                if (selectedType == 'one-time') {
                    $('#pattern_resp').hide(500);
                    $('#week_resp').hide(500);
                    $('#month_resp').hide(500);
                    $('#year_resp').hide(500);
                }
                if (selectedType == 'recurring') {
                    $('#pattern_resp').show(500);
                }
                // Call initializeDisplayState to adjust visibility based on new values
                initializeDisplayState();
            });

            // Handle changes for recurring pattern
            $('#recurring_pattern').on('change', function () {
                var selectedType = $(this).val();
                if (selectedType == 'weekly') {
                    $('#week_resp').show(500);
                    $('#month_resp').hide(500);
                    $('#year_resp').hide(500);
                }
                if (selectedType == 'monthly') {
                    $('#week_resp').hide(500);
                    $('#month_resp').show(500);
                    $('#year_resp').hide(500);
                }
                if (selectedType == 'yearly') {
                    $('#week_resp').hide(500);
                    $('#month_resp').hide(500);
                    $('#year_resp').show(500);
                }
            });

            // Handle changes for location
            $('#location').on('change', function () {
                var selectedType = $(this).val();
                if (selectedType == 'church') {
                    $('#venue_resp').hide(500);
                }
                if (selectedType == 'other') {
                    $('#venue_resp').show(500);
                }
            });
        });

        $('#send_type').on('change', function () {
            var selectedType = $(this).val();
            if (selectedType == 'general') {
                $('#send_text').html('This Event would apply to all Churches under the Selected  Region/Zone/Group/Church Assembly');
            }
            if (selectedType == 'individual') {
                $('#send_text').html('This  Event would apply to the Selected Church Only');
            }

        });


        // Function to populate days of the month
        function populateDaysOfMonth(selectedDay) {
            var daysOfMonth = $('#days_of_month');
            daysOfMonth.empty(); // Clear existing options
            daysOfMonth.append('<option value="">Select a Day</option>'); // Placeholder option

            for (var i = 1; i <= 31; i++) {
                var selected = (i == selectedDay) ? 'selected' : '';
                daysOfMonth.append('<option value="' + i + '" ' + selected + '>' + i + '</option>');
            }
        }

        // Function to populate months of the year
        function populateMonthsOfYear(selectedMonth) {
            var monthsOfYear = $('#months_of_year');
            monthsOfYear.empty(); // Clear existing options
            monthsOfYear.append('<option value="">Select a Month</option>'); // Placeholder option

            var months = [
                { value: 1, name: 'January' },
                { value: 2, name: 'February' },
                { value: 3, name: 'March' },
                { value: 4, name: 'April' },
                { value: 5, name: 'May' },
                { value: 6, name: 'June' },
                { value: 7, name: 'July' },
                { value: 8, name: 'August' },
                { value: 9, name: 'September' },
                { value: 10, name: 'October' },
                { value: 11, name: 'November' },
                { value: 12, name: 'December' }
            ];

            $.each(months, function (index, month) {
                var selected = (month.value == selectedMonth) ? 'selected' : '';
                monthsOfYear.append('<option value="' + month.value + '" ' + selected + '>' + month.name + '</option>');
            });
        }
        if (ePattern === ' ') {
            ePattern = '';
        }
        // Example usage
        var e_pattern = ePattern; // Example e_pattern object with selected day and month
        populateDaysOfMonth(e_pattern);
        populateMonthsOfYear(e_pattern);


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