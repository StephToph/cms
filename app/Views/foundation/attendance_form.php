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
    <?php if ($param2 == 'mark') {
        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
        $foundation_id = $this->session->get('foundation_id');
        $class_no = $param4;
        if(empty($param4)){
            $class_no = 1;
        }
        $date_held = $this->Crud->read_field3('foundation_id', $foundation_id, 'week', $param3, 'class_no', $class_no, 'foundation_attendance', 'date_held');
        
    ?>

        <div class="row">
            <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) { echo $e_id; } ?>" />
            <input type="hidden" id="foundation_id" name="foundation_id" value="<?php if (!empty($foundation_id)) { echo $foundation_id; } ?>" />
            
            <div class="container table-responsive">
                <h5></h5>
                <div class="row">
                    <div class="col-sm-3 my-2">
                        <div class="form-group">
                            <label>Week</label>
                            <input type="text"  name="week" id="week" class="form-control" value="<?php if (!empty($param3)) { echo $param3;  } ?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4 my-2">
                        <div class="form-group">
                            <label>Number of Class (in a Week)</label>
                            <input type="text" name="class_no" id="class_no"
                                class="form-control" value="<?php if (!empty($param4)) { echo $param4;  } else{echo 1;} ?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-5 my-2">
                        <div class="form-group">
                            <label>Date Held</label>
                            <input type="text" data-date-format="yyyy-mm-dd" name="date_held" id="date_held"
                                class="form-control date-picker" value="<?php if (!empty($date_held)) {
                                    echo date('Y-m-d', strtotime($date_held));
                                } ?>">
                            <span class="small text-danger">Select date the Class was Held</span>
                        </div>
                        
                    </div>
                </div>
                <table class="table table-hover" id="student_table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Church</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="student_body">
                    <tr>
                            <td colspan="7"><h4 class="text-danger text-center">Select Date Held First</h4></td>
                    </tr>
                        
                    </tbody>
                    <tfoot id="student_more">
                        
                    </tfoot>
                </table>
                        
                <div class="col-sm-12 text-center my-2">
                    <hr />
                    <button class="btn btn-primary bb_for_btn" id="ajaxBtn" type="submit">
                        <i class="icon ni ni-save"></i> Submit
                    </button>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-12 my-3">
                <div id="bb_ajax_msg"></div>
            </div>
        </div>

    <?php } ?>

    <?php if ($param2 == 'view') {
        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
        $foundation_id = $this->session->get('foundation_id');
        $class_no = $param4;
        if(empty($param4)){
            $class_no = 1;
        }
        $date_held = $this->Crud->read_field3('foundation_id', $foundation_id, 'week', $param3, 'class_no', $class_no, 'foundation_attendance', 'date_held');
        
    ?>

        <div class="row">
            <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) { echo $e_id; } ?>" />
            <input type="hidden" id="foundation_id" name="foundation_id" value="<?php if (!empty($foundation_id)) { echo $foundation_id; } ?>" />
            
            <div class="container table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Church</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = $this->Crud->read3('foundation_id', $foundation_id, 'week', $param3, 'class_no', $class_no, 'foundation_attendance');
                            if(!empty($query)){
                                foreach($query as $q){
                                    $user_id = $q->user_id;
                                    $user_type = $q->user_type;
                                    $status = $q->status;
                                    $stat = '<span class="text-danger">Absent</span>';
                                    if($status > 0){
                                        $stat = '<span class="text-success">Present</span>';
                                    }
                                    if ($user_type == 'visitor') {
                                        $name = $this->Crud->read_field('id', $user_id, 'visitors', 'fullname');
                                        $church_id = $this->Crud->read_field('id', $user_id, 'visitors', 'church_id');
                                    }
                                    if ($user_type == 'member') {
                                        $name = $this->Crud->read_field('id', $user_id, 'user', 'firstname') . ' ' . $this->Crud->read_field('id', $user_id, 'user', 'surname');
                                        $church_id = $this->Crud->read_field('id', $user_id, 'user', 'church_id');
                                    }
                                
                                    $church = $this->Crud->read_field('id', $church_id, 'church', 'name');
					
                                    echo '
                                        <tr>
                                            <td>' . ucwords($name) . '<br><span class="text-dark small">' . ucwords($user_type) . '</span></td>
								            <td>' . ucwords($church) . '</td>
                                            <td>'.$stat.'</td>


                                        </tr>
                                    ';

                                }
                            } else{
                                echo '<tr><td colspan>No Attendance</td></tr>';
                            }
				        ?>
                        
                    </tbody>
                    
                </table>
                        
                <div class="col-sm-12 text-center my-2">
                    <hr />
                    <button class="btn btn-primary bb_for_btn" id="ajaxBtn" type="submit">
                        <i class="icon ni ni-save"></i> Submit
                    </button>
                </div>
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
        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd', // Set the date format
            autoclose: true
        });
    });

    function load_student(x, y) {
        var more = 'no';
        var methods = '';
        if (parseInt(x) > 0 && parseInt(y) > 0) {
            more = 'yes';
            methods = '/' + x + '/' + y;
        }

        if (more == 'no') {
            $('#student_body').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        } else {
            $('#student_more').html('<tr><td colspan="8"><div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></td></tr>');
        }


        var foundation_id = $('#foundation_id').val();
        var week = $('#week').val();
        var class_no = $('#class_no').val();
        //alert(status);

        $.ajax({
            url: site_url + 'foundation/attendance/load_student' + methods,
            type: 'post',
            data: { foundation_id: foundation_id, week:week,class_no:class_no },
            success: function (data) {
                var dt = JSON.parse(data);
                if (more == 'no') {
                    $('#student_body').html(dt.item);
                } else {
                    $('#student_body').append(dt.item);
                }
                $('#admin_counta').html(dt.count);
                if (dt.offset > 0) {
                    $('#student_more').html('<tr><td colspan="8"><a href="javascript:;" class="btn btn-dim btn-light btn-block p-30" onclick="load_student(' + dt.limit + ', ' + dt.offset + ');"><em class="icon ni ni-redo fa-spin"></em> Load ' + dt.left + ' More</a></td></tr>');
                } else {
                    $('#student_more').html('');
                }
            }
        });
    }

    
    $(document).ready(function() {
        $(document).on('change', '[id^=customSwitch]', function() {
            var studentId = $(this).data('id');
            var source = $(this).data('source');
            var isEnrolled = $(this).is(':checked') ? 1 : 0;  // 1 if checked, 0 if unchecked  
            var foundation_id = $('#foundation_id').val();
            var week = $('#week').val();
            var class_no = $('#class_no').val();
            var dateHeld = $('#date_held').val();

            // Send AJAX request to update enrollment status
            $.ajax({
                url: '<?= site_url("foundation/attendance/mark") ?>',  // Replace with your actual controller method
                type: 'POST',
                data: {
                    id: studentId,
                    source: source,
                    enroll: isEnrolled,
                    foundation_id: foundation_id, week:week,class_no:class_no,date_held:dateHeld
                }
                
            });
        });
    });

    $(document).ready(function() {
        // Trigger function on page load (for edit page)
        checkDateAndLoadStudents();

        // Trigger function when date is changed
        $('#date_held').on('change', function() {
            checkDateAndLoadStudents();
        });

        // Function to check if date is not empty and load students
        function checkDateAndLoadStudents() {
            var dateHeld = $('#date_held').val();  // Get the value of the date field

            if (dateHeld.trim() === "") {
                // If date is empty, show a warning
               $('#student_body').html(' <tr>  <td colspan="7"><h4 class="text-danger text-center">Select Date Held First</h4></td> </tr>');
            } else {
                // If date is not empty, proceed to load the students
                load_student();
            }
        }

        $('#ajaxBtn').click(function() {
            var foundation_id = $('#foundation_id').val();
            $('#modal').modal('hide');
            load_attendance('','', foundation_id);
        });
    });

</script>