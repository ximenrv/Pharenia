// ============================================================================
//  NAVBAR RESPONSIVE — Menú hamburguesa / drawer móvil
//  - Alterna el menú colapsable (.navbar__collapse) con la clase .is-open
//  - Cierra al seleccionar un enlace, al hacer clic fuera o con Escape
//  - Limpia el estado abierto al volver a cruzar el breakpoint (desktop)
// ============================================================================

document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("navbarToggle");
    const collapse = document.getElementById("navbarCollapse");

    // La barra de navegación no existe en todas las páginas.
    if (!toggle || !collapse) return;

    const isOpen = () => collapse.classList.contains("is-open");

    const setState = (open) => {
        collapse.classList.toggle("is-open", open);
        toggle.setAttribute("aria-expanded", open ? "true" : "false");
    };

    toggle.addEventListener("click", (e) => {
        e.stopPropagation();
        setState(!isOpen());
    });

    // Cerrar el drawer al seleccionar cualquier enlace o acción interna
    collapse.querySelectorAll("a, button").forEach((el) => {
        el.addEventListener("click", () => setState(false));
    });

    // Cerrar al hacer clic fuera del navbar
    document.addEventListener("click", (e) => {
        if (isOpen() && !collapse.contains(e.target) && !toggle.contains(e.target)) {
            setState(false);
        }
    });

    // Cerrar con la tecla Escape
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && isOpen()) setState(false);
    });

    // Al volver al estado desktop (breakpoint), destruir el estado del drawer
    const desktop = window.matchMedia("(min-width: 769px)");
    const handleDesktop = (e) => {
        if (e.matches) setState(false);
    };
    if (desktop.addEventListener) {
        desktop.addEventListener("change", handleDesktop);
    } else if (desktop.addListener) {
        desktop.addListener(handleDesktop);
    }
});