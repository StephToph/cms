<?php
use App\Models\Crud;
$this->Crud = new Crud();

$this->session = \Config\Services::session();
    
$switch_id = $this->session->get('switch_church_id');
$service_church_id = $this->session->get('service_church_id');
?>

<?= $this->extend('designs/backend'); ?>
<?= $this->section('title'); ?>
<?= $title; ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
   
<div class="nk-content">
    <style>
        .tags-input {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        .tag {
            background-color: #007bff;
            color: white;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            margin: 0.25rem;
            display: flex;
            align-items: center;
        }
        .tag .remove {
            margin-left: 0.5rem;
            cursor: pointer;
            color: white;
        }
        .error {
            color: red;
            margin-top: 0.5rem;
        }
    </style>
    <div class="container-fluid mt-3">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?= translate_phrase('Service Report'); ?></h3>

                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner position-relative card-tools-toggle">
                                <div class="card-title-group">
                                    <div class="card-tools">

                                    </div><!-- .card-tools -->
                                    <div class="card-tools me-n1">
                                        <ul class="btn-toolbar gx-1">
                                            <?php if(empty($switch_id)){?>
                                            <li>
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Add Report" id="add_btn"
                                                    class="btn btn-icon btn-outline-primary"><em
                                                        class="icon ni ni-plus-c"></em></a>
                                            </li><!-- li -->

                                            
                                            <?php } ?>
                                            <li>
                                                <div class="nk-block-head-sub mb-3" id="attendance_prev"
                                                    style="display:none;">
                                                    <a class="btn btn-outline-danger" id="back_btn"
                                                        href="javascript:;"><em
                                                            class="icon ni ni-arrow-left"></em><span>Service
                                                            Reports</span></a>
                                                </div>
                                                
                                            </li>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->

                            </div><!-- .card-inner -->
                            <div class="card-inner" id="show">

                                <div class="table-responsive" >
                                    <table class="table table-hovered ">
                                        <thead>
                                            <tr>
                                                <th><?=translate_phrase('Date'); ?></th>
                                                <th><?=translate_phrase('Service'); ?></th>
                                                <th><?=translate_phrase('Offering'); ?></th>
                                                <th><?=translate_phrase('Tithe'); ?></th>
                                                <th><?=translate_phrase('Partnership'); ?></th>
                                                <th><?=translate_phrase('Thanksgiving'); ?></th>
                                                <th><?=translate_phrase('Seed'); ?></th>
                                                <th><?=translate_phrase('Attendance'); ?></th>
                                                <th><?=translate_phrase('FT'); ?></th>
                                                <th><?=translate_phrase('NC'); ?></th>
                                                <th class="text-center"><?=translate_phrase('Actions'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="load_data">
                                        </tbody>
                                        <tfoot id="loadmore"></tfoot>
                                    </table>
                                </div><!-- .nk-tb-list -->

                                </div><!-- .nk-block-between -->
                            </div>
                            <div class="card-inner" id="form" style="display:none;">
                                <div class="row">
                                    
                                    <h5>Enter the Details for the Service Meeting Below</h5>
                                    <p class="text-danger">Always click the save record Button after update of
                                        attendance, first timers and new convert.</p>
                                    <?php echo form_open_multipart('service/report/manage', array('id' => 'bb_ajax_form', 'class' => 'row mt-4')); ?>
                                    <input type="hidden" name="report_id" id="report_id"
                                        value="<?php if (!empty($e_id)) {
                                            echo $e_id;
                                        } ?>">
                                    <?php
                                    $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
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
                                        <input type="hidden" id="ministry_id" value="<?= $ministry_id; ?>">


                                    <?php } ?>
                                    <?php if ($role != 'church leader') {
                                        $log_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                                        ?>
                                        <div class="col-sm-4 mb-3">
                                            <label class="name">Church Level</label>
                                            <select class="js-select2" name="level" id="level" onchange="load_level();">
                                                <option value=" ">Select Church Level</option>

                                            </select>
                                        </div>

                                        <div class="col-sm-4 mb-3" id="church_div" style="display:none;">
                                            <div class="form-group">
                                                <label>Church</label>
                                                <select class="js-select2" data-search="on" name="church_id" id="church_id" onchange="session_church();">
                                                    <option value=" ">Select Church</option>

                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-sm-4 mb-3">
                                        <div class="form-group">
                                            <label for="name">*<?= translate_phrase('Service Type'); ?></label>
                                            <select data-search="on" class=" js-select2" id="type" name="type" required>
                                                <option value="0">Select</option>
                                                <?php
                                                $type = $this->Crud->read_order('service_type', 'name', 'asc');
                                                if (!empty($type)) {
                                                    foreach ($type as $t) {
                                                        echo '<option value="' . $t->id . '">' . ucwords($t->name) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <div class="form-group">
                                            <label class="name">*Date</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="dates" id="dates"
                                                    class="form-control date-picker" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <div class="form-group">
                                            <label for="name"><?= translate_phrase('First Timer'); ?></label>
                                            <div class="form-control-wrap">
                                                <div class="input-group">
                                                    <input type="text" id="first_timer" name="first_timer"
                                                        oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')"
                                                        class="form-control" value="0">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 mb-3">
                                        <div class="form-group">
                                            <label for="name">*<?= translate_phrase('Head Count'); ?></label>
                                            <div class="form-control-wrap">
                                                <div class="input-group">
                                                    <input type="text" name="attendance" id="attendance"
                                                        oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')"
                                                        class="form-control" placeholder="0">

                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <div class="form-group">
                                            <label for="name"><?= translate_phrase('New Convert'); ?></label>
                                            <div class="form-control-wrap">
                                                <div class="input-group">
                                                    <input type="text" id="new_convert" class="form-control"
                                                        oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')"
                                                        name="new_convert" placeholder="0">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label for="name"><?= translate_phrase('Note'); ?></label>
                                            <textarea class="form-control" id="note" name="note"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12 text-center mt-3">
                                        <button class="btn btn-primary bb_fo_btn" type="submit">
                                            <em class="icon ni ni-save"></em>
                                            <span><?= translate_phrase('Save Record'); ?></span>
                                        </button>
                                    </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-sm-12 my-3">
                                            <div id="bb_ajax_msg"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-inner" id="attendance_view" style="display:none;">
                                <form id="attendanceForm">
                                    <div class="row">
                                        <input type="hidden" name="attendance_id" id="attendance_id">
                                        <span class="text-danger mb-2">Enter Attendance</span>
                                        <div class="col-sm-4 mb-3 ">
                                            <label>Head Count</label>
                                            <input class="form-control" id="head_count" type="text" name="total"
                                                readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3 ">
                                            <label>Total Marked Attendance</label>
                                            <input class="form-control" id="total_attendance" type="text" name="totalz"
                                                readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Member</label>
                                            <input class="form-control" id="member_attendance" type="text" name="member"
                                                value="0" readonly placeholder="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>First Timer</label>
                                            <input class="form-control" id="guest_attendance" type="text" name="guest"
                                                value="" readonly placeholder="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Male</label>
                                            <input class="form-control" id="male_attendance" type="text" name="male"
                                                value="" readonly placeholder="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Female</label>
                                            <input class="form-control" id="female_attendance" type="text" name="female"
                                                value="" readonly placeholder="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Children</label>
                                            <input class="form-control" id="children_attendance" type="text"
                                                name="children" value="" readonly placeholder="0">
                                        </div>
                                        
                                        <div class="col-md-12 my-3" id="metric_response">
                                            <!-- dynamic content appears here -->
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mt-5">
                                        <div class="col-sm-12 text-center mt-5">
                                            <button class="btn btn-primary bb_fo_btn" type="submit">
                                                <i class="icon ni ni-save"></i>
                                                <span><?= translate_phrase('Save Record'); ?></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 my-2">
                                            <div id="attendance_msg"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                           
                            <div class="card-inner" id="finance_view" style="display:none;">
                                <form id="financeForm">
                                    <input type="hidden" name="finance_id" id="finance_id">
                                    <input type="hidden" name="first_church_id" id="first_church_id">

                                    <div class="row">
                                        <span class="text-danger mb-2">Enter Finance Record in the Table
                                            Below</span>

                                             
                                        <div class="col-sm-4 mb-3 ">
                                            <label>Total Offering</label>
                                            <input class="form-control" id="total_offering" type="text" name="total_offering"
                                                readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Member Offering</label>
                                            <input class="form-control" id="member_offering" type="text"
                                                name="member_offering" readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Guest Offering</label>
                                            <input class="form-control" id="guest_offering" type="text" name="guest_offering"
                                                oninput="get_offering();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')"
                                                value="0">
                                        </div>
                                        
                                        <div class="col-sm-4 mb-3 ">
                                            <label>Total Tithe</label>
                                            <input class="form-control" id="total_tithe" type="text" name="total_tithe"
                                                readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Member Tithe</label>
                                            <input class="form-control" id="member_tithe" type="text"
                                                name="member_tithe" readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Guest Tithe</label>
                                            <input class="form-control" id="guest_tithe" type="text" name="guest_tithe"
                                                oninput="get_tithe();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')"
                                                value="0">
                                        </div>
                                         
                                        <div class="col-sm-4 mb-3 ">
                                            <label>Total Thanksgiving Offering</label>
                                            <input class="form-control" id="total_thanksgiving" type="text" name="total_thanksgiving"
                                                readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Member Thanksgiving Offering</label>
                                            <input class="form-control" id="member_thanksgiving" type="text"
                                                name="member_thanksgiving" readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Guest Thanksgiving Offering</label>
                                            <input class="form-control" id="guest_thanksgiving" type="text" name="guest_thanksgiving"
                                                oninput="get_thanksgiving();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')"
                                                value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3 ">
                                            <label>Total Special Seed Offering</label>
                                            <input class="form-control" id="total_seed" type="text" name="total_seed"
                                                readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Member Special Seed Offering</label>
                                            <input class="form-control" id="member_seed" type="text"
                                                name="member_seed" readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Guest Special Seed Offering</label>
                                            <input class="form-control" id="guest_seed" type="text" name="guest_seed"
                                                oninput="get_seed();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')"
                                                value="0">
                                        </div>
                                        
                                        <div class="col-sm-4 mb-3 ">
                                            <label>Total Partnership</label>
                                            <input class="form-control" id="total_part" type="text" name="total_part"
                                                readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Member Partnership</label>
                                            <input class="form-control" id="member_part" type="text" name="member_part"
                                                readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Guest Partnership</label>
                                            <input class="form-control" id="guest_part" type="text" name="guest_part"
                                                oninput="get_part();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')"
                                                readonly value="0">
                                        </div>
                                      
                                    </div>
                                    <hr>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover mt-5">
                                            <thead>
                                                <tr>
                                                    <th width="250px;">MEMBER</th>
                                                    
                                                    <th>OFFERING</th>
                                                    <th>TITHE</th>
                                                    <th>THANKSGIVING</th>
                                                    <th>SPECIAL SEED</th>
                                                    <?php
                                                    $parts = $this->Crud->read_order('partnership', 'name', 'asc');
                                                    if (!empty($parts)) {
                                                        foreach ($parts as $pp) {
                                                            $name = $pp->name;
                                                            if (strtoupper($pp->name) == 'BIBLE SPONSOR')
                                                                $name = 'Bible';
                                                            if (strtoupper($pp->name) == 'CHILDREN MINISTRY')
                                                                $name = 'Children';
                                                            if (strtoupper($pp->name) == 'HEALING SCHOOL MAGAZINE')
                                                                $name = 'H.S.M';
                                                            if (strtoupper($pp->name) == 'HEALING STREAM')
                                                                $name = 'H.S';
                                                            if (strtoupper($pp->name) == 'LOVEWORLD LWUSA')
                                                                $name = 'lwusa';
                                                            if (strtoupper($pp->name) == 'MINISTRY PROGRAM')
                                                                $name = 'Ministry';
                                                            // if($pp->name == 'BIBLE SPONSOR')$name = 'Bible';
                                                    
                                                            echo ' <th >' . strtoupper($name) . '</th>';
                                                        }
                                                    }
                                                    ?>
                                                    <th>CURRENCY</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="member_partner_list">

                                            </tbody>
                                        </table>
                                        <div class="col-12 my-3 text-center">
                                            <p id="mem_resp"></p>
                                            <button type="button" class="btn btn-primary" id="mem_btn">Add More</button>
                                        </div>
                                    </div>
                                    <hr>

                                    
                                    <div id="guest_part_view" class="table-responsive" style="display:none;">
                                        <table class="table table-striped table-hover mt-5">
                                            <thead>
                                                <tr>
                                                    <th>GUEST</th>
                                                    <th>OFFERING</th>
                                                    <th>TITHE</th>
                                                    <th>THANKSGIVING</th>
                                                    <th>SPECIAL SEED</th>
                                                    <?php
                                                    $parts = $this->Crud->read_order('partnership', 'name', 'asc');
                                                    if (!empty($parts)) {
                                                        foreach ($parts as $index => $pp) {
                                                            $name = $pp->name;
                                                            if (strtoupper($pp->name) == 'BIBLE SPONSOR')
                                                                $name = 'Bible';
                                                            if (strtoupper($pp->name) == 'CHILDREN MINISTRY')
                                                                $name = 'Children';
                                                            if (strtoupper($pp->name) == 'HEALING SCHOOL MAGAZINE')
                                                                $name = 'H.S.M';
                                                            if (strtoupper($pp->name) == 'HEALING STREAM')
                                                                $name = 'H.S';
                                                            if (strtoupper($pp->name) == 'LOVEWORLD LWUSA')
                                                                $name = 'lwusa';
                                                            if (strtoupper($pp->name) == 'MINISTRY PROGRAM')
                                                                $name = 'Ministry';
                                                            // if($pp->name == 'BIBLE SPONSOR')$name = 'Bible';
                                                    
                                                            echo ' <th width="200px">' . strtoupper($name) . '</th>';


                                                        }
                                                    }
                                                    ?>
                                                    <th>CURRENCY</th>
                                                </tr>
                                            </thead>
                                            <tbody id="guest_partner_list"> </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12 text-center my-5">
                                            <button class="btn btn-primary bb_fo_btn" type="submit">
                                                <i class="icon ni ni-save"></i>
                                                <span><?= translate_phrase('Save Record'); ?></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div id="finance_msg"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-inner" id="media_view" style="display:none;">
                                <form id="mediaForm" action="<?= site_url('upload') ?>">
                                    <input type="hidden" name="media_id" id="media_id">

                                  
                                    <div class="row">
                                        <span class="text-danger my-2">Enter Service Images</span>
                                        <div class="col-sm-12">
                                            <label class="form-label">Dropzone FileSize Limit (4mb)</label>
                                            <div class="upload-zone" id="upload-zone" data-url="<?=site_url('service/report/manage/media'); ?>" data-max-file-size="4">
                                                <div class="dz-message" data-dz-message>
                                                    <span class="dz-message-text">Drag and drop file</span>
                                                    <span class="dz-message-or">or</span>
                                                    <button type="button" class="btn btn-primary">SELECT</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container mt-4">
                                            <h5>Video Url</h5>
                                            <div class="tags-input" id="tags-input" style="border: 0px;">
                                                <input type="text" id="tag-input" placeholder="Add tags (separated by commas)" class="form-control" />
                                            </div>
                                            <div class="error" id="error-message"></div>
                                            <div id="url-input" class="tags-input"></div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div id="media_msg"></div>
                                        </div>
                                    </div><hr>
                                    <div id="gallery_view" class="nk-block" style="display:none;">
                                        
                                       
                                    </div>
                                   
                                </form>
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>


<script>var site_url = '<?php echo site_url(); ?>';</script>
<script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

<script>
    $(document).ready(function() {
        $('#add_first_timer').click(function() {
            // Clone the original form fields (the first set)
            var originalFields = $('#container').clone();

            // Reset the input field values in the cloned div to blank or null
            originalFields.find('input, select, textarea').val('');
            originalFields.find('input[type="radio"]').prop('checked', false); // Uncheck radio buttons

            // Wrap the cloned div in a new container
            var newContainer = $('<div class="row mb-3 new-form-group card-bordered p-2"></div>');
            newContainer.append(originalFields); // Append the cloned form fields to the new container
            
            // Add a delete button to the newly added container
            newContainer.append('<button class="btn btn-danger btn-sm remove-field" type="button">Remove</button>');
            
            // Append the new container to the main container
            $('#containerz').append(newContainer);

            // Increment the first_count value
            var currentCount = parseInt($('#first_count').val());
            $('#first_count').val(currentCount + 1);  // Increment the value of first_count
        });

        // Delete functionality for removing a cloned form group
        $(document).on('click', '.remove-field', function() {
            $(this).closest('.new-form-group').remove(); // Remove the entire div containing the form fields and delete button
            // Decrease the first_count value when deleting a form group
            var currentCount = parseInt($('#first_count').val());
            if (currentCount > 1) {  // Ensure it doesn't go below 1
                $('#first_count').val(currentCount - 1);  // Decrement the value of first_count
            }
        });
});
</script>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script src="<?php echo site_url(); ?>assets/js/service_report.js?v=<?= time(); ?>"></script>
<?= $this->endSection(); ?>