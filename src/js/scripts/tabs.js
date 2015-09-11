'use strict';

var $ = require('jquery');

module.exports = function () {
    $(function () {
        var tabItems = $('.tabs-nav li a'),
            tabContentWrapper = $('.tabs-list');

        var activeItems = tabItems.find('.active');

        if (!activeItems.length) {
            var selectedItem = tabItems.first();
            selectTab(selectedItem);
        }

        tabItems.on('click', function (event) {
            event.preventDefault();

            var selectedItem = $(this);

            if (!selectedItem.hasClass('active')) {

                selectTab(selectedItem);
            }
        });

        function selectTab(selectedItem) {
            var selectedTab = selectedItem.data('content');
            var selectedContent = tabContentWrapper.find('li[data-content="' + selectedTab + '"]');

            tabItems.removeClass('active');
            selectedItem.addClass('active');
            selectedContent.addClass('active').siblings('li').removeClass('active');
        }

        $('.btn[data-viewtype]').on('click', function (event) {
            event.preventDefault();

            var viewType = $(this).data('viewtype');

            $(this).addClass('active').siblings('a').removeClass('active');

            tabContentWrapper.removeClass('grid list').addClass(viewType);
        });

    });

};