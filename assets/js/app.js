/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

const cookieBanner = document.querySelector('.cookie-banner');
const cookieButton = document.querySelector('.cookie-banner .btn');

cookieButton.addEventListener('click', () => {
  cookieBanner.classList.remove('active');
  localStorage.setItem('cookiesAccepted', 'true');
});

setTimeout(() => {
  if (!localStorage.getItem('cookiesAccepted')) {
    cookieBanner.classList.add('active');
  }
}, 2000);
