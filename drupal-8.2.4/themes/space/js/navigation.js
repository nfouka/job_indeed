/**
 * @file
 * Controls the menu positioning and hamburger functionality.
 */

(function ($) {
  // Scroll the entire page when the down arrow is clicked.
  $('.nav-down').click(function() {

    var offset = 0;

    // Find out if the toolbar is enabled.
    if ($('body').hasClass('toolbar-fixed')) {
      if ($('body').hasClass('toolbar-tray-open')) {
        offset = 79;
      }
      else {
        offset = 39;
      }
    }

    if ($(window).width() < 860) {
      menu_offset = $('.menu-hamburger').innerHeight();
    }
    else {
      menu_offset = $('#main-menu').innerHeight();
    }

    $("html, body").animate({scrollTop: $(window).height() - offset - menu_offset}, 600);

    return false;
  });

  $('.menu-hamburger').click(function() {
    if (!$('body').hasClass('main-menu-open')) {
      $('body').addClass('main-menu-open');
    }
    else {
      if ($('body').hasClass('main-menu-open')) {
        $('body').removeClass('main-menu-open');
      }
    }
  });
})(jQuery);
