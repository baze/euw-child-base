'use strict';

var $ = require('jquery');

module.exports = function () {

    $(function () {
        $('.social-popup').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            var windowName = $(this).attr('title');
            window.open(url, windowName, "height=300,width=400");
        });
    });

};