window.addEventListener("load", async () => {
    // Listener del botón "Siguiente"
    const nextBtn = document.getElementById("nextButton");
    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            if (window.AudioManager && typeof AudioManager.playButton === "function") {
                AudioManager.playButton();
            }
            if (typeof nextDialogue === "function") {
                nextDialogue();
            }
        });
    }

    // Listener del botón "Jugar"
    const playBtn = document.getElementById("playButton");
    if (playBtn) {
        playBtn.addEventListener("click", () => {
            if (window.AudioManager && typeof AudioManager.playButton === "function") {
                AudioManager.playButton();
            }
            if (typeof finishTutorial === "function") {
                finishTutorial();
            }
        });
    }

    // Listener del botón Reintentar en Game Over
const retryBtn = document.getElementById("retryButton");
if (retryBtn) {
    retryBtn.addEventListener("click", () => {
        if (window.AudioManager && typeof AudioManager.playButton === "function") {
            AudioManager.playButton();
        }
        const gameOverScreen = document.getElementById("gameOverScreen");
        if (gameOverScreen) gameOverScreen.classList.remove("show");

        if (window.FiguresGameEngine) {
            FiguresGameEngine.startGame();
        }
    });
}

    if (typeof sleep === "function") {
        await sleep(1000);
    } else {
        await new Promise(resolve => setTimeout(resolve, 1000));
    }

    if (window.AudioManager && typeof AudioManager.playBackground === "function") {
        AudioManager.playBackground();
    }

    if (typeof startTutorial === "function") {
        startTutorial();
    }
});