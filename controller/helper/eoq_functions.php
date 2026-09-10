<?php



function hitungROP($rata_permintaan_harian, $lead_time, $safety_stock) {
    // ROP = (d × L) + SS
    return round(($rata_permintaan_harian * $lead_time) + $safety_stock);
}


function hitungHoldingCost($harga_satuan, $persentase) {
    if ($harga_satuan <= 0 || $persentase <= 0) {
        return 0;
    }
    return $harga_satuan * ($persentase / 100);
}

function hitungEOQ($permintaan_tahunan, $biaya_pemesanan, $holding_cost) {
    if ($permintaan_tahunan <= 0 || $biaya_pemesanan <= 0 || $holding_cost <= 0) {
        return 0;
    }
    return sqrt((2 * $permintaan_tahunan * $biaya_pemesanan) / $holding_cost);
}

function hitungSafetyStock($tingkat_layanan, $deviasi_permintaan, $lead_time) {
    // Pastikan lead_time minimal 1
    $lead_time = max(1, $lead_time);
    
    $nilai_z = [
        80 => 0.84,
        85 => 1.04,
        90 => 1.28,
        95 => 1.65,
        99 => 2.33
    ];
    
    $z = $nilai_z[$tingkat_layanan] ?? 1.65; // default 95%
    
    return $z * $deviasi_permintaan * sqrt($lead_time);
}





function cek_status_stok($stok_saat_ini, $rop, $stok_minimum, $stok_maksimum) {
    $status = 'NORMAL';
    $pesan = '';
    $kelas_alert = 'success';

    if ($stok_saat_ini <= $rop) {
        $status = 'PESAN_ULANG';
        $pesan = 'Stok telah mencapai titik pemesanan ulang!';
        $kelas_alert = 'warning';
    }

    if ($stok_saat_ini <= $stok_minimum) {
        $status = 'KRITIS';
        $pesan = 'Stok berada di bawah batas minimum!';
        $kelas_alert = 'danger';
    }

    if ($stok_saat_ini >= $stok_maksimum) {
        $status = 'BERLEBIH';
        $pesan = 'Stok melebihi batas maksimum!';
        $kelas_alert = 'info';
    }

    return array(
        'status' => $status,
        'pesan' => $pesan,
        'kelas_alert' => $kelas_alert
    );
}

function check_stock_level($current_stock, $reorder_point, $min_stock, $max_stock) {
    $status = 'NORMAL';
    $message = '';
    $alert_class = 'success';
    
    if ($current_stock <= $reorder_point) {
        $status = 'REORDER';
        $message = 'Sudah mencapai Reorder Point!';
        $alert_class = 'warning';
    }
    
    if ($current_stock <= $min_stock) {
        $status = 'CRITICAL';
        $message = 'Stok sudah di bawah minimum!';
        $alert_class = 'danger';
    }
    
    if ($current_stock >= $max_stock) {
        $status = 'OVERSTOCK';
        $message = 'Stok melebihi batas maksimal!';
        $alert_class = 'info';
    }
    
    return array(
        'status' => $status,
        'message' => $message,
        'alert_class' => $alert_class
    );
}

?>
