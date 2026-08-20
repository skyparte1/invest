import * as bootstrap from 'bootstrap';

document.getElementById('current-year').textContent = new Date().getFullYear();

document.querySelectorAll('#mainNav .nav-link, #mainNav .nav-actions a').forEach((link) => {
    link.addEventListener('click', () => {
        const navigation = document.getElementById('mainNav');

        if (navigation.classList.contains('show')) {
            bootstrap.Collapse.getOrCreateInstance(navigation).hide();
        }
    });
});
