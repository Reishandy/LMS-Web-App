// Clear url params
window.history.replaceState({}, document.title, "dashboard.php");

// Add course
let form_add = document.getElementById('form-add');
form_add.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_add.checkValidity()) {
        $.ajax({
            type: 'POST',
            url: '../../logic/course/add.php',
            data: {
                'owner_id': document.getElementsByName('owner_id').item(0).value,
                'name': document.getElementById('name').value,
                'description': document.getElementById('description').value
            },
            success: (response) => {
                leave_page();
                leave(document.getElementById('modal-add'));

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
    } else {
        form_add.classList.add('was-validated');
    }
});

// Join course
let form_join = document.getElementById('form-join');
form_join.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_join.checkValidity()) {
        $.ajax({
            type: 'POST',
            url: '../../logic/course/enroll.php',
            data: {
                'user_id': document.getElementsByName('user_id').item(0).value,
                'course_id': document.getElementById('course-id').value
            },
            success: (response) => {
                leave_page();
                leave(document.getElementById('modal-join'));

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
    } else {
        form_join.classList.add('was-validated');
    }
});

// Delete course
let modal_delete = document.getElementById('modal-delete');
modal_delete.addEventListener('show.bs.modal' , (e) => {
    console.log('show');
    let button = e.relatedTarget;
    let id = button.getAttribute('data-bs-id');
    let name = button.getAttribute('data-bs-name');

    let modal_title = modal_delete.querySelector('.modal-title');
    let course_id = modal_delete.querySelector('#course-id-delete');

    modal_title.textContent = 'Hapus ' + name + '?';
    course_id.value = id;
});

let form_delete = document.getElementById('form-delete');
form_delete.addEventListener('submit', (e) => {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: '../../logic/course/delete.php',
        data: {
            'course_id': document.getElementById('course-id-delete').value
        },
        success: (response) => {
            leave_page();
            leave(modal_delete);

            setTimeout(() => {
                window.location.replace(response);
            }, 500);
        }
    });

    leave_page();
    leave(modal_delete);

    setTimeout(() => {
        form_delete.submit();
    }, 500);
});

// Edit course
let modal_edit = document.getElementById('modal-edit');
modal_edit.addEventListener('show.bs.modal', (e) => {
    let button = e.relatedTarget;
    let id = button.getAttribute('data-bs-id');
    let name = button.getAttribute('data-bs-name');
    let description = button.getAttribute('data-bs-description');

    let title = modal_edit.querySelector('.modal-title');
    let course_id = modal_edit.querySelector('#course-id-edit');
    let course_name = modal_edit.querySelector('#edit-name');
    let course_description = modal_edit.querySelector('#edit-description');

    title.textContent = 'Edit ' + name;
    course_id.value = id;
    course_name.value = name;
    course_description.value = description;

    // Dispatch event to trigger textarea resize, wait for the textarea to be rendered
    setTimeout(() => {
        course_description.dispatchEvent(new Event('input'));
    }, 500);
});

let form_edit = document.getElementById('form-edit');
form_edit.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_edit.checkValidity()) {
        $.ajax({
            type: 'POST',
            url: '../../logic/course/edit.php',
            data: {
                'course_id': document.getElementById('course-id-edit').value,
                'name': document.getElementById('edit-name').value,
                'description': document.getElementById('edit-description').value
            },
            success: (response) => {
                leave_page();
                leave(modal_edit);

                setTimeout(() => {
                    window.location.replace(response);
                }, 500);
            }
        });
    } else {
        form_edit.classList.add('was-validated');
    }
});