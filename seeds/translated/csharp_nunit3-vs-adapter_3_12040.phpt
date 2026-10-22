--TEST--
Translated seed (inferredbugs-csharp: nunit3-vs-adapter/3)
--FILE--
<?php
$cases = [
    ['lang' => 'php', 'multi' => false],
    ['lang' => 'php', 'multi' => true],
];

foreach ($cases as $case) {
    $tests = [];
    if ($case['multi']) {
        $tests[] = ['name' => 'TestA', 'body' => function () { return; }];
        $tests[] = ['name' => 'TestB', 'body' => function () { return; }];
    } else {
        $tests[] = ['name' => 'Test', 'body' => function () { return; }];
    }

    $passed = 0;
    foreach ($tests as $test) {
        $result = ($test['body'])();
        if ($result === null) {
            $passed++;
        }
    }

    $summary = ['total' => count($tests), 'passed' => $passed];
    if ($summary['total'] !== $summary['passed']) {
        throw new RuntimeException('unexpected test result');
    }
}
?>
--EXPECT--
