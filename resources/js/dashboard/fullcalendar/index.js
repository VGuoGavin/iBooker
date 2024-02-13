import * as $ from 'jquery';
import 'fullcalendar/dist/fullcalendar.min.js';
import 'fullcalendar/dist/fullcalendar.min.css';

export default (function () {
    $('#full-calendar').fullCalendar({
        editable: false,
        header: {
            left: 'month,agendaWeek,agendaDay',
            center: 'title',
            right: 'today prev,next',
        },

        eventRender: function(eventObj, $el) {
            $el.popover({
                title: eventObj.title,
                content: eventObj.description,
                trigger: 'hover',
                placement: 'auto',
                container: 'body'
            });
          },
    });
}());
