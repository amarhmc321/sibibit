$(document).ready(function () {
   
  formTemplate("#form-timeline", {
    defaultEndpoint: "controller/process/aksiTimeline.php",
  });
});


$(document).on("click", ".edit", function(e) {
        editTimeline(); 
});


// Fungsi untuk membuka modal edit data
function editTimeline() {
  $.ajax({
    url: `controller/process/aksiTimeline.php?action=read`,
    type: "POST",
    dataType: "json",
    success: function (data) {
      $("#modalTimeline").modal("show");
     
      // STEP 1 
      $("#step1_start").val(data.step1_start);
      $("#step1_end").val(data.step1_end);

       // STEP 2 
      $("#step2_start").val(data.step2_start);
      $("#step2_end").val(data.step2_end);

       // STEP 1 
      $("#step3_start").val(data.step3_start);
      $("#step3_end").val(data.step3_end);

       // STEP 1 
      $("#step4_start").val(data.step4_start);
      $("#step4_end").val(data.step4_end);
     
      

   
    },
    error: function () {
      Popup.error("Gagal!", "Error pada link", 3000);
    },
  });
}





