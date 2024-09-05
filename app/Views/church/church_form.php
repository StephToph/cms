
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
                <h3><b><?=translate_phrase('Are you sure?');?></b></h3>
                <input type="hidden" name="d_dept_id" value="<?php if(!empty($d_id)){echo $d_id;} ?>" />
            </div>
            
            <div class="col-sm-12 text-center">
                <button class="btn btn-danger text-uppercase" type="submit">
                    <i class="icon ni ni-trash"></i> <?=translate_phrase('Yes - Delete');?>
                </button>
            </div>
        </div>
    <?php } ?>

    <!-- insert/edit view -->
    <?php if($param2 == 'edit' || $param2 == '') { ?>
        
        
        <div class="row">
            <input type="hidden" name="dept_id" value="<?php if(!empty($e_id)){echo $e_id;} ?>" />
            <div class="col-sm-12 mb-3">
                <div class="form-group"><b>Ministry Logo</b><br>
                    <label for="img-upload" class="pointer text-center" style="width:100%;">
                        <input type="hidden" name="img" value="<?php if(!empty($e_img)){echo $e_img;} ?>" />
                        <img id="img" src="<?php if(!empty($e_img)){echo site_url( $e_img);} ?>" style="max-width:100%;" />
                        <span class="btn btn-info btn-block no-mrg-btm">Choose Image</span>
                        <input class="d-none" type="file" name="pics" id="img-upload" accept="image/*">
                    </label>
                </div>
            </div>
            
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Name'); ?></label>
                    <input class="form-control" type="text" id="name" name="name" value="<?php if(!empty($e_name)) {echo $e_name;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Email'); ?></label>
                    <input class="form-control" type="email" id="email" name="email" value="<?php if(!empty($e_email)) {echo $e_email;} ?>" required>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Phone'); ?></label>
                    <input class="form-control" type="text" id="phone" name="phone" value="<?php if(!empty($e_phone)) {echo $e_phone;} ?>">
                </div>
            </div>

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?=translate_phrase('Address'); ?></label>
                    <input class="form-control" type="text" id="address" name="address" value="<?php if(!empty($e_address)) {echo $e_address;} ?>" required>
                </div>
            </div>
            <?php if ($role == 'developer' || $role == 'administrator') { ?>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="ministry_id">*<?= translate_phrase('Ministry'); ?></label>
                    <select id="ministry_id" name="ministry_id" class="js-select2 ministry_select">
                        <option value="">Select</option>
                        <?php
                            $ministries = $this->Crud->read_order('ministry', 'name', 'asc');
                            foreach ($ministries as $ministry) {
                                $selected = '';
                                if(!empty($e_ministry_id))$selected = ($e_ministry_id == $ministry->id) ? 'selected' : '';
                                echo '<option value="' . $ministry->id . '" ' . $selected . '>' . ucwords($ministry->name) . '</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="regional_id">*<?= translate_phrase('Region'); ?></label>
                    <select class="js-select2 regional_select" name="regional_id" id="regional_id">
                        <option value="">Select</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="zonal_id">*<?= translate_phrase('Zone'); ?></label>
                    <select class="js-select2 zonal_select" name="zonal_id" id="zonal_id">
                        <option value="">Select</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="zonal_id">*<?= translate_phrase('Group'); ?></label>
                    <select class="js-select2 zonal_select" name="group_id" id="group_id">
                        <option value="">Select</option>
                    </select>
                </div>
            </div>
        <?php } else { ?>
            <input type="hidden" name="ministry_id" value="<?= $this->Crud->read_field('id', $log_id, 'user', 'ministry_id'); ?>">
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="regional_id">*<?= translate_phrase('Region'); ?></label>
                    <select class="js-select2 regional_select" name="regional_id" id="regional_id">
                        <option value="">Select Region</option>
                        <?php
                            $ministryId = $this->Crud->read_field('id', $log_id, 'user', 'ministry_id');
                            $regions = $this->Crud->read2_order('ministry_id', $ministryId, 'type', 'region', 'church', 'name', 'asc');
                            foreach ($regions as $region) {
                                $selected = ($e_regional_id == $region->id) ? 'selected' : '';
                                echo '<option value="' . $region->id . '" ' . $selected . '>' . ucwords($region->name) . '</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="zonal_id">*<?= translate_phrase('Zone'); ?></label>
                    <select class="js-select2 zonal_select" name="zonal_id" id="zonal_id">
                        <option value="">Select</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="zonal_id">*<?= translate_phrase('Group'); ?></label>
                    <select class="js-select2 zonal_select" name="group_id" id="group_id">
                        <option value="">Select</option>
                    </select>
                </div>
            </div>
        <?php } ?>

           
        </div>
        

        <div class="row" >
            
            <div class="col-sm-12 text-center mt-3">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?=translate_phrase('Save Record');?>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12"><div id="bb_ajax_msg"></div></div>
        </div>

    <?php } ?>
<?php echo form_close(); ?>
<script>
    $('.js-select2').select2();
    function readURL(input, id) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#' + id).attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#img-upload").change(function(){
		readURL(this, 'img');
	});

    $(document).ready(function() {
        var eRegionId = <?php if(!empty($e_regional_id)){?> '<?=$e_regional_id?>'<?php } else {?> ''<?php }?>;
        var eZonalId = <?php if(!empty($e_zonal_id)){?> '<?=$e_zonal_id?>'<?php } else {?> ''<?php }?>;
        var eGroupId = <?php if(!empty($e_group_id)){?> '<?=$e_group_id?>'<?php } else {?> ''<?php }?>;
        
        if (eRegionId !== '') {
            var ministryId = $('#ministry_id').val();
            
            if (ministryId !== '') {
                $.ajax({
                    type: 'POST',
                    url: '<?=site_url('church/get_region')?>', // replace with your controller and function
                    data: {ministry_id: ministryId},
                    dataType: 'json',
                    success: function(data) {
                        $('#regional_id').empty();
                        if (data.length === 0) {
                            $('#regional_id').append('<option value="">No regions found</option>'); // display a message if no regions are found
                        } else {
                            $('#regional_id').append('<option value="">Select Region</option>'); 
                            $.each(data, function(index, region) {
                                var selected = '';
                                if (region.id === eRegionId) {
                                    selected = 'selected';
                                }
                                $('#regional_id').append('<option value="' + region.id + '" ' + selected + '>' + region.name + '</option>');
                            });
                        }
                    }
                });
            }
        }

        if (eZonalId !== '' && eRegionId !== '') {
            $.ajax({
                type: 'POST',
                url: '<?=site_url('church/get_zone')?>', // replace with your controller and function
                data: {regional_id: eRegionId},
                dataType: 'json',
                success: function(data) {
                    $('#zonal_id').empty();
                    if (data.length === 0) {
                        $('#zonal_id').append('<option value="">No Zone found</option>'); // display a message if no regions are found
                    } else {
                        $('#zonal_id').append('<option value="">Select Zone</option>'); 
                        $.each(data, function(index, region) {
                            var selected = '';
                            if (region.id === eZonalId) {
                                selected = 'selected';
                            }
                            $('#zonal_id').append('<option value="' + region.id + '" ' + selected + '>' + region.name + '</option>');
                        });
                    }
                }
            });
        
        }

        if (eZonalId !== '' && eGroupId !== '') {
            $.ajax({
                type: 'POST',
                url: '<?=site_url('church/get_group')?>', // replace with your controller and function
                data: {zonal_id: eZonalId},
                dataType: 'json',
                success: function(data) {
                    $('#group_id').empty();
                    if (data.length === 0) {
                        $('#group_id').append('<option value="">No Group found</option>'); // display a message if no regions are found
                    } else {
                        $.each(data, function(index, region) {
                            var selected = '';
                            if (region.id === eGroupId) {
                                selected = 'selected';
                            }
                            $('#group_id').append('<option value="' + region.id + '" ' + selected + '>' + region.name + '</option>');
                        });
                    }
                }
            });
        
        }

        $('#ministry_id').on('change', function() {
            var ministryId = $(this).val();
            if (ministryId === '') {
                $('#regional_id').empty();
                $('#regional_id').append('<option value="">Select</option>'); // reset to default option
            } else {
                $.ajax({
                    type: 'POST',
                    url: '<?=site_url('church/get_region')?>', // replace with your controller and function
                    data: {ministry_id: ministryId},
                    dataType: 'json',
                    success: function(data) {
                        $('#regional_id').empty();
                        if (data.length === 0) {
                            $('#regional_id').append('<option value="">No regions found</option>'); // display a message if no regions are found
                        } else {
                            $('#regional_id').append('<option value="">Select Region</option>'); 
                            $.each(data, function(index, region) {
                                $('#regional_id').append('<option value="' + region.id + '">' + region.name + '</option>');
                            });
                        }
                    }
                });
            }
            
        });

        $('#regional_id').on('change', function() {
            var regionalId = $(this).val();
            if (regionalId === '') {
                $('#zonal_id').empty();
                $('#zonal_id').append('<option value="">Select</option>'); // reset to default option
            } else {
                $.ajax({
                    type: 'POST',
                    url: '<?=site_url('church/get_zone')?>', // replace with your controller and function
                    data: {regional_id: regionalId},
                    dataType: 'json',
                    success: function(data) {
                        $('#zonal_id').empty();
                        if (data.length === 0) {
                            $('#zonal_id').append('<option value="">No Zones found</option>'); // display a message if no regions are found
                        } else {
                            $('#zonal_id').append('<option value="">Select Zone</option>');
                            $.each(data, function(index, region) {
                                $('#zonal_id').append('<option value="' + region.id + '">' + region.name + '</option>');
                            });
                        }
                    }
                });
            }
            
        });
        
        $('#zonal_id').on('change', function() {
            var zonalId = $(this).val();
            if (zonalId === '') {
                $('#group_id').empty();
                $('#group_id').append('<option value="">Select</option>'); // reset to default option
            } else {
                $.ajax({
                    type: 'POST',
                    url: '<?=site_url('church/get_group')?>', // replace with your controller and function
                    data: {zonal_id: zonalId},
                    dataType: 'json',
                    success: function(data) {
                        $('#group_id').empty();
                        if (data.length === 0) {
                            $('#group_id').append('<option value="">No Group Church found</option>'); // display a message if no regions are found
                        } else {
                            $.each(data, function(index, region) {
                                $('#group_id').append('<option value="' + region.id + '">' + region.name + '</option>');
                            });
                        }
                    }
                });
            }
            
        });
    
    });

</script>

<script src="<?php echo site_url(); ?>assets/js/jsform.js"></script>
