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
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body" id="form_resp">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Forms</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total <span id="counta"></span> form(s).</p>
                            </div>
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
                                                <a href="javascript:;" class="btn btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
                                            </li><!-- li -->
                                            <?php if(empty($switch_id)){?>
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li>
                                                <a href="javascript:;" pageName="<?=site_url('ministry/invitation/manage'); ?>" pageTitle="Add" data-bs-toggle="tooltip" data-bs-placement="top" title="New Form" pageSize="modal-lg" class="btn btn-icon btn-outline-primary pop"><em class="icon ni ni-plus-c"></em></a>
                                            </li>
                                            <?php } ?>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                <div class="card-search search-wrap" data-search="search">
                                    <div class="card-body">
                                        <div class="search-content">
                                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                            <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search by user or email" oninput="load('', '')" id="search">
                                        </div>
                                    </div>
                                </div><!-- .card-search -->
                            </div><!-- .card-inner -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Level</th>
                                            <th>Event</th>
                                            <th>Link Generated</th>
                                            <th ></th>
                                        </tr>
                                    </thead>
                                    <tbody id="load_data"> </tbody>
                                    <tfoot id="loadmore"></tfoot>
                                </table>
                                
                            </div><!-- .card-inner -->
                           
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                    
                </div><!-- .nk-block -->
            </div>
            <div class="nk-content-body" id="extension_resp" style="display:none;">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Form Extension</h3>
                           
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
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="Back to Forms" class="btn btn-icon btn-outline-danger " onclick="form_back();"><em class="icon ni ni-arrow-long-left"></em></a>
                                            </li>
                                            <?php 
                                                $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                                               ?>

                                                <li>
                                                    <a href="javascript:;" style="display:none;" id="extend_btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Extend Form" pageName="<?=site_url('ministry/invitation/extension'); ?>" pageTitle="Add" pageSize="modal-lg" class="btn btn-icon btn-outline-primary pop"><em class="icon ni ni-plus-c"></em></a>
                                                </li>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                
                            </div><!-- .card-inner -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Form</th>
                                            <th>Church</th>
                                            <th>Date</th>
                                            <th ></th>
                                        </tr>
                                    </thead>
                                    <tbody id="extension_data"> </tbody>
                                    <tfoot id="extension_more"></tfoot>
                                </table>
                                
                            </div><!-- .card-inner -->
                           
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                    
                </div><!-- .nk-block -->
            </div>
            
            <div class="nk-content-body" id="share_resp" style="display:none;">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Form Link</h3>
                           
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
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="Back to Forms" class="btn btn-icon btn-outline-danger " onclick="form_back();"><em class="icon ni ni-arrow-long-left"></em></a>
                                            </li>
                                            <?php 
                                                $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                                               ?>

                                                <li>
                                                    <a href="javascript:;" style="display:none;" id="share_btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate" pageName="<?=site_url('ministry/invitation/share'); ?>" pageTitle="Generate  Link" pageSize="modal-md" class="btn btn-icon btn-outline-primary pop"><em class="icon ni ni-plus-c"></em></a>
                                                </li>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                
                            </div><!-- .card-inner -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Member</th>
                                            <th>Church</th>
                                            <th>Unique Link</th>
                                            <th>Response</th>
                                            <th>Date</th>
                                            <th ></th>
                                        </tr>
                                    </thead>
                                    <tbody id="share_data"> </tbody>
                                    <tfoot id="share_more"></tfoot>
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
    });

    function extension(id){
        $('#extension_resp').show(500);
        $('#form_resp').hide(500);
        $('#share_resp').hide(500);
        $('#extend_btn').attr('pageName',site_url + 'ministry/invitation/extension/'+id);
        load_extension('','',id);
        
    }

    
    function form_share(id, type){
        $('#share_resp').show(500);
        $('#extension_resp').hide(500);
        $('#form_resp').hide(500);
        $('#share_btn').attr('pageName',site_url + 'ministry/invitation/share/'+id);
        load_share('','',id, type);
        
    }

    function form_back(){
        $('#extension_resp').hide(500);
        $('#form_resp').show(500);
        $('#share_resp').hide(500);
        load();
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

        $.ajax({
            url: site_url + 'ministry/invitation/load' + methods,
            type: 'post',
            data: {search: search },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_data').html(dt.item);
                } else {
                    $('#load_data').append(dt.item);
                }
                $('#counta').html(dt.count);
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


    function load_extension(x, y, id) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#extension_data').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#extension_more').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }


        $.ajax({
            url: site_url + 'ministry/invitation/extension_load' + methods,
            type: 'post',
            data: {id: id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#extension_data').html(dt.item);
                } else {
                    $('#extension_data').append(dt.item);
                }

                if(dt.statuses == false){
                    $('#extend_btn').show(500);
                } else{
                    $('#extend_btn').hide(500);

                }
                
                if (dt.offset > 0) {
                    $('#extension_more').html('<tr><td colspan="8"><a href="javascript:;" class="btn btn-light btn-block p-30" onclick="load_extension(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a></td></tr>');
                } else {
                    $('#extension_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }


    function load_share(x, y, id, type) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#share_data').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#share_more').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }


        $.ajax({
            url: site_url + 'ministry/invitation/share_load' + methods,
            type: 'post',
            data: {id: id, type:type },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#share_data').html(dt.item);
                } else {
                    $('#share_data').append(dt.item);
                }

                if(dt.statuses == false){
                    $('#share_btn').show(500);
                } else{
                    $('#share_btn').hide(500);

                }
                
                if (dt.offset > 0) {
                    $('#share_more').html('<tr><td colspan="8"><a href="javascript:;" class="btn btn-light btn-block p-30" onclick="load_share(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a></td></tr>');
                } else {
                    $('#share_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }
</script>   

<?=$this->endSection();?>

