--TEST--
Translated seed (inferredbugs-java: commons-codec/2)
--FILE--
<?php

function base64_encode_custom($data) {
    return base64_encode($data);
}

function base64_decode_custom($data) {
    return base64_decode($data);
}

function testEncodeDecode() {
    $original = "Hello World!";
    $encoded = base64_encode_custom($original);
    $decoded = base64_decode_custom($encoded);
    echo $encoded . "\n";
    echo $decoded . "\n";
}

function testPadding() {
    $inputs = ["A", "AA", "AAA"];
    foreach ($inputs as $input) {
        $encoded = base64_encode_custom($input);
        echo $encoded . "\n";
    }
}

function testEmpty() {
    $encoded = base64_encode_custom("");
    echo "Empty encode: " . (empty($encoded) ? "true" : "false") . "\n";
}

function testInvalid() {
    $invalid = "%%%";
    $decoded = base64_decode_custom($invalid);
    echo "Invalid decode: " . (empty($decoded) ? "true" : "false") . "\n";
}

function runTests() {
    testEncodeDecode();
    testPadding();
    testEmpty();
    testInvalid();
}

runTests();
--EXPECT--
