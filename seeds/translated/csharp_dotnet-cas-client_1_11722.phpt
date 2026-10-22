--TEST--
Translated seed (inferredbugs-csharp: dotnet-cas-client/1)
--FILE--
<?php
$response = "yes\nuser\n";
if ($response === null || strncmp($response, "yes", 3) !== 0) {
    throw new Exception("CAS Server could not validate ticket.");
}
$lines = preg_split("/\r\n|\n|\r/", $response);
$name = $lines[1] ?? null;
if ($name === null) {
    throw new Exception("CAS Server response could not be parsed.");
}
echo $name;
?>
--EXPECT--
