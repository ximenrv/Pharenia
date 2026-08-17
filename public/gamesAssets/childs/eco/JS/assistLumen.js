const AssistantManager = {
    state: "idle",
    writing: false,
    typingSpeed: 40,
    sprites: {
        idle: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenBase.png`,
        blink: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenParpadeo.png`,
        talk: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenTalk.png`,
        cheer: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenVictory.png`,
        wrong: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenWrong.png`
    },
    blinkTimeout: null
};

function getLumenElements() {
    return {
        head: document.getElementById("assistantHead"),
        text: document.getElementById("assistantText"),
        dialogue: document.getElementById("assistantDialogue")
    };
}

AssistantManager.setState = function(state) {
    this.state = state;
    const { head } = getLumenElements();
    if (head && this.sprites[state]) {
        head.src = this.sprites[state];
    }
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
    if (this.state !== "idle" || this.writing) return;
    this.setState("blink");
    await assistantSleep(250);
    if (!this.writing) {
        this.setState("idle");
    }
};

AssistantManager.startBlinking = function() {
    clearTimeout(this.blinkTimeout);
    const blinkLoop = async () => {
        if (this.state === "idle" && !this.writing) {
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
    await assistantSleep(350);
};

AssistantManager.hideBubble = async function() {
    const { text } = getLumenElements();
    if (!text) return;
    text.classList.remove("showBubble");
    text.classList.add("hideBubble");
    await assistantSleep(350);
};

AssistantManager.write = async function(textToWrite, forcedEmotion = null) {
    const { dialogue } = getLumenElements();
    if (!dialogue) return;
    
    clearTimeout(this.blinkTimeout);
    this.writing = true;

    this.setState(forcedEmotion || "talk");
    
    dialogue.textContent = "";

    const speed = this.typingSpeed || 40;
    let currentText = "";

    for (let i = 0; i < textToWrite.length; i++) {
        currentText += textToWrite[i];
        dialogue.textContent = currentText;
        await new Promise(resolve => setTimeout(resolve, speed));
    }

    this.writing = false;

    if (!forcedEmotion) {
        this.stopTalking();
    }
};

//--- API GLOBAL CONECTADA AL MEMORY MANAGER ---//
const AssistLumen = {
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
        AssistantManager.startBlinking();
    }
});

function assistantSleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

window.AssistLumen = AssistLumen;