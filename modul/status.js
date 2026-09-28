function permohonan(kode) {
  const jenisMap = {
    C: { label: "Cuti", class: "bg-cuti text-dark" },
    I: { label: "Izin", class: "bg-izin text-dark" },
    S: { label: "Sakit", class: "bg-sakit" },
  };

  const data = jenisMap[kode?.toUpperCase()];
  if (!data) {
    return `<span class="badge bg-light text-muted">-</span>`;
  }

  return `<span class="badge ${data.class}">${data.label}</span>`;
}

function pelanggaran(kode) {
  const jenisMap = {
    SP1: { label: "SP1", class: "bg-izin text-dark" },
    SP2: { label: "SP2", class: "bg-warning text-dark" },
    SP3: { label: "SP3", class: "bg-danger" },
  };

  if (!kode) {
    return `<span class="badge bg-light text-muted">-</span>`;
  }

  // Kalau tipe string (misal "sp1,sp2"), split jadi array
  let codes = Array.isArray(kode) ? kode : String(kode).split(",");

  const badges = codes.map(k => {
    const key = k.trim().toUpperCase(); // rapikan & kapital
    const data = jenisMap[key];
    if (!data) {
      return `<span class="badge bg-light text-muted">-</span>`;
    }
    return `<span class="badge ${data.class}">${data.label}</span>`;
  });

  return badges.join(" ");
}


function permohonan2(kode) {
  const jenisMap = {
    C: { label: "CUTI", class: "bg-cuti text-dark" },
    I: { label: "IZIN", class: "bg-izin text-dark" },
    S: { label: "SAKIT", class: "bg-sakit" },
  };

  const data = jenisMap[kode?.toUpperCase()];
  if (!data) {
    return `-`;
  }

  return data.label;
}

function tandaiDanFormatTanggalSmart({
  tableSelector = "#Permohonan",
  tanggalSelector = ".tgl-cek",
  statusHeaderText = "Status",
} = {}) {
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const bulanIndo = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  const table = document.querySelector(tableSelector);
  if (!table) return console.warn("Tabel tidak ditemukan:", tableSelector);

  // 🔎 DETEKSI KOLOM STATUS DULU
  const headerCells = table.querySelectorAll("thead tr th");
  let statusColIndex = -1;

  headerCells.forEach((th, index) => {
    const text = th.textContent.trim().toLowerCase();
    if (text === statusHeaderText.toLowerCase()) {
      statusColIndex = index;
    }
  });

  if (statusColIndex === -1) {
    console.warn(`Kolom dengan judul "${statusHeaderText}" tidak ditemukan`);
    return;
  }

  // 🔁 LOOP SEMUA BARIS TABEL BODY
  const rows = table.querySelectorAll("tbody tr");
  rows.forEach((row) => {
    const tglCell = row.querySelector(tanggalSelector);
    if (!tglCell) return;

    const statusCell = row.cells[statusColIndex];
    if (!statusCell) return;

    const statusText = statusCell.textContent.trim().toLowerCase();
    if (statusText !== "pending") return; // 💥 hanya jika Pending

    const tglText = tglCell.textContent.trim();
    const tgl = new Date(tglText);
    tgl.setHours(0, 0, 0, 0);

    if (!isNaN(tgl.getTime())) {
      const selisihHari = Math.floor((tgl - today) / (1000 * 60 * 60 * 24));

      // Format ke Indo
      const formattedDate = `${tgl.getDate()} ${
        bulanIndo[tgl.getMonth()]
      } ${tgl.getFullYear()}`;

      // Tentukan badge style dan tooltip
      let badgeClass = "bg-secondary";
      let tooltip = "Tanggal normal";

      if (selisihHari <= -3) {
        badgeClass = "bg-danger text-white";
        tooltip = "⚠️ Sudah lewat lebih dari 2 hari!";
      } else if (selisihHari === -1) {
        badgeClass = "bg-warning text-dark";
        tooltip = "⚠️ Sudah lewat 1 hari!";
      }

      tglCell.innerHTML = `<span data-bs-toggle="tooltip2" class="badge ${badgeClass}" title="${tooltip}">${formattedDate}</span>`;
    } else {
      console.warn(`Tanggal tidak valid: ${tglText}`);
    }
  });
}

function tandaiDanFormatTanggal(selector = ".tgl-cek") {
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Hapus jam: biar banding tanggal murni

  // Array bulan Indonesia
  const bulanIndo = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  document.querySelectorAll(selector).forEach((el) => {
    const tglText = el.textContent.trim();
    const tgl = new Date(tglText);
    tgl.setHours(0, 0, 0, 0);

    if (!isNaN(tgl.getTime())) {
      const selisihMs = tgl.getTime() - today.getTime();
      const selisihHari = Math.floor(selisihMs / (1000 * 60 * 60 * 24));

      // Reset class biar gak numpuk
      el.classList.remove("bg-danger", "text-white", "bg-warning", "text-dark");

      // Aturan pewarnaan
      if (selisihHari <= -3) {
        el.classList.add("bg-danger", "text-white");
        el.title = "Sudah lewat lebih dari 2 hari";
      } else if (selisihHari === -1) {
        el.classList.add("bg-warning", "text-dark");
        el.title = "Sudah lewat 1 hari";
      }

      // Format tanggal Indo dan tampilkan
      const tanggalIndo = `${tgl.getDate()} ${
        bulanIndo[tgl.getMonth()]
      } ${tgl.getFullYear()}`;
      el.textContent = tanggalIndo;
    } else {
      console.warn(`Tanggal tidak valid: ${tglText}`);
    }
  });
}


function aksiAdmin(id, tgl, ket, status){
 switch (parseInt(status)) {
    case 0:
      return `<button data-bs-toggle="tooltip2" title="Edit ${ket}" 
                        class="btn btn-secondary btn-sm btn-edit" data-id="${id}">
                            <span class="fa fa-edit"></span>
                        </button>
                        <button data-bs-toggle="tooltip2" title="Hapus ${ket}" 
                        class="btn btn-danger btn-sm btn-delete" data-id="${id}">
                            <span class="fa fa-trash"></span>
                        </button>`;
    case 1:
      return `<button data-bs-toggle="tooltip2" title="Edit ${ket}" 
                        class="btn btn-secondary btn-sm btn-edit" data-id="${id}">
                            <span class="fa fa-edit"></span>
                        </button>

      <button data-bs-toggle="tooltip2" title="Cetak ${ket}" 
                        class="btn btn-success btn-sm btn-cetak" data-id="${id}" data-tgl="${tgl}">
                            <span class="fa fa-print"></span>
              </button>

                        `;
    default:
      return `
      <button data-bs-toggle="tooltip2" title="Edit ${ket}" 
                        class="btn btn-secondary btn-sm btn-edit" data-id="${id}">
                            <span class="fa fa-edit"></span>
                        </button>
      <button data-bs-toggle="tooltip2" title="Hapus ${ket}" 
                        class="btn btn-danger btn-sm btn-delete" data-id="${id}">
                            <span class="fa fa-trash"></span>
                        </button>`;
  }
}

function aksi(id, tgl, ket, status){
 switch (parseInt(status)) {
    case 0:
      return `<button data-bs-toggle="tooltip2" title="Edit ${ket}" 
                        class="btn btn-secondary btn-sm btn-edit" data-id="${id}">
                            <span class="fa fa-edit"></span>
                        </button>
                        <button data-bs-toggle="tooltip2" title="Hapus ${ket}" 
                        class="btn btn-danger btn-sm btn-delete" data-id="${id}">
                            <span class="fa fa-trash"></span>
                        </button>`;
    case 1:
      return `<button data-bs-toggle="tooltip2" title="Cetak ${ket}" 
                        class="btn btn-success btn-sm btn-cetak" data-id="${id}" data-tgl="${tgl}">
                            <span class="fa fa-print"></span>
                        </button>`;
    default:
      return '-';
  }
}


function badge_bibit(jml_bibit, jns_bibit){
 switch (jns_bibit) {
    case "Benih":
      return `<span class="badge bg-success">${jml_bibit} PHN ${jns_bibit}</span>`;
    case "Pesnab":
      return `<span class="badge bg-hadir text-dark">${jml_bibit} LTR/KG ${jns_bibit} </span>`;

    default:
      return '<span class="badge bg-secondary">-</span>';
  }
}


function jns_bibit(jml_bibit, jns_bibit){
 switch (jns_bibit) {
    case "Benih":
      return `<span class="badge bg-success">${jml_bibit} PHN ${jns_bibit}</span>`;
    case "Pesnab":
      return `<span class="badge bg-hadir text-dark">${jml_bibit} LTR/KG ${jns_bibit} </span>`;

    default:
      return `<span class="badge bg-success">${jml_bibit} ${jns_bibit}</span>`;
  }
}

// Rumus bantuan bibit per hektar:
// Dinamis sesuai master data bibit (tbl_bibit.jml_per_hektar & tbl_bibit.satuan)
// Default jika belum diset: Kakao: 1000 benih/Ha; Sawit/Cengkeh/Kelapa Genjah: 100 pohon/Ha
function hitungBibit(namaBibit, judul, luasLahan, jmlPerHektarCustom, satuanCustom) {
  let luas = parseFloat(luasLahan) || 0;
  if (luas <= 0) {
    return {
      jumlah: 0,
      rate: 0,
      satuan: "",
      jenis: "",
      text: ""
    };
  }

  let textTarget = ((namaBibit || "") + " " + (judul || "")).toLowerCase();
  let rate = parseInt(jmlPerHektarCustom) || 0;
  let satuan = satuanCustom || "";
  let jenis = namaBibit || "Bibit";

  // Jika rate belum dispesifikasikan secara dinamis, fallback ke deteksi nama
  if (rate <= 0) {
    if (textTarget.includes("kakao")) {
      rate = 1000;
      satuan = satuan || "benih";
      jenis = "Kakao";
    } else if (textTarget.includes("sawit")) {
      rate = 100;
      satuan = satuan || "pohon";
      jenis = "Sawit";
    } else if (textTarget.includes("cengkeh")) {
      rate = 100;
      satuan = satuan || "pohon";
      jenis = "Cengkeh";
    } else if (textTarget.includes("kelapa genjah") || textTarget.includes("kelapa ganja") || textTarget.includes("kelapa")) {
      rate = 100;
      satuan = satuan || "pohon";
      jenis = "Kelapa Genjah";
    } else {
      rate = 100;
      satuan = satuan || "pohon";
      jenis = namaBibit || "Bibit";
    }
  } else {
    if (!satuan) satuan = "pohon";
  }

  let total = Math.round(luas * rate);
  let formattedTotal = new Intl.NumberFormat('id-ID').format(total);
  let formattedRate = new Intl.NumberFormat('id-ID').format(rate);

  return {
    jumlah: total,
    rate: rate,
    satuan: satuan,
    jenis: jenis,
    text: `${jenis} (${formattedRate} ${satuan}/Ha) × ${luas} Ha = ${formattedTotal} ${satuan}`
  };
}

function s_bibit(permohonan) {
  permohonan = parseInt(permohonan);
  switch (permohonan) {
    case 0:
      return '<span class="badge bg-danger" data-bs-toggle="tooltip2" title="Tidak Tersedia"><i class="fas fa-times-circle me-1"></i>Tidak Tersedia</span>';
    case 1:
      return '<span class="badge bg-success"  data-bs-toggle="tooltip2" title="Tesrsedia"><i class="fas fa-check-circle me-1"></i>Tersedia</span>';
    default:
      return '<span class="badge bg-secondary">-</span>';
  }
}




function formatStatus(permohonan) {
  permohonan = parseInt(permohonan);
  switch (permohonan) {
    case 0:
      return '<span class="badge bg-warning text-dark" data-bs-toggle="tooltip2" title="menunggu Persetujuan"><i class="fas fa-hourglass-half me-1"></i>Pending</span>';
    case 1:
      return '<span class="badge bg-success"  data-bs-toggle="tooltip2" title="Sudah Disetujui"><i class="fas fa-check-circle me-1"></i>Disetujui</span>';
    case 2:
      return '<span class="badge bg-danger"  data-bs-toggle="tooltip2" title="Ditolak"><i class="fas fa-times-circle me-1"></i>Ditolak</span>';
    default:
      return '<span class="badge bg-secondary">-</span>';
  }
}

function formatTTD(ttd, ket) {
  ttd = parseInt(ttd);
  switch (ttd) {
    case 0:
      return '<span class="badge bg-warning text-dark" data-bs-toggle="tooltip2" title="menunggu Persetujuan"><i class="fas fa-hourglass-half me-1"></i>Pending</span>';
    case 1:
      return `<span class="badge bg-success"  data-bs-toggle="tooltip2" title="Sudah Di Tanda Tanggani">${ket}<i class="fas fa-check ms-1"></i></span>`;
    case 2:
      return '<span class="badge bg-danger"  data-bs-toggle="tooltip2" title="Ditolak"><i class="fas fa-times-circle me-1"></i>Ditolak</span>';
    default:
      return '<span class="badge bg-secondary">-</span>';
  }
}

function hias(text, untuk) {
  // Normalisasi value
  const val = text !== null && text !== undefined ? String(text).trim() : "";

  // Cek kosong atau nol khusus untuk nik/idk
  if (!val || ((untuk === "nik" || untuk === "idk") && Number(val) === 0)) {
    return `<span class="badge bg-white text-dark">-</span>`;
  }

  const badgeClass = {
    kode: "bg-white text-dark",
    nik: "bg-white text-dark",
    idk: "bg-dark",
    rp: "bg-dark",
    plus: "bg-success",
  };

  const cls = badgeClass[untuk] || "bg-light text-muted";
  return `<span class="badge ${cls}">${val}</span>`;
}

function hias2(text, untuk) {
  // Normalisasi value
  const val = text !== null && text !== undefined ? String(text).trim() : "";

  // Cek kosong atau nol khusus untuk nik/idk
  if (!val || ((untuk === "nik" || untuk === "idk") && Number(val) === 0)) {
    return `<span class="badge bg-white text-dark">-</span>`;
  }

  const badgeClass = {
    kode: "bg-white text-dark",
    nik: "bg-white text-dark",
    idk: "bg-dark",
    rp: "bg-dark",
    plus: "bg-success",
  };

  const cls = badgeClass[untuk] || "bg-light text-dark";
  return `<span class="badge ${cls} role-gaji">${val}</span>`;
}

function getBadgeJabatan(id, jabatan, lokasi) {
  const badgeMap = [
    { id: 1, class: "bg-white text-dark" },
    { id: 2, class: "bg-success" },
    { id: 3, class: "bg-white text-dark" },
    { id: 4, class: "bg-white text-dark" },
  ];

  // Cari badge berdasarkan id
  const item = badgeMap.find((b) => b.id === id);

  if (item) {
    return `<span class="badge ${item.class} ms-2">${jabatan}-${lokasi}</span>`;
  }

  // Default badge kalau tidak ada yang cocok
  return ``;
}

function getBadgeKaryawan(tglMasuk) {
  if (!tglMasuk) return "";

  const masuk = new Date(tglMasuk);
  const now = new Date();

  // Hitung selisih hari
  const diffTime = now - masuk;
  const diffDays = diffTime / (1000 * 60 * 60 * 24);

  // Kalau karyawan masuk dalam 7 hari terakhir
  if (diffDays <= 7 && diffDays >= 0) {
    return `<span class="badge bg-warning text-dark">NEW</span>`;
  }

  return "";
}

function getHelmIcon(jabatan) {
  jbtn = jabatan.toLowerCase();

  if (
    jbtn.includes("Direktur") ||
    jbtn.includes("Manager") ||
    jbtn.includes("HR SITE") ||
    jbtn.includes("koordinator") ||
    jbtn.includes("admin") ||
    jbtn.includes("pengawas")
  ) {
    return `<span class="badge bg-dark"><i class="fas fa-hard-hat text-white me-1"></i>${jabatan}</span>`; // ⚪ putih
  }
  if (jbtn.includes("kepala")) {
    return `<span class="badge bg-dark"> <i class="fas fa-hard-hat text-info me-1"></i>${jabatan}</span>`; // 🔵 biru
  }
  if (jbtn.includes("safety") || jbtn.includes("hse")) {
    return `<span class="badge bg-dark"><i class="fas fa-hard-hat text-danger me-1"></i>${jabatan}</span>`; // 🔴 merah
  }
  if (jbtn.includes("tukang") || jbtn.includes("pekerja")) {
    return `<i class="fas fa-hard-hat text-warning me-1"></i>`; // 🟡 kuning
  }
  if (jbtn.includes("tamu") || jbtn.includes("training")) {
    return `<i class="fas fa-hard-hat text-success me-1"></i>`; // 🟢 hijau
  }

  return `<span class="badge bg-dark"><i class="fas fa-hard-hat text-warning me-1"></i>${jabatan}</span>`; // Kuning
}

function getHelmIcon2(id, jbtn) {
  if (id == 1 || id == 2 || id == 3 ||  id == 4 ) {
    return `<span class="badge bg-dark"><i class="fas fa-hard-hat text-white me-1"></i>${jbtn}</span>`; // ⚪ putih
  }

  return `<span class="badge bg-dark"><i class="fas fa-hard-hat text-warning me-1"></i>${jbtn}</span>`; // Kuning
}

function totalDpt(total, nama) {
  let colorClass;
  if (total == 0) {
    colorClass = "bg-white text-danger";
  } else if (total >= 1 && total <= 2) {
    colorClass = "bg-dark text-warning"; // kuning
  } else if (total >= 3 && total < 5) {
    colorClass = "bg-dark"; // biru
  } else {
    colorClass = "bg-success"; // hijau
  }

  return `<span class="badge ${colorClass}">${total} ${nama}</span>`;
}

function totalJbt(total, nama) {
  let colorClass;
  if (total == 0) {
    colorClass = "bg-white text-danger";
  } else if (total >= 1 && total <= 5) {
    colorClass = "bg-dark text-warning"; // kuning
  } else if (total >= 6 && total <= 10) {
    colorClass = "bg-dark"; // biru
  } else {
    colorClass = "bg-success"; // hijau
  }

  return `<span class="badge ${colorClass}">${total} ${nama}</span>`;
}

function g_action(id, total, nama) {
  let colorClass;
  if (total == 0) {
    colorClass = "bg-white text-danger";
  } else if (total >= 1 && total <= 5) {
    colorClass = "bg-dark text-warning"; // kuning
  } else if (total >= 6 && total <= 10) {
    colorClass = "bg-dark"; // biru
  } else {
    colorClass = "bg-success"; // hijau
  }

  return `<span class="badge ${colorClass} detail" data-bs-toggle="tooltip2" title="Klik Untuk lihat Angota" data-page="detail-groups" data-id="${id}">${total} ${nama}</span>`;
}

function totalKry(total, nama) {
  let colorClass;
  if (total == 0) {
    colorClass = "bg-white text-danger";
  } else if (total >= 1 && total <= 10) {
    colorClass = "bg-dark text-warning"; // kuning
  } else if (total >= 11 && total <= 20) {
    colorClass = "bg-dark"; // biru
  } else {
    colorClass = "bg-success"; // hijau
  }

  return `<span class="badge ${colorClass}">${total} ${nama}</span>`;
}

function jns_permohonan(permohonan, j_permohonan) {
  const labels = {
    1: ["Cuti 1", "Cuti 2", "Cuti 3"],
    2: ["Izin 1", "Izin 2", "Izin 3"],
  };

  const jenis = labels[permohonan];
  const index = parseInt(j_permohonan, 10) - 1;

  if (jenis && index >= 0 && index < jenis.length) {
    return jenis[index];
  }

  return "-";
}

function jns_permohonan2(jns_permohonan) {
  if (typeof jns_permohonan === "string") {
    jns_permohonan = jns_permohonan.split(",");
  }

  if (!Array.isArray(jns_permohonan)) return "-";

  const labelMap = {
    C1: "Cuti Tanpa Bayar",
    C2: "Cuti Triwulan",
    C3: "Cuti khusus",
    I1: "Izin Keluarga",
    I2: "Izin Melahirkan",
    I3: "Izin Lainnya",
  };

  const result = jns_permohonan.map((kode) => labelMap[kode] || kode);
  return result.join(", ");
}

function s_level(s_level) {
  const levelMap = {
    1: { label: "Admin", class: "bg-white text-dark" },
    2: { label: "Manager", class: "bg-success" },
  };

  const data = levelMap[parseInt(s_level)];
  if (!data) {
    return `<span class="badge bg-info text-dark">Karyawan</span>`;
  }

  return `<span class="badge ${data.class}">${data.label}</span>`;
}

function s_prestasi(s_prestasi) {
  const mapPrestasi = (code) => {
    switch (code) {
      case "P1":
        return "Bonus Kinerja";
      case "P2":
        return "Bonus Skil";
      case "P3":
        return "Lainnya";
      default:
        return null;
    }
  };

  // Jika bukan array tapi ada koma, ubah jadi array
  if (typeof s_prestasi === "string" && s_prestasi.includes(",")) {
    s_prestasi = s_prestasi.split(",").map((item) => item.trim());
  }

  if (Array.isArray(s_prestasi)) {
    return (
      s_prestasi
        .map(mapPrestasi)
        .filter((item) => item)
        .join(", ") || "-"
    );
  }

  return mapPrestasi(s_prestasi) || "-";
}

function s_pendidikan(s_pendidikan) {
  switch (parseInt(s_pendidikan)) {
    case 1:
      return "SD";
    case 2:
      return "SMP";
    case 3:
      return "SMA/SMK";
    case 4:
      return "S1";
    case 5:
      return "S2";
    case 6:
      return "S3";
    default:
      return "-";
  }
}

function s_kerja(s_kerja) {
  switch (parseInt(s_kerja)) {
    case 1:
      return "Proyek";
    case 2:
      return "Tetap";
    case 3:
      return "Putus Kontrak";
    default:
      return "-";
  }
}

function w_kerja(s_kerja) {
  switch (parseInt(s_kerja)) {
    case 1:
      return "Siang";
    case 2:
      return "Malam";
    default:
      return "-";
  }
}

function s_aktif(s_aktif) {
  switch (parseInt(s_aktif)) {
    case 1:
      return `<span class="badge bg-success">Aktif</span>`;
    case 2:
      return `<span class="badge bg-danger">Non Aktif</span>`;
    case 3:
      return `<span class="badge bg-warning text-dark">Cuti</span>`;
    case 4:
      return `<span class="badge bg-izin">Izin</span>`;
    case 5:
      return `<span class="badge bg-sakit text-dark">Sakit</span>`;
    case 6:
      return `<span class="badge bg-dns">Dinas</span>`;
    case 7:
      return `<span class="badge bg-libur text-dark">Libur</span>`;
    case 8:
      return `<span class="badge bg-tk">TK</span>`;
    case 9:
      return `<span class="badge bg-dark">Resign</span>`;
    default:
      return `<span class="badge bg-light text-muted">-</span>`;
  }
}

function formatIDR(value) {
  // if (value > 0) {
  return Intl.NumberFormat("id-ID").format(value);
  // }
  // return '-'
}

function formatPersenBadge(value, warna = "white") {
  if (value == null || isNaN(value)) {
    return `<span class="badge bg-secondary">0%</span>`;
  }

  const warnaMap = {
    hijau: "bg-success",
    merah: "bg-danger",
    white: "bg-white text-dark",
    kuning: "bg-warning text-dark",
    abu: "bg-secondary",
    hitam: "bg-dark",
    info: "bg-info text-dark",
  };

  const classWarna = warnaMap[warna.toLowerCase()] || "bg-primary";
  const formatted = parseFloat(value).toFixed(2).replace(/\.00$/, ""); // hapus .00 kalau bulat

  return `<span class="badge ${classWarna}">${formatted}%</span>`;
}

function formatIDRBadge(value, warna = "hijau") {
  if (value == null || isNaN(value)) {
    return `<span class="badge bg-secondary">Rp 0</span>`;
  }

  const warnaMap = {
    hijau: "bg-success",
    merah: "bg-danger text-white",
    biru: "bg-primary",
    kuning: "bg-warning text-dark",
    abu: "bg-secondary",
    hitam: "bg-dark",
    info: "bg-info text-dark",
  };

  const classWarna = warnaMap[warna.toLowerCase()] || "bg-success";
  const formatted = "Rp " + Intl.NumberFormat("id-ID").format(value);

  return `<span class="badge ${classWarna}">${formatted}</span>`;
}

function formatIDRBadge2(value, warna = "hijau") {
  if (value == null || isNaN(value)) {
    return `<span class="badge bg-secondary">Rp 0</span>`;
  }

  const warnaMap = {
    hijau: "bg-success",
    merah: "bg-danger",
    biru: "bg-primary",
    kuning: "bg-warning text-dark",
    abu: "bg-secondary",
    hitam: "bg-dark",
    info: "bg-info text-dark",
  };

  const classWarna = warnaMap[warna.toLowerCase()] || "bg-success";
  const formatted = Intl.NumberFormat("id-ID").format(value);

  return `<span class="badge ${classWarna}">${formatted}</span>`;
}

function formatTanggalIndo3(tglMulai, tglSelesai, s_permohonan) {
  if (!tglMulai) return "";

  const bulanIndo = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  function parseTanggal(tgl) {
    const parts = tgl.split("-");
    if (parts.length !== 3) return null;
    return new Date(parts[0], parts[1] - 1, parts[2]);
  }

  function formatSingleTanggal(tgl) {
    const d = parseTanggal(tgl);
    if (!d) return tgl;
    return `${d.getDate().toString().padStart(2, "0")} ${
      bulanIndo[d.getMonth()]
    } ${d.getFullYear()}`;
  }

  const startDate = parseTanggal(tglMulai);
  const endDate = tglSelesai ? parseTanggal(tglSelesai) : startDate;

  if (!startDate) return "";

  const sameYear = startDate.getFullYear() === endDate.getFullYear();

  let labelTanggal;
  if (sameYear) {
    // Contoh: 03 September - 07 September 2025
    labelTanggal = `${startDate.getDate().toString().padStart(2, "0")} ${
      bulanIndo[startDate.getMonth()]
    } - ${endDate.getDate().toString().padStart(2, "0")} ${
      bulanIndo[endDate.getMonth()]
    } ${endDate.getFullYear()}`;
  } else {
    // Kalau beda tahun tampilkan dua-duanya
    labelTanggal = `${formatSingleTanggal(tglMulai)} - ${formatSingleTanggal(
      tglSelesai
    )}`;
  }

  // === Tentukan warna badge ===
  const now = new Date();
  let badgeClass = "bg-secondary"; // default
  let title = "";

  if (s_permohonan === 0 && endDate < now) {
    badgeClass = "bg-danger"; // merah
    title = "Tanggal sudah lewat & belum diproses";
  } else {
    const diffDays = Math.ceil((startDate - now) / (1000 * 60 * 60 * 24));
    if (diffDays >= 0 && diffDays <= 3) {
      badgeClass = "bg-warning text-dark"; // kuning
      title = "Tanggal sudah dekat";
    } else {
      badgeClass = "bg-white text-dark"; // hijau default (aman)
      title = "Tanggal masih aman";
    }
  }

  return `<span class="badge ${badgeClass}" title="${title}">${labelTanggal}</span>`;
}

function formatTanggalIndo5(tglMulai, tglSelesai, s_permohonan) {
  if (!tglMulai) return "";

  const bulanIndo = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "Mei",
    "Jun",
    "Jul",
    "Agu",
    "Sep",
    "Okt",
    "Nov",
    "Des",
  ];

  function parseTanggal(tgl) {
    if (!tgl) return null;
    const d = new Date(tgl.replace(" ", "T"));
    return isNaN(d) ? null : d;
  }

  function formatTanggalDenganJam(d, includeYear = true) {
    const jam = d.getHours().toString().padStart(2, "0");
    const menit = d.getMinutes().toString().padStart(2, "0");
    let str = `${d.getDate().toString().padStart(2, "0")} ${
      bulanIndo[d.getMonth()]
    } ${jam}:${menit}`;
    if (includeYear) str += ` ${d.getFullYear()}`;
    return str;
  }

  const startDate = parseTanggal(tglMulai);
  const endDate = tglSelesai ? parseTanggal(tglSelesai) : startDate;
  if (!startDate) return "";

  const sameYear = startDate.getFullYear() === endDate.getFullYear();
  let labelTanggal;

  if (sameYear) {
    labelTanggal = `${formatTanggalDenganJam(
      startDate,
      false
    )} - ${formatTanggalDenganJam(endDate, true)}`;
  } else {
    labelTanggal = `${formatTanggalDenganJam(
      startDate,
      true
    )} - ${formatTanggalDenganJam(endDate, true)}`;
  }

  // === Tentukan warna badge ===
  const now = new Date();

  // Buat versi "tanggal saja" biar perhitungan hari tidak bias jam
  const onlyDate = (d) => new Date(d.getFullYear(), d.getMonth(), d.getDate());

  const diffDays = Math.floor(
    (onlyDate(startDate) - onlyDate(now)) / (1000 * 60 * 60 * 24)
  );

  let badgeClass = "bg-white text-dark";
  let title = "";

  if (s_permohonan == 0) {
    if (endDate < now) {
      badgeClass = "bg-danger";
      title = "Tanggal sudah lewat & belum diproses";
    } else if (diffDays >= 0 && diffDays <= 3) {
      badgeClass = "bg-warning text-dark";
      title = "Tanggal sudah dekat";
    } else {
      badgeClass = "bg-white text-dark";
      title = "Tanggal masih aman";
    }
  }

  return `<span class="badge ${badgeClass}" title="${title}">${labelTanggal}</span>`;
}

function formatTanggalIndo4(tglMulai, tglSelesai, s_permohonan) {
  if (!tglMulai) return "";

  const bulanIndo = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "Mei",
    "Jun",
    "Jul",
    "Agu",
    "Sep",
    "Okt",
    "Nov",
    "Des",
  ];

  function parseTanggal(tgl) {
    const parts = tgl.split("-");
    if (parts.length !== 3) return null;
    return new Date(parts[0], parts[1] - 1, parts[2]);
  }

  function formatSingleTanggal(tgl) {
    const d = parseTanggal(tgl);
    if (!d) return tgl;
    return `${d.getDate().toString().padStart(2, "0")} ${
      bulanIndo[d.getMonth()]
    } ${d.getFullYear()}`;
  }

  const startDate = parseTanggal(tglMulai);
  const endDate = tglSelesai ? parseTanggal(tglSelesai) : startDate;

  if (!startDate) return "";

  const sameYear = startDate.getFullYear() === endDate.getFullYear();

  let labelTanggal;
  if (sameYear) {
    // Contoh: 03 September - 07 September 2025
    labelTanggal = `${startDate.getDate().toString().padStart(2, "0")} ${
      bulanIndo[startDate.getMonth()]
    } - ${endDate.getDate().toString().padStart(2, "0")} ${
      bulanIndo[endDate.getMonth()]
    } ${endDate.getFullYear()}`;
  } else {
    // Kalau beda tahun tampilkan dua-duanya
    labelTanggal = `${formatSingleTanggal(tglMulai)} - ${formatSingleTanggal(
      tglSelesai
    )}`;
  }

  // === Tentukan warna badge ===
  const now = new Date();
  let badgeClass = "bg-secondary"; // default
  let title = "";

  if (s_permohonan === 0 && endDate < now) {
    badgeClass = "bg-white text-dark"; // merah
    title = "Tanggal sudah lewat & belum diproses";
  } else {
    const diffDays = Math.ceil((startDate - now) / (1000 * 60 * 60 * 24));
    if (diffDays >= 0 && diffDays <= 3) {
      badgeClass = "bg-warning text-dark"; // kuning
      title = "Tanggal sudah dekat";
    } else {
      badgeClass = "bg-white text-dark"; // hijau default (aman)
      title = "Tanggal masih aman";
    }
  }

  return `<span class="badge ${badgeClass}" title="${title}">${labelTanggal}</span>`;
}

function formatTanggalIndo(tanggal) {
  if (!tanggal) return ""; // Jika null/undefined, return kosong

  const bulanIndo = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  // Pecah tanggal jadi array [YYYY, MM, DD]
  const parts = tanggal.split("-");
  if (parts.length !== 3) return tanggal; // Kalau bukan format YYYY-MM-DD

  const tahun = parts[0];
  const bulan = parseInt(parts[1], 10) - 1; // array index 0-11
  const hari = parts[2];

  return `${parseInt(hari)} ${bulanIndo[bulan]} ${tahun}`;
}

function formatTgl(tanggal) {
  if (!tanggal) return ""; // Jika null/undefined, return kosong

  const bulanIndo = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "Mei",
    "Jun",
    "Jul",
    "Agu",
    "Sep",
    "Okt",
    "Nov",
    "Des",
  ];

  // Pecah tanggal jadi array [YYYY, MM, DD]
  const parts = tanggal.split("-");
  if (parts.length !== 3) return tanggal; // Kalau bukan format YYYY-MM-DD

  const tahun = parts[0];
  const bulan = parseInt(parts[1], 10) - 1; // array index 0-11
  const hari = parts[2];

  return `${parseInt(hari)} ${bulanIndo[bulan]} ${tahun}`;
}

function formatPeriode(tanggal) {
  if (!tanggal) return ""; // Jika null/undefined, return kosong

  const bulanIndo = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  // Pecah tanggal jadi array [YYYY, MM, DD]
  const parts = tanggal.split("-");
  if (parts.length !== 3) return tanggal; // Kalau bukan format YYYY-MM-DD

  const tahun = parts[0];
  const bulan = parseInt(parts[1], 10) - 1;
  const bulan2 = parseInt(parts[1], 10);
  const hari = parts[2];

  return `${bulanIndo[bulan]} - ${bulanIndo[bulan2]} ${tahun}`;
}

function formatPeriode2(tanggal) {
  if (!tanggal) return ""; // Jika null/undefined, return kosong

  const bulanIndo = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "Mei",
    "Jun",
    "Jul",
    "Agu",
    "Sep",
    "Okt",
    "Nov",
    "Des",
  ];

  // Pecah tanggal jadi array [YYYY, MM, DD]
  const parts = tanggal.split("-");
  if (parts.length !== 3) return tanggal; // Kalau bukan format YYYY-MM-DD

  const tahun = parts[0];
  const bulan = parseInt(parts[1], 10) - 1;
  const bulan2 = parseInt(parts[1], 10);
  const hari = parts[2];

  return `${bulanIndo[bulan]} - ${bulanIndo[bulan2]} ${tahun}`;
}

function formatTanggalIndo2(tanggal) {
  if (!tanggal) return ""; // Jika null/undefined, return kosong

  const bulanIndo = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  // Pecah tanggal jadi array [YYYY, MM, DD]
  const parts = tanggal.split("-");
  if (parts.length !== 3) return tanggal; // Kalau bukan format YYYY-MM-DD

  const tahun = parts[0];
  const bulan = parseInt(parts[1], 10) - 1; // array index 0-11
  const hari = parts[2];

  return `${parseInt(hari)} ${bulanIndo[bulan]}`;
}

function getNamaHari(tanggal) {
  const hariList = ["Mig", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];

  // Set default bulan dan tahun (misalnya Juli 2025)
  const bulan = 6; // 0-based, jadi 6 = Juli
  const tahun = 2025;

  const date = new Date(tanggal); // (YYYY, MM, DD)

  return isNaN(date) ? null : hariList[date.getDay()];
}

// gaji-handler.js
// Modul untuk meng-handle input gaji dengan AutoNumeric
const GajiHandler = (function () {
  let instances = {};

  // Konfigurasi default
  const defaultConfig = {
    digitGroupSeparator: ".",
    decimalCharacter: ",",
    decimalPlaces: 0,
    unformatOnSubmit: true,
    modifyValueOnWheel: false,
  };

  return {
    init(...selectors) {
      selectors.forEach((selector) => {
        // Kalau ada instance lama, hapus dulu
        if (AutoNumeric.getAutoNumericElement(selector)) {
          AutoNumeric.getAutoNumericElement(selector).remove();
        }

        // Buat instance baru
        instances[selector] = new AutoNumeric(selector, defaultConfig);
      });

      return this; // supaya bisa chaining
    },
    get(selector) {
      return instances[selector] ? instances[selector].getNumber() : null;
    },
    set(selector, value) {
      if (instances[selector]) {
        instances[selector].set(value);
      }
    },
    clear(selector) {
      if (instances[selector]) {
        instances[selector].clear();
      }
    },
    getAll() {
      let values = {};
      for (let key in instances) {
        values[key] = instances[key].getNumber();
      }
      return values;
    },
  };
})();
