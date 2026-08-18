const ScoreManager = {
    score: 0,
    highScore: window.INITIAL_HIGH_SCORE || 0
};

updateRecordHUD();

async function saveScoreToDatabase(newScore) {
    if (!window.UPDATE_RECORD_URL || !window.CSRF_TOKEN) return;

    try {
        const response = await fetch(window.UPDATE_RECORD_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                game: 'record_Guardianes', // Nombre de la columna en la BD
                score: newScore
            })
        });

        const data = await response.json();
        if (data.success && data.updated) {
            ScoreManager.highScore = data.highScore;
            updateRecordHUD();
        }
    } catch (error) {
        console.error("Error al guardar el récord en la BD:", error);
    }
};

function setGameOverMessage(){
    const score = ScoreManager.score;
    const title = document.getElementById("messageTitle");
    const text = document.getElementById("messageText");
    if(score < 30){
        title.textContent = "¡No te rindas!";
        text.textContent = "Estoy seguro de que superaras tu record";
    }else if(score < 80){
        title.textContent = "¡Buen trabajo!";
        text.textContent = "Cada basura reciclada te hace más rapido";
    }else if(score < 150){
        title.textContent = "¡Increíble!";
        text.textContent = "Estás convirtiéndote en un verdadero Guardián.";
    }else{
        title.textContent = "¡Fantástico!";
        text.textContent = "¡Eres un auténtico Guardián del Planeta!";
    }
};

const TimerManager = {
    maxTime: 50,
    currentTime: 50,
    interval: null,
    running: false
};

function updateTimerHUD(){
    const seconds = String(TimerManager.currentTime).padStart(2,"0");
    document.getElementById("timerValue").textContent =`00:${seconds}`;
};

function updateWaveHUD(){
    document.getElementById("waveValue").textContent = waveManager.currentWave;
};

function addScore(points){
    ScoreManager.score += points;
    if(ScoreManager.score > ScoreManager.highScore){
        ScoreManager.highScore = ScoreManager.score;
        updateRecordHUD();
        saveScoreToDatabase(ScoreManager.score);
    }
    updateScoreHUD();
};

function updateScoreHUD(){
    document.getElementById("score").textContent = ScoreManager.score;
};

function updateRecordHUD(){
    document.getElementById("record").textContent = ScoreManager.highScore;
};

function startTimer(){
    if(TimerManager.running) return;
    TimerManager.running = true;
    TimerManager.interval = setInterval(()=>{
        TimerManager.currentTime--;
        updateTimerHUD();
        if(TimerManager.currentTime <= 0){
            stopTimer();
            onTimerFinished();
        }
    },1000);
};

function stopTimer(){
    clearInterval(TimerManager.interval);
    TimerManager.interval = null;
    TimerManager.running = false;
};

function resetTimer(){
    stopTimer();
    TimerManager.currentTime = TimerManager.maxTime;
    updateTimerHUD();
};

function onTimerFinished(){
    stopTimer();
    waveManager.playerCanInteract = false;
    AudioManager.playTimeOver();
    AudioManager.ocean.volume = 0.001;
    showGameOver();
};

async function showGameOver(){
    const title = document.getElementById("gameOverTitle");
    const container = document.getElementById("gameOverContainer");
    const button = document.getElementById("returnButton");
    const screen = document.getElementById("gameOverScreen");
    const scoreBlock = document.getElementById("scoreBlock");
    const recordBlock = document.getElementById("recordBlock");
    const waveBlock = document.getElementById("waveBlock");
    const gameOverLumen = document.getElementById("gameOverLumen");
    const retryButton = document.getElementById("retryButton");

    screen.classList.add("show");
    title.classList.remove("hideGameOverTitle");
    title.classList.add("showGameOverTitle");
    await sleep(1000);
    title.classList.remove("showGameOverTitle");
    title.classList.add("hideGameOverTitle");
    await sleep(400);
   container.classList.add("showContainer");
    await sleep(450);
    setGameOverMessage();
    await sleep(250);
    document.getElementById("gameOverMessage").classList.add("showMessage");
    await sleep(800);
    scoreBlock.classList.add("showStat");
    await sleep(200);
    recordBlock.classList.add("showStat");
    await sleep(200);
    waveBlock.classList.add("showStat");
    await sleep(350);
    document.getElementById("finalScore").textContent = ScoreManager.score;
    document.getElementById("finalRecord").textContent = ScoreManager.highScore;
    document.getElementById("finalWave").textContent = waveManager.currentWave;
    button.classList.add("showReturn");
    await sleep(800);
    button.classList.add("show");
    await sleep(100);
    button.classList.remove("showReturn");
    await sleep(250);
    gameOverLumen.classList.add("showLumen");
    await sleep(450);
    retryButton.classList.add("showRetry");
};

document.getElementById("retryButton").addEventListener("click", async ()=>{
    AudioManager.playButton();
    await restartGame();
});

document.getElementById("returnButton").addEventListener("click", async ()=>{
    AudioManager.playButton();
    await hideGameOver();
    returnToMenu();
});

async function restartGame(){
    await hideGameOver();
    ScoreManager.score = 0;
    updateScoreHUD();
    waveManager.currentWave = 1;
    updateWaveHUD();
    resetTimer();
    clearTrash();
    await sleep(1200);
    await startNextWave();
};

function returnToMenu(){
    AudioManager.ocean.pause();
    window.location.href = window.GAME_MENU_URL || '/';
};

function clearTrash(){
    trashObjects.forEach(trash =>{
        trash.element.remove();
    });
    trashObjects.length = 0;
};

async function hideGameOver(){
    const screen = document.getElementById("gameOverScreen");
    const container = document.getElementById("gameOverContainer");
    const returnButton = document.getElementById("returnButton");
    const retryButton = document.getElementById("retryButton");
    const message = document.getElementById("gameOverMessage");
    const scoreBlock = document.getElementById("scoreBlock");
    const recordBlock = document.getElementById("recordBlock");
    const waveBlock = document.getElementById("waveBlock");
    const lumen = document.getElementById("gameOverLumen");
    const title = document.getElementById("gameOverTitle");

    title.classList.remove("showGameOverTitle");
    container.classList.add("hideContainer");
    await sleep(650);
    screen.classList.remove("show");
    container.classList.remove("showContainer");
    container.classList.remove("hideContainer");
    AudioManager.ocean.volume = 0.01;
    screen.classList.remove("show");
};