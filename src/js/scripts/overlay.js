'use strict';

var $ = require('jquery');

module.exports = function () {

    var overlay = $('.overlay');

    $('.overlay-open').on('click', function (event) {
        event.preventDefault();

        overlay.addClass('overlay-open');

        // specific!
        var person_id = $(this).attr('data-person-id');
        var form = overlay.find('form');

        if (person_id && form) {
            var input = overlay.find('input[name="hidden-contact_person"]');
            input.val(person_id);
        }
    });

    $('.overlay-close').on('click', function () {
        overlay.removeClass('overlay-open');

        var input = overlay.find('input[name="hidden-contact_person"]');
        input.val(null);

    });
};