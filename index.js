document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('bg-video');
    const container = document.querySelector('.container');
    const mobileMenu = document.getElementById('mobile-menu');
    const menu = document.querySelector('.menu');

    // Animasi video mengikuti mouse
    container.addEventListener('mousemove', (e) => {
        const rect = container.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;
        // Batasi pergerakan maksimal 10px agar tidak keluar bingkai
        video.style.transform = `translate(${x * 10}px, ${y * 10}px)`;
    });

    // Reset transform saat mouse keluar
    container.addEventListener('mouseleave', () => {
        video.style.transform = 'translate(0, 0)';
    });

    // Toggle menu hamburger
    mobileMenu.addEventListener('click', () => {
        menu.classList.toggle('active');
    });
});