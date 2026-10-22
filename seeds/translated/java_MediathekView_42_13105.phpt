--TEST--
Translated seed (inferredbugs-java: MediathekView/42)
--FILE--
<?php

$listeSeitenZaehler = [];
$listeSeitenZaehlerFehler = [];
$listeSeitenZaehlerFehlerVersuche = [];
$listeSeitenZaehlerWartezeitFehlerVersuche = [];
$listeSummeByte = [];
$timeout = 10000;
$wartenBasis = 500;

function incSeitenZaehler(&$liste, $sender, $inc) {
    if (isset($liste[$sender])) {
        $liste[$sender] += $inc;
    } else {
        $liste[$sender] = $inc;
    }
}

function getUri($sender, $addr, &$seite, $kodierung, $maxVersuche, $meldung) {
    global $timeout, $wartenBasis, $listeSeitenZaehler, $listeSeitenZaehlerFehler, $listeSeitenZaehlerWartezeitFehlerVersuche, $listeSeitenZaehlerFehlerVersuche, $listeSummeByte;
    
    $aktTimeout = $timeout;
    $aktVer = 0;

    do {
        ++$aktVer;
        if ($aktVer > 1) {
            usleep(1000 * 1); // 1 second pause
        }
        
        $seite = file_get_contents($addr);
        if ($seite !== false) {
            incSeitenZaehler($listeSeitenZaehler, $sender, 1);
            return $seite;
        } else {
            incSeitenZaehler($listeSeitenZaehlerFehlerVersuche, $sender, 1);
            if ($aktVer >= $maxVersuche) {
                incSeitenZaehler($listeSeitenZaehlerFehler, $sender, 1);
            }
        }
    } while ($aktVer < $maxVersuche);

    return '';
}

$sender = "TestSender";
$addr = "http://example.com"; // Replace with a valid URL
$seite = '';
getUri($sender, $addr, $seite, 'UTF-8', 3, '');
--EXPECT--
