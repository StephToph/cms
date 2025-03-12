
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
                <h3><b><?=translate_phrase('Are you sure?');?></b></h3>
                <input type="hidden" name="d_giving_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete');?>
                </button>
            </div>
        </div>
    <?php } ?>

    
    <?php if($param2 == 'view'){?>
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
                    if(!empty($pays)){
                        foreach($pays as $p){
                            $time = $p->time;
                            if(!empty(json_decode($time))){
                                foreach(json_decode($time) as $t => $val){
                        
                            ?>
                                <tr>
                                    <td><?=$t ?></td>
                                    <td><?=date('h:iA', strtotime($val)); ?></td>
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
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        
        <div class="row">
            <input type="hidden" name="giving_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <?php
                $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

                if ($ministry_id > 0) { ?>
                    <input type="hidden" name="ministry_id" id="ministry_id" value="<?php echo $ministry_id; ?>">
                    <input type="hidden" name="church_id" value="<?php echo $church_id; ?>">
                <?php } else { if(empty($ministry_id)){?>
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

                <?php }} ?>

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

                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Members'); ?></label>
                            <select id="member_id" name="member_id" data-search="on" class="js-select2" required>
                                <option value="">Select</option>
                                
                            </select>
                        </div>
                    </div>
                <?php }  else { ?>
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Members'); ?></label>
                            <select id="member_ids" name="member_id" class="js-select2" required>
                                <option value="">Select</option>
                                <?php
                                    $role_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
                                    $part = $this->Crud->read2_order('church_id', $church_id, 'is_member', 1, 'user', 'surname', 'asc');
                                    if(!empty($part)){
                                        foreach($part as $p){
                                            $sel = '';
                                            if(!empty($e_member_id)){
                                                if($e_member_id == $p->id){
                                                    $sel = 'selected';
                                                }
                                            }
                                            echo '<option value="'.$p->id.'" '.$sel.'>'.ucwords($p->firstname.' '.$p->surname.' - '.$p->phone).'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
            <?php } ?>
            <div class="col-sm-12 mb-3">
                <div class="form-group">*<label class="form-label">Date Paid</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right"><em class="icon ni ni-calendar"></em> </div>
                        <input type="text" name="date_paid" value="<?php if(!empty($e_date_paid)){echo $e_date_paid;} ?>" class="form-control date-picker" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" required>
                    </div>
                </div>
            </div>
           
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Partnership'); ?></label>
                    <select id="role_id" name="partnership_id" class="js-select2" required>
                        <option value="">Select</option>
                        <?php
                            $part = $this->Crud->read_order('partnership', 'name', 'asc');
                            if(!empty($part)){
                                foreach($part as $p){ 
                                    $sel = '';
                                    if(!empty($e_partnership_id)){
                                        if($e_partnership_id == $p->id){
                                            $sel = 'selected';
                                        }
                                    }
                                    echo '<option value="'.$p->id.'" '.$sel.'>'.ucwords($p->name).'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Amount'); ?></label>
                    <input class="form-control" type="text" id="amount" name="amount" value="<?php if(!empty($e_amount_paid)) {echo $e_amount_paid;} ?>" required>
                </div>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Currency'); ?></label>
                    <select id="currency" name="currency" class="js-select2">
                        
                    </select>
                </div>
            </div>

            <?php if($role == 'member'){?>
                <div class="col-sm-12 mb-3">
                    <div class="form-group"><b>Upload Receipt </b><span class="text-danger small">(Screenshot)</span><br>
                        <label for="img-upload" class="pointer text-center" style="width:100%;">
                            <input type="hidden" name="img" value="<?php if(!empty($e_img)){echo $e_img;} ?>" />
                            <img id="img" src="<?php if(!empty($e_img)){echo site_url( $e_img);} ?>" style="max-width:100%;" />
                            <span class="btn btn-info btn-block no-mrg-btm">Upload Screenshot</span>
                            <input class="d-none" type="file" name="pics" accept="image/*" id="img-upload">
                        </label>
                    </div>
                </div>
            <?php } ?>
            <?php if($role != 'member'){?>
                <div class="col-sm-12 mb-3">
                    <div class="form-group">
                        <label for="name">*<?=translate_phrase('Payment Status'); ?></label>
                        <select id="status" name="status" class="js-select2">
                            <option value="0" <?php if(!empty($e_status)){if($e_status == 0){echo 'selected';}} ?>>Pending</option>
                            <option value="1" <?php if(!empty($e_status)){if($e_status == 1){echo 'selected';}} ?>>Confirmed</option>
                        </select>
                    </div>
                </div>
            <?php } else{?>
                <input type="hidden" name="status" value="0">
            <?php } ?>
            
        </div>

        <div class="row" >
            
            <div class="col-sm-12 text-center mt-5">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 my-3"><div id="bb_ajax_msg"></div></div>
        </div>

        
    <?php } ?>
<?php echo form_close(); ?>
<script>
    $('.js-select2').select2();
    $('.date-picker').datepicker();        
    function readURL(input, id) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#' + id).attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#img-upload").change(function(){
		readURL(this, 'img');
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

    // Load churches on ministry selection change
    $('#ministry_id').change(function () {
        var selectedMinistryId = $(this).val();
        var selectedLevel = $('#level').val();
        loadChurches(selectedMinistryId, selectedLevel);
    });

     // Load churches on ministry selection change
     $('#church_id').change(function () {
        var selectedChurch = $(this).val();
        loadMember(selectedChurch);
    });

   

    <?php
           $e_ministry_ids = !empty($e_ministry_id) ? $e_ministry_id : 0;
           $e_church_ids = !empty($e_church_id) ? $e_church_id : 0;
           $e_member_ids = !empty($e_member_id) ? $e_member_id : 0;
           $e_levels = !empty($e_level) ? $e_level : '';
        ?>
        var eMinistryId = <?php echo $e_ministry_ids; ?>;
        var eChurchId = <?php echo $e_church_ids; ?>;
        var eMemberId = <?php echo $e_member_ids; ?>;
        var eLevel= '<?php echo $e_levels; ?>';

        <?php if($param2 == 'edit'){?>
            $(function() {
                loadMember(eChurchId);
                loadChurches(eMinistryId,eLevel);
            });
   
           
        <?php }?>
        
        // Helper function to convert strings to title case
         function toTitleCase(str) {
            return str.toLowerCase().replace(/(?:^|\s)\S/g, function (a) { return a.toUpperCase(); });
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
                            $('#church_id').append(new Option('Select Church', '', false, false));
                            var eChurchIds = '<?php echo $e_church_ids; ?>';
        
                           // Populate the Church dropdown with the data received
                            $.each(response.data, function (index, churchs) {
                                var selected = (eChurchIds === churchs.id) ? 'selected' : ''; // Check if the ID matches
                                console.log(churchs.id);
                               
                                var churchName = toTitleCase(churchs.name); // Convert name to title case
                                var churchType = toTitleCase(churchs.type); // Convert type to title case
                                $('#church_id').append(new Option(churchName + ' - ' + churchType, churchs.id, selected, selected));
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

        // Function to load churches based on selected ministry ID and/or level
        function loadMember(churchId) {
            // Clear the Church dropdown
            $('#member_id').empty();
            $('#member_id').append(new Option('Loading...', '', false, false));
            $('#currency').empty();
            $('#currency').append(new Option('Loading...', '', false, false));

            // Construct data object based on provided parameters
            var data = {};
            if (churchId) {
                data.church_id = churchId;
            }

            // Proceed if there's data to be sent
            if (Object.keys(data).length > 0) {
                $.ajax({
                    url: site_url + 'ministry/announcement/get_members', // Update this to the path of your API endpoint
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function (response) {
                        $('#member_id').empty(); // Clear 'Loading...' option
                        $('#currency').empty(); // Clear 'Loading...' option

                        if (response.success) {
                            $('#member_id').append(new Option('Select Members', '', false, false));
                            
                            var eMemberId = '<?php echo $e_member_ids; ?>';
                           // Populate the Church dropdown with the data received
                           $.each(response.data, function (index, member) {
                                var selected = (eMemberId === member.id); // Check if the ID matches
                                var memberName = toTitleCase(member.name); // Convert name to title case
                                var churchType = (member.phone); // Convert type to title case
                                $('#member_id').append(new Option(memberName + ' - ' + churchType, member.id, selected, selected));
                            });

                            $.each(response.currency, function (index, member) {
                                var currencyName = toTitleCase(member); // Convert currency name to title case
                                var selected = (index !== 0); // Check if the ID matches
                                
                                var currencyId = index; // Get currency ID
                                $('#currency').append(new Option(currencyName, currencyId, selected, selected));
                            });

                        } else {
                            $('#member_id').append(new Option('No Members available', '', false, false));
                        }
                    },
                    error: function () {
                        $('#member_id').append(new Option('Error fetching MEmbers', '', false, false));
                    }
                });
            } else {
                $('#member_id').append(new Option('Please select a Church', '', false, false));
            }
        }

      

</script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script