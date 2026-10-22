--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/20)
--FILE--
<?php

$os = PHP_OS_FAMILY;
if ($os === 'Windows') {
    $osType = 'WINDOWS';
} elseif ($os === 'Linux') {
    $osType = 'LINUX';
} elseif ($os === 'Darwin') {
    $osType = 'MAC';
} else {
    $osType = 'NOT_SUPPORTED';
}

echo $osType;
--EXPECT--
