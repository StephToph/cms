<?php
    use App\Models\Crud;

    $this->Crud = new Crud();
?>
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
        /* Ensure the parent div has a defined height */
        .card-inner {
            position: relative; /* Make sure the parent div is positioned relative */
            height: 100%; /* Full height of the container */
            max-width: 100%; /* Optional: Make sure it doesn’t exceed its width */
        }

        /* Make the Jitsi iframe fill its parent container */
        #jitsi-meeting {
            width: 100%; /* Fill the full width of the parent */
            height: 100%; /* Fill the full height of the parent */
            min-height: 400px; /* Optional: Ensure it has a minimum height */
        }

        /* Ensure responsiveness */
        @media (max-width: 768px) {
            #jitsi-meeting {
                height: 60vh; /* Adjust height for smaller screens */
            }
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
                                        <h1 id="header-title"  style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);" class="display-1">Join Prayer: <?=strtoupper($room_name); ?></h1>
                                        <p class="lead">Please provide your details to join the prayer session.</p>
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
                                    
                                    <div class="card bg-lighter">
                                        <div class="card-inner">
                                            <div id="jitsi-meeting" data-initial-view="listWeek" class="embed-responsive"></div>
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
        
    
        <!-- Core Libraries -->
        <script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>

        <!-- Custom Scripts -->
        <script src="<?=site_url(); ?>assets/prayer/assets/js/bundlee5ca.js?ver=3.2.3"></script>
        <script src="<?=site_url(); ?>assets/prayer/assets/js/scriptse5ca.js?ver=3.2.3"></script>

        
        <script src="https://meet.jit.si/external_api.js"></script>
        <script>
            const domain = 'meet.jit.si';
            const options = {
                roomName: '<?=ucwords($room_name); ?>',
                width: '100%', 
                height: 400,
                parentNode: document.querySelector('#jitsi-meeting'),
                configOverwrite: {
                    startWithAudioMuted: true,
                    startWithVideoMuted: false,
                },
                userInfo: {
                    displayName: '<?=$name; ?>',
                    church: '<?=$church;?>'
                }
            };
            const api = new JitsiMeetExternalAPI(domain, options);
        </script>
    </body>
</html>
