$(document).ready(function () {
   

   loadOptions2(
    "controller/process/getKecamatan.php",
    ["#filter-kecamatan", "#id-kecamatan"],
    "id_kecamatan", "nama_kecamatan", loadDesa
  );

 $("#filter-kecamatan").on("change", function () {
    loadDesa();
  });



  // Fungsi untuk save 
  formTemplate("#form-desa", {
    defaultEndpoint: "controller/process/aksiDesa.php",
  });
});

function loadDesa() {
  const id_kecamatan = $("#filter-kecamatan").val();
  const id_komponen = $("#filter-komponen").val();
  $.ajax({
    url: "controller/process/aksiDesa.php?action=read",
    type: "POST",
    data: {
      id_kecamatan,
      id_komponen,
    },
    dataType: "json",
    beforeSend: function () {
          setTableLoading("#Desa", 2);
    },
    success: function (data) {
      if (!Array.isArray(data) || data.length === 0) {
        $("#Desa tbody").html(
          '<tr><td colspan="12" class="text-center">Tidak ada data</td></tr>'
        );
        return;
      }

      const rows = data.map(
          (item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.nama_desa}</td>
                    <td>${item.nama_kecamatan}</td>
                    <td class="d-flex gap-2">
                        <button data-bs-toggle="tooltip2" title="Edit ${item.nama_desa}" 
                        class="btn btn-secondary btn-sm btn-edit" data-id="${item.id_desa}">
                            <span class="fa fa-edit"></span>
                        </button>
                        <button data-bs-toggle="tooltip2" title="Hapus ${item.nama_desa}" 
                        class="btn btn-danger btn-sm btn-delete" data-id="${item.id_desa}">
                            <span class="fa fa-trash"></span>
                        </button>
                    </td>
                </tr>
            `
        )
        .join("");

      $("#Desa tbody").html(rows);
      
      initTooltipsHoverDesktop();

      // Delegated events (lebih aman daripada inline onclick)
      $("#Desa tbody").off("click", ".btn-edit").on("click", ".btn-edit", function () {
          editDesa($(this).data("id"));
        });
      $("#Desa tbody").off("click", ".btn-delete").on("click", ".btn-delete", function () {
          deleteDesa($(this).data("id"));
        });

      paginateTable("#Desa");

      
    },
     complete: function () {
    clearTableLoading("#Desa"); 
  },
    error: function (xhr, status, error) {
      const msg = xhr?.responseText || error || "Error tidak diketahui";
      $("#Desa tbody").html(
        `<tr><td colspan="12" class="text-center text-danger">Gagal memuat data: ${msg}</td></tr>`
      );
      console.error("AJAX Error:", msg);
    },
  });
}

// Fungsi untuk membuka modal edit data
function editDesa(id_desa) {
  $.ajax({
    url: `controller/process/aksiDesa.php?action=detail`,
    type: "POST",
    data: {id_desa},
    dataType: "json",
    success: function (data) {
      $("#modalDesa").modal("show");
      $(".modal-title").text("Edit Data "); // Judul modal
      $("#action").val("update"); // Set aksi ke "update"
      $("#submitButton").text("Update"); // Tombol submit

      $("#id-desa").val(data.id_desa);
      $("#id-kecamatan").val(data.id_kecamatan).trigger("change");
      $("#nama-desa").val(data.nama_desa);
      $("#kd-desa").val(data.kd_desa);
      $("#stock").val(data.stock);
      
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link", 3000);
    },
  });
}

// fungsi untuk hapus 
function deleteDesa(id_desa) {
  Popup.warning(
    "Konfirmasi Hapus",
    "Apakah Anda yakin ingin menghapus Data ?",
    null,
    function () {
      $.ajax({
        url: `controller/process/aksiDesa.php?action=delete`,
        type: "POST",
        data: {
          id_desa: id_desa,
        },
        dataType: "json",
        async: true,
        success: function (response) {
          Popup.read("Success!", "data berhasil dihapus ");
          loadDesa();
        },
        error: function () {
          Popup.error("Gagal!", "eror pada link ", 3000);
        },
      });
    }
  );
}



// fungsi untuk mengosongkan modal
$("#modalDesa").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".needs-validation").removeClass("was-validated");
  $("#id-komponen").prop("disabled", false);
  $("#id_desa").val("");
  $("#action").val("create");
  $(".modal-title").text("Tambah Data ");
});
