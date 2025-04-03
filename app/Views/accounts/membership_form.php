
<?php
use App\Models\Crud;
$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param2 == 'delete') {?>
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


    <?php if($param2 == 'upload') { ?>
        <div class="row">
            <ul class="text-danger small mb-3">
                <li>To Upload Bulk Product; Be sure its a Valid Excel File</li>
                <li>Step to Design Excel Sheet for Bulk Upload;</li>
                <li><br></li>
                <li>Download the Bulk Upload Template and use it as guide for uploading memberships</li>
                <li>Do not Edit or Move the Headers</li>
            </ul>
            
            <div class="col-sm-12 mt-3 mb-3 text-center">
                <a href="javascript:void(0);" class="btn btn-success text-uppercase" onclick="downloadProductTemplate()" type="button">
                    <em class="icon ni ni-download"></em><span> Download Membership Upload Template</span>
                </a>
            </div>
            
            <div class="col-sm-12 text-cente">
                <div class="form-group">
                    <label for="activate">Excel File</label>
                    <input type="file" class="form-control" name="csv_file" id="csv_file" required accept=".xlsx" />
                </div>
            </div>
            
            <div class="col-sm-12 mt-3 mb-3 text-center">
                <button class="btn btn-info text-uppercase" type="submit">
                    <i class="icon ni ni-upload"></i> <span>Yes - Upload</span>
                </button>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>
    <?php } ?>
    <?php if($param2 == 'view'){?>
        <b><?=ucwords($this->Crud->read_field('id', $param3, 'partnership', 'name')).'`s Partnership History '; ?></b><br>
        <div class="table-responsive">
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
                        $pays = $this->Crud->read2('member_id', $param4,'partnership_id', $param3, 'partners_history');

                        $total = 0;
                        if(!empty($pays)){
                            foreach($pays as $p){
                                $time = $p->reg_date;
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
        </div>

    <?php } ?>
    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

    <?php } ?>
    <?php if($param2 == 'leaders') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Make this Member a Leader');?></label>
                    <select class="form-control js-select2" data-toggle="select2" id="is_leader" name="is_leader" required>
                        <option value="1" <?php if(!empty($e_is_leader)){if($e_is_leader == 1){echo 'selected';}} ?>><?=translate_phrase('Yes - Make a Leader');?></option>
                        <option value="0" <?php if(!empty($e_is_leader)){if($e_is_leader == 0){echo 'selected';}} ?>><?=translate_phrase('No - Remove from Leadership');?></option>
                    </select>
                </div>
            </div>


            <div class="col-sm-12 mt-3 text-center">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Update Record');?>
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
    <?php if($param2 == 'message') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            
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
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <?php $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                if($church_id <= 0){?>
                <div class="col-sm-12 mb-2">
                    <div class="form-group">
                        <label for="activate"><?=translate_phrase('Show all Members?');?></label>
                        <select class="form-control js-select2" data-toggle="select2" id="include_church" name="include_church">
                            <option value="false"><?=translate_phrase('No - Display only members I oversee'); ?></option>
                            <option value="true"><?=translate_phrase('Yes - Display all members within my church and affiliated branches'); ?></option>
                        </select>
                    </div>
                </div>
                
                
            <?php } else { ?>
                <input type="hidden" id="include_church" value="false" />
            <?php } ?>
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
    $('#message').summernote({
        height: 300, // Set the height of the editor
        tabsize: 2,
        focus: true
    });

    function downloadProductTemplate() {
        // Show loading state
        $('#bb_ajax_msg').html('<p class="text-info">Processing your request...</p>');

        $.ajax({
            url: site_url + 'accounts/membership/manage/upload/download',
            type: 'POST',
            xhrFields: {
                responseType: 'blob' // Important for binary file
            },
            success: function (data, status, xhr) {
                var disposition = xhr.getResponseHeader('Content-Disposition');
                var filename = 'membership_upload_template.xlsx'; // Default fallback

                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, '').trim();
                    }
                }

                var blob = new Blob([data], { type: xhr.getResponseHeader('Content-Type') });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
                document.body.appendChild(link); // Required for Firefox
                link.click();
                document.body.removeChild(link);

                // Show success
                $('#bb_ajax_msg').html('<p class="text-success">Template downloaded successfully!</p>');
            },
            error: function (xhr, status, error) {
                $('#bb_ajax_msg').html('<p class="text-danger">Error: Unable to download the template. Please try again later.</p>');
            }
        });
    }

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

    $(document).ready(function() {
        // Initialize select2 for member dropdown
        $('#member_id').select2({
            placeholder: 'Select Member(s)',
            allowClear: true
        });

        // Function to load members based on the selected option in include_church
        function loadMembers(includeChurch) {
            // Clear the member dropdown options
            $('#member_id').empty();

            $.ajax({
                url: '<?=site_url('accounts/membership/get_member'); ?>', // Replace with your actual API endpoint
                type: 'GET',
                data: {
                    include_church: includeChurch
                },
                success: function(response) {
                    // Clear the member_id dropdown
                    $('#member_id').empty();
                    $('#member_id').html(response);
                   
                  
                },
                error: function() {
                    alert('Failed to load members');
                }
            });
        }

        // Trigger loadMembers function on change of include_church dropdown
        $('#include_church').on('change', function() {
            const includeChurch = $(this).val() === 'true';
            loadMembers(includeChurch);
        });

        // Load members initially based on the default value of include_church
        const initialIncludeChurch = $('#include_church').val() === 'true';
        loadMembers(initialIncludeChurch);
    });


</script>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>