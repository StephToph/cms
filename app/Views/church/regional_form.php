
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
                <div class="form-group"><b>Church Logo</b><br>
                    <label for="img-upload" class="pointer text-center" style="width:100%;">
                        <input type="hidden" name="img" value="<?php if(!empty($e_img)){echo $e_img;} ?>" />
                        <img id="img" src="<?php if(!empty($e_img)){echo site_url( $e_img);} ?>" style="max-width:100%;" />
                        <span class="btn btn-info btn-block no-mrg-btm">Choose Image</span>
                        <input class="d-none" type="file" name="pics" id="img-upload" accept="image/*">
                    </label>
                </div>
            </div>
            
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Name'); ?></label>
                    <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_name)) {echo $e_name;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name"><?=translate_phrase('Email'); ?></label>
                    <input class="form-control" type="email" id="email" name="email" value="<?php if(!empty($e_email)) {echo $e_email;} ?>" >
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name"><?=translate_phrase('Phone'); ?></label>
                    <input class="form-control" type="text" id="phone" name="phone" value="<?php if(!empty($e_phone)) {echo $e_phone;} ?>">
                </div>
            </div>

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name"><?=translate_phrase('Address'); ?></label>
                    <input class="form-control" type="text" id="address" name="address" value="<?php if(!empty($e_address)) {echo $e_address;} ?>" >
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name"><?=translate_phrase('Country'); ?></label>
                    <select id="country_i" name="country_id" class="js-select2">
                        
                        <option value="">Select</option>
                        <?php
                            $part = $this->Crud->read_order('country', 'name', 'asc');
                            if(!empty($part)){
                                foreach($part as $p){
                                    $sel = '';
                                    if(!empty($e_country_id)){
                                        if($e_country_id == $p->id){
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
            <?php if($role == 'developer' || $role == 'administrator'){?>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name"><?=translate_phrase('Ministry'); ?></label>
                    <select id="ministry_id" name="ministry_id" class="js-select2">
                        
                        <option value="">Select</option>
                        <?php
                            $part = $this->Crud->read_order('ministry', 'name', 'asc');
                            if(!empty($part)){
                                foreach($part as $p){
                                    $sel = '';
                                    if(!empty($e_ministry_id)){
                                        if($e_ministry_id == $p->id){
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
            
            <?php } else {?>
                <input type="hidden" id="ministry_id" name="ministry_id" value="<?=$this->Crud->read_field('id', $log_id, 'user', 'ministry_id'); ?>">
                
            <?php } ?>
           
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
    function readURL(input, id) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#' + id).attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#img-upload").change(function(){
		readURL(this, 'img');
	});
    </script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
