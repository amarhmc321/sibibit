    <!-- Page Content -->
    <div id="page-content-wrapper">
      <!-- Header -->
      <nav class="header navbar navbar-expand-lg  shadow-sm no-print">
        <div class="container-fluid p-0"> <!-- Padding dihapus agar lebih rapat ke tepi -->

          <!-- Tombol Sidebar Toggle -->
          <button class="btn btn-dark me-2" id="sidebarToggle" title="Toggle Menu" data-bs-toggle="tooltip2"> <!-- Ubah me-3 ke me-2 -->
            <i class="fa fa-bars"></i>
          </button>

          <div class="search-wrapper">
              <i class="fa fa-search search-icon"></i>
              <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari data...">
              <i class="fa fa-times clear-icon" id="clearBtn"></i>

          </div>

          <!-- Judul Dashboard -->
          <h5 class="mb-0 me-auto d-blok" id="judul-halaman">Dashboard</h5>
          <div class="d-flex me-3 cari" data-bs-toggle="tooltip2" title="Cari Data">
            <i class="fa fa-search" id="search-icon"></i>
          </div>
          <!-- Notifikasi -->
          <div class="dropdown me-3" data-bs-toggle="tooltip2" title="Notifikasi" id="notifikasi"></div>

          <!-- Dropdown User Profile -->
          <div class="dropdown" data-bs-toggle="tooltip2" title="Profile" id="Profil">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="user-avatar-lg" data-nama="<?= $_SESSION['nama'] ?>" data-foto="<?= $_SESSION['foto'] ?>"></div>
              <span class="profil-text ms-2"><?= $_SESSION['nama']; ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">

              <li data-bs-toggle="tooltip2" title="Lihat Profil">
                <a href="#" class="dropdown-item" data-page="profile">
                  <i class="fa fa-user me-2"></i> Profil Saya
                </a>
              </li>

              <li>
                <a href="#" class="dropdown-item" id="toggleTheme">
                  <i class="fas fa-moon me-2"></i> <span></span>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a href="#" onclick="logout();" class="dropdown-item text-danger">
                  <i class="fa fa-sign-out me-2"></i> Keluar
                </a>
              </li>
            </ul>
          </div>

        </div>
      </nav>


      <!-- Content -->
      <div class="content container-fluid">

        <div id="loadingOverlay">
          <div class="loader-wrap">
            <!-- Logo di tengah spinner -->
            <div class="spinner">
              <img src="img/logo/logo.png" alt="Logo" class="spinner-logo" height="990" width="990">
            </div>
            <div id="loaderText">Sedang memuat...</div>
          </div>
        </div>


        <div id="content">
        </div>
      </div>

      <!-- Footer -->
      <footer class="footer text-center no-print">
        <div class="container">
          <span>&copy; 2025 My Dashboard. All rights reserved by Amar HMC.</span>
        </div>
      </footer>
    </div>