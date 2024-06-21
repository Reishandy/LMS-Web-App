// Clear url params
window.history.replaceState({}, document.title, "dashboard.php");

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

// Logout
let logout = document.getElementById('logout');
logout.addEventListener('click', () => {
    let nav = document.getElementsByClassName('div-nav');
    let body = document.getElementsByClassName('div-body');
    leave(nav);
    setTimeout(() => {
        leave(body);
    }, 100);

    setTimeout(() => {
        window.location.href = '../../logic/auth/logout.php';
    }, 500);
});