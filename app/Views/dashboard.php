<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>
<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
<?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block g-gs">
                    <div class="mt-1 d-lg-none">.</div>
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Dashboard Overview'); ?></h3>
                        </div>
                        
                    </div>
                </div>
                <div class="nk-block">
                    <div class="d-flex flex-row-reverse">
                        <div class="btn-group align-items-center" id="data-resp" style="display:none;">
                            &nbsp;|&nbsp;<b><?=translate_phrase('Date');?>:</b>&nbsp;
                            <input type="date" class="form-control" name="date_from" id="date_from" oninput="load()" style="border:1px solid #ddd;" placeholder="<?=translate_phrase('START DATE');?>">
                            &nbsp;<i class="anticon anticon-arrow-right"></i>&nbsp;
                            <input type="date" class="form-control" name="date_to" id="date_to" oninput="load()" style="border:1px solid #ddd;" placeholder="<?=translate_phrase('END DATE');?>">
                        </div>

                        <div class="" style="color:transparent;  text-white align:right;"><span id="date_resul"></span></div>

                        <div class=" mx-1 mb-1">
                            <input type="hidden" id="date_type"value="This Month">
                            <div class="drodown">
                                <a href="javascript:;" class="dropdown-toggle btn btn-white btn  btn-outline-light" data-bs-toggle="dropdown"><em class="  icon ni ni-calender-date"></em><span id="filter_type"><span class="" id="filter_type"><?=translate_phrase('This'); ?></span> <?=translate_phrase('Month'); ?></span></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="javascript:;" class="typeBtn" data-value="Today"><span><?=translate_phrase('Today');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="Yesterday"><span><?=translate_phrase('Yesterday');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="Last_Week"><span><?=translate_phrase('Last 7 Days');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn active" data-value=""><span><?=translate_phrase('This Month');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="This_Year"><span><?=translate_phrase('This Year');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="Last_Month"><span><?=translate_phrase('Last 30 Days');?></span></a></li>
                                        <li><a href="javascript:;" class="typeBtn" data-value="Date_Range"><span><?=translate_phrase('Date Range');?></span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php 
                            $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
			
                            if($church_id > 0){
                                $checked = '';
                                $default_cur = $this->Crud->read_field('id', $church_id, 'church', 'default_currency');
                                $country_id = $this->Crud->read_field('id', $church_id, 'church', 'country_id');
                                if($country_id > 0 && $default_cur > 0){
                                    $checked = 'checked';
                                }
                                ?>
                            <div class=" mb-1">
                                <div class="form-group ">
                                    <div class="custom-control custom-checkbox custom-control-pro no-control">            
                                        <input type="checkbox" class="custom-control-input" name="btnRadio"  <?=$checked; ?> id="currency">            
                                        <label class="custom-control-label" for="currency"><em class=" icon ni ni-tranx"></em>
                                        <span><?=translate_phrase('Switch Currency'); ?> </span></label>        
                                    </div> 
                                </div>
                                
                            </div>
                            <div class="mb-1">
                                <span class="" id="currency_resp"></span>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row g-gs">
                        <?php if($role != 'cell leader' && $role != 'cell executive' & $role != 'assistant cell_leader'){?>
                            
                            <div class="col-md-4">
                                <div class="card card-bordered text-white bg-primary card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Total Membership'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total Membership'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount text-white" id="membership"> 0 <span class="currency currency-usd"></span></span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-bordered  card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Total First Timer'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total First Timer'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="first_timer"> 0 <span class="currency currency-usd"></span></span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-bordered  card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Total New Convert'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total New Convert'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="new_convert"> 0 <span  class="currency currency-usd"></span></span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Prospective Students'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Prospective Students'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="prospective"> 0 </span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-bordered  card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase(' Foundation Students'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total Foundation Students'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="students"> 0 <span
                                                    class="currency currency-usd"></span></span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-bordered  card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Foundation Graduates'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total Graduates'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="graduates"> 0 <span
                                                    class="currency currency-usd"></span></span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-bordered card-full">
                                    <a href="javascript:;" class="pop" pageName="<?=site_url('dashboard/records/finance/service_offering'); ?>" pageTitle="Service Offering Records" pageSize="modal-xl">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Total Service Offering');?></h6>
                                                </div>
                                                <div class="card-tools">
                                                    <em class="card-hint icon ni ni-help-fill"
                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                        title="<?=translate_phrase('Total Service Offering'); ?>"></em>
                                                    </div>
                                            </div>
                                            <div class="card-amount">
                                                <span class="amount" id="offering"> 0.00 <span
                                                        class="currency currency-usd"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-bordered card-full">
                                    <a href="javascript:;" class="pop" pageName="<?=site_url('dashboard/records/finance/cell_offering'); ?>" pageTitle="Cell Offering Records" pageSize="modal-xl">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Total Cell Offering');?></h6>
                                                </div>
                                                <div class="card-tools">
                                                    <em class="card-hint icon ni ni-help-fill"
                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                        title="Total Cell Offering"></em>
                                                    </div>
                                            </div>
                                            <div class="card-amount">
                                                <span class="amount cell_offering" id="cell_offering"> 0.00 <span
                                                        class="currency currency-usd"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-bordered card-full">
                                    <a href="javascript:;" class="pop" pageName="<?=site_url('dashboard/records/finance/service_tithe'); ?>" pageTitle="Service Tithe Records" pageSize="modal-xl">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Total Service Tithe');?></h6>
                                                </div>
                                                <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                        title="Total Service Tithe"></em></div>
                                            </div>
                                            <div class="card-amount"><span class="amount" id="tithe"> 0.00 
                                                <span class="currency currency-usd"></span></span>
                                            </div>
                                            <div class="invest-data">
                                                <div class="invest-data-amount g-2">
                                                    <div class="invest-data-history">
                                                        <div class="title"><?=translate_phrase('Participant');?></div>
                                                        <div class="amount" id="tithe_part">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-bordered  card-full">
                                    <a href="javascript:;" class="pop" pageName="<?=site_url('dashboard/records/finance/partnership'); ?>" pageTitle="Partnership Records" pageSize="modal-xl">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Total Partnership'); ?></h6>
                                                </div>
                                                <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                        title="<?=translate_phrase('Total Partnership'); ?>"></em>
                                                    </div>
                                            </div>
                                            <div class="card-amount"><span class="amount" id="partnership"> 0.00 <span
                                                        class="currency currency-usd"></span></span></div>
                                            <div class="invest-data">
                                                <div class="invest-data-amount g-2">
                                                    <div class="invest-data-history">
                                                        <div class="title"><?=translate_phrase('Participant'); ?></div>
                                                        <div class="amount" id="partnership_part">0 </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php } else {?>
                            <div class="col-md-3">
                                <div class="card card-bordered  card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Total Membership'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total Membership'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="membership"> 0 <span
                                                    class="currency currency-usd"></span></span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Prospective Students'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Prospective Students'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="prospective"> 0 </span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-bordered  card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase(' Foundation Students'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total Foundation Students'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="students"> 0 <span
                                                    class="currency currency-usd"></span></span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-bordered  card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Foundation Graduates'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total Graduates'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="graduates"> 0 <span
                                                    class="currency currency-usd"></span></span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-bordered card-full">
                                    <a href="javascript:;" class="pop" pageName="<?=site_url('dashboard/records/finance/cell_offering'); ?>" pageTitle="Membership" pageSize="modal-xl">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Total Cell Offering'); ?></h6>
                                            </div>
                                            <div class="card-tools">
                                                <em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total Cell Offering'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount">
                                            <span class="amount cell_offering" id="cell_offerings"> 0.00 <span
                                                    class="currency currency-usd"></span></span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-bordered  card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Total First Timer' ); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total First Timer' ); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="first_timer"> 0 <span
                                                    class="currency currency-usd"></span></span></div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-bordered  card-full">
                                    <div class="card-inner">
                                        <div class="card-title-group align-start mb-0">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('Total New Convert'); ?></h6>
                                            </div>
                                            <div class="card-tools"><em class="card-hint icon ni ni-help-fill"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="<?=translate_phrase('Total New Convert'); ?>"></em>
                                                </div>
                                        </div>
                                        <div class="card-amount"><span class="amount" id="new_convert"> 0 <span
                                                    class="currency currency-usd"></span></span></div>
                                        
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                            
                        <div class="col-md-12">
                            <div class="card card-bordered card-full">
                                <div class="card-inner d-flex flex-column h-100">
                                    <div class="card-title-group mb-3">
                                        <div class="card-title me-1">
                                            <h6 class="title"><?=translate_phrase('Partnership Section'); ?></h6>
                                        </div>
                                    </div>
                                    <div class="progress-list gy-3" id="partnership_list">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="card card-bordered h-100">
                                <div class="card-inner mb-n2">
                                    <div class="card-title-group">
                                        <div class="card-title ">
                                            <h6 class="title"><?=translate_phrase('Cell Report'); ?></h6>
                                        </div>
                                        <div class="card-tools align-end mb-2">
                                            <ul class="card-tools-nav">
                                                <li class="active"><a href="<?=site_url('activity'); ?>"><span><?=translate_phrase('All'); ?></span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="nk-tb-list is-loose traffic-channel-table" id="cell_data">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card card-bordered h-100">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title"><?=translate_phrase('Service Attendance Chart');?></h6>
                                            <p id="service_date"> </p>
                                        </div>
                                        <div class="card-tools">
                                            <div class="drodown">
                                                <a href="javascript:;" class="btn btn-sm btn-outline-light btn-white"  data-bs-toggle="dropdown"><?=translate_phrase('VIEW MORE');?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="traffic-channel">
                                        <div class="traffic-channel-doughnut-ck"><canvas class="analytics-doughnut"
                                                id="BookingData"></canvas>
                                        </div>
                                        <div class="traffic-channel-group g-2" id="service_key">
                                            
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card card-bordered h-100">
                                <div class="card-inner">
                                    <div class="card-title-group pb-3 g-2">
                                        <div class="card-title">
                                            <h6 class="title"><?=translate_phrase('Sunday vs Wednesday'); ?></h6>
                                        </div>
                                        
                                        <div class="card-tools shrink-0 d-none d-sm-block">
                                        
                                            <ul class="nav nav-switch-s2 nav-tabs bg-white">
                                                
                                                <li class="nav-item"><a href="javascript:;" onclick="handleClick(this)" class="nav-link active"><?=translate_phrase('Offering'); ?></a></li>
                                                <li class="nav-item"><a href="javascript:;" onclick="handleClick(this)" class="nav-link "><?=translate_phrase('Tithe'); ?></a></li>
                                                <li class="nav-item"><a href="javascript:;" onclick="handleClick(this)" class="nav-link"><?=translate_phrase('Partnership'); ?></a></li>
                                            </ul>
                                        </div>
                                        <?php
                                            // Get the current year
                                            $current_year = date('Y');

                                            // Start year (2024)
                                            $start_year = 2024;

                                            // Generate the select dropdown
                                            echo '<select class="form-select" id="current_year" style="width:10%" onchange="load_finance();">';
                                            for ($year = $start_year; $year <= $current_year; $year++) {
                                                // Set the selected attribute for the current year
                                                $selected = ($year == $current_year) ? 'selected' : '';
                                                echo "<option $selected>$year</option>";
                                            }
                                            echo '</select>';
                                        ?>

                                    </div>
                                    <div class="traffic-channel-group g-2">
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq bg-purple" data-bg="#798bff"></span><span><?=translate_phrase('Wednesday'); ?></span></div>
                                        </div>  
                                        <div class="traffic-channel-data">
                                            <div class="title"><span class="dot dot-lg sq bg-danger" data-bg="#e85347"></span><span><?=translate_phrase('Sunday'); ?></span></div>
                                        </div>  
                                           
                                    </div>
                                    <div class="analytic-ov">
                                        
                                        <div class="analytic-ov-ck"><canvas class="analytics-line-large"
                                                id="analyticOvData"></canvas></div>
                                        <div class="chart-label-group ms-5">
                                            <div class="chart-label"><?=translate_phrase('Week 1'); ?></div>
                                            <div class="chart-label d-none d-sm-block"><?=translate_phrase('Week 26'); ?>
                                            </div>
                                            <div class="chart-label"><?=translate_phrase('Week 52'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-bordered card-full">
                                <div class="card-inner-group">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title"><?=translate_phrase('First Timer');?></h6>
                                            </div>
                                            <div class="card-tools"><a href="<?=site_url('service/report'); ?>" class="link"><?=translate_phrase('View All');?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="timer_data">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-bordered card-full">
                                <div class="card-inner-group">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title my-1"><?=translate_phrase('Upcoming Birthdays');?></h6>
                                            </div>
                                            <div class="card-tools"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="activity_data">
                                        
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="finance_type" value="offering">


<?=$this->endSection();?>
<?=$this->section('scripts');?>

<script>
    $(document).ready(function() {
        // Listen for changes on the checkbox
        $('#currency').change(function() {
            // Check if the checkbox is checked
            let isChecked = $(this).is(':checked');

            // Define the default currency based on the checkbox
            let defaultCurrency = isChecked ? 'country_currency' : 'church_currency';

            // Perform the AJAX request to save the default currency
            $.ajax({
                url: '<?= site_url("church/updateDefaultCurrency") ?>', // CI4 controller action
                type: 'POST',
                data: {
                    defaultCurrency: defaultCurrency
                },
                success: function(response) {
                    // Handle success
                    // $('#currency_resp').html(response);
                    metric_load();
                    load();
                }
            });

        });
    });
</script>

<script>
    function handleClick(element) {
        // Remove "active" class from all links
        var links = document.querySelectorAll('.nav-link');
        links.forEach(function(link) {
            link.classList.remove('active');
        });

        // Add "active" class to the clicked link
        element.classList.add('active');

        // Set the value of the hidden input to the selected link's text content
        document.getElementById('finance_type').value = element.textContent;
        load_finance();
    }

    function analyticsLineLarge(selector, set_data) {
        var $selector = $(selector || ".analytics-line-large");
        $selector.each(function () {
            var $self = $(this),
                _self_id = $self.attr("id"),
                _get_data = void 0 === set_data ? eval(_self_id) : set_data,
                selectCanvas = document.getElementById(_self_id),
                ctx = selectCanvas.getContext("2d");

            // Destroy existing chart if it exists
            if (selectCanvas.chart) {
                selectCanvas.chart.destroy();
            }

            var chart_data = [];

            for (var i = 0; i < _get_data.datasets.length; i++) {
                chart_data.push({
                    label: _get_data.datasets[i].label,
                    tension: _get_data.lineTension,
                    backgroundColor: _get_data.datasets[i].background,
                    fill: true,
                    borderWidth: 2,
                    borderDash: _get_data.datasets[i].dash,
                    borderColor: _get_data.datasets[i].color,
                    pointBorderColor: "transparent",
                    pointBackgroundColor: "transparent",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: _get_data.datasets[i].color,
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 2,
                    pointRadius: 4,
                    pointHitRadius: 4,
                    data: _get_data.datasets[i].data,
                });
            }

            var chart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: _get_data.labels,
                    datasets: chart_data
                },
                options: {
                    plugins: {
                        legend: {
                            display: _get_data.legend || false,
                            labels: {
                                boxWidth: 12,
                                padding: 20,
                                color: "#6783b8"
                            }
                        },
                        tooltip: {
                            enabled: true,
                            rtl: NioApp.State.isRTL,
                            callbacks: {
                                label: function (a) {
                                    return "".concat(a.parsed.y, " ").concat(_get_data.dataUnit);
                                },
                            },
                            backgroundColor: "#fff",
                            borderColor: "#eff6ff",
                            borderWidth: 2,
                            titleFont: { size: 13 },
                            titleColor: "#6783b8",
                            titleMarginBottom: 6,
                            bodyColor: "#9eaecf",
                            bodyFont: { size: 12 },
                            bodySpacing: 4,
                            padding: 10,
                            footerMarginTop: 0,
                            displayColors: false
                        }
                    },
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            display: true,
                            position: NioApp.State.isRTL ? "right" : "left",
                            ticks: { beginAtZero: true, font: { size: 12 }, color: "#9eaecf", padding: 8, stepSize: 2400 },
                            grid: { color: NioApp.hexRGB("#526484", 0.2), tickLength: 0, zeroLineColor: NioApp.hexRGB("#526484", 0.2), drawTicks: false },
                        },
                        x: {
                            display: false,
                            ticks: { font: { size: 12 }, color: "#9eaecf", source: "auto", padding: 0, reverse: NioApp.State.isRTL },
                            grid: { color: "transparent", tickLength: 0, zeroLineColor: "transparent", offset: true, drawTicks: false },
                        },
                    },
                },
            });

            // Store the chart instance on the canvas element
            selectCanvas.chart = chart;
        });
    }
    
    function analyticsDoughnut(selector, set_data) {
        var $selector = $(selector || ".analytics-doughnut");
        $selector.each(function () {
            var $self = $(this),
                _self_id = $self.attr("id"),
                _get_data = void 0 === set_data ? eval(_self_id) : set_data,
                selectCanvas = document.getElementById(_self_id),
                ctx = selectCanvas.getContext("2d");

            // Clear the canvas
            ctx.clearRect(0, 0, selectCanvas.width, selectCanvas.height);

            // Destroy existing chart if it exists
            if (selectCanvas.chart) {
                selectCanvas.chart.destroy();
            }

            var chart_data = [];

            for (var i = 0; i < _get_data.datasets.length; i++) {
                chart_data.push({
                    backgroundColor: _get_data.datasets[i].background,
                    borderWidth: 2,
                    borderColor: _get_data.datasets[i].borderColor,
                    hoverBorderColor: _get_data.datasets[i].borderColor,
                    data: _get_data.datasets[i].data
                });
            }

            var chart = new Chart(selectCanvas, {
                type: "doughnut",
                data: {
                    labels: _get_data.labels,
                    datasets: chart_data
                },
                options: {
                    plugins: {
                        legend: {
                            display: _get_data.legend || false,
                            labels: {
                                boxWidth: 12,
                                padding: 20,
                                color: "#6783b8"
                            }
                        },
                        tooltip: {
                            enabled: true,
                            rtl: NioApp.State.isRTL,
                            callbacks: {
                                label: function (a) {
                                    return "".concat(a.parsed, " ").concat(_get_data.dataUnit);
                                },
                            },
                            backgroundColor: "#fff",
                            borderColor: "#eff6ff",
                            borderWidth: 2,
                            titleFont: { size: 13 },
                            titleColor: "#6783b8",
                            titleMarginBottom: 6,
                            bodyColor: "#9eaecf",
                            bodyFont: { size: 12 },
                            bodySpacing: 4,
                            padding: 10,
                            footerMarginTop: 0,
                            displayColors: false
                        }
                    },
                    rotation: -1.5,
                    cutoutPercentage: 70,
                    maintainAspectRatio: false
                }
            });

            // Store the chart instance on the canvas element
            selectCanvas.chart = chart;
        });
    }

    var site_url = '<?php echo site_url(); ?>';

    $(function() {
        metric_load(); load();load_finance();load_activity();
    });

    $('.typeBtn').click(function() {
        $('#date_type').val($(this).attr('data-value'));
        $('#filter_type').html($(this).html());
        $(this).addClass('active');
        $(this).siblings().removeClass('active');

        if ($(this).attr('data-value') == 'Date_Range') {
            $('#data-resp').show(300);
        } else {
            $('#data-resp').hide(300);
            metric_load();
            load();
        }
    });

    // Generate labels for 52 weeks of the year
    function generateWeekLabels() {
        var labels = [];
        var startDate = new Date(); // Get current date
        var endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 7 * 52); // Add 52 weeks to the current date
        var weekCount = 1;

        // Loop through each week
        while (startDate < endDate) {
            labels.push("Week " + weekCount);
            startDate.setDate(startDate.getDate() + 7); // Move to the next week
            weekCount++;
        }

        return labels;
    }

    // Example usage
    var weekLabels = generateWeekLabels();
    
    function load() {
        $('#service_date').html( '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>' );
        $('#service_key').html( '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>' );


        var date_type = $('#date_type').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var finance_type = $('#finance_type').val();

        $.ajax({
            url: site_url + 'dashboard/service_metric',
            data: {
                date_type: date_type,
                start_date: start_date,
                end_date: end_date,
                finance_type: finance_type
            },
            type: 'post',
            success: function(data) {
                var dt = JSON.parse(data);
                $('#service_date').html(dt.service_date);
                $('#service_key').html(dt.service_key);
                var dataArray = JSON.parse(dt.service_data);
                console.log(dataArray);
                var BookingData = { labels: ["Male", "Female", "Children", "First Timer"], dataUnit: "People", legend: !1, datasets: [{ borderColor: "#fff", background: ["#798bff", "#1ee0ac", "#f9db7b", "#ffa353"], data: dataArray }] };
                analyticsDoughnut('#BookingData', BookingData);
            
            }
        });
    }

     
    function load_activity() {
        $('#activity_data').html( '<div class="col-sm-12 text-center"><br><br><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>' );
       $('#timer_data').html( '<div class="col-sm-12 text-center"><br><br><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>' );
       
        $.ajax({
            url: site_url + 'dashboard/index/activity_load',
          
            type: 'get',
            success: function(data) {
                var dt = JSON.parse(data);
                $('#activity_data').html(dt.item);
                $('#timer_data').html(dt.timer_item);
               
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    function load_finance() {
        var finance_type = $('#finance_type').val();
        var current_year = $('#current_year').val();

        $.ajax({
            url: site_url + 'dashboard/finance_metric',
            data: {
                finance_type: finance_type,
                current_year: current_year
            },
            type: 'post',
            success: function(data) {
                var dt = JSON.parse(data);
                var data_sunday = JSON.parse(dt.finance_sunday);
                var data_wednesday = JSON.parse(dt.finance_wednesday);
                
                var analyticOvData = {
                    labels: weekLabels,
                    dataUnit: "$",
                    lineTension: 0.1,
                    datasets: [
                        {
                            label: "Current Year",
                            color: "#e85347",
                            dash: [5, 5],
                            background: "transparent",
                            data: data_sunday,
                        },
                        {
                            label: "Current Year",
                            color: "#798bff",
                            dash: [0, 0],
                            background: NioApp.hexRGB("#798bff", 0.15),
                            data: data_wednesday,
                        },
                    ],
                };
                analyticsLineLarge('.analytics-line-large', analyticOvData);
            }
        });
    }

    function metric_load() {
        $('#partnership').html(
            '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            );
        $('#prospective').html(
            '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            );
        $('#partnership_part').html(
            '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            );
        $('#tithe_part').html(
            '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            );
        $('#tithe').html(
            '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            );
        $('#offering').html(
            '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            );
        $('#offering_part').html(
            '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            );
        $('#partnership_list').html(
            '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            );
        $('#cell_data').html(
        '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
        );
        $('#cell_offering').html(
        '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
        );
        $('#cell_offerings').html(
        '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
        );
        $('#membership').html(
        '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
        );
        $('#first_timer').html(
        '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
        );
        $('#new_convert').html(
        '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
        );
        $('#students').html(
        '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
        );
        $('#graduates').html(
        '<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
        );
        var date_type = $('#date_type').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        $.ajax({
            url: site_url + 'dashboard/metric',
            type: 'post',
            data: {
                date_type: date_type,
                start_date: start_date,
                end_date: end_date
            },
            success: function(data) {
                var dt = JSON.parse(data);

                $('#tithe').html(dt.tithe);
                $('#tithe_part').html(dt.tithe_part);
                $('#offering_part').html(dt.offering_part);
                $('#offering').html(dt.offering);
                $('#cell_offering').html(dt.cell_offering);
                $('#cell_offerings').html(dt.cell_offering);
                $('#partnership_part').html(dt.partnership_part);
                $('#partnership').html(dt.partnership);
                $('#partnership_list').html(dt.partnership_list);
                $('#cell_data').html(dt.cell_data);
                $('#membership').html(dt.membership);
                $('#new_convert').html(dt.new_convert);
                $('#first_timer').html(dt.first_timer);
                $('#prospective').html(dt.prospective);
                $('#students').html(dt.student);
                $('#graduates').html(dt.graduate);
                
                //Progress plugins
                NioApp.BS.progress('[data-progress]');

            }
        });
    }

</script>
<?=$this->endSection();?>