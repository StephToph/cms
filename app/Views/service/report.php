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
        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        .table-scroll {
            max-height: 350px; /* adjust as needed */
            overflow-y: auto;
            overflow-x: auto;
        }

        .table-scroll table {
            width: max-content; /* ensures full width scroll */
            border-collapse: collapse;
        }

        .table-scroll thead th {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 1;
        }
        .table-scroll td input {
            padding: 8px;
            box-sizing: border-box;
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
                                        <ul>
                                            <li id="service_name"></li>
                                        </ul>
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
                                                <a href="javascript:;" id="toggleFilterBtn" class="btn btn-icon btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Toggle Filters">
                                                    <em class="icon ni ni-filter-alt"></em>
                                                </a>
                                            </li>

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
                            <div class="card card-bordered mb-3" id="filterSection" style="display: none;">
                                <div class="card-inner">
                                    <h6 class="title mb-3">Filter Service Reports</h6>
                                    <form id="filterForm">
                                        <div class="row g-3 align-center">
                                            <div class="col-md-3 mb-3">
                                                <div class="form-control-wrap">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control date-picker" id="filterDate" onchange="load();"  name="date" value="">
                                                        <button class="btn btn-outline-secondary" type="button" id="clearDateBtn"><em class="icon ni ni-cross"></em></button>
                                                    </div>
                                                </div>
                                                <span class="text-danger small">Service Date</span>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                               <div class="form-control-wrap">
                                                    <select class="js-select2" data-search="on" id="filterType" onchange="load();" name="type">
                                                        <option value="all">All Types</option>
                                                        <?php
                                                            $service_types = $this->Crud->read_order('service_type', 'name', 'asc'); 
                                                            foreach($service_types as $stype): ?>
                                                            <option value="<?= $stype->id; ?>"><?= esc($stype->name); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger small">Service Types</span>
                                            </div>
                                            <div class="col-sm-2 mb-3">
                                                <select class="form-control js-select2"  data-search="on" id="church_scope" name="church_scope" onchange="toggleChurchScope(this.value)">
                                                    <option value="all">All Churches</option>
                                                    <?php if (!empty($church_id)) { ?><option value="own">My Church</option><?php } ?>
                                                    <option value="selected">Selected Churches</option>
                                                </select>
                                                <span class="text-danger small">Church Filter Type</span>
                                            </div>

                                            <!-- Multi-select churches -->
                                            <div class="col-sm-4 mb-3" id="selected_church_container" style="display:none;">
                                                <select class="form-control js-select2"  data-search="on" id="selected_churches" name="selected_churches" multiple onchange="load();">
                                                    <!-- Dynamically populated -->
                                                </select>
                                                <span class="text-danger small">Select Churches</span>
                                            </div>

                                            <!-- Dynamic Cell Dropdown -->
                                            <!-- <div class="col-sm-4 mb-3" id="cell_container">
                                                <select class="form-control js-select2"  data-search="on" id="cell_id" name="cell_id" onchange="load();">
                                                   
                                                </select>
                                                <span class="text-danger small">Select Cell</span>
                                            </div> -->

                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row p-2" id="metricSection">
                                <div class="col-md-3 mb-3">
                                    <div class="card card-bordered text-white bg-primary card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Attendance'); ?></h6>
                                                </div>
                                            </div>
                                            <div class="card-amount"><span class="amount text-white" id="t_attendance"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card card-bordered border-primary card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('First Timer'); ?></h6>
                                                </div>
                                                
                                            </div>
                                            <div class="card-amount"><span class="amount" id="t_firstTimer"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card card-bordered border-primary card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('New Convert'); ?></h6>
                                                </div>
                                                
                                            </div>
                                            <div class="card-amount"><span class="amount" id="t_convert"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card card-bordered border-success card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Offering'); ?></h6>
                                                </div>
                                                
                                            </div>
                                            <div class="card-amount"><span class="amount " id="t_offering"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card card-bordered border-danger card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Tithe'); ?></h6>
                                                </div>
                                                
                                            </div>
                                            <div class="card-amount"><span class="amount" id="t_tithe"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="card card-bordered border-primary card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Partnership'); ?></h6>
                                                </div>
                                                
                                            </div>
                                            <div class="card-amount"><span class="amount" id="t_partnership"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="card card-bordered border-primary card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Thanksgiving'); ?></h6>
                                                </div>
                                                
                                            </div>
                                            <div class="card-amount"><span class="amount" id="t_thanksgiving"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card card-bordered border-primary card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Special Seed '); ?></h6>
                                                </div>
                                                
                                            </div>
                                            <div class="card-amount"><span class="amount" id="t_seed"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                
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
                                                 value="0">
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
                                        
                                        <div class="col-sm-4 mb-2">
                                            <label class="name">Search Member</label> 
                                            <div class="input-group">
                                                <input type="text" id="member_id" class="form-control" placeholder="Enter Name or Email">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-primary btn-dim" id="search_member_btn">Search</button>
                                                </div>
                                            </div>
                                            
                                           
                                        </div>
                                        
                                        <div class="col-sm-2 my-2">
                                            <button id="markMemberBTN" type="button" data-service-id="" onclick="get_memberz(this)"  data-bs-toggle="tooltip" data-bs-placement="top" title="Mark Member" class="btn btn-block btn-dim btn-outline-dark mt-3 mx-2">
                                                <em class="icon ni ni-user"></em><span> Mark Member</span>
                                            </button>
                                        </div>

                                        <div class="col-sm-2 my-2">
                                            <a href="javascript:;" id="firstTimerBtnz"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Add First Timer"
                                                class="btn btn-block btn-dim btn-outline-info pop mt-3 mx-2"
                                                pageTitle="<?=translate_phrase('Add First Timer');?>"
                                                pageName="<?= site_url('service/report/manage/timers'); ?>"
                                                pageSize="modal-xl">
                                                <em class="icon ni ni-plus-c"></em><span> Add First Timer</span>
                                            </a>

                                        </div>
                                        <div class="col-12 my-2 text-cener" id="memberAttendance" style="display:none;"></div>
                                        <div class="col-12 my-2 " id="member_response"></div>
                                        <div class="col-sm-12 my-3" id="metric_response">
                                            <!-- dynamic content appears here -->
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12 my-2">
                                            <div id="attendance_mzg"></div>
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
                                            <input class="form-control" id="total_partnership" type="text" name="total_part"
                                                readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Member Partnership</label>
                                            <input class="form-control" id="member_partnership" type="text" name="member_part"
                                                readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Guest Partnership</label>
                                            <input class="form-control" id="guest_partnership" type="text" name="guest_part"
                                                oninput="get_part();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')"
                                                readonly value="0">
                                        </div>
                                      
                                    </div>
                                    <hr>
                                    <div class="table-container">
                                        <div class="table-scroll">
                                            <table class="table table-striped table-hover mt-5 p-2">
                                                <thead>
                                                    <tr>
                                                        <th width="250px">MEMBER</th>
                                                        <th>OFFERING</th>
                                                        <th>TITHE</th>
                                                        <th>THANKSGIVING</th>
                                                        <th>SPECIAL SEED</th>
                                                        <?php
                                                        $parts = $this->Crud->read_order('partnership', 'name', 'asc');
                                                        if (!empty($parts)) {
                                                            foreach ($parts as $pp) {
                                                                $name = $pp->name;
                                                                if (strtoupper($pp->name) == 'BIBLE SPONSOR') $name = 'Bible';
                                                                if (strtoupper($pp->name) == 'CHILDREN MINISTRY') $name = 'Children';
                                                                if (strtoupper($pp->name) == 'HEALING SCHOOL MAGAZINE') $name = 'H.S.M';
                                                                if (strtoupper($pp->name) == 'HEALING STREAM') $name = 'H.S';
                                                                if (strtoupper($pp->name) == 'LOVEWORLD LWUSA') $name = 'lwusa';
                                                                if (strtoupper($pp->name) == 'MINISTRY PROGRAM') $name = 'Ministry';

                                                                echo '<th>' . strtoupper($name) . '</th>';
                                                            }
                                                        }
                                                        ?>
                                                        <th>CURRENCY</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="member_partner_list">
                                                    <!-- Rows -->
                                                </tbody>
                                            </table>
                                        </div>
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
         // When user types into the input box
        $('#member_id').on('input', function() {
            $('#memberAttendance').hide(500);
            $('#member_response').show(500);
            $('#metric_response').show(500);
            get_member();
        });

        // When user clicks Search button
        $('#search_member_btn').on('click', function() {
            $('#memberAttendance').hide(500);
            $('#member_response').show(500);
            $('#metric_response').show(500);
            get_member();
        });
        
        toggleChurchScope('all');
        $('#toggleFilterBtn').on('click', function () {
            $('#filterSection').slideToggle(300);
        });
        $('#clearDateBtn').on('click', function () {
            $('#filterDate').val('').datepicker('update', '').change();
        });

        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            clearBtn: true // ✅ This shows a "Clear" button
        });
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

    function toggleChurchScope(scope) {
        const $selectedChurchContainer = $('#selected_church_container');
        const $selectedChurches = $('#selected_churches');
        const $cellSelect = $('#cell_id');

        if (scope === 'selected') {
            $selectedChurchContainer.show();

            // Fetch church list only once
            if ($selectedChurches.children().length === 0) {
                $.ajax({
                    url: "<?= site_url('service/fetch_scope_churches') ?>",
                    method: 'GET',
                    success: function (res) {
                        $selectedChurches.empty();
                        $.each(res, function (i, church) {
                            $selectedChurches.append(`<option value="${church.id}">${church.name} (${church.type})</option>`);
                        });
                        $selectedChurches.select2();
                    }
                });
            }

            // Bind change event to fetch cells dynamically when church selection changes
            $selectedChurches.off('change').on('change', function () {
                const selected = $(this).val();
                if (selected.length > 0) {
                    $.ajax({
                        url: "<?= site_url('service/analytics/records/fetch_cells_by_churches') ?>",
                        method: 'POST',
                        data: { church_ids: selected },
                        success: function (res) {
                            $cellSelect.empty();
                            $cellSelect.append(`<option value="all">-- All Cell --</option>`); // default option
                            $.each(res, function (i, cell) {
                                $cellSelect.append(`<option value="${cell.id}">${cell.name} (Church ID: ${cell.church})</option>`);
                            });
                        }
                    });
                } else {
                    $cellSelect.empty();
                }
            });

        } else {
            $selectedChurchContainer.hide();
            $selectedChurches.val(null).trigger('change');

            // 🔁 Fetch cells for "own" or "all" scope
            $.ajax({
                url: "<?= site_url('service/analytics/records/fetch_cells_by_scope') ?>",
                method: 'POST',
                data: { scope: scope },
                success: function(res) {
                    $cellSelect.empty();
                    $cellSelect.append(`<option value="all">-- All Cell --</option>`); // default option

                    $.each(res, function(index, cell) {
                        $cellSelect.append(`
                            <option value="${cell.cell_id}">
                                ${cell.cell_name} (Church ID: ${cell.church})
                            </option>
                        `);
                    });
                },
            });
        }
    }

    
</script>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script src="<?php echo site_url(); ?>assets/js/service_report.js?v=<?= time(); ?>"></script>
<?= $this->endSection(); ?>