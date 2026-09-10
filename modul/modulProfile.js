 $(document).ready(function() {
        // Preview gambar saat memilih file
        $("#foto").change(function() {
            if (this.files && this.files[0]) {
                // Tampilkan progress bar
                $("#uploadProgress").removeClass("d-none");

                // Simulasi progress upload
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += 5;
                    $("#uploadProgress .progress-bar").css("width", progress + "%");

                    if (progress >= 100) {
                        clearInterval(progressInterval);

                        // Setelah upload selesai, preview gambar
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $("#previewFoto").attr("src", e.target.result)
                                .css("opacity", "0")
                                .animate({
                                    opacity: 1
                                }, 500);

                            // Sembunyikan progress bar setelah 500ms
                            setTimeout(() => {
                                $("#uploadProgress").addClass("d-none");
                            }, 500);
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                }, 50);
            }
        });

        // Toggle password visibility
        $("#togglePassword").click(function() {
            let passwordInput = $("#password");
            let icon = $(this).find("i");

            if (passwordInput.attr("type") === "password") {
                passwordInput.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                passwordInput.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });

        // Validasi real-time pada form
        $("input").blur(function() {
            if (!this.checkValidity()) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Preview tanda tangan
        $("#ttdUpload").change(function() {
            if (this.files && this.files[0]) {
                // Validasi ukuran file (maks 2MB)
                if (this.files[0].size > 2 * 1024 * 1024) {
                    alert("Ukuran file terlalu besar. Maksimal 2MB.");
                    $(this).val('');
                    return;
                }

                // Validasi tipe file
                const fileType = this.files[0].type;
                if (!fileType.match('image/jpeg') && !fileType.match('image/png')) {
                    alert("Format file tidak didukung. Hanya JPG dan PNG yang diizinkan.");
                    $(this).val('');
                    return;
                }

                let reader = new FileReader();
                reader.onload = function(e) {
                    $("#ttdPreview").attr("src", e.target.result);
                    $("#clearTtd").show();
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Hapus tanda tangan
        $("#clearTtd").click(function() {
            $("#ttdUpload").val('');
            $("#ttdPreview").attr("src", "img/ttd/default-ttd.png");
            $(this).hide();
        });

 $('.needs-validation').on('submit', function(event) {
            // Cek validasi form Bootstrap
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                $(this).addClass('was-validated');
                return;
            }

            // Jika validasi lolos, jalankan AJAX
            event.preventDefault();
            $(this).addClass('was-validated');

            const action = $("#action").val().trim();
            const formData = new FormData(this);

            // Ubah teks tombol menjadi loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Memproses...');

            // Kirim data menggunakan AJAX
            $.ajax({
                url: `controller/process/aksiUsers.php?action=${action}`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    if (response.message) {
                        Popup.read(`Success!`, `${response.message}`, 1000);
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        Popup.error('Gagal!', response.error, 3000);
                    }

                    // Kembalikan tombol ke state semula
                    submitBtn.prop('disabled', false).html(originalText);
                },
                error: function() {
                    Popup.error('Gagal!', 'Terjadi Kesalahan Saat Menambah Data', 3000);
                    // Kembalikan tombol ke state semula
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });


    });