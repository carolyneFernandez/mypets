/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
var $ = require('jquery');

global.$ = $;
global.jQuery = $;

require('bootstrap');

require('bootstrap-select/js/bootstrap-select');

require('./component/flatpickr');

import '@fortawesome/fontawesome-free/js/all'

// Routing
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
global.Routing = Routing;

require('./component/collection_type');

// require('./component/renameFile');

$(document).ready(function () {

    $(".row-href").click(function () {
        window.document.location = $(this).data("href");
    });

    $('.selectItemPerPage').on('change', function () {
        $(this).closest('form').submit();
    });

    $('input[type="file"]').change(function (e) {
        let name = "";
        if (e.target.files.length > 1) {
            $.each(e.target.files, function (index, value) {
                name += value.name;
                if (e.target.files.length - 1 !== index) name += ', ';
            })
        } else {
            name = e.target.files[0].name;
        }
        $('.custom-file-label').html(name);
    });

    $('tr.row-href a').on('click', function (e) {
        e.stopPropagation();
    });

    $('[data-toggle="tooltip"]').tooltip()

});

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

// console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
