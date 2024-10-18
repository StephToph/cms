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
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Currency Setup');?></h3>
                                
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="d-flex flex-row-reverse">
                            
                            <?php
                                $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
    
       
                            if ($ministry_id > 0) { ?>
                                <input type="hidden" id="ministry_id" name="ministry_id" value="<?php echo $ministry_id; ?>">
                                
                            <?php } else { ?>
                                <div class="col-sm-3 mb-1">
                                    <div class="form-group ">
                                        <select class="js-select2" data-search="on" name="ministry_id" id="ministry_id" onchange="load();">
                                            <option value="">Select Ministry</option>
                                            <?php

                                            $ministry = $this->Crud->read_order('ministry', 'name', 'asc');
                                            if (!empty($ministry)) {
                                                foreach ($ministry as $d) {
                                                    $sel = '';
                                                    
                                                    echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            <?php } ?>
                            <div class="col-sm-3 mx-1 mb-1">
                                <div class="form-group ">
                                    <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search by Country Name" oninput="load('', '')" id="search">
                                </div>
                            </div>
                        </div>
                    <div class="card card-bordered card-stretch">
                        
                        <div class="card-inner-group">
                            <div class="card-inner position-relative card-tools-toggle">
                                <div class="card-search search-wrap" data-search="search">
                                    <div class="card-body">
                                        <div class="search-content">
                                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                            
                                        </div>
                                    </div>
                                </div><!-- .card-search -->
                            </div><!-- .card-inner -->
                            <div class="">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Country</th>
                                                <th>Currency</th>
                                                <th>Symbol</th>
                                                <th>Rate to Espees</th>
                                            </tr>
                                        </thead>
                                        <tbody id="load_data"></tbody>
                                        <tfoot id="loadmore"></tfoot>
                                    </table>
                                </div>
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
    // Timeout variable to hold the delay
    let timeout = null;

    // Function to update the rate
    function update_rate(id) {
        // Get the value from the input field
        let value = $('#value' + id).val();

        // Regular expression for currency format
        let currencyRegex = /^\d+(\.\d{0,2})?$/;
        $('#rate_resp'+id).html('');
        // Clear any previous timeout to reset the delay
        clearTimeout(timeout);

        // Check if the value matches the currency format
        if (!currencyRegex.test(value)) {
            $('#value' + id).val(value.replace(/[^0-9.]/g, ''));
            $('#rate_resp'+id).html('Please enter a valid currency format (e.g., 100.00)');
            return;
        }

        if (parseFloat(value) == 0) {
            $('#rate_resp' + id).html('Value must not be 0').css('color', 'red');
            return;  // Don't run AJAX if the value is 0 or less
        }
        var ministry_id = $('#ministry_id').val();
        // Set a timeout to delay the AJAX call by 2 seconds (2000 milliseconds)
        timeout = setTimeout(function() {
            $('#rate_resp'+id).html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden"></span></div></div>');
            // Perform the AJAX request
            $.ajax({
                url: site_url + 'church/currency/update_rate',  // Your PHP file that handles the update
                type: 'POST',
                data: { 
                    id: id, 
                    rate: value,
                    ministry_id: ministry_id 
                },
                success: function(response) {
                    // Handle success response
                    $('#rate_resp'+id).html(response);
                    setTimeout(function() {
                        $('#rate_resp' + id).html('');
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    $('#rate_resp'+id).html('Error: ' + error);
                    setTimeout(function() {
                        $('#rate_resp' + id).html('');
                    }, 3000);
                }
            });
        }, 2000);  // 2-second delay
        
    }
    
    $(document).on('input', '.update_rates', function(e) {
        let input = $(this).val();

        // Allow only numbers and a single decimal point
        let validInput = input.replace(/[^0-9.]/g, ''); // remove non-numeric characters
        let countDecimals = (validInput.match(/\./g) || []).length;

        // If there is more than one decimal point, remove it
        if (countDecimals > 1) {
            validInput = validInput.slice(0, validInput.lastIndexOf('.'));
        }

        // Set the cleaned value back to the input
        $(this).val(validInput);
    });
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
        var ministry_id = $('#ministry_id').val();
        //alert(status);

        $.ajax({
            url: site_url + 'church/currency/load' + methods,
            type: 'post',
            data: { search: search, ministry_id:ministry_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_data').html(dt.item);
                } else {
                    $('#load_data').append(dt.item);
                }
                $('#counta').html(dt.count);
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