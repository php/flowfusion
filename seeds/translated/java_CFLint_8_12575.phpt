--TEST--
Translated seed (inferredbugs-java: CFLint/8)
--FILE--
<?php

$options = getopt("h::", [
    "rules::",
    "config::",
    "includeRule::",
    "excludeRule::",
    "folder::",
    "file::",
    "filterFile::",
    "v::",
    "version::",
    "ui::",
    "xml::",
    "html::",
    "json::",
    "text::",
    "stats::",
    "extensions::",
    "configfile::",
    "stdin::",
    "stdout::"
]);

$verbose = isset($options['v']);
$quiet = isset($options['q']);
$xmlOutput = isset($options['xml']);
$textOutput = isset($options['text']);
$jsonOutput = isset($options['json']);
$htmlOutput = isset($options['html']);
$folder = [];

if (isset($options['rules'])) {
    echo "Supported rules\n";
    // Mock rule output
    echo "  Rule1\n";
    echo "  Rule2\n";
    return;
}

if (isset($options['config'])) {
    // Load config logic here
}

if (isset($options['folder'])) {
    $folder = explode(',', $options['folder']);
}

if (isset($options['file'])) {
    $folder = array_merge($folder, explode(',', $options['file']));
}

if (isset($options['filterFile'])) {
    $filterFile = $options['filterFile'];
}

$scanFolder = $folder ?: [getcwd()];

foreach ($scanFolder as $scan) {
    if (is_dir($scan)) {
        // Scan logic, simulating a simple read operation
        $files = scandir($scan);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                // Simulated simple file reading and processing
                $content = file_get_contents($scan . '/' . $file);
                // Process content here
            }
        }
    }
}

if (isset($options['stdin'])) {
    // Read from stdin and process
    $stdinContent = file_get_contents("php://stdin");
    // Process stdin content here
}

if ($xmlOutput) {
    // Simulate XML output
    echo "<results>\n</results>";
}

if ($textOutput) {
    // Simulate text output
    echo "Results: \n";
}

if ($htmlOutput) {
    // Simulate HTML output
    echo "<html><body>Results</body></html>";
}

if ($jsonOutput) {
    // Simulate JSON output
    echo json_encode(["results" => []]);
}
--EXPECT--
