<?php
use App\Models\Crud;

$this->Crud = new Crud();
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
            <h3><b>Are you sure?</b></h3>
            <input type="hidden" name="d_id" value="<?php if (!empty($d_id)) {
                echo $d_id;
            } ?>" />
        </div>

        <div class="col-sm-12 text-center">
            <button class="btn btn-danger text-uppercase" type="submit">
                <i class="icon ni ni-trash"></i> Yes - Delete
            </button>
        </div>
    </div>
<?php } ?>

<?php if ($param2 == 'view') { ?>
    <div class="row gy-3 py-1">
         <!-- Event Name -->
        <div class="col-sm-12 mb-3">
            <h6 class="overline-title">Event Name</h6>
            <p id="preview-event-name"><?= ucwords($e_name); ?></p>
        </div>

        <!-- Start Time -->
        <div class="col-sm-6 mb-3">
            <h6 class="overline-title">Start Time</h6>
            <p id="preview-event-start"><?= $start_time ? date('h:iA',strtotime($start_time)) : 'Not specified'; ?></p>
        </div>

        <!-- End Time -->
        <div class="col-sm-6 mb-3">
            <h6 class="overline-title">End Time</h6>
            <p id="preview-event-end"><?= $end_time ? date('h:iA',strtotime($end_time)) : 'Not specified'; ?></p>
        </div>
        
        <div class="col-sm-6 mb-3">
            <h6 class="overline-title">Time Zone</h6>
            <?php
                // Define the array of time zones (name => value)
                $timeZones = [
                    "EST" => "Eastern Standard Time (EST)",
                    "CST" => "Central Standard Time (CST)",
                    "MST" => "Mountain Standard Time (MST)",
                    "PST" => "Pacific Standard Time (PST)",
                    "AKST" => "Alaska Standard Time (AKST)"
                ];
            ?>
            <p id="preview-event-reminder">
                <?php
                // Check if the time_zone is set and exists in the array, then display the full meaning
                if (!empty($time_zone) && isset($timeZones[$time_zone])) {
                    echo $timeZones[$time_zone];  // Show the full meaning of the selected time zone
                } else {
                    echo '';  // Display an empty string if no valid time zone is selected
                }
                ?>
            </p>
        </div>

        <!-- Church -->
        <div class="col-sm-5 mb-3">
            <h6 class="overline-title">Church</h6>
            <p id="preview-event-church"><?= ucwords($this->Crud->read_field('id', $church_idz, 'church', 'name')); ?></p>
        </div>


        <!-- Prayer Title -->
        <div class="col-sm-12 mb-3">
            <h6 class="overline-title">Prayer Title</h6>
            <p id="preview-event-prayer-title"><?= ucwords($prayer_title); ?></p>
        </div>

        <!-- Prayer Description -->
        <div class="col-sm-12 mb-3">
            <h6 class="overline-title">Prayer Point</h6>
            <p id="preview-event-prayer"><?= $prayer ? $prayer : 'No description provided'; ?></p>
        </div>

    </div>
<?php } ?>

<?php echo form_close(); ?>

<?php echo form_open_multipart('prayer/index/manage/join', array('id' => 'bb_ajax_form2', 'class' => '')); ?>
<?php if ($param2 == 'join') { ?>
    <input type="hidden" name="room_name" value="<?= isset($e_link['room_name']) ? $e_link['room_name'] : ''; ?>" >
    <input type="hidden" name="link" value="<?= isset($e_link['room_link']) ? $e_link['room_link'] : ''; ?>" >

    
    <div class="row py-1">
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label class="form-label" for="name">Name</label>
                <input class="form-control" type="text" id="name" name="name" value="" required>
            </div>
        </div>
        

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label  class="form-label">Church</label>
                <select class="js-select2" data-search="on" name="church_id" id="church_id" required>
                    <option value="">Select</option>
                    <?php 
                        if(!empty($e_churches)){
                            foreach($e_churches as $ch){

                                echo '<option value="'.$ch.'">'.ucwords($this->Crud->read_field('id', $ch, 'church', 'name')).'</option>';

                            }
                        }
                    ?>
                </select>
            </div>
        </div>


        <div class="col-sm-12 text-center">
            <hr />
            <button class="btn btn-primary bb_for_btn" id="bt" type="submit">
                <i class="icon ni ni-save"></i> Save
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div id="bb_ajax_msg2"></div>
        </div>
    </div>
<?php } ?>

<?php echo form_close(); ?>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>

<script>
    
    $(function () {
        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd', // Set the date format
            autoclose: true
        });

        $('.input-daterange').datepicker({
            format: 'yyyy-mm-dd',  // Format for the date
            autoclose: true,       // Automatically close the picker when a date is selected
            todayHighlight: true,  // Highlight today's date
        });

        $('#summernote').summernote({
            height: 300, // Set the height of the editor
            tabsize: 2,
            focus: true
        });

       
    });

    $('input[name="recurrence_end"]').on('change', function () {
        var selected = $(this).val();
        if (selected === 'after') {
            $('#occurrences').show(500);
            $('#end_date').hide(500);
            $('.recurring_option').show(500);
        } else if (selected === 'by') {
            $('#occurrences').hide(500);
            $('#end_date').show(500);
            $('.recurring_option').show(500);
        } else {
            $('#occurrences').hide(500);
            $('#end_date').hide(500);
            $('.recurring_option').hide(500);
        }
    });
    
    $('.recurring_option').hide(500);
    // Initially hide the fields for occurrences and end date
    $('#occurrences').hide(500);
    $('#end_date').hide(500);
    $('input[name="recurrence_end"]:checked').trigger('change');
    $('#is_recurring').change(function () {
        if ($(this).val() == '1') {
            $('.recurring_options').show(500);
        } else {
            $('.recurring_options').hide(500);
        }
    });
    $('#is_recurring').trigger('change');
    $('#category_ids').change(function () {
        if ($(this).val() == 'new') {
            console.log($(this).val());
            $('#category_resp').show(500);
        } else {
            $('#category_resp').hide(500);
        }
    });
    $('#frequency').change(function () {
        if ($(this).val() == 'weekly') {
            $('#weekly_days').show(500);
        } else {
            $('#weekly_days').hide(500);
        }
    });
    $('#frequency').trigger('change');
    
    
    var site_url = '<?php echo site_url(); ?>';

    $(document).ready(function () {
        $('.time-picker').timepicker({});
        <?php
            $e_church_ids = !empty($e_church_id) ? json_encode($e_church_id) : '[]';
            $e_member_ids = !empty($e_member_id) ? json_encode($e_member_id) : '[]';
            
        ?>
        var eChurchId = <?php echo $e_church_ids; ?>;
        var eMemberId = <?php echo $e_member_ids; ?>;

  
        if (typeof eChurchId === 'string') {
            eChurchId = JSON.parse(eChurchId); // Parse JSON string to array
        }
        if (typeof eMemberId === 'string') {
            eMemberId = JSON.parse(eMemberId); // Parse JSON string to array
        }
        // Function to load churches based on selected ministry ID and/or level
        function loadChurches(ministryId, level) {
            // Clear the Church dropdown
            $('#church_id').empty();
            $('#church_id').append(new Option('Loading...', '', false, false));

            // Construct data object based on provided parameters
            var data = {};
            if (ministryId) {
                data.ministry_id = ministryId;
            }
            if (level) {
                data.level = level;
            }

            // Proceed if there's data to be sent
            if (Object.keys(data).length > 0) {
                $.ajax({
                    url: site_url + 'ministry/announcement/get_church', // Update this to the path of your API endpoint
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function (response) {
                        $('#church_id').empty(); // Clear 'Loading...' option
                        $('#church_id').append(new Option('Select Church', '', false, false));

                        if (response.success) {

                            // Populate the Church dropdown with the data received
                            $.each(response.data, function (index, church) {
                                var selected = '';
                                if (church.id === eChurchId) {
                                    selected = 'selected';
                                }
                                var churchName = toTitleCase(church.name); // Convert name to title case
                                var churchType = toTitleCase(church.type); // Convert type to title case
                                $('#church_id').append(new Option(churchName + ' - ' + churchType, church.id, selected, selected));
                            });
                            var selectedChurchId = $('#church_id').val();
                            loadMembers(selectedChurchId); // Load members when a church is selected
                        
                        } else {
                            $('#church_id').append(new Option('No churches available', '', false, false));
                        }
                    },
                    error: function () {
                        $('#church_id').append(new Option('Error fetching churches', '', false, false));
                    }
                });
            } else {
                $('#church_id').append(new Option('Please select a ministry or level', '', false, false));
            }
        }

         // Auto-load churches if ministry_id or level is already set
        var ministryId = $('#ministry_id').val();
        var initialLevel = $('#level').val();
        if (ministryId || initialLevel) {
            // console.log(initialLevel);
            if (initialLevel !== ' ') {
                if (initialLevel === 'all') {
                    $('#church_div').hide(600);
                    $('#send_resp').hide(600); // Hide the Church dropdown
                } else {
                    $('#send_resp').show(600);
                    $('#church_div').show(600); // Show the Church dropdown
                }
            } else {
                $('#church_div').hide(600);
                $('#send_resp').hide(600);

            }

            loadChurches(ministryId, initialLevel);
        }

        // Load churches on ministry selection change
        $('#ministry_id').change(function () {
            var selectedMinistryId = $(this).val();
            var selectedLevel = $('#level').val();
            loadChurches(selectedMinistryId, selectedLevel);
        });

        // Handle the change event of the Church Level dropdown
        $('#level').change(function () {
            var selectedLevel = $(this).val();
            var selectedMinistryId = $('#ministry_id').val();

            if (selectedLevel === 'all' || selectedLevel === ' ') {
               
            } else {
                loadChurches(selectedMinistryId, selectedLevel); // Load churches based on selected level
            }
        });

                // Function to load members based on selected church ID
        function loadMembers(churchId) {
            // Clear the Member dropdown
            $('#member_id').empty();
            $('#member_id').append(new Option('Loading...', '', false, false)).trigger('change');;

            // Proceed only if a churchId is provided
            if (churchId) {
                $.ajax({
                    url: site_url + 'ministry/announcement/get_members', // Update this to the path of your API endpoint
                    type: 'POST',
                    dataType: 'json',
                    data: { church_id: churchId }, // Send the selected church ID
                    success: function (response) {
                        $('#member_id').empty(); // Clear 'Loading...' option
                        $('#member_id').append(new Option('Selec Member', '', false, false));

                        if (response.success) {
                            // Populate the Member dropdown with the data received
                            $.each(response.data, function (index, member) {
                                
                                var selected = eMemberId.includes(member.id); // Pre-select if necessary
                                var memberName = toTitleCase(member.name);
                                var memberPhone = member.phone || 'N/A';      // Show phone number or 'N/A' if missing
                                // Append the member's name and phone number to the select box
                                $('#member_id').append(new Option(memberName + ' (' + memberPhone + ')', member.id, selected, selected));

                            });
                        } else {
                            $('#member_id').append(new Option('No members available', '', false, false)).trigger('change');;
                        }
                    },
                    error: function () {
                        $('#member_id').append(new Option('Error fetching members', '', false, false)).trigger('change');;
                    }
                });
            } else {
                $('#member_id').append(new Option('Please select a church', '', false, false)).trigger('change');;
            }
        }

        // Helper function to convert strings to title case
        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function (txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        // Example: You might call loadMembers when the church dropdown changes
        $('#church_id').change(function() {
            var selectedChurchId = $(this).val();
            loadMembers(selectedChurchId); // Load members when a church is selected
        });


    });

</script>