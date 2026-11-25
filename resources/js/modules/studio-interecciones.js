export function initStudioInteractions() {
    // 1. Menú móvil
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            const isOpen = navMenu.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', String(isOpen));
        });

        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('is-open');
                navToggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // 2. Pestañas de Servicios (Adultos vs Infantil)
    const audienceTabs = document.querySelectorAll('.services__tab');
    const panelAdultos = document.getElementById('panel-adultos');
    const panelInfantil = document.getElementById('panel-infantil');

    audienceTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            audienceTabs.forEach(t => {
                t.classList.remove('is-active');
                t.setAttribute('aria-selected', 'false');
            });

            tab.classList.add('is-active');
            tab.setAttribute('aria-selected', 'true');

            const target = tab.dataset.audience;
            if (target === 'adultos') {
                panelAdultos.hidden = false;
                panelInfantil.hidden = true;
            } else {
                panelAdultos.hidden = true;
                panelInfantil.hidden = false;
            }
        });
    });

    // 3. Filtro de Galería por Técnica
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');

            const filter = btn.dataset.filter;

            galleryItems.forEach(item => {
                const category = item.dataset.category;
                if (filter === 'all' || category === filter) {
                    item.classList.remove('is-hidden');
                } else {
                    item.classList.add('is-hidden');
                }
            });
        });
    });

    // 4. Lightbox de Galería
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const lightboxClose = document.getElementById('lightboxClose');

    if (lightbox && lightboxImg) {
        galleryItems.forEach(item => {
            item.addEventListener('click', () => {
                const img = item.querySelector('img');
                const title = item.querySelector('strong')?.textContent || '';
                const subtitle = item.querySelector('span')?.textContent || '';

                lightboxImg.src = img.src;
                lightboxImg.alt = img.alt;
                lightboxCaption.textContent = `${title} — ${subtitle}`;
                lightbox.classList.add('is-open');
                lightbox.setAttribute('aria-hidden', 'false');
            });
        });

        const closeLightbox = () => {
            lightbox.classList.remove('is-open');
            lightbox.setAttribute('aria-hidden', 'true');
        };

        lightboxClose?.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) closeLightbox();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && lightbox.classList.contains('is-open')) {
                closeLightbox();
            }
        });
    }

    // 5. Reveal progresivo al hacer scroll
    const revealElements = document.querySelectorAll('[data-reveal]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    revealElements.forEach(el => observer.observe(el));
}