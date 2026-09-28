<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_kecamatan = isset($_POST['id_kecamatan']) ? htmlspecialchars($_POST['id_kecamatan']) : '';
$id_group     = isset($_POST['id_group']) ? htmlspecialchars($_POST['id_group']) : '';


switch ($action) {
    case 'read2':
        readPengajuanUsers($conn, $id_group);
        break;
    case 'read':
        readPengajuan($conn, $id_kecamatan, $id_group);
        break;
    case 'detail2':
        detailPengajuanUsers($conn);
        break;
    case 'detail':
        detailPengajuan($conn);
        break;
    case 'approve':
        approvePengajuan($conn);
        break;
    case 'create':
        createPengajuan($conn);
        break;
    case 'update':
        updatePengajuan($conn);
        break;
    case 'delete':
        deletePengajuan($conn);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}




// === Fungsi Ambil Data ===

function readPengajuanUsers($conn, $id_group){

    if (!$id_group) {
       $q = "SELECT pj.*,
            g.nama_kelompok,
            p.nama_petani as ketua,
            d.nama_desa,
            k.nama_kecamatan,
            b.nama_bibit,
            b.jml_per_hektar,
            b.satuan,
            COALESCE(tl.tot_luas_lahan, 0) AS tot_luas_lahan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_bibit b ON b.id_bibit = pj.id_bibit
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN (
           SELECT id_group, SUM(luas_lahan) AS tot_luas_lahan 
           FROM tbl_lahan 
           GROUP BY id_group
       ) tl ON pj.id_group = tl.id_group";
        $stmt = $conn->prepare($q);
    }else{
        $q = "SELECT pj.*,
            g.nama_kelompok,
            p.nama_petani as ketua,
            d.nama_desa,
            k.nama_kecamatan,
            b.nama_bibit,
            b.jml_per_hektar,
            b.satuan,
            COALESCE(tl.tot_luas_lahan, 0) AS tot_luas_lahan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_bibit b ON b.id_bibit = pj.id_bibit
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN (
           SELECT id_group, SUM(luas_lahan) AS tot_luas_lahan 
           FROM tbl_lahan 
           GROUP BY id_group
       ) tl ON pj.id_group = tl.id_group
         WHERE pj.id_group = ?";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("i", $id_group);
    }
        
    $stmt->execute();
    $stmt = $stmt->get_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        return;
    }

    $data = $stmt->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);

    if ($stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
}

function readPengajuan($conn, $id_kecamatan, $id_group){

    if (!$id_kecamatan) {
       $q = "SELECT pj.*,
            g.nama_kelompok,
            p.nama_petani as ketua,
            p2.nama_petani as oleh,
            d.nama_desa,
            k.nama_kecamatan,
            b.nama_bibit,
            b.jml_per_hektar,
            b.satuan,
            COALESCE(tl.tot_luas_lahan, 0) AS tot_luas_lahan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p  ON g.id_leader = p.id_petani
       LEFT JOIN tbl_petani p2 ON pj.id_petani = p2.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_bibit b ON b.id_bibit = pj.id_bibit
       LEFT JOIN (
           SELECT id_group, SUM(luas_lahan) AS tot_luas_lahan 
           FROM tbl_lahan 
           GROUP BY id_group
       ) tl ON pj.id_group = tl.id_group";
        $stmt = $conn->prepare($q);
    }else{
        $q = "SELECT pj.*,
           g.nama_kelompok,
            p.nama_petani as ketua,
            p2.nama_petani as oleh,
            d.nama_desa,
            k.nama_kecamatan,
            b.nama_bibit,
            b.jml_per_hektar,
            b.satuan,
            COALESCE(tl.tot_luas_lahan, 0) AS tot_luas_lahan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_petani p2 ON pj.id_petani = p2.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_bibit b ON b.id_bibit = pj.id_bibit
       LEFT JOIN (
           SELECT id_group, SUM(luas_lahan) AS tot_luas_lahan 
           FROM tbl_lahan 
           GROUP BY id_group
       ) tl ON pj.id_group = tl.id_group
         WHERE k.id_kecamatan = ?";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("i", $id_kecamatan);
    }
        
    $stmt->execute();
    $stmt = $stmt->get_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        return;
    }

    $data = $stmt->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);

    if ($stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
}




// === Fungsi Ambil Data Detail/edit ===

function detailPengajuanUsers($conn){
    $id_pengajuan = $_POST['id_pengajuan'];

    if (!isset($id_pengajuan) || $id_pengajuan === "") {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }

   $q = "SELECT pj.*,
            g.nama_kelompok,
            p.nama_petani as ketua,
            d.nama_desa,
            k.nama_kecamatan,
            b.nama_bibit,
            b.jml_per_hektar,
            b.satuan,
            COALESCE(tl.tot_luas_lahan, 0) AS tot_luas_lahan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_petani p ON pj.id_petani = p.id_petani
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_desa d ON pj.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_bibit b ON b.id_bibit = pj.id_bibit
       LEFT JOIN (
           SELECT id_group, SUM(luas_lahan) AS tot_luas_lahan 
           FROM tbl_lahan 
           GROUP BY id_group
       ) tl ON pj.id_group = tl.id_group
      WHERE pj.id_pengajuan = ?";

    $stmt = $conn->prepare($q);

    if (!$stmt) {
        echo json_encode(["error" => "Gagal mempersiapkan query: " . $conn->error]);
        return;
    }

    $stmt->bind_param("i", $id_pengajuan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        return;
    }

    $row = $result->fetch_assoc();
    echo json_encode($row);

    $stmt->close();
}

function detailPengajuan($conn){
    $id_pengajuan = $_POST['id_pengajuan'];

    if (!isset($id_pengajuan) || $id_pengajuan === "") {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }

   $q = "SELECT pj.*,
            g.nama_kelompok,
            p.nama_petani as ketua,
            d.nama_desa,
            k.nama_kecamatan,
            b.nama_bibit,
            b.jml_per_hektar,
            b.satuan,
            COALESCE(tl.tot_luas_lahan, 0) AS tot_luas_lahan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_bibit b ON b.id_bibit = pj.id_bibit
       LEFT JOIN (
           SELECT id_group, SUM(luas_lahan) AS tot_luas_lahan 
           FROM tbl_lahan 
           GROUP BY id_group
       ) tl ON pj.id_group = tl.id_group
      WHERE pj.id_pengajuan = ?";

    $stmt = $conn->prepare($q);

    if (!$stmt) {
        echo json_encode(["error" => "Gagal mempersiapkan query: " . $conn->error]);
        return;
    }

    $stmt->bind_param("i", $id_pengajuan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        return;
    }

    $row = $result->fetch_assoc();
    echo json_encode($row);

    $stmt->close();
}

// === Helper Dinamis Luas Lahan Kelompok & Rumus Bibit ===

function getTotalLuasLahanGroup($conn, $id_group) {
    if (empty($id_group)) return 0;
    $st = $conn->prepare("SELECT COALESCE(SUM(luas_lahan), 0) AS tot_luas_lahan FROM tbl_lahan WHERE id_group = ?");
    $st->bind_param("i", $id_group);
    $st->execute();
    $r = $st->get_result()->fetch_assoc();
    $st->close();
    return floatval($r['tot_luas_lahan'] ?? 0);
}

function getRumusBibit($conn, $id_bibit, $nama_bibit = null) {
    if (!empty($id_bibit)) {
        $st = $conn->prepare("SELECT jml_per_hektar, satuan, nama_bibit FROM tbl_bibit WHERE id_bibit = ?");
        $st->bind_param("i", $id_bibit);
        $st->execute();
        $r = $st->get_result()->fetch_assoc();
        $st->close();
        if ($r) {
            return [
                'rate' => intval($r['jml_per_hektar'] ?? 100),
                'satuan' => $r['satuan'] ?? 'pohon',
                'nama_bibit' => $r['nama_bibit']
            ];
        }
    }
    if (!empty($nama_bibit)) {
        $st = $conn->prepare("SELECT jml_per_hektar, satuan, nama_bibit FROM tbl_bibit WHERE nama_bibit LIKE ? LIMIT 1");
        $like = "%" . $nama_bibit . "%";
        $st->bind_param("s", $like);
        $st->execute();
        $r = $st->get_result()->fetch_assoc();
        $st->close();
        if ($r) {
            return [
                'rate' => intval($r['jml_per_hektar'] ?? 100),
                'satuan' => $r['satuan'] ?? 'pohon',
                'nama_bibit' => $r['nama_bibit']
            ];
        }
    }
    return ['rate' => 100, 'satuan' => 'pohon', 'nama_bibit' => ''];
}

// === Fungsi Tambah Data ===

function approvePengajuan($conn) {
    // Mulai transaksi database
    $conn->begin_transaction();

    try {
        // =====================
        // Ambil data POST & Session
        // =====================
       
        $id_pengajuan   = $_POST['id_pengajuan'] ?? null;
        $s_pengajuan    = $_POST['s_pengajuan'];
        $jml_bantuan    = isset($_POST['jml_bantuan']) ? intval($_POST['jml_bantuan']) : 0;
        $tgl_penyaluran = !empty($_POST['tgl_penyaluran']) ? $_POST['tgl_penyaluran'] : null;
        $catatan        = $_POST['catatan'] ?? '';

        if (empty($id_pengajuan)) {
            throw new Exception("ID Pengajuan tidak valid.");
        }

        // Ambil data pengajuan terkait id_group & id_bibit
        $st_pj = $conn->prepare("SELECT id_group, id_bibit, jml_bantuan FROM tbl_pengajuan WHERE id_pengajuan = ?");
        $st_pj->bind_param("i", $id_pengajuan);
        $st_pj->execute();
        $row_pj = $st_pj->get_result()->fetch_assoc();
        $st_pj->close();

        if (!$row_pj) {
            throw new Exception("Data pengajuan tidak ditemukan.");
        }

        $id_group = $row_pj['id_group'];
        $id_bibit = $row_pj['id_bibit'];

        // Adaptasi saat approval: jika s_pengajuan == 1 (ACC / Approve)
        if ($s_pengajuan == 1) {
            $tot_luas_lahan = getTotalLuasLahanGroup($conn, $id_group);
            $rumus = getRumusBibit($conn, $id_bibit);

            if ($jml_bantuan <= 0 && $tot_luas_lahan > 0) {
                $jml_bantuan = (int) round($tot_luas_lahan * $rumus['rate']);
            }
        }
        
        $q1 = $conn->prepare("UPDATE tbl_pengajuan SET tgl_penyaluran = ?, jml_bantuan = ?, s_pengajuan = ?, catatan = ? WHERE id_pengajuan = ?");
        $q1->bind_param("sissi", $tgl_penyaluran, $jml_bantuan, $s_pengajuan, $catatan, $id_pengajuan);

        if (!$q1->execute()) {
            throw new Exception("Gagal update data pengajuan.");
        }

        // =====================
        // Insert Notifikasi (Opsional untuk Update)
        // =====================

        $message = "Pengajuan Anda Dengan ID $id_pengajuan telah ditinjau";
        $type    = "Approved";

        $q2 = $conn->prepare("INSERT INTO tbl_notifikasi (message, type, id_ref) VALUES (?, ?, ?)");
        $q2->bind_param("ssi", $message, $type, $id_pengajuan);

        if (!$q2->execute()) {
            throw new Exception("Gagal insert notifikasi update.");
        }
        
        // =====================
        // COMMIT
        // =====================
        $conn->commit();

        echo json_encode([
            "status"  => "success",
            "message" => "Data pengajuan berhasil diperbarui"
        ]);

    } catch (Exception $e) {
        // =====================
        // ROLLBACK
        // =====================
        $conn->rollback();

        echo json_encode([
            "status"  => "error",
            "message" => $e->getMessage()
        ]);
    }
}


function createPengajuan($conn) {
    // Mulai transaksi database
    $conn->begin_transaction();

    try {
        // =====================
        // Ambil data POST & Session
        // =====================
        $id_petani       = $_SESSION['id_user'] ?? null; 
        $id_group        = $_POST['id_group'] ?? null;
        $id_bibit        = $_POST['id_bibit'] ?? null;
        $judul           = $_POST['judul'] ?? '';

        $tgl_penyaluran  = !empty($_POST['tgl_penyaluran']) ? $_POST['tgl_penyaluran'] : null;
        $jml_bantuan     = isset($_POST['jml_bantuan']) ? intval($_POST['jml_bantuan']) : 0;
        
        if (empty($id_group)) {
            throw new Exception("Kelompok tani wajib dipilih.");
        }

        // Jika jml_bantuan kosong atau 0, hitung otomatis berdasarkan total luas lahan kelompok & rumus bibit
        if ($jml_bantuan <= 0) {
            $tot_luas_lahan = getTotalLuasLahanGroup($conn, $id_group);
            $rumus = getRumusBibit($conn, $id_bibit, $judul);
            if ($tot_luas_lahan > 0) {
                $jml_bantuan = (int) round($tot_luas_lahan * $rumus['rate']);
            }
        }
        
        // =====================
        // Proses Upload File
        // =====================
        $nama_file = null;
        if (isset($_FILES['file-proposal']) && $_FILES['file-proposal']['error'] === UPLOAD_ERR_OK) {
            $tmp_name  = $_FILES['file-proposal']['tmp_name'];
            $file_ext  = pathinfo($_FILES['file-proposal']['name'], PATHINFO_EXTENSION);
            
            $nama_file = time() . '_' . uniqid() . '.' . $file_ext; 
            $upload_dir = '../../assets/uploads/'; 
            
            if (!move_uploaded_file($tmp_name, $upload_dir . $nama_file)) {
                throw new Exception("Gagal mengupload file proposal.");
            }
        }

        // =====================
        // 1. Insert Pengajuan
        // =====================
        $q1 = $conn->prepare("INSERT INTO tbl_pengajuan (id_group, id_petani, id_bibit, tgl_penyaluran, judul, file_proposal, jml_bantuan) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $q1->bind_param("iiisssi", $id_group, $id_petani, $id_bibit, $tgl_penyaluran, $judul, $nama_file, $jml_bantuan);

        if (!$q1->execute()) {
            throw new Exception("Gagal insert tbl_pengajuan");
        }
        
        $id_ref = $conn->insert_id; 

        // =====================
        // 2. Insert Notifikasi
        // =====================
        $message = "Pengajuan Baru dengan Judul <b>{$judul}</b> dengan ID kelompok {$id_group}";
        $type    = "Pending";

        $q2 = $conn->prepare("INSERT INTO tbl_notifikasi (message, type, id_ref) VALUES (?, ?, ?)");
        $q2->bind_param("ssi", $message, $type, $id_ref);

        if (!$q2->execute()) {
            throw new Exception("Gagal insert tbl_notifikasi");
        }
        
        // =====================
        // COMMIT
        // =====================
        $conn->commit();

        echo json_encode([
            "status"  => "success",
            "message" => "Data pengajuan berhasil ditambahkan"
        ]);

    } catch (Exception $e) {
        // =====================
        // ROLLBACK
        // =====================
        $conn->rollback();

        echo json_encode([
            "status"  => "error",
            "message" => $e->getMessage()
        ]);
    }
}

// === Fungsi Update Data ===
function updatePengajuan($conn) {
    // Mulai transaksi database
    $conn->begin_transaction();

    try {
        // =====================
        // Ambil data POST
        // =====================
        $id_pengajuan = $_POST['id_pengajuan'] ?? null;
        $id_group     = $_POST['id_group'] ?? null;
        $id_bibit     = $_POST['id_bibit'] ?? null;
        $judul        = $_POST['judul'] ?? null;
        $jml_bantuan  = isset($_POST['jml_bantuan']) ? intval($_POST['jml_bantuan']) : 0;
        
        if (empty($id_pengajuan)) {
            throw new Exception("ID Pengajuan tidak valid.");
        }

        if (empty($id_bibit)) {
            $st_cek = $conn->prepare("SELECT id_bibit FROM tbl_pengajuan WHERE id_pengajuan = ?");
            $st_cek->bind_param("i", $id_pengajuan);
            $st_cek->execute();
            $r_cek = $st_cek->get_result()->fetch_assoc();
            $st_cek->close();
            $id_bibit = $r_cek['id_bibit'] ?? null;
        }

        // Jika jml_bantuan kosong atau 0, hitung otomatis berdasarkan total luas lahan kelompok & rumus bibit
        if ($jml_bantuan <= 0 && !empty($id_group)) {
            $tot_luas_lahan = getTotalLuasLahanGroup($conn, $id_group);
            $rumus = getRumusBibit($conn, $id_bibit, $judul);
            if ($tot_luas_lahan > 0) {
                $jml_bantuan = (int) round($tot_luas_lahan * $rumus['rate']);
            }
        }

        // =====================
        // Ambil Nama File Lama
        // =====================
        $stmt_old = $conn->prepare("SELECT file_proposal FROM tbl_pengajuan WHERE id_pengajuan = ?");
        $stmt_old->bind_param("i", $id_pengajuan);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        
        if ($result_old->num_rows === 0) {
            throw new Exception("Data pengajuan tidak ditemukan.");
        }
        
        $row_old = $result_old->fetch_assoc();
        $file_lama = $row_old['file_proposal'];
        $stmt_old->close();

        // Default: gunakan nama file lama
        $nama_file = $file_lama;
        $upload_dir = '../../assets/uploads/';

        // =====================
        // Proses Upload File Baru (Jika ada)
        // =====================
        if (isset($_FILES['file-proposal']) && $_FILES['file-proposal']['error'] === UPLOAD_ERR_OK) {
            $tmp_name  = $_FILES['file-proposal']['tmp_name'];
            $file_ext  = pathinfo($_FILES['file-proposal']['name'], PATHINFO_EXTENSION);
            
            $nama_file_baru = time() . '_' . uniqid() . '.' . $file_ext; 
            
            if (!move_uploaded_file($tmp_name, $upload_dir . $nama_file_baru)) {
                throw new Exception("Gagal mengupload file proposal baru.");
            }

            $nama_file = $nama_file_baru;

            if (!empty($file_lama) && file_exists($upload_dir . $file_lama)) {
                unlink($upload_dir . $file_lama);
            }
        }

        // =====================
        // Update Pengajuan
        // =====================
       
        $q1 = $conn->prepare("UPDATE tbl_pengajuan SET id_group = ?, judul = ?, file_proposal = ?, jml_bantuan = ? WHERE id_pengajuan = ?");
        $q1->bind_param("isssi", $id_group, $judul, $nama_file, $jml_bantuan, $id_pengajuan);

        if (!$q1->execute()) {
            throw new Exception("Gagal update data pengajuan.");
        }

        // =====================
        // Insert Notifikasi (Opsional untuk Update)
        // =====================
        $message = "Pengajuan dengan Judul {$judul} dengan ID kelompok {$id_group} telah diperbarui";
        $type    = "Updated";

        $q2 = $conn->prepare("INSERT INTO tbl_notifikasi (message, type, id_ref) VALUES (?, ?, ?)");
        $q2->bind_param("ssi", $message, $type, $id_pengajuan);

        if (!$q2->execute()) {
            throw new Exception("Gagal insert notifikasi update.");
        }

        // =====================
        // COMMIT
        // =====================
        $conn->commit();

        echo json_encode([
            "status"  => "success",
            "message" => "Data pengajuan berhasil diperbarui."
        ]);

    } catch (Exception $e) {
        // =====================
        // ROLLBACK
        // =====================
        $conn->rollback();

        echo json_encode([
            "status"  => "error",
            "message" => $e->getMessage()
        ]);
    }
}


// === Fungsi Hapus Data ===
function deletePengajuan($conn)
{
    if (!isset($_POST['id_pengajuan'])) {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }
    $id_pengajuan = $_POST['id_pengajuan'];
    $sql = $conn->prepare("DELETE FROM tbl_pengajuan WHERE id_pengajuan = ?");
    $sql->bind_param("i", $id_pengajuan);

    if ($sql->execute()) {
        echo json_encode(["message" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus data: " . $conn->error]);
    }

    $sql->close();
}

$conn->close();
