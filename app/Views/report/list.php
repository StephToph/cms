<?php
use App\Models\Crud;
$this->Crud = new Crud();

$this->session = \Config\Services::session();
?>

<?= $this->extend('designs/backend'); ?>
<?= $this->section('title'); ?>
<?= $title; ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="nk-content">
    <div class="container-fluid mt-3">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?= translate_phrase('Generate Report'); ?></h3>

                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="row card-inner">
                            <div class="col-sm-4 my-2">
                                <button type="button" id="church_btn" class="btn btn-primary  btn-round  btn-block">
                                    <em class="icon ni ni-building"></em> <span>Generate Church Report</span> 
                                </button>
                            </div>
                            <div class="col-sm-4 my-2">
                                <button type="button" id="member_btn" class="btn btn-info  btn-round  btn-block">
                                    <em class="icon ni ni-user"></em> <span>Generate Member Report</span> 
                                </button>
                            </div>
                            <div class="col-sm-4 my-2">
                                <button type="button" id="event_btn" class="btn btn-danger  btn-round  btn-block">
                                    <em class="icon ni ni-text-rich"></em> <span>Generate Event Report</span> 
                                </button>
                            </div>
                            <div class="col-sm-9 my-2">
                                <div class="btn-group align-items-center" id="data-resp" style="display:none;">
                                    <div class="form-group">    
                                        <div class="form-control-wrap">        
                                            <div class="input-daterange date-picker-range input-group">            
                                                <input type="text" name="start_date" id="start_date" oninput="loads()" onchange="loads();" class="form-control" />            
                                                <div class="input-group-addon">TO</div>            
                                                <input type="text" name="end_date" id="end_date" oninput="loads()" onchange="loads();"  class="form-control" />        
                                            </div>    
                                        </div>
                                        <label class="form-label">Date Range</label>    
                                        
                                    </div>
                                   
                                </div>
                                <div class="btn-group" id="date-resp" style="display:none;">
                                    <div class="form-group mx-2 ">           
                                        <input type="text" id="date1" onchange="load_dates();" oninput="load_dates();" class="form-control date-picker">    
                                        <label class="form-label">Date 1</label>   
                                    </div>
                                           
                                    <div class="form-group mx-2 ">         
                                        <input type="text" id="date2" onchange="load_dates();" oninput="load_dates();" class="form-control date-picker">    
                                        <label class="form-label">Date 2</label>  
                                    </div>
                                   
                                </div>

                                <div class="col-md-12" style="color:transparent;  text-white align:right;"><span id="date_resul"></span></div>
                            </div>
                            <div class="col-sm-3 my-2">
                                <input type="hidden" id="date_type" value="This_Month">
                                <a href="javascript:;" class="dropdown-toggle btn btn-white btn-block btn  btn-outline-secondary" data-bs-toggle="dropdown"><em class="  icon ni ni-calender-date"></em><span id="filter_type"><span class="" id="filter_type"><?=translate_phrase('This'); ?></span> <?=translate_phrase('Month'); ?></span></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="javascript:;" class="typeBtn" data-value="Today"><span><?=translate_phrase('Today');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="Yesterday"><span><?=translate_phrase('Yesterday');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="Last_Week"><span><?=translate_phrase('Last 7 Days');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn active" data-value="This_Month"><span><?=translate_phrase('This Month');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="This_Year"><span><?=translate_phrase('This Year');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="Last_Month"><span><?=translate_phrase('Last 30 Days');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="Date_Range"><span><?=translate_phrase('Date Range');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="Two_Date"><span><?=translate_phrase('Seperate Dates');?></span></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div id="church_resp" class="col-sm-12 my-2 row" style="display:none;">
                            <?php
                                $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                                if ($ministry_id <= 0) { ?>
                                    <div class="col-sm-4 mb-3">
                                        <div class="form-group">
                                            <label class="name">Ministry </label>
                                            <select id="ministry_id" name="ministry_id" class="js-select2 "
                                                onchange="load_level();">
                                                <option value=" ">Select Ministry</option>
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

                                    <div class="col-sm-4 mb-3" >
                                        <div class="form-group">
                                            <label>Church</label>
                                            <select class="js-select2" data-search="on" name="church_id" id="church_id">
                                                <option value=" ">Select Level First</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mb-3" id="church_div" style="display:none;">
                                        <div class="form-group">
                                            <label>Church Type</label>
                                            <select class="js-select2" data-search="on" name="church_type" id="church_type">
                                                <option value="individual">Individual Church</option>
                                                <option value="general">General Church</option>
                                            </select>
                                        </div>
                                    </div>
                                <?php } else{?>

                                    <input type="hidden" id="church_id" value="<?=$this->Crud->read_field('id', $log_id, 'user', 'church_id'); ?>">
                                <?php } ?>
                                <div class="col-sm-12 my-2">
                                    <button type="button" class="btn btn-outline-primary btn-block" onclick="generate_church();"><span>Generate</span><em class="icon ni ni-search"></em></button>
                                </div>
                            </div>
                           
                        </div>
                        <div class="row card-inner">
                            <div class="col-12 my-3" id="generate_resp">

                            </div>
                        </div>
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

<script>var site_url = '<?php echo site_url(); ?>';</script>
<script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
   
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script>
     $('.typeBtn').click(function() {
        $('#date_type').val($(this).attr('data-value'));
        $('#filter_type').html($(this).html());
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
        $('#date-resp').hide(300);
        $('#data-resp').hide(300);
        if ($(this).attr('data-value') == 'Date_Range') {
            $('#data-resp').show(300);
        } else {
            if ($(this).attr('data-value') == 'Two_Date') {
                $('#date-resp').show(300);
            } else{
                $('#data-resp').hide(300);
                load();
            }
           
        }
    });

    $('#church_btn').click(function() {
       $('#church_resp').toggle(500);
    });

    
    function load_level(){
        var level = $('#level').val();
        var ministry_id = $('#ministry_id').val();
       
        
        if(ministry_id !== ' ' && ministry_id !== 0 && level !== ' '){
            $.ajax({
                url: site_url + 'service/report/load_churches',
                data: {level:level,ministry_id:ministry_id},
                type: 'post',
                success: function (data) {
                    var dt = JSON.parse(data);
                    var cellSelect = $('#church_id');
                    cellSelect.empty(); 
                    cellSelect.append('<option value="0">Select Church</option>');
                    
                    // Add options for each cell
                    dt.churches.forEach(function(cell) {
                        var selected = '';
                        cellSelect.append('<option value="' + cell.id + '" ' + selected + '>' + cell.name + '</option>');
                    });
                    $('#church_div').show(500);
                }
            });
        }  else{
            $('#church_div').hide(500);
            var cellSelect = $('#church_id');
            cellSelect.empty(); 
            cellSelect.append('<option value=" ">Empty Church</option>');
                    
        }
    }

    
    function loads() {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        if(!start_date || !end_date){
            $('#date_resul').css('color', 'Red');
            $('#date_resul').html('<?=translate_phrase('Enter Start and End Date');?>!!');
        } else if(start_date > end_date){
            $('#date_resul').css('color', 'Red');
            $('#date_resul').html('<?=translate_phrase('Start Date cannot be greater');?>!');
        } else {
            $('#date_resul').html('');
            
        }
    }

    function load_dates() {
        var start_date = $('#date1').val();
        var end_date = $('#date2').val();

        if(!start_date || !end_date){
            $('#date_resul').css('color', 'Red');
            $('#date_resul').html('<?=translate_phrase('Enter Both Dates');?>!!');
        } else {
            $('#date_resul').html('');
            
        }
    }

    function generate_church(){
        $('#generate_resp').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div> <span>Processing Request..</span></div>');

        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var date1 = $('#date1').val();
        var date2 = $('#date2').val();
        var date_type = $('#date_type').val();
        
        var church_id = $('#church_id').val();
        var ministry_id = $('#ministry_id').val();
        var level = $('#level').val();
        var church_type = $('#church_type').val();
        
        $.ajax({
            url: site_url + 'report/generate/type/church',
            data: {level:level,ministry_id:ministry_id,church_id:church_id,church_type:church_type,date_type:date_type,date1:date1,date2:date2,start_date:start_date,end_date:end_date},
            type: 'post',
            success: function (data) {
                $('#generate_resp').html(data);
                
            }
        });
    }
</script>
<?= $this->endSection(); ?>