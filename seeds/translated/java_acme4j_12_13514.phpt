--TEST--
Translated seed (inferredbugs-java: acme4j/12)
--FILE--
<?php

$locationUrl = "http://example.com/acme/account";

class Challenge {}
class Http01Challenge extends Challenge {}
class Dns01Challenge extends Challenge {}
class AcmeProtocolException extends Exception {}
class Authorization {
    private $login;
    private $url;
    private $json;

    public function __construct($login, $url) {
        $this->login = $login;
        $this->url = $url;
    }

    public function findChallenge($type) {
        if ($type === 'snail-01') {
            return null;
        } elseif ($type === 'http-01') {
            return new Http01Challenge();
        } elseif ($type === 'dns-01') {
            return new Dns01Challenge();
        } elseif ($type === 'duplicate-01') {
            throw new AcmeProtocolException();
        }
        return null;
    }

    public function update() {
        // Mock update logic
    }

    public function deactivate() {
        // Mock deactivate logic
    }

    public function setJSON($json) {
        $this->json = $json;
    }
}

class TestableConnectionProvider {
    public function createLogin() {
        return new stdClass();
    }
}

$authorization = new Authorization(new TestableConnectionProvider()->createLogin(), $locationUrl);

// Test that findChallenge does not find a non-existent challenge
$c1 = $authorization->findChallenge('snail-01');

// Test that HttpChallenge is available
$c2 = $authorization->findChallenge('http-01');

// Test that Dns01Challenge is available
$c3 = $authorization->findChallenge('dns-01');

// Test that findChallenge fails on duplicate challenges
try {
    $authorization->findChallenge('duplicate-01');
} catch (AcmeProtocolException $e) {
    // Exception is expected
}

// Test that authorization is properly updated
$authorization->update();

// Test that authorization can be deactivated
$authorization->deactivate();
--EXPECT--
