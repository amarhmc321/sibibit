<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';
date_default_timezone_set('Asia/Makassar');

$action  = $_GET['action'] ??  $_SESSION['level'];


$now    = date("Y-m-d H:i:s");
$dari   = date("Y-m-d H:i:s", strtotime("-3 days", strtotime($now)));
$hingga = date("Y-m-d H:i:s", strtotime("+4 days", strtotime($now)));

switch ($action) {
    case 'read':
        isRead($conn);
        break;
    case 'delete':
        deleteNoitf($conn);
        break;
   
    case 1:
        adminNotifikasi($conn, $dari, $hingga);
        break;
    case 3:
        petaniNotifikasi($conn, $dari, $hingga);
        break;
    case 2:
        managerNotifikasi($conn, $dari, $hingga);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}



function isRead($conn){
$id   = $_POST['id'] ?? null; 
    $update = $conn->prepare("UPDATE tbl_notifikasi SET is_read = 1 WHERE id_notifikasi = ?");
    $update->bind_param("i", $id);
    $update->execute();
    echo json_encode([
            "success" => true,
            "message" => ""
        ]);
}


function deleteNoitf($conn){
    $id   = $_POST['id'] ?? null;
    $sql = $conn->prepare("DELETE FROM tbl_notifikasi WHERE id_notifikasi = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    echo json_encode([
            "status" => "success",
            "message" => ""
        ]);

    $sql->close();
}


function adminNotifikasi($conn, $dari, $hingga){
    $notifikasi = [];

    // ===============================
    // 1. Notif Update SK Jabatan
    // ===============================
    $q1 = $conn->prepare("
        SELECT *
        FROM tbl_notifikasi
        WHERE type = 'Pending' AND created_at BETWEEN ? AND ? AND is_read = 0 ");
    $q1->bind_param("ss", $dari, $hingga);
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
        WHERE n.type = 'Approved' AND pj.id_petani = ? AND created_at BETWEEN ? AND ? AND n.is_read = 0 ");
    $q1->bind_param("iss", $id_user, $dari, $hingga);
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

function managerNotifikasi($conn, $dari, $hingga){
    $notifikasi = [];

    // ===============================
    // 1. Notif Update SK Jabatan
    // ===============================
    $q1 = $conn->prepare("
        SELECT *
        FROM tbl_notifikasi
        WHERE type = 'Pending' AND created_at BETWEEN ? AND ? AND is_read = 0");
    $q1->bind_param("ss", $dari, $hingga);
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
