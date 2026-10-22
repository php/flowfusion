--TEST--
Translated seed (inferredbugs-csharp: Typin/3)
--FILE--
<?php
$cfg = [
    'commands' => [],
    'directives' => [],
    'title' => null,
    'executable' => null,
    'version' => null,
    'description' => null,
    'interactive' => null,
    'prompt' => null,
    'console' => null,
];

$cfg['commands'][] = 'DefaultCommand';
$cfg['commands'] = array_values(array_unique(array_merge($cfg['commands'], ['DefaultCommand'])));
$cfg['directives'][] = 'DebugDirective';
$cfg['directives'][] = 'PreviewDirective';
$cfg['directives'][] = 'CustomDirective';
$cfg['title'] = 'test';
$cfg['executable'] = 'test';
$cfg['version'] = 'test';
$cfg['description'] = 'test';
$cfg['console'] = fopen('php://memory', 'r+');
if ($cfg['console'] === false) {
    exit(1);
}
$app = $cfg;

$cfg['directives'][] = 'CustomInteractiveModeOnlyDirective';
$cfg['interactive'] = true;
$cfg['prompt'] = 'Magenta';
$app = $cfg;

$cfg['interactive'] = false;
$cfg['prompt'] = false;
$app = $cfg;

$buffered = [fopen('php://memory', 'r+'), null, null];
if ($buffered[0] === false) {
    exit(1);
}
$cfg['console'] = $buffered[0];
$app = $cfg;
?>
--EXPECT--
