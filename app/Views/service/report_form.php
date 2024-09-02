
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
            <li class="nav-item">        
                <a class="nav-link" data-bs-toggle="tab" href="#tabItem5"><em class="icon ni ni-wallet"></em><span>Tithe</span></a>    
            </li>    
            
            <li class="nav-item">        
                <a class="nav-link" data-bs-toggle="tab" href="#tabItem6"><em class="icon ni ni-money"></em><span>Partnership</span></a>    
            </li>    
            
        </ul>
        <div class="tab-content">    
            <div class="tab-pane active" id="tabItem1">    
                <?php 
                    $r_id = $param3;
                    $reports = $this->Crud->read_single('id', $r_id, 'service_report');
                    if(empty($reports)){
                        echo '
                            <div class="col-sm-12">No Record</div>
                        ';
                    } else{
                        foreach($reports as $r){
                           $types = $this->Crud->read_field('id', $r->type, 'service_type', 'name');

                            
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
                    $attendant = json_decode($r->attendant);
                    echo '<div class="row"> 
                        <div class="col-sm-3  mb-3">
                            <label class="fw-bold">Total Attendance</label>
                        </div>
                        <div class="col-sm-9  mb-3">
                            <p>'.$r->attendance.'</p>
                        </div>
                        <div class="col-sm-3  mb-3">
                        <label class="fw-bold">Member</label>
                        </div>
                        <div class="col-sm-9  mb-3">
                            <p>'.$attendant->member.'</p>
                        </div>
                        <div class="col-sm-3  mb-3">
                            <label class="fw-bold">First Timer</label>
                        </div>
                        <div class="col-sm-9  mb-3">
                            <p>'.$attendant->guest.'</p>
                        </div>
                        
                        ';
                   
                        $attendant = $attendant->attendant;
                    if(!empty($attendant)){
                      
                        foreach($attendant as $at => $val){
                            $name = $this->Crud->read_field('id', $val, 'user', 'firstname').' '.$this->Crud->read_field('id', $val, 'user', 'surname');
                         ?>
                        <div class="col-sm-4 mb-2 border"><?=strtoupper($name); ?></div>
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
                            </div>
                            
                        </div>
                    <?php }
                    } else {
                        echo '
                            <div class="col-sm-12">No New Convert Record</div>
                        ';
                    }
                }?>     
            </div>
            <div class="tab-pane" id="tabItem5">        
                <?php if(empty($reports)){
                    echo '
                        <div class="col-sm-12">No Record</div>
                    ';
                } else {
                    $tithers = json_decode($r->tithers);
                    echo '<div class="row"> 
                        <div class="col-sm-3  mb-3">
                            <label class="fw-bold">Total</label>
                        </div>
                        <div class="col-sm-9  mb-3">
                            <p>$'.number_format($tithers->total,2).'</p>
                        </div>
                        <div class="col-sm-3  mb-3">
                        <label class="fw-bold">Member</label>
                        </div>
                        <div class="col-sm-9  mb-3">
                            <p>$'.number_format($tithers->member,2).'</p>
                        </div>
                        <div class="col-sm-3  mb-3">
                            <label class="fw-bold">Guest</label>
                        </div>
                        <div class="col-sm-9  mb-3">
                            <p>$'.number_format($tithers->guest,2).'</p>
                        </div>
                        
                        ';
                   
                        $attendant = $tithers->list;
                    if(!empty($attendant)){
                    //   print_r($attendant);
                        foreach($attendant as $at => $val){
                            $name = $this->Crud->read_field('id', $at, 'user', 'firstname').' '.$this->Crud->read_field('id', $at, 'user', 'surname');
                         ?>
                        <div class="col-sm-4 mb-2 border"><?=strtoupper($name); ?> => $<?=number_format($val,2); ?></div>
                     <?php } echo '</div>';
                    } else {
                        echo '
                            <div class="col-sm-12">No Tither Record</div>
                        ';
                    }
                }?>
            </div>   
            <div class="tab-pane" id="tabItem6">        
                <?php if(empty($reports)){
                    echo '
                        <div class="col-sm-12">No Record</div>
                    ';
                } else {
                    $attendant = json_decode($r->partners);
                    echo '<div class="row"> 
                        <div class="col-sm-3  mb-3">
                            <label class="fw-bold">Total</label>
                        </div>
                        <div class="col-sm-9  mb-3">
                            <p>$'.number_format($attendant->total_part,2).'</p>
                        </div>
                        <div class="col-sm-3  mb-3">
                        <label class="fw-bold">Member</label>
                        </div>
                        <div class="col-sm-9  mb-3">
                            <p>$'.$attendant->member_part.'</p>
                        </div>
                        <div class="col-sm-3  mb-3">
                            <label class="fw-bold">First Timer</label>
                        </div>
                        <div class="col-sm-9  mb-3">
                            <p>$'.$attendant->guest_part.'</p>
                        </div>
                        
                    ';
                
                    $attendant = $attendant->partnership;
                    if(!empty($attendant)){
                        $name = '';
                        $partners = (array)$attendant;
                        foreach($attendant as $at => $val){
                            echo '
                                <div class="table-responsive">
                                    <table class="table table-hover">
                            ';
                            if($at == 'guest'){
                                echo '
                                    <tr>
                                        <td><b>GUEST</b></td>
                                    </tr>
                                    <tr>
                                        <th>NAME</th>
                                    ';
                                    $parts = $this->Crud->read_order('partnership', 'name', 'asc');
                                    if(!empty($parts)){
                                        foreach($parts as $index => $pp){
                                            $name = $pp->name;
                                            if(strtoupper($pp->name) == 'BIBLE SPONSOR')$name = 'Bible';
                                            if(strtoupper($pp->name) == 'CHILDREN MINISTRY')$name = 'Children';
                                            if(strtoupper($pp->name) == 'HEALING SCHOOL MAGAZINE')$name = 'H.S.M';
                                            if(strtoupper($pp->name) == 'HEALING STREAM')$name = 'H.S';
                                            if(strtoupper($pp->name) == 'LOVEWORLD LWUSA')$name = 'lwusa';
                                            if(strtoupper($pp->name) == 'MINISTRY PROGRAM')$name = 'Ministry';
                                            // if($pp->name == 'BIBLE SPONSOR')$name = 'Bible';
                                            
                                            echo ' <th >'.strtoupper($name).'</th>';
                                        }
                                    }
                                    echo '</tr>';

                                    if(!empty($val)){
                                        $vals = (array)$val;
                                        foreach($vals as $v => $am){
                                            $ams = (array)$am;
                                            
                                            echo '<tr>
                                                    <td>'.strtoupper($v).'</td> ';
                                            ?>
                                            <?php  
                                                $parts = $this->Crud->read_order('partnership', 'name', 'asc');
                                                if(!empty($parts)){
                                                    foreach($parts as $index => $pp){
                                                        $pps = 0;
                                                        if(!empty($ams[$pp->id])){
                                                            $pps =  $ams[$pp->id];
                                                        }
                                                        echo ' <td>'.($pps).'</td>';
                                                    }
                                                }
                                            echo '</tr>';
                                        }
                                    }
                            }
                            if($at == 'member'){
                                echo '
                                    <tr>
                                        <td><b>MEMBER</b></td>
                                    </tr>
                                    <tr>
                                        <th>NAME</th>
                                    ';
                                    $parts = $this->Crud->read_order('partnership', 'name', 'asc');
                                    if(!empty($parts)){
                                        foreach($parts as $index => $pp){
                                            $name = $pp->name;
                                            if(strtoupper($pp->name) == 'BIBLE SPONSOR')$name = 'Bible';
                                            if(strtoupper($pp->name) == 'CHILDREN MINISTRY')$name = 'Children';
                                            if(strtoupper($pp->name) == 'HEALING SCHOOL MAGAZINE')$name = 'H.S.M';
                                            if(strtoupper($pp->name) == 'HEALING STREAM')$name = 'H.S';
                                            if(strtoupper($pp->name) == 'LOVEWORLD LWUSA')$name = 'lwusa';
                                            if(strtoupper($pp->name) == 'MINISTRY PROGRAM')$name = 'Ministry';
                                            // if($pp->name == 'BIBLE SPONSOR')$name = 'Bible';
                                            
                                            echo ' <th >'.strtoupper($name).'</th>';
                                        }
                                    }
                                    echo '</tr>';

                                    if(!empty($val)){
                                        $vals = (array)$val;
                                        foreach($vals as $v => $am){
                                            $ams = (array)$am;
                                            $vname = $this->Crud->read_field('id', $v, 'user', 'firstname').' '.$this->Crud->read_field('id', $v, 'user', 'surname');
                                            echo '<tr>
                                                    <td>'.strtoupper($vname).'</td> ';
                                            ?>
                                            <?php  
                                                $parts = $this->Crud->read_order('partnership', 'name', 'asc');
                                                if(!empty($parts)){
                                                    foreach($parts as $index => $pp){
                                                        $pps = 0;
                                                        if(!empty($ams[$pp->id])){
                                                            $pps =  $ams[$pp->id];
                                                        }
                                                        echo ' <td>'.($pps).'</td>';
                                                    }
                                                }
                                            echo '</tr>';
                                        }
                                    }
                            }
                           
                         ?>
                        
                            <?php echo '
                                    </table>
                                </div>';
                        } 
                    } else {
                        echo '
                            <div class="col-sm-12">No Attendance Record</div>
                        ';
                    }
                }?>
            </div>   
        </div>
    <?php } ?>
        
    
    <?php if($param2 == 'attendance'){?>
        <div class="row">
            <span class="text-danger mb-2">Mark Member's Attendance in the Table Below</span>
            <div class="col-sm-4 mb-3 ">
                <label>Total</label>
                <input class="form-control" id="total" type="text" name="total"  readonly value="0">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Member</label>
                <input class="form-control" id="member" type="text" name="member"  readonly value="0">
            </div>
            <div class="col-sm-4 mb-3">
                <label>First Timer</label>
                <input class="form-control" id="guest" type="text" name="guest" readonly value="<?=$timer_count; ?>">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Male</label>
                <input class="form-control" id="male" type="text" name="male"  readonly value="<?=$timer_male; ?>">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Female</label>
                <input class="form-control" id="female" type="text" name="female"  readonly value="<?=$timer_female; ?>">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Children</label>
                <input class="form-control" id="children" type="text" name="children"  readonly value="<?=$timer_child; ?>">
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table id="dtable" class="table table-striped table-hover mt-5">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th></th>
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
    <?php } ?>
     
    <?php if($param2 == 'tithe'){
        $converts = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'tithers'));
        $total =0 ;
        $member = 0;
        $guest = 0;
        if(!empty($converts)){
            $total = $converts->total;
            $member = $converts->member;
            $guest = $converts->guest;
            
        }
        ?>
        <div class="row">
            <span class="text-danger mb-2">Enter Member's Tithe in the Table Below</span>
            <div class="col-sm-4 mb-3 ">
                <label>Total</label>
                <input class="form-control" id="total_tithe" type="text" name="total_tithe"  readonly value="<?=number_format($total,2); ?>">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Member</label>
                <input class="form-control" id="member_tithe" type="text" name="member_tithe"  readonly value="<?=number_format($member,2); ?>">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Guest</label>
                <input class="form-control" id="guest_tithe" type="text" name="guest_tithe" oninput="get_tithe();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" value="<?=number_format($guest,2); ?>">
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table id="dtable" class="table table-striped table-hover mt-5">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th width="200px">Tithe</th>
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
    <?php } ?>
    <?php if($param2 == 'offering'){
        // echo $table_rec;
        $first = json_decode($first);
        $total =0 ;
        $member = 0;
        $guest = 0;
        
        if($param3){
            $converts = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'offering_givers'));
            if(!empty($converts)){
                $total = $converts->total;
                $member = $converts->member;
                $guest = $converts->guest;
                $guest_list = (array)$converts->guest_list;
            }
          
            $first = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'timers'));
            
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
    <?php } ?>
    
    <?php if($param2 == 'partnership'){
        $total =0 ;
        $member = 0;
        $guest = 0;
        $first = json_decode($first);
        if($param3){
            $converts = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'partners'));
        
            if(!empty($converts)){
                $total = $converts->total_part;
                $member = $converts->member_part;
                $guest = $converts->guest_part;
                $firsts = $converts->partnership;
                $first = (array)$firsts;
                
            }
        }
        
        ?>
        <div class="row">
            <span class="text-danger mb-2">Enter Member's Partnership in the Table Below</span>
            <div class="col-sm-4 mb-3 ">
                <label>Total</label>
                <input class="form-control" id="total_part" type="text" name="total_part"  readonly value="<?=($total); ?>">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Member</label>
                <input class="form-control" id="member_part" type="text" name="member_part"  readonly value="<?=($member); ?>">
            </div>
            <div class="col-sm-4 mb-3">
                <label>First Timer</label>
                <input class="form-control" id="guest_part" type="text" name="guest_part" oninput="get_part();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" readonly value="<?=($guest); ?>">
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <?php
                if(!empty($first)){
            ?>
                <table class="table table-striped table-hover mt-5" id="dataTable">
                    <thead>
                        <tr>
                            <th >First Timer</th>
                            <?php  
                                $parts = $this->Crud->read_order('partnership', 'name', 'asc');
                                if(!empty($parts)){
                                    foreach($parts as $index => $pp){
                                        $name = $pp->name;
                                        if(strtoupper($pp->name) == 'BIBLE SPONSOR')$name = 'Bible';
                                        if(strtoupper($pp->name) == 'CHILDREN MINISTRY')$name = 'Children';
                                        if(strtoupper($pp->name) == 'HEALING SCHOOL MAGAZINE')$name = 'H.S.M';
                                        if(strtoupper($pp->name) == 'HEALING STREAM')$name = 'H.S';
                                        if(strtoupper($pp->name) == 'LOVEWORLD LWUSA')$name = 'lwusa';
                                        if(strtoupper($pp->name) == 'MINISTRY PROGRAM')$name = 'Ministry';
                                        // if($pp->name == 'BIBLE SPONSOR')$name = 'Bible';
                                        
                                        echo ' <th >'.strtoupper($name).'</th>';
                                        echo '<input type="hidden" name="'.($index-1).'_parts[]" value="'.$pp->id.'">';

                                    }
                                }
                            ?>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($param3){
                            foreach($first as $f => $val){
                                if($f == 'guest'){
                                    $selval = [];
                                    if(!empty($val)){
                                        foreach($val as $pp => $pval){
                                            $selval[] = $pp;
                                            $parts_val = (array)$pval;
                                       
                                        }  
                                    }
                            ?>
                            <tr class="original-row">
                                <td>
                                    <select class="js-select2 firsts"  data-search="on" name="first_timer[]" id="firsts" data-placeholder="Select First Timer" selected required>
                                        
                                        <?php 
                                            if(!empty((array)$selval)){
                                                foreach($selval as $mm => $val){
                                                    echo '<option value="'.$val.'">'.strtoupper($val).'</option>';
                                                }
                                            } 
                                        ?>
                                    </select>
                                </td>
                                <?php 

                                    if(!empty($parts)){
                                        $vall = 0;
                                        foreach($parts as $pp => $val){
                                            if(!empty($parts_val)){
                                                if(!empty($parts_val[$pp])){$vall = $parts_val[$pp];}else{$vall = 0;}
                                            }
                                            echo '<td><input type="text" style="width:100px;" class="form-control firsts_amount" name="'.($pp-1).'_first[]" oninput="bindInputEvents();" value="'.$vall.'"></td>';
                                        }
                                    }
                                ?>
                                <td></td>
                            </tr>
                        <?php } 
                            }
                        }
                        else{?>

                            
                            <tr class="original-row">
                                <td>
                                    <select class="js-select2 firsts"  data-search="on" name="first_timer[]" id="firsts" data-placeholder="Select First Timer" required>
                                        <option value="">Select</option>
                                        <?php 
                                            if(!empty((array)$first)){
                                                foreach($first as $mm => $val){
                                                    echo '<option value="'.$val->fullname.'">'.strtoupper($val->fullname).'</option>';
                                                }
                                            } 
                                        ?>
                                    </select>
                                </td>
                                <?php 
                                    if(!empty($parts)){
                                        foreach($parts as $pp => $val){
                                            echo '<td><input type="text" style="width:100px;" class="form-control firsts_amount" name="'.$pp.'_first[]" oninput="bindInputEvents();" value="0"></td>';
                                        }
                                    }
                                ?>
                                <td></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="col-12 my-3 text-center">
                    <p id="first_resp"></p>
                    <button type="button" class="btn btn-info" id="more_btn">Add More</button>
                </div>
            <?php } ?>
            <table class="table table-striped table-hover mt-5" id="member_table">
                <thead>
                    <tr>
                        <th >Member</th>
                        <?php 
                            $parts = $this->Crud->read_order('partnership', 'name', 'asc');
                            if(!empty($parts)){
                                foreach($parts as $pp){
                                    $name = $pp->name;
                                    if(strtoupper($pp->name) == 'BIBLE SPONSOR')$name = 'Bible';
                                    if(strtoupper($pp->name) == 'CHILDREN MINISTRY')$name = 'Children';
                                    if(strtoupper($pp->name) == 'HEALING SCHOOL MAGAZINE')$name = 'H.S.M';
                                    if(strtoupper($pp->name) == 'HEALING STREAM')$name = 'H.S';
                                    if(strtoupper($pp->name) == 'LOVEWORLD LWUSA')$name = 'lwusa';
                                    if(strtoupper($pp->name) == 'MINISTRY PROGRAM')$name = 'Ministry';
                                    // if($pp->name == 'BIBLE SPONSOR')$name = 'Bible';
                                    
                                    echo ' <th >'.strtoupper($name).'</th>';
                                }
                            }
                        ?>
                       <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($param3){
                        if(!empty($first)){
                        foreach($first as $f => $val){
                            if($f == 'member'){
                                $selval = [];
                                if(!empty($val)){
                                    foreach($val as $pp => $pval){
                                        $selval[] = $pp;
                                        $parts_val = (array)$pval;
                                    
                                    }  
                                }
                        ?>
                        <tr class="original-rows">
                            <td>
                                <select class="js-select2 members" name="members[]" id="members" required>
                                <option value="">Select</option>
                                    <?php 
                                        $mem_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
                                        $mem = $this->Crud->read_single_order('role_id', $mem_id, 'user', 'firstname', 'asc');
                                        if(!empty($mem)){
                                            foreach($mem as $mm){
                                                $sel = '';
                                                if(in_array($mm->id,$selval))$sel = 'selected';
                                                echo '<option value="'.$mm->id.'" '.$sel.'>'.strtoupper($mm->firstname.' '.$mm->surname).'</option>';
                                            }
                                        } 
                                    ?>
                                </select>
                            </td>
                            <?php 
                                if(!empty($parts)){
                                    $vall = 0;
                                    foreach($parts as $pp => $val){
                                        if(!empty($parts_val)){
                                            if(!empty($parts_val[$pp])){$vall = $parts_val[$pp];}else{$vall = 0;}
                                        }
                                        echo '<td><input type="text" style="width:100px;" class="form-control members_amount" name="'.($pp-1).'_member[]" oninput="bindInputEvents();" value="'.$vall.'"></td>';
                                    }
                                }
                            ?>
                            <td></td>
                        </tr>
                    <?php } } }
                        } else{?>
                        <tr class="original-rows">
                            <td>
                                <select class="js-select2 members" name="members[]" id="members" required>
                                <option value="">Select</option>
                                    <?php 
                                        $mem_id = $this->Crud->read_field('name', 'Member', 'access_role', 'id');
                                        $mem = $this->Crud->read_single_order('role_id', $mem_id, 'user', 'firstname', 'asc');
                                        if(!empty($mem)){
                                            foreach($mem as $mm){
                                                echo '<option value="'.$mm->id.'" >'.strtoupper($mm->firstname.' '.$mm->surname).'</option>';
                                            }
                                        } 
                                    ?>
                                </select>
                            </td>
                            <?php 
                            if(!empty($parts)){
                                    foreach($parts as $pp => $val){
                                        echo ' <td ><input type="text" style="width:100px;" class="form-control  members_amount" oninput=" bindInputEvents();" name="'.($pp-1).'_member[]" value="0" ></td>';
                                    }
                                }
                            ?>
                            <td></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="col-12 my-3 text-center">
                <p id="mem_resp"></p>
                <button type="button" class="btn btn-primary" id="mem_btn">Add More</button>
            </div>
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
    <?php } ?>
    <!-- insert/edit view -->
    <?php if($param2 == 'new_convert') { ?>
        
            <?php 
                $cell_id = $this->Crud->read_field('id', $param4, 'cell_report', 'cell_id');
                $roles = $this->Crud->read_field('name', 'Member', 'access_role', 'id');

                $converts = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'converts'));
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
    <?php } ?>

     <!-- insert/edit view -->
     <?php if($param2 == 'first_timer') { ?>
        
            <?php 
                $cell_id = $this->Crud->read_field('id', $param4, 'cell_report', 'cell_id');
                $roles = $this->Crud->read_field('name', 'Member', 'access_role', 'id');

                $converts = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'timers'));
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
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('First Name'); ?></label>
                            <input class="form-control" value="<?php if(!empty($first_name)){echo $first_name; }?>"  type="text" id="first_name" name="first_name[]" required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Surname'); ?></label>
                            <input class="form-control" value="<?php if(!empty($surname)){echo $surname; }?>"  type="text" id="surname" name="surname[]"  required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Email'); ?></label>
                            <input class="form-control" value="<?php if(!empty($email)){echo $email; }?>"  type="email" id="email" name="email[]"  >
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Phone'); ?></label>
                            <input class="form-control" value="<?php if(!empty($phone)){echo $phone; }?>"  type="text" id="phone" name="phone[]"  required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Gender'); ?></label>
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
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Family Position'); ?></label>
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
                <div class="row border mb-3 p-2">
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('First Name'); ?></label>
                            <input class="form-control" type="text" id="first_name" name="first_name[]" required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name">*<?=translate_phrase('Surname'); ?></label>
                            <input class="form-control" type="text" id="surname" name="surname[]"  required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Email'); ?></label>
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
                            <label for="name"><?=translate_phrase('Gender'); ?></label>
                            <div class="form-control-wrap">
                                <select
                                    class="form-select js-select2" name="gender[]" required
                                    data-placeholder="Select Gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php if(!empty($e_gender)){if($e_gender == 'Male'){echo 'selected';}}?>>Male</option>
                                    <option value="Female" <?php if(!empty($e_gender)){if($e_gender == 'Female'){echo 'selected';}}?>>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="name"><?=translate_phrase('Family Position'); ?></label>
                            <div class="form-control-wrap">
                                <select class="form-select js-select2" id="family_position" name="family_position[]" data-placeholder="Select Position">
                                    <option value="">Select</option>
                                    <option value="Child" <?php if(!empty($e_family_position)){if($e_family_position == 'Child'){echo 'selected';}} ?>>Child </option>
                                    <option value="Parent" <?php if(!empty($e_family_position)){if($e_family_position == 'Parent'){echo 'selected';}} ?>>Parent </option>
                                    <option value="Other" <?php if(!empty($e_family_position)){if($e_family_position == 'Other'){echo 'selected';}} ?>>Other </option>
                                </select>
                            </div>
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
    <?php }?>
<?php echo form_close(); ?>
<input type="hidden" id="respo">
<input type="hidden" id="applicant">
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<!-- Include jQuery library -->

<script>
     var rowIndex = 1;
    // Array to store selected values
    var selectedValues = [];

    function clearSelectedValues() {
        selectedValues = [];
    }
    $(function() {
        clearSelectedValues();
    });
    $(document).ready(function() {
        
        var selectCounter = 1; 
       
        function initializeSelect2(selectElement) {
            selectElement.select2( );
            selectElement.attr('data-search', 'true'); // Add data-search attribute
        }

        // Function to create and return a new select element
        function createSelectElement() {
            var select = $('<select class="js-select2 firsts" data-search="on" name="first_timer[]" data-placeholder="Select First Timer" required></select>');

            <?php if(!empty((array)$first)) { ?>
                <?php foreach($first as $mm => $val) { ?>
                    var optionValue = '<?php //echo htmlspecialchars($val->fullname); ?>';
                    var optionText = '<?php //echo strtoupper(htmlspecialchars($val->fullname)); ?>';

                    // Check if this option is selected in any existing row
                    var isSelected = selectedValues.includes(optionValue);

                    // If the option is not selected in any existing row, add it to the new select element
                    if (!isSelected) {
                        select.append('<option value="' + optionValue + '">' + optionText + '</option>');
                    }
                <?php } ?>
            <?php } ?>

          
            return select;

        }
        

        function addNewRow() {
            
            var remainingOptions = $('.original-row select option:not(:selected)');
            if (remainingOptions.length === 0) {
                $('#first_resp').html("<span class='text-danger'>No more options available.</span>");
                return;
            }
            var selectedValue = $('.original-row select').val();
            if (selectedValue === "") {
                $('#first_resp').html("<span class='text-danger'>Select a First Time.</span>");
                return; // Stop further execution
            } 
            $('#first_resp').html('')
            var newRow = $('<tr class="new-row"></tr>');

            var firstTimerSelect = createSelectElement();

            // Assign a new unique id to the cloned select element
            var newSelectId = 'firsts_' + selectCounter;
            firstTimerSelect.attr('id', newSelectId);

            newRow.append('<td>' + firstTimerSelect.prop('outerHTML') + '</td>');

            // Add input fields for each partnership
            $('#dataTable th').each(function (index) {
                if (index > 1) {
                    var partName = (index-2) + "_first[]"; // Adjust the prefix as needed
                    newRow.append('<td><input type="text" style="width:100px;" class="form-control firsts_amount" name="' + partName + '" oninput="bindInputEvents();" value="0"></td>');
                }
            });

            // Add delete button in the action cell
            newRow.append('<td><button class="btn btn-danger btn-sm delete-row">Delete</button></td>');
            $('#dataTable tbody').append(newRow);

            // Initialize select2 plugin for the new select element
            initializeSelect2(newRow.find('select'));

            // Increment the counter
            selectCounter++;

              // Update Add More button state
            updateAddMoreButtonState();
        }

       // Function to update the state of the Add More button
        function updateAddMoreButtonState() {
            // Check if there are any new rows
            var hasNewRows = $('.new-row').length > 0;

            // If there are no new rows, check the original row's select element
            if (!hasNewRows) {
                var originalSelectOptionsCount = $('.original-row select option').length;
                var selectedValue = $('.original-row select').val();

                if (selectedValue === "1" || originalSelectOptionsCount <= 1) {
                    $('#more_btn').prop('disabled', true);
                } else {
                    $('#more_btn').prop('disabled', false);
                }
            } else {
                // Count the number of selected values except when value is 0
                var selectedCount = selectedValues.filter(function(value) {
                    return value !== "0";
                }).length;

                // Get the number of options in the last select element
                var lastSelectOptionsCount = $('#dataTable .new-row:last .firsts option').length;

                // Disable "Add More" button if the number of selected values matches the number of original options minus 1
                // or if the number of options in the last select element is 1
                if (lastSelectOptionsCount === 1 || selectedCount === lastSelectOptionsCount - 1) {
                    $('#more_btn').prop('disabled', true);
                } else {
                    $('#more_btn').prop('disabled', false);
                }
            }
        }

        // Event listener for the Add More button
        $('#more_btn').click(function() {
           
            // Add a new row with a fresh select element
            addNewRow();
        });

        
        // Event listener for selecting an option
        $(document).on('change', '.firsts', function() {
            var selectedValue = $(this).val();
            if (selectedValue) {
                selectedValues.push(selectedValue);
            }
            updateAddMoreButtonState();
        });

        $('#dataTable').on('click', '.delete-row', function() {
            var selectedValue = $(this).closest('tr').find('.firsts').val();
            if (selectedValue) {
                // Remove the selected value from the array
                var index = selectedValues.indexOf(selectedValue);
                if (index !== -1) {
                    selectedValues.splice(index, 1);
                }
            }
            
            // Remove the closest tr
            $(this).closest('tr').remove();
            // Update Add More button state
            updateAddMoreButtonState();
            updateTotal();

        });
    });

    $(document).ready(function() {
        
        var selectCounter = 1; 
        function initializeSelect2(selectElement) {
            selectElement.select2( );
            selectElement.attr('data-search', 'true'); // Add data-search attribute
        }
        // Function to create and return a new select element
        function createSelectElements() {
            var select = $('<select class="js-select2 members" data-search="on" name="members[]" data-placeholder="Select Memeber" required><option value="">Select</option></select>');
            
            <?php 
             
            if(!empty($mem)) { ?>
                <?php foreach($mem as $mm) { ?>
                    var optionValue = '<?php echo htmlspecialchars($mm->id); ?>';
                    var optionText = '<?php echo strtoupper(htmlspecialchars($mm->firstname.' '.$mm->surname)); ?>';

                    // Check if this option is selected in any existing row
                    var isSelected = selectedValues.includes(optionValue);

                    // If the option is not selected in any existing row, add it to the new select element
                    if (!isSelected) {
                        select.append('<option value="' + optionValue + '">' + optionText + '</option>');
                    }
                <?php } ?>
            <?php } ?>
            return select;

        }
        

        function addNewRows() {
            
            var remainingOptions = $('.original-rows select option:not(:selected)');
            if (remainingOptions.length === 0) {
                $('#mem_resp').html("<span class='text-danger'>No more options available.</span>");
                return;
            }
            var selectedValue = $('.original-rows select').val();
            if (selectedValue === "") {
                $('#mem_resp').html("<span class='text-danger'>Select a Member.</span>");
                return; // Stop further execution
            } 
            $('#mem_resp').html('')
            var newRow = $('<tr class="new-rows"></tr>');

            var firstTimerSelect = createSelectElements();

            // Assign a new unique id to the cloned select element
            var newSelectId = 'members_' + selectCounter;
            firstTimerSelect.attr('id', newSelectId);

            newRow.append('<td>' + firstTimerSelect.prop('outerHTML') + '</td>');

            // Add input fields for each partnership
            $('#member_table th').each(function (index) {
                if (index > 1) {
                    var partName = (index-2) + "_member[]"; // Adjust the prefix as needed
                    newRow.append('<td><input type="text" style="width:100px;" class="form-control members_amount" oninput=" bindInputEvents();" name="' + partName + '" value="0"></td>');
                }
            });

            // Add delete button in the action cell
            newRow.append('<td><button class="btn btn-danger btn-sm delete-row">Delete</button></td>');
            $('#member_table tbody').append(newRow);

            // Initialize select2 plugin for the new select element
            initializeSelect2(newRow.find('select'));

            // Increment the counter
            selectCounter++;

              // Update Add More button state
            updateAddMoreButtonStates();
        }

       // Function to update the state of the Add More button
        function updateAddMoreButtonStates() {
            // Check if there are any new rows
            var hasNewRows = $('.new-rows').length > 0;

            // If there are no new rows, check the original row's select element
            if (!hasNewRows) {
                var originalSelectOptionsCount = $('.original-rows select option').length;
                var selectedValue = $('.original-rows select').val();

                if (selectedValue === "1" || originalSelectOptionsCount <= 1) {
                    $('#mem_btn').prop('disabled', true);
                } else {
                    $('#mem_btn').prop('disabled', false);
                }
            } else {
                // Count the number of selected values except when value is 0
                var selectedCount = selectedValues.filter(function(value) {
                    return value !== "0";
                }).length;

                // Get the number of options in the last select element
                var lastSelectOptionsCount = $('#member_table .new-rows:last .members option').length;

                // Disable "Add More" button if the number of selected values matches the number of original options minus 1
                // or if the number of options in the last select element is 1
                if (lastSelectOptionsCount === 1 || selectedCount === lastSelectOptionsCount - 1) {
                    $('#mem_btn').prop('disabled', true);
                } else {
                    $('#mem_btn').prop('disabled', false);
                }
            }
        }

        // Event listener for the Add More button
        $('#mem_btn').click(function() {
           
            // Add a new row with a fresh select element
            addNewRows();
        });

        
        // Event listener for selecting an option
        $(document).on('change', '.members', function() {
            var selectedValue = $(this).val();
            if (selectedValue) {
                selectedValues.push(selectedValue);
            }
        });

        $('#member_table').on('click', '.delete-row', function() {
            var selectedValue = $(this).closest('tr').find('.members').val();
            if (selectedValue) {
                // Remove the selected value from the array
                var index = selectedValues.indexOf(selectedValue);
                if (index !== -1) {
                    selectedValues.splice(index, 1);
                }
            }
            
            // Remove the closest tr
            $(this).closest('tr').remove();
            // Update Add More button state
            updateAddMoreButtonStates();
            updateTotal();

        });
    });

    function updateTotal() {
        var inputs = document.getElementsByClassName('amountInput');
        var total = 0;
        for (var i = 0; i < inputs.length; i++) {
            if (!isNaN(inputs[i].value) && inputs[i].value !== '') {
                total += parseFloat(inputs[i].value);
            }
        }
        console.log(total);
        document.getElementById('total_part').value = total;
    }

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

    function calculateTotal() {
        
        var tithesInputs = document.querySelectorAll('.tithes');
        var total = 0;
        tithesInputs.forEach(function(input) {
            var value = parseFloat(input.value);
            total += isNaN(value) ? 0 : value;
        });
        console.log(total);
        var guest = $('#guest_tithe').val();
        
        $('#member_tithe').val(total.toFixed(2));
        total += parseFloat(guest);
        total = total.toFixed(2);
        $('#total_tithe').val(total);

        // Set value to 0 if the textbox is empty
        tithesInputs.forEach(function(input) {
            if (input.value === '') {
                input.value = '';
            }
        });
    }

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
    $('select[name="invited_by[]"]').trigger('change');

    function marks(id){
        // console.log(id);
        var total = $('#total').val();
        var member = $('#member').val();
        var male = $('#male').val();
        var female = $('#female').val();
        var children = $('#children').val();
        var guest = $('#guest').val();
        var applicant = $('#applicant').val();
        
        if($('#customSwitch'+id).prop('checked')){
            var vals = 1;
        } else{
            var vals = 0;
        }

        $.ajax({
            url: site_url + 'service/report/gets/' + id,
            type: 'post',
            data: {total:total,member:member,male:male,female:female,children:children,guest:guest, vals:vals, applicant:applicant},
            success: function (data) {
                $('#respo').html(data);
                
            }
        });
    }

    function get_total(){
        var member = $('#member').val();
        var guest = $('#guest').val();
        
        var total = parseInt(member) + parseInt(guest);
        $('#total').val(total);
    }
    function total_part(){
        var member = $('#member_part').val();
        var guest = $('#guest_part').val();
        
        var total = parseInt(member) + parseInt(guest);
        $('#total_part').val(total.toFixed(2));
    }
    
    get_total();
    function get_tithe(){
        var member = $('#member_tithe').val();
        var guest = $('#guest_tithe').val();
        
        var total = parseFloat(member) + parseFloat(guest);
        total = total.toFixed(2);
        $('#total_tithe').val(total);
    }

    function get_offering(){
        var member = $('#member_offering').val();
        var guest = $('#guest_offering').val();
        
        var total = parseFloat(member) + parseFloat(guest);
        total = total.toFixed(2);
        $('#total_offering').val(total);
    }

    function calculateSum() {
        var sum = 0;
        // Loop through all elements with the class 'members'
        $('.members_amount').each(function() {
            // Parse the value as a float and add it to the sum
            sum += parseFloat($(this).val()) || 0; // If parsing fails, default to 0
        });

        // Round the sum to 2 decimal places
        var roundedSum = Math.round(sum * 100) / 100;

        // Display the rounded sum in the 'member_part' text box
        $('#member_part').val(roundedSum.toFixed(2));
        total_part();
    }

   // Function to bind the calculateSum function to the input event of elements with class 'members_amount'
    function bindInputEvents() {
        $('.members_amount').on('input', calculateSum);
        $('.members_amount').on('input', restrictNumericInput);
        $('.firsts_amount').on('input', calculateFirst);
        $('.firsts_amount').on('input', restrictNumericInput);
    }

    // Function to allow only numeric input with up to two decimal places
    function restrictNumericInput() {
        // Replace any non-numeric characters (except decimal point) with an empty string
        $(this).val($(this).val().replace(/[^0-9.]/g, ''));

        // Allow only one decimal point
        var val = $(this).val();
        var parts = val.split('.');
        if (parts.length > 2) {
            parts.pop();
            $(this).val(parts.join('.'));
        }
    }

    // Call the bindInputEvents function to bind the input events
   



    function calculateFirst() {
        var sum = 0;
        // Loop through all elements with the class 'members'
        $('.firsts_amount').each(function() {
            // Parse the value as a float and add it to the sum
            sum += parseFloat($(this).val()) || 0; // If parsing fails, default to 0
        });

        // Round the sum to 2 decimal places
        var roundedSum = Math.round(sum * 100) / 100;

        // Display the rounded sum in the 'member_part' text box
        $('#guest_part').val(roundedSum.toFixed(2));
        total_part();
    }

    // Bind the calculateSum function to the input event of elements with class 'members'
    $('.firsts_amount').on('input', calculateFirst);

    // Allow only numeric input with up to two decimal places
    $('.firsts_amount').on('input', function() {
        // Replace any non-numeric characters (except decimal point) with an empty string
        $(this).val($(this).val().replace(/[^0-9.]/g, ''));

        // Allow only one decimal point
        var val = $(this).val();
        var parts = val.split('.');
        if (parts.length > 2) {
            parts.pop();
            $(this).val(parts.join('.'));
        }
    });

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
