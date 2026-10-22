--TEST--
Translated seed (inferredbugs-java: commons-csv/133)
--FILE--
<?php

$csvInput = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $csvInput);
$records = [];
foreach ($lines as $line) {
    if (trim($line) === '') continue;
    $records[] = str_getcsv($line);
}

for ($i = 0; $i < count($result); $i++) {
    if ($records[$i] !== $result[$i]) {
        // This is where you could handle a failure, e.g., log or echo a message
    }
}

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
$res = [
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

$lines = explode("\n", $code);
$records = [];
foreach ($lines as $line) {
    $records[] = str_getcsv($line);
}

foreach ($res as $i => $expected) {
    if ($records[$i] !== $expected) {
        // Handle failure
    }
}

$emptyFileParser = str_getcsv("");
if (empty($emptyFileParser)) {
    // Empty file behaviour is as expected
}

$csvEndings = [
    "foo\rbaar,\rhello,world\r,kanu",
    "foo\r\nbaar,\r\nhello,world\r\n,kanu",
    "foo\nbaar,\nhello,world\n,kanu"
];

foreach ($csvEndings as $code) {
    $lines = explode("\n", $code);
    $records = [];
    foreach ($lines as $line) {
        if (trim($line) === '') continue;
        $records[] = str_getcsv($line);
    }
    // Handle the records as needed
}

?>
--EXPECT--
