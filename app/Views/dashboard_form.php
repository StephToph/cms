
<?php
use App\Models\Crud;
$this->Crud = new Crud();
?>
<?php if($param2 == 'service_offering') { ?>
    <div class="row">
        <h4 class=" my-3 ">Service Offering Record from <?=date('d M Y', strtotime($start_date)); ?> to <?=date('d M Y', strtotime($end_date)); ?></h4>
        <!-- <h5 class=" my-3 text-primary"></h5> -->
    </div>
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <td>Date</td>
                        <td>Church</td>
                        <td>Service</td>
                        <td>Member</td>
                        <td>Type</td>
                        <td>Amount</td>
                    </tr>
                </thead>
                <tbody>
                    <?=$offering_list; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
<?php if($param2 == 'cell_offering') { ?>
    <div class="row">
        <h4 class=" my-3 ">Cell Offering Record from <?=date('d M Y', strtotime($start_date)); ?> to <?=date('d M Y', strtotime($end_date)); ?></h4>
        <!-- <h5 class=" my-3 text-primary"></h5> -->
    </div>
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <td>Date</td>
                        <td>Church</td>
                        <td>Cell</td>
                        <td>Week</td>
                        <td>Member</td>
                        <td>Type</td>
                        <td>Amount</td>
                    </tr>
                </thead>
                <tbody>
                    <?=$cell_list; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>

<?php if($param2 == 'service_tithe') { ?>
    <div class="row">
        <h4 class=" my-3 ">Service Tithe Record from <?=date('d M Y', strtotime($start_date)); ?> to <?=date('d M Y', strtotime($end_date)); ?></h4>
        <!-- <h5 class=" my-3 text-primary"></h5> -->
    </div>
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <td>Date</td>
                        <td>Church</td>
                        <td>Service</td>
                        <td>Member</td>
                        <td>Type</td>
                        <td>Amount</td>
                    </tr>
                </thead>
                <tbody>
                    <?=$offering_list; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>


<?php if($param2 == 'partnership') { ?>
    <div class="row">
        <h4 class=" my-3 ">Partnership Record from <?=date('d M Y', strtotime($start_date)); ?> to <?=date('d M Y', strtotime($end_date)); ?></h4>
        <!-- <h5 class=" my-3 text-primary"></h5> -->
    </div>
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <td>Date</td>
                        <td>Church</td>
                        <td>Service</td>
                        <td>Partnership</td>
                        <td>Member</td>
                        <td>Type</td>
                        <td>Amount</td>
                    </tr>
                </thead>
                <tbody>
                    <?=$offering_list; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
    