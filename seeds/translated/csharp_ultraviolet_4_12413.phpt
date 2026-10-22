--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/4)
--FILE--
<?php
$windows = [];
$master = null;
$primary = null;
$current = null;
$glwin = null;
$glcontext = 0;
$vsync = false;

$create = function ($caption, $native = false, $sync = false) use (&$windows) {
    $w = (object)['id' => count($windows) + 1, 'caption' => $caption ?? '', 'native' => $native, 'sync' => $sync];
    $windows[] = $w;
    return $w;
};

$master = $create('', false, false);
$primary = $create('primary', false, true);
$current = $primary;
$glwin = $primary;
$vsync = true;

$designateCurrent = function ($window, $context) use (&$current, &$glwin, &$glcontext, &$vsync, &$master, &$windows) {
    if ($current !== $window) {
        $current = $window;
        if ($window !== null && ($window !== $glwin || $window->sync !== $vsync)) {
            $win = $window ?? $master;
            $glwin = $win;
            $glcontext = $context;
            $vsync = $win->sync;
        }
    }
    if (count($windows) === 0) {
        $win = $master;
        $glwin = $win;
        $glcontext = $context;
        $vsync = $win->sync;
    }
};

$a = $create('a', false, false);
$b = $create('b', true, true);
$c = $create('c', false, false);

$designateCurrent($a, 1);
$designateCurrent($b, 2);
$designateCurrent($b, 3);
$designateCurrent(null, 4);

foreach ($windows as $w) {
    if ($w->id === 2) {
        $primary = $w;
        break;
    }
}

for ($i = 0; $i < count($windows); $i++) {
    if ($windows[$i] === $b) {
        array_splice($windows, $i, 1);
        break;
    }
}

$designateCurrent($c, 5);
$designateCurrent($a, 6);

echo count($windows), "\n";
echo $current ? $current->caption : "null", "\n";
echo $glwin ? $glwin->caption : "null", "\n";
echo $primary ? $primary->caption : "null", "\n";
?>
--EXPECT--
