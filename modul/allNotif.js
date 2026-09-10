$(document).ready(function () {
  allNotifikasi();
  // Event handler ketika tombol hapus diklik
  $(document)
    .off("click", ".btn-hapus-notif")
    .on("click", ".btn-hapus-notif", function (event) {
      const id = $(this).data("id");

      if (confirm("Apakah Anda yakin ingin menghapus notifikasi ini?")) {
        $.ajax({
          url: `controller/process/aksiNotifikasi.php?action=delete`,
          type: "POST",
          data: { id },
          dataType: "json",
          success: function (response) {
            if (response.status === "success") {
              Popup.success("Berhasil!", "Notifikasi telah dihapus.", 3000);
              allNotifikasi();
              notifikasi();
            } else {
              Popup.error("Gagal!", "Gagal menghapus notifikasi.", 3000);
            }
          },
          error: function () {
            Popup.error("Gagal!", "Terjadi kesalahan sistem.", 3000);
          },
        });
      }
    });
});

function allNotifikasi() {
  $.ajax({
    url: `controller/process/allNotif.php`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      renderAllNotif(response);
    },
    error: function () {
      Popup.error("Gagal!", "Terjadi kesalahan saat memuat data.", 3000);
    },
  });
}

function renderAllNotif(response) {
  const $kontainerSemuaNotif = $("#halaman-semua-notif");
  if ($kontainerSemuaNotif.length === 0) return;

  $("#total-notif-page").text(response.total_notif || 0);

  if (response.data && response.data.length > 0) {
    let semuaNotifHTML = "";

    response.data.forEach((item) => {
      let waktuNotif = hitungSelisihWaktu(item.created_at);
      let icon = "";
      let text = item.message;
      let page = "";

      // Logic penentuan icon dan page
      switch (item.type.trim()) {
        case "rop":
          icon = '<i class="fa fa-circle-exclamation text-info fs-4"></i>';
          page = `suku-cadang`;
          break;
        case "ss":
        case "ms":
          icon = '<i class="fa fa-triangle-exclamation text-danger fs-4"></i>';
          page = `suku-cadang`;
          break;
        case "pending":
          icon = '<i class="fa fa-cash-register text-primary fs-4"></i>';
          page = `transaksi`;
          break;
        default:
          icon = '<i class="fa fa-cash-register text-secondary fs-4"></i>';
          page = `data-pengajuan`;
      }

      // --- LOGIC PEMBEDA IS_READ ---
      // Jika is_read == 0 (belum dibaca), beri kelas khusus bg-light dan indikator titik biru
      const isUnread = parseInt(item.is_read) === 0;
      const kelasBelumBaca = isUnread ? "fw-bold bg-grey" : "";
      const titikIndikator = isUnread
        ? '<span class="badge bg-primary rounded-circle p-1 me-2" style="width: 8px; height: 8px; inline-block;"> </span>'
        : "";

      semuaNotifHTML += `
        <div class="list-group-item list-group-item-action p-3 ${kelasBelumBaca} d-flex align-items-center justify-content-between" >
            <a href="#" class="btn-notif-link d-flex align-items-center text-decoration-none flex-grow-1 me-3" data-page="${page}" data-id="${item.id_notifikasi}">
                <div class="me-3 flex-shrink-0">${icon}</div>
                <div class="flex-grow-1">
                    <p class="mb-1 text-secondary text-wrap">${titikIndikator}${text}</p>
                    <small class="text-muted d-block"><i class="fa-regular fa-clock me-1"></i>${waktuNotif}</small>
                </div>
            </a>
            
            <div class="flex-shrink-0 d-flex align-items-center">
                <button class="btn btn-sm btn-outline-danger btn-hapus-notif border-0 me-2" data-id="${item.id_notifikasi}" title="Hapus Notifikasi">
                    <i class="fa fa-trash-can fs-5"></i>
                </button>
                <i class="fa fa-chevron-right text-muted fs-7"></i>
            </div>
        </div>
      `;
    });

    $kontainerSemuaNotif.html(semuaNotifHTML);
  } else {
    $kontainerSemuaNotif.html(`
        <div class="text-center p-5">
            <i class="fa-regular fa-bell-slash text-muted mb-3" style="font-size: 3rem;"></i>
            <p class="text-muted mb-0">Tidak ada notifikasi untuk saat ini.</p>
        </div>
    `);
  }
}
