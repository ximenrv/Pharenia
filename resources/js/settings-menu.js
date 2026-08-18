document.addEventListener("DOMContentLoaded", () => {
    const menu = document.getElementById("settingsMenu");
    const trigger = document.getElementById("menuTrigger");
    const themeToggle = document.getElementById("themeToggle");
    const sunIcon = document.querySelector(".sun-icon");
    const moonIcon = document.querySelector(".moon-icon");

    // Función para actualizar qué icono SVG se muestra
    const updateIcons = (theme) => {
        if (theme === "dark") {
            sunIcon.style.display = "block"; // Muestra el sol para poder volver a claro
            moonIcon.style.display = "none"; // Oculta la luna
        } else {
            sunIcon.style.display = "none"; // Oculta el sol
            moonIcon.style.display = "block"; // Muestra la luna para cambiar a oscuro
        }
    };

    // 1. Abrir/Cerrar menú flotante
    trigger.addEventListener("click", () => {
        menu.classList.toggle("is-open");
    });

    // 2. Alternar Modo Oscuro
    themeToggle.addEventListener("click", () => {
        const currentTheme = document.documentElement.dataset.theme;
        const newTheme = currentTheme === "dark" ? "light" : "dark";

        document.documentElement.dataset.theme = newTheme;
        localStorage.setItem("theme", newTheme);

        updateIcons(newTheme);
    });

    // Inicializar el icono correcto al cargar la página
    const currentTheme = document.documentElement.dataset.theme || "light";
    updateIcons(currentTheme);
});
