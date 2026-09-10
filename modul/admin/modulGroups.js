$(document).ready(function () {
   loadOptions4(
    "controller/process/getKecamatan.php",
    ["#filter-kecamatan", "#id-kecamatan"],
    "id_kecamatan", "nama_kecamatan", loadGroups
  );

 $("#filter-kecamatan").on("change", function () {
    loadGroups();
  });
 selectTamplate("#modalGroups", "#id-petani", "controller/process/getId.php?action=select-petani");

  // Fungsi untuk save Obat
  formTemplate("#form-groups", {
    defaultEndpoint: "controller/process/aksiGroups.php",
  });
});

function loadGroups() {
  const id_kecamatan = $("#filter-kecamatan").val();
  $.ajax({
    url: "controller/process/aksiGroups.php?action=read",
    type: "POST",
    data: {
      id_kecamatan,
    },
    dataType: "json",
    beforeSend: function () {
          setTableLoading("#Groups", 2);
    },
    success: function (data) {
      if (!Array.isArray(data) || data.length === 0) {
        $("#Groups tbody").html(
          '<tr><td colspan="12" class="text-center">Tidak ada data</td></tr>'
        );
        return;
      }

      const rows = data.map(
          (item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.nama_kelompok}</td>
                    <td>${item.nama_kecamatan}-${item.nama_desa}</td>
                    <td>${item.ketua}</td>
                    <td>${g_action(item.id_group, item.total_anggota, "Anggota")}</td>
                    <td>${item.total_luas_lahan || 0}</td>
                    <td>${item.komoditas}</td>
                    <td class="d-flex gap-2">
                        <button data-bs-toggle="tooltip2" title="Edit ${item.nama_kelompok}" 
                        class="btn btn-secondary btn-sm btn-edit" data-id="${item.id_group}">
                            <span class="fa fa-edit"></span>
                        </button>
                        <button data-bs-toggle="tooltip2" title="Hapus ${item.nama_kelompok}" 
                        class="btn btn-danger btn-sm btn-delete" data-id="${item.id_group}">
                            <span class="fa fa-trash"></span>
                        </button>
                    </td>
                </tr>
            `
        )
        .join("");

      $("#Groups tbody").html(rows);
      
      initTooltipsHoverDesktop();

      // Delegated events (lebih aman daripada inline onclick)
      $("#Groups tbody").off("click", ".btn-edit").on("click", ".btn-edit", function () {
          editGroups($(this).data("id"));
        });
      $("#Groups tbody").off("click", ".btn-delete").on("click", ".btn-delete", function () {
          deleteGroups($(this).data("id"));
        });

      paginateTable("#Groups");

      
    },
     complete: function () {
    clearTableLoading("#Groups"); 
  },
    error: function (xhr, status, error) {
      const msg = xhr?.responseText || error || "Error tidak diketahui";
      $("#Groups tbody").html(
        `<tr><td colspan="12" class="text-center text-danger">Gagal memuat data: ${msg}</td></tr>`
      );
      console.error("AJAX Error:", msg);
    },
  });
}

// Fungsi untuk membuka modal edit data
function editGroups(id_group) {
  $.ajax({
    url: `controller/process/aksiGroups.php?action=detail`,
    type: "POST",
    data: {id_group},
    dataType: "json",
    success: function (data) {
      $("#modalGroups").modal("show");
      $(".modal-title").text("Edit Data Kelompok"); // Judul modal
      $("#action").val("update"); // Set aksi ke "update"
      $("#submitButton").text("Update"); // Tombol submit

      $("#id-group").val(data.id_group);
      $("#id-desa").val(data.id_desa);
      $("#nama-kelompok").val(data.nama_kelompok);
      $("#komoditas").val(data.komoditas);
      

      let option_petani = new Option(`${data.nama_petani}`,data.id_leader,true, true);
      $("#id-petani").append(option_petani).trigger("change");

      $("#id-kecamatan").val(data.id_kecamatan).trigger("change");
      loadDesa(data.id_kecamatan, data.id_desa);
      
      
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link", 3000);
    },
  });
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

// fungsi untuk hapus Obat
function deleteGroups(id_group) {
  Popup.warning(
    "Konfirmasi Hapus",
    "Apakah Anda yakin ingin menghapus Data Kelompok?",
    null,
    function () {
      $.ajax({
        url: `controller/process/aksiGroups.php?action=delete`,
        type: "POST",
        data: {
          id_group,
        },
        dataType: "json",
        async: true,
        success: function (response) {
          Popup.read("Success!", "data berhasil dihapus ");
          loadGroups();
        },
        error: function () {
          Popup.error("Gagal!", "eror pada link ", 3000);
        },
      });
    }
  );
}



// fungsi untuk mengosongkan modal
$("#modalGroups").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".needs-validation").removeClass("was-validated");
  $("#id-komponen").prop("disabled", false);
  $("#id_desa").val("");
  $("#action").val("create");
  $(".modal-title").text("Tambah Data Obat");
});
