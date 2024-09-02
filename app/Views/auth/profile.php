<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>


<?=$this->section('content');?>
    <!-- content @s -->
    <div class="nk-content" >
        <div class="container wide-xl  mt-5">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-content-wrap">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title"><?=translate_phrase('My Profile'); ?></h3>
                                <div class="nk-block-des">
                                    <p><?=translate_phrase('You have full control to manage your own account setting.');?></p>
                                    
                                </div>
                            </div>
                        </div><!-- .nk-block-head -->
                        <div class="nk-block">
                            <div class="card card-bordered">
                                <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="<?=site_url('auth/profile'); ?>"><em class="icon ni ni-user-fill-c"></em><span><?=translate_phrase('Personal'); ?></span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?=site_url('accounts/membership/partnership/'.$log_id); ?>"><em class="icon ni ni-cc-secure"></em><span><?=translate_phrase('Partnership Goals'); ?></span></a>
                                    </li>
                                </ul><!-- .nav-tabs -->
                                <div class="card-inner card-inner-lg">
                                    <div class="nk-block-head">
                                        <div class="nk-block-head-content">
                                            <h4 class="nk-block-title"><?=translate_phrase('Personal Information'); ?></h4>
                                            <div class="nk-block-des">
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="nk-data data-list data-list-s2">
                                            <div class="data-head">
                                                <h6 class="overline-title"><?=translate_phrase('Basics'); ?></h6>
                                            </div>
                                            <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile'); ?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/personal'); ?>">
                                                <div class="data-col">
                                                    <span class="data-label"><?=translate_phrase('Full Name');?></span>
                                                    <span class="data-value"><?=$fullname;?></span>
                                                </div>
                                                <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                            </div><!-- data-item -->
                                            <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile'); ?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/personal'); ?>">
                                                <div class="data-col">
                                                    <span class="data-label"><?php 
                                                        echo translate_phrase('Kingchat Handle');?></span>
                                                    <span class="data-value"><?=$chat_handle; ?></span>
                                                </div>
                                                <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label"><?=translate_phrase('Email'); ?></span>
                                                    <span class="data-value"><?=$email;?></span>
                                                </div>
                                                <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                            </div><!-- data-item -->
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label"><?=translate_phrase('Phone Number'); ?></span>
                                                    <span class="data-value text-soft"><?=$phone;?></span>
                                                </div>
                                                <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                            </div><!-- data-item -->
                                            <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/personal'); ?>">
                                                <div class="data-col">
                                                    <span class="data-label"><?=translate_phrase('Address'); ?></span>
                                                    <span class="data-value"><?=$address; ?></span>
                                                </div>
                                                <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                            </div>
                                           
                                        </div><!-- data-list -->
                                    </div><!-- .nk-block -->
                                </div><!-- .card-inner -->
                            </div><!-- .card -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?=$this->endSection();?>

<?=$this->section('scripts');?>
    <script src="<?=site_url(); ?>assets/js/jsmodal.js"></script>
<?=$this->endSection();?>