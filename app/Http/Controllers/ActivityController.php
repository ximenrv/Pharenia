<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function showStage($stage)
    {
        // Diccionario con los datos de los juegos por etapa
        $stagesData = [
            'ninez' => [
                'title' => 'Mundo de la Niñez',
                'subtitle' => 'Nivel 1 — Explorando emociones y colores',
                'bg_color' => '#eef7f9', // Un tono pastel infantil
                'accent_color' => '#2c525a',
                'games' => [
                    ['title' => 'Rompecabezas de Emociones', 'desc' => 'Asocia las caras de Lumen con su respectiva emoción.', 'img' => 'game-ninez-1.png', 'url' => '/juegos/ninez/rompecabezas'],
                    ['title' => 'El Limpiador del Océano', 'desc' => 'Ayuda al pulpo a clasificar la basura del mar.', 'img' => 'game-ninez-2.png', 'url' => '/juegos/ninez/oceano'],
                    ['title' => 'Sonidos Sagrados', 'desc' => 'Descubre qué instrumento musical está sonando.', 'img' => 'game-ninez-3.png', 'url' => '/juegos/ninez/sonidos'],
                ]
            ],
            'juventud' => [
                'title' => 'Desafíos de la Juventud',
                'subtitle' => 'Nivel 2 — Habilidades sociales y rutina',
                'bg_color' => '#fbf7e3', // Tono cálido juvenil
                'accent_color' => '#bfa12b',
                'games' => [
                    ['title' => 'Planificador Diario', 'desc' => 'Organiza las tareas de la semana de forma eficiente.', 'img' => 'game-juve-1.png', 'url' => '/juegos/juventud/rutina'],
                    ['title' => 'Conversaciones en el Aula', 'desc' => 'Elige las mejores respuestas para interactuar con amigos.', 'img' => 'game-juve-2.png', 'url' => '/juegos/juventud/social'],
                    ['title' => 'Descifra el Mapa', 'desc' => 'Juego de lógica para orientarse en la ciudad.', 'img' => 'game-juve-3.png', 'url' => '/juegos/juventud/mapa'],
                ]
            ],
            'adultez' => [
                'title' => 'Estación de la Adultez',
                'subtitle' => 'Nivel 3 — Autonomía y vida independiente',
                'bg_color' => '#f5efff', // Tono más formal
                'accent_color' => '#7c4dff',
                'games' => [
                    ['title' => 'Simulador de Compras', 'desc' => 'Administra tu dinero comprando los víveres necesarios.', 'img' => 'game-adul-1.png', 'url' => '/juegos/adultez/compras'],
                    ['title' => 'Entrevista de Trabajo', 'desc' => 'Practica el lenguaje corporal y respuestas clave.', 'img' => 'game-adul-2.png', 'url' => '/juegos/adultez/entrevista'],
                    ['title' => 'Organiza tu Espacio', 'desc' => 'Mantén ordenado tu hogar en el menor tiempo posible.', 'img' => 'game-adul-3.png', 'url' => '/juegos/adultez/hogar'],
                ]
            ]
        ];

        // Si la etapa no existe en el diccionario, manda error 404
        if (!array_key_exists($stage, $stagesData)) {
            abort(404);
        }

        return view('activities.stage', ['data' => $stagesData[$stage]]);
    }
}