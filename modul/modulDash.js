$(document).ready(function () {
  // timeline();
  loadPengajuan();
  initTooltipsWithClose();
  loadOptions2(
    "controller/process/getUserGroups.php",
    ["#filter-group", "#id-group"],
    "id_group", "nama_group"
  );

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



function loadPengajuan() {
    $.ajax({
        url: "controller/process/aksiBibit.php",
        method: "GET",
        data: { action: "read2" },
        dataType: "json",
        success: function(data) {
            if (!data || data.length === 0) {
                $("#dt-bibit").html(
                    '<div> class="text-center">Tidak ada data</div>'
                );
                return;
            }

            let table = "";
            let no = 1;
            $.each(data, function(_, item) {
                table += `
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="card h-100 shadow-sm border-0 hover-card">
        <img src="img/bibit/${item.foto}" class="card-img-top card-img-custom" alt="Bibit Kakao">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title fw-bold">${item.nama_bibit}</h5>
          <p class="card-text text-muted">${item.desk}.</p>
        
          ${s_bibitBtn(item.id_bibit, item.status, item.id_pengajuan)}
          
        </div>
      </div>
    </div>
                    `;
            });

            $("#dt-bibit").html(table);

           
        },
       
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            $("#dt-bibit").html('<div class="text-center text-danger">Gagal memuat data</div>');
        }
    });
}






// fungsi untuk edit Bibit
// Fungsi untuk membuka modal tambah data
function openAddModal() {
  $("#modalBibit").modal("show");
  $("#modalTitle").text("Tambah Data Bibit"); // Judul modal
  $("#action").val("create"); // Set aksi ke "create"
  $("#submitButton").text("Simpan"); // Tombol submit
  resetForm(); // Reset form ke keadaan awal
}


function addPengajuan(id_bibit) {
  $.ajax({
    url: `controller/process/aksiBibit.php?action=detail&id_bibit=${id_bibit}`,
    type: "GET",
    dataType: "json",
    success: function (data) {
      $("#modalPengajuan").modal("show");
      // Isi form dengan data yang diterima
      $("#id_bibit").val(data.id_bibit);
      $("#nama_bibit").val(data.nama_bibit);
      $("#jml-per-hektar").val(data.jml_per_hektar || 100);
      $("#satuan-bibit").val(data.satuan || 'pohon');
      $("#label-satuan-bantuan").text(data.satuan || 'pohon');
      $("#desk").val(data.desk);
      $("#status").val(data.status).trigger("change");

      // Auto select matching option in judul if found
      $("#judul option").each(function() {
        if ($(this).text().toLowerCase().includes(data.nama_bibit.toLowerCase())) {
          $(this).prop("selected", true);
        }
      });
      updateEstimasiBibitUser();
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link", 3000);
    },
  });
}

function addPengajuan2(id_bibit) {
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
      
      $("#jml-bantuan").val(data.jml_bantuan);
      // $("#file-proposal").val(data.file_proposal);
      
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



function s_bibitBtn(id, status, id_pengajuan) {
    // Cek jika user sudah pernah mengajukan bibit ini
    if (id_pengajuan !== null && id_pengajuan !== undefined) {
        return `<button class="btn btn-secondary text-white mt-auto w-100 rounded-pill" disabled>
                    <i class="bi bi-check-circle me-1"></i> Sudah Diajukan
                </button>`;
    }

    status = parseInt(status);
    switch (status) {
        case 0:
            return `<button class="btn btn-danger mt-auto w-100 rounded-pill" disabled>
                        <i class="bi bi-x-circle me-1"></i> Tidak Tersedia
                    </button>`;
        case 1:
            return `<a href="#" class="btn btn-success mt-auto w-100 rounded-pill ajukan" data-id="${id}">
                        <i class="bi bi-plus-circle me-1"></i> Ajukan Sekarang
                    </a>`;
        default:
            return '';
    }
}


$(document).on("click", ".ajukan", function(e) {
        e.preventDefault();
      const id = $(this).data('id');
        addPengajuan(id); 
});


function resetPdfPreview() {
    $("#pdf-preview").attr("src", "");
    $("#pdf-container").hide();
    $("#empty-preview").show();
}

$("#modalPengajuan").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".needs-validation").removeClass("was-validated");
  $("#id-pengajuan").val("");
  $("#tot-luas-lahan").val("");
  $("#jml-bantuan").val("");
  $("#estimasi-bibit").empty();
  $("#action").val("create");
  resetPdfPreview();
});