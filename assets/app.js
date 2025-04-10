import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

// Prevent multiple submit on form
const submitUserForm = document.querySelector("form[name='user_form']");
submitUserForm.addEventListener('submit', function(e) {
	const submitButton = document.querySelector("#user_form_submit");
	submitButton.setAttribute('disabled', 'disabled');
});
