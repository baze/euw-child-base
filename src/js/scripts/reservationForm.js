'use strict';

var $ = require('jquery');

module.exports = function () {

    var wrapper = $('._module-header-form');

    wrapper.find('.btn').on('click', function(event) {
        event.preventDefault();

        wrapper.toggleClass('open');
        //wrapper.find('.wpcf7').slideToggle();
    });

};