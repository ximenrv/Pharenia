import { Level } from './Level.js';
import { Recipe } from './Recipe.js';
import { Tutorial } from './Tutorial.js';
import { RECIPES, TUTORIAL_PASOS } from './data.js';
import { renderTutorial, renderSeleccionRecetas, renderIntroNivel, renderNivel } from './ui.js';

export const ESTADOS = {
    TUTORIAL: 'tutorial',
    SELECCION_RECETA: 'seleccion_receta',
    NIVEL_INTRO: 'nivel_intro',
    NIVEL: 'nivel',
    RESULTADO: 'resultado',
};

const DURACION_INTRO_NIVEL_MS = 2500;

export class Game {
    constructor(contenedor) {
        this.contenedor = contenedor;
        this.recetas = RECIPES.map((data) => new Recipe(data));
        this.tutorial = new Tutorial(TUTORIAL_PASOS);
        this.recetaActual = null;
        this.nivelActual = null;
        this.estado = ESTADOS.TUTORIAL;
        this.puntaje = 0;
    }

    iniciar() {
        this.mostrarTutorial();
    }

    mostrarTutorial() {
        this.estado = ESTADOS.TUTORIAL;
        renderTutorial(this.tutorial, this.contenedor, {
            onSiguiente: () => {
                if (this.tutorial.terminado) {
                    this.mostrarSeleccionRecetas();
                } else {
                    this.tutorial.siguiente();
                    this.mostrarTutorial();
                }
            },
            onAnterior: () => {
                this.tutorial.anterior();
                this.mostrarTutorial();
            },
        });
    }

    mostrarSeleccionRecetas() {
        this.estado = ESTADOS.SELECCION_RECETA;
        renderSeleccionRecetas(this.recetas, this.contenedor, {
            onSeleccionar: (recetaId) => this.elegirReceta(recetaId),
            onAnterior: () => this.mostrarTutorial(),
        });
    }

    elegirReceta(recetaId) {
        this.recetaActual = this.recetas.find((r) => r.id === recetaId);
        const numerosDisponibles = Object.keys(this.recetaActual.niveles)
            .map(Number)
            .sort((a, b) => a - b);
        this.iniciarNivel(numerosDisponibles[0] ?? 1);
    }

    iniciarNivel(numero) {
        this.nivelActual = new Level(numero, this.recetaActual);
        this.nivelActual.iniciar();
        this.estado = ESTADOS.NIVEL_INTRO;
        renderIntroNivel(numero, this.recetaActual, this.contenedor);

        clearTimeout(this.introTimeoutId);
        this.introTimeoutId = setTimeout(() => {
            this.estado = ESTADOS.NIVEL;
            this.mostrarNivel();
        }, DURACION_INTRO_NIVEL_MS);
    }

    mostrarNivel() {
        renderNivel(this.nivelActual, this.contenedor, {
            onSeleccionarIngrediente: (ingredienteId) => this.seleccionarIngrediente(ingredienteId),
            onReiniciar: () => {
                this.nivelActual.iniciar();
                this.mostrarNivel();
            },
            onInicio: () => this.mostrarSeleccionRecetas(),
        });
    }

    seleccionarIngrediente(ingredienteId) {
        return this.nivelActual.seleccionarIngrediente(ingredienteId);
    }

    siguienteNivel() {
        if (this.nivelActual.numero < 3) {
            this.iniciarNivel(this.nivelActual.numero + 1);
        } else {
            this.estado = ESTADOS.RESULTADO;
        }
    }
}
