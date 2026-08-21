export class Recipe {
    constructor({ id, nombre, emoji, imagenMenu, imagenPlato, niveles }) {
        this.id = id;
        this.nombre = nombre;
        this.emoji = emoji;
        this.imagenMenu = imagenMenu;
        this.imagenPlato = imagenPlato;
        this.niveles = niveles;
        this.pasoActual = 0;
        this.errores = 0;
    }

    get estrellas() {
        return Math.max(0, 5 - Math.floor(this.errores / 2));
    }

    getPasos(nivel) {
        return this.niveles[nivel]?.pasos ?? [];
    }

    getDistractores(nivel) {
        return this.niveles[nivel]?.distractores ?? [];
    }

    getTiempoVisible(nivel) {
        return this.niveles[nivel]?.tiempoVisible ?? null;
    }

    reset() {
        this.pasoActual = 0;
        this.errores = 0;
    }

    verificarIngrediente(nivel, ingredienteId) {
        const pasos = this.getPasos(nivel);
        const esperado = pasos[this.pasoActual];
        const esCorrecto = esperado === ingredienteId;

        if (esCorrecto) {
            this.pasoActual++;
        } else {
            this.errores++;
        }

        return {
            correcto: esCorrecto,
            completado: esCorrecto && this.pasoActual === pasos.length,
        };
    }
}
