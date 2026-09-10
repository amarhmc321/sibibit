<div class="dashboard-container container-fluid py-4">
    <div class="mb-4">
        <h3 class="fw-bold border-bottom pb-2">Jadwal Pelaksanaan Program Bantuan Bibit Tahun Anggaran <?= date("Y") ?> </h3>
    </div>

    <div class="stepper">

        <!-- STEP 1 -->
        <div class="step" data-start="2026-06-01T00:00:00" data-end="2026-06-30T23:59:59">
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
                    <h5 class="step-title">Pendaftaran Kelompok Tani</h5>
                    <div class="step-date mb-3">01 Juni - 30 Juni 2026</div>
                    <p class="step-description">Siapkan persyaratan dan kelengkapan administrasi yang diperlukan</p>
                </div>
            </div>
        </div>

        <!-- STEP 2 -->
        <div class="step" data-start="2026-07-01T00:00:00" data-end="2026-07-31T23:59:59">
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
                    <div class="step-date mb-3">01 - 31 Juli 2026</div>
                    <p class="step-description">Proses Penguplotan dan Seleksi Berkas</p>
                </div>
            </div>
        </div>

        <!-- STEP 3 -->
        <div class="step" data-start="2026-08-01T00:00:00" data-end="2026-08-03T23:59:59">
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
                    <div class="step-date mb-3">01 - 03 Agustus 2026</div>
                    <p class="step-description">Pengumuman Penerima Bantuan</p>
                </div>
            </div>
        </div>

        <!-- STEP 4 -->
        <div class="step" data-start="2026-08-15T00:00:00" data-end="2026-08-20T23:59:59">
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
                    <div class="step-date mb-3">15 - 20 Agustus 2026</div>
                    <p class="step-description">Proses Penyerahan Bantuan kepada Petani</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Header Bagian Bibit -->
    <div class="mb-4 mt-5">
        <h3 class="fw-bold border-bottom pb-2">Daftar Bibit yang tersedia</h3>
    </div>

    <!-- Row untuk Grid System -->
    <div class="row g-4" id="dt-bibit">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <img src="img/bibit/default.jpg" class="card-img-top card-img-custom" alt="Bibit Kakao">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">Bibit Kakao</h5>
                    <p class="card-text text-muted">Bibit kakao unggul siap tanam untuk hasil panen yang lebih maksimal.</p>
                    <a href="#" class="btn btn-success mt-auto w-100 rounded-pill">
                        <i class="bi bi-plus-circle me-1"></i> Ajukan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require "../modal/modalPengajuan.php";
?>

<script>
$(document).ready(function () {

    // Custom tanggal untuk testing
    const currentDate = new Date("2026-07-31");

    $(".step").each(function () {

        const step = $(this);

        const startDateString = step.attr("data-start");
        const endDateString   = step.attr("data-end");

        if (!startDateString || !endDateString) return;

        const startDate = new Date(startDateString);
        const endDate   = new Date(endDateString);

        const statusBadge = step.find(".step-status");

        // Reset semua class status terlebih dahulu
        step.removeClass("belum-selesai sedang-berlangsung");
        statusBadge.removeClass("belum-selesai sedang-berlangsung");

        // Kondisi 1: Tanggal sekarang sudah melewati batas akhir
        if (currentDate > endDate) {

            statusBadge.text("Selesai");

        }

        // Kondisi 2: Tanggal sekarang berada dalam rentang start & end
        else if (currentDate >= startDate && currentDate <= endDate) {

            step.addClass("sedang-berlangsung");
            statusBadge.addClass("sedang-berlangsung");
            statusBadge.text("Sedang Berlangsung");

        }

        // Kondisi 3: Tanggal sekarang belum mencapai tanggal mulai
        else {

            step.addClass("belum-selesai");
            statusBadge.addClass("belum-selesai");
            statusBadge.text("Belum Selesai");

        }

    });

});
</script>