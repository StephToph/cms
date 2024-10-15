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
                </tr>
                
                <tr>
                    <td><b>Church</b></td>
                    <td><?=ucwords($this->Crud->read_field('id', $e_church_id, 'church', 'name'));?> </td>
                </tr>
                
                <tr>
                    <td><b>Date</b></td>
                    <td><?=date('d F Y', strtotime($e_date));?></td>
                </tr>
                
                <tr>
                    <td><b>Type</b></td>
                    <td><?=ucwords($e_type);?></td>
                </tr>
                <tr>
                    <td><b>Notes</b></td>
                    <td style="word-wrap: break-word;white-space: normal; max-width: 300px;"><?=ucwords($e_notes);?></td>
                </tr>
                
                <tr>
                    <td><b>Follow Up By</b></td>
                    <td><?php
                        $names = $this->Crud->read_field('id', $e_member_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $e_member_id, 'user', 'surname');
                        $phone = $this->Crud->read_field('id', $e_member_id, 'user', 'phone');
                        echo '
                            <div class="user-card mx-2">
                                <div class="user-info">
                                    <span class="tb-lead">' . ucwords($names) . ' </span><br>
                                    <span class="small text-info">' . ucwords($phone) . '</span>
                                </div>
                            </div>
                        ';
                            
                    ?> </td>
                </tr>
                <tr>
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
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <input type="hidden" name="user_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label>Type</label>
                    <select class="js-select2" name="type" id="type" data-placeholder="Select Type" required>
                        <option value=" ">Select Type</option>
                        <option value="call" <?php if(!empty($e_type)){if($e_type ==  'call'){echo 'selected';}}; ?>>Phone Call</option>
                        <option value="visit" <?php if(!empty($e_type)){if($e_type ==  'visit'){echo 'selected';}}; ?>>House Visit</option>
                        <option value="email" <?php if(!empty($e_type)){if($e_type ==  'email'){echo 'selected';}}; ?>>Email Address</option>
                        <option value="message" <?php if(!empty($e_type)){if($e_type ==  'message'){echo 'selected';}}; ?>>Message/Chat</option>
                        
                    </select>
                </div>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <div class="form-control-wrap">
                        <input type="text" data-date-format="yyyy-mm-dd" name="date" id="date"
                            class="form-control date-picker" value="<?php if (!empty($e_date)) {
                                echo date('Y-m-d', strtotime($e_date));
                            } ?>">
                    </div>

                </div>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label  class="form-label" for="name">Notes</label>
                    <textarea id="summernote" class="form-control" name="notes" rows="5" required><?php if (!empty($e_notes)) {
                        echo $e_notes;
                    } ?></textarea>
                </div>
            </div>

       
            <div class="col-sm-12 text-center">
                <hr />
                <button class="btn btn-primary bb_frm_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
    <?php } ?>
    <?php if($param2 == 'admin_send') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?=translate_phrase('Are you sure?');?></b></h3>
                <input type="hidden" name="admin_id" value="<?php if(!empty($param3)){echo $param3;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-success text-uppercase" type="submit">
                    <em class="icon ni ni-share-alt"></em> <span><?=('Yes - Send Login Details');?></span>
                </button>
            </div>
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
</script>