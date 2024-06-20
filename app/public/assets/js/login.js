// Clear url params
window.history.replaceState({}, document.title, "login.php");

// Change to register page
let register = document.getElementsByTagName('h5')[0];
register.addEventListener('click', () => {
    leave(document.getElementsByClassName('div-form'));
    setTimeout(() => {
        window.location.href = 'register.php';
    }, 500);
});

// Change form type for professor
window.addEventListener('pageshow', () => {
    check_account();
})

function check_account() {
    let accountSelect = document.getElementById('account');
    let input_nim_nip = document.getElementById('nim-nip');
    let label_nim_nip = document.querySelector('label[for="nim-nip"]');
    let validate_nim_nip = document.getElementById('validate-nim-nip');

    if (accountSelect.value === 'professor') {
        label_nim_nip.textContent = 'NIP';
        input_nim_nip.placeholder = 'NIP';
        input_nim_nip.pattern = '^[0-9]{18}$';
        validate_nim_nip.textContent = 'NIP tidak valid';
    } else {
        label_nim_nip.textContent = 'NIM';
        input_nim_nip.placeholder = 'NIM';
        input_nim_nip.pattern = '^[0-9]{11}$';
        validate_nim_nip.textContent = 'NIM tidak valid';
    }
}

document.getElementById('account').addEventListener('change', check_account);

// Form validation and submission
let form_login = document.getElementsByTagName('form')[0];
form_login.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_login.checkValidity()) {
        leave(document.getElementsByClassName('div-form')[0]);
        setTimeout(() => {
            form_login.submit();
        }, 500);
    } else {
        form_login.classList.add('was-validated');
    }
});