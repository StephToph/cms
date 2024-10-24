<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
    
    $this->session = \Config\Services::session();
    
    $link = $param1;
    $min_title = $title;
    $form_id = $this->Crud->read_field('unique_link', $link, 'form_link', 'form_id');
    $form = $this->Crud->read_field('id', $form_id, 'form', 'name');
    $fields = $this->Crud->read_field('id', $form_id, 'form', 'fields');
    $ministry_id = $this->Crud->read_field('id', $form_id, 'form', 'ministry_id');
    $ministry = $this->Crud->read_field('id', $ministry_id, 'ministry', 'name');
    $logo = $this->Crud->read_field('id', $ministry_id, 'ministry', 'logo');
    if(empty($logo) || !file_exists($logo)){
        $logo = 'assets/new_logo1.png';
    }
    
    // echo $form_id;
    $min_title = $form.' - '.$ministry;

    header("Access-Control-Allow-Origin: *");  // Replace * with the specific origin(s) you want to allow
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
?>
<!DOCTYPE html>
<html lang="zxx" class="js">
<!-- Mirrored from dashlite.net/demo1/pages/auths/auth-register-v3.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 10 Mar 2024 16:14:29 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <base href="../../">
    <meta charset="utf-8">
    <meta name="author" content="Angel Church Management System">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="Church Performnce Tracking">
    <meta name="theme-color" content="blue">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?=site_url($logo); ?>">
    <title><?=$min_title; ?></title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?=site_url(); ?>assets/css/dashlitee5ca.css?ver=3.2.3">
    <link id="skin-default" rel="stylesheet" href="<?=site_url(); ?>assets/css/skins/theme-egyptian.css?ver=3.2.3">
    <style>
        
        .content-wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1; /* This ensures the content takes up available space */
        }

        .nk-auth-footer {
            text-align: center;
            padding: 20px 0;
        }
    </style>
</head>

<body class="nk-body bg-white npc-general pg-auth">
    <div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-wrap nk-wrap-nosidebar">
                <div class="nk-content ">
                    <div class="nk-split nk-split-page nk-split-md">
                        <div
                            class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white content-wrapper" style="width:80%;">
                            <div class="nk-block nk-auth-body content" style="max-width:100%;">
                                <div class="brand-logo pb-5"><a href="<?=site_url('ministry/forms/'.$param1); ?>" class="logo-link">
                                    <img class="logo-light logo-img logo-img-lg" src="<?=site_url($logo); ?>" srcset="<?=site_url($logo); ?> 2x" alt="logo"><img class="logo-dark logo-img logo-img-lg" src="<?=site_url($logo); ?>" srcset="<?=site_url($logo); ?> 2x" alt="logo-dark"></a>
                                </div>
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title"><?=ucwords($form);?></h5>
                                        <div class="nk-block-des">
                                            <p>Fields marked with an asterisk (*) are mandatory. Ensure you provide information in all required fields before submitting the form.</p>
                                        </div>
                                    </div>
                                </div>
                                <div id="validate_view">
                                    <input type="hidden" class="form-control" name="form_id" id="form_id" placeholder="Enter your Email" value="<?=$form_id; ?>">
                                    <input type="hidden" class="form-control" name="link" id="link" placeholder="Enter your Email" value="<?=$link; ?>">
                                    <div class="row">
                                        <div class="col-sm-4 my-2">
                                            <div class="form-group">
                                                <label class="form-label" for="email">Email</label>
                                                <div class="form-control-wrap">
                                                    <input type="email" class="form-control" name="emails" id="email" placeholder="Enter your Email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 my-2">
                                            <div class="form-group">
                                                <label class="form-label" for="phone">Phone*</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="phone" name="phones" placeholder="Enter Phone" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <div class="form-group mt-3">
                                                <button type="button" class="btn btn-lg btn-primary btn-block" id="submitForm">Submit</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row my-3">
                                    <div id="bb_ajax_msg"></div>
                                </div>
                                <div id="form_resp" style="display:none;">
                                    <?php echo form_open_multipart('ministry/forms/submit', array('id'=>'bb_ajax_form2', 'class'=>'')); ?>
                                        <input type="hidden" class="form-control" name="form_id" id="form_id" placeholder="Enter your Email" value="<?=$form_id; ?>">
                                        <input type="hidden" class="form-control" name="link" id="link" placeholder="Enter your Email" value="<?=$link; ?>">
                                        
                                        <div class="row" id="forms">

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-7">
                                                <div class="form-group mt-3">
                                                    <button type="button" id="form_submit" class="btn btn-lg btn-primary btn-block">Submit Form</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-3">
                                            <div id="bb_ajax_msg2"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="nk-block nk-auth-footer">
                                <div class="mt-3">
                                    <p>&copy; <?=date('Y').' '.$ministry;?>. All Rights Reserved.</p>
                                </div>
                            </div>
                        </div>
                        <div class="nk-split-content nk-split-stretch bg-abstract"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?=site_url(); ?>assets/js/bundle.js"></script>
    <script src="<?=site_url(); ?>assets/js/scripts.js"></script>
    <script src="<?php echo site_url(); ?>assets/js/jsform.js"></script> 
    
    <script>
        
        $(document).ready(function() {
            $('#submitForm').on('click', function(e) {
                e.preventDefault();
                $('#bb_ajax_msg').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                // Collect form data
                const form_id = $('#form_id').val();
                const link = $('#link').val();
                const phone = $('#phone').val();
                const email = $('#email').val();

                // Simple validation
                if (!phone) {
                    $('#bb_ajax_msg').html('Please fill all the required fields.');
                    return;
                }

                // AJAX request
                $.ajax({
                    url: '<?=site_url('ministry/forms/validate'); ?>',  // Change this to your target URL
                    type: 'POST',
                    data: {
                        link: link,
                        form_id: form_id,
                        phone: phone,
                        email: email
                    },
                    success: function(response) {
                        // Handle the response
                        $('#bb_ajax_msg').html(response);
                        setTimeout(function() {
                            $('#bb_ajax_msg').fadeOut('slow');
                        }, 3000);
                    }
                });
            });
        });

        $(document).ready(function () {
            // JSON structure (form configuration)
            const formConfig = <?=$fields; ?>;

            // Function to generate the form based on the JSON structure
            function generateForm(formConfig) {
                let formHtml = '';

                formConfig.forEach(field => {
                    if (field.type === 'text') {
                        formHtml += `
                            <div class="col-sm-4 my-2">
                                <div class="form-group">
                                    <label class="form-label">${field.label}</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" name="${field.label}" placeholder="Enter ${field.label}">
                                    </div>
                                </div>
                            </div>`;
                    } else if (field.type === 'single_choice') {
                        let options = '';
                        field.options.forEach(option => {
                            options += `<option value="${option}">${option}</option>`;
                        });
                        formHtml += `
                            <div class="col-sm-4 my-2">
                                <div class="form-group">
                                    <label class="form-label">${field.label}</label>
                                    <div class="form-control-wrap">
                                        <select class="form-control js-select2" name="${field.label}">
                                            ${options}
                                        </select>
                                    </div>
                                </div>
                            </div>`;
                    } else if (field.type === 'multiple_choice') {
                        let options = '';
                        field.options.forEach(option => {
                            options += `
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="${field.label}[]" value="${option}">
                                    <label class="form-check-label">${option}</label>
                                </div>`;
                        });
                        formHtml += `
                            <div class="col-sm-4 my-2">
                                <div class="form-group">
                                    <label class="form-label">${field.label}</label>
                                    <div class="form-control-wrap">
                                        ${options}
                                    </div>
                                </div>
                            </div>`;
                    } else if (field.type === 'true_false') {
                        formHtml += `
                            <div class="col-sm-4 my-2">
                                <div class="form-group">
                                    <label class="form-label">${field.label}</label>
                                    <div class="form-control-wrap">
                                        <select class="form-control js-select2" name="${field.label}">
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>`;
                    }
                });

                $('#forms').html(formHtml);

                // Initialize select2 after generating the form
                $('.js-select2').select2();
            }
            
            // Call the function to generate the form
            generateForm(formConfig);

            // Form submission using AJAX
            $('#form_submit').on('click', function (e) {
                e.preventDefault();
                $('#bb_ajax_msg2').html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                // Prepare form data as JSON
                let formData = $('#bb_ajax_form2').serializeArray();
    
                // Remove undefined or empty fields
                formData = formData.filter(function(field) {
                    return field.value !== undefined && field.value.trim() !== '';
                });

                // AJAX request to submit the form
                $.ajax({
                    url: '<?= site_url("ministry/forms/submit"); ?>',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#bb_ajax_msg2').html(response);
                        setTimeout(function() {
                            $('#bb_ajax_msg2').html('');
                        }, 3000);
                    }
                });
            });
        });


    </script>
</html>