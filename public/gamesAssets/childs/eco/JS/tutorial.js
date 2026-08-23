const tutorialText = document.getElementById("tutorialText");
const nextButton = document.getElementById("nextButton");
const playButton = document.getElementById("playButton");
const character = document.getElementById("lumen");
const tutorialPanel = document.getElementById("tutorialPanel");
const speakerName = document.getElementById("speakerName");
const characterContainer = document.getElementById("lumenContainer");

function __(key) {
    return (window.translations && window.translations[key]) ? window.translations[key] : key;
}

const tutorialDialogs = [
    {
        speaker: "LUMEN",
        emotion: "cheering",
        text: __("¡Bienvenido a Eco de los Colores!, me llamo Lumen."),
        button: "next",
        action: "explain"
    },
    {
        speaker: "LUMEN",
        emotion: "cheering",
        text: __("En este desafío pondremos a prueba tu memoria con la energía de las burbujas."),
        button: "next",
        action: "explain"
    },
    {
        speaker: "LUMEN",
        emotion: "cheering",
        text: __("Observa atentamente el patrón de luces que iluminaré en el tablero central."),
        button: "next",
        action: "explain"
    },
    {
        speaker: "LUMEN",
        emotion: "surprised",
        text: __("Repite la misma secuencia en orden. Si fallas, perderás una de tus 3 vidas. ¿Listo?"),
        button: "play",
        action: "startGame"
    }
];

const emotions = {
    happy: `${window.GAME_ASSETS_PATH}/lumen/tutorial/lumenVictory.png`,
    cheering: `${window.GAME_ASSETS_PATH}/lumen/tutorial/lumenCheer.png`,
    neutral: `${window.GAME_ASSETS_PATH}/lumen/tutorial/lumenHappy.png`,
    surprised: `${window.GAME_ASSETS_PATH}/lumen/tutorial/lumenSurprise.png`
};

const DialogueManager = {
    currentDialog: 0,
    writing: false,
    finished: false,
    fullText: "",
    currentIndex: 0,
    typingSpeed: 40,
    jumpText: 6,
    skip: false,
    timeout: null
};

function startTalking(emotion) {
    DialogueManager.writing = true;
    if (character) {
        character.src = emotions[emotion] || emotions.neutral;
        character.classList.add("lumenTalking");
    }
}

function stopTalking() {
    DialogueManager.writing = false;
    if (character) {
        character.classList.remove("lumenTalking");
        character.src = emotions.neutral;
    }
}

function typeText(text) {
    startTalking(tutorialDialogs[DialogueManager.currentDialog].emotion);
    if (tutorialText) tutorialText.textContent = "";
    DialogueManager.currentIndex = 0;

    function write() {
        if (DialogueManager.skip) {
            if (tutorialText) tutorialText.textContent = text;
            DialogueManager.finished = true;
            stopTalking();
            showCurrentButton();
            return;
        }
        if (DialogueManager.currentIndex < text.length) {
            if (tutorialText) tutorialText.textContent += text.charAt(DialogueManager.currentIndex);
            DialogueManager.currentIndex++;
            if (DialogueManager.currentIndex % DialogueManager.jumpText === 0) {
                animateCharacter(character);
            }
            DialogueManager.timeout = setTimeout(write, DialogueManager.typingSpeed);
        } else {
            stopTalking();
            showCurrentButton();
            DialogueManager.finished = true;
        }
    }
    write();
}

function nextDialogue() {
    if (DialogueManager.writing) {
        DialogueManager.skip = true;
        return;
    }

    if (DialogueManager.currentDialog < tutorialDialogs.length - 1) {
        showDialog(DialogueManager.currentDialog + 1);
    }
}

function animateCharacter(charElem) {
    if (!charElem) return;
    charElem.classList.remove("lumenTalking");
    void charElem.offsetWidth;
    charElem.classList.add("lumenTalking");
}

function showDialog(index) {
    const dialog = tutorialDialogs[index];
    DialogueManager.currentDialog = index;
    DialogueManager.fullText = dialog.text;
    DialogueManager.currentIndex = 0;
    DialogueManager.skip = false;
    DialogueManager.finished = false;
    if (tutorialText) tutorialText.textContent = "";
    if (character) character.src = emotions[dialog.emotion] || emotions.neutral;

    clearTimeout(DialogueManager.timeout);
    DialogueManager.timeout = null;
    typeText(dialog.text);
}

function showCurrentButton() {
    const dialog = tutorialDialogs[DialogueManager.currentDialog];

    if (dialog.button === "next") {
        if (playButton) {
            playButton.classList.remove("show");
            playButton.classList.add("hide");
        }
        if (nextButton) {
            nextButton.classList.remove("hide");
            nextButton.classList.add("show");
        }
    } else {
        if (nextButton) {
            nextButton.classList.remove("show");
            nextButton.classList.add("hide");
        }
        setTimeout(() => {
            if (playButton) {
                playButton.classList.remove("hide");
                playButton.classList.add("show");
            }
        }, 300);
    }
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function startTutorial() {
    await showLumen();
    await showBubble();
    await showSpeaker();
    showDialog(0);
}

async function showLumen() {
    if (!characterContainer) return;
    characterContainer.classList.remove("lumenEntrance");
    void characterContainer.offsetWidth;
    if (character) character.src = emotions.happy;
    characterContainer.style.opacity = "1";
    characterContainer.classList.add("lumenEntrance");
    await sleep(700);
    if (character) character.src = emotions.neutral;
}

async function showBubble() {
    await sleep(150);
    if (tutorialPanel) {
        tutorialPanel.style.opacity = "1";
        tutorialPanel.classList.add("bubbleAppear");
    }
    await sleep(450);
}

async function showSpeaker() {
    if (speakerName) {
        speakerName.style.opacity = "1";
        speakerName.classList.add("show");
    }
    await sleep(200);
}

async function finishTutorial() {
    console.log("Iniciando cierre de tutorial...");

    const speakerName = document.getElementById("speakerName");
    if (speakerName) speakerName.classList.remove("show");

    hideBubble();
    hideLumen();

    await sleep(600);
    if (tutorialPanel) tutorialPanel.classList.add("hidden");

    const assistantZone = document.getElementById("assistantZone");
    if (assistantZone) {
        assistantZone.classList.add("show");
    }

    await sleep(1000);

    if (window.MemoryManager && typeof MemoryManager.startGame === "function") {
        MemoryManager.startGame();
    }
}

function hideBubble() {
    if (!tutorialPanel) return;
    tutorialPanel.classList.remove("bubbleAppear");
    void tutorialPanel.offsetWidth;
    tutorialPanel.classList.add("bubbleDisappear");
}

function hideLumen() {
    if (!characterContainer) return;
    characterContainer.classList.remove("lumenEntrance");
    void characterContainer.offsetWidth;
    characterContainer.classList.add("lumenExit");
    setTimeout(() => {
        characterContainer.style.opacity = "0";
    }, 600);
}