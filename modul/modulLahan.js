// 
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
 selectTamplate("#modalLahan", "#id-group", "controller/process/getId.php?action=select-group");

  // Fungsi untuk save 
  formTemplate("#form-lahan", {
    defaultEndpoint: "controller/process/aksiLahan.php",
  });

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
          cariLokasiOtomatis(`${namaKecamatan},Kolaka, Sulawesi Tenggara`);
      }
  });

  // Deteksi perubahan pada dropdown Desa
  $("#id-desa").on("change", function () {
      let namaKecamatan = $("#id-kecamatan").find("option:selected").text();
      let namaDesa = $(this).find("option:selected").text();
      
      if(namaDesa && namaDesa !== "Pilih Desa dulu" && namaDesa !== "Pilih Desa...") {
          // Cari: Desa Y, Kecamatan X, Kabupaten Kolaka
          cariLokasiOtomatis(`${namaDesa}, ${namaKecamatan}, Kolaka, Sulawesi Tenggara`);
      }
  });


});


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

function loadLahan() {
  const id_kecamatan = $("#filter-kecamatan").val();
  $.ajax({
    url: "controller/process/aksiLahan.php?action=read2",
    type: "POST",
    data: {
      id_kecamatan,
    },
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

      const rows = data.map(
          (item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.nama_petani}</td>
                    <td>${item.nama_kelompok || "-"}</td>
                    <td>${item.nama_desa}</td>
                    <td>${item.luas_lahan}</td>
                    <td>${item.longitude}/${item.latitude}</td>
                    
                </tr>
            `
        )
        .join("");

      $("#Lahan tbody").html(rows);
      
      initTooltipsHoverDesktop();

      // Delegated events (lebih aman daripada inline onclick)
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
      console.error("AJAX Error:", msg);
    },
  });
}

// Fungsi untuk membuka modal edit data
function editLahan(id_lahan) {
  $.ajax({
    url: `controller/process/aksiLahan.php?action=detail`,
    type: "POST",
    data: {id_lahan},
    dataType: "json",
    success: function (data) {
      $("#modalLahan").modal("show");
      $(".modal-title").text("Edit Data Lahan"); // Judul modal
      $("#action").val("update"); // Set aksi ke "update"
      $("#submitButton").text("Update"); // Tombol submit

      $("#id-lahan").val(data.id_lahan);

      $("#id-desa").val(data.id_desa);
      $("#id-kecamatan").val(data.id_kecamatan).trigger("change");
      $("#luas-lahan").val(data.luas_lahan);
      let option_petani = new Option(`${data.nama_petani}`,data.id_petani,true, true);
      $("#id-petani").append(option_petani).trigger("change");
      let option_group = new Option(`${data.nama_kelompok || '-'}`,data.id_group,true, true);
      $("#id-group").append(option_group).trigger("change");


      loadDesa(data.id_kecamatan, data.id_desa);

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
    },
  });
}

// fungsi untuk hapus 
function deleteLahan(id_lahan) {
  Popup.warning(
    "Konfirmasi Hapus",
    "Apakah Anda yakin ingin menghapus Data Lahan?",
    null,
    function () {
      $.ajax({
        url: `controller/process/aksiLahan.php?action=delete`,
        type: "POST",
        data: {
          id_lahan,
        },
        dataType: "json",
        async: true,
        success: function (response) {
          Popup.read("Success!", "data berhasil dihapus ");
          loadLahan();
        },
        error: function () {
          Popup.error("Gagal!", "eror pada link ", 3000);
        },
      });
    }
  );
}


$("#id-kecamatan")
        .off("change")
        .on("change", function () {
          loadDesa($(this).val(), null);
        });

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


// fungsi untuk mengosongkan modal
$("#modalLahan").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".needs-validation").removeClass("was-validated");
  $("#id-lahan").prop("disabled", false);
  $("#id-petani").val("");
  $("#id-desa").val("");
  $("#action").val("create");
  $(".modal-title").text("Tambah Data Lahan");

   if(map && marker) {
      let latLng = new L.LatLng(kolakaLat, kolakaLng);
      marker.setLatLng(latLng);
      map.setView(latLng, 10);
  }
});


