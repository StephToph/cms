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
                                <h3 class="nk-block-title page-title"><?=ucwords(ucwords($church).' Membership Form'); ?></h3>
                                
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
                                                    <h6 class="title my-1"><?=translate_phrase('Membership');?></h6>
                                                </div>
                                                <div class="card-tools"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-3">
                                            <?php echo form_open_multipart('attendance/member', array('id'=>'bb_ajax_form', 'class'=>'row')); ?>
                                            
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
                                            
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label">Title</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select js-select2" name="title" id="title" data-placeholder="Select Title" required>
                                                                    <option value="">Select Title</option>
                                                                    <option value="Brother" <?php if(!empty($e_title)){if($e_title ==  'Brother'){echo 'selected';}}; ?>>Brother</option>
                                                                    <option value="Sister" <?php if(!empty($e_title)){if($e_title ==  'Sister'){echo 'selected';}}; ?>>Sister</option>
                                                                    <option value="Pastor" <?php if(!empty($e_title)){if($e_title ==  'Pastor'){echo 'selected';}}; ?>>Pastor</option>
                                                                    <option value="Elder" <?php if(!empty($e_title)){if($e_title ==  'Elder'){echo 'selected';}}; ?>>Elder</option>
                                                                    <option value="Deacon" <?php if(!empty($e_title)){if($e_title ==  'Deacon'){echo 'selected';}}; ?>>Deacon</option>
                                                                    <option value="Deaconess" <?php if(!empty($e_title)){if($e_title ==  'Deaconess'){echo 'selected';}}; ?>>Deaconess</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label"
                                                                for="last-name">Last Name</label><input type="text"
                                                                class="form-control" name="lastname" id="last-name" value="<?php if(!empty($e_lastname)){echo $e_lastname;} ?>" placeholder="Last Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label"
                                                                for="first-name">First Name</label><input type="text"
                                                                class="form-control" name="firstname" id="first-name"
                                                                placeholder="First Name" value="<?php if(!empty($e_firstname)){echo $e_firstname;} ?>" required></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label"
                                                                for="last-name">Other Name</label><input type="text"
                                                                class="form-control" name="othername" id="last-name" value="<?php if(!empty($e_othername)){echo $e_othername;} ?>" placeholder="Last Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label">Gender</label>
                                                            <div class="form-control-wrap">
                                                                <select
                                                                    class="form-select js-select2" name="gender" required
                                                                    data-placeholder="Select Gender">
                                                                    <option value="">Select Gender</option>
                                                                    <option value="Male" <?php if(!empty($e_gender)){if($e_gender == 'Male'){echo 'selected';}}?>>Male</option>
                                                                    <option value="Female" <?php if(!empty($e_gender)){if($e_gender == 'Female'){echo 'selected';}}?>>Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label"
                                                                for="phone-no">Phone</label><input type="number"
                                                                class="form-control" name="phone" id="phone-no" value="<?php if(!empty($e_phone)){echo $e_phone;} ?>" placeholder="Phone no">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label" for="email">Email  Address</label><input type="email" name="email" class="form-control" 
                                                                id="email" value="<?php if(!empty($e_email)){echo $e_email;} ?>" placeholder="Email Address"></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label" for="email">Kingchat Handle</label><input type="text" name="chat_handle" class="form-control" value="<?php if(!empty($e_chat_handle)){echo $e_chat_handle;} ?>" id="email" placeholder="Kingchat Handle"></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label">Birth Date</label>
                                                            <div class="form-control-wrap">
                                                                <div class="form-icon form-icon-right"><em
                                                                        class="icon ni ni-calendar"></em></div><input
                                                                    type="text" name="dob" value="<?php if(!empty($e_dob)){echo $e_dob;} ?>" class="form-control date-picker"
                                                                    data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label"
                                                                for="address">Adddress</label><input type="text" value="<?php if(!empty($e_address)){echo $e_address;} ?>"
                                                                class="form-control" name="address" id="address" placeholder="Address">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label">Family Status</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select js-select2" id="family_status" name="family_status"
                                                                    data-placeholder="Select Status" onchange="">
                                                                    <option value="">Select</option>
                                                                    <option value="single" <?php if(!empty($e_family_status)){if($e_family_status == 'single'){echo 'selected';}} ?>>Single </option>
                                                                    <option value="married" <?php if(!empty($e_family_status)){if($e_family_status == 'married'){echo 'selected';}} ?>>Married </option>
                                                                    <option value="sepreated" <?php if(!empty($e_family_status)){if($e_family_status == 'seperated'){echo 'selected';}} ?>>Seperated </option>
                                                                    <option value="divorced" <?php if(!empty($e_family_status)){if($e_family_status == 'divorced'){echo 'selected';}} ?>>Divorced </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3 marriedDiv"  style="display:none">
                                                        <div class="form-group"><label class="form-label">Spouse</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select js-select2"  name="spouse_id" id="spouse_id"  data-search="on"
                                                                    data-placeholder="Select Spouse">
                                                                    <option value="">Select</option>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3 marriedDiv" id="marriedDiv" style="display: none">
                                                        <div class="form-group"><label class="form-label">Marriage Anniverary</label>
                                                            <div class="form-control-wrap">
                                                                <div class="form-icon form-icon-right"><em
                                                                        class="icon ni ni-calendar"></em></div><input
                                                                    type="text" name="marriage_anniversary" value="<?php if(!empty($e_marriage_anniversary)){echo $e_marriage_anniversary;} ?>" class="form-control date-picker"
                                                                    data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label">Family Unit Position</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select js-select2" id="family_position" name="family_position"
                                                                    data-placeholder="Select Position" onchange="posit();">
                                                                    <option value="">Select</option>
                                                                    <option value="Child" <?php if(!empty($e_family_position)){if($e_family_position == 'Child'){echo 'selected';}} ?>>Child </option>
                                                                    <option value="Parent" <?php if(!empty($e_family_position)){if($e_family_position == 'Parent'){echo 'selected';}} ?>>Parent </option>
                                                                    <option value="Other" <?php if(!empty($e_family_position)){if($e_family_position == 'Other'){echo 'selected';}} ?>>Other </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3" id="parent_resp" style="display:none;">
                                                        <div class="form-group"><label class="form-label">Parent</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select js-select2" id="parent_id" name="parent_id"  data-search="on"
                                                                    data-placeholder="Select Parent">
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                        $parent  = $this->Crud->read2_order('church_id', $church_id, 'family_position', 'Parent', 'user', 'surname', 'asc');
                                                                        if(!empty($parent)){
                                                                            foreach($parent as $p){
                                                                                $sel = '';
                                                                                if(!empty($e_parent_id)){
                                                                                    if($e_parent_id == $p->id)$sel = 'selected';
                                                                                }
                                                                                echo '<option value="'.$p->id.'">'.ucwords($p->surname.' '.$p->firstname.' - '.$p->phone).'</option>';
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="password">Occupation</label>
                                                            <input class="form-control" type="text" id="job_type" name="job_type" value="<?php if(!empty($e_job_type)){echo $e_job_type;}?>" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="password">Company</label>
                                                            <input class="form-control" type="text" id="employer_address" name="employer_address" value="<?php if(!empty($e_employer_address)){echo $e_employer_address;}?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label">Department</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select js-select2" id="dept_id" name="dept_id[]" multiple data-placeholder="Select Department" onchange="dept_role();">
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                        $parent  = $this->Crud->read_order('dept', 'name', 'asc');
                                                                        if(!empty($parent)){
                                                                            foreach($parent as $p){
                                                                                $sel = '';
                                                                                if(!empty($e_dept_id)){
                                                                                    if($e_dept_id == $p->id){
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
                                                    </div>

                                                    <div class="col-12" style="display: none;" id="dept_display">
                                                        <div class="row" id="dept_roles_container"></div>
                                                    </div>

                                                    
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group"><label class="form-label">Cell</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select js-select2" id="cell_id" name="cell_id"
                                                                    data-placeholder="Select Cell">
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                        
                                                                        $parent  = $this->Crud->read_single_order('church_id',  $church_id, 'cells', 'name', 'asc');
                                                                        if(!empty($parent)){
                                                                            foreach($parent as $p){
                                                                                $sel = '';
                                                                                if(!empty($e_cell_id)){
                                                                                    if($e_cell_id == $p->id){
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
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3" id="cell_resp">
                                                        <div class="form-group">
                                                            <label class="form-label">Cell Role</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select js-select2" id="cell_role_i" name="cell_role_id"
                                                                    data-placeholder="Select Cell Role">
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                        $allowed = ['Cell Leader', 'Cell Executive', 'Assistant Cell Leader', 'Cell Member'];

                                                                        $parent  = $this->Crud->read_order('access_role', 'name', 'asc');
                                                                        if(!empty($parent)){
                                                                            foreach($parent as $p){
                                                                                if(!in_array($p->name, $allowed))continue;
                                                                                $sel = '';
                                                                                if(!empty($e_cell_role)){
                                                                                    if($e_cell_role == $p->id){
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
                                                    </div>
                                                    
                                                
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group">
                                                            <label  class="form-label">Foundation School</label>
                                                            <select class="js-select2" name="foundation_school" id="foundation_school" data-placeholder="Select" >
                                                                <option value="0" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '0'){echo 'selected';}}; ?>>Prospective Student</option>
                                                                <option value="1" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '1'){echo 'selected';}}; ?>>Student</option>
                                                                <option value="2" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '2'){echo 'selected';}}; ?>>Graduate</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3" style="display:none;" id="foundation_resp">
                                                        <div class="form-group">    
                                                            <label class="form-label">Foundation Week</label>    
                                                            <div class="input-group mb-3">
                                                            
                                                                <input type="number" class="form-control text-center" name="foundation_weeks" id="" max="7" min="1" step="1" value="<?php if(!empty($e_foundation_weeks)){echo $e_foundation_weeks;}else{echo '1';}; ?>">
                                                                
                                                                
                                                            </div>
                                                        </div> 
                                                    </div>
                                                    <div class="col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <div class="form-group">
                                                            <div class="col-sm-12 mb-2">
                                                                <label  class="form-label">Are you Baptised?</label><br>
                                                                <div class="custom-control custom-radio">    
                                                                    <input type="radio" id="customRadio12" value="yes" name="baptism"  <?php if(!empty($e_baptism)){if($e_baptism == 'yes'){echo 'checked';}} ?> class="custom-control-input">    
                                                                    <label class="custom-control-label" for="customRadio12">Yes</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">     
                                                                    <input type="radio" id="customRadio22" value="no" name="baptism"  <?php if(!empty($e_baptism)){if($e_baptism == 'no'){echo 'checked';}} ?> class="custom-control-input">    
                                                                    <label class="custom-control-label" for="customRadio22">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-2 col-md-6 mb-3 col-lg-4 col-xxl-3">
                                                        <label for="img-upload" class="pointer text-center" style="width:50%;">
                                                            <input type="hidden" name="img_id" value="<?php if(!empty($e_img_id)){echo $e_img_id;} ?>" />
                                                            <img id="img0" src="<?php if(!empty($e_img_id) && file_exists($e_img_id)){echo site_url($e_img_id);} else {echo site_url('assets/images/avatar.png');} ?>" style="max-width:100%;" />
                                                            <span class="btn btn-default btn-block no-mrg-btm d-grid btn btn-secondary waves-effect"><i class="mdi mdi-cloud-upload me-1"></i><?=translate_phrase('Choose Image'); ?></span>
                                                            <input class="d-none" type="file" name="pics" id="img-upload" accept="image/*">
                                                            
                                                        </label>
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
   function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    if(id != 'vid') {
                        $('#' + id).attr('src', e.target.result);
                    } else {
                        $('#' + id).show(500);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        $("#img-upload").change(function(){
            readURL(this, 'img0');
        });
        $(function() {
            $('#family_status').on('change', function(){
                var selectedValue = $(this).val();
                if(selectedValue === 'married') {
                    $('.marriedDiv').show(500);
                } else {
                    $('.marriedDiv').hide(500);
                }
            });
            $('#foundation_school').on('change', function () {
                var selectedType = $(this).val();
                if (selectedType == '1') {
                    $('#foundation_resp').show(500);
                } else{
                    $('#foundation_resp').hide(500);
                }
                
            });
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
        });
        function cell_role(cell){
            var cell_id = $('#cell_id').val();
            $.ajax({
                url: site_url + 'accounts/membership/get_cell_role/' + cell_id+ '/'+ cell,
                type: 'get',
                success: function (data) {
                    var dt = JSON.parse(data);
                    $('#cell_role_id').html(dt.list);
                    $('#bb_ajax_msg').html(dt.script);
                    
                }
            });
        }

        function posit(){
            var position = $('#family_position').val();
            if(position == 'Child'){
                $('#parent_resp').show(500);
            } else{
                $('#parent_resp').hide(500);
            }
        }

        var church_id = $('#church_id').val();
        var ministry_id = $('#ministry_id').val();
        
        getSpouse(church_id, ministry_id);
        
        function getSpouse(churchId, ministryId) {
            // Ensure church and ministry IDs are provided
            if (!churchId && !ministryId) {
                $('#spouse_id').html('<option value="">Select Spouse</option>');
                return;
            }
            var spouse_id = '<?= !empty($e_spouse_id) ? $e_spouse_id : ""; ?>';

            $.ajax({
                url: site_url + 'accounts/membership/get_spouse/' + churchId + '/' + ministryId,
                type: 'get',
                success: function (data) {
                    try {
                        var response = JSON.parse(data);

                        // Populate parent dropdown
                        var options = '<option value="">Select Spouse</option>';
                        response.forEach(function (parent) {
                            // Check if the current parent ID matches the selected spouse_id
                            var selected = parent.id === spouse_id ? 'selected' : '';
                            options += `<option value="${parent.id}" ${selected}>${parent.name}</option>`;
                        });
                        
                        $('#spouse_id').html(options);
                    
                    } catch (error) {
                        console.error('Error parsing response:', error);
                        $('#spouse_id').html('<option value="">Error loading Spouse</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#spouse_id').html('<option value="">Error fetching data</option>');
                }
            });
        }

            
            
        function dept_role() {
            const dept_ids = $('#dept_id').val(); // Get selected departments
            $('#dept_display').hide(500);
            const container = $('#dept_roles_container');
            container.empty(); // Clear current role fields

            if (dept_ids.length === 0) return;

            // Loop through each selected dept ID and fetch roles
            dept_ids.forEach(dept_id => {
                $.ajax({
                    url: "<?= site_url('accounts/membership/get_dept_role') ?>", // Adjust this URL to your route
                    method: 'POST',
                    data: { dept_id: dept_id },
                    success: function(response) {
                        // Assume response is an array of role objects { id, name }
                        $('#dept_display').show(500);
                        let options = '<option value="">Select Role</option>';
                        if (response.length > 0) {
                            response.forEach(role => {
                                options += `<option value="${role.name}">${role.name}</option>`;
                            });
                        }

                        const deptName = response[0]?.department_name || 'Department';
                        const html = `
                            <div class="col-md-6 mb-3 col-lg-4 col-xxl-3 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Role in ${deptName}</label>
                                    <div class="form-control-wrap">
                                        <select class="form-select js-select2" name="dept_role_id[${dept_id}]" >
                                            ${options}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        `;

                        container.append(html);
                        $('.js-select2').select2(); // Re-initialize select2 for new fields
                    }
                });
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