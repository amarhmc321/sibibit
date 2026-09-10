<!-- Content -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header mb-3">
                <h3 class="judul"><i class="fas fa-clock me-1"></i>Time Line</h3>
                <div data-bs-toggle="tooltip" title="Edit Data">
                    <button class="btn btn-sm btn-secondary edit"><i class="fas fa-edit"></i></button>
                </div>


            </div>
             <div class="stepper">

    <!-- STEP 1 -->
    <div class="step" data-step="1">
        <div class="step-header">
            <div class="step-icon">
                <i class="fas fa-file"></i>
            </div>
        </div>

        <div class="card step-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="step-number">01</span>
                    <span class="step-status"></span>
                </div>

                <h5 class="step-title">Persiapan Berkas Kelompok Tani</h5>
                <div class="step-date mb-3"></div>

                <p class="step-description">
                    Siapkan persyaratan dan kelengkapan administrasi yang diperlukan
                </p>
            </div>
        </div>
    </div>

    <!-- STEP 2 -->
    <div class="step" data-step="2">
        <div class="step-header">
            <div class="step-icon">
                <i class="fas fa-edit"></i>
            </div>
        </div>

        <div class="card step-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="step-number">02</span>
                    <span class="step-status"></span>
                </div>

                <h5 class="step-title">Pemeriksaan Proposal</h5>
                <div class="step-date mb-3"></div>

                <p class="step-description">
                    Proses Penguplotan dan Seleksi Berkas
                </p>
            </div>
        </div>
    </div>

    <!-- STEP 3 -->
    <div class="step" data-step="3">
        <div class="step-header">
            <div class="step-icon">
                <i class="fas fa-bullhorn"></i>
            </div>
        </div>

        <div class="card step-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="step-number">03</span>
                    <span class="step-status"></span>
                </div>

                <h5 class="step-title">Pengumuman Penerima Bantuan</h5>
                <div class="step-date mb-3"></div>

                <p class="step-description">
                    Pengumuman Penerima Bantuan
                </p>
            </div>
        </div>
    </div>

    <!-- STEP 4 -->
    <div class="step" data-step="4">
        <div class="step-header">
            <div class="step-icon">
                <i class="fas fa-trophy"></i>
            </div>
        </div>

        <div class="card step-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="step-number">04</span>
                    <span class="step-status"></span>
                </div>

                <h5 class="step-title">Penyaluran Bantuan</h5>
                <div class="step-date mb-3"></div>

                <p class="step-description">
                    Proses Penyerahan Bantuan kepada Petani
                </p>
            </div>
        </div>
    </div>

</div>
        </div>
    </div>
</div>


<!-- Modal load edit dan tambah Bibit -->
<?php
require "../modal/modalTimeline.php";
?>