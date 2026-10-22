--TEST--
Translated seed (inferredbugs-java: commons-codec/3)
--FILE--
<?php

function base64_encode_chunked($data) {
    return chunk_split(base64_encode($data), 76, "\r\n");
}

function generate_random_bytes($length) {
    return random_bytes($length);
}

function testBase64() {
    $content = "Hello World";
    $encodedContent = base64_encode($content);
    if ($encodedContent !== "SGVsbG8gV29ybGQ=") {
        throw new Exception("Encoding failed");
    }
}

function testEmptyBase64() {
    $empty = "";
    $result = base64_encode($empty);
    if (strlen($result) !== 0) {
        throw new Exception("Empty base64 encode failed");
    }
}

function testEncodeDecodeRandom() {
    for ($i = 1; $i <= 5; $i++) {
        $data = generate_random_bytes(rand(1, 10000));
        $enc = base64_encode($data);
        $dec = base64_decode($enc);
        if ($data !== $dec) {
            throw new Exception("Random encode/decode failed");
        }
    }
}

function testEncodeDecodeSmall() {
    for ($i = 0; $i < 12; $i++) {
        $data = generate_random_bytes($i);
        $enc = base64_encode($data);
        $dec = base64_decode($enc);
        if ($data !== $dec) {
            throw new Exception("Small encode/decode failed");
        }
    }
}

function testDecodeWithWhitespace() {
    $orig = "I am a late night coder.";
    $encodedArray = base64_encode($orig);
    $intermediate = $encodedArray;
    $intermediate = substr_replace($intermediate, ' ', 2, 0);
    $intermediate = substr_replace($intermediate, "\t", 5, 0);
    $intermediate = substr_replace($intermediate, "\r", 10, 0);
    $intermediate = substr_replace($intermediate, "\n", 15, 0);
    $decodedWithWS = base64_decode($intermediate);
    if ($decodedWithWS !== $orig) {
        throw new Exception("Decode with whitespace failed");
    }
}

function main() {
    testBase64();
    testEmptyBase64();
    testEncodeDecodeRandom();
    testEncodeDecodeSmall();
    testDecodeWithWhitespace();
}

main();
--EXPECT--
