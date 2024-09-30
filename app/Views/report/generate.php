
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
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr class="bg-warning" >
                                        <td width="100px" style="background-color: #007bff;">
                                            <img src="<?= site_url($logo); ?>" alt="Logo">
                                        </td>
                                        <td align="center" style="background-color: #007bff;">
                                            <h3 class="text-white mb-2"><?= strtoupper($ministry); ?></h3>
                                        </td>
                                        <td align="right" width="300px" style="background-color: #007bff;color:#fff;">
                                            <b class="small mb-4">
                                                Generated: 
                                                <?= date("F j, Y, h:i A"); ?><br>
                                            </b>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                            <table class="table mb-0" style="font-size: 15px">
                                <thead>
                                    <tr>
                                    <td align=""><b>ADDRESS:</b> 
                                        <?=ucwords($address);?>
                                    </td>
                                    <td align="left">
                                        <b>TELEPHONES:</b> 
                                        <?=$phone; ?>
                                    </td>
                                    </tr>
                                </thead>
                            </table>
                            
                            <h5 class="txt-dark capitalize-font" align="center"><?php echo $title; ?></h5>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?php $ser = $query;
                                    if (empty($ser)) { ?>
                                        <div align="right">0 Record</div>
                                        <div class="panel panel-default cardview">
                                            <div class="pane-wrapper collapse in">
                                                <div class="panel-body">
                                                    <table style="border: 1px;"> 
                                                        <thead style="color: black;">
                                                            <tr>
                                                                <td>
                                                                    <table class="table table-hovr table-bordered mb-0">
                                                                        <thead>
                                                                            <tr style="font-weight: bold; font-size: 13px">
                                                                                <td>Date</td>
                                                                                <td>Customer</td>
                                                                                <td>Company</td>
                                                                                <td >Vehicle</td>
                                                                                <td >Slip No.</td>
                                                                                <td align="center">G/W</td>
                                                                                <td align="center">T/W</td>
                                                                                <td align="center">NET/W</td>
                                                                                <td align="right">AUG Amt. (&#8358;)</td>
                                                                                <td align="right">Company Amt. (&#8358;)</td>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="font-size: 12px; text-align: center;">
                                                                            <tr>
                                                                                <td colspan="17">NO RECORD</td>
                                                                            </tr>
                                                                        </tbody>
                                                                        <tfoot style="background-color:#ddd;font-weight: bold;font-size: 12px; text-align: center;">
                                                                            <tr>
                                                                                <td colspan="5" >GRAND TOTAL</td>
                                                                                <td ><div>G/W</div>0</td>
                                                                                <td ><div>T/W</div>0</td>
                                                                                <td ><div>NET/W</div>0</td>
                                                                                <td><div>QTY - DUST</div>0</td>
                                                                                <td  align="right"><div>AUG Amt</div>
                                                                                    &#8358;0.00</td>
                                                                                <td  align="right"><div>Company Amt</div>
                                                                                    &#8358;0.00</td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                <?php } ?>
                            </div>
                                
                            <hr class="light-grey-hr">
                            <i>This document should be treated as confidencial</i> | <a href="javascript:;" onClick="printdoc();"><b>Print Report</b></a>  | <a href="javascript:;" onClick="printdoc();"><b>Save as PDF</b></a>  | <a href="javascript:;" onClick="printdoc();"><b>Save as Image</b></a> | <a class="text-primary" href="<?php echo base_url('report/list'); ?>" data-toggle="tooltip" title="Go Back"><i class=" icon ni ni-arrow-left-circle fa-1x"></i></a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
    <script src="<?=site_url(); ?>assets/js/bundle.js"></script>
    <script src="<?=site_url(); ?>assets/js/scriptse5ca.js"></script>
	
    <div class="js-preloader">  <div class="loading-animation duo-pulse"></div></div>
</body>

</html>
