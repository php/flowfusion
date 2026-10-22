--TEST--
Translated seed (inferredbugs-java: Java-Web-Security/1)
--FILE--
<?php
$input = '{"csp-report":{"document-uri":"http://example.com","referrer":"http://referrer.com","blocked-uri":"http://blocked.com","violated-directive":"script-src","source-file":"http://sourcefile.com","script-sample":"alert(1);","line-number":1}}';
$json = json_decode($input, true);

$cspReport = $json['csp-report'];
echo "document-uri: " . $cspReport['document-uri'] . "\n";
echo "referrer: " . $cspReport['referrer'] . "\n";
echo "blocked-uri: " . $cspReport['blocked-uri'] . "\n";
echo "violated-directive: " . $cspReport['violated-directive'] . "\n";
echo "source-file: " . $cspReport['source-file'] . "\n";
echo "script-sample: " . $cspReport['script-sample'] . "\n";
echo "line-number: " . $cspReport['line-number'] . "\n";
?>
--EXPECT--
