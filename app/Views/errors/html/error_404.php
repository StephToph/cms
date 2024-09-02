<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="author" content="<?=app_name;?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?=site_url();?>/assets/fav.png">
    <!-- Page Title  -->
    <title>Error 404 | <?=app_name;?></title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?=site_url();?>/assets/css/dashlite.css">
    <link id="skin-default" rel="stylesheet" href="<?=site_url();?>assets/css/skins/theme-blue.css">
    <link id="skin-default" rel="stylesheet" href="<?=site_url();?>/assets/css/theme.css">
</head>

<body class="nk-body bg-white npc-default pg-error">
    <div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-wrap nk-wrap-nosidebar">
                <div class="nk-content" >
                    <div class="nk-block nk-block-middle wide-md mx-auto">
                        <div class="nk-block-content nk-error-ld text-center">
                            <img class="nk-error-gfx" src="<?=site_url();?>/assets/images/gfx/error-404.svg" alt="">
                            <div class="wide-xs mx-auto">
                                <h3 class="nk-error-title">Oops! Why you’re here?</h3>
                                <p class="nk-error-text">We are very sorry for inconvenience. It looks like you’re try to access a page that either has been deleted or never existed.</p>
                                <a href="<?=site_url('dashboard');?>" class="btn btn-lg btn-primary mt-2">Back To Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?=site_url();?>/assets/js/bundle.js"></script>
    <script src="<?=site_url();?>/assets/js/scripts.js"></script>
</html>