<?php 

$logo = 'assets/new_logo1.png';
$background_image = 'assets/images/prayercloud.webp';
?>
<!DOCTYPE html>
<html lang="zxx" class="js">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Angel Church Management System">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description"
            content="Church Performnce Tracking">
        <meta name="theme-color" content="blue">
        <link rel="shortcut icon" href="<?=site_url(); ?>assets/new_logo1.png" />
        <title><?=$title; ?></title>
        <link rel="stylesheet" href="<?=site_url(); ?>assets/prayer/assets/css/dashlitee5ca.css?ver=<?=time(); ?>" />
        <link id="skin-default" rel="stylesheet" href="<?=site_url(); ?>assets/prayer/assets/css/themee5ca.css?ver=3.2.3" />
       
    </head>

    <style>
      .fc-list {
            width: 100%; /* Full width of the parent container */
            overflow-x: auto; /* Enable horizontal scrolling */
        }

        .fc-scroller {
            display: inline-block; /* Ensure the content size adapts to its contents */
            white-space: nowrap; /* Prevent wrapping of table rows */
        }

    </style>
    

    <body class="nk-body bg-white npc-landing">
        <div class="nk-app-root">
            <div class="nk-main">
                <header class="header header-32 has-header-main-s1 bg-dark" id="home" style="position: relative;">
                    <!-- Background Image with Overlay -->
                    <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('<?= site_url($background_image); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; opacity: 0.3;"></div>

                    <div class="header-main header-main-s1 is-sticky is-transparent on-dark">
                        <div class="container header-container">
                            <div class="header-wrap">
                                <div class="header-logo">
                                    <a href="<?= site_url(); ?>" class="logo-link">
                                        <img class="logo-light logo-img logo-img-lg" style="max-height:50px;" src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?>" alt="logo">
                                        <img class="logo-dark logo-img logo-img-lg" style="max-height:50px;" src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?>" alt="logo-dark">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="header-content py-6 is-dark mt-lg-n1 mt-n3">
                        <div class="container">
                            <div class="row flex-row-reverse justify-content-center text-center g-gs">
                                <div class="col-lg-6 col-md-7">
                                    <div class="header-caption">
                                        <h2 class="header-title" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">Prayer Cloud</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                
                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                
                                <div class="nk-block" id="calendar_resp">
                                    <div class="d-flex flex-row-reverse ">
                                        
                                        <!-- <?php if(empty($switch_id)){?>
                                            <div class="my-2">
                                                <a href="javascript:;" id="add_btn" pageName="<?=site_url('church/activity/manage'); ?>" pageTitle="Add" pageSize="modal-xl" class="btn btn-primary pop"><em class="icon ni ni-plus-c"></em> <span>Add Activity</span></a>
                                            </div>
                                        <?php } ?>
                                        <div class="my-2">
                                            <a href="javascript:;" pageName="<?=site_url('church/activity/manage/generate'); ?>" pageTitle="Generate" pageSize="modal-lg" class="btn btn-info pop mx-1"><em class="icon ni ni-list-index"></em> <span>Generate</span></a>
                                        </div> -->
                                    </div>
                                    <div class="card bg-lighter">
                                        <div class="card-inner">
                                            <div id="calendar" data-initial-view="listWeek" class="nk-calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer bg-dark is-dark section section-sm mt-5" style="padding:0.75rem 0" id="footer">
                    <div class="container">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <div class="footer-logo">
                                    <a href="<?= site_url(); ?>" class="logo-link">
                                        <img class="logo-light logo-img" src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?> 2x" alt="logo" />
                                        <img class="logo-dark logo-img" src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?> 2x" alt="logo-dark" />
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-9 d-flex justify-content-md-end">
                            <div class="text-base">&copy; <?=date('Y'); ?> - <?=app_name; ?>. </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        
    <div class="modal fade" id="previewEventPopu">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div id="preview-event-header" class="modal-header">
                    <h5 id="preview-event-title" class="modal-title">Placeholder Title</h5><a href="#" class="close"
                        data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
                </div>
                <div class="modal-body">
                    <div class="row gy-3 py-1">
                        <div class="col-sm-6">
                            <h6 class="overline-title">Start Time</h6>
                            <p id="preview-event-start"></p>
                        </div>
                        <div class="col-sm-6" id="preview-event-end-check">
                            <h6 class="overline-title">End Time</h6>
                            <p id="preview-event-end"></p>
                        </div>
                        <div class="col-sm-10" id="preview-event-description-check">
                            <h6 class="overline-title">Description</h6>
                            <p id="preview-event-description"></p>
                        </div>
                    </div>
                    <!-- <ul class="d-flex justify-content-between gx-4 mt-3">
                        <li><button  pageTitle="Edit " pageSize="modal-lg" pageName="<?=site_url('ministry/calendar/manage/edit/'); ?>" class="btn btn-primary pop">Edit Event</button></li>
                        <li><button data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#deleteEventPopup"
                                class="btn btn-danger pop btn-dim">Delete</button></li>
                    </ul> -->
                </div>
            </div>
            
        </div>
    </div>

        <div class="modal modal-center fade" tabindex="-1" id="modal" role="dialog" data-keyboard="false"
            data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <a href="javascript:;" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                    <div class="modal-header">
                        <h6 class="modal-title"></h6>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
    
        <!-- Core Libraries -->
        <script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>/assets/js/jsmodal.js"></script>

        <!-- FullCalendar and Other Dependencies -->
        <script src="<?php echo base_url(); ?>/assets/js/libs/fullcalendar.js"></script>

        <!-- Custom Scripts -->
        <script src="<?=site_url(); ?>assets/prayer/assets/js/bundlee5ca.js?ver=3.2.3"></script>
        <script src="<?=site_url(); ?>assets/prayer/assets/js/scriptse5ca.js?ver=3.2.3"></script>

        <!-- Initialization and Calendar Event Handling -->
        <script>
            var site_url = '<?php echo site_url(); ?>';   
            var calEventsStr = '<?php if (!empty($cal_events)) { echo json_encode($cal_events); } else { echo "[]"; } ?>';
            
            // Parse the JSON string into a JavaScript object/array
            var calEvents = JSON.parse(calEventsStr);
        </script>

        <!-- Custom Calendar Logic -->
        <script src="<?php echo base_url(); ?>/assets/js/apps/prayer_calendar.js?v=<?=time();?>"></script>

    </body>
</html>
