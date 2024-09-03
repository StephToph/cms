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
                            <h3 class="nk-block-title page-title"><?php if(!empty($param2)){echo ucwords($this->Crud->read_field('id', $param2, 'user', 'surname')).'`s '; } ?><?=translate_phrase('Partnership Goal');?></h3>
                        </div>
                        <?php if($role != 'member'){?>
                            <div class="nk-block-head-content">
                                <a class="btn btn-info" href="<?=site_url('accounts/membership'); ?>"><em class="icon ni ni-arrow-long-left"></em>Back to Membership</a>
                            </div><!-- .nk-block-head-content -->
                        <?php } ?>
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
                                            <?php 
                                                if(empty($this->Crud->read_field('id', $param2, 'user', 'partnership'))){
                                            ?>
                                                <li>
                                                    <a href="javascript:;" pageTitle="Add Partnership" class="btn btn-outline-primary btn-icon pop" pageName="<?=site_url('accounts/membership/partnership/'.$param2.'/add'); ?>"><em class="icon ni ni-plus-c"></em></a>
                                                </li><!-- li -->
                                            <?php } else{?>
                                                <li>
                                                    <a href="javascript:;" pageTitle="Edit Partnership" class="btn btn-outline-danger btn-icon pop" pageName="<?=site_url('accounts/membership/partnership/'.$param2.'/edit'); ?>"><em class="icon ni ni-edit"></em></a>
                                                </li><!-- li -->
                                            <?php } ?>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                               
                            </div><!-- .card-inner -->
                            
                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist" id="load_data">
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner">
                                <div class="nk-block-between-md g-3" id="loadmore">
                                </div><!-- .nk-block-between -->
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="member_id", value="<?php if(!empty($param2)){echo $param2;}?>">

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
            $('#load_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#loadmore').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

       
        var search = $('#search').val();
        var member_id = $('#member_id').val();
        //alert(status);

        $.ajax({
            url: site_url + 'accounts/membership/partnership/load' + methods,
            type: 'post',
            data: { search: search, member_id:member_id },
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

<?=$this->endSection();?>