--TEST--
Translated seed (inferredbugs-csharp: Camelot/1)
--FILE--
<?php

function openFile($file) {
    if (stripos(PHP_OS, 'WIN') === 0) {
        popen($file, 'r'); // Simulating Process.Start for Windows
        return;
    }

    $command = getCommand();
    $process = popen("$command \"$file\"", 'r'); // Execute the command with the file as an argument

    if ($process) {
        pclose($process); // Close the process
    }
}

function getCommand() {
    if (stripos(PHP_OS, 'LINUX') === 0) {
        return 'xdg-open';
    }

    if (stripos(PHP_OS, 'DARWIN') === 0) {
        return 'open';
    }

    throw new Exception("Unsupported platform");
}

// Main entry point
$file = 'example.txt'; // Specify the file to open
openFile($file);

?>
--EXPECT--
