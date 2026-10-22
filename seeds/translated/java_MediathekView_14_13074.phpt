--TEST--
Translated seed (inferredbugs-java: MediathekView/14)
--FILE--
<?php

class MediathekReader {
    private $name;
    private $startPrio;

    public function __construct($name, $startPrio) {
        $this->name = $name;
        $this->startPrio = $startPrio;
    }

    public function getStartPrio() {
        return $this->startPrio;
    }

    public function checkNameSenderFilmliste($name) {
        return $this->name === $name;
    }

    public function getNameSenderFilmliste() {
        return [$this->name];
    }

    public function getNameSenderMreader() {
        return $this->name;
    }

    public function run() {
        sleep(rand(1, 3)); // Simulate the loading process
    }
}

class FilmeSuchenSender {
    private $mediathekListe = [];
    private $allesLaden = true;
    
    public function __construct() {
        $this->mediathekListe[] = new MediathekReader("ARD", 0);
        $this->mediathekListe[] = new MediathekReader("ZDF", 0);
        $this->mediathekListe[] = new MediathekReader("3SAT", 1);
        $this->mediathekListe[] = new MediathekReader("WDR", 1);
    }

    public function filmeBeimSenderLaden() {
        $this->mrStarten(0);
        $this->mrStarten(1);
    }

    private function mrStarten($prio) {
        foreach ($this->mediathekListe as $mr) {
            if ($mr->getStartPrio() === $prio) {
                (new Thread($mr))->start();
            }
        }
        usleep(120000000); // Wait for 2 minutes (simulated)
    }

    public function updateSender($nameSender) {
        $this->allesLaden = false;
        foreach ($this->mediathekListe as $mr) {
            if ($mr->checkNameSenderFilmliste($nameSender)) {
                (new Thread($mr))->start();
            }
        }
    }
}

class Thread {
    private $reader;

    public function __construct($reader) {
        $this->reader = $reader;
    }

    public function start() {
        $this->reader->run();
    }
}

$suchen = new FilmeSuchenSender();
$suchen->filmeBeimSenderLaden();
$suchen->updateSender("ZDF");
--EXPECT--
