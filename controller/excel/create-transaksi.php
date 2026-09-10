<?php
session_start();
header('Content-Type: application/json');
include '../config/database.php';

if (isset($_POST['data'])) {

    $sukses_insert   = 0;
    $duplikat        = 0;
    $gagal           = 0;
    $tidak_ditemukan = 0;

    $created_by     = $_SESSION['id_user'];

    $conn->autocommit(false);

    // Ambil id_obat
    $getId = $conn->prepare("SELECT id_obat FROM tbl_obat WHERE kd_obat = ?");

    // Cek duplikat transaksi
    $cekTransaksi = $conn->prepare(" SELECT id_transaksi FROM tbl_transaksi WHERE id_obat = ? AND tgl_transaksi = ? ");

    // Insert transaksi
    $stmtInsert = $conn->prepare("INSERT INTO tbl_transaksi (id_obat, tgl_transaksi, jml_transaksi, created_by) VALUES (?, ?, ?, ?)");

    // Update stok di tbl_obat
    $updateStok = $conn->prepare("UPDATE tbl_obat SET stock = stock + ? WHERE id_obat = ?");


    if (!$getId || !$cekTransaksi || !$stmtInsert || !$updateStok) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal prepare statement']);
        exit;
    }

    try {

        foreach ($_POST['data'] as $row) {

            list(
                $kd_obat,
                $tgl_transaksi,
                $jml_transaksi
            ) = explode('|', $row);

            // Ambil id_obat 
            $getId->bind_param("s", $kd_obat);
            $getId->execute();
            $getId->store_result();

            if ($getId->num_rows === 0) {
                $tidak_ditemukan++;
                continue;
            }

            $getId->bind_result($id_obat);
            $getId->fetch();

            // Cek duplikat
            $cekTransaksi->bind_param("is", $id_obat, $tgl_transaksi);
            $cekTransaksi->execute();
            $cekTransaksi->store_result();

            if ($cekTransaksi->num_rows > 0) {
                $duplikat++;
                continue;
            }

            // Insert
            $stmtInsert->bind_param(
                "isii",
                $id_obat,
                $tgl_transaksi,
                $jml_transaksi,
                $created_by
            );

            if (!$stmtInsert->execute()) {
                throw new Exception("Gagal insert");
            }

            $sukses_insert++;
            // Tentukan perubahan stok
            $qty = (int)$jml_transaksi;
            $qty = -$qty;

            // Update stok
            $updateStok->bind_param("ii", $qty, $id_obat);

            if (!$updateStok->execute()) {
                throw new Exception("Gagal update stok");
            }
            
        }

        $conn->commit();

    } catch (Exception $e) {
        $conn->rollback();
        $gagal++;
    }

    $conn->autocommit(true);

    echo json_encode([
        'status'  => ($sukses_insert > 0 ? 'success' : 'failed'),
        'message' => "Berhasil: $sukses_insert | Duplikat: $duplikat | Gagal: $gagal | Tidak ditemukan: $tidak_ditemukan"
    ]);
    exit;
}
