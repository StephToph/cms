<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
    
    $this->session = \Config\Services::session();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
<div class="nk-content" >
    <div class="container-fluid mt-3">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Service Report');?></h3>
                            
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner position-relative card-tools-toggle">
                                <div class="card-title-group">
                                    <div class="card-tools">
                                        
                                    </div><!-- .card-tools -->
                                    <div class="card-tools me-n1">
                                        <ul class="btn-toolbar gx-1">
                                            <li>
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Report"  id="add_btn" class="btn btn-icon btn-outline-primary"><em class="icon ni ni-plus-c"></em></a>
                                            </li><!-- li -->
                                            <li>
                                                <div class="nk-block-head-sub mb-3" id="attendance_prev" style="display:none;">
                                                    <a class="btn btn-outline-danger" id="back_btn" href="javascript:;"><em class="icon ni ni-arrow-left"></em><span>Service Reports</span></a>
                                                </div>
                                            </li>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                
                            </div><!-- .card-inner -->
                            <div class="card-inner" id="show">
                                <div class="nk-tb-list nk-tb-ulist" id="load_data">
                                </div><!-- .nk-tb-list -->
                            
                                <div class="nk-block-between-md g-3" id="loadmore">
                                </div><!-- .nk-block-between -->
                            </div>
                            <div class="card-inner" id="form" style="display:none;">
                               <div class="row">
                                    <div class="nk-block-head-sub mb-3" id="prev" style="display:none;">
                                        <a class="back-to btn btn-outline-primary" id="back_btn" href="javascript:;"><em class="icon ni ni-arrow-left"></em><span>Service Reports</span></a>
                                    </div>
                                    <h5>Enter the Details for the Service Meeting Below</h5>
                                    <p class="text-danger">Always click the save record Button after update of attendance, first timers and new convert.</p>
                                    <?php echo form_open_multipart('service/report/manage', array('id'=>'bb_ajax_form', 'class'=>'row mt-4')); ?>
                                        <input type="hidden" name="report_id" id="report_id" value="<?php if(!empty($e_id)){echo $e_id;}?>">
                                        <?php 
                                        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                                        if ($ministry_id <= 0) { ?>
                                            <div class="col-sm-4 mb-3">
                                                <div class="form-group">
                                                    <label class="name">Ministry </label> 
                                                    <select id="ministry_id" name="ministry_id" class="js-select2 " onchange="load_level();">
                                                        <option value=" ">Select Ministry</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } else {?>
                                            <input type="hidden" id="ministry_id" value="<?=$ministry_id;?>">


                                        <?php } ?>
                                        <?php if($role != 'church leader'){
                                            $log_church_id = $this->Crud->read_field('id', $log_id, 'user',  'church_id');
                                            ?>
                                            <div class="col-sm-4 mb-3" >
                                                <label class="name">Church Level</label> 
                                                <select class="js-select2" name="level" id="level" onchange="load_level();">
                                                    <option value=" ">Select Church Level</option>
                                                    
                                                </select>
                                            </div>

                                            <div class="col-sm-4 mb-3" id="church_div" style="display:none;">
                                                <div class="form-group">
                                                    <label>Church</label>
                                                    <select class="js-select2" data-search="on" name="church_id" id="church_id" onchange="session_church();">
                                                        <option value=" ">Select Church</option>

                                                    </select>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Service Type'); ?></label>
                                                <select data-search="on" class=" js-select2" id="type" name="type" required>
                                                    <option value="0">Select</option>
                                                    <?php
                                                        $type = $this->Crud->read_order('service_type', 'name', 'asc');
                                                        if(!empty($type)){
                                                            foreach($type as $t){
                                                                echo '<option value="'.$t->id.'">'.ucwords($t->name).'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">    
                                                <label class="name">*Date</label>    
                                                <div class="form-control-wrap">        
                                                    <input type="text" name="dates" id="dates" class="form-control date-picker" required>   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name"><?=translate_phrase('First Timer'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text" id="first_timer" name="first_timer" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" value="0">        
                                                          
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Attendance'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text"  name="attendance" id="attendance" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" placeholder="0">        
                                                         
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name"><?=translate_phrase('New Convert'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text" id="new_convert" class="form-control" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" name="new_convert" placeholder="0">        
                                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Offering'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text" name="offering" id="offering" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" placeholder="0">        
                                                           
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Tithe'); ?></label> 
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text"  name="tithe" id="tithe" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" placeholder="0">        
                                                           
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-group">
                                                <label for="name">*<?=translate_phrase('Partnership'); ?></label>
                                                <div class="form-control-wrap">    
                                                    <div class="input-group">        
                                                        <input type="text"  name="partnership" id="partnership" oninput="this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" class="form-control" placeholder="0">        
                                                       
                                                    </div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12 mb-3">
                                            <div class="form-group">
                                                <label for="name"><?=translate_phrase('Note'); ?></label>
                                                <textarea class="form-control"  id="note" name="note" ></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" name="attendant" id="attendant">
                                        <input type="hidden" name="converts" id="converts">
                                        <input type="hidden" name="timers" id="timers">
                                        <input type="hidden" name="tither" id="tither">
                                        <input type="hidden" name="offering_givers" id="offering_givers">
                                        <input type="hidden" name="partners" id="partners">
                                        
                                        <div class="col-sm-12 text-center mt-3">
                                            <button class="btn btn-primary bb_fo_btn" type="submit">
                                                <em class="icon ni ni-save"></em> <span><?=translate_phrase('Save Record');?></span>
                                            </button>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-sm-12 my-3"><div id="bb_ajax_msg"></div></div>
                                    </div>
                               </div>
                            </div>
                            <div class="card-inner" id="attendance_view" style="display:none;">
                                <form  id="attendanceForm">
                                    <div class="row">
                                        <input type="hidden" name="attendance_id" id="attendance_id">
                                        <span class="text-danger mb-2">Enter Attendance</span>
                                        <div class="col-sm-4 mb-3 ">
                                            <label>Total</label>
                                            <input class="form-control" id="total_attendance" type="text" name="total"  readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Member</label>
                                            <input class="form-control" id="member_attendance" type="text" name="member"  value="0" placeholder="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>First Timer</label>
                                            <input class="form-control" id="guest_attendance" type="text" name="guest"  value="" placeholder="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Male</label>
                                            <input class="form-control" id="male_attendance" type="text" name="male"  value="" placeholder="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Female</label>
                                            <input class="form-control" id="female_attendance" type="text" name="female"  value="" placeholder="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Children</label>
                                            <input class="form-control" id="children_attendance" type="text" name="children"  value="" placeholder="0">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mt-5" >
                                        <div class="col-sm-12 text-center mt-5">
                                            <button class="btn btn-primary bb_fo_btn" type="submit">
                                                <i class="icon ni ni-save"></i> <span><?=translate_phrase('Save Record');?></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 my-2"><div id="attendance_msg"></div></div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-inner" id="tithe_view" style="display:none;">
                                <form  id="titheForm">
                                    <div class="row">
                                        <span class="text-danger mb-2">Enter Member's Tithe in the Table Below</span>
                                        <input type="hidden" name="tithe_id" id="tithe_id">
                                        
                                        <div class="col-sm-4 mb-3 ">
                                            <label>Total</label>
                                            <input class="form-control" id="total_tithe" type="text" name="total_tithe"  readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Member</label>
                                            <input class="form-control" id="member_tithe" type="text" name="member_tithe"  readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Guest</label>
                                            <input class="form-control" id="guest_tithe" type="text" name="guest_tithe" oninput="get_tithe();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" value="0">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="table-responsive">
                                        <table id="tithe_table" class="table table-striped table-hover mt-5">
                                            <thead>
                                                <tr>
                                                    <th>Member</th>
                                                    <th width="200px">Tithe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <nav style="display:none;" id="tithe_pagination">    
                                            <ul class="pagination justify-content-center my-3" id="tithe_table-pagination">        
                                                <li class="page-item" >
                                                    <a class="page-link" id="tithe_table-prev-button" href="javascript:;" tabindex="-1" aria-disabled="true">Prev</a>
                                                </li>        
                                                <li class="page-item">
                                                    <span class="page-link" id="tithe_table-page-info">Page 1 of 1</span>
                                                </li>     
                                                <li class="page-item">
                                                    <a class="page-link" id="tithe_table-next-button" href="javascript:;">Next</a>
                                                </li>    
                                            </ul>
                                        </nav>
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
                                        <div class="col-sm-12"><div id="tithe_msg"></div></div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-inner" id="new_convert_view" style="display:none;">
                                <form  id="new_convert_Form">
                                    <input type="hidden" name="new_convert_id" id="new_convert_id">
                                       
                                    <div id="rowsContainer">
                                        <!-- Rows will be dynamically added here -->
                                    </div>
                                    <div class="col-sm-12 my-4 text-center">
                                        <button id="addMores" class="btn btn-block btn-ico btn-outline-info" type="button"><i class="icon ni ni-plus-c"></i>  <span><?=translate_phrase('Add More');?></span></button>
                                    </div>


                                    <div class="row" >
                                        <div class="col-sm-12 text-center my-5">
                                            <button class="btn btn-primary bb_fo_btn" type="submit">
                                                <i class="icon ni ni-save"></i> <span><?=translate_phrase('Save Record');?></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12"><div id="new_convert_msg"></div></div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-inner" id="first_timer_view" style="display:none;">
                                <form  id="first_timer_Form">
                                    <input type="hidden" name="new_convert_id" id="first_timer_id">
                                    <input type="hidden" name="first_church_id" id="first_church_id">
                                       
                                    <div id="containers">
                                        <!-- Rows will be dynamically added here -->
                                    </div>
                                    <div class="col-sm-12 my-4 text-center">
                                        <button id="add_first_timer" class="btn btn-block btn-ico btn-outline-info" type="button"><i class="icon ni ni-plus-c"></i>  <span><?=translate_phrase('Add More');?></span></button>
                                    </div>


                                    <div class="row" >
                                        <div class="col-sm-12 text-center my-5">
                                            <button class="btn btn-primary bb_fo_btn" type="submit">
                                                <i class="icon ni ni-save"></i> <span><?=translate_phrase('Save Record');?></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12"><div id="first_timer_msg"></div></div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-inner" id="partnership_view" style="display:none;">
                                <form  id="partnershipForm">
                                    <input type="hidden" name="partnership_id" id="partnership_id">
                                    <input type="hidden" name="first_church_id" id="first_church_id">

                                    <div class="row">
                                        <span class="text-danger mb-2">Enter Member's Partnership in the Table Below</span>
                                        <div class="col-sm-4 mb-3 ">
                                            <label>Total</label>
                                            <input class="form-control" id="total_part" type="text" name="total_part"  readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>Member</label>
                                            <input class="form-control" id="member_part" type="text" name="member_part"  readonly value="0">
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label>First Timer</label>
                                            <input class="form-control" id="guest_part" type="text" name="guest_part" oninput="get_part();this.value = this.value.replace(/[^\d.]/g,'');this.value = this.value.replace(/(\..*)\./g,'$1')" readonly value="0">
                                        </div>
                                    </div>
                                                        
                                    <hr>
                                    <div class="table-responsive">
                                        <div id="guest_part_view" style="display:none;">
                                            <table class="table table-striped table-hover mt-5">
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
                                                <tbody id="guest_partner_list">
                                                    
                                                </tbody>
                                            </table>
                                            <div class="col-12 my-3 text-center">
                                                <p id="first_resp"></p>
                                                <button type="button" class="btn btn-info" id="more_btn">Add More</button>
                                            </div>
                                        </div>
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

                                    <div class="row" >
                                        <div class="col-sm-12 text-center my-5">
                                            <button class="btn btn-primary bb_fo_btn" type="submit">
                                                <i class="icon ni ni-save"></i> <span><?=translate_phrase('Save Record');?></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12"><div id="partnership_msg"></div></div>
                                    </div>
                                </form>
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

<script>var site_url = '<?php echo site_url(); ?>';</script>
<script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script src="<?php echo site_url(); ?>assets/js/service_report.js?v=<?=time(); ?>"></script>
<?=$this->endSection();?>