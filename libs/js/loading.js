/**
 * Buat overlay sekali, reuse berikutnya.
 * options:
 * - text: teks loading awal
 * - autoProgress: true/false
 * - duration: ms sampai 100% kalau autoProgress (default 2000)
 * - logo: path logo (default img/logo/apotek-logo.png)
 */
function showLoading(options = {}) {
  const opt = Object.assign(
    {
      text: "Menyiapkan data medis...",
      autoProgress: true,
      duration: 1800, // Durasi ideal transisi halaman
      logo: "img/logo/logo.png", // Path disesuaikan ke aset apotek Anda
      slogan: "Melayani dengan Sepenuh Hati.",
    },
    options
  );

  let overlay = document.getElementById("loading-overlay");

  if (!overlay) {
    overlay = document.createElement("div");
    overlay.className = "loading-overlay";
    overlay.id = "loading-overlay";

    // Logo Container
    const logoContainer = document.createElement("div");
    logoContainer.className = "logo-container";
    const logoImg = document.createElement("img");
    logoImg.alt = "Logo PPID KOLAKA";
    logoImg.src = opt.logo;
    logoContainer.appendChild(logoImg);

    // Nama Apotek
    const companyName = document.createElement("div");
    companyName.className = "company-name";
    companyName.textContent = "PPID Kabuapten Kolaka";

    // Teks Loading Progresional
    const loadingText = document.createElement("div");
    loadingText.className = "loading-text";
    loadingText.id = "loading-text";
    loadingText.textContent = opt.text;

    // Kontainer Progress Bar
    const progressBar = document.createElement("div");
    progressBar.className = "progress-bar";
    const progress = document.createElement("div");
    progress.className = "progress";
    progress.id = "loading-progress";
    progressBar.appendChild(progress);

    // Slogan Kesehatan
    const slogan = document.createElement("div");
    slogan.className = "slogan";
    slogan.id = "loading-slogan";
    slogan.textContent = opt.slogan;

    // Satukan Komponen
    overlay.appendChild(logoContainer);
    overlay.appendChild(companyName);
    overlay.appendChild(loadingText);
    overlay.appendChild(progressBar);
    overlay.appendChild(slogan);
    document.body.appendChild(overlay);
  } else {
    // Update konten dinamis jika overlay sudah ter-render sebelumnya
    const lt = overlay.querySelector("#loading-text");
    if (lt) lt.textContent = opt.text;
    const lg = overlay.querySelector(".logo-container img");
    if (lg && opt.logo) lg.src = opt.logo;
    const ls = overlay.querySelector("#loading-slogan");
    if (ls) ls.textContent = opt.slogan;
  }

  // Tampilkan dengan transisi halus
  overlay.style.display = "flex";
  setTimeout(() => {
    overlay.style.opacity = "1";
  }, 10);

  if (opt.autoProgress) {
    autoAdvanceProgress(opt.duration);
  }
}

/** Internal: Jalankan progress bar */
function autoAdvanceProgress(duration) {
  const progressEl = document.getElementById("loading-progress");
  if (!progressEl) return;

  progressEl.style.width = "0%";

  const start = performance.now();
  function step(now) {
    const pct = Math.min(((now - start) / duration) * 100, 100);
    progressEl.style.width = pct.toFixed(1) + "%";
    if (pct < 100) {
      requestAnimationFrame(step);
    }
  }
  requestAnimationFrame(step);
}

/** Set progress manual jika diperlukan (0-100) */
function setLoadingProgress(pct) {
  const progressEl = document.getElementById("loading-progress");
  if (progressEl) {
    progressEl.style.width = Math.max(0, Math.min(100, pct)) + "%";
  }
}

/** Update teks loading di tengah jalan */
function setLoadingText(text) {
  const lt = document.getElementById("loading-text");
  if (lt) lt.textContent = text;
}

/** Sembunyikan loading overlay */
function hideLoading(remove = false) {
  const overlay = document.getElementById("loading-overlay");
  if (!overlay) return;
  
  overlay.style.transition = "opacity 0.3s ease, transform 0.3s ease";
  overlay.style.opacity = "0";
  
  setTimeout(() => {
    overlay.style.display = "none";
    if (remove) overlay.remove();
  }, 300);
}