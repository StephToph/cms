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
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Membership');?></h3>
                            <div class="nk-block-des text-soft">
                                <p><?=translate_phrase('You have total');?> <span id="counta"></span> <?=translate_phrase('membership(s).');?></p>
                            </div>
                        </div>
                        
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
                                            </li>
                                            <?php if(empty($switch_id)){?>
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li>
                                                <a href="javascript:;" data-toggle="tooltip" data-bs-placement="top" title="Bulk Message" pageName="<?=site_url('accounts/membership/manage/bulk_message'); ?>" pageSize="modal-md" pageTitle="Bulk Message" class="btn  btn-outline-dark pop btn-icon"><em class="icon ni ni-chat"></em></a>

                                            </li><!-- li -->
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li>
                                                <a href="javascript:;" data-toggle="tooltip" data-bs-placement="top" title="Membership Bulk Upload" pageName="<?=site_url('accounts/membership/manage/upload'); ?>" pageSize="modal-md" pageTitle="Upload Membership" class="btn  btn-outline-success pop btn-icon"><em class="icon ni ni-upload-cloud"></em></a>

                                            </li><!-- li -->
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li>
                                                <a href="<?=site_url('accounts/membership/manages'); ?>" pageTitle="Add Membership" data-toggle="tooltip" data-bs-placement="top" title="Create New Membership"  class="btn btn-outline-primary btn-icon" pageName=""><em class="icon ni ni-plus-c"></em></a>
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
                            <div class="card-inner" id="filter_resp" style="display:none;">
                                <div class="row">
                                    <div class="col-sm-4 mb-3" >
                                        <select class=" js-select2" id="include" onchange="load();">
                                            <option value="true">All Churches</option>
                                            <option value="false" >My Church Only</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-inner table-responsive p-0">
                                <table class="table table-hover">
                                    <tbody id="load_data"></tbody>
                                    <tfoot id="loadmore"></tfoot>
                                </table>
                            </div>
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
        var include = $('#include').val();
        //alert(status);

        $.ajax({
            url: site_url + 'accounts/membership/load' + methods,
            type: 'post',
            data: { search: search, include:include },
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
</script>   

<?=$this->endSection();?>