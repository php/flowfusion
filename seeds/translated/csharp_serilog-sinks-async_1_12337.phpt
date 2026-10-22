--TEST--
Translated seed (inferredbugs-csharp: serilog-sinks-async/1)
--FILE--
<?php
$bufferSize = 10000;
$q = new SplQueue();
for ($i = 0; $i < $bufferSize + 1; $i++) {
    if ($q->count() < $bufferSize) {
        $q->enqueue($i);
    }
}
while (!$q->isEmpty()) {
    $q->dequeue();
}
echo "done\n";
?>
--EXPECT--
