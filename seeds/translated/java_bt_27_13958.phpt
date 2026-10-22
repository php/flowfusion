--TEST--
Translated seed (inferredbugs-java: bt/27)
--FILE--
<?php

class MagnetContext {
    private $magnetUri;
    private $torrent;
    private $bitfieldConsumer;

    public function __construct($magnetUri) {
        $this->magnetUri = $magnetUri;
    }

    public function getMagnetUri() {
        return $this->magnetUri;
    }

    public function setTorrent($torrent) {
        $this->torrent = $torrent;
    }

    public function setBitfieldConsumer($bitfieldConsumer) {
        $this->bitfieldConsumer = $bitfieldConsumer;
    }
}

class Torrent {
    private $name;
    private $size;

    public function __construct($name, $size) {
        $this->name = $name;
        $this->size = $size;
    }

    public function getName() {
        return $this->name;
    }

    public function getSize() {
        return $this->size;
    }
}

class AnnounceKey {
    private $trackerTiers;

    public function __construct($trackerTiers) {
        $this->trackerTiers = $trackerTiers;
    }
}

function createAnnounceKey($trackerUrls) {
    if (empty($trackerUrls)) {
        return null;
    } else {
        return new AnnounceKey([$trackerUrls]);
    }
}

$magnetUri = (object) [
    'trackerUrls' => ['http://tracker.example.com'],
    'displayName' => 'Example Torrent'
];

$context = new MagnetContext($magnetUri);
$torrent = new Torrent(null, 1024);

$announceKey = createAnnounceKey($magnetUri->trackerUrls);
if ($announceKey) {
    $torrent = new Torrent($magnetUri->displayName, $torrent->getSize());
}

$context->setTorrent($torrent);
--EXPECT--
