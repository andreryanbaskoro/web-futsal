window.Table = function (data, options = {}) {
    return {
        rows: Array.isArray(data) ? data : (data?.data ?? []),

        // CONFIG
        search: "",
        perPage: options.perPage ?? 10,
        currentPage: 1,
        searchKeys: options.searchKeys ?? [],
        deleteUrl: options.deleteUrl ?? null,
        sortKey: null,
        sortOrder: "asc",

        // Ambil nilai nested object, support 'a.b.c'
        getValue(item, key) {
            if (!key) return "";
            return (
                key.split(".").reduce((o, k) => {
                    if (o === null || o === undefined) return "";
                    return o[k];
                }, item) ?? ""
            );
        },

        // INIT: register watchers (Alpine.js)
        init() {
            // reset page saat search berubah
            if (typeof this.$watch === "function") {
                this.$watch("search", (val) => {
                    this.currentPage = 1;
                });

                // jika jumlah filtered berubah (mis. setelah search/sort), pastikan currentPage valid
                this.$watch(
                    () => this.filtered.length,
                    () => {
                        // jika currentPage melebihi totalPages, set ke halaman terakhir yang ada (atau 1)
                        if (this.currentPage > this.totalPages) {
                            this.currentPage = Math.max(1, this.totalPages);
                        }
                    },
                );

                // jika rows (data sumber) berubah, reset ke page 1 supaya konsisten
                this.$watch("rows", () => {
                    this.currentPage = 1;
                });
            }
        },

        // FILTERED + SEARCH + SORT
        get filtered() {
            let filteredRows = [...this.rows];

            // SEARCH
            if (this.search && this.search.trim() !== "") {
                const keyword = this.search.toLowerCase();
                const keys = this.searchKeys.length
                    ? this.searchKeys
                    : Object.keys(this.rows[0] ?? {});
                filteredRows = filteredRows.filter((item) =>
                    keys.some((key) => {
                        const val = this.getValue(item, key);
                        return (
                            val !== null &&
                            val !== undefined &&
                            val.toString().toLowerCase().includes(keyword)
                        );
                    }),
                );
            }

            // SORT
            if (this.sortKey) {
                filteredRows.sort((a, b) => {
                    let aVal = this.getValue(a, this.sortKey);
                    let bVal = this.getValue(b, this.sortKey);

                    // jika sortKey 'tanggal', convert ke Date
                    if (this.sortKey === "tanggal") {
                        aVal = new Date(aVal);
                        bVal = new Date(bVal);
                    }

                    let cmp;
                    if (aVal instanceof Date && bVal instanceof Date) {
                        cmp = aVal - bVal;
                    } else {
                        cmp = aVal
                            .toString()
                            .localeCompare(bVal.toString(), undefined, {
                                numeric: true,
                                sensitivity: "base",
                            });
                    }

                    return this.sortOrder === "asc" ? cmp : -cmp;
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
            if (!isNaN(p) && p >= 1 && p <= this.totalPages)
                this.currentPage = p;
        },

        // SORT HANDLER
        sortBy(column) {
            // reset page saat sort berubah (lebih konsisten untuk user)
            this.currentPage = 1;

            if (this.sortKey === column)
                this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
            else {
                this.sortKey = column;
                this.sortOrder = "asc";
            }
            this.updateSortingIcons();
        },

        updateSortingIcons() {
            document
                .querySelectorAll(".sorting-arrow")
                .forEach((el) => el.remove());
            const th = document.querySelector(
                `th[data-sort="${this.sortKey}"]`,
            );
            if (th) {
                const arrow = document.createElement("span");
                arrow.classList.add("sorting-arrow", "ml-1");
                arrow.innerHTML = this.sortOrder === "asc" ? " ▲" : " ▼";
                th.appendChild(arrow);
            }
        },

        // DELETE MODAL
        showDeleteModal(event) {
            Alpine.store("modal").deleteForm = event.target;
            Alpine.store("modal").show("delete");
        },
    };
};
