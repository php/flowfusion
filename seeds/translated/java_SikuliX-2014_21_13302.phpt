--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/21)
--FILE--
<?php

$version = PHP_VERSION;
$os = PHP_OS;

if (stripos($os, 'WIN') === 0) {
    $osType = 'Windows';
} elseif (stripos($os, 'DAR') === 0) {
    $osType = 'Mac';
} elseif (stripos($os, 'LINUX') === 0) {
    $osType = 'Linux';
} else {
    $osType = 'Not Supported';
}

$pathSeparator = DIRECTORY_SEPARATOR;

$clipboardContent = '';
if (function_exists('shell_exec')) {
    $clipboardContent = shell_exec('pbpaste 2>/dev/null') ?: shell_exec('get-clipboard 2>/dev/null') ?: shell_exec('xsel --clipboard --output 2>/dev/null');
}

function setClipboard($text) {
    shell_exec('echo ' . escapeshellarg($text) . ' | pbcopy 2>/dev/null') ?: shell_exec('echo ' . escapeshellarg($text) . ' | xclip -selection clipboard 2>/dev/null');
}

setClipboard('Test Clipboard Content');
--EXPECT--
