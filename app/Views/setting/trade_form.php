<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param2 == 'delete') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b>Are you sure?</b></h3>
                <input type="hidden" name="d_trade_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> Yes - Delete
                </button>
            </div>
        </div>
    <?php } ?>

    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <input type="hidden" name="trade_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="name">Trade</label>
                    <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_name)){echo $e_name;} ?>" required>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label for="name">Minimum Tax</label>
                    <input class="form-control" type="text" id="minimum" name="minimum" value="<?php if(!empty($e_minimum)){echo $e_minimum;} ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\..*?)\..*/g, '$1');"  required>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label for="name">Medium Tax</label>
                    <input class="form-control" type="text" id="medium" name="medium" value="<?php if(!empty($e_medium)){echo $e_medium;} ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\..*?)\..*/g, '$1');"  required>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="form-group">
                    <label for="name">Maximum Tax</label>
                    <input class="form-control" type="text" id="maximum" name="maximum" value="<?php if(!empty($e_maximum)){echo $e_maximum;} ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\..*?)\..*/g, '$1');"  required>
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

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>