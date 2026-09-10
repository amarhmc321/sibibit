/**
 * renderRowsWithSkeleton: Ganti skeleton row dengan data real + fade-in animasi
 * @param {jQuery} $tbody - tbody tabel
 * @param {Array} rowsData - array data dari server
 * @param {function} rowCallback - function(item, index) => row HTML/jQuery
 * @param {number} skeletonDelay - base delay per row (ms)
 * @param {function} onComplete - callback setelah semua row selesai di-render
 */
function renderRowsWithSkeleton($tbody, rowsData, rowCallback, skeletonDelay = 100, onComplete = () => {}) {
  const $skeletonRows = $tbody.find("tr.tr-placeholder");
  const maxRows = Math.max($skeletonRows.length, rowsData.length);

  for (let i = 0; i < maxRows; i++) {
    if (!rowsData[i]) continue;

    const delay = i * skeletonDelay + Math.floor(Math.random() * 50); // ±50ms agar natural

    setTimeout(() => {
      const rowHtml = $(rowCallback(rowsData[i], i)).addClass("fade-in");

      if ($skeletonRows[i]) {
        $($skeletonRows[i]).fadeOut(100, function () {
          $(this).replaceWith(rowHtml);
          setTimeout(() => rowHtml.addClass("show"), 20);
        });
      } else {
        $tbody.append(rowHtml);
        setTimeout(() => rowHtml.addClass("show"), 20);
      }

      // callback ketika semua row selesai
      if (i === maxRows - 1) {
        setTimeout(() => onComplete(), 200);
      }
    }, delay);
  }
}



  function getPeriode2526() {
    const today = new Date();
    const day = today.getDate();

    let start, end;

    if (day < 26) {
      start = new Date(today.getFullYear(), today.getMonth() - 1, 26);
      end   = new Date(today.getFullYear(), today.getMonth(), 25);
    } else {
      start = new Date(today.getFullYear(), today.getMonth(), 26);
      end   = new Date(today.getFullYear(), today.getMonth() + 1, 25);
    }

    const formatter = new Intl.DateTimeFormat("id-ID", {
      day: "numeric",
      month: "long",
      year: "numeric"
    });

    return {
      start: formatter.format(start),
      end: formatter.format(end)
    };
  }



  function scheduleNextUpdate() {
    const now = new Date();
    const nextMidnight = new Date(
      now.getFullYear(),
      now.getMonth(),
      now.getDate() + 1,
      0, 0, 0, 0
    );

    const msUntilMidnight = nextMidnight - now;

    setTimeout(function () {
      updatePeriodeText();
      scheduleNextUpdate(); // ulangi untuk hari berikutnya
    }, msUntilMidnight);
  }

  
function initTableEvents(tableSelector, options) {
    const $table = $(tableSelector);

    // Klik baris (edit)
      $table.off('click', 'tr.edit').on('click', 'tr.edit', function(e) {
        if ($(e.target).closest('.btn').length) return;

        const id = $(this).data('id');
        const status = parseInt($(this).data('status'), 10) || 0;

        if (options.onRowClick) {
            options.onRowClick(id, status, this);
        }
    });
    // Tombol edit
    $table.off('click', '.btn-edit').on('click', '.btn-edit', function(e) {
        e.stopPropagation();
        if (options.onEdit) {
            options.onEdit($(this).closest('tr').data('id'), this);
        }
    });

    // Tombol delete
    $table.off('click', '.btn-delete').on('click', '.btn-delete', function(e) {
        e.stopPropagation();
        if (options.onDelete) {
            options.onDelete($(this).closest('tr').data('id'), this);
        }
    });

    $table.off('click', '.bukti').on('click', '.bukti', function(e) {
        e.stopPropagation();
    });

    $table.off('click', '.btn-acc').on('click', '.btn-acc', function(e) {
        e.stopPropagation();
        if (options.onAcc) {
            options.onAcc($(this).closest('tr').data('id'), this);
        }
    });

    $table.off('click', '.btn-reject').on('click', '.btn-reject', function(e) {
        e.stopPropagation();
        if (options.onReject) {
            options.onReject($(this).closest('tr').data('id'), this);
        }
    });

    $table.off('click', '.btn-cetak').on('click', '.btn-cetak', function(e) {
        e.stopPropagation();
        if (options.onCetak) {
            options.onCetak($(this).closest('tr').data('id'), this);
        }
    });
}

function tombolAksi(role, item, title) {
    const status = parseInt($('#filter-status').val()); // kalau mau dipakai
    let aksi = "-";

    const btn = {
        edit:   `<button class="btn btn-secondary btn-sm btn-edit"data-bs-toggle="tooltip2" title="Edit ${title}">
                <i class="fa fa-edit"></i></button>`,
        delete: `<button class="btn btn-danger btn-sm btn-delete" data-bs-toggle="tooltip2" title="Hapus ${title}">
                <i class="fa fa-trash"></i></button>`,
        cetak:  `<button class="btn btn-success btn-sm btn-cetak" data-bs-toggle="tooltip2" title="Cetak ${title}">
                <i class="fa fa-print"></i></button>`,
        acc:    `<button class="btn btn-secondary btn-sm btn-acc" data-bs-toggle="tooltip2" title="ACC ${title}">
                <i class="fa fa-check"></i></button>`,
        reject: `<button class="btn btn-danger btn-sm btn-reject" data-bs-toggle="tooltip2" title="Tolak ${title}">
                <i class="fa fa-close"></i></button>`
    };

    const actionsMap = {
        1: { // role 1
            0: [btn.edit, btn.delete],
            1: [btn.cetak],
            2: [btn.delete]
        },
        2: { // role 2
            0: [btn.acc, btn.reject],
            1: [btn.cetak, btn.reject],
            2: [btn.acc, , btn.delete]
        },
        3: { // role 1
            0: [btn.edit, btn.delete],
            1: [btn.cetak],
            2: [btn.delete]
        },

         4: { // role 1
            0: [btn.edit, btn.delete],
            1: [],
            2: [btn.delete]
        },


    };

    if (actionsMap[role] && actionsMap[role][item]) {
        aksi = actionsMap[role][item].join(" ");
    }

    return aksi;
}

function tombolAksi2(role, item, title) {
    const status = parseInt($('#filter-status').val()); // kalau mau dipakai
    let aksi = "-";

    const btn = {
        edit:   `<button class="btn btn-secondary btn-sm btn-edit"data-bs-toggle="tooltip2" title="Edit ${title}">
                <i class="fa fa-edit"></i></button>`,
        delete: `<button class="btn btn-danger btn-sm btn-delete" data-bs-toggle="tooltip2" title="Hapus ${title}">
                <i class="fa fa-trash"></i></button>`,
        cetak:  `<button class="btn btn-success btn-sm btn-cetak" data-bs-toggle="tooltip2" title="Cetak ${title}">
                <i class="fa fa-print"></i></button>`,
        acc:    `<button class="btn btn-secondary btn-sm btn-acc" data-bs-toggle="tooltip2" title="ACC ${title}">
                <i class="fa fa-check"></i></button>`,
        reject: `<button class="btn btn-danger btn-sm btn-reject" data-bs-toggle="tooltip2" title="Tolak ${title}">
                <i class="fa fa-close"></i></button>`
    };

    const actionsMap = {
        1: { // role 1
            0: [btn.edit, btn.delete],
            2: [btn.delete]
        },
        2: { // role 2
            0: [btn.acc, btn.reject],
            1: [btn.delete, btn.reject],
            2: [btn.acc, , btn.delete]
        }

    };

    if (actionsMap[role] && actionsMap[role][item]) {
        aksi = actionsMap[role][item].join(" ");
    }

    return aksi;
}

function tombolAksiGrub(role, item, title) {
    const status = parseInt($('#filter-status').val()); 
    const filter_lokasi_s = $('#filter-lokasi-s').val() || 0;
    let aksi = "-";

    const btn = {
        edit:   `<button class="btn btn-secondary btn-sm btn-edit" data-bs-toggle="tooltip2" title="Edit ${title}">
                    <i class="fa fa-edit"></i></button>`,
        delete: `<button class="btn btn-danger btn-sm btn-delete" data-bs-toggle="tooltip2" title="Hapus ${title}">
                    <i class="fa fa-trash"></i></button>`,
        cetak:  `<button class="btn btn-success btn-sm btn-cetak" data-bs-toggle="tooltip2" title="Cetak ${title}">
                    <i class="fa fa-print"></i></button>`,
        acc:    `<button class="btn btn-secondary btn-sm btn-acc" data-bs-toggle="tooltip2" title="ACC ${title}">
                    <i class="fa fa-check"></i></button>`,
        reject: `<button class="btn btn-danger btn-sm btn-reject" data-bs-toggle="tooltip2" title="Tolak ${title}">
                    <i class="fa fa-close"></i></button>`,
        none: `-`,
    };

    // Struktur: role → item → lokasi
    const actionsMap = {
        1: { // role 1
            0: {
                0: [btn.edit, btn.delete],   
                1: [btn.acc, btn.edit, btn.delete],               
                2: [btn.delete]             
            },
            1: {
                0: [btn.cetak],
                1: [btn.cetak, btn.delete],
                2: [btn.cetak, btn.delete]
            }
        },
        2: { 
            0: {
                0: [btn.edit],
                1: [btn.acc],
                2: [btn.reject]
            },
            1: {
                0: [btn.cetak, btn.reject],
                1: [btn.cetak],
                2: [btn.reject, btn.delete]
            }
        },
        3: { 
            0: {
                0: [btn.none],
                1: [btn.cetak],
                2: [btn.none]
            },
            1: {
                0: [btn.cetak],
                1: [btn.cetak],
                2: [btn.none]
            }
        }

         
    };

    if (actionsMap[role] && actionsMap[role][item] && actionsMap[role][item][filter_lokasi_s]) {
        aksi = actionsMap[role][item][filter_lokasi_s].join(" ");
    }

    return aksi;
}

// Fungsi bantu
function getInitials(name) {
  let words = name.trim().split(/\s+/);
  let initials = words.map(w => w[0].toUpperCase()).join('');
  return initials.substring(0, 2);
}

function getColorFromName(name) {
  const colors = ['#1abc9c','#2ecc71','#3498db','#9b59b6','#e67e22','#e74c3c','#f1c40f','#34495e'];
  let hash = 0;
  for (let i = 0; i < name.length; i++) {
    hash = name.charCodeAt(i) + ((hash << 5) - hash);
  }
  return colors[Math.abs(hash) % colors.length];
}

// Fungsi generate avatar
function generateAvatar($el){
  $el.each(function(){
    let $this = $(this);
    if ($this.data('avatar-init')) return; // skip kalau sudah diinit

    let nama = $this.data('nama') || '';
    let foto = $this.data('foto');
    let content = '';

    if (!foto || foto === 'default.png') {
      let initials = getInitials(nama);
      let color = getColorFromName(nama);
      content = '<span class="avatar-circle" style="background-color:' + color + '">' + initials + '</span>';
    } else {
      content = '<img src="img/users/' + foto + '" alt="User Profile">';
    }

    // Tambahkan nama asli setelah avatar
    if ($this.hasClass('user-avatar-sm')) {
      $this.html(content + ' <span class="avatar-name">' + nama + '</span>');
    } else {
      $this.html(content);
    }

    $this.data('avatar-init', true);
  });
}


// Jalankan
$(document).ready(function(){
  generateAvatar($('.user-avatar-sm, .user-avatar-lg'));

  // Observer untuk elemen baru
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      $(mutation.addedNodes).find('.user-avatar-sm, .user-avatar-lg')
        .addBack('.user-avatar-sm, .user-avatar-lg')
        .each(function(){
          generateAvatar($(this));
        });
    });
  });

  observer.observe(document.body, { childList: true, subtree: true });

  const $html = $("html");
  const $btn = $("#toggleTheme i");
  const $text = $("#toggleTheme span");

  // Ambil tema dari localStorage
  const savedTheme = localStorage.getItem("theme") || "light";
  applyTheme(savedTheme);

  // Fungsi apply theme
  function applyTheme(theme) {
    $html.attr("data-bs-theme", theme);

    // ganti icon sesuai tema
    if (theme === "dark") {
      $btn.removeClass("fa-moon").addClass("fa-sun");
      $text.text("Mode terang");
    } else {
      $btn.removeClass("fa-sun").addClass("fa-moon");
      $text.text("Mode gelap");
    }

    localStorage.setItem("theme", theme);
  }

  // Klik tombol → toggle light/dark
  $("#toggleTheme").on("click", function () {
    const current = $html.attr("data-bs-theme");
    const newTheme = current === "dark" ? "light" : "dark";
    applyTheme(newTheme);
  });



});


    