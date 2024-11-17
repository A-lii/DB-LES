/* let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   profile.classList.remove('active');
   navbar.classList.remove('active');
} */


// Navbar Toggle
let navbar = document.querySelector('.header .flex .navbar');
let profile = document.querySelector('.header .flex .profile');

document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active'); // Show/hide navbar
    profile.classList.remove('active'); // Hide profile if navbar is open
};

// Profile Toggle
document.querySelector('#user-btn').onclick = () => {
    profile.classList.toggle('active'); // Show/hide profile
    navbar.classList.remove('active'); // Hide navbar if profile is open
};

// Hide on Scroll
window.onscroll = () => {
    profile.classList.remove('active'); // Hide profile
    navbar.classList.remove('active'); // Hide navbar
};

   