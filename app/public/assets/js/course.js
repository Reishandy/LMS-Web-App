// Back button
let back_button = document.getElementById('button-back');
back_button.addEventListener('click', (e) => {
    e.preventDefault();

    leave_page();
    setTimeout(() => {
        window.history.back();
    }, 500);
});

// Modal details
let modal_details = document.getElementById('modal-details');
modal_details.addEventListener('show.bs.modal', (e) => {
    let button = e.relatedTarget;
    let type = button.getAttribute('data-bs-type');
    let title = button.getAttribute('data-bs-title');
    let description = button.getAttribute('data-bs-description');
    let due_date = button.getAttribute('data-bs-due-date');
    let file_path = button.getAttribute('data-bs-file-path');
    let file_name = button.getAttribute('data-bs-file-name');
    let link = button.getAttribute('data-bs-link');

    let modal_title = modal_details.querySelector('.modal-title');
    let modal_body = modal_details.querySelector('.modal-body');
    // clear modal body
    modal_body.innerHTML = '';

    modal_title.textContent = type + ': ' + title;

    // add due date if available
    if (due_date !== '') {
        let due_date_element = document.createElement('h5');
        due_date_element.textContent = 'Deadline: ' + due_date;
        modal_body.appendChild(due_date_element);
    }

    // add description h5
    let description_h5 = document.createElement('h5');
    description_h5.textContent = 'Deskripsi:';
    modal_body.appendChild(description_h5);

    // add description
    let description_element = document.createElement('p');
    description_element.textContent = description;
    modal_body.appendChild(description_element);

    // Add attachment h5
    let attachment_h5 = document.createElement('h5');
    attachment_h5.textContent = 'Tautan:';
    modal_body.appendChild(attachment_h5);


    // Add file download button with name in the footer
    if (file_path !== '') {
        let file_element = document.createElement('a');
        file_element.href = file_path;
        file_element.download = file_name;
        file_element.textContent = file_name;
        modal_body.appendChild(file_element);
    }

    // Add link button in the footer
    if (link !== '') {
        let link_button = document.createElement('a');
        link_button.href = link;
        link_button.textContent = 'Link test';
        modal_body.appendChild(link_button);
    }
});

// Add material
let form_add_material = document.getElementById('form-material');
form_add_material.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_add_material.checkValidity()) {
        // Change the button to loading state while the file is being uploaded
        let button = form_add_material.querySelector('button[type="submit"]');
        button.innerHTML = ' <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Uploading...';

        leave_page();
        leave(document.getElementById('modal-material'));

        setTimeout(() => {
            form_add_material.submit();
        }, 500);
    } else {
        form_add_material.classList.add('was-validated');
    }
});

// Add assignment
let form_add_assignment = document.getElementById('form-assigment');
form_add_assignment.addEventListener('submit', (e) => {
    e.preventDefault();

    let dueDateInput = document.getElementById('modal-assigment-due-date');
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
        leave(document.getElementById('modal-assigment'));

        setTimeout(() => {
            form_add_assignment.submit();
        }, 500);
    } else {
        form_add_assignment.classList.add('was-validated');
    }
});

// Add test
let form_add_test = document.getElementById('form-test');
form_add_test.addEventListener('submit', (e) => {
    e.preventDefault();

    let dueDateInput = document.getElementById('modal-test-due-date');
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
        leave(document.getElementById('modal-test'));

        setTimeout(() => {
            form_add_test.submit();
        }, 500);
    } else {
        form_add_test.classList.add('was-validated');
    }
});