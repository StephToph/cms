
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
                <input type="hidden" name="d_membership_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete'); ?>
                </button>
            </div>
        </div>
    <?php } ?>

        
    <?php if($param2 == 'view'){?>
        <b><?=ucwords($this->Crud->read_field('id', $param3, 'partnership', 'name')).'`s Partnership History '; ?></b><br>
        <table id="dtable" class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Members</th>
                    <th>Amount Paid</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $start_date = $p_start_date;
                    $end_date = $p_end_date;
                    
                    if(!empty($start_date) && !empty($end_date)){
                        $pays = $this->Crud->date_range1($start_date, 'date_paid', $end_date, 'date_paid', 'partnership_id', $param3, 'partners_history');

                    } else{
                        $pays = $this->Crud->read_single('partnership_id', $param3, 'partners_history');

                    }
                   
                    $total = 0;
                    if(!empty($pays)){
                        foreach($pays as $p){
                            $time = $p->date_paid;
                            $member_id = $p->member_id;
                            $amount_paid = $p->amount_paid;
                            $status = $p->status;
                            $st = '<span class="text-warning">Pending</span>';
                            if($status > 0)$st = '<span class="text-success">Confirmed</span>';
                        
                            ?>
                                <tr>
                                    <td><?=date('d M Y h:iA', strtotime($time)); ?></td>
                                    <td><?=ucwords($this->Crud->read_field('id', $member_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $member_id, 'user', 'surname')); ?></td>
                                    <td><?='$'.number_format($amount_paid,2); ?></td>
                                    <td><?=''.($st); ?></td>
                                </tr>
                    <?php
                                }
                            
                        
                    } else{
                        echo '<tr><td colspan="3" class="text-center">No Records</td></tr>';
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