--TEST--
Translated seed (inferredbugs-java: acme4j/1)
--FILE--
<?php

class Account {
    public function __construct($keyPair) {}
}

class Registration {
    private $contacts = [];
    private $location;
    private $agreement;

    public function addContact($contact) {
        $this->contacts[] = $contact;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function setAgreement($agreement) {
        $this->agreement = $agreement;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getAgreement() {
        return $this->agreement;
    }
}

class Connection {
    public function sendSignedRequest($uri, $claims, $session, $account) {
        return 201;
    }

    public function getLocation() {
        return "https://example.com/acme/some-location";
    }

    public function getLink($relation) {
        return $relation === "terms-of-service" ? "http://example.com/agreement.pdf" : null;
    }
}

class TestableAbstractAcmeClient {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function newRegistration($account, $registration) {
        $uri = "https://example.com/acme/some-resource";
        $claims = json_encode(['contact' => $registration->contacts]);

        $responseCode = $this->connection->sendSignedRequest($uri, $claims, null, $account);
        if ($responseCode === 201) {
            $registration->setLocation($this->connection->getLocation());
            $registration->setAgreement($this->connection->getLink("terms-of-service"));
        }
    }
}

$account = new Account("dummy_key_pair");
$registration = new Registration();
$registration->addContact("mailto:foo@example.com");
$connection = new Connection();
$client = new TestableAbstractAcmeClient($connection);
$client->newRegistration($account, $registration);
--EXPECT--
