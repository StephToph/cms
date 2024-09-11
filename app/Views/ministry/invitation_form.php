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
    <div class="row">
        
        <div class="col-sm-12 mb-3 table-responsive">
            <table class="table table-hovered">
                
                <tr>
                    <td><h5 class="text-center text-info"><?= ucwords($e_title); ?></h5></td>
                </tr>
                <tr>
                    <td><?= ucwords(($e_description)); ?></d></td>
                </tr>
            </table>
            <table class="table table-hovered">
                <tr>
                    <td><b>Minstry</b></td>
                    <td><?=$this->Crud->read_field('id', $e_ministry_id, 'ministry', 'name');?></td>
                    <td><b>Church Level</b></td>
                    <td><?=ucwords($e_church_type);?> Level</td>
                    
                </tr>
                <tr>
                    <td><b>Form is For</b></td>
                    <td><?=ucwords($e_send_type);?> Church</td>
                    <td><b>Created At</b></td>
                    <td><?=date('d F Y h:i:sA', strtotime($e_reg_date));?></td>
                </tr>
                <?php if($e_church_type != 'all'){?>
                <tr>
                   
                    <td><b>Church</b></td>
                    <td colspan="4"><?php 
                        $church = '';
                        if(!empty($e_church_id)){
                            $churches = json_decode($e_church_id);
                            if(!empty($churches)){
                                foreach($churches as $c => $val){
                                    $church .=  ucwords($this->Crud->read_field('id', $val, 'church', 'name')).', ';
                                }
                            }
                        }
                        echo rtrim($church,', ');
                       
                    ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="5" class=" text-center"><b class="text-danger">Form Fields</b></td>
                </tr>
                <?php 
                    
                    if(!empty($e_fields)){
                        foreach($e_fields as $f => $field){
                            $type = str_replace('_', ' ',  $field->type);
                            $opts = '';
                            if($field->type == 'single_choice' || $field->type == 'multiple_choice'){
                                $opt = '';
                                $options = $field->options;
                                foreach($options as $op => $option){
                                    $opt .= $option.', ';
                                }
                                
                                $optaa = rtrim($opt, ', ');
                                $opta = '<span class="text-info">{'.ucwords($opt).'}</span>';
                            } else{
                                $opta = '';
                            }
                            $opts = $opta;
                            ?>
                            <tr>
                                <td colspan="2"><b><?=ucwords($field->label);?></b></td>
                                <td colspan="3"><?=ucwords($type).' '.$opts;?></td>
                            </tr>
                        <?php }
                    }
                    
                ?>

            </table>
        </div>

    </div>

<?php } ?>


<?php if ($param2 == 'extension') { ?>
    <div class="row">
        
        <div class="col-sm-12 mb-3 table-responsive">
            <h5 class="text-center text-info"><?= ucwords($e_title); ?></h5>
            <table class="table table-hover">
                <tr>
                    <td colspan="5" class=" text-cente"><b class="text-danger">Form Fields</b></td>
                </tr>
                <?php 
                    
                    if(!empty($e_fields)){
                        foreach($e_fields as $f => $field){
                            $type = str_replace('_', ' ',  $field->type);
                            $opts = '';
                            if($field->type == 'single_choice' || $field->type == 'multiple_choice'){
                                $opt = '';
                                $options = $field->options;
                                foreach($options as $op => $option){
                                    $opt .= $option.', ';
                                }
                                
                                $optaa = rtrim($opt, ', ');
                                $opta = '<span class="text-info">{'.ucwords($opt).'}</span>';
                            } else{
                                $opta = '';
                            }
                            $opts = $opta;
                            ?>
                            <tr>
                                <td colspan="2"><b><?=ucwords($field->label);?></b></td>
                                <td colspan="3"><?=ucwords($type).' '.$opts;?></td>
                            </tr>
                        <?php }
                    }
                    
                ?>

            </table>
        </div>

    </div>
    <style>
        .text-right {
            text-align: right;
        }
        
    </style>
    <div class="row">
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />

        <div class="col-sm-12 mb-3 " >
            <?php
                $label = '';
                $type = '';
                if(!empty($e_fields)){
                    foreach($e_fields as $fe => $value){
                        
                        $label = $value->label;
                        $type = $value->type;
                        if (isset($value->options) && !empty($value->options)) {
                            $options = $value->options;
                        } else {
                            $options = [];
                        }
                        if($fe == 0)break;
                    }
                   
                }

                $type = isset($type) ? $type : ''; 

                $displayStyle = ($type === 'single_choice' || $type === 'multiple_choice') ? 'display:block;' : 'display:none;';

            ?>
            <div class="row card-bordered optionsa my-2 p-2" data-counter="1">
                <h5>Field <span class="field-number">1</span></h5>
                <div class="col-sm-6 mb-3">
                    <label>Field Label</label>
                    <input class="form-control" type="text" id="label_1" name="label[]" value="<?php if (!empty($label)) { echo $label; } ?>" required>
                </div>
    
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Field Type</label>
                        <select class="form-select " name="type[]" id="type_1" required>
                            <option value="text" <?php if (!empty($type) && $type == 'text') { echo 'selected'; } ?>>Text</option>
                            <option value="single_choice" <?php if (!empty($type) && $type == 'single_choice') { echo 'selected'; } ?>>Single Choice</option>
                            <option value="multiple_choice" <?php if (!empty($type) && $type == 'multiple_choice') { echo 'selected'; } ?>>Multiple Choice</option>
                            <option value="true_false" <?php if (!empty($type) && $type == 'true_false') { echo 'selected'; } ?>>True or False</option>
                        </select>
                    </div>
                </div>
                
                <div id="options_container_1" class="options-container" style="<?php echo $displayStyle; ?>">
                    <?php
                        if(!empty($type)){
                            if($type == 'single_choice' || $type == 'multiple_choice'){
                                if(!empty($options)){
                                    for($i=0;$i<count($options);$i++){
                                        if($i > 1){
                                            echo '
                                            <div class="col-sm-12 mb-3 option_resp" id="option_1_'.$i.'">
                                                <div class="d-flex align-items-center mb-2">
                                                    <label class="me-2">Option</label>
                                                    <input class="form-control" type="text" name="options[1][]" id="option_input_1_'.$i.'" value="'.$options[$i].'">
                                                    <button class="btn btn-outline-danger btn-sm ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" id="delete_option_1_'.$i.'" title="Delete Option">
                                                        <i class="icon ni ni-trash"></i>
                                                    </button>
                                                </div>
                                            </div>';
                                        } else {

                                            echo '
                                            <div class="col-sm-12 mb-3 option_resp" id="option_1_'.$i.'">
                                                <div class="d-flex align-items-center mb-2">
                                                    <label class="me-2">Option</label>
                                                    <input class="form-control" type="text" name="options[1][]" id="option_input_1_'.$i.'" value="'.$options[$i].'">
                                                   
                                                </div>
                                            </div>';
                                        }
                                    }
                                }

                            }
                        }

                    ?>
                </div>

                <div class="col-sm-3 mb-2" id="add_more_options_1" style="<?php echo $displayStyle; ?>">
                    <label class="text-white">.</label>
                    <button class="btn btn-warning btn-dim btn-blck bb_for_btn" id="add_option_1" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Options" type="button">
                        <span>Add  Options</span><i class="icon ni ni-plus"></i>
                    </button>
                </div>
                <div class="col-sm-5 text-white my-2">.</div>
                <div class="col-sm-3 text-right my-2" id="delete_field_resp" style="display:none;">
                    <button class="btn btn-danger btn-block bb_for_btn" id="delete_field" type="button">
                        <i class="icon ni ni-trash"></i> <span>Delete Field</span>
                    </button>
                </div>
            </div>

            <div class="col-sm-12 text-center my-2">
                <hr />
                <button class="btn btn-info btn-block bb_for_btn" id="add_field" type="button">
                    <i class="icon ni ni-plus-c"></i> <span>Add Field</span>
                </button>
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

<!-- insert/edit view -->
<?php if ($param2 == 'edit' || $param2 == '') { ?>
    <style>
        .text-right {
            text-align: right;
        }
        
    </style>
    <div class="row">
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name">Title</label>
                <input class="form-control" type="text" id="title" name="title" value="<?php if (!empty($e_title)) {
                    echo $e_title;
                } ?>" required>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name">Description</label>
                <textarea id="summernot" class="form-control" name="description" rows="5" required><?php if (!empty($e_description)) {
                    echo $e_description;
                } ?></textarea>
            </div>
        </div>

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
            
            <div class="col-sm-6 mb-3" id="send_resp" style="display:none;">
                <div class="form-group">
                    <label>Send Type</label>
                    <select class="js-select2" data-search="on" name="send_type" id="send_type" required>
                        <option value="general" <?php if (!empty($e_send_type)) {
                            if ($e_send_type == 'general') {
                                echo 'selected';
                            }
                        }
                        ; ?>>General Church</option>
                        <option value="individual" <?php if (!empty($e_send_type)) {
                            if ($e_send_type == 'individual') {
                                echo 'selected';
                            }
                        }
                        ; ?>>
                            Individual Church</option>
                        
                    </select>
                    <span id="send_text" class="text-danger small">
                        <?php  if (!empty($e_send_type)) {
                            if($e_send_type == 'general'){
                                echo 'This form will apply to all churches under selected Church Level';
                            } else {
                                echo 'This form would apply to only selected Church Level';
                            }
                        } else{
                            echo 'This form will apply to all churches under selected Church Level';
                        }
                        ?>
                        </span>
                </div>
            </div>
        
            <div class="col-sm-12 mb-3" id="church_div" style="display:none;">
                <div class="form-group">
                    <label>Church</label>
                    <select class="js-select2" data-search="on" multiple name="church_id[]" id="church_id">
                        <option value="">Select</option>

                    </select>
                </div>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label>Event Associated To</label>
                    <select class="js-select2" data-search="on" name="event_id" id="event_id">
                        <option value="0">Not Associated</option>
                        <?php
                            
                            $log_church_id = $this->Crud->read_field('id', $log_id, 'user',  'church_id');
                            $log_ministry_id = $this->Crud->read_field('id', $log_id, 'user',  'ministry_id');
                            $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');

                            $events = $this->Crud->read_single_order('ministry_id', $log_ministry_id, 'events', 'title', 'asc');
                            if($role == 'developer' || $role == 'administrator'){
                                $events = $this->Crud->read('events');
                            }
                            if(!empty($events)){
                                foreach ($events as $event) {
                                    if($role != 'developer' && $role != 'administrator' && $role != 'ministry administrator'){
                                        if($event->church_type != 'all'){
                                            if($event->church_type == 'region' && $event->event_for == 'general'){
                                                $log_region_id = $this->Crud->read_field('id', $log_church_id, 'church', 'regional_id');
                                                if(!in_array($log_region_id, json_decode($event->church_id))){
                                                    continue;
                                                }
                                            } else {
                                                if(!in_array($log_church_id, json_decode($event->church_id))){
                                                    continue;
                                                }

                                            }
                                           
                                            if($event->church_type == 'zone' && $event->event_for == 'general'){
                                                $log_region_id = $this->Crud->read_field('id', $log_church_id, 'church', 'zonal_id');
                                                if(!in_array($log_region_id, json_decode($event->church_id))){
                                                    continue;
                                                }
                                            } else {
                                                if(!in_array($log_church_id, json_decode($event->church_id))){
                                                    continue;
                                                }

                                            }
                                            if($event->church_type == 'group' && $event->event_for == 'general'){
                                                $log_region_id = $this->Crud->read_field('id', $log_church_id, 'church', 'group_id');
                                                if(!in_array($log_region_id, json_decode($event->church_id))){
                                                    continue;
                                                }
                                            } else {
                                                if(!in_array($log_church_id, json_decode($event->church_id))){
                                                    continue;
                                                }

                                            }
                                           
                                            if($event->church_type == 'church' && $event->event_for == 'general'){
                                                if(!in_array($log_church_id, json_decode($event->church_id))){
                                                    continue;
                                                }

                                            }
                                           
                                        }
                                    }

                                    echo '<option value="'.$event->id.'">'.ucwords($event->title).'</option>';
                                }
                            }
                         ?>
                        
                    </select>
                </div>
            </div>

        <?php } ?>

        <div class="col-sm-12 mb-3 " >
            <?php
                $label = '';
                $type = '';
                if(!empty($e_fields)){
                    foreach($e_fields as $fe => $value){
                        
                        $label = $value->label;
                        $type = $value->type;
                        if (isset($value->options) && !empty($value->options)) {
                            $options = $value->options;
                        } else {
                            $options = [];
                        }
                        if($fe == 0)break;
                    }
                   
                }

                $type = isset($type) ? $type : ''; 

                $displayStyle = ($type === 'single_choice' || $type === 'multiple_choice') ? 'display:block;' : 'display:none;';

            ?>
            <div class="row card-bordered optionsa my-2 p-2" data-counter="1">
                <h5>Field <span class="field-number">1</span></h5>
                <div class="col-sm-6 mb-3">
                    <label>Field Label</label>
                    <input class="form-control" type="text" id="label_1" name="label[]" value="<?php if (!empty($label)) { echo $label; } ?>" required>
                </div>
    
                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Field Type</label>
                        <select class="form-select " name="type[]" id="type_1" required>
                            <option value="text" <?php if (!empty($type) && $type == 'text') { echo 'selected'; } ?>>Text</option>
                            <option value="single_choice" <?php if (!empty($type) && $type == 'single_choice') { echo 'selected'; } ?>>Single Choice</option>
                            <option value="multiple_choice" <?php if (!empty($type) && $type == 'multiple_choice') { echo 'selected'; } ?>>Multiple Choice</option>
                            <option value="true_false" <?php if (!empty($type) && $type == 'true_false') { echo 'selected'; } ?>>True or False</option>
                        </select>
                    </div>
                </div>
                
                <div id="options_container_1" class="options-container" style="<?php echo $displayStyle; ?>">
                    <?php
                        if(!empty($type)){
                            if($type == 'single_choice' || $type == 'multiple_choice'){
                                if(!empty($options)){
                                    for($i=0;$i<count($options);$i++){
                                        if($i > 1){
                                            echo '
                                            <div class="col-sm-12 mb-3 option_resp" id="option_1_'.$i.'">
                                                <div class="d-flex align-items-center mb-2">
                                                    <label class="me-2">Option</label>
                                                    <input class="form-control" type="text" name="options[1][]" id="option_input_1_'.$i.'" value="'.$options[$i].'">
                                                    <button class="btn btn-outline-danger btn-sm ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" id="delete_option_1_'.$i.'" title="Delete Option">
                                                        <i class="icon ni ni-trash"></i>
                                                    </button>
                                                </div>
                                            </div>';
                                        } else {

                                            echo '
                                            <div class="col-sm-12 mb-3 option_resp" id="option_1_'.$i.'">
                                                <div class="d-flex align-items-center mb-2">
                                                    <label class="me-2">Option</label>
                                                    <input class="form-control" type="text" name="options[1][]" id="option_input_1_'.$i.'" value="'.$options[$i].'">
                                                   
                                                </div>
                                            </div>';
                                        }
                                    }
                                }

                            }
                        }

                    ?>
                </div>

                <div class="col-sm-3 mb-2" id="add_more_options_1" style="<?php echo $displayStyle; ?>">
                    <label class="text-white">.</label>
                    <button class="btn btn-warning btn-dim btn-blck bb_for_btn" id="add_option_1" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Options" type="button">
                        <span>Add  Options</span><i class="icon ni ni-plus"></i>
                    </button>
                </div>
                <div class="col-sm-5 text-white my-2">.</div>
                <div class="col-sm-3 text-right my-2" id="delete_field_resp" style="display:none;">
                    <button class="btn btn-danger btn-block bb_for_btn" id="delete_field" type="button">
                        <i class="icon ni ni-trash"></i> <span>Delete Field</span>
                    </button>
                </div>
            </div>

            <div class="col-sm-12 text-center my-2">
                <hr />
                <button class="btn btn-info btn-block bb_for_btn" id="add_field" type="button">
                    <i class="icon ni ni-plus-c"></i> <span>Add Field</span>
                </button>
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

    $(function () {
        $('#summernote').summernote({
            height: 300, // Set the height of the editor
            tabsize: 2,
            focus: true
        });

        $('#church_id').select2({
            placeholder: 'Select Church(s)',
            allowClear: true  // This allows clearing the selection if needed
        });
    });


    var site_url = '<?php echo site_url(); ?>';

    var fieldCounter = 1;


    $(document).ready(function () {
       
        <?php
            $e_church_ids = !empty($e_church_id) ? json_encode($e_church_id) : '[]';
        ?>
        var eChurchId = <?php echo $e_church_ids; ?>;
        if (typeof eChurchId === 'string') {
            eChurchId = JSON.parse(eChurchId); // Parse JSON string to array
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

                        if (response.success) {
                            
                           // Populate the Church dropdown with the data received
                            $.each(response.data, function (index, church) {
                                var selected = eChurchId.includes(church.id);
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

        
    $(document).ready(function() {
        // Initialize a counter for the fields
        let fieldCounter = 1;
        <?php
            $e_field = !empty($e_fields) ? json_encode($e_fields) : '[]';
        ?>
        var eFields = <?php echo $e_field; ?>;
       
         // Initialize fields if editing
        function initializeFields() {
            if (Array.isArray(eFields)) {
                // Determine the highest existing counter in the DOM
                $('.optionsa').each(function() {
                    const existingCounter = $(this).data('counter');
                    if (existingCounter >= fieldCounter) {
                        fieldCounter = existingCounter + 1; // Set fieldCounter to the next available number
                    }
                });

                // Initialize fields based on eFields data
                eFields.forEach((field, index) => {
                    // If the counter is already taken, continue with the next counter
                    if (index + 1 >= fieldCounter) {
                        addField(index + 1, field.label, field.type, field.options);
                        fieldCounter = index + 2; // Update fieldCounter for new fields
                    }
                });
            }
        }


        // Add more field when button is clicked
        $('#add_field').click(function() {
            
            addField(fieldCounter);
            fieldCounter++;
        });

        // Function to add a new field
        function addField(counter, label = '', type = 'text', options = []) {
            const newField = `
            <div class="row card-bordered optionsa my-2 p-2" data-counter="${counter}">
                <h5>Field <span class="field-number">${counter}</span></h5>
                <div class="col-sm-6 mb-3">
                    <label>Field Label</label>
                    <input class="form-control" type="text" id="label_${counter}" name="label[]" value="${label}" required>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Field Type</label>
                        <select class="form-select" name="type[]" id="type_${counter}" required>
                            <option value="text" ${type === 'text' ? 'selected' : ''}>Text</option>
                            <option value="single_choice" ${type === 'single_choice' ? 'selected' : ''}>Single Choice</option>
                            <option value="multiple_choice" ${type === 'multiple_choice' ? 'selected' : ''}>Multiple Choice</option>
                            <option value="true_false" ${type === 'true_false' ? 'selected' : ''}>True or False</option>
                        </select>
                    </div>
                </div>

                <div id="options_container_${counter}" style="${type === 'single_choice' || type === 'multiple_choice' ? 'display:block;' : 'display:none;'}">
                    ${options.map((option, index) => `
                    <div class="col-sm-12 mb-3 option_resp" id="option_${counter}_${index + 1}">
                        <div class="d-flex align-items-center mb-2">
                            <label class="me-2">Option</label>
                            <input class="form-control" type="text" name="options[${counter}][]" id="option_input_${counter}_${index + 1}" value="${option}">
                            ${index > 0 ? `<button class="btn btn-outline-danger btn-sm ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" id="delete_option_${counter}_${index + 1}" title="Delete Option">
                                <i class="icon ni ni-trash"></i>
                            </button>` : ''}
                        </div>
                    </div>
                    `).join('')}
                </div>

                <div class="col-sm-6 my-2" id="add_more_options_${counter}" style="${type === 'single_choice' || type === 'multiple_choice' ? 'display:block;' : 'display:none;'}">
                    <label class="text-white">.</label>
                    <button class="btn btn-warning btn-dim btn-blck bb_for_btn" id="add_option_${counter}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Options" type="button">
                        <span>Add Options</span><i class="icon ni ni-plus"></i>
                    </button>
                </div>
                <div class="col-sm-12 text-right my-2" id="delete_field_resp_${counter}" style="display:none;">
                    <button class="btn btn-danger btn-bloc delete-field-btn" id="delete_field_${counter}" data-counter="${counter}" type="button">
                        <i class="icon ni ni-trash"></i> <span>Delete Field</span>
                    </button>
                </div>
            </div>`;


            // Append the new field to the container
            $('.row.card-bordered').last().after(newField);

            // Show delete button if more than one field
            if (fieldCounter > 1) {
                $('.row.card-bordered').each(function() {
                    $(this).find('[id^="delete_field_resp_"]').show(500);
                });
            }

        }

        // Delegate the click event for dynamically added delete buttons
        $(document).on('click', '.delete-field-btn', function() {
            const counter = $(this).data('counter');
            $(this).closest('.optionsa').remove();
            resetCounters();
            // Update fieldCounter to match the remaining fields
            fieldCounter = $('.optionsa').length;
            
            // Hide delete button if only one field is left
            if (fieldCounter <= 1) {
                $('.row.card-bordered').find('[id^="delete_field_resp_"]').hide(500);
            }
        });

        function resetCounters() {
            let newCounter = 1;

            $('.optionsa').each(function() {
                $(this).attr('data-counter', newCounter);
                $(this).find('.field-number').text(newCounter);

                $(this).find('[id^="label_"]').attr('id', `label_${newCounter}`);
                $(this).find('[id^="type_"]').attr('id', `type_${newCounter}`);
                $(this).find('[id^="options_container_"]').attr('id', `options_container_${newCounter}`);
                $(this).find('[id^="option_"]').attr('id', `option_${newCounter}`);
                $(this).find('[id^="option_input_"]').attr('id', `option_input_${newCounter}`);
                $(this).find('[id^="add_option_"]').attr('id', `add_option_${newCounter}`);
                $(this).find('[id^="delete_option_"]').attr('id', `delete_option_${newCounter}`);
                $(this).find('[id^="delete_field_resp_"]').attr('id', `delete_field_resp_${newCounter}`);
                $(this).find('[id^="delete_field_"]').attr('id', `delete_field_${newCounter}`);
                $(this).find('.delete-field-btn').attr('data-counter', newCounter);

                newCounter++;
            });
        }

        // Show options container and add_more_options based on field type
        $(document).off('change', '[id^="type_"]').on('change', '[id^="type_"]', function() {
            const counter = $(this).closest('.optionsa').data('counter');
            const type = $(this).val();
            console.log(counter);
            if (type !== 'single_choice' && type !== 'multiple_choice') {
                $(`#options_container_${counter}`).hide(500);
                $(`#option_${counter}`).hide(500);
                $(`#add_more_options_${counter}`).hide(500);
            } else {
                $(`#options_container_${counter}`).show(500);
                $(`#option_${counter}`).show(500);
                $(`#add_more_options_${counter}`).show(500);
            }
        });


        $(document).off('click', '[id^="add_option_"]').on('click', '[id^="add_option_"]', function() {
            const counter = $(this).closest('.optionsa').data('counter');
            const optionsContainer = $(`#options_container_${counter}`);
            const optionCount = optionsContainer.find('.option_resp').length + 1;

            let newOption;
            if (optionCount === 1) {
                // First option, no delete button
                newOption = `
                <div class="col-sm-12 mb-3 option_resp" id="option_${counter}_${optionCount}">
                    <div class="d-flex align-items-center mb-2">
                        <label class="me-2">Option</label>
                        <input class="form-control" type="text" name="options[${counter}][]" id="option_input_${counter}_${optionCount}">
                    </div>
                </div>`;
            } else {
                // Subsequent options, with delete button
                newOption = `
                <div class="col-sm-12 mb-3 option_resp" id="option_${counter}_${optionCount}">
                    <div class="d-flex align-items-center mb-2">
                        <label class="me-2">Option</label>
                        <input class="form-control" type="text" name="options[${counter}][]" id="option_input_${counter}_${optionCount}">
                        <button class="btn btn-outline-danger btn-sm ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" id="delete_option_${counter}_${optionCount}" title="Delete Option">
                            <i class="icon ni ni-trash"></i>
                        </button>
                    </div>
                </div>`;
            }

            // Append the new option to the options container
            optionsContainer.append(newOption);
        });

        // Delegate the click event for dynamically added delete option buttons
        $(document).on('click', '[id^="delete_option_"]', function() {
            $(this).closest('.option_resp').remove();
        });


        // Initialize fields if editing
        initializeFields();
        $('#modal').on('hide.bs.modal', function () {
            resetCounters();
            $('.options-container').empty();
        });
    });
</script>
