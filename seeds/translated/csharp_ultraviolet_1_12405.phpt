--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/1)
--FILE--
<?php
$windows = [];
$master = null;
$primary = null;
$current = null;
$glwin = null;
$vsync = false;

$create = function ($caption, $x, $y, $w, $h, $flags = 0) use (&$windows) {
    $win = (object)['id' => count($windows) + 1, 'native' => false, 'vsync' => (bool)($flags & 1)];
    $windows[] = $win;
    return $win;
};

$designateCurrentOpenGLWindow = function ($window) use (&$glwin, &$vsync, $master) {
    $win = $window ?? $master;
    $glwin = $win;
    $vsync = $win->vsync;
};

$designateCurrent = function ($window, $context = null) use (&$current, &$glwin, &$vsync, &$windows, $designateCurrentOpenGLWindow) {
    if ($current !== $window) {
        $current = $window;
        if ($window !== null && ($window !== $glwin || $window->vsync !== $vsync)) {
            $designateCurrentOpenGLWindow($window);
        }
    }
    if (count($windows) === 0) {
        $designateCurrentOpenGLWindow(null);
    }
};

$designatePrimary = function ($window) use (&$primary, &$windows) {
    if ($window !== null && !in_array($window, $windows, true)) {
        throw new Exception('InvalidResource');
    }
    $primary = $window;
};

$master = (object)['id' => 0, 'native' => false, 'vsync' => false];
$windows[] = $master;
$designateCurrentOpenGLWindow(null);

$w1 = $create('a', 0, 0, 10, 10, 1);
$w2 = $create('b', 1, 1, 20, 20, 0);
$designatePrimary($w1);
$designateCurrent($w1);
$designateCurrent($w2);
$idx = array_search($w1, $windows, true);
if ($idx !== false) {
    array_splice($windows, $idx, 1);
}
if ($primary === $w1) {
    $designatePrimary(null);
}
$designateCurrent($w2);
$windows = [];
$designateCurrent(null);
echo "ok\n";
?>
--EXPECT--
