// Animations
let link = document.getElementsByTagName('h5')[0];
link.addEventListener('mouseenter', () => {grow(link);});
link.addEventListener('mouseleave', () => {shrink(link);});

let button = document.getElementsByTagName('button')[0];
button.addEventListener('mouseenter', () => {grow(button);});
button.addEventListener('mouseleave', () => {shrink(button);});

let form_elements = Array.from(document.getElementsByClassName('form-floating'));
form_elements.forEach(element => {
    element.addEventListener('mouseenter', () => {grow(element, 1.1);});
    element.addEventListener('mouseleave', () => {shrink(element, 1.1);});
});

// Onload animation
window.onload = () => {
    let form = document.getElementsByClassName('div-form');
    enter(form);
}