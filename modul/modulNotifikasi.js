$(document).ready(function () {
  
  // pertama kali panggil notifikasi
  notifikasi();
  
// cekStock();
  // ulangi tiap 5 menit (300000 ms)
  
  // setInterval(function () {
  //  cekStock();
  // }, 300000);

  //  setInterval(function () {
  //   statusOnline();
  // }, 300000);

  //  setInterval(function () {
  //   statusOnline();
  // }, 30000);


});

function notifikasi() {
  $.ajax({
    url: `controller/process/aksiNotifikasi.php`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      renderNotifikasi(response);
    },
    error: function () {
      Popup.error("Gagal!", "Terjadi kesalahan saat memuat data.", 3000);
    },
  });
}

function statusOnline() {
fetch("controller/process/aksiUsers.php?action=status", { method: "POST" });
}




function renderNotifikasi(response) {
  if (response.data && response.data.length > 0) {
    let notifikasiHTML = "";
    let batas = 5; // jumlah notifikasi maksimal ditampilkan
    let dataTerbatas = response.data.slice(0, batas);

    dataTerbatas.forEach((item) => {
      const aktor =
        item.nama_aktor === sessionData.nama_karyawan
          ? "Anda"
          : item.nama_aktor;
      const target =item.target === sessionData.nama_karyawan ? "Anda" : item.target;
      let waktuNotif = hitungSelisihWaktu(item.created_at);
      let icon = "";
      let text = "";
      let page = "";

      switch (item.type.trim()) {
        case "rop":
          icon = '<i class="fa fa-circle-exclamation text-info"></i>';
          text = `${item.message}`;
          page = `transaksi`;
          break;
        case "ss":
          icon = '<i class="fa fa-triangle-exclamation text-danger"></i>';
          text = `${item.message}`;
          page = `transaksi`;
          break;
        default:
          icon = '<i class="fa fa-circle-exclamation text-warning"></i>';
          text = `${item.message}`;
          page = `data-pengajuan`;
      }

      notifikasiHTML += `
                <li>
                    <a href="#" class="dropdown-item is-read" data-page="${page}" data-id="${item.id_notifikasi}">
                        <div class="d-flex">
                            <div class="me-3">${icon}</div>
                            <div class="atur-notif">
                                <p class="mb-0 text-truncate" style="max-width:300px;">${text}</p>
                                <small class="text-muted">${waktuNotif}</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
            `;
    });

    $("#notifikasi").html(`
            <a href="#" class="nav-link dropdown-toggle" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell"></i>
                <span class="badge bg-danger rounded-pill">${response.total_notif}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notificationDropdown">
                <li><h6 class="dropdown-header">Notifikasi Terbaru</h6></li>
                ${notifikasiHTML}
                <li><a href="#" class="dropdown-item text-center fw-bold atur-notif" data-page="all-notif">Lihat semua notifikasi(${response.total_notif})</a></li>
            </ul>
        `);
  } else {
    $("#notifikasi").html(`
            <a href="#" class="nav-link dropdown-toggle" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notificationDropdown">
                <li><h6 class="dropdown-header"><b class="text-danger">Kosong</b></h6></li>
                <li><a href="#" class="dropdown-item text-center fw-bold atur-notif" data-page="all-notif">Lihat semua notifikasi</a></li>
            </ul>
        `);
  }
}


