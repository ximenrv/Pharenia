import { INGREDIENTES } from './data.js';

// Traducción: devuelve la versión traducida de la clave (o la clave si no existe)
const t = (key) => (window.translations && window.translations[key]) ? window.translations[key] : key;

let totalStarsReceta = 0;

function saveStarsToServer(stars) {
    if (!window.SAVE_RECORD_URL || !window.CSRF_TOKEN) return;
    fetch(window.SAVE_RECORD_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.CSRF_TOKEN,
        },
        body: JSON.stringify({
            game: 'stars_SigueLaReceta',
            score: stars,
        }),
    }).catch(function () {});
}

const CAPA_DESFASE_X = [0, -10, 8, -6, 5, -8, 9];
const CAPA_ROTACION = [0, -4, 3, -3, 4, -2.5, 3.5];

const MODO_NIVEL = {
    1: 'SIGUE LOS PASOS',
    2: 'RECUERDA LA RECETA',
    3: 'RECUERDA LA RECETA',
};

function textoModoNivel(numero) {
    return t(MODO_NIVEL[numero] ?? 'SIGUE LOS PASOS');
}

function resolverCapaPlato(info, numeroPaso, totalPasos) {
    if (numeroPaso === 0 && info.imgPlatoInicio) {
        return { src: info.imgPlatoInicio, escala: info.escalaPlatoInicio ?? 1 };
    }
    if (numeroPaso === totalPasos - 1 && info.imgPlatoFin) {
        return { src: info.imgPlatoFin, escala: info.escalaPlatoFin ?? 1 };
    }
    return { src: info.imgPlato ?? info.img, escala: info.escalaPlato ?? 1 };
}

function textoPaso(ingredienteId, index, pasos) {
    const info = INGREDIENTES[ingredienteId];
    const yaUsado = pasos.slice(0, index).includes(ingredienteId);

    if (yaUsado) {
        return t('Coloca la segunda porción de :ing.').replace(':ing', t(info.nombre).toLowerCase());
    }
    if (index === 0) {
        return t('Coloca :art:ing en el plato.').replace(':art', info.genero + ' ').replace(':ing', t(info.nombre).toLowerCase());
    }
    return t('Agrega :art:ing.').replace(':art', info.genero + ' ').replace(':ing', t(info.nombre).toLowerCase());
}

function crearTarjetaReceta(recipe, pasos) {
    const tarjeta = document.createElement('div');
    tarjeta.className = 'tarjeta-receta';

    const etiqueta = document.createElement('div');
    etiqueta.className = 'tarjeta-receta__etiqueta';
    etiqueta.textContent = `${recipe.emoji ?? '🍳'} ${t('RECETA')}`;
    tarjeta.appendChild(etiqueta);

    if (recipe.imagenMenu) {
        const img = document.createElement('img');
        img.className = 'tarjeta-receta__img';
        img.src = recipe.imagenMenu;
        img.alt = recipe.nombre;
        tarjeta.appendChild(img);
    }

    const nombre = document.createElement('h3');
    nombre.className = 'tarjeta-receta__nombre';
    nombre.textContent = t(recipe.nombre);
    tarjeta.appendChild(nombre);

    const lista = document.createElement('ol');
    lista.className = 'tarjeta-receta__pasos';
    pasos.forEach((id, index) => {
        const li = document.createElement('li');
        li.textContent = textoPaso(id, index, pasos);
        lista.appendChild(li);
    });
    tarjeta.appendChild(lista);

    return tarjeta;
}

function mostrarCompletado(pantalla, nombreReceta) {
    const overlay = document.createElement('div');
    overlay.className = 'nivel-completado fade-in';

    const titulo = document.createElement('p');
    titulo.className = 'nivel-completado__titulo';
    titulo.textContent = '🎉 ' + t('¡Receta completada!');

    const subtitulo = document.createElement('p');
    subtitulo.className = 'nivel-completado__subtitulo';
    subtitulo.textContent = t('¡Seguiste todos los pasos correctamente para tu :receta!').replace(':receta', t(nombreReceta).toLowerCase());

    overlay.appendChild(titulo);
    overlay.appendChild(subtitulo);
    pantalla.appendChild(overlay);
}

export function renderTutorial(tutorial, contenedor, { onSiguiente, onAnterior }) {
    contenedor.innerHTML = '';

    const pantalla = document.createElement('div');
    pantalla.className = 'pantalla-cocina fade-in';

    const personaje = document.createElement('img');
    personaje.className = 'personaje';
    personaje.src = tutorial.actual().personaje;
    personaje.alt = 'Lumen de chef';

    const dialogo = document.createElement('div');
    dialogo.className = 'dialogo';
    dialogo.textContent = t(tutorial.actual().texto);

    pantalla.appendChild(personaje);
    pantalla.appendChild(dialogo);

    const acciones = document.createElement('div');
    acciones.className = 'tutorial-acciones';

    const botonSiguiente = document.createElement('button');
    botonSiguiente.className = 'btn-siguiente';
    botonSiguiente.type = 'button';
    botonSiguiente.setAttribute('aria-label', 'Siguiente');
    botonSiguiente.innerHTML = t('Siguiente') + ' <span class="btn-flecha">▶</span>';
    botonSiguiente.addEventListener('click', onSiguiente);
    acciones.appendChild(botonSiguiente);

    if (tutorial.puedeRetroceder) {
        const botonRegresar = document.createElement('button');
        botonRegresar.className = 'btn-regresar';
        botonRegresar.type = 'button';
        botonRegresar.innerHTML = '<span class="btn-flecha">◀</span> ' + t('Regresar');
        botonRegresar.addEventListener('click', onAnterior);
        acciones.appendChild(botonRegresar);
    }

    pantalla.appendChild(acciones);

    contenedor.appendChild(pantalla);
}

export function renderSeleccionRecetas(recetas, contenedor, { onSeleccionar, onAnterior }) {
    contenedor.innerHTML = '';

    const pantalla = document.createElement('div');
    pantalla.className = 'pantalla-cocina fade-in';

    const titulo = document.createElement('h1');
    titulo.className = 'titulo-seleccion';
    titulo.textContent = t('¿Qué vamos a cocinar hoy?');

    const grid = document.createElement('div');
    grid.className = 'recetas-grid';

    recetas.forEach((receta) => {
        const card = document.createElement('div');
        card.className = 'receta-card';

        const nombre = document.createElement('span');
        nombre.className = 'receta-card__nombre';
        nombre.textContent = t(receta.nombre);
        card.appendChild(nombre);

        if (receta.imagenMenu) {
            const img = document.createElement('img');
            img.className = 'receta-card__img';
            img.src = receta.imagenMenu;
            img.alt = receta.nombre;
            card.appendChild(img);
        }

        card.addEventListener('click', () => onSeleccionar(receta.id));
        grid.appendChild(card);
    });

    const botonRegresar = document.createElement('button');
    botonRegresar.className = 'btn-regresar btn-regresar--suelto';
    botonRegresar.type = 'button';
    botonRegresar.innerHTML = '<span class="btn-flecha">◀</span> ' + t('Regresar');
    botonRegresar.addEventListener('click', onAnterior);

    pantalla.appendChild(titulo);
    pantalla.appendChild(grid);
    pantalla.appendChild(botonRegresar);
    contenedor.appendChild(pantalla);
}

export function renderIntroNivel(numero, recipe, contenedor) {
    contenedor.innerHTML = '';

    const pantalla = document.createElement('div');
    pantalla.className = 'pantalla-cocina nivel-intro fade-in';

    const caja = document.createElement('div');
    caja.className = 'nivel-intro__caja';

    const icono = document.createElement('div');
    icono.className = 'nivel-intro__icono';
    icono.textContent = recipe?.emoji ?? '🍽️';

    const titulo = document.createElement('h1');
    titulo.className = 'nivel-intro__titulo';
    titulo.textContent = `${t('NIVEL')} ${numero}`;

    const subtitulo = document.createElement('h2');
    subtitulo.className = 'nivel-intro__subtitulo';
    subtitulo.textContent = textoModoNivel(numero);

    caja.appendChild(icono);
    caja.appendChild(titulo);
    caja.appendChild(subtitulo);
    pantalla.appendChild(caja);
    contenedor.appendChild(pantalla);
}

export function renderNivel(nivel, contenedor, { onSeleccionarIngrediente, onReiniciar, onInicio }) {
    contenedor.innerHTML = '';

    const pantalla = document.createElement('div');
    pantalla.className = 'pantalla-nivel fade-in';

    const fondo = document.createElement('div');
    fondo.className = 'pantalla-nivel__fondo';
    pantalla.appendChild(fondo);

    const vineta = document.createElement('div');
    vineta.className = 'pantalla-nivel__vineta';
    pantalla.appendChild(vineta);

    const header = document.createElement('div');
    header.className = 'nivel-header';

    if (nivel.mostrarRecetaFija) {
        header.appendChild(crearTarjetaReceta(nivel.recipe, nivel.pasos));
    } else {
        const espaciador = document.createElement('div');
        espaciador.className = 'nivel-header__espaciador';
        header.appendChild(espaciador);
    }

    const badge = document.createElement('div');
    badge.className = 'nivel-badge';
    badge.textContent = `${t('NIVEL')} ${nivel.numero}: ${textoModoNivel(nivel.numero)}`;
    header.appendChild(badge);

    const statsBox = document.createElement('div');
    statsBox.className = 'nivel-stats';

    const contadores = document.createElement('div');
    contadores.className = 'nivel-contadores';

    const estrellas = document.createElement('div');
    estrellas.className = 'nivel-contador';
    estrellas.textContent = `⭐ ${nivel.recipe.estrellas}/5`;
    contadores.appendChild(estrellas);

    const textoHintNormal = '💡 ' + t('Sigue los pasos en el orden correcto para completar tu :receta.').replace(':receta', t(nivel.recipe.nombre).toLowerCase());
    const textoHintRecuerda = '💡 ' + t('Recuerda el orden que memorizaste.');
    let hintActual = nivel.ocultaTrasTiempo ? '' : textoHintNormal;

    const hint = document.createElement('div');
    hint.className = 'nivel-hint';
    hint.textContent = hintActual;

    statsBox.appendChild(contadores);
    statsBox.appendChild(hint);
    header.appendChild(statsBox);

    pantalla.appendChild(header);

    let overlayMemoriza = null;
    let contadorGrande = null;
    if (nivel.ocultaTrasTiempo) {
        overlayMemoriza = document.createElement('div');
        overlayMemoriza.className = 'memoriza-overlay fade-in';

        const tituloMemoriza = document.createElement('div');
        tituloMemoriza.className = 'memoriza-overlay__titulo';
        tituloMemoriza.textContent = t('¡Memoriza la receta!');

        const tarjetaGrande = crearTarjetaReceta(nivel.recipe, nivel.pasos);
        tarjetaGrande.classList.add('tarjeta-receta--grande');

        contadorGrande = document.createElement('div');
        contadorGrande.className = 'memoriza-overlay__contador';
        contadorGrande.textContent = `${Math.ceil(nivel.tiempoVisible / 1000)}`;
        tarjetaGrande.appendChild(contadorGrande);

        overlayMemoriza.appendChild(tituloMemoriza);
        overlayMemoriza.appendChild(tarjetaGrande);
        pantalla.appendChild(overlayMemoriza);
    }

    const encimera = document.createElement('div');
    encimera.className = 'encimera';
    if (nivel.ocultaTrasTiempo) {
        encimera.classList.add('encimera--oculta');
    }

    const filaIngredientes = document.createElement('div');
    filaIngredientes.className = 'encimera__ingredientes';

    const plato = document.createElement('div');
    plato.className = 'plato-nivel';

    const platoImg = document.createElement('img');
    platoImg.className = 'plato-nivel__img';
    platoImg.src = nivel.recipe.imagenPlato;
    platoImg.alt = 'Plato';

    const platoContenido = document.createElement('div');
    platoContenido.className = 'plato-nivel__contenido';

    plato.appendChild(platoImg);
    plato.appendChild(platoContenido);

    const idsIngredientes = nivel.getIngredientesEnJuego();

    idsIngredientes.forEach((id) => {
        const info = INGREDIENTES[id];
        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'ingrediente-btn';

        const img = document.createElement('img');
        img.className = 'ingrediente-btn__img';
        img.src = info.img;
        img.alt = info.nombre;

        boton.appendChild(img);

        boton.addEventListener('click', () => {
            const resultado = onSeleccionarIngrediente(id);

            if (resultado.correcto) {
                const indice = platoContenido.childElementCount;
                const numeroPaso = nivel.recipe.pasoActual - 1;
                const esUltimoPaso = numeroPaso === nivel.pasos.length - 1;
                const desfaseX = esUltimoPaso ? 0 : CAPA_DESFASE_X[indice % CAPA_DESFASE_X.length];
                const rotacion = esUltimoPaso ? 0 : CAPA_ROTACION[indice % CAPA_ROTACION.length];

                const capaInfo = resolverCapaPlato(info, numeroPaso, nivel.pasos.length);
                const capa = document.createElement('img');
                capa.className = 'plato-nivel__ingrediente';
                capa.src = capaInfo.src;
                capa.alt = info.nombre;
                capa.style.width = `calc(10vh * ${capaInfo.escala})`;
                capa.style.bottom = `${2.5 + indice * 0.6}vh`;
                capa.style.zIndex = String(indice + 1);
                capa.style.transform = `translateX(calc(-50% + ${desfaseX}px)) rotate(${rotacion}deg)`;
                platoContenido.appendChild(capa);

                if (resultado.completado) {
                    totalStarsReceta += nivel.recipe.estrellas;
                    saveStarsToServer(totalStarsReceta);
                    mostrarCompletado(pantalla, nivel.recipe.nombre);
                }
            } else {
                estrellas.textContent = `⭐ ${nivel.recipe.estrellas}/5`;

                clearTimeout(boton.dataset.feedbackTimeoutId);
                boton.classList.remove('feedback-incorrecto');
                void boton.offsetWidth;
                boton.classList.add('feedback-incorrecto');
                boton.dataset.feedbackTimeoutId = setTimeout(() => {
                    boton.classList.remove('feedback-incorrecto');
                }, 700);

                clearTimeout(hint.dataset.avisoTimeoutId);
                hint.textContent = '⚠️ ' + t('¡Casi! Recuerda el orden de la receta.');
                hint.dataset.avisoTimeoutId = setTimeout(() => {
                    hint.textContent = hintActual;
                }, 1600);
            }
        });

        filaIngredientes.appendChild(boton);
    });

    encimera.appendChild(filaIngredientes);
    encimera.appendChild(plato);
    pantalla.appendChild(encimera);

    const controles = document.createElement('div');
    controles.className = 'nivel-controles';

    const btnInicio = document.createElement('button');
    btnInicio.type = 'button';
    btnInicio.className = 'btn-inicio';
    btnInicio.textContent = '🏠';
    btnInicio.addEventListener('click', onInicio);

    const btnReiniciar = document.createElement('button');
    btnReiniciar.type = 'button';
    btnReiniciar.className = 'btn-reiniciar';
    btnReiniciar.textContent = '↺ ' + t('REINICIAR');
    btnReiniciar.addEventListener('click', onReiniciar);

    controles.appendChild(btnInicio);
    controles.appendChild(btnReiniciar);
    pantalla.appendChild(controles);

    contenedor.appendChild(pantalla);

    if (nivel.ocultaTrasTiempo) {
        let restanteMs = nivel.tiempoVisible;
        const intervaloId = setInterval(() => {
            restanteMs -= 1000;
            if (contadorGrande) {
                contadorGrande.textContent = `${Math.max(0, Math.ceil(restanteMs / 1000))}`;
            }
            if (restanteMs <= 0) {
                clearInterval(intervaloId);
                overlayMemoriza?.remove();
                encimera.classList.remove('encimera--oculta');
                encimera.classList.add('fade-in');
                hintActual = textoHintRecuerda;
                hint.textContent = hintActual;
            }
        }, 1000);
    }
}

export function mostrarFeedback(elemento, esCorrecto) {
    elemento.classList.remove('feedback-correcto', 'feedback-incorrecto');
    elemento.classList.add(esCorrecto ? 'feedback-correcto' : 'feedback-incorrecto');
}
