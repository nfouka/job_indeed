(function ($) {
  Drupal.behaviors.monarch_color_picker = {
    attach : function(context, settings) {
      $('#color-picker').colorPicker();
    }
  };
})(jQuery);
