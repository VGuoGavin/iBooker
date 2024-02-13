import * as $ from 'jquery';
import 'datatables';

export default (function () {
    $('#dataTable').DataTable({
        'iDisplayLength': 5,
        'aLengthMenu': [[5, 10, 25, -1], [5, 10, 25, 'All']],
    });
}());
