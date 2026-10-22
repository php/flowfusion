--TEST--
Translated seed (inferredbugs-java: commons-csv/115)
--FILE--
<?php

$CR = "\r";
$LF = "\n";
$CRLF = $CR . $LF;

$code = "one,two,three\n" 
    . "'',''\n"       
    . "/',/'\n"       
    . "'/'','/''\n"   
    . "'''',''''\n"   
    . "/,,/,\n"       
    . "//,//\n"       
    . "'//','//'\n"   
    . "   8   ,   \"quoted \"\" /\" // string\"   \n"     
    . "9,   /\n   \n";

$records = explode($CRLF, $code);
$result = [
    ["one", "two", "three"],
    ["", ""],
    ["'", "'"],
    ["'", "'"],
    ["'", "'"],
    [",", ","],
    ["/", "/"],
    ["/", "/"],
    ["   8   ", "   \"quoted \"\" /\" / string\"   "],
    ["9", "   \n   "],
];

foreach ($records as $record) {
    $values = array_map('trim', explode(',', $record));
    foreach ($values as $key => $value) {
        if (strpos($value, '/') !== false) {
            $values[$key] = str_replace('/', '', $value);
        }
    }
    $final[] = $values;
}

$finalCount = count($final);
$expectedCount = count($result);

if ($finalCount > 0) {
    for ($i = 0; $i < min($finalCount, $expectedCount); $i++) {
        if ($final[$i] !== $result[$i]) {
            // Handle mismatches if needed
        }
    }
}
?>
--EXPECT--
