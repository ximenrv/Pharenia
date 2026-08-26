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

let gameTimer = null;
let timeLeft = 120;

function startTimer() {
    if (gameTimer) clearInterval(gameTimer);

    timeLeft = 120;
    updateTimerDisplay();

    gameTimer = setInterval(() => {
        timeLeft--;
        updateTimerDisplay();

        if (timeLeft <= 0) {
            clearInterval(gameTimer);
            onTimeUp();
        }
    }, 1000);
}

function stopTimer() {
    clearInterval(gameTimer);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    const formattedMinutes = String(minutes).padStart(2, '0');
    const formattedSeconds = String(seconds).padStart(2, '0');
    
    const timerElem = document.getElementById('timerValue');
    if (timerElem) {
        timerElem.textContent = `${formattedMinutes}:${formattedSeconds}`;
    }
}

async function onTimeUp() {
    if (typeof stopBubbleSpawning === 'function') {
        stopBubbleSpawning();
    }

    if (window.AudioManager) {
        if (AudioManager.bgsound) AudioManager.bgsound.pause();
        AudioManager.playTimeOver();
    }

    const overlay = document.getElementById("timeUpOverlay");
    const timeBanner = document.getElementById("timeUpBanner");

    if (timeBanner) {
        timeBanner.textContent = __("¡SE ACABÓ EL TIEMPO!");
        timeBanner.classList.add("active");
    }
    if (overlay) {
        overlay.classList.add("active");
    }

    await sleep(1200);

    if (timeBanner) {
        timeBanner.classList.remove("active");
    }
    if (overlay) {
        overlay.classList.remove("active");
    }

    await sleep(500);

    showGameOver(true);
}

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

async function showGameOver(isTimeUp = false) {
    const engine = window.FiguresGameEngine || {};
    const currentScore = engine.score || 0;
    const currentRound = engine.round || 1;

    stopTimer();

    const menuTitle = document.getElementById("menuTitle");
    if (menuTitle) {
        menuTitle.textContent = isTimeUp ? __("¡SE ACABÓ EL TIEMPO!") : __("¡GAME OVER!");
    }

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

    if (document.getElementById("finalScore")) document.getElementById("finalScore").textContent = currentScore;
    if (document.getElementById("finalRecord")) document.getElementById("finalRecord").textContent = ScoreManager.highScore;
    if (document.getElementById("finalWave")) document.getElementById("finalWave").textContent = currentRound;

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

    if (window.FiguresGameEngine && typeof FiguresGameEngine.reset === "function") {
        FiguresGameEngine.reset();
    }

    if (window.FiguresManager && typeof FiguresManager.generateBoardWithPop === "function") {
        await FiguresManager.generateBoardWithPop();
    }

    if (window.AudioManager) {
        AudioManager.playBackground();
    }
    
    startTimer();
}

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

    // Evento del Botón Reintentar
    const retryBtn = document.getElementById("retryButton");
    if (retryBtn) {
        retryBtn.addEventListener("click", () => {
            if (window.AudioManager) AudioManager.playButton();
            hideGameOver();
        });
    }
});