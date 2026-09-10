<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['level'] != 2) {
  header("Location: index.php");
  exit;
}

if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>


<!DOCTYPE html>
<html lang="id" >

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
  <title>PPID Kabupaten Kolaka</title>
  <link href="assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="assets/fontawesome-free-6.7.2-web/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="assets/css/styles.css" rel="stylesheet">
  <link href="libs/css/mine2.css" rel="stylesheet">
  <link href="libs/css/loading.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="assets/daterangepicker/daterangepicker.min.css">
  <link href="assets/select2/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="assets/css/styles-dash.css">

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
  <link rel="manifest" href="favicon_io/site.webmanifest">
  <meta name="msapplication-TileColor" content="#0d6efd">
  <meta name="theme-color" content="#0d6efd">
</head>

<body>
  <div id="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar" class="d-flex flex-column">

      <div class="p-3 d-flex align-items-center h-company">
        <!-- Gambar profil perusahaan -->
        <img src="img/logo/logo.png" alt="Company Logo" class="img-fluid me-2 logo-perus">
        <!-- Tulisan My Dashboard dengan logika collapse -->
        <h4 class="mb-0 text-perus menu-text">PPID <span class="text-accent">Kabupten Kolaka</span></h4>
      </div>

      <div class="flex-grow-1 overflow-auto all-menu">
        <ul class="menu">

          <li>
            <a href="#" data-page="dash">
              <div>
                <i class="fa fa-house me-2"></i>
                <span class="menu-text">Dashboard</span>
              </div>

            </a>
          </li>



              <li>
                <a href="#" data-page="data-laporan">
                  <div>
                    <i class="fa fa-file me-2"></i>
                    <span class="menu-text">Laporan</span>
                  </div>
                </a>
              </li>


             


               

            </ul>

        


        </ul>
      </div>

      <div class="profile-sticky  d-flex justify-content-center align-items-center p-3">
        <div class="user-avatar-lg" data-nama="<?= $_SESSION['nama'] ?>" data-foto="<?= $_SESSION['foto'] ?>"></div>
        <span class="menu-text fw-bold ms-2">Kadis</span>
      </div>

    </nav>

    <div class="sidebar-overlay"></div>
    <?php include "partials/header.php" ?>

  </div>

   <script src="assets/js/jquery-3.7.1.min.js"></script>
  <!-- Bootstrap 5 JS Bundle dengan Popper -->
  <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

  <script src="assets/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="assets/daterangepicker/moment.min.js"></script>
  <script src="assets/daterangepicker/daterangepicker.min.js"></script>
  <script src="assets/select2/select2.min.js"></script>


  <script src="libs/js/mine2.js"></script>
  <script src="libs/js/loading.js"></script>
  <script src="libs/js/tooltip.js"></script>

  <script src="router/kadis.js"></script>
  <script src="router/sidebar.js"></script>


  <script src="assets/js/autoNumeric.min.js"></script>
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/html2pdf.bundle.min.js"></script>
  <script src="assets/js/qrcode.min.js"></script>


  <script src="modul/status.js"></script>
  <script src="modul/options.js"></script>
  <script src="modul/ui-helper.js"></script>
  <script src="modul/core.js"></script>
  <script src="libs/js/datatabel2.js"></script>
 <!--  <script src="modul/modulNotifikasi.js"></script> -->
  <script src="assets/chartjs/chart.min.js"></script>


<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Leaflet Control Geocoder (Untuk Fitur Pencarian) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

  <script>
    const sessionData = <?php echo json_encode($_SESSION); ?>;
    let dataFilter;
    let map;
let marker;
const kolakaLat = -4.0435;
const kolakaLng = 121.5815;

  </script>


</body>

</html>