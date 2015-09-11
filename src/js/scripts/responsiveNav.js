'use strict';

var responsiveNav = require('responsiveNav');

module.exports = function() {

    window.onload = function() {
        responsiveNav(".nav-collapse-menu", {
            animate: true, // Boolean: Use CSS3 transitions, true or false
            transition: 284, // Integer: Speed of the transition, in milliseconds
            label: '<i class="fa fa-bars"></i> Navigation', // String: Label for the navigation toggle
            insert: "before", // String: Insert the toggle before or after the navigation
            customToggle: "", // Selector: Specify the ID of a custom toggle
            closeOnNavClick: false, // Boolean: Close the navigation when one of the links are clicked
            openPos: "relative", // String: Position of the opened nav, relative or static
            navClass: "nav-collapse", // String: Default CSS class. If changed, you need to edit the CSS too!
            navActiveClass: "js-nav-active", // String: Class that is added to  element when nav is active
            jsClass: "js" // String: 'JS enabled' class which is added to  element
        });

        responsiveNav(".nav-collapse-info", {
            animate: true, // Boolean: Use CSS3 transitions, true or false
            transition: 284, // Integer: Speed of the transition, in milliseconds
            label: '<i class="fa fa-info"></i> Informationen', // String: Label for the navigation toggle
            insert: "before", // String: Insert the toggle before or after the navigation
            customToggle: "", // Selector: Specify the ID of a custom toggle
            closeOnNavClick: false, // Boolean: Close the navigation when one of the links are clicked
            openPos: "relative", // String: Position of the opened nav, relative or static
            navClass: "nav-collapse", // String: Default CSS class. If changed, you need to edit the CSS too!
            navActiveClass: "js-nav-active", // String: Class that is added to  element when nav is active
            jsClass: "js" // String: 'JS enabled' class which is added to  element
        });
    }

};