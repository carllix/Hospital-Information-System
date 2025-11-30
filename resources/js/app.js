import './bootstrap';
import { initializeRegisterForm } from './register';
import { initializeLoginForm } from './login';

document.addEventListener('DOMContentLoaded', () => {
    initializeRegisterForm();
    initializeLoginForm();
});
