--TEST--
Translated seed (inferredbugs-csharp: sentry-dotnet/4)
--FILE--
<?php
$options = ['attach_stacktrace' => false, 'in_app_exclude' => [], 'in_app_include' => []];

$create = function (?Throwable $e = null) use (&$options) {
    if ($e === null && empty($options['attach_stacktrace'])) {
        return null;
    }
    $trace = $e ? $e->getTrace() : debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $frames = [];
    foreach ($trace as $f) {
        $func = $f['function'] ?? null;
        $class = $f['class'] ?? null;
        $file = $f['file'] ?? null;
        $inApp = true;
        $ns = $class ? trim(strtok($class, '\\') ?: '', '\\') : '';
        foreach ($options['in_app_exclude'] as $x) {
            if ($x !== '' && str_starts_with($class ?? '', $x)) $inApp = false;
        }
        foreach ($options['in_app_include'] as $x) {
            if ($x !== '' && str_starts_with($class ?? '', $x)) $inApp = true;
        }
        $frames[] = ['function' => $func, 'module' => $file, 'in_app' => $inApp];
    }
    return ['frames' => $frames];
};

$frame = ['function' => null, 'module' => null];
if ($frame['function'] === null) $frame['function'] = null;
if ($frame['module'] === null) $frame['module'] = null;

$stack = $create();
$options['attach_stacktrace'] = true;
$stack = $create();
if ($stack !== null) {
    $last = $stack['frames'][array_key_last($stack['frames'])]['function'] ?? null;
}

try {
    throw new Exception();
} catch (Throwable $e) {
    $stack = $create($e);
}

$options['attach_stacktrace'] = true;
try {
    throw new Exception();
} catch (Throwable $e) {
    $stack = $create($e);
}

$options['in_app_exclude'][] = __NAMESPACE__;
$stack = $create();
$options['in_app_include'][] = __NAMESPACE__;
$stack = $create();

echo "";
?>
--EXPECT--
