--TEST--
Translated seed (inferredbugs-csharp: resin/18)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'collector_' . bin2hex(random_bytes(4));
mkdir($dir);

file_put_contents($dir . DIRECTORY_SEPARATOR . 'field.tri', "a\0b\0c\0");
file_put_contents($dir . DIRECTORY_SEPARATOR . 'a.pos', "1 2\n3 4\n");
file_put_contents($dir . DIRECTORY_SEPARATOR . 'b.pos', "5 6\n");
file_put_contents($dir . DIRECTORY_SEPARATOR . 'c.pos', "7 8\n9 10\n");

$queries = [
    ['field' => 'field', 'mode' => 'prefix', 'value' => 'a'],
    ['field' => 'field', 'mode' => 'exact', 'value' => 'b'],
    ['field' => 'field', 'mode' => 'prefix', 'value' => 'c'],
];

$trieCache = [];
$postingCache = [];

foreach ($queries as $q) {
    $triFile = $dir . DIRECTORY_SEPARATOR . $q['field'] . '.tri';
    if (!isset($trieCache[$triFile])) {
        $trieCache[$triFile] = file($triFile, FILE_IGNORE_NEW_LINES);
    }
    $terms = [];
    if ($q['mode'] === 'prefix') {
        foreach ($trieCache[$triFile] as $t) {
            if ($t !== '' && $t[0] === $q['value'][0]) $terms[] = $t;
        }
    } else {
        foreach ($trieCache[$triFile] as $t) {
            if ($t === $q['value']) $terms[] = $t;
        }
    }

    $scores = [];
    foreach ($terms as $term) {
        $posFile = $dir . DIRECTORY_SEPARATOR . $term . '.pos';
        if (!isset($postingCache[$posFile])) {
            $postingCache[$posFile] = array_values(array_filter(array_map('trim', file($posFile))));
        }
        foreach ($postingCache[$posFile] as $line) {
            [$doc, $count] = array_map('intval', preg_split('/\s+/', $line));
            $scores[$doc] = ($scores[$doc] ?? 0) + $count;
        }
    }

    arsort($scores);
    foreach ($scores as $doc => $score) {
        echo $q['field'] . ':' . $doc . '=' . $score . "\n";
    }
}
?>
--EXPECT--
