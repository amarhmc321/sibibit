<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_kecamatan = isset($_POST['id_kecamatan']) ? htmlspecialchars($_POST['id_kecamatan']) : '';
$id_bibit     = isset($_POST['id_bibit']) ? htmlspecialchars($_POST['id_bibit']) : '';
$id_group     = isset($_POST['id_group']) ? htmlspecialchars($_POST['id_group']) : '';


$dari = isset($_POST['dari']) ? $_POST['dari'] : date('Y-m-d', strtotime('-1 year'));
$hingga = isset($_POST['hingga']) ? $_POST['hingga'] : date('Y-m-d');


switch ($action) {
    case 'read':
        readLaporan($conn, $id_kecamatan, $id_bibit, $dari, $hingga);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}




// === Fungsi Ambil Data ===


function readLaporan($conn, $id_kecamatan, $id_bibit, $dari, $hingga)
{
    $q = "SELECT 
                pj.*,
                g.nama_kelompok,
                p.nama_petani AS ketua,
                SUM(l.luas_lahan) AS tot_luas_lahan,
                d.nama_desa,
                k.nama_kecamatan,
                b.nama_bibit
          FROM tbl_pengajuan pj
          LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
          LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
          LEFT JOIN tbl_lahan l  ON g.id_group = l.id_group
          LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
          LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
          LEFT JOIN tbl_bibit b ON b.id_bibit = pj.id_bibit
          WHERE DATE(pj.tgl_pengajuan) BETWEEN ? AND ?
            AND pj.s_pengajuan = 1";

    $params = [$dari, $hingga];
    $types = "ss";

    // Filter kecamatan jika dipilih
    if (!empty($id_kecamatan)) {
        $q .= " AND k.id_kecamatan = ?";
        $params[] = $id_kecamatan;
        $types .= "i";
    }

    // Filter bibit jika dipilih
    if (!empty($id_bibit)) {
        $q .= " AND pj.id_bibit = ?";
        $params[] = $id_bibit;
        $types .= "i";
    }

    $q .= " GROUP BY pj.id_pengajuan";

    $stmt = $conn->prepare($q);

    if (!$stmt) {
        echo json_encode([
            "error" => "Query gagal dipersiapkan",
            "message" => $conn->error
        ]);
        return;
    }

    $stmt->bind_param($types, ...$params);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            "error" => "Data tidak ditemukan"
        ]);
        $stmt->close();
        return;
    }

    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($data);

    $stmt->close();
}


function readLaporan2($conn, $id_kecamatan, $id_bibit, $dari, $hingga){

    if (!$id_kecamatan) {
       $q = "SELECT pj.*,
            g.nama_kelompok,
            p.nama_petani as ketua,
            SUM(l.luas_lahan) AS tot_luas_lahan,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p  ON g.id_leader = p.id_petani
       LEFT JOIN tbl_lahan  l ON g.id_group = l.id_group
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       WHERE DATE(pj.tgl_pengajuan) BETWEEN ? AND ? AND pj.s_pengajuan = 1
       GROUP BY pj.id_pengajuan
       ";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("ss", $dari, $hingga);
    }else{
        $q = "SELECT pj.*,
           g.nama_kelompok,
            p.nama_petani as ketua,
            SUM(l.luas_lahan) AS tot_luas_lahan,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_lahan  l ON g.id_group = l.id_group
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       WHERE DATE(pj.tgl_pengajuan) BETWEEN ? AND ? AND k.id_kecamatan = ?  AND pj.s_pengajuan = 1
       GROUP BY pj.id_pengajuan
       ";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("iss", $id_kecamatan, $dari, $hingga);
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



function detailLaporan($conn){
    $id_pengajuan = $_POST['id_pengajuan'];

    if (!isset($id_pengajuan) || $id_pengajuan === "") {
        echo json_encode(["error" => "ID tidak diberikan"]);
        return;
    }

  

   $q = "SELECT pj.*,
            g.nama_kelompok,
            p.nama_petani as ketua,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
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

    $row = $result->fetch_assoc(); // Ambil satu baris langsung
    echo json_encode($row); // Return sebagai object tunggal

    $stmt->close();
}



// === Fungsi Tambah Data ===

function approveLaporan($conn) {
    // Mulai transaksi database
    $conn->begin_transaction();

    try {
        // =====================
        // Ambil data POST & Session
        // =====================
       
        $id_pengajuan  = $_POST['id_pengajuan'] ?? null;
        $s_pengajuan   = $_POST['s_pengajuan'];
        $catatan       = $_POST['catatan'];
        
        $q1 = $conn->prepare("UPDATE tbl_pengajuan SET s_pengajuan = ?, catatan = ? WHERE id_pengajuan = ?");
        $q1->bind_param("isi", $s_pengajuan, $catatan, $id_pengajuan);

        if (!$q1->execute()) {
            throw new Exception("Gagal update data pengajuan.");
        }

        // =====================
        // Insert Notifikasi (Opsional untuk Update)
        // =====================




        $message = "Pengajuan Andah Dengan ID $id_pengajuan telah ditinjau";
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
            "message" => "Data pengajuan berhasil ditambahkan" // Diubah dari "" menjadi "Pengajuan"
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


function createLaporan($conn) {
    // Mulai transaksi database
    $conn->begin_transaction();

    try {
        // =====================
        // Ambil data POST & Session
        // =====================
        $id_petani = $_SESSION['id_user'] ?? null; 
        $id_group  = $_POST['id_group'] ?? null;
        $judul     = $_POST['judul'];
        
        // =====================
        // Proses Upload File
        // =====================
        $nama_file = null;
        // Sesuai HTML sebelumnya, name input file adalah 'file-proposal'
        if (isset($_FILES['file-proposal']) && $_FILES['file-proposal']['error'] === UPLOAD_ERR_OK) {
            $tmp_name  = $_FILES['file-proposal']['tmp_name'];
            $file_ext  = pathinfo($_FILES['file-proposal']['name'], PATHINFO_EXTENSION);
            
            // Buat nama file unik untuk mencegah bentrok
            $nama_file = time() . '_' . uniqid() . '.' . $file_ext; 
            
            // Sesuaikan direktori ini dengan struktur folder Anda
            $upload_dir = '../../assets/uploads/'; 
            
            if (!move_uploaded_file($tmp_name, $upload_dir . $nama_file)) {
                throw new Exception("Gagal mengupload file proposal.");
            }
        }

        // =====================
        // 1. Insert Pengajuan
        // =====================
        $q1 = $conn->prepare("INSERT INTO tbl_pengajuan (id_group, id_petani, judul, file_proposal) VALUES (?, ?, ?, ?)");
        $q1->bind_param("iiss", $id_group, $id_petani, $judul, $nama_file);

        if (!$q1->execute()) {
            throw new Exception("Gagal insert tbl_pengajuan");
        }
        
        // Dapatkan ID yang baru saja masuk (Posisikan di LUAR blok if)
        $id_ref = $conn->insert_id; 

        // =====================
        // 2. Insert Notifikasi
        // =====================
        // Tambahkan variabel $id_ref agar pesannya menjadi dinamis
        $message = "Pengajuan Baru dengan Judul <b>{$judul}</b> dengan ID kelomok {$id_group}";
        $type    = "Pending";

        // Catatan: Pastikan nama kolom di database benar 'message' bukan 'massage'
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
            "message" => "Data pengajuan berhasil ditambahkan" // Diubah dari "" menjadi "Pengajuan"
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
function updateLaporan($conn) {
    // Mulai transaksi database
    $conn->begin_transaction();

    try {
        // =====================
        // Ambil data POST
        // =====================
        $id_pengajuan = $_POST['id_pengajuan'] ?? null;
        $id_group     = $_POST['id_group'] ?? null; // Sesuaikan dengan nama input di form Anda
        $judul     = $_POST['judul'] ?? null;
        
        if (empty($id_pengajuan)) {
            throw new Exception("ID Pengajuan tidak valid.");
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
        $upload_dir = '../../assets/uploads/'; // Sesuaikan dengan direktori Anda

        // =====================
        // Proses Upload File Baru (Jika ada)
        // =====================
        if (isset($_FILES['file-proposal']) && $_FILES['file-proposal']['error'] === UPLOAD_ERR_OK) {
            $tmp_name  = $_FILES['file-proposal']['tmp_name'];
            $file_ext  = pathinfo($_FILES['file-proposal']['name'], PATHINFO_EXTENSION);
            
            // Buat nama file unik baru
            $nama_file_baru = time() . '_' . uniqid() . '.' . $file_ext; 
            
            if (!move_uploaded_file($tmp_name, $upload_dir . $nama_file_baru)) {
                throw new Exception("Gagal mengupload file proposal baru.");
            }

            // Tetapkan nama file baru untuk disimpan ke database
            $nama_file = $nama_file_baru;

            // Hapus file lama dari server untuk menghemat ruang (opsional tapi disarankan)
            if (!empty($file_lama) && file_exists($upload_dir . $file_lama)) {
                unlink($upload_dir . $file_lama);
            }
        }

        // =====================
        // Update Pengajuan
        // =====================
       
        $q1 = $conn->prepare("UPDATE tbl_pengajuan SET id_group = ?, judul = ?, file_proposal = ? WHERE id_pengajuan = ?");
        $q1->bind_param("issi", $id_group, $judul, $nama_file, $id_pengajuan);

        if (!$q1->execute()) {
            throw new Exception("Gagal update data pengajuan.");
        }

        // =====================
        // Insert Notifikasi (Opsional untuk Update)
        // =====================
        $message = `Pengajuan dengan Judul {$judul} dengan ID kelomok {$id_group} telah di perbarui`;
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
function deleteLaporan($conn)
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
