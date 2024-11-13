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

    <?php if($param2 == 'done') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?=translate_phrase('Are you sure?'); ?></b></h3>
                <input type="hidden" name="d_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-primary text-uppercase" type="submit">
                    <i class="icon ni ni-check-round"></i> <?=translate_phrase('Yes'); ?>
                </button>
            </div>
        </div>
    <?php } ?>

    <?php if ($param2 == 'edit' || $param2 == '') { ?>
        <style>
            .text-right {
                text-align: right;
            }
            
        </style>
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if (!empty($e_id)) {
                echo $e_id;
            } ?>" />
            <input type="hidden" id="father" value="<?php if (!empty($e_father_id)) {
               echo $e_father_id;
           } ?>" />
           <input type="hidden" id="mother" value="<?php if (!empty($e_mother_id)) {
              echo $e_mother_id;
          } ?>" />
            <?php
            $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
            $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

            if ($ministry_id > 0) { ?>
                <input type="hidden" name="ministry_id" value="<?php echo $ministry_id; ?>">
                <input type="hidden" name="church_id" value="<?php echo $church_id; ?>">
            <?php } else { ?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Ministry</label>
                        <select class="js-select2" data-search="on" name="ministry_id" id="ministry_id">
                            <option value="">Select Ministry</option>
                            <?php

                            $ministry = $this->Crud->read_order('ministry', 'name', 'asc');
                            if (!empty($ministry)) {
                                foreach ($ministry as $d) {
                                    $sel = '';
                                    if (!empty($e_ministry_id)) {
                                        if ($e_ministry_id == $d->id) {
                                            $sel = 'selected';
                                        }
                                    }
                                    echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

            <?php } ?>

            <?php if ($role != 'Church Leader') { ?>
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Church Level</label>
                        <select class="js-select2" data-search="on" name="level" id="level">
                            <option value="all">All Church Level</option>
                            <?php
                                
                                $log_church_id = $this->Crud->read_field('id', $log_id, 'user',  'church_id');
                                $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

                                if($log_church_type == 'region'){
                                    
                            ?>
                            
                                <option value="zone" <?php if(!empty($e_level)){if($e_level == 'zone'){echo 'selected';}} ?>>Zonal Church</option>
                                <option value="group" <?php if(!empty($e_level)){if($e_level == 'group'){echo 'selected';}} ?>>Group Church</option>
                                <option value="church" <?php if(!empty($e_level)){if($e_level == 'church'){echo 'selected';}} ?>>Church Assembly</option>
                            <?php } elseif($log_church_type == 'zone'){?>
                            
                                <option value="group" <?php if(!empty($e_level)){if($e_level == 'group'){echo 'selected';}} ?>>Group Church</option>
                                <option value="church" <?php if(!empty($e_level)){if($e_level == 'church'){echo 'selected';}} ?>>Church Assembly</option>

                            <?php } elseif($log_church_type == 'group'){?>
                            
                                <option value="church" <?php if(!empty($e_level)){if($e_level == 'church'){echo 'selected';}} ?>>Church Assembly</option>

                            <?php } else{?>
                                <option value="region" <?php if(!empty($e_level)){if($e_level == 'region'){echo 'selected';}} ?>>Regional Church</option>
                                <option value="zone" <?php if(!empty($e_level)){if($e_level == 'zone'){echo 'selected';}} ?>>Zonal Church</option>
                                <option value="group" <?php if(!empty($e_level)){if($e_level == 'group'){echo 'selected';}} ?>>Group Church</option>
                                <option value="church" <?php if(!empty($e_level)){if($e_level == 'church'){echo 'selected';}} ?>>Church Assembly</option>
                            <?php } ?>
                            
                        </select>
                    </div>
                </div>
                
                
            
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Church</label>
                        <select class="js-select2" data-search="on" name="church_id" id="church_id">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>

            <?php } else{?>
                <input type="hidden" id="church_id" value="<?php echo $church_id; ?>">
            <?php } ?>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label class="form-labelz">Dedication Date</label>
                    <div class="form-control-wrap">
                        <input type="text" data-date-format="yyyy-mm-dd" name="date" id="eates"
                            class="form-control date-picker" value="<?php if (!empty($e_date)) {
                                echo date('Y-m-d', strtotime($e_date));
                            } ?>">
                    </div>

                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">Child Surname</label>
                    <input class="form-control" type="text" id="surname" name="surname" value="<?php if (!empty($e_surname)) {
                        echo $e_surname;
                    } ?>" required>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">Child Firstname</label>
                    <input class="form-control" type="text" id="firstname" name="firstname" value="<?php if (!empty($e_firstname)) {
                        echo $e_firstname;
                    } ?>" required>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">Child Othername</label>
                    <input class="form-control" type="text" id="othername" name="othername" value="<?php if (!empty($e_othername)) {
                        echo $e_othername;
                    } ?>">
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">Gender</label>
                    <select class="js-select2" data-search="on" name="gender" id="gender">
                    <option value="male" <?php if (!empty($e_gender)) {if($e_gender == 'male'){echo 'selected';}} ?>>Male</option>
                    <option value="female" <?php if (!empty($e_gender)) {if($e_gender == 'female'){echo 'selected';}} ?>>Female</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label class="form-labelz">Child Date of Birth</label>
                    <div class="form-control-wrap">
                        <input type="text" data-date-format="yyyy-mm-dd" name="dob" id="dob"
                            class="form-control date-picker" value="<?php if (!empty($e_dob)) {
                                echo date('Y-m-d', strtotime($e_dob));
                            } ?>">
                    </div>

                </div>
            </div>

            <div class="col-sm-6 mb-3 parent_resp" style="display:none;">
                <div class="form-group">
                    <label>Father</label>
                    <select class="js-select2" data-search="on" name="father_id" id="father_id">
                        <option value="">Select</option>
                    </select>
                </div>
            </div>

            
            <div class="col-sm-6 mb-3 parent_resp" style="display:none;">
                <div class="form-group">
                    <label>Mother</label>
                    <select class="js-select2" data-search="on" name="mother_id" id="mother_id">
                        <option value="">Select</option>
                    </select>
                </div>
            </div>



            <div class="col-sm-12 text-center mt-4">
                <hr />
                <button class="btn btn-primary bb_for_btn" type="submit">
                    <i class="icon ni ni-save"></i> Save
                </button>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-12 my-3">
                <div id="bb_ajax_msg"></div>
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

    


    $(document).ready(function () {
        <?php
            $e_church_ids = !empty($e_church_id) ? $e_church_id : '';
            $e_father_ids = !empty($e_father_id) ? $e_father_id : '';
            $e_mother_ids = !empty($e_mother_id) ? $e_mother_id : '';
        ?>
        var eChurchId = <?php echo $e_church_ids; ?>;
        var eMotherId = <?php echo $e_mother_ids; ?>;
        var eFatherId = <?php echo $e_father_ids; ?>;
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

                        if (response.success) {
                            
                           // Populate the Church dropdown with the data received
                            $.each(response.data, function (index, church) {
                                var selected = (eChurchId === church.id); // Check if church.id matches the selected eChurchId
                                var churchName = toTitleCase(church.name); // Convert name to title case
                                var churchType = toTitleCase(church.type); // Convert type to title case
                                $('#church_id').append(new Option(churchName + ' - ' + churchType, church.id, selected, selected));
                            });

                            loadParent();
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

        // Helper function to convert strings to title case
        function toTitleCase(str) {
            return str.toLowerCase().replace(/(?:^|\s)\S/g, function (a) { return a.toUpperCase(); });
        }

        // Auto-load churches if ministry_id or level is already set
        var ministryId = $('#ministry_id').val();
        var initialLevel = $('#level').val();
        if (ministryId && initialLevel) {
            loadChurches(ministryId, initialLevel);
        }
        
        $('#parent_resp').hide(500);
        function loadParent(){        
            var church_id = $('#church_id').val();
            $('#father_id').empty();
            $('#father_id').append(new Option('Loading...', '', false, false));
            $('#mother_id').empty();
            $('#mother_id').append(new Option('Loading...', '', false, false));

            $('.parent_resp').show(500);
            $.ajax({
                url: site_url + 'dedication/list/record/get_parent', // Update this to the path of your API endpoint
                type: 'POST',
                data: {church_id:church_id, father_id:eFatherId,mother_id:eMotherId},
                success: function (response) {
                    $('#father_id').empty(); // Clear 'Loading...' option
                    $('#mother_id').empty();
                    
                    var dt = JSON.parse(response);

                    $('#father_id').html(dt.father);
                    $('#mother_id').html(dt.mother);
                    
                }
            });
            
        }

        // Load churches on ministry selection change
        $('#ministry_id').change(function () {
            var selectedMinistryId = $(this).val();
            var selectedLevel = $('#level').val();
            loadChurches(selectedMinistryId, selectedLevel);
        });
        $('#church_id').change(function () {
            loadParent();
        });

        // Handle the change event of the Church Level dropdown
        $('#level').change(function() {
            var selectedLevel = $(this).val();
            var selectedMinistryId = $('#ministry_id').val();
            loadChurches(selectedMinistryId, selectedLevel); // Load churches based on selected level
            
        });
    });


</script>