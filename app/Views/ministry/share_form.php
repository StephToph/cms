<?php
use App\Models\Crud;

$this->Crud = new Crud();
?>
<?php echo form_open_multipart($form_link, array('id' => 'bb_ajax_form', 'class' => '')); ?>
<!-- delete view -->
<?php if ($param3 == 'share_link') { ?>
    <div class="row">
        <div class="col-sm-12">
            <div id="bb_ajax_msg"></div>
        </div>
    </div>

    <input type="hidden" name="form_id" value="<?php if (!empty($param2)) {
            echo $param2;
        } ?>" />
    <div class="row">
        <div class="col-sm-12 text-center">
            <h2>Share Form Link</h2>

            <?php
            $link = site_url('ministry/forms/'.$e_unique_link); // Example form link, replace with your dynamic link
            ?>
            <input class="form-control" type="text" id="linkInput" value="<?= $link ?>" readonly>

            <!-- Copy Link Button -->
            <button type="button" class="btn btn-light share-btn btn-copy my-2" id="copyButton">Copy Link</button>
            <div id="message" style="display:none;">Link Copied</div>

            <!-- Share on Social Media -->
            <div>
                <button type="button" class="btn btn-primary btn-icon my-2" onclick="shareOnFacebook('<?= $link ?>')">
                    <em class="icon ni ni-facebook-f"></em>
                </button>
                <button type="button" class="btn btn-info btn-icon share-btn btn-twitter my-2" onclick="shareOnTwitter('<?= $link ?>')">
                    <em class="icon ni ni-twitter"></em>
                </button>
                <button type="button" class="btn btn-success btn-icon share-btn btn-whatsapp my-2" onclick="shareOnWhatsApp('<?= $link ?>')">
                    <em class="icon ni ni-whatsapp"></em>
                </button>
                <button type="button" class="btn btn-info btn-icon share-btn btn-telegram my-2" onclick="shareOnTelegram('<?= $link ?>')">
                    <em class="icon ni ni-telegram"></em>
                </button>
                <button type="button" class="btn btn-warning btn-icon share-btn btn-snapchat my-2" onclick="shareOnSnapchat('<?= $link ?>')">
                    <em class="icon ni ni-snapchat"></em>
                </button>
            </div>

        </div>

        
    </div>
<?php } ?>

<?php if ($param3 == 'view') { ?>
    <div class="row">
        <?php 
            $e_title = $this->Crud->read_field('id', $param2, 'form', 'name');
            $e_form_fields = json_decode($this->Crud->read_field('id', $param2, 'form', 'fields'));
        ?>
        <div class="col-sm-12 mb-3 table-responsive">
            <h5 class="text-center text-info"><?= ucwords($e_title); ?></h5>
            <table class="table table-hover">
                <tr>
                    <td colspan="5" class=" text-cente"><b class="text-danger">Form Fields</b></td>
                </tr>
                <?php 
                    
                    if(!empty($e_form_fields)){
                        foreach($e_form_fields as $f => $field){
                            $type = str_replace('_', ' ',  $field->type);
                            $opts = '';
                            if($field->type == 'single_choice' || $field->type == 'multiple_choice'){
                                $opt = '';
                                $options = $field->options;
                                foreach($options as $op => $option){
                                    $opt .= $option.', ';
                                }
                                
                                $optaa = rtrim($opt, ', ');
                                $opta = '<span class="text-info">{'.ucwords($opt).'}</span>';
                            } else{
                                $opta = '';
                            }
                            $opts = $opta;
                            ?>
                            <tr>
                                <td colspan="2"><b><?=ucwords($field->label);?></b></td>
                                <td colspan="3"><?=ucwords($type).' '.$opts;?></td>
                            </tr>
                        <?php }
                    }
                     
                    if(!empty($e_fields)){
                        echo '
                             <tr>
                                <td colspan="5" class=" text-cente"><b class="text-danger">Form Extension Fields</b></td>
                            </tr>
                        ';
                        foreach($e_fields as $f => $field){
                            $type = str_replace('_', ' ',  $field->type);
                            $opts = '';
                            if($field->type == 'single_choice' || $field->type == 'multiple_choice'){
                                $opt = '';
                                $options = $field->options;
                                foreach($options as $op => $option){
                                    $opt .= $option.', ';
                                }
                                
                                $optaa = rtrim($opt, ', ');
                                $opta = '<span class="text-info">{'.ucwords($opt).'}</span>';
                            } else{
                                $opta = '';
                            }
                            $opts = $opta;
                            ?>
                            <tr>
                                <td colspan="2"><b><?=ucwords($field->label);?></b></td>
                                <td colspan="3"><?=ucwords($type).' '.$opts;?></td>
                            </tr>
                        <?php }
                    }
                    
                ?>

            </table>
        </div>

    </div>

<?php } ?>


<!-- insert/edit view -->
<?php if ($param3 == 'edit' || $param3 == '') { ?>
    <div class="row">
        <input type="hidden" name="e_id" value="<?php if (!empty($e_id)) {
            echo $e_id;
        } ?>" />
        <input type="hidden" name="form_id" value="<?php if (!empty($param2)) {
            echo $param2;
        } ?>" />

        <div class="row">
            <div class="col-sm-12 text-center">
                <h3><b>Generate your shareable Link for this Form?</b></h3>
            </div>
            <div class="col-sm-12 text-center my-3">
                <button class="btn btn-primary text-uppercase" type="submit">
                    <i class="icon ni ni-shield-star"></i> Yes - Generate
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

    $('#copyButton').click(function() {
        // Select the input field with the link
        var linkInput = $('#linkInput');
        
        // Select the text in the input
        linkInput.select();
        linkInput[0].setSelectionRange(0, 99999); // For mobile devices
        
        // Copy the text to the clipboard
        document.execCommand('copy');
        
        // Show the confirmation message
        $('#message').fadeIn();
        
        // Hide the message after 3 seconds
        setTimeout(function() {
            $('#message').fadeOut();
        }, 3000);
    });

    function shareOnFacebook(link) {
        const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(link)}`;
        window.open(facebookUrl, '_blank');
    }

    function shareOnTwitter(link) {
        const twitterUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(link)}`;
        window.open(twitterUrl, '_blank');
    }

    function shareOnWhatsApp(link) {
        const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(link)}`;
        window.open(whatsappUrl, '_blank');
    }

        
    // Share on Telegram
    function shareOnTelegram(link) {
        const telegramUrl = `https://t.me/share/url?url=${encodeURIComponent(link)}`;
        window.open(telegramUrl, '_blank');
    }


    // Share on Snapchat
    function shareOnSnapchat(link) {
        const snapchatUrl = `https://www.snapchat.com/scan?attachmentUrl=${encodeURIComponent(link)}`;
        window.open(snapchatUrl, '_blank');
    }


    $(function () {
        $('#summernote').summernote({
            height: 300, // Set the height of the editor
            tabsize: 2,
            focus: true
        });

        
    });


    var site_url = '<?php echo site_url(); ?>';

    var fieldCounter = 1;
        
    $(document).ready(function() {
        // Initialize a counter for the fields
        let fieldCounter = 1;
        <?php
            $e_field = !empty($e_fields) ? json_encode($e_fields) : '[]';
        ?>
        var eFields = <?php echo $e_field; ?>;
       
         // Initialize fields if editing
        function initializeFields() {
            if (Array.isArray(eFields)) {
                // Determine the highest existing counter in the DOM
                $('.optionsa').each(function() {
                    const existingCounter = $(this).data('counter');
                    if (existingCounter >= fieldCounter) {
                        fieldCounter = existingCounter + 1; // Set fieldCounter to the next available number
                    }
                });

                // Initialize fields based on eFields data
                eFields.forEach((field, index) => {
                    // If the counter is already taken, continue with the next counter
                    if (index + 1 >= fieldCounter) {
                        addField(index + 1, field.label, field.type, field.options);
                        fieldCounter = index + 2; // Update fieldCounter for new fields
                    }
                });
            }
        }


        // Add more field when button is clicked
        $('#add_field').click(function() {
            
            addField(fieldCounter);
            fieldCounter++;
        });

        // Function to add a new field
        function addField(counter, label = '', type = 'text', options = []) {
            const newField = `
            <div class="row card-bordered optionsa my-2 p-2" data-counter="${counter}">
                <h5>Field <span class="field-number">${counter}</span></h5>
                <div class="col-sm-6 mb-3">
                    <label>Field Label</label>
                    <input class="form-control" type="text" id="label_${counter}" name="label[]" value="${label}" required>
                </div>

                <div class="col-sm-6 mb-3">
                    <div class="form-group">
                        <label>Field Type</label>
                        <select class="form-select" name="type[]" id="type_${counter}" required>
                            <option value="text" ${type === 'text' ? 'selected' : ''}>Text</option>
                            <option value="single_choice" ${type === 'single_choice' ? 'selected' : ''}>Single Choice</option>
                            <option value="multiple_choice" ${type === 'multiple_choice' ? 'selected' : ''}>Multiple Choice</option>
                            <option value="true_false" ${type === 'true_false' ? 'selected' : ''}>True or False</option>
                        </select>
                    </div>
                </div>

                <div id="options_container_${counter}" style="${type === 'single_choice' || type === 'multiple_choice' ? 'display:block;' : 'display:none;'}">
                    ${options.map((option, index) => `
                    <div class="col-sm-12 mb-3 option_resp" id="option_${counter}_${index + 1}">
                        <div class="d-flex align-items-center mb-2">
                            <label class="me-2">Option</label>
                            <input class="form-control" type="text" name="options[${counter}][]" id="option_input_${counter}_${index + 1}" value="${option}">
                            ${index > 0 ? `<button class="btn btn-outline-danger btn-sm ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" id="delete_option_${counter}_${index + 1}" title="Delete Option">
                                <i class="icon ni ni-trash"></i>
                            </button>` : ''}
                        </div>
                    </div>
                    `).join('')}
                </div>

                <div class="col-sm-6 my-2" id="add_more_options_${counter}" style="${type === 'single_choice' || type === 'multiple_choice' ? 'display:block;' : 'display:none;'}">
                    <label class="text-white">.</label>
                    <button class="btn btn-warning btn-dim btn-blck bb_for_btn" id="add_option_${counter}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Options" type="button">
                        <span>Add Options</span><i class="icon ni ni-plus"></i>
                    </button>
                </div>
                <div class="col-sm-12 text-right my-2" id="delete_field_resp_${counter}" style="display:none;">
                    <button class="btn btn-danger btn-bloc delete-field-btn" id="delete_field_${counter}" data-counter="${counter}" type="button">
                        <i class="icon ni ni-trash"></i> <span>Delete Field</span>
                    </button>
                </div>
            </div>`;


            // Append the new field to the container
            $('.row.card-bordered').last().after(newField);

            // Show delete button if more than one field
            if (fieldCounter > 1) {
                $('.row.card-bordered').each(function() {
                    $(this).find('[id^="delete_field_resp_"]').show(500);
                });
            }

        }

        // Delegate the click event for dynamically added delete buttons
        $(document).on('click', '.delete-field-btn', function() {
            const counter = $(this).data('counter');
            $(this).closest('.optionsa').remove();
            resetCounters();
            // Update fieldCounter to match the remaining fields
            fieldCounter = $('.optionsa').length;
            
            // Hide delete button if only one field is left
            if (fieldCounter <= 1) {
                $('.row.card-bordered').find('[id^="delete_field_resp_"]').hide(500);
            }
        });

        function resetCounters() {
            let newCounter = 1;

            $('.optionsa').each(function() {
                $(this).attr('data-counter', newCounter);
                $(this).find('.field-number').text(newCounter);

                $(this).find('[id^="label_"]').attr('id', `label_${newCounter}`);
                $(this).find('[id^="type_"]').attr('id', `type_${newCounter}`);
                $(this).find('[id^="options_container_"]').attr('id', `options_container_${newCounter}`);
                $(this).find('[id^="option_"]').attr('id', `option_${newCounter}`);
                $(this).find('[id^="option_input_"]').attr('id', `option_input_${newCounter}`);
                $(this).find('[id^="add_option_"]').attr('id', `add_option_${newCounter}`);
                $(this).find('[id^="delete_option_"]').attr('id', `delete_option_${newCounter}`);
                $(this).find('[id^="delete_field_resp_"]').attr('id', `delete_field_resp_${newCounter}`);
                $(this).find('[id^="delete_field_"]').attr('id', `delete_field_${newCounter}`);
                $(this).find('.delete-field-btn').attr('data-counter', newCounter);

                newCounter++;
            });
        }

        // Show options container and add_more_options based on field type
        $(document).off('change', '[id^="type_"]').on('change', '[id^="type_"]', function() {
            const counter = $(this).closest('.optionsa').data('counter');
            const type = $(this).val();
            console.log(counter);
            if (type !== 'single_choice' && type !== 'multiple_choice') {
                $(`#options_container_${counter}`).hide(500);
                $(`#option_${counter}`).hide(500);
                $(`#add_more_options_${counter}`).hide(500);
            } else {
                $(`#options_container_${counter}`).show(500);
                $(`#option_${counter}`).show(500);
                $(`#add_more_options_${counter}`).show(500);
            }
        });


        $(document).off('click', '[id^="add_option_"]').on('click', '[id^="add_option_"]', function() {
            const counter = $(this).closest('.optionsa').data('counter');
            const optionsContainer = $(`#options_container_${counter}`);
            const optionCount = optionsContainer.find('.option_resp').length + 1;

            let newOption;
            if (optionCount === 1) {
                // First option, no delete button
                newOption = `
                <div class="col-sm-12 mb-3 option_resp" id="option_${counter}_${optionCount}">
                    <div class="d-flex align-items-center mb-2">
                        <label class="me-2">Option</label>
                        <input class="form-control" type="text" name="options[${counter}][]" id="option_input_${counter}_${optionCount}">
                    </div>
                </div>`;
            } else {
                // Subsequent options, with delete button
                newOption = `
                <div class="col-sm-12 mb-3 option_resp" id="option_${counter}_${optionCount}">
                    <div class="d-flex align-items-center mb-2">
                        <label class="me-2">Option</label>
                        <input class="form-control" type="text" name="options[${counter}][]" id="option_input_${counter}_${optionCount}">
                        <button class="btn btn-outline-danger btn-sm ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" id="delete_option_${counter}_${optionCount}" title="Delete Option">
                            <i class="icon ni ni-trash"></i>
                        </button>
                    </div>
                </div>`;
            }

            // Append the new option to the options container
            optionsContainer.append(newOption);
        });

        // Delegate the click event for dynamically added delete option buttons
        $(document).on('click', '[id^="delete_option_"]', function() {
            $(this).closest('.option_resp').remove();
        });


        // Initialize fields if editing
        initializeFields();
        $('#modal').on('hide.bs.modal', function () {
            resetCounters();
            $('.options-container').empty();
        });
    });
</script>
