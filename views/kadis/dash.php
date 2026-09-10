<?php
date_default_timezone_set('Asia/Makassar');
$dari = date('Y-m-d', strtotime('-1 month'));
$hingga  = date('Y-m-d');
?>

<div class="dashboard-container">
    <!-- Header Dashboard -->
    <div class="header-dash d-flex gap-2 align-items-end">
        <div>
            <label for="tanggal_sampai">Kecamatan</label>
            <select class="form-select" id="filter-kecamatan"></select>
        </div>
        <div>
            <label for="tanggal_mulai">Dari</label>
            <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= $dari ?>" class="form-control">
        </div>

        <div>
            <label for="tanggal_sampai">Sampai</label>
            <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="<?= $hingga ?>" class="form-control">
        </div>
        
    </div>

    <!-- Filter Section -->
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-12 col-md-12">
            <!-- Stat Cards -->
            <div class="card-grid-dash">
                <div class="card-dash stat-card-dash pointer" >
                    <div class="stat-icon icon-teal">
                        <i class="fas fa-people-group"></i>
                    </div>
                    <div class="stat-info-dash">
                        <span>Kelompok Tani</span>
                        <h3 id="tot-kelompok" >0</h3>
                        <small class="text-success" id="tot-kelompok-baru">0 baru</small>
                    </div>
                </div>

                <div class="card-dash stat-card-dash pointer" >
                    <div class="stat-icon icon-pink">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-info-dash">
                        <span>Petani</span>
                        <h3 id="tot-petani" >0</h3>
                        <small class="text-success" id="tot-petani-baru">0 baru</small>
                    </div>
                </div>

                <div class="card-dash stat-card-dash pointer" >
                    <div class="stat-icon icon-purple">
                        <i class="fas fa-map-location"></i>
                    </div>
                    <div class="stat-info-dash">
                        <span>Lahan</span>
                        <h3 id="tot-lahan" >0</h3>
                        <small class="text-success" id="tot-lahan-baru">0 baru</small>
                    </div>
                </div>

                <div class="card-dash stat-card-dash pointer">
                    <div class="stat-icon bg-success text-light">
                        <i class="fas fa-envelope-circle-check"></i>
                    </div>
                    <div class="stat-info-dash">
                        <span>Pengajuan</span>
                        <h3 id="tot-pengajuan" >0</h3>
                        <small class="text-success" id="tot-pengajuan-baru">0 baru</small>
                    </div>
                </div>

            </div>
            
         
            
            <!-- Row Kedua: Ringkasan dan Transaksi Terbaru -->
            <div class="row">                    
                <div class="col-lg-12">
                    <!-- Transaksi Terbaru -->
                    <div class="recent-transactions">
                        <h3 class="chart-title mb-4">
                            <i class="fas fa-history me-2"></i> Pengajuan Terbaru
                        </h3>
                        <div class="table-responsive">
                            <table class="transactions-table">
                                <thead>
                                    <tr>
                                        <th>Oleh Tanggal</th>
                                        <th>Kelompok</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-transactions-list">
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="text-end mt-3">
                            <a href="#" class="text-muted link" data-page="data-pengajuan">
                                Lihat Semua Pengajuan <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div> -->
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

