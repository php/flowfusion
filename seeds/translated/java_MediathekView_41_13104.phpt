--TEST--
Translated seed (inferredbugs-java: MediathekView/41)
--FILE--
<?php

$urlWartenBasis = 500; // ms
$timeout = 10000;

$listeSeitenZaehler = [];
$listeSeitenZaehlerFehler = [];
$listeSeitenZaehlerFehlerVersuche = [];
$listeSeitenZaehlerWartezeitFehlerVersuche = [];
$listeSummeByte = [];

function incSeitenZaehler($liste, $sender, $inc) {
    foreach ($liste as &$item) {
        if ($item['senderName'] === $sender) {
            $item['seitenAnzahl'] += $inc;
            return;
        }
    }
    $liste[] = ['senderName' => $sender, 'seitenAnzahl' => $inc];
}

function getUri($sender, $addr, $encoding, $maxVersuche, &$seite, $meldung) {
    global $timeout, $urlWartenBasis, $listeSummeByte;
    $aktVer = 0;
    $PAUSE = 1000;

    do {
        $aktVer++;
        sleep($aktVer > 1 ? $PAUSE / 1000 : 0);
        $seite = "";
        
        try {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => "User-Agent: CustomAgent\r\n",
                    'timeout' => $timeout / 1000
                ]
            ]);
            $in = fopen($addr, 'r', false, $context);
            if ($in) {
                while (!feof($in)) {
                    $seite .= fread($in, 8192);
                    incSeitenZaehler($listeSummeByte, $sender, strlen($seite));
                }
                fclose($in);
                incSeitenZaehler($GLOBALS['listeSeitenZaehler'], $sender, 1);
                return $seite;
            } else {
                incSeitenZaehler($GLOBALS['listeSeitenZaehlerFehler'], $sender, 1);
            }
        } catch (Exception $ex) {
            incSeitenZaehler($GLOBALS['listeSeitenZaehlerFehlerVersuche'], $sender, 1);
        }
    } while ($aktVer < $maxVersuche);
    return $seite;
}

$seite = "";
getUri("TestSender", "http://example.com", "UTF-8", 3, $seite, "");
--EXPECT--
