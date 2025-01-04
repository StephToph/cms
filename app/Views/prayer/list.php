<?php 
    use App\Models\Crud;

    $this->Crud = new Crud();


    $logo = 'assets/new_logo1.png';
    $background_image = 'assets/images/prayercloud.webp';

    $parts = explode('-', $param1);
    $prayer_id = $parts[0];
    $code = implode('-', array_slice($parts, 1));
    $searchTerm = '';$churchId = 0;
    if(!empty($prayer_id)){
        $searchTerm = $this->Crud->read_field('id', $prayer_id, 'prayer', 'title');
    }

    if(!empty($code)){
        $data = json_decode( $this->Crud->read_field('id', $prayer_id, 'prayer', 'assignment'), true);

        // Function to find the church_id based on the code
        function getChurchIdByCode($data, $search_code) {
            foreach ($data as $date => $records) {
                foreach ($records as $key => $record) {
                    if (isset($record['code']) && $record['code'] === $search_code) {
                        return $record['church_id'];
                    }
                }
            }
            return null; // If no record is found with the code
        }
    
        // Call the function
        $churchId = getChurchIdByCode($data, $code);

    }
   

?>
<?php
?>
<!DOCTYPE html>
<html lang="zxx" class="js">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Angel Church Management System">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description"
            content="Church Performnce Tracking">
        <meta name="theme-color" content="blue">
        <link rel="shortcut icon" href="<?=site_url(); ?>assets/new_logo1.png" />
        <title><?=$title; ?></title>
        <link rel="stylesheet" href="<?=site_url(); ?>assets/prayer/assets/css/dashlitee5ca.css?ver=<?=time(); ?>" />
        <link id="skin-default" rel="stylesheet" href="<?=site_url(); ?>assets/prayer/assets/css/themee5ca.css?ver=3.2.3" />
       
    </head>

    <style>
        .fc-list {
            width: 100%; /* Ensure the parent container takes the full width */
           
            white-space: nowrap; /* Prevent content from wrapping */
        }

        .fc-scroller {
            overflow-x: auto !important ; /* Ensure the child container scrolls horizontally */
        }


    </style>
    

    <body class="nk-body bg-white npc-landing">
        <div class="nk-app-root">
            <div class="nk-main">
            <header class="header header-32 has-header-main-s1" id="home" style="position: relative; background-image: url('<?= site_url($background_image); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">

                <!-- Dark background overlay with inline style -->
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.1); z-index: 0;"></div>

                <div class="header-main header-main-s1 is-sticky is-transparent on-dark">
                    <div class="container header-container">
                        <div class="header-wrap">
                            <div class="header-logo">
                                <a href="<?= site_url(); ?>" class="logo-link">
                                    <img class="logo-light logo-img logo-img-lg" style="max-height:50px;" src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?>" alt="logo">
                                    <img class="logo-dark logo-img logo-img-lg" style="max-height:50px;" src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?>" alt="logo-dark">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="header-content py-6 mt-lg-n1 mt-n3">
                    <div class="container">
                        <div class="row flex-row-reverse justify-content-center text-center g-gs">
                            <div class="col-lg-12 col-md-10">
                                <div class="header-caption">
                                    <h1 class="header-title text-uppercase" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8); font-size: 4rem; color: white;"><?php  if(!empty($this->Crud->read_field('id', $prayer_id, 'prayer', 'title'))){ echo $this->Crud->read_field('id', $prayer_id, 'prayer', 'title'); } else { echo 'USA REGION 2 Prayer Cloud';} ?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>


                
                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                
                                <div class="nk-block" id="calendar_resp">
                                    <div class="d-flex flex-row-reverse ">
                                        <div class="col-sm-2 mx-2 mb-3">
                                            <div class="form-group">
                                               <select class="form-select js-select2" data-search="on" id="church_idz" >
                                                    <option value="all"><?=translate_phrase('All Church'); ?></option>
                                                    <?php 
                                                        $e_churches = $this->Crud->read2_order('regional_id', 8, 'type', 'church', 'church', 'name', 'asc');
                                                        if(!empty($e_churches)){
                                                            foreach($e_churches as $ch){
                                                                $zel = '';
                                                                $church_id = htmlspecialchars($churchId); 

                                                                if(!empty($church_id)){
                                                                    if($church_id == $ch->id){
                                                                        $zel = 'selected';
                                                                    }
                                                                }
                                                                echo '<option value="'.$ch->id.'" '.$zel.'>'.ucwords($ch->name).'</option>';

                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 mx-2 mb-3">
                                            <div class="form-group">
                                                <input class="form-control" type="text" id="search" name="search" placeholder="Search Prayer" value="<?= htmlspecialchars($searchTerm); ?>" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card bg-lighter">
                                        <div class="card-inner">
                                            <div id="calendar" data-initial-view="listWeek" class="nk-calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer bg-dark is-dark section section-sm mt-5" style="padding:0.75rem 0" id="footer">
                    <div class="container">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <div class="footer-logo">
                                    <a href="<?= site_url(); ?>" class="logo-link">
                                        <img class="logo-light logo-img" src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?> 2x" alt="logo" />
                                        <img class="logo-dark logo-img" src="<?= site_url($logo); ?>" srcset="<?= site_url($logo); ?> 2x" alt="logo-dark" />
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-9 d-flex justify-content-md-end">
                            <div class="text-base">&copy; <?=date('Y'); ?> - <?=app_name; ?>. </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        
        <div class="modal fade" id="previewEventPopu">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div id="preview-event-header" class="modal-header">
                        <h5 id="preview-event-title" class="modal-title">Placeholder Title</h5><a href="#" class="close"
                            data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
                    </div>
                    <div class="modal-body">
                        <div class="row gy-3 py-1">
                            <div class="col-sm-6">
                                <h6 class="overline-title">Start Time</h6>
                                <p id="preview-event-start"></p>
                            </div>
                            <div class="col-sm-6" id="preview-event-end-check">
                                <h6 class="overline-title">End Time</h6>
                                <p id="preview-event-end"></p>
                            </div>
                            <div class="col-sm-10" id="preview-event-description-check">
                                <h6 class="overline-title">Description</h6>
                                <p id="preview-event-description"></p>
                            </div>
                        </div>
                        <!-- <ul class="d-flex justify-content-between gx-4 mt-3">
                            <li><button  pageTitle="Edit " pageSize="modal-lg" pageName="<?=site_url('ministry/calendar/manage/edit/'); ?>" class="btn btn-primary pop">Edit Event</button></li>
                            <li><button data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#deleteEventPopup"
                                    class="btn btn-danger pop btn-dim">Delete</button></li>
                        </ul> -->
                    </div>
                </div>
                
            </div>
        </div>

        <div class="modal modal-center fade" tabindex="-1" id="modal" role="dialog" data-keyboard="false"
            data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <a href="javascript:;" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                    <div class="modal-header">
                        <h6 class="modal-title"></h6>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
    
        <!-- Core Libraries -->
        <script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>/assets/js/jsmodal.js"></script>

        <!-- FullCalendar and Other Dependencies -->
        <script src="<?php echo base_url(); ?>/assets/js/libs/fullcalendar.js"></script>

        <!-- Custom Scripts -->
        <script src="<?=site_url(); ?>assets/prayer/assets/js/bundlee5ca.js?ver=3.2.3"></script>
        <script src="<?=site_url(); ?>assets/prayer/assets/js/scriptse5ca.js?ver=3.2.3"></script>

        
        <script src="<?php echo base_url(); ?>/assets/js/apps/prayer_calendar.js?v=<?=time();?>"></script>
        <script>
            
      
         var site_url = '<?=site_url(); ?>';
        </script>
        <!-- Custom Calendar Logic -->

    </body>
</html>
