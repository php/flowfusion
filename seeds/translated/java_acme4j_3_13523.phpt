--TEST--
Translated seed (inferredbugs-java: acme4j/3)
--FILE--
<?php

class DummyConnection {
    public function sendSignedRequest($uri, $claims, $session, $registration) {
        if ($uri !== "https://example.com/acme/some-resource") {
            throw new Exception("Unexpected URI");
        }
        if ($claims !== json_encode(["resource" => "reg"])) {
            throw new Exception("Unexpected claims");
        }
        return 201;
    }

    public function getLocation() {
        return "https://example.com/acme/some-location";
    }

    public function getLink($relation) {
        return $relation === "terms-of-service" ? "http://example.com/agreement.pdf" : null;
    }
}

class Registration {
    private $location;
    private $agreement;
    private $keyPair;

    public function __construct($keyPair) {
        $this->keyPair = $keyPair;
    }

    public function addContact($contact) {
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setAgreement($agreement) {
        $this->agreement = $agreement;
    }

    public function getKeyPair() {
        return $this->keyPair;
    }
}

class TestableAbstractAcmeClient {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function newRegistration($registration) {
        $this->connection->sendSignedRequest("https://example.com/acme/some-resource", json_encode(["resource" => "reg"]), null, $registration);
        $registration->setLocation($this->connection->getLocation());
        $registration->setAgreement("http://example.com/agreement.pdf");
    }
}

$keyPair = "keyPair"; // Simulating keyPair
$registration = new Registration($keyPair);
$connection = new DummyConnection();
$client = new TestableAbstractAcmeClient($connection);
$client->newRegistration($registration);
--EXPECT--
