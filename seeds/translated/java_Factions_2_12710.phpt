--TEST--
Translated seed (inferredbugs-java: Factions/2)
--FILE--
<?php

function write($file, $content) {
    $out = fopen($file, 'w');
    fwrite($out, $content);
    fclose($out);
}

function read($file) {
    $inStream = fopen($file, 'r');
    $content = '';
    
    while (($line = fgets($inStream)) !== false) {
        $content .= $line;
    }
    
    fclose($inStream);
    return $content;
}

function inputStreamToReader($in) {
    if (strlen($in) < 3) {
        return $in;
    }

    $byte1 = ord($in[0]);
    $byte2 = ord($in[1]);
    
    if ($byte1 === 0xFF && $byte2 === 0xFE) {
        return mb_convert_encoding(substr($in, 2), 'UTF-16LE');
    } else if ($byte1 === 0xFF && $byte2 === 0xFF) {
        return mb_convert_encoding(substr($in, 2), 'UTF-16BE');
    } else {
        $byte3 = ord($in[2]);
        if ($byte1 === 0xEF && $byte2 === 0xBB && $byte3 === 0xBF) {
            return mb_convert_encoding(substr($in, 3), 'UTF-8');
        } else {
            $first3 = substr($in, 0, 3);
            return $first3 . substr($in, 3);
        }
    }
}

$file = 'test.txt';
$content = "Hello, world!\nThis is a test.";
write($file, $content);
$result = read($file);
echo $result;

?>
--EXPECT--
