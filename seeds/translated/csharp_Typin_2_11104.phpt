--TEST--
Translated seed (inferredbugs-csharp: Typin/2)
--FILE--
<?php
$items = [];
$items[] = 'DefaultCommand';
$items[] = 'DebugDirective';
$items[] = 'PreviewDirective';
$items[] = ['title' => 'test', 'executable' => 'test', 'version' => 'test', 'description' => 'test'];
$items[] = ['interactive' => true, 'promptForeground' => 'magenta'];
$items[] = ['interactive' => false, 'promptForeground' => 'magenta'];
$items[] = fopen('php://memory', 'r+');
$items[] = fopen('php://temp', 'r+');

foreach ($items as $item) {
    $config = [
        'commands' => [],
        'directives' => [],
        'title' => null,
        'executable' => null,
        'version' => null,
        'description' => null,
        'interactive' => false,
        'promptForeground' => null,
        'console' => null,
    ];

    $config['commands'][] = 'DefaultCommand';
    $config['commands'][] = 'DefaultCommand';
    $config['commands'][] = 'DefaultCommand';
    $config['commands'][] = 'DefaultCommand';
    $config['commands'][] = 'DefaultCommand';

    $config['directives'][] = 'DebugDirective';
    $config['directives'][] = 'PreviewDirective';

    if (is_array($item)) {
        if (array_key_exists('title', $item)) $config['title'] = $item['title'];
        if (array_key_exists('executable', $item)) $config['executable'] = $item['executable'];
        if (array_key_exists('version', $item)) $config['version'] = $item['version'];
        if (array_key_exists('description', $item)) $config['description'] = $item['description'];
        if (array_key_exists('interactive', $item)) $config['interactive'] = $item['interactive'];
        if (array_key_exists('promptForeground', $item)) $config['promptForeground'] = $item['promptForeground'];
    } else {
        $config['console'] = $item;
    }

    if (is_resource($item)) {
        $config['console'] = $item;
    }

    $app = $config;
    if ($app === null) {
        exit(1);
    }
}
?>
--EXPECT--
