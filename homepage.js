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
const btnCart = document.getElementById("btn-cart");
if (btnCart) btnCart.addEventListener("click", () => {
  window.location.href = "cart.php";
});
const btnLogin = document.getElementById("btn-login");
if (btnLogin) btnLogin.addEventListener("click", () => {
  window.location.href = "login.php";
});
const dropdownParent = document.querySelector(".dropdown-parent");
const dropdownMenu = document.querySelector(".dropdown");
if (dropdownParent && dropdownMenu) {
  dropdownParent.addEventListener("click", function (e) {
    e.preventDefault();
    dropdownMenu.classList.toggle("open");
  });
  document.addEventListener("click", function (e) {
    if (!dropdownParent.contains(e.target)) {
      dropdownMenu.classList.remove("open");
    }
  });
}
window.onload = function () {
  let slideIndex = 0;
  const slides = document.querySelectorAll(".slide");
  const nextBtn = document.querySelector(".next");
  const prevBtn = document.querySelector(".prev");

  if (slides.length === 0) {
    console.error("⚠ Slider tidak ditemukan di HTML!");
    return;
  }
  function showSlide(n) {
    slides.forEach((slide, i) => {
      slide.classList.remove("active");
      if (i === n) slide.classList.add("active");
    });
  }
  function nextSlide() {
    slideIndex = (slideIndex + 1) % slides.length;
    showSlide(slideIndex);
  }
  function prevSlide() {
    slideIndex = (slideIndex - 1 + slides.length) % slides.length;
    showSlide(slideIndex);
  }
  nextBtn.addEventListener("click", nextSlide);
  prevBtn.addEventListener("click", prevSlide);
  setInterval(nextSlide, 4000);
};
let slider = document.getElementById('reviewSlider');
let scrollX = 0;
setInterval(() => {
    scrollX += 330;
    if (scrollX >= slider.scrollWidth - slider.clientWidth) {
        scrollX = 0; 
    }
    slider.scrollTo({
        left: scrollX,
        behavior: "smooth"
    });
}, 3000);