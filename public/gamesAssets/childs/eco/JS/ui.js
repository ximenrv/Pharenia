const UIManager = {
    transitionTime: 350,

    showButton(button) {
        if (!button) return;
        button.classList.remove("hidden", "hide");
        button.classList.add("show");
    },

    hideButton(button) {
        if (!button) return;
        button.classList.remove("show");
        button.classList.add("hide");
        setTimeout(() => {
            button.classList.remove("hide");
            button.classList.add("hidden");
        }, this.transitionTime);
    },

    switchButton(currentButton, newButton) {
        this.hideButton(currentButton);
        setTimeout(() => {
            this.showButton(newButton);
        }, this.transitionTime);
    },

    enableButtons() {
        document.querySelectorAll(".uiButton")
            .forEach(button => {
                button.style.pointerEvents = "auto";
            });
    },

    disableButtons() {
        document.querySelectorAll(".uiButton")
            .forEach(button => {
                button.style.pointerEvents = "none";
            });
    },

    showAssistantZone() {
        const assistant = document.getElementById("assistantZone");
        if (assistant) {
            assistant.style.opacity = "1";
            assistant.classList.add("assistantAppear");
        }
    }
};

// Objeto global para coordinar el Game Over con la pantalla modal
const GameUI = {
    showGameOver(finalScore, finalRecord, finalWave) {
        const screen = document.getElementById("gameOverScreen");
        const container = document.getElementById("gameOverContainer");
        const scoreVal = document.getElementById("finalScore");
        const recordVal = document.getElementById("finalRecord");
        const waveVal = document.getElementById("finalWave");

        if (scoreVal) scoreVal.textContent = finalScore;
        if (recordVal) recordVal.textContent = finalRecord;
        if (waveVal) waveVal.textContent = finalWave;

        if (screen) screen.classList.add("show");
        if (container) container.classList.add("showContainer");

        setTimeout(() => {
            document.querySelectorAll(".gameOverBlock").forEach(block => block.classList.add("showStat"));
            const title = document.getElementById("gameOverTitle");
            if (title) title.classList.add("showGameOverTitle");
            const lumen = document.getElementById("gameOverLumen");
            if (lumen) lumen.classList.add("showLumen");
            const retry = document.getElementById("retryButton");
            if (retry) retry.classList.add("showRetry");
        }, 300);
    }
};