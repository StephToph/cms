<?php
use App\Models\Crud;
$this->Crud = new Crud();
?>
<?= $this->extend('attendance/backend'); ?>
<?= $this->section('title'); ?>
<?= $title; ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <?php
                                $type_id = $this->Crud->read_field2('date', date('Y-m-d'), 'church_id', $church_id, 'service_report', 'type');
                                $type = $this->Crud->read_field('id', $type_id, 'service_type', 'name');
                            ?>
                            <h3 class="nk-block-title page-title"><?=ucwords($type); ?></h3>
                            <div class="nk-block-des text-soft">
                                <p><?=date('Y-m-d'); ?></p>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="nk-block">
                    <div class="row ">
                        <?php 
                            $service_count = $this->Crud->check3('status', 0, 'date', date('Y-m-d'), 'church_id', $church_id, 'service_report');
                            if($service_count > 1){
                                if($attend_type == 'cell'){
                                    echo  '<div class="col-sm-3 mb-3">
                                        <select class="js-select2" data-search="on" name="service"  id="service_select" >';
                                            for ($i=0; $i < $service_count; $i++) { 
                                                echo '<option value="'.($i+1).'">Service '.($i+1).'</option>';
                                            }

                                        echo '</select>
                                    </div>';
                                } else {
                                    echo  '<div class="col-sm-3 mb-3">
                                        <select class="js-select2" data-search="on" name="service" id="service">';
                                            for ($i=0; $i < $service_count; $i++) { 
                                                echo '<option value="'.($i+1).'">Service '.($i+1).'</option>';
                                            }

                                        echo '</select>
                                    </div>';
                                }
                            
                        } else{
                            echo '<input type="hidden" id="service" value="1">
                            <input type="hidden" id="service_select" value="1">';
                        }
                        
                        if($attend_type == 'admin'){?>
                            <div class="col-sm-2 mb-3">
                                <a href="javascript:;" onclick="checkAnalyticsAccess();" class="btn btn-white btn-dim btn-block mx-2 btn-outline-primary"><em
                                        class="icon ni ni-reports"></em><span>Analytics</span></a>
                            </div>
                        <?php } ?>
                        <div class="col-sm-2 mb-3">
                            <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="Add First Timer"  class="btn btn-white btn-dim btn-block  btn-outline-info pop mx-2" pageTitle="<?=translate_phrase('Add First Timer');?>" pageName="<?php echo site_url('attendance/dashboard/manage'); ?>" pageSize="modal-xl">
                                <em class="icon ni ni-plus-c"></em><span> First Timer</span>
                            </a>
                        </div>
                        <div class="col-sm-2 mb-3">
                            <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="New membership" class="float-right btn btn-outline-danger btn-block btn-white pop  ml-2" pageTitle="<?=translate_phrase('New membership');?>" pageName="<?php echo site_url('attendance/dashboard/manage/member'); ?>" pageSize="modal-xl"><em
                                    class="icon ni ni-user"></em><span> New Member</span></a>
                        </div>
                        <div class="col-sm-2 mb-3">
                            <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="First Timer Link" class="float-right btn btn-outline-dark btn-block btn-white pop  ml-2" pageTitle="<?=translate_phrase('First Timer Link');?>" pageName="<?php echo site_url('attendance/dashboard/manage/link'); ?>" pageSize="modal-md"><em
                                    class="icon ni ni-qr"></em><span>First Timer QR</span></a>
                        </div>
                    </div>
                    <div class="row g-gs">
                        <div class="col-12" id="analytics" style="display:none;">
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <div class="card card-bordered text-white bg-primary card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Total Members'); ?></h6>
                                                </div>
                                            </div>
                                            <div class="card-amount"><span class="amount text-white" id="membership"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="card card-bordered  border-primary  card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Umarked Attendance'); ?></h6>
                                                </div>
                                               
                                            </div>
                                            <div class="card-amount"><span class="amount" id="unmarked"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="card card-bordered border-success card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Total Present'); ?></h6>
                                                </div>
                                               
                                            </div>
                                            <div class="card-amount"><span class="amount " id="present"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="card card-bordered border-danger card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Total Absent'); ?></h6>
                                                </div>
                                               
                                            </div>
                                            <div class="card-amount"><span class="amount" id="absent"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 mb-3">
                                    <div class="card card-bordered border-primary card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Male Present'); ?></h6>
                                                </div>
                                               
                                            </div>
                                            <div class="card-amount"><span class="amount" id="male"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 mb-3">
                                    <div class="card card-bordered border-primary card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('Female Present'); ?></h6>
                                                </div>
                                               
                                            </div>
                                            <div class="card-amount"><span class="amount" id="female"> 0 <span class="currency currency-usd"></span></span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3" id="metric_response">
                                    <!-- dynamic content appears here -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card card-bordered card-full">
                                <div class="card-inner-group">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title my-1"><?=translate_phrase('Mark Attendance');?></h6>
                                            </div>
                                            <div class="card-tools"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <input type="hidden" id="church_id" value="<?=$church_id; ?>">
                                        <input type="hidden" id="cell_id" value="<?=$cell_id; ?>">
                                            
                                        <?php if($attend_type == 'usher' || $attend_type == 'admin'){?>
                                            <div class="col-md-8 my-2">
                                                <div class="form-control-wrap p-2"> 
                                                    <label class="name">Enter Name/Email</label>   
                                                    <div class="input-group p-2">        
                                                        <input type="text" id="member_id" oninput="get_member();"class="form-control form-control-lg" placeholder="Enter Your Name or Email">        
                                                        <div class="input-group-append">            
                                                            <button class="btn btn-outline-primary btn-dim" onclick="get_member();">Search</button>        
                                                        </div>    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="container py-4">
                                                    <h4>Scan QR to Mark Attendance</h4>
                                                    <div id="reader" style="width: 350px;"></div>
                                                    <div id="scan_result" class="mt-3"></div>
                                                </div>

                                                <script src="<?= site_url('assets/js/html5-qrcode.min.js'); ?>"></script>



                                            </div>
                                            
                                        <?php } ?>

                                        <?php if($attend_type == 'cell' ){?>
                                            <div id="attendance_response">
                                                <!-- dynamic content appears here -->
                                            </div>
                                            
                                        <?php } ?>
                                        <div class="col-12 my-2 text-center" id="member_response"></div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <?php if($attend_type == 'admin' || $attend_type == 'usher'){?>
                            <div class="col-md-12 mb-3" id="general_response">
                                <!-- dynamic content appears here -->
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Member Confirmation Modal -->
<div class="modal fade" id="qrConfirmModal" tabindex="-1" aria-labelledby="qrConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="qrConfirmModalLabel">Confirm Member Attendance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="restartScanner()"></button>
      </div>
      <div class="modal-body text-center" id="qr_member_details">
        <!-- Content injected via JS -->
      </div>
      <div class="modal-footer justify-content-center">
        <button class="btn btn-success" id="confirmMarkBtn">✅ Confirm & Mark</button>
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="restartScanner()">Cancel</button>
      </div>
    </div>
  </div>
</div>
<audio id="beep" src="<?= base_url('assets/audio/beep.mp3'); ?>"></audio>
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    function speakText(text) {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.pitch = 1;
            utterance.rate = 1;
            speechSynthesis.speak(utterance);
        } else {
            alert('Sorry, your browser does not support text-to-speech.');
        }
    }
    speakText('Welcome!!');
    
    function get_member() {
        $('#member_response').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        var member_id = $('#member_id').val();
        var service = $('#service').val();
        var church_id = $('#church_id').val();

        $.ajax({
            url: site_url + 'attendance/dashboard/get_member',
            type: 'post',
            data: { member_id: member_id, service:service, church_id:church_id },
            success: function (data) {
                var dt = JSON.parse(data);
                $('#member_response').html(dt.response);
                
            }
        });
    }
    
    $(document).ready(function () {
        // Trigger change on page load
        $('#service_select').trigger('change');
        
    });

    function checkAnalyticsAccess() {
        let enteredPassword = prompt("Enter password to view analytics:");

        if (enteredPassword === null || enteredPassword === "") return;

        $.ajax({
            url: site_url + 'attendance/dashboard/verify_password',
            type: 'POST',
            data: { password: enteredPassword },
            success: function(response) {
                if (response.status === 'success') {
                    $('#analytics').toggle(500);
                } else {
                    alert("Incorrect password. Access denied.");
                }
            },
            error: function() {
                alert("An error occurred. Try again.");
            }
        });
    }


    $('#service_select').on('change', function () {
        let service = $(this).val();
        var cell_id = $('#cell_id').val();
        
        if (service !== "") {
            $.ajax({
                url: site_url + 'attendance/dashboard/get_attendance_by_service', // adjust this to your route
                type: 'POST',
                data: {
                    service: service,
                    cell_id : cell_id
                },
                beforeSend: function () {
                    $('#attendance_response').html('<div class="text-center">Loading attendance...</div>');
                },
                success: function (response) {
                    $('#attendance_response').html(response); // insert rendered table from PHP
                },
                error: function () {
                    $('#attendance_response').html('<div class="text-danger">Something went wrong. Please try again.</div>');
                }
            });
        } else {
            $('#attendance_response').html('');
        }
    });


    $(document).on('change', '.mark-present-switch', function () {
        var $this = $(this);
        var member_id = $(this).data('member-id');
        var isPresent = $(this).is(':checked');
        var service_id = $('#service').val() || $('#service_select').val();
        var church_id = $('#church_id').val();

        var mark = 0;
        if(isPresent){
            var mark = 1;
        }

        // Disable Absent if Present is checked
        $('#absentSwitch_' + member_id).prop('disabled', isPresent);
        $('#absentSwitchz_' + member_id).prop('disabled', isPresent);
        $('#absentSwitchm_' + member_id).prop('disabled', isPresent);
        
            $('#resp_' + member_id).html('<small class="text-info">Updating...</small>');
            // $this.prop('disabled', true);
            $.ajax({
                url: site_url + 'attendance/dashboard/mark_present',
                type: 'POST',
                data: {
                    member_id: member_id,
                    service_id: service_id,
                    church_id: church_id,
                    mark: mark
                },
                success: function (response) {
                    // Try to parse if it's JSON
                    let res;
                    try {
                        res = typeof response === 'object' ? response : JSON.parse(response);
                    } catch (e) {
                        res = { status: 'error', message: response };
                    }

                    if (res.status === 'success') {
                        $('#resp_' + member_id).html('<small class="text-success">Marked</small>');
                        // $this.prop('disabled', true); // ✅ disable the switch
                        let defaultService = $('#service').val();
                        loadMetrics(defaultService); 
                    } else {
                        $('#resp_' + member_id).html('<small class="text-danger">' + res.message + '</small>');
                        $this.prop('checked', false); // rollback toggle if error
                    }
                },
                error: function () {
                    $('#resp_' + member_id).html('<small class="text-danger">Failed to mark member as present.</small>');
                    $this.prop('disabled', false);
                }
            });
        
    });

    
    $(document).on('change', '.mark-convert-switch', function () {
        var $this = $(this);
        var member_id = $(this).data('member-id');
        var type = $(this).data('type');
        var isPresent = $(this).is(':checked');
        var service_id = $('#service').val() || $('#service_select').val();
        var church_id = $('#church_id').val();

        var mark = 0;
        if(isPresent){
            var mark = 1;
        }

       
            $('#con_resp_' + member_id).html('<small class="text-info">Updating...</small>');
            // $this.prop('disabled', true);
            $.ajax({
                url: site_url + 'attendance/dashboard/mark_convert',
                type: 'POST',
                data: {
                    member_id: member_id,
                    service_id: service_id,
                    church_id: church_id,
                    mark: mark,
                    type: type
                },
                success: function (response) {
                    // Try to parse if it's JSON
                    let res;
                    try {
                        res = typeof response === 'object' ? response : JSON.parse(response);
                    } catch (e) {
                        res = { status: 'error', message: response };
                    }

                    if (res.status === 'success') {
                        $('#con_resp_' + member_id).html('<small class="text-success">Marked</small>');
                        // $this.prop('disabled', true); // ✅ disable the switch
                        let defaultService = $('#service').val();
                        loadMetrics(defaultService); 
                    } else {
                        $('#con_resp_' + member_id).html('<small class="text-danger">' + res.message + '</small>');
                        $this.prop('checked', false); // rollback toggle if error
                    }
                },
                error: function () {
                    $('#con_resp_' + member_id).html('<small class="text-danger">Failed to mark member as New Convert.</small>');
                    $this.prop('disabled', false);
                }
            });
        
    });

    $(document).on('change', '.mark-absent-switch', function () {
        let $this = $(this);
        let member_id = $this.data('member-id');
        let isAbsent = $this.is(':checked');
        var service_id = $('#service').val() || $('#service_select').val();
        var church_id = $('#church_id').val();

        // Disable Present switch if Absent is checked
        $('#presentSwitch_' + member_id).prop('disabled', isAbsent);
        $('#presentSwitchz_' + member_id).prop('disabled', isAbsent);
        $('#presentSwitchm_' + member_id).prop('disabled', isAbsent);
        var mark = 0;
        if(isAbsent){
            var mark = 1;
        }

        console.log(mark);
        if(mark == 0){
            $.ajax({
                url: site_url + 'attendance/dashboard/mark_present',
                type: 'POST',
                data: {
                    member_id: member_id,
                    service_id: service_id,
                    church_id: church_id,
                    mark: mark
                },
                success: function (response) {
                    // Try to parse if it's JSON
                    let res;
                    try {
                        res = typeof response === 'object' ? response : JSON.parse(response);
                    } catch (e) {
                        res = { status: 'error', message: response };
                    }

                    if (res.status === 'success') {
                        $('#resp_' + member_id).html('<small class="text-success">Marked</small>');
                        // $this.prop('disabled', true); // ✅ disable the switch
                        let defaultService = $('#service').val();
                        loadMetrics(defaultService); 
                    } else {
                        $('#resp_' + member_id).html('<small class="text-danger">' + res.message + '</small>');
                        $this.prop('checked', false); // rollback toggle if error
                    }
                },
                error: function () {
                    $('#resp_' + member_id).html('<small class="text-danger">Failed to mark member as present.</small>');
                    $this.prop('disabled', false);
                }
            });
        }
        

        // Show or hide reason input
        if (isAbsent) {
            $('.absent_reason_wrapper_' + member_id).slideDown();
        } else {
            $('.absent_reason_wrapper_' + member_id).slideUp();
            $('#absent_reason_' + member_id).val('');
        }
    });

    $(document).on('change', '.reason-select', function () {
        let $this = $(this);
        let member_id = $this.data('member-id');
        let selected = $this.val();

        // Look for the wrapper closest to this reason dropdown
        let $row = $this.closest('tr');
        let $otherInput = $row.find('.other-reason-input');

        if (selected === 'Other' || selected.includes('Other')) {
            $otherInput.show().focus();
        } else {
            $otherInput.hide().val('');
        }
    });

    // Submit on reason select or when "other" input loses focus
    $(document).on('change blur', '.reason-select, .other-reason-input', function () {
        let $this = $(this);
        let $row = $this.closest('tr');
        let member_id = $this.data('member-id');

        let reason = $row.find('.reason-select').val();
        let other_reason = $row.find('.other-reason-input').val();
        let final_reason = reason === 'Other' || reason.includes('Other') ? other_reason.trim() : reason;

        if (final_reason !== '') {
            let service_id = $('#service').val() || $('#service_select').val();
            let church_id = $('#church_id').val();
            let $resp = $row.find('#resp_' + member_id);

            $resp.html('<small class="text-info">Saving reason...</small>');

            $.ajax({
                url: site_url + 'attendance/dashboard/mark_absent',
                type: 'POST',
                data: {
                    member_id: member_id,
                    reason: final_reason,
                    service_id: service_id,
                    church_id: church_id
                },
                success: function (response) {
                    let res = typeof response === 'object' ? response : JSON.parse(response);
                    if (res.status === 'success') {
                        $resp.html('<small class="text-success">' + res.message + '</small>');
                        let defaultService = $('#service').val();
                        loadMetrics(defaultService);
                        $row.find('.mark-absent-switch').prop('disabled', true);
                        $row.find('.reason-select').prop('disabled', true);
                        $row.find('.other-reason-input').prop('readonly', true);
                    } else {
                        $resp.html('<small class="text-danger">' + res.message + '</small>');
                    }
                },
                error: function () {
                    $resp.html('<small class="text-danger">Error saving reason.</small>');
                }
            });
        }
    });
        
    function loadMetrics(serviceNumber) {
        $.ajax({
            url: site_url + 'attendance/dashboard/get_attendance_metrics',
            type: 'POST',
            data: { service: serviceNumber },
            success: function (data) {
                $('#membership').text(data.membership);
                $('#present').text(data.present);
                $('#absent').text(data.absent);
                $('#male').text(data.male);
                $('#female').text(data.female);
                $('#unmarked').text(data.unmarked);
                $('#metric_response').html(data.metric_response);
                $('#general_response').html(data.general_response);
            },
            error: function () {
                $('#membership, #present, #absent,#male, #female, #unmarked').text('0');
            }
        });
    }

    $(document).ready(function () {
        let defaultService = $('#service').val();
        loadMetrics(defaultService); // Load metrics on page load

        $('#service').on('change', function () {
            let selectedService = $(this).val();
            loadMetrics(selectedService); // Reload metrics when changed
        });
    });

</script>   
<?php if($attend_type == 'usher' || $attend_type == 'admin'){?>
    <script>
        function speakText(text) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'en-US';
                utterance.pitch = 1;
                utterance.rate = 1;
                speechSynthesis.speak(utterance);
            } else {
                alert('Sorry, your browser does not support text-to-speech.');
            }
        }
        speakText('Welcome!!');
        $('#confirmMarkBtn').on('click', function () {
            const member_id = $('#mark_member_id').val();
            const service_id = $('#mark_service_id').val();
            const church_id = $('#mark_church_id').val();

            $('#qr_member_details').html('<p class="text-info">⏳ Marking attendance...</p>');

            $.post("<?= site_url('attendance/dashboard/mark_attendance') ?>", {
                member_id: member_id,
                service_id: service_id,
                church_id: church_id
            }, function (res) {
                $('#qr_member_details').html(`<p class="text-${res.status === 'success' ? 'success' : 'warning'} fw-bold">${res.message}</p>`);
                speakText(res.message.replace(/<[^>]*>?/gm, '').trim());
                setTimeout(() => {
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('qrConfirmModal'));
                    modal.hide();
                    restartScanner();
                }, 2000);
            }).fail(function () {
                $('#qr_member_details').html('<p class="text-danger">❌ Error marking attendance</p>');
            });
        });

        function onScanSuccess(decodedText) {
            if (decodedText.startsWith("USER-")) {
                const memberId = decodedText.replace("USER-", "");
                const service_id = $('#service').val() || $('#service_select').val();
                const church_id = $('#church_id').val();

                html5QrcodeScanner.clear();
                $('#scan_result').html(`<p class="text-info">🔍 Verifying member ID: ${memberId}...</p>`);

                $.post("<?= site_url('attendance/dashboard/verify_member') ?>", {
                    member_id: memberId,
                    service_id: service_id,
                    church_id: church_id
                }, function (res) {
                    if (res.status === 'ok') {
                        const m = res.member;

                        $('#qr_member_details').html(`
                            <img src="${m.img}" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;" />
                            <h5 class="mb-1">${m.name}</h5>
                            <p class="mb-1 text-muted">${m.email}</p>
                            <p class="mb-1 text-muted">${m.phone}</p>
                            <input type="hidden" id="mark_member_id" value="${m.id}">
                            <input type="hidden" id="mark_service_id" value="${service_id}">
                            <input type="hidden" id="mark_church_id" value="${church_id}">
                        `);
                        speakText("Member Confirmed, Please Click Button to Mark Attendance");
                
                        const modal = new bootstrap.Modal(document.getElementById('qrConfirmModal'));
                        modal.show();


                    } else {
                        speakText(res.message.replace(/<[^>]*>?/gm, '').trim());
                        $('#scan_result').html(`<p class="text-danger">❌ ${res.message}</p><button class="btn btn-primary mt-2" onclick="restartScanner()">Try Again</button>`);
                    }
                }).fail(function () {
                    $('#scan_result').html(`<p class="text-danger">❌ Failed to verify member.</p><button class="btn btn-primary mt-2" onclick="restartScanner()">Retry</button>`);
                });
            }
        }

        function confirmAttendance(memberId, service_id, church_id) {
            $('#scan_result').html('⏳ Marking attendance...');

            $.post("<?= site_url('attendance/dashboard/mark_attendance') ?>", {
                member_id: memberId,
                service_id: service_id,
                church_id: church_id
            }, function (res) {
                const statusClass = res.status === 'success' ? 'text-success' : 'text-warning';
                $('#scan_result').html(`<p class="${statusClass}">✅ ${res.message}</p><button class="btn btn-primary mt-2" onclick="restartScanner()">Scan Next</button>`);
            }).fail(function () {
                $('#scan_result').html(`<p class="text-danger">❌ Error while marking attendance.</p><button class="btn btn-primary mt-2" onclick="restartScanner()">Retry</button>`);
            });
        }

        function restartScanner() {
            $('#scan_result').html('');
            html5QrcodeScanner.render(onScanSuccess);
        }


        const html5QrcodeScanner = new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: 250
        });
        html5QrcodeScanner.render(onScanSuccess);

        
    </script>
<?php } ?>
<?= $this->endSection(); ?>