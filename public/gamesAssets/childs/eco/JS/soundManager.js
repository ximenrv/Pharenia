const AudioManager = {
    fadeInterval: null,
    button: new Audio(`${window.GAME_ASSETS_PATH}/sounds/confirm_button.mp3`),
    wrong: new Audio(`${window.GAME_ASSETS_PATH}/sounds/liveLost.mp3`),
    score: new Audio(`${window.GAME_ASSETS_PATH}/sounds/point.mp3`),
    bgsound: new Audio(`${window.GAME_ASSETS_PATH}/sounds/underWater.mp3`),
    
    colorSounds: {
        blue: new Audio(`${window.GAME_ASSETS_PATH}/sounds/ShineBlue.mp3`),
        cyan: new Audio(`${window.GAME_ASSETS_PATH}/sounds/ShineCyan.mp3`),
        black: new Audio(`${window.GAME_ASSETS_PATH}/sounds/ShineBlack.mp3`),
        violet: new Audio(`${window.GAME_ASSETS_PATH}/sounds/ShineViolet.mp3`)
    }
};

// Configuración de volúmenes
AudioManager.bgsound.volume = 0.10;
AudioManager.bgsound.loop = true;
AudioManager.button.volume = 0.25;
AudioManager.wrong.volume = 0.35;
AudioManager.score.volume = 0.25;

AudioManager.playScore = function() {
    this.score.currentTime = 0;
    this.score.play().catch(() => {});
};

AudioManager.playButton = function() {
    this.button.currentTime = 0;
    this.button.play().catch(() => {});
};

AudioManager.playBackground = function() {
    if (this.bgsound.paused) {
        this.bgsound.play().catch(e => {
            console.warn("Audio de fondo bloqueado por el navegador hasta interactuar:", e);
        });
    }
};


AudioManager.playColorSound = function(color) {
    if (this.colorSounds[color]) {
        // Clonar el nodo permite reproducciones consecutivas rápidas
        const soundClone = this.colorSounds[color].cloneNode();
        soundClone.volume = 0.15;
        soundClone.play().catch(e => console.warn("Audio bloqueado por navegador:", e));
        return soundClone;
    } else {
        this.playScore();
        return null;
    }
};

AudioManager.playWrong = function() {
    this.wrong.currentTime = 0;
    this.wrong.play().catch(e => console.warn("Audio bloqueado por navegador:", e));
};

AudioManager.playErrorSound = function() {
    this.playWrong();
};

// Exponer globalmente con ambos nombres por compatibilidad
window.AudioManager = AudioManager;
window.SoundManager = AudioManager;