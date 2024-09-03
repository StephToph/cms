<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>
<!DOCTYPE html>
<head>
    <base href="../../../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?=site_url(); ?>assets/zend.png">
    <!-- Page Title  -->
    <title><?=$title; ?></title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?=site_url(); ?>assets/css/dashlite.css?ver=3.1.2">
    <link id="skin-default" rel="stylesheet" href="<?=site_url(); ?>assets/css/skins/theme-blue.css?ver=3.1.2">
    <style>
          /* Small screens (up to 576px) */
          @media (max-width: 576px) {
            .logo-dark {
                max-width: 80%; /* Adjust styles for small screens */
              
            }

            .card-bordered{
                height: 60vh;
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
                margin-right:5%;margin-left:5%;
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
                <div class="nk-content">
                    
                <div class="nk-content ">
                    <div class="nk-split nk-split-page nk-split-md">
                        <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
                            <div class="nk-block nk-block-middle nk-auth-body">
                                <div class="brand-logo pb-5" style="display: flex;justify-content: center;    align-items: center;">
                                    <a href="<?=site_url(); ?>" class="logo-link">
                                        <img class="logo-light logo-img logo-img-lg" style="max-height:100px" src="<?=site_url(); ?>assets/logo.png" srcset="/demo1/images/logo2x.png 2x" alt="logo">
                                        <img class="logo-dark logo-img logo-img-lg" style="max-height:100px" src="<?=site_url(); ?>assets/logo.png" srcset="/demo1/images/logo-dark2x.png 2x" alt="logo-dark">
                                    </a>
                                </div>
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title">Sign-Up</h5>
                                        <div class="nk-block-des">
                                            <p>Register on the <?=app_name;?> pldashboard panel using your email and password.</p>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_open_multipart('auth/login', array('id'=>'bb_ajax_form', 'class'=>'')); ?>
                                    <div class="row mb-3"><div id="bb_ajax_msg"></div></div>
                                    <div class="form-group">
                                        <div class="form-label-group"><label class="form-label" for="default-01">Email</label></div>
                                        <div class="form-control-wrap"><input type="text"
                                                class="form-control form-control-lg" name="email" id="default-01"
                                                placeholder="Enter your email address or username"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group"><label class="form-label"
                                                for="password">Passcode</label><a class="link link-primary link-sm"
                                                tabindex="-1" href="<?=site_url('auth/forgot'); ?>">Reset Password?</a></div>
                                        <div class="form-control-wrap"><a tabindex="-1" href="#"
                                                class="form-icon form-icon-right passcode-switch lg"
                                                data-target="password"><em
                                                    class="passcode-icon icon-show icon ni ni-eye"></em><em
                                                    class="passcode-icon icon-hide icon ni ni-eye-off"></em></a><input
                                                type="password" name="password" class="form-control form-control-lg" id="password"
                                                placeholder="Enter your passcode"></div>
                                    </div>
                                    <div class="form-group"><button class="btn btn-lg btn-primary btn-block">Sign
                                            in</button></div>
                                </form>
                                <div class="form-note-s2 pt-4"> New on our platform? 
                                    <a href="<?=site_url('auth/register'); ?>">Create an account</a>
                                </div>
                            </div>
                            <div class="nk-block nk-auth-footer">
                                <div class="nk-block-between">
                                    <ul class="nav nav-sm">
                                        <li class="nav-item dropup"><a
                                                class="dropdown-toggle dropdown-indicator has-indicator link link-primary fw-normal py-2 px-3"
                                                data-bs-toggle="dropdown" data-offset="0,10"><small>English</small></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <ul class="language-list">
                                                    <li><a href="#" class="language-item"><img
                                                                src="<?=site_url(); ?>assets/images/flags/english.png" alt=""
                                                                class="language-flag"><span
                                                                class="language-name">English</span></a></li>
                                                    <li><a href="#" class="language-item"><img
                                                                src="<?=site_url(); ?>assets/images/flags/spanish.png" alt=""
                                                                class="language-flag"><span
                                                                class="language-name">Español</span></a></li>
                                                    <li><a href="#" class="language-item"><img
                                                                src="<?=site_url(); ?>assets/images/flags/french.png" alt=""
                                                                class="language-flag"><span
                                                                class="language-name">Français</span></a></li>
                                                    <li><a href="#" class="language-item"><img
                                                                src="<?=site_url(); ?>assets/images/flags/turkey.png" alt=""
                                                                class="language-flag"><span
                                                                class="language-name">Türkçe</span></a></li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-3">
                                    <p>&copy; <?=date('Y').' '.app_name;?>. All Rights Reserved.</p>
                                </div>
                            </div>
                        </div>
                            <div class="nk-split-content nk-split-stretch" style="flex-shrink: 0 !important; background-image: url(<?=site_url('assets/bg.webp'); ?>);
                            background-position: center;
                            background-color: rgba(0, 0, 0, 0.5);
                            background-size: cover;
                            opacity: 0.5;
                            background-repeat: no-repeat;">
                                
                        </div>
                    </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
                    <div class="containe">
                        <div class="nk-content-iner">
                            <div class="nk-content-body">
                                <div class="kyc-app wide-sm m-auto">
                                    <div class="nk-block-head nk-block-head-lg wide-xs mx-auto">
                                        <div class="brand-logo text-white text-center">
                                            <a href="<?=site_url(); ?>" class="logo-link pt-4">
                                                <img class="logo-dark pt-5 " src="<?=site_url(); ?>assets/zend.png" srcset="<?=site_url(); ?>assets/zend.png 2x" alt="logo-dark" height="180">
                                            </a>
                                            <h3>Welcome to TIDREMS</h3>
                                            <p class="mx-5" style="font-style:italic;">Tax ID DIrect Remittance System for easy and secure Enrolment and Payment of government taxes.</p>
                                            <img class="logo-dark py-1 mb-2" src="<?=site_url(); ?>assets/delta.png" srcset="<?=site_url(); ?>assets/delta.png 2x" alt="logo-dark" height="70">
                                        </div>
                                    </div><!-- nk-block -->
                                    <div class="nk-block">
                                        <div class="card card-bordered"  style="border-radius:25px;border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;">
                                            <?php echo form_open_multipart('auth/register', array('id'=>'bb_ajax_form', 'class'=>'')); ?>
                                            
                                                <div class="nk-kycfm">
                                                    <div class="nk-kycfm-head">
                                                        <div class="nk-kycfm-count">01</div>
                                                        <div class="nk-kycfm-title">
                                                            <h5 class="title">Personal Details</h5>
                                                            <p class="sub-title">Your simple personal information required for identification</p>
                                                        </div>
                                                    </div><!-- nk-kycfm-head -->
                                                    <div class="nk-kycfm-content">
                                                        <div class="nk-kycfm-note">
                                                            <em class="icon ni ni-info-fill" data-bs-toggle="tooltip" data-bs-placement="right" title="Tooltip on right"></em>
                                                            <p><?=translate_phrase('Please type carefully and fill out the form with your personal details. Your can’t edit these details once you submitted the form.'); ?></p>
                                                        </div>
                                                        <div class="row g-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label"><?=translate_phrase('FullName'); ?> <span class="text-danger">*</span></label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <input type="text" required name="fullname" class="form-control form-control-lg">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label"><?=translate_phrase('Email Address');?> 
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <input type="email" class="form-control form-control-lg" name="email" id="email" placeholder="<?=translate_phrase('Enter your email address'); ?>" oninput="email_check();">
                                                                        <div id="email_response"></div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .col -->
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label"><?=translate_phrase('Phone Number');?> <span class="text-danger">*</span></label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <input type="text" class="form-control form-control-lg" maxlength="11" minlength="11" id="phone" name="phone" placeholder="<?=translate_phrase('Enter your Phone number'); ?>" oninput="phone_check(this);" required>
                                                                        <div id="phone_response"></div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .col -->
                                                            
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label">LGA <span class="text-danger">*</span></label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <select class="form-control form-control-lg js-select2" id="lga_id" data-search="on" name="lga_id"  onchange="get_territory();" required>
                                                                            <option value="0"><?=translate_phrase('--Select LGA--'); ?></option>
                                                                            <?php
                                                                                $sel = 161;
                                                                                $country = $this->Crud->read_single_order('state_id', 316, 'city', 'name', 'asc');
                                                                                if(!empty($country)){
                                                                                    foreach($country as $c){
                                                                                        $sels = '';
                                                                                        if($sel == $c->id)$sels = 'selected';
                                                                                        echo '<option value="'.$c->id.'" '.$sels.'>'.$c->name.'</option>';
                                                                                    }
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .col -->

                                                            <div class="col-sm-6 mb-3">
                                                                <div class="form-group" id="">
                                                                    <label for="activate"><?=translate_phrase('Territory');?></label>
                                                                    <select class="form-select js-select2" data-search="on" id="territorys" name="territory" required>
                                                                        <option value="0"><?=translate_phrase('Select LGA First'); ?></option>
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>
            
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label"><?=translate_phrase('Residential Address');?> <span class="text-danger">*</span></label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <input type="text" class="form-control form-control-lg" name="address" id="address" placeholder="<?=translate_phrase('Enter your residential address'); ?>" required>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div><!-- .col -->
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label">Trade/Business Type <span class="text-danger">*</span></label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <select class="form-control form-control-lg js-select2" id="trade" data-search="on" name="trade" required>
                                                                            <option value="0"><?=translate_phrase('--Select Trade Type--'); ?></option>
                                                                            <?php
                                                                                $country = $this->Crud->read_order('trade', 'name', 'asc');
                                                                                if(!empty($country)){
                                                                                    foreach($country as $c){
                                                                                        $sels = '';
                                                                                        if($sel == $c->id)$sels = 'selected';
                                                                                        echo '<option value="'.$c->id.'" '.$sels.'>'.$c->name.'</option>';
                                                                                    }
                                                                                } 
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .col -->
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <a tabindex="-1" href="javascript:;" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                                                        </a>
                                                                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="<?=translate_phrase('Enter your password'); ?>" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <a tabindex="-1" href="javascript:;" class="form-icon form-icon-right passcode-switch lg" data-target="confirm">
                                                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                                                        </a>
                                                                        <input type="password" class="form-control form-control-lg" id="confirm" name="confirm" placeholder="<?=translate_phrase('Re-Enter your password'); ?>" required>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label"><?=translate_phrase('Account Type');?> <span class="text-danger">*</span></label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <select class="form-control-lg js-select2"  id="role_ids" name="role_ids" onchange="roles();" required>
                                                                            <option value="personal"><?=translate_phrase('Personal'); ?></option>
                                                                            <option value="business"><?=translate_phrase('Business'); ?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                           
                                                            
                                                            <div class="col-md-6" style="display:none;" id="bus_name">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label"><?=translate_phrase('Business Name');?> <span class="text-danger"></span></label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <input type="text" class="form-control form-control-lg" name="business_name" id="business_name" placeholder="<?=translate_phrase('Enter your Business Name'); ?>">
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6" style="display:none;" id="bus_address">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label"><?=translate_phrase('Business Address');?> <span class="text-danger"></span></label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <input type="text" class="form-control form-control-lg" name="business_address" id="business_address" placeholder="<?=translate_phrase('Enter your Business Address'); ?>">
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <div class="form-label-group">
                                                                        <label class="form-label"><?=translate_phrase('Reference Phone');?> </label>
                                                                    </div>
                                                                    <div class="form-control-group">
                                                                        <input type="text" class="form-control form-control-lg" name="referral" id="referral"  maxlength="11" minlength="11" oninput="refs(this);" placeholder="<?=translate_phrase('Enter your Reference Phone'); ?>">
                                                                        
                                                                    </div>
                                                                    <span class="mt-2" id="ref_resp"></span>
                                                                </div>
                                                            </div>

                                                        </div><!-- .row -->
                                                    </div><!-- nk-kycfm-content -->
                                                    
                                                    <div class="nk-kycfm-head">
                                                        <div class="nk-kycfm-count">02</div>
                                                        <div class="nk-kycfm-title">
                                                            <h5 class="title"><?=translate_phrase('Document Upload'); ?></h5>
                                                            <p class="sub-title"><?=translate_phrase('To verify your identity, please upload your Passport.'); ?></p>
                                                        </div>
                                                    </div><!-- nk-kycfm-head -->
                                                    <div class="nk-kycfm-content">
                                                        <!-- <div class="nk-kycfm-upload">
                                                            <h6 class="title nk-kycfm-upload-title"><?=translate_phrase('Upload Valid ID Card Here'); ?></h6>
                                                            <div class="row align-items-center">
                                                                <div class="col-sm-8">
                                                                    <div class="nk-kycfm-upload-box">
                                                                        <label for="img-upload" class="pointer text-center" style="width:80%;">
                                                                            <img id="img0" src="<?php if(!empty($img)){echo site_url($img);} ?>" style="max-width:70%;" />
                                                                            <span class="btn btn-default btn-block no-mrg-btm d-grid btn btn-secondary waves-effect"><i class="mdi mdi-cloud-upload me-1"></i><?=translate_phrase('Upload ID Card'); ?></span>
                                                                            <input class="d-none" type="file" name="pics" id="img-upload">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 d-none d-sm-block">
                                                                    <div class="mx-md-4">
                                                                        <img src="<?=site_url(); ?>assets/images/icons/id-front.svg" alt="ID Front">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="nk-kycfm-upload">
                                                            <h6 class="title nk-kycfm-upload-title"><?=translate_phrase('Upload National ID or Utility Bill Here');?></h6>
                                                            <div class="row align-items-center">
                                                                <div class="col-sm-8">
                                                                    <div class="nk-kycfm-upload-box">
                                                                        <label for="img-uploads" class="pointer text-center" style="width:80%;">
                                                                            <img id="imgs" src="<?php if(!empty($img)){echo site_url($img);} ?>" style="max-width:70%;" />
                                                                            <span class="btn btn-default btn-block no-mrg-btm d-grid btn btn-secondary waves-effect"><i class="mdi mdi-cloud-upload me-1"></i><?=translate_phrase('Upload Image'); ?></span>
                                                                            <input class="d-none" type="file" name="utility"  id="img-uploads">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 d-none d-sm-block">
                                                                    <div class="mx-md-4">
                                                                        <img src="<?=site_url(); ?>assets/images/icons/bills.svg" alt="ID Back">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                        <div class="nk-kycfm-upload">
                                                            <h6 class="title nk-kycfm-upload-title"><?=translate_phrase('Upload Pasport Photograph Here');?></h6>
                                                            <div class="row align-items-center">
                                                                <div class="col-sm-8">
                                                                    <div class="nk-kycfm-upload-box">
                                                                        <label for="img-uploa" class="pointer text-center" style="width:80%;">
                                                                            <img id="imgs1" src="<?php if(!empty($img)){echo site_url($img);} ?>" style="max-width:70%;" />
                                                                            <span class="btn btn-default btn-block no-mrg-btm d-grid btn btn-secondary waves-effect"><i class="mdi mdi-cloud-upload me-1"></i><?=translate_phrase('Upload Image'); ?></span>
                                                                            <input class="d-none" type="file" name="pasport" id="img-uploa">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 d-none d-sm-block">
                                                                    <div class="mx-md-4">
                                                                        <img src="<?=site_url(); ?>assets/images/icons/profile.svg" alt="Profile">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- nk-kycfm-upload -->
                                                    </div><!-- nk-kycfm-content -->
                                                    <div class="nk-kycfm-footer">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-control-xs custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" name="agree" id="tc-agree" required>
                                                                <label class="custom-control-label" for="tc-agree"><?=translate_phrase('I Have Read The');?> <a href="javascript:;"><?=translate_phrase('Terms Of Condition');?></a> <?=translate_phrase('And');?> <a href="javascript:;"><?=translate_phrase('Privacy Policy') ;?></a></label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="custom-control custom-control-xs custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="info-assure" required>
                                                                <label class="custom-control-label" for="info-assure"><?=translate_phrase('All The Personal Information I Have Entered Is Correct.');?></label>
                                                            </div>
                                                        </div>
                                                        <div class="nk-kycfm-action pt-2">
                                                            <button type="submit" class="btn btn-lg btn-block btn-primary"><?=translate_phrase('Register') ;?></button>
                                                        </div>
                                                        <div class="row"><div id="bb_ajax_msg" class="pt-4 pb-4"></div></div>
                                                        <div class="form-note-s2 pt-4 text-center">
                                                            <?=translate_phrase('Already have an account'); ?> ? 
                                                            <a href="<?=site_url(); ?>"><strong><?=translate_phrase('Sign in instead'); ?></strong></a>

                                                        </div>
                                                    </div><!-- nk-kycfm-footer -->
                                                </div><!-- nk-kycfm -->
                                            </form>
                                        </div><!-- .card -->
                                    </div><!-- nk-block -->
                                </div><!-- .kyc-app -->
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="modal modal-center fade" tabindex="-1" id="myModal" role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <a href="javascript:;" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                    <div class="modal-header">
                        <h6 class="modal-title"></h6>
                    </div>
                    <div class="modal-body" id="modal-content-placeholder">

                    </div>
                </div>
            </div>
        </div>

    </div><!-- app body @e -->
    <script src="<?=site_url(); ?>assets/js/bundle.js?ver=3.1.2"></script>
    <script src="<?=site_url(); ?>assets/js/scripts.js?ver=3.1.2"></script>
    
    <script src="<?php echo site_url(); ?>assets/js/jsform.js"></script> 
    <script>
        $(function() {
            // onFailedEvent();
        });

        // Assume a successful event triggers this function
        function onSuccessEvent() {
            // Specify the URL of the page you want to load into the modal
            var pageUrl = "<?=site_url('auth/register_success'); ?>";

            
            $(".modal-dialog").addClass('modal-md');
            $(".modal-center .modal-title").html('Success Page');
            $(".modal-center .modal-body").html('<div class="row"><div class="text-center col-lg-12"><div class="spinner-border" role="status">  <span class="visually-hidden">Loading...</span></div><br/> Content loading, please wait...</div></div>');
            $(".modal-center .modal-body").load(pageUrl);
            $(".modal-center").modal("show");
        }

         // Assume a successful event triggers this function
        function onFailedEvent() {
            // Specify the URL of the page you want to load into the modal
            var pageUrl = "<?=site_url('auth/register_failed'); ?>";

            
            $(".modal-dialog").addClass('modal-md');
            $(".modal-center .modal-title").html('Error Page');
            $(".modal-center .modal-body").html('<div class="row"><div class="text-center col-lg-12"><div class="spinner-border" role="status">  <span class="visually-hidden">Loading...</span></div><br/> Content loading, please wait...</div></div>');
            $(".modal-center .modal-body").load(pageUrl);
            $(".modal-center").modal("show");
        }

        // Close the modal when the close button or outside the modal is clicked
        $(".close, #myModal").on("click", function() {
            $("#myModal").css("display", "none");
        });

        // Prevent modal from closing when the content inside the modal is clicked
        $(".modal-content").on("click", function(event) {
            event.stopPropagation();
        });
            
    </script>
       
    <script>
        $('.js-select2').select2();
       
        var site_url = '<?=site_url(); ?>';
        function country(){
            var country_id = $('#country_id').val();
            $.ajax({
                url: site_url + 'auth/get_state/' + country_id,
                success: function (data) {
                    $('#state_id').html(data);                   
                }
            });
        }

        function get_territory(){
        var lga_id = $('#lga_id').val();
        $.ajax({
            url: site_url + 'accounts/get_territory/' + lga_id,
            success: function(data) {
                $('#territorys').html(data);
            }
        });
        
    }

        function roles(){
            var role_id = $('#role_ids').val();
            if(role_id == 'personal'){
               $('#bus_name').hide(500);
               $('#bus_address').hide(500);
               
            }
            if(role_id == 'business'){
                $('#bus_name').show(500);
                $('#bus_address').show(500);
            }
        }

        function email_check(){
            var email = $('#email').val();
            $.ajax({
                url: site_url + 'auth/check_email/' + email,
                success: function (data) {
                    $('#email_response').html(data);                   
                }
            });
        }
        function phone_check(input) {
            var phone = $('#phone').val();
            input.value = input.value.replace(/\D/g, '');
            
            $.ajax({
                url: site_url + 'auth/check_phone/' + phone,
                success: function (data) {
                    $('#phone_response').html(data);
                }
            });
        }

        function refs(input) {
            var referral = $('#referral').val();
            input.value = input.value.replace(/\D/g, '');
            
            $.ajax({
                url: site_url + 'auth/check_ref/' + referral,
                success: function (data) {
                    $('#ref_resp').html(data);
                }
            });
        }


        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    if(id != 'vid') {
                        $('#' + id).attr('src', e.target.result);
                    } else {
                        $('#' + id).show(500);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        $("#img-upload").change(function(){
            readURL(this, 'img0');
        });
        
        $("#img-uploads").change(function(){
            readURL(this, 'imgs');
        });
        $("#img-uploa").change(function(){
            readURL(this, 'imgs1');
        });
    </script>
</body>

</html>