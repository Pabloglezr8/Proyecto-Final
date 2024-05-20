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

document.addEventListener('DOMContentLoaded', () => {
    const breadcrumb = document.getElementById('breadcrumb');
    const pathArray = window.location.pathname.split('/').filter(e => e);

    let path = '';
    const breadcrumbHTML = pathArray.map((segment, index) => {
        path += `/${segment}`;
        if (index === pathArray.length - 1) {
            return `<li><span>${segment.replace(/-/g, ' ')}</span></li>`;
        } else {
            return `<li><a href="${path}">${segment.replace(/-/g, ' ')}</a></li>`;
        }
    });

    breadcrumb.innerHTML = `<li><a href="/">Inicio</a></li>${breadcrumbHTML.join('')}`;
});
