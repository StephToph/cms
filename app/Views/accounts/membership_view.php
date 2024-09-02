<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
<div class="nk-content" >
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body mt-3">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?=translate_phrase('Membership');?> / <strong class="text-primary small"><?=ucwords($fullname); ?></strong></h3>
                            <div class="nk-block-des text-soft">
                                <ul class="list-inline">
                                    <li><?=translate_phrase('Last Login');?>: <span class="text-base"><?=$last_log; ?></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="<?=site_url('accounts/membership'); ?>" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span><?=translate_phrase('Back');?></span></a>
                            <a href="<?=site_url('accounts/membership'); ?>" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
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
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#notification"><em class="icon ni ni-bell"></em><span><?=translate_phrase('Partnership');?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"  data-bs-toggle="tab" href="#activity"><em class="icon ni ni-activity"></em><span><?=translate_phrase('Activities');?></span></a>
                                    </li> -->
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
                                                            <span class="profile-ud-label"><?=translate_phrase('Title');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_title); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Membership ID');?></span>
                                                            <span class="profile-ud-value"><?=($v_user_no); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Full Name');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($fullname); ?></span>
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
                                                            <span class="profile-ud-label"><?=translate_phrase('Kingchat Handle');?></span>
                                                            <span class="profile-ud-value"><?=$v_chat_handle; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Gender');?></span>
                                                            <span class="profile-ud-value"><?=$v_gender; ?> </span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Birth Date');?></span>
                                                            <span class="profile-ud-value"><?=$v_dob; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Marital Status');?></span>
                                                            <span class="profile-ud-value"><?=$v_family_status; ?></span>
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
                                                            <span class="profile-ud-label"><?=translate_phrase('Marriage Anniversary');?></span>
                                                            <span class="profile-ud-value"><?=$v_marriage_anniversary; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Family Position');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_family_position); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Department');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($this->Crud->read_field('id', $v_dept_id, 'dept', 'name')); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Department Role');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_dept_role); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Cell');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($this->Crud->read_field('id', $v_cell_id, 'cells', 'name')); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Cell Role');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_cell_role); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Job Type');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_job_type); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Employer Name');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_employer_address); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Foundation School');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_foundation_school); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-ud-item">
                                                        <div class="profile-ud wider">
                                                            <span class="profile-ud-label"><?=translate_phrase('Baptism');?></span>
                                                            <span class="profile-ud-value"><?=ucwords($v_baptism); ?></span>
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
                                            
                                        </div>
                                        <div class="tab-pane" id="order">
                                            <div class="nk-block-head">
                                                <h5 class="title"><?=$fullname;?>'s <?=translate_phrase('Transaction History');?></h5>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-tb-list border border-light rounded overflow-hidden is-compact" id="transaction_data">
                                            </div>
                                            <div id="transaction_more"></div><!-- .nk-block -->
                                        </div>
                                        <div class="tab-pane" id="wallet">
                                            <div class="nk-block">
                                                <div class="nk-block-head">
                                                    <h5 class="title"><?=translate_phrase('Wallet History');?></h5>
                                                </div><!-- .nk-block-head -->
                                                <div class="nk-block mb-3">
                                                    <div class="row g-3">
                                                        <div class="col-sm-4">
                                                            <div class="card bg-success">
                                                                <div class="nk-wgw sm">
                                                                    <a class="nk-wgw-inner mb-3 pb-1" href="javascript:;">
                                                                        <div class="nk-wgw-name">
                                                                            <div class="nk-wgw-icon">
                                                                                <em class="icon ni ni-sign-kobo"></em>
                                                                            </div>
                                                                            <h5 class="nk-wgw-title title text-white"><?=translate_phrase('Available Balance');?></h5>
                                                                        </div>
                                                                        <div class="nk-wgw-balance ">
                                                                            
                                                                            <div class="amount text-white">&#8358;<?='-';?></div>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div><!-- .col -->
                                                        <div class="col-sm-4">
                                                            <div class="card bg-danger text-light">
                                                                <div class="nk-wgw sm">
                                                                    <a class="nk-wgw-inner mb-3 pb-1" href="javascript:;">
                                                                        <div class="nk-wgw-name">
                                                                            <div class="nk-wgw-icon">
                                                                                <em class="icon ni ni-sign-kobo"></em>
                                                                            </div>
                                                                            <h5 class="nk-wgw-title title text-white"><?=translate_phrase('Total Withdrawal');?></h5>
                                                                        </div>
                                                                        <div class="nk-wgw-balance ">
                                                                            <?php
                                                                                
                                                                            ?>
                                                                            <div class="amount text-white">&#8358;<?='-';?></div>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div><!-- .col -->
                                                        <div class="col-sm-4">
                                                            <div class="card bg-info text-light">
                                                                <div class="nk-wgw sm">
                                                                    <a class="nk-wgw-inner mb-3 pb-1" href="javascript:;">
                                                                        <div class="nk-wgw-name">
                                                                            <div class="nk-wgw-icon">
                                                                                <em class="icon ni ni-sign-kobo"></em>
                                                                            </div>
                                                                            <h5 class="nk-wgw-title title text-white"><?=translate_phrase('Total Credit');?></h5>
                                                                        </div>
                                                                        <div class="nk-wgw-balance ">
                                                                            <?php
                                                                                
                                                                            ?>
                                                                            <div class="amount text-white">&#8358;<?='-';?></div>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div><!-- .col -->
                                                    </div><!-- .row -->
                                                </div> 
                                                <div class="nk-tb-list border border-light rounded overflow-hidden is-compact" id="wallet_data">
                                                </div>
                                                <div id="wallet_more"></div>    
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
                                </div>
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