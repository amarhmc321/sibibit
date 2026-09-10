(function () {
$(document).ready(function () {
     loadOptions2(
    "controller/process/getUserGroups.php",
    ["#filter-kelompok", "#id-kecamatan"],
    "id_group",
    "nama_group", Dash
  );  
    // Load data awal
   

    // Event listener untuk filter tanggal
    $('#filter-kelompok, #tanggal_mulai, #tanggal_sampai').on('change', function() {
        Dash();
    });
});

function Dash() {
    const id_group = $("#filter-kelompok").val();
    const dari     = $('#tanggal_mulai').val();
    const hingga   = $('#tanggal_sampai').val();
    
    $.ajax({
        url: `controller/process/aksiDash.php`,
        type: "POST",
        data: {
            id_group,
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
    $("#tot-pengajuan").text(res.tot_pengajuan);
    $("#tot-pending").text(res.tot_pending);
    $("#tot-acc").text(res.tot_acc);
    $("#tot-tolak").text(res.tot_tolak);
    
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