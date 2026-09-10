    $(document).ready(function() {
    initTooltipsWithClose();
    
    // Toggle Password Visibility
    $("#togglePassword").click(function() {
        let passwordField = $("#password");
        let type = passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", type);
        $(this).toggleClass("fa-eye fa-eye-slash");
    });

    $(document).on("submit", "#loginForm", function(e) {
        e.preventDefault();
       

        let username = $.trim($("#username").val());
        let password = $.trim($("#password").val());

        if (!username || !password) {
            Popup.error('Peringatan!', 'Silakan isi semua kolom.', 2000);
            return;
        }

        // Disable submit button
        const $submitBtn = $("#loginForm button[type='submit']");
        $submitBtn.prop("disabled", true);
        $submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        // Show loading dengan progress yang lebih realistis
        showLoading({
            text: "Memeriksa kredensial...",
            autoProgress: false, // Manual control untuk progress yang lebih akurat
            duration: 0 // Non-auto, kita kontrol manual
        });

        // Set progress bertahap untuk UX yang lebih baik
        setLoadingProgress(30);

        $.ajax({
            url: "controller/process/aksiUsers.php?action=login",
            type: "POST",
            dataType: "json",
            data: { username, password },
            
            beforeSend: function() {
                setLoadingText("Mengautentikasi...");
                setLoadingProgress(60);
            },
            
            success: function(response) {
                if (response.success) {
                    setLoadingText("Login berhasil! Mengalihkan...");
                    setLoadingProgress(100);
                    
                    let redirectUrl = "index.php";

                    // Redirect otomatis berdasarkan level dari server
                    switch (response.level) {
                        case 1: redirectUrl     = "menu-admin.php?page=dash"; break;
                        case 3: redirectUrl     = "menu-petani.php?page=dash"; break;
                        default: redirectUrl    = "menu-kadis.php?page=dash";
                    }

                    // Redirect setelah loading selesai
                    setTimeout(() => {
                        hideLoading();
                        window.location.href = redirectUrl;
                    }, 800);
                    
                } else {
                    setLoadingText("Login gagal!");
                    setLoadingProgress(100);
                    
                    setTimeout(() => {
                        hideLoading();
                        Popup.error('Login Gagal', response.message || "Username atau password salah!", 3000);
                        
                        // Reset button state
                        $submitBtn.prop("disabled", false);
                        $submitBtn.html('Login');
                    }, 500);
                }
            },
            
            error: function(xhr, status, error) {
                setLoadingText("Error koneksi!");
                setLoadingProgress(100);
                
                setTimeout(() => {
                    hideLoading();
                    Popup.error('Error!', `Terjadi kesalahan: ${xhr.status} - ${error}`, 3000);
                    
                    // Reset button state
                    $submitBtn.prop("disabled", false);
                    $submitBtn.html('Login');
                }, 500);
            },
            
            complete: function() {
                // Complete sudah ditangani di success/error
            }
        });
    });

    // Hapus ajaxStop global karena bisa mengganggu loading lain
    // $(document).ajaxStop(function() {
    //     hideLoading();
    // });
});