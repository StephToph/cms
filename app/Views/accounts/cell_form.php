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


    <?php if ($param2 == 'view') { ?>
        <table id="dtable" class="table table-striped">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pays = $this->Crud->read_single('id', $param3, 'cells');

                $total = 0;
                if (!empty($pays)) {
                    foreach ($pays as $p) {
                        $time = $p->time;
                        if (!empty(json_decode($time))) {
                            foreach (json_decode($time) as $t => $val) {

                                ?>
                                <tr>
                                    <td><?= $t ?></td>
                                    <td><?= date('h:iA', strtotime($val)); ?></td>
                                </tr>
                                <?php
                            }
                        }
                    }
                }

                ?>
            </tbody>
        </table>

    <?php } ?>
    <!-- insert/edit view -->
    <?php if ($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12">
                <div id="bb_ajax_msg"></div>
            </div>
        </div>


        <div class="row">
            <input type="hidden" name="cell_id" value="<?php if (!empty($e_id)) {
                echo $e_id;
            } ?>" />
            <?php
            $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
            $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

            if ($ministry_id > 0) { ?>
                <input type="hidden" name="ministry_id" id="ministry_id" value="<?php echo $ministry_id; ?>">
                <input type="hidden" name="church_id" value="<?php echo $church_id; ?>">
            <?php } else { ?>
                <div class="col-sm-12 mb-3">
                    <div class="form-group">
                        <label class="">Ministry</label>
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

            <?php if ($church_id == 0) { ?>
                <div class="col-sm-12 mb-3">
                    <div class="form-group">
                        <label class="form-label">Church Level</label>
                        <select class="js-select2" data-search="on" name="level" id="level">
                            <option value="">Select Church Level</option>
                            <?php

                            $log_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                            $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

                            if ($log_church_type == 'region') {

                                ?>

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
                            <?php } elseif ($log_church_type == 'zone') { ?>

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

                            <?php } elseif ($log_church_type == 'group') { ?>

                                <option value="church" <?php if (!empty($e_level)) {
                                    if ($e_level == 'church') {
                                        echo 'selected';
                                    }
                                } ?>>Church
                                    Assembly</option>

                            <?php } else { ?>
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
                            <?php } ?>

                        </select>
                    </div>
                </div>


                <div class="col-sm-12 mb-3" id="church_div">
                    <div class="form-group">
                        <label class="form-label">Church</label>
                        <select class="js-select2" data-search="on" name="church_id" id="church_id">
                            <option value="">Select</option>

                        </select>
                    </div>
                </div>

            <?php } ?>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?= translate_phrase('Name'); ?></label>
                    <input class="form-control" type="text" id="name" name="name"
                        value="<?php if (!empty($e_name)) {
                            echo $e_name;
                        } ?>" required>
                </div>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?= translate_phrase('Location'); ?></label>
                    <input class="form-control" type="text" id="location" name="location"
                        value="<?php if (!empty($e_location)) {
                            echo $e_location;
                        } ?>" required>
                </div>
            </div>

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?= translate_phrase('Phone'); ?></label>
                    <input class="form-control" type="text" id="phone" name="phone"
                        value="<?php if (!empty($e_phone)) {
                            echo $e_phone;
                        } ?>">
                </div>
            </div>

        </div>
        <div id="containers">
            <?php if (!empty($e_time)) {
                $a = 0;
                foreach ($e_time as $k => $val) {
                    $r_val = 'style="display:none;"';
                    $req = 'required';
                    if ($a > 0) {
                        $r_val = 'style="display:display;"';
                        $req = '';
                    }
                    ?>
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="name">*<?= translate_phrase('Meeting Day'); ?></label>
                                <select class="form-control" name="days[]" <?= $req; ?>>
                                    <option value="">Select</option>
                                    <option value="Sunday" <?php if ($k == 'Sunday') {
                                        echo 'selected';
                                    } ?>>Sunday</option>
                                    <option value="Monday" <?php if ($k == 'Monday') {
                                        echo 'selected';
                                    } ?>>Monday</option>
                                    <option value="Tuesday" <?php if ($k == 'Tuesday') {
                                        echo 'selected';
                                    } ?>>Tuesday</option>
                                    <option value="Wednesday" <?php if ($k == 'Wednesday') {
                                        echo 'selected';
                                    } ?>>Wednesday</option>
                                    <option value="Thursday" <?php if ($k == 'Thursday') {
                                        echo 'selected';
                                    } ?>>Thursday</option>
                                    <option value="Friday" <?php if ($k == 'Friday') {
                                        echo 'selected';
                                    } ?>>Friday</option>
                                    <option value="Saturday" <?php if ($k == 'Saturday') {
                                        echo 'selected';
                                    } ?>>Saturday</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-8 mb-3">
                            <label for="name">*<?= translate_phrase('Meeting Time'); ?></label>
                            <div class="form-group input-group">
                                <input class="form-control" type="time" id="location" value="<?php if (!empty($val)) {
                                    echo $val;
                                } ?>"
                                    name="times[]" <?= $req; ?>>
                                <button <?= $r_val; ?> class="btn btn-icon btn-outline-danger deleteBtns" type="button"><i
                                        class="icon ni ni-trash"></i> </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $a++;
                }
            } else { ?>
            <div class="row">
                <div class="col-sm-4 mb-3">
                    <div class="form-group">
                        <label for="name">*<?= translate_phrase('Meeting Day'); ?></label>
                        <select class="form-control" name="days[]" required>
                            <option value="">Select</option>
                            <option value="Sunday">Sunday</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-8 mb-3">
                    <label for="name">*<?= translate_phrase('Meeting Time'); ?></label>
                    <div class="form-group input-group">
                        <input class="form-control" type="time" id="location" name="times[]" required>
                        <button style="display:none;" class="btn btn-icon btn-outline-danger deleteBtns" type="button"><i
                                class="icon ni ni-trash"></i> </button>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
        <div class="col-sm-12 mb-3 text-center">
            <button id="addMores" class="btn btn-ico btn-outline-info" type="button"><i class="icon ni ni-plus-c"></i>
                <span><?= translate_phrase('Add More Days'); ?></span></button>
        </div>


        <div class="row">

            <div class="col-sm-12 text-center mt-3">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?= translate_phrase('Save Record'); ?>
                </button>
            </div>
        </div>
    <?php } ?>
    
    <?php if($param2 == 'cell_message') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Message all Members?');?></label>
                    <select class="form-control js-select2" data-toggle="select2" id="type" name="type">
                        <option value="true"><?=translate_phrase('Yes - All Members in Cell'); ?></option>
                        <option value="false"><?=translate_phrase('No - Only Cell Executives'); ?></option>
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
    
    
    <?php if($param2 == 'bulk_message') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>
        
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Send to all Cell Member?');?></label>
                    <select class="form-control js-select2" data-toggle="select2" id="type" name="type">
                        <option value="false"><?= translate_phrase('No - Cell Executives Only'); ?></option>
                        <option value="true"><?= translate_phrase('Yes - All Cell Members'); ?></option>
                    </select>
                </div>
            </div>
                
              
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Cell');?></label>
                    <select class="form-control js-select2" multiple data-search="on" data-toggle="select2" id="cell_id" name="cell_id[]">
                        <?php 
                            $cells = $this->Crud->read_order('cells', 'name', 'asc');
                            $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                            $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                            if($church_id > 0 && $ministry_id > 0){
                                $cells = $this->Crud->read_single_order('church_id', $church_id, 'cells', 'name', 'asc');
                            }
                            if($church_id < 0 && $ministry_id > 0){
                                $cells = $this->Crud->read_single_order('ministry_id', $ministry_id, 'cells', 'name', 'asc');
                            }

                            if(!empty($cells)){
                                foreach($cells as $cell){
                                    $c = '';
                                    if($church_id <= 0){
                                        $c = ' - '.$this->Crud->read_field('id', $cell->church_id, 'church', 'name');
                                    }
                                    echo '<option value="'.$cell->id.'">'.ucwords($cell->name).' '.ucwords($c).'</option>';
                                }
                            }
                        ?>
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
    
    $(document).ready(function () {
        <?php
           $e_church_ids = !empty($e_church_id) ? $e_church_id : 0;
        ?>
        var eChurchId = <?php echo $e_church_ids; ?>;
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
                                var selected = (eChurchId === church.id); // Check if the ID matches
                                var churchName = toTitleCase(church.name); // Convert name to title case
                                var churchType = toTitleCase(church.type); // Convert type to title case
                                $('#church_id').append(new Option(churchName + ' - ' + churchType, church.id, selected, selected));
                            });
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
        if (ministryId || initialLevel) {
            if (initialLevel === 'all') {
                $('#church_div').hide(600);
                $('#send_resp').hide(600); // Hide the Church dropdown
            } else {
                $('#send_resp').show(600);
                $('#church_div').show(600); // Show the Church dropdown
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
        $('#level').change(function() {
            var selectedLevel = $(this).val();
            var selectedMinistryId = $('#ministry_id').val();

            if (selectedLevel === 'all') {
                $('#church_div').hide(600);
                $('#send_resp').hide(600); // Hide the Church dropdown
            } else {
                $('#send_resp').show(600);
                $('#church_div').show(600); // Show the Church dropdown
                loadChurches(selectedMinistryId, selectedLevel); // Load churches based on selected level
            }
        });

        // Initial check to handle the case when the page loads with a preset level
        if (initialLevel !== 'all') {
            $('#church_div').show(600); // Ensure the Church dropdown is shown if a level is selected
        } else {
            $('#church_div').hide(600); // Hide the Church dropdown if the level is 'all'
        }
    });


    document.getElementById('addMores').addEventListener('click', function () {
        var container = document.getElementById('container');
        var div = container.children[0].cloneNode(true);

        // Clear input value of the cloned div
        div.querySelector('input').value = '';
        div.querySelector('input').removeAttribute('required');

        // Show delete button in the cloned div
        div.querySelector('.deleteBtn').style.display = 'inline-block';

        // Add event listener to delete button
        div.querySelector('.deleteBtn').addEventListener('click', function () {
            div.parentNode.removeChild(div);
        });

        container.appendChild(div);
    });

    $('#addMores').on('click', function () {
        var container = $('#containers');
        var clonedRow = container.children('.row').first().clone();

        // Clear values of cloned inputs
        clonedRow.find('input').val('');
        clonedRow.find('select, input').removeAttr('required');

        // Hide delete button in the cloned row
        clonedRow.find('.deleteBtns').show();

        clonedRow.find('.js-select2').select2();
        // Append cloned row to container
        container.append(clonedRow);
    });

    // Event delegation to handle dynamically added delete buttons
    $('#containers').on('click', '.deleteBtns', function () {
        $(this).closest('.row').remove();
    });

    $('#containers').on('change', 'select[name="day[]"]', function () {
        var timeInput = $(this).closest('.row').find('input[name="time[]"]');
        if ($(this).val()) {
            timeInput.attr('required', 'required');
        } else {
            timeInput.removeAttr('required');
        }
    });
    </script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script