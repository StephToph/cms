"use strict";
!(function (g, f) {
    f(window), f("body"), g.Break;
    (g.Calendar = function () {
        var e = new Date(),
            t = String(e.getDate()).padStart(2, "0"),
            a = String(e.getMonth() + 1).padStart(2, "0"),
            n = e.getFullYear(),
            i = new Date(e),
            i = (i.setDate(e.getDate() + 1), String(i.getDate()).padStart(2, "0"), String(i.getMonth() + 1).padStart(2, "0"), i.getFullYear(), new Date(e)),
            e = (i.setDate(e.getDate() - 1), String(i.getDate()).padStart(2, "0")),
            r = String(i.getMonth() + 1).padStart(2, "0"),
            d = n + "-" + a,
            i = i.getFullYear() + "-" + r + "-" + e,
            r = n + "-" + a + "-" + t,
            v = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            e = document.getElementById("calendar"),
            initialView = e.getAttribute("data-initial-view") || (g.Win.width < g.Break.md ? "listWeek" : "dayGridMonth"),  // Use the value from the data attribute
            headerToolbarConfig = initialView === "listWeek"
                ? { left: "title prev,next", center: null, right: null } // No right toolbar for view switching
                : { left: "title prev,next", center: null, right: "today dayGridMonth,timeGridWeek,timeGridDay,listWeek" }, // Default header with view switching
            n = (document.getElementById("externalEvents"), document.getElementById("removeEvent"), f("#addEvent")),
            s = (f("#addEventForm"), f("#addEventPopup")),
            a = f("#updateEvent"),
            m = f("#editEventForm"),
            o = f("#editEventPopup"),
            p = f("#previewEventPopup"),
            t = f("#deleteEvent"),
            c = new FullCalendar.Calendar(e, {
                timeZone: "UTC",
                initialView: initialView, // Set the initial view dynamically
                themeSystem: "bootstrap5",
                headerToolbar: headerToolbarConfig,
                height: 800,
                contentHeight: 780,
                aspectRatio: 3,
                editable: !0,
                droppable: !0,
                views: { 
                    dayGridMonth: { dayMaxEventRows: 2 },
                    listWeek: {
                        eventContent: function (info) {
                            // Create a div row for the event layout
                            var rowDiv = document.createElement('div');
                            rowDiv.classList.add('row', 'align-items-center');  // Using Bootstrap's row and align-items-center for vertical alignment
                    
                            // Create title div with col-6
                            var titleDiv = document.createElement('div');
                            titleDiv.classList.add('col-6');
                            titleDiv.innerHTML = '<strong>' + info.event.title + '</strong>';
                    
                            // Create category div with col-3
                            var categoryDiv = document.createElement('div');
                            categoryDiv.classList.add('col-3');
                            categoryDiv.innerHTML = '<strong>' + info.event.extendedProps.category + '</strong>';
                    
                            // Create buttons div with col-3
                            var buttonsDiv = document.createElement('div');
                            buttonsDiv.classList.add('col-3', 'text-end');  // text-end for right alignment
                    
                            // Create Edit button
                            var editBtn = document.createElement('a');
                            var editUrl = '/manage/edit/' + info.event.publicId;  // Update the URL format as needed
                            editBtn.href = editUrl;
                            editBtn.className = 'pop btn btn-sm btn-warning mx-1';  // mx-1 for spacing
                            editBtn.textContent = 'Edit';
                    
                            // Create Delete button
                            var deleteBtn = document.createElement('a');
                            var deleteUrl = '/manage/delete/' + info.event.publicId;  // Update the URL format as needed
                            deleteBtn.href = deleteUrl;
                            deleteBtn.className = 'pop btn btn-sm btn-danger mx-1';  // mx-1 for spacing
                            deleteBtn.textContent = 'Delete';
                    
                            // Append buttons to the buttonsDiv
                            buttonsDiv.appendChild(editBtn);
                            buttonsDiv.appendChild(deleteBtn);
                    
                            // Append title, category, and buttons divs to the rowDiv
                            rowDiv.appendChild(titleDiv);
                            rowDiv.appendChild(categoryDiv);
                            rowDiv.appendChild(buttonsDiv);
                    
                            // Return the array of elements (in this case, the single rowDiv element)
                            return { domNodes: [rowDiv] };
                        }
                    }
                    
                    
                },
                direction: g.State.isRTL ? "rtl" : "ltr",
                nowIndicator: !0,
                now: r + "T09:25:00",
                eventMouseEnter: function (e) {
                    var t = e.el,
                        a = e.event._def.title,
                        e = e.event._def.extendedProps.description;
                    e &&
                        new bootstrap.Popover(t, {
                            template: '<div class="popover event-popover"><div class="popover-arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
                            title: a,
                            content: e || "",
                            placement: "top",
                        }).show();
                },
                eventMouseLeave: function () {
                    u();
                },
                eventDragStart: function () {
                    u();
                },
                eventClick: function (e) {
                    var t = e.event._def.title,
                        a = e.event._def.extendedProps.description,
                        n = e.event._instance.range.start,
                        i = n.getFullYear() + "-" + String(n.getMonth() + 1).padStart(2, "0") + "-" + String(n.getDate()).padStart(2, "0"),
                        r = n.toUTCString().split(" "),
                        d = ((r = "00:00:00" == (r = r[r.length - 2]) ? "" : r), e.event._instance.range.end),
                        s = d.getFullYear() + "-" + String(d.getMonth() + 1).padStart(2, "0") + "-" + String(d.getDate()).padStart(2, "0"),
                        o = d.toUTCString().split(" "),
                        l = ((o = "00:00:00" == (o = o[o.length - 2]) ? "" : o), e.event._def.ui.classNames[0].slice(3)),
                        id = e.event._def.publicId, // Store the event ID
                        i =
                            (f("#edit-event-title").val(t),
                            f("#edit-event-start-date").val(i).datepicker("update"),
                            f("#edit-event-end-date").val(s).datepicker("update"),
                            f("#edit-event-start-time").val(r),
                            f("#edit-event-end-time").val(o),
                            f("#edit-event-description").val(a),
                            f("#edit-event-theme").val(l),
                            f("#edit-event-theme").trigger("change.select2"),
                            m.attr("data-id", id),
                            String(n.getDate()).padStart(2, "0") + " " + v[n.getMonth()] + " " + n.getFullYear() + (r ? " - " + h(r) : "")),
                        s = String(d.getDate()).padStart(2, "0") + " " + v[d.getMonth()] + " " + d.getFullYear() + (o ? " - " + h(o) : ""),
                        e =
                            (f("#preview-event-title").text(t),
                            f("#preview-event-header").addClass("fc-" + l),
                            f("#preview-event-start").text(i),
                            f("#preview-event-end").text(s),
                            f("#preview-event-description").text(a),
                            f("#preview-event-id").text(id), // Add the event ID to the preview modal
                            a || f("#preview-event-description-check").css("display", "none"),
                            u(),
                            document.querySelectorAll(".fc-more-popover"));
                    e &&
                        e.forEach(function (e) {
                            e.remove();
                        }),
                        p.modal("show");
                        // Update the button's pageName attribute with the event ID
                        var button = document.querySelector('.pop');
                        if (button) {
                            var baseUrl = button.getAttribute('pageName').split('/manage/edit/')[0];
                            button.setAttribute('pageName', baseUrl + '/manage/edit/' + id);
                        }
                },
                events: calEvents,
            });
        function u() {
            document.querySelectorAll(".event-popover").forEach(function (e) {
                e.remove();
            });
        }
        function h(e) {
            return 1 < (e = e.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [e]).length && ((e = e.slice(1)).pop(), (e[5] = +e[0] < 12 ? " AM" : " PM"), (e[0] = +e[0] % 12 || 12)), (e = e.join(""));
        }
        c.render(),
            n.on("click", function (e) {
                e.preventDefault();
                var e = f("#event-title").val(),
                    t = f("#event-start-date").val(),
                    a = f("#event-end-date").val(),
                    n = f("#event-start-time").val(),
                    i = f("#event-end-time").val(),
                    r = f("#event-description").val(),
                    d = f("#event-theme").val(),
                    n = n ? "T" + n + "Z" : "",
                    i = i ? "T" + i + "Z" : "";
                c.addEvent({ id: "added-event-id-" + Math.floor(9999999 * Math.random()), title: e, start: t + n, end: a + i, className: "fc-" + d, description: r }), s.modal("hide");
            }),
            a.on("click", function (e) {
                e.preventDefault();
                var e = f("#edit-event-title").val(),
                    t = f("#edit-event-start-date").val(),
                    a = f("#edit-event-end-date").val(),
                    n = f("#edit-event-start-time").val(),
                    i = f("#edit-event-end-time").val(),
                    r = f("#edit-event-description").val(),
                    d = f("#edit-event-theme").val(),
                    n = n ? "T" + n + "Z" : "",
                    i = i ? "T" + i + "Z" : "";
                c.getEventById(m[0].dataset.id).remove(), c.addEvent({ id: m[0].dataset.id, title: e, start: t + n, end: a + i, className: "fc-" + d, description: r }), o.modal("hide");
            }),
            t.on("click", function (e) {
                e.preventDefault(), c.getEventById(m[0].dataset.id).remove();
            }),
            g.Select2(".select-calendar-theme", {
                templateResult: function (e) {
                    return e.id ? f('<span class="fc-' + e.element.value + '"> <span class="dot"></span>' + e.text + "</span>") : e.text;
                },
            }),
            s.on("hidden.bs.modal", function (e) {
                setTimeout(function () {
                    f("#addEventForm input,#addEventForm textarea").val(""), f("#event-theme").val("event-primary"), f("#event-theme").trigger("change.select2");
                }, 1e3);
            }),
            p.on("hidden.bs.modal", function (e) {
                f("#preview-event-header").removeClass().addClass("modal-header");
            });
    }),
        g.coms.docReady.push(g.Calendar);
})(NioApp, jQuery);
