<script type="text/javascript">
// Variabel global untuk map dan marker
let map;
let marker;

$(document).ready(function () {
  loadOptions4(
    "controller/process/getKecamatan.php",
    ["#filter-kecamatan", "#id-kecamatan"],
    "id_kecamatan", "nama_kecamatan", loadLahan
  );

  $("#filter-kecamatan").on("change", function () {
    loadLahan();
  });

  selectTamplate("#modalLahan", "#id-petani", "controller/process/getId.php?action=select-petani");

  formTemplate("#form-petani", { // Pastikan ID form sesuai (di HTML id="form-petani")
    defaultEndpoint: "controller/process/aksiLahan.php",
  });

  // --- INISIALISASI MAP SAAT MODAL TAMPIL ---
  $('#modalLahan').on('shown.bs.modal', function () {
    // Jika map belum ada, inisialisasi
    if (!map) {
        // Set default koordinat (Contoh: Tengah Indonesia / Sesuaikan dengan daerah Anda)
        let defaultLat = -0.789275; 
        let defaultLng = 113.921327;

        map = L.map('map-lahan').setView([defaultLat, defaultLng], 5);

        // Tambahkan layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan Marker yang bisa di-drag (digeser)
        marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

        // Event: Saat marker selesai digeser, update input
        marker.on('dragend', function (e) {
            let pos = marker.getLatLng();
            $("#latitude").val(pos.lat.toFixed(6));
            $("#longitude").val(pos.lng.toFixed(6));
        });

        // Event: Saat peta diklik, pindahkan marker dan update input
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            $("#latitude").val(e.latlng.lat.toFixed(6));
            $("#longitude").val(e.latlng.lng.toFixed(6));
        });
    }

    // WAJIB: Fix ukuran peta jika dirender di dalam modal Bootstrap yang awalnya hidden
    setTimeout(function() {
        map.invalidateSize();
    }, 200);
  });
});

// ... (Fungsi loadLahan() tetap sama seperti milik Anda) ...

// Fungsi untuk membuka modal edit data
function editLahan(id_lahan) {
  $.ajax({
    url: `controller/process/aksiLahan.php?action=detail`,
    type: "POST",
    data: {id_lahan},
    dataType: "json",
    success: function (data) {
      $("#modalLahan").modal("show");
      $(".modal-title").text("Edit Data Lahan");
      $("#action").val("update");
      $("#submitButton").text("Update");

      $("#id-lahan").val(data.id_lahan);
      $("#id-kecamatan").val(data.id_kecamatan).trigger("change");
      $("#luas-lahan").val(data.luas_lahan);
      
      let option_petani = new Option(`${data.nama_petani}`, data.id_petani, true, true);
      $("#id-petani").append(option_petani).trigger("change");

      loadDesa(data.id_kecamatan, data.id_desa);
      
      // --- UPDATE KOORDINAT DAN PETA SAAT EDIT ---
      $("#latitude").val(data.latitude);
      $("#longitude").val(data.longitude);

      // Tunggu modal selesai animasi lalu update posisi map
      setTimeout(function() {
          if(map && marker && data.latitude && data.longitude) {
              let latLng = new L.LatLng(data.latitude, data.longitude);
              marker.setLatLng(latLng);
              map.setView(latLng, 15); // Zoom level 15 agar lebih dekat
          }
      }, 300);
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link", 3000);
    },
  });
}

// ... (Fungsi loadDesa tetap sama) ...

// Fungsi untuk mengosongkan modal
$("#modalLahan").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".needs-validation").removeClass("was-validated");
  $("#id-lahan").prop("disabled", false);
  $("#id-petani").val("").trigger("change");
  $("#action").val("create");
  $(".modal-title").text("Tambah Data Lahan");

  // Reset koordinat map jika ada form tambah baru
  $("#latitude").val("");
  $("#longitude").val("");
  if(map && marker) {
      let defaultLat = -0.789275; // Kembalikan ke default awal
      let defaultLng = 113.921327;
      let latLng = new L.LatLng(defaultLat, defaultLng);
      marker.setLatLng(latLng);
      map.setView(latLng, 5);
  }
});
</script>


<script type="text/javascript">
  let map;
let marker;

// Koordinat Tengah Kabupaten Kolaka
const kolakaLat = -4.0435;
const kolakaLng = 121.5815;

$(document).ready(function () {
  
  // ... (Kode inisialisasi loadOptions4 dan formTemplate tetap sama) ...

  // --- INISIALISASI MAP SAAT MODAL TAMPIL ---
  $('#modalLahan').on('shown.bs.modal', function () {
    if (!map) {
        // 1. Buat Peta, set default ke Kolaka, Zoom level 10
        map = L.map('map-lahan', {
            center: [kolakaLat, kolakaLng],
            zoom: 10,
            minZoom: 8 // Mencegah user zoom out terlalu jauh keluar dari sulawesi
        });

        // 2. Load Tiles (Tampilan Peta)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // 3. Tambahkan Marker bisa digeser
        marker = L.marker([kolakaLat, kolakaLng], {draggable: true}).addTo(map);

        // 4. Fitur Pencarian (Geocoder)
        L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: "Cari desa/lokasi di Kolaka..."
        })
        .on('markgeocode', function(e) {
            let latlng = e.geocode.center;
            marker.setLatLng(latlng);
            map.setView(latlng, 15); // Zoom dekat setelah dicari
            
            $("#latitude").val(latlng.lat.toFixed(6));
            $("#longitude").val(latlng.lng.toFixed(6));
        })
        .addTo(map);

        // 5. Update Input saat Marker digeser
        marker.on('dragend', function (e) {
            let pos = marker.getLatLng();
            $("#latitude").val(pos.lat.toFixed(6));
            $("#longitude").val(pos.lng.toFixed(6));
        });

        // 6. Update Input & Marker saat peta diklik
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            $("#latitude").val(e.latlng.lat.toFixed(6));
            $("#longitude").val(e.latlng.lng.toFixed(6));
        });
    }

    // Refresh ukuran peta (wajib di bootstrap modal)
    setTimeout(function() {
        map.invalidateSize();
    }, 200);
  });

  // --- FITUR MENCARI LOKASI GPS USER ---
  $("#btn-my-location").on("click", function() {
      let btn = $(this);
      let originalText = btn.html();

      if (navigator.geolocation) {
          btn.html('<i class="fa fa-spinner fa-spin"></i> Mencari...');
          btn.prop('disabled', true);

          navigator.geolocation.getCurrentPosition(
              function(position) { // Success
                  let lat = position.coords.latitude;
                  let lng = position.coords.longitude;
                  let latLng = new L.LatLng(lat, lng);
                  
                  // Pindahkan marker dan pandangan peta ke lokasi GPS
                  marker.setLatLng(latLng);
                  map.setView(latLng, 16); 
                  
                  // Isi form input
                  $("#latitude").val(lat.toFixed(6));
                  $("#longitude").val(lng.toFixed(6));
                  
                  // Kembalikan tombol ke semula
                  btn.html(originalText);
                  btn.prop('disabled', false);
              }, 
              function(error) { // Error
                  alert("Gagal mendapatkan lokasi. Pastikan GPS aktif dan Anda mengizinkan akses lokasi di browser.");
                  btn.html(originalText);
                  btn.prop('disabled', false);
              },
              { enableHighAccuracy: true } // Minta lokasi seakurat mungkin
          );
      } else {
          alert("Browser Anda tidak mendukung fitur Geolocation.");
      }
  });

});
</script>


<script type="text/javascript">
  // Variabel global
let map;
let marker;
const kolakaLat = -4.0435;
const kolakaLng = 121.5815;

$(document).ready(function () {
  
  loadOptions4(
    "controller/process/getKecamatan.php",
    ["#filter-kecamatan", "#id-kecamatan"],
    "id_kecamatan", "nama_kecamatan", loadLahan
  );

  $("#filter-kecamatan").on("change", function () {
    loadLahan();
  });

  selectTamplate("#modalLahan", "#id-petani", "controller/process/getId.php?action=select-petani");

  formTemplate("#form-petani", {
    defaultEndpoint: "controller/process/aksiLahan.php",
  });

  // --- INISIALISASI PETA ---
  $('#modalLahan').on('shown.bs.modal', function () {
    if (!map) {
        map = L.map('map-lahan', { center: [kolakaLat, kolakaLng], zoom: 10 });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([kolakaLat, kolakaLng], {draggable: true}).addTo(map);

        // Update input kalau marker digeser secara manual di peta
        marker.on('dragend', function (e) {
            let pos = marker.getLatLng();
            $("#latitude").val(pos.lat.toFixed(6));
            $("#longitude").val(pos.lng.toFixed(6));
        });

        // Pindah marker kalau peta diklik
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            $("#latitude").val(e.latlng.lat.toFixed(6));
            $("#longitude").val(e.latlng.lng.toFixed(6));
        });
    }

    setTimeout(function() {
        map.invalidateSize();
    }, 200);
  });

  // ==========================================
  // FITUR 1: UPDATE MAP SAAT KETIK MANUAL
  // ==========================================
  $("#latitude, #longitude").on("input", function() {
      let lat = parseFloat($("#latitude").val());
      let lng = parseFloat($("#longitude").val());

      // Jika input valid (angka) dan map sudah dirender
      if (!isNaN(lat) && !isNaN(lng) && map && marker) {
          let latLng = new L.LatLng(lat, lng);
          marker.setLatLng(latLng);
          map.setView(latLng, 15); // Zoom otomatis saat diketik
      }
  });

  // ==========================================
  // FITUR 2: PENCARIAN BERDASARKAN DROPDOWN
  // ==========================================
  
  // Deteksi perubahan pada dropdown Kecamatan
  $("#id-kecamatan").off("change").on("change", function () {
      loadDesa($(this).val(), null);
      
      // Ambil teks kecamatan (bukan value ID-nya)
      let namaKecamatan = $(this).find("option:selected").text();
      if(namaKecamatan && namaKecamatan !== "Pilih Kecamatan...") {
          // Cari: Kecamatan X, Kabupaten Kolaka
          cariLokasiOtomatis(`Kecamatan ${namaKecamatan}, Kabupaten Kolaka, Sulawesi Tenggara`);
      }
  });

  // Deteksi perubahan pada dropdown Desa
  $("#id-desa").on("change", function () {
      let namaKecamatan = $("#id-kecamatan").find("option:selected").text();
      let namaDesa = $(this).find("option:selected").text();
      
      if(namaDesa && namaDesa !== "Pilih Desa dulu" && namaDesa !== "Pilih Desa...") {
          // Cari: Desa Y, Kecamatan X, Kabupaten Kolaka
          cariLokasiOtomatis(`${namaDesa}, Kecamatan ${namaKecamatan}, Kabupaten Kolaka, Sulawesi Tenggara`);
      }
  });

});

// Fungsi pencarian otomatis ke OpenStreetMap
function cariLokasiOtomatis(query) {
    $.getJSON('https://nominatim.openstreetmap.org/search', {
        format: 'json',
        q: query,
        limit: 1
    }, function(data) {
        // Jika lokasi ditemukan
        if (data && data.length > 0) {
            let lat = parseFloat(data[0].lat);
            let lng = parseFloat(data[0].lon);
            let latLng = new L.LatLng(lat, lng);
            
            // Pindahkan map dan marker
            if(map && marker) {
                marker.setLatLng(latLng);
                map.setView(latLng, 14); // Set zoom menengah
            }
            
            // Isi form otomatis (user masih bisa mengeditnya)
            $("#latitude").val(lat.toFixed(6));
            $("#longitude").val(lng.toFixed(6));
        }
    });
}

// ... (Fungsi loadLahan, loadDesa, editLahan, dan deleteLahan tetap sama seperti sebelumnya) ...
</script>



<script type="text/javascript">
  // ==========================================
// VARIABEL GLOBAL PETA
// ==========================================
let map;
let marker;
const kolakaLat = -4.0435; // Titik tengah Kab Kolaka
const kolakaLng = 121.5815;
let isLoadingEdit = false; // Penanda agar fitur edit tidak bentrok dengan pencarian otomatis

$(document).ready(function () {
  
  // 1. Load Data Kecamatan & Petani
  loadOptions4(
    "controller/process/getKecamatan.php",
    ["#filter-kecamatan", "#id-kecamatan"],
    "id_kecamatan", "nama_kecamatan", loadLahan
  );

  $("#filter-kecamatan").on("change", function () {
    loadLahan();
  });

  selectTamplate("#modalLahan", "#id-petani", "controller/process/getId.php?action=select-petani");

  // 2. Setup Form Submit
  formTemplate("#form-lahan", {
    defaultEndpoint: "controller/process/aksiLahan.php",
  });

  // ==========================================
  // SETUP PETA SAAT MODAL DIBUKA
  // ==========================================
  $('#modalLahan').on('shown.bs.modal', function () {
    if (!map) {
        // Inisialisasi Peta
        map = L.map('map-lahan', { center: [kolakaLat, kolakaLng], zoom: 10 });

        // Tampilan Peta dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan Marker yang bisa digeser
        marker = L.marker([kolakaLat, kolakaLng], {draggable: true}).addTo(map);

        // Event: Saat marker digeser secara manual di peta
        marker.on('dragend', function (e) {
            let pos = marker.getLatLng();
            $("#latitude").val(pos.lat.toFixed(6));
            $("#longitude").val(pos.lng.toFixed(6));
        });

        // Event: Saat peta diklik, pindahkan marker
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            $("#latitude").val(e.latlng.lat.toFixed(6));
            $("#longitude").val(e.latlng.lng.toFixed(6));
        });
    }

    // Refresh ukuran peta agar tidak error di dalam modal bootstrap
    setTimeout(function() {
        map.invalidateSize();
    }, 200);
  });

  // ==========================================
  // SINKRONISASI KETIK MANUAL KOORDINAT -> PETA
  // ==========================================
  $("#latitude, #longitude").on("input", function() {
      let lat = parseFloat($("#latitude").val());
      let lng = parseFloat($("#longitude").val());

      if (!isNaN(lat) && !isNaN(lng) && map && marker) {
          let latLng = new L.LatLng(lat, lng);
          marker.setLatLng(latLng);
          map.setView(latLng, 15);
      }
  });

  // ==========================================
  // PENCARIAN PETA OTOMATIS DARI DROPDOWN
  // ==========================================
  
  // Dropdown Kecamatan
  $("#id-kecamatan").off("change").on("change", function () {
      loadDesa($(this).val(), null);
      
      // Lakukan pencarian peta JIKA BUKAN SEDANG PROSES EDIT
      if (!isLoadingEdit) {
          let namaKecamatan = $(this).find("option:selected").text();
          if(namaKecamatan && namaKecamatan !== "Pilih Kecamatan...") {
              cariLokasiOtomatis(`Kecamatan ${namaKecamatan}, Kabupaten Kolaka, Sulawesi Tenggara`);
          }
      }
  });

  // Dropdown Desa
  $("#id-desa").on("change", function () {
      // Lakukan pencarian peta JIKA BUKAN SEDANG PROSES EDIT
      if (!isLoadingEdit) {
          let namaKecamatan = $("#id-kecamatan").find("option:selected").text();
          let namaDesa = $(this).find("option:selected").text();
          
          if(namaDesa && namaDesa !== "Pilih Desa dulu" && namaDesa !== "Pilih Desa...") {
              cariLokasiOtomatis(`${namaDesa}, Kecamatan ${namaKecamatan}, Kabupaten Kolaka, Sulawesi Tenggara`);
          }
      }
  });

});

// ==========================================
// FUNGSI PENCARIAN NAMA DAERAH KE KOORDINAT
// ==========================================
function cariLokasiOtomatis(query) {
    $.getJSON('https://nominatim.openstreetmap.org/search', {
        format: 'json',
        q: query,
        limit: 1
    }, function(data) {
        if (data && data.length > 0) {
            let lat = parseFloat(data[0].lat);
            let lng = parseFloat(data[0].lon);
            let latLng = new L.LatLng(lat, lng);
            
            if(map && marker) {
                marker.setLatLng(latLng);
                map.setView(latLng, 14); // Zoom dekat ke desa/kecamatan
            }
            
            // Isi form otomatis
            $("#latitude").val(lat.toFixed(6));
            $("#longitude").val(lng.toFixed(6));
        }
    });
}

// ==========================================
// FUNGSI MENAMPILKAN DATA TABEL
// ==========================================
function loadLahan() {
  const id_kecamatan = $("#filter-kecamatan").val();
  $.ajax({
    url: "controller/process/aksiLahan.php?action=read",
    type: "POST",
    data: { id_kecamatan },
    dataType: "json",
    beforeSend: function () {
          setTableLoading("#Lahan", 2);
    },
    success: function (data) {
      if (!Array.isArray(data) || data.length === 0) {
        $("#Lahan tbody").html(
          '<tr><td colspan="12" class="text-center">Tidak ada data</td></tr>'
        );
        return;
      }

      const rows = data.map((item, i) => `
          <tr>
              <td>${i + 1}</td>
              <td>${item.nama_petani}</td>
              <td>${item.nama_desa}</td>
              <td>${item.luas_lahan}</td>
              <td>${item.longitude} / ${item.latitude}</td>
              <td class="d-flex gap-2">
                  <button data-bs-toggle="tooltip2" title="Edit ${item.nama_petani}" 
                  class="btn btn-secondary btn-sm btn-edit" data-id="${item.id_lahan}">
                      <span class="fa fa-edit"></span>
                  </button>
                  <button data-bs-toggle="tooltip2" title="Hapus ${item.nama_petani}" 
                  class="btn btn-danger btn-sm btn-delete" data-id="${item.id_lahan}">
                      <span class="fa fa-trash"></span>
                  </button>
              </td>
          </tr>
      `).join("");

      $("#Lahan tbody").html(rows);
      initTooltipsHoverDesktop();

      // Delegated events
      $("#Lahan tbody").off("click", ".btn-edit").on("click", ".btn-edit", function () {
          editLahan($(this).data("id"));
      });
      $("#Lahan tbody").off("click", ".btn-delete").on("click", ".btn-delete", function () {
          deleteLahan($(this).data("id"));
      });

      paginateTable("#Lahan");
    },
    complete: function () {
       clearTableLoading("#Lahan"); 
    },
    error: function (xhr, status, error) {
      const msg = xhr?.responseText || error || "Error tidak diketahui";
      $("#Lahan tbody").html(
        `<tr><td colspan="12" class="text-center text-danger">Gagal memuat data: ${msg}</td></tr>`
      );
    },
  });
}

// ==========================================
// FUNGSI EDIT DATA
// ==========================================
function editLahan(id_lahan) {
  $.ajax({
    url: `controller/process/aksiLahan.php?action=detail`,
    type: "POST",
    data: {id_lahan},
    dataType: "json",
    beforeSend: function() {
        isLoadingEdit = true; // Kunci pencarian otomatis agar tidak merusak data koordinat database
    },
    success: function (data) {
      $("#modalLahan").modal("show");
      $(".modal-title").text("Edit Data Lahan");
      $("#action").val("update");
      $("#submitButton").text("Update");

      // Isi Data Form
      $("#id-lahan").val(data.id_lahan);
      $("#luas-lahan").val(data.luas_lahan);
      
      let option_petani = new Option(`${data.nama_petani}`, data.id_petani, true, true);
      $("#id-petani").append(option_petani).trigger("change");

      // Isi Dropdown (Tahan efek cari otomatis)
      $("#id-kecamatan").val(data.id_kecamatan);
      loadDesa(data.id_kecamatan, data.id_desa);
      
      // Isi Koordinat Asli dari Database
      $("#latitude").val(data.latitude);
      $("#longitude").val(data.longitude);

      // Pindahkan Peta ke Koordinat Database (Tunggu animasi modal selesai)
      setTimeout(function() {
          if(map && marker && data.latitude && data.longitude) {
              let lat = parseFloat(data.latitude);
              let lng = parseFloat(data.longitude);
              let latLng = new L.LatLng(lat, lng);
              
              marker.setLatLng(latLng);
              map.setView(latLng, 15);
          }
          isLoadingEdit = false; // Buka kembali kuncinya
      }, 400); 
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link", 3000);
      isLoadingEdit = false;
    },
  });
}

// ==========================================
// FUNGSI HAPUS DATA
// ==========================================
function deleteLahan(id_lahan) {
  Popup.warning(
    "Konfirmasi Hapus",
    "Apakah Anda yakin ingin menghapus Data Lahan ini?",
    null,
    function () {
      $.ajax({
        url: `controller/process/aksiLahan.php?action=delete`,
        type: "POST",
        data: { id_lahan: id_lahan },
        dataType: "json",
        success: function (response) {
          Popup.read("Success!", "Data berhasil dihapus");
          loadLahan();
        },
        error: function () {
          Popup.error("Gagal!", "Error pada link", 3000);
        },
      });
    }
  );
}

// ==========================================
// FUNGSI LOAD DATA DESA (Helper)
// ==========================================
function loadDesa(id_kecamatan, id_desa) {
    editOptions(
        "controller/process/getDesa.php",
        "#id-desa",
        "id_desa",
        "nama_desa",
        id_desa,
        "Pilih Desa...",
        { id_kecamatan }
    );
}

// ==========================================
// RESET FORM SAAT MODAL DITUTUP
// ==========================================
$("#modalLahan").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".needs-validation").removeClass("was-validated");
  
  $("#id-lahan").val("");
  $("#id-petani").val("").trigger("change");
  $("#action").val("create");
  $(".modal-title").text("Tambah Data Lahan");
  $("#submitButton").text("Save");

  // Kembalikan map ke posisi awal saat tambah data baru
  if(map && marker) {
      let latLng = new L.LatLng(kolakaLat, kolakaLng);
      marker.setLatLng(latLng);
      map.setView(latLng, 10);
  }
});
</script>