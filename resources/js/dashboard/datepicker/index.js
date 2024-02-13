import * as $ from 'jquery';

export default (function () {
    global.moment = require('moment');
    require('tempusdominus-bootstrap-4');
    $('#startDateTime').datetimepicker({
        format: 'DD/MM/YYYY HH:mm',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom',
        },
    });
    $('#endDateTime').datetimepicker({
        format: 'DD/MM/YYYY HH:mm',
        useCurrent: false,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom',
        },
    });
    $('#startDateTime').on('change.datetimepicker', function(e) {
        $('#endDateTime').datetimepicker('minDate', e.date);
    });
    $('#endDateTime').on('change.datetimepicker', function(e) {
        $('#startDateTime').datetimepicker('maxDate', e.date);
    });
}());
