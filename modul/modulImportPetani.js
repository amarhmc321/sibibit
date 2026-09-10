$(document).ready(function () {
  loadKecamatanImport();

  $("#filter-kecamatan").off("change").on("change", function () {
    loadDesaImport($(this).val());
  });

  function loadKecamatanImport() {
    loadOptions4(
      "controller/process/getKecamatan.php",
      "#filter-kecamatan",
      "id_kecamatan",
      "nama_kecamatan"
    );
    $("#filter-desa").html(
      '<option value="" selected disabled>Pilih Kecamatan dulu</option>'
    );
  }

  function loadDesaImport(id_kecamatan) {
    if (!id_kecamatan) {
      $("#filter-desa").html(
        '<option value="" selected disabled>Pilih Kecamatan dulu</option>'
      );
      return;
    }

    editOptions(
      "controller/process/getDesa.php",
      "#filter-desa",
      "id_desa",
      "nama_desa",
      null,
      "Pilih Desa...",
      { id_kecamatan }
    );
  }

  $("#formUpload").submit(function (e) {
    e.preventDefault();

    const id_desa = $("#filter-desa").val();
    if (!id_desa) {
      Popup.warning("Peringatan", "Pilih desa tujuan terlebih dahulu.", 3000);
      return;
    }

    var fd = new FormData(this);
    $("#preview").html("Loading...");
    $("#sv").html("");

    $.ajax({
      url: "controller/excel/preview-petani.php",
      type: "POST",
      data: fd,
      contentType: false,
      processData: false,
      dataType: "json",
      beforeSend: function () {
        showLoader("Memuat Data Excel");
      },
      success: function (res) {
        if (res.status === "success") {
          let data = [];
          let btnSimpan = ` <button id="btnSimpan" class="btn btn-success btn-sm ">Simpan ke DB</button>`;
          let html = `
                <table class="table table-hover table-striped table-bordered" >
                <thead class="table-light">
                <tr>
                <th>NO</th>
                <th>Nama Petani</th>
                <th>NIK</th>
                <th>Jekel</th>
                <th>Kontak</th>
                <th>Alamat</th>
                </tr></thead><tbody>`;

          $.each(res.valid, function (i, d) {
            html += `<tr>
                        <td>${i + 1}</td>
                        <td>${d.nama_petani}</td>
                        <td>${d.nik || "-"}</td>
                        <td>${d.jekel || "-"}</td>
                        <td>${d.kontak || "-"}</td>
                        <td>${d.alamat || "-"}</td>
                    </tr>`;

            let encoded = `${d.nama_petani}|${d.nik}|${d.jekel}|${d.kontak}|${d.alamat}`;
            data.push(encoded);
          });

          html += "</tbody></table>";

          const desaTujuan = $("#filter-desa option:selected").text().trim();
          if (desaTujuan) {
            html += `<p class="text-muted mb-0">Desa tujuan: <b>${desaTujuan}</b></p>`;
          }

          btnSimpan += `<input type="hidden" class=" action-button" id="dataJson" value='${JSON.stringify(
            data
          )}'>`;

          if (res.invalid.length > 0) {
            html += `<p style="color:red;">Ada ${res.invalid.length} baris tidak valid, tidak akan disimpan.</p>`;
          }

          $("#preview").html(html);
          $("#sv").html(btnSimpan);
        } else {
          $("#preview").html('<p style="color:red;">' + res.message + "</p>");
        }
      },
      complete: function () {
        hideLoader();
      },
      error: function () {
        Popup.error("Gagal!", "Terjadi Kesalahan ", 3000);
      },
    });
  });

  $(document).on("click", "#btnSimpan", function () {
    let data = JSON.parse($("#dataJson").val());
    const id_desa = $("#filter-desa").val();

    $.ajax({
      url: "controller/excel/create-petani.php",
      type: "POST",
      data: {
        data: data,
        id_desa: id_desa,
      },
      dataType: "json",
      beforeSend: function () {
        showLoader("Menyimpan Data Excel");
      },
      success: function (res) {
        let msg = res.message || "Proses selesai.";

        switch (res.status) {
          case "success":
            Popup.read("Berhasil!", msg, 300000);
            break;
          case "partial":
            Popup.warning("hanya Sebagian berhasil disimpan", msg, 500000, function () {
              location.reload();
            });
            break;
          case "duplicate":
            Popup.warning(
              "Semua data sudah pernah disimpan",
              msg,
              500000,
              function () {
                location.reload();
              }
            );
            break;
          case "failed":
          default:
            Popup.error("Tidak ada data yang berhasil disimpan", msg, 400000);
            break;
        }
      },
      complete: function () {
        hideLoader();
      },
      error: function () {
        Popup.error("Gagal!", "Aksi Gagal.", 3000);
      },
    });
  });
});
