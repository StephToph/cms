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
        <div class="container wide-xl ">
            <div class="nk-content-inner mt-5">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Territory</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Manage LGA Territory</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a href="javascript:;" class="float-right btn btn-primary pop" pageTitle="Add Territory" pageName="<?php echo base_url('settings/territory/manage'); ?>" pageSize="">
                                                    <em class="icon ni ni-plus-sm"></em> Add
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                                        <div class="col-12"> 
                                                            <select class="form-select js-select2" data-search="on" id="territory" name="territory" onchange="load();">
                                                                <option value="all"><?=translate_phrase('Filter LGA'); ?></option>
                                                                <?php 
                                                                    $lga = $this->Crud->read_single_order('state_id', 316, 'city', 'name', 'asc');
                                                                    if(!empty($lga)){
                                                                        foreach($lga as $l){
                                                                ?>
                                                                    <option value="<?=$l->id; ?>"><?=$l->name; ?></option>
                                                                <?php
                                                                      }}
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                
                                            </li>
                                        </ul>
                                    </div>      
                                        <div class="card-tools me-n1">
                                                   
                                            <ul class="btn-toolbar gx-1">
                                                <li>
                                                    <a href="#" class="btn btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
                                                </li>
                                            </ul><!-- .btn-toolbar -->
                                            
                                        </div><!-- .card-tools -->
                                    </div><!-- .card-title-group -->
                                    <div class="card-search search-wrap" data-search="search">
                                        <div class="card-body">
                                            <div class="search-content">
                                                <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                                <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search by lga" oninput="load('', '')" id="search">
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

        var search = $('#search').val();
        var territory = $('#territory').val();
        //alert(status);

        $.ajax({
            url: site_url + 'settings/territory/load' + methods,
            type: 'post',
            data: {  search: search,  territory:territory },
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