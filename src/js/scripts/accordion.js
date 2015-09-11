'use strict';

var $ = require('jquery');

module.exports = function() {

    if ($(window).width() < 768) {
        var toggle = $('<div class="accordion-toggle"></div>');

        $('.accordion-headline').append(toggle);

        $('.accordion-inner').hide();

        $('.accordion-headline').click(function (event) {
            $(this).toggleClass('active');
            $(this).next('.accordion-inner').slideToggle(400);
        });
    }

};