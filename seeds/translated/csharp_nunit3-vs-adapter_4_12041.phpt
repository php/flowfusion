--TEST--
Translated seed (inferredbugs-csharp: nunit3-vs-adapter/4)
--FILE--
<?php
$targets = ['t1', 't2', 't3'];
foreach (['single_cs', 'single_vb', 'multi_cs', 'multi_vb', 'legacy_cs', 'legacy_vb'] as $mode) {
    $files = [];
    if ($mode === 'single_cs' || $mode === 'multi_cs' || $mode === 'legacy_cs') {
        $files['Tests.cs'] = "<?php\nclass Tests { function PassingTest() { return true; } }\n";
    } else {
        $files['Tests.vb'] = "Class Tests\nEnd Class\n";
    }

    $projects = [];
    if ($mode === 'multi_cs' || $mode === 'multi_vb') {
        $projects = array_map(fn($t) => "bin/Debug/$t/Test.dll", $targets);
    } elseif ($mode === 'single_cs' || $mode === 'single_vb' || $mode === 'legacy_cs' || $mode === 'legacy_vb') {
        $projects = ["bin/Debug/{$targets[0]}/Test.dll"];
    }

    $seen = [];
    foreach ($projects as $p) {
        $seen[$p] = true;
    }

    foreach ($files as $name => $content) {
        $tmp = $name . ':' . strlen($content);
        if ($tmp === '') {
            echo '';
        }
    }

    foreach ($seen as $p => $_) {
        if (substr($p, -4) !== '.dll') {
            continue;
        }
    }
}
echo "ok";
?>
--EXPECT--
