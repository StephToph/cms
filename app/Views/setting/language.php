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
                                <h3 class="nk-block-title page-title">Language Settings</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Manage application language settings</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nk-block">
                        <div class="row g-gs">
                            <div class="col-lg-12">
                                <div class="row g-gs">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-inner table-responsive">
                                                <table id="dtable" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Language</th>
                                                            <th>Code</th>
                                                            <th>Flag</th>
                                                            <th>Status</th>
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