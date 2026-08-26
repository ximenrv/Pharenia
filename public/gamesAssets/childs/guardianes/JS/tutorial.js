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

function getTutorialDialogs() {
    return [
        {
            speaker: "LUMEN",
            emotion: "cheering",
            text: __('¡Bienvenido a Guardianes del Planeta!, me llamo Lumen.'),
            button: "next",
            action: "explain"
        },
        {
            speaker: "LUMEN",
            emotion: "cheering",
            text: __('Este día necesito de tu ayuda para limpiar la playa.'),
            button: "next",
            action: "explain"
        },
        {
            speaker: "LUMEN",
            emotion: "cheering",
            text: __('Veras la marea trae basura a la costa, entonces necesito depositarla en su lugar.'),
            button: "next",
            action: "explain"
        },
        {
            speaker: "LUMEN",
            emotion: "cheering",
            text: __('¿Me puedes ayudar?..... parece que, ahí viene una OLA.'),
            button: "play",
            action: "prepareWave"
        }
    ];
};

const emotions = {
    happy: `${window.GAME_ASSETS_PATH}/lumen/tutorial/lumenVictory.png`,
    cheering: `${window.GAME_ASSETS_PATH}/lumen/tutorial/lumenCheer.png`,
    neutral: `${window.GAME_ASSETS_PATH}/lumen/tutorial/lumenHappy.png`,
    surprised: `${window.GAME_ASSETS_PATH}/lumen/tutorial/lumenSurprise.png`
};

const DialogueManager = {
    currentDialog:0,
    writing:false,
    finished:false,
    fullText:"",
    currentIndex:0,
    typingSpeed:40,
    jumpText:6,
    skip:false,
    timeout:null
};

function startTalking(emotion){
    DialogueManager.writing = true;
    character.src = emotions[emotion];
    character.classList.add("lumenTalking");
}

function stopTalking(){
    DialogueManager.writing = false;
    character.classList.remove("lumenTalking");
    character.src = emotions.neutral;
}

function typeText(text){
    const dialogs = getTutorialDialogs();
    startTalking(dialogs[DialogueManager.currentDialog].emotion);
    tutorialText.textContent = "";
    DialogueManager.currentIndex = 0;
    function write(){
        if(DialogueManager.skip){
            tutorialText.textContent = text;
            DialogueManager.finished = true;
            stopTalking();
            showCurrentButton();
            return;
        }
        if(DialogueManager.currentIndex < text.length){
            tutorialText.textContent += text.charAt(DialogueManager.currentIndex);
            DialogueManager.currentIndex++;
            if(DialogueManager.currentIndex % DialogueManager.jumpText === 0){
                animateCharacter(character);
            }
            DialogueManager.timeout = setTimeout(
                write,
                DialogueManager.typingSpeed
            );
        }else{
            stopTalking();
            showCurrentButton();
            DialogueManager.finished = true;
        }
    }
    write();
}

function nextDialogue(){
    if(DialogueManager.writing){
        DialogueManager.skip = true;
        return;
    }

    const dialogs = getTutorialDialogs();
    if(DialogueManager.currentDialog < dialogs.length - 1){
        showDialog(DialogueManager.currentDialog + 1);
    }
}

function animateCharacter(character){
    character.classList.remove("lumenTalking");
    void character.offsetWidth;
    character.classList.add("lumenTalking");
}

function showDialog(index){
    const dialogs = getTutorialDialogs();
    const dialog = dialogs[index];
    DialogueManager.currentDialog = index;
    DialogueManager.fullText = dialog.text;
    DialogueManager.currentIndex = 0;
    DialogueManager.skip = false;
    DialogueManager.finished = false;
    tutorialText.textContent = "";
    character.src = emotions[dialog.emotion];

    clearTimeout(DialogueManager.timeout);
    DialogueManager.timeout = null;
    typeText(dialog.text);
}

function showCurrentButton(){
    const dialogs = getTutorialDialogs();
    const dialog = dialogs[DialogueManager.currentDialog];
    if(dialog.button === "next"){
        if(!nextButton.classList.contains("show")){
            UIManager.showButton(nextButton);
        }
    }else{
        UIManager.hideButton(nextButton);
        setTimeout(()=>{
            UIManager.showButton(playButton);
        },UIManager.transitionTime);
    }
}

function sleep(ms){
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function startTutorial(){
    await showLumen();
    await showBubble();
    await showSpeaker();
    showDialog(0);
}

async function showLumen(){
    characterContainer.classList.remove("lumenEntrance");
    void characterContainer.offsetWidth;
    character.src = `${window.GAME_ASSETS_PATH}/lumen/tutorial/lumenVictory.png`;
    characterContainer.style.opacity = "1";
    characterContainer.classList.add("lumenEntrance");
    await sleep(700);
    character.src = `${window.GAME_ASSETS_PATH}/lumen/tutorial/lumenHappy.png`;
}

async function showBubble(){
    await sleep(150);
    tutorialPanel.style.opacity="1";
    tutorialPanel.classList.add("bubbleAppear");
    await sleep(450);
}

async function showSpeaker(){
    speakerName.style.opacity="1";
    speakerName.classList.add("show");
    await sleep(200);
}

async function finishTutorial(){
    waveManager.playerCanInteract = false;
    UIManager.hideButton(playButton);
    await sleep(350);
    await hideBubble();
    await hideLumen();
    if (globalReturnBtn) {
        globalReturnBtn.classList.remove('hidden');
    }
    resetTimer();
    startTimer();
    startFirstWave();
    updateWaveHUD();
}

playButton.addEventListener("click", () => {
    AudioManager.playButton();
    finishTutorial();
});

async function hideBubble(){
    tutorialPanel.classList.remove("bubbleAppear");
    void tutorialPanel.offsetWidth;
    tutorialPanel.classList.add("bubbleDisappear");
    await sleep(450);
}

async function hideLumen(){
    characterContainer.classList.remove("lumenEntrance");
    void characterContainer.offsetWidth;
    characterContainer.classList.add("lumenExit");
    await sleep(600);
    characterContainer.style.opacity = "0";
}