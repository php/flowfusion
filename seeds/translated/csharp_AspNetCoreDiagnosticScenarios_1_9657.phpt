--TEST--
Translated seed (inferredbugs-csharp: AspNetCoreDiagnosticScenarios/1)
--FILE--
<?php

class PokemonData {
    public $name;
    public $type;
}

function bigJsonSynchronousInput($json) {
    $rootobject = json_decode($json);
}

function bigJsonInput($json) {
    $rootobject = json_decode($json);
}

function bigContentBad($json) {
    $rootobject = json_decode($json);
}

function bigContentManualGood($json) {
    $stream = fopen('php://temp', 'r+');
    fwrite($stream, $json);
    rewind($stream);

    $data = stream_get_contents($stream);
    $rootobject = json_decode($data);

    fclose($stream);
}

$jsonInput = '{"name": "Pikachu", "type": "Electric"}'; // Simulating incoming JSON input

bigJsonSynchronousInput($jsonInput);
bigJsonInput($jsonInput);
bigContentBad($jsonInput);
bigContentManualGood($jsonInput);
--EXPECT--
