// Description textarea auto resize all textareas
let textareas = document.getElementsByTagName('textarea');
textareas = Array.from(textareas);
textareas.forEach(textarea => {
    textarea.addEventListener('input', () => {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    });
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
    $.ajax({
        type: 'POST',
        url: '../../logic/auth/logout.php',
        success: (response) => {
            leave(document.getElementById('modal-logout'));
            leave_page();

            setTimeout(() => {
                window.location.replace(response);
            }, 500);
        }
    });
});

// Switch div-details classes
function switch_details() {
    let details = document.getElementsByClassName('div-details')[0];
    let row = details.querySelector('.row');
    let col = row.querySelector('.col');
    let col7 = row.querySelector('.col-7');

    if (window.innerWidth < 600) {
        row.classList.remove('row');
        col.classList.remove('col');
        row.classList.add('col');
        col.classList.add('row');
        col7.classList.remove('col-7');
        col7.classList.add('col');
    } else {
        row.classList.add('row');
        col.classList.add('col');
        col.classList.remove('row');
        row.classList.remove('col');
        col7.classList.add('col-7');
        col7.classList.remove('col');
    }
}
switch_details();

// Onresize
window.addEventListener('resize', () => {
    switch_details();
});