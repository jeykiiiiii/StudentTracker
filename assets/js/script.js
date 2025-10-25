function toggleNav() {
    const sideNav = document.getElementById('sideNav');
    sideNav.style.width = sideNav.style.width === '250px' ? '0' : '250px';
}

document.addEventListener("DOMContentLoaded", () => {
    const loginBtn = document.getElementById("login-btn");
    const loginModal = document.getElementById("login-modal");
    const closeLoginModal = document.getElementById("close-modal");

    const registerBtn = document.getElementById("register-btn");
    const registerModal = document.getElementById("register-modal");
    const closeRegisterModal = document.getElementById("close-register-modal");

    loginBtn.addEventListener("click", (e) => {
        e.preventDefault();
        loginModal.classList.add("active");
    });

    closeLoginModal.addEventListener("click", () => {
        loginModal.classList.remove("active");
    });

    registerBtn.addEventListener("click", (e) => {
        e.preventDefault();
        registerModal.classList.add("active");
    });

    closeRegisterModal.addEventListener("click", () => {
        registerModal.classList.remove("active");
    });

    window.addEventListener("click", (event) => {
        if (event.target === loginModal) {
            loginModal.classList.remove("active");
        }
        if (event.target === registerModal) {
            registerModal.classList.remove("active");
        }
    });

    function togglePassword(inputId, checkboxId) {
        const passwordInput = document.getElementById(inputId);
        const checkbox = document.getElementById(checkboxId);

        checkbox.addEventListener("change", () => {
            passwordInput.type = checkbox.checked ? "text" : "password";
        });
    }

    togglePassword("login-password", "show-login-password");
    togglePassword("register-password", "show-register-password");
});


function confirmDelete() {
    const confirmation = confirm("Are you sure you want to delete this assessment?");
    if (confirmation) {
        document.getElementById('delete-assessment-form').submit();
    }
}

function confirmAdd() {
    const confirmation = confirm("Are you sure you want to add this assessment?");
    if (confirmation) {
        document.getElementById('add-assessment-form').submit();
    }
}