
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
                <input type="hidden" name="d_type_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete');?>
                </button>
            </div>
        </div>
    <?php } ?>

    
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="type_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <?php if($role == 'developer' || $role == 'administrator' || $role == 'ministry administrator'){?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label for="name">*<?= translate_phrase('Church'); ?></label>
                        <select data-search="on" class=" js-select2" id="church_id" name="church_id" required>
                            <option value="">Select Church</option>
                            <?php
                            $type = $this->Crud->read_order('church', 'name', 'asc');
                            if (!empty($type)) {
                                foreach ($type as $t) {
                                    $sel='';
                                    if(!empty($e_church_id)){
                                        if($e_church_id == $t->id)$sel = 'selected';
                                    }
                                   
                                    echo '<option value="' . $t->id . '" '.$sel.'>' . ucwords($t->name.' - '.$t->type) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            <?php } else {?>
                <input type="hidden" name="church_id" value="<?=$this->Crud->read_field('id', $log_id, 'user', 'church_id'); ?>">
            <?php } ?>    
           
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?= translate_phrase('Service Type'); ?></label>
                    <select data-search="on" class=" js-select2" id="type" name="type" required>
                        <option value="0">Select</option>
                        <?php
                        $type = $this->Crud->read_order('service_type', 'name', 'asc');
                        if (!empty($type)) {
                            foreach ($type as $t) {
                                $sel='';
                                if(!empty($e_type_id)){
                                    if($e_type_id == $t->id)$sel = 'selected';
                                }
                               
                                echo '<option value="' . $t->id . '" '.$sel.'>' . ucwords($t->name) . '</option>';
                            }
                        }
                        ?>

                        <option value="__new__">➕ Add New Service Type</option>
                    </select>
                </div>
            </div>

            <!-- Hidden input for new service type name -->
            <div class="col-sm-6 mb-3" id="new_service_type_container" style="display:none;">
                <div class="form-group">
                    <label for="new_type">Enter New Service Type</label>
                    <input type="text" class="form-control" name="new_type" id="new_type" placeholder="e.g. Healing Service">
                </div>
            </div>

                         <!-- Service Type -->
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label>Service Type</label>
                    <select  data-search="on" class=" js-select2" name="service_type" id="service_type" required>
                        <option value="one-time" <?php if(!empty($e_type)){if($e_type == 'one-time'){echo 'selected';}} ?>>One Time</option>
                        <option value="recurring" <?php if(!empty($e_type)){if($e_type == 'recurring'){echo 'selected';}} ?>>Recurring</option>
                    </select>
                </div>
            </div>

            <!-- One-Time Date -->
            <div class="col-sm-6 mb-3 one-time-fields" style="display:none;">
                <div class="form-group">
                    <label>Service Date</label>
                    <input type="text" name="service_date" value="<?php if(!empty($e_service_date)){echo $e_service_date;} ?>" class="form-control date-picker">
                </div>
            </div>
             <!-- Time Pickers (Both) -->
             <div class="col-sm-3 mb-3 common-time-fields" style="display:none;">
                <label>Start Time</label>
                <input type="text" class="form-control time-picker" name="start_time" placeholder="Start Time"  value="<?php if(!empty($e_start_time)){echo $e_start_time;} ?>">
            </div>
            <div class="col-sm-3 mb-3 common-time-fields" style="display:none;">
                <label>End Time</label>
                <input type="text" class="form-control time-picker" name="end_time" placeholder="End Time"  value="<?php if(!empty($e_end_time)){echo $e_end_time;} ?>">
            </div>
            <!-- Recurring Pattern -->
            <div class="col-sm-6 mb-3 recurring-fields" style="display:none;">
                <div class="form-group">
                    <label>Recurring Pattern</label>
                    <select  data-search="on" class=" js-select2" name="recurring_pattern" id="recurring_pattern">
                        <option value="">Select Pattern</option>
                        <option value="weekly" <?php if(!empty($e_recurrence_pattern)){if($e_recurrence_pattern == 'weekly'){echo 'selected';}} ?>>Weekly</option>
                        <option value="monthly" <?php if(!empty($e_recurrence_pattern)){if($e_recurrence_pattern == 'monthly'){echo 'selected';}} ?>>Monthly</option>
                        <option value="yearly" <?php if(!empty($e_recurrence_pattern)){if($e_recurrence_pattern == 'yearly'){echo 'selected';}} ?>>Yearly</option>
                    </select>
                </div>
            </div>
                         <!-- Recurring Common Fields -->
            <div class="col-sm-6 mb-3 recurring-fields" style="display:none;">
                <label>Start Date</label>
                <input type="text" class="form-control date-picker" value="<?php if(!empty($e_start_date)){echo $e_start_date;} ?>" name="start_date">
            </div>
           
            <!-- Weekly Days -->
            <div class="col-sm-12 mb-3 recurring-details weekly-fields" style="display:none;">
                <?php
                    $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    $selected_days = [];

                    if (!empty($e_weekly_days)) {
                        $selected_days = explode(',', $e_weekly_days); // Convert to array
                    }
                ?>

                <label>Select Days of the Week</label>
                <div class="form-check form-check-inline">
                    <?php foreach ($days as $day): ?>
                        <label class="me-3">
                            <input type="checkbox" name="weekly_days[]" value="<?= $day ?>" 
                                <?= in_array($day, $selected_days) ? 'checked' : '' ?>> <?= $day ?>
                        </label>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- Monthly Pattern -->
            <div class="col-sm-12 mb-3 recurring-details monthly-fields" style="display:none;">
                <label>Monthly Recurrence Type</label>
                <select  data-search="on" class=" js-select2 mb-2" name="monthly_type" id="monthly_type">
                    <option value="">Select Recurrence Type</option>
                    <option value="dates" <?php if(!empty($e_monthly_type)){if($e_monthly_type == 'dates'){echo 'selected';}} ?>>On Specific Dates</option>
                    <option value="pattern" <?php if(!empty($e_monthly_type)){if($e_monthly_type == 'pattern'){echo 'selected';}} ?>>On Pattern (e.g. 1st Monday)</option>
                </select>

                <!-- Dates input -->
                <input type="text" class="form-control mb-2 monthly-dates" value="<?php if(!empty($e_monthly_dates)){echo $e_monthly_dates;} ?>" name="monthly_dates"
                    placeholder="Enter dates separated by commas (e.g. 5,15,30)" style="display:none;">

                <!-- Pattern-based -->
                <div class="monthly-patterns my-2 " style="display:none;">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <label>Select Weeks</label>
                            <select  data-search="on" class=" js-select2" name="monthly_weeks[]" multiple>
                                <option <?php if(!empty($e_monthly_weeks)){if($e_monthly_weeks == '1'){echo 'selected';}} ?> value="1">First</option>
                                <option <?php if(!empty($e_monthly_weeks)){if($e_monthly_weeks == '2'){echo 'selected';}} ?> value="2">Second</option>
                                <option <?php if(!empty($e_monthly_weeks)){if($e_monthly_weeks == '3'){echo 'selected';}} ?> value="3">Third</option>
                                <option <?php if(!empty($e_monthly_weeks)){if($e_monthly_weeks == '4'){echo 'selected';}} ?> value="4">Fourth</option>
                                <option <?php if(!empty($e_monthly_weeks)){if($e_monthly_weeks == '-1'){echo 'selected';}} ?> value="-1">Last</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <?php
                                $weekdays = ['Sun' => 'Sunday', 'Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday', 'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday'];
                                $selected_monthly_weekdays = [];

                                if (!empty($e_monthly_weekdays)) {
                                    $selected_monthly_weekdays = explode(',', $e_monthly_weekdays); // Convert to array
                                }
                            ?>

                            <label>Select Days</label>
                            <select data-search="on" class="js-select2 form-control" name="monthly_weekdays[]" multiple>
                                <?php foreach ($weekdays as $val => $label): ?>
                                    <option value="<?= $val ?>" <?= in_array($val, $selected_monthly_weekdays) ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Yearly -->
            <div class="col-sm-6 mb-3 recurring-details yearly-fields" style="display:none;">
                <label>Select Date in Year</label>
                <input type="text" class="form-control date-picker"  value="<?php if(!empty($e_yearly_date)){echo $e_yearly_date;} ?>" name="yearly_date">
            </div>

            <?php if($param2 == ''){ if($role != 'center manager'){?>
            <!-- Schedule Scope Selector -->
            <div class="col-sm-12 mb-3">
                <label><strong>Schedule Scope</strong></label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input  schedule-scope" type="radio" name="scope_type" value="own" checked>
                    <label class="form-check-label">Only My Church</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input  schedule-scope" type="radio" name="scope_type" value="all">
                    <label class="form-check-label">All Churches Under my Church</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input  schedule-scope" type="radio" name="scope_type" value="selected">
                    <label class="form-check-label">Select Churches</label>
                </div>
            </div>

            <!-- Selected Churches Multi-Select -->
            <div class="col-sm-12 mb-3" id="select_churches_wrapper" style="display: none;">
                <label>Select Churches</label>
                <?php 
                    $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                    $church_type = $this->Crud->read_field('id', $church_id, 'church', 'type');
                    $ty = 'regional_id';
                    if($church_type == 'zone'){
                        $ty = 'zonal_id';
                    }
                    if($church_type == 'group'){
                        $ty = 'group_id';
                    }
                    if($church_type == 'church'){
                        $ty = 'church_id';
                    }
                ?>
                <select class="form-control js-select2" name="selected_churches[]" multiple>
                    <?php foreach ($this->Crud->read_single_order($ty, $church_id, 'church', 'name', 'asc') as $ch): ?>
                        <option value="<?= $ch->id ?>"><?= ucwords($ch->name . ' - ' . $ch->type) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" name="my_church_id" value="<?= $this->Crud->read_field('id', $log_id, 'user', 'church_id') ?>">
            <?php } }?>
            <div class="col-sm-12 text-center mt-3">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>
<script>
    $(document).ready(function () {
        $('#type').on('change', function () {
            if ($(this).val() === '__new__') {
                $('#new_service_type_container').slideDown();
            } else {
                $('#new_service_type_container').slideUp();
                $('#new_type').val('');
            }
        });
   
        
        $('.schedule-scope').on('change', function() {
            const selectedScope = $(this).val();

            if (selectedScope === 'selected') {
                $('#select_churches_wrapper').slideDown(500);
            } else {
                $('#select_churches_wrapper').slideUp(500);
            }
        });

        // Optional: Trigger default state on load
        $('.schedule-scope:checked').trigger('change');
    
        function toggleServiceTypeFields() {
            const type = $('#service_type').val();

            if (type === 'one-time') {
                $('.one-time-fields').show(500);
                $('.recurring-fields, .recurring-details').hide(500);
            } else if (type === 'recurring') {
                $('.recurring-fields').show(500);
                $('.one-time-fields').hide(500);
                toggleRecurringPattern(); // also handle pattern details
            } else {
                $('.one-time-fields, .recurring-fields, .recurring-details').hide(500);
            }

            $('.common-time-fields').show(500);
        }

        function toggleRecurringPattern() {
            const pattern = $('#recurring_pattern').val();
            $('.recurring-details').hide();

            if (pattern === 'weekly') {
                $('.weekly-fields').show();
            } else if (pattern === 'monthly') {
                $('.monthly-fields').show();
                $('#monthly_type').trigger('change'); // show correct sub-option
            } else if (pattern === 'yearly') {
                $('.yearly-fields').show();
            }
        }

        // Monthly sub-options logic
        $('#monthly_type').on('change', function () {
            const selected = $(this).val();
            if (selected === 'dates') {
                $('.monthly-dates').show();
                $('.monthly-patterns').hide();
            } else if (selected === 'pattern') {
                $('.monthly-dates').hide();
                $('.monthly-patterns').show();
            }
        });

        // Bind change events
        $('#service_type').on('change', toggleServiceTypeFields);
        $('#recurring_pattern').on('change', toggleRecurringPattern);

        // Initialize on load
        toggleServiceTypeFields();

        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd', // Set the date format
            autoclose: true
        });
        $('.time-picker').timepicker({});
    });

</script>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script