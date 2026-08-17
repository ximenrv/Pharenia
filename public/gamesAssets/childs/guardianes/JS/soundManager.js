const AudioManager = {
    fadeInterval: null,
    ocean: new Audio(`${window.GAME_ASSETS_PATH}/sounds/ocean.mp3`),
    button: new Audio(`${window.GAME_ASSETS_PATH}/sounds/confirm_button.mp3`),
    wrong: new Audio(`${window.GAME_ASSETS_PATH}/sounds/error.mp3`),
    score: new Audio(`${window.GAME_ASSETS_PATH}/sounds/point.mp3`),
    timeOver: new Audio(`${window.GAME_ASSETS_PATH}/sounds/timeup.mp3`),
    grab: new Audio(`${window.GAME_ASSETS_PATH}/sounds/trash.mp3`)
};

AudioManager.ocean.loop = true;
AudioManager.ocean.volume = 0.07;

AudioManager.button.volume = 0.25;
AudioManager.wrong.volume = 0.08;
AudioManager.score.volume = 0.30;
AudioManager.grab.volume = 0.05;
AudioManager.timeOver.volume = 0.15;

window.addEventListener("load",()=>{
    // Nota: Algunos navegadores bloquean la reproducción automática sin interacción previa del usuario
    AudioManager.ocean.play().catch(e => console.log("Autoplay bloqueado hasta interacción:", e));
});

AudioManager.playScore = function(){
    this.score.currentTime = 0;
    this.score.play();
}

AudioManager.playWrong = function(){
    this.wrong.currentTime = 0;
    this.wrong.play();
}

AudioManager.playButton = function(){
    this.button.currentTime = 0;
    this.button.play();
}

AudioManager.playGrab = function(){
    this.grab.currentTime = 0;
    this.grab.play();
}

AudioManager.playTimeOver = function(){
    this.timeOver.currentTime = 0;
    this.timeOver.play();
}

AudioManager.waveOcean = function(targetVolume, duration = 1000){
    clearInterval(this.fadeInterval);
    const start = this.ocean.volume;
    const diff = targetVolume - start;
    const steps = 20;
    let current = 0;
    this.fadeInterval = setInterval(()=>{
        current++;
        this.ocean.volume = start + (diff * current / steps);
        if(current >= steps){
            clearInterval(this.fadeInterval);
            this.ocean.volume = targetVolume;
        }
    }, duration / steps);
};

AudioManager.waveCrash = async function(){
    this.waveOcean(0.40,700);
    await sleep(800);
    this.waveOcean(0.07,1200);
};