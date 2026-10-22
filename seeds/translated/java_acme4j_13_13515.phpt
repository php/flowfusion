--TEST--
Translated seed (inferredbugs-java: acme4j/13)
--FILE--
<?php

class Challenge {}
class Http01Challenge extends Challenge {}
class Dns01Challenge extends Challenge {}
class AcmeProtocolException extends Exception {}
class Authorization {
    private $challenges = [];
    private $domain;
    private $status;
    
    public function __construct() {
        $this->challenges['http-01'] = new Http01Challenge();
        $this->challenges['dns-01'] = new Dns01Challenge();
    }

    public function findChallenge($type) {
        if ($type === 'duplicate-01') {
            throw new AcmeProtocolException();
        }
        return $this->challenges[$type] ?? null;
    }

    public function update() {
        $this->domain = 'example.org';
        $this->status = 'valid';
    }
    
    public function getDomain() {
        return $this->domain;
    }

    public function getStatus() {
        return $this->status;
    }
}

$auth = new Authorization();

// Test finding challenges
$c1 = $auth->findChallenge('snail-01'); // should be null
$c2 = $auth->findChallenge('http-01'); // should be an instance of Http01Challenge
$c3 = $auth->findChallenge('dns-01'); // should be an instance of Dns01Challenge

// Test failing on duplicate challenges
try {
    $auth->findChallenge('duplicate-01');
} catch (AcmeProtocolException $e) {}

// Test update and retrieval
$auth->update();
$domain = $auth->getDomain(); // should be 'example.org'
$status = $auth->getStatus(); // should be 'valid'
--EXPECT--
