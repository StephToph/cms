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
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Partnership Analytics');?></h3>
                        </div>
                        <div class="nk-block-head-content">
                            <button type="button" onclick="$('#filter_resp').toggle(500);" class="btn btn-primary">Filter</button>
                        </div>
                        
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block-head nk-block-head-sm row" style="display:none" id="filter_resp">
                    <div class="col-sm-6 row">
                        <div class="col-sm-6">
                            <input type="date" class="form-control" name="start_date" id="start_date" oninput="loads()" style="border:1px solid #ddd;" placeholder="<?=translate_phrase('START DATE');?>">
                            <span class="text-danger">Start Date</span>
                        </div>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" name="end_date" id="end_date" oninput="loads()" style="border:1px solid #ddd;" placeholder="<?=translate_phrase('END DATE');?>">
                            <span class="text-danger">End Date</span>
                            
                        </div>
                        <div class="col-md-12" style="color:transparent;  text-white align:right;"><span id="date_resul"></span></div>
                    </div>
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            
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
            load('', '');
        }
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
        var member_id = $('#member_id').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        //alert(status);

        $.ajax({
            url: site_url + 'accounts/analytics/load' + methods,
            type: 'post',
            data: { search: search, start_date:start_date,end_date:end_date },
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