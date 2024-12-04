
        function toggleForms() {
            const registrationForm = document.getElementById('registration-form');
            const loginForm = document.getElementById('login-form');
            if (registrationForm.style.display === "none") {
                registrationForm.style.display = "block";
                loginForm.style.display = "none";
            } else {
                registrationForm.style.display = "none";
                loginForm.style.display = "block";
            }
        }

        