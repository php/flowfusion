--TEST--
Translated seed (inferredbugs-csharp: FlubuCore/1)
--FILE--
<?php
usleep(2000000);

$isWindows = true;
$isNetCore = false;

if ($isWindows) {
    $flubuPath = realpath("WebApi/flubu.exe") ?: getcwd() . DIRECTORY_SEPARATOR . "WebApi" . DIRECTORY_SEPARATOR . "flubu.exe";
    $deployScript = $isNetCore
        ? (realpath("Updates/WebApi/DeploymentScript.cs") ?: getcwd() . DIRECTORY_SEPARATOR . "Updates" . DIRECTORY_SEPARATOR . "WebApi" . DIRECTORY_SEPARATOR . "DeploymentScript.cs")
        : (realpath("WebApi/DeployScript.cs") ?: getcwd() . DIRECTORY_SEPARATOR . "WebApi" . DIRECTORY_SEPARATOR . "DeployScript.cs");
} else {
    $flubuPath = realpath("Updates/WebApi/Deploy.bat") ?: getcwd() . DIRECTORY_SEPARATOR . "Updates" . DIRECTORY_SEPARATOR . "WebApi" . DIRECTORY_SEPARATOR . "Deploy.bat";
    $deployScript = realpath("Updates/WebApi/DeploymentScript.cs") ?: getcwd() . DIRECTORY_SEPARATOR . "Updates" . DIRECTORY_SEPARATOR . "WebApi" . DIRECTORY_SEPARATOR . "DeploymentScript.cs";
}

echo "path: $deployScript\n";
echo "flubu path: $flubuPath\n";

$retry = 0;
while ($retry < 10) {
    $cmd = $isWindows
        ? escapeshellarg($flubuPath) . " -s=" . escapeshellarg($deployScript)
        : "dotnet flubu -s=" . escapeshellarg($deployScript);

    $descriptors = [1 => ["pipe", "w"], 2 => ["pipe", "w"]];
    $proc = proc_open($cmd, $descriptors, $pipes, dirname($flubuPath));
    if (is_resource($proc)) {
        fclose($pipes[1]);
        fclose($pipes[2]);
        $code = proc_close($proc);
    } else {
        $code = 1;
    }

    echo "flubu exit code: $code\n";
    if ($code === 0) {
        break;
    }
    $retry++;
}

usleep(2000000);
?>
--EXPECT--
