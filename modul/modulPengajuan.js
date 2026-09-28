// 
$(document).ready(function () {
   loadOptions2(
    "controller/process/getUserGroups.php",
    ["#filter-group", "#id-group"],
    "id_group", "nama_group", loadPengajuan
  );

 $("#filter-kecamatan").on("change", function () {
    loadPengajuan();
  });


  
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

  $("#id-group, #judul").on("change input", function() {
    updateEstimasiBibitUser();
  });

});

function updateEstimasiBibitUser() {
  let id_group = $("#id-group").val();
  let nama_bibit = $("#nama_bibit").val();
  let judul = $("#judul").val();
  let jml_per_hektar = parseInt($("#jml-per-hektar").val()) || 0;
  let satuan = $("#satuan-bibit").val() || 'pohon';

  if (!id_group) {
    $("#tot-luas-lahan").val("");
    $("#jml-bantuan").val("");
    $("#estimasi-bibit").html('<small class="text-muted"><i class="fa fa-info-circle me-1"></i>Pilih kelompok untuk memuat total luas lahan & estimasi bibit.</small>');
    return;
  }

  $.ajax({
    url: `controller/process/getUserGroups.php?id_group=${id_group}`,
    type: "GET",
    dataType: "json",
    success: function(resGroup) {
      let luas = parseFloat(resGroup.tot_luas_lahan) || 0;
      $("#tot-luas-lahan").val(luas > 0 ? luas : 0);

      let calc = hitungBibit(nama_bibit, judul, luas, jml_per_hektar, satuan);
      if (calc.jumlah > 0) {
        $("#jml-bantuan").val(calc.jumlah);
        $("#estimasi-bibit").html(`<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa fa-calculator me-1"></i>Estimasi: ${new Intl.NumberFormat('id-ID').format(calc.jumlah)} ${calc.satuan} (${calc.text})</span>`);
      } else {
        $("#jml-bantuan").val(0);
        if (luas <= 0) {
          $("#estimasi-bibit").html('<span class="text-danger small"><i class="fa fa-exclamation-triangle me-1"></i>Kelompok ini belum memiliki data lahan terdaftar di sistem.</span>');
        } else {
          $("#estimasi-bibit").empty();
        }
      }
    }
  });
}

function resetPdfPreview() {
    $("#pdf-preview").attr("src", "");
    $("#pdf-container").hide();
    $("#empty-preview").show();
}

function loadPengajuan() {
  const id_group = $("#filter-group").val();
  $.ajax({
    url: "controller/process/aksiPengajuan.php?action=read2",
    type: "POST",
    data: {
      id_group,
    },
    dataType: "json",
    beforeSend: function () {
          setTableLoading("#Pengajuan", 2);
    },
    success: function (data) {
      if (!Array.isArray(data) || data.length === 0) {
        $("#Pengajuan tbody").html(
          '<tr><td colspan="13" class="text-center">Tidak ada data</td></tr>'
        );
        return;
      }

      const rows = data.map(
          (item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.judul}</td>
                    <td>${item.nama_kelompok}</td>
                    <td>${item.nama_kecamatan} - ${item.nama_desa}</td>
                    <td>${item.ketua}</td>
                    <td>${item.nama_bibit}</td>
                    <td>${parseFloat(item.tot_luas_lahan) > 0 ? item.tot_luas_lahan + ' Ha' : '-'}</td>
                    <td>${formatStatus(item.s_pengajuan)}</td>
                    <td>${item.tgl_pengajuan}</td>
                    <td>${item.s_pengajuan >= 2 ? "-" : item.tgl_penyaluran }</td>
                    <td>${item.catatan || "-"}</td>
                    <td>${
        item.file_proposal
          ? `<a href="assets/uploads/${item.file_proposal}" target="_blank" class="badge bg-info bukti">Lihat Proposal</a>`
          : `<span class="badge bg-secondary">-</span>`
      }</td>
                    <td class="d-flex gap-2"> ${aksi(item.id_pengajuan, item.tgl_pengajuan, item.nama_kelompok, item.s_pengajuan)}</td>
                </tr>
            `
        )
        .join("");

      $("#Pengajuan tbody").html(rows);
      
      initTooltipsHoverDesktop();

      // Delegated events (lebih aman daripada inline onclick)
      $("#Pengajuan tbody").off("click", ".btn-edit").on("click", ".btn-edit", function () {
          editPengajuan($(this).data("id"));
        });
      $("#Pengajuan tbody").off("click", ".btn-delete").on("click", ".btn-delete", function () {
          deletePengajuan($(this).data("id"));
        });

      $("#Pengajuan tbody").off("click", ".btn-cetak").on("click", ".btn-cetak", function () {
         let id  = $(this).data("id");
         let tgl = $(this).data("tgl");
          cetakPengajuan(id,tgl);
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

function cetakPengajuan(id_pengajuan, tgl_pengajuan) {

let get = `id_pengajuan=${id_pengajuan}&date=${tgl_pengajuan}`;

  Popup.success("Download Success!", "Data telah di-download.", 3000);
  setTimeout(function () {
    window.location.href = `views/cetak/cetak-laporan-user.php?${get}`;
  }, 500); 
}

// Fungsi untuk membuka modal edit data
function editPengajuan(id_pengajuan) {
  $.ajax({
    url: `controller/process/aksiPengajuan.php?action=detail2`,
    type: "POST",
    data: {id_pengajuan},
    dataType: "json",
    success: function (data) {
      $("#modalPengajuan").modal("show");
      $(".modal-title").text("Edit Data Pengajuan"); 
      $("#action").val("update");
      $("#judul").val(data.judul);
      $("#ketua").val(data.ketua);
      $("#id-pengajuan").val(data.id_pengajuan);
      $("#id-group").val(data.id_group);
      $("#id_bibit").val(data.id_bibit);
      $("#nama_bibit").val(data.nama_bibit);
      $("#jml-per-hektar").val(data.jml_per_hektar || 100);
      $("#satuan-bibit").val(data.satuan || 'pohon');
      $("#label-satuan-bantuan").text(data.satuan || 'pohon');
      $("#tot-luas-lahan").val(parseFloat(data.tot_luas_lahan) > 0 ? data.tot_luas_lahan : 0);
      $("#jml-bantuan").val(data.jml_bantuan);
      $("#tgl-penyaluran").val(data.tgl_penyaluran);

      let calc = hitungBibit(data.nama_bibit, data.judul, data.tot_luas_lahan, data.jml_per_hektar, data.satuan);
      if (calc.jumlah > 0) {
        $("#estimasi-bibit").html(`<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="fa fa-calculator me-1"></i>Estimasi: ${new Intl.NumberFormat('id-ID').format(calc.jumlah)} ${calc.satuan} (${calc.text})</span>`);
      } else {
        $("#estimasi-bibit").empty();
      }
      
      // --- MENAMPILKAN PREVIEW PDF SAAT EDIT ---
      // Asumsi 'data.file' berisi nama file PDF (misal: proposal123.pdf)
      if(data.file_proposal && data.file_proposal !== "") {
          $("#file-proposal").prop("required", false);
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

// fungsi untuk hapus 
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
          id_pengajuan
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
  $("#tot-luas-lahan").val("");
  $("#jml-bantuan").val("");
  $("#estimasi-bibit").empty();
  $("#action").val("create");
  $(".modal-title").text("Tambah Data Pengajuan");
  
  // --- RESET PREVIEW PDF ---
  resetPdfPreview();
});
