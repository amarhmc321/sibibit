function initTooltipsWithClose(
  selector = '[data-bs-toggle="tooltip"]',
  mobileBreakpoint = 768,
  mobileDuration = 5000
) {
  const $els = $(selector);

  // Simpan title asli sekali saja
  $els.each(function () {
    if (!$(this).data("tt-title")) {
      const t = $(this).attr("title") || "";
      $(this).data("tt-title", t);
      $(this).removeAttr("title"); // cegah tooltip default browser
    }
  });

  // Bersihkan instance lama
  function disposeAll() {
    $els.each(function () {
      const inst = bootstrap.Tooltip.getInstance(this);
      if (inst) inst.dispose();
    });
  }

  // Mode Mobile: show + auto hide + tombol X
  function initMobile() {
    $els.each(function () {
      const txt = $(this).data("tt-title") || "";
      $(this).tooltip({
        trigger: "manual",
        html: true,
        title: `
          <span class="tooltip-text">${txt}</span>
          <span class="tooltip-close" role="button" aria-label="Tutup">&times;</span>
        `,
        template: `
          <div class="tooltip" role="tooltip">
            <div class="tooltip-arrow"></div>
            <div class="tooltip-inner"></div>
          </div>
        `,
      });
      const inst = bootstrap.Tooltip.getInstance(this);
      inst.show();
      // auto-hide setelah mobileDuration ms
      setTimeout(() => {
        inst.hide();
      }, mobileDuration);
    });
  }

  // Mode Desktop: hover only, tanpa tombol X, tanpa auto-hide
  function initDesktop() {
    $els.each(function () {
      const txt = $(this).data("tt-title") || "";
      $(this).tooltip({
        trigger: "hover",
        html: true,
        title: `<span class="tooltip-text">${txt}</span>`,
        template: `
          <div class="tooltip" role="tooltip">
            <div class="tooltip-arrow"></div>
            <div class="tooltip-inner"></div>
          </div>
        `,
      });
    });
  }

  // Terapkan mode sesuai ukuran layar
  function applyMode() {
    disposeAll();
    if (window.innerWidth <= mobileBreakpoint) {
      initMobile();
    } else {
      initDesktop();
    }
  }

  // Klik tombol X (mobile)
  $(document)
    .off("click.tooltipClose")
    .on("click.tooltipClose", ".tooltip-close", function () {
      const $tip = $(this).closest(".tooltip");
      const id = $tip.attr("id");
      if (!id) return;
      $('[aria-describedby="' + id + '"]').each(function () {
        const inst = bootstrap.Tooltip.getInstance(this);
        if (inst) inst.hide();
      });
    });

  // Re-init on resize
  $(window).off("resize.tooltipResp").on("resize.tooltipResp", applyMode);

  // Init pertama kali
  applyMode();
}

function initTooltipsHoverDesktop(
  selector = '[data-bs-toggle="tooltip2"]',
  breakpoint = 768
) {
  const $els = $(selector);

  // Kalau mobile: buang semua tooltip & selesai
  if (window.innerWidth <= breakpoint) {
    $els.each(function () {
      const inst = bootstrap.Tooltip.getInstance(this);
      if (inst) inst.dispose();
    });
    return;
  }

  // Simpan title asli sekali
  $els.each(function () {
    if (!$(this).data("tt-title")) {
      const t = $(this).attr("title") || "";
      $(this).data("tt-title", t);
      $(this).removeAttr("title"); // cegah native title
    }
  });

  // Dispose instance lama
  $els.each(function () {
    const inst = bootstrap.Tooltip.getInstance(this);
    if (inst) inst.dispose();
  });

  // Init ulang (hover only)
  $els.each(function () {
    const txt = $(this).data("tt-title") || "";
    $(this).tooltip({
      trigger: "hover", // hanya hover
      html: true,
      title: `<span class="tooltip-text">${txt}</span>`,
      template: `
        <div class="tooltip" role="tooltip">
          <div class="tooltip-arrow"></div>
          <div class="tooltip-inner"></div>
        </div>
      `,
    });
  });
}
