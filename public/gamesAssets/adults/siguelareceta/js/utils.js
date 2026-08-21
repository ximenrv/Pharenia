export function shuffle(lista) {
    const copia = [...lista];
    for (let i = copia.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [copia[i], copia[j]] = [copia[j], copia[i]];
    }
    return copia;
}

export class Timer {
    constructor(duracionMs, onTick, onFin) {
        this.duracionMs = duracionMs;
        this.onTick = onTick;
        this.onFin = onFin;
        this.intervaloId = null;
        this.restante = duracionMs;
    }

    iniciar() {
        this.restante = this.duracionMs;
        this.intervaloId = setInterval(() => {
            this.restante -= 100;
            this.onTick?.(this.restante);
            if (this.restante <= 0) {
                this.detener();
                this.onFin?.();
            }
        }, 100);
    }

    detener() {
        clearInterval(this.intervaloId);
        this.intervaloId = null;
    }
}
