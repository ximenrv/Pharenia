import { Game } from './Game.js';

document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.getElementById('app');
    const game = new Game(contenedor);
    game.iniciar();
});
