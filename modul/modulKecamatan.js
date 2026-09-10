$(document).ready(function () {
  // Panggil load Unit
  loadKecamatan();
  initTooltipsWithClose();
  // initKodeGenerator();
  formTemplate("#form-kecamatan", {
    defaultEndpoint: "controller/process/aksiKecamatan.php",
  });
});

function loadKecamatan() {
    $.ajax({
        url: "controller/process/aksiKecamatan.php",
        method: "GET",
        data: { action: "read" },
        dataType: "json",
        beforeSend: function() {
          setTableLoading("#Kecamatan", 2);
        },
        success: function(data) {
            if (!data || data.length === 0) {
                $("#Kecamatan tbody").html(
                    '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>'
                );
                return;
            }

            let table = "";
            let no = 1;
            $.each(data, function(_, item) {
                table += `
                    <tr>
                        <td>${no++}</td>
                        <td>${item.nama_kecamatan}</td>
                        <td>${totalJbt(item.total_desa, "Desa")}</td>
                        <td class="d-flex gap-2">
                            <button data-bs-toggle="tooltip2" title="Edit ${item.nama_kecamatan}" 
                                class="btn btn-secondary btn-sm" 
                                onclick="editUnit('${item.id_kecamatan}')">
                                <span class="fa fa-edit"></span>
                            </button>
                            <button data-bs-toggle="tooltip2" title="Hapus ${item.nama_kecamatan}" 
                                class="btn btn-danger btn-sm delete-btn" 
                                onclick="deleteUnit('${item.id_kecamatan}')">
                                <span class="fa fa-trash"></span>
                            </button>
                        </td>
                    </tr>`;
            });

            $("#Kecamatan tbody").html(table);

            // Jalankan paginasi setelah data dimuat
            paginateTable("#Kecamatan");
            initTooltipsHoverDesktop();
        },
        complete: function () {
    clearTableLoading("#Kecamatan"); 
  },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            $("#Kecamatan tbody").html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>');
        }
    });
}


// fungsi untuk edit Unit
// Fungsi untuk membuka modal tambah data
function openAddModal() {
  $("#modalKecamatan").modal("show");
  $("#modalTitle").text("Tambah Data Unit"); // Judul modal
  $("#action").val("create"); // Set aksi ke "create"
  $("#submitButton").text("Simpan"); // Tombol submit
  resetForm(); // Reset form ke keadaan awal
}

// Fungsi untuk membuka modal edit data
function editUnit(id_kecamatan) {
  $.ajax({
    url: `controller/process/aksiKecamatan.php?action=detail&id_kecamatan=${id_kecamatan}`,
    type: "GET",
    dataType: "json",
    success: function (data) {
      $("#modalKecamatan").modal("show");
      $("#modalTitle").text("Edit Data Unit"); // Judul modal
      $("#action").val("update"); // Set aksi ke "update"
      $("#submitButton").text("Update"); // Tombol submit

      // Isi form dengan data yang diterima
      $("#id_kecamatan").val(data.id_kecamatan);
      $("#nama_kecamatan").val(data.nama_kecamatan);
      // $("#kd_kecamatan").val(data.kd_kecamatan);

   
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link", 3000);
    },
  });
}

// Fungsi untuk mereset form ke keadaan awal
function resetForm() {
  $("#id_kecamatan").val(""); // Kosongkan ID Unit
  $("#nama_kecamatan").val(""); // Kosongkan nama Unit
}
// fungsi untuk hapus Unit
function deleteUnit(id_kecamatan) {
  Popup.warning(
    "Konfirmasi Hapus",
    "Apakah Anda yakin ingin menghapus Data Kecamatan?",
    null,
    function () {
      $.ajax({
        url: `controller/process/aksiKecamatan.php?action=delete`,
        type: "POST",
        data: {
          id_kecamatan: id_kecamatan,
        },
        dataType: "json",
        async: true,
        success: function (response) {
          Popup.read("Success!", "data berhasil dihapus ");
          loadKecamatan();
        },
        error: function () {
          Popup.error("Gagal!", "eror pada link ", 2000);
        },
      });
    }
  );
}

// fungsi untuk mengosongkan modal
$("#modalKecamatan").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".needs-validation").removeClass("was-validated");
  $("#id_kecamatan").empty();
  $("#action").val("create");
  $(".modal-title").text("Tambah Data Unit");
});
