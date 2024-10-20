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
    <?php if ($param2 == 'edit' || $param2 == '') {
        $ministry_id = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
        $church_id = $this->Crud->read_field('id', $log_id, 'user', 'church_id');
        $foundation_id = $this->session->get('foundation_id');
    ?>

    <div class="row">
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) { echo $e_id; } ?>" />
        <input type="hidden" id="foundation_id" name="foundation_id" value="<?php if (!empty($foundation_id)) { echo $foundation_id; } ?>" />
        
        <div class="container table-responsive">
            <h5>Enroll Students for Foundation School</h5>

            <table class="table table-hover" id="student_table">
                <thead>
                    <tr>
                        <th>Prospectic Student</th>
                        <th>Church</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="student_body">
                   
                    
                </tbody>
                <tfoot id="student_more">
                    
                </tfoot>
            </table>
        
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
        load_student();
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
        //alert(status);

        $.ajax({
            url: site_url + 'foundation/students/load_prospective' + methods,
            type: 'post',
            data: { foundation_id: foundation_id },
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
            console.log('test');
            // Send AJAX request to update enrollment status
            $.ajax({
                url: '<?= site_url("foundation/students/enroll_student") ?>',  // Replace with your actual controller method
                type: 'POST',
                data: {
                    id: studentId,
                    source: source,
                    enroll: isEnrolled
                }
                
            });
        });
    });

</script>