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
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Givings');?></h3>
                            <div class="nk-block-des text-soft">
                                <p>Total of <span id="counta">0</span> Records.</p>
                            </div>
                        </div>
                        
                        <div class="nk-block-head-content">
                            <button type="button" onclick="$('#filter_resp').toggle(500);" class="btn btn-primary"><em class="icon ni ni-filter"></em> <span>Filter</span></button>
                        </div>
                        <!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div>
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
                    <div class="col-sm-3">
                        <select id="partnership_id" name="partnership_id" class="js-select2" onchange="load();">
                            <option value="all">All Partnership</option>
                            <?php
                                $part = $this->Crud->read_order('partnership', 'name', 'asc');
                                if(!empty($part)){
                                    foreach($part as $p){ 
                                        $sel = '';
                                        
                                        echo '<option value="'.$p->id.'" '.$sel.'>'.ucwords($p->name).'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
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
                                                <a href="javascript:;" pageTitle="Add Givings" class="btn btn-outline-primary btn-icon pop" pageName="<?=site_url('accounts/givings/manage'); ?>"><em class="icon ni ni-plus-c"></em></a>
                                            </li><!-- li -->
                                           <?php } ?>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                <div class="card-search search-wrap" data-search="search">
                                    <div class="card-body">
                                        <div class="search-content">
                                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                            <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search" oninput="load('', '')" id="search">
                                        </div>
                                    </div>
                                </div><!-- .card-search -->
                            </div><!-- .card-inner -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Member</th>
                                            <th>Partnership</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th> </th>
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
        </div>
    </div>
</div>

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
            $('#load_data').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#loadmore').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }

        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        var search = $('#search').val();
        var partnership = $('#partnership_id').val();
        //alert(status);

        $.ajax({
            url: site_url + 'accounts/givings/load' + methods,
            type: 'post',
            data: { search: search, start_date:start_date,end_date:end_date,partnership:partnership},
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