<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
    $this->session = \Config\Services::session();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    
   
    <!-- insert/edit view -->
    <?php if ($param1 == 'enroll') {
        $user_id = $param3;
        $source = $param2;
        $ministry_id = $this->Crud->read_field('id', $user_id, 'user', 'ministry_id');
        $church_id = $this->Crud->read_field('id', $user_id, 'user', 'church_id');
        if($source == 'visitor'){
            $ministry_id = $this->Crud->read_field('id', $user_id, 'visitors', 'ministry_id');
            $church_id = $this->Crud->read_field('id', $user_id, 'visitors', 'church_id');
            
        }
    ?>

    <div class="row">
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) { echo $e_id; } ?>" />
        <input type="hidden" name="ministry_id" value="<?php if (!empty($ministry_id)) { echo $ministry_id; } ?>" />
        <input type="hidden" name="church_id" value="<?php if (!empty($church_id)) { echo $church_id; } ?>" />
        <input type="hidden" name="user_id" value="<?php if (!empty($user_id)) { echo $user_id; } ?>" />
        <input type="hidden" name="source" value="<?php if (!empty($source)) { echo $source; } ?>" />
        
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label">Year</label><br>
                <select id="year" name="year" class="js-select2" required>
                    <option value="">-- Select Year --</option>
                    <?php
                        $currentYear = date("Y"); // Get the current year
                        for ($year = 2023; $year <= $currentYear; $year++) {
                            $sel = '';
                            if (!empty($e_year) && $e_year == $year) {
                                $sel = 'selected';
                            }
                            echo '<option value="' . $year . '" ' . $sel . '>' . $year . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label">Quarter</label><br>
                <select id="quarter" name="quarter" class="js-select2" required>
                    <option value="">-- Select Quarter --</option>
                    <?php
                        $quarters = ['Q1' => 'January - March', 'Q2' => 'April - June', 'Q3' => 'July - September', 'Q4' => 'October - December'];
                        foreach ($quarters as $key => $value) {
                            $sel = '';
                            if (!empty($e_quarter) && $e_quarter == $key) {
                                $sel = 'selected';
                            }
                            echo '<option value="' . $key . '" ' . $sel . '>' . $value . '</option>';
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
        <div class="col-sm-12 my-3">
            <div id="bb_ajax_msg"></div>
        </div>
    </div>

<?php } ?>
<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script>
    $(function() {
        $('.js-select2').select2();
    });

</script>