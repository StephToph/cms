<?php
use App\Models\Crud;
$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id'=>'bb_ajax_form', 'class'=>'')); ?>
    <!-- delete view -->
    <?php if($param2 == 'personal') { ?>
       
        <div class="row">
            <!-- Profile Picture -->
            <div class="mb-3 col-md-4">
                <label for="img-upload" class="pointer text-center w-100">
                    <input type="hidden" name="img_id" value="<?= !empty($img_id) ? $img_id : ''; ?>" />
                    <img id="img0" src="<?= !empty($img_id) && file_exists($img_id) ? site_url($img_id) : site_url('assets/images/avatar.png'); ?>" style="max-width: 100%;" />
                    <span class="btn btn-secondary d-grid mt-2"><i class="icon ni ni-cloud-upload me-1"></i> <?= translate_phrase('Choose Image'); ?></span>
                    <input type="file" name="pics" id="img-upload" class="d-none" accept="image/*">

                </label>
            </div>

            <!-- Personal Info -->
            <div class="col-md-8">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label"><?= translate_phrase('Membership ID'); ?></label>
                        <input type="text" name="membership_id" class="form-control" value="<?= $user_no ?? ''; ?>" readonly>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label class="form-label"><?= translate_phrase('Surname'); ?></label>
                        <input type="text" name="surname" class="form-control" value="<?= $surname ?? ''; ?>" required>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label"><?= translate_phrase('First Name'); ?></label>
                        <input type="text" name="firstname" class="form-control" value="<?= $firstname ?? ''; ?>" required>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label"><?= translate_phrase('Other Names'); ?></label>
                        <input type="text" name="othernames" class="form-control" value="<?= $othername ?? ''; ?>">
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label"><?= translate_phrase('Email'); ?></label>
                        <input type="email" name="email" class="form-control" value="<?= $email ?? ''; ?>" readonly>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label class="form-label"><?= translate_phrase('Phone'); ?></label>
                        <input type="text" name="phone" class="form-control" value="<?= $phone ?? ''; ?>" readonly>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label class="form-label"><?= translate_phrase('Chat Handle'); ?></label>
                        <input type="text" name="chat_handle" class="form-control" value="<?= $chat_handle ?? ''; ?>">
                    </div>
                    <div class="mb-3 col-md-12">
                        <label class="form-label"><?= translate_phrase('Address'); ?></label>
                        <input type="text" name="address" class="form-control" value="<?= $address ?? ''; ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="row">
            <div class="col-md-12 my-4 text-center">
                <button type="submit" class="btn btn-primary bb_form_bn"><?= translate_phrase('Update Profile'); ?></button>
            </div>
            <div id="bb_ajax_msg"></div>

        </div>
    <?php } ?>

    
    <?php if($param2 == 'church') { ?>
        
        <div class="row">
            <input type="hidden" class="form-control" id="church_id" name="church_id" value="<?=$church_id;?>">
            <div class="mb-2 col-md-6">
                <label for="phone" class="form-label"><?=translate_phrase('Name'); ?></label>
                <input type="text" class="form-control" id="church_name" name="church_name" required placeholder="<?=translate_phrase('Church Name'); ?>" value="<?=$church_name;?>">
            </div>
            <div class="mb-2 col-md-6">
                <label for="phone" class="form-label"><?=translate_phrase('Email'); ?></label>
                <input type="email" class="form-control" id="church_email" name="church_email" placeholder="" value="<?=$church_email;?>" required>
            </div>
            <div class="mb-2 col-md-6">
                <label for="phone" class="form-label"><?=translate_phrase('Phone'); ?></label>
                <input type="text" class="form-control" id="church_phone" name="church_phone" placeholder="<?=translate_phrase('Address'); ?>" value="<?=$church_phone;?>">
            </div>
            <div class="mb-2 col-md-6">
                <label for="phone" class="form-label"><?=translate_phrase('Country'); ?></label>
                <select class="js-select2" data-search="on" name="country_id" id="country_id">
                    <option value="">Select Country</option>
                    <?php

                    $ministry = $this->Crud->read_order('country', 'name', 'asc');
                    if (!empty($ministry)) {
                        foreach ($ministry as $d) {
                            $sel = '';
                            if(!empty($church_country)){
                                if($church_country == $d->id){
                                    $sel = 'selected';
                                }
                            }
                            echo '<option value="' . $d->id . '" ' . $sel . '>' . ucwords($d->name) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-2 col-md-12">
                <label for="phone" class="form-label"><?=translate_phrase('Address'); ?></label>
                <input type="text" class="form-control" id="church_address" name="church_address" value="<?=$church_address;?>" placeholder="Address">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 my-3" align="center">
                <button type="submit" class="btn btn-primary bb_form_bn"><?=translate_phrase('Update Profile'); ?></button>
            </div>
            <div id="bb_ajax_msg"></div>
        </div>
    <?php } ?>
    
    
    <?php if ($param2 == 'cell') { ?>
        <div class="row">
            <div class="col-sm-12">
                <div id="bb_ajax_msg"></div>
            </div>
        </div>


        <div class="row">
            <input type="hidden" name="cell_id" value="<?php if (!empty($e_id)) {
                echo $e_id;
            } ?>" />
           

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?= translate_phrase('Name'); ?></label>
                    <input class="form-control" type="text" id="name" name="name"
                        value="<?php if (!empty($cell)) {
                            echo $cell;
                        } ?>" required>
                </div>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?= translate_phrase('Location'); ?></label>
                    <input class="form-control" type="text" id="location" name="location"
                        value="<?php if (!empty($cell_location)) {
                            echo $cell_location;
                        } ?>" required>
                </div>
            </div>

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="name">*<?= translate_phrase('Phone'); ?></label>
                    <input class="form-control" type="text" id="phone" name="phone"
                        value="<?php if (!empty($cell_phone)) {
                            echo $cell_phone;
                        } ?>">
                </div>
            </div>

        </div>
        <div id="containers">
        <?php
            if (!empty($cell_time)) {
                // Decode JSON to associative array
                $cell_time_data = is_array($cell_time) ? $cell_time : json_decode($cell_time, true);

                if (!empty($cell_time_data) && is_array($cell_time_data)) {
                    $a = 0;
                    foreach ($cell_time_data as $k => $val) {
                        $r_val = ($a === 0) ? 'style="display:none;"' : '';
                        $req = ($a === 0) ? 'required' : '';
                        ?>
                        <div class="row">
                            <div class="col-sm-4 mb-3">
                                <div class="form-group">
                                    <label for="day">*<?= translate_phrase('Meeting Day'); ?></label>
                                    <select class="form-control" name="days[]" <?= $req; ?>>
                                        <option value="">Select</option>
                                        <?php
                                        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                                        foreach ($days as $day): ?>
                                            <option value="<?= $day; ?>" <?= ($k == $day) ? 'selected' : ''; ?>><?= $day; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-8 mb-3">
                                <label for="time">*<?= translate_phrase('Meeting Time'); ?></label>
                                <div class="form-group input-group">
                                    <input class="form-control" type="time" name="times[]" value="<?= esc($val); ?>" <?= $req; ?>>
                                    <button <?= $r_val; ?> class="btn btn-icon btn-outline-danger deleteBtns" type="button">
                                        <i class="icon ni ni-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                        $a++;
                    }
                }
            } else { ?>
            <div class="row">
                <div class="col-sm-4 mb-3">
                    <div class="form-group">
                        <label for="name">*<?= translate_phrase('Meeting Day'); ?></label>
                        <select class="form-control" name="days[]" required>
                            <option value="">Select</option>
                            <option value="Sunday">Sunday</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-8 mb-3">
                    <label for="name">*<?= translate_phrase('Meeting Time'); ?></label>
                    <div class="form-group input-group">
                        <input class="form-control" type="time" id="location" name="times[]" required>
                        <button style="display:none;" class="btn btn-icon btn-outline-danger deleteBtns" type="button"><i
                                class="icon ni ni-trash"></i> </button>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
        <div class="col-sm-12 mb-3 text-center">
            <button id="addMores" class="btn btn-ico btn-outline-info" type="button"><i class="icon ni ni-plus-c"></i>
                <span><?= translate_phrase('Add More Days'); ?></span></button>
        </div>


        <div class="row">

            <div class="col-sm-12 text-center mt-3">
                <button class="btn btn-primary bb_fo_btn" type="submit">
                    <i class="icon ni ni-save"></i> <?= translate_phrase('Save Record'); ?>
                </button>
            </div>
        </div>
    <?php } ?>
<?php echo form_close(); ?>
    <script src="<?=site_url();?>assets/js/jsform.js"></script>
    <script>
        var site_url = '<?=site_url();?>';
        $('.select2').select2();

        <?php if($param2 == 'personal'){ ?>
            $(function() {
                get_state();
            });

            
        <?php } ?>
        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    if(id != 'vid') {
                        $('#' + id).attr('src', e.target.result);
                    } else {
                        $('#' + id).show(500);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        $("#img-upload").change(function(){
            readURL(this, 'img0');
        });

       

        function validate_account(){
            var bank = $('#bank').val();
            var account = $('#account').val();

            if(bank !== '' && account.length == 10){
                $('#account_resp').html('<div class="spinner-border" role="status">  <span class="visually-hidden">Loading...</span></div>');
                $.ajax({
                    url: site_url + 'auth/validate_account/' + account + '/'+ bank,
                    success: function(data) {
                        $('#account_resp').html(data);
                        $('#btn').prop('disabled', false);
                    }
                });
            }
           
        }
    </script>