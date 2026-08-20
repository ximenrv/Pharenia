import { shuffle } from './utils.js';

export class Level {
    constructor(numero, recipe) {
        this.numero = numero;
        this.recipe = recipe;
        this.pasos = recipe.getPasos(numero);
        this.distractores = recipe.getDistractores(numero);
        this.tiempoVisible = recipe.getTiempoVisible(numero);
    }

    get mostrarRecetaFija() {
        return this.numero === 1;
    }

    get ocultaTrasTiempo() {
        return this.numero === 2;
    }

    get tieneDistractores() {
        return this.numero === 3;
    }

    getIngredientesEnJuego() {
        return shuffle([...new Set([...this.pasos, ...this.distractores])]);
    }

    iniciar() {
        this.recipe.reset();
    }

    seleccionarIngrediente(ingredienteId) {
        return this.recipe.verificarIngrediente(this.numero, ingredienteId);
    }
}
