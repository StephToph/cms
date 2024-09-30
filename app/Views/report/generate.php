
<?php
use App\Models\Crud;
$this->Crud = new Crud();

$this->session = \Config\Services::session();


$username = $this->Crud->read_field('id', $log_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $log_id, 'user', 'surname');
$log_name = $this->Crud->read_field('id', $log_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $log_id, 'user', 'surname');
$email = $this->Crud->read_field('id', $log_id, 'user', 'email');
$ministry_id = $this->Crud->read_field('id', $church_id, 'church', 'ministry_id');
$address = $this->Crud->read_field('id', $church_id, 'church', 'address');
$phone = $this->Crud->read_field('id', $church_id, 'church', 'phone');

$ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
$ministry_logo = $this->Crud->read_field('id', $ministry_id, 'ministry', 'logo');
$log_role_id = $this->Crud->read_field('id', $log_id, 'user', 'role_id');
$log_role = strtolower($this->Crud->read_field('id', $log_role_id, 'access_role', 'name'));
$log_user_img_id = 0;
$log_user_img = $this->Crud->image($log_user_img_id, 'big');

$logo = 'assets/new_logo.png';
$min_title = $title;
if($ministry_id > 0){
    $logo = $ministry_logo;
    $min_title = str_replace('C M S', $ministry, $title);
    // define('app_name', $ministry);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title><?php echo $title; ?></title>
	
    <link rel="shortcut icon" href="<?=site_url($logo); ?>">
   
	<!-- Custom CSS -->
    <link rel="stylesheet" href="<?=site_url(); ?>assets/css/dashlitee5ca.css?ver=3.2.3">
	
</head>

<body class="nk-body bg-white npc-default pg-error">
    <div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-wrap nk-wrap-nosidebar">
                <div class="nk-content" >
                    <div class="nk-block ">
                        <div class="table-responsive"  id="content">
                            <table class="table mb-0">
                                <thead>
                                    <tr class="bg-warning" >
                                        <td width="100px" style="background-color: #007bff;">
                                            <img width="150px" src="<?= site_url($logo); ?>" alt="Logo">
                                        </td>
                                        <td style="background-color: #007bff;">
                                            <h3 class="text-white text-center mb-2"><?= strtoupper($ministry); ?></h3>
                                        </td>
                                        <td width="300px" style="background-color: #007bff;color:#fff;">
                                            <b class="small text-right mb-4">
                                                Generated: 
                                                <?= date("F j, Y, h:i A"); ?><br>
                                            </b>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <td><b>ADDRESS:</b> 
                                            <?=ucwords($address);?>
                                        </td>
                                        <td>
                                            <b>TELEPHONES:</b> 
                                            <?=$phone; ?>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                            
                            <h3 class="text-dark text-center"><?php echo strtoupper($title); ?></h3>
                            <div class="col-sm-12 p-1">
                                <?php $ser = $query;
                                    if($report_title == 'Church Report '){
                                        if (empty($ser)) { 
                                        
                                            ?>
                                            <span class="text-center"> 0 Record</span>
                                            <div class="table-responsive">
                                                <table class="table table-hover" style="border: 1px;"> 
                                                    <thead style="color: black;text-align: center;">
                                                        <tr style="font-weight: bold; ">
                                                            <td>Date</td>
                                                            <td>Service</td>
                                                            <td>Member Present</td>
                                                            <td>Member Absent</td>
                                                            <td >Offering</td>
                                                            <td>TIthe</td>
                                                            <td>Partnership</td>
                                                            <td>New Converts</td>
                                                            <td>First Timer</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="text-align: center;">
                                                        <tr>
                                                            <td colspan="17"><h4>NO RECORD</h4></td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot style="font-weight: bold; text-align: center;">
                                                        <tr>
                                                            <td colspan="3" class="text-sm-end">Member Present <br><b>0</b></td>
                                                            <td>Member Absent <br><b>0</b></td>
                                                            <td >Offering <br><b>0</b></td>
                                                            <td >TIthe <br><b>0</b></td>
                                                            <td >Partnership <br><b>0</b></td>
                                                            <td>New Converts <br><b>0</b></td>
                                                            <td>First Timer <br><b>0</b></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            
                                    <?php } else{?>
                                        <span class="text-center"> <?= ($ser) ? count($ser) : 0; ?> Result</span>
                                        <div class="table-responsive">
                                            <table class="table table-hover" style="border: 1px;"> 
                                                <thead style="color: black;text-align: center;">
                                                    <tr style="font-weight: bold; ">
                                                        <td>S/N</td>
                                                        <td>Date</td>
                                                        <td>Church</td>
                                                        <td>Service</td>
                                                        <td>Member Present</td>
                                                        <td>Member Absent</td>
                                                        <td >Offering</td>
                                                        <td>TIthe</td>
                                                        <td>Partnership</td>
                                                        <td>New Converts</td>
                                                        <td>First Timer</td>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align: center;">
                                                    <?php $a =1; 
                                                    $total_present = 0;
                                                    $total_absent = 0;
                                                    $total_offering = 0;
                                                    $total_tithe = 0;
                                                    $total_partnership = 0;
                                                    $total_timer = 0;
                                                    $total_convert = 0;
                                                    
                                                    foreach($ser as $rec){ 
                                                        $members = $this->Crud->check2('is_member', 1, 'church_id',  $rec->church_id, 'user');
                                                        $absent = (int)$members - (int)$rec->attendance;
                                                        $total_absent += (int)$absent;
                                                        ?>
                                                        <tr>
                                                            <td><?=$a; ?></td>
                                                            <td><?=$rec->date; ?></td>
                                                            <td class="text-capitalize"><?=$this->Crud->read_field('id', $rec->church_id, 'church', 'name').' '.$this->Crud->read_field('id', $rec->church_id, 'church', 'type'); ?></td>
                                                            <td class="text-capitalize"><?=$this->Crud->read_field('id', $rec->type, 'service_type', 'name'); ?></td>
                                                            <td><?=number_format($rec->attendance); ?></td>
                                                            <td><?=number_format($absent); ?></td>
                                                            <td ><?=curr.number_format((float)$rec->offering,2); ?></td>
                                                            <td><?=curr.number_format((float)$rec->tithe,2); ?></td>
                                                            <td><?=curr.number_format((float)$rec->partnership,2); ?></td>
                                                            <td><?=number_format($rec->new_convert); ?></td>
                                                            <td><?=number_format($rec->first_timer); ?></td>
                                                    

                                                        </tr>
                                                    <?php 
                                                        $a++;
                                                        $total_present += (int)$rec->attendance;
                                                        $total_offering += (float)$rec->offering;
                                                        $total_tithe += (float)$rec->tithe;
                                                        $total_partnership += (float)$rec->partnership;
                                                        $total_convert += (int)$rec->new_convert;
                                                        $total_timer += (int)$rec->first_timer;
                                                        
                                                    } ?>
                                                </tbody>
                                                <tfoot style="font-weight: bold; text-align: center;">
                                                    <tr>
                                                        <td colspan="5" class="text-sm-end">Member Present <br><b  class="text-danger"><?=number_format($total_present);?></b></td>
                                                        <td>Member Absent <br><b class="text-danger"><?=number_format($total_absent);?></b></td>
                                                        <td >Offering <br><b class="text-danger"><?=curr.number_format($total_offering,2);?></b></td>
                                                        <td >TIthe <br><b class="text-danger"><?=curr.number_format($total_tithe,2);?></b></td>
                                                        <td >Partnership <br><b  class="text-danger"><?=curr.number_format($total_partnership,2);?></b></td>
                                                        <td>New Converts <br><b class="text-danger"><?=number_format($total_convert);?></b></td>
                                                        <td>First Timer <br><b class="text-danger"><?=number_format($total_timer);?></b></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    <?php } 
                                } ?>
                            </div>
                                
                            
                        </div>
                        <hr class="light-grey-hr">
                        <i>This document should be treated as confidencial</i> | <a href="javascript:;"  onclick="printDiv('content')"><b>Print Report</b></a>  | <a href="javascript:;" onClick="printdoc();"><b>Save as PDF</b></a>  | <a href="javascript:;" onClick="printdoc();"><b>Save as Image</b></a> | <a class="text-primary" href="<?php echo base_url('report/list'); ?>" data-toggle="tooltip" title="Go Back"><i class=" icon ni ni-arrow-left-circle fa-1x"></i></a>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
	
    <script src="<?=site_url(); ?>assets/js/bundle.js"></script>
    <script src="<?=site_url(); ?>assets/js/scriptse5ca.js"></script>
	<script>
        function printDiv(divId) {
            var content = document.getElementById(divId).innerHTML;
            var myWindow = window.open('', '', 'width=600,height=400');
            myWindow.document.write('<html><head><title>Print</title>');
            myWindow.document.write('</head><body>');
            myWindow.document.write(content);
            myWindow.document.write('</body></html>');
            myWindow.document.close();
            myWindow.print();
        }
    </script>

    <div class="js-preloader">  <div class="loading-animation duo-pulse"></div></div>
</body>

</html>
