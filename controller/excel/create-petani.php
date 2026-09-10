<?php
session_start();
header('Content-Type: application/json');
include '../config/database.php';

if (!isset($_POST['data']) || empty($_POST['data']) || !isset($_POST['id_desa'])) {
    echo json_encode([
        'status'  => 'failed',
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

$id_desa = (int) $_POST['id_desa'];

$sukses       = 0;
$duplikat     = 0;
$gagal        = 0;
$tidak_valid  = 0;

// Ambil id_petani berdasarkan NIK
$cekNik = $conn->prepare("SELECT id_petani FROM tbl_petani WHERE nik = ? AND nik <> ''");

// Cek duplikat bila NIK kosong (berdasarkan nama + desa)
$cekPetani = $conn->prepare("SELECT id_petani FROM tbl_petani WHERE nama_petani = ? AND id_desa = ?");

// Insert akun user
$insertUser = $conn->prepare("INSERT INTO tbl_users (username, password, level) VALUES (?, ?, 3)");

// Insert petani
$insertPetani = $conn->prepare("INSERT INTO tbl_petani (id_petani, id_desa, nama_petani, alamat, jekel, kontak, nik) VALUES (?, ?, ?, ?, ?, ?, ?)");

if (!$cekNik || !$cekPetani || !$insertUser || !$insertPetani) {
    echo json_encode([
        'status'  => 'failed',
        'message' => 'Gagal prepare statement'
    ]);
    exit;
}

/* ===============================
   GENERATE USERNAME Petani###
================================ */
function nextUsername($conn)
{
    $res = $conn->query("SELECT MAX(username) AS last_username FROM tbl_users WHERE username REGEXP '^Petani[0-9]+$'");

    $last = 0;
    if ($res) {
        $row = $res->fetch_assoc();
        if (!empty($row['last_username'])) {
            $last = (int) preg_replace('/[^0-9]/', '', $row['last_username']);
        }
    }

    return "Petani" . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
}

foreach ($_POST['data'] as $row) {

    list($nama_petani, $nik, $jekel, $kontak, $alamat) = array_pad(explode('|', $row), 5, '');

    $nama_petani = trim($nama_petani);
    $nik         = trim($nik);
    $kontak      = trim($kontak);
    $alamat      = trim($alamat);

    if ($nama_petani === '') {
        $tidak_valid++;
        continue;
    }

    $jekelLower = strtolower(trim($jekel));
    if ($jekelLower === 'p' || strpos($jekelLower, 'perempuan') === 0) {
        $jekel = 'P';
    } else {
        $jekel = 'L';
    }

    /* ===============================
       CEK DUPLIKAT
    ================================ */
    if ($nik !== '') {
        $cekNik->bind_param("s", $nik);
        $cekNik->execute();
        $cekNik->store_result();

        if ($cekNik->num_rows > 0) {
            $duplikat++;
            continue;
        }
    } else {
        $cekPetani->bind_param("si", $nama_petani, $id_desa);
        $cekPetani->execute();
        $cekPetani->store_result();

        if ($cekPetani->num_rows > 0) {
            $duplikat++;
            continue;
        }
    }

    /* ===============================
       SIMPAN PER BARIS (ISOLASI ERROR)
    ================================ */
    $conn->begin_transaction();

    try {
        $username = nextUsername($conn);
        $password = password_hash('SIBIBIT', PASSWORD_DEFAULT);

        $insertUser->bind_param("ss", $username, $password);
        if (!$insertUser->execute()) {
            throw new Exception("Gagal insert tbl_users");
        }

        $id_petani = $conn->insert_id;

        $insertPetani->bind_param(
            "iisssss",
            $id_petani,
            $id_desa,
            $nama_petani,
            $alamat,
            $jekel,
            $kontak,
            $nik
        );

        if (!$insertPetani->execute()) {
            throw new Exception("Gagal insert tbl_petani");
        }

        $conn->commit();
        $sukses++;
    } catch (Exception $e) {
        $conn->rollback();
        $gagal++;
    }
}

$status = 'failed';
if ($sukses > 0 && ($duplikat > 0 || $gagal > 0 || $tidak_valid > 0)) {
    $status = 'partial';
} elseif ($sukses > 0) {
    $status = 'success';
} elseif ($duplikat > 0) {
    $status = 'duplicate';
}

echo json_encode([
    'status'  => $status,
    'message' => "Berhasil: $sukses | Duplikat: $duplikat | Gagal: $gagal | Tidak valid: $tidak_valid"
]);
exit;
