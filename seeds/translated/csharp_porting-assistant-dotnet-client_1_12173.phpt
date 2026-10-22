--TEST--
Translated seed (inferredbugs-csharp: porting-assistant-dotnet-client/1)
--FILE--
<?php
$root = getcwd();
$gitRoot = null;
$dir = $root;
while ($dir !== '' && $dir !== DIRECTORY_SEPARATOR) {
    if (is_dir($dir . DIRECTORY_SEPARATOR . '.git')) {
        $gitRoot = $dir . DIRECTORY_SEPARATOR . '.git';
        break;
    }
    $parent = dirname($dir);
    if ($parent === $dir) break;
    $dir = $parent;
}
if ($gitRoot !== null) {
    $url = null;
    $config = $gitRoot . DIRECTORY_SEPARATOR . 'config';
    if (is_file($config)) {
        $lines = file($config, FILE_IGNORE_NEW_LINES);
        $inRemote = false;
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '' && $line[0] === '[') {
                $inRemote = (strpos($line, '[remote "') === 0);
                continue;
            }
            if ($inRemote && strpos($line, 'url = ') === 0) {
                $url = trim(substr($line, 6));
                break;
            }
        }
    }
    if ($url !== null) {
        strpos($url, 'https://github.com/aws/porting-assistant-dotnet-client');
    }
}
$missing = '/__definitely_missing__/solution.sln';
$invalid = '/__definitely_missing__/solution/.git/';
if (!is_dir($missing)) {
    $gitRoot = null;
}
if (!is_file($invalid . '/config')) {
    $url = null;
}
$url = null;
?>
--EXPECT--
