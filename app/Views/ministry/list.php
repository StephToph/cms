<?php
use App\Models\Crud;
$this->Crud = new Crud();
?>

<?= $this->extend('designs/backend'); ?>
<?= $this->section('title'); ?>
<?= $title; ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="nk-content">
    <div class="container-fluid mt-3">
        <div class="nk-content-inner">
            <div class="nk-content-body" id="church_resp">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title"><?= translate_phrase('Ministry'); ?></h3>
                            <div class="nk-block-des text-soft">
                                <p><?= translate_phrase('You have total'); ?> <span id="counta"></span>
                                    <?= translate_phrase('ministry.'); ?></p>
                            </div>
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
                                                <a href="javascript:;" class="btn btn-icon search-toggle toggle-search"
                                                    data-target="search"><em class="icon ni ni-search"></em></a>
                                            </li>
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li>
                                                <a href="javascript:;" pageTitle="Add Ministry"
                                                    class="btn btn-outline-primary btn-icon pop"
                                                    pageName="<?= site_url('ministry/index/manage'); ?>"><em
                                                        class="icon ni ni-plus-c"></em></a>
                                            </li><!-- li -->

                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                <div class="card-search search-wrap" data-search="search">
                                    <div class="card-body">
                                        <div class="search-content">
                                            <a href="#" class="search-back btn btn-icon toggle-search"
                                                data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                            <input type="text" class="form-control border-transparent form-focus-none"
                                                placeholder="Search by name" oninput="load('', '')" id="search">
                                        </div>
                                    </div>
                                </div><!-- .card-search -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist" id="load_data">
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner">
                                <div class="nk-block-between-md g-3" id="loadmore">
                                </div><!-- .nk-block-between -->
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
            <div class="nk-content-body" id="admin_resp" style="display:none;">

                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between g-3">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Ministry / <strong
                                            class="text-primary small" id="ministry">-</strong> / <strong
                                            class="text-primary small" id="name">-</strong></h3>
                                            <div class="nk-block-des text-soft">
                                                <ul class="list-inline">
                                                    <li>User ID: <span class="text-base" id="user_id">-</span></li>
                                                    <li>Last Login: <span class="text-base" id="last_log">-</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="nk-block-head-content">
                                            <a href="javascript:;" onclick="church_back();"
                                                class="btn btn-outline-danger d-none d-sm-inline-flex"><em
                                                    class="icon ni ni-arrow-left"></em><span>Back</span>
                                            </a>
                                            <a href="javascript:;" onclick="church_back();" class="btn btn-icon btn-outline-danger d-inline-flex d-sm-none"><em
                                                class="icon ni ni-arrow-left"></em>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-block">
                                    <div class="card card-bordered">
                                        <div class="card-aside-wrap">
                                            <div class="card-content">
                                                <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
                                                    <li class="nav-item"><a class="nav-link active" href="javascript:;"><em
                                                                class="icon ni ni-user-circle"></em><span>Administrator</span></a>
                                                    </li>
                                                </ul>
                                                <div class="card-inner">
                                                    <div class="nk-block">
                                                        <div class="nk-block-head">
                                                            <h5 class="title">Personal Information</h5>
                                                            <p id="send_text"></p>
                                                        </div>
                                                        <div class="profile-ud-list">
                                                            
                                                            <div class="profile-ud-item">
                                                                <div class="profile-ud wider"><span
                                                                        class="profile-ud-label">Title</span><span
                                                                        class="profile-ud-value" id="title"></span>
                                                                </div>
                                                            </div>
                                                            <div class="profile-ud-item">
                                                                <div class="profile-ud wider"><span
                                                                        class="profile-ud-label">First Name</span><span
                                                                        class="profile-ud-value" id="firstname"></span>
                                                                </div>
                                                            </div>
                                                            <div class="profile-ud-item">
                                                                <div class="profile-ud wider"><span
                                                                        class="profile-ud-label">Surname</span><span
                                                                        class="profile-ud-value" id="surname"></span>
                                                                </div>
                                                            </div>
                                                            <div class="profile-ud-item">
                                                                <div class="profile-ud wider"><span
                                                                        class="profile-ud-label">Email
                                                                        Address</span><span
                                                                        class="profile-ud-value" id="email"></span>
                                                                </div>
                                                            </div>
                                                            <div class="profile-ud-item">
                                                                <div class="profile-ud wider"><span
                                                                        class="profile-ud-label">Mobile
                                                                        Number</span><span
                                                                        class="profile-ud-value" id="phone" ></span>
                                                                </div>
                                                            </div>
                                                            <div class="profile-ud-item">
                                                                <div class="profile-ud wider"><span
                                                                        class="profile-ud-label">User Role</span><span
                                                                        class="profile-ud-value" id="user_role" ></span>
                                                                </div>
                                                            </div>
                                                            <div class="profile-ud-item">
                                                                <div class="profile-ud wider">
                                                                    
                                                                    <span class="profile-ud-label"  >
                                                                        <a href="javascript:;" id="admin_btn" pageTitle="Add Admin"  class="btn btn-block btn-outline-primary pop" pageName="<?= site_url('ministry/index/manage/admin'); ?>"><em class="icon ni ni-edit-alt"></em> <span id="btn_text">Add Admin</span></a>
                                                                    </span>

                                                                    <span class="profile-ud-value" id="sends_text" style="display:none;">
                                                                        <a href="javascript:;" pageTitle="Send Login" id="send_btn"  class="btn  btn-outline-success pop" pageName="<?=site_url('ministry/index/manage/admin_send'); ?>"><em class="icon ni ni-share"></em> <span>Send Login</span></a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>var site_url = '<?php echo site_url(); ?>';</script>
<script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
<script>
    $(function () {
        load('', '');
    });



    function church_back() {
        $('#church_resp').show(500);
        $('#admin_resp').hide(500);
        load('', '');

    }
    function church_admin(id) {
        $('#church_resp').hide(500);
        $('#admin_resp').show(500);

        load_admin(id);

    }

    function load_admin(id){
        var ministry_id = id;
        $.ajax({
            url: site_url + 'ministry/index/load_admin',
            type: 'post',
            data: { ministry_id: ministry_id },
            success: function (data) {
                var dt = JSON.parse(data);
                $('#name').html(dt.fullname);
                $('#names').html(dt.fullname);
                $('#surname').html(dt.surname);
                $('#firstname').html(dt.firstname);
                $('#user_id').html(dt.user_id);
                $('#last_log').html(dt.last_log);
                $('#email').html(dt.email);
                $('#status').html(dt.status);
                $('#title').html(dt.title);
                $('#phone').html(dt.phone);
                $('#ministry').html(dt.ministry);
                $('#user_role').html(dt.user_role);
                $('#btn_text').html(dt.btn_text);
                $('#send_text').html(dt.send_text);
                var urls = site_url + 'ministry/index/manage/admin/'+ministry_id;
                $('#admin_btn').attr('pageName', urls);
                
                
                if (dt.admin_id <= 0) {
                    $('#sends_text').fadeOut(500); // More standard method for fading out
                } else {
                    $('#sends_text').fadeIn(500); // More standard method for fading in
                    var urls = site_url + 'ministry/index/manage/admin_send/' + dt.admin_id;
                    $('#send_btn').attr('pageName', urls); // Use data attribute for custom data
                }
            }
        });
    }


    function load(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#load_data').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        } else {
            $('#loadmore').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }


        var search = $('#search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'ministry/index/load' + methods,
            type: 'post',
            data: { search: search },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_data').html(dt.item);
                } else {
                    $('#load_data').append(dt.item);
                }
                $('#counta').html(dt.count);
                if (dt.offset > 0) {
                    $('#loadmore').html('<a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a>');
                } else {
                    $('#loadmore').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }
</script>

<?= $this->endSection(); ?>