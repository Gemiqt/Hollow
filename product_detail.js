document.addEventListener("DOMContentLoaded", function () {
    const toggleSearch = document.getElementById("toggleSearch");
    const searchInput = document.querySelector(".search-input");
    toggleSearch?.addEventListener("change", function () {
        if (this.checked) {
            searchInput.style.opacity = "1";
            searchInput.style.width = "180px";
            searchInput.focus();
        } else {
            searchInput.style.opacity = "0";
            searchInput.style.width = "0px";
        }
    });
    document.addEventListener("click", function (event) {
        if (!event.target.closest(".search-wrapper")) {
            if (toggleSearch) toggleSearch.checked = false;
            if (searchInput) {
                searchInput.style.opacity = "0";
                searchInput.style.width = "0";
            }
        }
    });
    document.getElementById("open-size-chart")?.addEventListener("click", () => {
        document.getElementById("size-chart-popup").style.display = "flex";
    });
    document.getElementById("close-size-chart")?.addEventListener("click", () => {
        document.getElementById("size-chart-popup").style.display = "none";
    });
    const qtyInput = document.getElementById("qtyInput");
    if (!qtyInput) return;

    const maxStock = parseInt(qtyInput.max);

    document.getElementById("plusQty")?.addEventListener("click", () => {
        let current = parseInt(qtyInput.value);
        if (current < maxStock) qtyInput.value = current + 1;
    });
    document.getElementById("minusQty")?.addEventListener("click", () => {
        let current = parseInt(qtyInput.value);
        if (current > 1) qtyInput.value = current - 1;
    });
    qtyInput.addEventListener("input", function () {
        let val = parseInt(this.value);
        if (val > maxStock) this.value = maxStock;
        if (val < 1 || isNaN(val)) this.value = 1;
    });
});