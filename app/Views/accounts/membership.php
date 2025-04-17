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
                                            <li id="bulk-button">
                                                <a href="javascript:;" data-toggle="tooltip" data-bs-placement="top" title="Bulk Message" pageName="<?=site_url('accounts/membership/manage/bulk_message'); ?>" pageSize="modal-md" pageTitle="Bulk Message" class="btn  btn-outline-dark pop btn-icon"><em class="icon ni ni-chat"></em></a>

                                            </li><!-- li -->
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li id="upload-button">
                                                <a href="javascript:;" data-toggle="tooltip" data-bs-placement="top" title="Membership Bulk Upload" pageName="<?=site_url('accounts/membership/manage/upload'); ?>" pageSize="modal-md" pageTitle="Upload Membership" class="btn  btn-outline-success pop btn-icon"><em class="icon ni ni-upload-cloud"></em></a>

                                            </li><!-- li -->
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li id="link-button">
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="New member Link" class="float-right btn btn-outline-dark btn-icon pop" pageTitle="<?=translate_phrase('New member Link');?>" pageName="<?php echo base_url('accounts/membership/manage/link'); ?>" pageSize="modal-md">
                                                    <em class="icon ni ni-user"></em>
                                                </a>
                                            </li>
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li id="add-button">
                                                <a href="<?=site_url('accounts/membership/manages'); ?>" pageTitle="Add Membership" data-toggle="tooltip" data-bs-placement="top" title=" Add Membership"  class="btn btn-outline-primary btn-icon" pageName=""><em class="icon ni ni-plus-c"></em></a>
                                            </li><!-- li -->
                                           <?php } ?>
                                           <li class="btn-toolbar-sep"></li>
                                           <li id="filter-button">
                                                <a href="javascript:;" id="toggleFilterBtn" class="btn btn-icon btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Toggle Filters">
                                                    <em class="icon ni ni-filter-alt"></em>
                                                </a>
                                            </li>
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
                            <div class="card card-bordered mb-3" id="filterSection" style="display: none;">
                                <div class="card-inner">
                                    <h6 class="title mb-3">Filter Service Reports</h6>
                                    <form id="filterForm">
                                        <div class="row g-3 align-center">
                                            <div class="col-sm-2 mb-3">
                                                <select class="form-control js-select2"  data-search="on" id="church_scope" name="church_scope" onchange="toggleChurchScope(this.value)">
                                                    <option value="all">All Churches</option>
                                                    <?php if (!empty($church_id)) { ?><option value="own">My Church</option><?php } ?>
                                                    <option value="selected">Selected Churches</option>
                                                </select>
                                                <span class="text-danger small">Church Filter Type</span>
                                            </div>

                                            <!-- Multi-select churches -->
                                            <div class="col-sm-4 mb-3" id="selected_church_container" style="display:none;">
                                                <select class="form-control js-select2"  data-search="on" id="selected_churches" name="selected_churches" multiple onchange="load();">
                                                    <!-- Dynamically populated -->
                                                </select>
                                                <span class="text-danger small">Select Churches</span>
                                            </div>

                                            <!-- Dynamic Cell Dropdown -->
                                            <div class="col-sm-4 mb-3" id="cell_container">
                                                <select class="form-control js-select2"  data-search="on" id="cell_id" name="cell_id" onchange="load();">
                                                   
                                                </select>
                                                <span class="text-danger small">Select Cell</span>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-inner table-responsive p-0">
                                <table class="table table-hover">
                                    <thead class="thead-dar">
                                        <tr>
                                            <th><?=translate_phrase('Title'); ?></th>
                                            <th><?=translate_phrase('Name'); ?></th>
                                            <th><?=translate_phrase('Member ID'); ?></th>
                                            <th ><?=translate_phrase('Phone'); ?></th>
                                            <th><?=translate_phrase('Email'); ?></th>
                                            <th><?=translate_phrase('Kingschat Handle'); ?></th>
                                            <th><?=translate_phrase('Cell'); ?></th>
                                            <th><?=translate_phrase('DOB'); ?></th>
                                            <th > </th>
                                        </tr>
                                    </thead>
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
        toggleChurchScope('all');
        $('#toggleFilterBtn').on('click', function () {
            $('#filterSection').slideToggle(300);
        });
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
        var church_scope = $('#church_scope').val();
        var selected_churches = $('#selected_churches').val(); // array
        var cell_id = $('#cell_id').val();

        $.ajax({
            url: site_url + 'accounts/membership/load' + methods,
            type: 'post',
            data: { search: search,
                church_scope: church_scope,
                selected_churches: selected_churches,
                cell_id: cell_id 
            },
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