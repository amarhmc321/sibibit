(function () {
  // Tambahin CSS helper (sekali aja)
  if (!document.getElementById("col-hidden-style")) {
    const style = document.createElement("style");
    style.id = "col-hidden-style";
    style.textContent = `.hidden { display: none !important; }`;
    document.head.appendChild(style);
  }

  // Kolom fixed yang wajib selalu tampil
  const fixedCols = [
    "no",
    "aksi",
    "id_karyawan",
    "nama_karyawan",
    "lokasi",
    "departemen",
    "jabatan",
    "s_aktif",
  ];

  // Fungsi inisialisasi filter kolom
  function initColumnFilter() {
    const savedFilters =
      JSON.parse(localStorage.getItem("employeeFilters")) || [];
    const checkedSet = new Set(savedFilters);

    // Atur checkbox sesuai savedFilters + disable kalau fixed
    document.querySelectorAll(".filter-checkbox").forEach((cb) => {
      if (fixedCols.includes(cb.value)) {
        cb.checked = true;
        cb.disabled = true; // tidak bisa diubah user
      } else {
        cb.checked = checkedSet.has(cb.value);
      }
    });

    // Terapkan filter ke tabel
    applyColumnFilters(savedFilters);

    // Pastikan event handler tidak dobel
    $(document)
      .off("change.employeeFilters", ".filter-checkbox")
      .on("change.employeeFilters", ".filter-checkbox", function () {
        // Abaikan kalau fixed
        if (fixedCols.includes(this.value)) {
          this.checked = true; // paksa tetap checked
          return;
        }

        const selectedFilters = Array.from(
          document.querySelectorAll(".filter-checkbox:checked")
        )
          .map((cb) => cb.value)
          .filter((v) => !fixedCols.includes(v));

        localStorage.setItem(
          "employeeFilters",
          JSON.stringify(selectedFilters)
        );
        applyColumnFilters(selectedFilters);
        paginateTable("#employeeTable");
      });
  }

  // Fungsi global untuk apply filter
  window.applyColumnFilters = function (filters) {
    const filtersSet = new Set(filters);

    document
      .querySelectorAll('th[class*="col-"], td[class*="col-"]')
      .forEach((el) => {
        const match = el.className.match(/\bcol-([^\s]+)/);
        if (!match) return;

        const col = match[1];

        // Fixed column selalu tampil
        if (fixedCols.includes(col)) {
          el.classList.remove("hidden");
        } else if (filtersSet.has(col)) {
          el.classList.remove("hidden");
        } else {
          el.classList.add("hidden");
        }
      });
  };

  // Jalankan init setelah konten halaman employee dimuat
  $(document).on("contentLoaded.employee", function () {
    initColumnFilter();
  });
})();
