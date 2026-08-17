const trashTypes = [
    {
        image: `${window.GAME_ASSETS_PATH}/trash/metal/metalTrash.png`,
        type: "metal",
        points: 1,
        special: false
    },
    {
        image: `${window.GAME_ASSETS_PATH}/trash/glass/glassTrash.png`,
        type: "glass",
        points: 1,
        special: false
    },
    {
        image: `${window.GAME_ASSETS_PATH}/trash/plastic/plasticTrash.png`,
        type: "plastic",
        points: 1,
        special: false
    },
    {
        image: `${window.GAME_ASSETS_PATH}/trash/metal/metalTrashGold.png`,
        type: "metal",
        points: 3,
        special: true
    },
    {
        image: `${window.GAME_ASSETS_PATH}/trash/glass/glassTrashGold.png`,
        type: "glass",
        points: 3,
        special: true
    },
    {
        image: `${window.GAME_ASSETS_PATH}/trash/plastic/plasticTrashGold.png`,
        type: "plastic",
        points: 3,
        special: true
    }
];

const trashObjects = [];

function generateTrashData() {
    const specialChance = 0.08; // 8% de probabilidad
    if (Math.random() < specialChance) {
        const specialTrash = trashTypes.filter(trash => trash.special);
        return specialTrash[Math.floor(Math.random() * specialTrash.length)];
    }
    const normalTrash = trashTypes.filter(trash => !trash.special);
    return normalTrash[Math.floor(Math.random() * normalTrash.length)];
}

function createTrash() {
    const trash = document.createElement("img");
    trash.className = "trash";
    const data = generateTrashData();
    trash.src = data.image;
    trash.draggable = false;
    trash.addEventListener("dragstart", e => e.preventDefault());
    document.getElementById("trashLayer").appendChild(trash);
    trash.style.left = Math.random() * 85 + "%";
    trash.style.top = "-50px";
    trash.dataset.type = data.type;
    trash.addEventListener("mousedown", startDrag);
    const rotation = Math.random() * 15 - 5;
    trash.style.transform = `rotate(${rotation}deg)`;
    trashObjects.push({
        element: trash,
        type: data.type,
        points: data.points,
        special: data.special,
        x: parseFloat(trash.style.left),
        y: parseFloat(trash.style.top),
        targetY: 300 + Math.random() * 60,
        rotation,
        floatTime: Math.random() * 100,
        scale: 1,
        dragging: false
    });
}

let trashSpawner = null;
function startTrashSpawner() {
    if (trashSpawner) return;
    trashSpawner = setInterval(() => {
        if (trashObjects.length < waveManager.trashPerWave) {
            createTrash();
        }
    }, 1200);
}

function stopTrashSpawner() {
    clearInterval(trashSpawner);
    trashSpawner = null;
}

function updateTrash() {
    for (const trash of trashObjects) {
        if (!trash.dragging) {
            trash.floatTime += 0.08;
        }
        const offset = trash.dragging
            ? 0
            : Math.sin(trash.floatTime) * 3;
        trash.element.style.transform =
            `translateY(${offset}px) rotate(${trash.rotation}deg) scale(${trash.scale})`;
        if (!trash.dragging && trash.y < trash.targetY) {
            trash.y += 6;
        }
        trash.element.style.top = trash.y + "px";
    }
}

function gameLoop() {
    updateTrash();
    requestAnimationFrame(gameLoop);
}

gameLoop();