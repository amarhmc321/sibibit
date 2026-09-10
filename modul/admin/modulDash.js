(function () {
$(document).ready(function () {
     loadOptions4(
    "controller/process/getKecamatan.php",
    ["#filter-kecamatan", "#id-kecamatan"],
    "id_kecamatan",
    "nama_kecamatan"
  );  
    // Load data awal
    Dash();

    // Event listener untuk filter tanggal
    $('#filter-kecamatan, #tanggal_mulai, #tanggal_sampai').on('change', function() {
        Dash();
    });
});

function Dash() {
    const id_kecamatan = $("#filter-kecamatan").val();
    const dari = $('#tanggal_mulai').val();
    const hingga = $('#tanggal_sampai').val();
    
    $.ajax({
        url: `controller/process/aksiDash.php`,
        type: "POST",
        data: {
            id_kecamatan,
            dari,
            hingga,
        },
        dataType: "json",
        success: function (res) {
            if (res.success) {
                renderDash(res);
                updateRecentTransactions(res.recent_transactions);
            } else {
                Popup.error("Gagal!", "Data tidak ditemukan.", 3000);
            }
        },
        error: function (xhr, status, error) {
            Popup.error("Gagal!", "Terjadi kesalahan saat memuat data: " + error, 3000);
        }
    });
}

function renderDash(res) {

    // Update stat cards
    $("#tot-kelompok").text(res.tot_kelompok);
    $("#tot-kelompok-baru").html(`<i class="fas fa-arrow-up"></i> ${res.tot_kelompok_baru} baru`);
    
    $("#tot-petani").text(res.tot_petani);
    $("#tot-petani-baru").html(`<i class="fas fa-arrow-up"></i> ${res.tot_petani_baru} baru`);

    $("#tot-lahan").text(res.tot_lahan);
    $("#tot-lahan-baru").html(`<i class="fas fa-arrow-up"></i> ${res.tot_lahan_baru} baru`);
    
    $("#tot-pengajuan").text(res.tot_pengajuan);
    $("#tot-pengajuan-baru").html(`<i class="fas fa-arrow-up"></i> ${res.tot_pengajuan_baru} baru`);
}

function updateRecentTransactions(transactions) {
    const tbody = $('#recent-transactions-list');
    tbody.empty();
    
    if (!transactions || transactions.length === 0) {
        tbody.html(`
            <tr>
                <td colspan="4" class="text-center py-4">
                    <i class="fas fa-info-circle me-2"></i>Tidak ada transaksi
                </td>
            </tr>
        `);
        return;
    }
    
    transactions.forEach(item => {
        const date = new Date(item.tgl_pengajuan).toLocaleDateString('id-ID');
        const statuobatlass = item.jns_transaksi === 'Masuk' ? 'success' : 'danger';
        const statusIcon = item.jns_transaksi === 'Masuk' ? 'fa-arrow-up' : 'fa-arrow-down';
        
        tbody.append(`
            <tr>
                <td>${item.oleh} - ${date}</td>
                <td>${item.nama_kelompok}</td>
                <td>${formatStatus(item.s_pengajuan)}</td>
                <td>${item.catatan}</td>
            </tr>
        `);
    });
}

})();