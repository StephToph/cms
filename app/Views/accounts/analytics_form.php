
<?php
use App\Models\Crud;
$this->Crud = new Crud();
$this->session = \Config\Services::session();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param2 == 'delete') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?=translate_phrase('Are you sure?');?></b></h3>
                <input type="hidden" name="d_membership_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete'); ?>
                </button>
            </div>
        </div>
    <?php } ?>

        
    <?php if($param2 == 'view'){?>
        <b><?=ucwords($this->Crud->read_field('id', $param3, 'partnership', 'name')).'`s Partnership History '; ?></b><br>
        <div class="row table-responsive">
            <table id="dtable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Members</th>
                        <th>Amount Paid</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $start_date = $p_start_date;
                        $end_date = $p_end_date;

                        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
                        
                        if($role != 'developer' && $role != 'administrator'){
                            if($role == 'ministry administrator'){
                                $pays = $this->Crud->date_range2($start_date, 'date_paid', $end_date, 'date_paid', 'partnership_id', $param3, 'ministry_id', $ministry_id, 'partners_history');

                            } else {
                                $pays = $this->Crud->date_range2($start_date, 'date_paid', $end_date, 'date_paid', 'partnership_id', $param3, 'church_id', $church_id, 'partners_history');

                            }
                           
                        } else {
                            $pays = $this->Crud->date_range1($start_date, 'date_paid', $end_date, 'date_paid', 'partnership_id', $param3, 'partners_history');

                        }
                    
                        $total = 0;
                        if(!empty($pays)){
                            foreach($pays as $p){
                                $time = $p->date_paid;
                                $member_id = $p->member_id;
                                $amount_paid = $p->amount_paid;
                                $status = $p->status;
                                $st = '<span class="text-warning">Pending</span>';
                                if($status > 0)$st = '<span class="text-success">Confirmed</span>';
                            
                                ?>
                                    <tr>
                                        <td><?=date('d M Y', strtotime($time)); ?></td>
                                        <td><?=ucwords($this->Crud->read_field('id', $member_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $member_id, 'user', 'surname')); ?></td>
                                        <td><?=$this->session->get('currency') .number_format($amount_paid,2); ?></td>
                                        <td><?=''.($st); ?></td>
                                    </tr>
                        <?php
                                    }
                                
                            
                        } else{
                            echo '<tr><td colspan="3" class="text-center">No Records</td></tr>';
                        }
                        
                    ?>
                </tbody>
            </table>
        </div>

    <?php } ?>

    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

       
    <?php } ?>
<?php echo form_close(); ?>
<script>
    $('.js-select2').select2();
   
    function statea() {
        var country = $('#country_id').val();
        $.ajax({
            url: '<?=site_url('accounts/get_state/');?>'+ country,
            success: function(data) {
                $('#state_resp').html(data);
            }
        });
        
    }

    function lgaa() {
        var lga = $('#state').val();
        $.ajax({
            url: '<?=site_url('accounts/get_lga/');?>'+ lga,
            success: function(data) {
                $('#lga_resp').html(data);
            }
        });
    }

    function branc() {
        var lgas = $('#lga').val();
        $.ajax({
            url: '<?=site_url('accounts/get_branch/');?>'+ lgas,
            success: function(data) {
                $('#branch_resp').html(data);
            }
        });
    }

</script>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>