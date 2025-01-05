"use strict";
!(function (g, f) {
    f(window), f("body"), g.Break;

    let isCalendarInitialized = false;  // Flag to ensure the calendar is only initialized once
    let calendar = null;  // Track the calendar instance

    g.Calendar = function (searchTerm, churchId) {
        var e = new Date(),
            t = String(e.getDate()).padStart(2, "0"),
            a = String(e.getMonth() + 1).padStart(2, "0"),
            n = e.getFullYear(),
            r = n + "-" + a + "-" + t;

        var initialDate = r; // Default to current date if no events are found
        var initialView = "dayGridMonth";  // Default to month view
        var initialVisibleRange = { start: r, end: r };  // Default to current week if no events are found

        var e = document.getElementById("calendar"),
            initialView = e.getAttribute("data-initial-view") || (g.Win.width < g.Break.md ? "listWeek" : "dayGridMonth"),  
            headerToolbarConfig = initialView === "listWeek"
                ? { left: "title prev,next", center: null, right: null }
                : { left: "title prev,next", center: null, right: "today dayGridMonth,timeGridWeek,timeGridDay,listWeek" };

        // Destroy existing calendar if initialized and reinitialize
        if (calendar) {
            calendar.destroy();  // Destroy existing calendar
        }

        // Reinitialize the calendar with updated data
        calendar = new FullCalendar.Calendar(e, {
            timeZone: "UTC",
            initialView: initialView,
            initialDate: initialDate,  // Use the current date if no events are found
            visibleRange: initialVisibleRange,  // Set the visible range to the current week if no events
            themeSystem: "bootstrap5",
            headerToolbar: headerToolbarConfig,
            height: 'auto',
            contentHeight: 'auto',
            aspectRatio: 3,
            editable: !0,
            droppable: !0,
            views: { 
                dayGridMonth: { dayMaxEventRows: 2 },
                listWeek: {
                    eventContent: function (info) {
                        var rowDiv = document.createElement('div');
                        rowDiv.classList.add('row', 'align-items-center');  

                        var titleDiv = document.createElement('div');
                        titleDiv.classList.add('col');
                        titleDiv.innerHTML = '<strong>' + info.event.title + '</strong>';

                        var categoryDiv = document.createElement('div');
                        categoryDiv.classList.add('col');
                        categoryDiv.innerHTML = '<strong>' + info.event.extendedProps.church + '</strong>';

                        var buttonsDiv = document.createElement('div');
                        buttonsDiv.classList.add('col', 'text-end');  

                        var editBtn = document.createElement('a');
                        var editUrl = site_url + 'prayer/index/manage/join/' + info.event.id;  
                        editBtn.href = 'javascript:;';
                        editBtn.setAttribute('pageName', editUrl);
                        editBtn.setAttribute('pageTitle', 'Join Prayer: ' + info.event.title);
                        editBtn.setAttribute('pageSize', 'modal-md'); 
                        editBtn.className = 'btn btn-primary text-white my-1 pops mx-1';  
                        editBtn.innerHTML = 'Join';

                        var viewBtn = document.createElement('a');
                        var viewUrl = site_url + 'prayer/index/manage/view/' + info.event.id;  
                        viewBtn.href = 'javascript:;';
                        viewBtn.setAttribute('pageName', viewUrl);
                        viewBtn.setAttribute('pageTitle', 'View Prayer Point: ' + info.event.title); 
                        viewBtn.setAttribute('pageSize', 'modal-xl'); 
                        viewBtn.className = 'btn btn-success text-white my-1 pops mx-1';  
                        viewBtn.innerHTML = 'View Prayer';
                        
                        buttonsDiv.appendChild(editBtn);
                        buttonsDiv.appendChild(viewBtn);

                        rowDiv.appendChild(titleDiv);
                        rowDiv.appendChild(categoryDiv);
                        rowDiv.appendChild(buttonsDiv);
                        
                        return { domNodes: [rowDiv] };
                    }
                }
            },
            direction: g.State.isRTL ? "rtl" : "ltr",
            nowIndicator: !0,
            now: r + "T09:25:00",
            events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: site_url + 'prayer/get_calendar', // PHP script to fetch events
                    method: 'POST',
                    data: {
                        searchTerm: f("#search").val(),  // Correctly get the value of #search input
                        churchId: f("#church_idz").val()  // Get the current churchId value
                    },
                    dataType: 'json',  // Tell jQuery that the response is JSON
                    success: function (response) {
                        // Check if response is an array
                        if (Array.isArray(response)) {
                            // If events are available, find the next event date
                            if (response.length > 0) {
                                var eventDates = response.map(function(event) {
                                    return event.start.split("T")[0];  // Extract date part from event's start date
                                });
                                
                                // Find the next available event date
                                var nextEventDate = eventDates.find(function(date) {
                                    return new Date(date) > new Date();  // Find the first future event date
                                });

                                // If a future event exists, set the initial date to that event's date
                                if (nextEventDate) {
                                    initialDate = nextEventDate;  // Set to the next event date
                                    initialVisibleRange = { start: nextEventDate, end: nextEventDate };  // Set visible range to the event's week
                                } else {
                                    // If no future event exists, set it to the current date or week
                                    initialDate = r;  // Set to current date if no future event is found
                                    initialVisibleRange = { start: r, end: r };  // Set the visible range to the current week
                                }
                            }

                            successCallback(response);  // Pass the response as-is to FullCalendar
                            console.log(response);  // Log the new events for debugging
                        } else {
                            console.error("The response is not an array:", response);
                            failureCallback();  // Trigger failure if the response is not an array
                        }
                    },
                    error: function () {
                        failureCallback();
                    }
                });
            }
        });

        // Render Calendar only once on page load
        if (!isCalendarInitialized) {
            calendar.render();  // Initial render of the calendar
            isCalendarInitialized = true;  // Set the flag to true to prevent re-initialization
        }

        // Attach event listeners only once on page load (not on each Calendar call)
        if (f("#search").data('events-attached') !== true) {
            f("#search").on("input", function () {
                var searchTerm = f("#search").val();  // Correctly get searchTerm from the input field
                var churchId = f("#church_idz").val();  // Get the current churchId value

                // Re-render the calendar with updated search term and church ID
                g.Calendar(searchTerm, churchId);
                calendar.render(); 
            }).data('events-attached', true);  // Mark that the event is attached

            f("#church_idz").on("change", function () {
                var searchTerm = f("#search").val();  // Get searchTerm from the input field
                var churchId = f("#church_idz").val();  // Get the current churchId value

                // Re-render the calendar with updated search term and church ID
                g.Calendar(searchTerm, churchId);
                calendar.render(); 
            }).data('events-attached', true);  // Mark that the event is attached
        }

        // Event listener for "Join" and "View Prayer" buttons
        $(document).on('click', '.pops', function () {
            var pageName = $(this).attr('pageName');  // Get the pageName (URL)
            var pageTitle = $(this).attr('pageTitle');  // Get the pageTitle
            var pageSize = $(this).attr('pageSize');  // Get the pageSize for modal

            $(".modal-dialog").addClass(pageSize);
            $(".modal-center .modal-title").html(pageTitle);
            $(".modal-center .modal-body").html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div><br>Loading Please Wait..</div>');
            $(".modal-center .modal-body").load(pageName);
            $(".modal-center").modal("show");
        });

    },
        g.coms.docReady.push(g.Calendar);
})(NioApp, jQuery);
