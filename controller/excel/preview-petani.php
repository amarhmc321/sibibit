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
    'nama_petani' => ['nama', 'nama petani', 'nama_petani', 'nama lengkap'],
    'nik'         => ['nik', 'no ktp', 'no. ktp', 'noktp', 'ktp'],
    'jekel'       => ['jekel', 'jenis kelamin', 'jk', 'gender'],
    'kontak'      => ['kontak', 'no hp', 'no. hp', 'nohp', 'hp', 'telepon', 'telp'],
    'alamat'      => ['alamat', 'alamat lengkap'],
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

            // header dianggap valid bila kolom wajib ada
            if (isset($headerIndex['nama_petani'])) {
                $headerIndex += [
                    'nik'    => null,
                    'jekel'  => null,
                    'kontak' => null,
                    'alamat' => null,
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
        $get = function ($key) use ($row, $headerIndex) {
            if ($headerIndex[$key] === null || !isset($row[$headerIndex[$key]])) {
                return '';
            }
            $val = $row[$headerIndex[$key]];
            // reader mengubah angka menjadi float -> kembalikan sebagai teks utuh
            if (is_float($val) || is_int($val)) {
                $val = sprintf('%.0f', $val);
            }
            return trim((string) $val);
        };

        $jekel = strtolower($get('jekel'));
        if ($jekel === 'p' || strpos($jekel, 'perempuan') === 0) {
            $jekel = 'P';
        } elseif ($jekel === 'l' || strpos($jekel, 'laki') === 0 || strpos($jekel, 'pria') === 0) {
            $jekel = 'L';
        } elseif ($jekel === '') {
            $jekel = '';
        } else {
            $jekel = 'L';
        }

        $kontak = $get('kontak');
        // pulihkan leading zero nomor HP yang hilang saat dibaca sebagai angka
        if (preg_match('/^8\d{9,10}$/', $kontak)) {
            $kontak = '0' . $kontak;
        }

        $data = [
            'sheet'       => $s + 1,
            'nama_petani' => $get('nama_petani'),
            'nik'         => $get('nik'),
            'jekel'       => $jekel,
            'kontak'      => $kontak,
            'alamat'      => $get('alamat'),
        ];

        /* ===============================
           VALIDASI ISI
        ================================ */
        if ($data['nama_petani'] !== '') {
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
