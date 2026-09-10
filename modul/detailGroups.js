$(document).ready(function () {
   const id_group = getIdFromUrl();

  headerGroups(id_group);
  detailGroups(id_group);
});

function getIdFromUrl() {
    return new URLSearchParams(window.location.search).get("id");
}

function detailGroups(id_group) {
 
  $.ajax({
    url: "controller/process/aksiGroups.php?action=group-detail",
    type: "POST",
    data: {
      id_group,
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
                    <td>${item.nama_petani}</td>
                    <td>${item.luas_lahan}</td>
                    <td>${item.longitude}/ ${item.latitude}</td>
                    <td>${item.nik}</td>
                </tr>
            `
        )
        .join("");

      $("#Groups tbody").html(rows);
      
      initTooltipsHoverDesktop();


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


function headerGroups(id_group) {
  $.ajax({
    url: `controller/process/aksiGroups.php?action=group-header`,
    type: "POST",
    data: {id_group},
    dataType: "json",
    success: function (data) {
     
     let html = `<table>
                    <tr>
                        <th>Nama Kelompok</th>
                        <td>:</td>
                        <td>${data.nama_kelompok}</td>
                    </tr>
                    <tr>
                        <th>Ketua Kelompok</th>
                        <td>:</td>
                        <td>${data.ketua}</td>
                    </tr>
                    <tr>
                        <th>Kabupaten</th>
                        <td>:</td>
                        <td>Kolaka</td>
                    </tr>
                    <tr>
                        <th>Kecamatan</th>
                        <td>:</td>
                        <td>${data.nama_kecamatan}</td>
                    </tr>
                    <tr>
                        <th>Desa</th>
                        <td>:</td>
                        <td>${data.nama_desa}</td>
                    </tr>
                </table>
`;

$("#header-group").html(html);

      
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link", 3000);
    },
  });
}