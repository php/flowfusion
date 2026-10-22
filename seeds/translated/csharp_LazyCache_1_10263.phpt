--TEST--
Translated seed (inferredbugs-csharp: LazyCache/1)
--FILE--
<?php
$cache = [];
$key = 'testKey';
$object = (object)['SomeItems' => [1, 2, 3, 'testing123'], 'SomeMessage' => 'testing123'];

$cache[$key] = $object;
$object->SomeItems[] = 'Another';
$object->SomeMessage = 'changed-it-up';
$actual = $cache[$key] ?? null;
if ($actual !== $object) exit(1);
if (!in_array('Another', $actual->SomeItems, true)) exit(1);
if ($actual->SomeMessage !== 'changed-it-up') exit(1);

$cache = [];
$cache[$key] = 'testObject';
if (($cache[$key] ?? null) !== 'testObject') exit(1);

$cache = [];
$cache['nullableInt'] = 123;
if (($cache['nullableInt'] ?? null) !== 123) exit(1);

$cache = [];
$cache['dt'] = new DateTimeImmutable('2000-01-01T00:00:00Z');
if (($cache['dt'] ?? null) != new DateTimeImmutable('2000-01-01T00:00:00Z')) exit(1);

$cache = [];
$cache['remove'] = new stdClass();
unset($cache['remove']);
if (array_key_exists('remove', $cache)) exit(1);

$cache = [];
$times = 0;
if (!array_key_exists($key, $cache)) {
    $times++;
    $cache[$key] = 'resultOne';
}
if (!array_key_exists($key, $cache)) {
    $times++;
    $cache[$key] = 'resultTwo';
}
if ($cache[$key] !== 'resultOne' || $times !== 1) exit(1);

$cache = [];
$cache[$key] = 0;
if (($cache[$key] ?? 0) !== 0) exit(1);
?>
--EXPECT--
