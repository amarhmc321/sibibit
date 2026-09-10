
// TablePaginator class (tetap utuh seperti aslinya)
class TablePaginator {
  constructor(selector, groupSize = 1) {
    this.$table = $(selector);
    this.$searchInput = $("#searchInput");
    this.$clearBtn = $("#clearBtn");
    this.$pagination = $("#pagination");
    this.$info = $("#info");
    this.$thead = this.$table.find("thead");
    this.$tbody = this.$table.find("tbody");
    this.rowsPerPage = parseInt($("#rowsPerPage").val()) || 15;
    
    this.storageKey = `tablePaginator_${selector.replace(/[^a-zA-Z0-9]/g, '_')}`;
    const savedPage = localStorage.getItem(this.storageKey);
    this.currentPage = savedPage ? parseInt(savedPage) : 1;
    
    this.currentSort = { index: null, asc: true };
    this.groupSize = groupSize;
    this.originalData = this._initializeData();
    this.$headers = this.$thead.find("th");
    this.isExporting = false;
    this.preExportState = null;

    this._setupEventHandlers();
    this.applyPagination();
  }

  _initializeData() {
    const $rows = this.$tbody.find("tr");
    if (this.groupSize === 1) return $rows.toArray();

    const groups = [];
    for (let i = 0; i < $rows.length; i += this.groupSize) {
      groups.push($rows.slice(i, i + this.groupSize));
    }
    return groups;
  }

  _setupEventHandlers() {
    // Input search handler
    this.$searchInput.on("input", () => {
      this.$clearBtn.toggle(this.$searchInput.val().length > 0);
      this.currentPage = 1;
      this.applyPagination();
    });

    // Clear button handler
    this.$clearBtn.on("click", () => {
      this.$searchInput.val("").focus();
      this.$clearBtn.hide();
      this.currentPage = 1;
      this.applyPagination();
    });

    // Sorting handler
    this.$thead.on("click", "th", (e) => {
      const $clickedHeader = $(e.currentTarget);
      const index = this.$headers.index($clickedHeader);
      this._updateSortState(index, $clickedHeader);
      this.currentPage = 1;
      this.applyPagination();
    });

    // Rows per page handler
    $("#rowsPerPage").on("change", () => {
      this.rowsPerPage = parseInt($("#rowsPerPage").val());
      this.currentPage = 1;
      this.applyPagination();
    });

    // Pagination handler - dengan penyimpanan ke localStorage
    this.$pagination.on("click", "button:not(.disabled)", (e) => {
      this.currentPage = parseInt($(e.currentTarget).data("page"));
      // Menyimpan currentPage ke localStorage
      localStorage.setItem(this.storageKey, this.currentPage);
      this.applyPagination();
    });
  }

  _updateSortState(index, $clickedHeader) {
    if (this.currentSort.index === index) {
      this.currentSort.asc = !this.currentSort.asc;
    } else {
      this.currentSort = { index, asc: true };
    }

    // Clear all sorting indicators
    this.$headers.removeClass("sorted-asc sorted-desc");

    // Apply correct indicator to clicked header
    $clickedHeader.addClass(
      this.currentSort.asc ? "sorted-asc" : "sorted-desc"
    );
  }

  applyPagination() {
    const searchText = this.$searchInput.val().toLowerCase();
    let filteredData = this._filterData(searchText);
    filteredData = this._sortData(filteredData);
    this._displayData(filteredData);
  }

  _filterData(searchText) {
    return this.originalData.filter((item) => {
      if (this.groupSize === 1) {
        return $(item).text().toLowerCase().includes(searchText);
      } else {
        // Search only in the first row of each group
        return $(item[0]).text().toLowerCase().includes(searchText);
      }
    });
  }

  _sortData(data) {
    if (this.currentSort.index === null) return data;

    return [...data].sort((a, b) => {
      const getValue = (item) => {
        try {
          if (this.groupSize === 1) {
            return $(item)
              .children()
              .eq(this.currentSort.index)
              .text()
              .trim()
              .toLowerCase();
          } else {
            // Get value from first row of the group
            return $(item[0])
              .children()
              .eq(this.currentSort.index)
              .text()
              .trim()
              .toLowerCase();
          }
        } catch {
          return "";
        }
      };

      const aVal = getValue(a);
      const bVal = getValue(b);

      // Handle numeric comparison
      if ($.isNumeric(aVal) && $.isNumeric(bVal)) {
        const numA = parseFloat(aVal);
        const numB = parseFloat(bVal);
        return this.currentSort.asc ? numA - numB : numB - numA;
      }

      // Handle string comparison
      return this.currentSort.asc
        ? aVal.localeCompare(bVal)
        : bVal.localeCompare(aVal);
    });
  }

  _displayData(data) {
    const totalItems = data.length;
    const totalPages = Math.ceil(totalItems / this.rowsPerPage) || 1;
    this.currentPage = Math.min(Math.max(1, this.currentPage), totalPages);
    const start = (this.currentPage - 1) * this.rowsPerPage;
    const end = start + this.rowsPerPage;
    const itemsToShow = data.slice(start, end);

    this.$tbody.empty();
    if (this.groupSize === 1) {
      itemsToShow.forEach((row) => this.$tbody.append(row));
    } else {
      itemsToShow.forEach((group) => {
        for (let i = 0; i < group.length; i++) {
          this.$tbody.append(group[i]);
        }
      });
    }

    this.$info.text(
      `Menampilkan ${Math.min(end, totalItems)} dari ${totalItems} data`
    );
    this._renderPagination(totalPages);
  }

  _renderPagination(totalPages) {
    const maxButtons = 5;
    const half = Math.floor(maxButtons / 2);
    let startPage = Math.max(1, this.currentPage - half);
    let endPage = Math.min(totalPages, this.currentPage + half);

    if (this.currentPage <= half) endPage = Math.min(totalPages, maxButtons);
    if (this.currentPage > totalPages - half)
      startPage = Math.max(1, totalPages - maxButtons + 1);

    let paginationHtml = `
            <button class="btn btn-sm ${
              this.currentPage === 1 ? "btn-secondary disabled" : "btn-primary"
            }" 
                data-page="${this.currentPage - 1}">‹
            </button> `;

    if (startPage > 1) {
      paginationHtml += `<button class="btn btn-sm btn-primary" data-page="1">1</button>`;
      if (startPage > 2) paginationHtml += `<span class="mx-1">...</span>`;
    }

    for (let i = startPage; i <= endPage; i++) {
      paginationHtml += `
                <button class="btn btn-sm ${
                  i === this.currentPage ? "btn-outline-primary" : "btn-primary"
                }" 
                    data-page="${i}">${i}
                </button> `;
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1)
        paginationHtml += `<span class="mx-1">...</span>`;
      paginationHtml += `<button class="btn btn-sm btn-primary" data-page="${totalPages}">${totalPages}</button>`;
    }

    paginationHtml += `
            <button class="btn btn-sm ${
              this.currentPage === totalPages
                ? "btn-secondary disabled"
                : "btn-primary"
            }" 
                data-page="${this.currentPage + 1}">›
            </button>`;

    this.$pagination.html(paginationHtml);
  }

  // Save state before export
  saveState() {
    this.preExportState = {
      currentPage: this.currentPage,
      rowsPerPage: this.rowsPerPage,
      searchText: this.$searchInput.val(),
      sort: { ...this.currentSort },
    };
  }

  // Restore state after export
  restoreState() {
    if (!this.preExportState) return;

    this.currentPage = this.preExportState.currentPage;
    this.rowsPerPage = this.preExportState.rowsPerPage;
    this.$searchInput.val(this.preExportState.searchText);
    this.currentSort = { ...this.preExportState.sort };
    this.$clearBtn.toggle(this.preExportState.searchText.length > 0);

    // Update UI sort indicator
    this.$headers.removeClass("sorted-asc sorted-desc");
    if (this.currentSort.index !== null) {
      const $header = this.$headers.eq(this.currentSort.index);
      $header.addClass(this.currentSort.asc ? "sorted-asc" : "sorted-desc");
    }

    this.preExportState = null;
  }

  // Temporarily disable pagination for export
  disablePagination() {
    this.isExporting = true;
    this.saveState();

    // Show all filtered data
    const searchText = this.$searchInput.val().toLowerCase();
    let filteredData = this._filterData(searchText);
    filteredData = this._sortData(filteredData);

    this.$tbody.empty();
    if (this.groupSize === 1) {
      filteredData.forEach((row) => this.$tbody.append(row));
    } else {
      filteredData.forEach((group) => {
        for (let i = 0; i < group.length; i++) {
          this.$tbody.append(group[i]);
        }
      });
    }

    this.$info.text(
      `Menampilkan semua ${filteredData.length} data (mode ekspor)`
    );
    this.$pagination.empty();
  }

  // Re-enable pagination after export
  enablePagination() {
    this.isExporting = false;
    this.restoreState();
    this.applyPagination();
  }
}

// Initialize normal table pagination
function paginateTable(selector) {
  const paginator = new TablePaginator(selector, 1);
  // $(selector).data('paginator', paginator);
}

// Initialize grouped table pagination (3 rows per group)
function paginateTable2(selector) {
  const paginator = new TablePaginator(selector, 3);
  $(selector).data("paginator", paginator);
}