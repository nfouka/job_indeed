(function ($) {
  Drupal.behaviors.space_header = {
    attach : function(context, settings) {
      space_center_header();

      // If the screen size changes, adjust.
      $(window).resize(function() {
        space_center_header();
      });
    }
  };

  // Position the branding block to center in the header.
  function space_center_header() {
    var branding_height = $('#header-top').height();
    var block_height = $('.block-system-branding-block').height();
    var difference = (branding_height - block_height - 50) / 2;

    if (difference > 0) {
      $('.block-system-branding-block').css('top', difference + 'px');
    }
  }
})(jQuery);
