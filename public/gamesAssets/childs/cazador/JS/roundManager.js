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
                game: 'record_Cazador',
                score: newScore
            })
        });

        const data = await response.json();
        if (data.success) {
            if (data.highScore !== undefined) {
                ScoreManager.highScore = data.highScore;
                window.INITIAL_HIGH_SCORE = data.highScore;
            }
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

function setGameOverMessage() {
    const currentScore = window.FiguresGameEngine ? FiguresGameEngine.score : ScoreManager.score;
    const title = document.getElementById("messageTitle");
    const text = document.getElementById("messageText");

    if (!title || !text) return;

    if (currentScore < 100) {
        title.textContent = __("¡Buen intento!");
        text.textContent = __("Sigue practicando para cazar más figuras.");
    } else if (currentScore < 300) {
        title.textContent = __("¡Muy bien!");
        text.textContent = __("Demostraste una habilidad increible.");
    } else if (currentScore < 600) {
        title.textContent = __("¡Increíble!");
        text.textContent = __("¡Tienes el talento para ser un Cazador de Burbujas!");
    } else {
        title.textContent = __("¡Asombroso!");
        text.textContent = __("¡Haz batido tu récord!");
    }
};

async function showGameOver() {
    const engine = window.FiguresGameEngine || {};
    const currentScore = engine.score || 0;
    const currentRound = engine.round || 1;

    if (currentScore > ScoreManager.highScore) {
        ScoreManager.highScore = currentScore;
        await saveScoreToDatabase(currentScore);
    }

    const screen = document.getElementById("gameOverScreen");
    const container = document.getElementById("gameOverContainer");
    const returnBtn = document.getElementById("returnButton");
    const retryBtn = document.getElementById("retryButton");
    const gameOverLumen = document.getElementById("gameOverLumen");

    if (!screen) return;

    screen.classList.add("show");
    
    await sleep(150);
    if (container) container.classList.add("showContainer");
    setGameOverMessage();

    // Actualización de valores
    if (document.getElementById("finalScore")) document.getElementById("finalScore").textContent = currentScore;
    if (document.getElementById("finalRecord")) document.getElementById("finalRecord").textContent = ScoreManager.highScore;
    if (document.getElementById("finalWave")) document.getElementById("finalWave").textContent = currentRound;

    document.getElementById("gameOverTitle")?.classList.add("showGameOverTitle");
    document.getElementById("gameOverMessage")?.classList.add("showMessage");

    await sleep(250);
    document.getElementById("scoreBlock")?.classList.add("showStat");
    await sleep(120);
    document.getElementById("recordBlock")?.classList.add("showStat");
    await sleep(120);
    document.getElementById("waveBlock")?.classList.add("showStat");

    if (returnBtn) returnBtn.classList.add("showReturn");
    if (document.getElementById("gameOverStar")) document.getElementById("gameOverStar").classList.add("showStar");
    if (retryBtn) retryBtn.classList.add("showRetry");
    if (gameOverLumen) gameOverLumen.classList.add("showLumen");
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
    if (window.AudioManager && window.AudioManager.bgsound) {
        AudioManager.bgsound.pause();
    }
    window.location.href = window.GAME_MENU_URL || '/';
};

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