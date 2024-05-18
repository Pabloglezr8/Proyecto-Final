document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobile-menu');
    const hamburgerContainer = document.querySelector('.hamburger-container');

    mobileMenuButton.addEventListener('click', function () {
        hamburgerContainer.classList.toggle('show');
        mobileMenuButton.classList.toggle('active');
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) {
            hamburgerContainer.classList.remove('show');
            mobileMenuButton.classList.remove('active');
        }
    });
});
