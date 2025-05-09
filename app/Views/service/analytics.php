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
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Service Attendance Analytics');?></h3>
                           
                        </div>
                        <div class="nk-block-head-content">
                            <button type="button" onclick="$('#filter_resp').toggle(500);" class="btn btn-primary"><em class="icon ni ni-filter"></em> <span>Filter</span></button>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block-head nk-block-head-sm row" style="display:none" id="filter_resp">
                    <div class="col-sm-3">
                        <input type="date" class="form-control" name="date" id="date" oninput="loads()" style="border:1px solid #ddd;" value="<?=$date; ?>" placeholder="<?=translate_phrase('SERVICE DATE');?>">
                        <span class="text-danger small">Service Date</span>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <select class="js-select2" data-search="on" onchange="load();" name="service" id="service">';
                            
                        </select>
                        <span class="text-danger small">Service Type</span>
                    </div>

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
                            <!-- Dynamically populated -->
                        </select>
                        <span class="text-danger small">Select Cell</span>
                    </div>

                    
                    <!-- <div class="col-sm-3 mb-3">
                        <select class="form-control js-select2"  data-search="on" id="marked_type" name="marked_type">
                            <option value="all">All</option>
                            <option value="admin">Church Admin</option>
                            <option value="monitoring">Attendance Monitor</option>
                            <option value="cell">Cell Leaders</option>
                        </select>
                        <span class="text-danger small">Monitor Type</span>
                    </div>
                      Dynamic Multi-Select of Churches (hidden by default) 
                    <div class="col-sm-4 mb-3" id="selected_attendance_container" style="display:none;">
                        <select class="form-control js-select2"  data-search="on" id="marked_by" name="marked_by" onchange="load();">
                            
                        </select>
                        <span class="text-danger small">Attendance Monitor</span>
                    </div> -->
                    
                </div><!-- .nk-block-head -->
                <div class="row">
                    <?php
                        $cards = [
                            ['id' => 'membership', 'label' => 'Total Members', 'border' => 'bg-primary text-white', 'filter' => 'all', 'color'=>'text-white'],
                            ['id' => 'present', 'label' => 'Total Present', 'border' => 'border-success', 'filter' => 'present', 'color'=>'text-dark'],
                            ['id' => 'absent', 'label' => 'Total Absent', 'border' => 'border-danger', 'filter' => 'absent', 'color'=>'text-dark'],
                            ['id' => 'male', 'label' => 'Male Present', 'border' => 'border-primary', 'filter' => 'male', 'color'=>'text-dark'],
                            ['id' => 'female', 'label' => 'Female Present', 'border' => 'border-primary', 'filter' => 'female', 'color'=>'text-dark'],
                            ['id' => 'firstTimer', 'label' => 'First Timer', 'border' => 'border-primary', 'filter' => 'first_timer', 'color'=>'text-dark']
                        ];

                        foreach ($cards as $card):
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="card card-bordered card-full <?= $card['border']; ?> filter-card cursor-pointer"
                            data-filter="<?= $card['filter']; ?>">
                            <div class="card-inner">
                                <div class="card-title-group align-start mb-0">
                                    <div class="card-title">
                                        <h6 class="title"><?= translate_phrase($card['label']); ?></h6>
                                    </div>
                                </div>
                                <div class="card-amount">
                                    <span class="amount <?= $card['color']; ?>" id="<?= $card['id']; ?>"> 0 <span class="currency currency-usd"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>

                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title">
                                        <h6 class="title my-1" id="service_date"><?=$date.' '.translate_phrase('Service Attendance');?></h6>
                                    </div>
                                    <div class="card-tools"></a>
                                    </div>
                                </div>
                            </div>
                           <div class="card-inner p-0 table-responsive">
                              
                                <table class="table table-striped table-hover mt-5">
                                    <thead>
                                        <tr>
                                            <th>Clock-In</th>
                                            <th>Church</th>
                                            <th>Member</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="load_data"></tbody>
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
    let cardFilter = 'all';
    $(function() {
        loads();
         toggleChurchScope('all');
        $('#marked_type').on('change', loadMarkedByOptions);

    });

    function loadMarkedByOptions() {
        const type = $('#marked_type').val();

        if (type === 'admin' || type === 'all') {
            $('#selected_attendance_container').hide(500 );
            load();
            $('#marked_by').empty().trigger('change');
            return;
        }
        // console.log(type);
        // Show the select box
        $('#selected_attendance_container').show(500 );

        // Fetch corresponding users
        $.ajax({
            url: "<?= site_url('service/analytics/records/fetch_marked_by_users') ?>",
            method: "POST",
            data: {
                type: type,
                church_scope: $('#church_scope').val(), // optional if you want scoped filters
                church_id: <?= $church_id ?? 'null' ?>,
                selected_churches: $('#selected_churches').val() // optional if you want scoped filters
            },
            success: function (res) {
                $('#marked_by').empty();
                $.each(res, function (i, user) {
                    $('#marked_by').append(`<option value="${user.id}">${user.name}</option>`);
                });
                $('#marked_by').trigger('change').select2();

            }
        });
    }

    
   
    function loads() {
        var date = $('#date').val();
        $('#service_date').html(date+' Service Attendance');

        populateServiceDropdown(date);
    }
  

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

    $('.filter-card').on('click', function () {
        cardFilter = $(this).data('filter'); // store selected card filter
        $('.filter-card').removeClass('bg-highlight');
        $(this).addClass('bg-highlight');

        load('', ''); // reload with card filter
    });
    function load(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }
        var church_scope = $('#church_scope').val();
        var selected_churches = $('#selected_churches').val(); // multiple


        if (more == 'no') {
            $('#load_data').html('<tr><td colspan="9"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#loadmore').html('<tr><td colspan="9"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }
        
       
        var search = $('#search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'service/analytics/load' + methods,
            type: 'post',
            data: {
                search: $('#search').val(),
                date: $('#date').val(),
                service: $('#service').val(),
                cell_id: $('#cell_id').val(),
                marked_type: $('#marked_type').val(),
                marker_by: $('#marker_by').val(),
                church_scope: church_scope,
                selected_churches: selected_churches,
                filter: cardFilter // ✅ NEW LINE TO SEND FILTER
            },
            success: function (data) {
                var dt = JSON.parse(data);
                $('#membership').text(dt.membership);
                $('#present').text(dt.present);
                $('#absent').text(dt.absent);
                $('#male').text(dt.male);
                $('#female').text(dt.female);
                $('#firstTimer').text(data.firstTimer);
                $('#unmarked').text(dt.unmarked);

                if (more == 'no') {
                    $('#load_data').html(dt.item);
                } else {
                    $('#load_data').append(dt.item);
                }

                $('#counta').html(dt.count);
                if (dt.offset > 0) {
                    $('#loadmore').html('<tr><td colspan="9"><a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load  More</a></td></tr>');
                } else {
                    $('#loadmore').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }
    function populateServiceDropdown(date) {
        $('#service').empty();
        $.ajax({
            url: site_url + 'service/analytics/records/get_service',
            type: 'post',
            data: {
                date: date
            },
            success: function (data) {
                $('#service').html(data);
                load('', '');
            }
        });
   
    }

</script>   

<?=$this->endSection();?>