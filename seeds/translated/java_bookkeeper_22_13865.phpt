--TEST--
Translated seed (inferredbugs-java: bookkeeper/22)
--FILE--
<?php

$electionPath = "/underreplication/auditorelection";
$auditorElectors = [];
$bs = range(1, 6); // Simulating 6 bookies

function verifyAuditor() {
    global $bs;
    if (count($bs) > 1) {
        die("Multiple Bookies acting as Auditor!");
    }
    return reset($bs);
}

function shutdownBookie(&$bkServer) {
    global $auditorElectors;
    echo "Shutting down bookie: $bkServer\n";
    unset($bkServer);
}

function waitForNewAuditor($auditor) {
    global $bs;
    $retryCount = 8;
    while ($retryCount > 0) {
        if (count($bs) > 0) {
            $newAuditor = reset($bs);
            if ($auditor == $newAuditor) {
                usleep(500000); // Sleep for 500ms
            } else {
                return $newAuditor;
            }
        }
        $retryCount--;
    }
    die("New Auditor is not reelected after auditor crashes");
}

$auditor = verifyAuditor();
shutdownBookie($auditor);

$newAuditor1 = waitForNewAuditor($auditor);
shutdownBookie($newAuditor1);
$newAuditor2 = waitForNewAuditor($newAuditor1);
if ($auditor == $newAuditor2) {
    die("Auditor re-election is not happened for auditor failure!");
}
--EXPECT--
