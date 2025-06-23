<?php
function cleanString(string $value): string
{
    return trim(htmlspecialchars($value, ENT_QUOTES));
}

function translate($key, $lang = 'fr') {
    static $translations = [];

    if (!isset($translations[$lang])) {
        $file = __DIR__ . "/../lang/$lang.json";
        if (file_exists($file)) {
            $translations[$lang] = json_decode(file_get_contents($file), true);
        } else {
            $translations[$lang] = [];
        }
    }

    return $translations[$lang][$key] ?? $key;
}