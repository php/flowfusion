--TEST--
Translated seed (inferredbugs-java: JDeploy/1)
--FILE--
<?php

$cmd = 'ls -l'; // Command that might cause issues depending on shell and environment
$descriptorSpec = [
    1 => ["pipe", "w"], // stdout
    2 => ["pipe", "w"]  // stderr
];

$process = proc_open($cmd, $descriptorSpec, $pipes);

if (is_resource($process)) {
    $output = stream_get_contents($pipes[1]);
    $error = stream_get_contents($pipes[2]);
    
    fclose($pipes[1]);
    fclose($pipes[2]);

    proc_close($process);
    
    echo $error . "\n" . $output;
}
--EXPECT--
