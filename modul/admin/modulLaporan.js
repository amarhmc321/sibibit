$(document).ready(function () {
   loadOptions4(
    "controller/process/getKecamatan.php",
    ["#filter-kecamatan", "#id-kecamatan"],
    "id_kecamatan", "nama_kecamatan", loadPengajuan
  );

   loadOptions4(
    "controller/process/getBibit.php",
    ["#filter-bibit", "#id-bibit"],
    "id_bibit", "nama_bibit"
  );

 $("#filter-kecamatan, #filter-bibit, #tanggal_mulai, #tanggal_sampai").on("change", function () {
    loadPengajuan();
  });


  // Fungsi untuk save Obat
  formTemplate("#form-pengajuan", {
    defaultEndpoint: "controller/process/aksiLaporan.php",
  });

  $("#file-proposal").on("change", function(e) {
    const file = e.target.files[0];
    
    // Validasi apakah file adalah PDF
    if (file && file.type === "application/pdf") {
        const fileURL = URL.createObjectURL(file);
        
        // Tampilkan iframe dan sembunyikan placeholder
        $("#pdf-preview").attr("src", fileURL);
        $("#pdf-container").show();
        $("#empty-preview").hide();
    } else {
        alert("Harap upload file dengan format PDF.");
        $(this).val(""); // Kosongkan input
        resetPdfPreview();
    }
  });

 $('#btn-export').on('click', cetakLaporan);

});


function cetakLaporan() {
  const id_kecamatan = $("#filter-kecamatan").val();
  const id_bibit = $("#filter-bibit").val();
  const dari = $('#tanggal_mulai').val();
  const hingga = $('#tanggal_sampai').val();

let date = formatTanggalIndo(dari) + " Sampai " + formatTanggalIndo(hingga);

let get = `id_kecamatan=${id_kecamatan}&id_bibit=${id_bibit}&dari=${dari}&hingga=${hingga}&date=${date}`;

  Popup.success("Download Success!", "Data telah di-download.", 3000);
  setTimeout(function () {
    window.location.href = `views/cetak/cetak-laporan.php?${get}`;
  }, 500); 
}


function resetPdfPreview() {
    $("#pdf-preview").attr("src", "");
    $("#pdf-container").hide();
    $("#empty-preview").show();
}

function loadPengajuan() {
  const id_kecamatan = $("#filter-kecamatan").val();
  const id_bibit = $("#filter-bibit").val();
  const dari = $('#tanggal_mulai').val();
  const hingga = $('#tanggal_sampai').val();

  $.ajax({
    url: "controller/process/aksiLaporan.php?action=read",
    type: "POST",
    data: {
      id_kecamatan, id_bibit, dari, hingga,
    },
    dataType: "json",
    beforeSend: function () {
          setTableLoading("#Pengajuan", 2);
    },
    success: function (data) {
      if (!Array.isArray(data) || data.length === 0) {
        $("#Pengajuan tbody").html(
          '<tr><td colspan="12" class="text-center">Tidak ada data</td></tr>'
        );
        return;
      }

      const rows = data.map(
          (item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.tgl_pengajuan}</td>
                    <td>${item.judul}</td>
                    <td>${item.nama_kecamatan} - ${item.nama_desa}</td>
                    <td>${item.nama_kelompok}</td>
                    <td>${item.ketua}</td>
                    <td>${item.tot_luas_lahan} Ha</td>
                    <td>${jns_bibit(item.jml_bantuan, item.nama_bibit)}</td>
                  
                </tr>
            `
        )
        .join("");

      $("#Pengajuan tbody").html(rows);
      
      initTooltipsHoverDesktop();

      // Delegated events (lebih aman daripada inline onclick)
      $("#Pengajuan tbody").off("click", ".btn-edit").on("click", ".btn-edit", function () {
          ApprovePengajuan($(this).data("id"));
        });
      $("#Pengajuan tbody").off("click", ".btn-delete").on("click", ".btn-delete", function () {
          deletePengajuan($(this).data("id"));
        });

      paginateTable("#Pengajuan");

      
    },
     complete: function () {
    clearTableLoading("#Pengajuan"); 
  },
    error: function (xhr, status, error) {
      const msg = xhr?.responseText || error || "Error tidak diketahui";
      $("#Pengajuan tbody").html(
        `<tr><td colspan="12" class="text-center text-danger">Gagal memuat data: ${msg}</td></tr>`
      );
      console.error("AJAX Error:", msg);
    },
  });
}

// Fungsi untuk membuka modal edit data
function ApprovePengajuan(id_pengajuan) {
  $.ajax({
    url: `controller/process/aksiLaporan.php?action=detail`,
    type: "POST",
    data: {id_pengajuan},
    dataType: "json",
    success: function (data) {
      $("#modalPengajuan").modal("show");
      $(".modal-title").text("Tinjau Data Pengajuan"); // Disamakan dengan konteks
      $("#action").val("approve");
      
      $("#nama-kelompok").val(data.nama_kelompok);
      $("#id-pengajuan").val(data.id_pengajuan);
      $("#s-pengajuan").val(data.s_pengajuan).trigger("change");
      $("#catatan").val(data.catatan);
      
      // --- MENAMPILKAN PREVIEW PDF SAAT EDIT ---
      // Asumsi 'data.file' berisi nama file PDF (misal: proposal123.pdf)
      if(data.file_proposal && data.file_proposal !== "") {
          
          const pdfUrl = `assets/uploads/${data.file_proposal}`; 
          
          $("#pdf-preview").attr("src", pdfUrl);
          $("#pdf-container").show();
          $("#empty-preview").hide();
      } else {
          resetPdfPreview();
      }
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link", 3000);
    },
  });
}





// fungsi untuk mengosongkan modal
$("#modalPengajuan").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".needs-validation").removeClass("was-validated");
  
  $("#id-pengajuan").val("");
  $("#action").val("create");
  $(".modal-title").text("Tambah Data Pengajuan");
  
  // --- RESET PREVIEW PDF ---
  resetPdfPreview();
});
