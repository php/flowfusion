--TEST--
Translated seed (inferredbugs-java: dagger/3)
--FILE--
<?php

$manifestXml = $argv[1];
$moduleName = $argv[2];
$baseDir = $argv[3];

if (!file_exists($manifestXml)) {
    echo "No such file: " . $manifestXml . PHP_EOL;
    exit(1);
}

if (!is_dir($baseDir)) {
    echo "No such directory: " . $baseDir . PHP_EOL;
    exit(1);
}

$dom = new DOMDocument();
$dom->load($manifestXml);
$root = $dom->documentElement;

if ($root->nodeName !== "manifest") {
    throw new InvalidArgumentException("Expected <manifest> but was <" . $root->nodeName . ">");
}

$packageName = $root->getAttribute("package");
if ($packageName === "") {
    throw new InvalidArgumentException("Expected a package attribute");
}

$moduleNameShort = substr($moduleName, strrpos($moduleName, '.') + 1);
$file = $baseDir . '/' . str_replace('.', '/', $packageName) . "/$moduleNameShort.php";
mkdir(dirname($file), 0777, true);

$names = [];
foreach ($root->getElementsByTagName('application') as $app) {
    foreach ($app->childNodes as $node) {
        if ($node instanceof DOMElement && in_array($node->nodeName, ['activity', 'provider', 'receiver', 'service'])) {
            $nameAttr = $node->getAttribute('android:name');
            if ($nameAttr === "") {
                throw new InvalidArgumentException("Expected a name attribute on " . $node->nodeName);
            }
            $names[] = $nameAttr;
        }
    }
}

sort($names);
$className = strpos($moduleName, '.') !== false ? $moduleName : "$packageName\\$moduleNameShort";

file_put_contents($file, "<?php\n\nnamespace $packageName;\n\nclass $moduleNameShort {}\n");
?>
--EXPECT--
