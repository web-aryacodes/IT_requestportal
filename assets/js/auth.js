document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('passwordToggle');
    const password = document.getElementById('password');

    if (toggle && password) {
        toggle.addEventListener('click', () => {
            const visible = password.type === 'text';

            password.type = visible ? 'password' : 'text';
            toggle.textContent = visible ? '👁' : '🙈';
            toggle.setAttribute(
                'aria-label',
                visible ? 'Show password' : 'Hide password'
            );
        });
    }

    const loginForm = document.getElementById('loginForm');

    if (loginForm) {
        loginForm.addEventListener('submit', event => {
            let valid = true;

            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const role = document.getElementById('role');

            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const roleError = document.getElementById('roleError');

            emailError.textContent = '';
            passwordError.textContent = '';
            roleError.textContent = '';

            if (!email.value.trim()) {
                emailError.textContent = 'Email is required.';
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                emailError.textContent = 'Enter a valid email address.';
                valid = false;
            }

            if (!password.value) {
                passwordError.textContent = 'Password is required.';
                valid = false;
            }

            if (!role.value) {
                roleError.textContent = 'Select a role.';
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
            }
        });
    }

    const signupForm = document.getElementById('signupForm');

    if (signupForm) {
        signupForm.addEventListener('submit', event => {
            let valid = true;

            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const contact = document.getElementById('contact');
            const password = document.getElementById('password');

            const nameError = document.getElementById('nameError');
            const emailError = document.getElementById('emailError');
            const contactError = document.getElementById('contactError');
            const passwordError = document.getElementById('passwordError');

            nameError.textContent = '';
            emailError.textContent = '';
            contactError.textContent = '';
            passwordError.textContent = '';

            if (!name.value.trim()) {
                nameError.textContent = 'Name is required.';
                valid = false;
            }

            if (!email.value.trim()) {
                emailError.textContent = 'Email is required.';
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                emailError.textContent = 'Enter a valid email address.';
                valid = false;
            }

            if (!/^\d{10}$/.test(contact.value.trim())) {
                contactError.textContent = 'Enter a valid 10-digit number.';
                valid = false;
            }

            if (password.value.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters.';
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
            }
        });
    }
});