<?php
use App\Models\Crud;
$this->Crud = new Crud();

$this->session = \Config\Services::session();
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
            <h3><b><?= translate_phrase('Are you sure?'); ?></b></h3>
            <input type="hidden" name="d_cell_id" value="<?php if (!empty($d_id)) {
                echo $d_id;
            } ?>" />
        </div>

        <div class="col-sm-12 text-center">
            <button class="btn btn-danger text-uppercase" type="submit">
                <i class="icon ni ni-trash"></i> <?= translate_phrase('Yes - Delete'); ?>
            </button>
        </div>
    </div>
<?php } ?>


<!-- insert/edit view -->
<?php if ($param2 == 'edit' || $param2 == '') { ?>
    <div class="row">
        <div class="col-sm-12">
            <div id="bb_ajax_msg"></div>
        </div>
    </div>


    <div class="row">
        <input type="hidden" name="member_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />
        <?php  if ($param2 == '') { ?>

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?= translate_phrase('Church Members'); ?></label>
                    <select class="js-select2" data-search="on" multiple name="members[]" id="member_id">
                        <option value="">Select Members</option>
                        <?php
                        $cell_id = $this->session->get('cell_id');
                        $church_id = $this->Crud->read_field('id', $cell_id, 'cells', 'church_id');
                        $ministry = $this->Crud->read_single_order('church_id', $church_id, 'user', 'surname', 'asc');
                        if (!empty($ministry)) {
                            foreach ($ministry as $d) {
                                if($d->cell_id > 0)continue;
                                $sel = '';
                                if (!empty($e_id)) {
                                    if ($e_id == $d->id) {
                                        $sel = 'selected';
                                    }
                                }
                                echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->surname.' '.$d->firstname) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        <input type="hidden" name="cell_id" value="<?=$cell_id;?>" />
        <?php } else{?>
            
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label class="form-label">Cell Role</label>
                    <div class="form-control-wrap">
                        <select class="form-select js-select2" id="cell_role_i" name="cell_role_id"
                            data-placeholder="Select Cell Role">
                            <option value="">Select</option>
                            <?php
                                $allowed = ['Cell Leader', 'Cell Executive', 'Assistant Cell Leader', 'Cell Member'];

                                $parent  = $this->Crud->read_order('access_role', 'name', 'asc');
                                if(!empty($parent)){
                                    foreach($parent as $p){
                                        if(!in_array($p->name, $allowed))continue;
                                        $sel = '';
                                        if(!empty($e_cell_role)){
                                            if($e_cell_role == $p->id){
                                                $sel = 'selected';
                                            }
                                        }
                                        echo '<option value="'.$p->id.'" '.$sel.'>'.ucwords($p->name).'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Activate');?></label>
                    <select class="form-control js-select2" data-toggle="select2" id="status" name="status" >
                        <option value="1" <?php if(!empty($e_status)){if($e_status == 1){echo 'selected';}} ?>><?=translate_phrase('Activate');?></option>
                        <option value="0" <?php if(empty($e_status)){if($e_status == 0){echo 'selected';}} ?>><?=translate_phrase('Disable');?></option>
                    </select>
                </div>
            </div>
        <?php } ?>
    </div>


    <div class="row">

        <div class="col-sm-12 text-center mt-3">
            <button class="btn btn-primary bb_fo_btn" type="submit">
                <i class="icon ni ni-save"></i> <?= translate_phrase('Save Record'); ?>
            </button>
        </div>
    </div>
<?php } ?>
<?php echo form_close(); ?>
<script>
    $('.js-select2').select2();
 
    document.getElementById('addMores').addEventListener('click', function () {
        var container = document.getElementById('container');
        var div = container.children[0].cloneNode(true);

        // Clear input value of the cloned div
        div.querySelector('input').value = '';
        div.querySelector('input').removeAttribute('required');

        // Show delete button in the cloned div
        div.querySelector('.deleteBtn').style.display = 'inline-block';

        // Add event listener to delete button
        div.querySelector('.deleteBtn').addEventListener('click', function () {
            div.parentNode.removeChild(div);
        });

        container.appendChild(div);
    });

    $('#addMores').on('click', function () {
        var container = $('#containers');
        var clonedRow = container.children('.row').first().clone();

        // Clear values of cloned inputs
        clonedRow.find('input').val('');
        clonedRow.find('select, input').removeAttr('required');

        // Hide delete button in the cloned row
        clonedRow.find('.deleteBtns').show();

        clonedRow.find('.js-select2').select2();
        // Append cloned row to container
        container.append(clonedRow);
    });

    // Event delegation to handle dynamically added delete buttons
    $('#containers').on('click', '.deleteBtns', function () {
        $(this).closest('.row').remove();
    });

    $('#containers').on('change', 'select[name="day[]"]', function () {
        var timeInput = $(this).closest('.row').find('input[name="time[]"]');
        if ($(this).val()) {
            timeInput.attr('required', 'required');
        } else {
            timeInput.removeAttr('required');
        }
    });
    </script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script