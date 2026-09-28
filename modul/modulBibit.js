$(document).ready(function () {
  
  loadBibit();
  initTooltipsWithClose();
  

  formTemplate("#form-bibit", {
    defaultEndpoint: "controller/process/aksiBibit.php",
  });

$("#foto")
    .off("change")
    .on("change", function (event) {
      previewImage(event);
    });


});

function loadBibit() {
    $.ajax({
        url: "controller/process/aksiBibit.php",
        method: "GET",
        data: { action: "read" },
        dataType: "json",
        beforeSend: function() {
          setTableLoading("#Bibit", 2);
        },
        success: function(data) {
            if (!data || data.length === 0) {
                $("#Bibit tbody").html(
                    '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>'
                );
                return;
            }

            let table = "";
            let no = 1;
            $.each(data, function(_, item) {
                let rasioText = `${new Intl.NumberFormat('id-ID').format(item.jml_per_hektar || 100)} ${item.satuan || 'pohon'} / Ha`;
                table += `
                    <tr>
                        <td>${no++}</td>
                        <td>${item.nama_bibit}</td>
                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle">${rasioText}</span></td>
                        <td>${item.desk}</td>
                        <td>${s_bibit(item.status)}</td>
                        <td class="d-flex gap-2">
                            <button data-bs-toggle="tooltip2" title="Edit ${item.nama_bibit}" 
                                class="btn btn-secondary btn-sm" 
                                onclick="editBibit('${item.id_bibit}')">
                                <span class="fa fa-edit"></span>
                            </button>
                            <button data-bs-toggle="tooltip2" title="Hapus ${item.nama_bibit}" 
                                class="btn btn-danger btn-sm delete-btn" 
                                onclick="deleteBibit('${item.id_bibit}')">
                                <span class="fa fa-trash"></span>
                            </button>
                        </td>
                    </tr>`;
            });

            $("#Bibit tbody").html(table);

            // Jalankan paginasi setelah data dimuat
            paginateTable("#Bibit");
            initTooltipsHoverDesktop();
        },
        complete: function () {
    clearTableLoading("#Bibit"); 
  },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            $("#Bibit tbody").html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>');
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

// Fungsi untuk membuka modal edit data
function editBibit(id_bibit) {
  $.ajax({
    url: `controller/process/aksiBibit.php?action=detail&id_bibit=${id_bibit}`,
    type: "GET",
    dataType: "json",
    success: function (data) {
      $("#modalBibit").modal("show");
      $("#modalTitle").text("Edit Data Bibit"); // Judul modal
      $("#action").val("update"); // Set aksi ke "update"
      $("#submitButton").text("Update"); // Tombol submit

      // Isi form dengan data yang diterima
      $("#id_bibit").val(data.id_bibit);
      $("#nama_bibit").val(data.nama_bibit);
      $("#jml_per_hektar").val(data.jml_per_hektar || 100);
      $("#satuan").val(data.satuan || "pohon");
      // $("#stock").val(data.stock);
      $("#desk").val(data.desk);
      $("#status").val(data.status).trigger("change");
      if (data.foto) {
        $("#profilePreview").attr("src", `img/bibit/${data.foto}`);
      }
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link", 3000);
    },
  });
}

// Fungsi untuk mereset form ke keadaan awal
function resetForm() {
  $("#id_bibit").val(""); // Kosongkan ID Bibit
  $("#nama_bibit").val(""); // Kosongkan nama Bibit
  $("#jml_per_hektar").val(100);
  $("#satuan").val("pohon");
  $("#desk").val("");
  $("#profilePreview").attr("src", "img/bibit/default.jpg");
}
// fungsi untuk hapus Bibit
function deleteBibit(id_bibit) {
  Popup.warning(
    "Konfirmasi Hapus",
    "Apakah Anda yakin ingin menghapus Data Bibit?",
    null,
    function () {
      $.ajax({
        url: `controller/process/aksiBibit.php?action=delete`,
        type: "POST",
        data: {
          id_bibit: id_bibit,
        },
        dataType: "json",
        async: true,
        success: function (response) {
          Popup.read("Success!", "data berhasil dihapus ");
          loadBibit();
        },
        error: function () {
          Popup.error("Gagal!", "eror pada link ", 2000);
        },
      });
    }
  );
}

// fungsi untuk mengosongkan modal
$("#modalBibit").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".needs-validation").removeClass("was-validated");
  $("#id_bibit").empty();
  $("#action").val("create");
  $(".modal-title").text("Tambah Data Bibit");
});
