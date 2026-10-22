--TEST--
Translated seed (inferredbugs-java: bookkeeper/14)
--FILE--
<?php

class BookieWatcher {
    private $knownBookies = [];
    private $bookieRegistrationPath;
    private $ZK_CONNECT_BACKOFF_SEC = 1;

    public function __construct($bookieRegistrationPath) {
        $this->bookieRegistrationPath = $bookieRegistrationPath;
    }

    public function readBookies() {
        $this->processResult(rand(0, 1), $this->bookieRegistrationPath, [], ['bookie1', 'bookie2', 'bookie3']);
    }

    public function processResult($rc, $path, $ctx, $children) {
        if ($rc !== 0) {
            sleep($this->ZK_CONNECT_BACKOFF_SEC);
            $this->readBookies();
            return;
        }

        $newBookieAddrs = [];
        foreach ($children as $bookieAddrString) {
            $newBookieAddrs[] = $bookieAddrString;
        }

        $deadBookies = array_diff($this->knownBookies, $newBookieAddrs);
        $this->knownBookies = $newBookieAddrs;

        // Simulating closing clients for dead bookies
        foreach ($deadBookies as $deadBookie) {
            // Simulate closure process
        }
    }

    public function getNewBookies($numBookiesNeeded) {
        return $this->getAdditionalBookies([], $numBookiesNeeded);
    }

    public function getAdditionalBookies($existingBookies, $numAdditionalBookiesNeeded) {
        $newBookies = [];

        if ($numAdditionalBookiesNeeded <= 0) {
            return $newBookies;
        }

        $allBookies = $this->knownBookies;

        shuffle($allBookies);

        foreach ($allBookies as $bookie) {
            if (in_array($bookie, $existingBookies)) {
                continue;
            }

            $newBookies[] = $bookie;
            $numAdditionalBookiesNeeded--;

            if ($numAdditionalBookiesNeeded == 0) {
                return $newBookies;
            }
        }

        throw new Exception("Not enough bookies");
    }
}

$watcher = new BookieWatcher('/path/to/bookies');
$watcher->readBookies();
--EXPECT--
