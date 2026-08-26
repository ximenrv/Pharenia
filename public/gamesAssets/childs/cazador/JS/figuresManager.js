const FiguresManager = {
    figures: ['circle', 'square', 'triangle', 'star', 'heart'],
    boardState: [], 
    elements: {
        board: null,
        slots: []
    },

    init() {
        this.elements.board = document.getElementById('memoryBoard');
        if (this.elements.board) {
            // Obtener o crear los 9 slots (3x3) dentro del tablero
            this.setupSlots();
        }
    },

    setupSlots() {
        if (!this.elements.board) return;
        this.elements.board.innerHTML = '';
        this.elements.slots = [];

        for (let i = 0; i < 9; i++) {
            const slot = document.createElement('div');
            slot.className = 'boardSlot';
            slot.dataset.index = i;
            this.elements.board.appendChild(slot);
            this.elements.slots.push(slot);
        }
    },

    // Genera un nuevo conjunto de 9 figuras asegurando que las 5 estén presentes
    generateBoard() {
        // 1. Garantizar una figura de cada tipo (5 figuras)
        let newFigures = [...this.figures];

        // 2. Llenar los 4 espacios restantes de forma aleatoria
        for (let i = 0; i < 4; i++) {
            const randomFig = this.figures[Math.floor(Math.random() * this.figures.length)];
            newFigures.push(randomFig);
        }

        // 3. Mezclar aleatoriamente el arreglo de 9 elementos (Fisher-Yates)
        for (let i = newFigures.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [newFigures[i], newFigures[j]] = [newFigures[j], newFigures[i]];
        }

        this.boardState = newFigures;
        this.renderBoard();
    },

    // Renderiza las burbujas en el DOM
    renderBoard() {
        this.elements.slots.forEach((slot, index) => {
            slot.innerHTML = '';
            const figureType = this.boardState[index];

            if (figureType) {
                const bubble = document.createElement('div');
                bubble.className = `colorBubble ${figureType}`;
                bubble.dataset.figure = figureType;
                bubble.dataset.index = index;
                bubble.addEventListener('click', (e) => {
                    if (window.FiguresGameEngine) {
                        window.FiguresGameEngine.handleBubbleClick(figureType, index, bubble);
                    }
                });

                slot.appendChild(bubble);
            }
        });
    },

    // Ejecuta la animación de explosión y remueve la figura del tablero
    popBubble(index) {
        return new Promise((resolve) => {
            const slot = this.elements.slots[index];
            if (!slot) return resolve();

            const bubble = slot.querySelector('.colorBubble');
            if (bubble) {
                bubble.classList.add('popping');
                setTimeout(() => {
                    slot.innerHTML = '';
                    this.boardState[index] = null;
                    resolve();
                }, 350);
            } else {
                resolve();
            }
        });
    },

    // Comprueba cuántas burbujas quedan en el tablero de una figura específica
    countRemainingOf(figure) {
        return this.boardState.filter(type => type === figure).length;
    },

    getRemainingFigures() {
        const uniqueFigures = new Set(this.boardState.filter(type => type !== null));
        return Array.from(uniqueFigures);
    },

    disableBoard() {
        if (this.elements.board) {
            this.elements.board.style.pointerEvents = 'none';
        }
    },

    enableBoard() {
        if (this.elements.board) {
            this.elements.board.style.pointerEvents = 'auto';
        }
    }
};

FiguresManager.generateBoardWithPop = async function() {
    this.generateBoard();
    const bubbles = document.querySelectorAll(".colorBubble");

    bubbles.forEach(bubble => {
        bubble.style.opacity = "0";
    });

    for (let i = 0; i < bubbles.length; i++) {
        const bubble = bubbles[i];
        
        bubble.style.opacity = "1"; 
        bubble.classList.add("popIn");

        setTimeout(() => {
            bubble.classList.remove("popIn");
        }, 400);

        await new Promise(resolve => setTimeout(resolve, 110));
    }
    startTimer();
};

document.addEventListener('DOMContentLoaded', () => {
    FiguresManager.init();
});

window.FiguresManager = FiguresManager;