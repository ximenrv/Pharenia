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
        text: __("¡Bienvenido a Cazador de Burbujas!, mi nombre es Lumen."),
        button: "next",
        action: "explain"
    },
    {
        speaker: "LUMEN",
        emotion: "cheering",
        text: __("En este desafío pondremos a prueba tus habilidades cazando formas geométricas."),
        button: "next",
        action: "explain"
    },
    {
        speaker: "LUMEN",
        emotion: "cheering",
        text: __("Yo te mostraré un cartel con la figura que debes buscar en el tablero."),
        button: "next",
        action: "explain"
    },
    {
        speaker: "LUMEN",
        emotion: "surprised",
        text: __("Explotas todas las burbujas que tengan esa misma figura."),
        button: "next",
        action: "explain"
    },
    {
        speaker: "LUMEN",
        emotion: "surprised",
        text: __("¡Cuidado con equivocarte o perderás vidas!.. ¿Listo?"),
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
    characterContainer.classList.remove("lumenEntrance", "lumenExit");
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
        tutorialPanel.style.opacity = "";
        tutorialPanel.classList.remove("hideTutorial", "hidden");
        tutorialPanel.classList.add("show");
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
    const tutorialButtons = document.querySelectorAll(".uiButton");
    const tutorialLayer = document.getElementById("tutorialLayer");

    if (tutorialButtons) {
        tutorialButtons.forEach(btn => {
            btn.classList.remove("show");
            btn.classList.add("hide");
        });
    }

    if (speakerName) speakerName.classList.remove("show");

    hideBubble();
    hideLumen();

    await sleep(650);

    if (tutorialPanel) tutorialPanel.classList.add("hidden");
    if (tutorialLayer) tutorialLayer.classList.add("hidden");

    if (window.FiguresManager && typeof FiguresManager.generateBoardWithPop === "function") {
        await FiguresManager.generateBoardWithPop();
    }

    await sleep(300);

    if (window.AssistLumen) {
        AssistLumen.activate();
    }

    await sleep(1200);

    if (window.AssistLumen) {
        await AssistLumen.startInitialCaza();
    }
}

function hideBubble() {
    if (!tutorialPanel) return;
    tutorialPanel.classList.remove("show");
    tutorialPanel.classList.add("hideTutorial");
}

function hideLumen() {
    if (!characterContainer) return;
    characterContainer.classList.remove("lumenEntrance");
    characterContainer.classList.add("lumenExit");
    setTimeout(() => {
        characterContainer.style.opacity = "0";
    }, 600);
}