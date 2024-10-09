<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
    $db = \Config\Database::connect();
    $this->session = \Config\Services::session();
    
    $switch_id = $this->session->get('switch_church_id');
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
                <div class="nk-content-body my-4">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Church Activities</h3>
                            </div>
                            <div class="nk-block-head-content" id="add_resp">
                                <?php if(empty($switch_id)){?>
                                    <a href="javascript:;" pageName="<?=site_url('church/activity/manage'); ?>" pageTitle="Add" pageSize="modal-lg" class="btn btn-outline-primary pop"><em class="icon ni ni-plus-c"></em> <span>Add Activity</span></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block" id="calendar_resp">
                        <div class="card">
                            <div class="card-inner">
                                <div id="calendar" data-initial-view="listWeek" class="nk-calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="previewEventPopup">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div id="preview-event-header" class="modal-header">
                    <h5 id="preview-event-title" class="modal-title">Placeholder Title</h5><a href="#" class="close"
                        data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
                </div>
                <div class="modal-body">
                    <div class="row gy-3 py-1">
                        <div class="col-sm-6">
                            <h6 class="overline-title">Start Time</h6>
                            <p id="preview-event-start"></p>
                        </div>
                        <div class="col-sm-6" id="preview-event-end-check">
                            <h6 class="overline-title">End Time</h6>
                            <p id="preview-event-end"></p>
                        </div>
                        <div class="col-sm-10" id="preview-event-description-check">
                            <h6 class="overline-title">Description</h6>
                            <p id="preview-event-description"></p>
                        </div>
                    </div>
                    <!-- <ul class="d-flex justify-content-between gx-4 mt-3">
                        <li><button  pageTitle="Edit " pageSize="modal-lg" pageName="<?=site_url('ministry/calendar/manage/edit/'); ?>" class="btn btn-primary pop">Edit Event</button></li>
                        <li><button data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#deleteEventPopup"
                                class="btn btn-danger pop btn-dim">Delete</button></li>
                    </ul> -->
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/jsmodal.js"></script></script>
<script>
    var site_url = '<?php echo site_url(); ?>';   
    var calEventsStr = '<?php if (!empty($cal_events)) { echo json_encode($cal_events); } else { echo "[]"; } ?>';
    console.log(calEvents);
    
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