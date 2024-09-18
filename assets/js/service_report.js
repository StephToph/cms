
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
        $('#show').show(500);
        $('#form').hide(500);
        $('#attendance_view').hide(500);
        $('#tithe_view').hide(500);
        $('#new_convert_view').hide(500);
        $('#first_timer_view').hide(500);
        $('#attendance_prev').hide(500);
        $('#add_btn').show(500);
         // Reset row count and clear rows
         rowCount = 0;first_timer_count=0;
         $('#guest_part_view').hide(500);
         $('#guest_partner_list').empty();
            
        $('#partnership_view').hide(500);
        $('#rowsContainer').empty(); $('#containers').empty();
        $('#prev').hide(500);

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



    function attendance_report(id){
        $('#attendance_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#attendance_view').show(500);
        $('#attendance_prev').show(500);
        
        $.ajax({
            url: site_url + 'service/report/manage/attendance/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#attendance_id').val(dt.attendance_id)
                $('#total_attendance').val(dt.total_attendance);
                $("#member_attendance").val(dt.member_attendance);
                $("#guest_attendance").val(dt.guest_attendance);
                $('#male_attendance').val(dt.male_attendance);
                $('#female_attendance').val(dt.female_attendance);
                $('#children_attendance').val(dt.children_attendance);

                $('#attendance_msg').html(''); 
                
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

                var tableSelector = '#tithe_table'; // Replace with your table's CSS selector
                var ajaxUrl = site_url + 'service/report/tithe_list'; // Replace with your AJAX URL
               
                generateTable(tableSelector, ajaxUrl, [
                    { title: 'Member' },
                    { title: 'Tithe' },
                  ]);
                  $('#tithe_pagination').show(500);
                $('#tithe_msg').html('');
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
                    <input class="form-control" type="text" id="first_name_${rowCount}" name="first_name[]" ${record ? 'value="' + (record.fullname.split(' ')[0] || '') + '"' : ''} required>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="surname_${rowCount}">*Surname</label>
                    <input class="form-control" type="text" id="surname_${rowCount}" name="surname[]" ${record ? 'value="' + (record.fullname.split(' ')[1] || '') + '"' : ''} required>
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
                    <input class="form-control" type="text" id="phone_${rowCount}" name="phone[]" ${record ? 'value="' + (record.phone || '') + '"' : ''} required>
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
        
        $.ajax({
            url: site_url + 'service/report/manage/first_timer/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#first_timer_id').val(dt.id)

                // Parse the convert_list JSON string
                var existingRecords = JSON.parse(dt.timer_list);
                console.log(existingRecords);
                
                if(existingRecords.length > 0){
                    timerRecords(existingRecords);
                }
                
                $('#first_timer_msg').html('');
            }
        });
       
    }

    function partnership_report(id){
        $('#partnership_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#show').hide(500);
        $('#add_btn').hide(500);
        $('#partnership_view').show(500);
        $('#attendance_prev').show(500);
        
        $.ajax({
            url: site_url + 'service/report/manage/partnership/' + id,
            type: 'get',
            success: function (data) {
                var dt = JSON.parse(data);
                $('#partnership_id').val(dt.id)
                $('#total_part').val(dt.total_part)
                $('#member_part').val(dt.member_part)
                $('#guest_part').val(dt.guest_part)

                fetchAndPopulateFirstTimers(dt.id);
                
                $('#partnership_msg').html('');
            }
        });
       
    }
    
    function fetchAndPopulateFirstTimers(id) {
        $.ajax({
            url: site_url + 'service/report/records/getFirstTimers/'+id, // Adjust the URL according to your API
            type: 'get',
            success: function (data) {
                var firstTimers = JSON.parse(data); // Assuming the response is JSON formatted
    
                // Clear existing entries
                $('#guest_partner_list').empty();
            
                // Check if there are any first timers
                if (firstTimers.length === 0) {
                    
                    $('#guest_part_view').hide(500);
                    $('#guest_partner_list').html('<tr><td colspan="8" class="text-center">No first timers available</td></tr>');
                    return;
                }
                
                $('#guest_part_view').show(500);
                // Iterate over firstTimers and append rows to the table
                firstTimers.forEach(function(timer) {
                    var row = '<tr class="original-row">';
                    // Check if fullname is defined and convert to uppercase
                    var fullname = timer.id ? timer.id.toUpperCase() : '';
                    var phone = timer.phone ? timer.phone : '';
                  
                    row += '<td><input type="hidden" readonly class="form-control firsts" name="first_timer[]" value="' + fullname + ' - ' + phone + '"><span class="small">' + fullname + ' - ' + phone + '</span></td>';
                    
                    if(fullname){
                        $.ajax({
                            url: site_url + 'service/report/records/get_service_partnership/'+id,
                            method: 'post',
                            data: { name: fullname },
                            success: function(data) {
                                var partners = JSON.parse(data); // Assuming the response is JSON formatted
                               
                                if (partners) {
                                    partners.forEach(function(partner) {
                                        row += '<td><input type="text" style="width:100px;"  class="form-control firsts_amount" name="' + partner.id + '_first[]" oninput="bindInputEvents();" value="' + partner.amount + '"> </td>'; // Contribution amount
                                    });
                                }
                                
                                row += '</tr>';
                                 // Now append the row to the table after the AJAX call is successful
                                $('#guest_partner_list').append(row);
                            }
                        });
                    } else{
                        row += '</tr>';
                         // Now append the row to the table after the AJAX call is successful
                        $('#guest_partner_list').append(row);
                    }

                    
                });
                $('.js-select2 ').select2();
            },
            error: function (xhr, status, error) {
                console.error('Error fetching first timers:', error);
                $('#guest_partner_list').html('<tr><td colspan="8" class="text-center">Failed to load first timers.</td></tr>');
            }
        });
    }
    
    function bindInputEvents() {
        $('.members_amount').on('input', calculateSum);
        $('.members_amount').on('input', restrictNumericInput);
        $('.firsts_amount').on('input', calculateFirst);
        $('.firsts_amount').on('input', restrictNumericInput);
    }

    // Function to allow only numeric input with up to two decimal places
    function restrictNumericInput() {
        // Replace any non-numeric characters (except decimal point) with an empty string
        $(this).val($(this).val().replace(/[^0-9.]/g, ''));

        // Allow only one decimal point
        var val = $(this).val();
        var parts = val.split('.');
        if (parts.length > 2) {
            parts.pop();
            $(this).val(parts.join('.'));
        }
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


    
    
    function timerRecords(records) {
        records.forEach(record => {
            $('#containers').append(createNewSection(record));
            $('select[name="invited_by[]"]').on('change', handleInvitedByChange);
              // Execute the function immediately to handle any initial state
               // Select the most recently added select element
            const $selectElement = $('select[name="invited_by[]"]').last();

            // Attach the event handler for the change event
            $selectElement.on('change', handleInvitedByChange);

            handleInvitedByChange.call($selectElement[0]);
        });
    }
     // Initialize row count
     let first_timer_count = 0;

     // Container where new form sections will be appended
     const container = $('#containers'); // Adjust this selector to your actual container
 
     // Function to create a new form section with values
     function createNewSection(values) {
         // Increment row count
         first_timer_count++;
 
         // Determine whether to show the delete button
        const showDeleteButton = first_timer_count > 1;
        var surname = '';
        var firstName = '';
        // Check if user object exists and has a non-empty fullname
        if (values && values.fullname) {
            // Destructure and split the fullname in one line
            [surname, ...firstNameParts] = values.fullname.split(' ');
            firstName = firstNameParts.join(' '); // Join the rest back to a string
        }
     
         // Create new form section HTML with values
         return `
             <div class="row border mb-3 p-2" id="row-${first_timer_count}">
                 <div class="col-sm-4 mb-3">
                     <div class="form-group">
                         <label for="first_name_${first_timer_count}">*First Name</label>
                         <input class="form-control" type="text" id="first_name_${first_timer_count}" name="first_name[]" value="${firstName}" required>
                     </div>
                 </div>
                 <div class="col-sm-4 mb-3">
                     <div class="form-group">
                         <label for="surname_${first_timer_count}">*Surname</label>
                         <input class="form-control" type="text" id="surname_${first_timer_count}" name="surname[]" value="${surname}" required>
                     </div>
                 </div>
                 <div class="col-sm-4 mb-3">
                     <div class="form-group">
                         <label for="email_${first_timer_count}">Email</label>
                         <input class="form-control" type="email" id="email_${first_timer_count}" name="email[]" value="${values.email || ''}">
                     </div>
                 </div>
                 <div class="col-sm-4 mb-3">
                     <div class="form-group">
                         <label for="phone_${first_timer_count}">*Phone</label>
                         <input class="form-control" type="text" id="phone_${first_timer_count}" name="phone[]" value="${values.phone || ''}" required>
                     </div>
                 </div>
                 <div class="col-sm-4 mb-3">
                     <div class="form-group">
                         <label for="gender_${first_timer_count}">Gender</label>
                         <div class="form-control-wrap">
                             <select class="form-select js-select2" id="gender_${first_timer_count}" name="gender[]" required>
                                 <option value="">Select Gender</option>
                                 <option value="Male" ${values.gender === 'Male' ? 'selected' : ''}>Male</option>
                                 <option value="Female" ${values.gender === 'Female' ? 'selected' : ''}>Female</option>
                             </select>
                         </div>
                     </div>
                 </div>
                 <div class="col-sm-4 mb-3">
                     <div class="form-group">
                         <label for="family_position_${first_timer_count}">Family Position</label>
                         <div class="form-control-wrap">
                             <select class="form-select js-select2" id="family_position_${first_timer_count}" name="family_position[]">
                                 <option value="">Select</option>
                                 <option value="Child" ${values.family_position === 'Child' ? 'selected' : ''}>Child</option>
                                 <option value="Parent" ${values.family_position === 'Parent' ? 'selected' : ''}>Parent</option>
                                 <option value="Other" ${values.family_position === 'Other' ? 'selected' : ''}>Other</option>
                             </select>
                         </div>
                     </div>
                 </div>
                 <div class="col-sm-4 mb-3">
                     <div class="form-group">
                         <label for="dob_${first_timer_count}">Date of Birth</label>
                         <input class="form-control" type="date" id="dob_${first_timer_count}" name="dob[]" value="${values.dob || ''}">
                     </div>
                 </div>
                 <div class="col-sm-4 mb-3">
                     <div class="form-group">
                         <label for="invited_by_${first_timer_count}">*Invited By</label>
                         <select class="form-select invited_bys js-select2" id="invited_by_${first_timer_count}" name="invited_by[]" required>
                             <option value="">Select</option>
                             <option value="Member" ${values.invited_by === 'Member' ? 'selected' : ''}>Member</option>
                             <option value="Online" ${values.invited_by === 'Online' ? 'selected' : ''}>Online</option>
                             <option value="Others" ${values.invited_by === 'Others' ? 'selected' : ''}>Others</option>
                         </select>
                     </div>
                 </div>
                 <div class="col-sm-4 mb-3 channel-div" id="channel-div-${first_timer_count}" style="${values.invited_by === 'Others' ? '' : 'display: none;'}">
                     <div class="form-group">
                         <label for="channel_${first_timer_count}">Other Channel</label>
                         <input class="form-control" type="text" id="channel_${first_timer_count}" name="channel[]" value="${values.channel || ''}">
                     </div>
                 </div>
                 <div class="col-sm-4 mb-3 member-div" id="member-div-${first_timer_count}" style="${values.invited_by === 'Member' ? '' : 'display: none;'}">
                     <div class="form-group">
                         <label for="member_${first_timer_count}">Member</label>
                         <select class="form-select js-select2 member_id" id="member_${first_timer_count}" name="member_id[]">
                             <option value="">Select Member</option>
                             <!-- Add PHP or dynamic content here if needed -->
                         </select>
                     </div>
                 </div>
                 <div class="col-sm-12 mb-3 text-center">
                     ${showDeleteButton ? `<button type="button" class="btn btn-danger btn-delete" data-row="${first_timer_count}">Delete</button>` : ''}
                 </div>
             </div>
         `;
     }
 
     // Click event to add more form sections
     $('#add_first_timer').on('click', function() {
         // Create a new empty section
         container.append(createNewSection({}));
        $('.js-select2').select2();
         
        $('select[name="invited_by[]"]').on('change', handleInvitedByChange);
    
    
     });

     function handleInvitedByChange(event) {
        
        const invitedByValue = $(this).val();
        console.log(invitedByValue);
        const parent = $(this).closest('.row'); // Change '.parent-class' to the actual parent class
        const channelDiv = parent.find('.channel-div');
        const memberDiv = parent.find('.member-div');
        const memberSelect = parent.find('.member_id');
        var service_id = $('#first_timer_id').val();

        if (invitedByValue === 'Member') {
            memberDiv.show(500);
            channelDiv.hide(500);
             // AJAX call to fetch members
            $.ajax({
                url: site_url + 'service/report/records/get_church/'+service_id, // Update with your API endpoint
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Clear previous options
                    memberSelect.empty().append('<option value="">Select Member</option>');

                    // Populate the member dropdown
                    $.each(data, function(index, member) {
                        const selected = member.selected === "selected" ? ' selected' : '';
                        memberSelect.append(`<option value="${member.id}"${selected}>${member.name}</option>`);
                    });

                    // Re-initialize the select2 (if using select2)
                    memberSelect.select2();
                }
            });
        } else if (invitedByValue === 'Others' ||  invitedByValue === 'Online') {
            channelDiv.show(500);
            memberDiv.hide(500);
        } else {
            channelDiv.hide(500);
            memberDiv.hide(500);
        }
    }
    
    function deleteSection(rowId) {
        $(`#row-${rowId}`).remove(500); // Remove the row from the DOM
        first_timer_count--; // Decrement the row count
    }
    
    // Event listener to handle delete button clicks using jQuery
    $(document).on('click', '.btn-delete', function() {
        const rowId = $(this).data('row'); // Get the row ID from data attribute
        deleteSection(rowId);
    });

    function get_tithe(){
        var member = $('#member_tithe').val();
        var guest = $('#guest_tithe').val();
        
        var total = parseFloat(member) + parseFloat(guest);
        total = total.toFixed(2);
        $('#total_tithe').val(total);
    }

    function updateTotals() {
        // Get values from the input fields
        var memberValue = parseInt($('#member_attendance').val()) || 0;
        var guestValue = parseInt($('#guest_attendance').val()) || 0;
        var maleValue = parseInt($('#male_attendance').val()) || 0;
        var femaleValue = parseInt($('#female_attendance').val()) || 0;
        var childrenValue = parseInt($('#children_attendance').val()) || 0;

        // Calculate the total
        var total = memberValue + guestValue + maleValue + femaleValue + childrenValue;

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
            $('#load_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#loadmore').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

       
        var search = $('#search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'service/report/load' + methods,
            type: 'post',
            data: { search: search },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_data').html(dt.item);
                } else {
                    $('#load_data').append(dt.item);
                }
                $('#counta').html(dt.count);
                if (dt.offset > 0) {
                    $('#loadmore').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
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

        $('#first_timer_Form').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = $(this).serialize(); // Serialize form data
            $('#first_timer_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            // Send an AJAX POST request
            $.ajax({
                url: site_url + 'service/report/manage/first_timer', // Replace with your server URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle a successful response
                    $('#first_timer_msg').html(response);
                    first_timer_count = 0;

                    $('#containers').empty();
                }
            });
        });

    });

    

    function calculateTotal() {
        
        var tithesInputs = document.querySelectorAll('.tithes');
        var total = 0;
        tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            total += isNaN(value) ? 0 : value;
        });
        console.log(total);
        var guest = $('#guest_tithe').val();
        
        $('#member_tithe').val(total.toFixed(2));
        total += parseFloat(guest);
        total = total.toFixed(2);
        $('#total_tithe').val(total);

        // Set value to 0 if the textbox is empty
        tithesInputs.forEach(function(input) {
            if (input.value === '') {
                input.value = '';
            }
        });
    }

    
    function generateTable(tableSelector, url, columns) {
        // Configuration
        const tableBodySelector = `${tableSelector} tbody`;
        const paginationContainerSelector = `${tableSelector}-pagination`;
        const prevButtonSelector = `${tableSelector}-prev-button`;
        const nextButtonSelector = `${tableSelector}-next-button`;
        const pageInfoSelector = `${tableSelector}-page-info`;
        const rowsPerPage = 50; // Number of rows to display per page
        $(tableBodySelector).html('<div class="col-sm-12 text-center"><i class="icon ni ni-loader" aria-hidden="true"></i> Processing... please wait</div>');
        // Variables to store data and state
        let tableData = [];
        let currentPage = 1;
        let totalPages = 1;
      
        // Function to generate table headers
        function generateTableHeaders(columns) {
          const tableHeader = document.querySelector(`${tableSelector} thead tr`);
          columns.forEach((column) => {
            const th = document.createElement('th');
            th.textContent = column;
            tableHeader.appendChild(th);
          });
        }
      
        function generateTableData(data) {
            const tableBody = document.querySelector(tableBodySelector);
            $(tableBody).html('<div class="col-sm-12 text-center"><i class="icon ni ni-loader" aria-hidden="true"></i> Processing... please wait</div>');
        
            tableBody.innerHTML = ''; // Clear existing rows
            
            data.forEach((row) => {
                const tr = document.createElement('tr');
                row.forEach((cell) => {
                    const td = document.createElement('td');
                    td.innerHTML = cell; // Use innerHTML to insert HTML content
                    tr.appendChild(td);
                });
                tableBody.appendChild(tr);
            });
        }
        
      
        // Function to handle pagination
        function handlePagination() {
          const prevButton = document.querySelector(prevButtonSelector);
          const nextButton = document.querySelector(nextButtonSelector);
          const pageInfo = document.querySelector(pageInfoSelector);
      
          prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
              currentPage--;
              loadTableData();
            }
          });
      
          nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
              currentPage++;
              loadTableData();
            }
          });
      
          function updatePaginationInfo() {
            pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
          }
         
          updatePaginationInfo();
        }
      
        // Function to load table data from AJAX source
        function loadTableData() {
          fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              page: currentPage,
              rowsPerPage: rowsPerPage,
            }),
          })
            .then((response) => response.json())
            .then((data) => {
              tableData = data;
              totalPages = Math.ceil(data.length / rowsPerPage);
              const paginatedData = tableData.slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage);
              generateTableData(paginatedData);
              handlePagination();
             
            })
            .catch((error) => console.error(error));
        }
      
        // Initialize table
        fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            page: 1,
            rowsPerPage: rowsPerPage,
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            tableData = data;
            totalPages = Math.ceil(data.length / rowsPerPage);
            const paginatedData = tableData.slice(0, rowsPerPage);
            generateTableData(paginatedData);
            handlePagination();
          })
          .catch((error) => console.error(error));
      }
    
    