const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');
registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});
loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});
document.getElementById("signinBtn").addEventListener("click", function (e) {
    e.preventDefault(); 
    window.location.href = "homepage.php";
});