<?php

function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception("El archivo .env no existe.");
    }

    // Leer línea por línea el archivo .env
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Ignorar comentarios
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Dividir las variables en clave y valor
        list($name, $value) = explode('=', $line, 2);

        // Quitar posibles espacios alrededor de la clave y valor
        $name = trim($name);
        $value = trim($value);

        // Definir la variable de entorno si aún no está definida
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Llamada a la función para cargar las variables de entorno
loadEnv(__DIR__ . '/.env');