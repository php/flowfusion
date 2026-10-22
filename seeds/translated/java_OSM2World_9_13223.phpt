--TEST--
Translated seed (inferredbugs-java: OSM2World/9)
--FILE--
<?php

function createVertexShader($filename) {
    $vertShader = 1; // Simulate shader ID allocation
    if ($vertShader == 0) throw new RuntimeException("Unable to create vertex shader.");

    $vertCode = "";
    $stream = loadShaderFile($filename);
    if ($stream === null) throw new RuntimeException("Vertex shader not found: \"$filename\"");

    $line = '';
    while (($line = fgets($stream)) !== false) {
        $vertCode .= $line;
    }

    $compileStatus = compileShader($vertShader, $vertCode);
    if ($compileStatus === false) throw new RuntimeException("Compilation error for shader [$filename].");

    return $vertShader;
}

function createFragmentShader($filename) {
    $fragShader = 1; // Simulate shader ID allocation
    if ($fragShader == 0) return 0;

    $fragCode = "";
    $stream = loadShaderFile($filename);
    if ($stream === null) throw new RuntimeException("Fragment shader not found: \"$filename\"");

    $line = '';
    while (($line = fgets($stream)) !== false) {
        $fragCode .= $line;
    }

    $compileStatus = compileShader($fragShader, $fragCode);
    if ($compileStatus === false) throw new RuntimeException("Compilation error for shader [$filename].");

    return $fragShader;
}

function compileShader($shader, $code) {
    // Simulated compilation, return false to simulate failure in this pattern
    return false; 
}

function loadShaderFile($filename) {
    $filePath = "resources/" . ltrim($filename, '/');
    return file_exists($filePath) ? fopen($filePath, 'r') : null;
}

createVertexShader('vertex_shader.glsl');
createFragmentShader('fragment_shader.glsl');
--EXPECT--
