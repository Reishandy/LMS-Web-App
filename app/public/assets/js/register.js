// Clear url params
window.history.replaceState({}, document.title, "register.php");

// Change to login page
let login = document.getElementsByTagName('h5')[0];
login.addEventListener('click', () => {
    leave(document.getElementsByClassName('div-form'));
    setTimeout(() => {
        window.location.href = 'login.php';
    }, 500);
});

// Change form type for professor
window.addEventListener('pageshow', () => {
    check_account()
})

function check_account() {
    let accountSelect = document.getElementById('account');
    let select_class = document.getElementById('class');
    let select_year = document.getElementById('year');
    let input_nim_nip = document.getElementById('nim-nip');
    let label_nim_nip = document.querySelector('label[for="nim-nip"]');
    let validate_nim_nip = document.getElementById('validate-nim-nip');

    if (accountSelect.value === 'professor') {
        // Disable class and year select with bootstrap
        // Change aria-label="Disabled select example" and remove required attribute and add disabled attribute
        // Then change the value to empty string
        select_class.setAttribute('aria-label', 'Disabled select example');
        select_class.removeAttribute('required');
        select_class.setAttribute('disabled', '');
        select_class.value = '';

        select_year.setAttribute('aria-label', 'Disabled select example');
        select_year.removeAttribute('required');
        select_year.setAttribute('disabled', '');
        select_year.value = '';

        // Change nim-nip label and input placeholder, pattern, and custom validity
        label_nim_nip.textContent = 'NIP';
        input_nim_nip.placeholder = 'NIP';
        input_nim_nip.pattern = '^[0-9]{18}$';
        validate_nim_nip.textContent = 'NIP tidak valid';
    } else {
        // Enable them back
        select_class.setAttribute('aria-label', 'Default select example');
        select_class.setAttribute('required', '');
        select_class.removeAttribute('disabled');
        select_class.value = 'a';

        select_year.setAttribute('aria-label', 'Default select example');
        select_year.setAttribute('required', '');
        select_year.removeAttribute('disabled');
        select_year.value = '2023';

        label_nim_nip.textContent = 'NIM';
        input_nim_nip.placeholder = 'NIM';
        input_nim_nip.pattern = '^[0-9]{11}$';
        validate_nim_nip.textContent = 'NIM tidak valid';
    }
}

document.getElementById('account').addEventListener('change', check_account);

// Check password match
function check_password_match() {
    let password = document.getElementById('password');
    let password_confirm = document.getElementById('password-confirm');

    if (password.value !== password_confirm.value) {
        password_confirm.setCustomValidity('Passwords do not match');
    } else {
        password_confirm.setCustomValidity('');
    }
}

document.getElementById('password').addEventListener('change', check_password_match);
document.getElementById('password-confirm').addEventListener('change', check_password_match);

// Form validation and submission
let form_register = document.getElementsByTagName('form')[0];
form_register.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_register.checkValidity()) {
        leave(document.getElementsByClassName('div-form')[0]);
        setTimeout(() => {
            form_register.submit();
        }, 500);
    } else {
        form_register.classList.add('was-validated');
    }
});