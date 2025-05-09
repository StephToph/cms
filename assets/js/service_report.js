
    $(function() {
        load('', '');
        $('.js-select2').select2();
    });

    
    
    function load_level(eChurchId){
        var ministry_id = $('#ministry_id').val();
        var level = $('#level').val();
        
        if(ministry_id !== ' ' && ministry_id !== 0 && level !== ' '){
            $.ajax({
                url: site_url + 'service/report/load_churches',
                data: {level:level,ministry_id:ministry_id},
                type: 'post',
                success: function (data) {
                    var dt = JSON.parse(data);
                    var cellSelect = $('#church_id');
                    cellSelect.empty(); 
                    cellSelect.append('<option value="">Select Church</option>');
                    
                    // Add options for each cell
                    dt.churches.forEach(function(cell) {
                        var selected = '';
                        if (cell.id === eChurchId) {
                            selected = 'selected';
                        }
                        cellSelect.append('<option value="' + cell.id + '" ' + selected + '>' + cell.name + '</option>');
                    });
                        
                }
            });
            $('#church_div').show(500);
                   
        }  else {
            $('#church_div').hide(500);
        }
    }

    function load_ministry(eMinistryId){
        $.ajax({
            type: 'POST',
            url: site_url + 'service/report/records/get_ministry',
            data: {ministry_id: eMinistryId},
            dataType: 'json',
            success: function(data) {
                $('#ministry_id').empty();
                if (data.length === 0) {
                    $('#ministry_id').append('<option value="">No Ministry found</option>'); // display a message if no regions are found
                } else {
                    $('#ministry_id').append('<option value=" ">Select Ministry</option>'); 
                            
                    $.each(data, function(index, ministry) {
                        var selected = '';
                        if (ministry.id === eMinistryId) {
                            selected = 'selected';
                        }
                        $('#ministry_id').append('<option value="' + ministry.id + '" ' + selected + '>' + ministry.name + '</option>');
                    });
                }
            }
        });
    }

    

    function load_church_level(eLevel, eChurchId){
        $.ajax({
            type: 'POST',
            url: site_url + 'service/report/records/get_church_level',
            data: {level: eLevel},
            dataType: 'json',
            success: function(data) {
                $('#level').empty();
                if (data.length === 0) {
                    $('#level').append('<option value="">No Level found</option>'); // display a message if no regions are found
                } else {
                     $('#level').append('<option value=" ">Select Level</option>'); 
                            
                    $.each(data, function(index, level) {
                        var selected = '';
                        if (level.id === eLevel) {
                            selected = 'selected';
                            
                        }
                        $('#level').append('<option value="' + level.id + '" ' + selected + '>' + level.name + '</option>');
                    });
                    if (eLevel !== 'all' && typeof eLevel !== 'number') {
                        $('#church_div').show(500);
                        load_level(eChurchId);
                    } else{
                        $('#church_div').hide(500);
                    }
                }
            }
        });
    }
    
    
    function session_church(){
        var church_id = $('#church_id').val();
        
        if(church_id !== ' '){
            $.ajax({
                url: site_url + 'service/report/church_select',
                data: {church_id:church_id},
                type: 'post',
                success: function (data) {
                }
            }); 
        } 
    }

    var initialInfo = {
        class: 'btn-outline-primary',
        onclick: 'add_report();',
        iconClass: 'ni-plus-c'
    };

    var newInfo = {
        class: 'btn-outline-success',
        onclick: 'load();',
        iconClass: 'ni-arrow-long-left'
    };
    var currentInfo = initialInfo;

    $('#add_btn').click(function() {
        $('#show').toggle(500);
        $('#form').toggle(500);
        document.getElementById("bb_ajax_form").reset();
        document.getElementById("type").value = '';
        $('#prev').hide(500);
        // Toggle between initial and new info
        currentInfo = (currentInfo === initialInfo) ? newInfo : initialInfo;
        load_ministry();load_church_level();
        $(this).attr('title', (currentInfo === initialInfo) ? 'Add Report' : 'Back to Reports');
        var markButton = document.getElementById("markButton");
        var convertBtn = document.getElementById("convertBtn");
        var timerBtn = document.getElementById("timerBtn");
        var partnerBtn = document.getElementById("partnerBtn");
        var titheBtn = document.getElementById("titheBtn");
        // Update button class, onclick function, and icon class
        $(this).removeClass().addClass('btn btn-icon ' + currentInfo.class);
        // $(this).attr('onclick', currentInfo.onclick);
        $(this).find('em').removeClass().addClass('icon ni ' + currentInfo.iconClass);
       
    });

    $('#back_btn').click(function() {
        load();
        $('#show').show(500);
        $('#form').hide(500);
        $('#service_name').html('');
                
        $('#attendance_view').hide(500);
        $('#mark_attendance_view').hide(500);
         $('#new_convert_view').hide(500);
        $('#first_timer_view').hide(500);
        $('#attendance_prev').hide(500);
        $('#add_btn').show(500);
         // Reset row count and clear rows
         rowCount = 0;first_timer_count=0;
         $('#guest_part_view').hide(500);
         $('#guest_partner_list').empty();
        $('#tithe_table_resp').empty();
        $('#finance_view').hide(500);
        $('#absent_attendance_list').empty();
        $('#rowsContainer').empty(); $('#containers').empty();
        $('#prev').hide(500);
        $('#media_view').hide(500);

    });

    $(document).on('click', '.toggle-switches-btn', function () {
        var memberId = $(this).data('member-id');
        var wrapper = $('#switches_wrapper_' + memberId);
        wrapper.slideToggle(200);
      });
      
   
    function edit_report(id){
        var selectElement = document.getElementById("cells_id");
        var markButton = document.getElementById("markButton");
        var convertBtn = document.getElementById("convertBtn");
        var timerBtn = document.getElementById("timerBtn");
        var partnerBtn = document.getElementById("partnerBtn");
        var titheBtn = document.getElementById("titheBtn");
        $('#bb_ajax_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#form').show(500);
        $('#attendance_prev').show(500);
        $('#prev').show(500);
        currentInfo = (currentInfo === initialInfo) ? newInfo : initialInfo;

        // Update button class, onclick function, and icon class
        $(this).removeClass().addClass('btn btn-icon ' + currentInfo.class);
        // $(this).attr('onclick', currentInfo.onclick);
        $(this).find('em').removeClass().addClass('icon ni ' + currentInfo.iconClass);

        
        $.ajax({
            url: site_url + 'service/report/edit/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#report_id').val(dt.e_id);
                $('#service_name').html(dt.service_name);
                $('#type').val(dt.e_type).change();
                $("#partnership").val(dt.e_partnership).val();
                $("#tithe").val(dt.e_tithe).val();
                $('#dates').val(dt.e_date);
                $('#attendance').val(dt.e_attendance);
                $('#new_convert').val(dt.e_new_convert);
                $('#first_timer').val(dt.e_first_timer);
                $('#offering').val(dt.e_offering);
                $('#note').val(dt.e_note);
                $('#attendant').val(dt.e_attendant);
                $('#timers').val(dt.e_timers);
                $('#tither').val(dt.e_tithers);
                $('#partners').val(dt.e_partners);
                $('#converts').val(dt.e_converts);
                $('#ministry_id').val(dt.e_ministry_id);
                $('#level').val(dt.e_level);
                $('#church_id').val(dt.e_church_id);
                

                $('#bb_ajax_msg').html('');load_ministry(dt.e_ministry_id);
                load_church_level(dt.e_level,dt.e_church_id);
            }
        });

    }

    function attendance_report(id) {
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#attendance_view').show(500);
        $('#attendance_prev').show(500);
        loadMetrics(id);
        $('#member_id').val('');    
        var markMemberBTN = document.getElementById("markMemberBTN");
        if (markMemberBTN) {
            markMemberBTN.setAttribute("data-service-id", id); // store the service ID
        }
        // $('#memberAttendance').hide(500);
        // $('#member_response').show(500);
        // $('#metric_response').show(500);
        
        var firstTimerBtn = document.getElementById("firstTimerBtnz");
        if (firstTimerBtn) {
            let baseUrl = site_url + "service/report/manage/timers";
            firstTimerBtn.setAttribute("pageName", baseUrl + "/" + id);
        }
    
        // ❌ BUG: missing $ for jQuery selector
        $('#attendance_id').val(id); // ✅ Fix here
    
        $.ajax({
            url: site_url + 'service/recordz/attendance/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#total_attendance').val(dt.total_attendance);
                $('#head_count').val(dt.head_count);
                $("#member_attendance").val(dt.member_attendance);
                $("#guest_attendance").val(dt.guest_attendance);
                $('#male_attendance').val(dt.male_attendance);
                $('#female_attendance').val(dt.female_attendance);
                $('#children_attendance').val(dt.children_attendance);
                $('#attendance_mzg').html('');

                $('#service_name').html(dt.service_name);

                 // ✅ Dynamically update the button's data-service-id
                $('#markMemberBTN').attr('data-service-id', id);

                // ✅ Automatically trigger the button
                // $('#markMemberBTN').trigger('click');
            }
        });
    }
    
    function get_memberz(btn) {
        const $attendance = $('#memberAttendance');
        const $response = $('#member_response');
        const $metric = $('#metric_response');
    
        // Toggle visibility
        if ($attendance.is(':visible')) {
            $attendance.slideUp(300);
            $response.slideDown(300);
            $metric.slideDown(300);
        } else {
            $attendance.slideDown(300);
            $response.slideUp(300);
            $metric.slideUp(300);
    
            // Show loading spinner
            $attendance.html(`
                <div class="col-sm-12 text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
    
            const service = $(btn).data('service-id');
    
            $.ajax({
                url: site_url + 'service/report/get_member',
                type: 'post',
                data: { service: service },
                success: function (data) {
                    const dt = JSON.parse(data);
                    $attendance.html(dt.response);
                }
            });
        }
    }
    
    

    function loadMetrics(serviceNumber) {
        $.ajax({
            url: site_url + 'service/report/get_attendance_metrics',
            type: 'POST',
            data: { service: serviceNumber },
            success: function (data) {
               $('#metric_response').html(data.metric_response);
               $('.js-select2').select2();
            },
            error: function () {
                $('#metric_response').html('');
            }
        });
    }

    $(document).on('change', '.mark-present-switchz', function () {
        var $this = $(this);
        var member_id = $this.data('member-id');
        var isPresent = $this.is(':checked');
        var service_id = $('#attendance_id').val();
        var church_id = $('#church_id').val();
    
        var mark = isPresent ? 1 : 0; // 1 = Present, 0 = Absent
    
        // ✅ Hide/Show Reason box immediately
        if (isPresent) {
            $('#absent_reason_wrapper_' + member_id).slideUp();
        } else {
            $('#absent_reason_wrapper_' + member_id).slideDown();
        }
    
        // Feedback during update
        $('#resp_' + member_id).html('<small class="text-info">Updating...</small>');
    
        $.ajax({
            url: site_url + 'service/report/attendance/mark_present',
            type: 'POST',
            data: {
                member_id: member_id,
                service_id: service_id,
                church_id: church_id,
                mark: mark
            },
            success: function (response) {
                let res;
                try {
                    res = typeof response === 'object' ? response : JSON.parse(response);
                } catch (e) {
                    res = { status: 'error', message: response };
                }
    
                if (res.status === 'success') {
                    $('#resp_' + member_id).html('<small class="text-success">Marked</small>');
                    // Refresh attendance report if needed
                    attendance_report(service_id);
                } else {
                    $('#resp_' + member_id).html('<small class="text-danger">' + res.message + '</small>');
                    $this.prop('checked', !isPresent); // Rollback checkbox if failed
                    if (isPresent) {
                        $('#absent_reason_wrapper_' + member_id).slideDown(); // rollback UI
                    } else {
                        $('#absent_reason_wrapper_' + member_id).slideUp();
                    }
                }
            },
            error: function () {
                $('#resp_' + member_id).html('<small class="text-danger">Failed to update status.</small>');
                $this.prop('checked', !isPresent);
                if (isPresent) {
                    $('#absent_reason_wrapper_' + member_id).slideDown();
                } else {
                    $('#absent_reason_wrapper_' + member_id).slideUp();
                }
            }
        });
    });
    
    
    $(document).on('change', '.mark-convert-switch', function () {
        var $this = $(this);
        var member_id = $(this).data('member-id');
        var type = $(this).data('type');
        var isPresent = $(this).is(':checked');
        var service_id = $('#attendance_id').val();
        var church_id = $('#church_id').val();

        var mark = 0;
        if(isPresent){
            var mark = 1;
        }

       
            $('#con_resp_' + member_id).html('<small class="text-info">Updating...</small>');
            // $this.prop('disabled', true);
            $.ajax({
                url: site_url + 'service/report/attendance/mark_convert',
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
                        
                        attendance_report(service_id); 
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
    // Handle Absent Toggle
    $(document).on('change', '.mark-absent-switch', function () {
        let $this = $(this);
        let member_id = $this.data('member-id');
        let isAbsent = $this.is(':checked');
        let service_id = $('#attendance_id').val();
        let church_id = $('#church_id').val();
        let mark = isAbsent ? 1 : 0;

        // Disable present switches
        $('#presentSwitch_' + member_id).prop('disabled', isAbsent);
        $('#presentSwitchz_' + member_id).prop('disabled', isAbsent);
        $('#presentSwitchm_' + member_id).prop('disabled', isAbsent);

        // Show or hide reason wrapper
        $('#absent_reason_wrapper_' + member_id).toggle(isAbsent);
        if (isAbsent) $('#absent_reason_' + member_id).val('');

        // If unchecked, mark them present
        if (!mark) {
            $.ajax({
                url: site_url + 'service/report/attendance/mark_present',
                type: 'POST',
                data: {
                    member_id: member_id,
                    service_id: service_id,
                    church_id: church_id,
                    mark: mark
                },
                success: function (response) {
                    let res = typeof response === 'object' ? response : JSON.parse(response);
                    if (res.status === 'success') {
                        $('#resp_' + member_id).html('<small class="text-success">Marked</small>');
                       
                        attendance_report(service_id);
                    } else {
                        $('#resp_' + member_id).html('<small class="text-danger">' + res.message + '</small>');
                        $this.prop('checked', false);
                    }
                },
                error: function () {
                    $('#resp_' + member_id).html('<small class="text-danger">Failed to mark member as present.</small>');
                    $this.prop('disabled', false);
                }
            });
        }
    });

    // Show or hide "Other" reason input field
    $(document).on('change', '.reason-selectz', function () {
        let $this = $(this);
        let member_id = $this.data('member-id');
        let selected = $this.val();
        let $otherInput = $('#other_reason_' + member_id);

        if (selected.includes('Other')) {
            $otherInput.show().focus();
        } else {
            $otherInput.hide().val('');
        }
    });

    // Auto-submit Reason
    $(document).on('change blur', '.reason-selectz, .other-reason-inputz', function () {
        let $this = $(this);
        let member_id = $this.data('member-id');
        let reason = $('#absent_reason_' + member_id).val();
        let other_reason = $('#other_reason_' + member_id).val();
        let final_reason = reason.includes('Other') ? other_reason.trim() : reason;

        if (final_reason !== '') {
            let service_id = $('#attendance_id').val();
            let church_id = $('#church_id').val();
            let $resp = $('#resp_' + member_id);

            $resp.html('<small class="text-info">Saving reason...</small>');

            $.ajax({
                url: site_url + 'service/report/attendance/mark_absent',
                type: 'POST',
                data: {
                    member_id: member_id,
                    service_id: service_id,
                    church_id: church_id,
                    reason: final_reason
                },
                success: function (response) {
                    let res = typeof response === 'object' ? response : JSON.parse(response);
                    if (res.status === 'success') {
                        $resp.html('<small class="text-success">' + res.message + '</small>');
                        attendance_report(service_id); // reload report if needed
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


    function get_member() {
        $('#member_response').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        var member_id = $('#member_id').val();
        var service = $('#attendance_id').val();
        var church_id = $('#church_id').val();

        $.ajax({
            url: site_url + 'service/report/attendance/get_member',
            type: 'post',
            data: { member_id: member_id, service:service, church_id:church_id },
            success: function (data) {
                var dt = JSON.parse(data);
                $('#member_response').html(dt.response);
                
            }
        });
    }

    
    function tithe_report(id){
        $('#tithe_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#tithe_view').show(500);
        $('#attendance_prev').show(500);
        
        $.ajax({
            url: site_url + 'service/report/manage/tithe/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#tithe_id').val(dt.tithe_id)
                $('#total_tithe').val(dt.total_tithe);
                $("#member_tithe").val(dt.member_tithe);
                $("#guest_tithe").val(dt.guest_tithe);
                $("#tithe_list").val(dt.tithe_list);
                // populateTithe(id)
               
                  $('#tithe_pagination').show(500);
                $('#tithe_msg').html('');
            }
        });
       
    }
    
    
    
    function offering_report(id){
        $('#offering_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#offering_view').show(500);
        $('#attendance_prev').show(500);
        
        $.ajax({
            url: site_url + 'service/report/manage/offering/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#offering_id').val(dt.offering_id)
                $('#total_offering').val(dt.total_offering);
                $("#member_offering").val(dt.member_offering);
                $("#guest_offering").val(dt.guest_offering);
                $("#offering_list").val(dt.offering_list);
                $('#total_thanksgiving').val(dt.total_thanksgiving);
                $("#member_thanksgiving").val(dt.member_thanksgiving);
                $("#guest_thanksgiving").val(dt.guest_thanksgiving);
                $("#thanksgiving_list").val(dt.thanksgiving_list);
                $('#total_seed').val(dt.total_seed);
                $("#member_seed").val(dt.member_seed);
                $("#guest_seed").val(dt.guest_seed);
                $("#seed_list").val(dt.seed_list);
                // populateOffering(id)
               
                $('#offering_pagination').show(500);
                $('#offering_msg').html('');
            }
        });
       
    }

    function thanksgiving_report(id){
        $('#thanksgiving_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#thanksgiving_view').show(500);
        $('#attendance_prev').show(500);
        
        $.ajax({
            url: site_url + 'service/report/manage/thanksgiving/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#thanksgiving_id').val(dt.thanksgiving_id)
                $('#total_thanksgiving').val(dt.total_thanksgiving);
                $("#member_thanksgiving").val(dt.member_thanksgiving);
                $("#guest_thanksgiving").val(dt.guest_thanksgiving);
                $("#thanksgiving_list").val(dt.thanksgiving_list);
                // populateThanksgiving(id)
               
                  $('#thanksgiving_pagination').show(500);
                $('#thanksgiving_msg').html('');
            }
        });
       
    }

    function seed_report(id){
        $('#seed_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#seed_view').show(500);
        $('#attendance_prev').show(500);
        
        $.ajax({
            url: site_url + 'service/report/manage/seed/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#seed_id').val(dt.seed_id)
                $('#total_seed').val(dt.total_seed);
                $("#member_seed").val(dt.member_seed);
                $("#guest_seed").val(dt.guest_seed);
                $("#seed_list").val(dt.seed_list);
                // populateSeed(id)
               
                  $('#seed_pagination').show(500);
                $('#seed_msg').html('');
            }
        });
       
    }
    
        Dropzone.autoDiscover = false; // Prevent auto-discovery

        function initDropzone() {
            var uploadZoneElement = document.getElementById('upload-zone');
            var uploadUrl = uploadZoneElement.getAttribute('data-url'); // Read URL from data attribute
        
            // Check if URL is correctly read
            if (!uploadUrl) {
                console.error("No URL provided for Dropzone.");
                return; // Stop if URL is missing
            }
        
            var uploadZone = new Dropzone(uploadZoneElement, {
                url: uploadUrl, // Use the URL from data attribute
                maxFilesize: parseFloat(uploadZoneElement.getAttribute('data-max-file-size')), // Max file size in MB
                acceptedFiles: "image/*", // Accept only images
                init: function() {
                    this.on("success", function(file, response) {
                        $('#media_msg').html(response);
                    });
                }
            });
        }
        


    // Call the function to initialize Dropzone
   
    function media_report(id){
        $('#media_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#media_view').show(500);
        $('#attendance_prev').show(500);
        $('#url-input').html('');
        $('#error-message').html('');
        var uploadUrl = site_url + "service/report/manage/media/"+id; // PHP URL
        
        $('#upload-zone').attr('data-url', uploadUrl);
        
        // Destroy existing Dropzone instance if it exists
        if (Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(function(dropzone) {
                dropzone.destroy();
            });
        }

        $('#media_id').val(id);
        initDropzone();

        $.ajax({
            url: site_url + 'service/report/records/service_media/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#gallery_view').html(dt.medias);
                $('#gallery_view').show(500);
                $('#url-input').prepend(dt.url);
                $('#media_msg').html('');
            }
        });
       
    }

    function delete_media(id,service){
        $('#media_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
       
        $.ajax({
            url: site_url + 'service/report/records/delete_media/' + id,
            type: 'get',
            success: function (data) {
                $('#media_msg').html(data);
               media_report(service);

            }
                

        });
    }

    let rowCount = 0;

    function createRow(record = null) {
        // Increment the row count
        rowCount++;

        // Create a new row
        let newRow = $('<div>', { class: 'row border new_converts mb-4' });

        // Add fields to the new row
        newRow.append(`
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="first_name_${rowCount}">*First Name</label>
                    <input class="form-control" type="text" id="first_name_${rowCount}" name="first_name[]" ${record ? 'value="' + (record.fullname.split(' ')[0] || '') + '"' : ''} >
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="surname_${rowCount}">*Surname</label>
                    <input class="form-control" type="text" id="surname_${rowCount}" name="surname[]" ${record ? 'value="' + (record.fullname.split(' ')[1] || '') + '"' : ''} >
                </div>
            </div>
            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="email_${rowCount}">*Email</label>
                    <input class="form-control" type="email" id="email_${rowCount}" name="email[]" ${record ? 'value="' + (record.email || '') + '"' : ''}>
                </div>
            </div>
            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="phone_${rowCount}">*Phone</label>
                    <input class="form-control" type="text" id="phone_${rowCount}" name="phone[]" ${record ? 'value="' + (record.phone || '') + '"' : ''} >
                </div>
            </div>
            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="dob_${rowCount}">*Birthday</label>
                    <input class="form-control" type="date" id="dob_${rowCount}" name="dob[]" ${record ? 'value="' + (record.dob || '') + '"' : ''}>
                </div>
            </div>
            
        `);

        if (rowCount > 1) {
            newRow.append(`
                <div class="col-sm-9 my-2 text-right">.</div>
                <div class="col-sm-3 my-2 text-right">
                    <button type="button" class="btn btn-block btn-danger btn-sm deleteRow"><em class="icon ni ni-trash"></em> <span>Delete</span></button>
                </div>
            `);
        }
        return newRow;
    }

    // Handle "Add More" button click
    $('#addMores').on('click', function() {
        $('#rowsContainer').append(createRow());
    });

    // Handle delete button click
    $('#rowsContainer').on('click', '.deleteRow', function() {
        $(this).closest('.row').remove(500);
        rowCount--;

        // Disable delete button if only one row remains
        if ($('#rowsContainer .row').length === 1) {
            $('#rowsContainer .deleteRow').prop('disabled', true);
        }
    });

    // Function to add rows with existing records
    function addRowsWithRecords(records) {
        records.forEach(record => {
            $('#rowsContainer').append(createRow(record));
        });
    }

    function new_convert_report(id){
        $('#new_convert_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#new_convert_view').show(500);
        $('#attendance_prev').show(500);
        
        $.ajax({
            url: site_url + 'service/report/manage/new_convert/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#new_convert_id').val(dt.id)

                // Parse the convert_list JSON string
                var existingRecords = JSON.parse(dt.convert_list);
                
                
                if(existingRecords.length > 0){
                    addRowsWithRecords(existingRecords);
                }
                
                $('#new_convert_msg').html('');
            }
        });
       
    }
  
    function first_timer_report(id){
        $('#first_timer_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#first_timer_view').show(500);
        $('#attendance_prev').show(500);
        $('#firstTimerForm')[0].reset(); // Clear existing form
        $('#formContainer').html(''); // Clear existing records


        $.ajax({
            url: site_url + 'service/report/manage/first_timer/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#first_timer_id').val(dt.id);
                // Parse the convert_list JSON string
                var existingRecords = JSON.parse(dt.timer_list);
                console.log(existingRecords);
                
                fetchFormFields(dt.church_id, existingRecords);
                
                $('#first_timer_msg').html('');
            }
        });
       
    }
 // Function to load form fields dynamically
    function fetchFormFields(churchId, records) {
        records.forEach(record => {
            addFormField(record);
        });
    }

    // Function to add form field (for both new and existing records)
    function addFormField(data = {}) {
        let newRecord = `<div class="card new_card p-3 mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Firstname</label>
                    <input type="text" class="form-control" name="firstname[]" value="${data.firstname || ''}" >
                </div>
                <div class="col-md-4">
                    <label class="form-label">Surname</label>
                    <input type="text" class="form-control" name="surname[]" value="${data.surname || ''}" >
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email[]" value="${data.email || ''}" >
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone[]" value="${data.phone || ''}" >
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender[]">
                        <option value="Male" ${data.gender === 'Male' ? 'selected' : ''}>Male</option>
                        <option value="Female" ${data.gender === 'Female' ? 'selected' : ''}>Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Family Position</label>
                    <select class="form-select" name="family_position[]">
                        <option value="Child" ${data.family_position === 'Child' ? 'selected' : ''}>Child</option>
                        <option value="Parent" ${data.family_position === 'Parent' ? 'selected' : ''}>Parent</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="dob[]" value="${data.dob || ''}" >
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invited By</label>
                    <select class="form-select" name="invited_by[]">
                        <option value="Online" ${data.invited_by === 'Online' ? 'selected' : ''}>Online</option>
                        <option value="Member" ${data.invited_by === 'Member' ? 'selected' : ''}>Member</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-danger mt-3 remove-btn">Remove</button>
        </div>`;

        $("#formContainer").append(newRecord);
    }

    // On page load, add an empty form if no data exists
    if ($('#formContainer').is(':empty')) {
        addFormField();
    }

    // Add new blank form when clicking "Add More"
    $("#addMore").click(function() {
        addFormField();
    });

    // Remove form entry
    $(document).on("click", ".remove-btn", function() {
        $(this).closest(".card").remove();
    });


    function finance_report(id){
        $('#finance_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#finance_view').show(500);
        $('#attendance_prev').show(500);
        
        $.ajax({
            url: site_url + 'service/report/manage/finance/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#finance_id').val(dt.id);

                $('#service_name').html(dt.service_name);

                $('#total_partnership').val(dt.total_partnership);
                $('#member_partnership').val(dt.member_partnership);
                $('#guest_partnership').val(dt.guest_partnership);
                $('#total_thanksgiving').val(dt.total_thanksgiving);
                $("#member_thanksgiving").val(dt.member_thanksgiving);
                $("#guest_thanksgiving").val(dt.guest_thanksgiving);
                $('#total_tithe').val(dt.total_tithe);
                $("#member_tithe").val(dt.member_tithe);
                $("#guest_tithe").val(dt.guest_tithe);
                $('#total_seed').val(dt.total_seed);
                $("#member_seed").val(dt.member_seed);
                $("#guest_seed").val(dt.guest_seed);
                $('#total_offering').val(dt.total_offering);
                $("#member_offering").val(dt.member_offering);
                $("#guest_offering").val(dt.guest_offering);

                fetchAndPopulateFirstTimers(dt.id);
               
                populateMember(dt.id)
                $('#finance_msg').html('');
            }
        });
       
    }
    
    function fetchAndPopulateFirstTimers(id) {
        $.ajax({
            url: site_url + 'service/report/records/getFirstTimers/' + id,
            type: 'get',
            success: function (data) {
                const firstTimers = JSON.parse(data);
                $('#guest_partner_list').empty();
    
                if (firstTimers.length === 0) {
                    $('#guest_part_view').hide(500);
                    $('#guest_partner_list').html('<tr><td colspan="8" class="text-center">No first timers available</td></tr>');
                    return;
                }
    
                $('#guest_part_view').show(500);
    
                firstTimers.forEach(function (timer, index) {
                    const fullname = timer.name?.toUpperCase() || '';
                    const phone = timer.phone || '';
                    const member_id = timer.id;
    
                    if (!fullname) return;
    
                    $.ajax({
                        url: site_url + 'service/report/records/get_service_partnership/' + id,
                        method: 'POST',
                        data: { name: member_id },
                        dataType: 'json',
                        success: function (response) {
                            if (!response || !response.partners) return;
    
                            const currency = response.currency || [];
                            const partners = response.partners;
                            const guest_offering = response.guest_offering || "";
                            const guest_tithe = response.guest_tithe || "";
                            const guest_thanksgiving = response.guest_thanksgiving || "";
                            const guest_seed = response.guest_seed || "";
    
                            let row = '<tr>';
                            row += `<td><input type="hidden" class="form-control member-id-field guests" name="guests[]" value="${member_id}">`;
                            row += `<span class="small">${fullname} - ${phone}</span></td>`;
    
                            // Core guest finance types
                            const contributions = {
                                "offering": guest_offering,
                                "tithe": guest_tithe,
                                "thanksgiving": guest_thanksgiving,
                                "seed": guest_seed
                            };
    
                            Object.entries(contributions).forEach(([type, value]) => {
                                row += `
                                    <td>
                                        <input type="text" 
                                               style="width:100px;" 
                                               class="form-control finance-fields" 
                                               name="guest_${type}[]" 
                                               value="${value}" 
                                               placeholder="0"
                                               data-field="${type}" 
                                               data-member-id="${member_id}" 
                                               data-user-type="guest"
                                               oninput="
                                                    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\\..*)\\./g, '$1');
                                                    autoSaveFinance(this);
                                               "
                                        ><small class="text-success msg-box" style="display:none;"></small>
                                    </td>`;
                            });
    
                            // Guest partnership contributions
                            partners.forEach(function (partner) {
                                const amount = partner.amount || '0';
                                const partnerField = `partner_${partner.id}`;
                                row += `
                                    <td>
                                        <input type="text" 
                                               style="width:100px;" 
                                               class="form-control finance-fields" 
                                               name="${partnerField}_guest[]" 
                                               value="${amount}" 
                                               data-field="${partnerField}" 
                                               data-member-id="${member_id}" 
                                               placeholder="0"
                                               data-user-type="guest"
                                               oninput="
                                                    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\\..*)\\./g, '$1');
                                                    autoSaveFinance(this);
                                               "
                                        ><small class="text-success msg-box" style="display:none;"></small>
                                    </td>`;
                            });
    
                            // Currency dropdown
                            const currency_selectz = $('<select class="js-select2  currency-select form-control" name="guest_currency[]"></select>');
                            currency_selectz.append('<option value="0">Espees</option>');
    
                            if (currency && Object.keys(currency).length > 0) {
                                Object.entries(currency).forEach(([id, name]) => {
                                    currency_selectz.append(`<option value="${id}" selected>${name}</option>`);
                                });
                            }
    
                            row += `<td>${currency_selectz.prop('outerHTML')}</td>`;
                            row += '</tr>';
    
                            $('#guest_partner_list').append(row);
                            $('.js-select2').select2();
                        },
                        error: function (xhr, status, error) {
                            console.error("Partnership fetch error:", status, error);
                            alert("Could not fetch partnership data.");
                        }
                    });
                });
            },
            error: function (xhr, status, error) {
                console.error('First timer fetch error:', error);
                $('#guest_partner_list').html('<tr><td colspan="8" class="text-center">Failed to load first timers.</td></tr>');
            }
        });
    }

    function autoSaveFinance(input) {
        const $input = $(input);
        let val = $input.val();
        const match = val.match(/^\d*\.?\d{0,2}/);
        val = match ? match[0] : '';
        $input.val(val);
        
        const $row = $input.closest('tr'); // ✅ Correct reference
        const amount = parseFloat(val) || 0;
        const member_id = $input.data('member-id');
        const field = $input.data('field');
        const user_type = $input.data('user-type') || 'guest';
        const report_id = $('#finance_id').val();
        const currency = $row.find('.currency-select').val(); // ✅ Now works
    
        if (!member_id || !field || !report_id) return;
    
        // ✅ Send all required data
        $.post(site_url + 'service/report/records/save_finance_field', {
            member_id: member_id,
            field_name: field,
            amount: amount,
            report_id: report_id,
            user_type: user_type,
            currency: currency
        }, function (res) {
            if (res.status) {
                console.log(`✅ ${user_type} - ${field} saved: ₦${amount}`);
            } else {
                alert('Save failed: ' + res.message);
            }
        }, 'json');
    
        updateFinanceTotals(field);
    }
    
    
    function updateFinanceTotals(field) {
        const isPartner = field.startsWith('partner_');
        const mainField = isPartner ? 'partnership' : field;
    
        let guestTotal = 0;
    
        // ✅ Loop through all guest inputs and sum values for this finance type
        $(`.finance-fields[data-user-type="guest"]`).each(function () {
            const thisField = $(this).data('field');
            const thisVal = parseFloat($(this).val()) || 0;
    
            if (
                (!isPartner && thisField === field) || 
                (isPartner && thisField.startsWith('partner_'))
            ) {
                guestTotal += thisVal;
            }
        });
    
        // ✅ Update guest field only (e.g. #guest_offering)
        const guestSelector = `#guest_${mainField}`;
        $(guestSelector).val(guestTotal.toFixed(2));
    
        // ✅ Read existing member value (unchanged)
        const memberVal = parseFloat($(`#member_${mainField}`).val()) || 0;
    
        // ✅ Update total (guest + member)
        const total = memberVal + guestTotal;
        $(`#total_${mainField}`).val(total.toFixed(2));
    }
    
    

    let churchMembers = [];
    let partnerships = [];
    let currency = [];
               
    function populateMember(id) {
        $.ajax({
            url: site_url + 'service/report/records/get_members_finance/' + id, // API Endpoint
            type: 'GET',
            success: function (response) {
                try {
                    var mems = JSON.parse(response); // Parse JSON Response
    
                    // Ensure the response contains valid data
                    if (mems && typeof mems === "object") {
                        
                        // Clear existing table rows
                        $('#member_partner_list').empty();
    
                        // Populate table rows if available
                        if (mems.members_part && mems.members_part.trim() !== "") {
                            $('#member_partner_list').html(mems.members_part).fadeIn(500);
                        } else {
                            $('#member_partner_list').html('<tr><td colspan="100%" class="text-center">No records found.</td></tr>').fadeIn(500);
                        }
    
                        // Store Church Members
                        if (Array.isArray(mems.members)) {
                            churchMembers = mems.members;
                        } else {
                            console.error('mems.members is not an array');
                            churchMembers = [];
                        }
    
                        // Store Partnerships
                        partnerships = mems.partnerships || [];
                        currency = mems.currency || [];
    
                        // Initialize Select2 for any dynamically added dropdowns
                        $('.js-select2').select2();
    
                    } else {
                        console.error("Invalid data structure returned from API");
                    }
                } catch (error) {
                    console.error("Error parsing JSON response:", error);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                $('#member_partner_list').html('<tr><td colspan="100%" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
            }
        });
    }
    
    
    function deleteRowz(button) {
        $(button).closest('tr').remove(); // Remove the closest <tr> (table row)
    }
    
              
    function populateAttendance(id) {
        $.ajax({
            url: site_url + 'service/report/records/get_members_attendance/'+id, // Adjust the URL according to your API
            type: 'get',
            success: function (data) {
                var membersObject = JSON.parse(data); // Assuming the response is JSON formatted
                let mems = Object.values(membersObject.members);

                // console.log(mems);
                $('#member_attendance_list').html(membersObject.members_part).fadeIn(500);
                if (Array.isArray(mems)) {
                    churchMembers = mems;
                
                    // Clear the select element before adding new options
                    $('#present_members').empty();
                
                    // Populate the select element with options
                    churchMembers.forEach(member => {
                        $('#present_members').append(`<option value="${member.id}">${member.fullname} - ${member.phone}</option>`);
                    });
                    $('#present_members').select2({
                        placeholder: "Select Members", // Placeholder text
                        allowClear: true // Allows clearing selection
                    });
                } else {
                    console.error('mems.members is not an array');
                    churchMembers = []; // or some default value
                }
                
                
            }
        });
    }

    let absentRowIndex = 0;

    $('#absent_add_btn').click(function() {
        absentRowIndex++;
    
        // Create a new row
        const absent_newRow = $('<tr></tr>');
    
        // Create a select element for church members
        const absent_memberSelect = $(`<select class="js-select2" name="absent_members[]" id="members_${absentRowIndex}" ></select>`);
    
        // Add an empty default option
        absent_memberSelect.append('<option value="" selected disabled>Select a Member</option>');
    
        if (churchMembers && churchMembers.length > 0) {
            churchMembers.forEach(function(member) {
                absent_memberSelect.append(`<option value="${member.id}">${member.fullname} - ${member.phone}</option>`);
            });
        }
    
        // Append elements to the row
        absent_newRow.append($('<td width="250px;"></td>').append(absent_memberSelect));
        absent_newRow.append(`
            <td>
                <input type="text" class="form-control" name="reasons[]">
            </td>
        `);
    
        // Append the new row to the table body
        $('#absent_attendance_list').append(absent_newRow);
    
        // Initialize Select2 for this individual element
        absent_memberSelect.select2();
    });

    // Global input restriction handler
    function restrictNumericInput() {
        let val = $(this).val();
    
        // Match allowed pattern: digits, optional dot, up to 2 decimals
        const match = val.match(/^\d*\.?\d{0,2}/);
    
        // Apply matched portion (or empty if nothing valid)
        $(this).val(match ? match[0] : '');
    }
    
    

    function calculateSum() {
        var sum = 0;
        // Loop through all elements with the class 'members'
        $('.members_amount').each(function() {
            // Parse the value as a float and add it to the sum
            sum += parseFloat($(this).val()) || 0; // If parsing fails, default to 0
        });

        // Round the sum to 2 decimal places
        var roundedSum = Math.round(sum * 100) / 100;

        // Display the rounded sum in the 'member_part' text box
        $('#member_part').val(roundedSum.toFixed(2));
        total_part();
    }

    function calculateFirst() {
        var sum = 0;
        // Loop through all elements with the class 'members'
        $('.firsts_amount').each(function() {
            // Parse the value as a float and add it to the sum
            sum += parseFloat($(this).val()) || 0; // If parsing fails, default to 0
        });

        // Round the sum to 2 decimal places
        var roundedSum = Math.round(sum * 100) / 100;

        // Display the rounded sum in the 'member_part' text box
        $('#guest_part').val(roundedSum.toFixed(2));
        total_part();
    }

    function total_part(){
        var member = $('#member_part').val();
        var guest = $('#guest_part').val();
        
        var total = parseInt(member) + parseInt(guest);
        $('#total_part').val(total.toFixed(2));
    }

    // Bind the calculateSum function to the input event of elements with class 'members'
    $('.firsts_amount').on('input', calculateFirst);

    // function timerRecords(records) {
    //     records.forEach(record => {
    //         // $('#containers').append(createNewSection(record));
    //         $('select[name="invited_by[]"]').on('change', handleInvitedByChange);
    //           // Execute the function immediately to handle any initial state
    //            // Select the most recently added select element
    //         const $selectElement = $('select[name="invited_by[]"]').last();

    //         // Attach the event handler for the change event
    //         $selectElement.on('change', handleInvitedByChange);

    //         handleInvitedByChange.call($selectElement[0]);
    //     });
    // }
     // Initialize row count
     let first_timer_count = 0;

     // Container where new form sections will be appended
     const container = $('#containers'); // Adjust this selector to your actual container
 
    
    function deleteSection(rowId) {
        $(`#row-${rowId}`).remove(500); // Remove the row from the DOM
        first_timer_count--; // Decrement the row count
    }
    
    // Event listener to handle delete button clicks using jQuery
    $(document).on('click', '.btn-delete', function() {
        const rowId = $(this).data('row'); // Get the row ID from data attribute
        deleteSection(rowId);
    });

    function updateTotals() {
        // Get values from the input fields
        var memberValue = parseInt($('#member_attendance').val()) || 0;
        var guestValue = parseInt($('#guest_attendance').val()) || 0;
        var maleValue = parseInt($('#male_attendance').val()) || 0;
        var femaleValue = parseInt($('#female_attendance').val()) || 0;
        var childrenValue = parseInt($('#children_attendance').val()) || 0;

        // Calculate the total
        var total = memberValue + guestValue;

        // Update the total field
        $('#total_attendance').val(total);
    }

    // Attach event listeners to input fields
    $('#member_attendance, #guest_attendance, #male_attendance, #female_attendance, #children_attendance').on('input', updateTotals);
    

    function load(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#load_data').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#loadmore').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }


       
        // Get filter values
        const search = $('#search').val();
        const date = $('#filterDate').val();
        const type = $('#filterType').val();
        const scope = $('#church_scope').val();
        const selectedChurches = $('#selected_churches').val(); // array
        const cell_id = $('#cell_id').val();

        $.ajax({
            url: site_url + 'service/report/load' + methods,
            type: 'post',
            data: {
                search: search,
                date: date,
                type: type,
                church_scope: scope,
                selected_churches: selectedChurches,
                cell_id: cell_id
            },
            success: function (data) {
                var dt = JSON.parse(data);
            
                if (more === 'no') {
                    $('#load_data').html(dt.item);
                } else {
                    $('#load_data').append(dt.item);
                }

                if (dt.services.length > 0) {
                    let options = `<option value="all">All Types</option>`;
                    dt.services.forEach(service => {
                        options += `<option value="${service.id}">${service.name}</option>`;
                    });
                    $('#filterType').html(options);
                } else {
                    // If no services found, show only "All Types"
                    $('#filterType').html(`<option value="all">All Types</option>`);
                }
            
            
                $('#counta').html(dt.count);
                $('#t_attendance').html(dt.t_attendance);
                $('#t_firstTimer').html(dt.t_firstTimer);
                $('#t_convert').html(dt.t_convert);
                $('#t_offering').html(dt.t_offering);
                $('#t_tithe').html(dt.t_tithe);
                $('#t_partnership').html(dt.t_partnership);
                $('#t_thanksgiving').html(dt.t_thanksgiving);
                $('#t_seed').html(dt.t_seed);
            
                // Show Load More button only if there are records left
                if (parseInt(dt.left) > 0) {
                    let loadMoreCount = Math.min(parseInt(dt.left), parseInt(dt.limit)); // don't show more than remaining
                    $('#loadmore').html(`
                        <tr>
                            <td colspan="8">
                                <a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load(${dt.limit}, ${dt.offset});">
                                    <em class="icon ni ni-redo fa-spin"></em> Load ${loadMoreCount} More
                                </a>
                            </td>
                        </tr>
                    `);
                } else {
                    $('#loadmore').html('');
                }
            },
            
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    $(document).ready(function() {
        
    
        // Attach a submit event handler to the form
        $('#attendanceForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = $(this).serialize(); // Serialize form data
            $('#attendance_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            // Send an AJAX POST request
            $.ajax({
                url: site_url + 'service/report/manage/attendance', // Replace with your server URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle a successful response
                    $('#attendance_msg').html(response);
                }
            });
        });

        $('#titheForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = $(this).serialize(); // Serialize form data
            $('#tithe_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            // Send an AJAX POST request
            $.ajax({
                url: site_url + 'service/report/manage/tithe', // Replace with your server URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle a successful response
                    $('#tithe_msg').html(response);
                }
            });
        });

        
        $('#offeringForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = $(this).serialize(); // Serialize form data
            $('#offering_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            // Send an AJAX POST request
            $.ajax({
                url: site_url + 'service/report/manage/offering', // Replace with your server URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle a successful response
                    $('#offering_msg').html(response);
                }
            });
        });

        $('#thanksgivingForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = $(this).serialize(); // Serialize form data
            $('#thanksgiving_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            // Send an AJAX POST request
            $.ajax({
                url: site_url + 'service/report/manage/thanksgiving', // Replace with your server URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle a successful response
                    $('#thanksgiving_msg').html(response);
                }
            });
        });
        $('#seedForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = $(this).serialize(); // Serialize form data
            $('#seed_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            // Send an AJAX POST request
            $.ajax({
                url: site_url + 'service/report/manage/seed', // Replace with your server URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle a successful response
                    $('#seed_msg').html(response);
                }
            });
        });

        $('#new_convert_Form').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = $(this).serialize(); // Serialize form data
            $('#new_convert_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            // Send an AJAX POST request
            $.ajax({
                url: site_url + 'service/report/manage/new_convert', // Replace with your server URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle a successful response
                    $('#new_convert_msg').html(response);
                    rowCount = 0;
                    $('#rowsContainer').empty();
                }
            });
        });

        $('#firstTimerForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            $('#first_timer_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            let first_timers = []; // Array to hold structured data

            // Loop through each card (first timer entry)
            $(".new_card").each(function() {
                let formData = {
                    firstname: $(this).find("[name='firstname[]']").val(),
                    surname: $(this).find("[name='surname[]']").val(),
                    email: $(this).find("[name='email[]']").val(),
                    phone: $(this).find("[name='phone[]']").val(),
                    gender: $(this).find("[name='gender[]']").val(),
                    family_position: $(this).find("[name='family_position[]']").val(),
                    dob: $(this).find("[name='dob[]']").val(),
                    invited_by: $(this).find("[name='invited_by[]']").val()
                };
                first_timers.push(formData); // Add the structured data
            });
    
            // Final JSON payload with structured data
            let finalData = {
                first_timer_id: $("#first_timer_id").val(),
                first_timers: first_timers
            };
    
            console.log(finalData); // Debugging: Check structured output before sending
    
            $.ajax({
                url: site_url + 'service/report/manage/first_timer',
                type: "POST",
                data: JSON.stringify(finalData),
                contentType: "application/json",
                success: function(response) {
                    $('#first_timer_msg').html(response);
                    first_timer_count = 0;

                    $('#containers').empty();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    alert("Error submitting form!");
                }
            });
           
            
        });

        $('#financeForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = $(this).serialize(); // Serialize form data
            $('#finance_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            // Send an AJAX POST request
            $.ajax({
                url: site_url + 'service/report/manage/finance', // Replace with your server URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle a successful response
                    $('#finance_msg').html(response);
                    $('#financeForm').trigger('reset');
                }
            });
        });

        $('#mark_attendanceForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = $(this).serialize(); // Serialize form data
            $('#mark_attendance_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            // Send an AJAX POST request
            $.ajax({
                url: site_url + 'service/report/manage/mark_attendance', // Replace with your server URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle a successful response
                    $('#mark_attendance_msg').html(response);
                }
            });
        });

    });

    

    function calculateTotal() {
        var tithesInputs = document.querySelectorAll('.tithe');
        var total = 0;
        tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            total += isNaN(value) ? 0 : value;
        });
        $('#member_tithe').val(total.toFixed(2));

        var guest_tithesInputs = document.querySelectorAll('.guestz_tithe');
        var guest_total = 0;
        guest_tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            guest_total += isNaN(value) ? 0 : value;
        });
        $('#guest_tithe').val(guest_total.toFixed(2));

        
        total += parseFloat(guest_total);
        
        total = total.toFixed(2);
        $('#total_tithe').val(total);

        // Set value to 0 if the textbox is empty
        tithesInputs.forEach(function(input) {
            if (input.value === '') {
                input.value = '';
            }
        });
    }

    

    function calculateTotalz() {
        
        var tithesInputs = document.querySelectorAll('.offering');
        var total = 0;
        tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            total += isNaN(value) ? 0 : value;
        });
        $('#member_offering').val(total.toFixed(2));

        var guest_total = 0;
        var guest_tithesInputs = document.querySelectorAll('.guestz_offering');
        var guest_total = 0;
        guest_tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            guest_total += isNaN(value) ? 0 : value;
        });
        var guest = $('#guest_offering').val(guest_total.toFixed(2));
        
        total += parseFloat(guest_total);
        total = total.toFixed(2);
        $('#total_offering').val(total);

        // Set value to 0 if the textbox is empty
        tithesInputs.forEach(function(input) {
            if (input.value === '') {
                input.value = '';
            }
        });
    }

    function calculateTotalz_thanksgiving() {
        
        var tithesInputs = document.querySelectorAll('.thanksgiving');
        var total = 0;
        tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            total += isNaN(value) ? 0 : value;
        });
        $('#member_thanksgiving').val(total.toFixed(2));

        var guest_tithesInputs = document.querySelectorAll('.guestz_thanksgiving');
        var guest_total = 0;
        guest_tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            guest_total += isNaN(value) ? 0 : value;
        });
        $('#guest_thanksgiving').val(guest_total.toFixed(2));

        
        total += parseFloat(guest_total);
        total = total.toFixed(2);
        $('#total_thanksgiving').val(total);

        // Set value to 0 if the textbox is empty
        tithesInputs.forEach(function(input) {
            if (input.value === '') {
                input.value = '';
            }
        });
    }

    
    function calculateTotalz_seed() {
        var tithesInputs = document.querySelectorAll('.seed');
        var total = 0;
        tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            total += isNaN(value) ? 0 : value;
        });
        $('#member_seed').val(total.toFixed(2));

        var guest_tithesInputs = document.querySelectorAll('.guestz_seed');
        var guest_total = 0;
        guest_tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            guest_total += isNaN(value) ? 0 : value;
        });
        $('#guest_seed').val(guest_total.toFixed(2));

        
        total += parseFloat(guest_total);
        total = total.toFixed(2);
        $('#total_seed').val(total);

        // Set value to 0 if the textbox is empty
        tithesInputs.forEach(function(input) {
            if (input.value === '') {
                input.value = '';
            }
        });
    }

    
    function generateTable(x, y, id) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }
        var searchBox = $('#tithe_search');
        searchBox.on('input', function() {
            var search = $(this).val();
            generateTable(x, y, id);
        });

        if (more == 'no') {
            $('#tithe_table_resp').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#tithe_btns').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

       
        var search = $('#tithe_search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'service/report/tithe_list' + methods,
            type: 'post',
            data: { search: search,service_id:id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#tithe_table_resp').html(dt.item);
                } else {
                    $('#tithe_table_resp').append(dt.item);
                }
                
                if (dt.offset > 0) {
                    $('#tithe_btns').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="generateTable(' + dt.limit + ', ' + dt.offset + ', '+id+');"><em class="icon ni ni-redo fa-spin"></em> Load  More</a>');
                } else {
                    $('#tithe_btns').html('');
                }
              
            }
        });
    }
    
    $(document).ready(function () {
        $('#tag-input').on('keydown', function (e) {
            var media_id = $('#media_id').val();
            if (e.which === 188) { // Comma key
                e.preventDefault();
                const tag = $(this).val().trim();
                // addTag(tag,media_id);
                if (isValidUrlStructure(tag)) {
                    checkUrl(tag, media_id);
                } else {
                    $('#error-message').text('Invalid URL structure');
                }
            }
        });

        function isValidUrlStructure(url) {
            const pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                '((([a-z0-9]+([-.][a-z0-9]+)*)\\.)+[a-z]{2,}|' + // domain name
                'localhost|' + // localhost
                '\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}|' + // IP
                '\\[?[a-f0-9]*:[a-f0-9:%.~+!$&\'()*+,;=]+\\]?)' + // IPv6
                '(\\:\\d+)?(\\/[-a-z0-9%_.~+;=]*)*' + // port and path
                '(\\?[;&a-z0-9%_.~+=-]*)?' + // query string
                '(\\#[-a-z0-9_]*)?$', 'i'); // fragment locator
            return pattern.test(url);
        }


        function checkUrl(tag, media_id) {
            $.ajax({
                url: tag,
                type: 'HEAD',
                success: function (data, status, xhr) {
                    if (xhr.status === 202) {
                        addTag(tag, media_id);
                        $('#tag-input').val(''); // Clear the input field
                        $('#error-message').text(''); // Clear any error message
                    } else {
                        $('#error-message').text('URL must return a 202 status code');
                    }
                },
                error: function () {
                    $('#error-message').text('Invalid link or URL does not exist');
                }
            });
        }

        function addTag(tag, media_id) {
            const tagElement = $('<span class="tag"></span>');
            const linkElement = $('<a></a>')
                .attr('href', tag)
                .attr('target', '_blank') // Open in a new tab
                .text(tag)
                .css('color', 'white'); // Style link color

            $.ajax({
                url: site_url + 'service/report/records/add_url/'+media_id, // Replace with your server URL
                type: 'POST',
                data: {url:tag},
                success: function(response) {
                    // media_report(media_id);
                }
            });
                
            const removeBtn = $('<span class="remove">×</span>').click(function () {
                // Get the link text (URL) from the anchor tag
                const linkText = $(this).siblings('a').text(); // Gets the text of the anchor tag
                console.log(linkText);
                $.ajax({
                    url: site_url + 'service/report/records/delete_url/'+media_id, // Update the endpoint as necessary
                    type: 'POST',
                    data: { url: linkText }, // Send the link text as data
                    success: function(response) {
                        
                    }
                });
                
                // Remove the tag from the UI
                $(this).parent().remove();
                
            });
            
            tagElement.append(linkElement).append(removeBtn);
            $('#url-input').prepend(tagElement);
        }

        
    });

        // Function to remove URL
    function removeUrl(linkText, media) {
        $.ajax({
            url: site_url + 'service/report/records/delete_url/' + media, // Update the endpoint as necessary
            type: 'POST',
            data: { url: linkText }, // Send the link text as data
            success: function(response) {
                // Handle success response if needed
                console.log('URL successfully deleted:', response);
            },
            error: function(xhr, status, error) {
                // Handle error if needed
                console.error('Error deleting URL:', error);
                alert('Failed to delete URL. Please try again.');
            }
        });
    }
    $(document).on('click', '.remove_url', function() {
        const link = $(this).closest('.tag').find('.video_link').val();
        const media = $('#media_id').val(); // Assuming this is how you get media ID
        console.log(link);
        
        // Call the function to remove the URL
        removeUrl(link, media);
        
        // Remove the tag and the button from the UI
        $(this).closest('.tag').remove(); // Adjust to remove the entire tag
    });


    
    $(document).on('change', '.mark-present-switch', function () {
        var $this = $(this);
        var member_id = $this.data('member-id');
        var isPresent = $this.is(':checked');
        var service_id = $('#attendance_id').val();
        var church_id = $('#church_id').val();
    
        var mark = isPresent ? 1 : 0; // 1 = Present, 0 = Absent
    
        // ✅ Hide/Show Reason box immediately
        if (isPresent) {
            $('#absent_reason_wrapper_' + member_id).slideUp();
        } else {
            $('#absent_reason_wrapper_' + member_id).slideDown();
        }
    
        // Feedback during update
        $('#resp_' + member_id).html('<small class="text-info">Updating...</small>');
    
        $.ajax({
            url: site_url + 'service/report/attendance/mark_present',
            type: 'POST',
            data: {
                member_id: member_id,
                service_id: service_id,
                church_id: church_id,
                mark: mark
            },
            success: function (response) {
                let res;
                try {
                    res = typeof response === 'object' ? response : JSON.parse(response);
                } catch (e) {
                    res = { status: 'error', message: response };
                }
    
                if (res.status === 'success') {
                    $('#resp_' + member_id).html('<small class="text-success">Marked</small>');
                    // Refresh attendance report if needed
                    attendance_report(service_id);
                } else {
                    $('#resp_' + member_id).html('<small class="text-danger">' + res.message + '</small>');
                    $this.prop('checked', !isPresent); // Rollback checkbox if failed
                    if (isPresent) {
                        $('#absent_reason_wrapper_' + member_id).slideDown(); // rollback UI
                    } else {
                        $('#absent_reason_wrapper_' + member_id).slideUp();
                    }
                }
            },
            error: function () {
                $('#resp_' + member_id).html('<small class="text-danger">Failed to update status.</small>');
                $this.prop('checked', !isPresent);
                if (isPresent) {
                    $('#absent_reason_wrapper_' + member_id).slideDown();
                } else {
                    $('#absent_reason_wrapper_' + member_id).slideUp();
                }
            }
        });
    });
    
    $(document).on('change', '.mark-convert-switch', function () {
        var $this = $(this);
        var member_id = $(this).data('member-id');
        var type = $(this).data('type');
        var isPresent = $(this).is(':checked');
        var service_id = $('#attendance_id').val();
        var church_id = $('#church_id').val();

        var mark = 0;
        if(isPresent){
            var mark = 1;
        }

       
            $('#con_resp_' + member_id).html('<small class="text-info">Updating...</small>');
            // $this.prop('disabled', true);
            $.ajax({
                url: site_url + 'service/report/attendance/mark_convert',
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
                        
                        attendance_report(service_id); 
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
    // Handle Absent Toggle
    $(document).on('change', '.mark-absent-switch', function () {
        let $this = $(this);
        let member_id = $this.data('member-id');
        let isAbsent = $this.is(':checked');
        let service_id = $('#attendance_id').val();
        let church_id = $('#church_id').val();
        let mark = isAbsent ? 1 : 0;

        // Disable present switches
        $('#presentSwitchq_' + member_id).prop('disabled', isAbsent);
       
        // Show or hide reason wrapper
        $('#absent_reason_wrapper_' + member_id).toggle(isAbsent);
        if (isAbsent) $('#absent_reasonq_' + member_id).val('');

        // If unchecked, mark them present
        if (!mark) {
            $.ajax({
                url: site_url + 'service/report/attendance/mark_present',
                type: 'POST',
                data: {
                    member_id: member_id,
                    service_id: service_id,
                    church_id: church_id,
                    mark: mark
                },
                success: function (response) {
                    let res = typeof response === 'object' ? response : JSON.parse(response);
                    if (res.status === 'success') {
                        $('#respq_' + member_id).html('<small class="text-success">Marked</small>');
                       
                        attendance_report(service_id);
                    } else {
                        $('#respq_' + member_id).html('<small class="text-danger">' + res.message + '</small>');
                        $this.prop('checked', false);
                    }
                },
                error: function () {
                    $('#respq_' + member_id).html('<small class="text-danger">Failed to mark member as present.</small>');
                    $this.prop('disabled', false);
                }
            });
        }
    });

    // Show other reason input when "Other" is selected
    $(document).on('change', '.reason-select', function () {
        let $this = $(this);
        let member_id = $this.data('member-id');
        let $row = $this.closest('tr');
        let selected = $this.val();
        let $otherInput = $row.find('.other-reason-input');

        if (selected.includes('Other')) {
            $otherInput.show().focus();
        } else {
            $otherInput.hide().val('');
        }
    });

    // Auto-submit reason
    $(document).on('change blur', '.reason-select, .other-reason-input', function () {
        let $this = $(this);
        let $row = $this.closest('tr');
        let member_id = $this.data('member-id');
        let reason = $row.find('.reason-select').val();
        let other_reason = $row.find('.other-reason-input').val();
        let final_reason = reason.includes('Other') ? other_reason.trim() : reason;

        if (final_reason !== '') {
            let service_id = $('#attendance_id').val();
            let church_id = $('#church_id').val();
            let $resp = $('#respq_' + member_id);
            let isAbsent = $row.find('.mark-absent-switch').is(':checked');
            let mark = isAbsent ? 1 : 0;

            $resp.html('<small class="text-info">Saving reason...</small>');

            $.ajax({
                url: site_url + 'service/report/attendance/mark_absent',
                type: 'POST',
                data: {
                    member_id: member_id,
                    reason: final_reason,
                    service_id: service_id,
                    mark: mark,
                    church_id: church_id
                },
                success: function (response) {
                    let res = typeof response === 'object' ? response : JSON.parse(response);
                    if (res.status === 'success') {
                        $resp.html('<small class="text-success">' + res.message + '</small>');
                       
                        attendance_report(service_id);
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
    let partnershipList = []; // to be filled from backend

    // Load partnerships once
    $.get(site_url + 'service/report/records/get_partnerships', function (res) {
        partnershipList = res.data; // expects [{id: 1, name: "Bible"}, ...]
        }, 'json');

    // ========== Add More Member Finance Row ==========
    $(document).on('click', '#mem_btn', function () {
        let service_id = $('#attendance_id').val();
        const $tableBody = $('#member_partner_list');
    
        $tableBody.find('tr:contains("No records found")').remove();
    
        let partnershipInputs = '';
        partnershipList.forEach(p => {
            partnershipInputs += `<td><input type="number" class="form-control finance-field" style="min-width: 120px;" data-field="partner_${p.id}" placeholder="0"></td>`;
        });
    
        const row = `
            <tr class="member-finance-row">
                <td style="min-width: 200px;">
                    <select class="form-control member-select js-select2" name="members[]" style="width: 100%;">
                        <option value="">Select Member</option>
                    </select>
                    <input type="hidden" class="member-id-field" name="member_id[]" value="">
                </td>
                <td><input type="number" class="form-control finance-field" style="min-width: 120px;" data-field="offering" placeholder="0"><small class="text-success msg-box" style="display:none;"></small></td>
                <td><input type="number" class="form-control finance-field" style="min-width: 120px;" data-field="tithe" placeholder="0"><small class="text-success msg-box" style="display:none;"></small></td>
                <td><input type="number" class="form-control finance-field" style="min-width: 120px;" data-field="thanksgiving" placeholder="0"><small class="text-success msg-box" style="display:none;"></small></td>
                <td><input type="number" class="form-control finance-field" style="min-width: 120px;" data-field="seed" placeholder="0"><small class="text-success msg-box" style="display:none;"></small></td>
                ${partnershipInputs}
                <td>
                    <select class="form-control currency-select" style="min-width: 120px;">
                        <option value="0">ESPees</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row"><em class="icon ni ni-cross"></em></button></td>
            </tr>
        `;
    
        $tableBody.append(row);
        $('.js-select2').select2();
    
         // Load members into the new select
        $.post(site_url + 'service/report/records/get_member_list', {}, function (res) {
            const $lastSelect = $('.member-select:last');
            $lastSelect.append(res.options);

            // Setup change handler to fetch currency
            $lastSelect.on('change', function () {
                const member_id = $(this).val();
                let service_id = $('#finance_id').val();
                const $row = $(this).closest('tr');
                $row.find('.member-id-field').val(member_id);

                // Now fetch currency based on member's country
                $.post(site_url + 'service/report/records/get_member_currency', { member_id: member_id }, function (res) {
                    if (res.status && res.currency_code) {
                        const $currency = $row.find('.currency-select');
                        $currency.html(`
                            <option value="0">ESPees</option>
                            <option value="${res.currency_code}">${res.currency_name}</option>
                        `);
                    }
                }, 'json');
            });
        }, 'json');
    });
    

    // ========== Set Member ID on Dropdown Change ==========
    $(document).on('change', '.member-select', function () {
        const member_id = $(this).val();
        $(this).closest('tr').find('.member-id-field').val(member_id);
    });

    // ========== Auto-Save Finance Input ==========
    let financeTimeout;

    $(document).on('input keyup keypress blur', '.finance-field', function () {
        clearTimeout(financeTimeout); // Clear any existing timeout

        const $input = $(this); // Cache the input
        financeTimeout = setTimeout(function () {
            const $row = $input.closest('tr');
            const member_id = $row.find('.member-id-field').val();
            const field = $input.data('field');
            const report_id = $('#finance_id').val();
            const user_type = $input.data('user-type') || 'member';
            const currency = $row.find('.currency-select').val();
            const $msgBox = $input.next('.msg-box');

            let val = $input.val();

            // ✅ Sanitize: remove all except digits and dot
            val = val.replace(/[^0-9.]/g, '');

            // ✅ Limit to one dot
            const parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts[1];
            }

            // ✅ Limit to 2 decimal places
            if (parts.length === 2) {
                parts[1] = parts[1].substring(0, 2);
                val = parts[0] + '.' + parts[1];
            }

            // ✅ Update input value
            $input.val(val);

            if (!member_id) {
                alert('Please select a member before entering values.');
                $input.val('');
                return;
            }

            if (!field || val === '') return;

            // ✅ Save to backend
            $.post(site_url + 'service/report/records/save_finance_field', {
                member_id: member_id,
                field_name: field,
                amount: val,
                report_id: report_id,
                user_type: user_type,
                currency: currency
            }, function (res) {
                if (res.status) {
                    $msgBox.text('✓ Saved').fadeIn();
    
                    setTimeout(() => {
                        $msgBox.fadeOut();
                    }, 2000);
                } else {
                    $msgBox.text('✖ Failed').removeClass('text-success').addClass('text-danger').fadeIn();
    
                    setTimeout(() => {
                        $msgBox.fadeOut().removeClass('text-danger').addClass('text-success');
                    }, 2000);
                }
            }, 'json');

        }, 1000); // Delay 1 second after last input
    });

    
    
    // ========== Remove Member Row ==========
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });
    $(document).on('click', '.remove-row', function () {
        const $row = $(this).closest('tr');
        const member_id = $row.find('.member-id-field').val();
        const report_id = $('#finance_id').val();
    
        if (!member_id || !report_id) {
            alert('Invalid member or report ID.');
            return;
        }
    
        if (!confirm('Are you sure you want to delete this finance record?')) return;
    
        $.post(site_url + 'service/report/records/delete_finance_record', {
            member_id: member_id,
            report_id: report_id
        }, function (res) {
            if (res.status) {
                $row.remove();
                console.log('Finance record deleted and report updated.');
                finance_report(report_id);
            } else {
                alert('Delete failed: ' + res.message);
            }
        }, 'json');
    });
    

    function restrictNumericInput() {
        let val = $(this).val();
    
        // Match allowed pattern: digits, optional dot, up to 2 decimals
        const match = val.match(/^\d*\.?\d{0,2}/);
    
        // Apply matched portion (or empty if nothing valid)
        $(this).val(match ? match[0] : '');
    }
    
    $(document).on('input', '.finance-field', function () {
        let val = $(this).val();
    
        // Sanitize: allow only digits and a single decimal point with 2 decimal places
        const match = val.match(/^\d*\.?\d{0,2}/);
        val = match ? match[0] : '';
        $(this).val(val);
    
        const $row = $(this).closest('tr');
        const field = $(this).data('field');
        if (!field) return;
    
        // 🔄 Update per-member row total
        let rowTotal = 0;
        $row.find('.finance-field').each(function () {
            rowTotal += parseFloat($(this).val()) || 0;
        });
        $row.find('.row-total').val(rowTotal.toFixed(2));
    
        // 🔢 If it's a partner_* field, treat it as 'partnership'
        const isPartner = field.startsWith('partner_');
        const mainField = isPartner ? 'partnership' : field;
    
        // 🔁 Sum all same-type fields across all rows → MEMBER total
        let memberSum = 0;
        if (isPartner) {
            $('[data-field^="partner_"]').each(function () {
                memberSum += parseFloat($(this).val()) || 0;
            });
        } else {
            $(`.finance-field[data-field="${field}"]`).each(function () {
                memberSum += parseFloat($(this).val()) || 0;
            });
        }
    
        // 📝 Update member field
        $(`#member_${mainField}`).val(memberSum.toFixed(2));
    
        // ➕ Add member to guest to get total
        const guestVal = parseFloat($(`#guest_${mainField}`).val()) || 0;
        const total = memberSum + guestVal;
    
        $(`#total_${mainField}`).val(total.toFixed(2));
    });
    
    