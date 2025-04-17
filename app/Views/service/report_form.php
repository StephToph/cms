
<?php
use App\Models\Crud;
$this->Crud = new Crud();

$this->session = \Config\Services::session();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form2', 'class'=>'')); 
   
?>
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
                <a class="nav-link" data-bs-toggle="tab" href="#tabItem5"><em class="icon ni ni-wallet"></em><span>Finance</span></a>    
            </li>    
            
        </ul>

        <div class="tab-content">    
            <div class="tab-pane active" id="tabItem1">    
                <div class="row">
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">Service Date</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=date('d F Y', strtotime($date)); ?></p>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">Service Type</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=$types; ?></p>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">Attendance</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=number_format($attendance)?></p>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">First Timer</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=number_format($timer); ?></p>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">New Converts</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                    <p><?=number_format($convert); ?></p>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">Offering</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=$this->session->get('currency').number_format($total_offering, 2); ?></p>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">Tithe</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=$this->session->get('currency').number_format($total_tithe, 2); ?></p>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">Special Seed</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=$this->session->get('currency').number_format($total_seed, 2); ?></p>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">Thanksgiving</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=$this->session->get('currency').number_format($total_thanksgiving, 2); ?></p>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">Partnership</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=$this->session->get('currency').number_format($total_part, 2); ?></p>
                    </div>
                    
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">Note</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=ucwords($note); ?></p>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <label class="fw-bold">Input Date</label>
                    </div>
                    <div class="col-sm-9 mb-3">
                        <p><?=date('d M Y h:iA', strtotime($reg_date)); ?></p>
                    </div>
                
                </div>
            </div>    
            <div class="tab-pane" id="tabItem2">  
                <div class="row">      
                    <?php if(empty($member_attendance)){
                        echo '
                            <div class="col-sm-12">No Attendance Record</div>
                        ';
                    } else {    

                        $general_response = '<div class="table-responsive"><table class="table table-hover">';
                        if (!empty($member_attendance)) {
                            
                            foreach ($member_attendance as $q) {
                                
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
                                    <td>
                                        '.ucwords($status).'<br>'.$absent_reason.'
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
            <div class="tab-pane" id="tabItem5">   
                <div class="row">     
                    <?php if(empty($finance)){
                        echo '
                            <div class="col-sm-12">No Record</div>
                        ';
                    } else {
                        $general_response = '<div class="table-responsive"><table class="table table-hover table-striped">';
                        if (!empty($finance)) {
                            
                            foreach ($finance as $q){
                                if($q->amount <= 0)continue;
                                $surname = $this->Crud->read_field('id', $q->user_id, 'user', 'surname');
                                $othername = $this->Crud->read_field('id', $q->user_id, 'user', 'othername');
                                $firstname = $this->Crud->read_field('id', $q->user_id, 'user', 'firstname');
                                $name = $surname.' '.$firstname.' '.$othername;
                                if($q->user_type == 'guest'){
                                    $name = $guest;
                                }
                                $part = '';
                                if($q->finance_type == 'partnership'){
                                    $part = $this->Crud->read_field('id', $q->partnership_id, 'partnership', 'name');
                                }
                                $general_response .= '
                                <tr>
                                    <td>' . ucwords(strtolower($name )) . '</td>
                                    <td>'.ucwords($q->user_type).'</td>
                                    <td>'.ucwords($q->finance_type).'<br>'.$part.'</td>
                                    <td>'.$this->session->get('currency').number_format($q->amount,2).'</td>
                                    
                                </tr>';
                            
                            }
                        }

                        $general_response .= '</table></div> ';
                        echo $general_response;
                    }?>
                </div>
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
                <input class="form-control" id="member" type="text" name="member"  value="0">
            </div>
            <div class="col-sm-4 mb-3">
                <label>First Timer</label>
                <input class="form-control" id="guest" type="text" name="guest" readonly value="0">
            </div>
            
            <div class="col-sm-4 mb-3">
                <label>Male</label>
                <input class="form-control" id="male" type="text" name="male"  value="0">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Female</label>
                <input class="form-control" id="female" type="text" name="female"  value="0">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Children</label>
                <input class="form-control" id="children" type="text" name="children"  value="0">
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
     
    <?php if($param2 == 'tithe'){
        $converts = json_decode($this->Crud->read_field('id', $param3, 'service_report', 'tithers'));
        if(empty($param3)){
            $session_tithe = $this->session->get('service_tithe');
            if(!empty($session_tithe)){
                $converts = json_decode($session_tithe);
            }
        }
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
                <input class="form-control" id="member_offering" type="text" name="member_offering"  value="<?=($member); ?>" oninput="get_offering();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')">
            </div>
            <div class="col-sm-4 mb-3">
                <label>Guest</label>
                <input class="form-control" id="guest_offering" type="text" name="guest_offering" oninput="get_offering();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" value="<?=($guest); ?>">
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
                                        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                                        if(empty($church_id)){
                                            $church_id = $this->session->get('service_church_id');
                                        }
                                        $mem = $this->Crud->read2_order('is_member', 1, 'church_id', $church_id, 'user', 'firstname', 'asc');
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
                                        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                                        if(empty($church_id)){
                                            $church_id = $this->session->get('service_church_id');
                                        }
                                        $mem = $this->Crud->read2_order('is_member', 1, 'church_id', $church_id, 'user', 'firstname', 'asc');
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
    <?php if($param2 == 'timers') { ?>
        
        <div class="row">
            <input type="hidden" name="service" value="<?php if(!empty($param3)){echo $param3;} ?>" />

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
            <div class="col-sm-4">
                <label for="country" class="form-label fw-bold">Country </label>
                <input type="text" class="form-control" readonly id="country" name="country" placeholder="Your country">
            </div>
            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label">State</label>
                    <select class="form-select js-select2" name="state_id" id="state_id" data-placeholder="Select" >
                       
                    </select>
                </div>
            </div>
                            
            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label class="form-label" for="name">*<?=translate_phrase('Postal Code');?></label>
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
                <?php
                    $ministry_id = $this->Crud->read_field('id', $param3, 'service_report', 'ministry_id');
                    $church_id = $this->Crud->read_field('id', $param3, 'service_report', 'church_id');
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
<input type="hidden" id="respo">
<input type="hidden" id="applicant">
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<!-- Include jQuery library -->

<script>
     fetch("http://ip-api.com/json/")
    .then(res => res.json())
    .then(data => {
        console.log("Country:", data.country);
        get_state(data.country);
        document.getElementById('country').value = data.country;
    });

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
    $(function() {
        $('.js-select2').select2();
    });
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd', // Set the date format
        autoclose: true
    });
    
   function updateTotals() {
        // Get values from the input fields
        var memberValue = parseInt($('#member').val()) || 0;
        var guestValue = parseInt($('#guest').val()) || 0;
        var maleValue = parseInt($('#male').val()) || 0;
        var femaleValue = parseInt($('#female').val()) || 0;
        var childrenValue = parseInt($('#children').val()) || 0;

        // Calculate the total
        var total = memberValue + guestValue + maleValue + femaleValue + childrenValue;

        // Update the total field
        $('#total').val(total);
    }

    // Attach event listeners to input fields
    $('#member, #guest, #male, #female, #children').on('input', updateTotals);
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
        // console.log(total);
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
