--TEST--
Translated seed (inferredbugs-csharp: hass-workstation-service/1)
--FILE--
<?php
$mode = $argv[1] ?? 'registry';

if ($mode === 'registry') {
    $data = [
        'a' => ['LastUsedTimeStop' => 0],
        'b' => ['LastUsedTimeStop' => '123'],
        'c' => [],
    ];
    $active = false;
    foreach ($data as $subKey) {
        if (array_key_exists('LastUsedTimeStop', $subKey)) {
            $endTime = is_int($subKey['LastUsedTimeStop']) ? $subKey['LastUsedTimeStop'] : -1;
            if ($endTime <= 0) {
                $active = true;
                break;
            }
        }
    }
    echo $active ? "True" : "False";
} elseif ($mode === 'opencv') {
    try {
        $h = @fopen('php://memory', 'r');
        if ($h === false) {
            echo "False";
        } else {
            $buf = fread($h, 1);
            fclose($h);
            echo ($buf === '' || $buf === false) ? "True" : "False";
        }
    } catch (Throwable $e) {
        echo "False";
    }
} else {
    echo "Error";
}
?>
--EXPECT--
