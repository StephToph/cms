
<?php
use App\Models\Crud;
$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param2 == 'delete') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?=translate_phrase('Are you sure?');?></b></h3>
                <input type="hidden" name="d_cell_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete');?>
                </button>
            </div>
        </div>
    <?php } ?>

    
<?php if($param2 == 'view'){?>
    <table id="dtable" class="table table-striped">
        <thead>
            <tr>
                <th>Day</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $pays = $this->Crud->read_single('id', $param3, 'cells');

                $total = 0;
                if(!empty($pays)){
                    foreach($pays as $p){
                        $time = $p->time;
                        if(!empty(json_decode($time))){
                            foreach(json_decode($time) as $t => $val){
                       
                        ?>
                            <tr>
                                <td><?=$t ?></td>
                                <td><?=date('h:iA', strtotime($val)); ?></td>
                            </tr>
                   <?php
                            }
                        }
                    }
                }
                
            ?>
        </tbody>
    </table>

<?php } ?>
    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="cell_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Name'); ?></label>
                    <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_name)) {echo $e_name;} ?>" required>
                </div>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Location'); ?></label>
                    <input class="form-control" type="text" id="location" name="location" value="<?php if(!empty($e_location)) {echo $e_location;} ?>" required>
                </div>
            </div>
            
        </div>
        <div  id="containers">
            <?php if(!empty($e_time)){$a = 0;
                foreach($e_time as $k => $val){
                    $r_val = 'style="display:none;"';$req = 'required';
                    if($a > 0){
                        $r_val = 'style="display:display;"';$req = '';
                    }
                    ?>
                     <div class="row">
                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="name">*<?=translate_phrase('Meeting Day'); ?></label>
                                <select class="form-control" name="days[]"  <?=$req; ?>>
                                    <option value="">Select</option>
                                    <option value="Sunday" <?php if($k == 'Sunday'){echo 'selected';} ?>>Sunday</option>
                                    <option value="Monday" <?php if($k == 'Monday'){echo 'selected';} ?>>Monday</option>
                                    <option value="Tuesday" <?php if($k == 'Tuesday'){echo 'selected';} ?>>Tuesday</option>
                                    <option value="Wednesday" <?php if($k == 'Wednesday'){echo 'selected';} ?>>Wednesday</option>
                                    <option value="Thursday" <?php if($k == 'Thursday'){echo 'selected';} ?>>Thursday</option>
                                    <option value="Friday" <?php if($k == 'Friday'){echo 'selected';} ?>>Friday</option>
                                    <option value="Saturday" <?php if($k == 'Saturday'){echo 'selected';} ?>>Saturday</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-8 mb-3">
                                <label for="name">*<?=translate_phrase('Meeting Time'); ?></label>
                            <div class="form-group input-group">
                                <input class="form-control" type="time" id="location" value="<?php if(!empty($val)){echo $val;} ?>" name="times[]"  <?=$req; ?>>
                                <button <?=$r_val; ?>  class="btn btn-icon btn-outline-danger deleteBtns" type="button"><i class="icon ni ni-trash"></i> </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $a++; }} else {?>
            <div class="row">
                <div class="col-sm-4 mb-3">
                    <div class="form-group">
                        <label for="name">*<?=translate_phrase('Meeting Day'); ?></label>
                        <select class="form-control" name="days[]" required>
                            <option value="">Select</option>
                            <option value="Sunday">Sunday</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-8 mb-3">
                        <label for="name">*<?=translate_phrase('Meeting Time'); ?></label>
                    <div class="form-group input-group">
                        <input class="form-control" type="time" id="location" name="times[]" required>
                        <button style="display:none;"  class="btn btn-icon btn-outline-danger deleteBtns" type="button"><i class="icon ni ni-trash"></i> </button>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="col-sm-12 mb-3 text-center">
            <button id="addMores" class="btn btn-ico btn-outline-info" type="button"><i class="icon ni ni-plus-c"></i>  <?=translate_phrase('Add More Days');?></button>
        </div>

        <label for="name">*<?=translate_phrase('Cell Role');?></label>
        <div class="row" id="container">
            <?php if(!empty($e_roles)){$a = 0;
                foreach($e_roles as $k => $val){
                    $r_val = 'style="display:none;"';$req = 'required';
                    if($a > 0){
                        $r_val = 'style="display:display;"';$req = '';
                    }
                    ?>
                <div class="col-sm-12 mb-3 ">
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="role" placeholder="Enter Cell Roles" name="roles[]" value="<?php if(!empty($val)) {echo $val;} ?>" <?=$req; ?>>
                        <button <?=$r_val; ?>  class="btn btn-icon btn-outline-danger deleteBtn" type="button"><i class="icon ni ni-trash"></i> </button>
                    </div>
                    
                </div>
           <?php $a++; }} else {?>
                <div class="col-sm-12 mb-3 ">
                    <div class="form-group input-group">
                        <input class="form-control" type="text" id="role" placeholder="Enter Cell Roles" name="roles[]" value="<?php if(!empty($val)) {echo $val;} ?>" required>
                        <button style="display:none;" class="btn btn-icon btn-outline-danger deleteBtn" type="button"><i class="icon ni ni-trash"></i> </button>
                    </div>
                    
                </div>
           <?php }?>
        </div>

        <div class="row" >
            <div class="col-sm-12 mb-3 text-center">
                <button id="addMore" class="btn btn-ico btn-outline-primary" type="button"><i class="icon ni ni-plus"></i> <?=translate_phrase('Add More Roles');?></button>
            </div>
            <div class="col-sm-12 text-center mt-3">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>
<script>
    $('.js-select2').select2();
    document.getElementById('addMore').addEventListener('click', function() {
        var container = document.getElementById('container');
        var div = container.children[0].cloneNode(true);
        
        // Clear input value of the cloned div
        div.querySelector('input').value = '';
        div.querySelector('input').removeAttribute('required');
        
        // Show delete button in the cloned div
        div.querySelector('.deleteBtn').style.display = 'inline-block';
        
        // Add event listener to delete button
        div.querySelector('.deleteBtn').addEventListener('click', function() {
            div.parentNode.removeChild(div);
        });
        
        container.appendChild(div);
    });

    $('#addMores').on('click', function() {
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
    $('#containers').on('click', '.deleteBtns', function() {
        $(this).closest('.row').remove();
    });

    $('#containers').on('change', 'select[name="day[]"]', function() {
        var timeInput = $(this).closest('.row').find('input[name="time[]"]');
        if ($(this).val()) {
            timeInput.attr('required', 'required');
        } else {
            timeInput.removeAttr('required');
        }
    });

</script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script