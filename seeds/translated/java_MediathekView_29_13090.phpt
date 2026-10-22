--TEST--
Translated seed (inferredbugs-java: MediathekView/29)
--FILE--
<?php

function checkPathWritable($path) {
    $ret = false;
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    if ($path !== "" && is_dir($path) && is_writable($path)) {
        $tmpFile = tempnam($path, 'mediathek');
        unlink($tmpFile);
        $ret = true;
    }
    return $ret;
}

function programCheck($data) {
    $text = "";
    foreach ($data as $programSet) {
        if (!$programSet['isFreeLine'] && !$programSet['isLabel']) {
            $text .= "++++++++++++++++++++++++++++++++++++++++++++\n";
            $text .= "| Programmgruppe: " . $programSet['name'] . "\n";
            $targetPath = $programSet['targetPath'];
            if ($targetPath === "") {
                $text .= "|      Zielpfad fehlt!\n";
            } elseif (!checkPathWritable($targetPath)) {
                $text .= "|      Falscher Zielpfad!\n";
                $text .= "|      -> Zielpfad \"$targetPath\" nicht beschreibbar!\n";
            }
            foreach ($programSet['programs'] as $program) {
                if (!is_executable($program['path'])) {
                    $text .= "|      Falscher Programmpfad!\n";
                    $text .= "|      -> Programmname: " . $program['name'] . "\n";
                    $text .= "|      -> Pfad: " . $program['path'] . "\n";
                    if (strpos($program['path'], DIRECTORY_SEPARATOR) === false) {
                        $text .= "|      Wenn das Programm nicht im Systempfad liegt, wird der Start nicht klappen!\n";
                    }
                }
            }
            $text .= "| -> Ok!\n";
            $text .= "++++++++++++++++++++++++++++++++++++++++++++\n\n\n";
        }
    }
    echo $text;
}

$data = [
    ['isFreeLine' => false, 'isLabel' => false, 'name' => 'Test Group', 'targetPath' => '/tmp/test', 'programs' => [['name' => 'Test Program', 'path' => '/usr/bin/test']]]
];

programCheck($data);
--EXPECT--
