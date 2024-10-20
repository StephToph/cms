<?php
use App\Models\Crud;
$this->Crud = new Crud();

$this->session = \Config\Services::session();
    
$switch_id = $this->session->get('switch_church_id');
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
                            <h3 class="nk-block-title page-title"><?= translate_phrase('Foundation Setup'); ?></h3>
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
                                            <?php if(empty($switch_id)){?>
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li>
                                                <a href="javascript:;" pageTitle="Add Setup"
                                                    class="btn btn-outline-primary btn-icon pop"
                                                    pageName="<?= site_url('foundation/setup/manage'); ?>"><em
                                                        class="icon ni ni-plus-c"></em></a>
                                            </li><!-- li -->
                                                <?php } ?>
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
                            <div class="">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Quarter</th>
                                                <th>Date</th>
                                                <th>Joint Class Status</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="load_data"></tbody>
                                        <tfoot id="loadmore"></tfoot>
                                    </table>
                                </div>
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
            <div class="nk-content-body" id="admin_resp" style="display:none;">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title" id="church_title">
                                <?= translate_phrase('Instructors'); ?></h3>
                            
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
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Back to Setup" class="btn btn-outline-danger btn-icon"
                                                    onclick="church_back();"><em
                                                        class="icon ni ni-curve-down-left"></em></a>
                                            </li><!-- li -->
                                            <?php if(empty($switch_id)){
                                                
                                                ?>
                                                <li class="btn-toolbar-sep"></li><!-- li -->
                                                <li id="instructor_resp">
                                                    <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Instructor" pageTitle="Instructors"
                                                        class="btn btn-outline-primary btn-icon pop"
                                                        pageName="<?= site_url('foundation/instructor/manage'); ?>" pageSize="modal-lg"><em
                                                            class="icon ni ni-plus-c"></em></a>
                                                </li><!-- li -->
                                            <?php } ?>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                <div class="card-search search-wrap" data-search="search">
                                    <div class="card-body">
                                        <div class="search-content">
                                            <a href="#" class="search-back btn btn-icon toggle-search"
                                                data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                            <input type="text" class="form-control border-transparent form-focus-none"
                                                placeholder="Search by name" id="admin_search">
                                        </div>
                                    </div>
                                </div><!-- .card-search -->
                            </div><!-- .card-inner -->
                            <div class="card-inner ">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Week</th>
                                                <th>Course</th>
                                                <th>Instructor</th>
                                                <th></th>
                                            </th>
                                        </thead>
                                        <tbody id="load_admin"></tbody>
                                        <tfoot id="admin_more"></tfoot>
                                    </table>
                                </div>
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
            
            <div class="nk-content-body" id="enroll_resp" style="display:none;">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title" id="church_title">
                                <?= translate_phrase('Enrolled Students'); ?></h3>
                            
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
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Back to Setup" class="btn btn-outline-danger btn-icon"
                                                    onclick="church_back();"><em
                                                        class="icon ni ni-curve-down-left"></em></a>
                                            </li><!-- li -->
                                            <?php if(empty($switch_id)){
                                                
                                                ?>
                                                <li class="btn-toolbar-sep"></li><!-- li -->
                                                <li id="student_resp">
                                                    <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Enroll Student" pageTitle="Enroll Student"
                                                        class="btn btn-outline-primary btn-icon pop"
                                                        pageName="<?= site_url('foundation/students/manage'); ?>" pageSize="modal-lg"><em
                                                            class="icon ni ni-plus-c"></em></a>
                                                </li><!-- li -->
                                            <?php } ?>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                                <div class="card-search search-wrap" data-search="search">
                                    <div class="card-body">
                                        <div class="search-content">
                                            <a href="#" class="search-back btn btn-icon toggle-search"
                                                data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                            <input type="text" class="form-control border-transparent form-focus-none"
                                                placeholder="Search by name" id="admin_search">
                                        </div>
                                    </div>
                                </div><!-- .card-search -->
                            </div><!-- .card-inner -->
                            <div class="card-inner ">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Church</th>
                                                <th>Foundation</th>
                                                <th>Source</th>
                                                <th></th>
                                            </th>
                                        </thead>
                                        <tbody id="load_enroll"></tbody>
                                        <tfoot id="enroll_more"></tfoot>
                                    </table>
                                </div>
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
            <div class="nk-content-body" id="attendance_resp" style="display:none;">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title" id="church_title">
                                <?= translate_phrase('Attendance'); ?></h3>
                            
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
                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Back to Setup" class="btn btn-outline-danger btn-icon"
                                                    onclick="church_back();"><em
                                                        class="icon ni ni-curve-down-left"></em></a>
                                            </li>
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div>
                            </div><!-- .card-inner -->
                            <div class="card-inner ">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Week</th>
                                                <th>No of Class</th>
                                                <th>Total Student</th>
                                                <th>Present</th>
                                                <th>Absent</th>
                                                <th>Date Held</th>
                                                <th></th>
                                            </th>
                                        </thead>
                                        <tbody id="load_attendance"></tbody>
                                        <tfoot id="attendance_more"></tfoot>
                                    </table>
                                </div>
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
<script>
    $(function () {
        load('', '');
    });

    

    function church_back() {
        $('#church_resp').show(500);
        $('#admin_resp').hide(500);
        
        $('#enroll_resp').hide(500);
        $('#attendance_resp').hide(500);
        
        load('', '');

    } 

    function enroll(id) {
        $('#church_resp').hide(500);
        $('#admin_resp').hide(500);
        $('#attendance_resp').hide(500);
        $('#enroll_resp').show(500);
        
        $('#enroll_search').on('input', function () {
            load_enroll('', '', id);
        });
        load_enroll('', '', id);

    }

    function attendance(id) {
        $('#church_resp').hide(500);
        $('#admin_resp').hide(500);
        $('#enroll_resp').hide(500);
        $('#attendance_resp').show(500);
        
        load_attendance('', '', id);

    }

    function church_admin(id) {
        $('#church_resp').hide(500);
        $('#admin_resp').show(500);
        
        $('#admin_search').on('input', function () {
            load_admin('', '', id);
        });
        load_admin('', '', id);

    }

    function load_attendance(x, y, id) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#load_attendance').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#attendance_more').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }


        var search = $('#enroll_search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'foundation/attendance/load' + methods,
            type: 'post',
            data: { search: search, id: id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_attendance').html(dt.item);
                } else {
                    $('#load_attendance').append(dt.item);
                }
                $('#admin_counta').html(dt.count);
                if (dt.offset > 0) {
                    $('#attendance_more').html('<tr><td colspan="8"><a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load_attendance(' + dt.limit + ', ' + dt.offset + ', '+id+');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a></td></tr>');
                } else {
                    $('#attendance_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }




    function load_enroll(x, y, id) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#load_enroll').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#enroll_more').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }


        var search = $('#enroll_search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'foundation/students/load' + methods,
            type: 'post',
            data: { search: search, id: id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_enroll').html(dt.item);
                } else {
                    $('#load_enroll').append(dt.item);
                }
                $('#admin_counta').html(dt.count);
                if (dt.offset > 0) {
                    $('#enroll_more').html('<tr><td colspan="8"><a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load_enroll(' + dt.limit + ', ' + dt.offset + ', '+id+');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a></td></tr>');
                } else {
                    $('#enroll_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
            }
        });
    }

    

    function load_admin(x, y, id) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#load_admin').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#admin_more').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }


        var search = $('#admin_search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'foundation/instructor/load' + methods,
            type: 'post',
            data: { search: search, id: id },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#load_admin').html(dt.item);
                } else {
                    $('#load_admin').append(dt.item);
                }
                $('#admin_counta').html(dt.count);
                if (dt.offset > 0) {
                    $('#admin_more').html('<tr><td colspan="8"><a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load_admin(' + dt.limit + ', ' + dt.offset + ', '+id+');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a></td></tr>');
                } else {
                    $('#admin_more').html('');
                }
            },
            complete: function () {
                $.getScript(site_url + '/assets/js/jsmodal.js');
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
            $('#load_data').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#loadmore').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }


        var search = $('#search').val();
        //alert(status);

        $.ajax({
            url: site_url + 'foundation/setup/load' + methods,
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
                    $('#loadmore').html('<tr><td colspan="8"><a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a></td></tr>');
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