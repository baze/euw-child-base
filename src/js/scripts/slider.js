'use strict';

var $ = require('jquery');
require('jquery.flexslider');

module.exports = function () {

    $('.flexslider').flexslider({
        animation: "fade",
        slideshow: true,
        controlNav: true,
        directionNav: true,
        slideshowSpeed: 6500,
        animationSpeed: 750
    });
};