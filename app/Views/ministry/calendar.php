<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
    $db = \Config\Database::connect();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
<!-- content @s -->
<div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Events</h3>
                            </div>
                            <div class="nk-block-head-content" id="add_resp">
                                <a class="btn btn-outline-danger mt-3 mx-2" href="javascript:void(0);" onclick="event_back();"><em class="icon ni ni-cc-new"></em><span>Event View</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block" id="view_resp"  style="display:none;">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner position-relative card-tools-toggle">
                                    <div class="card-title-group">
                                        <div class="card-tools">

                                        </div><!-- .card-tools -->
                                        <div class="card-tools me-n1">
                                            <ul class="btn-toolbar gx-1">
                                                <li>
                                                    <a href="javascript:;" class="btn btn-icon search-toggle toggle-search"
                                                        data-target="search"><em class="icon ni ni-search"></em></a>
                                                </li>
                                                <li class="btn-toolbar-sep"></li><!-- li -->
                                                <li>
                                                    <a href="javascript:;" pageName="<?=site_url('ministry/calendar/manage'); ?>" pageTitle="Add" class="btn btn-icon btn-outline-primary pop"><em class="icon ni ni-plus-c"></em></a>
                                                </li>
                                            </ul><!-- .btn-toolbar -->
                                        </div><!-- .card-tools -->
                                    </div><!-- .card-title-group -->
                                    <div class="card-search search-wrap" data-search="search">
                                        <div class="card-body">
                                            <div class="search-content">
                                                <a href="#" class="search-back btn btn-icon toggle-search"
                                                    data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                                <input type="text" class="form-control border-transparent form-focus-none"
                                                    placeholder="Search by name" id="admin_search">
                                            </div>
                                        </div>
                                    </div><!-- .card-search -->
                                </div><!-- .card-inner -->
                                <div class="card-inner ">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Church</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="load_data"></tbody>
                                            <tfoot id="loadmore"></tfoot>
                                        </table>
                                    </div>
                                </div><!-- .card-inner -->
                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div>
                    <div class="nk-block" id="calendar_resp">
                        <div class="card">
                            <div class="card-inner">
                                <div id="calendar" class="nk-calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
<script>
    var site_url = '<?php echo site_url(); ?>';   
    var calEventsStr = '<?php if (!empty($cal_events)) { echo json_encode($cal_events); } else { echo "[]"; } ?>';
    $(function() {
        load('', '');
    });
    // Parse the JSON string into a JavaScript object/array
    var calEvents = JSON.parse(calEventsStr);

    function event_back() {
        $('#calendar_resp').hide(500);
        $('#view_resp').show(500);
        $('#add_resp').html('<a class="btn btn-outline-primary mt-3 mx-2" href="javascript:void(0);" onclick="calendar_view();"><em class="icon ni ni-calendar"></em><span>Calendar View</span></a>');

    
        load('', '');

    }
    function calendar_view() {
        $('#calendar_resp').show(500);
        $('#view_resp').hide(500);
        $('#add_resp').html('<a class="btn btn-outline-danger mt-3 mx-2" href="javascript:void(0);" onclick="event_back();"><em class="icon ni ni-cc-new"></em><span>Event View</span></a>');


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
        //alert(status);

        $.ajax({
            url: site_url + 'ministry/calendar/load' + methods,
            type: 'post',
            data: { search: search },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_data').html(dt.item);
                } else {
                    $('#load_data').append(dt.item);
                }
                
                if (dt.offset > 0) {
                    $('#loadmore').html('<tr><td colspan="8"><a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a></td></tr>');
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
<?=$this->endSection();?>