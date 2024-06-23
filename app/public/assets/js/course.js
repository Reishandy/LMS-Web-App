// Back button
let back_button = document.getElementById('button-back');
back_button.addEventListener('click', (e) => {
    e.preventDefault();

    leave_page();
    setTimeout(() => {
        window.location.href = '../dashboard/dashboard.php';
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
        file_element.textContent = file_name;
        file_element.target = '_blank';
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
        button.innerHTML = ' <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Mengunggah...';

        $.ajax({
            type: 'POST',
            url: '../../logic/course_inside/material_add.php',
            data: new FormData(form_add_material),
            processData: false,
            contentType: false,
            success: (response) => {
                leave_page();
                leave(document.getElementById('modal-material'));

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
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
    now.setHours(0, 0, 0, 0);
    selectedDate.setHours(0, 0, 0, 0);

    if (selectedDate < now) {
        dueDateInput.setCustomValidity('Batas waktu tidak boleh sebelum dari hari ini');
    } else {
        dueDateInput.setCustomValidity('');
    }

    if (form_add_assignment.checkValidity()) {
        // Change the button to loading state while the file is being uploaded
        let button = form_add_assignment.querySelector('button[type="submit"]');
        button.innerHTML = ' <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Mengunggah...';

        $.ajax({
            type: 'POST',
            url: '../../logic/course_inside/assigment_add.php',
            data: new FormData(form_add_assignment),
            processData: false,
            contentType: false,
            success: (response) => {
                leave_page();
                leave(document.getElementById('modal-assigment'));

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
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
    now.setHours(0, 0, 0, 0);
    selectedDate.setHours(0, 0, 0, 0);

    if (selectedDate < now) {
        dueDateInput.setCustomValidity('Batas waktu tidak boleh sebelum dari hari ini');
    } else {
        dueDateInput.setCustomValidity('');
    }

    if (form_add_test.checkValidity()) {
        $.ajax({
            type: 'POST',
            url: '../../logic/course_inside/test_add.php',
            data: new FormData(form_add_test),
            processData: false,
            contentType: false,
            success: (response) => {
                leave_page();
                leave(document.getElementById('modal-test'));

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
    } else {
        form_add_test.classList.add('was-validated');
    }
});

// Edit material modal
let modal_edit_material = document.getElementById('modal-material-edit');
modal_edit_material.addEventListener('show.bs.modal', (e) => {
    let button = e.relatedTarget;
    let id = button.getAttribute('data-bs-id');
    let title = button.getAttribute('data-bs-title');
    let description = button.getAttribute('data-bs-description');
    let file_name = button.getAttribute('data-bs-file-name');

    let title_input = modal_edit_material.querySelector('.modal-title');
    let id_input = modal_edit_material.querySelector('#id-material-edit');
    let title_edit = modal_edit_material.querySelector('#modal-material-edit-name');
    let description_edit = modal_edit_material.querySelector('#modal-material-edit-description');
    let attachment = modal_edit_material.querySelector('#attachment-material');

    title_input.textContent = 'Edit ' + title;
    id_input.value = id;
    title_edit.value = title;
    description_edit.value = description;
    attachment.textContent = 'Tautan yang tercantum: ' + file_name;

    setTimeout(() => {
        description_edit.dispatchEvent(new Event('input'));
    }, 500);
});

let edit_material_form = document.getElementById('form-material-edit');
edit_material_form.addEventListener('submit', (e) => {
    e.preventDefault();

    if (edit_material_form.checkValidity()) {
        // Change the button to loading state while the file is being uploaded
        let button = edit_material_form.querySelector('button[type="submit"]');
        button.innerHTML = ' <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Mengunggah...';

        $.ajax({
            type: 'POST',
            url: '../../logic/course_inside/material_edit.php',
            data: new FormData(edit_material_form),
            processData: false,
            contentType: false,
            success: (response) => {
                leave_page();
                leave(document.getElementById('modal-material-edit'));

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
    } else {
        edit_material_form.classList.add('was-validated');
    }
});

// Edit assignment modal
let modal_edit_assignment = document.getElementById('modal-assigment-edit');
modal_edit_assignment.addEventListener('show.bs.modal', (e) => {
    let button = e.relatedTarget;
    let id = button.getAttribute('data-bs-id');
    let title = button.getAttribute('data-bs-title');
    let description = button.getAttribute('data-bs-description');
    let due_date = button.getAttribute('data-bs-due-date');
    let file_name = button.getAttribute('data-bs-file-name');

    let title_input = modal_edit_assignment.querySelector('.modal-title');
    let id_input = modal_edit_assignment.querySelector('#id-assigment-edit');
    let title_edit = modal_edit_assignment.querySelector('#modal-assigment-edit-name');
    let description_edit = modal_edit_assignment.querySelector('#modal-assigment-edit-description');
    let due_date_edit = modal_edit_assignment.querySelector('#modal-assigment-edit-due-date');
    let attachment = modal_edit_assignment.querySelector('#attachment-assigment');

    title_input.textContent = 'Edit ' + title;
    id_input.value = id;
    title_edit.value = title;
    description_edit.value = description;
    due_date_edit.value = due_date;
    attachment.textContent = 'Tautan yang tercantum: ' + file_name;

    setTimeout(() => {
        description_edit.dispatchEvent(new Event('input'));
    }, 500);
});

let edit_assignment_form = document.getElementById('form-assigment-edit');
edit_assignment_form.addEventListener('submit', (e) => {
    e.preventDefault();

    let dueDateInput = document.getElementById('modal-assigment-edit-due-date');
    let selectedDate = new Date(dueDateInput.value);
    let now = new Date();

    // Remove the time part from the date
    now.setHours(0, 0, 0, 0);
    selectedDate.setHours(0, 0, 0, 0);

    if (selectedDate < now) {
        dueDateInput.setCustomValidity('Batas waktu tidak boleh sebelum dari hari ini');
    } else {
        dueDateInput.setCustomValidity('');
    }

    if (edit_assignment_form.checkValidity()) {
        // Change the button to loading state while the file is being uploaded
        let button = edit_assignment_form.querySelector('button[type="submit"]');
        button.innerHTML = ' <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Mengunggah...';

        $.ajax({
            type: 'POST',
            url: '../../logic/course_inside/assigment_edit.php',
            data: new FormData(edit_assignment_form),
            processData: false,
            contentType: false,
            success: (response) => {
                leave_page();
                leave(document.getElementById('modal-assigment-edit'));

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
    } else {
        edit_assignment_form.classList.add('was-validated');
    }
});

// Edit test modal
let modal_edit_test = document.getElementById('modal-test-edit');
modal_edit_test.addEventListener('show.bs.modal', (e) => {
    let button = e.relatedTarget;
    let id = button.getAttribute('data-bs-id');
    let title = button.getAttribute('data-bs-title');
    let description = button.getAttribute('data-bs-description');
    let link = button.getAttribute('data-bs-link');
    let due_date = button.getAttribute('data-bs-due-date');

    let title_input = modal_edit_test.querySelector('.modal-title');
    let id_input = modal_edit_test.querySelector('#id-test-edit');
    let title_edit = modal_edit_test.querySelector('#modal-test-edit-name');
    let description_edit = modal_edit_test.querySelector('#modal-test-edit-description');
    let link_edit = modal_edit_test.querySelector('#modal-test-edit-link');
    let due_date_edit = modal_edit_test.querySelector('#modal-test-edit-due-date');

    title_input.textContent = 'Edit ' + title;
    id_input.value = id;
    title_edit.value = title;
    description_edit.value = description;
    link_edit.value = link;
    due_date_edit.value = due_date;

    setTimeout(() => {
        description_edit.dispatchEvent(new Event('input'));
    }, 500);
});

let edit_test_form = document.getElementById('form-test-edit');
edit_test_form.addEventListener('submit', (e) => {
    e.preventDefault();

    let dueDateInput = document.getElementById('modal-test-edit-due-date');
    let selectedDate = new Date(dueDateInput.value);
    let now = new Date();

    // Remove the time part from the date
    now.setHours(0, 0, 0, 0);
    selectedDate.setHours(0, 0, 0, 0);

    if (selectedDate < now) {
        dueDateInput.setCustomValidity('Batas waktu tidak boleh sebelum dari hari ini');
    } else {
        dueDateInput.setCustomValidity('');
    }

    if (edit_test_form.checkValidity()) {
        $.ajax({
            type: 'POST',
            url: '../../logic/course_inside/test_edit.php',
            data: new FormData(edit_test_form),
            processData: false,
            contentType: false,
            success: (response) => {
                leave_page();
                leave(document.getElementById('modal-test-edit'));

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
    } else {
        edit_test_form.classList.add('was-validated');
    }
});

// Submit assigment and modal
let modal_submit_assignment = document.getElementById('modal-assigment-submit');
modal_submit_assignment.addEventListener('show.bs.modal', (e) => {
    let button = e.relatedTarget;
    let id = button.getAttribute('data-bs-id');
    let title = button.getAttribute('data-bs-title');

    let title_input = modal_submit_assignment.querySelector('.modal-title');
    let id_input = modal_submit_assignment.querySelector('#id-assigment-submit');

    title_input.textContent = 'Kumpulkan ' + title;
    id_input.value = id;
});

let form_submit_assignment = document.getElementById('form-assigment-submit');
form_submit_assignment.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_submit_assignment.checkValidity()) {
        // Change the button to loading state while the file is being uploaded
        let button = form_submit_assignment.querySelector('button[type="submit"]');
        button.innerHTML = ' <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Mengunggah...';

        $.ajax({
            type: 'POST',
            url: '../../logic/course_inside/assigment_submit.php',
            data: new FormData(form_submit_assignment),
            processData: false,
            contentType: false,
            success: (response) => {
                leave_page();
                leave(document.getElementById('modal-assigment-submit'));

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
    } else {
        form_submit_assignment.classList.add('was-validated');
    }
});

// Submit test
let form_submit_test = document.getElementById('form-test-submit');
form_submit_test.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_submit_test.checkValidity()) {
        // Change the button to loading state while the file is being uploaded
        let button = form_submit_test.querySelector('button[type="submit"]');
        button.innerHTML = ' <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Mengunggah...';

        $.ajax({
            type: 'POST',
            url: '../../logic/course_inside/test_submit.php',
            data: new FormData(form_submit_test),
            processData: false,
            contentType: false,
            success: (response) => {
                leave_page();
                leave(document.getElementById('modal-test-submit'));

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
    } else {
        form_submit_test.classList.add('was-validated');
    }
});

// Delete item
let modal_delete_in = document.getElementById('modal-delete-in');
modal_delete_in.addEventListener('show.bs.modal', (e) => {
    let button = e.relatedTarget;
    let id = button.getAttribute('data-bs-id');
    let type = button.getAttribute('data-bs-type');
    let title = button.getAttribute('data-bs-title');

    let modal_title = modal_delete_in.querySelector('.modal-title');
    let modal_body = modal_delete_in.querySelector('.modal-body');
    let input_id = modal_delete_in.querySelector('#id-delete-in');
    let input_type = modal_delete_in.querySelector('#type-delete-in');

    modal_title.textContent = 'Hapus ' + type + ' ' + title + '?';
    modal_body.textContent = 'Apakah anda yakin ingin menghapus ' + type + ' ' + title + '?';
    input_id.value = id;
    input_type.value = type;
});
let form_delete_in = document.getElementById('form-delete');
form_delete_in.addEventListener('submit', (e) => {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: '../../logic/course_inside/in_delete.php',
        data: new FormData(form_delete_in),
        processData: false,
        contentType: false,
        success: (response) => {
            leave_page();
            leave(document.getElementById('modal-delete-in'));

            setTimeout(() => {
                window.location.replace(response);
            }, 500);
        }
    });
});

// Submissions modal
let modal_submissions = document.getElementById('modal-submissions');
modal_submissions.addEventListener('show.bs.modal', (e) => {
    let button = e.relatedTarget;
    let id = button.getAttribute('data-bs-id');
    let course_id = button.getAttribute('data-bs-course-id');
    let type = button.getAttribute('data-bs-type');
    let title = button.getAttribute('data-bs-title');
    let all = button.getAttribute('data-bs-all');

    let title_input = modal_submissions.querySelector('.modal-title');
    let table = modal_submissions.querySelector("tbody")
    let spinner = modal_submissions.querySelector('#spinner');

    title_input.textContent = 'Ssubmisi dari ' + type + ' ' + title;
    table.innerHTML = '';
    spinner.style.opacity = 1;

    // call the ../../logic/course_inside/submissions_get.php to get the submissions using ajax
    $.ajax({
        url: '../../logic/course_inside/submissions_get.php',
        type: 'POST',
        data: {
            id: id,
            course_id: course_id,
            type: type
        },
        success: function (response) {
            // Parse the response
            let submissions = JSON.parse(response);

            // Sort submissions by date_submitted in descending order
            submissions.sort((a, b) => new Date(b.date_submitted) - new Date(a.date_submitted));

            if (all === 'false') {
                // Track unique assignments to get the most recent ones
                let unique_assignments = {};

                // Filter out submissions, keeping only the most recent per user_id
                submissions = submissions.filter(submission => {
                    if (!unique_assignments[submission.user_id] ||
                        new Date(submission.date_submitted) > new Date(unique_assignments[submission.user_id].date_submitted)) {
                        unique_assignments[submission.user_id] = submission;
                        return true;
                    }
                    return false;
                });
            }

            // Hide the spinner
            spinner.style.opacity = 0;

            // Add submissions to the table
            submissions.forEach((submission, index) => {
                let row = document.createElement('tr');

                // Create cells for each field
                let cell_no = document.createElement('td');
                cell_no.textContent = index + 1;
                row.appendChild(cell_no);

                let cell_id = document.createElement('td');
                cell_id.textContent = submission.user_id;
                row.appendChild(cell_id);

                let cell_name = document.createElement('td');
                cell_name.textContent = submission.name;
                row.appendChild(cell_name);

                let cell_class = document.createElement('td');
                cell_class.textContent = submission.class.toUpperCase();
                row.appendChild(cell_class);

                let cell_year = document.createElement('td');
                cell_year.textContent = submission.year;
                row.appendChild(cell_year);

                let cell_date_submitted = document.createElement('td');
                cell_date_submitted.textContent = submission.date_submitted;
                row.appendChild(cell_date_submitted);

                let cell_description = document.createElement('td');
                cell_description.textContent = submission.description || 'N/A';
                row.appendChild(cell_description);

                let cell_file = document.createElement('td');
                if (submission.file_path && submission.file_name) {
                    let downloadLink = document.createElement('a');
                    downloadLink.href = submission.file_path;
                    downloadLink.textContent = submission.file_name;
                    downloadLink.target = '_blank';
                    cell_file.appendChild(downloadLink);
                } else {
                    cell_file.textContent = 'N/A';
                }
                row.appendChild(cell_file);

                // Append the row to the table body
                table.appendChild(row);
            });
        }
    })
});