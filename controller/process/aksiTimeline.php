<?php
session_start();
header("Content-Type: application/json");
include '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_timeline = isset($_POST['id_timeline']) ? htmlspecialchars($_POST['id_timeline']) : '';


switch ($action) {
    case 'read':
        readTimeLine($conn, $id_timeline);
        break;
    case 'update':
        updateTimeLine($conn);
        break;
    default:
        echo json_encode(["error" => "Aksi tidak valid"]);
}


function readTimeLine($conn){
     $query = "SELECT 
                id,
                step1_start,
                step2_start,
                step3_start,
                step4_start,
                step1_end,
                step2_end,
                step3_end,
                step4_end
              FROM tbl_pelaksanaan
              LIMIT 1";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode([
            "status" => false,
            "message" => mysqli_error($conn)
        ]);
        exit;
    }

    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo json_encode([
            "status" => false,
            "message" => "Data timeline tidak ditemukan"
        ]);
        exit;
    }

    echo json_encode($data);

    exit;
}


function updateTimeLine($conn){
    header('Content-Type: application/json');

    try {
        // =====================
        // Ambil data POST
        // =====================
        $id_timeline        = 1;

        $step1_start        = $_POST['step1_start'];
        $step1_end          = $_POST['step1_end'];

        $step2_start        = $_POST['step2_start'];
        $step2_end          = $_POST['step2_end'];

        $step3_start        = $_POST['step3_start'];
        $step3_end          = $_POST['step3_end'];

        $step4_start        = $_POST['step4_start'];
        $step4_end          = $_POST['step4_end'];
        
       

        // =====================
        // BEGIN TRANSACTION
        // =====================
        $conn->begin_transaction();

        // =====================
        // 1. UPDATE tbl_desa
        // =====================
        $q1 = $conn->prepare("UPDATE tbl_pelaksanaan SET 
            step1_start = ?,
            step2_start = ?,
            step3_start = ?,
            step4_start = ?,
            step1_end = ?,
            step2_end = ?,
            step3_end = ?,
            step4_end = ? 

            WHERE id = ?");
        $q1->bind_param(
            "ssssssssi",
            $step1_start,
            $step2_start,
            $step3_start,
            $step4_start,
            $step1_end,
            $step2_end,
            $step3_end,
            $step4_end,
            $id_timeline
        );

        if (!$q1->execute()) {
            throw new Exception("Gagal update");
        }

   

        // =====================
        // COMMIT
        // =====================
        $conn->commit();

        echo json_encode([
            "status"  => "success",
            "message" => "Data timeline berhasil diperbarui"
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

