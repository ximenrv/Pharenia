export class Tutorial {
    constructor(pasos) {
        this.pasos = pasos;
        this.indiceActual = 0;
    }

    actual() {
        return this.pasos[this.indiceActual];
    }

    get terminado() {
        return this.indiceActual >= this.pasos.length - 1;
    }

    get puedeRetroceder() {
        return this.indiceActual > 0;
    }

    siguiente() {
        if (!this.terminado) {
            this.indiceActual++;
        }
        return this.terminado;
    }

    anterior() {
        if (this.puedeRetroceder) {
            this.indiceActual--;
        }
        return this.indiceActual;
    }

    reset() {
        this.indiceActual = 0;
    }
}
