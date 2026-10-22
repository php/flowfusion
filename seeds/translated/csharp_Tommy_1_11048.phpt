--TEST--
Translated seed (inferredbugs-csharp: Tommy/1)
--FILE--
<?php
$toml = <<<'TOML'
[Foo]
Bar = 1
foo.bar = 2

[[A]]
X = true

[[a]]
x = false
TOML;

$lines = preg_split('/\R/', $toml);
$data = [];
$path = [];

foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#') continue;

    if ($line[0] === '[') {
        if (preg_match('/^\[\[([^\]]+)\]\]$/', $line, $m)) {
            $name = strtolower(trim($m[1]));
            $path = [$name, (string)(isset($data[$name]) && is_array($data[$name]) ? count($data[$name]) : 0)];
            if (!isset($data[$name]) || !is_array($data[$name])) $data[$name] = [];
            $data[$name][] = [];
        } elseif (preg_match('/^\[([^\]]+)\]$/', $line, $m)) {
            $path = [strtolower(trim($m[1]))];
            if (!isset($data[$path[0]]) || !is_array($data[$path[0]])) $data[$path[0]] = [];
        }
        continue;
    }

    if (preg_match('/^([^=]+)=(.+)$/', $line, $m)) {
        $key = strtolower(trim($m[1]));
        $value = trim($m[2]);
        $full = implode('.', array_merge($path, [$key]));
        if (array_key_exists($full, $data)) continue;
        $data[$full] = $value;
    }
}

echo "";
?>
--EXPECT--
