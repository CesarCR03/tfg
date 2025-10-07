const bannerContent = document.querySelector('.banner-content');
bannerContent.innerHTML += bannerContent.innerHTML;

document.querySelectorAll('.imagenesHover').forEach(img => {
    const originalSrc = img.src; 
    const hoverSrc = img.getAttribute('data-hover'); 

    img.addEventListener('mouseover', () => {
        img.src = hoverSrc;
    });

    img.addEventListener('mouseout', () => {
        img.src = originalSrc;
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const menu = document.querySelector(".menuPrincipal"),
          hamburg = document.querySelector(".hamburg");

    function toggleMenu() {
        menu.classList.toggle("active");
        hamburg.textContent = menu.classList.contains("active") ? "✖" : "☰";
    }

    hamburg.addEventListener("click", toggleMenu);

    document.addEventListener("click", e => {
        if (!menu.contains(e.target) && !hamburg.contains(e.target)) {
            menu.classList.remove("active");
            hamburg.textContent = "☰";
        }
    });
});