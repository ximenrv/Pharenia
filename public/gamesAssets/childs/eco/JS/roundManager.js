const ScoreManager = {
    score: 0,
    highScore: window.INITIAL_HIGH_SCORE || 0
};


function __(key) {
    return (window.translations && window.translations[key]) ? window.translations[key] : key;
}

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
                game: 'record_Eco',
                score: newScore,
                child_id: window.ACTIVE_CHILD_ID || null
            })
        });

        const data = await response.json();
        if (data.success && data.highScore !== undefined) {
            ScoreManager.highScore = data.highScore;
            window.INITIAL_HIGH_SCORE = data.highScore;
            updateRecordHUD();
        }
    } catch (error) {
        console.error("Error al guardar el récord en la BD:", error);
    }
};

function updateScoreHUD() {
    const scoreElem = document.getElementById("score");
    if (scoreElem) scoreElem.textContent = ScoreManager.score;
};

function updateRecordHUD() {
    const recordElem = document.getElementById("record");
    if (recordElem) recordElem.textContent = ScoreManager.highScore;
};

function addScore(points) {
    ScoreManager.score += points;
    if (ScoreManager.score > ScoreManager.highScore) {
        ScoreManager.highScore = ScoreManager.score;
        saveScoreToDatabase(ScoreManager.score);
        updateRecordHUD();
    }
    updateScoreHUD();
};

function setGameOverMessage() {
    const score = ScoreManager.score;
    const title = document.getElementById("messageTitle");
    const text = document.getElementById("messageText");

    if (!title || !text) return;

    if (score < 300) {
        title.textContent = __("¡Bien hecho!");
        text.textContent = __("Sigue entrenando tu memoria para llegar más lejos.");
    } else if (score < 800) {
        title.textContent = __("¡Muy bien!");
        text.textContent = __("Demostraste una increible gran memoria.");
    } else if (score < 1100) {
        title.textContent = __("¡Increíble!");
        text.textContent = __("¡Tu memoria escucha el Eco de los Colores!");
    } else {
        title.textContent = __("¡Asombroso!");
        text.textContent = __("¡Haz batido tu record");
    }
};

async function showGameOver() {
    if (MemoryManager.score > ScoreManager.highScore) {
        ScoreManager.highScore = MemoryManager.score;
        await saveScoreToDatabase(MemoryManager.score);
    }

    const screen = document.getElementById("gameOverScreen");
    const container = document.getElementById("gameOverContainer");
    const returnBtn = document.getElementById("returnButton");
    const retryBtn = document.getElementById("retryButton");
    const gameOverLumen = document.getElementById("gameOverLumen");
    const title = document.getElementById("gameOverTitle");

    if (!screen) return;

    screen.classList.add("show");
    title.classList.add("showGameOverTitle");
    
    await sleep(600);
    container.classList.add("showContainer");
    setGameOverMessage();

    document.getElementById("gameOverMessage")?.classList.add("showMessage");

    await sleep(300);
    document.getElementById("scoreBlock")?.classList.add("showStat");
    await sleep(150);
    document.getElementById("recordBlock")?.classList.add("showStat");
    await sleep(150);
    document.getElementById("waveBlock")?.classList.add("showStat");
    
    if (document.getElementById("finalScore")) document.getElementById("finalScore").textContent = MemoryManager.score;
    if (document.getElementById("finalRecord")) document.getElementById("finalRecord").textContent = ScoreManager.highScore;
    if (document.getElementById("finalWave")) document.getElementById("finalWave").textContent = MemoryManager.round;

    if (returnBtn) returnBtn.classList.add("show");
    if (gameOverLumen) gameOverLumen.classList.add("showLumen");
    if (retryBtn) retryBtn.classList.add("showRetry");
};

async function hideGameOver() {
    const screen = document.getElementById("gameOverScreen");
    const container = document.getElementById("gameOverContainer");

    if (container) container.classList.add("hideContainer");
    await sleep(400);
    if (screen) screen.classList.remove("show");
    if (container) {
        container.classList.remove("showContainer");
        container.classList.remove("hideContainer");
    }
};

function returnToMenu() {
    AudioManager.bgsound.pause();
    window.location.href = window.GAME_MENU_URL || '/';
};

// Event Listeners para los botones de Game Over
document.addEventListener("DOMContentLoaded", () => {
    updateRecordHUD();

    const returnBtn = document.getElementById("returnButton");
    if (returnBtn) {
        returnBtn.addEventListener("click", () => {
            if (window.AudioManager) AudioManager.playButton();
            returnToMenu();
        });
    }
});