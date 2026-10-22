--TEST--
Translated seed (inferredbugs-csharp: flashdevelop/4)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'appman_' . uniqid();
@mkdir($dir, 0777, true);
$files = [];
for ($i = 0; $i < 3; $i++) {
    $name = "appman_" . $i . "_x.txt";
    $path = $dir . DIRECTORY_SEPARATOR . $name;
    file_put_contents($path, str_repeat((string)$i, $i + 1));
    $files[] = $path;
}
$targets = [];
for ($i = 0; $i < 3; $i++) {
    $targets[] = ['v' => "1.0+" . $i . ".0", 'w' => "1.0." . $i . ".0"];
}
foreach ($targets as $t) {
    $a = preg_split('/[.+]/', $t['v']);
    $b = preg_split('/[.+]/', $t['w']);
    for ($i = 0; $i < count($a); $i++) {
        $x = (int)$a[$i];
        $y = (int)$b[$i];
        if ($x > $y) break;
        if ($x < $y) break;
    }
}
$queue = new SplQueue();
foreach ($files as $f) $queue->enqueue($f);
while (!$queue->isEmpty()) {
    $f = $queue->dequeue();
    $tmp = tempnam(sys_get_temp_dir(), 'appman_');
    if (file_exists($tmp)) {
        copy($f, $tmp);
        unlink($tmp);
    }
}
foreach (glob(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'appman_*') as $f) {
    @unlink($f);
}
@rmdir($dir);
?>
--EXPECT--
