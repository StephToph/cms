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
    <div class="row gy-3 py-1" id="event-content">
        <!-- Event Name -->
        <div class="col-sm-12 mb-3">
            <h5 class="overline-title">Prayer Title</h5>
            <p class="text-dark" id="preview-event-name"><?= ucwords($e_name); ?></p>
        </div>

        <!-- Start Time -->
        <div class="col-sm-6 mb-3">
            <h5 class="overline-title">Start Time</h5>
            <p class="text-dark" id="preview-event-start"><?= date('h:iA', strtotime($start_time)); ?></p>
        </div>

        <!-- End Time -->
        <div class="col-sm-6 mb-3">
            <h5 class="overline-title">End Time</h5>
            <p class="text-dark" id="preview-event-end"><?= date('h:iA', strtotime($end_time)); ?></p>
        </div>

        <div class="col-sm-6 mb-3">
            <h5 class="overline-title">Time Zone</h5>
            <p class="text-dark" id="preview-event-reminder">
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
            <h5 class="overline-title">Church</h5>
            <p class="text-dark" id="preview-event-church"><?= ucwords($this->Crud->read_field('id', $church_idz, 'church', 'name')); ?></p>
        </div>

        <!-- Prayer Description -->
        <div class="col-sm-12 mb-3">
            <h5 class="overline-title">Prayer Point</h5>
            <p class="text-dark" id="preview-event-prayer"><?= $prayer; ?></p>
        </div>
    </div>

    <div class="row gy-3 py-1">
        <div class="col-sm-12 text-center">
            <hr />
            <button class="btn btn-primary bb_for_btn" id="bt" onclick="downloadPDF()" type="button">
                DOWNLOAD PRAYER POST
            </button>
        </div>
        <div class="col-sm-12 my-2">
            <div id="bb_ajax_msg"></div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

    <script>
        function downloadImage() {
            const element = document.getElementById('event-content'); // Get the content to capture

            // Use html2canvas to capture the content as an image
            html2canvas(element, { 
                scale: 2,  // Increase the scale for better resolution
                useCORS: true,  // Enable cross-origin resource sharing (if needed)
                backgroundColor: '#fff'  // Set a background color (optional)
            }, function(canvas) {
                // Convert the canvas to an image (PNG format)
                const imgData = canvas.toDataURL('image/png');
                
                // Create an anchor element to trigger the download
                const link = document.createElement('a');
                link.href = imgData;  // The image data URL
                link.download = 'prayer_event_details.png';  // Set the file name for download
                link.click();  // Trigger the download
            });
        }
    </script>
<?php } ?>


<?php echo form_close(); ?>

<?php echo form_open_multipart('prayer/index/manage/join', array('id' => 'bb_ajax_form2', 'class' => '')); ?>
    <?php if ($param2 == 'join') { ?>
        <input type="hidden" name="room_name" value="<?= isset($e_link['room_name']) ? $e_link['room_name'] : ''; ?>" >
        <input type="hidden" name="link" value="<?= isset($e_link['room_link']) ? $e_link['room_link'] : ''; ?>" >

        <input type="hidden" name="duration" value="<?= isset($duration) ? $duration : ''; ?>" >
        <input type="hidden" name="record_key" value="<?= isset($record_key) ? $record_key : ''; ?>" >
        <input type="hidden" name="date" value="<?= isset($e_date) ? $e_date : ''; ?>" >
        <input type="hidden" name="start_time" value="<?= isset($start_time) ? $start_time : ''; ?>" >
        <input type="hidden" name="church_id" value="<?= isset($church_idz) ? $church_idz : ''; ?>" >
        <input type="hidden" name="prayer_id" value="<?= isset($e_id) ? $e_id : ''; ?>" >

        
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
                    <select class="js-select2" data-search="on" name="church_idz" id="church_id" required>
                        <option value="">Select</option>
                        <?php 
                            $e_churches = $this->Crud->read2_order('regional_id', 8, 'type', 'church', 'church', 'name', 'asc');
                            if(!empty($e_churches)){
                                foreach($e_churches as $ch){
                                    
                                    echo '<option value="'.$ch->id.'">'.ucwords($ch->name).'</option>';

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
   function downloadPDF() {
        const element = document.getElementById('event-content'); // Get the content to capture
        
        // Use html2pdf with enhanced options for dynamic adjustment
        html2pdf()
            .from(element)
            .set({
                margin: [10, 10, 10, 25],  // Set margins to prevent clipping
                filename: 'prayer_event_details.pdf', // Output filename
                html2canvas: {
                    scale: 4,  // Increase resolution for better quality
                    letterRendering: true, // Improve text rendering
                    useCORS: true,  // Allow cross-origin images
                    backgroundColor: '#ffffff'  // Set background color for clarity
                },
                jsPDF: {
                    unit: 'mm',  // Use millimeters for units
                    format: 'a4', // PDF format (A4 size)
                    orientation: 'portrait',  // Portrait orientation for the page
                    autoSize: true, // Ensure the content fits on the page
                    compressPDF: true  // Compress the PDF for smaller file size
                }
            })
            .save(); // Automatically triggers PDF download
    } 
</script>