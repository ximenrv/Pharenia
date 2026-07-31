document.addEventListener("DOMContentLoaded", function () {
    /*TIPOS DE TRATAMIENTO LISTADO */
    function switchLevelTab(event, levelId) {
        document.querySelectorAll(".level-detail-card").forEach((card) => {
            card.classList.remove("active");
        });
        document.querySelectorAll(".levels-tab-btn").forEach((btn) => {
            btn.classList.remove("active");
        });
        document.getElementById(levelId).classList.add("active");
        event.currentTarget.classList.add("active");
    }
});

/*CHALLENGES MCHAT */
class ChallengeApp {
    constructor() {
        this.currentStep = window.initialQuizStep || 1;
        this.isCompleted = window.isQuizCompleted || false;
        this.pageSize = 5;

        this.questions = {
            1: "Si usted señala algo al otro lado de la habitación, ¿su hijo/a lo mira?",
            2: "¿Alguna vez se ha preguntado si su hijo/a podría ser sordo/a?",
            3: "¿Su hijo/a juega a juegos de simulación o de hacer creer?",
            4: "¿A su hijo/a le gusta subirse a las cosas?",
            5: "¿Hace su hijo/a movimientos raros con los dedos cerca de sus ojos?",
            6: "¿Su hijo/a responde cuando usted lo/a llama por su nombre?",
            7: "¿Su hijo/a sonríe en respuesta a su sonrisa o a su rostro?",
            8: "¿Su hijo/a se enoja con los ruidos cotidianos comunes?",
            9: "¿Su hijo/a camina bien?",
            10: "¿Su hijo/a lo mira a los ojos cuando le habla, lo viste o juega con él/ella?",
            11: "¿Intenta su hijo/a imitar lo que usted hace?",
            12: "¿Gira la cabeza para ver si usted está mirando algo interesante?",
            13: "¿Su hijo/a intenta que usted lo/a mire para admirar algo?",
            14: "¿Comprende su hijo/a cuando usted le pide que haga algo?",
            15: "¿Parece su hijo/a ensimismado o desconectado del entorno a veces?",
        };

        this.savedAnswers = window.savedAnswers || {};

        this.initQuestionsDOM();
        this.forceHideModulesOnStart();
    }

    forceHideModulesOnStart() {
        const mchatWrapper = document.getElementById("mchatModuleWrapper");
        const simWrapper = document.getElementById("simulationModuleWrapper");
        if (mchatWrapper) mchatWrapper.classList.add("hidden");
        if (simWrapper) simWrapper.classList.add("hidden");
    }

    initQuestionsDOM() {
        const container = document.getElementById("quizQuestionsContainer");
        if (!container) return;
        container.innerHTML = "";

        for (const [num, text] of Object.entries(this.questions)) {
            let paddedNum = String(num).padStart(2, "0");
            let isCheckedSi =
                this.savedAnswers[`q_${num}`] === "si" ? "checked" : "";
            let isCheckedNo =
                this.savedAnswers[`q_${num}`] === "no" ? "checked" : "";

            let html = `
                <div class="mchat-question hidden" data-qid="${num}" data-index="${num}">
                    <span class="mchat-question__number">${paddedNum}</span>
                    <p class="mchat-question__text">${text}</p>
                    <div class="mchat-options">
                        <label class="mchat-option">
                            <input type="radio" name="q_${num}" value="si" ${isCheckedSi}>
                            <span class="mchat-option__btn">Sí</span>
                        </label>
                        <label class="mchat-option">
                            <input type="radio" name="q_${num}" value="no" ${isCheckedNo}>
                            <span class="mchat-option__btn mchat-option__btn--no">No</span>
                        </label>
                    </div>
                </div>
            `;
            container.innerHTML += html;
        }
    }

    loadModule(moduleName) {
        const menuWrapper = document.getElementById("sectionHeaderWrapper");
        const mchatModule = document.getElementById("mchatModuleWrapper");
        const simModule = document.getElementById("simulationModuleWrapper");
        const header = document.getElementById("mainHeaderSection");
        const descBox = document.getElementById("headerDescriptionBox");

        if (menuWrapper) menuWrapper.classList.add("hidden");
        if (mchatModule) mchatModule.classList.add("hidden");
        if (simModule) simModule.classList.add("hidden");

        if (header) header.classList.add("header-centered");
        if (descBox) descBox.style.display = "none";

        if (moduleName === "mchat") {
            if (mchatModule) mchatModule.classList.remove("hidden");
            if (this.isCompleted) {
                this.showResultState();
            } else {
                this.renderStep(this.currentStep);
            }
        } else if (moduleName === "aliados") {
            if (simModule) simModule.classList.remove("hidden");

            if (!window.simulationApp) {
                window.simulationApp = new SimulationApp();
            }
            window.simulationApp.start();
        }
    }

    returnToMenu() {
        const menuWrapper = document.getElementById("sectionHeaderWrapper");
        const mchatModule = document.getElementById("mchatModuleWrapper");
        const simModule = document.getElementById("simulationModuleWrapper");
        const header = document.getElementById("mainHeaderSection");
        const descBox = document.getElementById("headerDescriptionBox");

        if (mchatModule) mchatModule.classList.add("hidden");
        if (simModule) simModule.classList.add("hidden");
        if (menuWrapper) menuWrapper.classList.remove("hidden");

        if (header) header.classList.remove("header-centered");
        if (descBox) descBox.style.display = "block";
    }

    renderStep(step) {
        this.currentStep = step;
        const questionElements = document.querySelectorAll(".mchat-question");

        let startIdx = (step - 1) * this.pageSize;
        let endIdx = startIdx + this.pageSize;

        questionElements.forEach((q, index) => {
            if (index >= startIdx && index < endIdx) {
                q.classList.remove("hidden");
            } else {
                q.classList.add("hidden");
            }
        });

        let ranges = { 1: "1-5", 2: "6-10", 3: "11-15" };
        const indicator = document.getElementById("quizStepIndicator");
        if (indicator) {
            indicator.innerText = `Paso ${step} de 3 (Preguntas ${ranges[step]})`;
        }

        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");

        if (prevBtn) prevBtn.blur();
        if (nextBtn) nextBtn.blur();

        if (prevBtn) prevBtn.classList.toggle("hidden", step === 1);
        if (nextBtn) {
            nextBtn.innerText =
                step === 3 ? "Calcular Resultado Final" : "Siguiente Bloque →";
        }

        const section = document.getElementById("challenges-section");
        if (section) {
            const topPos =
                section.getBoundingClientRect().top + window.pageYOffset - 20;
            window.scrollTo({ top: topPos, behavior: "smooth" });
        }
    }

    validateCurrentBlock() {
        let startIdx = (this.currentStep - 1) * this.pageSize;
        let endIdx = startIdx + this.pageSize;
        let questionElements = document.querySelectorAll(".mchat-question");
        let isValid = true;

        for (let i = startIdx; i < endIdx; i++) {
            if (questionElements[i]) {
                let qid = questionElements[i].getAttribute("data-qid");
                let answered = questionElements[i].querySelector(
                    `input[name="q_${qid}"]:checked`,
                );
                if (!answered) {
                    isValid = false;
                    break;
                }
            }
        }
        return isValid;
    }

    nextStep() {
        if (!this.validateCurrentBlock()) {
            const modal = document.getElementById("validationModal");
            if (modal) modal.classList.remove("hidden");
            return;
        }

        let answersData = this.collectCurrentBlockAnswers();

        if (this.currentStep < 3) {
            let nextStepNum = this.currentStep + 1;
            this.saveProgressToServer(answersData, nextStepNum, () => {
                this.renderStep(nextStepNum);
            });
        } else {
            this.submitQuiz();
        }
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.renderStep(this.currentStep - 1);
        }
    }

    collectCurrentBlockAnswers() {
        let startIdx = (this.currentStep - 1) * this.pageSize;
        let endIdx = startIdx + this.pageSize;
        let questionElements = document.querySelectorAll(".mchat-question");
        let data = {};

        for (let i = startIdx; i < endIdx; i++) {
            if (questionElements[i]) {
                let qid = questionElements[i].getAttribute("data-qid");
                let checked = questionElements[i].querySelector(
                    `input[name="q_${qid}"]:checked`,
                );
                if (checked) data[`q_${qid}`] = checked.value;
            }
        }
        return data;
    }

    saveProgressToServer(answers, nextStep, callback) {
        fetch("/information/mchat/progress", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                    .value,
            },
            body: JSON.stringify({ step: nextStep, answers: answers }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success && callback) callback();
            });
    }

    submitQuiz() {
        let form = document.getElementById("mchatQuizForm");
        let formData = new FormData(form);
        let allAnswers = {};
        for (let [key, value] of formData.entries()) {
            if (key.startsWith("q_")) allAnswers[key] = value;
        }

        fetch("/information/mchat/submit", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                    .value,
            },
            body: JSON.stringify({ answers: allAnswers }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    this.isCompleted = true;
                    this.displayResult(
                        data.score,
                        data.risk_level,
                        data.feedback,
                    );
                }
            });
    }

    displayResult(score, riskLevel, feedback) {
        const questionElements = document.querySelectorAll(".mchat-question");
        questionElements.forEach((q) => q.classList.remove("hidden"));

        document
            .getElementById("quizQuestionsContainer")
            .classList.add("hidden");
        document.querySelector(".quiz-nav-buttons").classList.add("hidden");
        document
            .getElementById("quizResultContainer")
            .classList.remove("hidden");
        document.getElementById("finalScoreVal").innerText = score;

        let badge = document.getElementById("finalRiskBadge");
        badge.innerText = `Riesgo ${riskLevel}`;
        badge.className = `mchat-result__badge mchat-result__badge--${riskLevel.toLowerCase()}`;
        document.getElementById("finalFeedbackText").innerText = feedback;
        document.getElementById("quizStepIndicator").innerText =
            "Test Completado";
    }

    showResultState() {
        const questionElements = document.querySelectorAll(".mchat-question");
        questionElements.forEach((q) => q.classList.remove("hidden"));

        document
            .getElementById("quizQuestionsContainer")
            .classList.add("hidden");
        document.querySelector(".quiz-nav-buttons").classList.add("hidden");
        document
            .getElementById("quizResultContainer")
            .classList.remove("hidden");
        document.getElementById("quizStepIndicator").innerText =
            "Test Completado";
    }

    closeModal() {
        const modal = document.getElementById("validationModal");
        if (modal) modal.classList.add("hidden");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    window.appChallenges = new ChallengeApp();

    const urlParams = new URLSearchParams(window.location.search);
    const activeModule = urlParams.get("module");

    if (activeModule === "mchat") {
        window.appChallenges.loadModule("mchat");
        const section = document.querySelector(".challenges-section");
        if (section) section.scrollIntoView({ behavior: "smooth" });
    } else if (activeModule === "aliados" || activeModule === "simulation") {
        window.appChallenges.loadModule("aliados");
        const section = document.querySelector(".challenges-section");
        if (section) section.scrollIntoView({ behavior: "smooth" });
    }
});

/*SIMULADOR DE ALIADOS */

class SimulationApp {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 5;
        this.answers = {};
        this.currentRotation = 0;

        const container = document.getElementById("simulationSection");
        this.initialHTML = container ? container.innerHTML : "";

        this.scenarios = {
            1: {
                question:
                    "Un compañero con TEA comienza a taparse los oídos fuertemente y agitar las manos debido al ruido de la bocina escolar. ¿Qué harías?",
                options: {
                    a: "Gritarle para que se calme y preste atención.",
                    b: "Guiarlo con calma hacia un espacio con menos ruido y estímulos.",
                    c: "Ignorarlo por completo para que se acostumbre al entorno.",
                },
                correct: "b",
                feedback:
                    "Reducir la sobrecarga sensorial alejándolo del ruido es el paso clave para evitar una crisis por estimulación excesiva.",
            },
            2: {
                question:
                    "Durante una actividad en equipo, notas que un alumno con TEA prefiere trabajar solo y evita mirar a los demás. ¿Qué enfoque es el más empático?",
                options: {
                    a: "Exigirle que participe mirando a los ojos como sus compañeros.",
                    b: "Respetar su espacio y ofrecerle un rol alternativo donde se sienta cómodo.",
                    c: "Excluirlo del equipo para no retrasar el trabajo.",
                },
                correct: "b",
                feedback:
                    "La empatía compasiva implica respetar las diferencias de comunicación y ofrecer alternativas seguras de integración.",
            },
            3: {
                question:
                    "En el recreo, un niño con TEA repite una frase una y otra vez (ecolalia) moviendo un objeto en sus manos. ¿Cómo actuarías?",
                options: {
                    a: "Decirle de forma cortante que deje de repetir eso.",
                    b: "Comprender que es su forma de autorregulación y permitirle su espacio sin juzgar.",
                    c: "Quitarle el objeto para que interactúe con los demás juegos.",
                },
                correct: "b",
                feedback:
                    "Los movimientos repetitivos y la ecolalia suelen ser mecanismos vitales de autorregulación ante la ansiedad o la emoción.",
            },
            4: {
                question:
                    "Tienes que explicar un cambio repentino en la rutina escolar del día. ¿Cuál es la mejor manera de comunicárselo?",
                options: {
                    a: "Decírselo de imprevisto en el momento para que aprenda a ser flexible.",
                    b: "Anticipárselo visualmente o con antelación explicando los cambios paso a paso.",
                    c: "No decirle nada para evitar que se preocupe antes de tiempo.",
                },
                correct: "b",
                feedback:
                    "La anticipación visual y estructurada reduce drásticamente la ansiedad generada por la incertidumbre en personas con TEA.",
            },
            5: {
                question:
                    "Un padre te comenta que su hijo se siente abrumado en las fiestas de cumpleaños ruidosas. ¿Qué consejo de aliado le darías?",
                options: {
                    a: "Obligarlo a quedarse toda la fiesta para que supere su miedo social.",
                    b: "Permitirle retirarse a una zona tranquila o planificar descansos cortos durante el evento.",
                    c: "Prohibirle asistir a cualquier evento social en adelante.",
                },
                correct: "b",
                feedback:
                    "Establecer zonas de escape o tiempos regulados permite la socialización saludable sin llevar al agotamiento sensorial.",
            },
        };
    }

    async start() {
        this.answers = {};
        this.currentRotation = 0;

        try {
            const response = await fetch("/simulation/progress");
            const data = await response.json();
            if (data.success && data.answers) {
                this.answers = data.answers;
                const answeredKeys = Object.keys(this.answers);
                this.currentStep =
                    answeredKeys.length > 0
                        ? Math.min(answeredKeys.length + 1, this.totalSteps)
                        : 1;
            }
        } catch (e) {
            console.warn("Aviso: Iniciando en modo local por fallo de red:", e);
            this.currentStep = 1;
        }

        const activeCard = document.getElementById("activeSimulatorCard");
        if (activeCard) activeCard.style.transform = `rotateY(0deg)`;

        this.renderScenario(this.currentStep);
    }

    renderScenario(step) {
        const scenario = this.scenarios[step];
        if (!scenario) return;

        this.currentStep = step;

        const stepIndicator = document.getElementById("simStepIndicator");
        const questionText = document.getElementById("simQuestionText");
        const optionsContainer = document.getElementById("simOptionsContainer");
        const prevBtnFront = document.getElementById("prevBtnFront");

        if (stepIndicator)
            stepIndicator.innerText = `Situación ${step} de ${this.totalSteps}`;
        if (questionText) questionText.innerText = scenario.question;

        if (optionsContainer) {
            optionsContainer.innerHTML = "";
            for (const [key, text] of Object.entries(scenario.options)) {
                const btn = document.createElement("button");
                btn.type = "button";
                btn.className = "simulator-opt-btn";

                if (this.answers[`q_${step}`] === key) {
                    btn.style.borderColor = "#4f46e5";
                    btn.style.backgroundColor = "#eef2ff";
                }

                btn.innerHTML = `<strong>${key.toUpperCase()})</strong> ${text}`;
                btn.onclick = () => this.selectOption(step, key);
                optionsContainer.appendChild(btn);
            }
        }

        if (prevBtnFront) {
            prevBtnFront.style.display = step > 1 ? "inline-block" : "none";
        }
    }

    selectOption(step, choice) {
        const scenario = this.scenarios[step];
        const isCorrect = choice === scenario.correct;

        // 1. Guardamos la respuesta explícitamente
        this.answers[`q_${step}`] = choice;

        // 🔍 PRUEBA DE DEPURACIÓN: Abre tu consola (F12) y verifica que aquí aparezca la respuesta
        console.log("Respuestas actuales en memoria:", this.answers);

        const iconEl = document.getElementById("simFeedbackIcon");
        const titleEl = document.getElementById("simFeedbackTitle");
        const descEl = document.getElementById("simFeedbackDesc");
        const nextBtn = document.getElementById("simNextBtn");

        if (iconEl) iconEl.innerText = isCorrect ? "✨" : "💡";
        if (titleEl) {
            titleEl.innerText = isCorrect
                ? "¡Excelente, respuesta correcta!"
                : "Buena reflexión, pero hay una mejor opción:";
            titleEl.style.color = isCorrect ? "#10b981" : "#f59e0b";
        }

        if (descEl) descEl.innerText = scenario.feedback;

        if (nextBtn) {
            if (step === this.totalSteps) {
                nextBtn.innerText = "Ver Resultado Final de Empatía →";
                nextBtn.onclick = (e) => {
                    e.preventDefault();
                    this.submitFinalSimulation();
                };
            } else {
                nextBtn.innerText = "Siguiente Situación →";
                nextBtn.onclick = (e) => {
                    e.preventDefault();
                    this.nextScenario();
                };
            }
        }

        this.currentRotation += 180;
        const activeCard = document.getElementById("activeSimulatorCard");
        if (activeCard)
            activeCard.style.transform = `rotateY(${this.currentRotation}deg)`;

        // 2. Enviamos al servidor
        this.saveProgressToServer();
    }
    nextScenario() {
        if (this.currentStep < this.totalSteps) {
            this.currentStep++;
            this.renderScenario(this.currentStep);
            this.currentRotation += 180;

            const activeCard = document.getElementById("activeSimulatorCard");
            if (activeCard)
                activeCard.style.transform = `rotateY(${this.currentRotation}deg)`;

            const section = document.getElementById("challenges-section");
            if (section)
                section.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    }

    prevScenario() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.renderScenario(this.currentStep);
            this.currentRotation -= 360;

            const activeCard = document.getElementById("activeSimulatorCard");
            if (activeCard)
                activeCard.style.transform = `rotateY(${this.currentRotation}deg)`;

            const section = document.getElementById("challenges-section");
            if (section)
                section.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    }

    getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute("content") : "";
    }

    saveProgressToServer() {
        // Asegurarnos de que las respuestas se envíen como un objeto plano estructurado
        const answersObj = Object.assign({}, this.answers);

        fetch("/simulation/save-progress", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": this.getCsrfToken(),
            },
            body: JSON.stringify({
                answers: answersObj,
                current_step: this.currentStep,
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                console.log("Progreso guardado correctamente en la BD:", data);
            })
            .catch((err) => console.error("Error guardando progreso:", err));
    }
    renderResultState(data) {
        const container = document.getElementById("simulationSection");
        if (!container) return;

        container.innerHTML = `
            <div class="simulator-card text-center" style="padding: 3rem; background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🏆</div>
                <h2 style="font-size: 1.8rem; color: #1f2937; margin-bottom: 0.5rem;">¡Simulación Completada!</h2>
                <p style="color: #4b5563; margin-bottom: 1.5rem;">Tu nivel de empatía obtenido es:</p>
                <div style="background: #eef2ff; color: #4f46e5; padding: 1rem; border-radius: 12px; font-size: 1.3rem; font-weight: bold; margin-bottom: 1.5rem;">
                    ${data.empathy_level || "Aliado Compasivo"}
                </div>
                <p style="color: #374151; font-size: 1.05rem; line-height: 1.6; margin-bottom: 2rem;">
                    ${data.feedback || "Has completado con éxito todas las situaciones del simulador de aliados."}
                </p>
                <button type="button" class="challenges-primary-btn" onclick="window.simulationApp.resetSimulator()">
                    🔄 Repetir Simulador
                </button>
            </div>
        `;

        const challengeSection = document.getElementById("challenges-section");
        if (challengeSection)
            challengeSection.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
    }

    async resetSimulator() {
        const container = document.getElementById("simulationSection");
        if (container && this.initialHTML)
            container.innerHTML = this.initialHTML;

        // Reiniciamos llamando a start() para que el backend cree un nuevo attempt limpio
        await this.start();

        const challengeSection = document.getElementById("challenges-section");
        if (challengeSection)
            challengeSection.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
    }

    submitFinalSimulation() {
        fetch("/simulation/submit", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": this.getCsrfToken(),
            },
            body: JSON.stringify({
                answers: this.answers, // 👈 Aquí pasas this.answers
                current_step: this.currentStep,
            }),
        })
            .then((res) => {
                if (!res.ok) throw new Error("Error en el servidor");
                return res.json();
            })
            .then((data) => {
                if (data && data.success) {
                    this.renderResultState(data);
                } else {
                    this.renderResultState({
                        empathy_level: "Aliado Compasivo Destacado",
                        feedback:
                            "Has demostrado una excelente comprensión de las situaciones y los retos que viven las personas con TEA.",
                    });
                }
            })
            .catch((err) => {
                console.warn("Modo local activado:", err);
                this.renderResultState({
                    empathy_level: "Aliado Empático en Formación",
                    feedback:
                        "Completaste satisfactoriamente todas las situaciones del simulador de aliados.",
                });
            });
    }
}

document.addEventListener("DOMContentLoaded", () => {
    window.simulationApp = new SimulationApp();
    window.simulationApp.start();
});

window.SimulationApp = SimulationApp;
