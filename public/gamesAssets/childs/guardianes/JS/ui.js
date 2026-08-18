const UIManager = {
    transitionTime:350,
    showButton(button){
        button.classList.remove("hidden","hide");
        button.classList.add("show");
    },

    hideButton(button){
        button.classList.remove("show");
        button.classList.add("hide");
        setTimeout(()=>{
            button.classList.remove("hide");
            button.classList.add("hidden");
        },this.transitionTime);
    },

    switchButton(currentButton,newButton){
        this.hideButton(currentButton);
        setTimeout(()=>{
            this.showButton(newButton);
        },this.transitionTime);
    },

    enableButtons(){
        document.querySelectorAll(".uiButton")
        .forEach(button=>{
            button.style.pointerEvents="auto";
        });

    },

    disableButtons(){
        document.querySelectorAll(".uiButton")
        .forEach(button=>{
            button.style.pointerEvents="none";
        });
    }
};

async function showBins(){
    metalBin.parentElement.classList.add("binVisible");
    metalBin.classList.add("binAppear");
    await sleep(180);
    glassBin.parentElement.classList.add("binVisible");
    glassBin.classList.add("binAppear");
    await sleep(180);
    plasticBin.parentElement.classList.add("binVisible");
    plasticBin.classList.add("binAppear");
    await sleep(180);
}