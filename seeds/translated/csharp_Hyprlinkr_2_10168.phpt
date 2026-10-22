--TEST--
Translated seed (inferredbugs-csharp: Hyprlinkr/2)
--FILE--
<?php
$u = parse_url('http://localhost/api/foo/123');
if (!$u || !isset($u['path'])) exit;
$p = trim($u['path'], '/');
$parts = explode('/', $p);
if (count($parts) < 3) exit;
[$api, $controller, $id] = $parts;
if ($api !== 'api' || !array_key_exists($controller, ['foo' => 1, 'bar' => 1])) exit;
if (!ctype_digit($id)) exit;
$args = ['id' => (int)$id];
ksort($args);
$expected = ['id' => 123];
ksort($expected);
if ($args == $expected) {
    $m = 'action_' . $controller;
    if (function_exists($m)) {
        $m($args['id']);
    }
}
function action_foo($id) { $x = $id + 1; }
function action_bar($id) { $x = $id - 1; }
?>
--EXPECT--
