<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
    
    $this->session = \Config\Services::session();
    
    $switch_id = $this->session->get('switch_church_id');
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
                            <h3 class="nk-block-title page-title" id="title"><?=translate_phrase('Service Type');?></h3>
                            <div class="nk-block-des text-soft">
                                <p><?=translate_phrase('You have total');?> <span id="counta"></span> <?=translate_phrase('record.');?></p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content" id="add_resp">
                            <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Add Service Schedule" id="add_btn" class="btn btn-primary">Service Schedule</a>
                            <div class="nk-block-head-sub mb-3" id="attendance_prev" style="display:none;">
                                <a class="btn btn-outline-danger" id="back_btn" href="javascript:;"><em class="icon ni ni-arrow-left"></em><span>Service Type</span>
                                </a>
                            </div>
                                                
                        </div>
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block" id="service_type_block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner position-relative card-tools-toggle">
                                <div class="card-title-group">
                                    <div class="card-tools">
                                        
                                    </div><!-- .card-tools -->
                                    <div class="card-tools me-n1">
                                        <ul class="btn-toolbar gx-1">
                                            <li>
                                                <a href="javascript:;" class="btn btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
                                            </li>
                                            <?php if(empty($switch_id)){?>
                                                <li class="btn-toolbar-sep"></li><!-- li -->
                                                <li id="type-btn">
                                                    <a href="javascript:;" pageTitle="Add Type" class="btn btn-outline-primary btn-icon pop" pageName="<?=site_url('service/type/manage'); ?>"><em class="icon ni ni-plus-c"></em></a>
                                                </li><!-- li -->
                                           <?php } ?>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                <div class="card-search search-wrap" data-search="search">
                                    <div class="card-body">
                                        <div class="search-content">
                                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                            <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search by name" oninput="load('', '')" id="search">
                                        </div>
                                    </div>
                                </div><!-- .card-search -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-0 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th ></th>
                                        </tr>
                                    </thead>
                                    <tbody id="load_data"></tbody>
                                    <tfoot  id="loadmore"></tfoot>
                                </table>
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->

                <div class="nk-block" id="service_schedule_block" style="display: none;" >
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner position-relative card-tools-toggle">
                                <div class="card-title-group">
                                    <div class="card-tools">
                                        
                                    </div><!-- .card-tools -->
                                    <div class="card-tools me-n1">
                                        <ul class="btn-toolbar gx-1">
                                            
                                            <?php if(empty($switch_id)){?>
                                                <li class="btn-toolbar-sep"></li><!-- li -->
                                                <li>
                                                    <a href="javascript:;" pageSize="modal-lg" pageTitle="Add schedule" class="btn btn-outline-primary btn-icon pop" pageName="<?=site_url('service/schedule/manage'); ?>"><em class="icon ni ni-plus-c"></em></a>
                                                </li><!-- li -->
                                           <?php } ?>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                               
                            </div><!-- .card-inner -->
                            <div class="card-inner p-0 table-responsive">
                                <table class="table table-striped table-hover mt-5">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th>Recurrence</th>
                                            <th>Church</th>
                                            <th>Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="schedule_load_data"></tbody>
                                    <tfoot id="schedule_loadmore"></tfoot>
                                </table>

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
        $('#add_btn').on('click', function () {
            $('#service_type_block').hide(500);                    // hide type view
            $('#service_schedule_block').show(500);               // show schedule form
            $('#add_btn').hide(500);                              // hide "Service Schedule" button
            $('#attendance_prev').show(500);   
            $('#title').html('Service Schedule');  
            load_schedule();                 // show "Back to Type" button
        });

        $('#back_btn').on('click', function () {
            $('#service_type_block').show(500);                   // show type view again
            $('#service_schedule_block').hide(500);               // hide schedule form
            $('#add_btn').show(500);                              // show "Service Schedule" button
            $('#attendance_prev').hide(500); 
            $('#title').html('Service Type');   
            load();                                  // hide back button
        });
    });
   
   
    function load(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        
        if (more == 'no') {
            $('#load_data').html('<tr><td colspan="9"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#loadmore').html('<tr><td colspan="9"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }


       
        var search = $('#search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'service/type/load' + methods,
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
                    $('#loadmore').html('<tr><td colspan="9"><a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load  More</a></td></tr>');
                } else {
                    $('#loadmore').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    
    function load_schedule(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#schedule_load_data').html('<tr><td colspan="9"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#schedule_loadmore').html('<tr><td colspan="9"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }

       
        var search = $('#search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'service/schedule/load' + methods,
            type: 'post',
            data: { search: search },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#schedule_load_data').html(dt.item);
                } else {
                    $('#schedule_load_data').append(dt.item);
                }
                $('#counta').html(dt.count);
                if (dt.offset > 0) {
                    $('#schedule_loadmore').html('<tr><td colspan="9"><a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load_schedule(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load  More</a></td></tr>');
                } else {
                    $('#schedule_loadmore').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }


</script>   

<?=$this->endSection();?>