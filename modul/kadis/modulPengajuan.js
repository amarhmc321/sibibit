$(document).ready(function () {
   loadOptions4(
    "controller/process/getKecamatan.php",
    ["#filter-kecamatan", "#id-kecamatan"],
    "id_kecamatan", "nama_kecamatan", loadPengajuan
  );

 $("#filter-kecamatan").on("change", function () {
    loadPengajuan();
  });


  // Fungsi untuk save Obat
  formTemplate("#form-pengajuan", {
    defaultEndpoint: "controller/process/aksiPengajuan.php",
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


});

function resetPdfPreview() {
    $("#pdf-preview").attr("src", "");
    $("#pdf-container").hide();
    $("#empty-preview").show();
}

function loadPengajuan() {
  const id_kecamatan = $("#filter-kecamatan").val();
  $.ajax({
    url: "controller/process/aksiPengajuan.php?action=read",
    type: "POST",
    data: {
      id_kecamatan,
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
                    <td>${item.nama_kelompok}</td>
                    <td>${item.nama_kecamatan} - ${item.nama_desa}</td>
                    <td>${item.ketua}</td>
                    <td>${item.tgl_pengajuan}</td>
                    <td>${item.oleh}</td>
                    <td>${formatStatus(item.s_pengajuan)}</td>
                    <td>${item.catatan || "-"}</td>
                    <td>${
        item.file_proposal
          ? `<a href="assets/uploads/${item.file_proposal}" target="_blank" class="badge bg-info bukti">Lihat Proposal</a>`
          : `<span class="badge bg-secondary">-</span>`
      }</td>
                    <td class="d-flex gap-2">
                        <button data-bs-toggle="tooltip2" title="Edit ${item.nama_kelompok}" 
                        class="btn btn-secondary btn-sm btn-edit" data-id="${item.id_pengajuan}">
                            <span class="fa fa-edit"></span>
                        </button>
                        <button data-bs-toggle="tooltip2" title="Hapus ${item.nama_kelompok}" 
                        class="btn btn-danger btn-sm btn-delete" data-id="${item.id_pengajuan}">
                            <span class="fa fa-trash"></span>
                        </button>
                    </td>
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
    url: `controller/process/aksiPengajuan.php?action=detail`,
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

// fungsi untuk hapus Obat
function deletePengajuan(id_pengajuan) {
  Popup.warning(
    "Konfirmasi Hapus",
    "Apakah Anda yakin ingin menghapus Permohonan?",
    null,
    function () {
      $.ajax({
        url: `controller/process/aksiPengajuan.php?action=delete`,
        type: "POST",
        data: {
          id_pengajuan,
        },
        dataType: "json",
        async: true,
        success: function (response) {
          Popup.read("Success!", "data berhasil dihapus ");
          loadPengajuan();
        },
        error: function () {
          Popup.error("Gagal!", "eror pada link ", 3000);
        },
      });
    }
  );
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
