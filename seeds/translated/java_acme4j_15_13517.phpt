--TEST--
Translated seed (inferredbugs-java: acme4j/15)
--FILE--
<?php

$requestUrl = "http://example.com/acme/";
$accountUrl = "http://example.com/account/";
$mockUrlConnection = [];
$sessionNonce = null;

function getHeaderField($key) {
    global $mockUrlConnection;
    return $mockUrlConnection[$key] ?? null;
}

function setHeaderField($key, $value) {
    global $mockUrlConnection;
    $mockUrlConnection[$key] = $value;
}

function getNonce() {
    global $sessionNonce;
    return $sessionNonce;
}

function resetNonce() {
    global $sessionNonce;
    $nonce = getHeaderField("Replay-Nonce");
    if ($nonce === null) {
        throw new Exception("Invalid nonce");
    }
    $sessionNonce = $nonce;
}

function testNoNonceFromHeader() {
    setHeaderField("Replay-Nonce", null);
    if (getNonce() !== null) {
        throw new Exception("Expected null nonce");
    }
}

function testGetNonceFromHeader() {
    $dummyNonce = "dummy-nonce";
    setHeaderField("Replay-Nonce", $dummyNonce);
    resetNonce();
    if (getNonce() !== $dummyNonce) {
        throw new Exception("Expected nonce to match");
    }
}

function testInvalidNonceFromHeader() {
    $badNonce = "#$%&/*+*#'";
    setHeaderField("Replay-Nonce", $badNonce);
    try {
        resetNonce();
        throw new Exception("Expected to fail");
    } catch (Exception $ex) {
        if ($ex->getMessage() !== "Invalid nonce") {
            throw new Exception("Expected invalid nonce exception");
        }
    }
}

function main() {
    testNoNonceFromHeader();
    testGetNonceFromHeader();
    testInvalidNonceFromHeader();
}

main();
--EXPECT--
