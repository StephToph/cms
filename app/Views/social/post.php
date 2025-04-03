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
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Social Post</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total <span id="counta"></span> social post.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-stretch" id="table_view">
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
                                            </li><!-- li -->
                                            <?php if (empty($switch_id)) { ?>
                                                <li class="btn-toolbar-sep"></li><!-- li -->
                                                <li>
                                                    <a href="javascript:;" pageName="<?= site_url('social/post/manage'); ?>"
                                                        pageTitle="Add" pageSize="modal-lg"
                                                        class="btn btn-icon btn-outline-primary pop"><em
                                                            class="icon ni ni-plus-c"></em></a>
                                                </li>
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
                                                placeholder="Search by title or description" oninput="load('', '')"
                                                id="search">
                                        </div>
                                    </div>
                                </div><!-- .card-search -->
                            </div><!-- .card-inner -->
                            <div class="card-inner table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Church</th>
                                            <th scope="col">Platforms</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Scheduled</th>
                                            <th scope="col">Posted</th>
                                            <th scope="col" width="150px">Actions</th>
                                        </tr>

                                    </thead>
                                    <tbody id="load_data"> </tbody>
                                    <tfoot id="loadmore"></tfoot>
                                </table>

                            </div><!-- .card-inner -->

                        </div><!-- .card-inner-group -->
                    </div>
                    <div class="card card-bordered card-stretch" style="display:none;" id="pre_view">
                        <div class="card-inner-group">
                            <div class="card-inner position-relative card-tools-toggle">
                                <div class="card-title-group">
                                    <div class="card-tools">

                                    </div><!-- .card-tools -->
                                    <div class="card-tools me-n1">
                                        <ul class="btn-toolbar gx-1">
                                            <li>
                                                <a href="javascript:;" class="btn btn-danger btn-icon"
                                                    onclick="$('#table_view').toggle(400);$('#pre_view').toggle(500);"><em
                                                        class="icon ni ni-arrow-left"></em></a>
                                            </li><!-- li -->

                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->

                            </div><!-- .card-inner -->
                            <div class="card-inner table-responsive">
                                 <!-- Nav Tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item"> 
                                        <a class="nav-link active" data-bs-toggle="tab" href="#platformPreview" role="tab">
                                            <em class="icon ni ni-monitor"></em><span>My Platform</span>
                                        </a> 
                                    </li>
                                    <li class="nav-item"> 
                                        <a class="nav-link" data-bs-toggle="tab" href="#facebookPreview" role="tab">
                                            <em class="icon ni ni-facebook-f"></em><span>Facebook</span>
                                        </a> 
                                    </li>
                                    <li class="nav-item"> 
                                        <a class="nav-link" data-bs-toggle="tab" href="#instagramPreview" role="tab">
                                            <em class="icon ni ni-instagram"></em><span>Instagram</span>
                                        </a> 
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content mt-3">

                                    <!-- My Platform -->
                                    <div class="tab-pane fade show active" id="platformPreview" role="tabpanel">
                                        <div class="card p-3 shadow-sm">
                                            <strong><?= ucwords($e_author ?? 'Author') ?> · <?= date('M d, Y', strtotime($e_scheduled_at ?? now())) ?></strong>
                                            <p><?= nl2br($e_content ?? '') ?></p>
                                            <?php if (!empty($e_media_urls)) {
                                                foreach (json_decode($e_media_urls, true) as $media) {
                                                    echo '<div class="mb-2">';
                                                    if (preg_match('/\.(mp4|avi|mov|wmv)$/i', $media)) {
                                                        echo '<video src="' . base_url($media) . '" width="100%" controls></video>';
                                                    } else {
                                                        echo '<img src="' . base_url($media) . '" style="width:100%; max-height:400px; object-fit:cover;" />';
                                                    }
                                                    echo '</div>';
                                                }
                                            } ?>
                                        </div>
                                    </div>

                                    <!-- Facebook Preview -->
                                    <div class="tab-pane fade" id="facebookPreview" role="tabpanel">
                                        <div class="card p-3 shadow-sm text-left">
                                            <strong>@<?= strtolower(str_replace(' ', '', $e_author ?? 'author')) ?> · Facebook</strong>
                                            <p><?= nl2br($e_content ?? '') ?></p>
                                            <?php
                                                if (!empty($e_media_urls)) {
                                                    foreach (json_decode($e_media_urls, true) as $media) {
                                                        echo '<div class="mb-2">';
                                                        if (preg_match('/\.(mp4|avi|mov|wmv)$/i', $media)) {
                                                            echo '<video src="' . base_url($media) . '" width="100%" controls></video>';
                                                        } else {
                                                            echo '<img src="' . base_url($media) . '" style="width:100%; max-height:400px; object-fit:cover;" />';
                                                        }
                                                        echo '</div>';
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Instagram Preview -->
                                    <div class="tab-pane fade" id="instagramPreview" role="tabpanel">
                                        <div class="card p-3 shadow-sm text-center">
                                            <?php
                                                if (!empty($e_media_urls)) {
                                                    $media = json_decode($e_media_urls, true);
                                                    if (!empty($media[0])) {
                                                        echo '<img src="' . base_url($media[0]) . '" class="mb-3" style="width:100%; max-height:400px; object-fit:cover;" />';
                                                    }
                                                }
                                            ?>
                                            <p><strong><?= $e_author ?? 'Author' ?></strong><br><?= nl2br($e_content ?? '') ?></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- .card-inner -->

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

    function preview(id) {
        $('#table_view').hide(500);
        $('#pre_view').show(500);
        load_preview(id);
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

        $.ajax({
            url: site_url + 'social/post/load' + methods,
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
                    $('#loadmore').html('<tr><td colspan="8"><a href="javascript:;" class="btn btn-light btn-block p-30" onclick="load(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a></td></tr>');
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