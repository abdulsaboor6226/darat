/**
 * CT Mobile Menu
 *
 * @package WP Pro Real Estate 7
 * @subpackage JavaScript
 */

jQuery(function ($) {
    $(document).ready(function () {

        $(".mobile-nav ul").removeClass("cbp-tm-menu");
        $(".mobile-nav ul").addClass("cbp-spmenu");

        $(
            '<div id="showLeftPush" class="show-hide"><i id="showLeftPushIcon" class="fa fa-navicon"></i></div>',
            {}
        ).appendTo("#masthead");

        var menuLeft = document.getElementById("cbp-spmenu"),
            showLeftPush = document.getElementById("showLeftPush"),
            showLeftPushIcon = document.getElementById("showLeftPushIcon"),
            headerWrap = document.getElementById("header-wrap"),
            body = document.body;

        if ( ! showLeftPush ) {
            return;
        }
        
        (container = document.getElementById("wrapper")),
            (showLeftPush.onclick = function () {
                $("body").bind("touchmove", function (e) {
                    e.preventDefault();
                });
                classie.toggle(this, "active");
                classie.toggle(body, "cbp-spmenu-push-toleft");
                classie.toggle(body, "stop-scrolling");
                classie.toggle(menuLeft, "cbp-spmenu-open");
                classie.toggle(showLeftPushIcon, "fa-close");

                classie.toggle(headerWrap, "to-left");
            });

        if (!$("body").hasClass("stop-scrolling")) {
            $("body").unbind("touchmove");
        }
    });
});

/**
 * Toggle Support
 * jshint: es6
 */
jQuery(document).ready(function ($) {

    "use strict";

    let $mobile_menu = $("#cbp-spmenu ul.menu");
    let $parent_menu_items = $mobile_menu.find("li.menu-item-has-children");
    // iterate on each child to appeand the toggle icon.
    $.each($parent_menu_items, function () {
        $("> a", $(this)).append("<span class='submenu-toggle'><i class='fas fa-chevron-down'></i></span>");

    });

    $("#cbp-spmenu li.current-menu-item").parentsUntil('ul.menu').addClass('open');
    $("#cbp-spmenu li.current-menu-item").parentsUntil('ul.menu').css('display', 'block');

    // Toggle event.
    $("span.submenu-toggle").click(function (e) {
        e.preventDefault();
        let $parent_list = $(this).parent().parent();
        $parent_list.toggleClass('open');
        $parent_list.find(" > ul.sub-menu").slideToggle('fast');
    });
});