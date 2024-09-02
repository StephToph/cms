<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>
<!DOCTYPE html>
<head>
    <base href="<?=site_url(); ?>assets/../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="green">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?=site_url(); ?>assets/fav.png">
    <!-- Page Title  -->
    <title><?=$title; ?></title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?=site_url(); ?>assets/css/dashlite.css">
    <link id="skin-default" rel="stylesheet" href="<?=site_url(); ?>assets/css/skins/theme-blue.css">
    <style>
          /* Small screens (up to 576px) */
          @media (max-width: 576px) {
            .logo-dark {
                max-width: 80%; /* Adjust styles for small screens */
              
            }

            .card-bordered{
                height: 70vh;
            }
        }

        /* Medium screens (577px to 992px) */
        @media (min-width: 577px) and (max-width: 992px) {
            .logo-dark {
                max-width: 60%; /* Adjust styles for medium screens */
            }
        }

        /* Large screens (993px and above) */
        @media (min-width: 993px) {
            .logo-dark {
                max-width: 60%; /* Adjust styles for large screens */
               
            }
            .card-bordered{
                margin-right:25%;margin-left:25%;
            }
        }
    </style>
</head>
<!--End of Tawk.to Script-->
<body class="nk-body bg-white npc-general pg-auth">
    <!-- app body @s -->
    <div class="nk-app-root">
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-split nk-split-page nk-split-md">
                        <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
                            <div class="nk-block nk-block-middle nk-auth-body">
                                <div class="brand-logo pb-5" style="display: flex;justify-content: center;    align-items: center;">
                                    <a href="<?=site_url(); ?>" class="logo-link">
                                        <img class="logo-light logo-img logo-img-lg" style="max-height:100px" src="<?=site_url(); ?>assets/logo.png" srcset="<?=site_url(); ?>assets/logo.png" alt="logo">
                                        <img class="logo-dark logo-img logo-img-lg" style="max-height:100px" src="<?=site_url(); ?>assets/logo.png" srcset="<?=site_url(); ?>assets/logo.png" alt="logo-dark">
                                    </a>
                                </div>
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title">Sign-In</h5>
                                        <div class="nk-block-des">
                                            <p>Access the <?=app_name;?> dashboard panel using your email and password.</p>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_open_multipart('auth/login', array('id'=>'bb_ajax_form', 'class'=>'')); ?>
                                    <div class="row mb-3"><div id="bb_ajax_msg"></div></div>
                                    <div class="form-group">
                                        <div class="form-label-group"><label class="form-label" for="default-01">Membership ID</label></div>
                                        <div class="form-control-wrap"><input type="text"
                                                class="form-control form-control-lg" name="membership_id" id="default-01"
                                                placeholder="Enter your Membership ID"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group"><label class="form-label"
                                                for="password">Password</label><a class="link link-primary link-sm"
                                                tabindex="-1" href="<?=site_url('auth/forgot'); ?>">Reset Password?</a></div>
                                        <div class="form-control-wrap"><a tabindex="-1" href="#"
                                                class="form-icon form-icon-right passcode-switch lg"
                                                data-target="password"><em
                                                    class="passcode-icon icon-show icon ni ni-eye"></em><em
                                                    class="passcode-icon icon-hide icon ni ni-eye-off"></em></a><input
                                                type="password" name="password" class="form-control form-control-lg" id="password"
                                                placeholder="Enter your password"></div>
                                    </div>
                                    <div class="form-group"><button class="btn btn-lg btn-primary btn-block">Sign
                                            in</button></div>
                                </form>
                            </div>
                            <div class="nk-block nk-auth-footer">
                                
                                <div class="mt-3">
                                    <p>&copy; <?=date('Y').' '.app_name;?>. All Rights Reserved.</p>
                                </div>
                            </div>
                        </div>
                            <div class="nk-split-content nk-split-stretch d-none d-md-block" style="flex-shrink: 0 !important; background-image: url(<?=site_url('assets/bg.webp'); ?>);
                            background-position: center;
                            background-color: rgba(0, 0, 0, 0.5);
                            background-size: cover;
                            opacity: 0.9;
                            background-repeat: no-repeat;">
                                
                        </div>
                    </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>

    </div>
    
    <script>
        var site_url = '<?=site_url(); ?>';
        function lang_session(lang_id){
            if(lang_id !== ''){
                $.ajax({
                    url: site_url + 'auth/language/' + lang_id,
                    success: function (data) {
                        $('#bb_ajax_msg').html(data);                   
                    }
                });
            }
        }
    </script>
    
     <script src="<?=site_url(); ?>assets/js/bundle.js"></script>
    <script src="<?=site_url(); ?>assets/js/scripts.js"></script>
    <script src="<?php echo site_url(); ?>assets/js/jsform.js"></script> 
    <!-- select region modal -->
</body>

</html>