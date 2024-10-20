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
                <h3><b><?=translate_phrase('Are you sure?'); ?></b></h3>
                <input type="hidden" name="d_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete'); ?>
                </button>
            </div>
        </div>
    <?php } ?>

    <?php if ($param2 == 'view') { ?>
    <div class="row">
        <div class="col-sm-12 mb-3 table-responsive">
            
            <table class="table table-hovered table-borderless">
                <tr>
                    <td><b>Ministry</b></td>
                    <td><?=$this->Crud->read_field('id', $e_ministry_id, 'ministry', 'name');?></td>
                
                    <td><b>Church</b></td>
                    <td><?=ucwords($this->Crud->read_field('id', $e_church_id, 'church', 'name'));?> </td>
                </tr>
                <tr>
                    <td><b>Source Type</b></td>
                    <td><?=ucwords($e_source_type);?></td>
                
                    <td><b>Source</b></td>
                    <td><?php 
                        if($e_source_type == 'cell'){
                            $type = $this->Crud->read_field('id', $e_source_id, 'cell_report', 'type');
                            if($type == 'wk1')$source = 'WK1 - Prayer and Planning';
							if($type == 'wk2')$source = 'Wk2 - Bible Study';
							if($type == 'wk3')$source = 'Wk3 - Bible Study';
							if($type == 'wk4')$source = 'Wk4 - Fellowship / Outreach';
							if($type == 'wk5')$source = 'Wk5 - Fellowship';
                            echo ucwords($source);
                        }
                        if($e_source_type == 'service'){
                            $type = $this->Crud->read_field('id', $e_source_id, 'service_report', 'type');
                            
							$source = $this->Crud->read_field('id', $type, 'service_type', 'name');
                            echo ucwords($source);
                        }
                        ?> </td>
                </tr>
                <tr>
                <td><b>Visit Date</b></td>
                    <td><?=date('d F Y', strtotime($e_visit_date));?></td>
                
                    <td><b>Title</b></td>
                    <td><?=ucwords($e_title);?></td>
                </tr>
                <tr>
                    <td><b>Full Name</b></td>
                    <td><?=ucwords($e_fullname);?></td>
                
                    <td><b>Email</b></td>
                    <td><?=($e_email);?> </td>
                </tr>
                <tr>
                    <td><b>Phone</b></td>
                    <td><?=$e_phone;?></td>
                
                    <td><b>Gender</b></td>
                    <td><?=ucwords($e_gender);?> </td>
                </tr>
                <tr>
                    <td><b>DOB</b></td>
                    <td><?=($e_dob);?> </td>
                
                    <td><b>Invited By</b></td>
                    <td><?=ucwords($e_invited_by);?></td>
                </tr>
                
                <tr>
                    <td><b>Channel</b></td>
                    <td><?php if($e_invited_by == 'Member'){
                            $channel = $this->Crud->read_field('id', $e_channel, 'user', 'firstname').' '.$this->Crud->read_field('id', $e_channel, 'user', 'surname');
                        } else{
                            $channel = $e_channel;
                        }
                        echo ucwords($channel);
                    ?></td>
                
                    <td><b>Foundation School</b></td>
                    <td><?php 
                        if($e_foundation_school == 1){
                            echo 'Student - Week {'.$e_foundation_weeks.'}';
                        } elseif($e_foundation_school == 2){
                            echo 'Graduate';
                        } else{
                            echo 'Prospective Student';
                        }
                    ?> </td>
                </tr>
                <tr>
                    <td><b>Assigned To</b></td>
                    <td><?php
                        $assigned = json_decode($e_assigned_to);
                        if(!empty($assigned)){
                            foreach($assigned as $as){
                                $names = $this->Crud->read_field('id', $as, 'user', 'firstname').' '.$this->Crud->read_field('id', $as, 'user', 'surname');
                                $phone = $this->Crud->read_field('id', $as, 'user', 'phone');
                                echo '
                                    <div class="user-card mx-2">
										<div class="user-info">
											<span class="tb-lead">' . ucwords($names) . ' </span><br>
											<span class="small text-info">' . ucwords($phone) . '</span>
										</div>
									</div>
                                ';
                            }
                        } else{
                            echo 'Not Assigned to Leader';
                        }
                    ?> </td>
                </tr>
                <tr>
                    <td><b>Membership</b></td>
                    <td><?php
                        if(!empty($e_is_member)){
                            echo 'Now a Member - {'.$e_user_no.'}';
                        } else{
                            echo 'Not yet a Member';
                        }
                    ?></td>
                </tr>
                <tr>
                    <td><b>Follow Up</b></td>
                    <td><?=$this->Crud->check('id', $e_id, 'follow_up');?></td>
                
                    <td><b>Created At</b></td>
                    <td><?=date('d F Y h:i:sA', strtotime($e_reg_date));?></td>
                </tr>

            </table>
        </div>

    </div>

<?php } ?>

    <!-- insert/edit view -->
    
    <?php if($param2 == 'edit' || $param2 == '') { ?>
      

        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label>Title</label>
                    <select class="js-select2" name="title" id="title" data-placeholder="Select Title" required>
                        <option value="">Select Title</option>
                        <option value="Mr." <?php if(!empty($e_title)){if($e_title ==  'Mr.'){echo 'selected';}}; ?>>Mr.</option>
                        <option value="Mrs." <?php if(!empty($e_title)){if($e_title ==  'Mrs.'){echo 'selected';}}; ?>>Mrs.</option>
                        <option value="Ms." <?php if(!empty($e_title)){if($e_title ==  'Ms.'){echo 'selected';}}; ?>>Ms.</option>
                        <option value="Brother" <?php if(!empty($e_title)){if($e_title ==  'Brother'){echo 'selected';}}; ?>>Brother</option>
                        <option value="Sister" <?php if(!empty($e_title)){if($e_title ==  'Sister'){echo 'selected';}}; ?>>Sister</option>
                        <option value="Evang." <?php if(!empty($e_title)){if($e_title ==  'Evang.'){echo 'selected';}}; ?>>Evang.</option>
                        <option value="Deacon" <?php if(!empty($e_title)){if($e_title ==  'Deacon'){echo 'selected';}}; ?>>Deacon</option>
                        <option value="Deaconess" <?php if(!empty($e_title)){if($e_title ==  'Deaconess'){echo 'selected';}}; ?>>Deaconess</option>
                        <option value="Pastor" <?php if(!empty($e_title)){if($e_title ==  'Pastor'){echo 'selected';}}; ?>>Pastor</option>
                        <option value="Rev." <?php if(!empty($e_title)){if($e_title ==  'Rev.'){echo 'selected';}}; ?>>Rev.</option>
                        
                    </select>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Full Name'); ?></label>
                    <input class="form-control" type="text" id="fullname" name="fullname" value="<?php if(!empty($e_fullname)) {echo $e_fullname;} ?>" readonly>
                </div>
            </div>


            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Email');?></label>
                    <input class="form-control" type="text" id="email" name="email" value="<?php if(!empty($e_email)) {echo $e_email;} ?>" readonly>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Phone');?></label>
                    <input class="form-control" type="text" id="phone" name="phone" value="<?php if(!empty($e_phone)) {echo $e_phone;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label>Gender</label>
                    <select class="js-select2" name="gender" id="gender" data-placeholder="Select Gender" required>
                        <option value="">Select Gender</option>
                        <option value="male" <?php if(!empty($e_gender)){if($e_gender ==  'male'){echo 'selected';}}; ?>>Male</option>
                        <option value="female" <?php if(!empty($e_gender)){if($e_gender ==  'female'){echo 'selected';}}; ?>>Female</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label class="form-label">DOB</label>
                    <div class="form-control-wrap">
                        <input type="text" data-date-format="yyyy-mm-dd" name="dob" id="dob"
                            class="form-control date-picker" value="<?php if (!empty($e_dob)) {
                                echo date('Y-m-d', strtotime($e_dob));
                            } ?>">
                    </div>

                </div>
            </div>
            
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label>Foundation School</label>
                    <select class="js-select2" name="foundation_school" id="foundation_school" data-placeholder="Select" >
                        <option value="0" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '0'){echo 'selected';}}; ?>>Prospective Student</option>
                        <option value="1" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '1'){echo 'selected';}}; ?>>Student</option>
                        <option value="2" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '2'){echo 'selected';}}; ?>>Graduate</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3" style="display:none;" id="foundation_resp">
                <div class="form-group">    
                    <label class="form-label">Foundation Week</label>
                    <div class="input-group mb-3">
                        <button class="btn btn-outline-secondary decrease" type="button" id="button-minus">
                            <em class="icon ni ni-minus"></em>
                        </button>
                        
                        <input type="number" class="form-control text-center" name="foundation_weeks" id="spinner" max="7" min="1" step="1" value="<?php if(!empty($e_foundation_weeks)){echo $e_foundation_weeks;}else{echo '1';}; ?>">
                        
                        <button class="btn btn-outline-secondary increase" type="button" id="button-plus">
                            <em class="icon ni ni-plus"></em>
                        </button>
                    </div>
                </div> 
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label>Assign to Leader</label>
                    <select class="js-select2" name="is_assign" id="is_assign" data-placeholder="Select" >
                        <option value="0" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '0'){echo 'selected';}}; ?>>No - Do Not Assign</option>
                        <option value="1" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '1'){echo 'selected';}}; ?>>Yes - Assign</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 mb-3" style="display:none;" id="assign_resp">
                <div class="form-group">
                    <label>Leader</label>
                    <select class="js-select2" name="assigned_to[]" data-search="on" multiple id="assigned_to" data-placeholder="Select">
                    <?php
                        $member = $this->Crud->read_single_order('church_id', $e_church_id, 'user', 'surname', 'asc');
                        if (!empty($member)) {
                            foreach ($member as $d) {
                                $sel = '';
                                if (!empty($e_assigned_to)) {
                                    if (in_array($d->id, json_decode($e_assigned_to))) {
                                        $sel = 'selected';
                                    }
                                }
                                echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->firstname.' '.$d->surname) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php if(empty($e_is_member)){if($e_is_member ==  '0'){?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Move to Membership</label>
                        <select class="js-select2" name="is_member" id="is_member" data-placeholder="Select" >
                            <option value="0" <?php if(!empty($e_is_member)){if($e_is_member ==  '0'){echo 'selected';}}; ?>>No - Do Not Move</option>
                            <option value="1" <?php if(!empty($e_is_member)){if($e_is_member ==  '1'){echo 'selected';}}; ?>>Yes - Move</option>
                        </select>
                    </div>
                </div>
            <?php } }?>
            <div class="col-sm-12 text-center">
                <hr />
                <button class="btn btn-primary bb_form_bt" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 my-3"><div id="bb_ajax_msg"></div></div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script>
    $(function() {
        $('.js-select2').select2();
    });
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd', // Set the date format
        autoclose: true
    });

    $('#is_assign').on('change', function () {
        var selectedType = $(this).val();
        if (selectedType == '1') {
            $('#assign_resp').show(500);
        } else{
            $('#assign_resp').hide(500);
        }
        
    });

    $('#foundation_school').on('change', function () {
        var selectedType = $(this).val();
        if (selectedType == '1') {
            $('#foundation_resp').show(500);
        } else{
            $('#foundation_resp').hide(500);
        }
        
    });
    $('#is_assign').trigger('change');
    $('#foundation_school').trigger('change');

    $('.increase').on('click', function() {
        let spinner = $('#spinner');
        let currentValue = parseInt(spinner.val());
        let max = parseInt(spinner.attr('max'));

        if (!isNaN(currentValue) && currentValue < max) {
            spinner.val(currentValue + 1);
        }
    });

    // Decrease button functionality
    $('.decrease').on('click', function() {
        let spinner = $('#spinner');
        let currentValue = parseInt(spinner.val());
        let min = parseInt(spinner.attr('min'));

        if (!isNaN(currentValue) && currentValue > min) {
            spinner.val(currentValue - 1);
        }
    });

    $('#spinner').on('input', function() {
        let value = parseInt($(this).val());

        // If input is not a number, set it to 0
        if (isNaN(value)) {
            $(this).val(0);
            return;
        }

        // Ensure the value is between 0 and 7
        if (value < 0) {
            $(this).val(0);
        } else if (value > 7) {
            $(this).val(7);
        }
    });

    // Prevent entering non-numeric characters
    $('#spinner').on('keypress', function(e) {
        let keyCode = e.which;

        // Allow only numbers (keycode for 0-9 is between 48 and 57)
        if (keyCode < 48 || keyCode > 57) {
            e.preventDefault();
        }
    });
</script>