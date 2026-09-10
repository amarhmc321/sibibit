<div class="card">
    <div class="card-header">
        <h3 class="judul"><i class="fa-solid fa-fa-cash-register"></i><span> Import Data Transaksi</span></h3>
        <ul class="tombol-cetak">
            <a class="dropdown-item" href="#" data-page="transaksi">
                <i class="fas fa-arrow-right me-2"></i>Kembali
            </a>
        </ul>

    </div>

    <div class="card-body">
        <!-- Form Upload -->
        <form id="formUpload" enctype="multipart/form-data">
            <div class="row g-2 align-items-center mb-3">
                <!-- Input File -->
                <div class="col-md-6">
                    <input type="file" class="form-control" name="file_excel" accept=".xls,.xlsx" required>
                </div>

                <!-- Select Lokasi -->
                <div class="col-md-4" id="filter">
                </div>

                <!-- Tombol -->
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm">Preview Data</button>
                </div>
            </div>
        </form>



        <!-- Accordion Tutorial -->
        <div class="accordion my-3" id="cara-import">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTransfer">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseImport" aria-expanded="false" aria-controls="collapseImport">
                        Bagaimana Cara Import Data?
                    </button>
                </h2>
                <div id="collapseImport" class="accordion-collapse collapse" aria-labelledby="headingImport"
                    data-bs-parent="#cara-import">
                    <div class="accordion-body">
                        <ol class="ps-3">
                            <li>Klik <b>Browse</b> untuk mencari file yang ingin diimport</li>
                            <li>Pastikan file berformat <b>.xls</b> atau <b>.xlsx</b></li>
                            <li>Pastikan Data sesuai</li>
                            <li>Klik <b>Preview</b> untuk melihat hasil data</li>
                            <li>Klik <b>Simpan</b> untuk menyimpan data</li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>

        <div class="accordion my-3" id="Help">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTransfer">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseHelp" aria-expanded="false" aria-controls="collapseHelp">
                        Download Contoh Format File?
                    </button>
                </h2>
                <div id="collapseHelp" class="accordion-collapse collapse" aria-labelledby="headingHelp"
                    data-bs-parent="#Help">
                    <div class="accordion-body">
                        <ol class="ps-3">
                            <li>Link  <a href="views/404.html">Download</a></li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>



        <!-- Preview Table -->
        <div class="table-responsive my-3" id="preview" style="white-space: nowrap;">
            <!-- tabel preview muncul di sini -->
        </div>

        <!-- Simpan Data -->
        <div id="sv" class="action-buttons"></div>
    </div>


</div>

<script>
    
</script>