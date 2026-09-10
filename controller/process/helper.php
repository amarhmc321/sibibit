<?php
function buildDateTimeFromDate($dateInput)
{
    if (!$dateInput) return null;
    return $dateInput . ' ' . date('H:i:s');
}


function isInShiftRange($currentTime, $shift)
{
  if ($shift == 1) { // siang
    return ($currentTime >= '06:00' && $currentTime <= '17:30');
  } else if ($shift == 2) { // malam
    // Shift malam dari 17:45 sampai 00:15 (esok)
    return ($currentTime >= '17:45' || $currentTime <= '00:15');
  }
  return false;
}

function saveAktivitas($conn, $sumber_tabel, $id_sumber, $deskripsi): bool {
    $id_aktor = $_SESSION['id_karyawan'] ?? null;
    $aktor = getActor();
    $sql = $conn->prepare("
        INSERT INTO tbl_aktivitas (id_aktor, aktor, sumber_tabel, id_sumber, tgl_aktivitas, deskripsi) VALUES (?, ?, ?, ?, NOW(), ?)
    ");
    if (!$sql) {
        throw new Exception("Gagal prepare aktivitas: " . $conn->error);
    }

    $sql->bind_param("issis", $id_aktor, $aktor, $sumber_tabel, $id_sumber, $deskripsi);
    $ok = $sql->execute();
    if (!$ok) {
        throw new Exception("Gagal simpan aktivitas: " . $sql->error);
    }

    $sql->close();
    return true;
}

function getActor(): string
{
    $level        = $_SESSION['level']        ?? null;
    $namaKaryawan = $_SESSION['nama_karyawan'] ?? '';
    $namaJabatan  = $_SESSION['nama_jabatan']  ?? '';
    $namaLokasi   = $_SESSION['nama_lokasi']   ?? '';

    if (in_array($level, [1, 2, 3, 4])) {
        // Gabungkan jabatan dan lokasi
        return trim($namaJabatan . ' - ' . $namaLokasi);
    }

    return $namaKaryawan;
}

function getPeriode2526(): array
{
    $todayDay = date('d');

    if ($todayDay < 26) {
        // periode berjalan: 25 bulan lalu - 26 bulan ini
        $startDate = date('Y-m-26', strtotime('-1 month'));
        $endDate   = date('Y-m-25');
    } else {
        // periode berjalan: 25 bulan ini - 26 bulan depan
        $startDate = date('Y-m-26');
        $endDate   = date('Y-m-25', strtotime('+1 month'));
    }

    return [$startDate, $endDate];
}

function cek_role($level){
    $role= '';
    switch ($level) {
    case 1:
        $role = 'Admin';
        break;
    case 2:
       $role  = 'Keuan';
        break;
    case 3:
         $role = 'HRD';
        break;
    case 4:
        $role = 'Supervisior';
        break;
    default:
        $role = 'Karyawan';
    }
 return $role;
}

function cek_level($value){
    $level= '';
    switch ($value) {
    case 1:
        $level  = 1;
        break;
    case 2:
       $level   = 2;
        break;
    case 3:
         $level = 3;
        break;
    case 4:
        $level  = 4;
        break;
    default:
        $level  = 5;
    }
 return $level;
}

function handleUploadFoto($files, $fieldName, $nama, $oldFile = "default.png", $target_dir = "../../img/karyawan/")
{
    // pastikan folder ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $newFile = $oldFile;

    if (isset($files[$fieldName]) && $files[$fieldName]['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($files[$fieldName]['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        if (!in_array($ext, $allowed)) {
            throw new Exception("Format file tidak valid ($fieldName)");
        }

        // hapus file lama kalau bukan default
        if ($oldFile !== 'default.png' && file_exists($target_dir . $oldFile)) {
            @unlink($target_dir . $oldFile);
        }

        // bikin nama file aman
        $slug = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nama);
        $newFile = $slug . "-" . $fieldName . "-" . time() . "." . $ext;
        $target_file = $target_dir . $newFile;

        if (!move_uploaded_file($files[$fieldName]['tmp_name'], $target_file)) {
            throw new Exception("Gagal upload $fieldName");
        }
    }

    return $newFile;
}

function handleUploads($files, $fieldName, $nama, $oldFile = "default.png", $target_dir = "../../assets/uploads/")
{
    // pastikan folder ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $newFile = $oldFile;

    if (isset($files[$fieldName]) && $files[$fieldName]['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($files[$fieldName]['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        if (!in_array($ext, $allowed)) {
            throw new Exception("Format file tidak valid ($fieldName)");
        }

        // hapus file lama kalau bukan default
        if ($oldFile !== 'default.png' && file_exists($target_dir . $oldFile)) {
            @unlink($target_dir . $oldFile);
        }

        // bikin nama file aman
        $slug = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nama);
        $newFile = $slug . "-" . $fieldName . "-" . time() . "." . $ext;
        $target_file = $target_dir . $newFile;

        if (!move_uploaded_file($files[$fieldName]['tmp_name'], $target_file)) {
            throw new Exception("Gagal upload $fieldName");
        }
    }

    return $newFile;
}


function handleUploadTtd($files, $nama, $oldTtd = "default.png", $target_dir = "../../img/ttd/")
{
    $ttd = $oldTtd;

    if (isset($files['ttd_karyawan']) && $files['ttd_karyawan']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($files['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        if (!in_array($ext, $allowed)) {
            throw new Exception("Format file tidak valid");
        }

        if ($oldTtd !== 'default.png' && file_exists($target_dir . $oldTtd)) {
            @unlink($target_dir . $oldTtd);
        }

        $slug = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nama);
        $ttd = $slug . "-" . time() . "." . $ext;
        $target_file = $target_dir . $ttd;

        if (!move_uploaded_file($files['ttd_karyawan']['tmp_name'], $target_file)) {
            throw new Exception("Gagal upload ttd karyawan");
        }
    }
    return $ttd;
}

function fetchWithFilter($conn, $baseSql, $filterMap = [], $sessionFilter = [], $orderBy = "")
{
    $sql    = $baseSql . " WHERE 1=1 ";
    $params = [];
    $types  = '';

    // --- filter wajib dari session ---
    foreach ($sessionFilter as $rule) {
        [$condition, $column, $type, $value] = $rule;
        if ($condition && $value !== null) {
            $sql      .= " AND $column = ?";
            $types    .= $type;
            $params[]  = $value;
        }
    }

    // --- filter tambahan dari POST ---
    foreach ($filterMap as $postKey => $cfg) {
        $type     = $cfg['type'];
        $column   = $cfg['column'];
        $operator = strtoupper($cfg['operator']);

        if ($operator === 'BETWEEN') {
            $start = $_POST[$postKey . '_start'] ?? null;
            $end   = $_POST[$postKey . '_end'] ?? null;
            if (!empty($start) && !empty($end)) {
                // kalau cuma YYYY-MM-DD, tambahkan waktu full
                if (strlen($start) === 10) $start .= " 00:00:00";
                if (strlen($end) === 10)   $end   .= " 23:59:59";

                $sql .= " AND $column BETWEEN ? AND ?";
                $types   .= $type . $type;
                $params[] = $start;
                $params[] = $end;
            }
        } else {
            $value = $_POST[$postKey] ?? null;
            if ($value !== null && $value !== '') {
                $sql .= " AND $column $operator ?";
                $types   .= $type;
                $params[] = $value;
            }
        }
    }

    // --- order by ---
    if ($orderBy) {
        $sql .= " ORDER BY $orderBy";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare gagal: " . $conn->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        throw new Exception("Eksekusi gagal: " . $stmt->error);
    }

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}



function updateStatusTemplate($conn, $config)
{
    // Validasi input
    $id_entity = $_POST[$config['id_field']] ?? '';
    $status = $_POST['status'] ?? '';
    $id_user = $_SESSION[$config['session_field']] ?? null;
    $waktu_log = date("Y-m-d H:i:s");

    if ($id_entity === '' || $status === '' || !$id_user) {
        echo json_encode(['error' => 'Data tidak lengkap atau sesi login hilang']);
        return;
    }

    $conn->begin_transaction();
    try {
        // 1. Ambil data utama
        $mainData = getMainData($conn, $config, $id_entity);
        if (!$mainData) throw new Exception("Data utama tidak ditemukan");

        // 2. Update status entitas utama
        updateMainStatus($conn, $config, $id_entity, $status);

        // 3. Proses tindakan tambahan berdasarkan status
        if ($status == 1) {
            processApproval($conn, $config, $mainData, $id_user, $waktu_log);
        } elseif ($status == 2) {
            processRejection($conn, $config, $mainData, $id_user, $waktu_log);
        }

        // 4. Commit transaksi
        $conn->commit();
        echo json_encode([
            'success' => true,
            'msg' => $config['success_message'][$status]
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['error' => 'Rollback: ' . $e->getMessage()]);
    }
}

// Helper functions
function getMainData($conn, $config, $id_entity)
{
    $stmt = $conn->prepare($config['queries']['get_main_data']);
    $stmt->bind_param($config['bind_params']['get_main_data'], $id_entity);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}

function updateMainStatus($conn, $config, $id_entity, $status)
{
    $stmt = $conn->prepare($config['queries']['update_status']);
    $stmt->bind_param($config['bind_params']['update_status'], $status, $id_entity);
    if (!$stmt->execute()) throw new Exception("Gagal update status");
    $stmt->close();
}

function processApproval($conn, $config, $mainData, $id_user, $waktu_log)
{
    // Eksekusi sesuai konfigurasi
    if (isset($config['on_approve'])) {
        $config['on_approve']($conn, $mainData, $id_user, $waktu_log);
    }
}

function processRejection($conn, $config, $mainData, $id_user, $waktu_log)
{
    // Eksekusi sesuai konfigurasi
    if (isset($config['on_reject'])) {
        $config['on_reject']($conn, $mainData, $id_user, $waktu_log);
    }
}
