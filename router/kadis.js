$(document).ready(function () {
  let defaultPage = "home-admin";
  let urlParams = new URLSearchParams(window.location.search);
  let savedPage =
    urlParams.get("page") || localStorage.getItem("activePage") || defaultPage;

   let savedId =
    urlParams.get("id") || localStorage.getItem("activeId") || null;

  let currentXHR = null;
  let currentPageEventNamespace = null;
  let currentPage = null; // Menyimpan halaman yang sedang aktif
  let pageCache = {}; // Cache untuk halaman yang sudah dimuat

  // ============================
  // MASTER CONFIG PAGE
  // ============================
  const pages = {
    "dash": {
      view: "views/kadis/dash.php",
      title: "Dashboard",
      scripts: ["modul/kadis/modulDash.js"],
    },

    "data-pengajuan": {
      view: "views/kadis/data-pengajuan.php",
      title: "Data Pengajuan",
      scripts: ["modul/kadis/modulPengajuan.js"],
    }, 

    "detail-groups": {
      view: "views/detail-groups.php",
      title: "Detail Kelompok",
      scripts: ["modul/detailGroups.js"],
    }, 

    "data-petani": {
      view: "views/data-petani.php",
      title: "Data Petani",
      scripts: ["modul/modulPetani.js"],
    }, 

    "all-notif": {
      view: "views/all-notif.php",
      title: "Data Petani",
      scripts: ["modul/allNotif.js"],
    }, 

    "data-laporan": {
      view: "views/kadis/data-laporan.php",
      title: "Data Laporan",
      scripts: ["modul/kadis/modulLaporan.js"],
    }, 

    "data-groups": {
      view: "views/kadis/data-groups.php",
      title: "Data Kelompok",
      scripts: ["modul/kadis/modulGroups.js"],
    },     

    "data-kecamatan": {
      view: "views/data-kecamatan.php",
      title: "Data Kecamatan",
      scripts: ["modul/modulKecamatan.js"],
    },

    "data-desa": {
      view: "views/data-desa.php",
      title: "Data Desa",
      scripts: ["modul/modulDesa.js"],
    },

    "data-lahan": {
      view: "views/kadis/data-lahan.php",
      title: "Data lahan",
      scripts: ["modul/kadis/modulLahan.js"],
    },
   
    users: {
      view: "views/users.php",
      title: "Pengguna",
      scripts: ["modul/modulUsers.js"],
    },

    profile: {
      view: "views/profile.php",
      title: "Profil",
      scripts: ["modul/modulProfile.js"],
    },
    setings: {
      view: "views/setings.php",
      title: "Setings",
      scripts: [],
    },
  };

  // ============================
  // Fungsi Capitalize fallback
  // ============================
  function capitalize(str) {
    return str.replace(/-/g, " ").replace(/\b\w/g, (l) => l.toUpperCase());
  }

  // ============================
  // Load Script per Page
  // ============================
  function loadScriptByPage(page) {
    $("script[data-page-script]").remove(); // bersihkan script lama
    const scripts = (pages[page] && pages[page].scripts) || [];
    scripts.forEach((src) => {
      let script = document.createElement("script");
      script.src = src;
      script.setAttribute("data-page-script", page);
      document.body.appendChild(script);
    });
  }

  // ============================
  // Load Page
  // ============================


  function loadPage(page, id = null) {
    // cegah reload page sama + id sama
    if (currentPage === page && savedId == id) {
      return;
    }

    $(document).off("submit.formTemplate");

    if (currentXHR && currentXHR.readyState !== 4) {
      currentXHR.abort();
    }

    if (currentPageEventNamespace) {
      $(document).off(`.page.${currentPageEventNamespace}`);
    }

    currentPageEventNamespace = page;
    currentPage = page;
    savedId = id;

    // ============================
    // UPDATE URL + STORAGE
    // ============================
    let url = "?page=" + page;
    if (id) url += "&id=" + id;

    history.pushState({ page, id }, "", url);

    localStorage.setItem("activePage", page);
    if (id) {
      localStorage.setItem("activeId", id);
    } else {
      localStorage.removeItem("activeId");
    }

    // ============================
    // CACHE KEY (FIX BUG)
    // ============================
    let cacheKey = page + (id ? "_" + id : "");

    if (pageCache[cacheKey]) {
      $("#content").html(pageCache[cacheKey]);
      setActiveMenu(page);

      $(document).trigger(`contentLoaded.page.${page}`, [id]);

      loadScriptByPage(page);
      $("#loadingOverlay").fadeOut(150);
      return;
    }

    $("#loaderText").text(
      "Memuat " + (pages[page]?.title || capitalize(page)) + "..."
    );
    $("#loadingOverlay").fadeIn(150);

    let $judul = $("#judul-halaman");
    if ($judul.length) {
      $judul.text(pages[page]?.title || capitalize(page));
    }

    let viewPath = pages[page]?.view || "views/404.html";

    currentXHR = $.get(viewPath)
      .done(function (html) {
        pageCache[cacheKey] = html;

        $("#content").html(html);
        setActiveMenu(page);

        // kirim ID ke module
        $(document).trigger(`contentLoaded.page.${page}`, [id]);

        loadScriptByPage(page);
      })
      .fail(function () {
        $.get("views/404.html", function (html404) {
          $("#content").html(html404);
          setActiveMenu(null);
        });
      })
      .always(function () {
        $("#loadingOverlay").fadeOut(150);
      });
  }



  // ============================
  // Set Active Menu
  // ============================
  function setActiveMenu(page) {
    $(".menu a").removeClass("active");
    $(".menu .collapse")
      .removeClass("show")
      .prev("a")
      .attr("aria-expanded", "false");

    let activeLink = $(`.menu a[data-page="${page}"]`);
    if (activeLink.length) {
      activeLink.addClass("active");

      let parentSubmenu = activeLink.closest("ul.collapse");
      if (parentSubmenu.length) {
        parentSubmenu.addClass("show");
        parentSubmenu
          .prev("a")
          .addClass("active")
          .attr("aria-expanded", "true");
      }
    }
  }

  // ============================
  // Event Click Menu
  // ============================
  $(".menu a")
    .off("click")
    .on("click", function (event) {
      let page = $(this).data("page");
      if (!page) return;

      event.preventDefault();
      loadPage(page);

      if ($(window).width() < 1078) {
        $("#sidebar").removeClass("mobile-visible");
        $(".sidebar-overlay").removeClass("d-block");
      }
    });

  $(document)
    .off("click", ".dropdown-item")
    .on("click", ".dropdown-item", function (event) {
      let page = $(this).data("page");
      if (!page) return;

      event.preventDefault();
      loadPage(page);
    });

  $(document)
    .off("click", ".card-dash")
    .on("click", ".card-dash, .activity-item, .view-all, .link-excel, .link", function (event) {
      let page = $(this).data("page");
      if (!page) return;

      event.preventDefault();
      loadPage(page);
    });

    $(document).off("click", ".detail").on("click", ".detail", function (event) {
   

    const page = $(this).data("page");
    const id = $(this).data("id") || null;

    loadPage(page, id);
    window.scrollTo(0, 0);
  });

    $(document).off("click", ".btn-notif-link, .is-read")
    .on("click", ".btn-notif-link, .is-read", function (event) {
      let page = $(this).data("page");
      let id = $(this).data("id");
      if (!page) return;

      event.preventDefault();
$.ajax({
    url: `controller/process/aksiNotifikasi.php?action=read`,
    type: "POST",
    data: {id},
    dataType: "json",
    success: function (response) {
     
      notifikasi();
    },
    error: function () {
      Popup.error("Gagal!", "Terjadi kesalahan saat memuat data.", 3000);
    },
  });

   loadPage(page);
      
    });

  // ============================
  // Load Halaman Pertama
  // ============================
   loadPage(savedPage, savedId);
  
  // Handler untuk tombol refresh/reload halaman
  $(document).on('click', '.refresh-page', function() {
    if (currentPage) {
      // Hapus dari cache dan muat ulang
      delete pageCache[currentPage];
      loadPage(currentPage);
    }
  });
});
