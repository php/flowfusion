--TEST--
Translated seed (inferredbugs-java: OSM2World/10)
--FILE--
<?php

$filename = 'shader.glsl'; // Assume the shader file is named shader.glsl
$vertShader = glCreateShader(GL_VERTEX_SHADER);
if ($vertShader == 0) exit("Unable to create vertex shader.");

$vertCode = "";
$stream = loadShaderFile($filename);
if ($stream === null) exit("Vertex shader not found: \"$filename\"");

$line;
while (($line = fgets($stream)) !== false) {
    $vertCode .= $line;
}

glShaderSource($vertShader, 1, [$vertCode], null);
glCompileShader($vertShader);

$shaderStatus = [];
glGetShaderiv($vertShader, GL_COMPILE_STATUS, $shaderStatus);
if ($shaderStatus[0] == GL_FALSE) {
    printShaderInfoLog($vertShader);
    exit("Compilation error for shader [$filename].");
}
printShaderInfoLog($vertShader);

function loadShaderFile($filename) {
    $stream = fopen($filename, 'r');
    if ($stream === false) {
        return null;
    }
    return $stream;
}

function printShaderInfoLog($shader) {
    $infoLength = [];
    glGetShaderiv($shader, GL_INFO_LOG_LENGTH, $infoLength);
    if ($infoLength[0] > 1) {
        $log = '';
        glGetShaderInfoLog($shader, $infoLength[0], null, $log);
        echo "Info log: " . $log;
    }
}

?>
--EXPECT--
