(function () {
    "use strict";
    $(function () {
        //	Add hover code to Bootstrap menus
        $('ul.nav li.dropdown').on('mouseover',function () {
            if (window.innerWidth <= 768) return;
            var dropdown = $(this);
            dropdown.addClass('open');
            var menu = dropdown.find('.dropdown-menu');
            //  Figure out whether the menu should go below (default)
            //  or above (if it doesn't fit below)
            var offset = 20;
            var window_top = menu.parent().offset().top - $(window).scrollTop();
            var fit_bottom = $(window).innerHeight() >= window_top + menu.outerHeight() + offset;
            var fit_top = (window_top - $(window).scrollTop()) > 0;
            if (fit_bottom || !fit_top) {
                menu.css('top','100%');
            } else {
                menu.css('top','-' + menu.outerHeight() + 'px');
            }
            //  Figure out whether the menu should go to the right
            //  (default) or to the left (if it doesn't fit to the
            //  right)
            var left = menu.parent().offset().left;
            var window_width = $(window).innerWidth();
            if (left > (window_width / 2)) {
                if ((left + menu.parent().outerWidth() - menu.outerWidth()) < 0) {
                    menu.addClass('no-fit');
                } else {
                    menu.removeClass('no-fit');
                }
                menu.css('left','auto');
                menu.css('right','0px');
                menu.addClass('flipped');
            } else {
                if ((left + menu.outerWidth()) > window_width) {
                    menu.addClass('no-fit');
                } else {
                    menu.removeClass('no-fit');
                }
                menu.css('left','0px');
                menu.css('right','auto');
                menu.removeClass('flipped');
            }
        }).on('mouseout',function () {
            if (window.innerWidth < 768) return;
            var dropdown = $(this);
            dropdown.removeClass('open');
        });
    });
})();