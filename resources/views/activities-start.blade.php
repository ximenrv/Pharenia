<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Línea de Vida - Pharenia</title>
    
    @vite(['resources/css/activities-start.css', 'resources/css/navbar.css', 'resources/css/footer.css'])
</head>
<body>

    @include('loader')

    <x-navbar />

    <main class="main-content">
        <div class="timeline-wrapper">
            
            <div class="timeline-container">
                <svg class="timeline-svg" viewBox="0 0 800 150" preserveAspectRatio="none">
                    <path d="M 40,100 Q 220,-10 400,75 T 760,100" fill="none" stroke="#dfb13b" stroke-width="4" stroke-linecap="round"/>
                </svg>

                <div class="timeline-node node-child" data-stage="child">
                    <a href="{{ route('activities.stage', ['stage' => 'ninez']) }}" class="node-point-link">
                        <div class="node-point"></div>
                    </a>
                    <span class="node-label">Niñez</span>
                </div>

                <div class="timeline-node node-youth" data-stage="youth">
                    <a href="{{ route('activities.stage', ['stage' => 'juventud']) }}" class="node-point-link">
                        <div class="node-point"></div>
                    </a>
                    <span class="node-label">Juventud</span>
                </div>

                <div class="timeline-node node-adult" data-stage="adult">
                    <a href="{{ route('activities.stage', ['stage' => 'adultez']) }}" class="node-point-link">
                        <div class="node-point"></div>
                    </a>
                    <span class="node-label">Adultez</span>
                </div>
            </div>

            <div class="info-panel-container">
                
                <div class="info-card card-default">
                    <h3>Explora las Etapas</h3>
                    <p>Pasa el cursor sobre cualquiera de los nodos de la línea de tiempo para descubrir los detalles del módulo, objetivos clave y las habilidades que desarrollarás.</p>
                </div>

                <div class="info-card card-child">
                    <h3>Módulo de Niñez</h3>
                    <p class="stage-desc">Explora las bases fundamentales del desarrollo temprano, el impacto de los entornos iniciales y los primeros esquemas de aprendizaje cognitivo.</p>
                    <div class="skills-section">
                        <h4>Habilidades a Desarrollar:</h4>
                        <div class="skills-tags">
                            <span>Psicología Evolutiva</span>
                            <span>Análisis Cognitivo</span>
                            <span>Fundamentos de Aprendizaje</span>
                        </div>
                    </div>
                </div>

                <div class="info-card card-youth">
                    <h3>Módulo de Juventud</h3>
                    <p class="stage-desc">Comprende las transiciones complejas de la identidad, la gestión socioemocional y las dinámicas de pensamiento crítico bajo entornos cambiantes.</p>
                    <div class="skills-section">
                        <h4>Habilidades a Desarrollar:</h4>
                        <div class="skills-tags">
                            <span>Inteligencia Emocional</span>
                            <span>Resolución de Conflictos</span>
                            <span>Construcción de Identidad</span>
                        </div>
                    </div>
                </div>

                <div class="info-card card-adult-info">
                    <h3>Módulo de Adultez</h3>
                    <p class="stage-desc">Domina las estructuras de toma de decisiones de alta fidelidad, liderazgo estratégico y la resiliencia en entornos de responsabilidad avanzada.</p>
                    <div class="skills-section">
                        <h4>Habilidades a Desarrollar:</h4>
                        <div class="skills-tags">
                            <span>Liderazgo Estratégico</span>
                            <span>Toma de Decisiones</span>
                            <span>Pensamiento Sistémico</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <x-footer/>
</body>
</html>