window.addEventListener('load', () => {
    const preloader = document.getElementById('preloader');
    if (preloader) {
        if (!sessionStorage.getItem('techforge_visited')) {
            sessionStorage.setItem('techforge_visited', 'true');
            setTimeout(() => {
                preloader.classList.add('opacity-0');
                setTimeout(() => preloader.style.display = 'none', 1000); 
            }, 1800);
        } else {
            preloader.classList.add('opacity-0');
            setTimeout(() => preloader.style.display = 'none', 1000);
        }
    }
});
