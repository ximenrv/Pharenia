let currentHoveredBin = null;

const DragManager = {
    dragging: null,
    offsetX: 0,
    offsetY: 0,
    originalX: 0,
    originalY: 0
};

function startDrag(e){
    if(!waveManager.playerCanInteract){
        return;
    }
    AudioManager.playGrab();
    DragManager.dragging = e.currentTarget;
     DragManager.dragging.style.zIndex = "1000";
    const trashData = trashObjects.find(t => t.element === DragManager.dragging);
    trashData.dragging = true;
    trashData.scale = 1.15;
    const rect = DragManager.dragging.getBoundingClientRect();

    DragManager.offsetX = e.clientX - rect.left;
    DragManager.offsetY = e.clientY - rect.top;

    DragManager.originalX = DragManager.dragging.offsetLeft;
    DragManager.originalY = DragManager.dragging.offsetTop;
}

function dragMove(e){
    if(!DragManager.dragging) return;
    const trashData = trashObjects.find(
        t => t.element === DragManager.dragging
    );
    trashData.x =
        e.clientX - DragManager.offsetX;
    trashData.y =
        e.clientY - DragManager.offsetY;
    DragManager.dragging.style.left =
        trashData.x + "px";
    DragManager.dragging.style.top =
        trashData.y + "px";
 updateBinHighlight();
}

async function endDrag(e){
    if(!DragManager.dragging) return;
    const trashData =trashObjects.find(t => t.element === DragManager.dragging);
    const hoveredBin = getHoveredBin(trashData);

    if(hoveredBin){
    if(hoveredBin.dataset.type === trashData.type){
        await recycleTrash(trashData);
    }else{
        AudioManager.playWrong();
        AssistantManager.react("wrong");
        returnTrash(trashData);
    }
}else{
    if(isInsideBeach(trashData)){
        trashData.dragging = false;
        trashData.scale = 1;
    }else{
        AudioManager.playWrong();
        AssistantManager.react("wrong");
        returnTrash(trashData);
    }
}
    DragManager.dragging = null;
    clearBinHighlight();
}

document.addEventListener("mousemove", dragMove);
document.addEventListener("mouseup", endDrag);

function updateBinHighlight(){
    if(!DragManager.dragging) return;
    const trashType = DragManager.dragging.dataset.type;
    const correctBin = document.querySelector(`.bin[data-type="${trashType}"]`);
    if(!correctBin) return;
    const trashRect = DragManager.dragging.getBoundingClientRect();
    const binRect = correctBin.getBoundingClientRect();
    const touching =
        trashRect.right  > binRect.left &&
        trashRect.left   < binRect.right &&
        trashRect.bottom > binRect.top &&
        trashRect.top    < binRect.bottom;
    if(touching){
        if(currentHoveredBin !== correctBin){
            if(currentHoveredBin){
                currentHoveredBin.classList.remove("binActive");
            }
            currentHoveredBin = correctBin;
            const type = currentHoveredBin.dataset.type;
            switch(type){
                case "metal":
                    currentHoveredBin.classList.add("binActiveMetal");
                    break;
                case "glass":
                    currentHoveredBin.classList.add("binActiveGlass");
                    break;
                case "plastic":
                    currentHoveredBin.classList.add("binActivePlastic");
                    break;
                }
            }}else{
                if(currentHoveredBin){
                    currentHoveredBin.classList.remove(
                        "binActiveMetal",
                        "binActiveGlass",
                        "binActivePlastic");
                    currentHoveredBin = null;
        }
    }
}

async function recycleTrash(trashData){
    const targetBin = currentHoveredBin;
    if(!targetBin) return;
    trashData.dragging = false;
    trashData.element.classList.add("trashSuccess");
    flashBin(targetBin, trashData.type);
    await sleep(150);
    const rect = targetBin.getBoundingClientRect();
    const world = document.getElementById("world").getBoundingClientRect();
    showFloatingScore(
        rect.left - world.left + rect.width / 2,
        rect.top - world.top,
        trashData.points
    );
    await sleep(200);
    addScore(trashData.points);
    AudioManager.playScore();
    AssistantManager.tryCheer(trashData.points);
    await sleep(200);
    trashData.element.remove();
    const index = trashObjects.indexOf(trashData);
    if(index !== -1){
        trashObjects.splice(index,1);
    }
    checkWaveFinished();
}

function checkWaveFinished(){
    if(trashObjects.length > 0) return;
    finishWave();
}

async function finishWave(){
    stopTimer();
    await sleep(900);
    waveManager.currentWave++;
    await startNextWave();
}

function clearBinHighlight(){
    if(!currentHoveredBin) return;
    currentHoveredBin.classList.remove(
        "binActiveMetal",
        "binActiveGlass",
        "binActivePlastic"
    );
    currentHoveredBin = null;
}

function flashBin(bin, type){
    if(!bin) return;
    highlightBin(type);
    switch(type){
        case "metal":
            bin.style.filter = "brightness(2.2) drop-shadow(0 0 35px rgba(255,70,70,.95))";
        break;
        case "glass":
            bin.style.filter = "brightness(2.2) drop-shadow(0 0 35px rgba(80,180,255,.95))";
        break;
        case "plastic":
            bin.style.filter = "brightness(2.2) drop-shadow(0 0 35px rgba(80,255,120,.95))";
        break;
    }
    setTimeout(()=>{ removeHighlight(type); },120);
}

function showFloatingScore(x, y, points){
    const score = document.createElement("div");
    score.className = "floatingScore";
    if(points === 3){
        score.classList.add("special");
    }
    score.textContent = "+" + points;
    score.style.left = x + "px";
    score.style.top = y + "px";
    document.getElementById("effectsLayer").appendChild(score);
    score.addEventListener("animationend",()=>{
        score.remove();
    });
}

function returnTrash(trashData){
    const trash = trashData.element;
    trash.classList.add("returningTrash");
    trash.style.left = DragManager.originalX + "px";
    trash.style.top = DragManager.originalY + "px";
    trashData.x = DragManager.originalX;
    trashData.y = DragManager.originalY;
    trashData.dragging = false;
    trashData.scale = 1;
    trash.addEventListener("transitionend",function finish(){
        trash.classList.remove("returningTrash");
        trash.removeEventListener("transitionend",finish);
    });
}

function isInsideBeach(trash){
    const world = document.getElementById("world").getBoundingClientRect();
    const rect = trash.element.getBoundingClientRect();
    const centerX = rect.left + rect.width / 2;
    const centerY = rect.top + rect.height / 2;

    const beachTop = world.top + 290;
    const beachBottom = world.bottom - 290;
    return (
        centerX >= world.left &&
        centerX <= world.right &&
        centerY >= beachTop &&
        centerY <= beachBottom
    );
}

function getHoveredBin(trash){
    const trashRect = trash.element.getBoundingClientRect();
    const bins = document.querySelectorAll(".bin");
    for(const bin of bins){
        const rect = bin.getBoundingClientRect();
        const touching =
            trashRect.right  > rect.left &&
            trashRect.left   < rect.right &&
            trashRect.bottom > rect.top &&
            trashRect.top    < rect.bottom;
        if(touching){
            return bin;
        }
    }
    return null;
}
