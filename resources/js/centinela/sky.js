/**
 * Fondo de fantasía generado por código (Canvas 2D, sin imágenes ni SVG):
 * cielo nocturno con nebulosa procedural, campo de estrellas a dos
 * profundidades, velo de aurora y polvo dorado flotante.
 *
 * Diseño pensado para público con TEA y para móvil:
 * - Todo el movimiento es lento, continuo y de bajo contraste: deriva de
 *   la nebulosa en ciclos de ~1 minuto, brillo de estrellas con periodos
 *   de 6–14 s y amplitud pequeña (nunca parpadea), polvo ascendente suave.
 * - Rendimiento: la nebulosa se pinta UNA vez en un canvas offscreen a
 *   media resolución y se reutiliza cada frame; solo se redibujan
 *   estrellas titilantes, aurora y polvo. DPR limitado a 2.
 * - Con prefers-reduced-motion se dibuja un único fotograma estático.
 */
export function initSky(canvas) {
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

    let w = 0;
    let h = 0;
    let dpr = 1;
    let nebula = null; // canvas offscreen con nebulosa + estrellas fijas
    let twinkles = []; // estrellas con brillo oscilante
    let dust = [];     // partículas de polvo dorado
    let rafId = null;
    let lastTime = 0;

    // Sprite con halo radial para estrellas y polvo (evita shadowBlur).
    const SPRITE = 64;
    const sprite = document.createElement('canvas');
    sprite.width = SPRITE;
    sprite.height = SPRITE;
    const sctx = sprite.getContext('2d');
    const glow = sctx.createRadialGradient(32, 32, 0, 32, 32, 32);
    glow.addColorStop(0, 'rgba(255, 244, 214, 1)');
    glow.addColorStop(0.3, 'rgba(246, 215, 137, 0.55)');
    glow.addColorStop(1, 'rgba(246, 215, 137, 0)');
    sctx.fillStyle = glow;
    sctx.fillRect(0, 0, SPRITE, SPRITE);

    /* ------------------------------------------------------------
     * Nebulosa procedural (se genera una vez por resize)
     * ------------------------------------------------------------ */
    function buildNebula() {
        const SCALE = 0.5; // media resolución: aspecto suave y barato
        nebula = document.createElement('canvas');
        nebula.width = Math.max(2, Math.round(w * SCALE));
        nebula.height = Math.max(2, Math.round(h * SCALE));
        const n = nebula.getContext('2d');
        const NW = nebula.width;
        const NH = nebula.height;

        // Cielo base: degradado índigo profundo
        const base = n.createLinearGradient(0, 0, 0, NH);
        base.addColorStop(0, '#101638');
        base.addColorStop(0.5, '#0b1030');
        base.addColorStop(1, '#070a1c');
        n.fillStyle = base;
        n.fillRect(0, 0, NW, NH);

        // Masas de nebulosa: violetas, índigos y un toque cálido dorado
        const palette = [
            [104, 86, 204],  // violeta
            [64, 60, 148],   // índigo
            [134, 96, 176],  // púrpura suave
            [52, 66, 150],   // azul profundo
            [146, 110, 62],  // dorado cálido (muy tenue)
            [112, 74, 112],  // malva apagado
        ];
        n.globalCompositeOperation = 'lighter';
        const blobs = 16;
        for (let i = 0; i < blobs; i++) {
            const [r, g, b] = palette[i % palette.length];
            const cx = Math.random() * NW;
            const cy = Math.random() * NH;
            const radius = (0.18 + Math.random() * 0.35) * Math.min(NW, NH);
            const alpha = 0.05 + Math.random() * 0.08;
            const grad = n.createRadialGradient(cx, cy, 0, cx, cy, radius);
            grad.addColorStop(0, `rgba(${r}, ${g}, ${b}, ${alpha})`);
            grad.addColorStop(1, `rgba(${r}, ${g}, ${b}, 0)`);
            n.fillStyle = grad;
            n.fillRect(cx - radius, cy - radius, radius * 2, radius * 2);
        }

        // Estrellas fijas (lejanas, diminutas)
        const starCount = Math.round((NW * NH) / 9000);
        for (let i = 0; i < starCount; i++) {
            const x = Math.random() * NW;
            const y = Math.random() * NH;
            const size = Math.random() < 0.85 ? 1 : 1.6;
            const alpha = 0.15 + Math.random() * 0.5;
            const warm = Math.random() < 0.25;
            n.fillStyle = warm
                ? `rgba(246, 222, 160, ${alpha})`
                : `rgba(214, 222, 248, ${alpha})`;
            n.fillRect(x, y, size, size);
        }
        n.globalCompositeOperation = 'source-over';
    }

    /* ------------------------------------------------------------
     * Estrellas titilantes y polvo dorado
     * ------------------------------------------------------------ */
    function buildDynamic() {
        const twinkleCount = Math.max(18, Math.min(46, Math.round((w * h) / 42000)));
        twinkles = Array.from({ length: twinkleCount }, () => ({
            x: Math.random() * w,
            y: Math.random() * h,
            r: 1.2 + Math.random() * 2.4,
            alpha: 0.2 + Math.random() * 0.35,
            period: 6 + Math.random() * 8, // brillo muy lento, sin parpadeo
            phase: Math.random() * Math.PI * 2,
        }));

        let dustCount = Math.round((w * h) / 30000);
        if (w < 640) dustCount = Math.round(dustCount * 0.65);
        dustCount = Math.max(18, Math.min(64, dustCount));
        dust = Array.from({ length: dustCount }, () => makeDust(true));
    }

    function makeDust(anywhere) {
        const deep = Math.random() < 0.35;
        return {
            x: Math.random() * w,
            y: anywhere ? Math.random() * h : h + 24,
            r: deep ? 2.0 + Math.random() * 2.2 : 0.8 + Math.random() * 1.6,
            speed: deep ? 2.5 + Math.random() * 4 : 6 + Math.random() * 8, // px/s
            alpha: deep ? 0.05 + Math.random() * 0.09 : 0.14 + Math.random() * 0.22,
            swayAmp: 6 + Math.random() * 14,
            swayPeriod: 8 + Math.random() * 9,
            phase: Math.random() * Math.PI * 2,
        };
    }

    /* ------------------------------------------------------------
     * Dibujo
     * ------------------------------------------------------------ */
    function drawFrame(t) {
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx.clearRect(0, 0, w, h);

        // Nebulosa con deriva extremadamente lenta (ciclo de ~1 minuto)
        const dx = Math.sin(t / 47) * 14;
        const dy = Math.cos(t / 61) * 10;
        const cover = Math.max(w / nebula.width, h / nebula.height) * 1.06;
        const dw = nebula.width * cover;
        const dh = nebula.height * cover;
        ctx.drawImage(nebula, (w - dw) / 2 + dx, (h - dh) / 2 + dy, dw, dh);

        // Velo de aurora: dos bandas diagonales tenues que se desplazan despacio
        ctx.save();
        ctx.globalCompositeOperation = 'lighter';
        for (const [offset, period, hue] of [[0, 43, '104, 86, 204'], [0.45, 57, '146, 110, 62']]) {
            const shift = Math.sin((t / period) * Math.PI * 2 + offset * 6) * w * 0.06;
            const gx = w * (0.25 + offset) + shift;
            const band = ctx.createLinearGradient(gx - w * 0.3, 0, gx + w * 0.3, h);
            band.addColorStop(0, `rgba(${hue}, 0)`);
            band.addColorStop(0.5, `rgba(${hue}, 0.045)`);
            band.addColorStop(1, `rgba(${hue}, 0)`);
            ctx.fillStyle = band;
            ctx.fillRect(0, 0, w, h);
        }
        ctx.restore();

        // Estrellas titilantes (brillo que respira, amplitud pequeña)
        for (const s of twinkles) {
            const breathe = 0.75 + 0.25 * Math.sin((t / s.period) * Math.PI * 2 + s.phase);
            const size = s.r * 5;
            ctx.globalAlpha = s.alpha * breathe;
            ctx.drawImage(sprite, s.x - size / 2, s.y - size / 2, size, size);
        }

        // Polvo dorado ascendente
        for (const p of dust) {
            const sway = Math.sin((t / p.swayPeriod) * Math.PI * 2 + p.phase) * p.swayAmp;
            const size = p.r * 4;
            ctx.globalAlpha = p.alpha;
            ctx.drawImage(sprite, p.x + sway - size / 2, p.y - size / 2, size, size);
        }
        ctx.globalAlpha = 1;
    }

    function tick(now) {
        const t = now / 1000;
        const dt = Math.min(0.05, t - lastTime);
        lastTime = t;
        for (const p of dust) {
            p.y -= p.speed * dt;
            if (p.y < -24) {
                Object.assign(p, makeDust(false));
                p.x = Math.random() * w;
            }
        }
        drawFrame(t);
        rafId = requestAnimationFrame(tick);
    }

    function start() {
        if (rafId === null && !motionQuery.matches) {
            lastTime = performance.now() / 1000;
            rafId = requestAnimationFrame(tick);
        }
    }

    function stop() {
        if (rafId !== null) {
            cancelAnimationFrame(rafId);
            rafId = null;
        }
    }

    motionQuery.addEventListener?.('change', () => {
        if (motionQuery.matches) {
            stop();
            drawFrame(performance.now() / 1000);
        } else {
            start();
        }
    });

    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(resize, 150);
    });

    function resize() {
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        w = window.innerWidth;
        h = window.innerHeight;
        canvas.width = Math.round(w * dpr);
        canvas.height = Math.round(h * dpr);
        canvas.style.width = `${w}px`;
        canvas.style.height = `${h}px`;
        buildNebula();
        buildDynamic();
        if (motionQuery.matches) drawFrame(0);
    }

    resize();
    if (motionQuery.matches) {
        drawFrame(0);
    } else {
        start();
    }
}
