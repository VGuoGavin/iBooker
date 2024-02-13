import { library, dom } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';

export default (function () {
    window.$ = window.jQuery = require('jquery');
    library.add(fas, far);
    dom.watch();
}());

