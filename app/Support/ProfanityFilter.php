<?php

namespace App\Support;

/**
 * Filtro de censura suave para Lumen (Nivel 2 del sistema de contención).
 *
 * No castiga ni bloquea: simplemente censura groserías e insultos comunes
 * en español reemplazándolos por asteriscos, para que Lumen pueda responder
 * con empatía sin amplificar el lenguaje ofensivo.
 */
class ProfanityFilter
{
    /**
     * Palabras altisonantes e insultos comunes en español.
     * En minúsculas y sin acentos; la normalización se hace al comparar.
     * Se evitan términos ambiguos de uso cotidiano (p. ej. "odio", "gordo")
     * para no censurar expresiones legítimas.
     *
     * @var array<int, string>
     */
    protected static array $words = [
        'puta', 'puto', 'putita', 'puton',
        'mierda', 'mierdoso',
        'carajo', 'verga',
        'chingar', 'chingada', 'chingado',
        'pendejo', 'pendeja', 'pendejada',
        'idiota', 'imbecil', 'estupido', 'estupida',
        'tarado', 'tarada', 'retrasado', 'retrasada', 'subnormal',
        'maricon', 'marica', 'joto',
        'cabron', 'cabrona',
        'culero', 'culera',
        'coño', 'concha',
        'gilipollas', 'capullo', 'capulla',
        'hijueputa', 'hijoputa',
        'zorra', 'perra',
        'mamahuevo', 'mamaguevo', 'mamon', 'mamona',
        'culiao', 'culiada', 'weon', 'weona',
        'boludo', 'boluda', 'pelotudo', 'pelotuda',
        'gonorrea', 'malparido', 'malparida',
        'cerote', 'cerota', 'pajero', 'pajera',
        'menso', 'mensa', 'baboso', 'babosa',
        'muerete',
        'fuck', 'shit', 'bitch', 'asshole',
    ];

    /**
     * Normaliza el texto: minúsculas, sin acentos, leetspeak básico a letras.
     */
    protected static function normalize(string $text): string
    {
        $text = mb_strtolower($text);

        return strtr($text, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ü' => 'u', 'ñ' => 'n',
            '0' => 'o', '1' => 'i', '3' => 'e', '4' => 'a', '5' => 's',
            '7' => 't', '@' => 'a', '$' => 's', '!' => 'i',
        ]);
    }

    /**
     * ¿El token (palabra) normalizado coincide con una palabra de la lista?
     * Coincidencia exacta o por prefijo (para capturar plurales/derivados
     * como "putas" o "pendejos") sin falsos positivos tipo "computadora".
     */
    protected static function tokenMatches(string $normalizedToken): bool
    {
        foreach (self::$words as $word) {
            $needle = self::normalize($word);

            if ($normalizedToken === $needle) {
                return true;
            }

            if (mb_strlen($needle) >= 4 && str_starts_with($normalizedToken, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * ¿El texto contiene al menos una palabra de la lista?
     */
    public static function containsProfanity(string $text): bool
    {
        preg_match_all('/[\p{L}0-9@$!]+/u', $text, $matches);

        foreach ($matches[0] as $token) {
            if (self::tokenMatches(self::normalize($token))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Reemplaza las palabras de la lista por asteriscos, preservando el resto
     * del texto original (mayúsculas, puntuación, emojis).
     */
    public static function censor(string $text): string
    {
        return preg_replace_callback(
            '/[\p{L}0-9@$!]+/u',
            function (array $match) {
                $token = $match[0];

                if (! self::tokenMatches(self::normalize($token))) {
                    return $token;
                }

                // Censura suave: conserva la primera letra legible.
                $first = mb_substr($token, 0, 1);

                return $first . str_repeat('*', max(3, mb_strlen($token) - 1));
            },
            $text
        );
    }
}
