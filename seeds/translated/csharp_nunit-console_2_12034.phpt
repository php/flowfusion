--TEST--
Translated seed (inferredbugs-csharp: nunit-console/2)
--FILE--
<?php
$workDirectory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_console_runner_' . getmypid();
if (!is_dir($workDirectory)) {
    mkdir($workDirectory, 0777, true);
}
$oldOut = fopen('php://stdout', 'w');
$oldErr = fopen('php://stderr', 'w');
$outFile = $workDirectory . DIRECTORY_SEPARATOR . 'out.txt';
$errFile = $workDirectory . DIRECTORY_SEPARATOR . 'err.txt';
file_put_contents($outFile, "test output\n");
file_put_contents($errFile, "test error\n");
$xml = new SimpleXMLElement('<result><tests><test name="a"/><test name="b"/></tests></result>');
$xml->addAttribute('time', (string)(microtime(true) - 0.000001));
$xml->addChild('summary', 'ok');
$xmlString = $xml->asXML();
file_put_contents($workDirectory . DIRECTORY_SEPARATOR . 'result.xml', $xmlString);
file_put_contents($workDirectory . DIRECTORY_SEPARATOR . 'result2.xml', str_replace('ok', 'ok', $xmlString));
echo "Options -\n";
echo "    Use Same AppDomain\n";
echo "    Work Directory: $workDirectory\n";
echo "    Selected test(s):\n";
echo "    a\n";
echo "    b\n";
echo "NUnit3 Result File Saved as " . $workDirectory . DIRECTORY_SEPARATOR . 'result.xml' . "\n";
echo "NUnit2 Result File Saved as " . $workDirectory . DIRECTORY_SEPARATOR . 'result2.xml' . "\n";
?>
--EXPECT--
