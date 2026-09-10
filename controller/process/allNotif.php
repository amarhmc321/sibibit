<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';
date_default_timezone_set('Asia/Makassar');

$action  = $_SESSION['level'];


$now    = date("Y-m-d H:i:s");
$dari   = date("Y-m-d H:i:s", strtotime("-3 days", strtotime($now)));
$hingga = date("Y-m-d H:i:s", strtotime("+4 days", strtotime($now)));

switch ($action) {
    case 1:
        adminNotifikasi($conn, $dari, $hingga);
        break;
    case 3:
        petaniNotifikasi($conn, $dari, $hingga);
        break;
    case 2:
        adminNotifikasi($conn, $dari, $hingga);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}





function adminNotifikasi($conn, $dari, $hingga){
    $notifikasi = [];

    // ===============================
    // 1. Notif Update SK Jabatan
    // ===============================
    $q1 = $conn->prepare("
        SELECT *
        FROM tbl_notifikasi
        WHERE type = 'Pending'");
    $q1->execute();
    $notifikasi = array_merge($notifikasi, $q1->get_result()->fetch_all(MYSQLI_ASSOC));
    $q1->close();

   
    // ===============================
    // Urutkan berdasarkan tgl_aktivitas DESC
    // ===============================
    usort($notifikasi, function ($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    echo json_encode([
        "total_notif" => count($notifikasi),
        "data" => $notifikasi
    ]);
}



function petaniNotifikasi($conn, $dari, $hingga){
    $id_user  = $_SESSION['id_user'] ?? null;
    $notifikasi = [];

    // ===============================
    // 1. Notif Update SK Jabatan
    // ===============================
    $q1 = $conn->prepare("
        SELECT n.*
        FROM tbl_notifikasi n
        LEFT JOIN tbl_pengajuan pj ON n.id_ref = pj.id_pengajuan
        WHERE n.type = 'Approved' AND pj.id_petani = ?  ");
    $q1->bind_param("i", $id_user);
    $q1->execute();
    $notifikasi = array_merge($notifikasi, $q1->get_result()->fetch_all(MYSQLI_ASSOC));
    $q1->close();

   
    // ===============================
    // Urutkan berdasarkan tgl_aktivitas DESC
    // ===============================
    usort($notifikasi, function ($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    echo json_encode([
        "total_notif" => count($notifikasi),
        "data" => $notifikasi
    ]);
}








$conn->close();
