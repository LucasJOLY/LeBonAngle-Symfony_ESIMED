import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/style.css';
import './styles/pagination.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


setTimeout(() => {
    document.querySelectorAll('.flash-message').forEach(message => {
        setTimeout(() => message.remove(), 5000);
    });
}, 5000);