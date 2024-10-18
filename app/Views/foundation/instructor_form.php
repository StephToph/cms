<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param2 == 'delete') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?=translate_phrase('Are you sure?'); ?></b></h3>
                <input type="hidden" name="d_user_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete'); ?>
                </button>
            </div>
        </div>
    <?php } ?>

    <!-- insert/edit view -->
    
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>
        <div class="container table-responsive">
            <h5>Assign Instructors for Foundation School</h5>

            <table class="table table-borderless" id="instructor-table">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Course</th>
                        <th>Instructor</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="instructor-body">
                    <?php if(!empty($e_teacher_course)) {
                       foreach($e_teacher_course as $index => $course) { ?>
                        <tr>
                            <td width="130px">
                                <input type="text" name="weeks[]" value="Week <?php echo $index + 1; ?>" class="form-control" readonly>
                            </td>
                            <td>
                                <select name="courses[]" data-search="on" class="form-control js-select2 course-select" required>
                                    <option value="">-- Select Course --</option>
                                    <?php
                                    // Fetch available courses from the database
                                    $courses = $this->Crud->read_order('foundation_courses', 'name', 'asc');
                                    foreach ($courses as $c) {
                                        echo '<option value="' . $c->id . '"' . ($c->id == $course->course_id ? ' selected' : '') . '>' . $c->name . '</option>';
                                    }
                                    ?>
                                    <option value="new">+ Add New Course</option>
                                </select>
                                <input type="text" name="new_courses[]" class="form-control new-course mt-2" style="display:none;" placeholder="Enter new course name">
                            </td>
                            <td>
                                <select name="instructors[]" data-search="on" class="form-control js-select2" required>
                                    <option value="">-- Select Instructor --</option>
                                    <?php
                                    // Fetch available instructors from the database
                                    $instructors = $this->Crud->read_single_order('is_leader', 1, 'user', 'surname', 'asc');
                                    foreach ($instructors as $instructor) {
                                        echo '<option value="' . $instructor->id . '"' . ($instructor->id == $course->instructor_id ? ' selected' : '') . '>' . ucwords($instructor->surname.' '.$instructor->firstname) . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php } }else {?>
                        <!-- Start with Week 1 to Week 7 -->
                        <?php for ($i = 1; $i <= 7; $i++): ?>
                        <tr>
                            <td width="130px">
                                <input type="text" name="weeks[]" value="Week <?php echo $i; ?>" class="form-control" readonly>
                            </td>
                            <td>
                                <select name="courses[]" data-search="on" class="form-control js-select2 course-select" required>
                                    <option value="">-- Select Course --</option>
                                    <?php
                                    // Fetch available courses from the database
                                    $courses = $this->Crud->read_order('foundation_courses', 'name', 'asc');
                                    foreach ($courses as $course) {
                                        echo '<option value="' . $course->id . '">' . $course->name . '</option>';
                                    }
                                    ?>
                                    <option value="new">+ Add New Course</option>
                                </select>
                                <input type="text" name="new_courses[]" class="form-control new-course mt-2" style="display:none;" placeholder="Enter new course name">
                    
                            </td>
                            <td>
                                <select name="instructors[]" data-search="on" class="form-control js-select2" required>
                                    <option value="">-- Select Instructor --</option>
                                    <?php
                                    // Fetch available instructors from the database
                                    $instructors = $this->Crud->read_single_order('is_leader', 1, 'user', 'surname', 'asc');
                                    foreach ($instructors as $instructor) {
                                        echo '<option value="' . $instructor->id . '">' . ucwords($instructor->surname.' '.$instructor->firstname) . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                        <?php endfor; ?>
                    <?php } ?>
                    
                </tbody>
                <tfoot>
                    <tr><td colspan="7">
                        <!-- Button to add more weeks -->
                        <button type="button" id="add-week" class="btn btn-primary btn-block my-2">Add More Week</button>

                    </td></tr>
                </tfoot>
            </table>

            <div class="col-sm-12 text-center">
                <button class="btn btn-success text-uppercase" type="submit">
                    <em class="icon ni ni-save"></em> <span><?=('Submit');?></span>
                </button>
            </div>
        
        </div>
    <?php } ?>
    <?php if($param2 == 'admin_send') { ?>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b><?=translate_phrase('Are you sure?');?></b></h3>
                <input type="hidden" name="admin_id" value="<?php if(!empty($param3)){echo $param3;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-success text-uppercase" type="submit">
                    <em class="icon ni ni-share-alt"></em> <span><?=('Yes - Send Login Details');?></span>
                </button>
            </div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
<script>
    $(function() {
        $('.js-select2').select2();
    });
    $(document).ready(function() {
    // Week count initialization for dynamic week addition
    let weekCount = 7;

    // Function to add a new row for additional week
    $('#add-week').on('click', function() {
        weekCount++; // Increment the week number
        let newRow = `
            <tr>
                <td>
                    <input type="text" name="weeks[]" value="Week ${weekCount}" class="form-control" readonly>
                </td>
                <td>
                    <select name="courses[]" class="form-control course-select" required>
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course->id; ?>"><?php echo $course->name; ?></option>
                        <?php endforeach; ?>
                        <option value="new">+ Add New Course</option>
                    </select>
                     <!-- New course input field will be shown here -->
                    <input type="text" name="new_courses[]" class="form-control new-course mt-2" style="display:none;" placeholder="Enter new course name">
                
                </td>
                <td>
                    <select name="instructors[]" class="form-control" required>
                        <option value="">-- Select Instructor --</option>
                        <?php foreach ($instructors as $instructor): ?>
                            <option value="<?php echo $instructor->id; ?>"><?php echo ucwords($instructor->surname.' '.$instructor->firstname); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                </td>
            </tr>
        `;
        $('#instructor-body').append(newRow);
    });

    // Function to remove a row
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });

    // Handle new course addition
    $(document).on('change', '.course-select', function() {
        if ($(this).val() == 'new') {
            $(this).siblings('.new-course').show(500); // Show the new course input field
        } else {
            $(this).siblings('.new-course').hide(500); // Hide the new course input field
        }
    });
});
</script>