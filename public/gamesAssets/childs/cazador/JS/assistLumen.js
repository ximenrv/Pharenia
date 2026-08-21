const AssistantManager = {
    state: "idle",
    writing: false,
    typingSpeed: 40,
    currentTargetFigure: null,

    sprites: {
        idle: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenBase.png`,
        blink: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenParpadeo.png`,
        talk: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenTalk.png`,
        cheer: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenVictory.png`,
        wrong: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenWrong.png`
    },

    figureSigns: {
        circle: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenSigns/lumenSignCir.png`,
        square: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenSigns/lumenSignSqr.png`,
        triangle: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenSigns/lumenSignTrig.png`,
        star: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenSigns/lumenSignStar.png`,
        heart: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenSigns/lumenSignHeart.png`
    },

    blinkTimeout: null
};

function getLumenElements() {
    return {
        zone: document.getElementById("assistantZone"),
        head: document.getElementById("assistantHead"),
        text: document.getElementById("assistantText"),
        dialogue: document.getElementById("assistantDialogue")
    };
}

AssistantManager.setState = function(state) {
    this.state = state;
    const { head } = getLumenElements();
    if (!head) return;

    if (state === "idle" && this.currentTargetFigure && this.figureSigns[this.currentTargetFigure]) {
        head.src = this.figureSigns[this.currentTargetFigure];
        head.classList.add("signActive");
    } else if (this.sprites[state]) {
        head.classList.remove("signActive");
        head.src = this.sprites[state];
    }
};

AssistantManager.setTargetFigure = async function(figure) {
    this.currentTargetFigure = figure;
    const { head } = getLumenElements();
    
    if (!head) return;
    head.classList.remove("signActive");
    head.src = this.sprites.idle;
    await assistantSleep(100);

    head.classList.add("changeSign");
    await assistantSleep(300);

    if (this.figureSigns[figure]) {
        head.src = this.figureSigns[figure];
        head.classList.add("signActive");
    }
    head.classList.remove("changeSign");
};

AssistantManager.startTalking = function() {
    clearTimeout(this.blinkTimeout);
    this.setState("talk");
};

AssistantManager.stopTalking = function() {
    this.setState("idle");
    this.startBlinking();
};

AssistantManager.blink = async function() {
    if (this.state !== "idle" || this.writing || this.currentTargetFigure) return;
    
    this.setState("blink");
    await assistantSleep(250);
    if (!this.writing) {
        this.setState("idle");
    }
};

AssistantManager.startBlinking = function() {
    clearTimeout(this.blinkTimeout);
    const blinkLoop = async () => {
        if (this.state === "idle" && !this.writing && !this.currentTargetFigure) {
            await this.blink();
        }
        const delay = 4000 + Math.random() * 3000;
        this.blinkTimeout = setTimeout(blinkLoop, delay);
    };
    blinkLoop();
};

AssistantManager.showBubble = async function() {
    const { text } = getLumenElements();
    if (!text) return;
    text.classList.remove("hideBubble");
    text.classList.add("showBubble");
    await assistantSleep(300);
};

AssistantManager.hideBubble = async function() {
    const { text } = getLumenElements();
    if (!text) return;
    text.classList.remove("showBubble");
    text.classList.add("hideBubble");
    await assistantSleep(300);
};

AssistantManager.write = async function(textToWrite, forcedEmotion = null) {
    const { dialogue } = getLumenElements();
    if (!dialogue) return;
    
    clearTimeout(this.blinkTimeout);
    this.writing = true;

    // Solo cambiar a estado de habla si NO hay una figura objetivo mostrándose (cartel intacto)
    if (!AssistantManager.currentTargetFigure) {
        this.setState(forcedEmotion || "talk");
    }

    dialogue.textContent = "";
    const speed = this.typingSpeed || 40;
    let currentText = "";

    for (let i = 0; i < textToWrite.length; i++) {
        currentText += textToWrite[i];
        dialogue.textContent = currentText;
        await new Promise(resolve => setTimeout(resolve, speed));
    }

    this.writing = false;

    if (!forcedEmotion && !AssistantManager.currentTargetFigure) {
        this.stopTalking();
    }
};

AssistantManager.resetAssistantState = function() {
    const { text, dialogue, head } = getLumenElements();
    if (dialogue) dialogue.textContent = "";

    if (text) {
        text.classList.remove("showBubble");
        text.classList.add("hideBubble");
    };
    
    this.currentTargetFigure = null;
    this.writing = false;
    this.setState("idle");
};

//--- API GLOBAL UNIFICADA DE ASSIST LUMEN ---//
const AssistLumen = {
    hasStarted: false,

    activate() {
        const { zone } = getLumenElements();
        AssistantManager.resetAssistantState();

        if (zone) {
            zone.classList.add("active");
            AssistantManager.startBlinking();
        }
    },

    async startInitialCaza() {
        if (this.hasStarted) return;
        this.hasStarted = true;

        await this.sayDialogue("¡Comencemos la caza!", 1800);

        if (window.FiguresGameEngine && typeof FiguresGameEngine.startGame === "function") {
            FiguresGameEngine.startGame();
        }
    },

    deactivate() {
        const { zone } = getLumenElements();
        if (zone) {
            zone.classList.remove("active");
        }
    },

    async sayDialogue(text, duration = 1500, forcedEmotion = null) {
        const { text: bubble } = getLumenElements();
        if (!bubble) return;

        await AssistantManager.showBubble();
        await AssistantManager.write(text, forcedEmotion);

        const readingTime = Math.max(duration, text.length * 55);
        await new Promise(resolve => setTimeout(resolve, readingTime));

        await AssistantManager.hideBubble();
        AssistantManager.setState("idle");
        AssistantManager.startBlinking();
    },

    async setFigure(figure) {
        return await AssistantManager.setTargetFigure(figure);
    },

    clearFigure() {
        AssistantManager.currentTargetFigure = null;
        AssistantManager.setState("idle");
    },

    react(emotion, duration = 1200) {
        AssistantManager.setState(emotion);
        setTimeout(() => {
            if (!AssistantManager.writing) {
                AssistantManager.setState("idle");
            }
        }, duration);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    const { head } = getLumenElements();
    if (head) {
        AssistantManager.setState("idle");
    }
});

function assistantSleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

window.AssistLumen = AssistLumen;