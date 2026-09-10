$(document).ready(function () {
  initTooltipsWithClose();
  formTemplate("#form-users", {
    defaultEndpoint: "controller/process/aksiUsers.php",
  });
  // Reset modal saat ditutup
  $("#modalUsers").on("hidden.bs.modal", resetModalUsers);
  $(".add").on("click", openAddUsersModal);
  loadUsers();
  setupSelect("#modalUsers");
});

// ============================
// LOAD DATA ADMIN
// ============================
function loadUsers() {
  const idLokasi = $("#filter-lokasi").val() || "";
  const token = $('meta[name="csrf-token"]').attr("content");

  $.ajax({
    url: "controller/process/aksiUsers.php?action=read",
    type: "POST",
    data: { filterLokasi: idLokasi }, // kirim ke server
    dataType: "json",
    headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Authorization': token
  },
    success: function (data) {
      const $tbody = $("#Users tbody");
      $tbody.empty();

      if (!Array.isArray(data) || data.length === 0) {
        $tbody.html(
          '<tr><td colspan="10" class="text-center">Tidak ada data</td></tr>'
        );
        return;
      }

      let rows = data
        .map((item, index) => usersRow(item, index + 1))
        .join("");
      $tbody.html(rows);

      initTooltipsHoverDesktop();
      paginateTable("#Users");

      initTableEvents("#Users", {
        onRowClick: (id) => editUsers(id),
        onEdit: (id) => editUsers(id),
        onDelete: (id) => deleteUsers(id),
      });
    },
    error: function (xhr, status, error) {
      console.error("Gagal memuat data:", error);
      $("#Users tbody").html(
        '<tr><td colspan="10" class="text-center text-danger">Error memuat data</td></tr>'
      );
    },
  });
}



// Template untuk 1 row admin
function usersRow(item, no) {
  return `
        <tr class="edit" data-id="${item.id_user}">
            <td>${no}</td>
            <td><div class="user-avatar-sm me-1" data-nama="${item.nama}" data-foto="${item.foto}"></div></td>
            <td>${item.username}</td>
            <td>${s_level(item.level)}</td>
            <td>${cekON(item.record) || "-"}</td>
            <td class="d-flex gap-2">
                <button class="btn btn-secondary btn-sm btn-edit" data-bs-toggle="tooltip" title="Edit">
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm btn-delete" data-bs-toggle="tooltip" title="Hapus">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>`;
}

// ============================
// EDIT ADMIN
// ============================
function editUsers(id_user) {
  showLoader("Memuat data ...");
  $.ajax({
    url: `controller/process/aksiUsers.php?action=detail`,
    type: "POST",
    data: {
      id_user,
    },
    dataType: "json",
    success: function (data) {
      fillUsersModal(data);
      $("#modalUsers").modal("show");
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link.", 3000);
    },
    complete: function () {
      setTimeout(hideLoader, 100);
    },
  });
}

// Isi data modal dari response
function fillUsersModal(data) {
  $("#action").val("update");
  $("#id_user").val(data.id_user);
  $("#id_penulis").val(String(data.id_penulis).padStart(4, "0")).prop("readonly", true);
  $("#nama").val(data.nama);
  $("#username").val(data.username);
  $("#password").prop("required", false);
  $("#password").attr("placeholder", "Biarkan kosong jika tidak ingin dirubah");
  $(".modal-title").text("Edit Data Pengguna");
  $(`input[name='level'][value='${data.level}']`)
    .prop("checked", true)
    .change();
}

// ============================
// DELETE ADMIN
// ============================
function deleteUsers(id_user) {
  Popup.warning(
    "Konfirmasi Hapus",
    "Apakah Anda yakin ingin menghapus Data Pengguna?",
    null,
    function () {
      $.ajax({
        url: `controller/process/aksiUsers.php?action=delete`,
        type: "POST",
        data: {
          id_user,
        },
        dataType: "json",
        success: function () {
          Popup.read("Success!", "Data berhasil dihapus");
          loadUsers();
        },
        error: function () {
          Popup.error("Gagal!", "Error pada link.", 3000);
        },
      });
    }
  );
}



function openAddUsersModal() {
  $.getJSON(
    "controller/process/getId.php?action=generate_next_username",
    function (dt) {
      $("#username").val(dt.username);
      $("#password").val(dt.username);
    }
  ).fail(function (err) {
    console.error("Terjadi kesalahan:", err);
  });
}

$("#togglePassword").click(function () {
  let passwordField = $("#password");
  let type = passwordField.attr("type") === "password" ? "text" : "password";
  passwordField.attr("type", type);
  $(this).toggleClass("fa-eye fa-eye-slash");
});

// ============================
// RESET MODAL ADMIN
// ============================
function resetModalUsers() {
  const $form = $(this).find("form")[0];
  if ($form) $form.reset();
  $(".needs-validation").removeClass("was-validated");
  $("#action").val("create");
  $("#id_user").val("");
  $(".modal-title").text("Tambah Data Pengguna");
  $("#password").prop("required", true);
  $("#password").attr("placeholder", "Minimal 8 karakter");
}


