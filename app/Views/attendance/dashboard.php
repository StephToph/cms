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
                            <h3 class="nk-block-title page-title"><?=ucwords($church); ?></h3>
                            <div class="nk-block-des text-soft">
                                <p><?=date('d F Y h:i:sA'); ?></p>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="nk-block">
                    <div class="row ">
                        <div class="col-sm-3 mb-3">
                            <select class="js-select2" data-search="on" name="service"  id="service" >
                            <?php 
                           
                            if(!empty($service_count)){
                                foreach ($service_count as $service) { 
                                    $start = date('h:iA', strtotime($this->Crud->read_field('id', $service->schedule_id, 'service_schedule', 'start_time')));
                                    $end = date('h:iA', strtotime($this->Crud->read_field('id', $service->schedule_id, 'service_schedule', 'end_time')));
                                    $type = $this->Crud->read_field('id', $service->type, 'service_type', 'name');
                                    echo '<option value="'.$service->id.'">'.ucwords($type).' {'.$start.' - '.$end.'}</option>';
                                }
                            } 
                        
                            echo '</select></div>';
                        
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
                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="Add membership" class="float-right btn btn-outline-danger btn-block btn-white pop  ml-2" pageTitle="<?=translate_phrase('Add membership');?>" pageName="<?php echo site_url('attendance/dashboard/manage/member'); ?>" pageSize="modal-xl"><em
                                        class="icon ni ni-user"></em><span> Add Member</span></a>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="First Timer Link" class="float-right btn btn-outline-dark btn-block btn-white pop  ml-2" pageTitle="<?=translate_phrase('First Timer Link');?>" pageName="<?php echo site_url('attendance/dashboard/manage/link'); ?>" pageSize="modal-md"><em
                                        class="icon ni ni-qr"></em><span>First Timer QR</span></a>
                            </div>
                        </div>
                    <div class="row g-gs">
                        <div class="col-12" id="analytics" style="display:none;">
                            <div class="row">
                                <div class="col-md-4 mb-3">
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
                                <div class="col-md-4 mb-3">
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
                                <div class="col-md-4 mb-3">
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
                                <div class="col-md-3 mb-3">
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
                                
                                <div class="col-md-3 mb-3">
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
                                
                                <div class="col-md-3 mb-3">
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
                                <div class="col-md-3 mb-3">
                                    <div class="card card-bordered border-primary card-full">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-0">
                                                <div class="card-title">
                                                    <h6 class="title"><?=translate_phrase('First Timer'); ?></h6>
                                                </div>
                                               
                                            </div>
                                            <div class="card-amount"><span class="amount" id="firstTimer"> 0 <span class="currency currency-usd"></span></span></div>
                                            
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
                                            
                                        <?php if($attend_type == 'monitoring' || $attend_type == 'admin'){?>
                                            <div class="col-md-7 my-2">
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
                                            <div class="col-md-5 my-2">
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
                        <?php if($attend_type == 'admin' || $attend_type == 'monitoring'){?>
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
    function get_member() {
        $('#member_response').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        var member_id = $('#member_id').val();
        var service = $('#service').val();
        var church_id = $('#church_id').val();

        $.ajax({
            url: site_url + 'attendance/records/get_member',
            type: 'post',
            data: { member_id: member_id, service:service, church_id:church_id },
            success: function (data) {
                var dt = JSON.parse(data);
                $('#member_response').html(dt.response);
                
            }
        });
    }
    

    function checkAnalyticsAccess() {
        let enteredPassword = prompt("Enter password to view analytics:");

        if (enteredPassword === null || enteredPassword === "") return;

        $.ajax({
            url: site_url + 'attendance/records/verify_password',
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
                url: site_url + 'attendance/records/get_attendance_by_service', // adjust this to your route
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


    // Handle Mark Present Switch
    $(document).on('change', '.mark-present-switch', function () {
        var $this = $(this);
        var member_id = $this.data('member-id');
        var isPresent = $this.is(':checked');
        var service_id = $('#service').val();
        var church_id = $('#church_id').val();

        $('#resp_' + member_id).html('<small class="text-info">Updating...</small>');

        $.ajax({
            url: site_url + 'attendance/records/mark_present',
            type: 'POST',
            data: {
                member_id: member_id,
                service_id: service_id,
                church_id: church_id,
                mark: isPresent ? 1 : 0
            },
            success: function (response) {
                let res;
                try {
                    res = typeof response === 'object' ? response : JSON.parse(response);
                } catch (e) {
                    res = { status: 'error', message: response };
                }

                if (res.status === 'success') {
                    $('#resp_' + member_id).html('<small class="text-success">' + res.message + '</small>');

                    // ✅ Handle Reason Box Visibility
                    if (isPresent) {
                        $('#absent_reason_wrapper_' + member_id).slideUp();
                        $('#absent_reason_' + member_id).val('');
                        $('#other_reason_' + member_id).val('').hide();
                    } else {
                        $('#absent_reason_wrapper_' + member_id).slideDown();
                    }

                    loadMetrics(service_id);
                } else {
                    $('#resp_' + member_id).html('<small class="text-danger">' + res.message + '</small>');
                    $this.prop('checked', !isPresent); // rollback if error
                }
            },
            error: function () {
                $('#resp_' + member_id).html('<small class="text-danger">Failed to update status.</small>');
                $this.prop('checked', !isPresent);
            }
        });
    });

    $(document).on('change', '.reason-select', function () {
    var $this = $(this);
    var member_id = $this.data('member-id');
    var selected = $this.val().toLowerCase(); // 🔥 force lowercase for safe matching
    var $otherInput = $('#other_reason_' + member_id);

    if (selected.includes('other')) { // 🔥 lowercase match always works
        $otherInput.show().focus();
    } else {
        $otherInput.hide().val('');
    }
});


$(document).on('change blur', '.reason-select, .other-reason-input', function () {
        var $this = $(this);
        var $row = $this.closest('tr');
        var member_id = $this.data('member-id');

        setTimeout(function() { // wait to capture final typed value
            var reason = $('#absent_reason_' + member_id).val() || '';
            var other_reason = $('#other_reason_' + member_id).val() || '';
            
            var final_reason = '';

            if (reason.includes('Other')) {
                // 🔥 Force to use custom input
                if (other_reason.trim() !== '') {
                    final_reason = other_reason.trim();
                } else {
                    final_reason = ''; // ❗ Blank if user didn't fill
                }
            } else {
                final_reason = reason.trim();
            }

            var service_id = $('#service').val();
            var church_id = $('#church_id').val();
            var $resp = $('#resp_' + member_id);

            if (final_reason === '') {
                $resp.html('<small class="text-warning">Please provide a valid reason for absence.</small>');
                return; // ❗ Stop here, don't proceed if blank
            }

            $resp.html('<small class="text-info">Saving reason...</small>');

            $.ajax({
                url: site_url + 'attendance/records/mark_absent',
                type: 'POST',
                data: {
                    member_id: member_id,
                    service_id: service_id,
                    church_id: church_id,
                    reason: final_reason
                },
                success: function (response) {
                    let res;
                    try {
                        res = typeof response === 'object' ? response : JSON.parse(response);
                    } catch (e) {
                        res = { status: 'error', message: response };
                    }

                    if (res.status === 'success') {
                        $resp.html('<small class="text-success">' + res.message + '</small>');
                        loadMetrics(service_id);
                    } else {
                        $resp.html('<small class="text-danger">' + res.message + '</small>');
                    }
                },
                error: function () {
                    $resp.html('<small class="text-danger">Error saving reason.</small>');
                }
            });
        }, 100);
    });


    $(document).on('change', '.mark-convert-switch', function () {
        var $this = $(this);
        var member_id = $(this).data('member-id');
        var type = $(this).data('type');
        var isPresent = $(this).is(':checked');
        var service_id = $('#service').val();
        var church_id = $('#church_id').val();

        var mark = 0;
        if(isPresent){
            var mark = 1;
        }

       
            $('#con_resp_' + member_id).html('<small class="text-info">Updating...</small>');
            // $this.prop('disabled', true);
            $.ajax({
                url: site_url + 'attendance/records/mark_convert',
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
    
    function loadMetrics(serviceNumber) {
        $.ajax({
            url: site_url + 'attendance/records/get_attendance_metrics',
            type: 'POST',
            data: { service: serviceNumber },
            success: function (data) {
                $('#membership').text(data.membership);
                $('#present').text(data.present);
                $('#absent').text(data.absent);
                $('#male').text(data.male);
                $('#female').text(data.female);
                $('#firstTimer').text(data.firstTimer);
                $('#unmarked').text(data.unmarked);
                $('#metric_response').html(data.metric_response);
                $('#general_response').html(data.general_response);
            },
            error: function () {
                $('#membership, #present, #absent,#male, #female, #firstTimer, #unmarked').text('0');
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
<?php if($attend_type == 'monitoring' || $attend_type == 'admin'){?>
    <script>
       $('#confirmMarkBtn').on('click', function () {
            const member_id = $('#mark_member_id').val();
            const service_id = $('#mark_service_id').val();
            const church_id = $('#mark_church_id').val();

            $('#qr_member_details').html('<p class="text-info">⏳ Marking attendance...</p>');

            $.post("<?= site_url('attendance/records/mark_attendance') ?>", {
                member_id: member_id,
                service_id: service_id,
                church_id: church_id
            }, function (res) {
                $('#qr_member_details').html(`<p class="text-${res.status === 'success' ? 'success' : 'warning'} fw-bold">${res.message}</p>`);
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
                const service_id = $('#service').val();
                const church_id = $('#church_id').val();

                html5QrcodeScanner.clear();
                $('#scan_result').html(`<p class="text-info">🔍 Verifying member ID: ${memberId}...</p>`);

                $.post("<?= site_url('attendance/records/verify_member') ?>", {
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
                       
                        const modal = new bootstrap.Modal(document.getElementById('qrConfirmModal'));
                        modal.show();


                    } else {
                         $('#scan_result').html(`<p class="text-danger">❌ ${res.message}</p><button class="btn btn-primary mt-2" onclick="restartScanner()">Try Again</button>`);
                    }
                }).fail(function () {
                    $('#scan_result').html(`<p class="text-danger">❌ Failed to verify member.</p><button class="btn btn-primary mt-2" onclick="restartScanner()">Retry</button>`);
                });
            }
        }

        function confirmAttendance(memberId, service_id, church_id) {
            $('#scan_result').html('⏳ Marking attendance...');

            $.post("<?= site_url('attendance/records/mark_attendance') ?>", {
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

        $(document).ready(function(){
            // Initialize Select2 for the original select dropdown
            $('.js-select2').select2();
        });
    </script>
<?php } ?>
<?= $this->endSection(); ?>