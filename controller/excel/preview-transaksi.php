<?php
header('Content-Type: application/json');

require '../../libs/spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
require '../../libs/spreadsheet-reader-master/SpreadsheetReader.php';

/* ===============================
   VALIDASI FILE
================================ */
if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'File tidak ditemukan atau gagal upload'
    ]);
    exit;
}

$ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ['xls', 'xlsx'])) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Format file tidak didukung (xls / xlsx saja)'
    ]);
    exit;
}

/* ===============================
   SIMPAN FILE (AMAN)
================================ */
if (!is_dir('uploads')) {
    mkdir('uploads', 0755, true);
}

$filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['file_excel']['name']);
$target   = 'uploads/' . time() . '-' . $filename;

move_uploaded_file($_FILES['file_excel']['tmp_name'], $target);

/* ===============================
   INIT READER
================================ */
$reader = new SpreadsheetReader($target);

$valid   = [];
$invalid = [];

/* ===============================
   HEADER ALIAS
================================ */
$headerAlias = [
    'kd_obat'       => ['kd obat', 'kode obat', 'kode'],
    'tgl_transaksi' => ['tanggal', 'tgl transaksi', 'tgl', 'tgl transaksi',],
    'jml_transaksi' => ['jumlah', 'jumlah transaksi', 'qty', 'jml transaksi']
];

$totalSheet = count($reader->sheets());

/* ===============================
   LOOP SHEET
================================ */
for ($s = 0; $s < $totalSheet; $s++) {

    $reader->ChangeSheet($s);

    $headerIndex = [];
    $headerFound = false;

    foreach ($reader as $rowIndex => $row) {

        /* ===============================
           CARI HEADER
        ================================ */
        if (!$headerFound) {
            foreach ($row as $colIndex => $colVal) {
                $colVal = strtolower(trim($colVal));

                foreach ($headerAlias as $key => $aliases) {
                    if (in_array($colVal, $aliases)) {
                        $headerIndex[$key] = $colIndex;
                    }
                }
            }

            // header dianggap valid minimal kolom wajib ada
            if (isset($headerIndex['kd_obat'],$headerIndex['tgl_transaksi'], $headerIndex['jml_transaksi'])) {
                // set default untuk kolom opsional
                $headerIndex += [
                    'jns_transaksi' => null,
                    'ket_transaksi' => null
                ];

                $headerFound = true;
            }

            continue;
        }

        /* ===============================
           SKIP BARIS KOSONG
        ================================ */
        if (!array_filter($row)) {
            continue;
        }

        /* ===============================
           AMBIL DATA
        ================================ */
        $tgl = $row[$headerIndex['tgl_transaksi']] ?? '';

        // handle tanggal excel numerik
        if (is_numeric($tgl)) {
            $tgl = date('Y-m-d', strtotime('1899-12-30 +' . $tgl . ' days'));
        }

        $data = [
            'sheet'         => $s + 1,
            'kd_obat'         => trim($row[$headerIndex['kd_obat']] ?? ''),
            'tgl_transaksi' => trim($tgl),
            'jml_transaksi' => trim($row[$headerIndex['jml_transaksi']] ?? '')
        ];

        /* ===============================
           VALIDASI ISI
        ================================ */
        if (
            $data['kd_obat'] !== '' &&
            $data['tgl_transaksi'] !== '' &&
            is_numeric($data['jml_transaksi'])
        ) {
            $valid[] = $data;
        } else {
            $invalid[] = $data;
        }
    }
}

/* ===============================
   HAPUS FILE (OPSIONAL)
================================ */
// unlink($target);

echo json_encode([
    'status'  => 'success',
    'valid'   => $valid,
    'invalid' => $invalid
]);
