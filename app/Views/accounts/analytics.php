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
                            <button type="button" onclick="$('#filter_resp').toggle(500);" class="btn btn-primary"><em class="icon ni ni-filter"></em> <span>Filter</span></button>
                        </div>
                        
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block-head nk-block-head-sm row" style="display:none" id="filter_resp">
                    
                    <form id="filterForm">
                        <div class="row g-3 align-center">
                            <div class="col-sm-4 row">
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
                            <!-- <div class="col-md-3 mb-3">
                                <div class="form-control-wrap">
                                    <select class="js-select2" data-search="on" id="filterType" onchange="load();" name="type">
                                        <option value="all">All Types</option>
                                        <?php
                                            $service_types = $this->Crud->read_order('service_type', 'name', 'asc'); 
                                            foreach($service_types as $stype): ?>
                                            <option value="<?= $stype->id; ?>"><?= esc($stype->name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <span class="text-danger small">Service Types</span>
                            </div> -->
                            <div class="col-sm-2 mb-3">
                                <select class="form-control js-select2"  data-search="on" id="church_scope" name="church_scope" onchange="toggleChurchScope(this.value)">
                                    <option value="all">All Churches</option>
                                    <?php if (!empty($church_id)) { ?><option value="own">My Church</option><?php } ?>
                                    <option value="selected">Selected Churches</option>
                                </select>
                                <span class="text-danger small">Church Filter Type</span>
                            </div>

                            <!-- Multi-select churches -->
                            <div class="col-sm-3 mb-3" id="selected_church_container" style="display:none;">
                                <select class="form-control js-select2"  data-search="on" id="selected_churches" name="selected_churches" multiple onchange="load();">
                                    <!-- Dynamically populated -->
                                </select>
                                <span class="text-danger small">Select Churches</span>
                            </div>

                            <!-- Dynamic Cell Dropdown -->
                            <div class="col-sm-3 mb-3" id="cell_container">
                                <select class="form-control js-select2"  data-search="on" id="cell_id" name="cell_id" onchange="load();">
                                    
                                </select>
                                <span class="text-danger small">Select Cell</span>
                            </div>

                        </div>
                    </form>
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><b><?php echo translate_phrase('Partnership'); ?></b></th>
                                            <th><b><?php echo translate_phrase('Pledge'); ?></b></th>
                                            <th><b><?php echo translate_phrase('Participant'); ?></b></th>
                                            <th><b><?php echo translate_phrase('Given'); ?></b></th>
                                            <th><b><?php echo translate_phrase('Balance'); ?></b></th>
                                            <th><b><?php echo translate_phrase('Participant'); ?></b></th>
                                            <th class="text-center"><b><?php echo translate_phrase('Actions'); ?></b></th>
                                        </tr>
                                    </thead>

                                    <tbody id="load_data"></tbody>
                                    <tfoot id="loadmore"></tfoot>
                                </table>
                            </div>
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

        toggleChurchScope('all');
    });
   
    function toggleChurchScope(scope) {
        const $selectedChurchContainer = $('#selected_church_container');
        const $selectedChurches = $('#selected_churches');
        const $cellSelect = $('#cell_id');

        if (scope === 'selected') {
            $selectedChurchContainer.show();

            // Fetch church list only once
            if ($selectedChurches.children().length === 0) {
                $.ajax({
                    url: "<?= site_url('service/fetch_scope_churches') ?>",
                    method: 'GET',
                    success: function (res) {
                        $selectedChurches.empty();
                        $.each(res, function (i, church) {
                            $selectedChurches.append(`<option value="${church.id}">${church.name} (${church.type})</option>`);
                        });
                        $selectedChurches.select2();
                    }
                });
            }

            // Bind change event to fetch cells dynamically when church selection changes
            $selectedChurches.off('change').on('change', function () {
                const selected = $(this).val();
                if (selected.length > 0) {
                    $.ajax({
                        url: "<?= site_url('service/analytics/records/fetch_cells_by_churches') ?>",
                        method: 'POST',
                        data: { church_ids: selected },
                        success: function (res) {
                            $cellSelect.empty();
                            $cellSelect.append(`<option value="all">-- All Cell --</option>`); // default option
                            $.each(res, function (i, cell) {
                                $cellSelect.append(`<option value="${cell.id}">${cell.name} (Church ID: ${cell.church})</option>`);
                            });
                        }
                    });
                } else {
                    $cellSelect.empty();
                }
            });

        } else {
            $selectedChurchContainer.hide();
            $selectedChurches.val(null).trigger('change');

            // 🔁 Fetch cells for "own" or "all" scope
            $.ajax({
                url: "<?= site_url('service/analytics/records/fetch_cells_by_scope') ?>",
                method: 'POST',
                data: { scope: scope },
                success: function(res) {
                    $cellSelect.empty();
                    $cellSelect.append(`<option value="all">-- All Cell --</option>`); // default option

                    $.each(res, function(index, cell) {
                        $cellSelect.append(`
                            <option value="${cell.cell_id}">
                                ${cell.cell_name} (Church ID: ${cell.church})
                            </option>
                        `);
                    });
                },
            });
        }
    }

    
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

       
       
        // Collect form filter values
        var search = $('#search').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var church_scope = $('#church_scope').val();
        var selected_churches = $('#selected_churches').val(); // array
        var cell_id = $('#cell_id').val();

        $.ajax({
            url: site_url + 'accounts/analytics/load' + methods,
            type: 'post',
            data: {
            search: search,
                start_date: start_date,
                end_date: end_date,
                church_scope: church_scope,
                selected_churches: selected_churches,
                cell_id: cell_id
            },success: function (data) {
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