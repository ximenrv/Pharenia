<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Información') }}</title>
    
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>

    <script>
        window.translations = @json($translations);
    </script>

    @vite(['resources/css/information.css', 'resources/css/navbar.css', 'resources/css/footer.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/js/information.js', 'resources/css/dark-theme.css', 'resources/css/lumen-chat.css', 'resources/js/lumen-chat.js'])
    
</head>
<body class="info-body">

    @include('components.loader')

    <x-navbar />

    @include('components.transition-waves')

    <div class="info-container" id="section01">
        
        <header class="info-header" id="trigger-start">
            <div class="info-header__left">
                <span class="info-header__subtitle">{{ __('FILOSOFÍA') }}</span>
                <h1 class="info-header__title">01</h1>
            </div>
            <div class="info-header__right">
                <blockquote class="info-header__quote">
                    "{{ __('El autismo no es una limitación. Es una forma diferente de experimentar el mundo.') }}"
                </blockquote>
            </div>
        </header>

        <section class="info-cards">
            <div class="info-card">
                <div class="info-card__cloud-container">
                    <img src="{{ asset('img/cloud-1.png') }}" alt="Nube" class="info-card__cloud">
                </div>
                <div class="info-card__content">
                    <h3 class="info-card__title">{{ __('¿Para qué nace pharenia?') }}</h3>
                    <p class="info-card__text">
                        {{ __('Para combat la desinformación que rodea al Trastorno del Espectro Autista.') }}
                    </p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-card__cloud-container">
                    <img src="{{ asset('img/cloud-2.png') }}" alt="Nube" class="info-card__cloud">
                </div>
                <div class="info-card__content">
                    <h3 class="info-card__title">{{ __('¿Qué somos?') }}</h3>
                    <p class="info-card__text">
                        {{ __('Un puente tecnológico entre el hogar y el desarrollo personal. No reemplazamos la intervención clínica, la complementamos.') }}
                    </p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-card__cloud-container">
                    <img src="{{ asset('img/cloud-3.png') }}" alt="Nube" class="info-card__cloud">
                </div>
                <div class="info-card__content">
                    <h3 class="info-card__title">{{ __('¿Cómo nos complementamos?') }}</h3>
                    <p class="info-card__text">
                        {{ __('Por medio de herramientas tecnológicas accesibles a todo público, cuidadas por nuestro equipo Crea J.') }}
                    </p>
                </div>
            </div>
        </section>
        
        <section class="tea-section">
            <div class="tea-section__left" id="section02">
                <header class="tea-section__header">
                    <h2 class="tea-section__number">02</h2>
                    <h1 class="tea-section__title">{{ __('El TEA') }}</h1>
                </header>

                <div class="tea-section__content">
                    <p class="tea-section__main-text">
                        {!! __('El Trastorno del Espectro Autista (TEA) es una forma diferente en la que algunas personas perciben, sienten y se relacionan con el mundo. <strong>No es una enfermedad</strong>, sino una condición que está presente desde la infancia y acompaña a la persona toda su vida. Cada persona con TEA es única: algunas pueden necesitar más apoyo en su día a día, mientras que otras son más independientes. Por eso se habla de un "espectro", porque las características y necesidades pueden variar mucho de una persona a otra.') !!}
                    </p>

                    <div class="tea-section__alert-box">
                        <h4 class="tea-section__alert-title">{{ __('EDAD DE MANIFESTACIÓN') }}</h4>
                        <p class="tea-section__alert-text">
                            {{ __('El TEA suele manifestarse antes de los 3 años de edad. Al ser una condición del neurodesarrollo, está presente durante toda la vida de la persona. Algunos niños muestran señales desde los primeros 12 meses, mientras que en otros estas se hacen más evidentes alrededor de los 24 meses o después.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="tea-section__right">
                <div class="sabias-card" role="group" aria-labelledby="sabias-title">
                    <div class="sabias-card__orb" aria-hidden="true">
                        <span class="sabias-card__wheel"></span>
                        <span class="sabias-card__core">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.4 1 2.3h6c0-.9.4-1.8 1-2.3A7 7 0 0 0 12 2z"/></svg>
                        </span>
                    </div>

                    <span id="sabias-title" class="sabias-card__badge">{{ __('¿Sabías qué?') }}</span>

                    <ul class="sabias-card__list">
                        <li class="sabias-card__item">
                            <span class="sabias-card__dot sabias-card__dot--teal" aria-hidden="true"></span>
                            <p>{{ __('Se estima que el TEA se presenta en aproximadamente 1 de cada 100 niños en el mundo.') }}</p>
                        </li>
                        <li class="sabias-card__item">
                            <span class="sabias-card__dot sabias-card__dot--gold" aria-hidden="true"></span>
                            <p>{{ __('Cada persona con TEA es única: tiene sus propias fortalezas y necesidades de apoyo.') }}</p>
                        </li>
                        <li class="sabias-card__item">
                            <span class="sabias-card__dot sabias-card__dot--coral" aria-hidden="true"></span>
                            <p>{{ __('Con comprensión y apoyo, muchas personas con TEA desarrollan una vida plena y autónoma.') }}</p>
                        </li>
                    </ul>

                    <div class="sabias-card__footer">
                        <p>✦ {{ __('Pharenia te acompaña a entender el TEA.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="levels-cloud-section" id="section03">
            
            <header class="levels-cloud-header">
                <div class="levels-cloud-header__top-row">
                    <h2 class="levels-cloud-header__number">03</h2>
                </div>
                <div class="levels-cloud-header__content">
                    <span class="levels-cloud-header__subtitle">{{ __('Clasificación dsm-5') }}</span>
                    <h1 class="levels-cloud-header__title">{{ __('Niveles de Soporte') }}</h1>
                </div>
            </header>

            <div class="levels-cloud-path">
                
                <article class="level-cloud-card level-cloud-card--1">
                    <div class="level-cloud-card-content">
                        <div class="level-cloud-badge badge-lvl1">{{ __('Grado Leve') }}</div>
                        <h3 class="level-cloud-title"><span class="level-cloud-num num-lvl1">01.</span> {{ __('Requiere Apoyo') }}</h3>
                        <p class="level-cloud-text">
                            {{ __('Cuentan con comunicación verbal fluida y capacidades cognitivas completas. Sin embargo, presentan dificultades para iniciar interacciones sociales y respuestas atípicas. Tienen retos al organizar, planificar y adaptarse a giros bruscos en la rutina.') }}
                        </p>
                        <div class="level-cloud-focus-inline focus-inline-lvl1">
                            {!! __('mchat.focus_intervention_1') !!}
                        </div>
                    </div>
                </article>

                <article class="level-cloud-card level-cloud-card--2">
                    <div class="level-cloud-card-content">
                        <div class="level-cloud-badge badge-lvl2">{{ __('Grado Moderado') }}</div>
                        <h3 class="level-cloud-title"><span class="level-cloud-num num-lvl2">02.</span> {{ __('Apoyo Notable') }}</h3>
                        <p class="level-cloud-text">
                            {{ __('Las deficiencias en la comunicación verbal y no verbal son evidentes incluso con apoyos visuales. Suelen comunicarse con frases sencillas y sus interacciones se limitan a intereses muy específicos. Muestran ansiedad o malestar visible ante interrupciones.') }}
                        </p>
                        <div class="level-cloud-focus-inline focus-inline-lvl2">
                            {!! __('mchat.focus_intervention_2') !!}
                        </div>
                    </div>
                </article>

                <article class="level-cloud-card level-cloud-card--3">
                    <div class="level-cloud-card-content">
                        <div class="level-cloud-badge badge-lvl3">{{ __('Grado Severo') }}</div>
                        <h3 class="level-cloud-title"><span class="level-cloud-num num-lvl3">03.</span> {{ __('Apoyo Muy Notable') }}</h3>
                        <p class="level-cloud-text">
                            {{ __('Alteraciones graves de la comunicación y del comportamiento que causan deficiencias severas en el funcionamiento diario. Incluye la mayoría de casos de autismo no verbal, manifestando una gran resistencia al cambio y conductas repetitivas constantes.') }}
                        </p>
                        <div class="level-cloud-focus-inline focus-inline-lvl3">
                            {!! __('mchat.focus_intervention_3') !!}
                        </div>
                    </div>
                </article>

            </div>
        </section>

        <section class="stages-section">
            <header class="stages-header">
                <div class="stages-header__right" id="section04">
                    <span class="stages-header__subtitle">{{ __('ETAPAS DE DESARROLLO') }}</span>
                    <h2 class="stages-header__number">04</h2>
                </div>
                <div class="stages-header__left">
                    <h1 class="stages-header__title">{{ __('El TEA en las diferentes etapas de la vida') }}</h1>
                </div>
            </header>

            <div class="stages-grid">
                <div class="stage-card">
                    <div class="stage-card__cloud-container">
                        <img src="{{ asset('img/cloud-1.png') }}" alt="Nube" class="stage-card__cloud stage-card__cloud--1">
                    </div>
                    <div class="stage-card__body">
                        <p class="stage-card__text">
                            {{ __('En esta etapa suelen hacerse evidentes las primeras características del TEA. La detección e intervención tempranas favorecen el desarrollo de habilidades comunicativas, sociales y adaptativas. Según las necesidades individuales, pueden recomendarse intervenciones como terapia del lenguaje, terapia ocupacional o análisis aplicado de la conducta (ABA).') }}
                        </p>
                    </div>
                    <div class="stage-card__tag">
                        <h3 class="stage-card__tag-title">{{ __('Niñez (0 – 12 años)') }}</h3>
                    </div>
                </div>

                <div class="stage-card">
                    <div class="stage-card__cloud-container">
                        <img src="{{ asset('img/cloud-2.png') }}" alt="Nube" class="stage-card__cloud stage-card__cloud--2">
                    </div>
                    <div class="stage-card__body">
                        <p class="stage-card__text">
                            {{ __('Durante la adolescencia pueden presentarse desafíos relacionados con la interacción social, la comunicación y la adaptación a los cambios propios de esta etapa. También pueden requerirse apoyos orientados al fortalecimiento de la autonomía, las habilidades sociales y la adaptación a nuevos entornos.') }}
                        </p>
                    </div>
                    <div class="stage-card__tag">
                        <h3 class="stage-card__tag-title">{{ __('Jóvenes (13 – 17 años)') }}</h3>
                    </div>
                </div>

                <div class="stage-card">
                    <div class="stage-card__cloud-container">
                        <img src="{{ asset('img/cloud-3.png') }}" alt="Nube" class="stage-card__cloud stage-card__cloud--3">
                    </div>
                    <div class="stage-card__body">
                        <p class="stage-card__text">
                            {{ __('En la adultez, las necesidades de apoyo dependen de las características y circunstancias de cada persona. Algunas personas logran desenvolverse con mayor autonomía en ámbitos como el estudio, el trabajo y la vida independiente, mientras que otras continúan requiriendo apoyos en diferentes áreas de su vida.') }}
                        </p>
                    </div>
                    <div class="stage-card__tag">
                        <h3 class="stage-card__tag-title">{{ __('Adultos (18+ años)') }}</h3>
                    </div>
                </div>
            </div>
        </section>

        <section class="treatments-section">
            <div class="treatments-section__left" id="section05">
                <header class="treatments-section__header">
                    <h2 class="treatments-section__number">05</h2>
                    <h1 class="treatments-section__title">{{ __('TIPOS DE TRATAMIENTO') }}</h1>
                </header>
                <img src="{{ asset('img/lumen-treatments.png') }}" alt="Lumen - Tipos de Tratamiento" class="treatments-section__char-img">
            </div>

            <div class="treatments-section__right">
                <div class="treatments-section__box">
                    <p class="treatments-section__intro-text">
                        {{ __('El tratamiento para el TEA no es único, sino que combina diferentes enfoques personalizados de forma interdisciplinaria para mejorar el desarrollo, la comunicación, la autonomía y la participación social de cada persona.') }}
                    </p>
                    
                    <div class="treatments-section__accordion-container">
                        <details class="treatments-accordion" name="treatments">
                            <summary class="treatments-accordion__summary">{{ __('Tratamientos conductuales') }}</summary>
                            <div class="treatments-accordion__content">
                                <p>{{ __('Se enfocan en enseñar nuevas habilidades y conductas mediante el refuerzo positivo. Ejemplos comunes incluyen el análisis conductual aplicado (ABA), que ayuda a mejorar la comunicación y reducir comportamientos desafiantes.') }}</p>
                            </div>
                        </details>

                        <details class="treatments-accordion" name="treatments">
                            <summary class="treatments-accordion__summary">{{ __('Tratamientos del desarrollo') }}</summary>
                            <div class="treatments-accordion__content">
                                <p>{{ __('Se centran en mejorar habilidades específicas del desarrollo, como el habla, el lenguaje y las destrezas físicas. Incluyen la terapia del lenguaje y la terapia ocupacional, orientadas a la autonomía diaria.') }}</p>
                            </div>
                        </details>

                        <details class="treatments-accordion" name="treatments">
                            <summary class="treatments-accordion__summary">{{ __('Tratamientos educacionales') }}</summary>
                            <div class="treatments-accordion__content">
                                <p>{{ __('Intervenciones que se integran en el entorno escolar. Utilizan sistemas visuales estructurados (como el método TEACCH) para ayudar a los estudiantes a comprender mejor sus rutinas y actividades académicas.') }}</p>
                            </div>
                        </details>

                        <details class="treatments-accordion" name="treatments">
                            <summary class="treatments-accordion__summary">{{ __('Tratamientos socio-relacionales') }}</summary>
                            <div class="treatments-accordion__content">
                                <p>{{ __('Buscan mejorar las habilidades sociales y construir vínculos emocionales. Se enfocan en el juego interactivo, círculos de amigos y el desarrollo de dinámicas de empatía grupal.') }}</p>
                            </div>
                        </details>

                        <details class="treatments-accordion" name="treatments">
                            <summary class="treatments-accordion__summary">{{ __('Tratamientos farmacológicos') }}</summary>
                            <div class="treatments-accordion__content">
                                <p>{{ __('Aunque no curan el TEA, ciertos medicamentos recetados por especialistas pueden ayudar a gestionar síntomas coadyuvantes como la alta ansiedad, la hiperactividad o problemas graves del sueño.') }}</p>
                            </div>
                        </details>

                        <details class="treatments-accordion" name="treatments">
                            <summary class="treatments-accordion__summary">{{ __('Tratamientos psicológicos') }}</summary>
                            <div class="treatments-accordion__content">
                                <p>{{ __('La terapia cognitivo-conductual (TCC) ayuda a las personas a comprender la relación entre sus pensamientos, sentimientos y comportamientos, facilitando el manejo de las emociones cotidianas.') }}</p>
                            </div>
                        </details>

                        <details class="treatments-accordion" name="treatments">
                            <summary class="treatments-accordion__summary">{{ __('Intervenciones nutricionales') }}</summary>
                            <div class="treatments-accordion__content">
                                <p>{{ __('Planes alimenticios supervisados por profesionales para asegurar la correcta absorción de nutrientes, en especial cuando existen patrones de alimentación muy selectivos o problemas gastrointestinales.') }}</p>
                            </div>
                        </details>

                        <details class="treatments-accordion" name="treatments">
                            <summary class="treatments-accordion__summary">{{ __('Tratamientos complementarios') }}</summary>
                            <div class="treatments-accordion__content">
                                <p>{{ __('Actividades terapéuticas integrales que enriquecen el bienestar del individuo, tales como la equinoterapia (terapia con caballos), la terapia asistida con animales, el arte o la musicoterapia.') }}</p>
                            </div>
                        </details>
                    </div>
                </div>
            </div>
        </section>

        <section class="challenges-section" id="challenges-section">
            
            @guest
                <div class="challenges-auth-overlay">
                    <div class="challenges-auth-card">
                        <div class="challenges-auth-card__icon">🔒</div>
                        <h3 class="challenges-auth-card__title">{{ __('Centro Interactivo Protegido') }}</h3>
                        <p class="challenges-auth-card__text">{{ __('Para medir tu nivel de empatía, realizar el cribado clínico y almacenar tu progreso automático, necesitas contar con una cuenta activa.') }}</p>
                        <a href="{{ route('login') }}" class="challenges-auth-card__btn">{{ __('Iniciar Sesión / Registrarse') }}</a>
                    </div>
                </div>
            @endguest

            <div class="challenges-module-content @guest is-locked @endguest" id="section06">
                
                <header id="mainHeaderSection" class="challenges-header">
                    <div class="challenges-header__left">
                        <h2 class="challenges-header__number">06</h2>
                        <h1 class="challenges-header__title">{{ __('Centro de Desafíos TEA') }}</h1>
                    </div>
                    <div id="headerDescriptionBox" class="challenges-header__right">
                        <p class="challenges-header__description">
                            {{ __('Pon a prueba tus conocimientos, evalúa señales de alerta clínica o entrena tus habilidades de inclusión social en tiempo real a través de nuestros tres módulos gamificados.') }}
                        </p>
                    </div>
                </header>

                <div id="sectionHeaderWrapper">
                    <div class="challenges-container">
                        <div id="challengesMenu" class="challenges-grid">
                            <div class="challenge-card" onclick="appChallenges.loadModule('mchat')">
                                <div class="challenge-card__badge">{{ __('Clínico') }}</div>
                                <h3 class="challenge-card__title">{{ __('Cuestionario M-CHAT-R') }}</h3>
                                <p class="challenge-card__desc">{{ __('Herramienta de cribado para evaluar el riesgo de rasgos del Espectro Autista en infantes.') }}</p>
                                <span class="challenge-card__action">{{ __('Comenzar Desafío →') }}</span>
                            </div>
                            
                            <div class="challenge-card challenge-card--gold" onclick="appChallenges.loadModule('aliados')">
                                <div class="challenge-card__badge">{{ __('Empatía') }}</div>
                                <h3 class="challenge-card__title">{{ __('Simulador de Aliados') }}</h3>
                                <p class="challenge-card__desc">{{ __('Toma decisiones en tiempo real ante 5 situaciones reales e incómodas de inclusión diaria.') }}</p>
                                <span class="challenge-card__action">{{ __('Entrar al Simulador →') }}</span>
                            </div>

                            <div class="challenge-card" onclick="appChallenges.loadModule('mitos')">
                                <div class="challenge-card__badge">{{ __('Educación') }}</div>
                                <h3 class="challenge-card__title">{{ __('Mitos vs Realidades') }}</h3>
                                <p class="challenge-card__desc">{{ __('Derriba estigmas sociales respondiendo de forma ágil un Verdadero o Falso dinámico.') }}</p>
                                <span class="challenge-card__action">{{ __('Iniciar Desafío →') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="mchatModuleWrapper" class="hidden">
                    
                    <button type="button" class="challenges-back-btn" onclick="appChallenges.returnToMenu()">
                        {{ __('← Volver al menú de desafíos') }}
                    </button>

                    <div class="challenges-container">
                        <div class="mchat-quiz-header">
                            <h2 class="mchat-quiz-title">{{ __('Cuestionario M-CHAT-R') }}</h2>
                            <span id="quizStepIndicator" class="mchat-step-indicator">{{ __('Bloque 1 de 3 (Preguntas 1-5)') }}</span>
                        </div>

                        <form id="mchatQuizForm">
                            @csrf
                            
                            <div id="quizQuestionsContainer"></div>

                            <div class="quiz-nav-buttons">
                                <button type="button" id="prevBtn" class="challenges-secondary-btn hidden" onclick="appChallenges.prevStep()">{{ __('← Anterior') }}</button>
                                <button type="button" id="nextBtn" class="challenges-primary-btn" onclick="appChallenges.nextStep()">{{ __('Siguiente Bloque →') }}</button>
                            </div>

                        </form>

                        <div id="quizResultContainer" class="hidden mchat-result">
                            <h3 class="mchat-result__title">{{ __('Puntuación Total:') }} <span id="finalScoreVal">{{ $progress->total_score ?? 0 }}</span></h3>
                            <div id="finalRiskBadge" class="mchat-result__badge @if(($progress->risk_level ?? '') == 'Alto') mchat-result__badge--alto @elseif(($progress->risk_level ?? '') == 'Medio') mchat-result__badge--medio @else mchat-result__badge--bajo @endif">
                                {{ __('Riesgo') }} {{ $progress->risk_level ?? __('Bajo') }}
                            </div>
                            <p id="finalFeedbackText" class="mchat-result__feedback">
                                @if(isset($progress->risk_level))
                                    @if($progress->risk_level == 'Alto') {{ __('La puntuación indica un riesgo alto. Se recomienda una evaluación clínica detallada.') }}
                                    @elseif($progress->risk_level == 'Medio') {{ __('La puntuación indica un riesgo moderado. Se sugiere seguimiento cercano.') }}
                                    @else {{ __('La puntuación indica un riesgo bajo. No se requieren acciones adicionales a menos que se observen otros signos.') }}
                                    @endif
                                @else
                                    {{ __('La puntuación indica un riesgo bajo...') }}
                                @endif
                            </p>
                            
                            <form action="{{ route('information.mchat.reset') }}" method="POST">
                                @csrf
                                <button type="submit" class="challenges-reset-btn">{{ __('Repetir Test') }}</button>
                            </form>
                        </div>

                    </div>
                </div>
                
                <div id="simulationModuleWrapper" class="hidden">
                    <button type="button" class="challenges-back-btn" onclick="appChallenges.returnToMenu()">
                        {{ __('← Volver al menú de desafíos') }}
                    </button>
                    <div class="simulation-container" id="simulationSection">
                        <div class="simulator-card" id="activeSimulatorCard">
                            <div class="simulator-card__face simulator-card__front">
                        <span class="simulator-badge" id="simStepIndicator">{{ __('Situación 1 de 5') }}</span>
                        <p class="simulator-text" id="simQuestionText">{{ __('Cargando situación...') }}</p>
                        
                        <div class="simulator-options" id="simOptionsContainer">
                        </div>

                        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
                            <button type="button" class="challenges-secondary-btn" id="prevBtnFront" style="display: none;" onclick="window.simulationApp.prevScenario()">
                                {{ __('← Anterior') }}
                            </button>
                        </div>
                    </div>
                            <div class="simulator-card__face simulator-card__back">
                                <div id="simFeedbackIcon" class="feedback-icon">✨</div>
                                <h3 id="simFeedbackTitle" class="feedback-title">{{ __('¡Correcto!') }}</h3>
                                <p id="simFeedbackDesc" class="feedback-desc">{{ __('Explicación de por qué esta es la mejor opción de aliado.') }}</p>
                                <button type="button" class="challenges-primary-btn" id="simNextBtn" onclick="window.simulationApp.nextScenario()">{{ __('Siguiente Situación →') }}</button>
                            </div>
                        </div>
                    </div>
                </div> 

                <div id="mythModuleWrapper" class="myth-challenges-wrapper hidden" >
                    <button type="button" class="myth-back-btn" onclick="appChallenges.returnToMenu()">
                        {{ __('← Volver al menú de desafíos') }}
                    </button>

                    <div class="myth-container" id="mythSection">
                        <div class="myth-card" id="activeMythCard">
                            <div class="myth-card__face myth-card__front">
                                <span class="myth-badge" id="mythStepIndicator">{{ __('Pregunta 1 de 10') }}</span>
                                <p class="myth-text" id="mythStatementText">{{ __('Cargando enunciado...') }}</p>
                                
                                <div class="myth-options" id="mythOptionsContainer">
                                </div>

                                <div class="myth-navigation-container" id="mythNavContainer" style="display: flex; justify-content: space-between; margin-top: 1.5rem;">
                                </div>
                            </div>

                            <div class="myth-card__face myth-card__back">
                                <div id="mythFeedbackIcon" class="myth-feedback-icon">✨</div>
                                <h3 id="mythFeedbackTitle" class="myth-feedback-title">{{ __('¡Correcto!') }}</h3>
                                <p id="mythFeedbackDesc" class="myth-feedback-desc">{{ __('Explicación detallada del mito y la realidad.') }}</p>
                                <button type="button" class="myth-primary-btn" id="mythNextBtn" onclick="window.mythApp.nextMyth()">{{ __('Siguiente Pregunta →') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <div id="validationModal" class="challenges-modal-overlay hidden">
            <div class="challenges-modal-card">
                <div class="challenges-modal-icon">⚠️</div>
                <h3 class="challenges-modal-title">{{ __('Preguntas Incompletas') }}</h3>
                <p class="challenges-modal-text">{{ __('Por favor, responde todas las preguntas de esta sección antes de avanzar al siguiente bloque.') }}</p>
                <button type="button" class="challenges-modal-btn" onclick="appChallenges.closeModal()">{{ __('Entendido') }}</button>
            </div>
        </div>
    </div>

    <x-footer/>

    <x-settings-menu />

    <!-- Lumen: compañero virtual con IA -->
    <x-lumen-chat />

    <script>
        window.initialQuizStep = {{ $progress->current_step ?? 1 }};
        window.isQuizCompleted = {{ ($progress->is_completed ?? false) ? 'true' : 'false' }};
        window.savedAnswers = @json($progress->answers ?? []);
        document.addEventListener("DOMContentLoaded", () => {
        window.simulationApp = new SimulationApp();
        window.simulationApp.start();
    });

        document.addEventListener("DOMContentLoaded", () => {
            window.mythApp = new MythChallengeApp();
            window.mythApp.start();
    });
    </script>

    <script src="{{ asset('js/information.js') }}"></script>

</body>
</html>