<?php
use App\Models\Crud;

$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id' => 'bb_ajax_form', 'class' => '')); ?>
<!-- delete view -->
<?php if ($param2 == 'delete') { ?>
    <div class="row">
        <div class="col-sm-12">
            <div id="bb_ajax_msg"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 text-center">
            <h3><b>Are you sure?</b></h3>
            <input type="hidden" name="d_id" value="<?php if (!empty($d_id)) {
                echo $d_id;
            } ?>" />
        </div>

        <div class="col-sm-12 text-center">
            <button class="btn btn-danger text-uppercase" type="submit">
                <i class="icon ni ni-trash"></i> Yes - Delete
            </button>
        </div>
    </div>
<?php } ?>

<?php if ($param2 == 'view') { ?>
    <div class="row">
        
        <div class="col-sm-12 mb-3 table-responsive">
            <table class="table table-hovered">
                
                <tr>
                    <td><h5 class="text-center text-info"><?= ucwords($e_title); ?></h5></td>
                </tr>
                <tr>
                    <td><?= ucwords(($e_description)); ?></d></td>
                </tr>
            </table>
            <table class="table table-hovered">
                <tr>
                    <td><b>Minstry</b></td>
                    <td><?=$this->Crud->read_field('id', $e_ministry_id, 'ministry', 'name');?></td>
                    <td><b>Church Level</b></td>
                    <td><?=ucwords($e_church_type);?> Level</td>
                    
                </tr>
                <tr>
                    <td><b>Form is For</b></td>
                    <td><?=ucwords($e_send_type);?> Church</td>
                    <td><b>Created At</b></td>
                    <td><?=date('d F Y h:i:sA', strtotime($e_reg_date));?></td>
                </tr>
                <?php if($e_church_type != 'all'){?>
                <tr>
                   
                    <td><b>Church</b></td>
                    <td colspan="4"><?php 
                        $church = '';
                        if(!empty($e_church_id)){
                            $churches = json_decode($e_church_id);
                            if(!empty($churches)){
                                foreach($churches as $c => $val){
                                    $church .=  ucwords($this->Crud->read_field('id', $val, 'church', 'name')).', ';
                                }
                            }
                        }
                        echo rtrim($church,', ');
                       
                    ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="5" class=" text-center"><b class="text-danger">Form Fields</b></td>
                </tr>
                <?php 
                    
                    if(!empty($e_fields)){
                        foreach($e_fields as $f => $field){
                            $type = str_replace('_', ' ',  $field->type);
                            $opts = '';
                            if($field->type == 'single_choice' || $field->type == 'multiple_choice'){
                                $opt = '';
                                $options = $field->options;
                                foreach($options as $op => $option){
                                    $opt .= $option.', ';
                                }
                                
                                $optaa = rtrim($opt, ', ');
                                $opta = '<span class="text-info">{'.ucwords($opt).'}</span>';
                            } else{
                                $opta = '';
                            }
                            $opts = $opta;
                            ?>
                            <tr>
                                <td colspan="2"><b><?=ucwords($field->label);?></b></td>
                                <td colspan="3"><?=ucwords($type).' '.$opts;?></td>
                            </tr>
                        <?php }
                    }
                    
                ?>

            </table>
        </div>

    </div>

<?php } ?>



<!-- insert/edit view -->
<?php if ($param2 == 'edit' || $param2 == '') { ?>
    <style>
        .text-right {
            text-align: right;
        }
        
    </style>
    <div class="row">
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />


        <div class="col-sm-12 mb-3 " >
            <?php
                $type = isset($type) ? $type : ''; 

                $displayStyle = ($type === 'single_choice' || $type === 'multiple_choice') ? 'display:block;' : 'display:none;';
            ?>
            <div class="row optionsa my-2 p-2">
                <div class="col-sm-6 mb-3">
                    <label>Field Label</label>
                    <input class="form-control" type="text" id="field_name" name="field_name" value="<?php if (!empty($e_field_name)) { echo $e_field_name; } ?>" required>
                </div>
    
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Field Type</label>
                        <select class="js-select2" data-search="on" name="field_type" id="field_type" required>
                            <option value="text" <?php if (!empty($e_field_type) && $e_field_type == 'text') { echo 'selected'; } ?>>Text</option>
                            <option value="number" <?php if (!empty($e_field_type) && $e_field_type == 'number') { echo 'selected'; } ?>>Number</option>
                            <option value="email" <?php if (!empty($e_field_type) && $e_field_type == 'email') { echo 'selected'; } ?>>Email</option>
                            <option value="date" <?php if (!empty($e_field_type) && $e_field_type == 'date') { echo 'selected'; } ?>>Date</option>
                            <option value="select" <?php if (!empty($e_field_type) && $e_field_type == 'select') { echo 'selected'; } ?>>Dropdown (Select)</option>
                            <option value="checkbox" <?php if (!empty($e_field_type) && $e_field_type == 'checkbox') { echo 'selected'; } ?>>Checkbox</option>
                            <option value="textarea" <?php if (!empty($e_field_type) && $e_field_type == 'textarea') { echo 'selected'; } ?>>Textarea</option>
                        </select>
                    </div>
                </div>
                
                
                <div class="col-sm-6 mb-3" id="field-options-container" style="display: none;">
                    <div class="mb-3" >
                        <label for="field-options" class="form-label">Field Options (comma-separated)</label>
                        <input type="text" class="form-control" id="field-options" value="<?php if (!empty($e_field_options)) { echo $e_field_options; } ?>" name="field_options" placeholder="Enter options separated by commas (e.g., Male, Female, Other)">
                    </div>
                </div>
                
                <div class="col-sm-6 mb-3">
                    <div class="mb-3">
                        <label for="is-required" class="form-label">Is Required</label>
                        <select class="js-select2" data-search="on" id="is-required" name="is_required" required>
                            <option value="1" <?php if (!empty($e_is_required) && $e_is_required == '1') { echo 'selected'; } ?>>Yes</option>
                            <option value="0" <?php if (!empty($e_is_required) && $e_is_required == '0') { echo 'selected'; } ?>>No</option>
                        </select>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-sm-12 text-center mt-4">
            <hr />
            <button class="btn btn-primary bb_for_btn" type="submit">
                <i class="icon ni ni-save"></i> Save
            </button>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-12 my-3">
            <div id="bb_ajax_msg"></div>
        </div>
    </div>

<?php } ?>
<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>

<script>

    $(function () {
        $('#summernote').summernote({
            height: 300, // Set the height of the editor
            tabsize: 2,
            focus: true
        });

    });

    $('#field_type').on('change', function () {
        var  selectedType = $(this).val();
        if(selectedType == 'select' || selectedType == 'checkbox'){
            $('#field-options-container').show(500);
        } else { 
            $('#field-options-container').hide(500);
        }
    });

    $('#field_type').trigger('change');
</script>
