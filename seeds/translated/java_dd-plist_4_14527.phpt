--TEST--
Translated seed (inferredbugs-java: dd-plist/4)
--FILE--
<?php

function readAll($in, $max) {
    $buf = '';
    while ($max > 0) {
        $n = fread($in, 1);
        if ($n === '') break; // EOF
        $buf .= $n;
        $max--;
    }
    return $buf;
}

function parse($data) {
    $magic_string = substr($data, 0, 8);
    if (strpos($magic_string, "bplist00") === 0) {
        return 'Binary property list parsed'; // Simulated parsing result
    } elseif (strpos($magic_string, "<?xml") === 0) {
        return 'XML property list parsed'; // Simulated parsing result
    } else {
        throw new Exception("Unsupported property list format.");
    }
}

function parseFile($file) {
    $handle = fopen($file, 'rb');
    $data = readAll($handle, PHP_INT_MAX);
    fclose($handle);
    return parse($data);
}

// Main execution
$file = 'input.plist'; // Replace with actual file path
$root = parseFile($file);

?>
--EXPECT--
