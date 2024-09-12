
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
                <input type="hidden" name="d_dept_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete');?>
                </button>
            </div>
        </div>
    <?php } ?>

    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        
        
        <div class="row">
            <input type="hidden" name="dept_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Name'); ?></label>
                    <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_name)) {echo $e_name;} ?>" required>
                </div>
            </div>
        </div>
        

        <div class="row" >
            <div class="col-sm-12 text-center mt-3">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
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
</script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script