// =====================================================================
// Interacciones de la maqueta (sin dependencias)
//   1. Menú móvil (layouts/header.blade.php)
//   2. Pestañas de talleres (sections/talleres.blade.php)
// =====================================================================

// ---------- 1. Menú móvil ----------
function initMenu() {
    const toggle = document.getElementById('navToggle');
    const menu = document.getElementById('navMenu');
    if (!toggle || !menu) return;

    const setOpen = (open) => {
        menu.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
    };

    toggle.addEventListener('click', () => setOpen(!menu.classList.contains('is-open')));

    // Cerrar al pulsar un enlace o la tecla Escape
    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', (e) => e.key === 'Escape' && setOpen(false));
}

// ---------- 2. Pestañas ----------
function initTabs() {
    const tabs = document.querySelectorAll('[data-tab]');
    const panels = document.querySelectorAll('[data-panel]');
    if (!tabs.length) return;

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const id = tab.dataset.tab;
            tabs.forEach((t) => t.setAttribute('aria-selected', String(t === tab)));
            panels.forEach((p) => (p.hidden = p.dataset.panel !== id));
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initMenu();
    initTabs();
});
