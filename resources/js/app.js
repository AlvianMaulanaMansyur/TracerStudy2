// resources/js/app.js

import './bootstrap';

// Impor jQuery
import jQuery from 'jquery';

// Atur jQuery di jendela global
window.$ = window.jQuery = jQuery;

// Impor CSS jQuery UI
import 'jquery-ui/themes/base/all.css';

// Impor jQuery UI widgets yang Anda butuhkan
import 'jquery-ui/ui/widgets/autocomplete'; // Contoh mengimpor widget autocomplete
import 'jquery-ui/ui/widgets/draggable'; // Contoh mengimpor widget draggable
import 'jquery-ui/ui/widgets/droppable'; // Contoh mengimpor widget droppable