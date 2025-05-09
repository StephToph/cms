
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

<?php if($param1 == 'birthday'){?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date of Birth</th>
                </tr>
            </thead>
            <tbody>
                <?php $a =1;if (!empty($birthdays)) : ?>
                    <?php foreach ($birthdays as $member) : ?>
                        <tr>
                            <?php
                                $name = esc($member->firstname . ' ' . $member->othername . ' ' . $member->surname);
                                $profileUrl = site_url('accounts/membership/view/' . $member->id);
                                
                                $names = '
                                <a href="' . $profileUrl . '" class="text-decoration-none" title="View Profile">
                                    <i class="ni ni-eye me-1 text-primary"></i>' . ucwords(strtolower($name)) . '
                                </a>';
                                
                            
                            ?>
                            <td><?= $a; ?></td>
                            <td><?= ($names) ?></td>
                            <td><?= esc($member->email) ?></td>
                            <td><?= esc($member->phone) ?></td>
                            <td><?= date('F j', strtotime($member->dob)) ?></td>
                        </tr>
                    <?php $a++; endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">No upcoming birthdays this month</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php } ?>
    