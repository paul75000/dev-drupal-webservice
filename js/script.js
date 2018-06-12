(function ($, Drupal, window, document) {
  Drupal.behaviors.myjs = {
    attach: function (context, settings) {
      $(window).load(function () {
        // Execute on page load

      });
      $(window).resize(function () {
        // Execute code when the window is resized.
      });
      $(window).scroll(function () {
        // Execute code when the window scrolls.
      });
      $(document).ready(function () {
        // Execute code once the DOM is ready.
        alert('coucou');
      });
    }
  };
});
