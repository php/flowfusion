--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/5)
--FILE--
<?php
$windows = [];
$master = $primary = $current = $glwin = null;
$glcontext = 0;
$vsync = false;

$create = function ($caption, $x, $y, $w, $h, $flags = 0) {
    static $next = 1;
    return (object)[
        'id' => $next++,
        'caption' => $caption ?? '',
        'x' => $x, 'y' => $y, 'w' => $w, 'h' => $h,
        'flags' => $flags,
        'sync' => (bool)($flags & 1),
        'native' => false
    ];
};

$designateCurrentOpenGLWindow = function ($window, $context) use (&$glwin, &$glcontext, &$vsync, $master) {
    $win = $window ?? $master;
    $glwin = $win;
    $glcontext = $context;
    $vsync = $win->sync;
};

$createWindow = function ($caption, $x, $y, $w, $h, $flags = 0) use (&$windows, $create) {
    $win = $create($caption, $x, $y, $w, $h, $flags);
    $windows[] = $win;
    return $win;
};

$master = $createWindow('', 0, 0, 0, 0, 1);
$primary = $createWindow('p', 10, 20, 640, 480, 1);
$current = $primary;
$glwin = $primary;
$glcontext = 1234;
$vsync = $primary->sync;

$native = $createWindow('n', 0, 0, 1, 1, 0);
$native->native = true;

foreach ($windows as $w) {
    if ($w->id === $primary->id) {
        $current = $w;
        break;
    }
}

$found = null;
foreach ($windows as $w) {
    if ($w->id === $primary->id) {
        $found = $w;
        break;
    }
}

if ($found !== null) {
    $ptr = spl_object_id($found);
}

if (count($windows) > 0) {
    $designateCurrentOpenGLWindow($current, $glcontext);
}

$toDestroy = $native;
for ($i = 0; $i < count($windows); $i++) {
    if ($windows[$i] === $toDestroy) {
        array_splice($windows, $i, 1);
        break;
    }
}

if ($current === $toDestroy) {
    $current = null;
}

if ($primary === $toDestroy) {
    $primary = null;
}

if ($glwin === $toDestroy && $glcontext !== 0) {
    $designateCurrentOpenGLWindow(null, $glcontext);
}

echo count($windows), "\n";
?>
--EXPECT--
