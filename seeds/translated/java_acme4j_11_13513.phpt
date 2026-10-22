--TEST--
Translated seed (inferredbugs-java: acme4j/11)
--FILE--
<?php

$SNAILMAIL_TYPE = "snail-01";
$DUPLICATE_TYPE = "duplicate-01";

$locationUrl = "http://example.com/acme/account";

class Challenge {}
class Http01Challenge extends Challenge {}
class Dns01Challenge extends Challenge {}
class TlsSni02Challenge extends Challenge {}
class AcmeProtocolException extends Exception {}
class Authorization {
    private $challenges = [];
    private $domain = "example.org";
    private $status = "valid";
    
    public function __construct() {
        $this->challenges[Http01Challenge::class] = new Http01Challenge();
        $this->challenges[Dns01Challenge::class] = new Dns01Challenge();
        $this->challenges[TlsSni02Challenge::class] = new TlsSni02Challenge();
        $this->challenges["duplicate"] = new Challenge();
    }

    public function findChallenge($type) {
        return $this->challenges[$type] ?? null;
    }

    public function update() {
        if (isset($this->challenges["duplicate"])) {
            throw new AcmeProtocolException();
        }
    }

    public function getDomain() {
        return $this->domain;
    }

    public function getStatus() {
        return $this->status;
    }
}

$authorization = new Authorization();

// Test non-existent challenge
$c1 = $authorization->findChallenge($SNAILMAIL_TYPE);
$c2 = $authorization->findChallenge(Http01Challenge::class);
$c3 = $authorization->findChallenge(Dns01Challenge::class);
$c4 = $authorization->findChallenge(TlsSni02Challenge::class);

// Test finding duplicate challenge
try {
    $authorization->update();
} catch (AcmeProtocolException $e) {
}

// Verify values
$domain = $authorization->getDomain();
$status = $authorization->getStatus();

echo "Domain: $domain, Status: $status\n";
--EXPECT--
