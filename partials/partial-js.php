  <script src="assets/js/jquery-3.7.1.min.js"></script>
  <!-- Bootstrap 5 JS Bundle dengan Popper -->
  <script src="assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

  <script src="assets/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="assets/daterangepicker/moment.min.js"></script>
  <script src="assets/daterangepicker/daterangepicker.min.js"></script>
  <script src="assets/select2/select2.min.js"></script>
  
  <script src="assets/quill/quill.min.js"></script>
  <script src="assets/moment/moment.min.js"></script>
  <script src="assets/moment/id.min.js"></script>


  <script src="libs/js/mine2.js"></script>
  <script src="libs/js/loading.js"></script>
  <script src="libs/js/tooltip.js"></script>

<?php if ($_SESSION['level'] == 3): ?>
<script src="ui-helper/ctrlMenu-User2.js"></script>
<?php else: ?>
  <script src="ui-helper/ctrlMenu.js"></script>
<?php endif ?>


  <script src="ui-helper/sidebar.js"></script>


  <script src="assets/js/autoNumeric.min.js"></script>
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/html2pdf.bundle.min.js"></script>
  <script src="assets/js/qrcode.min.js"></script>


  <script src="modul/status.js"></script>
  <script src="modul/options.js"></script>
  <script src="modul/ui-helper.js"></script>
  <script src="modul/core.js"></script>
  <script src="libs/js/datatabel2.js"></script>
  <script src="modul/modulNotifikasi.js"></script>

  <script>
    const sessionData = <?php echo json_encode($_SESSION); ?>;
    let dataFilter;
    // let wordGoal = 1000;
// let autoSaveEnabled = true;
// let isFocusMode = false;
// let autoSaveTimer = null;
// let editHistory = [];
// let historyIndex = -1;
// let maxHistory = 50;
// let quill = null;
// let focusQuill = null;
// let currentEditorState = '';
// let isModuleInitialized = false;
  </script>
