const toggleSearch = document.getElementById("toggleSearch");
const searchInput = document.querySelector(".search-input");
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
})
const btnCart = document.getElementById("btn-cart");
btnCart.addEventListener("click", function () {
  window.location.href = "cart.php";
});
const btnLogin = document.getElementById("btn-login");
btnLogin.addEventListener("click", function () {
  window.location.href = "login.php";
});
const dropdownParent = document.querySelector(".dropdown-parent");
const dropdownMenu = document.querySelector(".dropdown");
dropdownParent.addEventListener("click", function (e) {
  e.preventDefault();
  dropdownMenu.classList.toggle("open");
});
document.addEventListener("click", function (e) {
  if (!dropdownParent.contains(e.target)) {
    dropdownMenu.classList.remove("open");
  }
});
document.addEventListener("DOMContentLoaded", updateCartCount);
document.getElementById("filter-cat")?.addEventListener("change", function() {
  let value = this.value;
  let cards = document.querySelectorAll(".product-card");
  cards.forEach(card => {
    if (value === "all" || card.dataset.category === value) {
      card.style.display = "block";
    } else {
      card.style.display = "none";
    }
  });
});
function addToCart(id) {
  window.location.href = "add_to_cart.php?id=" + id;
}