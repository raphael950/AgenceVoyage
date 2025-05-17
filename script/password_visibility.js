document.addEventListener("DOMContentLoaded", () => {
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#pass");

    if (togglePassword && password) {
        togglePassword.addEventListener("click", function () {
            const isPassword = password.getAttribute("type") === "password";
            password.setAttribute("type", isPassword ? "text" : "password");

            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    }
});
