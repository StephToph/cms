<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
<div class="nk-content">
    <div class="container wide-xl ">
        <div class="nk-content-inner mt-5">
            <div class="nk-content-body  mt-3">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Administrator'); ?></h3>
                            
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="more-options">
                                    <ul class="nk-block-tools g-3">
                                        <li>
                                            <a href="javascript:;" class="float-right btn btn-primary pop" pageTitle="<?=translate_phrase('Add Account');?>" pageName="<?php echo base_url('accounts/administrator/manage'); ?>" pageSize="">
                                                <i class="icin ni ni-plus-c"></i> <?=translate_phrase('Add'); ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
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
                                        <ul class="btn-toolbar gx-1">
                                            <li>
                                                <div class="col-sm-12 mb-1">
                                                    <div class="row">
                                                        <div class="col-6 col-sm-6"> <label for="name" class="small text-muted"><?=translate_phrase('START DATE');?></label>
                                                            <input type="date" class="form-control" name="start_date" id="start_date" oninput="loads()" style="border:1px solid #ddd;">
                                                        </div>
                                                        <div class="col-6 col-sm-6"> <label for="name" class="small text-muted"><?=translate_phrase('END DATE');?></label>
                                                            <input type="date" class="form-control" name="end_date" id="end_date" oninput="loads()" style="border:1px solid #ddd;">
                                                        </div>
                                                        <div class="col-md-12" style="color: transparent;"><span id="date_resul"></span></div>
                                                    </div>
                                                </div>
                
                                            </li>
                                        </ul>
                                    </div><!-- .card-tools -->
                                    <div class="card-tools me-n1">
                                        <ul class="btn-toolbar gx-1">
                                            <li>
                                                <a href="#" class="btn btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
                                            </li><!-- li -->
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li>
                                                <div class="toggle-wrap">
                                                    <a href="#" class="btn btn-icon btn-trigger toggle" data-target="cardTools"><em class="icon ni ni-menu-right"></em></a>
                                                    <div class="toggle-content" data-content="cardTools">
                                                        <ul class="btn-toolbar gx-1">
                                                            <li class="toggle-close">
                                                                <a href="#" class="btn btn-icon btn-trigger toggle" data-target="cardTools"><em class="icon ni ni-arrow-left"></em></a>
                                                            </li><!-- li -->
                                                            <li>
                                                                <div class="dropdown">
                                                                    <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                                                                        <div class="dot dot-primary"></div>
                                                                        <em class="icon ni ni-filter-alt"></em>
                                                                    </a>
                                                                    <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-end">
                                                                        <div class="dropdown-head">
                                                                            <span class="sub-title dropdown-title"><?=translate_phrase('Filter Business Account');?></span>
                                                                            <div class="dropdown">
                                                                                <a href="#" class="btn btn-sm btn-icon">
                                                                                    <em class="icon ni ni-more-h"></em>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dropdown-body dropdown-body-rg">
                                                                            <div class="row gx-6 gy-3">
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <label class="overline-title overline-title-alt"><?=translate_phrase('LGA');?></label>
                                                                                        <select class="form-select js-select2" data-search="on" id="state_id" onchange="load('', '')">
                                                                                            <option value="0"><?=translate_phrase('All LGA');?></option>
                                                                                            <?php
                                                                                                $state = $this->Crud->read_single_order('state_id', 316, 'city', 'name', 'asc');
                                                                                                if(!empty($state)){
                                                                                                    foreach($state as $s){
                                                                                                        echo '
                                                                                                            <option value="'.$s->id.'">'.$s->name.'</option>
                                                                                                        ';
                                                                                                    }
                                                                                                }

                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <label class="overline-title overline-title-alt"><?=translate_phrase('Active Status'); ?></label>
                                                                                        <select class="form-select js-select2" data-search="on" id="status" onchange="load('', '')">
                                                                                            <option value="all"><?=translate_phrase('All Status'); ?></option>
                                                                                            <option value="1"><?=translate_phrase('Activated');?></option>
                                                                                            <option value="0"><?=translate_phrase('Banned');?></option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .filter-wg -->
                                                                </div><!-- .dropdown -->
                                                            </li><!-- li -->
                                                        </ul><!-- .btn-toolbar -->
                                                    </div><!-- .toggle-content -->
                                                </div><!-- .toggle-wrap -->
                                            </li><!-- li -->
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
                </div>
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
            $('#date_resul').html('Enter Start and End Date!!');
        } else if(start_date > end_date){
            $('#date_resul').css('color', 'Red');
            $('#date_resul').html('Start Date cannot be greater!');
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
            $('#load_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>Loading Please Wait</div>');
        } else {
            $('#loadmore').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>Loading Please Wait</div>');
        }

        
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var state_id = $('#state_id').val();
        var status = $('#status').val();
        var ref_status = $('#ref_status').val();
        var verify = $('#verify').val();
        var search = $('#search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'accounts/administrator/load' + methods,
            type: 'post',
            data: { state_id: state_id, search: search,start_date: start_date,end_date: end_date , status: status, verify: verify, ref_status: ref_status },
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