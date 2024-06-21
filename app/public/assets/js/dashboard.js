// Clear url params
window.history.replaceState({}, document.title, "dashboard.php");

// Description textarea auto resize
let description = document.getElementById('description');
description.addEventListener('input', () => {
    description.style.height = 'auto';
    description.style.height = description.scrollHeight + 'px';
});

// Onload animation
window.onload = () => {
    let nav = document.getElementsByClassName('div-nav');
    let body = document.getElementsByClassName('div-body');
    enter(nav);
    setTimeout(() => {
        enter(body);
    }, 100);
    setTimeout(() => {
        display_cards();
    }, 500);
}

// Animations
let a = document.getElementsByTagName('a');
a = Array.from(a);
a.forEach(element => {
    element.addEventListener('mouseenter', () => {grow(element);});
    element.addEventListener('mouseleave', () => {shrink(element);});
});

let button = document.getElementsByTagName('button');
button = Array.from(button);
button.forEach(element => {
    element.addEventListener('mouseenter', () => {grow(element);});
    element.addEventListener('mouseleave', () => {shrink(element);});
});

// Card animations using observer and enter and leave function
function display_cards() {
    let cards = document.getElementsByClassName('div-card');
    cards = Array.from(cards);

    let observer = new IntersectionObserver((entries) => {
        let holder = [];
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                holder.push(entry.target);

                observer.unobserve(entry.target);
            }
        });
        card(holder);
    });

    cards.forEach(card => {
        observer.observe(card);
    });
}

// Leave function
function leave_page(element) {
    let nav = document.getElementsByClassName('div-nav');
    let body = document.getElementsByClassName('div-body');

    setTimeout(() => {
        leave(nav);
    }, 100);
    setTimeout(() => {
        leave(body);
    }, 100);
}

// Logout
let logout = document.getElementById('logout');
logout.addEventListener('click', () => {
    leave(document.getElementById('modal-logout'));

    leave_page();
    setTimeout(() => {
        window.location.href = '../../logic/auth/logout.php';
    }, 500);
});

// Add course
let form_add = document.getElementsByTagName('form')[0];
form_add.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_add.checkValidity()) {
        leave_page();
        leave(document.getElementById('modal-add'));

        setTimeout(() => {
            form_add.submit();
        }, 500);
    } else {
        form_add.classList.add('was-validated');
    }
});

// Join course
let form_join = document.getElementsByTagName('form')[1];
form_join.addEventListener('submit', (e) => {
    e.preventDefault();

    if (form_join.checkValidity()) {
        leave_page();
        leave(document.getElementById('modal-join'));

        setTimeout(() => {
            form_join.submit();
        }, 500);
    } else {
        form_join.classList.add('was-validated');
    }
});