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
                                <h3 class="nk-block-title page-title"><?=ucwords(ucwords($church).' First Timer Form'); ?></h3>
                                
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="row g-gs">
                            <div class="col-md-12">
                                <div class="card card-bordered card-full">
                                    <div class="card-inner-group">
                                        <div class="card-inner">
                                            <div class="card-title-group">
                                                <div class="card-title">
                                                    <h6 class="title my-1"><?=translate_phrase(' First Timer Form');?></h6>
                                                </div>
                                                <div class="card-tools"></a>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row p-3">
                                            <?php echo form_open_multipart('attendance/timer', array('id'=>'bb_ajax_form', 'class'=>'row')); ?>
                                            
                                                <?php if(empty($code) || empty($church_id)) : ?>
                                                   
                                                    <div class="col-sm-3 mb-3" id="church_div" >
                                                        <div class="form-group">
                                                            <label>Church</label>
                                                            <select class="js-select2" data-search="on" name="church_id" id="church_id">
                                                                <option value=" ">Select Church</option>
                                                                <?php
                                                                    $church = $this->Crud->read_order('church', 'name', 'asc');
                                                                    if(!empty($church)){
                                                                        foreach($church as $ch){
                                                                            $country_name = $this->Crud->read_field('id', $ch->country_id, 'country', 'name');
                                                                            echo '<option value="'.$ch->id.'" data-country="'.$country_name.'" data-country-id="'.$ch->country_id.'">'.ucwords($ch->name.' - '.$ch->type).'</option>';
                                                                        }
                                                                    }
                                                                ?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php else : ?>
                                                    <input type="hidden" id="church_id" name="church_id" value="<?=$church_id; ?>">
                                                    
                                                <?php endif ?>
                                            
                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Title</label>
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

                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">*<?=translate_phrase('First Name'); ?></label>
                                                        <input class="form-control" type="text" id="firstname" name="firstname" required >
                                                    </div>
                                                </div>


                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">*<?=translate_phrase('Surame'); ?></label>
                                                        <input class="form-control" type="text" id="surname" name="surname" required>
                                                    </div>
                                                </div>


                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">*<?=translate_phrase('Email');?></label>
                                                        <input class="form-control" type="text" id="email" name="email" value="<?php if(!empty($e_email)) {echo $e_email;} ?>" >
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">*<?=translate_phrase('Phone');?></label>
                                                        <input class="form-control" type="text" id="phone" name="phone" value="<?php if(!empty($e_phone)) {echo $e_phone;} ?>" required>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Gender</label>
                                                        <select class="js-select2" name="gender" id="gender" data-placeholder="Select Gender" required>
                                                            <option value="">Select Gender</option>
                                                            <option value="male" <?php if(!empty($e_gender)){if($e_gender ==  'male'){echo 'selected';}}; ?>>Male</option>
                                                            <option value="female" <?php if(!empty($e_gender)){if($e_gender ==  'female'){echo 'selected';}}; ?>>Female</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 mb-3">
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
                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">*<?=translate_phrase('Address');?></label>
                                                        <input class="form-control" type="text" id="address" name="address"required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">*<?=translate_phrase('City');?></label>
                                                        <input class="form-control" type="text" id="city" name="city"  required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <?php
                                                        $country_id = $this->Crud->read_field('id', $church_id, 'church', 'country_id'); 
                                                        $country = $this->Crud->read_field('id', $country_id, 'country', 'name');
                                                    ?>
                                                    <label for="country" class="form-label fw-bold">Country </label>
                                                    <input type="text" id="country" class="form-control" readonly placeholder="Your country" value="<?=$country; ?>">
                                                    <input type="hidden"  id="country_id" name="country" value="<?=$country_id; ?>">
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label">State</label>
                                                        <select class="js-select2" name="state_id" id="state_id" data-placeholder="Select" >
                                                        <?php

                                                            $ministry = $this->Crud->read_single_order('country_id', $country_id, 'state', 'name', 'asc');
                                                            if (!empty($ministry)) {
                                                                foreach ($ministry as $d) {
                                                                    $sel = '';
                                                                    
                                                                    echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name) . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                                
                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name"><?=translate_phrase('Postal Code');?></label>
                                                        <input class="form-control" type="text" id="postal" name="postal"  >
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Marital Status</label>
                                                        <select class="js-select2" name="marital" id="marital" data-placeholder="Select Marital Status" required>
                                                            <option value="">Select Marital Status</option>
                                                            <option value="married">Married</option>
                                                            <option value="single" >Single</option>
                                                            <option value="widowed">Widowed</option>
                                                            <option value="divorced" >Divorved/Seperated</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 mb-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">*<?=translate_phrase('Occupation');?></label>
                                                        <input class="form-control" type="text" id="occupation" name="occupation"  >
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-3">
                                                    <label class="form-label fw-bold">How did you connect to service?</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="connection" id="inPerson" value="In person">
                                                        <label class="form-check-label" for="inPerson">In person</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="connection" id="online" value="Online">
                                                        <label class="form-check-label" for="online">Online</label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <?php
                                                        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                                                        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                                                        $church = $this->Crud->read_field('id', $church_id, 'church', 'name');
                                                        $ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
                                                        
                                                    ?>
                                                    <label class="form-label fw-bold">Would you consider joining Us?</label>
                                                    <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="joining" id="joinYes" value="Yes">
                                                    <label class="form-check-label" for="joinYes">Yes</label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="joining" id="joinNo" value="No">
                                                    <label class="form-check-label" for="joinNo">No</label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <label class="form-label fw-bold">Are you Baptised by immersion?</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="baptised" id="baptisedYes" value="Yes">
                                                        <label class="form-check-label" for="baptisedYes">Yes</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="baptised" id="baptisedNo" value="No">
                                                        <label class="form-check-label" for="baptisedNo">No</label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <label class="form-label fw-bold">Would you want us to visit you?</label>
                                                    <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="visit" id="visitYes" value="Yes">
                                                    <label class="form-check-label" for="visitYes">Yes</label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="visit" id="visitNo" value="No">
                                                    <label class="form-check-label" for="visitNo">No</label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <label for="visitTime" class="form-label fw-bold">If yes, when is best?</label>
                                                    <input type="text" class="form-control" id="visitTime" name="visit_time" placeholder="Your answer">
                                                </div>
                                                
                                                <div class="col-sm-3 mb-3">
                                                    <label for="name"  class="form-label fw-bold">*<?=translate_phrase('Invited By'); ?></label>
                                                    <select class="js-select2" data-search="on" name="invited_by" id="invited_by" >
                                                        <option value="">Select</option>
                                                        <option value="Member">Member</option>
                                                        <option value="Online">Online</option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-sm-3 mb-3 channel-div related-div"  style="display: none;">
                                                    <label for="name"  class="form-label fw-bold"><?=translate_phrase('Channel'); ?></label>

                                                    <div class="platform-select-wrap" style="display: none;">
                                                        <!-- Platform SELECT (for Online) -->
                                                        <select class="js-select2" data-search="on" name="platform" id="platform" style="display: none;">
                                                            <option value="">Select Platform</option>
                                                            <option value="Facebook">Facebook</option>
                                                            <option value="Instagram">Instagram</option>
                                                            <option value="YouTube">YouTube</option>
                                                            <option value="WhatsApp">WhatsApp</option>
                                                            <option value="Email Newsletter">Email Newsletter</option>
                                                            <option value="Direct Mail/Postcard">Direct Mail/Postcard</option>
                                                            <option value="Event/Conference">Event/Conference</option>
                                                            <option value="Podcast">Podcast</option>
                                                            <option value="LinkedIn">LinkedIn</option>
                                                            <option value="Twitter/X">Twitter/X</option>
                                                            <option value="Tiktok">Tiktok</option>
                                                            <option value="Our Website">Our Website</option>
                                                            <option value="Google Search ">Google Search </option>
                                                            <option value="TV">TV</option>
                                                            <option value="Radio ">Radio </option>
                                                        </select>

                                                    </div>
                                                    
                                                    <div class="channel-input-wrap" style="display: none;">
                                                        <input class="form-control" type="text" id="channel" name="channel" placeholder="Enter referral source">
                                                    </div>
                                                </div>

                                                <div class="col-sm-3 mb-3 member-div related-div"  style="display: none;">
                                                    
                                                    <label for="name"  class="form-label fw-bold"><?=translate_phrase('Member'); ?></label>
                                                    <select class="form-select js-select2" data-search="on" id="member_id" name="member_id">
                                                        <option value="">Select Member</option>
                                                        <?php 
                                                            $roles_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
                                                            $mem = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1,  'user', 'firstname', 'asc');
                                                                if(!empty($mem)){
                                                                    foreach($mem as $m){
                                                                        echo '<option value="'.$m->id.'">'.ucwords($m->firstname.' '.$m->surname).'</option>';
                                                                    }
                                                                }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-sm-3">
                                                <?php 
                                                    $service_count = $this->Crud->check3('status', 0, 'date', date('Y-m-d'), 'church_id', $church_id, 'service_report');
                                                    if($service_count > 1){
                                                        if($attend_type == 'cell'){
                                                            echo  '<label for="name"  class="form-label fw-bold">Service Number</label>
                                                                <select class="js-select2" data-search="on" name="service"  id="service_select" >';
                                                                    for ($i=0; $i < $service_count; $i++) { 
                                                                        echo '<option value="'.($i+1).'">Service '.($i+1).'</option>';
                                                                    }

                                                                echo '</select>
                                                            ';
                                                        } else {
                                                            echo  '<label for="name"  class="form-label fw-bold">Service Number</label>
                                                                <select class="js-select2" data-search="on" name="service" id="service">';
                                                                    for ($i=0; $i < $service_count; $i++) { 
                                                                        echo '<option value="'.($i+1).'">Service '.($i+1).'</option>';
                                                                    }

                                                                echo '</select>
                                                            ';
                                                        }
                                                    
                                                } else{
                                                    echo '<input type="hidden" name="service" id="service" value="1">
                                                    <input type="hidden" name="service" id="service_select" value="1">';
                                                }
                                                ?>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="prayerRequest" class="form-label fw-bold">Do you have a prayer request? If yes write them below:</label>
                                                    <textarea class="form-control" id="prayerRequest" name="prayer_request" rows="3" placeholder="Your answer"></textarea>
                                                </div>

                                                <div class="col-sm-12 text-center">
                                                    <hr />
                                                    <button class="btn btn-primary bb_form_bt" type="submit">
                                                        <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                                                    </button>
                                                </div>
            
                                            
                                            </form>
                                            <div class="col-12 my-2 text-center" id="bb_ajax_msg"></div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script>
    $(document).ready(function () {
        $('#church_id').on('change', function() {
            let countryId = $(this).find(':selected').data('country-id');
            let country = $(this).find(':selected').data('country');
            $('#country_id').val(countryId);
            $('#country').val(country);
            get_state(countryId);
        });
        $('#invited_by').on('change', function () {
            var selectedOption = $(this).val();

            // Hide all related divs and channel/platform wrappers
            $('.related-div').hide(300);
            $('.platform-select-wrap').hide(300);
            $('.channel-input-wrap').hide(300);

            if (selectedOption === "Member") {
                $('.member-div').show(300);
            } else if (selectedOption === "Online") {
                $('.channel-div').show(300);
                $('.platform-select-wrap').show(300);
            } else if (selectedOption === "Others") {
                $('.channel-div').show(300);
                $('.channel-input-wrap').show(300);
            }
        });
    });


    function get_state(country){
        $.ajax({
            url: site_url + 'attendance/get_state/'+country, // Update this to the path of your API endpoint
            type: 'get',
            success: function (response) {
                const $churchDropdown = $('#state_id');
                $churchDropdown.empty(); // Clear existing options

                if (response) {
                    $churchDropdown.append(response);
                } else {
                    $churchDropdown.append(new Option('No churches available', '', false, false));
                }
            },

            error: function () {
                $('#church_id').append(new Option('Error fetching churches', '', false, false));
            }
            });
    }

</script>

<script>
    $(function() {
        // $('.js-select2').select2();
    });
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd', // Set the date format
        autoclose: true
    });
    $('#level').change(function() {
        var selectedLevel = $(this).val();
        var selectedMinistryId = $('#ministry_id').val();

        if (selectedLevel != '') {
            loadChurches(selectedMinistryId, selectedLevel); // Load churches based on selected level
        }
    });

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
                    const $churchDropdown = $('#church_id');
                    $churchDropdown.empty(); // Clear existing options

                    if (response.success) {
                        $churchDropdown.append(new Option('Select Church', '', false, false));

                        $.each(response.data, function (index, church) {
                            const churchName = (church.name);
                            const churchType = (church.type);
                            const optionText = `${churchName} - ${churchType}`;
                            $churchDropdown.append(new Option(optionText, church.id));
                        });
                    } else {
                        $churchDropdown.append(new Option('No churches available', '', false, false));
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
            $('#spinner').attr('required', 'required');
        } else{
            $('#foundation_resp').hide(500);
            $('#spinner').removeAttr('required');
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

<?= $this->endSection(); ?>