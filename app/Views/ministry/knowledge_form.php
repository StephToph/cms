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
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name">Title</label>
                <input class="form-control" type="text" id="title" name="title" value="<?php if (!empty($e_title)) {
                    echo $e_title;
                } ?>" >
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group"><b>File</b><br>
                <label for="img-upload" class="pointer text-center" style="width:100%;">
                    <input type="hidden" name="img" value="<?php if(!empty($e_file)){echo $e_file;} ?>" />
                    
                    <span class="btn btn-info btn-block no-mrg-btm">Choose File</span>
                    <input class="d-none" type="file" name="pics" id="img-upload">
                </label>
                <div id="file-info" class="file-info"><?php if(!empty($e_file)){echo $e_file;} ?></div>
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
    var site_url = '<?php echo site_url(); ?>';
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

        NioApp.Dropzone('.upload-zone', {
            url: site_url + "ministry/knowledge/manage/upload"
      });
    });

    $(document).ready(function() {
        $('#img-upload').on('change', function() {
            var fileName = $(this).val().split('\\').pop(); // Extract file name from the path
            if (fileName) {
                $('#file-info').text('Selected file: ' + fileName); // Display file name
            } else {
                $('#file-info').text(''); // Clear file info if no file selected
            }
        });
    });

</script>