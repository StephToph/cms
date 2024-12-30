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
                height: 100vh; /* Adjust height for smaller screens */
            }
        }


    </style>
    

    
    <body class="nk-body bg-white npc-landing">
        <div class="nk-app-root">
            <div class="nk-main">
                
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

                
            </div>
        </div>
        
    
        <!-- Core Libraries -->
        <script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>

        <!-- Custom Scripts -->
        <script src="<?=site_url(); ?>assets/prayer/assets/js/bundlee5ca.js?ver=3.2.3"></script>
        <script src="<?=site_url(); ?>assets/prayer/assets/js/scriptse5ca.js?ver=3.2.3"></script>

        <script src='https://8x8.vc/libs/external_api.min.js'></script>
        <script>
            const domain = '8x8.vc';
            const jwtToken = '<?= $jwtToken ?>';  
            console.log(jwtToken);
            const options = {
                roomName: 'vpaas-magic-cookie-0ab53463adb9451ab8ddd32e5206ef9f/<?=ucwords($room_name); ?>',
                width: '100%', 
                height: 800,
                parentNode: document.querySelector('#jitsi-meeting'),
                jwt: jwtToken  
            };
            const api = new JitsiMeetExternalAPI(domain, options);
        </script>
    </body>
</html>
