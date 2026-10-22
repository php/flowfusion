--TEST--
Translated seed (inferredbugs-csharp: nunit-console/1)
--FILE--
<?php
$children = [];
for ($i = 0; $i < 3; $i++) {
    $children[] = [
        'name' => "child$i",
        'run' => function () use ($i) {
            if ($i === 1) {
                throw new RuntimeException("boom");
            }
            return ['state' => 'success', 'value' => $i];
        }
    ];
}

$context = [
    'currentTest' => 'suite',
    'currentResult' => ['state' => 'success', 'results' => [], 'exception' => null],
    'timeout' => null,
];

try {
    foreach ($children as $child) {
        try {
            $result = $child['run']();
        } catch (Throwable $e) {
            $result = ['state' => 'failed', 'exception' => $e->getMessage()];
        }
        $context['currentResult']['results'][] = $result;
        if (($result['state'] ?? null) === 'cancelled') {
            break;
        }
    }
} catch (Throwable $e) {
    $context['currentResult']['exception'] = $e->getMessage();
}

echo "done\n";
?>
--EXPECT--
