<?php
// include '../../controller/process/config/database.php';
include '../../controller/config/database.php';

$id_pengajuan   = $_GET['id_pengajuan'];
$tanggal        = $_GET['date'];



function splitTahun($tanggal) {
    $split = explode('-', $tanggal);
    if (count($split) === 3) {
        return "{$split[0]}" ;
    }
    return $tanggal;
}

$tahun = splitTahun($tanggal);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penerimaan BIBIT</title>
    <style type="text/css">
        @page {
            size: A4 landscape;
            margin-top: 0;
            margin-bottom: 0;
            margin-left: 0;
            margin-right: 0;
            padding-left: 0;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            padding: 20px; /* Tambahan padding agar tidak terlalu mepet tepi kertas saat dicetak */
        }
        
        /* CSS Untuk Kop Surat */
        .kop-surat {
            width: 100%;
            border: none;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .kop-surat td {
            border: none;
        }
        .garis-kop {
            border-top: 3px solid black;
            border-bottom: 1px solid black;
            height: 2px;
            margin-top: 10px;
            margin-bottom: 20px;
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
        .table-data {
            border-collapse: collapse;
            width: 100%;
        }
        .table-data th {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            height: 30px;
        }
        .table-data td {
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
        .num-format {
            mso-number-format:"\#\,\#\#0";
        }
    </style>
</head>
<body>
   
    <!-- KOP SURAT (Logo, Judul, Garis) -->
    <table class="kop-surat">
        <tr>
            <!-- Kolom Kiri untuk Logo -->
            <td style="width: 15%; text-align: center;">
                <!-- SESUAIKAN PATH GAMBAR LOGO DI BAWAH INI -->
                <img src="../../img/logo/logo.png" style="width: 100px; height: auto;" alt="Logo Kolaka">
            </td>
            
            <!-- Kolom Tengah untuk Teks -->
            <td style="width: 70%; text-align: center;">
                <h2 style="margin: 0; line-height: 1.3;">
                    PEMERINTAH KABUPATEN KOLAKA <br>
                    DINAS PERKEBUNAN DAN PETERNAKAN <br>
                    <b>REKAPAN BANTUAN</b> <br>
                    <span style="font-size: 14pt; font-weight: normal;">SUMBER DANA APBD TAHUN ANGGARAN <?= $tahun ?></span>
                </h2>
            </td>
            
            <!-- Kolom Kanan (Kosong) agar teks benar-benar berada di tengah kertas -->
            <td style="width: 15%;"></td>
        </tr>
    </table>
    
    <!-- Garis Mendatar Kop Surat -->
    <div class="garis-kop"></div>


    <!-- Tabel Data -->
    <table class="table-data" border="1" cellpadding="6">
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
       $q = "SELECT pj.*,
            g.nama_kelompok,
            p.nama_petani as ketua,
            COALESCE(SUM(l.luas_lahan), 0) AS tot_luas_lahan,
            d.nama_desa,
            k.nama_kecamatan,
            b.nama_bibit
       FROM tbl_pengajuan pj
       LEFT JOIN tbl_groups g ON pj.id_group = g.id_group
       LEFT JOIN tbl_petani p ON g.id_leader = p.id_petani
       LEFT JOIN tbl_lahan  l ON g.id_group = l.id_group
       LEFT JOIN tbl_desa d ON g.id_desa = d.id_desa
       LEFT JOIN tbl_kecamatan k ON d.id_kecamatan = k.id_kecamatan
       LEFT JOIN tbl_bibit b ON b.id_bibit = pj.id_bibit
       WHERE pj.id_pengajuan = ?
       GROUP BY g.id_group
       ";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("i", $id_pengajuan);
        
       
        $stmt->execute();
        $result = $stmt->get_result();

        $no = 1;

        if ($result->num_rows === 0):
        ?>
            <tr>
                <td colspan="8" class="text-center" style="color: #94a3b8; font-style: italic;">Tidak ada data transaksi pada periode ini.</td>
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

    <br><br>
    
    <!-- Bagian Tanda Tangan -->
    <table style="border-collapse: collapse; border: none; width: 100%;">
    <tr>
        <!-- Bagian Kiri (Bisa dikosongkan atau diisi pihak lain yang mengetahui) -->
        <td style="border: none; width: 50%; text-align: center;">
            <br>
            <!-- Mengetahui, <br> -->
            <!-- Pejabat Pembuat Komitmen <br> -->
            <br><br><br><br><br>
            <!-- <b><u>NAMA PEJABAT KIRI</u></b><br> -->
            <!-- NIP. 1980xxxxxxxxxxxxxx -->
        </td>
        
        <!-- Bagian Kanan (Penandatangan Utama) -->
        <td style="border: none; width: 50%; text-align: center;">
            Kolaka, <?= htmlspecialchars(date("d F Y", strtotime($tanggal))); ?><br>
            Kepala Dinas Perkebunan dan Peternakan<br>
            Kabupaten Kolaka<br>
            
            <!-- Gambar Tanda Tangan -->
            <div style="margin-top: 5px; margin-bottom: 5px;">
                <img src="../../img/ttd/ttd-1.png" style="width: 120px; height: auto;">
            </div>
            
            <b><u>Zulham S.Kom</u></b><br>
            NIP. 1234567890
        </td>
    </tr>
</table>

</body>
</html>

<script type="text/javascript">
    window.print();
</script>