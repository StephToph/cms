<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?><!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../../">
    <meta charset="utf-8">
    <meta name="author" content="TiDREM">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="Add Money, Make Transfers, Pay Bills">
    <meta name="theme-color" content="blue">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?=site_url(); ?>assets/zend.png">
    <title><?=$title; ?></title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?=site_url(); ?>/assets/landing/css/dashlite.css?ver=3.1.2">
    <link id="skin-default" rel="stylesheet" href="<?=site_url(); ?>assets/css/skins/theme-egyptian.css?ver=<?=time(); ?>">
</head>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6596ffd10ff6374032bca25d/1hjatej6s';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->


<body class="nk-body bg-white npc-landing ">
    <div class="nk-app-root">
       <!-- main @s -->
       <div class="nk-main ">
            <header class="header has-header-main-s1" id="home"  style="background-image: url(<?=site_url('assets/land1.jpg'); ?>);background-size: cover;">
            <div class="header-main header-main-s1 is-sticky is-transparent on-dark">
                    <div class="container header-container">
                        <div class="header-wrap">
                            <div class="header-logo">
                                <a href="<?=site_url(); ?>" class="logo-link">
                                    <img class="logo-light logo-img" src="<?=site_url(); ?>assets/zend.png" srcset="<?=site_url(); ?>assets/zend.png 2x" alt="logo" style="max-height:60px">
                                    <img class="logo-dark logo-img" src="<?=site_url(); ?>assets/zend.png" srcset="<?=site_url(); ?>assets/zend.png 2x" alt="logo-dark" style="max-height:60px">
                                </a>
                            </div>
                            
                        </div><!-- .header-warp-->
                    </div><!-- .container-->
                </div><!-- .header-main-->
                
                <div class="header-content my-5 py-1" >
                    <div class="container">
                        <div class="row flex-lg-row-reverse align-items-center justify-content-between g-gs">
                            <div class="col-lg-5">
                                
                            </div><!-- .col- -->
                            <div class="col-lg-5 col-md-10">
                                <div class="header-caption">
                                    <h1 class="header-title fw-medium">About Zend Technologies LTD</h1>
                                    <div class="header-text text-dark">
                                        <p>Zend is an integrated technology solution company focused on the emerging markets across the globe. We specialise in using technology to drive and automate customer acquistion, commerce and wealth creation for organisation and government that lead to marked economic properity for the people.</p>
                                    </div>
                                    <ul class="header-action btns-inline">
                                        <li><a href="<?=site_url('auth/login'); ?>" class="btn btn-success btn-lg"><span>Sign In</span></a></li>
                                        <li><a href="<?=site_url('auth/register'); ?>" class="btn btn-primary btn-lg"><span>Create Account</span></a></li>
                                        
                                    </ul>
                                </div><!-- .header-caption -->
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </div><!-- .container -->
                </div><!-- .header-content -->
                <!-- .header-brand -->
            </header><!-- .header -->
            <section class="section section-service pb-3 pt-5" id="feature">
                <div class="container">
                    <div class="section-content">
                        <div class="row justify-content-center text-dark text-start g-gs">
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <div class="service">
                                    <div class="service-icon styled-icon styled-icon-s2 styled-icon-6x circle text-primary">
                                        <img src="<?=site_url('assets/acquisition.png'); ?>" style="opacity:60%" height="45px">
                                    </div><!-- .service-icon -->
                                    <div class="service-text">
                                        <h6 class="title mb-4">Customer Acquistion Automation</h6>
                                        <p class="small">Technology is changing the way customers view the world. It is changing the way customers consume media, interact with each other, buy things and share their stories. <br><br>Organizations also must align with this changing customer dynamics and owning the digital touchpoints through customers daily journey online and convert those experiences into retain relationships. <br><br>This is where Zend comes in. we deploy technology that engender mass customer acquisitions.</p>
                                    </div><!-- .service-text -->
                                </div><!-- .service -->
                            </div><!-- .col -->
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <div class="service">
                                    <div class="service-icon styled-icon styled-icon-s2 styled-icon-6x circle text-primary">
                                        <img src="<?=site_url('assets/identity.png'); ?>" style="opacity:60%" height="45px">
                                    </div><!-- .service-icon -->
                                    <div class="service-text">
                                        <h6 class="title">Population Enumeration and Identity Management using Technology</h6>
                                        <p class="small">Daily, peoples activities and footprints are captured by telecoms and internet solutions. Enumerating people in countries, city and regions by any geographic and demographic metrics can now be made possible using technology and the right partner like Zend. </p>
                                    </div><!-- .secvice-text -->
                                </div><!-- .service -->
                            </div><!-- .col- -->
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <div class="service">
                                    <div class="service-icon styled-icon styled-icon-s2 styled-icon-6x circle text-primary">
                                        <img src="<?=site_url('assets/payment.png'); ?>" style="opacity:60%" height="45px">
                                    </div><!-- .service-icon -->
                                    <div class="service-text">
                                        <h6 class="title mb-4">Payment Automation</h6>
                                        <p class="small">Most institutions and organizations across emerging markets still rely on manual process to collect the larger size of their revenue from their customers. The educational and infrastructural complexities make it difficult to transition. Zend is working with some organization with remarkable results in their area. </p>
                                    </div><!-- .service-text -->
                                </div><!-- .service -->
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </div><!-- .section-content -->
                </div><!-- .container -->
            </section><!-- .section -->
            <br>
            
            <section class="section section-feature py-5" id="feature"  style="background-image: url(<?=site_url('assets/land2.jpg'); ?>);background-size: cover;">
                <div class="container">
                    <div class="row justify-content-between  align-items-center">
                        <div class="col-lg-5">
                            <div class=" left">
                                
                            </div>
                        </div><!-- .col -->
                        <div class="col-lg-6">
                            <div class="text-blok p-3 bg-primary text-white py-5">
                                <h6 class="title mml-2">Our Solutions</h6>
                                <ul class="list list-lg list-light small list-checked-circle pb-2 outlined">
                                    <li>Customer Acquisition Automation</li>
                                    <li>Population Enumeration and Identity Management using Technologyy</li>
                                    <li>Custom Software Solutions</li>
                                    <li>Hardware Solutions</li>
                                    <li>Payment Automation </li>
                                    <li>Agency Network Management</li>
                                </ul>
                            </div><!-- .text-block -->
                        </div><!-- .col -->
                    </div><!-- .row -->
                </div><!-- .container -->
            </section><!-- .section -->
            <section class="section section-feature py-5" id="feature"  style="background-image: url(<?=site_url('assets/land3.png'); ?>);background-size: cover;">
                <div class="container">
                    <div class="row justify-content-between  align-items-center">
                        <div class="col-sm-10 col-lg-6">
                            <div class="text-blok text-dark p-3 py-5">
                                <h6 class="title mml-2">Industries We Serve</h6>
                                <p class="small"><br>
                                    <b>Financial Services</b><br>
                                    Zend provides innovative new products through research and development to leading financial services companies such as banks and insurance firms. Our personalized banking solutions with Providus Bank is helping thousands of customers enjoy banking the way they want. <br><br>
                                    <b>Telecommunications</b><br>
                                    Zend provides management services to giant in the telecoms industry. Our solutions help in customer acquisition, technology transition for thousands of retailers and large scale dealers. <br><br>
                                    <b>Government</b><br>
                                    We are one of the leading providers of large administration solutions to state governments in Nigeria. We part with states such as the Delta State government to improve tax collections in the informal sector. </p>
                            </div><!-- .text-block -->
                        </div><!-- .col -->
                    </div><!-- .row -->
                </div><!-- .container -->
            </section><!-- .section -->
            <section class="section secton-cta bg-lighter">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-10">
                            <div class="row align-items-center g-0">
                                <div class="col-md-7">
                                    <div class="card card-shadow round-xl  bg-primary pb-4 pb-md-0">
                                        <div class="card-inner card-inner-md">
                                            <div class="text-block text-white">
                                                <h3 class="title">Contact Details</h3>
                                                <ul class="list list-nostyle fs-16px">
                                                    <li>Head Office: No 1. Ashabi Cole street, Agindingbi, Ikeja, Lagos.</li>
                                                    <li>Phone: +234 811 159 1118</li>
                                                    <li>Email: info@zend.ng</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .col -->
                                <div class="col-md-5">
                                    <div class="card card-shadow round-xl ms-lg-n7 ms-md-n5 mx-4 me-md-0 mt-md-0 mt-n4">
                                        <div class="card-inner card-inner-lg">
                                            <div class="form-block">
                                                <div class="section-head section-head-sm">
                                                    <h4 class="title">Do you have any other question?</h4>
                                                </div>
                                                
                                                <?php echo form_open_multipart('dashboard/home/email', array('id'=>'bb_ajax_form', 'class'=>'form-submit')); ?>
                                                    <div id="bb_ajax_msg"></div>
                                                    <div class="row g-4">
                                                        <div class="col-12">
                                                            <div class="form-group ">
                                                                <label class="form-label" for="name">Your Name</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control form-control-lg" name="name" id="name" placeholder="Your Name" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="email">Enter Your Email</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control form-control-lg" name="email" id="email" placeholder="Enter Your Email" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="question">Your Question</label>
                                                                <div class="form-control-wrap">
                                                                    <textarea class="form-control no-resize" name="question" id="question" placeholder="Your Question" required></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <button type="submit" class="btn btn-lg btn-primary">Ask Question</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .col -->
                            </div><!-- .row -->
                        </div><!-- .col -->
                    </div><!-- .row -->
                </div><!-- .container -->
            </section><!-- .section -->
           
            <footer class="footer">
                <div class=" text-center bg-primary py-3">
                    <div class="container">
                        <div class="text-ba text-white"> &copy; <?=date('Y'); ?>, <?=app_name; ?>.</div>
                    </div>
                </div>
            </footer><!-- .footer -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="<?=site_url(); ?>/assets/landing/js/bundle.js?ver=3.1.2"></script>
    <script src="<?=site_url(); ?>/assets/landing/js/scripts.js?ver=3.1.2"></script>
    
    <script src="<?=site_url();?>assets/js/jsform.js"></script>
</body>

</html>
