<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
<style>
       

       /* Small screens (up to 576px) */
       @media (max-width: 767px) {
           .idcard {
               height: 230px;
              
           }
           .idcards {
               height: 300px;
              
           }
           .hexagon-container {
               width: 35%;
               height: 113.96px;
               position: relative;
               overflow: hidden;
               top: 17%;
               left: 3%;
           }
           .qr-container {
               width: 18%;
               height: 54.96px;
               position: relative;
               overflow: hidden;
               top: 1%;
               left: 60%;
           }
           .qr-image {
               width: 100%;
               height: 100%;
               background-color: #2263b3 ; 
           }
           .qr-image img {
               width: 100%;
               height: 100%;
               object-fit: fill; /* Ensure the image covers the hexagon shape */
           }

           .hexagon-image {
               width: 100%;
               height: 100%;
               background-color: #2263b3 ; /* Background color for the hexagon */
           }

           .hexagon-image img {
               width: 100%;
               height: 100%;
               object-fit: fill; /* Ensure the image covers the hexagon shape */
           }
           #qrcode{
               margin:1%;
               margin-top:0%;
               margin-bottom:1%;
           }
           .name{
               position: relative;
               overflow: hidden;
               left: 18%;
               top: 0%;
               font-size: 15px;
           }
           .tax{
               position: relative;
               overflow: hidden;
               left: 27%;
               top: 0%;
               font-size: 16px;
               width: 100%;
           }
           .qr-containers {
               width: 41%;
               height: 36%;
               position: relative;
               overflow: hidden;
               top: 14%;
               left: 30%;
           }
           
           .qr-images {
               width: 100%;
               height: 100%;
               background-color: #2263b3 ; 
           }
           .qr-images img {
               width: 100%;
               height: 100%;
               object-fit: fill; /* Ensure the image covers the hexagon shape */
           }
           .taxs{
               position: relative;
               overflow: hidden;
               color: black;
               left: 3%;
               top: 23%;
               font-size: 28px;
               width:100%;
           }
           #qrcodes{
               margin:10%;
               margin-top:0%;
               margin-bottom:1%;
           }
       }

       /* Medium screens (577px to 992px) */
       @media (min-width: 768px) and (max-width: 991px) {
           .idcard {
               height: 300px;
              
           }
           
           .idcards {
               height: 300px;
              
           }
           .hexagon-container {
               width: 190px;
               height: 147.96px;
               position: relative;
               overflow: hidden;
               top: 17%;
               left: 5%;
           }
           .qr-container {
               width: 93px;
               height: 71.96px;
               position: relative;
               overflow: hidden;
               top: -3%;
               left: 60%;
           }
           .qr-image {
               width: 100%;
               height: 100%;
               background-color: #2263b3 ; 
           }
           .qr-image img {
               width: 100%;
               height: 100%;
               object-fit: fill; /* Ensure the image covers the hexagon shape */
           }

           .hexagon-image {
               width: 100%;
               height: 100%;
               background-color: #2263b3 ; /* Background color for the hexagon */
           }
           .hexagon-image::before{
               
               z-index: -1;
           }

           .hexagon-image img {
               width: 100%;
               height: 100%;
               object-fit: fill; /* Ensure the image covers the hexagon shape */
           }
           #qrcode{
               margin:8%;
               margin-top:0%;
               margin-bottom:1%;
           }
           #qrcodes{
               margin:8%;
               margin-top:0%;
               margin-bottom:1%;
           }
           .name{
               position: relative;
               overflow: hidden;
               left: 12%;
               top: 0%;
               font-size: 21px;
           }
           .tax{
               position: relative;
               overflow: hidden;
               left: 26%;
               top: -3%;
               font-size: 28px;
               width: 100%;
           }
           
           .qr-containers {
               width: 41%;
               height: 36%;
               position: relative;
               overflow: hidden;
               top: 14%;
               left: 30%;
           }
           
           .qr-images {
               width: 100%;
               height: 100%;
               background-color: #2263b3 ; 
           }
           .qr-images img {
               width: 100%;
               height: 100%;
               object-fit: fill; /* Ensure the image covers the hexagon shape */
           }
           .taxs{
               position: relative;
               overflow: hidden;
               color: black;
               left: 3%;
               top: 23%;
               font-size: 28px;
               width:100%;
           }
           #qrcodes{
               margin:20%;
               margin-top:0%;
               margin-bottom:1%;
           }
       }

       /* Large screens (993px and above) */
       @media (min-width: 992px) {
           .idcard {
               height: 400px;
              
           }
           
           .idcards {
               height: 400px;
              
           }
           .hexagon-container {
               width: 34.5%;
               height: 198px;
               position: relative;
               overflow: hidden;
               top: 17%;
               left: 5%;   
           }
           .qr-container {
            width: 114px;
            height: 100.96px;
            position: relative;
            overflow: hidden;
            top: 1%;
            left: 60%;
           }
           .qr-image {
               width: 100%;
               height: 100%;
               background-color: #2263b3 ; 
           }
           .qr-image img {
               width: 100%;
               height: 100%;
               object-fit: fill; /* Ensure the image covers the hexagon shape */
           }
           .qr-containers {
               width: 41%;
               height: 36%;
               position: relative;
               overflow: hidden;
               top: 14%;
               left: 30%;
           }
           
           .qr-images {
               width: 100%;
               height: 100%;
               background-color: #2263b3 ; 
           }
           .qr-images img {
               width: 100%;
               height: 100%;
               object-fit: fill; /* Ensure the image covers the hexagon shape */
           }

           .hexagon-image {
               width: 100%;
               height: 100%;
               background-color: #2263b3 ; /* Background color for the hexagon */
           }

           .hexagon-image img {
               width: 100%;
               height: 100%;
               object-fit: fill; /* Ensure the image covers the hexagon shape */
           }
           #qrcode{
               margin:20%;
               margin-top:0%;
               margin-bottom:1%;
           }
           #qrcodes{
               margin:30%;
               margin-top:0%;
               margin-bottom:1%;
           }
           .name{
               position: relative;
               overflow: hidden;
               left: 8%;
               top: 0%;
               font-size: 23px;
           }
           .tax{
               position: relative;
               overflow: hidden;
               left: 23%;
               top: 1%;
               font-size: 29px;
               width:100%;
           }

           .taxs{
               position: relative;
               overflow: hidden;
               left: 0%;
               top: 25%;
               font-size: 30px;
               width:100%;
           }
          
       }
       
   </style>
<!-- content @s -->
<div class="nk-content" >
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body mt-5">
                <div class="nk-block-head nk-block-head-sm ">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content  mt-5">
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Account');?> / <strong class="text-primary small"><?=ucwords($fullname); ?></strong></h3>
                            <div class="nk-block-des text-soft">
                                <ul class="list-inline">
                                    <li><?=translate_phrase('Last Login');?>: <span class="text-base"><?=$last_log; ?></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="<?=site_url('accounts/personal'); ?>" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span><?=translate_phrase('Back');?></span></a>
                            <a href="<?=site_url('accounts/personal'); ?>" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                        </div>
                    </div>
                    <input type="hidden" id="u_id" value="<?=$id; ?>">
                </div><!-- .nk-block-head -->
                <div class="nk-block nk-block-lg">
                    <div class="card card-bordered">
                        <div class="card-aside-wrap">
                            <div class="card-content">
                                <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#personal-info"><em class="icon ni ni-user-circle"></em><span><?=translate_phrase('Personal');?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#notification"><em class="icon ni ni-bell"></em><span><?=translate_phrase('Notifications');?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"  data-bs-toggle="tab" href="#activity"><em class="icon ni ni-activity"></em><span><?=translate_phrase('Activities');?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"  data-bs-toggle="tab" href="#card" href="#card"><em class="icon ni ni-view-panel"></em><span>ID<?=translate_phrase(' Card'); ?></span></a>
                                    </li>
                                    <li class="nav-item nav-item-trigger d-xxl-none">
                                        <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-user-list-fill"></em></a>
                                    </li>
                                </ul><!-- .nav-tabs -->
                                <div class="card-inner">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personal-info">
                                            <div class="nk-block">
                                                <div class="nk-block-head">
                                                    <h5 class="title"><?=translate_phrase('Personal Information');?></h5>
                                                </div><!-- .nk-block-head -->
                                                <div class="profile-ud-list">
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Full Name');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($fullname); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Tax ID');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($tax_id); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Mobile Number');?></span>
                                                            <span class="profile-ud-value"><?=$v_phone; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Email Address');?></span>
                                                            <span class="profile-ud-value"><?=$v_email; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Country');?></span>
                                                            <span class="profile-ud-value"><?=$v_country; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('State');?></span>
                                                            <span class="profile-ud-value"><?=$v_state; ?> State</span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('LGA');?></span>
                                                            <span class="profile-ud-value"><?=$v_city; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Address');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_address); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Territory');?></span>
                                                            <span class="profile-ud-value"><?=ucwords(str_replace('_', ' ', $v_territory)); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Trade Line');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_trade); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Payment Duration');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_duration); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Joining Date');?></span>
                                                            <span class="profile-ud-value"><?=$reg_date; ?></span>
                                                        </div>
                                                    </div>
                                                </div><!-- .profile-ud-list -->
                                            </div><!-- .nk-block -->
                                            <div class="nk-block">
                                                <div class="nk-block-head nk-block-head-line">
                                                    <h6 class="title overline-title text-base"><?=translate_phrase('Additional Information');?></h6>
                                                </div><!-- .nk-block-head -->
                                                <div class="profile-ud-list">
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Utility Bill');?></span>
                                                            <span class="profile-ud-value"><?php if(empty($v_utility) || !file_exists($v_utility)){echo '-';} else{echo '<img src="'.site_url($v_utility).'"></span>';} ?>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('ID Card');?></span>
                                                            <span class="profile-ud-value"><?php if(empty($v_id_card) || !file_exists($v_id_card)){echo '-';} else{echo '<img src="'.site_url($v_id_card).'"></span>';} ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('QR Code');?></span>
                                                            <span class="profile-ud-value"><?php if(empty($v_qrcode) || !file_exists($v_qrcode)){echo '-';} else{echo '<img src="'.site_url($v_qrcode).'"></span>';} ?> </span>
                                                        </div>
                                                    </div>
                                                    
                                                </div><!-- .profile-ud-list -->
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="order">
                                            <div class="nk-block-head">
                                                <h5 class="title"><?=$fullname;?>'s <?=translate_phrase('Transaction History');?></h5>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-tb-list border border-light rounded overflow-hidden is-compact" id="transaction_data">
                                            </div>
                                            <div id="transaction_more"></div><!-- .nk-block -->
                                        </div>
                                        <div class="tab-pane" id="card">
                                            <div class="nk-block-head">
                                                <div class="nk-block-head-content">
                                                    <h4 class="nk-block-title">ID<?=translate_phrase(' Card');?></h4>
                                                    <div class="nk-block-des">
                                                    </div>
                                                </div>
                                            </div><!-- .nk-block-head -->
                                            
                                            <div class="nk-block">
                                                <div class="nk-data data-list data-list-s2">
                                                    <div class="data-head">
                                                        <h6 class="overline-title"></h6>
                                                    </div>
                                                    <div class="row text-center bg-white qrs" id="qrcode">
                                                        
                                                        <div class="col-12 mb-4 idcard" style="background: url(<?=site_url('assets/id.png'); ?>)  no-repeat center center;background-size: 100% 100%; margin-right: 5%;color: #fff;   " >
                                                        
                                                            
                                                            <div class="hexagon-container">
                                                                <div class="hexagon-image">
                                                                    <?=($passport);?>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="name"> <b><?=$fullname; ?></b></div>
                                                            <div class="tax"> <?=$tax_id; ?></b></div>
                                                            <div class="qr-container">
                                                                <div class="qr-image">
                                                                    <?=($qrcode);?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row text-center" style="font-size:18px;margin:2% 15%;">
                                                        <div class="col-sm-12 mb-2"> 
                                                            <button class="btn btn-primary btn-block" onclick="saveDivAsPDF()"><?=translate_phrase('Download ID Card'); ?></button>
                                                            <span id="ref_resp"  class="text-danger"></span>
                                                        </div>
                                                        <div class="col-sm-12" id="card_response"></div>
                                                    </div>
                                                            
                                                </div><!-- data-list -->
                                            </div>
                                            <div class="nk-block-head">
                                                <div class="nk-block-head-content">
                                                    <h4 class="nk-block-title">QR<?=translate_phrase(' Code');?></h4>
                                                    <div class="nk-block-des">
                                                    </div>
                                                </div>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-block">
                                                <div class="nk-data data-list data-list-s2">
                                                    <div class="data-head">
                                                        <h6 class="overline-title"></h6>
                                                    </div>
                                                    <div class="row text-center bg-white" id="qrcodes">
                                                        
                                                        <div class="col-12 mb-4 idcards" style="background: url(<?=site_url('assets/qr.jpeg'); ?>)  no-repeat center center;background-size: 100% 100%; margin-right: 5%;color: #fff;   " >
                                                        
                                                            <div class="qr-containers">
                                                                <div class="qr-images">
                                                                    <?=($qrcode);?>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="taxs text-dark"> <?=$tax_id; ?></b></div>
                                                        </div>
                                                    </div>
                                                    <div class="row text-center" style="font-size:18px;">
                                                        <div class="col-12 mb-2"> 
                                                            <button class="btn btn-primary btn-block"  onclick="qrcodes()" ><?=translate_phrase('Download Qr Code'); ?></button>
                                                            <span id="ref_resp" class="text-danger"></span>
                                                            </div>
                                                            
                                                            <div class="col-sm-12" id="qr_response"></div>
                                                        
                                                    </div>
                                                            
                                                </div><!-- data-list -->
                                            </div><!-- .nk-block -->
                                        </div>
                                        <div class="tab-pane" id="notification">
                                            <div class="nk-block-head">
                                                <h5 class="title"><?=$fullname;?>'s <?=translate_phrase('Notification');?></h5>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-tb-list border border-light rounded overflow-hidden is-compact" id="notification_data">
                                            </div>
                                            <div id="notification_more"></div>
                                        </div>
                                        <div class="tab-pane" id="activity">
                                            <div class="nk-block-head">
                                                <h5 class="title"><?=$fullname;?>'s <?=translate_phrase('Activity Log');?></h5>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-tb-list border border-light rounded overflow-hidden is-compact" id="activity_data">
                                            </div>
                                            <div id="activity_more"></div>
                                        </div>
                                    </div>
                                </div><!-- .card-inner -->
                            </div><!-- .card-content -->
                            <div class="card-aside card-aside-right user-aside toggle-slide toggle-slide-right toggle-break-xxl" data-content="userAside" data-toggle-screen="xxl" data-toggle-overlay="true" data-toggle-body="true">
                                <div class="card-inner-group" data-simplebar>
                                    <div class="card-inner">
                                        <div class="user-card user-card-s2">
                                            <div class="user-avatar lg bg-primary">
                                                <?=$v_img; ?>
                                            </div>
                                            <div class="user-info">
                                                <div class="badge bg-outline-light rounded-pill ucap"><?=translate_phrase($role); ?></div>
                                                <h5><?=ucwords($fullname); ?></h5>
                                                <span class="sub-text"><?=$v_email; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .card-inner -->
                            </div><!-- .card-aside -->
                        </div><!-- .card-aside-wrap -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- content @e -->
<script>var site_url = '<?php echo site_url(); ?>';</script>
<script src="<?php echo site_url(); ?>/assets/js/jquery.min.js"></script>
<script>
    $(function() {
        notification('', '');
        wallet('', '');
        activity('', '');
    });

    function activity(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#activity_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#activity_more').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

        var u_id = $('#u_id').val();
        $.ajax({
            url: site_url + 'accounts/customer_details/activity/load' + methods,
            type: 'post',
            data: {u_id: u_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#activity_data').html(dt.item);
                } else {
                    $('#activity_data').append(dt.item);
                }
                if (dt.offset > 0) {
                    $('#activity_more').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="activity(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#activity_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    function notification(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#notification_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#notification_more').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

        var u_id = $('#u_id').val();
        $.ajax({
            url: site_url + 'accounts/customer_details/notification/load' + methods,
            type: 'post',
            data: {u_id: u_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#notification_data').html(dt.item);
                } else {
                    $('#notification_data').append(dt.item);
                }
                if (dt.offset > 0) {
                    $('#notification_more').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="notification(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#notification_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    function order(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#transaction_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#transaction_more').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

        var u_id = $('#u_id').val();
        $.ajax({
            url: site_url + 'accounts/customer_details/order/load' + methods,
            type: 'post',
            data: {u_id: u_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#transaction_data').html(dt.item);
                } else {
                    $('#transaction_data').append(dt.item);
                }
                if (dt.offset > 0) {
                    $('#transaction_more').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="order(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#transaction_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    function wallet(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#wallet_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#wallet_more').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

        var u_id = $('#u_id').val();
        $.ajax({
            url: site_url + 'accounts/customer_details/wallet/load' + methods,
            type: 'post',
            data: {u_id: u_id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#wallet_data').html(dt.item);
                } else {
                    $('#wallet_data').append(dt.item);
                }
                if (dt.offset > 0) {
                    $('#wallet_more').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="order(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#wallet_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }
</script>   

<?=$this->endSection();?>