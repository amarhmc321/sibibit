function loadOptions(
  url,
  targetSelectors,
  valueKey = "id",
  textKey = "name",
  placeholder = null,
  extraParams = {},
  callback
) {
  // Kalau cuma string, ubah jadi array 1 elemen
  if (!Array.isArray(targetSelectors)) {
    targetSelectors = [targetSelectors];
  }

  $.ajax({
    url: url,
    type: "GET",
    data: extraParams,
    dataType: "json",
    success: function (data) {
      let options = "";

      // Pastikan data adalah array
      if (!Array.isArray(data)) {
        data = [];
      }

      // Tambah placeholder kalau ada
      if (placeholder) {
        options += `<option value="" disabled selected>${placeholder}</option>`;
      }

      if (data.length > 0) {
        // Loop data untuk option
        options += data
          .map(
            (item) =>
              `<option value="${item[valueKey]}">${item[textKey]}</option>`
          )
          .join("");
      } else {
        // Jika tidak ada data
        options += `<option value="" disabled>Tidak ada data tersedia</option>`;
      }

      // Masukkan ke semua target
      targetSelectors.forEach((selector) => $(selector).html(options));

      // Callback jika ada
      if (typeof callback === "function") callback(data);
    },
    error: function () {
      // Kalau gagal load, tetap tampilkan info di select
      targetSelectors.forEach((selector) => {
        $(selector).html(
          `<option value="" disabled>Gagal memuat data</option>`
        );
      });
      Popup.error("Gagal!", "Error memuat data", 3000);
    },
  });
}

function loadSatuan(
  url,
  targetSelectors,
  valueKey = "id",
  textKey = "name",
  placeholder = null,
  extraParams = {},
  callback
) {
  // Kalau cuma string, ubah jadi array 1 elemen
  if (!Array.isArray(targetSelectors)) {
    targetSelectors = [targetSelectors];
  }

  $.ajax({
    url: url,
    type: "GET",
    data: extraParams,
    dataType: "json",
    success: function (data) {
      let options = "";

      // Pastikan data adalah array
      if (!Array.isArray(data)) {
        data = [];
      }

      // Tambah placeholder kalau ada
      if (placeholder) {
        options += `<option value="" disabled selected>${placeholder}</option>`;
      }

      if (data.length > 0) {
        // Loop data untuk option
        options += data
          .map(
            (item) =>
              `<option value="${item[valueKey]}">${item[textKey]}</option>`
          )
          .join("");
      } else {
        // Jika tidak ada data
        options += `<option value="" disabled>Tidak Ada Satuan Tersedia</option>`;
      }

      // Masukkan ke semua target
      targetSelectors.forEach((selector) => $(selector).html(options));

      // Callback jika ada
      if (typeof callback === "function") callback(data);
    },
    error: function () {
      // Kalau gagal load, tetap tampilkan info di select
      targetSelectors.forEach((selector) => {
        $(selector).html(
          `<option value="" disabled>Gagal memuat data</option>`
        );
      });
      Popup.error("Gagal!", "Error memuat data", 3000);
    },
  });
}


function loadOptions3(
  url,
  targetSelectors,
  valueKey = "id",
  textKey = "name",
  placeholder = null,
  extraParams = {},
  callback
) {
  // Kalau cuma string, ubah jadi array 1 elemen
  if (!Array.isArray(targetSelectors)) {
    targetSelectors = [targetSelectors];
  }

  $.ajax({
    url: url,
    type: "GET",
    data: extraParams, // Data tambahan untuk request
    dataType: "json",
    success: function (data) {
      let options = "";

      // Tambah placeholder jika ada
      if (placeholder) {
        options += `<option value="1" disabled selected>${placeholder}</option>`;
      }

      // Loop data untuk option
      options += data
        .map(
          (item) =>
            `<option value="${item[valueKey]}">${item[textKey]}</option>`
        )
        .join("");

      // Masukkan ke semua target
      targetSelectors.forEach((selector) => $(selector).html(options));

      // Callback jika ada
      if (typeof callback === "function") callback();
    },
    error: function () {
      Popup.error("Gagal!", "Error memuat data", 3000);
    },
  });
}

function loadJBTN(
  url,
  targetSelectors,
  valueKey = "id",
  textKey = "name",
  callback
) {
  // Kalau cuma string, ubah jadi array 1 elemen
  if (!Array.isArray(targetSelectors)) {
    targetSelectors = [targetSelectors];
  }

  $.ajax({
    url: url,
    type: "GET",
    dataType: "json",
    success: function (data) {
      let options = data
        .map(
          (item) =>
            `<option value="${item[valueKey]}">${item[textKey]}</option>`
        )
        .join("");

      // Tambahkan opsi custom "Other"
      options += `<option value="">ALL</option>`;

      // Isi semua target select
      targetSelectors.forEach((selector) => $(selector).html(options));

      if (typeof callback === "function") callback();
    },
    error: function () {
      Popup.error("Gagal!", "Error memuat data", 3000);
    },
  });
}


function loadDualSelect(config) {
  const {
    urlFirst,
    urlSecond,
    selectFirst,
    selectSecond,
    valueKey1 = "id",
    textKey1 = "name",
    valueKey2 = "id",
    textKey2 = "name",
    callback
  } = config;

  $.ajax({
    url: urlFirst,
    dataType: "json",
    success: function (data1) {
      let options1 = data1.map(i =>
        `<option value="${i[valueKey1]}">${i[textKey1]}</option>`
      ).join("");

      $(selectFirst).html(options1);

      const value = $(selectFirst).val();
      const text  = $(selectFirst).find(":selected").text();

      $.ajax({
  url: urlSecond,
  dataType: "json",
  data: { id: value },
  success: function (data2) {
    let options2 = data2.map(i =>
      `<option value="${i[valueKey2]}">${i[textKey2]}</option>`
    ).join("");

    $(selectSecond).html(options2).trigger("change");

    
  }
});


      

    }
  });
}





function loadOptions2(
  url,
  targetSelectors,
  valueKey = "id",
  textKey = "name",
  callback
) {
  // Kalau cuma string, ubah jadi array 1 elemen
  if (!Array.isArray(targetSelectors)) {
    targetSelectors = [targetSelectors];
  }

  $.ajax({
    url: url,
    type: "GET",
    dataType: "json",
    success: function (data) {
      let options = data
        .map(
          (item) =>
            `<option value="${item[valueKey]}">${item[textKey]}</option>`
        )
        .join("");
      targetSelectors.forEach((selector) => $(selector).html(options));
      if (typeof callback === "function") callback();
    },
    error: function () {
      Popup.error("Gagal!", "Error memuat data", 3000);
    },
  });
}


function loadOptions5(
  url,
  targetSelectors,
  valueKey = "id",
  textKey = "name",
  callback
) {
  // Kalau cuma string, ubah jadi array 1 elemen
  if (!Array.isArray(targetSelectors)) {
    targetSelectors = [targetSelectors];
  }
  let options = '<option value="">ALL</option>';

  $.ajax({
    url: url,
    type: "GET",
    dataType: "json",
    success: function (data) {
      options += data
        .map(
          (item) =>
            `<option value="${item[valueKey]}">${item[textKey]}</option>`
        )
        .join("");
      targetSelectors.forEach((selector) => $(selector).html(options));
      if (typeof callback === "function") callback();
    },
    error: function () {
      Popup.error("Gagal!", "Error memuat data", 3000);
    },
  });
}

function loadOptions4(
  url,
  targetSelectors,
  valueKey = "id",
  textKey = "name",
  callback
) {
  // Kalau cuma string, ubah jadi array 1 elemen
  if (!Array.isArray(targetSelectors)) {
    targetSelectors = [targetSelectors];
  }
  let options = '<option value="">ALL</option>';

  $.ajax({
    url: url,
    type: "GET",
    dataType: "json",
    success: function (data) {
      options += data
        .map(
          (item) =>
            `<option value="${item[valueKey]}">${item[textKey]}</option>`
        )
        .join("");
      targetSelectors.forEach((selector) => $(selector).html(options));
      if (typeof callback === "function") callback();
    },
    error: function () {
      Popup.error("Gagal!", "Error memuat data", 3000);
    },
  });
}

function selectLokasi(
  targetSelectors,
  valueKey = "id",
  textKey = "name",
  callback
) {
  // Kalau cuma string, ubah jadi array 1 elemen
  if (!Array.isArray(targetSelectors)) {
    targetSelectors = [targetSelectors];
  }

  let options = '<option value="">Semua Lokasi </option>';
  $.ajax({
    url: "controller/process/getLokasi.php",
    type: "GET",
    dataType: "json",
    success: function (data) {
      if (Array.isArray(data) && data.length > 0) {
        options += data
          .map(
            (item) =>
              `<option value="${item[valueKey]}">${item[textKey]}</option>`
          )
          .join("");
      } else {
        options += "<option disabled>Tidak ada lokasi</option>";
      }

      targetSelectors.forEach((selector) => $(selector).html(options));

      if (typeof callback === "function") callback();
    },
    error: function () {
      Popup.error("Gagal!", "Error memuat data lokasi", 3000);
    },
  });
}

function selectLokasi2(
  targetSelectors,
  valueKey = "id",
  textKey = "name",
  callback
) {
  // Kalau cuma string, ubah jadi array 1 elemen
  if (!Array.isArray(targetSelectors)) {
    targetSelectors = [targetSelectors];
  }

  let options = "";
  $.ajax({
    url: "controller/process/getLokasi.php",
    type: "GET",
    dataType: "json",
    success: function (data) {
      if (Array.isArray(data) && data.length > 0) {
        options += data
          .map(
            (item) =>
              `<option value="${item[valueKey]}">${item[textKey]}</option>`
          )
          .join("");
      } else {
        options += "<option disabled>Tidak ada lokasi</option>";
      }

      targetSelectors.forEach((selector) => $(selector).html(options));

      if (typeof callback === "function") callback();
    },
    error: function () {
      Popup.error("Gagal!", "Error memuat data lokasi", 3000);
    },
  });
}

function editOptions(
  url,
  targetSelectors,
  valueKey,
  textKey,
  selectedValue = null,
  placeholder = null,
  extraParams = {},
  callback
) {
  if (!Array.isArray(targetSelectors)) {
    targetSelectors = [targetSelectors]; // Biar bisa handle 1 atau banyak selector
  }

  $.ajax({
    url: url,
    type: "GET",
    data: extraParams, // Bisa kirim parameter tambahan
    dataType: "json",
    success: function (data) {
      let options = "";

      // Tambah placeholder (optional)
      if (placeholder) {
        options += `<option value="" disabled ${
          !selectedValue ? "selected" : ""
        }>${placeholder}</option>`;
      }

      // Loop data untuk bikin option
      options += data
        .map((item) => {
          const selected = item[valueKey] == selectedValue ? "selected" : "";
          return `<option value="${item[valueKey]}" ${selected}>${item[textKey]}</option>`;
        })
        .join("");

      // Masukkan ke semua target
      targetSelectors.forEach((selector) => $(selector).html(options));

      if (typeof callback === "function") callback();
    },
    error: function () {
      Popup.error("Gagal!", `Error memuat data dari ${url}`, 3000);
    },
  });
}
