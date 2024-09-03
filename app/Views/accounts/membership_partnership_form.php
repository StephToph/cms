
<?php
use App\Models\Crud;
$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param3 == 'add' || $param3 == 'edit') { ?>
        <input type="hidden" name="part_id" value="">
        <div class="row">
            <?php 
                $value = 0;
                $partner = $this->Crud->read_order('partnership', 'name', 'asc');
                if(!empty($partner)){
                    foreach($partner as $p){ 
                        $amount = 0;
                        if($param3 == 'edit'){
                            $goal = json_decode($e_partnership);
                            // Convert stdClass object to array
                            $arrayFromObject = (array) $goal;
                            if (array_key_exists($p->id, $arrayFromObject)) {
                                if ($arrayFromObject[$p->id] == null) {
                                    $value = 0;
                                } else {
                                    $value = $arrayFromObject[$p->id];
                                }
                            } else {
                                // Key does not exist, return 0
                                $value = 0;
                            }

                        }

                        ?>
                    <div class="col-sm-6 mb-2">
                        <div class="form-group" id="">
                            <label for="activate"><?=translate_phrase('Partnership');?></label>
                            <input class="form-control" type="text" value="<?=ucwords($p->name); ?>" id="partnership" name="partnership[]" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <div class="form-group">
                            <label for="password"><?=translate_phrase('Yearly Goal');?></label>
                            <input class="form-control" type="text" id="goal" value="<?=$value; ?>" name="goal[]">
                        </div>
                    </div>
                <?php 
                }
            }
            ?>

            <div class="col-sm-12 mt-3 mb-3 text-center">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>

            <div class="row">
                <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
            </div>
        
        </div>
    <?php } ?>

    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="user_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Ban');?></label>
                    <select class="form-control js-select2" data-toggle="select2" id="ban" name="ban" required>
                        <option value="1" <?php if(!empty($e_activate)){if($e_activate == 1){echo 'selected';}} ?>><?=translate_phrase('No');?></option>
                        <option value="0" <?php if(empty($e_activate)){if($e_activate == 0){echo 'selected';}} ?>><?=translate_phrase('Yes');?></option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group" id="">
                    <label for="activate"><?=translate_phrase('Role');?></label>
                    <select class="form-select js-select2" data-search="on" id="role" name="role">
                        <option value=" ">Select</option>
                        <?php $cat = $this->Crud->read_single_order('name!=', 'Developer', 'access_role', 'name', 'asc');
                            foreach ($cat as $ca) {
                                // if($ca->name == 'Administrator') continue;
                                if($role == 'manager' && $ca->name == 'Manager') continue;
                                ?>
                                <option value="<?=$ca->id;?>" <?php if(!empty($e_role_id)){if($e_role_id == $ca->id){echo 'selected';}} ?>><?=ucwords($ca->name); ?></option>
                            <?php }?>
                    </select>
                </div>
            </div>
            
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label">Trade/Business Type <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-control-group">
                        <select class="form-control form-control-lg js-select2" id="trade" data-search="on" name="trade"  required>
                            <option value="0"><?=translate_phrase('--Select Trade Type--'); ?></option>
                            <?php
                                $country = $this->Crud->read_order('trade', 'name', 'asc');
                                if(!empty($country)){
                                    foreach($country as $c){
                                        $sels = '';
                                        // if($sel == $c->id)$sels = 'selected';
                                        if(!empty($e_trade))if($e_trade == $c->id)$sels='selected';
                                        echo '<option value="'.$c->id.'" '.$sels.'>'.$c->name.'</option>';
                                    }
                                } 
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="password"><?=translate_phrase('Reset Password');?></label>
                    <input class="form-control" type="text" id="password" name="password">
                </div>
            </div>
            

            <div class="col-sm-12 mt-3 text-center">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="ri-save-line"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>
<script>
    $('.js-select2').select2();
   
    function statea() {
        var country = $('#country_id').val();
        $.ajax({
            url: '<?=site_url('accounts/get_state/');?>'+ country,
            success: function(data) {
                $('#state_resp').html(data);
            }
        });
        
    }

    function lgaa() {
        var lga = $('#state').val();
        $.ajax({
            url: '<?=site_url('accounts/get_lga/');?>'+ lga,
            success: function(data) {
                $('#lga_resp').html(data);
            }
        });
    }

    function branc() {
        var lgas = $('#lga').val();
        $.ajax({
            url: '<?=site_url('accounts/get_branch/');?>'+ lgas,
            success: function(data) {
                $('#branch_resp').html(data);
            }
        });
    }

</script>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>