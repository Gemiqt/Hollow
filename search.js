const toggleSearch = document.getElementById("toggleSearch");
const searchInput = document.querySelector(".search-input");
if (toggleSearch && searchInput) {
  toggleSearch.addEventListener("change", function () {
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
      toggleSearch.checked = false;
      searchInput.style.opacity = "0";
      searchInput.style.width = "0";
    }
  });
}