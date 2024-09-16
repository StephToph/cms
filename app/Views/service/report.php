<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
<div class="nk-content" >
    <div class="container-fluid mt-3">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Service Report');?></h3>
                            
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
                                            <li>
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Report"  id="add_btn" class="btn btn-icon btn-outline-primary"><em class="icon ni ni-plus-c"></em></a>
                                            </li><!-- li -->
                                            <li>
                                                <div class="nk-block-head-sub mb-3" id="attendance_prev" style="display:none;">
                                                    <a class="btn btn-outline-danger" id="back_btn" href="javascript:;"><em class="icon ni ni-arrow-left"></em><span>Service Reports</span></a>
                                                </div>
                                            </li>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                
                            </div><!-- .card-inner -->
                            <div class="card-inner" id="show">
                                <div class="nk-tb-list nk-tb-ulist" id="load_data">
                                </div><!-- .nk-tb-list -->
                            
                                <div class="nk-block-between-md g-3" id="loadmore">
                                </div><!-- .nk-block-between -->
                            </div>
                            <div class="card-inner" id="form" style="display:none;">
                               <div class="row">
                                    <div class="nk-block-head-sub mb-3" id="prev" style="display:none;">
                                        <a class="back-to btn btn-outline-primary" id="back_btn" href="javascript:;"><em class="icon ni ni-arrow-left"></em><span>Service Reports</span></a>
                                    </div>
                                    <h5>Enter the Details for the Service Meeting Below</h5>
                                    <p class="text-danger">Always click the save record Button after update of attendance, first timers and new convert.</p>
                                    <?php echo form_open_multipart('service/report/manage', array('id'=>'bb_ajax_form', 'class'=>'row mt-4')); ?>
                                        <input type="hidden" name="report_id" id="report_id" value="<?php if(!empty($e_id)){echo $e_id;}?>">
                                        <?php 
                                        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                                        if ($ministry_id <= 0) { ?>
                                            <div class="col-sm-4 mb-3">
                                                <div class="form-group">
                                                    <label class="name">Ministry </label> 
                                                    <select id="ministry_id" name="ministry_id" class="js-select2 " onchange="load_level();">
                                                        <option value=" ">Select Ministry</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } else {?>
                                            <input type="hidden" id="ministry_id" value="<?=$ministry_id;?>">


                                        <?php } ?>
                                        <?php if($role != 'church leader'){
                                            $log_church_id = $this->Crud->read_field('id', $log_id, 'user',  'church_id');
                                            ?>
                                            <div class="col-sm-4 mb-3" >
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
                                                <label for="name">*<?=translate_phrase('Service Type'); ?></label>
                                                <select data-search="on" class=" js-select2" id="type" name="type" required>
                                                    <option value="0">Select</option>
                                                    <?php
                                                        $type = $this->Crud->read_order('service_type', 'name', 'asc');
                                                        if(!empty($type)){
                                                            foreach($type as $t){
                                                                echo '<option value="'.$t->id.'">'.ucwords($t->name).'</option>';
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
                                                    <input type="text" name="dates" id="dates" class="form-control date-picker" required>   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name"><?=translate_phrase('First Timer'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text" id="first_timer" name="first_timer" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" value="0">        
                                                          
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Attendance'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text"  name="attendance" id="attendance" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" placeholder="0">        
                                                         
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name"><?=translate_phrase('New Convert'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text" id="new_convert" class="form-control" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" name="new_convert" placeholder="0">        
                                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Offering'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text" name="offering" id="offering" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" placeholder="0">        
                                                           
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Tithe'); ?></label> 
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text"  name="tithe" id="tithe" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" placeholder="0">        
                                                           
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Partnership'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text"  name="partnership" id="partnership" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" placeholder="0">        
                                                       
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12 mb-3">
                                            <div class="form-group">
                                                <label for="name"><?=translate_phrase('Note'); ?></label>
                                                <textarea class="form-control"  id="note" name="note" ></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" name="attendant" id="attendant">
                                        <input type="hidden" name="converts" id="converts">
                                        <input type="hidden" name="timers" id="timers">
                                        <input type="hidden" name="tither" id="tither">
                                        <input type="hidden" name="offering_givers" id="offering_givers">
                                        <input type="hidden" name="partners" id="partners">
                                        
                                        <div class="col-sm-12 text-center mt-3">
                                            <button class="btn btn-primary bb_fo_btn" type="submit">
                                                <em class="icon ni ni-save"></em> <span><?=translate_phrase('Save Record');?></span>
                                            </button>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-sm-12 my-3"><div id="bb_ajax_msg"></div></div>
                                    </div>
                               </div>
                            </div>
                            <div class="card-inner" id="attendance_view" style="display:none;">
                                <div class="row">
                                    
                                    <span class="text-danger mb-2">Enter Attendance</span>
                                    <div class="col-sm-4 mb-3 ">
                                        <label>Total</label>
                                        <input class="form-control" id="total_attendance" type="text" name="total"  readonly value="0">
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label>Member</label>
                                        <input class="form-control" id="member_attendance" type="text" name="member"  value="0" placeholder="0">
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label>First Timer</label>
                                        <input class="form-control" id="guest_attendance" type="text" name="guest"  value="" placeholder="0">
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label>Male</label>
                                        <input class="form-control" id="male_attendance" type="text" name="male"  value="" placeholder="0">
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label>Female</label>
                                        <input class="form-control" id="female_attendance" type="text" name="female"  value="" placeholder="0">
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label>Children</label>
                                        <input class="form-control" id="children_attendance" type="text" name="children"  value="" placeholder="0">
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-5" >
                                    <div class="col-sm-12 text-center mt-5">
                                        <button class="btn btn-primary bb_fo_btn" type="submit">
                                            <i class="icon ni ni-save"></i> <span><?=translate_phrase('Save Record');?></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12"><div id="bb_ajax_msg2"></div></div>
                                </div>
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
<script>
    $(function() {
        load('', '');
    });
    
    function load_level(eChurchId){
        var ministry_id = $('#ministry_id').val();
        var level = $('#level').val();
        
        if(ministry_id !== ' ' && ministry_id !== 0 && level !== ' '){
            $.ajax({
                url: site_url + 'service/report/load_churches',
                data: {level:level,ministry_id:ministry_id},
                type: 'post',
                success: function (data) {
                    var dt = JSON.parse(data);
                    var cellSelect = $('#church_id');
                    cellSelect.empty(); 
                    cellSelect.append('<option value="">Select Church</option>');
                    
                    // Add options for each cell
                    dt.churches.forEach(function(cell) {
                        var selected = '';
                        if (cell.id === eChurchId) {
                            selected = 'selected';
                        }
                        cellSelect.append('<option value="' + cell.id + '" ' + selected + '>' + cell.name + '</option>');
                    });
                        
                }
            });
            $('#church_div').show(500);
                   
        }  else {
            $('#church_div').hide(500);
        }
    }

    function load_ministry(eMinistryId){
        $.ajax({
            type: 'POST',
            url: '<?=site_url('service/report/records/get_ministry')?>', // replace with your controller and function
            data: {ministry_id: eMinistryId},
            dataType: 'json',
            success: function(data) {
                $('#ministry_id').empty();
                if (data.length === 0) {
                    $('#ministry_id').append('<option value="">No Ministry found</option>'); // display a message if no regions are found
                } else {
                    $('#ministry_id').append('<option value=" ">Select Ministry</option>'); 
                            
                    $.each(data, function(index, ministry) {
                        var selected = '';
                        if (ministry.id === eMinistryId) {
                            selected = 'selected';
                        }
                        $('#ministry_id').append('<option value="' + ministry.id + '" ' + selected + '>' + ministry.name + '</option>');
                    });
                }
            }
        });
    }

    

    function load_church_level(eLevel, eChurchId){
        $.ajax({
            type: 'POST',
            url: '<?=site_url('service/report/records/get_church_level')?>', // replace with your controller and function
            data: {level: eLevel},
            dataType: 'json',
            success: function(data) {
                $('#level').empty();
                if (data.length === 0) {
                    $('#level').append('<option value="">No Level found</option>'); // display a message if no regions are found
                } else {
                     $('#level').append('<option value=" ">Select Level</option>'); 
                            
                    $.each(data, function(index, level) {
                        var selected = '';
                        if (level.id === eLevel) {
                            selected = 'selected';
                            
                        }
                        $('#level').append('<option value="' + level.id + '" ' + selected + '>' + level.name + '</option>');
                    });
                    if (eLevel !== 'all' && typeof eLevel !== 'number') {
                        $('#church_div').show(500);
                        load_level(eChurchId);
                    } else{
                        $('#church_div').hide(500);
                    }
                }
            }
        });
    }
    
    
    function session_church(){
        var church_id = $('#church_id').val();
        
        if(church_id !== ' '){
            $.ajax({
                url: site_url + 'service/report/church_select',
                data: {church_id:church_id},
                type: 'post',
                success: function (data) {
                }
            }); 
        } 
    }

    var initialInfo = {
        class: 'btn-outline-primary',
        onclick: 'add_report();',
        iconClass: 'ni-plus-c'
    };

    var newInfo = {
        class: 'btn-outline-success',
        onclick: 'load();',
        iconClass: 'ni-arrow-long-left'
    };
    var currentInfo = initialInfo;

    $('#add_btn').click(function() {
        $('#show').toggle(500);
        $('#form').toggle(500);
        document.getElementById("bb_ajax_form").reset();
        document.getElementById("type").value = '';
        $('#prev').hide(500);
        // Toggle between initial and new info
        currentInfo = (currentInfo === initialInfo) ? newInfo : initialInfo;
        load_ministry();load_church_level();
        $(this).attr('title', (currentInfo === initialInfo) ? 'Add Report' : 'Back to Reports');
        var markButton = document.getElementById("markButton");
        var convertBtn = document.getElementById("convertBtn");
        var timerBtn = document.getElementById("timerBtn");
        var partnerBtn = document.getElementById("partnerBtn");
        var titheBtn = document.getElementById("titheBtn");
        // Update button class, onclick function, and icon class
        $(this).removeClass().addClass('btn btn-icon ' + currentInfo.class);
        // $(this).attr('onclick', currentInfo.onclick);
        $(this).find('em').removeClass().addClass('icon ni ' + currentInfo.iconClass);
       
    });

    $('#back_btn').click(function() {
        $('#show').show(500);
        $('#form').hide(500);
        $('#attendance_view').hide(500);
        $('#attendance_prev').hide(500);
        $('#add_btn').show(500);
        
        $('#prev').hide(500);

    });

    function edit_report(id){
        var selectElement = document.getElementById("cells_id");
        var markButton = document.getElementById("markButton");
        var convertBtn = document.getElementById("convertBtn");
        var timerBtn = document.getElementById("timerBtn");
        var partnerBtn = document.getElementById("partnerBtn");
        var titheBtn = document.getElementById("titheBtn");
        $('#bb_ajax_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#form').show(500);
        $('#prev').show(500);
        currentInfo = (currentInfo === initialInfo) ? newInfo : initialInfo;

        // Update button class, onclick function, and icon class
        $(this).removeClass().addClass('btn btn-icon ' + currentInfo.class);
        // $(this).attr('onclick', currentInfo.onclick);
        $(this).find('em').removeClass().addClass('icon ni ' + currentInfo.iconClass);

        $.ajax({
            url: site_url + 'service/report/edit/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#report_id').val(dt.e_id);
                $('#type').val(dt.e_type).change();
                $("#partnership").val(dt.e_partnership).val();
                $("#tithe").val(dt.e_tithe).val();
                $('#dates').val(dt.e_date);
                $('#attendance').val(dt.e_attendance);
                $('#new_convert').val(dt.e_new_convert);
                $('#first_timer').val(dt.e_first_timer);
                $('#offering').val(dt.e_offering);
                $('#note').val(dt.e_note);
                $('#attendant').val(dt.e_attendant);
                $('#timers').val(dt.e_timers);
                $('#tither').val(dt.e_tithers);
                $('#partners').val(dt.e_partners);
                $('#converts').val(dt.e_converts);
                $('#ministry_id').val(dt.e_ministry_id);
                $('#level').val(dt.e_level);
                $('#church_id').val(dt.e_church_id);
                

                $('#bb_ajax_msg').html('');load_ministry(dt.e_ministry_id);
                load_church_level(dt.e_level,dt.e_church_id);
            }
        });

    }



    function attendance_report(id){
        $('#bb_ajax_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#attendance_view').show(500);
        $('#attendance_prev').show(500);
        
        $.ajax({
            url: site_url + 'service/report/manage/attendance/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#total_attendance').val(dt.total_attendance);
                $("#member_attendance").val(dt.member_attendance);
                $("#guest_attendance").val(dt.guest_attendance);
                $('#male_attendance').val(dt.male_attendance);
                $('#female_attendance').val(dt.female_attendance);
                $('#children_attendance').val(dt.children_attendance);

                $('#bb_ajax_msg').html('');
            }
        });
       
    }
    function updateTotals() {
        // Get values from the input fields
        var memberValue = parseInt($('#member_attendance').val()) || 0;
        var guestValue = parseInt($('#guest_attendance').val()) || 0;
        var maleValue = parseInt($('#male_attendance').val()) || 0;
        var femaleValue = parseInt($('#female_attendance').val()) || 0;
        var childrenValue = parseInt($('#children_attendance').val()) || 0;

        // Calculate the total
        var total = memberValue + guestValue + maleValue + femaleValue + childrenValue;

        // Update the total field
        $('#total_attendance').val(total);
    }

    // Attach event listeners to input fields
    $('#member_attendance, #guest_attendance, #male_attendance, #female_attendance, #children_attendance').on('input', updateTotals);
    
    function updatePageName() {
        var selectElement = document.getElementById("cells_id");
        var markButton = document.getElementById("markButton");
        var convertBtn = document.getElementById("convertBtn");
        var timerBtn = document.getElementById("timerBtn");
        var partnerBtn = document.getElementById("partnerBtn");
        var titheBtn = document.getElementById("titheBtn");
         
    }

    function load(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#load_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#loadmore').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

       
        var search = $('#search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'service/report/load' + methods,
            type: 'post',
            data: { search: search },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_data').html(dt.item);
                } else {
                    $('#load_data').append(dt.item);
                }
                $('#counta').html(dt.count);
                if (dt.offset > 0) {
                    $('#loadmore').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#loadmore').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }
</script>   

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<?=$this->endSection();?>