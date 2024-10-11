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
                                <h3 class="nk-block-title page-title"><?=translate_phrase(' Profile'); ?></h3>
                                <div class="nk-block-des">
                                    <p><?=translate_phrase('You have full control to manage your own account setting.');?></p>
                                    
                                </div>
                            </div>
                        </div><!-- .nk-block-head -->
                        <div class="nk-block">
                            <div class="card card-bordered">
                                <div class="card-inner card-inner-lg" id="profile_resp">
                                    
                                    <div class="nk-block">
                                        <div class="nk-data data-list data-list-s2">
                                            <div class="data-head">
                                                <h6 class="overline-title"><?=translate_phrase('Basics'); ?></h6>
                                            </div>
                                            
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Ministry</span>
                                                    <span class="data-value"><?=ucwords($this->Crud->read_field('id', $ministry_id, 'ministry', 'name')); ?></span>
                                                </div>
                                                <div class="data-col data-col-end">
                                                    <span class="data-more disable">
                                                        <em class="icon ni ni-lock-alt"></em>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/church'); ?>">
                                                <div class="data-col">
                                                    <span class="data-label">Church</span>
                                                    <span class="data-value"><?=ucwords($church_name); ?></span>
                                                </div>
                                                <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                            </div>
                                            <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/church'); ?>">
                                                <div class="data-col">
                                                    <span class="data-label">Church Email</span>
                                                    <span class="data-value"><?=($church_email); ?></span>
                                                </div>
                                                <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                            </div>
                                            <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/church'); ?>">
                                                <div class="data-col">
                                                    <span class="data-label">Church Phone</span>
                                                    <span class="data-value"><?=ucwords($church_phone); ?></span>
                                                </div>
                                                <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                            </div>
                                            <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/church'); ?>">
                                                <div class="data-col">
                                                    <span class="data-label">Church Address</span>
                                                    <span class="data-value"><?=ucwords($church_address); ?></span>
                                                </div>
                                                <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                            </div>
                                            <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/church'); ?>">
                                                <div class="data-col">
                                                    <span class="data-label">Church Country</span>
                                                    <span class="data-value">
                                                        <?php
                                                            if(empty($church_country)){
                                                                echo 'Country Not Set';
                                                                $currency = '';
                                                            } else {
                                                                echo $this->Crud->read_field('id',  $church_country, 'country', 'name').' ('.ucwords($this->Crud->read_field('id',  $church_country, 'country', 'currency_name')).')';
                                                            }
                                                        ?>
                                                    </span>
                                                </div>
                                                <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                            </div>
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Default Currency</span>
                                                    <span class="data-value">
                                                        <?php
                                                            if(empty($church_currency)){
                                                                echo 'Espees - ESP';
                                                            }else{
                                                                echo $this->Crud->read_field2('country_id',  $church_country, 'ministry_id', $ministry_id, 'currency', 'currency_name').' - '. $this->Crud->read_field2('country_id',  $church_country, 'ministry_id', $ministry_id, 'currency', 'symbol');
                                                            }
                                                        ?>
                                                    </span>
                                                </div>
                                                <div class="data-col data-col-end">
                                                    <span class="data-more disable">
                                                        <em class="icon ni ni-lock-alt"></em>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php if($church_region){?>
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Regional Church</span>
                                                    <span class="data-value"><?=ucwords($this->Crud->read_field('id', $church_region, 'church', 'name')); ?></span>
                                                </div>
                                                <div class="data-col data-col-end">
                                                    <span class="data-more disable">
                                                        <em class="icon ni ni-lock-alt"></em>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <?php if($church_zone){?>
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Zonal Church</span>
                                                    <span class="data-value"><?=ucwords($this->Crud->read_field('id', $church_zone, 'church', 'name')); ?></span>
                                                </div>
                                                <div class="data-col data-col-end">
                                                    <span class="data-more disable">
                                                        <em class="icon ni ni-lock-alt"></em>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php }?>
                                            <?php if($church_group){?>
                                            <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Group Church</span>
                                                    <span class="data-value"><?=ucwords($this->Crud->read_field('id', $church_group, 'church', 'name')); ?></span>
                                                </div>
                                                <div class="data-col data-col-end">
                                                    <span class="data-more disable">
                                                        <em class="icon ni ni-lock-alt"></em>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <!-- <div class="data-item">
                                                <div class="data-col">
                                                    <span class="data-label">Pastor-in-Charge</span>
                                                    <span class="data-value">
                                                        <?=ucwords($this->Crud->read_field('id', $church_pastor, 'user', 'title').' '.$this->Crud->read_field('id', $church_pastor, 'user', 'firstname').' '.$this->Crud->read_field('id', $church_pastor, 'user', 'surnmae')); ?>
                                                    </span>
                                                </div>
                                                <div class="data-col data-col-end">
                                                    <span class="data-more disable">
                                                        <em class="icon ni ni-lock-alt"></em>
                                                    </span>
                                                </div>
                                            </div> -->
                                        </div><!-- data-list -->
                                    </div><!-- .nk-block -->
                                </div>
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