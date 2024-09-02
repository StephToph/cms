<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
 

    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <input type="hidden" name="territory_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />

            <div class="col-sm-12 mt-1">
                <div class="form-group">
                    <label for="name">LGA</label>
                    <select class="form-select js-select2" data-search="on" id="lga_id" name="lga_id">
                        <option value="all"><?=translate_phrase('Select LGA'); ?></option>
                            <?php 
                                $lga = $this->Crud->read_single_order('state_id', 316, 'city', 'name', 'asc');
                                if(!empty($lga)){
                                    foreach($lga as $l){
                                        $sel = '';
                                        if(!empty($e_lga_id)){
                                            if($e_lga_id == $l->id){
                                                $sel = 'selected';
                                            }
                                        }
                            ?>
                                <option value="<?=$l->id; ?>" <?=$sel; ?>><?=$l->name; ?></option>
                            <?php
                                    }}
                            ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label for="name">Territory</label>
                    <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_name)){echo $e_name;} ?>" required>
                </div>
            </div>
            <div class="col-sm-12 text-center">
                <hr />
                <button class="btn btn-primary bb_form_btn" type="submit">
                    <i class="icon ni ni-save"></i> Save Record
                </button>
            </div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>
<script>$('.js-select2').select2();</script>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>