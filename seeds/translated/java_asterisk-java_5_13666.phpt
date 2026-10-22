--TEST--
Translated seed (inferredbugs-java: asterisk-java/5)
--FILE--
<?php

$version = null;
$versions = null;
$skipQueues = false;

function getVersion($file) {
    global $version, $versions;

    if ($versions === null) {
        $map = [];
        $result = explode("\n", shell_exec("show version files"));

        for ($i = 2; $i < count($result); $i++) {
            if (preg_match("/^([\\S]+)\\s+Revision: ([0-9\\.]+)/", $result[$i], $matches)) {
                $map[$matches[1]] = $matches[2];
            }
        }

        $versions = $map;
    }

    if (!isset($versions[$file])) {
        return null;
    }

    $parts = explode(".", $versions[$file]);
    $intParts = [];

    foreach ($parts as $part) {
        $intParts[] = is_numeric($part) ? (int)$part : 0;
    }

    return $intParts;
}

$versionInfo = getVersion("sample_file");
--EXPECT--
