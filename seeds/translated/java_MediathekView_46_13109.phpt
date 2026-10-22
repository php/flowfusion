--TEST--
Translated seed (inferredbugs-java: MediathekView/46)
--FILE--
<?php

$output = "";
$importUrl = "";
$userAgent = "";
$senderAllesLaden = false;
$updateFilmliste = true;
$pfad = "";
$serverLaufen = true;

$argv = $GLOBALS['argv'];
if (!empty($argv)) {
    if (strpos($argv[1], '-') !== 0) {
        if (substr($argv[1], -1) !== DIRECTORY_SEPARATOR) {
            $argv[1] .= DIRECTORY_SEPARATOR;
        }
        $pfad = $argv[1];
    }
    for ($i = 0; $i < count($argv); ++$i) {
        if ($argv[$i] === 'STARTP_ALLES') {
            $senderAllesLaden = true;
            $updateFilmliste = false;
        }
        if (strcasecmp($argv[$i], 'STARTP_EXPORT_DATEI') === 0 && isset($argv[$i + 1])) {
            $output = $argv[$i + 1];
        }
        if (strcasecmp($argv[$i], 'STARTP_IMPORT_URL') === 0 && isset($argv[$i + 1])) {
            $importUrl = $argv[$i + 1];
        }
        if (strcasecmp($argv[$i], 'STARTP_USER_AGENT') === 0 && isset($argv[$i + 1])) {
            $userAgent = $argv[$i + 1];
        }
    }
}

function logMessage($message) {
    echo $message . PHP_EOL;
}

if ($senderAllesLaden) {
    logMessage("Filme laden: alles laden");
} else {
    logMessage("Filme laden: nur update laden");
}
if ($updateFilmliste) {
    logMessage("Filmliste: aktualisieren");
} else {
    logMessage("Filmliste: neue erstellen");
}

logMessage("ImportUrl: " . $importUrl);
logMessage("Outputfile: " . $output);
logMessage("");

while ($serverLaufen) {
    usleep(5000000);
}

if (!empty($importUrl)) {
    logMessage("Filmliste importieren von: " . $importUrl);
}

$outputList = explode(",", ltrim($output, ','));
foreach ($outputList as $file) {
    logMessage("Writing to file: " . $file);
}

logMessage("End of program");
--EXPECT--
