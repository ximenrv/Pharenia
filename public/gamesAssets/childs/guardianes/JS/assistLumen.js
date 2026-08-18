const assistant = document.getElementById("assistant");
const assistantHead = document.getElementById("assistantHead");
const assistantSlot = document.getElementById("assistantBubble");
const assistantText = document.getElementById("assistantText");
const assistantAvatar = document.getElementById("assistantAvatar");
const assistantDialogue = document.getElementById("assistantDialogue");

const AssistantManager = {
    state:"idle",
    current:0,
    writing:false,
    currentIndex:0,
    typingSpeed:50,
    timer:4000,
    timeout:null,
    animation:null,
    sprites:{
        idle: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenBase.png`,
        blink: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenParpadeo.png`,
        talk: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenTalk.png`,
        cheer: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenVictory.png`,
        wrong: `${window.GAME_ASSETS_PATH}/lumen/assistent/lumenWrong.png`
    },
    blinkTimeout:null,
    cheerChance: 0.30
};

AssistantManager.setState = function(state){
    this.state = state;
    assistantHead.src = this.sprites[state];
};

AssistantManager.tryCheer = function(points){
    if(this.writing) return;
    if(points >= 3){
        this.react("cheer",900);
        return;
    }
    if(Math.random() < this.cheerChance){
        this.react("cheer",700);
    }
}

const assistantDialogs = [
{
    text:"¡Hola de nuevo amigo!",
    target: null
},

{
    text:"Ahora te explicare los contenedores",
    target: null
},

{
    text:"El contenedor ROJO es para los metales",
    target:"metal"
},

{
    text:"El AZUL es para los vidrios",
    target:"glass"
},

{
    text:"Y el VERDE es para los plásticos",
    target:"plastic"
},

{
    text:"¡Ahora es tu turno!",
    target: null
},

{
    text:"¡Recoge tanta basura como puedas!",
    target: null
},

{
    text:"¡Pero asegurate que no se te acabe el tiempo!",
    target: null
},

{
    text:"¿Listo? 3.... 2.... 1.... ¡Comencemos!",
    target: null
}
];

async function startAssistant(){
    waveManager.playerCanInteract = false;
    await sleep(700);
    assistant.style.opacity = "1";
    assistant.classList.add("assistantAppear");
    AssistantManager.startBlinking();
    await sleep(1200);
    await AssistantManager.explainBins();
}



AssistantManager.startTalking = function(){
    clearTimeout(this.blinkTimeout);
    this.setState("talk");

};

AssistantManager.stopTalking = function(){
    this.setState("idle");
    this.startBlinking();
};

AssistantManager.blink = async function(){
    if(this.state !== "idle") return;
    this.setState("blink");
    await sleep(250);
    this.setState("idle");
};

AssistantManager.startBlinking = function(){
    const blinkLoop = async ()=>{
        if(this.state === "idle"){
            await this.blink();
        }

        const delay = 6500 + Math.random() * 3500;
        this.blinkTimeout = setTimeout(
            blinkLoop,delay
        );
    };
    blinkLoop();
};

AssistantManager.showBubble = async function(){
    assistantText.classList.remove("hideBubble");
    assistantText.classList.add("showBubble");
    await sleep(350);
};

AssistantManager.hideBubble = async function(){
    assistantText.classList.remove("showBubble");
    assistantText.classList.add("hideBubble");
    await sleep(350);
    const gameStatus = document.getElementById("gameStatus");
    if (gameStatus) {
        gameStatus.classList.add("gameStatusEnter");
    }
    await sleep(350);
    startTimer();
};

AssistantManager.write = async function(text){
    this.startTalking();
    assistantDialogue.textContent = "";
    for(let i = 0; i < text.length; i++){
        assistantDialogue.textContent += text[i];
        await sleep(this.typingSpeed);
    }
    this.stopTalking();
};

AssistantManager.explainBins = async function(){
    await this.showBubble();
    for(const dialog of assistantDialogs){
        if(dialog.target){
            highlightBin(dialog.target);
        }
        await this.write(dialog.text);
        await sleep(1200);
        if(dialog.target){
            removeHighlight(dialog.target);
        }
    }
    await this.hideBubble();
    waveManager.playerCanInteract = true;
};

AssistantManager.react = async function(state, duration = 500){
    if(this.writing) return;
    clearTimeout(this.blinkTimeout);
    this.setState(state);
    await sleep(duration);
    this.startBlinking();
    this.setState("idle");
};