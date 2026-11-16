// Toggle Mobile Menu
const btn = document.getElementById('mobile-menu-btn');
const menu = document.getElementById('mobile-menu');

btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
});

// Tutup menu otomatis jika salah satu link diklik (khusus mobile)
const links = menu.querySelectorAll('a');
links.forEach(link => {
    link.addEventListener('click', () => {
        menu.classList.add('hidden');
    });
});