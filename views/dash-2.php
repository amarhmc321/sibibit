<?php
date_default_timezone_set('Asia/Makassar');
$dari = date('Y-m-d', strtotime('-1 month'));
$hingga  = date('Y-m-d');
?>

<div class="dashboard-container">
    <!-- Header Dashboard -->
    <div class="header-dash d-flex gap-2 align-items-end">
        <div>
            <label for="tanggal_sampai">Kelompok</label>
            <select class="form-select" id="filter-kelompok"></select>
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

                <div class="card-dash stat-card-dash pointer" data-page="data-pengajuan">
                    <div class="stat-icon icon-blue text-light">
                        <i class="fas fa-envelope-circle-check"></i>
                    </div>
                    <div class="stat-info-dash">
                        <span>Pengajuan</span>
                        <h3 id="tot-pengajuan" >0</h3>
                    </div>
                </div>

                <div class="card-dash stat-card-dash pointer" data-page="data-pengajuan">
                    <div class="stat-icon bg-warning text-dark">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-info-dash">
                        <span>Pengajuan Pending</span>
                        <h3 id="tot-pending" >0</h3>
                       
                    </div>
                </div>

                <div class="card-dash stat-card-dash pointer" data-page="data-pengajuan">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-info-dash">
                        <span>Pengajuan Diterima</span>
                        <h3 id="tot-acc" >0</h3>
                        
                    </div>
                </div>

                <div class="card-dash stat-card-dash pointer" data-page="data-pengajuan">
                    <div class="stat-icon bg-danger">
                        <i class="fas fa-close"></i>
                    </div>
                    <div class="stat-info-dash">
                        <span>Pengajuan Ditolak</span>
                        <h3 id="tot-tolak" >0</h3>
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
                        <div class="text-end mt-3">
                            <a href="#" class="text-muted link" data-page="data-pengajuan">
                                Lihat Semua Pengajuan <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

