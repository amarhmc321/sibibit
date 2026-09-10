// ======================= UI HELPER ======================= //


function showLoader(text = "Memuat...") {
  $("#loaderText").text(text);
  $("#loadingOverlay").fadeIn(150);
}

function hideLoader() {
  setTimeout(() => $("#loadingOverlay").fadeOut(150), 100);
}

function selectTamplate(
  dropdownParentSelector, 
  select = '#id-tim', 
  url = 'controller/process/getId.php?action=select-tim',
  placeholder = "Cari Data.."
  ) 
 {
  const $select = $(select);
  if ($select.length === 0) return; // biar gak error kalau elemennya belum ada

  $select.select2({
    placeholder: placeholder,
    width: "100%",
    dropdownParent: $(dropdownParentSelector),
    ajax: {
      url: url,
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          term: params.term,
        };
      },
      processResults: function (data) {
        return {
          results: data.map(function (item) {
            return {
              id: item.id,
              text: item.text,
            };
          }),
        };
      },
      cache: true,
    },
    minimumInputLength: 2,
  });

}

function setTableLoading(tableId, rows = 5) {
  const $table = $(tableId);
  const $tbody = $table.find("tbody");
  const $ths = $table.find("thead th");

  let placeholderHtml = "";

  for (let i = 0; i < rows; i++) {
    let row = '<tr class="tr-placeholder">';

    $ths.each(function () {
      let phType = "medium"; // default
      if ($(this).hasClass("ph-short")) phType = "short";
      if ($(this).hasClass("ph-medium")) phType = "medium";
      if ($(this).hasClass("ph-long")) phType = "long";
      if ($(this).hasClass("ph-full")) phType = "full";
      if ($(this).hasClass("ph-avatar")) {
        row += `<td>
                  <div class="d-flex align-items-center">
                    <div class="table-placeholder circle me-2"></div>
                    <div class="table-placeholder medium"></div>
                  </div>
                </td>`;
        return;
      }
      if ($(this).hasClass("ph-grub-all")) {
        row += `<td>
                  <div class="d-flex align-items-center mb-2">
                    <div class="table-placeholder circle me-2"></div>
                    <div class="table-placeholder medium me-2"></div>
                    <div class="table-placeholder short"></div>
                  </div>
                  <div class="d-flex align-items-center mb-2">
                    <div class="table-placeholder circle me-2"></div>
                    <div class="table-placeholder medium me-2"></div>
                    <div class="table-placeholder short"></div>
                  </div>
                  <div class="d-flex align-items-center">
                    <div class="table-placeholder circle me-2"></div>
                    <div class="table-placeholder medium me-2"></div>
                    <div class="table-placeholder short"></div>
                  </div>
                </td>`;
        return;
      }
      if ($(this).hasClass("ph-grub-one")) {
        row += `<td>
                  <div class="d-flex align-items-center mb-2">
                    <div class="table-placeholder circle me-2"></div>
                    <div class="table-placeholder medium me-2"></div>
                    <div class="table-placeholder short"></div>
                  </div>
                  <div class="d-flex align-items-center mb-2">
                    <div class="table-placeholder circle me-2"></div>
                    <div class="table-placeholder medium me-2"></div>
                    <div class="table-placeholder short"></div>
                  </div>
                  <div class="d-flex align-items-center">
                    <div class="table-placeholder circle me-2"></div>
                    <div class="table-placeholder medium me-2"></div>
                    <div class="table-placeholder short"></div>
                  </div>
                </td>`;
        return;
      }
      if ($(this).hasClass("ph-permohonan")) {
        row += `<td>
                    <div class="table-placeholder long"></div>
                </td>`;
        return;
      }
      if ($(this).hasClass("ph-alamat")) {
        row += `<td>
                  <div class="table-placeholder extra-long large"></div>
                </td>`;
        return;
      }
      if ($(this).hasClass("ph-keterangan")) {
        row += `<td>
                  <div class="table-placeholder extra-long large"></div>
                </td>`;
        return;
      }
      if ($(this).hasClass("ph-actions-1")) {
        row += `<td>
                  <div class="d-flex gap-2">
                    <div class="table-placeholder action-btn"></div>
                  </div>
                </td>`;
        return;
      }
      if ($(this).hasClass("ph-actions-2")) {
        row += `<td>
                  <div class="d-flex gap-2">
                    <div class="table-placeholder action-btn"></div>
                    <div class="table-placeholder action-btn"></div>
                  </div>
                </td>`;
        return;
      }

      row += `<td><div class="table-placeholder ${phType}"></div></td>`;
    });

    row += "</tr>";
    placeholderHtml += row;
  }

  $tbody.html(placeholderHtml);
  $(".table-placeholder").fadeIn(150);
 
}


function clearTableLoading(tableSelector) {
  const $table = $(tableSelector);
  $table.find("tr.tr-placeholder").remove();
  $table.find(".table-placeholder").remove();
}




function setTableLoading2(tableId, rows = 5) {
            const $table = $(tableId);
            const $tbody = $table.find("tbody");
            
            // Hitung jumlah kolom yang sebenarnya
            const $headerRows = $table.find("thead tr");
            let columnCount = 0;
            let columnClasses = [];
            
            // Proses setiap baris header untuk menghitung kolom
            $headerRows.each(function(rowIndex) {
                const $cells = $(this).find("th");
                let colIndex = 0;
                
                $cells.each(function() {
                    const $cell = $(this);
                    const colspan = parseInt($cell.attr("colspan") || "1");
                    const rowspan = parseInt($cell.attr("rowspan") || "1");
                    
                    // Jika cell ini memiliki rowspan, pastikan kita punya cukup ruang di columnClasses
                    if (rowIndex > 0 && rowspan > 1) {
                        // Cari kolom yang sesuai untuk cell ini
                        while (colIndex < columnClasses.length && columnClasses[colIndex] !== null) {
                            colIndex++;
                        }
                    }
                    
                    // Isi kolom dengan kelas placeholder
                    for (let i = 0; i < colspan; i++) {
                        if (colIndex >= columnClasses.length) {
                            columnClasses.push(null);
                        }
                        
                        // Jika ini adalah baris pertama atau cell memiliki rowspan
                        if (rowIndex === 0 || rowspan > 1) {
                            columnClasses[colIndex] = $cell.attr("class") || "";
                        }
                        
                        colIndex++;
                    }
                });
                
                // Pastikan columnCount selalu yang terbesar
                if (colIndex > columnCount) {
                    columnCount = colIndex;
                }
            });
            
            // Pastikan columnClasses memiliki panjang yang sesuai
            while (columnClasses.length < columnCount) {
                columnClasses.push("");
            }
            
            // Bangsu placeholder HTML
            let placeholderHtml = "";
            
            for (let i = 0; i < rows; i++) {
                let row = '<tr class="tr-placeholder">';
                
                columnClasses.forEach(cellClass => {
                    let phType = "medium"; // default
                    
                    if (!cellClass) cellClass = "";
                    
                    if (cellClass.includes("ph-short")) phType = "short";
                    if (cellClass.includes("ph-medium")) phType = "medium";
                    if (cellClass.includes("ph-long")) phType = "long";
                    if (cellClass.includes("ph-full")) phType = "full";
                    
                    if (cellClass.includes("ph-avatar")) {
                        row += `<td>
                            <div class="d-flex align-items-center">
                                <div class="table-placeholder circle me-2"></div>
                                <div class="table-placeholder medium"></div>
                            </div>
                        </td>`;
                        return;
                    }
                       
                    if (cellClass.includes("ph-alamat")) {
                        row += `<td>
                            <div class="table-placeholder extra-long large"></div>
                        </td>`;
                        return;
                    }
                    
                    if (cellClass.includes("ph-keterangan")) {
                        row += `<td>
                            <div class="table-placeholder extra-long xlarge"></div>
                        </td>`;
                        return;
                    }
                    
                    if (cellClass.includes("ph-actions-1")) {
                        row += `<td>
                            <div class="d-flex gap-2">
                                <div class="table-placeholder action-btn"></div>
                            </div>
                        </td>`;
                        return;
                    }
                    
                    row += `<td><div class="table-placeholder ${phType}"></div></td>`;
                });
                
                row += "</tr>";
                placeholderHtml += row;
            }
            
            $tbody.html(placeholderHtml);
            $table.addClass("loading");
        }
function clearTableLoading2(tableId) {
  $(tableId).removeClass("loading");
}




function escapeHTML(str) {
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function handleEditError(xhr, status, error) {
  console.error("Edit error:", error, xhr.responseText);
  Popup.error("Gagal!", "Terjadi kesalahan saat memuat data edit", 3000);
}
function handleLoadError(xhr, status, error) {
  // console.error("Load error:", status, error);
  Popup.error("Gagal!", "Terjadi kesalahan saat memuat data", 3000);
}

function formatDate(date) {
  let y = date.getFullYear();
  let m = String(date.getMonth() + 1).padStart(2, "0");
  let d = String(date.getDate()).padStart(2, "0");
  return `${y}-${m}-${d}`;
}

function generateDateRange(start, end) {
  let startDate = new Date(start);
  let endDate = new Date(end);
  let dates = [];

  while (startDate <= endDate) {
    dates.push(formatDate(startDate));
    startDate.setDate(startDate.getDate() + 1);
  }

  return dates;
}

function getKehadiran(status = null, jamMasuk = "", jamKeluar = "", jml_lembur = 0, tooltip = "") {
  let cellContent = "X",
      style = "",
      slhb = "",
      slhm = "";

  if (status) {
    tooltip += `<br>Status: ${getStatusName(status)}`;
    if (jamMasuk) tooltip += `<br>Masuk: ${jamMasuk}`;
    if (jamKeluar) tooltip += `<br>Keluar: ${jamKeluar}`;
    if (jml_lembur) tooltip += `<br>Lembur: ${jml_lembur} Jam`;

    const statusMap = {
      "H":       { content: "H", style: "background-color: #E2EFDA !important; color: #000 !important;" },
      "H1/2":    { content: "½", style: "background-color: #FF0000 !important; color: #FFFF00 !important;" },
      "A":       { content: "TK", style: "background-color: #C00000 !important; color: #fff !important;" },
      "TK":      { content: "TK", style: "background-color: #C00000 !important; color: #fff !important;" },
      "L":       { content: "L", style: "background-color: #FFFF00 !important;" },
      "LHB":     { content: "H", style: "background-color: #E2EFDA !important;", slhb: "background-color: #B2B2B2 !important;" },
      "LHM":     { content: "H", style: "background-color: #E2EFDA !important;", slhm: "background-color: #7B7B7B !important;" },
      "S":       { content: "S", style: "background-color: #002060 !important; color: #fff !important;" },
      "I":       { content: "I", style: "background-color: #9BC2E6 !important;" },
      "DS":      { content: "DS", style: "background-color: #548235 !important;" },
      "C":       { content: "C", style: "background-color: #FFE699 !important;" }
    };

    const data = statusMap[status] || { content: status, style2: "bg-primary" };
    cellContent = data.content;
    style = data.style || "";
    slhb = data.slhb || "";
    slhm = data.slhm || "";
  } else {
    tooltip += "<br>Status: Tidak Ada Data";
  }

  return { cellContent, style, slhb, slhm, tooltip };
}



// Fungsi untuk mendapatkan nama status
function getStatusName(code) {
  const statusMap = {
    H: "Hadir",
    "H1/2": "Setengah Hari",
    A: "Alpha/Tanpa Keterangan",
    TK: "Alpha/Tanpa Keterangan",
    L: "Libur",
    S: "Sakit",
    I: "Izin",
    DS: "Dinas",
    C: "Cuti",
    CT: "Cuti",
    LHB: "Lembur Hari Biasa",
    LHM: "Lembur Hari Minggu",
  };
  return statusMap[code] || `Lembur`;
}

function hitungSelisihWaktu(waktu) {
  if (!waktu) {
    return ""; // default kalau null/undefined/kosong
  }

  const waktuNotif = new Date(waktu);
  if (isNaN(waktuNotif)) {
    return "format waktu tidak valid"; // kalau string waktu aneh/invalid
  }

  const sekarang = new Date();
  const selisihDetik = Math.floor((waktuNotif - sekarang) / 1000);

  const rtf = new Intl.RelativeTimeFormat("id", { numeric: "auto" });

  if (Math.abs(selisihDetik) < 60) {
    return rtf.format(Math.round(selisihDetik), "second");
  } else if (Math.abs(selisihDetik) < 3600) {
    return rtf.format(Math.round(selisihDetik / 60), "minute");
  } else if (Math.abs(selisihDetik) < 86400) {
    return rtf.format(Math.round(selisihDetik / 3600), "hour");
  } else {
    return rtf.format(Math.round(selisihDetik / 86400), "day");
  }
}

function cekON(waktu) {
  if (!waktu) {
    return `<span class="badge bg-danger">Belum Pernah Aktif</span>`;
  }

  const waktuNotif = new Date(waktu);
  if (isNaN(waktuNotif)) {
    return `<span class="badge bg-warning text-dark">Format Waktu Tidak Valid</span>`;
  }

  const sekarang = new Date();
  const selisihDetik = Math.floor((sekarang - waktuNotif) / 1000);

  // Online
  if (selisihDetik < 59) {
    return `<span class="badge bg-success">Sedang Online</span>`;
  }

  const rtf = new Intl.RelativeTimeFormat("id", { numeric: "auto" });

 
  // < 1 jam
  if (selisihDetik < 3600) {
    return `<span class="badge bg-white text-dark">Aktif ${rtf.format(-Math.round(selisihDetik / 60), "minute")}</span>`;
  }
  // < 1 hari
  else if (selisihDetik < 86400) {
    return `<span class="badge bg-primary">Aktif ${rtf.format(-Math.round(selisihDetik / 3600), "hour")}</span>`;
  }
  // < 3 hari
  else if (selisihDetik < 86400 * 4) {
    return `<span class="badge bg-secondary ">Aktif ${rtf.format(-Math.round(selisihDetik / 86400), "day")}</span>`;
  }
  // < 7 hari
  else if (selisihDetik < 86400 * 7) {
    return `<span class="badge bg-warning text-dark">Aktif ${rtf.format(-Math.round(selisihDetik / 86400), "day")}</span>`;
  }
  // > 7 hari
  else {
    return `<span class="badge bg-dark">Aktif ${rtf.format(-Math.round(selisihDetik / 86400), "day")}</span>`;
  }
}



function makeStorageKey(module, selector) {
  return module + "_" + selector.replace("#", "");
}

function initFilterTemplate3(options) {
  const startDefault = moment().subtract(7, "days");
  const endDefault = moment().add(7, "days");

  const drpConfig = {
    startDate: startDefault,
    endDate: endDefault,
    locale: { format: "YYYY-MM-DD" },
    opens: "left",
    autoUpdateInput: false,
  };

  if (options.maxDays && Number.isInteger(options.maxDays)) {
    drpConfig.maxSpan = { days: options.maxDays };
  }

  const $input = $(options.dateSelector);
  $input.daterangepicker(drpConfig);

  // Apply dari DateRangePicker
  $input.on("apply.daterangepicker", function (ev, picker) {
    options.filterStartDate = picker.startDate.format("YYYY-MM-DD");
    options.filterEndDate = picker.endDate.format("YYYY-MM-DD");
    $input.val(options.filterStartDate + " to " + options.filterEndDate);
    if (typeof options.onChange === "function") options.onChange();
  });

  // Default value
  options.filterStartDate = startDefault.format("YYYY-MM-DD");
  options.filterEndDate = endDefault.format("YYYY-MM-DD");
  $input.val(options.filterStartDate + " to " + options.filterEndDate);

  // === NEW: Bisa diketik manual ===
  $input.on("change", function () {
    let val = $(this).val().trim();

    // format: YYYY-MM (ambil sebulan penuh)
    if (/^\d{4}-\d{2}$/.test(val)) {
      const start = moment(val + "-01", "YYYY-MM-DD");
      const end = start.clone().endOf("month");
      options.filterStartDate = start.format("YYYY-MM-DD");
      options.filterEndDate = end.format("YYYY-MM-DD");
    }
    // format: YYYY-MM-DD to YYYY-MM-DD
    else if (/^\d{4}-\d{2}-\d{2}\s+to\s+\d{4}-\d{2}-\d{2}$/.test(val)) {
      const [s, e] = val.split("to").map((v) => v.trim());
      options.filterStartDate = moment(s).format("YYYY-MM-DD");
      options.filterEndDate = moment(e).format("YYYY-MM-DD");
    }
    // format: YYYY-MM-DD (satu hari)
    else if (/^\d{4}-\d{2}-\d{2}$/.test(val)) {
      options.filterStartDate = val;
      options.filterEndDate = val;
    } else {
      // fallback ke default
      options.filterStartDate = startDefault.format("YYYY-MM-DD");
      options.filterEndDate = endDefault.format("YYYY-MM-DD");
      $(this).val(options.filterStartDate + " to " + options.filterEndDate);
    }

    if (typeof options.onChange === "function") options.onChange();
  });

  return options;
}


function initExtraFilters(module, filters, onChange) {
  if (!Array.isArray(filters)) return;

  filters.forEach((f) => {
    const $el = $(f.selector);
    if (!$el.length) return; // skip kalau element tidak ada

    const key = makeStorageKey(module || "default", f.selector);

    // restore dari localStorage
    let restored = false;
    if (f.persist) {
      const savedVal = localStorage.getItem(key);
      if (savedVal !== null) {
        $el.val(savedVal);
        restored = true;
      }
    }

    // binding event
    $el.off(f.event || "change").on(f.event || "change", () => {
      if (f.persist) {
        localStorage.setItem(key, $el.val());
      }
      if (typeof onChange === "function") {
        onChange(f.selector, $el.val());
      }
    });

    // trigger onChange sekali kalau restore berhasil
    if (restored && typeof onChange === "function") {
      onChange(f.selector, $el.val());
    }
  });
}


function initFilterTemplate(options) {
  const startDefault = moment().subtract(92, "days");
  const endDefault = moment();

  // Daterangepicker
  $(options.dateSelector).daterangepicker({
    startDate: startDefault,
    endDate: endDefault,
    locale: { format: "YYYY-MM-DD" },
    opens: "left",
    autoUpdateInput: false,
  });

  // Saat user apply manual
  $(options.dateSelector).on("apply.daterangepicker", function (ev, picker) {
    options.filterStartDate = picker.startDate.format("YYYY-MM-DD");
    options.filterEndDate = picker.endDate.format("YYYY-MM-DD");
    $(this).val(options.filterStartDate + " to " + options.filterEndDate);
    if (typeof options.onChange === "function") options.onChange();
  });

  // Nilai default
  options.filterStartDate = startDefault.format("YYYY-MM-DD");
  options.filterEndDate = endDefault.format("YYYY-MM-DD");
  $(options.dateSelector).val(
    options.filterStartDate + " to " + options.filterEndDate
  );

  // Extra filter (status, lokasi, dll)
  if (Array.isArray(options.extraFilters)) {
    options.extraFilters.forEach((f) => {
      const $el = $(f.selector);
      const key = makeStorageKey(options.module || "default", f.selector);

      // restore dari localStorage
      if (f.persist) {
        const savedVal = localStorage.getItem(key);
        if (savedVal !== null) {
          $el.val(savedVal);
        }
      }

      $el.off(f.event || "change").on(f.event || "change", () => {
        if (f.persist) {
          localStorage.setItem(key, $el.val());
        }
        if (typeof options.onChange === "function") options.onChange();
      });
    });
  }

  return options; // supaya bisa diakses lagi
}

function initFilterTemplate2(options) {
  const startDefault = moment().subtract(6, "days");
  const endDefault = moment();

  // Daterangepicker
  $(options.dateSelector).daterangepicker({
    startDate: startDefault,
    endDate: endDefault,
    locale: { format: "YYYY-MM-DD" },
    opens: "left",
    autoUpdateInput: false,
  });

  // Saat user apply manual
  $(options.dateSelector).on("apply.daterangepicker", function (ev, picker) {
    options.filterStartDate = picker.startDate.format("YYYY-MM-DD");
    options.filterEndDate = picker.endDate.format("YYYY-MM-DD");
    $(this).val(options.filterStartDate + " to " + options.filterEndDate);
    if (typeof options.onChange === "function") options.onChange();
  });

  // Nilai default
  options.filterStartDate = startDefault.format("YYYY-MM-DD");
  options.filterEndDate = endDefault.format("YYYY-MM-DD");
  $(options.dateSelector).val(
    options.filterStartDate + " to " + options.filterEndDate
  );

  // Extra filter
  if (Array.isArray(options.extraFilters)) {
    options.extraFilters.forEach((f) => {
      const $el = $(f.selector);

      // restore value dari localStorage
      const savedVal = localStorage.getItem(f.selector);
      if (savedVal !== null) {
        $el.val(savedVal);
      }

      $el.off(f.event || "change").on(f.event || "change", () => {
        // simpan value ke localStorage
        localStorage.setItem(f.selector, $el.val());
        if (typeof options.onChange === "function") options.onChange();
      });
    });
  }

  return options; // supaya bisa diakses lagi
}

function setupSelect(dropdownParentSelector) {
  const $select = $("#id_karyawan");
  if ($select.length === 0) return; // biar gak error kalau elemennya belum ada

  $select.select2({
    placeholder: "Cari Karyawan...",
    width: "100%",
    dropdownParent: $(dropdownParentSelector),
    ajax: {
      url: "controller/process/getId.php?action=select",
      dataType: "json",
      delay: 250,
      data: function (params) {
        let lokasi = $("#filter-lokasi").length
          ? $("#filter-lokasi").val()
          : "";
        return {
          term: params.term,
          lokasi: lokasi,
        };
      },
      processResults: function (data) {
        return {
          results: data.map(function (item) {
            return {
              id: item.id_karyawan,
              text: `${item.nama_karyawan} (${String(item.id_karyawan).padStart(
                5,
                "0"
              )})`,
              id_lokasi: item.id_lokasi,
              id_jabatan: item.id_jabatan,
              nama_karyawan: item.nama_karyawan,
              nama_lokasi: item.nama_lokasi,
              nama_jabatan: item.nama_jabatan,
            };
          }),
        };
      },
      cache: true,
    },
    minimumInputLength: 2,
  });

  $select.off("select2:select").on("select2:select", function (e) {
    const selected = e.params.data;
    $("#id_lokasi").val(selected.id_lokasi);
    $("#id_jabatan").val(selected.id_jabatan);
    $("#nama_karyawan").val(selected.nama_karyawan);
    $("#nama_lokasi").val(selected.nama_lokasi);
    $("#nama_jabatan").val(selected.nama_jabatan);
  });
}

function generateKode3Huruf(nama) {
  // 1. Bersihkan input
  const clean = $.trim(nama || "")
    .toUpperCase()
    .replace(/[^A-Z\s]/g, "");
  const words = clean.split(/\s+/).filter((w) => w);

  // 2. Jika kosong → XXX
  if (words.length === 0) return "XXX";

  const vokal = new Set(["A", "E", "I", "O", "U"]);
  let kode = "";

  if (words.length === 1) {
    const w = words[0];

    if (w.length === 3) {
      return w; // persis 3 huruf
    } else if (w.length < 3) {
      return w.padEnd(3, "X"); // kurang dari 3 → pad X
    } else {
      // >3 huruf: cek jumlah konsonan di sisa string
      const sisa = w.slice(1); // buang huruf pertama
      const konsonan = [...sisa].filter((ch) => !vokal.has(ch));

      if (konsonan.length >= 2) {
        // cukup konsonan → ambil first + 2 konsonan
        return w[0] + konsonan[0] + konsonan[1];
      } else {
        // konsonan kurang → fallback ke 3 huruf pertama
        return w.substring(0, 3);
      }
    }
  } else if (words.length === 2) {
    const [w1, w2] = words;
    const h1 = w1[0] || "X";
    const h2 = w1[1] || "X";
    const h3 = w2[0] || "X";
    return h1 + h2 + h3;
  } else {
    // ≥3 kata
    const h1 = words[0][0] || "X";
    const h2 = words[1][0] || "X";
    const h3 = words[2][0] || "X";
    return h1 + h2 + h3;
  }
}

function initKodeGenerator() {
  $(".input-nama")
    .off("input.kodeGen")
    .on("input.kodeGen", function () {
      const nama = $(this).val();
      const target = $(this).data("kode-target");
      $(target).val(generateKode3Huruf(nama));
    });
}

function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function () {
    const output = document.getElementById("profilePreview");
    output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);
}

function setDefaultTime(selector, date = new Date()) {
  const jam = String(date.getHours()).padStart(2, "0");
  const menit = String(date.getMinutes()).padStart(2, "0");
  const waktu = `${jam}:${menit}`;
  $(selector).val(waktu);
}

function getNowTime(date = new Date()) {
  const jam = String(date.getHours()).padStart(2, "0");
  const menit = String(date.getMinutes()).padStart(2, "0");
  return `${jam}:${menit}`;
}

function initDateRangeValidator(
  startSelector,
  endSelector,
  autoEnd = false,
  offsetDays = 0
) {
  const $start = $(startSelector);
  const $end = $(endSelector);

  // Saat tanggal mulai diubah
  $start.on("change", function () {
    const startDate = $start.val();
    if (!startDate) return;

    if (autoEnd && offsetDays > 0) {
      const endDate = calculateEndDate(startDate, offsetDays);
      $end.val(endDate || "");
    }
  });

  // Validasi saat tanggal selesai berubah
  $end.on("change", function () {
    const startDate = new Date($start.val());
    const endDate = new Date($end.val());

    if (!startDate || !endDate || isNaN(startDate) || isNaN(endDate)) return;

    if (endDate < startDate) {
      alert("Tanggal selesai tidak boleh lebih awal dari tanggal mulai");
      $end.val("");
    }
  });
}

function initLookup(options) {
  const {
    inputSelector, // ID/kelas input yang diketik
    targetSelector, // ID/kelas input tujuan (misal untuk isi nama)
    url, // URL AJAX
    paramName = "id_karyawan", // Nama parameter untuk dikirim (default)
    responseKey = "nama_karyawan", // Kunci data di response JSON
    placeholder = "Data tidak ditemukan", // Pesan kalau data nggak ada
    minLength = 3, // Jumlah minimal karakter sebelum AJAX dijalankan
  } = options;

  $(document).on("keyup change", inputSelector, function () {
    let value = $(this).val().trim();

    // Kalau kosong, kosongin target-nya
    if (value === "") {
      $(targetSelector).val("");
      return;
    }

    // Tambahan: Minimal panjang karakter sebelum AJAX jalan
    if (value.length < minLength) {
      $(targetSelector).val("Ketik minimal " + minLength + " karakter...");
      return;
    }

    $.ajax({
      url: url,
      type: "GET",
      data: {
        action: "search",
        [paramName]: value,
      },
      dataType: "json",
      success: function (response) {
        if (response && response[responseKey]) {
          $(targetSelector).val(response[responseKey]);
        } else {
          $(targetSelector).val(placeholder);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        $(targetSelector).val("Error mengambil data");
      },
    });
  });
}

function initLookup2(options) {
  const {
    inputSelector,
    targetSelector1,
    targetSelector2,
    targetSelector3,
    targetSelector4,
    targetSelector5,
    url,
    paramName = "id_karyawan",
    placeholder = "Data tidak ditemukan",
    minLength = 2,
    delay = 300, // delay debounce dalam milidetik
  } = options;

  const targetSelectors = [
    targetSelector1,
    targetSelector2,
    targetSelector3,
    targetSelector4,
    targetSelector5,
  ];

  const setField = (selector, value) => {
    const $el = $(selector);
    if ($el.is("img")) {
      const imagePath = value
        ? `img/karyawan/${value}`
        : "img/karyawan/default.png";
      $el.attr("src", imagePath);
    } else {
      $el.val(value || "");
    }
  };

  const resetField = (selector) => {
    const $el = $(selector);
    if ($el.is("img")) {
      $el.attr("src", "img/karyawan/default.png");
    } else {
      $el.val("");
    }
  };

  const resetAll = () => {
    targetSelectors.forEach(resetField);
  };

  const setAllFields = (values) => {
    values.forEach((val, i) => {
      setField(targetSelectors[i], val);
    });
  };

  const setPlaceholders = (type = "notFound") => {
    const fallback = {
      notFound: [
        "default.png",
        placeholder,
        placeholder,
        placeholder,
        placeholder,
      ],
      error: ["default.png", "Error", "Error", "Error", "Error"],
    };
    setAllFields(fallback[type]);
  };

  // === DEBOUNCE FUNCTION ===
  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

  const handleLookup = function () {
    const value = $(this).val().trim();

    if (value.length < minLength) {
      resetAll();
      return;
    }

    $.ajax({
      url: url,
      type: "GET",
      data: {
        action: "searchAll",
        [paramName]: value,
      },
      dataType: "json",
      success: function (response) {
        if (response && response.nama_karyawan) {
          setAllFields([
            response.foto,
            response.nama_karyawan,
            response.lokasi,
            response.divisi,
            response.jabatan,
          ]);
        } else {
          setPlaceholders("notFound");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        setPlaceholders("error");
      },
    });
  };

  const debouncedHandler = debounce(handleLookup, delay);

  $(document).on("keyup change", inputSelector, debouncedHandler);
}

function logout() {
  // Tampilkan loading custom kamu
  showLoading({
    text: "Sedang logout...",
    autoProgress: true,
    duration: 1500, // durasi animasi progres
  });

  $.ajax({
    url: "controller/process/aksiUsers.php?action=logout",
    type: "POST",
    dataType: "json",
    success: function (res) {
      if (res.status === "success") {
        Popup.success(
          "Logout berhasil!",
          "Anda akan diarahkan ke halaman login.",
          2000
        );
        setLoadingText("Logout berhasil! Mengalihkan...");
        setLoadingProgress(100);

        setTimeout(() => {
          window.location.href = "index.php";
        }, 1500);
      } else {
        Popup.error("Gagal!", "Logout gagal. Silakan coba lagi.", 3000);
        setLoadingText("Logout gagal!");
        setLoadingProgress(100);
      }
    },
    error: function (xhr) {
      console.error("Error response:", xhr.responseText);
      Popup.error("Gagal!", "Terjadi kesalahan saat logout.", 3000);
      setLoadingText("Terjadi kesalahan!");
      setLoadingProgress(100);
    },
    complete: function () {
      setTimeout(() => {
        hideLoading();
      }, 500);
    },
  });
}

function cetak(link) {
  Popup.success("Download Success!", "Data telah di-download.", 3000);
  setTimeout(function () {
    window.location.href = link;
  }, 500); // 0.5 detik udah cukup buat nampilin popup
}

function dbToInputDateTime(dbDateTime) {
  if (!dbDateTime) return "";
  // "2025-08-07 05:44:41" → Date object
  const d = new Date(dbDateTime.replace(" ", "T"));
  if (isNaN(d)) return "";

  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, "0");
  const day = String(d.getDate()).padStart(2, "0");
  const hours = String(d.getHours()).padStart(2, "0");
  const minute = String(d.getMinutes()).padStart(2, "0");

  return `${year}-${month}-${day}T${hours}:${minute}`;
}

function inputToDbDateTime(inputValue) {
  if (!inputValue) return "";
  // "2025-08-07T05:44" → "2025-08-07 05:44:00"
  return inputValue.replace("T", " ") + ":00";
}

function formTemplate(selector, options = {}) {
  const namespace = `submit.formTemplate.${selector}`;

  // Pastikan hapus event lama khusus namespace ini
  $(document).off(`submit.${namespace}`, selector);

  $(document).on(`submit.${namespace}`, selector, function (event) {
    event.preventDefault();

    const $form = $(this);

    if (!$form[0].checkValidity()) {
      event.stopPropagation();
      $form.addClass("was-validated");
      return;
    }
    $form.addClass("was-validated");

    const endpoint = $form.data("endpoint") || options.defaultEndpoint;
    const action = $form.find('[name="action"]').val() || "create";
    const formData = new FormData(this);

    if (typeof options.beforeSend === "function") {
      options.beforeSend(formData, $form);
    }
    showLoader("Simpan Data...");
    $.ajax({
      url: `${endpoint}?action=${action}`,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.message) {
          Popup.read("Success!", response.message, 10000);
          $form[0].reset();
          $form.removeClass("was-validated");

          const targetModal = $form.data("target-modal");
          if (targetModal) {
            const modalEl = document.getElementById(targetModal);
            if (modalEl) {
              const modalInstance =
                bootstrap.Modal.getInstance(modalEl) ||
                new bootstrap.Modal(modalEl);
              modalInstance.hide();
            } else {
              console.warn(`Modal dengan id #${targetModal} tidak ditemukan!`);
            }
          }

          autoReload(endpoint, $form);

          if (typeof options.afterSuccess === "function") {
            options.afterSuccess(response, $form);
          }
        } else {
          Popup.error("Gagal!", response.error, 3000);
        }
      },
      error: function () {
        Popup.error("Gagal!", "Terjadi Kesalahan Saat Memproses Data", 3000);
      },
      complete: function () {
        setTimeout(hideLoader, 100);
      },
    });
  });

  function autoReload(endpoint, $form) {
    const reloadFunction = $form.data("reload-function");
    if (reloadFunction && typeof window[reloadFunction] === "function") {
      window[reloadFunction]();
      return;
    }

    const match = endpoint.match(/aksi(\w+)\.php/i);
    if (match) {
      const funcName = "load" + match[1];
      if (typeof window[funcName] === "function") {
        window[funcName]();
      } else {
        console.warn(`Fungsi ${funcName}() tidak ditemukan!`);
      }
    }
  }
}
