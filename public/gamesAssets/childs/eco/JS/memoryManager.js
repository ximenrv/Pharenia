function memorySleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

const MemoryManager = {
    sequence: [],
    playerSequence: [],
    colors: ['blue', 'cyan', 'black', 'violet'],
    
    round: 1,
    score: 0,
    record: 0,
    lives: 3,
    mustResetSequence: false,
    
    isPlayingSequence: false,
    canPlayerInput: false,

    elements: {
        bubbles: {},
        sequenceSlots: null,
        healthSegments: [],
        scoreDisplay: null,
        recordDisplay: null
    },

    init() {
        this.colors.forEach(color => {
            const id = 'bubble' + color.charAt(0).toUpperCase() + color.slice(1);
            this.elements.bubbles[color] = document.getElementById(id);
        });

        this.elements.sequenceSlots = document.getElementById('sequenceSlots');
        this.elements.scoreDisplay = document.getElementById('score');
        this.elements.recordDisplay = document.getElementById('record');
        
        this.elements.healthSegments = [
            document.getElementById('life1'),
            document.getElementById('life2'),
            document.getElementById('life3')
        ];

        // Sincronizar el récord inicial usando la variable de Laravel enviada desde Blade
        this.record = window.INITIAL_HIGH_SCORE || 0;
        if (typeof ScoreManager !== 'undefined') {
            ScoreManager.highScore = this.record;
        }

        if (this.elements.recordDisplay) {
            this.elements.recordDisplay.textContent = this.record;
        }

        this.setupEventListeners();
    },

    setupEventListeners() {
        this.colors.forEach(color => {
            const bubble = this.elements.bubbles[color];
            if (bubble) {
                bubble.addEventListener('click', () => this.handleBubbleClick(color));
            }
        });
    },

    startGame() {
        this.sequence = [];
        this.playerSequence = [];
        this.round = 1;
        this.score = 0;
        this.lives = 3;
        this.mustResetSequence = false;

        this.updateHUD();
        this.resetHealthBar();
        this.nextRound();
    },

    async nextRound() {
        this.playerSequence = [];
        this.canPlayerInput = false;
        this.disablePlayerInput();
        this.clearSequenceBar();

        if (this.mustResetSequence) {
            this.sequence = [];
            this.mustResetSequence = false;
        }

        if (this.sequence.length === 0) {
            for (let i = 0; i < 3; i++) {
                const randomColor = this.colors[Math.floor(Math.random() * this.colors.length)];
                this.sequence.push(randomColor);
            }
        } else {
            const randomColor = this.colors[Math.floor(Math.random() * this.colors.length)];
            this.sequence.push(randomColor);
        }

        let dialogueText = "";

        if (this.sequence.length === 3) {
            dialogueText = "¡Observa la secuencia\ncon atención!";
        } else if (this.sequence.length === 8) {
            dialogueText = "Una ultima y probemos\ncon otro patrón.";
            if (window.AssistLumen) AssistLumen.react("cheer", 2000);
            this.mustResetSequence = true;
        } else {
            dialogueText = "¿Ahora que tal una más?";
            if (window.AssistLumen) AssistLumen.react("cheer", 1000);
        }

        if (window.AssistLumen && typeof AssistLumen.sayDialogue === "function") {
            await AssistLumen.sayDialogue(dialogueText, 1800);
        }

        await this.playSequence();
    },

    async playSequence() {
        this.isPlayingSequence = true;
        this.canPlayerInput = false;
        this.disablePlayerInput();
        for (let i = 0; i < this.sequence.length; i++) {
            const color = this.sequence[i];
        
            if (window.SoundManager && typeof SoundManager.playColorSound === "function") {
                SoundManager.playColorSound(color);
            }
            await this.activateBubble(color, 500);
            await memorySleep(250);
        }

        if (window.AssistLumen && typeof AssistLumen.sayDialogue === "function") {
            await AssistLumen.sayDialogue("¡Tu turno!", 1200);
        }
        this.isPlayingSequence = false;
        this.canPlayerInput = true;
        this.enablePlayerInput();
    },

    activateBubble(color, duration = 300) {
        return new Promise(resolve => {
            const bubble = this.elements.bubbles[color];
            if (!bubble) return resolve();
            bubble.classList.add('active');
        
            setTimeout(() => {
                bubble.classList.remove('active');
                resolve();
            }, duration);
        });
    },

    handleBubbleClick(color) {
        if (!this.canPlayerInput || this.isPlayingSequence) return;
        this.activateBubble(color, 250);
        this.playerSequence.push(color);
        const currentIndex = this.playerSequence.length - 1;
        this.addMiniBubbleToBar(color);
    
        if (this.playerSequence[currentIndex] !== this.sequence[currentIndex]) {
            this.handleError();
            return;
        }

        if (window.SoundManager && typeof SoundManager.playColorSound === "function") {
            SoundManager.playColorSound(color);
        }
        if (this.playerSequence.length === this.sequence.length) {
            this.handleRoundSuccess();
        }
    },

    async handleRoundSuccess() {
        this.canPlayerInput = false;
        this.disablePlayerInput();

    
        const pointsGained = 10;
        this.score += pointsGained;
        this.updateHUD();
        this.round++;
    
        if (window.AssistLumen && typeof AssistLumen.sayDialogue === "function") {
            await AssistLumen.sayDialogue("¡Buen trabajo!", 1200, "cheer");
        }

        await memorySleep(300);
        this.nextRound();
    },
    
    async handleError() {
        this.canPlayerInput = false;
        this.disablePlayerInput();
        this.lives--;
        this.updateHealthBar();

    
        if (window.SoundManager && typeof SoundManager.playErrorSound === "function") {
            SoundManager.playErrorSound();
        }

        if (this.lives > 0) {
            if (window.AssistLumen && typeof AssistLumen.sayDialogue === "function") {
                await AssistLumen.sayDialogue("¡Ups! Inténtalo de nuevo.", 1600, "wrong");
            }
            this.playerSequence = [];
            this.clearSequenceBar();
            this.playSequence();
    
        } else {
            this.gameOver()
        }
    },

    disablePlayerInput() {
        const board = document.getElementById("memoryBoard") || document.querySelector(".bubbles-container");
        if (board) {
            board.style.pointerEvents = "none";
        }
    },

    enablePlayerInput() {
        const board = document.getElementById("memoryBoard") || document.querySelector(".bubbles-container");
        if (board) {
            board.style.pointerEvents = "auto";
        }
    },

    addMiniBubbleToBar(color) {
        if (!this.elements.sequenceSlots) return;

        const mini = document.createElement('div');
        mini.className = `miniBubble ${color}`;
        this.elements.sequenceSlots.appendChild(mini);
        this.elements.sequenceSlots.parentElement.scrollLeft = this.elements.sequenceSlots.parentElement.scrollWidth;
    },

    clearSequenceBar() {
        if (this.elements.sequenceSlots) {
            this.elements.sequenceSlots.innerHTML = '';
        }
    },

    resetHealthBar() {
        this.elements.healthSegments.forEach(segment => {
            if (segment) segment.classList.remove('lost');
        });
    },

    updateHealthBar() {
        const indexToLose = this.lives;
        if (this.elements.healthSegments[indexToLose]) {
            this.elements.healthSegments[indexToLose].classList.add('lost');
        }
    },

    updateHUD() {
        if (this.elements.scoreDisplay) {
            this.elements.scoreDisplay.textContent = this.score;
        }
        
        const currentRecord = (typeof ScoreManager !== 'undefined') ? ScoreManager.highScore : this.record;

        if (this.score > currentRecord) {
            this.record = this.score;

            if (typeof ScoreManager !== 'undefined') {
                ScoreManager.highScore = this.score;
            }

            if (typeof updateRecordHUD === "function") {
                updateRecordHUD();
            } else if (this.elements.recordDisplay) {
                this.elements.recordDisplay.textContent = this.score;
            }

            // Guardar en la base de datos de Laravel
            if (typeof saveScoreToDatabase === "function") {
                saveScoreToDatabase(this.score);
            }
        }
    },

    async gameOver() {
        if (window.AssistLumen && typeof AssistLumen.sayDialogue === "function") {
            await AssistLumen.sayDialogue("¡Buen intento!", 2000, "wrong");
        }

        if (typeof showGameOver === "function") {
            showGameOver();
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    MemoryManager.init();
});

window.MemoryManager = MemoryManager;