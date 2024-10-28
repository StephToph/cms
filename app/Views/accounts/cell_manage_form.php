<?php
use App\Models\Crud;
$this->Crud = new Crud();

$this->session = \Config\Services::session();
    $cell_id = $this->session->get('cell_id');
    
					
?>
<?php echo form_open_multipart($form_link, array('id' => 'bb_ajax_form', 'class' => '')); ?>
    <!-- delete view -->
    <?php if ($param2 == 'delete') { ?>
        <div class="row">
            <div class="col-sm-12">
                <div id="bb_ajax_msg"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?= translate_phrase('Are you sure?'); ?></b></h3>
                <input type="hidden" name="d_cell_id" value="<?php if (!empty($d_id)) {
                    echo $d_id;
                } ?>" />
            </div>

            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?= translate_phrase('Yes - Delete'); ?>
                </button>
            </div>
        </div>
    <?php } ?>


    <!-- insert/edit view -->
    <?php if ($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12">
                <div id="bb_ajax_msg"></div>
            </div>
        </div>


        <div class="row">
            <input type="hidden" name="member_id" value="<?php if (!empty($e_id)) {
                echo $e_id;
            } $cell_id = $this->session->get('cell_id'); ?>" />
            
            <input type="hidden" name="cell_id" value="<?=$cell_id;?>" />
            <?php  if ($param2 == '') { ?>

                <div class="col-sm-12 mb-3">
                    <div class="form-group">
                        <label for="name">*<?= translate_phrase('Church Members'); ?></label>
                        <select class="js-select2" data-search="on" multiple name="members[]" id="member_id">
                            <option value="">Select Members</option>
                            <?php
                            
                            $church_id = $this->Crud->read_field('id', $cell_id, 'cells', 'church_id');
                            $ministry = $this->Crud->read_single_order('church_id', $church_id, 'user', 'surname', 'asc');
                            if (!empty($ministry)) {
                                foreach ($ministry as $d) {
                                    if($d->cell_id > 0)continue;
                                    $sel = '';
                                    if (!empty($e_id)) {
                                        if ($e_id == $d->id) {
                                            $sel = 'selected';
                                        }
                                    }
                                    echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->surname.' '.$d->firstname.' - '.$d->phone) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            <?php } else{  ?>
                
                <div class="col-sm-12 mb-2">
                    <div class="form-group">
                        <label class="form-label">Cell Role</label>
                        <div class="form-control-wrap">
                            <select class="form-select js-select2" id="cell_role_i" name="cell_role_id"
                                data-placeholder="Select Cell Role">
                                <option value="">Select</option>
                                <?php
                                    $allowed = ['Cell Leader', 'Cell Executive', 'Assistant Cell Leader', 'Cell Member'];

                                    $parent  = $this->Crud->read_order('access_role', 'name', 'asc');
                                    if(!empty($parent)){
                                        foreach($parent as $p){
                                            if(!in_array($p->name, $allowed))continue;
                                            $sel = '';
                                            if(!empty($e_cell_role)){
                                                if($e_cell_role == $p->id){
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
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="activate"><?=translate_phrase('Activate');?></label>
                        <select class="form-control js-select2" data-toggle="select2" id="status" name="status" >
                            <option value="1" <?php if(!empty($e_status)){if($e_status == 1){echo 'selected';}} ?>><?=translate_phrase('Activate');?></option>
                            <option value="0" <?php if(empty($e_status)){if($e_status == 0){echo 'selected';}} ?>><?=translate_phrase('Disable');?></option>
                        </select>
                    </div>
                </div>
            <?php } ?>
        </div>


        <div class="row">

            <div class="col-sm-12 text-center mt-3">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?= translate_phrase('Save Record'); ?>
                </button>
            </div>
        </div>
    <?php } ?>

    <?php if($param2 == 'message') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($param3)){echo $param3;} ?>" />
            
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Subject');?></label>
                    <input type='text' class="form-control" name='subject' id='subject' required>
                </div>
            </div>
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Message');?></label>
                    <textarea class="form-control"name='message' id='message' rows="5" required></textarea>
                </div>
            </div>


            <div class="col-sm-12 mt-3 text-center">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-send"></i> <?=translate_phrase('Send Message');?>
                </button>
            </div>
        </div>
    <?php } ?>
    
    
    <?php if($param2 == 'bulk_message') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <input type="hidden" name="cell_id" value="<?php echo $cell_id; ?>" />
            
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Send to all Member?');?></label>
                    <select class="form-control js-select2" id="send_type" name="send_type">
                        <option value="executives"><?= translate_phrase('No - Cell Executives Only'); ?></option>
                        <option value="all"><?= translate_phrase('Yes - All Cell Members'); ?></option>
                    </select>
                </div>
            </div>

            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Member');?></label>

                    <select class="form-control js-select2" multiple data-search="on" data-toggle="select2" id="member_id" name="member_id[]">
                       
                    </select>
                </div>
            </div>

            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Subject');?></label>
                    <input type='text' class="form-control" name='subject' id='subject' required>
                </div>
            </div>
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Message');?></label>
                    <textarea class="form-control"name='message' id='message' rows="5" required></textarea>
                </div>
            </div>


            <div class="col-sm-12 mt-3 text-center">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-send"></i> <?=translate_phrase('Send Message');?>
                </button>
            </div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>

<script>
    $('.js-select2').select2();
    function updateMemberDropdown(type) {
        $('#member_id').empty(); // Clear existing options

        $.ajax({
            url: `<?= site_url('accounts/cell/getMembers/'); ?>${type}`, // Update with your actual controller URL
            type: "GET",
            dataType: "json",
            success: function(members) {
                members.forEach(member => {
                    // Check if the option with the same ID already exists
                    if ($(`#member_id option[value="${member.id}"]`).length === 0) {
                        $('#member_id').append(`<option value="${member.id}">${member.name}</option>`);
                    }
                });
            },
            error: function(error) {
                console.error("Error fetching members:", error);
            }
        });
    }

    $(document).ready(function() {
        // Initial load of executives only
        updateMemberDropdown("executives");

        // Update dropdown on send_type change
        $('#send_type').change(function() {
            const type = $(this).val();
            updateMemberDropdown(type);
        });
    });
   
</script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script