--TEST--
Translated seed (inferredbugs-java: asterisk-java/7)
--FILE--
<?php
class AsteriskServer {
    private $version;
    private $versions = [];

    public function getVersion($file) {
        if (empty($this->versions)) {
            $this->retrieveVersions();
        }
        return $this->versions[$file] ?? null;
    }

    private function retrieveVersions() {
        $response = $this->sendAction('show version files');
        foreach ($response as $line) {
            if (preg_match("/^([\\S]+)\\s+Revision: ([0-9\\.]+)/", $line, $matches)) {
                $this->versions[$matches[1]] = $matches[2];
            }
        }
    }

    private function sendAction($action) {
        // Simulate sending an action to the Asterisk server and returning response
        return [
            "Asterisk CLI version 16.1.0",
            "Some other version info",
            "custom_channel_name Revision: 2.3.4",
            "another_channel_name Revision: 1.2.3"
        ];
    }
    
    public function run() {
        $version = $this->getVersion('custom_channel_name');
        echo $version . "\n"; // Outputs 2.3.4
        $anotherVersion = $this->getVersion('another_channel_name');
        echo $anotherVersion . "\n"; // Outputs 1.2.3
        $unknownVersion = $this->getVersion('unknown_name');
        echo $unknownVersion . "\n"; // Outputs nothing
    }
}

$server = new AsteriskServer();
$server->run();
--EXPECT--
