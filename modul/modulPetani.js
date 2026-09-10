// 
$(document).ready(function () {
   loadOptions4(
    "controller/process/getKecamatan.php",
    ["#filter-kecamatan", "#id-kecamatan"],
    "id_kecamatan", "nama_kecamatan", loadPetani
  );

 $("#filter-kecamatan").on("change", function () {
    loadPetani();
  });


  // Fungsi untuk save 
  formTemplate("#form-petani", {
    defaultEndpoint: "controller/process/aksiPetani.php",
  });
});

function loadPetani() {
  const id_kecamatan = $("#filter-kecamatan").val();
  $.ajax({
    url: "controller/process/aksiPetani.php?action=read",
    type: "POST",
    data: {
      id_kecamatan,
    },
    dataType: "json",
    beforeSend: function () {
          setTableLoading("#Petani", 2);
    },
    success: function (data) {
      if (!Array.isArray(data) || data.length === 0) {
        $("#Petani tbody").html(
          '<tr><td colspan="12" class="text-center">Tidak ada data</td></tr>'
        );
        return;
      }

      const rows = data.map(
          (item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.nama_petani}</td>
                    <td>${item.nama_desa}</td>
                    <td>${item.jekel}</td>
                    <td>${item.kontak}</td>
                    <td>${item.nik || "-"}</td>
                    <td class="d-flex gap-2">
                        <button data-bs-toggle="tooltip2" title="Edit ${item.nama_petani}" 
                        class="btn btn-secondary btn-sm btn-edit" data-id="${item.id_petani}">
                            <span class="fa fa-edit"></span>
                        </button>
                        <button data-bs-toggle="tooltip2" title="Hapus ${item.nama_petani}" 
                        class="btn btn-danger btn-sm btn-delete" data-id="${item.id_petani}">
                            <span class="fa fa-trash"></span>
                        </button>
                    </td>
                </tr>
            `
        )
        .join("");

      $("#Petani tbody").html(rows);
      
      initTooltipsHoverDesktop();

      // Delegated events (lebih aman daripada inline onclick)
      $("#Petani tbody").off("click", ".btn-edit").on("click", ".btn-edit", function () {
          editPetani($(this).data("id"));
        });
      $("#Petani tbody").off("click", ".btn-delete").on("click", ".btn-delete", function () {
          deletePetani($(this).data("id"));
        });

      paginateTable("#Petani");

      
    },
     complete: function () {
    clearTableLoading("#Petani"); 
  },
    error: function (xhr, status, error) {
      const msg = xhr?.responseText || error || "Error tidak diketahui";
      $("#Petani tbody").html(
        `<tr><td colspan="12" class="text-center text-danger">Gagal memuat data: ${msg}</td></tr>`
      );
      console.error("AJAX Error:", msg);
    },
  });
}

// Fungsi untuk membuka modal edit data
function editPetani(id_petani) {
  $.ajax({
    url: `controller/process/aksiPetani.php?action=detail`,
    type: "POST",
    data: {id_petani},
    dataType: "json",
    success: function (data) {
      $("#modalPetani").modal("show");
      $(".modal-title").text("Edit Data "); // Judul modal
      $("#action").val("update"); // Set aksi ke "update"
      $("#submitButton").text("Update"); // Tombol submit
      data.jekel === "L"
    ? $("#jekelL").prop("checked", true)
    : $("#jekelP").prop("checked", true);
    $("#kontak").val(data.kontak);
    $("#username2").val(data.username);
  $("#password").prop("required", false);
  // $("#password").attr("placeholder", "kosongkan jika tidak ingin diubah");

      $("#id-petani").val(data.id_petani);
      $("#id-kecamatan").val(data.id_kecamatan).trigger("change");
      $("#nama-petani").val(data.nama_petani);
      $("#nik").val(data.nik);
      
      loadDesa(data.id_kecamatan, data.id_desa);


      // saat departemen berubah → muat ulang jabatan
      

     
      
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


// fungsi untuk hapus 
function deletePetani(id_petani) {
  Popup.warning(
    "Konfirmasi Hapus",
    "Apakah Anda yakin ingin menghapus Data Petani?",
    null,
    function () {
      $.ajax({
        url: `controller/process/aksiPetani.php?action=delete`,
        type: "POST",
        data: {
          id_petani,
        },
        dataType: "json",
        async: true,
        success: function (response) {
          Popup.read("Success!", "data berhasil dihapus ");
          loadPetani();
        },
        error: function () {
          Popup.error("Gagal!", "eror pada link ", 3000);
        },
      });
    }
  );
}


$(".add").on("click", function () {
 get_next_username();

});


function get_next_username(){
     $.ajax({
    url: "controller/process/getid.php?action=get_next_username",
    type: "GET",
    dataType: "json",
    success: function (res){
      $("#username2").val(res);
      $("#password").val(res);
    },
    error: function () {
      Popup.error("Gagal!", "eror pada link ", 3000);
    }, 
  });
}


$("#togglePassword").click(function () {
    let passwordField = $("#password");
    let type = passwordField.attr("type") === "password" ? "text" : "password";
    passwordField.attr("type", type);
    $(this).toggleClass("fa-eye fa-eye-slash");
  });

// fungsi untuk mengosongkan modal
$("#modalPetani").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".needs-validation").removeClass("was-validated");
  $("#id_petani").val("");
  $("#action").val("create");
  $(".modal-title").text("Tambah Data ");
});
