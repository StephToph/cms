
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
                <?php if(empty($reports)){
                    echo '
                        <div class="col-sm-12">No Record</div>
                    ';
                } else {
                    echo '<div class="row"> 
                        <div class="col-sm-2 mb-3">
                            <label class="fw-bold">First Timer</label>
                        </div>
                        <div class="col-sm-10 mb-3">
                            <p>'.$r->first_timer.'</p>
                        </div></div>';
                    $timers = json_decode($r->timers);
                    if(!empty($timers)){
                        foreach($timers as $at => $val){
                            $time = (array)$val;
                           
                        ?>
                        <div class="row border mb-2 p-2"> 
                            <div class="col-sm-4 mb-2 ">
                                <label class="fw-bold">Name</label>
                                <p><?=ucwords($time['fullname']); ?></p>
                            </div>
                            <div class="col-sm-4 mb-2 ">
                                <label class="fw-bold">Email</label>
                                <p><?=ucwords($time['email']); ?></p>
                            </div>
                            <div class="col-sm-4 mb-2 ">
                                <label class="fw-bold">Phone</label>
                                <p><?=ucwords($time['phone']); ?></p>
                            </div>
                            <div class="col-sm-4 mb-2 ">
                                <label class="fw-bold">Birthday</label>
                                <p><?=ucwords($time['dob']); ?></p>
                            </div>
                        </div>
                    <?php }
                    } else {
                        echo '
                            <div class="col-sm-12">No First Timer Record</div>
                        ';
                    }
                }?>  
            </div>    
            <div class="tab-pane" id="tabItem4">        
            <?php if(empty($reports)){
                    echo '
                        <div class="col-sm-12">No Record</div>
                    ';
                } else {
                    echo '<div class="row"> 
                        <div class="col-sm-3 mb-3">
                            <label class="fw-bold">New Convert</label>
                        </div>
                        <div class="col-sm-9 mb-3">
                            <p>'.$r->new_convert.'</p>
                        </div></div>';
                    $timers = json_decode($r->converts);
                    if(!empty($timers)){
                        foreach($timers as $at => $val){
                            $time = (array)$val;
                           
                        ?>
                        <div class="row border mb-2 p-2"> 
                            <div class="col-sm-4 mb-2 ">
                                <label class="fw-bold">Name</label>
                                <p><?=ucwords($time['fullname']); ?></p>
                            </div>
                            <div class="col-sm-4 mb-2 ">
                                <label class="fw-bold">Email</label>
                                <p><?=ucwords($time['email']); ?></p>
                            </div>
                            <div class="col-sm-4 mb-2 ">
                                <label class="fw-bold">Phone</label>
                                <p><?=ucwords($time['phone']); ?></p>
                            </div>
                            <div class="col-sm-4 mb-2 ">
                                <label class="fw-bold">Birthday</label>
                                <p><?=ucwords($time['dob']); ?></p>
                            </div>
                            <div class="col-sm-4 mb-2 ">
                                <label class="fw-bold">Invited By</label>
                                <p><?=ucwords($time['invited_by']); ?></p>
                            </div>
                            <?php if($time['invited_by'] == 'Member'){?>
                                <div class="col-sm-4 mb-2 ">
                                    <label class="fw-bold">Channel</label>
                                    <p><?=ucwords($this->Crud->read_field('id', $time['channel'], 'user', 'firstname').' '.$this->Crud->read_field('id', $time['channel'], 'user', 'surname')); ?></p>
                                </div>
                            <?php } else{?>
                                <div class="col-sm-4 mb-2 ">
                                    <label class="fw-bold">Channel</label>
                                    <p><?=ucwords($time['channel']); ?></p>
                                </div>
                            <?php } ?>
                            
                        </div>
                    <?php }
                    } else {
                        echo '
                            <div class="col-sm-12">No New Convert Record</div>
                        ';
                    }
                }?>     
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
                    $cell_id = $this->Crud->read_field('id', $param4, 'cell_report', 'cell_id');
                    $roles = $this->Crud->read_field('name', 'Member', 'access_role', 'id');

                    $user = $this->Crud->read2('cell_id', $param3,'role_id', $roles, 'user');
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
											<span class="tb-lead"><?=ucwords($p->firstname.' '.$p->surname); ?></span>
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

     <!-- insert/edit view -->
     <?php if($param2 == 'first_timer') { ?>
        <?php if(empty($param3)){?>
            <div class="row">
                <div class="col-sm-12 text-danger text-center">Select a Cell First</div>
            </div>
        <?php }else{?>
            <?php 
                $cell_id = $this->Crud->read_field('id', $param4, 'cell_report', 'cell_id');
                $roles = $this->Crud->read_field('name', 'Member', 'access_role', 'id');

                $converts = json_decode($this->Crud->read_field('id', $param4, 'cell_report', 'timers'));
                if(!empty($converts)){
                    $firstIteration = true; // Flag to track the first iteration

                    foreach($converts as $c => $val){
                        $vals = (array)$val;
                        // Split the string into an array of words
                        $words = explode(" ", $vals['fullname']);
                        $email = $vals['email'];
                        $phone = $vals['phone'];
                        $dob = $vals['dob'];
                        $invited_by = $vals['invited_by'];
                        $channel = $vals['channel'];
                        $email = $vals['email'];
                        
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
                            <input class="form-control" value="<?php if(!empty($first_name)){echo $first_name; }?>"  type="text" id="first_name" name="first_name[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Surname'); ?></label>
                            <input class="form-control" value="<?php if(!empty($surname)){echo $surname; }?>"  type="text" id="surname" name="surname[]"  required>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Email'); ?></label>
                            <input class="form-control" value="<?php if(!empty($email)){echo $email; }?>"  type="email" id="email" name="email[]"  >
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Phone'); ?></label>
                            <input class="form-control" value="<?php if(!empty($phone)){echo $phone; }?>"  type="text" id="phone" name="phone[]"  required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Birthday'); ?></label>
                            <input class="form-control" value="<?php if(!empty($dob)){echo $dob; }?>"  type="date" id="dob" name="dob[]" >
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Invited By'); ?></label>
                            <select class="form-select js-select2" name="invited_by[]" required>
                                <option value="">Select</option>
                                <option <?php if(!empty($invited_by)){if($invited_by == 'Member'){echo 'selected';}} ?> value="Member">Member</option>
                                <option <?php if(!empty($invited_by)){if($invited_by == 'Online'){echo 'selected';}} ?> value="Online">Online</option>
                                <option <?php if(!empty($invited_by)){if($invited_by == 'Others'){echo 'selected';}} ?> value="Others">Others</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-sm-4 mb-3" name="channel-div" style="display: none;">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Channel'); ?></label>
                            <input class="form-control" type="text" value="<?php if(!empty($channel)){echo $channel;} ?>"  id="channel" name="channel[]" >
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3" name="member-div" style="display: none;">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Member'); ?></label>
                            <select class="form-select js-select2" data-search="on" name="member_id[]">
                                <option value="">Select Member</option>
                                <?php 
                                    $roles_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
                                    $mem = $this->Crud->read_single_order('role_id', $roles_id, 'user', 'firstname', 'asc');
                                        if(!empty($mem)){
                                            
                                            foreach($mem as $m){
                                                $mrm = '';
                                                if(!empty($channel)){if($channel == $m->id){$mrm = 'selected';}}
                                                echo '<option value="'.$m->id.'" '.$mrm.'>'.ucwords($m->firstname.' '.$m->surname).'</option>';
                                            }
                                        }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                </div>

            <?php } }else{ ?>
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
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Email'); ?></label>
                            <input class="form-control" type="email" id="email" name="email[]"  >
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Phone'); ?></label>
                            <input class="form-control" type="text" id="phone" name="phone[]"  required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Birthday'); ?></label>
                            <input class="form-control" type="date" id="dob" name="dob[]" >
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Invited By'); ?></label>
                            <select class="form-select js-select2" name="invited_by[]" required>
                                <option value="">Select</option>
                                <option value="Member">Member</option>
                                <option value="Online">Online</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-sm-4 mb-3" name="channel-div" style="display: none;">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Channel'); ?></label>
                            <input class="form-control" type="text" id="channel" name="channel[]" >
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3" name="member-div" style="display: none;">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Member'); ?></label>
                            <select class="form-select js-select2" data-search="on" name="member_id[]">
                                <option value="">Select Member</option>
                                <?php 
                                    $roles_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
                                    $mem = $this->Crud->read_single_order('role_id', $roles_id, 'user', 'firstname', 'asc');
                                        if(!empty($mem)){
                                            foreach($mem as $m){
                                                echo '<option value="'.$m->id.'">'.ucwords($m->firstname.' '.$m->surname).'</option>';
                                            }
                                        }
                                ?>
                            </select>
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
<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<!-- Include jQuery library -->

<script>
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
</script>

</script>

