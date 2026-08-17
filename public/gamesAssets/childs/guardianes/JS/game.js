window.addEventListener("load", async ()=>{
    nextButton.addEventListener("click", () => {
        AudioManager.playButton();
        nextDialogue();
    });
    await sleep(1500);
    startTutorial();
});


function highlightBin(type){
    const bin = document.querySelector(`#binLayer .bin[data-type="${type}"]`);
    if(!bin) return;
    switch(type){
        case "metal":
            bin.style.filter =
            "brightness(1.5) drop-shadow(0 0 20px rgba(255,70,70,.95))";
        break;
        case "glass":
            bin.style.filter =
            "brightness(1.5) drop-shadow(0 0 20px rgba(80,180,255,.95))";
        break;
        case "plastic":
            bin.style.filter =
            "brightness(1.5) drop-shadow(0 0 20px rgba(80,255,120,.95))";
        break;
    }
}

function removeHighlight(type){
    const bin = document.querySelector(`#binLayer .bin[data-type="${type}"]`);
    if(!bin) return;
    bin.style.filter = "";
}