$(document).ready(function () {
  const $searchWrapper = $(".search-wrapper");
  const $searchInput = $("#searchInput");
  const $searchIcon = $("#search-icon");
  const $notifikasi = $("#notifikasi");
  const $profil = $("#Profil");
  const $judul = $("#judul-halaman");
  const $sToggle = $("#sidebarToggle");

  // Klik icon search
  $searchIcon.on("click", function (e) {
    e.stopPropagation();
    $searchWrapper.addClass("active");
    $searchInput.focus();
    $searchIcon.hide();
    $profil.hide();
    $notifikasi.hide();
    $judul.hide();
    $sToggle.hide();
  });

  // Klik di dalam search wrapper jangan menutup
  $searchWrapper.on("click", function (e) {
    e.stopPropagation();
  });

  // Klik di luar search wrapper
  $(document).on("click", function () {
    $searchWrapper.removeClass("active");
    $searchInput.val("");
    $searchIcon.show();
    $profil.show();
    $notifikasi.show();
    $judul.show();
    $sToggle.show();
  });

  initTooltipsHoverDesktop();
  // Toggle sidebar untuk desktop
  function handleDesktopToggle() {
    $("#sidebar").toggleClass("collapsed");
    $("#page-content-wrapper").toggleClass("collapsed");
    // Simpan state di localStorage
    localStorage.setItem(
      "sidebarCollapsed",
      $("#sidebar").hasClass("collapsed")
    );
    localStorage.setItem(
      "pageCollapsed",
      $("#page-content-wrapper").hasClass("collapsed")
    );
  }

  // Toggle sidebar untuk mobile
  function handleMobileToggle() {
    $("#sidebar").toggleClass("mobile-visible");
    $(".sidebar-overlay").toggleClass("d-block");
  }

  // Handle toggle berdasarkan viewport
  $("#sidebarToggle").click(function () {
    if ($(window).width() >= 1078) {
      handleDesktopToggle();
    } else {
      handleMobileToggle();
    }
  });

  // Handle resize window
  $(window).resize(function () {
    if ($(window).width() >= 1078) {
      // Reset mobile state
      $("#sidebar").removeClass("mobile-visible");
      $("#page-content-wrapper").removeClass("mobile-visible");
      $(".sidebar-overlay").removeClass("d-block");

      // Apply desktop state dari localStorage
      const isCollapsed = localStorage.getItem("sidebarCollapsed") === "true";
      $("#sidebar").toggleClass("collapsed", isCollapsed);
    } else {
      // Reset desktop state
      $("#sidebar").removeClass("collapsed");
      $("#page-content-wrapper").removeClass("collapsed");
    }
  });

  // Tutup sidebar mobile dengan overlay
  $(".sidebar-overlay").click(function () {
    handleMobileToggle();
  });

  // Handle hover untuk desktop collapsed
  $("#sidebar").hover(
    function () {
      if ($(window).width() >= 1078 && $("#sidebar").hasClass("collapsed")) {
        $(this).addClass("hovered");
      }
    },
    function () {
      if ($(window).width() >= 1078 && $("#sidebar").hasClass("collapsed")) {
        $(this).removeClass("hovered");
      }
    }
  );

  // Inisialisasi state awal dari localStorage
  if ($(window).width() >= 1078) {
    const isCollapsed = localStorage.getItem("sidebarCollapsed") === "true";
    $("#sidebar").toggleClass("collapsed", isCollapsed);
    $("#page-content-wrapper").toggleClass("collapsed", isCollapsed);
  }
});
