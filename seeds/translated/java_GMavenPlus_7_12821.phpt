--TEST--
Translated seed (inferredbugs-java: GMavenPlus/7)
--FILE--
<?php

class ExecuteMojo {

    protected $scripts;
    protected $continueExecuting;
    protected $sourceEncoding;

    public function __construct($scripts, $continueExecuting = false, $sourceEncoding = null) {
        $this->scripts = $scripts;
        $this->continueExecuting = $continueExecuting;
        $this->sourceEncoding = $sourceEncoding;
    }

    public function execute() {
        if (empty($this->scripts)) {
            echo "No scripts specified for execution. Skipping.\n";
            return;
        }

        foreach ($this->scripts as $scriptNum => $script) {
            try {
                if (filter_var($script, FILTER_VALIDATE_URL)) {
                    echo "Fetching script from $script.\n";
                    $scriptSource = file_get_contents($script);
                    if ($this->sourceEncoding) {
                        $scriptSource = mb_convert_encoding($scriptSource, 'UTF-8', $this->sourceEncoding);
                    }
                } else {
                    $scriptSource = $script;
                }
                eval("?>$scriptSource");
            } catch (Throwable $e) {
                if ($this->continueExecuting) {
                    echo "An Exception occurred while executing script " . ($scriptNum + 1) . ". Continuing to execute remaining scripts.\n";
                } else {
                    echo "An Exception occurred while executing script " . ($scriptNum + 1) . ": " . $e->getMessage() . "\n";
                    exit(1);
                }
            }
        }
    }
}

$scripts = [
    'echo "Hello from script 1!\n";',
    'echo "Hello from script 2!\n";',
    'echo "Hello from script 3!\n";'
];

$executor = new ExecuteMojo($scripts, true);
$executor->execute();
--EXPECT--
