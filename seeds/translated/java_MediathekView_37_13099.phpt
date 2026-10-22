--TEST--
Translated seed (inferredbugs-java: MediathekView/37)
--FILE--
<?php

class Start {
    const STATUS_INIT = 0;
    const STATUS_RUN = 1;
    const STATUS_FERTIG = 2;
    const STATUS_ERR = 3;
    public $status;
    public $restSekunden;
    public $datenDownload;

    public function __construct($status, $restSekunden) {
        $this->status = $status;
        $this->restSekunden = $restSekunden;
        $this->datenDownload = ['progress' => -1];
    }
}

class DDaten {
    public function getStart($url) {
        return new Start(rand(0, 3), rand(0, 120)); // Simulate different states.
    }
}

function getDownloadProgress($s) {
    $i = $s->datenDownload['progress'];
    if ($i == -1) {
        return "";
    } elseif ($i == 0) {
        return "warten";
    } elseif ($i == 1) {
        return "gestartet";
    } elseif ($i > 1 && $i < 100) {
        return (string)($i / 10) . "%";
    } else {
        return $s->status == Start::STATUS_ERR ? "fehlerhaft" : "fertig";
    }
}

function main() {
    $ddaten = new DDaten();
    $url = "http://example.com"; // Dummy URL
    $s = $ddaten->getStart($url);
    
    // Simulate setting progress and outputting the result
    for ($i = 0; $i <= 100; $i += 10) {
        $s->datenDownload['progress'] = $i;
        echo getDownloadProgress($s) . PHP_EOL;
    }
}

main();
--EXPECT--
