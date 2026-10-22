--TEST--
Translated seed (inferredbugs-csharp: nunit3-vs-adapter/2)
--FILE--
<?php
$targetFrameworks = ['net8.0', 'net7.0'];
$dotNetCliTargetFrameworks = ['net8.0', 'net7.0', 'net6.0'];

foreach ([$targetFrameworks, $dotNetCliTargetFrameworks] as $frameworks) {
    foreach ($frameworks as $tfm) {
        $files = [];
        $files[$tfm . '/Tests.php'] = "<?php\nclass Tests { public function PassingTest() { return true; } }\n";
        $files[$tfm . '/project.json'] = json_encode(['targetFramework' => $tfm, 'tests' => array_keys($files)], JSON_PRETTY_PRINT);
        $loaded = [];
        foreach ($files as $name => $content) {
            $loaded[$name] = $content;
        }
        $count = 0;
        foreach ($loaded as $name => $content) {
            if (str_ends_with($name, 'Tests.php')) {
                require 'data://text/plain,' . rawurlencode($content);
                $count++;
            }
        }
        if ($count !== 1) {
            throw new RuntimeException('unexpected test count');
        }
    }
}

$multi = [];
foreach ($targetFrameworks as $tfm) {
    $multi[$tfm] = ['Tests.php' => "<?php\nclass Tests { public function PassingTest() { return 1; } }\n"];
}
foreach ($multi as $tfm => $proj) {
    foreach ($proj as $file => $code) {
        require 'data://text/plain,' . rawurlencode($code);
    }
}

$legacy = [];
$legacy['packages.config'] = ['NUnit' => '3.11.0', 'TestAdapter' => '1.0.0'];
$legacy['Tests.php'] = "<?php\nclass Tests { public function PassingTest() { return 'pass'; } }\n";
foreach ($legacy as $name => $content) {
    if ($name === 'Tests.php') {
        require 'data://text/plain,' . rawurlencode($content);
    }
}

echo "";
?>
--EXPECT--
