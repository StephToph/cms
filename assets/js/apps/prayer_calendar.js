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
            // Dynamically determine initial view based on screen width
            initialView = (g.Win.width < g.Break.md ? "listWeek" : "dayGridMonth"),
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
                height: "auto", // Auto height for responsiveness
                contentHeight: "auto", // Adjust content height dynamically
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
                            var editUrl = site_url + 'church/activity/manage/edit/' + info.event.id;
                            editBtn.href = 'javascript:;';
                            editBtn.setAttribute('pageName', editUrl);
                            editBtn.setAttribute('pageTitle', 'Join Prayer: ' + info.event.title);
                            editBtn.setAttribute('pageSize', 'modal-xl');
                            editBtn.className = 'btn btn-primary text-white mt-2 mx-1';
                            editBtn.innerHTML = 'Join';

                            var viewBtn = document.createElement('a');
                            var viewUrl = site_url + 'prayer/index/manage/view/' + info.event.id;
                            viewBtn.href = 'javascript:;';
                            viewBtn.setAttribute('pageName', viewUrl);
                            viewBtn.setAttribute('pageTitle', 'View Prayer Point: ' + info.event.title);
                            viewBtn.setAttribute('pageSize', 'modal-xl');
                            viewBtn.className = 'btn btn-success text-white pops mt-2 mx-1';
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
                        id = e.event._def.publicId, 
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
                            f("#preview-event-id").text(id), 
                            a || f("#preview-event-description-check").css("display", "none"),
                            u(),
                            document.querySelectorAll(".fc-more-popover"));
                    e &&
                        e.forEach(function (e) {
                            e.remove();
                        }),
                        p.modal("show");

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

        $(document).on('click', '.pops', function (event) {
            var pageTitle = $(this).attr('pageTitle');
            var pageName = $(this).attr('pageName');
            var pageSize = $(this).attr('pageSize');
            $(".modal-dialog").removeClass("modal-lg modal-sm modal-xl").addClass(pageSize);
            $(".modal-center .modal-title").html(pageTitle);
            $(".modal-center .modal-body").html('<div class="col-sm-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div><br>Loading Please Wait..</div>');
            $(".modal-center .modal-body").load(pageName, function () {});
            var add_url = site_url + 'church/activity/manage';
            $("#add_btn").attr('pageName', add_url);
            $(".modal-center").modal("show");
        });

        c.render();
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
        });
    }),
        g.coms.docReady.push(g.Calendar);
})(NioApp, jQuery);
