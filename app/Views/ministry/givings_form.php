
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
                <input type="hidden" name="d_giving_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
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
            <input type="hidden" name="giving_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <div class="col-sm-12 mb-3">
                <div class="form-group">*<label class="form-label">Date Paid</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right"><em class="icon ni ni-calendar"></em> </div>
                        <input type="text" name="date_paid" value="<?php if(!empty($e_date_paid)){echo $e_date_paid;} ?>" class="form-control date-picker" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" required>
                    </div>
                </div>
            </div>
            <?php if($role != 'member'){?>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Members'); ?></label>
                    <select id="rol_id" name="member_id" class="js-select2" required>
                        <option value="">Select</option>
                        <?php
                            $role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
                            $part = $this->Crud->read_single_order('role_id', 4, 'user', 'surname', 'asc');
                            if(!empty($part)){
                                foreach($part as $p){
                                    $sel = '';
                                    if(!empty($e_member_id)){
                                        if($e_member_id == $p->id){
                                            $sel = 'selected';
                                        }
                                    }
                                    echo '<option value="'.$p->id.'" '.$sel.'>'.ucwords($p->firstname.' '.$p->surname).'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <?php } else {?>
                <input type="hidden" name="member_id" value="<?=$log_id; ?>">
            <?php } ?>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Partnership'); ?></label>
                    <select id="role_id" name="partnership_id" class="js-select2" required>
                        <option value="">Select</option>
                        <?php
                            $part = $this->Crud->read_order('partnership', 'name', 'asc');
                            if(!empty($part)){
                                foreach($part as $p){ 
                                    $sel = '';
                                    if(!empty($e_partnership_id)){
                                        if($e_partnership_id == $p->id){
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
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Amount'); ?></label>
                    <input class="form-control" type="text" id="amount" name="amount" value="<?php if(!empty($e_amount_paid)) {echo $e_amount_paid;} ?>" required>
                </div>
            </div>

            <?php if($role == 'member'){?>
                <div class="col-sm-12 mb-3">
                    <div class="form-group"><b>Upload Receipt </b><span class="text-danger small">(Screenshot)</span><br>
                        <label for="img-upload" class="pointer text-center" style="width:100%;">
                            <input type="hidden" name="img" value="<?php if(!empty($e_img)){echo $e_img;} ?>" />
                            <img id="img" src="<?php if(!empty($e_img)){echo site_url( $e_img);} ?>" style="max-width:100%;" />
                            <span class="btn btn-info btn-block no-mrg-btm">Upload Screenshot</span>
                            <input class="d-none" type="file" name="pics" accept="image/*" id="img-upload">
                        </label>
                    </div>
                </div>
            <?php } ?>
            <?php if($role != 'member'){?>
                <div class="col-sm-12 mb-3">
                    <div class="form-group">
                        <label for="name">*<?=translate_phrase('Payment Status'); ?></label>
                        <select id="status" name="status" class="js-select2">
                            <option value="0" <?php if(!empty($e_status)){if($e_status == 0){echo 'selected';}} ?>>Pending</option>
                            <option value="1" <?php if(!empty($e_status)){if($e_status == 1){echo 'selected';}} ?>>Confirmed</option>
                        </select>
                    </div>
                </div>
            <?php } else{?>
                <input type="hidden" name="status" value="0">
            <?php } ?>
            
        </div>

        <div class="row" >
            
            <div class="col-sm-12 text-center mt-5">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>
<script>
    $('.js-select2').select2();
    $('.date-picker').datepicker();        
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

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script