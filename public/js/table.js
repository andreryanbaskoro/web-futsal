window.Table = function (data, options = {}) {
    return {
        rows: Array.isArray(data) ? data : data?.data ?? [],

        // CONFIG (bisa dioverride per tabel)
        search: "",
        perPage: options.perPage ?? 10,
        currentPage: 1,
        searchKeys: options.searchKeys ?? [],
        deleteUrl: options.deleteUrl ?? null,
        sortKey: null, // Track the column being sorted
        sortOrder: "asc", // Track the sort order ('asc' or 'desc')

        // Compute filtered rows
        get filtered() {
            let filteredRows = this.rows;

            // Filter by search term
            if (this.search) {
                const keyword = this.search.toLowerCase();
                filteredRows = filteredRows.filter((item) =>
                    Object.values(item).some(
                        (value) =>
                            value &&
                            value.toString().toLowerCase().includes(keyword)
                    )
                );
            }

            // Sort the filtered rows
            if (this.sortKey) {
                filteredRows = filteredRows.sort((a, b) => {
                    const aValue = a[this.sortKey] ?? "";
                    const bValue = b[this.sortKey] ?? "";

                    // Handle sorting based on the order
                    if (this.sortOrder === "asc") {
                        return aValue
                            .toString()
                            .localeCompare(bValue.toString());
                    } else {
                        return bValue
                            .toString()
                            .localeCompare(aValue.toString());
                    }
                });
            }

            return filteredRows;
        },

        get totalPages() {
            return Math.max(1, Math.ceil(this.filtered.length / this.perPage));
        },

        get paginated() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filtered.slice(start, start + this.perPage);
        },

        get displayedPages() {
            const total = this.totalPages;
            const current = this.currentPage;
            const range = [];

            for (let i = 1; i <= total; i++) {
                if (
                    i === 1 ||
                    i === total ||
                    (i >= current - 1 && i <= current + 1)
                ) {
                    range.push(i);
                } else if (range[range.length - 1] !== "...") {
                    range.push("...");
                }
            }
            return range;
        },

        prev() {
            if (this.currentPage > 1) this.currentPage--;
        },

        next() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },

        goToPage(page) {
            if (page === "...") return;
            const p = Number(page);
            if (!isNaN(p) && p >= 1 && p <= this.totalPages) {
                this.currentPage = p;
            }
        },

        // Toggle sorting for a given column and set the icon dynamically
        sortBy(column) {
            if (this.sortKey === column) {
                // Toggle the order if already sorted by this column
                this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
            } else {
                this.sortKey = column;
                this.sortOrder = "asc"; // default to ascending order
            }

            // Update the sorting arrows dynamically
            this.updateSortingIcons();
        },

        // Dynamically inject sorting icons into table headers
        updateSortingIcons() {
            // Remove any existing arrows in the table headers
            document.querySelectorAll(".sorting-arrow").forEach((arrow) => {
                arrow.remove();
            });

            // Create and add new arrow icons based on sort order
            const th = document.querySelector(
                `th[data-sort="${this.sortKey}"]`
            );

            if (th) {
                const arrow = document.createElement("span");
                arrow.classList.add("sorting-arrow");
                arrow.innerHTML = this.sortOrder === "asc" ? "↑" : "↓";
                th.appendChild(arrow);
            }
        },

        showDeleteModal(event) {
            // simpan form yang diklik ke store
            Alpine.store("modal").deleteForm = event.target;
            Alpine.store("modal").show("delete");
        },
    };
};
