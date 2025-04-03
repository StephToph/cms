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
    <div class="row">
        <div class="col-sm-6 mb-3">
            <div class="user-card">
                <div class="user-avatar">
                    <?php $img = $this->Crud->read_field('id', $e_from_id, 'user', 'img_id');
                    $src = base_url($this->Crud->image($img, 'big')); ?>
                    <img src="<?= $src; ?>" alt="">
                </div>
                <div class="user-info">
                    <span
                        class="lead-text"><?= ucwords($this->Crud->read_field('id', $e_from_id, 'user', 'firstname').' '.$this->Crud->read_field('id', $e_from_id, 'user', 'surname')); ?></span>
                    <span class="sub-text"><?= $e_reg_date; ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 mb-3">
            <h5><?= ucwords($e_type); ?> Announcement to <?= ucwords($e_level); ?> Church (<?= ucwords($e_send_type); ?>)</h5>
        </div>

        <div class="col-sm-12 mb-3">
            <h5 class="text-center text-info"><?= ucwords($e_title); ?></h5>
            <div class="my-1">
                <p><?= ucwords(($e_content)); ?></p>
            </div>
        </div>

        <div class="mb-3">
            <h6></h6>
            <div class="mt-2 row">
                <?php
                if ($e_type == 'department') { ?>
                    <div class="col-sm-6 mb-2">
                        <div class="user-card">
                            <div class="user-info">
                                <span
                                    class="lead-text"><?= ucwords($this->Crud->read_field('id', $e_dept_id, 'dept', 'name')); ?>
                                    Department</span>
                            </div>
                        </div>
                    </div>

                <?php } else {
                    if($e_send_type == 'general'){
                        echo '<p class="text-info">Members In</p>';
                    }

                    if($e_send_type == 'individual'){
                        echo '<p class="text-info">Church</p>';
                    }
                    
                    
                    if (!empty($e_church_id)) {
                        foreach (json_decode($e_church_id) as $rec => $va) {
                            ;
                            $role = $this->Crud->read_field('id', $va, 'church', 'name');
                            $level = $this->Crud->read_field('id', $va, 'church', 'type');

                            $wors = $this->Crud->image_name($role);
                            $img = '<span>' . $wors . '</span>';

                            ?>
                            <div class="col-sm-4 mb-2">
                                <div class="user-card">
                                    <div class="user-avatar"><?= $img; ?></div>
                                    <div class="user-info">
                                        <span class="lead-text"><?= ucwords($role.' '.$level); ?> </span>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        
                    }?>

                <?php } ?>

            </div>
        </div>
    </div>

<?php } ?>

<!-- insert/edit view -->
<?php if ($param2 == 'edit' || $param2 == '') { ?>

    <div class="row">
        <input type="hidden" name="post_id" value="<?php if (!empty($e_id)) { echo $e_id; } ?>" />

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label class="form-label"  for="title">Post Title</label>
                <input class="form-control" type="text" id="title" name="title" value="<?php if (!empty($e_title)) { echo $e_title; } ?>" required>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label class="form-label"  for="content">Post Content</label>
                <textarea id="summernote" class="form-control" name="content" rows="5" required><?php if (!empty($e_content)) { echo $e_content; } ?></textarea>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label class="form-label"  for="media">Upload Media (Images or Videos)</label>
                <input type="file" class="form-control" name="media[]" multiple accept="image/*,video/*">
                <?php if (!empty($e_media_urls)) {
                    $mediaFiles = json_decode($e_media_urls, true);
                    echo '<small>Current Media:</small><div>';
                    foreach ($mediaFiles as $media) {
                        $isVideo = preg_match('/\.(mp4|avi|mov|wmv)$/i', $media);
                        if ($isVideo) {
                            echo '<video src="'.base_url($media).'" width="120" controls style="margin:5px"></video>';
                        } else {
                            echo '<img src="'.base_url($media).'" width="100" style="margin:5px">';
                        }
                    }
                    echo '</div>';
                    echo '<input type="hidden" name="existing_media" value="'.htmlspecialchars($e_media_urls).'">';
                } ?>
            </div>
        </div>


        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label class="form-label" >Select Platforms</label><br>
                <?php
                    $platforms = $e_platforms ?? [];
                    $allPlatforms = ['facebook', 'instagram', 'twitter', 'linkedin'];
                    foreach ($allPlatforms as $p) {
                        $checked = in_array($p, $platforms) ? 'checked' : '';
                        echo '<label><input type="checkbox" name="platforms[]" value="'.$p.'" '.$checked.'> '.ucfirst($p).'</label> &nbsp;';
                    }
                ?>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label class="form-label" for="scheduled_at">Schedule Date & Time</label>
                <div class="row">
                    <div class="col-md-6">
                        <input class="form-control date-picker" type="text" name="schedule_date" value="<?php if (!empty($e_scheduled_at)) echo date('Y-m-d', strtotime($e_scheduled_at)); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <input class="form-control time-picker" type="text" name="schedule_time" value="<?php if (!empty($e_scheduled_at)) echo date('H:i', strtotime($e_scheduled_at)); ?>" required>
                    </div>
                </div>
            </div>

        </div>

        <?php
            $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
            $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');

            if ($church_id > 0) { ?>
                <input type="hidden" name="church_id" value="<?php echo $church_id; ?>">
        <?php } else { ?>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label class="form-label" >Church</label>
                    <select class="js-select2" data-search="on" name="church_id" id="church_id" required>
                        <option value="">Select</option>
                        <?php
                            $churches = $this->Crud->read_order('church', 'name', 'asc');
                            if($role == 'ministry administrator'){
                                $churches = $this->Crud->read_single_order('ministry_id', $ministry_id, 'church', 'name', 'asc');
                            }
                            if (!empty($churches)) {
                                foreach ($churches as $d) {
                                    $sel = (!empty($e_church_id) && $e_church_id == $d->id) ? 'selected' : '';
                                    echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name.' - '.$d->type) . '</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        <?php } ?>

        <div class="col-sm-12 text-center">
            <hr />
            <button class="btn btn-primary bb_for_btn" id="bt" type="submit">
                <i class="icon ni ni-save"></i> Save Post
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

    $(function () {
        $('#summernote').summernote({
            height: 300, // Set the height of the editor
            tabsize: 2,
            focus: true
        });

        $('#church_id').select2({
            placeholder: 'Select Church(s)',
            allowClear: true  // This allows clearing the selection if needed
        });
        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd', // Set the date format
            autoclose: true
        });

        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd', // Set the date format
            autoclose: true
        });
        $('.time-picker').timepicker({});
    });


    var site_url = '<?php echo site_url(); ?>';

</script>