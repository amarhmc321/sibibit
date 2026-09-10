<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sibibit";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}



// <?php
// $host = "localhost"; 
// $user = "sipn9613_lppm"; 
// $pass = "sip3mlppm"; 
// $dbname = "sipn9613_sikar_ptsjs"; 

// $conn = new mysqli($host, $user, $pass, $dbname);

// if ($conn->connect_error) {
//     die("Koneksi gagal: " . $conn->connect_error);
// }
?>