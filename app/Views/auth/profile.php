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
                                
                                <div class="card-inner card-inner-lg">
                                      <!-- Nav Tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item"> 
                                            <a class="nav-link active" data-bs-toggle="tab" href="#personalPreview" role="tab">
                                                <em class="icon ni ni-user"></em><span>Personal</span>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a class="nav-link" data-bs-toggle="tab" href="#churchPreview" role="tab">
                                                <em class="icon ni ni-home"></em><span>Church</span>
                                            </a> 
                                        </li>
                                    </ul>

                                    <!-- Tab Content -->
                                    <div class="tab-content mt-3">

                                        <!-- My Platform -->
                                        <div class="tab-pane fade show active" id="personalPreview" role="tabpanel">
                                            <div class="nk-block">
                                                <div class="nk-data data-list data-list-s2">
                                                    <div class="data-item">
                                                        <div class="data-col">
                                                            <span class="data-label">Membership ID</span>
                                                            <span class="data-value"><?=ucwords($user_no); ?></span>
                                                        </div>
                                                        <div class="data-col data-col-end">
                                                            <span class="data-more disable">
                                                                <em class="icon ni ni-lock-alt"></em>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-col">
                                                            <span class="data-label">Fullname</span>
                                                            <span class="data-value"><?=ucwords($fullname); ?></span>
                                                        </div>
                                                        <div class="data-col data-col-end">
                                                            <span class="data-more disable">
                                                                <em class="icon ni ni-lock-alt"></em>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/personal'); ?>">
                                                        <div class="data-col">
                                                            <span class="data-label">Email</span>
                                                            <span class="data-value"><?=ucwords($email); ?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div>
                                                    <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/personal'); ?>">
                                                        <div class="data-col">
                                                            <span class="data-label">Phone</span>
                                                            <span class="data-value"><?=($phone); ?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div>
                                                    <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/personal'); ?>">
                                                        <div class="data-col">
                                                            <span class="data-label">Chat Handle</span>
                                                            <span class="data-value"><?=($chat_handle); ?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div>
                                                    <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/personal'); ?>">
                                                        <div class="data-col">
                                                            <span class="data-label">Address</span>
                                                            <span class="data-value"><?=ucwords($address); ?></span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div>
                                                    <div class="data-item pop" pageTitle="<?=translate_phrase('Manage Profile');?>" pageSize="modal-md" pageName="<?=site_url('auth/profile/manage/personal'); ?>">
                                                        <div class="data-col">
                                                            <span class="data-label">Role</span>
                                                            <span class="data-value">
                                                                <?php
                                                                    if(empty($role_id)){
                                                                        echo 'State Not Set';
                                                                        $currency = '';
                                                                    } else {
                                                                        echo $this->Crud->read_field('id',  $role_id, 'access_role', 'name');
                                                                    }
                                                                ?>
                                                            </span>
                                                        </div>
                                                        <div class="data-col data-col-end"><span class="data-more"><em class="icon ni ni-forward-ios"></em></span></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-col">
                                                            <span class="data-label">Facebook Account</span>
                                                            <span class="data-value">
                                                                <a href="<?= base_url('social/facebook') ?>" class="btn btn-primary btn-facebook">
                                                                    <em class="icon ni ni-facebook-f"></em> Connect Facebook
                                                                </a>

                                                            </span>
                                                        </div>
                                                        <div class="data-col data-col-end">
                                                            <span class="data-more disable">
                                                                <em class="icon ni ni-lock-alt"></em>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-col">
                                                            <span class="data-label">QR Code</span>
                                                            <span class="data-value">
                                                                 <img src='<?= site_url($qrcode);?>' alt='QR Code' style='max-width:200px; margin-top:10px;' />
                                                            </span>
                                                        </div>
                                                        <div class="data-col data-col-end">
                                                            <span class="data-more disable">
                                                                <em class="icon ni ni-lock-alt"></em>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div><!-- data-list -->
                                            </div>
                                        </div>

                                        <!-- Facebook Preview -->
                                        <div class="tab-pane fade" id="churchPreview" role="tabpanel">
                                            <div class="nk-block">
                                                <?php $can_edit_church = ($is_admin == 1); 
                                                    $facebook_data = json_decode($this->Crud->read_field2('type', 'church', 'type_id', $church_id, 'social_connect', 'facebook'), true);
                                                    $facebook_page = $facebook_data['page_name'] ?? '';
                                                ?>

                                                <div class="nk-data data-list data-list-s2">

                                                    <!-- Ministry (Always Locked) -->
                                                    <div class="data-item">
                                                        <div class="data-col">
                                                            <span class="data-label">Ministry</span>
                                                            <span class="data-value"><?= ucwords($this->Crud->read_field('id', $ministry_id, 'ministry', 'name')); ?></span>
                                                        </div>
                                                        <div class="data-col data-col-end">
                                                            <span class="data-more disable">
                                                                <em class="icon ni ni-lock-alt"></em>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Editable Fields -->
                                                    <?php
                                                    $fields = [
                                                        ['label' => 'Church', 'value' => ucwords($church_name)],
                                                        ['label' => 'Church Email', 'value' => $church_email],
                                                        ['label' => 'Church Phone', 'value' => ucwords($church_phone)],
                                                        ['label' => 'Church Address', 'value' => ucwords($church_address)],
                                                    ];

                                                    foreach ($fields as $field): ?>
                                                        <?php if ($can_edit_church): ?>
                                                            <div class="data-item pop" pageTitle="<?= translate_phrase('Manage Profile'); ?>" pageSize="modal-md" pageName="<?= site_url('auth/profile/manage/church'); ?>">
                                                        <?php else: ?>
                                                            <div class="data-item">
                                                        <?php endif; ?>
                                                            <div class="data-col">
                                                                <span class="data-label"><?= $field['label']; ?></span>
                                                                <span class="data-value"><?= $field['value']; ?></span>
                                                            </div>
                                                            <div class="data-col data-col-end">
                                                                <span class="data-more <?= $can_edit_church ? '' : 'disable' ?>">
                                                                    <em class="icon ni <?= $can_edit_church ? 'ni-forward-ios' : 'ni-lock-alt' ?>"></em>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>

                                                    <!-- Church Country -->
                                                    <?php
                                                        $country_display = empty($church_country)
                                                            ? 'Country Not Set'
                                                            : $this->Crud->read_field('id',  $church_country, 'country', 'name') . ' (' . ucwords($this->Crud->read_field('id',  $church_country, 'country', 'currency_name')) . ')';
                                                    ?>
                                                    <?php if ($can_edit_church): ?>
                                                        <div class="data-item pop" pageTitle="<?= translate_phrase('Manage Profile'); ?>" pageSize="modal-md" pageName="<?= site_url('auth/profile/manage/church'); ?>">
                                                    <?php else: ?>
                                                        <div class="data-item">
                                                    <?php endif; ?>
                                                        <div class="data-col">
                                                            <span class="data-label">Church Country</span>
                                                            <span class="data-value"><?= $country_display; ?></span>
                                                        </div>
                                                        <div class="data-col data-col-end">
                                                            <span class="data-more <?= $can_edit_church ? '' : 'disable' ?>">
                                                                <em class="icon ni <?= $can_edit_church ? 'ni-forward-ios' : 'ni-lock-alt' ?>"></em>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Default Currency (Always Locked) -->
                                                    <div class="data-item">
                                                        <div class="data-col">
                                                            <span class="data-label">Default Currency</span>
                                                            <span class="data-value">
                                                                <?php
                                                                    if (empty($church_currency)) {
                                                                        echo 'Espees - ESP';
                                                                    } else {
                                                                        echo $this->Crud->read_field2('country_id',  $church_country, 'ministry_id', $ministry_id, 'currency', 'currency_name') . ' - ' .
                                                                            $this->Crud->read_field2('country_id',  $church_country, 'ministry_id', $ministry_id, 'currency', 'symbol');
                                                                    }
                                                                ?>
                                                            </span>
                                                        </div>
                                                        <div class="data-col data-col-end">
                                                            <span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span>
                                                        </div>
                                                    </div>

                                                    <!-- Optional Church Levels (Always Locked) -->
                                                    <?php if ($church_region): ?>
                                                        <div class="data-item">
                                                            <div class="data-col">
                                                                <span class="data-label">Regional Church</span>
                                                                <span class="data-value"><?= ucwords($this->Crud->read_field('id', $church_region, 'church', 'name')); ?></span>
                                                            </div>
                                                            <div class="data-col data-col-end">
                                                                <span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($church_zone): ?>
                                                        <div class="data-item">
                                                            <div class="data-col">
                                                                <span class="data-label">Zonal Church</span>
                                                                <span class="data-value"><?= ucwords($this->Crud->read_field('id', $church_zone, 'church', 'name')); ?></span>
                                                            </div>
                                                            <div class="data-col data-col-end">
                                                                <span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ($church_group): ?>
                                                        <div class="data-item">
                                                            <div class="data-col">
                                                                <span class="data-label">Group Church</span>
                                                                <span class="data-value"><?= ucwords($this->Crud->read_field('id', $church_group, 'church', 'name')); ?></span>
                                                            </div>
                                                            <div class="data-col data-col-end">
                                                                <span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                   
                                                    <div class="data-item">
                                                        <div class="data-col">
                                                            <span class="data-label">Facebook Account</span>
                                                            <span class="data-value">
                                                                <?php if ($can_edit_church): ?>
                                                                    <a href="<?= base_url('social/facebook') ?>" class="btn btn-facebook btn-sm">
                                                                        <em class="icon ni ni-facebook-f"></em> <?= !empty($facebook_page) ? 'Connected: ' . $facebook_page : 'Connect Facebook'; ?>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <?= !empty($facebook_page) ? esc($facebook_page) : '-'; ?>
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                        <div class="data-col data-col-end">
                                                            <span class="data-more <?= $can_edit_church ? '' : 'disable' ?>">
                                                                <em class="icon ni <?= $can_edit_church ? 'ni-forward-ios' : 'ni-lock-alt' ?>"></em>
                                                            </span>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                                <!-- data-list -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- .nk-block -->
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