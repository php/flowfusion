--TEST--
Translated seed (inferredbugs-csharp: CliWrap/1)
--FILE--
<?php

class Command
{
    private string $targetFilePath;
    private string $arguments;
    private string $workingDirPath;
    private array $environmentVariables;

    public function __construct(string $targetFilePath, string $arguments = "", string $workingDirPath = "")
    {
        $this->targetFilePath = $targetFilePath;
        $this->arguments = $arguments;
        $this->workingDirPath = $workingDirPath ?: getcwd();
        $this->environmentVariables = [];
    }

    public function withArguments(string $arguments): Command
    {
        return new Command($this->targetFilePath, $arguments, $this->workingDirPath);
    }

    public function withEnvironmentVariables(array $environmentVariables): Command
    {
        $this->environmentVariables = $environmentVariables;
        return $this;
    }

    public function execute(): void
    {
        $descriptorspec = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr
        ];

        $process = proc_open($this->targetFilePath . ' ' . $this->arguments, $descriptorspec, $pipes, $this->workingDirPath, $this->environmentVariables);

        if (is_resource($process)) {
            fclose($pipes[0]); // Close stdin

            $output = stream_get_contents($pipes[1]);
            $errorOutput = stream_get_contents($pipes[2]);

            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        }
    }
}

$command = new Command('php -r "echo str_repeat(\'Hello, World!\\n\', 5);"');
$command->execute();
--EXPECT--
