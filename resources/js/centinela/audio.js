/**
 * Sonidos muy suaves generados con WebAudio (sin archivos).
 * Pensados para no sobresaltar: senos y triángulos cortos, volumen bajo.
 * El sonido arranca DESACTIVADO; el usuario lo enciende si quiere.
 */
let ctx = null;
let enabled = false;

function ensureContext() {
    if (!ctx) {
        const AC = window.AudioContext || window.webkitAudioContext;
        if (!AC) return null;
        ctx = new AC();
    }
    if (ctx.state === 'suspended') ctx.resume();
    return ctx;
}

export function soundEnabled() {
    return enabled;
}

export function toggleSound() {
    enabled = !enabled;
    if (enabled) ensureContext();
    return enabled;
}

/** Tono breve y suave con caída exponencial. */
function tone({ freq = 440, type = 'sine', duration = 0.12, volume = 0.08, delay = 0 }) {
    if (!enabled) return;
    const ac = ensureContext();
    if (!ac) return;

    const t0 = ac.currentTime + delay;
    const osc = ac.createOscillator();
    const gain = ac.createGain();
    osc.type = type;
    osc.frequency.setValueAtTime(freq, t0);
    gain.gain.setValueAtTime(0.0001, t0);
    gain.gain.exponentialRampToValueAtTime(volume, t0 + 0.015);
    gain.gain.exponentialRampToValueAtTime(0.0001, t0 + duration);
    osc.connect(gain).connect(ac.destination);
    osc.start(t0);
    osc.stop(t0 + duration + 0.05);
}

/** Amenaza neutralizada: ping dorado, agudo y breve. */
export function sndHitThreat() {
    tone({ freq: 880, type: 'sine', duration: 0.14, volume: 0.07 });
    tone({ freq: 1320, type: 'sine', duration: 0.1, volume: 0.04, delay: 0.03 });
}

/** Figura protegida alcanzada: tono grave y cálido, nada estridente. */
export function sndHitProtect() {
    tone({ freq: 196, type: 'triangle', duration: 0.22, volume: 0.08 });
}

/** Fin de la patrulla: dos notas suaves en ascenso. */
export function sndEnd() {
    tone({ freq: 523, type: 'sine', duration: 0.25, volume: 0.06 });
    tone({ freq: 784, type: 'sine', duration: 0.35, volume: 0.05, delay: 0.18 });
}

/** Tarjeta recogida: arpeguito dorado breve y alegre. */
export function sndPower() {
    tone({ freq: 660, type: 'triangle', duration: 0.1, volume: 0.06 });
    tone({ freq: 880, type: 'triangle', duration: 0.1, volume: 0.055, delay: 0.07 });
    tone({ freq: 1175, type: 'triangle', duration: 0.16, volume: 0.05, delay: 0.14 });
}
