function engineSleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function __(key) {
    return (window.translations && window.translations[key]) ? window.translations[key] : key;
}

const FiguresGameEngine = {
    targetFigure: null,
    round: 1,
    score: 0,
    record: 0,
    lives: 3,
    
    canPlayerInput: false,

    elements: {
        healthSegments: [],
        scoreDisplay: null,
        recordDisplay: null
    },

    init() {
        this.elements.scoreDisplay = document.getElementById('score');
        this.elements.recordDisplay = document.getElementById('record');
        
        this.elements.healthSegments = [
            document.getElementById('life1'),
            document.getElementById('life2'),
            document.getElementById('life3')
        ];

        this.record = window.INITIAL_HIGH_SCORE || 0;
        if (typeof ScoreManager !== 'undefined') {
            ScoreManager.highScore = this.record;
        }

        if (this.elements.recordDisplay) {
            this.elements.recordDisplay.textContent = this.record;
        }
    },

    startGame() {
        this.round = 1;
        this.score = 0;
        this.lives = 3;
    
        if (typeof ScoreManager !== 'undefined') {
            ScoreManager.score = 0;
        }

        this.updateHUD();
        this.resetHealthBar();
    
        if (window.FiguresManager) {
        
            const remaining = FiguresManager.getRemainingFigures();
        
            if (!remaining || remaining.length === 0) {
                FiguresManager.generateBoard();
            }
        }
        this.startNextTarget();
    },

    async startNextTarget() {
        this.canPlayerInput = false;
        if (window.FiguresManager) FiguresManager.disableBoard();
        const remainingFigures = window.FiguresManager ? FiguresManager.getRemainingFigures() : [];
    
        // Si ya no quedan figuras en el tablero
        if (remainingFigures.length === 0) {
            if (window.AssistLumen) {
                AssistLumen.clearFigure();
                await AssistLumen.sayDialogue( __("¡Parece que las\nburbujas volvieron!"), 1800, "cheer");
            }

            this.round++;
        
            if (window.FiguresManager && typeof FiguresManager.generateBoardWithPop === "function") {
                await FiguresManager.generateBoardWithPop();
            } else if (window.FiguresManager) {
                FiguresManager.generateBoard();
            }
            return this.startNextTarget();
        }

        const randomIndex = Math.floor(Math.random() * remainingFigures.length);
        this.targetFigure = remainingFigures[randomIndex];
    
    
        if (window.AssistLumen) {
            await AssistLumen.setFigure(this.targetFigure);
            await engineSleep(400); 
            await AssistLumen.sayDialogue( __("¡Caza todas las figuras\nque tienen esta forma!"), 1400);
    
        }
        this.canPlayerInput = true;
        if (window.FiguresManager) FiguresManager.enableBoard();
    },

    async handleBubbleClick(clickedFigure, index, bubbleElement) {
        if (!this.canPlayerInput) return;

        // --- ACIERTO ---
        if (clickedFigure === this.targetFigure) {
            if (window.SoundManager && typeof SoundManager.playScore === "function") {
                SoundManager.playScore();
            }

            if (window.FiguresManager) {
                await FiguresManager.popBubble(index);
            }

            const pointsGained = 10;
            this.score += pointsGained;
            if (typeof ScoreManager !== 'undefined') {
                ScoreManager.score = this.score;
            }
            this.updateHUD();

            const remainingOfTarget = window.FiguresManager ? FiguresManager.countRemainingOf(this.targetFigure) : 0;

            if (remainingOfTarget === 0) {
                this.canPlayerInput = false;
                if (window.FiguresManager) FiguresManager.disableBoard();

                if (window.AssistLumen) {
                    await AssistLumen.sayDialogue( __("¡Excelente!, ahora..."), 1200, "cheer");
                }
                
                await engineSleep(300);
                this.startNextTarget();
            }

        // --- ERROR ---
        } else {
            this.handleError();
        }
    },

    async handleError() {
        this.canPlayerInput = false;
        if (window.FiguresManager) FiguresManager.disableBoard();

        this.lives--;
        this.updateHealthBar();

        if (window.SoundManager && typeof SoundManager.playErrorSound === "function") {
            SoundManager.playErrorSound();
        }

        if (this.lives > 0) {
            if (window.AssistLumen && typeof AssistLumen.sayDialogue === "function") {
                await AssistLumen.sayDialogue( __("¡Hay una presa mejor!\nInténtalo de nuevo."), 1500, "wrong");
            }
            this.canPlayerInput = true;
            if (window.FiguresManager) FiguresManager.enableBoard();
        } else {
            this.gameOver();
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

            if (typeof saveScoreToDatabase === "function") {
                saveScoreToDatabase(this.score);
            }
        }
    },

    async gameOver() {
        if (window.AssistLumen) {
            AssistLumen.clearFigure();
            if (typeof AssistLumen.sayDialogue === "function") {
                await AssistLumen.sayDialogue( __("¡Gran trabajo! pero te\nhas quedado sin vidas."), 2000, "wrong");
            }
        }

        if (typeof showGameOver === "function") {
            showGameOver();
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    FiguresGameEngine.init();
});

window.FiguresGameEngine = FiguresGameEngine;