<?php
// include '../../controller/process/config/database.php';
include '../../controller/config/database.php';

$id_kecamatan  = $_GET['id_kecamatan'];
$id_bibit      = $_GET['id_bibit'];
$dari          = $_GET['dari'];
$hingga        = $_GET['hingga'];
$date          = $_GET['date'];

// Format tanggal Indonesia untuk judul (opsional, jika format $_GET['date'] adalah YYYY-MM-DD)
function format_tgl_indo($tanggal) {
    $bulan = array(1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
    $split = explode('-', $tanggal);
    if (count($split) === 3) {
        return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
    }
    return $tanggal;
}

function splitTahun($tgl1, $tgl2) {
    $split1 = explode('-', $tgl1);
    $split2 = explode('-', $tgl2);
    if (count($split1) === 3) {
        return "{$split1[0]}-{$split2[0]}" ;
    }
    return $tanggal;
}

$tgl_laporan = format_tgl_indo($date);
$tahun = splitTahun($dari, $hingga);

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Permohonan_Bibit_($date).xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Export Data Ke Excel</title>
    <style type="text/css">
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .title {
            font-size: 16pt;
            font-weight: bold;
            color: #1e3a8a; /* Navy */
            text-align: left;
        }
        .subtitle {
            font-size: 11pt;
            color: #4b5563;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            height: 30px;
        }
        /*thead {
            background-color: #1e40af; 
            color: #ffffff;
        }*/
        td {
            vertical-align: middle;
            height: 25px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .bg-total {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        /* Format khusus Excel untuk angka (mencegah format ilmiah/error) */
        .num-format {
            mso-number-format:"\#\,\#\#0";
        }
    </style>
</head>
<body>
   
    
   <center>
    <h2>PEMERINTAH KABUPATEN KOLAKA <br>
        DINAS PERKEBUNANAN DAN PETERNAKAN <br>
        <b>REKAPAN BANTUAN</b> <br>
        SUMBER DANA APBD TAHUN ANGGARAN <?= $tahun ?>
    </h2>
</center>

    <!-- Tabel Data -->
    <table border="1" cellpadding="6">
        <thead style="background-color: #1efefe">
            <tr>
                <th width="50">No</th>
                <th>Tahun</th>
                <th width="250">Uraian/Kegiatan</th>
                <th width="200">Kecamatan/Kelurahan</th>
                <th width="150">Kelompok Tani</th>
                <th width="120">Ketua</th>
                <th>Luas Lahan(Ha)</th>
                <th>Jumlah/Jenis</th>
               
            </tr>
        </thead>
        <tbody>
  <?php

$q = "SELECT 
            pj.*,
            g.nama_kelompok,
            p.nama_petani AS ketua,
            COALESCE(SUM(l.luas_lahan), 0) AS tot_luas_lahan,
            d.nama_desa,
            k.nama_kecamatan,
            b.nama_bibit
      FROM tbl_pengajuan pj
      LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
      LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
      LEFT JOIN tbl_lahan l  ON g.id_group = l.id_group
      LEFT JOIN tbl_desa d  ON g.id_desa = d.id_desa
      LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
      LEFT JOIN tbl_bibit b ON b.id_bibit = pj.id_bibit
      WHERE DATE(pj.tgl_pengajuan) BETWEEN ? AND ?
        AND pj.s_pengajuan = 1";

$params = [$dari, $hingga];
$types = "ss";

// Filter kecamatan
if (!empty($id_kecamatan)) {
    $q .= " AND k.id_kecamatan = ?";
    $params[] = $id_kecamatan;
    $types .= "i";
}

// Filter bibit
if (!empty($id_bibit)) {
    $q .= " AND pj.id_bibit = ?";
    $params[] = $id_bibit;
    $types .= "i";
}

$q .= " GROUP BY pj.id_pengajuan";

$stmt = $conn->prepare($q);

if (!$stmt) {
    die("Query gagal: " . $conn->error);
}

$stmt->bind_param($types, ...$params);

$stmt->execute();

$result = $stmt->get_result();

$no = 1;

if ($result->num_rows === 0):
?>
            <tr>
                <td colspan="5" class="text-center" style="color: #94a3b8; font-style: italic;">Tidak ada data transaksi pada periode ini.</td>
            </tr>
        <?php 
        unset($no); // menghapus penomoran jika kosong
        else:
            while ($x = $result->fetch_assoc()):
               
        ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td class="text-center"><?= htmlspecialchars(date("d-m-Y", strtotime($x['tgl_pengajuan']))); ?></td>
                <td class="text-center"><?= htmlspecialchars($x['judul']); ?></td>
                <td><?= htmlspecialchars($x['nama_kecamatan']); ?>-<?= htmlspecialchars($x['nama_desa']); ?></td>
                <td class="text-center"><?= htmlspecialchars($x['nama_kelompok']); ?></td>
                <td class="text-center"><?= htmlspecialchars($x['ketua']); ?></td>
                <td class="text-center"><?= htmlspecialchars($x['tot_luas_lahan']); ?></td>
                <td class="text-center"><?= $x['jml_bantuan']; ?> <?= $x['nama_bibit']; ?></td>
                
            </tr>
        <?php 
            endwhile; 
        endif;
        ?>
        </tbody>
        
        
    </table>

</body>
</html>