--TEST--
Translated seed (inferredbugs-csharp: nunit3-vs-adapter/5)
--FILE--
<?php
$targetFrameworks = ['net8.0', 'net6.0'];
$dotNetCliTargetFrameworks = ['net8.0'];
$legacy = ['net45'];

$cases = [
    ['single', 'cs', $targetFrameworks[0]],
    ['single', 'vb', $targetFrameworks[0]],
    ['multi', 'cs', $targetFrameworks],
    ['multi', 'vb', $targetFrameworks],
    ['legacy', 'cs', $legacy[0]],
    ['legacy', 'vb', $legacy[0]],
];

foreach ($cases as $case) {
    [$kind, $lang, $tf] = $case;
    $files = [];
    if ($lang === 'cs') {
        $files['Tests.cs'] = "<?php\nclass Tests { public function PassingTest() { return true; } }\n";
    } else {
        $files['Tests.php'] = "<?php\nclass Tests { public function PassingTest() { return true; } }\n";
    }

    $config = [
        'kind' => $kind,
        'lang' => $lang,
        'target' => $tf,
        'targets' => is_array($tf) ? implode(';', $tf) : $tf,
        'files' => $files,
    ];

    $ok = isset($config['files']) && count($config['files']) === 1;
    if (!$ok) {
        throw new RuntimeException('unexpected configuration');
    }

    $pass = (new Tests())->PassingTest();
    if ($pass !== true) {
        throw new RuntimeException('test did not pass');
    }
}

echo "";
?>
--EXPECT--
