<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?><!DOCTYPE html>
<head>
    <base href="../../../">
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
                height: 50vh;
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
                                        <h5 class="nk-block-title"><?=translate_phrase('Reset password');?></h5>
                                        <div class="nk-block-des">
                                            <p><?=translate_phrase('If you forgot your password, well, then we will email you instructions to reset your password.');?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_open_multipart('auth/forgot/code', array('id'=>'bb_ajax_form', 'class'=>'')); ?>
                            <div class="row"><div id="bb_ajax_msg"></div></div>
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="default-01"><?=translate_phrase('Email Address'); ?></label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control form-control-lg" id="email" name="email" placeholder="<?=translate_phrase('Enter your email address'); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-lg btn-primary btn-block"><?=translate_phrase('Send Reset Link'); ?></button>
                                </div>
                            <?=form_close();?>
                            <?php echo form_open_multipart('auth/forgot/confirm_code', array('id'=>'bb_ajax_form2', 'class'=>'', 'style'=>'display:none')); ?>
                                <div class="row"><div id="bb_ajax_msg2"></div></div>
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="default-01"><?=translate_phrase('Reset Code'); ?></label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control form-control-lg" id="code" name="code" placeholder="Enter Code" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-lg btn-primary btn-block"><?=translate_phrase('Confirm Code'); ?></button>
                                </div>
                            <?=form_close();?>
                            <?php echo form_open_multipart('auth/forgot/password', array('id'=>'bb_ajax_form3', 'class'=>'', 'style'=>'display:none')); ?>
                                    <div class="row"><div id="bb_ajax_msg3"></div></div>
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="default-01"><?=translate_phrase('New Password'); ?></label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="<?=translate_phrase('Enter Password'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block"><?=translate_phrase('Submit'); ?></button>
                                    </div>
                            <?=form_close();?>
                            <div class="form-note-s2 pt-5 text-center">
                                <a href="<?=site_url(''); ?>"><strong><?=translate_phrase('Return to login'); ?></strong></a>
                            </div>
                            </div>
                            <div class="nk-block nk-auth-footer">
                                
                                <div class="mt-3">
                                    <p>&copy; <?=date('Y').' '.app_name;?>. All Rights Reserved.</p>
                                </div>
                            </div>
                        </div>
                            <div class="nk-split-content nk-split-stretch" style="flex-shrink: 0 !important; background-image: url(<?=site_url('assets/bg.webp'); ?>);
                            background-position: center;
                            background-color: rgba(0, 0, 0, 0.5);
                            background-size: cover;
                            opacity: 0.9;
                            background-repeat: no-repeat;">
                                
                        </div>
                    </div>
                <!-- wrap @e -->
                </div>
        </div><!-- .nk-split -->
    </div><!-- app body @e -->
    <!-- JavaScript -->
    <script src="<?=site_url(); ?>assets/js/bundle.js?ver=3.1.2"></script>
    <script src="<?=site_url(); ?>assets/js/scripts.js?ver=3.1.2"></script>
    
    <script src="<?php echo site_url(); ?>assets/js/jsform.js"></script> 
    <!-- select region modal -->
    
</body>

</html>