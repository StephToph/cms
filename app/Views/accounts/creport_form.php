
<?php
use App\Models\Crud;
$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form2', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param2 == 'delete') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg2"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?=translate_phrase('Are you sure?');?></b></h3>
                <input type="hidden" name="d_cell_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete');?>
                </button>
            </div>
        </div>
    <?php } ?>
        
     <!-- delete view -->
     <?php if($param2 == 'report') { ?>
        <ul class="nav nav-tabs">    
            <li class="nav-item">        
                <a class="nav-link active" data-bs-toggle="tab" href="#tabItem1"><em class="icon ni ni-reports"></em><span>Overview</span></a>    
            </li>    
            <li class="nav-item">        
                <a class="nav-link" data-bs-toggle="tab" href="#tabItem2"><em class="icon ni ni-users"></em><span>Attendance</span></a>    
            </li>    
            <li class="nav-item">        
                <a class="nav-link" data-bs-toggle="tab" href="#tabItem3"><em class="icon ni ni-user-add"></em><span>First Timer</span></a>    
            </li>    
            <li class="nav-item">       
                <a class="nav-link" data-bs-toggle="tab" href="#tabItem4"><em class="icon ni ni-repeat"></em><span>New Convert</span></a>    
            </li>
        </ul>
        <div class="tab-content">    
            <div class="tab-pane active" id="tabItem1">    
                <?php 
                    $r_id = $param3;
                    $reports = $this->Crud->read_single('id', $r_id, 'cell_report');
                    if(empty($reports)){
                        echo '
                            <div class="col-sm-12">No Record</div>
                        ';
                    } else{
                        foreach($reports as $r){
                            $types = '';
                            if($r->type == 'wk1')$types = 'WK1 - Prayer and Planning';
                            if($r->type == 'wk2')$types = 'Wk2 - Bible Study';
                            if($r->type == 'wk3')$types = 'Wk3 - Bible Study';
                            if($r->type == 'wk4')$types = 'Wk4 - Fellowship / Outreach';
                ?>    
                    <div class="row">
                        <div class="col-sm-3 mb-3">
                            <label class="fw-bold">Meeting Date</label>
                        </div>
                        <div class="col-sm-9 mb-3">
                            <p><?=date('d F Y', strtotime($r->date)); ?></p>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="fw-bold">Meeting</label>
                        </div>
                        <div class="col-sm-9 mb-3">
                            <p><?=$types; ?></p>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="fw-bold">Offering</label>
                        </div>
                        <div class="col-sm-9 mb-3">
                            <p><?='$'.number_format($r->offering, 2); ?></p>
                        </div>
                        
                        <div class="col-sm-3 mb-3">
                            <label class="fw-bold">Attendance</label>
                        </div>
                        <div class="col-sm-9 mb-3">
                            <p><?=$r->attendance; ?></p>
                        </div>
                        
                        <div class="col-sm-3 mb-3">
                            <label class="fw-bold">First Timer</label>
                        </div>
                        <div class="col-sm-9 mb-3">
                            <p><?=$r->first_timer; ?></p>
                        </div>
                        
                        <div class="col-sm-3 mb-3">
                            <label class="fw-bold">New Converts</label>
                        </div>
                        <div class="col-sm-9 mb-3">
                            <p><?=$r->new_convert; ?></p>
                        </div>
                        
                        <div class="col-sm-3 mb-3">
                            <label class="fw-bold">Note</label>
                        </div>
                        <div class="col-sm-9 mb-3">
                            <p><?=ucwords($r->note); ?></p>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="fw-bold">Input Date</label>
                        </div>
                        <div class="col-sm-9 mb-3">
                            <p><?=date('d M Y h:iA', strtotime($r->reg_date)); ?></p>
                        </div>
                        
                    </div>
                <?php } } ?>
            </div>    
            <div class="tab-pane" id="tabItem2">        
                <?php if(empty($reports)){
                    echo '
                        <div class="col-sm-12">No Record</div>
                    ';
                } else {
                    echo '<div class="row"> 
                        <div class="col-sm-3  mb-3">
                            <label class="fw-bold">Attendance</label>
                        </div>
                        <div class="col-sm-9  mb-3">
                            <p>'.$r->attendance.'</p>
                        </div>';
                    $attendant = json_decode($r->attendant);
                    if(!empty($attendant)){
                       
                        foreach($attendant as $at => $val){
                            $name = $this->Crud->read_field('id', $val, 'user', 'firstname').' '.$this->Crud->read_field('id', $val, 'user', 'surname');
                        ?>
                        <div class="col-sm-4 mb-2 border"><?=ucwords($name); ?></div>
                    <?php } echo '</div>';
                    } else {
                        echo '
                            <div class="col-sm-12">No Attendance Record</div>
                        ';
                    }
                }?>
            </div> 
            <div class="tab-pane" id="tabItem3">  
                <div class="row">      
                    <?php if(empty($guest_attendance)){
                        echo '
                            <div class="col-sm-12">No First Timer Record</div>
                        ';
                    } else {    

                        $general_response = '<div class="table-responsive"><table class="table table-hover">';
                        
                        if (!empty($guest_attendance)) {
                            
                            foreach ($guest_attendance as $q) {
                                $status = 'present';
                                
                                $general_response .= '
                                <tr>
                                    <td>' . ucwords(strtolower($q->fullname)).'</td>
                                    <td>'.($q->email).'</td>
                                    <td>'.($q->phone).'</td>
                                    <td>
                                        '.ucwords($status).'
                                    </td>
                                </tr>';
                            
                                
                            }
                            
                        }

                        $general_response .= '</table></div> 
                            ';
                        echo $general_response;
                    }?>
                </div>
            </div>    
            <div class="tab-pane" id="tabItem4">  
                <div class="row">      
                    <?php if(empty($convert_member) && empty($convert_guest)){
                        echo '
                            <div class="col-sm-12">No New Conver Record</div>
                        ';
                    } else {    

                        $general_response = '<div class="table-responsive"><table class="table table-hover">';
                        if (!empty($convert_member)) {
                            
                            foreach ($convert_member as $q) {
                                
                $status = strtolower($this->Crud->read_field2('member_id', $q->member_id, 'service_id', $service_report_id, 'service_attendance', 'status'));
                                // if ($status != 'present') continue;
                                // If absent, fetch the reason (optional)
                                $absent_reason = '';
                                if ($status == 'absent') {
                                    $absent_reason = $this->Crud->read_field2('member_id', $q->member_id, 'service_id', $service_report_id, 'service_attendance', 'reason');
                                }

                                $surname = $this->Crud->read_field('id', $q->member_id, 'user', 'surname');
                                $othername = $this->Crud->read_field('id', $q->member_id, 'user', 'othername');
                                $firstname = $this->Crud->read_field('id', $q->member_id, 'user', 'firstname');
                                $email = $this->Crud->read_field('id', $q->member_id, 'user', 'email');
                                $phone = $this->Crud->read_field('id', $q->member_id, 'user', 'phone');
                            
                                $general_response .= '
                                <tr>
                                    <td>' . ucwords(strtolower($firstname . ' ' . $surname . ' ' . $othername)) . '</td>
                                    <td>'.($email).'</td>
                                    <td>'.($phone).'</td>
                                    
                                </tr>';
                            
                                
                            }
                            
                        } 
                        if (!empty($convert_guest)) {
                            
                            foreach ($convert_guest as $q) {
                                $status = 'present';
                                
                                $general_response .= '
                                <tr>
                                    <td>' . ucwords(strtolower($q->fullname)).'</td>
                                    <td>'.($q->email).'</td>
                                    <td>'.($q->phone).'</td>
                                    
                                </tr>';
                            
                                
                            }
                            
                        }
                        $general_response .= '</table></div> 
                            ';
                        echo $general_response;
                    }?>
                </div>
            </div>
        </div>
    <?php } ?>
        
    
    <?php if($param2 == 'attendance'){?>
        <?php if(empty($param3)){?>
            <div class="row">
                <div class="col-sm-12 text-danger text-center">Select a Cell First</div>
            </div>
        <?php }else{?>
        <table id="dtable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Member</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // $param3.' '.$param4;
                    $cell_id = $param3;
                    $roles = $this->Crud->read_field('name', 'Member', 'access_role', 'id');

                    $user = $this->Crud->read_single_order('cell_id', $param3, 'user', 'firstname', 'asc');
                    $attends = json_decode($this->Crud->read_field('id', $param4, 'cell_report', 'attendant'));
                    // print_r($attends);
                    if(!empty($user)){
                        foreach($user as $p){
                            $sel = '';
                            if(!empty($attends)){
                                if(in_array($p->id, $attends)){
                                    $sel = 'checked';
                                }
                            }
                           
                            $img = $this->Crud->image($p->img_id, 'big');
                            ?>
                            <tr>
                                <td>
                                    <div class="user-card">
										<div class="user-avatar ">
											<img alt="" src="<?=site_url($img); ?>" height="40px"/>
										</div>
										<div class="user-info">
											<span class="tb-lead"><?=ucwords($p->firstname.' '.$p->surname).' '.$p->phone; ?></span>
										</div>
									</div>
                                </td>
                                <td align="right"> 
                                    <div class="custom-control custom-switch">    
                                        <input type="checkbox" name="mark[]" class="custom-control-input" id="customSwitch<?=$p->id;?>" <?=$sel; ?> value="<?=$p->id;?>">    
                                        <label class="custom-control-label" for="customSwitch<?=$p->id;?>">Mark</label>
                                    </div>
                                    
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="2" class="text-center">
                                <button class="btn btn-primary bb_fo_btn" type="submit">
                                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                                </button>
                            </td>
                        </tr> 
                   <?php } else{ ?>
                    <tr>
                        <td colspan="2">No Member in Cell</td>
                    </tr>
                  <?php  }
                       
                ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg2"></div></div>
        </div>
    <?php }} ?>

    
    <?php if($param2 == 'offering'){
        if(empty($param3)){?>
            <div class="row">
                <div class="col-sm-12 text-danger text-center">Select a Cell First</div>
            </div>
        <?php }else{
        // echo $table_rec;
        $first = json_decode($first);
        $total =0 ;
        $member = 0;
        $guest = 0;
        
        if($param4){
            $converts = json_decode($this->Crud->read_field('id', $param4, 'cell_report', 'offering_givers'));
            if(!empty($converts)){
                $total = $converts->total;
                $member = $converts->member;
                $guest = $converts->guest;
                $guest_list = (array)$converts->guest_list;
            }
          
            $first = json_decode($this->Crud->read_field('id', $param4, 'cell_report', 'timers'));
            
        }
        ?>
        <div class="row">
            <span class="text-danger mb-2">Enter Member's Offering in the Table Below</span>
            <div class="col-sm-4 mb-3 ">
                <label>Total</label>
                <input class="form-control" id="total_offering" type="text" name="total_offering"  readonly value="<?=($total); ?>">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Member</label>
                <input class="form-control" id="member_offering" type="text" name="member_offering"  readonly value="<?=($member); ?>">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Guest</label>
                <input class="form-control" id="guest_offering" type="text" name="guest_offering" readonly oninput="get_offering();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" value="<?=($guest); ?>">
            </div>
        </div>
        <?php if(!empty($first)){?>
            <hr>
            <div class="table-responsive">
                <table id="dtables" class="table table-striped table-hover mt-5">
                    <thead>
                        <tr>
                            <th>First Timer</th>
                            <th width="200px">Offering</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                           if($param3 && !empty((array)$first)){
                                foreach($first as $mm => $val){
                                    $vals = 0;
                                    if(!empty($guest_list)){
                                        foreach($guest_list as $guest => $amount){
                                            if($guest == strtoupper($val->fullname)){
                                                $vals = $amount;
                                                // echo $vals.' ';
                                            }
                                        }
                                    }
                                   
                                    ?>
                                    
                                <tr>
                                    <td><span class="text-muted"><?=ucwords($val->fullname); ?></span> <input type="hidden" name="guests[]" value="<?=strtoupper($val->fullname); ?>"></td>
                                
                                    <td>
                                        <input type="text" class="form-control guest_offerings" name="guest_offerings[]" id="offering_<?php echo $val->fullname; ?>" value="<?=$vals; ?>" oninput="guest_offerings();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')">

                                    </td>
                                </tr>
                           <?php } } else{
                            if(!empty((array)$first)){
                                foreach($first as $mm => $val){
                                    
                            ?>
                            <tr>
                                <td><span class="text-muted"><?=ucwords($val->fullname); ?></span><input type="hidden" name="guests[]" value="<?=strtoupper($val->fullname); ?>"></td>
                            
                                <td>
                                    <input type="text" class="form-control guest_offerings" name="guest_offerings[]" id="offering_<?php echo $mm; ?>" value="0" oninput="guest_offerings();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')">

                                </td>
                            </tr>

                        <?php } } }?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
        <hr>
        <div class="table-responsive">
            <table id="dtable" class="table table-striped table-hover mt-5">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th width="200px">Offering</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <hr>
        <div class="row mt-5" >
            <div class="col-sm-12 text-center mt-5">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg2"></div></div>
        </div>
    <?php } } ?>
    <!-- insert/edit view -->
    <?php if($param2 == 'new_convert') { ?>
        <?php if(empty($param3)){?>
            <div class="row">
                <div class="col-sm-12 text-danger text-center">Select a Cell First</div>
            </div>
        <?php }else{?>
            <?php 
                $cell_id = $this->Crud->read_field('id', $param4, 'cell_report', 'cell_id');
                $roles = $this->Crud->read_field('name', 'Member', 'access_role', 'id');

                $converts = json_decode($this->Crud->read_field('id', $param4, 'cell_report', 'converts'));
                if(!empty($converts)){
                    $firstIteration = true; // Flag to track the first iteration

                    foreach($converts as $c => $val){
                        $vals = (array)$val;
                        // Split the string into an array of words
                        $words = explode(" ", $vals['fullname']);

                        // Get the last word
                        $surname = array_pop($words);

                        // Reassemble the remaining words
                        $first_name = implode(" ", $words);
                        // echo $vals['fullname'];
                         // Check if it's not the first iteration
                         $btn = '';
                        if (!$firstIteration) {
                            $btn = '<button class="btn btn-danger deleteRow d-flex justify-content-center align-items-center"> <em class="icon ni ni-trash"></em> <span>Remove</span></button>';
                        }
                        
                        // After the first iteration, set the flag to false
                        $firstIteration = false;
            ?>
                <div class="row border mb-3">
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('First Name'); ?></label>
                            <input class="form-control" value="<?php if(!empty($first_name)){echo $first_name; }?>" type="text" id="first_name" name="first_name[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Surname'); ?></label>
                            <input class="form-control" type="text" value="<?php if(!empty($surname)){echo $surname; }?>" id="surname" name="surname[]"  required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Email'); ?></label>
                            <input class="form-control" value="<?php if(!empty($vals['email'])){echo $vals['email']; }?>" type="email" id="email" name="email[]"  >
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Phone'); ?></label>
                            <input class="form-control" value="<?php if(!empty($vals['phone'])){echo $vals['phone']; }?>" type="text" id="phone" name="phone[]"  required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Birthday'); ?></label>
                            <input class="form-control" value="<?php if(!empty($vals['dob'])){echo $vals['dob']; }?>" type="date" id="dob" name="dob[]" >
                        </div>
                    </div>
                    <?=$btn; ?>
                </div>
            <?php } }else{?>
                <div class="row border mb-3">
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('First Name'); ?></label>
                            <input class="form-control" type="text" id="first_name" name="first_name[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Surname'); ?></label>
                            <input class="form-control" type="text" id="surname" name="surname[]"  required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Email'); ?></label>
                            <input class="form-control" type="email" id="email" name="email[]"  >
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Phone'); ?></label>
                            <input class="form-control" type="text" id="phone" name="phone[]"  required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Birthday'); ?></label>
                            <input class="form-control" type="date" id="dob" name="dob[]" >
                        </div>
                    </div>
                    
                </div>
            <?php } ?>
            <div class="col-sm-12 my-3 text-center">
                <button id="addMores" class="btn btn-ico btn-outline-info" type="button"><i class="icon ni ni-plus-c"></i>  <?=translate_phrase('Add More');?></button>
            </div>


            <div class="row" >
            <div class="col-sm-12 text-center mt-3">
                    <button class="btn btn-primary bb_fo_btn" type="submit">
                        <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12"><div id="bb_ajax_msg2"></div></div>
            </div>
    <?php } }?>

    <?php if($param2 == 'timers') { ?>
        
        <div class="row">
            <input type="hidden" name="cell_id" value="<?php if(!empty($param3)){echo $param3;} ?>" />

            <div class="col-sm-4 mb-3">
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

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label" for="name">*<?=translate_phrase('Full Name'); ?></label>
                    <input class="form-control" type="text" id="fullname" name="fullname" value="<?php if(!empty($e_fullname)) {echo $e_fullname;} ?>" >
                </div>
            </div>

            <?php
                $ministry_id = $this->Crud->read_field('id', $param3, 'cells', 'ministry_id');
                $church_id = $this->Crud->read_field('id', $param3, 'cells', 'church_id');
                $church = $this->Crud->read_field('id', $church_id, 'church', 'name');
                $country_id = $this->Crud->read_field('id', $church_id, 'church', 'country_id');
                $country = $this->Crud->read_field('id', $country_id, 'country', 'name');
                
            ?>
            <input class="form-control" type="hidden" value="<?=$country_id; ?>" id="country" name="country"  required>
            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label" for="name">*<?=translate_phrase('Email');?></label>
                    <input class="form-control" type="text" id="email" name="email" value="<?php if(!empty($e_email)) {echo $e_email;} ?>" >
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label" for="name">*<?=translate_phrase('Phone');?></label>
                    <input class="form-control" type="text" id="phone" name="phone" value="<?php if(!empty($e_phone)) {echo $e_phone;} ?>" required>
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select class="js-select2" name="gender" id="gender" data-placeholder="Select Gender" required>
                        <option value="">Select Gender</option>
                        <option value="male" <?php if(!empty($e_gender)){if($e_gender ==  'male'){echo 'selected';}}; ?>>Male</option>
                        <option value="female" <?php if(!empty($e_gender)){if($e_gender ==  'female'){echo 'selected';}}; ?>>Female</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-4 mb-3">
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
            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label" for="name">*<?=translate_phrase('Address');?></label>
                    <input class="form-control" type="text" id="address" name="address"required>
                </div>
            </div>
            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label" for="name">*<?=translate_phrase('City');?></label>
                    <input class="form-control" type="text" id="city" name="city"  required>
                </div>
            </div>
           
            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label">State</label>
                    <select class="js-select2 form-select" name="state_id" id="state_id" data-placeholder="Select" >
                       <?php 
                            $state = $this->Crud->read_single_order('country_id', $country_id, 'state', 'name', 'asc');
                            if(!empty($state)){
                                foreach($state as $st){
                                    echo '<option value="'.$st->id.'">'.$st->name.'</option>';
                                }
                            }
                       ?>
                    </select>
                </div>
            </div>
                            
            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label" for="name"><?=translate_phrase('Postal Code');?></label>
                    <input class="form-control" type="text" id="postal" name="postal"  >
                </div>
            </div>
            
            <div class="col-sm-4 mb-3">
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

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label" for="name">*<?=translate_phrase('Occupation');?></label>
                    <input class="form-control" type="text" id="occupation" name="occupation"  >
                </div>
            </div>
            
            <div class="col-sm-4">
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

            <div class="col-sm-4">
                
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
            <input type="hidden" name="ministry_id" value="<?php if(!empty($ministry_id)){echo $ministry_id;} ?>" />
            <input type="hidden" name="church_id" value="<?php if(!empty($church_id)){echo $church_id;} ?>" />

            <div class="col-sm-4">
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

            <div class="col-sm-4">
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

            <div class="col-sm-4">
                <label for="visitTime" class="form-label fw-bold">If yes, when is best?</label>
                <input type="text" class="form-control" id="visitTime" name="visit_time" placeholder="Your answer">
            </div>
            
            <div class="col-sm-4 mb-3">
                <label for="name"  class="form-label fw-bold">*<?=translate_phrase('Invited By'); ?></label>
                <select class="js-select2" data-search="on" name="invited_by" id="invited_by" >
                    <option value="">Select</option>
                    <option value="Member">Member</option>
                    <option value="Online">Online</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            
            <div class="col-sm-4 mb-3 channel-div related-div"  style="display: none;">
                <label for="name"  class="form-label fw-bold"><?=translate_phrase('Channel'); ?></label>
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

                <!-- Channel TEXT INPUT (for Others) -->
                <input class="form-control" type="text" id="channel" name="channel" placeholder="Enter referral source" style="display: none;">

            </div>

            <div class="col-sm-4 mb-3 member-div related-div"  style="display: none;">
                
                <label for="name"  class="form-label fw-bold"><?=translate_phrase('Member'); ?></label>
                <select class="js-select2" data-search="on" name="member_id">
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
        </div>

        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg2"></div></div>
        </div>
    <?php }?>
<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<!-- Include jQuery library -->

<script>
     $(document).ready(function () {
        $('#invited_by').on('change', function () {
            var selectedOption = $(this).val();
            var $channelDiv = $('.channel-div');
            var $memberDiv = $('.member-div');
            var $platformSelect = $('#platform');
            var $channelInput = $('#channel');

            // Hide all by default
            $('.related-div').hide(500);
            $platformSelect.hide(500);
            $channelInput.hide(500);

            // Logic based on selection
            if (selectedOption === "Member") {
                $memberDiv.show(500);
            } else if (selectedOption === "Online") {
                $channelDiv.show(500);
                $platformSelect.show(500);
                $channelInput.hide(500);
            } else if (selectedOption === "Others") {
                $channelDiv.show(500);
                $platformSelect.hide(500);
                $channelInput.show(500);
            }
        });
        
        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd', // Set the date format
            autoclose: true
        });
    });

    $(document).ready(function(){
        // Initialize Select2 for the original select dropdown
        $('.js-select2').each(function() {
            if (!$(this).data('select2')) {
                $(this).select2();
            }
        });
        // Function to handle the click event of the "Add More Convert" button
        $('#addMores').click(function(){
            // Clone the first row
            var newRow = $('.row.border').first().clone();
            
            // Clear input values in the cloned row
            newRow.find('input[type="text"], input[type="email"], input[type="date"]').val('');
            
            // Append the cloned row after the last existing row
            $('.row.border').last().after(newRow);
            
            // Add a delete button with icon to the cloned row
            newRow.append('<button class="btn btn-danger deleteRow"> <em class="icon ni ni-trash"></em> <span>Remove</span></button>');
             // Reinitialize Select2 for the cloned select dropdown
             newRow.find('.js-select2').select2();
            // Center align the delete button
            newRow.find('.deleteRow').addClass('d-flex justify-content-center align-items-center');
        });

        // Function to handle the click event of the delete button for dynamically added rows
        $(document).on('click', '.deleteRow', function(){
            // Remove the corresponding row when delete button is clicked
            $(this).closest('.row.border').remove();
        });
    });
    
    $(document).on('change', 'select[name="invited_by[]"]', function(){
        var selectedOption = $(this).val();
        var channelDiv = $(this).closest('.row').find('div[name="channel-div"]');
        var memberDiv = $(this).closest('.row').find('div[name="member-div"]');
        
        // Hide all related divs initially
        $('div[name="related-div"]').hide();
        
         // Show the corresponding div based on the selected option
         if(selectedOption === "Member") {
            memberDiv.show(500);channelDiv.hide();
        } else if(selectedOption === "Online" || selectedOption === "Others") {
            channelDiv.show(500);memberDiv.hide();
        }
    });

    // Trigger the change event on page load
    $('select[name="invited_by[]"]').trigger('change');

    function calculateTotals() {
        
        var tithesInputs = document.querySelectorAll('.offerings');
        var total = 0;
        tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            total += isNaN(value) ? 0 : value;
        });
        console.log(total);
        var guest = $('#guest_offering').val();
        
        $('#member_offering').val(total.toFixed(2));
        total += parseFloat(guest);
        total = total.toFixed(2);
        $('#total_offering').val(total);

        // Set value to 0 if the textbox is empty
        tithesInputs.forEach(function(input) {
            if (input.value === '') {
                input.value = '';
            }
        });
    }

    
    function guest_offerings() {
        
        var tithesInputs = document.querySelectorAll('.guest_offerings');
        var total = 0;
        tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            total += isNaN(value) ? 0 : value;
        });
        console.log(total);
        var guest = $('#member_offering').val();
        
        $('#guest_offering').val(total.toFixed(2));
        total += parseFloat(guest);
        total = total.toFixed(2);
        $('#total_offering').val(total);

        // Set value to 0 if the textbox is empty
        tithesInputs.forEach(function(input) {
            if (input.value === '') {
                input.value = '';
            }
        });
    }
    // Trigger the change event on page load
    function get_offering(){
        var member = $('#member_offering').val();
        var guest = $('#guest_offering').val();
        
        var total = parseFloat(member) + parseFloat(guest);
        total = total.toFixed(2);
        $('#total_offering').val(total);
    }
</script>
<?php if(!empty($table_rec)){ ?>
    <!-- <script src="<?=site_url();?>assets/backend/vendors/datatables/jquery.dataTables.min.js"></script>
        <script src="<?=site_url();?>assets/backend/vendors/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?=site_url();?>assets/backend/js/pages/datatables.js"></script> -->
    <script type="text/javascript">
    $(document).ready(function() {
        //datatables
        var table = $('#dtable').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [<?php if(!empty($order_sort)){echo '['.$order_sort.']';} ?>], //Initial order.
            "language": {
                "processing": "<i class='icon ni ni-loader' aria-hidden='true'></i> <?=translate_phrase('Processing... please wait'); ?>"
            },
            // "pagingType": "full",

            // Load data for the table's content from an Ajax source
            "ajax": {
                url: "<?php echo site_url($table_rec); ?>",
                type: "POST",
               
            },

            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [
                <?php if(!empty($no_sort)){echo $no_sort;} ?>], //columns not sortable
                "orderable": false, //set not orderable
            }, ],

        });

    });
    
    </script>
<?php } ?>

