// Back button
let back_button = document.getElementById('button-back');
back_button.addEventListener('click', (e) => {
    e.preventDefault();

    leave_page();
    setTimeout(() => {
        window.history.back();
    }, 500);
});

// Add material
let form_add_material = document.getElementById('form-add-material');
form_add_material.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_add_material.checkValidity()) {
        // Change the button to loading state while the file is being uploaded
        let button = form_add_material.querySelector('button[type="submit"]');
        button.innerHTML = ' <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Uploading...';

        leave_page();
        leave(document.getElementById('modal-add'));

        setTimeout(() => {
            form_add_material.submit();
        }, 500);
    } else {
        form_add_material.classList.add('was-validated');
    }
});

// Add assignment
let form_add_assignment = document.getElementById('form-add-assigment');
form_add_assignment.addEventListener('submit', (e) => {
    e.preventDefault();

    let dueDateInput = document.getElementById('modal-add-assigment-due-date');
    let selectedDate = new Date(dueDateInput.value);
    let now = new Date();

    // Remove the time part from the date
    now.setHours(0,0,0,0);
    selectedDate.setHours(0,0,0,0);

    if (selectedDate < now) {
        dueDateInput.setCustomValidity('Batas waktu tidak boleh sebelum dari hari ini');
    } else {
        dueDateInput.setCustomValidity('');
    }

    if (form_add_assignment.checkValidity()) {
        // Change the button to loading state while the file is being uploaded
        let button = form_add_assignment.querySelector('button[type="submit"]');
        button.innerHTML = ' <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Uploading...';

        leave_page();
        leave(document.getElementById('modal-add'));

        setTimeout(() => {
            form_add_assignment.submit();
        }, 500);
    } else {
        form_add_assignment.classList.add('was-validated');
    }
});

// Add test
let form_add_test = document.getElementById('form-add-test');
form_add_test.addEventListener('submit', (e) => {
    e.preventDefault();

    let dueDateInput = document.getElementById('modal-add-test-due-date');
    let selectedDate = new Date(dueDateInput.value);
    let now = new Date()

    // Remove the time part from the date
    now.setHours(0,0,0,0);
    selectedDate.setHours(0,0,0,0);

    if (selectedDate < now) {
        dueDateInput.setCustomValidity('Batas waktu tidak boleh sebelum dari hari ini');
    } else {
        dueDateInput.setCustomValidity('');
    }

    if (form_add_test.checkValidity()) {
        leave_page();
        leave(document.getElementById('modal-add'));

        setTimeout(() => {
            form_add_test.submit();
        }, 500);
    } else {
        form_add_test.classList.add('was-validated');
    }
});