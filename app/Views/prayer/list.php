<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from world5.commonsupport.com/html/smart-up/event-schedule.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 26 Dec 2024 16:13:54 GMT -->
<head>
	<meta charset="UTF-8">
	<title><?=$title; ?></title>
    
    <link rel="shortcut icon" href="<?=site_url('assets/new_logo1.png'); ?>">
	<!-- responsive meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- master stylesheet -->
	<link rel="stylesheet" href="<?=site_url(); ?>assets/prayer/css/style.css">
	<!-- responsive stylesheet -->
	<link rel="stylesheet" href="<?=site_url(); ?>assets/prayer/css/responsive.css">
    <!-- Bootstrap CSS CDN -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
</head>
    
    
    
<body>

<!--Start header area-->  
<header class="header">
    <div class="container">
        <div class="clearfix">
            <div class="logo pull-left">
                <a href="<?=site_url('prayer'); ?>">
                    <img style="max-height:50px" src="<?=site_url(); ?>assets/new_logo1.png" alt="Awesome Image">
                </a>    
            </div>
            <div class="header-right pull-right clearfix">   
                <div class="nav-footer collapse pull-right" id="mainNavWrapper">
                    <ul>
                        <li><a href="<?=site_url('auth'); ?>">CMS</a></li>
                    </ul>
                </div>
                
            </div>
        </div>
    </div>
</header>
<!--End header area-->

<!--Start breadcrumb area-->
<section class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Prayer Cloud</h1>
                <h4>Where faith meets community, and prayers soar higher.</h4>
            </div>
        </div>
    </div>
</section> 
<!--End breadcrumb area-->
    
<!--Start weekly event schedule area-->
<section class="weekly-event-schedule-area">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-xs-12">
            <?php
                // Set up the months and years for the dropdown
                $months = [
                    '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                    '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                    '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
                ];

               // Get the current year and available years for selection
                $currentYear = date('Y');
                $years = range(2023, $currentYear + 5); // Show 5 years before and after the current year

                // Get current month, week, and year for auto-selection
                $selectedMonth = date('m');
                $selectedYear = $currentYear;
                $selectedWeek = date('W');

                $currentYear = date('Y');
                $currentMonth = date('m');
                $currentDate = date('Y-m-d');
                // Get the first day of the selected month
                $firstDayOfMonth = strtotime("$currentYear-$currentMonth-01");

                // Get the last day of the current month
                $lastDayOfMonth = strtotime("last day of $currentYear-$currentMonth");

                // Calculate total days in the month
                $totalDaysInMonth = (int)date('d', $lastDayOfMonth);

                // Calculate the number of weeks in the month
                $weeksInMonth = ceil($totalDaysInMonth / 7); // Rough calculation of weeks in the month

                // Now calculate the current week of the month based on the current date
                $currentWeekNumber = (int)ceil(date('j', strtotime($currentDate)) / 7); // Week number based on the current day

                // Handle user selection (this would typically come from a form or URL)
                $selectedWeek = isset($_GET['week']) ? $_GET['week'] : $currentWeekNumber; // Default to current week if no week is selected
                // Calculate the number of weeks in the month
            ?>
                <div class="section-title pull-left" id="selectedScheduleInfo">
                    <b><h2 ></h2>
                    </b>
                </div>  
            </div>
            <div class="col-md-6 col-xs-12">
                
                <div class="filter-options pull-right mx-2 mb-2">
                    <select name="year" id="year" class="form-control">
                        
                    </select>
                </div>

                <div class="filter-options pull-right mx-2 mb-2">
                    <select name="month" id="month" class="form-control">
                        <?php foreach ($months as $monthNum => $monthName): ?>
                            <option value="<?= $monthNum ?>" <?= $monthNum == $selectedMonth ? 'selected' : '' ?>><?= $monthName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-options pull-right mx-2 mb-2">
                    <select name="week" id="week" class="form-control">
                        
                    </select>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="weekly-event-schedule-tab">
                    <ul class="weekly-event-schedule-tab-menu" role="tablist" id="weeklyTabMenu">
                        <li role="presentation" class="active"><a href="#monday" aria-controls="monday" role="tab" data-toggle="tab">Monday 11/02/2016</a></li>
                        <li role="presentation"><a href="#tuesday" aria-controls="tuesday" role="tab" data-toggle="tab">Tuesday 12/02/2016</a></li>
                        <li role="presentation"><a href="#wednesday" aria-controls="wednesday" role="tab" data-toggle="tab">Wednesday 13/02/2016</a></li>
                        <li role="presentation"><a href="#thusday" aria-controls="thusday" role="tab" data-toggle="tab">Thusday 14/02/2016</a></li>
                        <li role="presentation"><a href="#friday" aria-controls="friday" role="tab" data-toggle="tab">Friday 15/02/2016</a></li>
                        <li role="presentation"><a href="#saturday" aria-controls="saturday" role="tab" data-toggle="tab">Saturday 11/02/2016</a></li>
                    </ul>
                    <div class="tab-content" id="weeklyTabContent">
                        <!--Start single tab panel-->
                        <div role="tabpanel" class="tab-pane fade in active" id="monday">
                            <div class="content">
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">09:00 AM - 10:00 AM / Room 21A</p>
                                    <h4 data-toggle="collapse" data-target="#event-one" aria-expanded="true" aria-controls="event-one">Suscipit lobortis nisl ut aliquip ex ea commodo era modno weta</h4>
                                    <div class="box collapse in" id="event-one">
                                        <p>Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-1.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span> Jonathan Doe</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">10:30 AM - 12:00 AM / Room 314B</p>
                                    <h4 data-toggle="collapse" data-target="#event-two" aria-expanded="true" aria-controls="event-two">Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie sonsqoneto</h4>
                                    <div class="box collapse" id="event-two">
                                        <p>At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-2.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span>Mark Terence</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">02:30 PM - 03:30 PM / Room 54D</p>
                                    <h4 data-toggle="collapse" data-target="#event-three" aria-expanded="true" aria-controls="event-three">Nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat</h4>
                                    <div class="box collapse" id="event-three">
                                        <p>At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-3.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span>Mark Terence</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">04:00 AM - 5:00 AM / Room 21A</p>
                                    <h4 data-toggle="collapse" data-target="#event-four" aria-expanded="true" aria-controls="event-four">Suscipit lobortis nisl ut aliquip ex ea commodo era modno weta</h4>
                                    <div class="box collapse" id="event-four">
                                        <p>Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-1.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span> Jonathan Doe</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">05:30 AM - 06:30 AM / Room 314B</p>
                                    <h4 data-toggle="collapse" data-target="#event-five" aria-expanded="true" aria-controls="event-five">Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie sonsqoneto</h4>
                                    <div class="box collapse" id="event-five">
                                        <p>At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-2.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span>Mark Terence</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">07:00 PM - 08:00 PM / Room 54D</p>
                                    <h4 data-toggle="collapse" data-target="#event-six" aria-expanded="true" aria-controls="event-six">Nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat</h4>
                                    <div class="box collapse" id="event-six">
                                        <p>At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-3.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span>Mark Terence</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <div class="load-more-button">
                                    <a href="#">LOAD MORE</a>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tuesday">
                            <div class="content">
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">09:00 AM - 10:00 AM / Room 21A</p>
                                    <h4 data-toggle="collapse" data-target="#tuesday-event-one" aria-expanded="true" aria-controls="tuesday-event-one">Suscipit lobortis nisl ut aliquip ex ea commodo era modno weta</h4>
                                    <div class="box collapse in" id="tuesday-event-one">
                                        <p>Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-1.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span> Jonathan Doe</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">10:30 AM - 12:00 AM / Room 314B</p>
                                    <h4 data-toggle="collapse" data-target="#tuesday-event-two" aria-expanded="true" aria-controls="tuesday-event-two">Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie sonsqoneto</h4>
                                    <div class="box collapse" id="tuesday-event-two">
                                        <p>At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-2.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span>Mark Terence</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">02:30 PM - 03:30 PM / Room 54D</p>
                                    <h4 data-toggle="collapse" data-target="#tuesday-event-three" aria-expanded="true" aria-controls="tuesday-event-three">Nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat</h4>
                                    <div class="box collapse" id="tuesday-event-three">
                                        <p>At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-3.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span>Mark Terence</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">04:00 AM - 5:00 AM / Room 21A</p>
                                    <h4 data-toggle="collapse" data-target="#tuesday-event-four" aria-expanded="true" aria-controls="tuesday-event-four">Suscipit lobortis nisl ut aliquip ex ea commodo era modno weta</h4>
                                    <div class="box collapse" id="tuesday-event-four">
                                        <p>Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-1.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span> Jonathan Doe</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">05:30 AM - 06:30 AM / Room 314B</p>
                                    <h4 data-toggle="collapse" data-target="#tuesday-event-five" aria-expanded="true" aria-controls="tuesday-event-five">Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie sonsqoneto</h4>
                                    <div class="box collapse" id="tuesday-event-five">
                                        <p>At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-2.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span>Mark Terence</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <!--Start single content-->
                                <div class="single-content">
                                    <p class="time">07:00 PM - 08:00 PM / Room 54D</p>
                                    <h4 data-toggle="collapse" data-target="#tuesday-event-six" aria-expanded="true" aria-controls="tuesday-event-six">Nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat</h4>
                                    <div class="box collapse" id="tuesday-event-six">
                                        <p>At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                                        <div class="bottom-content clearfix">
                                            <div class="img-holder">
                                                <img src="<?=site_url(); ?>assets/prayer/img/weekly-event-schedule/speaker-3.png" alt="Awesome Image">    
                                            </div>
                                            <div class="speaker-name">
                                                <p><span>Speaker:</span>Mark Terence</p>    
                                            </div>
                                            <div class="see-details">
                                                <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>see details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End single content-->
                                <div class="load-more-button">
                                    <a href="#">LOAD MORE</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</section>
<!--End weekly event schedule area-->

<!--Start footer bottom area-->
<section class="footer-bottom-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="copyright-text text-center">
                    <p>Copyright <?=date('Y'); ?> © <?=app_name; ?> | All Rights Reserved</p>
                </div>
            </div>
        </div>    
    </div>
</section>       
<!--End footer bottom area-->

	<!-- main jQuery -->
	<script src="<?=site_url(); ?>assets/prayer/js/jquery-1.12.4.min.js"></script>
	<!-- bootstrap -->
	<script src="<?=site_url(); ?>assets/prayer/js/bootstrap.min.js"></script>
	<script src="<?=site_url(); ?>assets/prayer/js/jquery.easing.min.js"></script>
    
    
    <script src="<?=site_url(); ?>assets/prayer/js/jquery-ui-1.11.4/jquery-ui.js"></script>
    <script src="<?=site_url(); ?>assets/prayer/js/jquery.countdown.min.js"></script>
    
	<!-- thm custom script -->
	<script src="<?=site_url(); ?>assets/prayer/js/custom.js"></script>
    <script>var site_url = '<?php echo site_url(); ?>';
        $(document).ready(function() {
            // Initialize years dynamically
            var currentYear = new Date().getFullYear();
            var startYear = currentYear - 5;
            var endYear = currentYear + 5;

            // Populate the Year dropdown
            for (var i = startYear; i <= endYear; i++) {
                $('#year').append('<option value="'+i+'">'+i+'</option>');
            }

            // Set the default selected year and month
            var selectedYear = currentYear;
            var selectedMonth = new Date().getMonth() + 1;  // Get the current month (1-based)
            var selectedWeek = Math.ceil(new Date().getDate() / 7); // Get the current week based on the day of the month

            $('#year').val(selectedYear);
            $('#month').val(selectedMonth);
            $('#week').val(selectedWeek);

            // Event data: replace with actual event data or dynamic content
            var events = {
                "week1": [
                    { time: "09:00 AM - 10:00 AM", room: "Room 21A", title: "Event 1", speaker: "John Doe", description: "Event details..." },
                    { time: "10:30 AM - 12:00 PM", room: "Room 314B", title: "Event 2", speaker: "Jane Smith", description: "Event details..." }
                ],
                "week2": [
                    { time: "02:30 PM - 03:30 PM", room: "Room 314B", title: "Event 3", speaker: "Mark Terence", description: "Event details..." }
                ]
                // Add events for other weeks here
            };

            // Function to generate tabs and event content dynamically
            function generateWeeklyTabs(year, month, selectedWeek) {
                var firstDay = new Date(year, month - 1, 1); // First day of the selected month
                var lastDay = new Date(year, month, 0); // Last day of the selected month
                var totalDaysInMonth = lastDay.getDate();
                
                var tabMenu = '';
                var tabContent = '';
                var activeClass = 'active'; // Set the first tab as active

                // Calculate the start of the selected week (assumed Sunday to Saturday)
                var startOfWeek = new Date(year, month - 1, (selectedWeek - 1) * 7 + 1); // Start of the selected week

                // Generate tabs for each day of the selected week (Sunday to Saturday)
                for (var i = 0; i < 7; i++) {
                    var currentDay = new Date(startOfWeek);
                    currentDay.setDate(startOfWeek.getDate() + i); // Move to the next day (Sunday, Monday, etc.)

                    var dayName = getDayName(currentDay); // Get the day name (Sunday, Monday, etc.)
                    var formattedDate = formatDate(currentDay); // Format the date as dd/mm/yyyy

                    // Create tab menu items for each day of the week (from Sunday to Saturday)
                    tabMenu += `<li role="presentation" class="${activeClass}">
                                    <a href="#${dayName.toLowerCase()}" aria-controls="${dayName.toLowerCase()}" role="tab" data-toggle="tab">
                                        ${dayName} ${formattedDate}
                                    </a>
                                </li>`;

                    // Create tab content items for each day (Events for each day would go here)
                    var eventContent = generateEventContent(dayName, formattedDate); // Generate events dynamically
                    tabContent += `<div role="tabpanel" class="tab-pane ${activeClass}" id="${dayName.toLowerCase()}">
                                    <h3>${dayName} ${formattedDate}</h3>
                                    ${eventContent}
                                </div>`;

                    // Remove active class after the first iteration
                    activeClass = '';
                }

                // Insert the generated tab menu and tab content into the DOM
                $('#weeklyTabMenu').html(tabMenu);
                $('#weeklyTabContent').html(tabContent);
            }

            // Function to format date as dd/mm/yyyy
            function formatDate(date) {
                var day = ('0' + date.getDate()).slice(-2);
                var month = ('0' + (date.getMonth() + 1)).slice(-2);  // months are zero-based
                var year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }

            // Function to get the day name (e.g., "Sunday", "Monday", etc.)
            function getDayName(date) {
                var daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                return daysOfWeek[date.getDay()];
            }

            // Function to generate event content dynamically (replace with actual event data)
            function generateEventContent(dayName, date) {
                // For now, we will just display a placeholder for events
                var events = [
                    { time: "09:00 AM - 10:00 AM", room: "Room 21A", title: "Sample Event 1", speaker: "John Doe", description: "Event details..." },
                    { time: "10:30 AM - 12:00 PM", room: "Room 314B", title: "Sample Event 2", speaker: "Jane Smith", description: "Event details..." }
                ];

                var eventContent = '';
                events.forEach(function(event, index) {
                    eventContent += `<div class="single-content">
                                        <p class="time">${event.time} / ${event.room}</p>
                                        <h4 data-toggle="collapse" data-target="#${dayName.toLowerCase()}-event-${index + 1}" aria-expanded="true" aria-controls="${dayName.toLowerCase()}-event-${index + 1}">${event.title}</h4>
                                        <div class="box collapse" id="${dayName.toLowerCase()}-event-${index + 1}">
                                            <p>${event.description}</p>
                                            <div class="bottom-content clearfix">
                                                <div class="img-holder">
                                                    <img src="${site_url}assets/prayer/img/event/single-event/speaker.png" alt="Speaker">    
                                                </div>
                                                <div class="speaker-name">
                                                    <p><span>Speaker:</span> ${event.speaker}</p>    
                                                </div>
                                                <div class="see-details">
                                                    <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> see details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                });

                return eventContent;
            }

            // Initialize the week dropdown and tabs on page load
            generateWeeklyTabs(selectedYear, selectedMonth, selectedWeek);

            // Handle changes to year or month selection
            $('#year, #month').change(function() {
                selectedYear = $('#year').val();
                selectedMonth = $('#month').val();
                selectedWeek = 1; // Reset to week 1 for simplicity
                $('#week').val(selectedWeek); // Reset the week dropdown to the first week
                generateWeeklyTabs(selectedYear, selectedMonth, selectedWeek);
            });

            // Handle changes to the selected week
            $('#week').change(function() {
                selectedWeek = $('#week').val();
                generateWeeklyTabs(selectedYear, selectedMonth, selectedWeek);
            });
        });
    </script>
</body>
</html>