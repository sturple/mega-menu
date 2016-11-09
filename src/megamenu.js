(function () {
    "use strict";
    $(function () {
        //	Add hover code to Bootstrap menus
        $('ul.nav li.dropdown').on('mouseover',function () {
            if (window.innerWidth <= 768) return;
            var menu = $(this).find('.dropdown-menu');
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
            menu.show();
            var left = menu.offset().left;
            menu.hide();
            var right = $(window).innerWidth() - left - menu.outerWidth();
            if (right < 0) {
                menu.css('left','auto');
                menu.css('right','0px');
            }
            menu.stop(true,true).delay(200).fadeIn(500);
        }).on('mouseout',function () {
            if (window.innerWidth < 768) return;
            var menu = $(this).find('.dropdown-menu');
            menu.stop(true,true).delay(200).fadeOut(500,function () {   menu.attr('style','');  });
        });
    });
})();