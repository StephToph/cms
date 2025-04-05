
<?php
use App\Models\Crud;
$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param2 == 'delete') {?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?=translate_phrase('Are you sure?');?></b></h3>
                <input type="hidden" name="d_membership_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete'); ?>
                </button>
            </div>
        </div>
    <?php } ?>


    <?php if($param2 == 'upload') { ?>
        <div class="row">
            <ul class="text-danger small mb-3">
                <li>To Upload Bulk Product; Be sure its a Valid Excel File</li>
                <li>Step to Design Excel Sheet for Bulk Upload;</li>
                <li><br></li>
                <li>Download the Bulk Upload Template and use it as guide for uploading memberships</li>
                <li>Do not Edit or Move the Headers</li>
            </ul>
            
            <div class="col-sm-12 mt-3 mb-3 text-center">
                <a href="javascript:void(0);" class="btn btn-success text-uppercase" onclick="downloadProductTemplate()" type="button">
                    <em class="icon ni ni-download"></em><span> Download Membership Upload Template</span>
                </a>
            </div>
            
            <div class="col-sm-12 text-cente">
                <div class="form-group">
                    <label for="activate">Excel File</label>
                    <input type="file" class="form-control" name="csv_file" id="csv_file" required accept=".xlsx" />
                </div>
            </div>
            
            <div class="col-sm-12 mt-3 mb-3 text-center">
                <button class="btn btn-info text-uppercase" type="submit">
                    <i class="icon ni ni-upload"></i> <span>Yes - Upload</span>
                </button>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>
    <?php } ?>
    <?php if($param2 == 'view'){?>
        <b><?=ucwords($this->Crud->read_field('id', $param3, 'partnership', 'name')).'`s Partnership History '; ?></b><br>
        <div class="table-responsive">
            <table id="dtable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Members</th>
                        <th>Amount Paid</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $pays = $this->Crud->read2('member_id', $param4,'partnership_id', $param3, 'partners_history');

                        $total = 0;
                        if(!empty($pays)){
                            foreach($pays as $p){
                                $time = $p->reg_date;
                                $member_id = $p->member_id;
                                $amount_paid = $p->amount_paid;
                                $status = $p->status;
                                $st = '<span class="text-warning">Pending</span>';
                                if($status > 0)$st = '<span class="text-success">Confirmed</span>';
                            
                                ?>
                                    <tr>
                                        <td><?=date('d M Y h:iA', strtotime($time)); ?></td>
                                        <td><?=ucwords($this->Crud->read_field('id', $member_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $member_id, 'user', 'surname')); ?></td>
                                        <td><?='$'.number_format($amount_paid,2); ?></td>
                                        <td><?=''.($st); ?></td>
                                    </tr>
                        <?php
                                    }
                                
                            
                        } else{
                            echo '<tr><td colspan="3" class="text-center">No Records</td></tr>';
                        }
                        
                    ?>
                </tbody>
            </table>
        </div>

    <?php } ?>
    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row gy-4">
            <input type="hidden" name="membership_id" value="<?php if(!empty($e_id)){echo $e_id;}?>">
            <?php
                $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                $roles_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
                $roles = $this->Crud->read_field('id', $roles_id, 'access_role', 'name');
                ?>
                <input type="hidden" name="ministry_id"  id="ministry_id"  value="<?php echo $ministry_id; ?>">
                <input type="hidden" name="church_id" id="church_id" value="<?php echo $church_id; ?>">
            
            <div class="col-md-6 col-lg-4 col-xxl-3">
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
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group"><label class="form-label"
                        for="last-name">Last Name</label><input type="text"
                        class="form-control" name="lastname" id="last-name" value="<?php if(!empty($e_lastname)){echo $e_lastname;} ?>" placeholder="Last Name" required>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group"><label class="form-label"
                        for="first-name">First Name</label><input type="text"
                        class="form-control" name="firstname" id="first-name"
                        placeholder="First Name" value="<?php if(!empty($e_firstname)){echo $e_firstname;} ?>" required></div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group"><label class="form-label"
                        for="last-name">Other Name</label><input type="text"
                        class="form-control" name="othername" id="last-name" value="<?php if(!empty($e_othername)){echo $e_othername;} ?>" placeholder="Last Name">
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
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
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group"><label class="form-label"
                        for="phone-no">Phone</label><input type="number"
                        class="form-control" name="phone" id="phone-no" value="<?php if(!empty($e_phone)){echo $e_phone;} ?>" placeholder="Phone no">
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group"><label class="form-label" for="email">Email
                        Address</label><input type="email" name="email" class="form-control" 
                        id="email" value="<?php if(!empty($e_email)){echo $e_email;} ?>" placeholder="Email Address"></div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group"><label class="form-label" for="email">Kingchat Handle</label><input type="text" name="chat_handle" class="form-control" value="<?php if(!empty($e_chat_handle)){echo $e_chat_handle;} ?>" id="email" placeholder="Kingchat Handle"></div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group"><label class="form-label">Birth Date</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right"><em
                                class="icon ni ni-calendar"></em></div><input
                            type="text" name="dob" value="<?php if(!empty($e_dob)){echo $e_dob;} ?>" class="form-control date-picker"
                            data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group"><label class="form-label"
                        for="address">Adddress</label><input type="text" value="<?php if(!empty($e_address)){echo $e_address;} ?>"
                        class="form-control" name="address" id="address" placeholder="Address">
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
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
            
            <div class="col-md-6 col-lg-4 col-xxl-3 marriedDiv"  style="display:none">
                <div class="form-group"><label class="form-label">Spouse</label>
                    <div class="form-control-wrap">
                        <select class="form-select js-select2"  name="spouse_id" id="spouse_id"  data-search="on"
                            data-placeholder="Select Spouse">
                            <option value="">Select</option>
                            
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3 marriedDiv" id="marriedDiv" style="display: none">
                <div class="form-group"><label class="form-label">Marriage Anniverary</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right"><em
                                class="icon ni ni-calendar"></em></div><input
                            type="text" name="marriage_anniversary" value="<?php if(!empty($e_marriage_anniversary)){echo $e_marriage_anniversary;} ?>" class="form-control date-picker"
                            data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
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

            
            <div class="col-md-6 col-lg-4 col-xxl-3" id="parent_resp" style="display:none;">
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
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group">
                    <label class="form-label" for="password">Occupation</label>
                    <input class="form-control" type="text" id="job_type" name="job_type" value="<?php if(!empty($e_job_type)){echo $e_job_type;}?>" >
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group">
                    <label class="form-label" for="password">Company</label>
                    <input class="form-control" type="text" id="employer_address" name="employer_address" value="<?php if(!empty($e_employer_address)){echo $e_employer_address;}?>">
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
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

            
            <div class="col-md-6 col-lg-4 col-xxl-3">
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
            <div class="col-md-6 col-lg-4 col-xxl-3" id="cell_resp">
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
            
         
            <div class="col-md-6 col-lg-4 col-xxl-3">
                <div class="form-group">
                    <label  class="form-label">Foundation School</label>
                    <select class="js-select2" name="foundation_school" id="foundation_school" data-placeholder="Select" >
                        <option value="0" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '0'){echo 'selected';}}; ?>>Prospective Student</option>
                        <option value="1" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '1'){echo 'selected';}}; ?>>Student</option>
                        <option value="2" <?php if(!empty($e_foundation_school)){if($e_foundation_school ==  '2'){echo 'selected';}}; ?>>Graduate</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 col-xxl-3" style="display:none;" id="foundation_resp">
                <div class="form-group">    
                    <label class="form-label">Foundation Week</label>    
                    <div class="input-group mb-3">
                       
                        <input type="number" class="form-control text-center" name="foundation_weeks" id="" max="7" min="1" step="1" value="<?php if(!empty($e_foundation_weeks)){echo $e_foundation_weeks;}else{echo '1';}; ?>">
                        
                        
                    </div>
                </div> 
            </div>
            <div class="col-md-6 col-lg-4 col-xxl-3">
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
            <?php 
                $service_count = $this->Crud->check3('status', 0, 'date', date('Y-m-d'), 'church_id', $church_id, 'service_report');
                if($service_count > 1){
                    echo  '<div class="col-sm-3 mb-3">
                        <label  class="form-label">Service</label><br>
                        <select class="js-select2" data-search="on" name="service" id="service">';
                            for ($i=0; $i < $service_count; $i++) { 
                                echo '<option value="'.($i+1).'">Service '.($i+1).'</option>';
                            }

                        echo '</select>
                    </div>';
                } else{
                    echo '<input type="hidden" name="service"  id="service" value="1">';
                }
            ?>
            <div class="mb-2 col-md-6 col-lg-4 col-xxl-3">
                <label for="img-upload" class="pointer text-center" style="width:50%;">
                    <input type="hidden" name="img_id" value="<?php if(!empty($e_img_id)){echo $e_img_id;} ?>" />
                    <img id="img0" src="<?php if(!empty($e_img_id) && file_exists($e_img_id)){echo site_url($e_img_id);} else {echo site_url('assets/images/avatar.png');} ?>" style="max-width:100%;" />
                    <span class="btn btn-default btn-block no-mrg-btm d-grid btn btn-secondary waves-effect"><i class="mdi mdi-cloud-upload me-1"></i><?=translate_phrase('Choose Image'); ?></span>
                    <input class="d-none" type="file" name="pics" id="img-upload" accept="image/*">
                    
                </label>
            </div>
            <div class="col-sm-12 mb-3 text-center">
                <div class="form-group  mt-4"><button type="submit"
                        class="btn btn-primary">Save Membership</button></div>
            </div>
        </div>
        <div class="row gy-4">
            <div class="col-sm-12 my-3"><div id="bb_ajax_msg"></div></div>
        </div>


    <?php } ?>
    <?php if($param2 == 'leaders') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Make this Member a Leader');?></label>
                    <select class="form-control js-select2" data-toggle="select2" id="is_leader" name="is_leader" required>
                        <option value="1" <?php if(!empty($e_is_leader)){if($e_is_leader == 1){echo 'selected';}} ?>><?=translate_phrase('Yes - Make a Leader');?></option>
                        <option value="0" <?php if(!empty($e_is_leader)){if($e_is_leader == 0){echo 'selected';}} ?>><?=translate_phrase('No - Remove from Leadership');?></option>
                    </select>
                </div>
            </div>


            <div class="col-sm-12 mt-3 text-center">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Update Record');?>
                </button>
            </div>
        </div>
    <?php } ?>
    <?php if($param2 == 'admin_send') { ?>
        <div class="row">
            <div class="col-sm-12">
                <div id="copyBody" style=""><?=($body); ?></div>
            </div>
            <div class="col-sm-12 text-center">
                
                <input type="hidden" name="admin_id" value="<?php if(!empty($param3)){echo $param3;} ?>" />
            </div>
            
            
            <div class="col-sm-6 text-center mt-4">
                <button class="btn btn-primary text-uppercase" type="button" id="copyBtn">
                    <em class="icon ni ni-pen"></em> <span><?=('Copy Login Details');?></span>
                </button>
            </div>

            <div class="col-sm-6 text-center mt-4">
                <button class="btn btn-success text-uppercase" type="submit">
                    <em class="icon ni ni-share-alt"></em> <span><?=('Send Login Details');?></span>
                </button>
                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>
    <?php } ?>
    <?php if($param2 == 'message') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            
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
        
    <?php if ($param2 == 'link') { ?>
        <div class="row">
            <?php
                $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

                if ($church_id > 0) { ?>
                    <input type="hidden" name="ministry_id" id="ministry_id" value="<?php echo $ministry_id; ?>">
                    <input type="hidden" name="church_id" id="church_id" value="<?php echo $church_id; ?>">
                <?php }?>
                   
                <?php if ($church_id == 0) { ?>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label class="form-label">Church Type</label>
                            <select class="js-select2" data-search="on" name="level" id="level">
                                <option value="">Select Church Level</option>
                                <?php
                                $log_church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                                $log_church_type = $this->Crud->read_field('id', $log_church_id, 'church', 'type');?>

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
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 mb-3" id="church_div">
                        <div class="form-group">
                            <label class="form-label">Church</label>
                            <select class="js-select2" data-search="on" name="church_id" id="church_id">
                                <option value="">Select</option>

                            </select>
                        </div>
                    </div>

                <?php }?>
                <p class="mb-3">Share this link with first timers or display the QR code below.</p>

                <div class="mb-3">
                <input type="text" id="firstTimerLink" class="form-control text-center" readonly
                    value="">
                </div>

                <div id="qrContainer" class="my-3 d-flex justify-content-center">
                    <!-- QR will be generated here -->
                    <div id="firstTimerQR"></div>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <button class="btn btn-outline-primary mx-1" onclick="copyFirstTimerLink()">Copy Link</button>
                    <button class="btn btn-outline-success mx-1" onclick="downloadQRCode()">Download QR Code</button>
                </div>

            </div>
                                    <!-- Include QRCode.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <?php } ?>
    
    <?php if($param2 == 'bulk_message') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <?php $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                if($church_id <= 0){?>
                <div class="col-sm-12 mb-2">
                    <div class="form-group">
                        <label for="activate"><?=translate_phrase('Show all Members?');?></label>
                        <select class="form-control js-select2" data-toggle="select2" id="include_church" name="include_church">
                            <option value="false"><?=translate_phrase('No - Display only members I oversee'); ?></option>
                            <option value="true"><?=translate_phrase('Yes - Display all members within my church and affiliated branches'); ?></option>
                        </select>
                    </div>
                </div>
                
                
            <?php } else { ?>
                <input type="hidden" id="include_church" value="false" />
            <?php } ?>
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="activate"><?=translate_phrase('Member');?></label>
                    <select class="form-control js-select2" multiple data-search="on" data-toggle="select2" id="member_id" name="member_id[]">
                        
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
    <?php if($param2 == 'bulk_qr') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>
        <div class="row">
            <input type="hidden" name="edit_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
        
            <!-- Send Type Selector -->
            <div class="col-sm-12 mb-2">
                <div class="form-group">
                    <label for="send_type"><?=translate_phrase('Send Type');?></label>
                    <select class="form-control js-select2" id="send_type" name="send_type">
                        <option value="manual"><?=translate_phrase('All Members');?></option>
                        <option value="date_range"><?=translate_phrase('Send to Members Registered Within Date Range');?></option>
                    </select>
                </div>
            </div>

            <!-- Date Range Selection -->
            <div class="col-sm-6 mb-2 send_type_field d-none" id="date_range_from">
                <div class="form-group">
                    <label for="date_from"><?=translate_phrase('Date From');?></label>
                    <input type="date" class="form-control" id="date_from" name="date_from" />
                </div>
            </div>

            <div class="col-sm-6 mb-2 send_type_field d-none" id="date_range_to">
                <div class="form-group">
                    <label for="date_to"><?=translate_phrase('Date To');?></label>
                    <input type="date" class="form-control" id="date_to" name="date_to" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-sm-12 mt-3 text-center">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-send"></i> <?=translate_phrase('Send Message');?>
                </button>
            </div>
        </div>

    <?php } ?>
<?php echo form_close(); ?>

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
<script>
     $('.date-picker').datepicker({
        format: 'yyyy-mm-dd', // Set the date format
        autoclose: true
    });
    <?php if($param2 == 'edit'){?>
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
                            <div class="col-md-6 col-lg-4 col-xxl-3 mb-3">
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
    <?php } ?>
     $(document).ready(function() {
        $('#copyBtn').on('click', function() {
            const content = document.getElementById('copyBody').innerText.trim();

            // Use the Clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(content).then(function() {
                    showCopySuccess();
                }).catch(function(err) {
                    fallbackCopyText(content); // fallback if permission denied
                });
            } else {
                fallbackCopyText(content); // fallback for non-secure context or unsupported
            }
        });

        function fallbackCopyText(text) {
            let textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showCopySuccess();
            } catch (err) {
                alert('Copy failed. Please try manually.');
            }
            document.body.removeChild(textArea);
        }

        function showCopySuccess() {
            $('#bb_ajax_msg').html('<div class="alert alert-success">Login details copied to clipboard!</div>');
            setTimeout(() => {
                $('#bb_ajax_msg').fadeOut('slow', function () {
                    $(this).html('').show();
                });
            }, 2000);
        }
    });
</script>
<script>
    $('.js-select2').select2();
    $('#message').summernote({
        height: 300, // Set the height of the editor
        tabsize: 2,
        focus: true
    });
    
    <?php if ($param2 == 'link') { ?>
    function updateFirstTimerDetails(churchId) {
        const $linkInput = $('#firstTimerLink');
        const $qrContainer = $('#firstTimerQR');
      
        if (!churchId) {
            $linkInput.val('');
            $qrContainer.empty();
            return;
        }

        // Send AJAX to backend to fetch or create URL
        $.ajax({
            url: "<?= site_url('accounts/membership/manage/link/generate') ?>", // Backend route
            type: "POST",
            dataType: "json",
            data: { church_id: churchId },
            success: function (response) {
                if (response.success && response.url) {
                    $linkInput.val(response.url);
                    $qrContainer.empty();

                    new QRCode($qrContainer[0], {
                        text: response.url,
                        width: 200,
                        height: 200,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                } else {
                    $linkInput.val('Error generating link');
                    $qrContainer.empty();
                }
            },
            error: function () {
                $linkInput.val('Server error');
                $qrContainer.empty();
            }
        });
    }

    // On document ready
    $(document).ready(function () {
        $('#church_id').on('change', function () {
            const selectedChurchId = $(this).val();
            updateFirstTimerDetails(selectedChurchId);
        });

        // Initial load (optional)
        const initialChurchId = $('#church_id').val();
        console.log(initialChurchId);
        if (initialChurchId) {
            updateFirstTimerDetails(initialChurchId);
        }
    });

    // Copy to clipboard
    function copyFirstTimerLink() {
        const $copyInput = $('#firstTimerLink');
        $copyInput.select();
        document.execCommand('copy');
        alert("Link copied to clipboard!");
    }

    
    function downloadQRCode() {
        const qrCanvas = $('#firstTimerQR canvas')[0];

        if (!qrCanvas) {
            alert("QR Code not available yet!");
            return;
        }

        const image = qrCanvas.toDataURL("image/png");
        const link = document.createElement('a');
        link.href = image;
        link.download = 'first_timer_qr.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    <?php } ?>
    $(document).ready(function() {
        $('#send_type').on('change', function() {
            var selected = $(this).val();
            if (selected === 'manual') {
                $('#manual_select').removeClass('d-none');
                $('#date_range_from, #date_range_to').addClass('d-none');
            } else if (selected === 'date_range') {
                $('#manual_select').addClass('d-none');
                $('#date_range_from, #date_range_to').removeClass('d-none');
            }
        }).trigger('change'); // Trigger change on page load
    });
    function downloadProductTemplate() {
        // Show loading state
        $('#bb_ajax_msg').html('<p class="text-info">Processing your request...</p>');

        $.ajax({
            url: site_url + 'accounts/membership/manage/upload/download',
            type: 'POST',
            xhrFields: {
                responseType: 'blob' // Important for binary file
            },
            success: function (data, status, xhr) {
                var disposition = xhr.getResponseHeader('Content-Disposition');
                var filename = 'membership_upload_template.xlsx'; // Default fallback

                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, '').trim();
                    }
                }

                var blob = new Blob([data], { type: xhr.getResponseHeader('Content-Type') });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
                document.body.appendChild(link); // Required for Firefox
                link.click();
                document.body.removeChild(link);

                // Show success
                $('#bb_ajax_msg').html('<p class="text-success">Template downloaded successfully!</p>');
            },
            error: function (xhr, status, error) {
                $('#bb_ajax_msg').html('<p class="text-danger">Error: Unable to download the template. Please try again later.</p>');
            }
        });
    }

    function statea() {
        var country = $('#country_id').val();
        $.ajax({
            url: '<?=site_url('accounts/get_state/');?>'+ country,
            success: function(data) {
                $('#state_resp').html(data);
            }
        });
        
    }

    function lgaa() {
        var lga = $('#state').val();
        $.ajax({
            url: '<?=site_url('accounts/get_lga/');?>'+ lga,
            success: function(data) {
                $('#lga_resp').html(data);
            }
        });
    }

    function branc() {
        var lgas = $('#lga').val();
        $.ajax({
            url: '<?=site_url('accounts/get_branch/');?>'+ lgas,
            success: function(data) {
                $('#branch_resp').html(data);
            }
        });
    }

    $(document).ready(function() {
        // Initialize select2 for member dropdown
        $('#member_id').select2({
            placeholder: 'Select Member(s)',
            allowClear: true
        });

        // Function to load members based on the selected option in include_church
        function loadMembers(includeChurch) {
            // Clear the member dropdown options
            $('#member_id').empty();

            $.ajax({
                url: '<?=site_url('accounts/membership/get_member'); ?>', // Replace with your actual API endpoint
                type: 'GET',
                data: {
                    include_church: includeChurch
                },
                success: function(response) {
                    // Clear the member_id dropdown
                    $('#member_id').empty();
                    $('#member_id').html(response);
                   
                  
                },
                error: function() {
                    alert('Failed to load members');
                }
            });
        }

        // Trigger loadMembers function on change of include_church dropdown
        $('#include_church').on('change', function() {
            const includeChurch = $(this).val() === 'true';
            loadMembers(includeChurch);
        });

        // Load members initially based on the default value of include_church
        const initialIncludeChurch = $('#include_church').val() === 'true';
        loadMembers(initialIncludeChurch);
    });


</script>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>