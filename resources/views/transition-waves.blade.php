<div class="cont-olas-fijas" id="contenedor-olas">
    <svg viewBox="0 0 1440 690" xmlns="http://www.w3.org/2000/svg" class="svg-wave-item svg-olas-normal">
        <g class="wave-group">
            <style>
                .path-dynamic-0 { animation: pathAnim-0 4s linear infinite; }
                .path-dynamic-1 { animation: pathAnim-1 4s linear infinite; }
                .path-dynamic-2 { animation: pathAnim-2 4s linear infinite; }

                @keyframes pathAnim-0 {
                    0%, 100% { d: path("M 0,700 L 0,131 C 78.95,106.34 157.9,81.68 240,72 C 322.09,62.31 407.32,67.59 489,78 C 570.67,88.4 648.8,103.93 738,103 C 827.2,102.06 927.47,84.67 997,75 C 1066.52,65.32 1105.29,63.37 1174,74 C 1242.7,84.62 1341.35,107.81 1440,131 L 1440,700 L 0,700 Z"); }
                    25% { d: path("M 0,700 L 0,131 C 85.83,119.92 171.66,108.84 260,109 C 348.33,109.15 439.18,120.55 514,131 C 588.81,141.44 647.6,150.93 731,167 C 814.4,183.06 922.41,205.71 995,193 C 1067.58,180.28 1104.73,132.22 1173,116 C 1241.26,99.77 1340.63,115.38 1440,131 L 1440,700 L 0,700 Z"); }
                    50% { d: path("M 0,700 L 0,131 C 87.57,128 175.14,125 254,128 C 332.85,130.98 402.97,139.96 487,130 C 571.02,120.03 668.93,91.13 760,108 C 851.06,124.86 935.28,187.5 998,180 C 1060.71,172.5 1101.91,94.85 1172,75 C 1242.08,55.14 1341.04,93.07 1440,131 L 1440,700 L 0,700 Z"); }
                    75% { d: path("M 0,700 L 0,131 C 86.97,89.17 173.94,47.35 247,70 C 320.05,92.64 379.17,179.74 459,186 C 538.82,192.25 639.33,117.67 731,108 C 822.66,98.33 905.48,153.58 983,165 C 1060.51,176.41 1132.71,143.97 1208,131 C 1283.28,118.02 1361.64,124.51 1440,131 L 1440,700 L 0,700 Z"); }
                }

                @keyframes pathAnim-1 {
                    0%, 100% { d: path("M 0,700 L 0,306 C 60.28,277.48 120.56,248.97 211,259 C 301.43,269.02 422.0,317.58 515,319 C 607.99,320.41 673.4,274.66 740,278 C 806.6,281.33 874.39,333.74 957,356 C 1039.6,378.25 1137.03,370.35 1220,357 C 1302.96,343.64 1371.48,324.82 1440,306 L 1440,700 L 0,700 Z"); }
                    25% { d: path("M 0,700 L 0,306 C 61.4,324.16 122.8,342.32 200,328 C 277.19,313.67 370.18,266.86 469,246 C 567.81,225.13 672.46,230.2 745,260 C 817.53,289.8 857.95,344.33 932,351 C 1006.04,357.66 1113.72,316.47 1204,301 C 1294.27,285.52 1367.13,295.76 1440,306 L 1440,700 L 0,700 Z"); }
                    50% { d: path("M 0,700 L 0,306 C 65.86,290.66 131.73,275.32 224,291 C 316.26,306.67 434.91,353.35 508,344 C 581.08,334.64 608.6,269.26 677,261 C 745.4,252.73 854.68,301.58 941,318 C 1027.31,334.41 1090.66,318.4 1170,311 C 1249.33,303.59 1344.66,304.79 1440,306 L 1440,700 L 0,700 Z"); }
                    75% { d: path("M 0,700 L 0,306 C 75.98,325.71 151.97,345.42 242,331 C 332.02,316.57 436.07,268.01 509,271 C 581.92,273.98 623.73,328.53 697,339 C 770.26,349.46 874.99,315.85 958,298 C 1041.0,280.14 1102.28,278.04 1179,282 C 1255.71,285.95 1347.85,295.97 1440,306 L 1440,700 L 0,700 Z"); }
                }

                @keyframes pathAnim-2 {
                    0%, 100% { d: path("M 0,700 L 0,481 C 58.35,511.7 116.7,542.41 202,545 C 287.29,547.58 399.52,522.06 494,485 C 588.47,447.93 665.2,399.33 745,419 C 824.8,438.66 907.67,526.6 993,532 C 1078.32,537.4 1166.09,460.25 1241,438 C 1315.9,415.74 1377.95,448.37 1440,481 L 1440,700 L 0,700 Z"); }
                    25% { d: path("M 0,700 L 0,481 C 57.56,504.27 115.13,527.54 198,540 C 280.86,552.45 389.01,554.07 476,528 C 562.98,501.92 628.8,448.13 715,426 C 801.2,403.86 907.78,413.38 990,431 C 1072.21,448.61 1130.06,474.31 1201,484 C 1271.93,493.68 1355.96,487.34 1440,481 L 1440,700 L 0,700 Z"); }
                    50% { d: path("M 0,700 L 0,481 C 77.97,453.7 155.94,426.41 234,442 C 312.05,457.58 390.19,516.04 483,521 C 575.8,525.95 683.26,477.4 757,451 C 830.73,424.6 870.73,420.35 937,440 C 1003.26,459.64 1095.78,503.18 1184,514 C 1272.21,524.81 1356.1,502.9 1440,481 L 1440,700 L 0,700 Z"); }
                    75% { d: path("M 0,700 L 0,481 C 105.88,444.6 211.76,408.2 281,427 C 350.23,445.79 382.82,519.78 446,540 C 509.17,560.21 602.93,526.66 690,505 C 777.06,483.33 857.44,473.55 952,461 C 1046.55,448.44 1155.3,433.12 1239,436 C 1322.7,438.87 1381.35,459.93 1440,481 L 1440,700 L 0,700 Z"); }
                }
            </style>
            <path d="" stroke="none" fill="#2c525a" fill-opacity="0.2" class="path-dynamic-0"></path>
            <path d="" stroke="none" fill="#2c525a" fill-opacity="0.4" class="path-dynamic-1"></path>
            <path d="" stroke="none" fill="#2c525a" fill-opacity="1" class="path-dynamic-2"></path>
        </g>
    </svg>

    <div class="cuerpo-olas"></div>

    <svg viewBox="0 0 1440 690" xmlns="http://www.w3.org/2000/svg" class="svg-wave-item rotate-180">
        <g class="wave-group">
            <path d="" stroke="none" fill="#2c525a" fill-opacity="0.2" class="path-dynamic-0"></path>
            <path d="" stroke="none" fill="#2c525a" fill-opacity="0.4" class="path-dynamic-1"></path>
            <path d="" stroke="none" fill="#2c525a" fill-opacity="1" class="path-dynamic-2"></path>
        </g>
    </svg>
</div>

<style>
    .cont-olas-fijas {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
        pointer-events: none;
        z-index: 10;
        opacity: 0;
        transform: scaleY(0);
        transform-origin: bottom center;
        transition: opacity 0.2s ease;
    }

    /* 🎯 Hacemos que los SVGs dependan enteramente de las proporciones del contenedor padre */
    .svg-wave-item {
        display: block;
        width: 100%;
        height: auto;
        flex-shrink: 0; /* Evita que los SVGs se aplasten erróneamente */
    }

    /* Ajuste milimétrico para evitar bordes blancos entre los SVGs y el div central */
    .svg-olas-normal {
        margin-bottom: -2px;
    }
    .rotate-180 {
        transform: rotate(180deg);
        margin-top: -2px;
    }

    .cuerpo-olas {
        width: 100%;
        flex-grow: 1;
        background-color: #0693e3;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const contOlas = document.getElementById("contenedor-olas");
    const triggerSection = document.querySelector(".tea-section");

    if (!contOlas || !triggerSection) return;

    contOlas.style.transformOrigin = "bottom center";

    window.addEventListener("scroll", () => {
        const rect = triggerSection.getBoundingClientRect();
        const totalHeight = window.innerHeight;

        // 🎯 CONFIGURACIÓN: En cuántos píxeles de scroll ocurre todo el efecto
        const distanciaTotalEfecto = 800; 

        if (rect.top < totalHeight && rect.bottom > 0) {
            contOlas.style.opacity = "1";

            const pixelesRecorridos = totalHeight - rect.top;
            const progresoSeccion = pixelesRecorridos / distanciaTotalEfecto;
            const progresoClamp = Math.min(Math.max(progresoSeccion, 0), 1);

            // ─── PARTE 1: ESCALA SUBE HASTA 3 (0% al 50% del mini-scroll) ───
            if (progresoClamp <= 0.5) {
                const progresoNormalizado = progresoClamp / 0.5;
                const factorSubida = progresoNormalizado * 3; 
                
                contOlas.style.transform = `scaleY(${factorSubida}) translateY(0%)`;
            } 
            // ─── PARTE 2: SE MANTIENE GIGANTE Y SUBE AL TECHO (50% al 100% del mini-scroll) ───
            else {
                const progresoRestante = (progresoClamp - 0.5) / 0.5;
                
                // 🔒 Cambiado: Mantenemos la escala fija en 3 para que no se haga pequeño
                const factorFijo = 3; 
                
                // Se desplaza hacia arriba manteniendo su tamaño completo
                const desplazamientoArriba = progresoRestante * -250; 

                contOlas.style.transform = `scaleY(${factorFijo}) translateY(${desplazamientoArriba}%)`;
            }

        } else {
            contOlas.style.opacity = "0";
            contOlas.style.transform = "scaleY(0) translateY(0%)";
        }
    });
});
</script>