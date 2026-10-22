--TEST--
Translated seed (inferredbugs-java: GCViewer/16)
--FILE--
<?php

$lines = [
    "[0.731s][info][gc           ] GC(0) Pause Init Mark 1.021ms",
    "[0.735s][info][gc           ] GC(0) Concurrent marking 74M->74M(128M) 3.688ms",
    "[43.948s][info][gc             ] GC(831) Pause Full (Allocation Failure) 7943M->6013M(8192M) 14289.335ms"
];

$pattern = "/^(?:\\[(?<time>[0-9-T:.]*)])?(?:\\[(?<uptime>[^s]*)s])?\\[(?<level>[^]]+)]\\[(?:(?<tags>[^] ]+)[ ]*)][ ]GC\\((?<gcnumber>[0-9]+)\\)[ ](?<type>([-.a-zA-Z ()]+|[a-zA-Z1 ()]+))(?:[ ](?<tail>[0-9]{1}.*))?$/";

foreach ($lines as $line) {
    if (preg_match($pattern, $line, $matches)) {
        $gcNumber = (int)$matches['gcnumber'];
        $uptime = isset($matches['uptime']) ? (float)$matches['uptime'] : null;
        $level = $matches['level'];
        $tags = $matches['tags'];
        $tail = isset($matches['tail']) ? $matches['tail'] : null;

        echo "Parsed GC Event:\n";
        echo "GC Number: $gcNumber\n";
        echo "Uptime: " . ($uptime !== null ? $uptime . 's' : 'N/A') . "\n";
        echo "Level: $level\n";
        echo "Tags: $tags\n";
        echo "Tail: " . ($tail !== null ? $tail : 'N/A') . "\n\n";
    }
}
--EXPECT--
