const waveManager = {
    currentWave: 1,
    active: false,
    trashPerWave: 5,
    playerCanInteract: false
};

function sleep(ms){
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function startFirstWave(){
    resetTimer();
    updateWaveHUD();
    waveManager.trashPerWave = 5 + (waveManager.currentWave - 1) * 2;
    await spawnWave();
    await sleep(1100);
    startAssistant();
}

async function startNextWave(){
    updateWaveHUD();
    resetTimer();
    startTimer();
    waveManager.trashPerWave = 5 + (waveManager.currentWave - 1) * 2;
    await spawnWave();
}

async function spawnWave(){
    await sleep(700);
    waveManager.active = true;
    waveManager.playerCanInteract = false;
    AudioManager.waveCrash();
    
    const ocean = document.getElementById("ocean") || document.querySelector(".ocean");
    if(ocean) ocean.classList.add("highTide");
    
    await sleep(1100);
    if(ocean) ocean.classList.remove("highTide");
    
    for(let i = 0; i < waveManager.trashPerWave; i++){
        createTrash();
        await sleep(40);
    }
    await sleep(1200);
    showBins();
    waveManager.playerCanInteract = true;
    waveManager.active = false;
}