--TEST--
Translated seed (inferredbugs-java: AppiumTestDistribution/16)
--FILE--
<?php

function runCommand($command) {
    $output = "";
    $handle = popen($command, "r");
    if ($handle) {
        while (!feof($handle)) {
            $line = fread($handle, 1024);
            $output .= $line;
            if (strpos($line, "Console LogLevel: debug") !== false && strpos($line, "Complete") !== false) {
                break;
            }
        }
        pclose($handle);
    }
    return $output;
}

function runCommandThruProcessBuilder($command) {
    $output = "";
    $commands = ["/bin/sh", "-c", $command];
    $process = proc_open($commands[0], [
        1 => ["pipe", "w"],
        2 => ["pipe", "w"]
    ], $pipes);
    
    if (is_resource($process)) {
        fwrite($pipes[1], $command);
        fclose($pipes[1]);
        
        while ($line = fgets($pipes[1])) {
            $output .= $line;
            echo $output;
        }

        fclose($pipes[1]);
        proc_close($process);
    }
    
    return trim(explode(":", $output)[1]);
}

$command = "echo 'Console LogLevel: debug'; sleep 1; echo 'Complete'";
runCommand($command);
runCommandThruProcessBuilder($command);
--EXPECT--
