<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Pengajuan Bantuan Bibit</title>
  <link href="assets/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
  <link href="assets/fontawesome-free-6.7.2-web/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="assets/css/styles.css" rel="stylesheet">
  <link href="assets/css/styles-login.css" rel="stylesheet">
  <link href="libs/css/mine2.css" rel="stylesheet">
  <script src="libs/js/tooltip.js"></script>
  <link href="libs/css/loading.css" rel="stylesheet">
  <link class="rounded-circle" rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
  <link rel="manifest" href="favicon_io/site.webmanifest">
</head>



<body>
  <div class="bg-orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
  </div>

  <div class="container login-wrapper">
    <div class="row justify-content-center w-100 g-0">
      <div class="col-11 col-sm-9 col-md-6 col-lg-4">
        <div class="card shadow-lg">

          <div class="card-body p-4 p-md-5">
            <div class="brand-card text-center">
              <div class="logo-container-login mb-3">
                <div class="logo-glow"></div>
                <img src="img/logo/logo.png" alt="Logo Apotek Sehat Farma" class="rounded-circle profile-img-login" />
              </div>
              <h2 class="menu-text mb-1">Dinas Perkebunan Kabupaten Kolaka</h2>
              <p class="tagline">Sistem Informasi Pengajuan Bibit Perkebunan</p>
            </div>

            <form id="loginForm">
              <div class="input-container">
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                  <input type="text" id="username" class="form-control" placeholder="Masukkan username" name="username" autofocus required />
                </div>
              </div>

              <div class="input-container">
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" id="password" class="form-control" placeholder="Masukkan password" name="password" required />
                  <span class="input-group-text toggle-password-btn" id="togglePassword" style="display: none;">
                    <i class="fas fa-eye"></i>
                  </span>
                </div>
              </div>

              <button class="btn btn-login w-100 py-3 fw-bold" type="submit">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk
              </button>
            </form>

            <div class="footer-text">
              &copy; 2026 PPID Kabupaten Kolaka
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/jquery-3.7.1.min.js"></script>
  <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <script src="libs/js/mine2.js"></script>
  <script src="libs/js/loading.js"></script>
  <script src="modul/modulLogin.js"></script>
</body>

</html>