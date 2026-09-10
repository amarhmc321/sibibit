(function($) {
  'use strict';
  $(function() {
    // Toggle sidebar ketika tombol diklik
    $('[data-toggle="offcanvas"]').on("click", function(event) {
      event.stopPropagation(); // Mencegah klik menyebar ke document
      $('.sidebar-offcanvas').toggleClass('active');
    });

    // Tutup sidebar jika klik di luar sidebar & navbar
    $(document).on("click", function(event) {
      if (!$('.sidebar-offcanvas').is(event.target) && 
          $('.sidebar-offcanvas').has(event.target).length === 0 &&
          !$('.navbar').is(event.target) &&
          $('.navbar').has(event.target).length === 0 &&
          !$(event.target).closest('[data-toggle="offcanvas"]').length) {
        $('.sidebar-offcanvas').removeClass('active');
      }
    });

  });
})(jQuery);
