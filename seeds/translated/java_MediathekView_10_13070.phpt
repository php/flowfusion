--TEST--
Translated seed (inferredbugs-java: MediathekView/10)
--FILE--
<?php

class FilmeSuchen {
    public $mediathekListe = [];
    public $allesLaden = true;
    public $listeFilmeNeu;
    private $listeFilmeAlt = null;
    private $fertigMeldung = [];

    public function __construct() {
        $this->initMediathekReader();
    }

    public function filmeBeimSenderLaden($aallesLaden, $alteListe) {
        $this->allesLaden = $aallesLaden;
        $this->initStart($alteListe);
        foreach ($this->mediathekListe as $mr) {
            $this->startThread($mr);
        }
    }

    private function initStart($alteListe) {
        $this->fertigMeldung = [];
        $this->listeFilmeAlt = $alteListe;
        $this->listeFilmeNeu = new ListeFilme();
    }

    private function startThread($reader) {
        // Simulate thread start
        $reader->run();
    }

    private function initMediathekReader() {
        $this->mediathekListe[] = new MediathekArd($this);
        $this->mediathekListe[] = new MediathekZdf($this);
    }

    public function melden($sender, $max, $progress, $text) {
        // Dummy implementation
    }

    public function meldenFertig($sender) {
        $this->fertigMeldung[] = "Fertig $sender";
        if ($this->allesLaden) {
            $this->listeFilmeNeu->updateListe($this->listeFilmeAlt);
        }
    }

    private function progressBar() {
        // Simulate progress bar
    }
}

class ListeFilme {
    public function updateListe($alteListe) {
        // Dummy implementation
    }
}

class MediathekArd {
    private $controller;

    public function __construct($controller) {
        $this->controller = $controller;
    }

    public function run() {
        // Simulate processing
    }
}

class MediathekZdf {
    private $controller;

    public function __construct($controller) {
        $this->controller = $controller;
    }

    public function run() {
        // Simulate processing
    }
}

$filmeSuchen = new FilmeSuchen();
$filmeSuchen->filmeBeimSenderLaden(true, null);
$filmeSuchen->meldenFertig("Sender1");
--EXPECT--
