<?php
use App\Models\Crud;

$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id' => 'bb_ajax_form', 'class' => '')); ?>
<!-- delete view -->
<?php if ($param2 == 'delete') { ?>
    <div class="row">
        <div class="col-sm-12">
            <div id="bb_ajax_msg"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 text-center">
            <h3><b>Are you sure?</b></h3>
            <input type="hidden" name="d_id" value="<?php if (!empty($d_id)) {
                echo $d_id;
            } ?>" />
        </div>

        <div class="col-sm-12 text-center">
            <button class="btn btn-danger text-uppercase" type="submit">
                <i class="icon ni ni-trash"></i> Yes - Delete
            </button>
        </div>
    </div>
<?php } ?>

<?php if ($param2 == 'view') { ?>
    <div class="row gy-3 py-1">
         <!-- Event Name -->
        <div class="col-sm-12 mb-3">
            <h6 class="overline-title">Prayer Title</h6>
            <p id="preview-event-name"><?= ucwords($e_name); ?></p>
        </div>

        <!-- Start Time -->
        <div class="col-sm-6 mb-3">
            <h6 class="overline-title">Start Time</h6>
            <p id="preview-event-start"><?= $start_time ? date('h:iA',strtotime($start_time)) : 'Not specified'; ?></p>
        </div>

        <!-- End Time -->
        <div class="col-sm-6 mb-3">
            <h6 class="overline-title">End Time</h6>
            <p id="preview-event-end"><?= $end_time ? date('h:iA',strtotime($end_time)) : 'Not specified'; ?></p>
        </div>
        
        <div class="col-sm-6 mb-3">
            <h6 class="overline-title">Time Zone</h6>
            <?php
                // Define the array of time zones (name => value)
                $timeZones = [
                    "EST" => "Eastern Standard Time (EST)",
                    "CST" => "Central Standard Time (CST)",
                    "MST" => "Mountain Standard Time (MST)",
                    "PST" => "Pacific Standard Time (PST)",
                    "AKST" => "Alaska Standard Time (AKST)"
                ];
            ?>
            <p id="preview-event-reminder">
                <?php
                // Check if the time_zone is set and exists in the array, then display the full meaning
                if (!empty($time_zone) && isset($timeZones[$time_zone])) {
                    echo $timeZones[$time_zone];  // Show the full meaning of the selected time zone
                } else {
                    echo '';  // Display an empty string if no valid time zone is selected
                }
                ?>
            </p>
        </div>

        <!-- Church -->
        <div class="col-sm-5 mb-3">
            <h6 class="overline-title">Church</h6>
            <p id="preview-event-church"><?= ucwords($this->Crud->read_field('id', $church_idz, 'church', 'name')); ?></p>
        </div>


        <!-- Prayer Description -->
        <div class="col-sm-12 mb-3">
            <h6 class="overline-title">Prayer Point</h6>
            <p id="preview-event-prayer"><?= $prayer ? $prayer : 'No description provided'; ?></p>
        </div>

    </div>
<?php } ?>

<?php echo form_close(); ?>

<?php echo form_open_multipart('prayer/index/manage/join', array('id' => 'bb_ajax_form2', 'class' => '')); ?>
    <?php if ($param2 == 'join') { ?>
        <input type="hidden" name="room_name" value="<?= isset($e_link['room_name']) ? $e_link['room_name'] : ''; ?>" >
        <input type="hidden" name="link" value="<?= isset($e_link['room_link']) ? $e_link['room_link'] : ''; ?>" >

        <input type="hidden" name="duration" value="<?= isset($duration) ? $duration : ''; ?>" >

        
        <div class="row py-1">
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control" type="text" id="name" name="name" value="" required>
                </div>
            </div>
            

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label  class="form-label">Church</label>
                    <select class="js-select2" data-search="on" name="church_id" id="church_id" required>
                        <option value="">Select</option>
                        <?php 
                            $e_churches = $this->Crud->read_single_order('regional_id', 8, 'church', 'name', 'asc');
                            if(!empty($e_churches)){
                                foreach($e_churches as $ch){
                                    
                                    echo '<option value="'.$ch.'">'.ucwords($this->Crud->read_field('id', $ch, 'church', 'name')).'</option>';

                                }
                            }
                        ?>
                    </select>
                </div>
            </div>


            <div class="col-sm-12 text-center">
                <hr />
                <button class="btn btn-primary bb_for_btn" id="bt" type="submit">
                    <i class="icon ni ni-save"></i> Save
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div id="bb_ajax_msg2"></div>
            </div>
        </div>
    <?php } ?>

<?php echo form_close(); ?>
<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>

<script>
   var site_url = '<?php echo site_url(); ?>';

</script>