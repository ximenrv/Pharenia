<?php

return [
    'centinela' => [
        'meta' => [
            'title' => 'Centinela · Learn to tell them apart',
            'description' => 'Centinela: a calm patrol to learn how to tell things apart. Not everything that looks different is a threat.',
        ],

        'header' => [
            'brand' => 'Centinela',
            'slogan' => 'Learn to tell them apart before you act',
            'back' => 'Back to activities',
        ],

        'home' => [
            'title' => 'Tonight\'s patrol is waiting for you',
            'intro_line1' => 'Your ship fires on its own. You only choose where to stand: protect the <span class="font-semibold text-leaf-300">green</span> outline and neutralize the <span class="font-semibold text-ember-300">red</span>.',
            'intro_line2' => '«Not everything that looks different is a threat.»',
            'choose_patrol' => 'Choose your patrol',
            'howto_button' => 'How does it work?',
        ],

        'howto' => [
            'title' => 'How does it work?',
            'steps' => [
                [
                    'title' => 'Watch the outline',
                    'text' => '<span class="font-semibold text-leaf-300">Green</span> = protect. <span class="font-semibold text-ember-300">Red</span> = threat. The color marks the action, not the nature: even a snake can have a green outline.',
                ],
                [
                    'title' => 'The ship fires on its own',
                    'text' => 'You only move: use the ← → arrows (or A and D), or drag your finger or mouse across the sky.',
                ],
                [
                    'title' => 'Choose your line of fire',
                    'text' => 'Stand under the red figures and move away when a green one passes. Destroying a red one adds 1 point; hitting a green one removes 1 integrity.',
                ],
                [
                    'title' => 'No hidden penalties',
                    'text' => 'Letting a red one pass costs nothing: it is just a missed opportunity. If your integrity reaches 0, the patrol ends.',
                ],
                [
                    'title' => 'Catch the golden cards',
                    'text' => 'Every now and then a golden card falls: pass over it with the ship to collect it. The bolt gives you <strong>rapid fire</strong> and the double arrows give you <strong>double shot</strong> for a few seconds. You do not need to shoot them: just catch them.',
                ],
            ],
            'legend_protect' => 'Protect · green outline',
            'legend_threat' => 'Threats · red outline',
            'legend_help' => 'Help · golden cards (collect with the ship)',
            'back_button' => 'Back to start',
        ],

        'game' => [
            'heading' => 'Patrol in progress',
            'exit' => 'Exit',
            'sound_on' => 'Sound: on',
            'sound_off' => 'Sound: off',
            'pause' => 'Pause',
            'integrity' => 'Integrity',
            'score' => 'Score',
            'progress' => 'Patrol progress',
            'rapid_label' => 'Rapid fire',
            'double_label' => 'Double shot',
            'pause_title' => 'Pause',
            'pause_text' => 'The patrol waits. You set the pace.',
            'resume' => 'Resume',
            'quit' => 'Exit to menu',
            'controls' => 'Move with ← → or drag the pointer · the ship fires on its own',
            'arena_label' => 'Patrol sky: play area',
        ],

        'results' => [
            'title' => 'End of game',
            'subtitle' => 'The patrol is over',
            'subtitle_early' => 'Integrity reached 0: the patrol stopped early',
            'score' => 'Score',
            'precision' => 'Accuracy',
            'protected' => 'Protected elements',
            'threats' => 'Threats eliminated',
            'quote' => '«Keep going.»',
            'replay' => 'Play again',
            'home' => 'Back to menu',
        ],
    ],
];
