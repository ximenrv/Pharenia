export const TUTORIAL_PASOS = [
    {
        personaje: 'assets/personajes/lumendedochef.png',
        texto: '¡Bienvenido a Sigue la Receta!',
    },
    {
        personaje: 'assets/personajes/lumenhappychef.png',
        texto: 'Aquí aprenderás a preparar diferentes recetas. Observa con atención, recuerda cada paso y sigue la secuencia correcta para completar cada platillo',
    },
    {
        personaje: 'assets/personajes/lumendedochef.png',
        texto: 'Elige una de estas comidas para comenzar',
    },
];

export const INGREDIENTES = {
    pan: { nombre: 'Pan', genero: 'el', img: 'assets/ingredients/sandiwch/pan.png', imgPlato: 'assets/ingredients/sandiwch/pan2.png' },
    jamon: { nombre: 'Jamón', genero: 'el', img: 'assets/ingredients/sandiwch/jamon.png', imgPlato: 'assets/ingredients/sandiwch/jamón2.png' },
    queso: { nombre: 'Queso', genero: 'el', img: 'assets/ingredients/sandiwch/queso.png', imgPlato: 'assets/ingredients/sandiwch/queso2.png' },
    lechuga: { nombre: 'Lechuga', genero: 'la', img: 'assets/ingredients/sandiwch/lechuga.png' },
    tomate: { nombre: 'Tomate', genero: 'el', img: 'assets/ingredients/sandiwch/tomate.png', imgPlato: 'assets/ingredients/sandiwch/tomate2.png' },
    mayonesa: { nombre: 'Mayonesa', genero: 'la', img: 'assets/ingredients/mayonesa.png' },
    cebolla: { nombre: 'Cebolla', genero: 'la', img: 'assets/ingredients/cebolla.png' },
    pepinillo: { nombre: 'Pepinillo', genero: 'el', img: 'assets/ingredients/pepinillo.png' },
    panham: {
        nombre: 'Pan',
        genero: 'el',
        img: 'assets/ingredients/hamburguesa/panham.png',
        imgPlatoInicio: 'assets/ingredients/hamburguesa/panabajo.png',
        imgPlatoFin: 'assets/ingredients/hamburguesa/panarriba.png',
        escalaPlatoFin: 1.4,
    },
    carne: { nombre: 'Carne', genero: 'la', img: 'assets/ingredients/hamburguesa/carne.png' },
};

export const RECIPES = [
    {
        id: 'sandwich',
        nombre: 'Sándwich',
        emoji: '🍳',
        imagenMenu: 'assets/ingredients/sandiwch/sandiwch.png',
        imagenPlato: 'assets/ingredients/plato.png',
        niveles: {
            1: {
                pasos: ['pan', 'jamon', 'queso', 'lechuga', 'tomate', 'pan'],
            },
            2: {
                pasos: ['pan', 'jamon', 'queso', 'lechuga', 'pan'],
                tiempoVisible: 5000,
            },
            3: {
                pasos: ['pan', 'jamon', 'queso', 'lechuga', 'tomate', 'mayonesa', 'pan'],
                distractores: ['cebolla', 'pepinillo'],
            },
        },
    },
    {
        id: 'hamburguesa',
        nombre: 'Hamburguesa',
        emoji: '🍔',
        imagenMenu: 'assets/ingredients/hamburguesa/hamburguesa.png',
        imagenPlato: 'assets/ingredients/plato.png',
        niveles: {
            2: {
                pasos: ['panham', 'carne', 'queso', 'lechuga', 'tomate', 'panham'],
                tiempoVisible: 10000,
            },
        },
    },
];
