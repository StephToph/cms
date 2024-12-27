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
                        
                    </ul>
                    <div class="tab-content" id="weeklyTabContent">
                       
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
	<!-- bx slider -->
	<script src="<?=site_url(); ?>assets/prayer/js/jquery.bxslider.min.js"></script>
	<!-- count to -->
    
    
    <script src="<?=site_url(); ?>assets/prayer/js/jquery-ui-1.11.4/jquery-ui.js"></script>
	<script src="<?=site_url(); ?>assets/prayer/js/custom.js"></script>


    <script>var site_url = '<?php echo site_url(); ?>';
        $(document).ready(function() {
            // Initialize years dynamically
            var currentYear = new Date().getFullYear();
            var startYear = 2024;
            var endYear = currentYear + 3;

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

                    // Update the Week dropdown and tabs when year or month changes
            function updateWeekDropdown(year, month) {
                var firstDay = new Date(year, month - 1, 1); // First day of the selected month
                var lastDay = new Date(year, month, 0); // Last day of the selected month

                // Calculate the number of days in the month
                var totalDaysInMonth = lastDay.getDate();

                // Calculate the number of weeks
                var weeksInMonth = Math.ceil(totalDaysInMonth / 7);

                // Clear current options
                $('#week').empty();

                // Populate the week dropdown with weeks for the selected month
                for (var i = 1; i <= weeksInMonth; i++) {
                    var startOfWeek = new Date(year, month - 1, (i - 1) * 7 + 1); // Start of the i-th week
                    var endOfWeek = new Date(year, month - 1, i * 7); // End of the i-th week

                    // Adjust the last week if it exceeds the number of days in the month
                    if (endOfWeek.getDate() > totalDaysInMonth) {
                        endOfWeek = new Date(year, month - 1, totalDaysInMonth);
                    }

                    // Format start and end dates
                    var startDateFormatted = formatDate(startOfWeek);
                    var endDateFormatted = formatDate(endOfWeek);

                    // Add week option
                    $('#week').append('<option value="'+i+'">Week ' + i + ' - ' + startDateFormatted + ' - ' + endDateFormatted + '</option>');
                }

                // Auto-select the current week if no specific week is selected
                autoSelectCurrentWeek(year, month);
            }

            // Function to auto-select the current week
            function autoSelectCurrentWeek(year, month) {
                var currentDate = new Date();
                if (currentDate.getFullYear() === year && (currentDate.getMonth() + 1) === month) {
                    var currentDay = currentDate.getDate();
                    var currentWeek = Math.ceil(currentDay / 7);  // Calculate the current week of the month

                    // Set the selected week in the dropdown
                    $('#week').val(currentWeek);

                    // Update the selected schedule info based on the selected week
                    updateScheduleInfo(currentWeek);
                }
            }

            // Format date to DD/MM/YYYY
            function formatDate(date) {
                var day = ('0' + date.getDate()).slice(-2);
                var month = ('0' + (date.getMonth() + 1)).slice(-2);  // months are zero-based
                var year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }

            // Update the selected schedule information
            function updateScheduleInfo(week) {
                var selectedYear = $('#year').val();
                var selectedMonth = $('#month').val();

                var firstDay = new Date(selectedYear, selectedMonth - 1, 1);
                var startOfWeek = new Date(firstDay.setDate(firstDay.getDate() + (week - 1) * 7)); // First day of the week

                var endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6); // Last day of the week

                var startDateFormatted = formatDate(startOfWeek);
                var endDateFormatted = formatDate(endOfWeek);

                // Update the selected schedule information
                $('#selectedScheduleInfo h2').text('Week ' + week + ' - ' + startDateFormatted + ' - ' + endDateFormatted);
            }

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
                        <div class="content">
                        ${eventContent}
                    </div> </div>`;

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
            updateWeekDropdown(selectedYear, selectedMonth);
   
            // Handle changes to year or month selection
            $('#year, #month').change(function() {
                selectedYear = $('#year').val();
                selectedMonth = $('#month').val();
                selectedWeek = 1; // Reset to week 1 for simplicity
                $('#week').val(selectedWeek); // Reset the week dropdown to the first week
                generateWeeklyTabs(selectedYear, selectedMonth, selectedWeek);
                updateWeekDropdown(selectedYear, selectedMonth);
            });

            // Handle changes to the selected week
            $('#week').change(function() {
                selectedWeek = $('#week').val();
                generateWeeklyTabs(selectedYear, selectedMonth, selectedWeek);
                updateScheduleInfo(selectedWeek);
            });
        });
    </script>
</body>
</html>