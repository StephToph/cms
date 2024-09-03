<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
    <div class="nk-content" >
        <div class="container wide-xl ">
            <div class="nk-content-inner mt-5">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Roles</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Manage application roles</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <a href="javascript:;" class="float-right btn btn-primary pop" pageTitle="Add Role" pageName="<?php echo base_url('settings/roles/manage'); ?>" pageSize="">
                                                    <em class="icon ni ni-plus-sm"></em> Add
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nk-block">
                        <div class="row g-gs">
                            <div class="col-xxl-6">
                                <div class="row g-gs">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-inner table-responsive">
                                                <table id="dtable" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Role</th>
                                                            <th width="120px"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody> </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?=$this->endSection();?>