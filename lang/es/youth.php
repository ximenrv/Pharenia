<?php

return [
    'centinela' => [
        'meta' => [
            'title' => 'Centinela · Aprende a distinguir',
            'description' => 'Centinela: una patrulla serena para aprender a distinguir. No todo lo que parece diferente es una amenaza.',
        ],

        'header' => [
            'brand' => 'Centinela',
            'slogan' => 'Aprende a distinguir antes de actuar',
            'back' => 'Volver a actividades',
        ],

        'home' => [
            'title' => 'La patrulla de esta noche te espera',
            'intro_line1' => 'Tu nave dispara sola. Tú solo eliges dónde estar: protege el contorno <span class="font-semibold text-leaf-300">verde</span> y neutraliza el <span class="font-semibold text-ember-300">rojo</span>.',
            'intro_line2' => '«No todo lo que parece diferente es una amenaza.»',
            'choose_patrol' => 'Elige tu patrulla',
            'howto_button' => '¿Cómo funciona?',
        ],

        'howto' => [
            'title' => '¿Cómo funciona?',
            'steps' => [
                [
                    'title' => 'Observa el contorno',
                    'text' => '<span class="font-semibold text-leaf-300">Verde</span> = proteger. <span class="font-semibold text-ember-300">Rojo</span> = amenaza. El color marca la acción, no la naturaleza: una serpiente también puede llevar contorno verde.',
                ],
                [
                    'title' => 'La nave dispara sola',
                    'text' => 'Tú solo mueves: usa las flechas ← → (o A y D), o arrastra el dedo o el ratón por el cielo.',
                ],
                [
                    'title' => 'Elige tu línea de fuego',
                    'text' => 'Colócate bajo las figuras rojas y apártate cuando pase una verde. Destruir una roja suma 1 punto; alcanzar una verde resta 1 de integridad.',
                ],
                [
                    'title' => 'Sin castigos ocultos',
                    'text' => 'Dejar pasar una roja no resta nada: es solo una oportunidad que se va. Si tu integridad llega a 0, la patrulla termina.',
                ],
                [
                    'title' => 'Atrapa las tarjetas doradas',
                    'text' => 'De vez en cuando cae una tarjeta dorada: pasa por ella con la nave para recogerla. El rayo te da <strong>disparo veloz</strong> y las flechas dobles, <strong>doble disparo</strong>, durante unos segundos. No hace falta dispararles: solo hay que atraparlas.',
                ],
            ],
            'legend_protect' => 'Proteger · contorno verde',
            'legend_threat' => 'Amenazas · contorno rojo',
            'legend_help' => 'Ayudas · tarjetas doradas (recoger con la nave)',
            'back_button' => 'Volver al inicio',
        ],

        'game' => [
            'heading' => 'Patrulla en curso',
            'exit' => 'Salir',
            'sound_on' => 'Sonido: sí',
            'sound_off' => 'Sonido: no',
            'pause' => 'Pausa',
            'integrity' => 'Integridad',
            'score' => 'Puntuación',
            'progress' => 'Progreso de la patrulla',
            'rapid_label' => 'Disparo veloz',
            'double_label' => 'Doble disparo',
            'pause_title' => 'Pausa',
            'pause_text' => 'La patrulla espera. Tú marcas el ritmo.',
            'resume' => 'Reanudar',
            'quit' => 'Salir al menú',
            'controls' => 'Muévete con ← → o arrastra el puntero · la nave dispara sola',
            'arena_label' => 'Cielo de la patrulla: zona de juego',
        ],

        'results' => [
            'title' => 'Fin de la partida',
            'subtitle' => 'La patrulla ha terminado',
            'subtitle_early' => 'La integridad llegó a 0: la patrulla se detuvo antes de tiempo',
            'score' => 'Puntuación',
            'precision' => 'Precisión',
            'protected' => 'Elementos protegidos',
            'threats' => 'Amenazas eliminadas',
            'quote' => '«Sigue adelante.»',
            'replay' => 'Jugar de nuevo',
            'home' => 'Volver al menú',
        ],
    ],
];
