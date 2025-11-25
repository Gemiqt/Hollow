let cart = JSON.parse(localStorage.getItem("cart")) || [];
const cartItemsDiv = document.getElementById("cart-items");
const cartTotal = document.getElementById("cart-total");
function renderCart() {
  cartItemsDiv.innerHTML = "";
  if (cart.length === 0) {
    cartItemsDiv.innerHTML = "<p>Your cart is empty, please add products to your cart.</p>";
    cartTotal.textContent = "Rp0";
    return;
  }
  let total = 0;
  cart.forEach((item, index) => {
    total += item.price * item.qty;
    cartItemsDiv.innerHTML += `
      <div class="cart-item">
        <img src="${item.image}" alt="">
        <div class="item-info">
          <h3>${item.name}</h3>
          <p>Rp${item.price.toLocaleString()}</p>
          <div class="qty-control">
              <button onclick="decreaseQty(${index})">-</button>
              <span>${item.qty}</span>
              <button onclick="increaseQty(${index})">+</button>
          </div>
        </div>
        <button class="remove-btn" onclick="removeItem(${index})">Hapus</button>
      </div>
    `;
  });
  cartTotal.textContent = "Rp" + total.toLocaleString();
}
function increaseQty(index) {
  cart[index].qty += 1;
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
  renderCart();
}
function decreaseQty(index) {
  if (cart[index].qty > 1) {
    cart[index].qty -= 1;
  } else {
    cart.splice(index, 1);
  }
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
  renderCart();
}
function removeItem(index) {
  cart.splice(index, 1);
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
  renderCart();
}
renderCart();