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
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner pt-4">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">First Timer Form</h3>
                            
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="d-flex flex-row-reverse">
                        
                        <?php
                            $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                            $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                            $level = $this->Crud->read_field('id', $log_id, 'user', 'church_type');

    
                        if ($ministry_id > 0 && $church_id > 0) { ?>
                            <input type="hidden" id="ministry_id" name="ministry_id" value="<?php echo $ministry_id; ?>">
                            <input type="hidden" id="level" name="level" value="<?php echo $level; ?>">
                            <input type="hidden" id="church_id" name="church_id" value="<?php echo $church_id; ?>">
                            
                        <?php } elseif($ministry_id > 0 && $church_id <= 0) { ?>
                            <input type="hidden" id="ministry_id" name="ministry_id" value="<?php echo $ministry_id; ?>">
                            <div class="col-sm-3 mb-3 mx-2">
                                <div class="form-group">
                                    <select class="js-select2" data-search="on" name="church_id" id="church_id" onchange="load();">
                                        <option value="all">All Church</option>
                                        <?php

                                        $church = $this->Crud->read_single_order('ministry_id', $ministry_id, 'church', 'name', 'asc');
                                        if (!empty($church)) {
                                            foreach ($church as $d) {
                                                $sel = '';
                                                
                                                echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3 mx-2">
                                <div class="form-group">
                                    <select class="js-select2" data-search="on" name="level" id="level" onchange="form_check();">
                                        <option value="all">All Church Level</option>
                                        <option value="region">Regional Church</option>
                                        <option value="zone">Zonal Church</option>
                                        <option value="group">Group Church</option>
                                        <option value="church">Church Assembly</option>
                                    </select>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-3 mb-3 mx-2">
                                <div class="form-group">
                                    <select class="js-select2" data-search="on" name="church_id" id="church_id" onchange="load();">
                                        <option value="all">All Church</option>
                                        <?php

                                        $church = $this->Crud->read_order('church', 'name', 'asc');
                                        if (!empty($church)) {
                                            foreach ($church as $d) {
                                                $sel = '';
                                                
                                                echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3 mx-2">
                                <div class="form-group">
                                    <select class="js-select2" data-search="on" name="level" id="level" onchange="form_check();">
                                        <option value="all">All Church Level</option>
                                        <option value="region">Regional Church</option>
                                        <option value="zone">Zonal Church</option>
                                        <option value="group">Group Church</option>
                                        <option value="church">Church Assembly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 mb-1 mx-2">
                                <div class="form-group ">
                                    <select class="js-select2" data-search="on" name="ministry_id" id="ministry_id" onchange="form_check();">
                                        <option value="all">All Ministry</option>
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
                        
                    </div>
                    
                    <div class="card card-bordered card-stretch" id="form_view" >
                        <div class="card-inner-group">
                            <div class="card-inner position-relative card-tools-toggle">
                                <div class="card-title-group">
                                    <div class="card-tools">
                                        
                                    </div><!-- .card-tools -->
                                    <div class="card-tools me-n1" id="button_view" style="display:none;" >
                                        <ul class="btn-toolbar gx-1">
                                           
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li>
                                                <a href="javascript:;" pageName="<?=site_url('church/form/manage'); ?>" pageTitle="Add" data-bs-toggle="tooltip" data-bs-placement="top" title="New Form" pageSize="modal-lg" class="btn btn-icon btn-outline-primary pop"><em class="icon ni ni-plus-c"></em></a>
                                            </li>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                               
                            </div><!-- .card-inner -->
                            <div class="table-responsive" id="form_resp" style="display:none;" >
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Label</th>
                                            <th>Type </th>
                                            <th>Options</th>
                                            <th>Is Required</th>
                                            <th ></th>
                                        </tr>
                                    </thead>
                                    <tbody id="load_data"> </tbody>
                                    <tfoot id="loadmore"></tfoot>
                                </table>
                                
                            </div><!-- .card-inner -->
                            <div class="row text-center h3 text-danger" >
                                <div class="col-12 p-3" id="form_response" style="display:none;" ></div>
                                
                                
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
        form_check();
    });

    function form_check(){
        var ministry_id = $('#ministry_id').val();
        var level = $('#level').val();
        var church_id = $('#church_id').val();
        
        $('#button_view').hide(500);
        $('#form_resp').hide(500);
            
        // Perform validation
        $('#form_response').show(500);
        if (!ministry_id || ministry_id === "all") {
            $('#form_response').html("Please select a valid ministry.");
        } else if (!level || level === "all") {
            $('#form_response').html("Please select a valid level.");
        } else {
            $('#form_response').html('Loading Church!');
            getChurch();
        }
    }


    function load(x, y) {
        
        var church_id = $('#church_id').val();

        if (!church_id || church_id === "all") {
            $('#form_response').html("Please select a valid church.");
            
            $('#form_response').show(500);
            $('#button_view').hide(500);
            $('#form_resp').hide(500);
        } else {
            $('#form_response').hide(500);
            $('#form_response').html('');
            // Proceed if all validations pass
            $('#button_view').show(500);
            var search = $('#search').val();
            $('#load_data').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
            $('#form_resp').show(500);
            $.ajax({
                url: site_url + 'church/form/load',
                type: 'post',
                data: {church_id: church_id },
                success: function (data) {
                    var dt = JSON.parse(data);
                    $('#load_data').html(dt.item);
                    
                },
                complete: function () {
                    $.getScript(site_url + '/assets/js/jsmodal.js');
                }
            });
        
        }
    }

    function getChurch() {
        var ministry_id = $('#ministry_id').val();
        var level = $('#level').val();
        var church_id = $('#church_id').val();

        $.ajax({
            url: site_url + 'church/form/get_church',
            type: 'post',
            data: {level: level, ministry_id:ministry_id },
            success: function (data) {
                var dt = JSON.parse(data);
                $('#church_id').html(dt.churches);
               
                $('#form_response').html('');
                load();
               
            }
        });
    }

</script>   

<?=$this->endSection();?>

