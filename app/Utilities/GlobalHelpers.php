<?php

function config(string $key, $default = null)
{
    static $config = [];
    $keys = explode('.', $key);
    $file = $keys[0];
    $subKey = isset($keys[1]) ? $keys[1] : null;

    if (!isset($config[$file])) {
        $filePath = dirname(__DIR__) . "/../config/{$file}.php";
        if (file_exists($filePath)) {
            $config[$file] = require $filePath;
        } else {
            return $default;
        }
    }

    $value = $config[$file];
    if ($subKey) {
        return $value[$subKey] ?? $default;
    }
    return $value ?? $default;
}

function view($view, $data = [])
{
    extract($data);
    include __DIR__ . "/../../resources/views/{$view}.php";
}
