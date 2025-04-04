
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
        
    <?php if ($param2 == 'link') { ?>
        <div class="row">
            <?php
                $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

                if ($church_id > 0) { ?>
                    <input type="hidden" name="ministry_id" id="ministry_id" value="<?php echo $ministry_id; ?>">
                    <input type="hidden" name="church_id" id="church_id" value="<?php echo $church_id; ?>">
                <?php }?>
                   
                <?php if ($church_id == 0) { ?>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label class="form-label">Church Type</label>
                            <select class="js-select2" data-search="on" name="level" id="level">
                                <option value="">Select Church Level</option>
                                <?php
                                $log_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                                $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');?>

                                    <option value="region" <?php if (!empty($e_level)) {
                                        if ($e_level == 'region') {
                                            echo 'selected';
                                        }
                                    } ?>>Regional
                                        Church</option>
                                    <option value="zone" <?php if (!empty($e_level)) {
                                        if ($e_level == 'zone') {
                                            echo 'selected';
                                        }
                                    } ?>>Zonal Church
                                    </option>
                                    <option value="group" <?php if (!empty($e_level)) {
                                        if ($e_level == 'group') {
                                            echo 'selected';
                                        }
                                    } ?>>Group
                                        Church</option>
                                    <option value="church" <?php if (!empty($e_level)) {
                                        if ($e_level == 'church') {
                                            echo 'selected';
                                        }
                                    } ?>>Church
                                        Assembly</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 mb-3" id="church_div">
                        <div class="form-group">
                            <label class="form-label">Church</label>
                            <select class="js-select2" data-search="on" name="church_id" id="church_id">
                                <option value="">Select</option>

                            </select>
                        </div>
                    </div>

                <?php }?>
                <p class="mb-3">Share this link with first timers or display the QR code below.</p>

                <div class="mb-3">
                <input type="text" id="firstTimerLink" class="form-control text-center" readonly
                    value="">
                </div>

                <div id="qrContainer" class="my-3 d-flex justify-content-center">
                    <!-- QR will be generated here -->
                    <div id="firstTimerQR"></div>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <button class="btn btn-outline-primary mx-1" onclick="copyFirstTimerLink()">Copy Link</button>
                    <button class="btn btn-outline-success mx-1" onclick="downloadQRCode()">Download QR Code</button>
                </div>

            </div>
                                    <!-- Include QRCode.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

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
    <?php if($param2 == 'bulk_qr') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
        
            <!-- Send Type Selector -->
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="send_type"><?=translate_phrase('Send Type');?></label>
                    <select class="form-control js-select2" id="send_type" name="send_type">
                        <option value="manual"><?=translate_phrase('All Members');?></option>
                        <option value="date_range"><?=translate_phrase('Send to Members Registered Within Date Range');?></option>
                    </select>
                </div>
            </div>

            <!-- Date Range Selection -->
            <div class="col-sm-6 mb-2 send_type_field d-none" id="date_range_from">
                <div class="form-group">
                    <label for="date_from"><?=translate_phrase('Date From');?></label>
                    <input type="date" class="form-control" id="date_from" name="date_from" />
                </div>
            </div>

            <div class="col-sm-6 mb-2 send_type_field d-none" id="date_range_to">
                <div class="form-group">
                    <label for="date_to"><?=translate_phrase('Date To');?></label>
                    <input type="date" class="form-control" id="date_to" name="date_to" />
                </div>
            </div>

            <!-- Submit Button -->
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
    
    function updateFirstTimerDetails(churchId) {
        const $linkInput = $('#firstTimerLink');
        const $qrContainer = $('#firstTimerQR');
      
        if (!churchId) {
            $linkInput.val('');
            $qrContainer.empty();
            return;
        }

        // Send AJAX to backend to fetch or create URL
        $.ajax({
            url: "<?= site_url('accounts/membership/manage/link/generate') ?>", // Backend route
            type: "POST",
            dataType: "json",
            data: { church_id: churchId },
            success: function (response) {
                if (response.success && response.url) {
                    $linkInput.val(response.url);
                    $qrContainer.empty();

                    new QRCode($qrContainer[0], {
                        text: response.url,
                        width: 200,
                        height: 200,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                } else {
                    $linkInput.val('Error generating link');
                    $qrContainer.empty();
                }
            },
            error: function () {
                $linkInput.val('Server error');
                $qrContainer.empty();
            }
        });
    }

    // On document ready
    $(document).ready(function () {
        $('#church_id').on('change', function () {
            const selectedChurchId = $(this).val();
            updateFirstTimerDetails(selectedChurchId);
        });

        // Initial load (optional)
        const initialChurchId = $('#church_id').val();
        console.log(initialChurchId);
        if (initialChurchId) {
            updateFirstTimerDetails(initialChurchId);
        }
    });

    // Copy to clipboard
    function copyFirstTimerLink() {
        const $copyInput = $('#firstTimerLink');
        $copyInput.select();
        document.execCommand('copy');
        alert("Link copied to clipboard!");
    }

    
    function downloadQRCode() {
        const qrCanvas = $('#firstTimerQR canvas')[0];

        if (!qrCanvas) {
            alert("QR Code not available yet!");
            return;
        }

        const image = qrCanvas.toDataURL("image/png");
        const link = document.createElement('a');
        link.href = image;
        link.download = 'first_timer_qr.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    $(document).ready(function() {
        $('#send_type').on('change', function() {
            var selected = $(this).val();
            if (selected === 'manual') {
                $('#manual_select').removeClass('d-none');
                $('#date_range_from, #date_range_to').addClass('d-none');
            } else if (selected === 'date_range') {
                $('#manual_select').addClass('d-none');
                $('#date_range_from, #date_range_to').removeClass('d-none');
            }
        }).trigger('change'); // Trigger change on page load
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