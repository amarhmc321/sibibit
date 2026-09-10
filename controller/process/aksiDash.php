<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';
require_once 'helper.php';
date_default_timezone_set('Asia/Makassar');

$action  = $_SESSION['level'] ?? null;

$id_kecamatan        = isset($_POST['id_kecamatan']) ? htmlspecialchars($_POST['id_kecamatan']) : '';

$dari = isset($_POST['dari']) ? $_POST['dari'] : date('Y-m-d', strtotime('-1 month'));
$hingga = isset($_POST['hingga']) ? $_POST['hingga'] : date('Y-m-d');



switch ($action) {
    case 1:
        adminDash($conn, $dari, $hingga, $id_kecamatan);
        break;
    case 2:
        kadisDash($conn, $dari, $hingga, $id_kecamatan);
        break;
    case 3:
        petaniDash($conn, $dari, $hingga);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}





function petaniDash($conn, $dari, $hingga){
    $id_petani = $_SESSION['id_user'];
    $id_group  = $_POST['id_group'];
    

    $Qpj = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE id_group = ? ");
    $Qpj->bind_param("i", $id_group);
    $Qpj->execute();
    $resPj = $Qpj->get_result()->fetch_assoc();
    $totalPengajuan = $resPj['total'] ?? 0;
    $Qpj->close();

    $Qpjp = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE id_group = ? AND s_pengajuan = 0");
    $Qpjp->bind_param("i", $id_group);
    $Qpjp->execute();
    $resPjp = $Qpjp->get_result()->fetch_assoc();
    $totalPjPending = $resPjp['total'] ?? 0;
    $Qpjp->close();

    $Qpja = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE id_group = ? AND s_pengajuan = 1");
    $Qpja->bind_param("i", $id_group);
    $Qpja->execute();
    $resPjp = $Qpja->get_result()->fetch_assoc();
    $totalPjAcc = $resPjp['total'] ?? 0;
    $Qpja->close();

    $Qpjt = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE id_group = ? AND s_pengajuan = 2");
    $Qpjt->bind_param("i", $id_group);
    $Qpjt->execute();
    $resPjp = $Qpjt->get_result()->fetch_assoc();
    $totalPjTolak = $resPjp['total'] ?? 0;
    $Qpjt->close();

   
    
    


    // 10. Transaksi Terbaru
    $recentTransactions = [];
   
        $q10 = $conn->prepare("
            SELECT pj.*,
           g.nama_kelompok,
            p.nama_petani as ketua,
            p2.nama_petani as oleh,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_petani p2 ON pj.id_petani = p2.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
         WHERE pj.id_group = ? AND pj.tgl_pengajuan BETWEEN ? AND ?
            ORDER BY pj.tgl_pengajuan DESC LIMIT 7
        ");
        $q10->bind_param("iss", $id_group, $dari, $hingga);
    $q10->execute();
    $result10 = $q10->get_result();
    while ($row = $result10->fetch_assoc()) {
        $recentTransactions[] = $row;
    }
    $q10->close();

  

    echo json_encode([
        "success" => true,
        "tot_pengajuan" => $totalPengajuan,
        "tot_pending"   => $totalPjPending,
        "tot_acc"   => $totalPjAcc,
        "tot_tolak"   => $totalPjTolak,
    
       
        "recent_transactions" => $recentTransactions
    ]);
   
}


function adminDash($conn, $dari, $hingga){
    $totalKelompok = 0;
    $q1 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_groups");
    $q1->execute();
    $res1 = $q1->get_result()->fetch_assoc();
    $totalKelompok = $res1['total'] ?? 0;
    $q1->close();

    // 2. Unit Baru (dalam rentang waktu)
    $totalKelompokBaru = 0;
    $q2 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_groups WHERE DATE(created_at) BETWEEN ? AND ?");
    $q2->bind_param("ss", $dari, $hingga);
    $q2->execute();
    $res2 = $q2->get_result()->fetch_assoc();
    $totalKelompokBaru = $res2['total'] ?? 0;
    $q2->close();

    // 3. Total data
    $totalPetani = 0;
    if (empty($id_kecamatan)) {
        $q3 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_petani");
        $q3->execute();
    } else {
        $q3 = $conn->prepare("
            SELECT COUNT(*) AS total 
            FROM tbl_petani p
            LEFT JOIN tbl_desa d ON p.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_desa = k.id_desa
          WHERE k.id_kecamatan = ?");
        $q3->bind_param("i", $id_kecamatan);
        $q3->execute();
    }
    $res3 = $q3->get_result()->fetch_assoc();
    $totalPetani = $res3['total'] ?? 0;
    $q3->close();

    // 4. data Baru (dalam rentang waktu)
    $totalPetaniBaru = 0;
    if (empty($id_kecamatan)) {
        $q4 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_petani WHERE DATE(created_at) BETWEEN ? AND ?");
        $q4->bind_param("ss", $dari, $hingga);
    } else {
        $q4 = $conn->prepare("SELECT COUNT(*) AS total 
            FROM tbl_petani p 
            LEFT JOIN tbl_desa d ON p.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_desa = k.id_desa
            WHERE k.id_kecamatan = ? AND DATE(p.created_at) BETWEEN ? AND ?");
        $q4->bind_param("iss", $id_kecamatan, $dari, $hingga);
    }
    $q4->execute();
    $res4 = $q4->get_result()->fetch_assoc();
    $totalPetaniBaru = $res4['total'] ?? 0;
    $q4->close();

    $totalLahan = 0;
    if (empty($id_kecamatan)) {
        $q5 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_lahan");
        $q5->execute();
    } else {
        $q5 = $conn->prepare("
            SELECT COUNT(*) AS total 
            FROM tbl_lahan l
            LEFT JOIN tbl_desa d ON l.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_desa = k.id_desa
          WHERE k.id_kecamatan = ?");
        $q5->bind_param("i", $id_kecamatan);
        $q5->execute();
    }
    $res5 = $q5->get_result()->fetch_assoc();
    $totalLahan = $res5['total'] ?? 0;
    $q5->close();

    // 4. data Baru (dalam rentang waktu)
    $totalLahanBaru = 0;
    if (empty($id_kecamatan)) {
        $q6 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_lahan WHERE DATE(created_at) BETWEEN ? AND ?");
        $q6->bind_param("ss", $dari, $hingga);
    } else {
        $q6 = $conn->prepare("SELECT COUNT(*) AS total 
            FROM tbl_lahan l 
            LEFT JOIN tbl_desa d ON l.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_desa = k.id_desa
            WHERE k.id_kecamatan = ? AND DATE(l.created_at) BETWEEN ? AND ?");
        $q6->bind_param("iss", $id_kecamatan, $dari, $hingga);
    }
    $q6->execute();
    $res6 = $q6->get_result()->fetch_assoc();
    $totalLahanBaru = $res6['total'] ?? 0;
    $q6->close();

    $totalPengajuan = 0;
    if (empty($id_kecamatan)) {
        $q7 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_pengajuan");
        $q7->execute();
    } else {
        $q7 = $conn->prepare("
            SELECT COUNT(*) AS total 
            FROM tbl_pengajuan pj
            LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
            LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
            WHERE k.id_kecamatan = ?");
        $q7->bind_param("i", $id_kecamatan);
        $q7->execute();
    }
    $res7 = $q7->get_result()->fetch_assoc();
    $totalPengajuan = $res7['total'] ?? 0;
    $q7->close();

    // 4. data Baru (dalam rentang waktu)
    $totalPengajuanBaru = 0;
    if (empty($id_kecamatan)) {
        $q8 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE DATE(tgl_pengajuan) BETWEEN ? AND ?");
        $q8->bind_param("ss", $dari, $hingga);
    } else {
        $q8 = $conn->prepare("SELECT COUNT(*) AS total 
            FROM tbl_lahan pj 
            LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
            LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
            WHERE k.id_kecamatan = ? AND DATE(pj.tgl_pengajuan) BETWEEN ? AND ?");
        $q8->bind_param("iss", $id_kecamatan, $dari, $hingga);
    }
    $q8->execute();
    $res8 = $q8->get_result()->fetch_assoc();
    $totalPengajuanBaru = $res8['total'] ?? 0;
    $q8->close();

    


    // 10. Transaksi Terbaru
    $recentTransactions = [];
    if (empty($id_kecamatan)) {
        $q10 = $conn->prepare("
            SELECT pj.*,
            g.nama_kelompok,
            p.nama_petani as ketua,
            p2.nama_petani as oleh,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p  ON g.id_leader = p.id_petani
       LEFT JOIN tbl_petani p2 ON pj.id_petani = p2.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       WHERE pj.tgl_pengajuan BETWEEN ? AND ?
            ORDER BY pj.tgl_pengajuan DESC LIMIT 7
        ");
        $q10->bind_param("ss", $dari, $hingga);
    } else {
        $q10 = $conn->prepare("
            SELECT pj.*,
           g.nama_kelompok,
            p.nama_petani as ketua,
            p2.nama_petani as oleh,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_petani p2 ON pj.id_petani = p2.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
         WHERE k.id_kecamatan = ? AND pj.tgl_pengajuan BETWEEN ? AND ?
            ORDER BY pj.tgl_pengajuan DESC LIMIT 7
        ");
        $q10->bind_param("iss", $id_kecamatan, $dari, $hingga);
    }
    $q10->execute();
    $result10 = $q10->get_result();
    while ($row = $result10->fetch_assoc()) {
        $recentTransactions[] = $row;
    }
    $q10->close();

  

    echo json_encode([
        "success" => true,
        "tot_kelompok" => $totalKelompok,
        "tot_kelompok_baru" => $totalKelompokBaru,
        "tot_petani" => $totalPetani,
        "tot_petani_baru" => $totalPetaniBaru,
        "tot_lahan" => $totalLahan,
        "tot_lahan_baru" => $totalLahanBaru,
        "tot_pengajuan" => $totalPengajuan,
        "tot_pengajuan_baru" => $totalPengajuanBaru, 
        "recent_transactions" => $recentTransactions
    ]);
   
}



function kadisDash($conn, $dari, $hingga){
    $totalKelompok = 0;
    $q1 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_groups");
    $q1->execute();
    $res1 = $q1->get_result()->fetch_assoc();
    $totalKelompok = $res1['total'] ?? 0;
    $q1->close();

    // 2. Unit Baru (dalam rentang waktu)
    $totalKelompokBaru = 0;
    $q2 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_groups WHERE DATE(created_at) BETWEEN ? AND ?");
    $q2->bind_param("ss", $dari, $hingga);
    $q2->execute();
    $res2 = $q2->get_result()->fetch_assoc();
    $totalKelompokBaru = $res2['total'] ?? 0;
    $q2->close();

    // 3. Total data
    $totalPetani = 0;
    if (empty($id_kecamatan)) {
        $q3 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_petani");
        $q3->execute();
    } else {
        $q3 = $conn->prepare("
            SELECT COUNT(*) AS total 
            FROM tbl_petani p
            LEFT JOIN tbl_desa d ON p.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_desa = k.id_desa
          WHERE k.id_kecamatan = ?");
        $q3->bind_param("i", $id_kecamatan);
        $q3->execute();
    }
    $res3 = $q3->get_result()->fetch_assoc();
    $totalPetani = $res3['total'] ?? 0;
    $q3->close();

    // 4. data Baru (dalam rentang waktu)
    $totalPetaniBaru = 0;
    if (empty($id_kecamatan)) {
        $q4 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_petani WHERE DATE(created_at) BETWEEN ? AND ?");
        $q4->bind_param("ss", $dari, $hingga);
    } else {
        $q4 = $conn->prepare("SELECT COUNT(*) AS total 
            FROM tbl_petani p 
            LEFT JOIN tbl_desa d ON p.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_desa = k.id_desa
            WHERE k.id_kecamatan = ? AND DATE(p.created_at) BETWEEN ? AND ?");
        $q4->bind_param("iss", $id_kecamatan, $dari, $hingga);
    }
    $q4->execute();
    $res4 = $q4->get_result()->fetch_assoc();
    $totalPetaniBaru = $res4['total'] ?? 0;
    $q4->close();

    $totalLahan = 0;
    if (empty($id_kecamatan)) {
        $q5 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_lahan");
        $q5->execute();
    } else {
        $q5 = $conn->prepare("
            SELECT COUNT(*) AS total 
            FROM tbl_lahan l
            LEFT JOIN tbl_desa d ON l.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_desa = k.id_desa
          WHERE k.id_kecamatan = ?");
        $q5->bind_param("i", $id_kecamatan);
        $q5->execute();
    }
    $res5 = $q5->get_result()->fetch_assoc();
    $totalLahan = $res5['total'] ?? 0;
    $q5->close();

    // 4. data Baru (dalam rentang waktu)
    $totalLahanBaru = 0;
    if (empty($id_kecamatan)) {
        $q6 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_lahan WHERE DATE(created_at) BETWEEN ? AND ?");
        $q6->bind_param("ss", $dari, $hingga);
    } else {
        $q6 = $conn->prepare("SELECT COUNT(*) AS total 
            FROM tbl_lahan l 
            LEFT JOIN tbl_desa d ON l.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_desa = k.id_desa
            WHERE k.id_kecamatan = ? AND DATE(l.created_at) BETWEEN ? AND ?");
        $q6->bind_param("iss", $id_kecamatan, $dari, $hingga);
    }
    $q6->execute();
    $res6 = $q6->get_result()->fetch_assoc();
    $totalLahanBaru = $res6['total'] ?? 0;
    $q6->close();

    $totalPengajuan = 0;
    if (empty($id_kecamatan)) {
        $q7 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_pengajuan");
        $q7->execute();
    } else {
        $q7 = $conn->prepare("
            SELECT COUNT(*) AS total 
            FROM tbl_pengajuan pj
            LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
            LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
            WHERE k.id_kecamatan = ?");
        $q7->bind_param("i", $id_kecamatan);
        $q7->execute();
    }
    $res7 = $q7->get_result()->fetch_assoc();
    $totalPengajuan = $res7['total'] ?? 0;
    $q7->close();

    // 4. data Baru (dalam rentang waktu)
    $totalPengajuanBaru = 0;
    if (empty($id_kecamatan)) {
        $q8 = $conn->prepare("SELECT COUNT(*) AS total FROM tbl_pengajuan WHERE DATE(tgl_pengajuan) BETWEEN ? AND ?");
        $q8->bind_param("ss", $dari, $hingga);
    } else {
        $q8 = $conn->prepare("SELECT COUNT(*) AS total 
            FROM tbl_lahan pj 
            LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
            LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
            LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
            WHERE k.id_kecamatan = ? AND DATE(pj.tgl_pengajuan) BETWEEN ? AND ?");
        $q8->bind_param("iss", $id_kecamatan, $dari, $hingga);
    }
    $q8->execute();
    $res8 = $q8->get_result()->fetch_assoc();
    $totalPengajuanBaru = $res8['total'] ?? 0;
    $q8->close();

    


    // 10. Transaksi Terbaru
    $recentTransactions = [];
    if (empty($id_kecamatan)) {
        $q10 = $conn->prepare("
            SELECT pj.*,
            g.nama_kelompok,
            p.nama_petani as ketua,
            p2.nama_petani as oleh,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p  ON g.id_leader = p.id_petani
       LEFT JOIN tbl_petani p2 ON pj.id_petani = p2.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       WHERE pj.tgl_pengajuan BETWEEN ? AND ?
            ORDER BY pj.tgl_pengajuan DESC LIMIT 7
        ");
        $q10->bind_param("ss", $dari, $hingga);
    } else {
        $q10 = $conn->prepare("
            SELECT pj.*,
           g.nama_kelompok,
            p.nama_petani as ketua,
            p2.nama_petani as oleh,
            d.nama_desa,
            k.nama_kecamatan
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_petani p2 ON pj.id_petani = p2.id_petani
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
         WHERE k.id_kecamatan = ? AND pj.tgl_pengajuan BETWEEN ? AND ?
            ORDER BY pj.tgl_pengajuan DESC LIMIT 7
        ");
        $q10->bind_param("iss", $id_kecamatan, $dari, $hingga);
    }
    $q10->execute();
    $result10 = $q10->get_result();
    while ($row = $result10->fetch_assoc()) {
        $recentTransactions[] = $row;
    }
    $q10->close();

  

    echo json_encode([
        "success" => true,
        "tot_kelompok" => $totalKelompok,
        "tot_kelompok_baru" => $totalKelompokBaru,
        "tot_petani" => $totalPetani,
        "tot_petani_baru" => $totalPetaniBaru,
        "tot_lahan" => $totalLahan,
        "tot_lahan_baru" => $totalLahanBaru,
        "tot_pengajuan" => $totalPengajuan,
        "tot_pengajuan_baru" => $totalPengajuanBaru, 
        "recent_transactions" => $recentTransactions
    ]);
   
}








$conn->close();
