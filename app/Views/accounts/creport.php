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
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Cell Report');?></h3>
                            
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
                                                <a href="javascript:;"  id="add_btn" class="btn btn-icon btn-outline-primary mx-1"><em class="icon ni ni-plus-c"></em></a>
                                            </li><!-- li -->
                                           <li>
                                               <a href="javascript:;" id="filter_btn" onclick="filter_resp();" class="text-right btn mx-1 btn-icon btn-block btn-outline-danger"><em class="icon ni ni-filter"></em></a>
                                           </li>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                
                            </div><!-- .card-inner -->
                            <div class="card-inner" id="show">
                                <div class="row">
                                    <div class="col-sm-4 mb-3 filter_resp" style="display:none;">
                                        <div class="row">
                                            <div class="col-6 col-sm-6">
                                                <input type="date" class="form-control" name="start_date" id="start_date" oninput="loads()" style="border:1px solid #ddd;">
                                                <label for="name" class="small text-muted"><?=translate_phrase('START DATE');?></label>
                                            </div>
                                            <div class="col-6 col-sm-6"> 
                                                <input type="date" class="form-control" name="end_date" id="end_date" oninput="loads()" style="border:1px solid #ddd;">
                                                <label for="name" class="small text-muted"><?=translate_phrase('END DATE');?></label>
                                            </div>
                                            <div class="col-md-12" style="color: transparent;"><span id="date_resul"></span></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mb-3 filter_resp" style="display:none;">
                                        <select data-search="on" class=" js-select2" id="meeting_type" onchange="load();">
                                            <option value="all">All Meeting Type</option>
                                            <option value="wk1" >WK1 - Prayer and Planning</option>
                                            <option value="wk2" >WK2 - Bible Study</option>
                                            <option value="wk3" >WK3 - Bible Study</option>
                                            <option value="wk4" >WK4 - Fellowship / Outreach</option>
                                        </select>
                                    </div>
                                    <?php 
                                        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                                        if ($ministry_id <= 0) { ?>
                                        <div class="col-sm-3 mb-3 filter_resp" style="display:none;">
                                            <div class="form-group">
                                                <select id="ministry_id" name="ministry_id" class="js-select2 "  onchange="load_cells();">
                                                    <option value="all">All Ministry</option>
                                                    <?php
                                                        $ministries = $this->Crud->read_order('ministry', 'name', 'asc');
                                                        foreach ($ministries as $ministry) {
                                                            $selected = '';
                                                            echo '<option value="' . $ministry->id . '" ' . $selected . '>' . ucwords($ministry->name) . '</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } else {?>
                                        <input type="hidden" id="ministry_id" value="<?=$ministry_id;?>">


                                    <?php } ?>
                                    <?php if($role != 'church leader'){
                                        $log_church_id = $this->Crud->read_field('id', $log_id, 'user',  'church_id');
                                        ?>
                                        <div class="col-sm-2 mb-3 level_resp"  style="display:none;" id="level_resp">
                                            <select class="js-select2" name="level" id="level" onchange="load_level();">
                                                <option value="all">All Church Level</option>
                                                <?php if($log_church_id > 0){?>
                                                <option value="<?=$log_church_id; ?>">My Church</option>

                                               <?php }
                                                    $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

                                                    if($log_church_type == 'region'){
                                                        
                                                ?>
                                                
                                                    <option value="zone" >Zonal Church</option>
                                                    <option value="group" >Group Church</option>
                                                    <option value="church" >Church Assembly</option>
                                                <?php } elseif($log_church_type == 'zone'){?>
                                                
                                                    <option value="group" >Group Church</option>
                                                    <option value="church" >Church Assembly</option>

                                                <?php } elseif($log_church_type == 'group'){?>
                                                
                                                    <option value="church" >Church Assembly</option>

                                                <?php } else{?>
                                                    <option value="region">Regional Church</option>
                                                    <option value="zone" >Zonal Church</option>
                                                    <option value="group" >Group Church</option>
                                                    <option value="church" >Church Assembly</option>
                                                <?php } ?>
                                                
                                            </select>
                                        </div>

                                    <?php } ?>
                                    <div class="col-sm-3 mb-3 region_resp"  style="display:none;" id="region_resp">
                                        <select class="js-select2" name="region_id" id="region_id">
                                            <option value="all">All Region Church </option>
                                           
                                        </select>
                                    </div>
                                    
                                    <div class="col-sm-3 mb-3 zone_resp"  style="display:none;" id="zone_resp">
                                        <select class="js-select2" name="zone_id" id="zone_id" >
                                            <option value="all">All Zonal Church </option>
                                           
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mb-3 group_resp"  style="display:none;" id="group_resp">
                                        <select class="js-select2" name="group_id" id="group_id" >
                                            <option value="all">All Group Church </option>
                                           
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mb-3 church_resp"  style="display:none;" id="church_resp">
                                        <select class="js-select2" name="church_id" id="church_id" >
                                            <option value="all">All Church Assembly</option>
                                           
                                        </select>
                                    </div>
                                    
                                    <div class="col-sm-3 mb-3 filter_resp" style="display:none;" id="cell_resp">
                                        <select class="form-select js-select2" id="cell_idz" onchange="load();"
                                            data-placeholder="All Cell">
                                            <option value="all">All Cell</option>
                                            
                                        </select>
                                    </div>

                                </div>

                                <div class="card-inner table-responsive p-0">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text"><?php echo translate_phrase('Date'); ?></th>
                                                <th class="text"><?php echo translate_phrase('Meeting'); ?></th>
                                                <th class="text"><?php echo translate_phrase('Offering'); ?></th>
                                                <th class="text"><?php echo translate_phrase('Attendance'); ?></th>
                                                <th class="text"><?php echo 'FT'; ?></th>
                                                <th class="text"><?php echo 'NC'; ?></th>
                                                <th class="text text-center"><?php echo translate_phrase('Actions'); ?></th>
                                            </tr>
                                        </thead>

                                        <tbody id="load_data"></tbody>
                                        <tfoot id="loadmore"></tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="card-inner" id="form" style="display:none;">
                                <div class="row">
                                    <div class="nk-block-head-sub mb-3" id="prev" style="display:none;">
                                        <a class="back-to" id="back_btn" href="javascript:;"><em class="icon ni ni-arrow-left"></em><span>Cell Reports</span></a>
                                    </div>
                                    <h5>Enter the Details for the Cell Meeting Below</h5>
                                    <p class="text-danger">Always click the save record Button after update of attendance, first timers and new convert.</p>
                                    <?php echo form_open_multipart('accounts/creport/manage', array('id'=>'bb_ajax_form', 'class'=>'row mt-4')); ?>
                                        <input type="hidden" name="creport_id" id="report_id" value="<?php if(!empty($e_id)){echo $e_id;}?>">
                                        
                                        <?php 
                                        $celss = $this->Crud->read_field('id', $log_id, 'user', 'cell_id');
                                        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                                        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                                        
                                        if($role == 'cell executive' || $role == 'cell leader' || $role == 'assistant cell leader'){?>

                                            <input type="hidden" name="cell_id" value="<?=$this->Crud->read_field('id', $log_id, 'user', 'cell_id'); ?>">
                                        <?php } else { ?>
                                            
                                            <div class="col-md-6 col-lg-4 col-xxl-3">
                                                <div class="form-group">
                                                    <label class="form-labl">Cell</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select js-select2" id="cell_id" name="cell_id" onchange="updatePageName();"
                                                            data-placeholder="Select Cell">
                                                            <option value="">Select</option>
                                                            <?php
                                                                if($ministry_id == 0){
                                                                    $parent  = $this->Crud->read_order('cells', 'name', 'asc');
                                                                }
                                                                if($ministry_id > 0){
                                                                    $parent  = $this->Crud->read_single_order('ministry_id',  $ministry_id, 'cells', 'name', 'asc');
                                                                }
                                                                if($ministry_id > 0 && $church_id > 0){
                                                                    $parent  = $this->Crud->read_single_order('church_id',  $church_id, 'cells', 'name', 'asc');
                                                                }

                                                                if(!empty($parent)){
                                                                    foreach($parent as $p){
                                                                        $church = $this->Crud->read_field('id', $p->church_id, 'church', 'name');
                                                                        $sel = '';
                                                                        if(!empty($e_cell_id)){
                                                                            if($e_cell_id == $p->id){
                                                                                $sel = 'selected';
                                                                            }
                                                                        }
                                                                        echo '<option value="'.$p->id.'" '.$sel.'>'.ucwords($p->name.' - '.$church).'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        <?php } ?>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Meeting Type'); ?></label>
                                                <select data-search="on" class=" js-select2" id="type" name="type">
                                                    <option value="">Select</option>
                                                    <option value="wk1" >WK1 - Prayer and Planning</option>
                                                    <option value="wk2" >WK2 - Bible Study</option>
                                                    <option value="wk3" >WK3 - Bible Study</option>
                                                    <option value="wk4" >WK4 - Fellowship / Outreach</option>
                                                    <option value="wk5" >WK5 - Fellowship</option>
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
                                                <label for="name">*<?=translate_phrase('Marked Attendance'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text" name="attendance" id="attendance" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" readonly placeholder="">        
                                                        <div class="input-group-append">            
                                                            <button type="button"  class="btn btn-outline-primary btn-dim pop" pageTitle="Mark Meeting Attendance" pageSize="modal-lg" pageName="<?php echo  site_url('accounts/creport/manage/attendance/'.$celss); ?>" id="markButton">MARK</button>        
                                                        </div>    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Offering'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text" name="offering" id="offering" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" readonly placeholder="0">        
                                                        <div class="input-group-append">            
                                                            <button type="button"  class="btn btn-outline-primary btn-dim pop" pageTitle="Enter Meeting Offering" pageSize="modal-lg" pageName="<?php echo  site_url('accounts/creport/manage/offering/'.$celss); ?>" id="offeringButton">ENTER RECORD</button>        
                                                        </div>     
                                                    </div>    
                                                </div>
                                                <span class="text-danger"></span>
                                                
                                            </div>
                                        </div>
                                        <div class="col-sm-4 my-3">
                                            <a href="javascript:;" id="firstTimerBtnz"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Add First Timer"
                                                    class="btn btn-block btn-dim btn-outline-info pop mt-3 mx-2"
                                                    pageTitle="<?=translate_phrase('Add First Timer');?>"
                                                    pageName="<?= site_url('accounts/creport/manage/timers'); ?>"
                                                    pageSize="modal-xl">
                                                    <em class="icon ni ni-plus-c"></em><span> Add First Timer</span>
                                            </a>
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
                                        <input type="hidden" name="offering_givers" id="offering_givers">
                                        
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
        <?php if($role == 'church leader'){?>
            load_cells();
        <?php } ?>
    });
    
    function filter_resp(){
        load_cells();
        $('.filter_resp').show(500);
        $('#filter_btn').attr('onclick', 'filter_back()');

    }
    function filter_back(){
        $('.filter_resp').hide(500);
        $('.cell_resp').hide(500);
        $('.level_resp').hide(500);
        $('#filter_btn').attr('onclick', 'filter_resp()');

    }

    function load_level(){
        var level = $('#level').val();
        $('.region_resp').hide(500);
        $('.zone_resp').hide(500);
        $('.group_resp').hide(500);
        $('.church_resp').hide(500);
        if(level === 'all'){
            $('.region_resp').hide(500);
            $('.zone_resp').hide(500);
            $('.group_resp').hide(500);
            $('.church_resp').hide(500);
        }
        if(level === 'region'){
            $('.region_resp').show(500);
            $('.zone_resp').hide(500);
            $('.group_resp').hide(500);
            $('.church_resp').hide(500);
        }
        if(level === 'zone'){
            $('.region_resp').hide(500);
            $('.zone_resp').show(500);
            $('.group_resp').hide(500);
            $('.church_resp').hide(500);
        }
        if(level === 'group'){
             $('.region_resp').hide(500);
            $('.group_resp').show(500);
            $('.zone_resp').hide(500);
            $('.church_resp').hide(500);
        }
        if(level === 'church'){
             $('.region_resp').hide(500);
             $('.zone_resp').hide(500);
             $('.group_resp').hide(500);
            $('.church_resp').show(500);
        }
        load_cells();
    }

    var initialInfo = {
        class: 'btn-outline-primary',
        onclick: 'add_report();',
        iconClass: 'ni-plus-c'
    };

    var newInfo = {
        class: 'btn-outline-success',
        onclick: 'load();',
        iconClass: 'ni-eye'
    };
    var currentInfo = initialInfo;

    $('#add_btn').click(function() {
        $('#show').toggle(500);
        $('#form').toggle(500);
        document.getElementById("bb_ajax_form").reset();
        document.getElementById("cells_id").value = '';
        document.getElementById("type").value = '';
        $('#prev').hide(500);
        // Toggle between initial and new info
        currentInfo = (currentInfo === initialInfo) ? newInfo : initialInfo;

        updatePageName();
        // Update button class, onclick function, and icon class
        $(this).removeClass().addClass('btn btn-icon ' + currentInfo.class);
        // $(this).attr('onclick', currentInfo.onclick);
        $(this).find('em').removeClass().addClass('icon ni ' + currentInfo.iconClass);
    });

    $('#back_btn').click(function() {
        $('#show').show(500);
        $('#form').hide(500);
        
        $('#prev').hide(500);

    });

    function edit_report(id){
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
            url: site_url + 'accounts/creport/edit/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#report_id').val(dt.e_id);
                $('#type').val(dt.e_type).change();;
                $('#cell_id').val(dt.e_cell_id);
                $('#dates').val(dt.e_date);
                $('#attendance').val(dt.e_attendance);
                $('#new_convert').val(dt.e_new_convert);
                $('#first_timer').val(dt.e_first_timer);
                $('#offering').val(dt.e_offering);
                $('#note').val(dt.e_note);
                $('#attendant').val(dt.e_attendant);
                $('#timers').val(dt.e_timers);
                $('#converts').val(dt.e_converts);
                
                var url = site_url + 'accounts/creport/manage/attendance';
                var updatedPageName = url + "/" + dt.e_cell_id + "/" + dt.e_id;
                markButton.setAttribute("pageName", updatedPageName);

              
                var offeringBtn = document.getElementById("offeringButton");
                var urls = site_url + 'accounts/creport/manage/offering';
                var updatedPageName = urls + "/" + dt.e_cell_id + "/" + dt.e_id;
                offeringBtn.setAttribute("pageName", updatedPageName);
                
                $('#bb_ajax_msg').html('');
               
            }
        });

    }

    function updatePageName() {
        var selectElement = document.getElementById("cells_id");
        var markButton = document.getElementById("markButton");
        var offeringBtn = document.getElementById("offeringButton");
        var firstTimerBtn = document.getElementById("firstTimerBtnz");
        
        var selectedValue = selectElement.value;

        // Attendance URL
        var url = site_url + 'accounts/creport/manage/attendance';
        var updatedPageName = url + "/" + selectedValue;
        if (markButton) markButton.setAttribute("pageName", updatedPageName);

        // Offering URL
        var urls = site_url + 'accounts/creport/manage/offering';
        var updatedPageName2 = urls + "/" + selectedValue;
        if (offeringBtn) offeringBtn.setAttribute("pageName", updatedPageName2);

        // First Timer URL
        var timerUrl = site_url + 'accounts/creport/manage/timers';
        var updatedTimerUrl = timerUrl + "/" + selectedValue;
        if (firstTimerBtn) firstTimerBtn.setAttribute("pageName", updatedTimerUrl);
    }



    function load(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#load_data').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#loadmore').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }

       
        var search = $('#search').val();
        var cell_id = $('#cell_id').val();
        var level = $('#level').val();
        var meeting_type = $('#meeting_type').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var region_id = $('#region_id').val();
        var zone_id = $('#zone_id').val();
        var group_id = $('#group_id').val();
        var church_id = $('#church_id').val();
        //alert(status);

        $.ajax({
            url: site_url + 'accounts/creport/load' + methods,
            type: 'post',
            data: { search: search,cell_id: cell_id,level: level,meeting_type: meeting_type,start_date: start_date,end_date: end_date,region_id: region_id,zone_id: zone_id,group_id: group_id,church_id: church_id},
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_data').html(dt.item);
                } else {
                    $('#load_data').append(dt.item);
                }
                
                if (dt.offset > 0) {
                    $('#loadmore').html('<tr><td colspan="8"><a href="javascript:;" class="btn btn-light btn-block p-30" onclick="load(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a></td></tr>');
                } else {
                    $('#loadmore').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    function load_cells(){
        var level = $('#level').val();
        var ministry_id = $('#ministry_id').val();
        var region_id = $('#region_id').val();
        var zone_id = $('#zone_id').val();
        var group_id = $('#group_id').val();
        var church_id = $('#church_id').val();
        $.ajax({
            url: site_url + 'accounts/creport/load_cells',
            data: {level:level,ministry_id:ministry_id,region_id:region_id,zone_id:zone_id,group_id:group_id,church_id:church_id},
            type: 'post',
            success: function (data) {
                var dt = JSON.parse(data);
                
                if(dt.level_status === true){
                    var cellSelect = $('#cell_idz');
                    cellSelect.empty(); 
                    cellSelect.append('<option value="all">All Cell</option>');
                    
                    // Add options for each cell
                    dt.cells.forEach(function(cell) {
                        cellSelect.append('<option value="' + cell.id + '">' + cell.name + '</option>');
                    });
                    var cellSelect = $('#region_id');
                    cellSelect.empty(); 
                    cellSelect.append('<option value="all">All Region Churches</option>');
                    
                    // Add options for each cell
                    dt.region_list.forEach(function(cell) {
                        cellSelect.append('<option value="' + cell.id + '">' + cell.name + '</option>');
                    });
                    var cellSelect = $('#zone_id');
                    cellSelect.empty(); 
                    cellSelect.append('<option value="all">All Zone Churches</option>');
                    
                    // Add options for each cell
                    dt.zone_list.forEach(function(cell) {
                        cellSelect.append('<option value="' + cell.id + '">' + cell.name + '</option>');
                    });
                    var cellSelect = $('#group_id');
                    cellSelect.empty(); 
                    cellSelect.append('<option value="all">All Group Churches</option>');
                    
                    // Add options for each cell
                    dt.group_list.forEach(function(cell) {
                        cellSelect.append('<option value="' + cell.id + '">' + cell.name + '</option>');
                    });
                    var cellSelect = $('#church_id');
                    cellSelect.empty(); 
                    cellSelect.append('<option value="all">All Church Assembly</option>');
                    
                    // Add options for each cell
                    dt.church_list.forEach(function(cell) {
                        cellSelect.append('<option value="' + cell.id + '">' + cell.name + '</option>');
                    });
                    $('#level_resp').show(500);
                    load();
                } else {
                    $('#level_resp').hide(500);
                }
            }
        });
    }

    $(document).ready(function() {
        // Function to fetch and update zones
        function updateZones(regionId) {
            if (regionId !== 'all') {
                $.ajax({
                    url: site_url + 'accounts/creport/records/load_zones',
                    data: { region_id: regionId },
                    type: 'post',
                    success: function(data) {
                        const dt = JSON.parse(data);
                        const zoneSelect = $('#zone_id');
                        zoneSelect.empty().append('<option value="all">All Zonal Church</option>');
                        dt.zones.forEach(zone => {
                            zoneSelect.append(`<option value="${zone.id}">${zone.name}</option>`);
                        });
                        $('#zone_resp').show(500);
                        updateCells(dt.cells); // Update cells for the selected region
                    }
                });
            } else {
                $('#zone_resp').hide(500);
                updateCells([]); // Clear cells if no region is selected
            }
        }

        // Function to fetch and update groups
        function updateGroups(zoneId) {
            if (zoneId !== 'all') {
                $.ajax({
                    url: site_url + 'accounts/creport/records/load_groups',
                    data: { zone_id: zoneId },
                    type: 'post',
                    success: function(data) {
                        const dt = JSON.parse(data);
                        const groupSelect = $('#group_id');
                        groupSelect.empty().append('<option value="all">All Group Church</option>');
                        dt.groups.forEach(group => {
                            groupSelect.append(`<option value="${group.id}">${group.name}</option>`);
                        });
                        $('#group_resp').show(500);
                        updateCells(dt.cells); // Update cells for the selected zone
                    }
                });
            } else {
                $('#group_resp').hide(500);
                updateCells([]); // Clear cells if no zone is selected
            }
        }

        // Function to fetch and update churches
        function updateChurches(groupId) {
            if (groupId !== 'all') {
                $.ajax({
                    url: site_url + 'accounts/creport/records/load_churches',
                    data: { group_id: groupId },
                    type: 'post',
                    success: function(data) {
                        const dt = JSON.parse(data);
                        const churchSelect = $('#church_id');
                        churchSelect.empty().append('<option value="all">All Church Assembly</option>');
                        dt.churches.forEach(church => {
                            churchSelect.append(`<option value="${church.id}">${church.name}</option>`);
                        });
                        $('#church_resp').show(500);
                        updateCells(dt.cells); // Update cells for the selected group
                    }
                });
            } else {
                $('#church_resp').hide(500);
                updateCells([]); // Clear cells if no group is selected
            }
        }

        // Function to fetch and update cells
        function updateCells(cells = []) {
            const cellSelect = $('#cell_id');
            cellSelect.empty().append('<option value="all">All Cell</option>');
            cells.forEach(cell => {
                cellSelect.append(`<option value="${cell.id}">${cell.name}</option>`);
            });
            $('#cell_resp').show(500);
        }

        // Event handlers for dropdown changes
        $('#region_id').change(function() {
            const regionId = $(this).val();
            updateZones(regionId);
            // Clear subsequent dropdowns
            $('#zone_id').val('all').trigger('change');
            $('#group_id').val('all').trigger('change');
            $('#church_id').val('all').trigger('change');
            load();
        });

        $('#zone_id').change(function() {
            const zoneId = $(this).val();
            updateGroups(zoneId);
            // Clear subsequent dropdowns
            $('#group_id').val('all').trigger('change');
            $('#church_id').val('all').trigger('change');
            load();
        });

        $('#group_id').change(function() {
            const groupId = $(this).val();
            updateChurches(groupId);
            // Clear subsequent dropdown
            $('#church_id').val('all').trigger('change');
            load();
        });

        $('#church_id').change(function() {
            const churchId = $(this).val();
            if (churchId !== 'all') {
                $.ajax({
                    url: site_url + 'accounts/creport/records/load_cells',
                    data: { church_id: churchId },
                    type: 'post',
                    success: function(data) {
                        const dt = JSON.parse(data);
                        updateCells(dt.cells); // Update cells for the selected church
                        load();
                    }
                });
            } else {
                updateCells([]); // Clear cells if no church is selected
            }
        });
    });


</script>   

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<?=$this->endSection();?>