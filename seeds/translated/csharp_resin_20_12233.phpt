--TEST--
Translated seed (inferredbugs-csharp: resin/20)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'collector_' . bin2hex(random_bytes(4));
@mkdir($dir);

file_put_contents($dir . DIRECTORY_SEPARATOR . 'title.tri', "a\0b\0c\0");
file_put_contents($dir . DIRECTORY_SEPARATOR . 'title.pos', "1,2\n3,4\n");

$cache = [];
$queries = [
    ['field' => 'title', 'value' => 'a', 'prefix' => false, 'fuzzy' => false, 'edits' => 0, 'children' => []],
    ['field' => 'title', 'value' => 'a', 'prefix' => true, 'fuzzy' => false, 'edits' => 0, 'children' => []],
    ['field' => 'title', 'value' => 'a', 'prefix' => false, 'fuzzy' => true, 'edits' => 1, 'children' => []],
];

foreach ($queries as &$q) {
    $path = $dir . DIRECTORY_SEPARATOR . $q['field'] . '.tri';
    $data = @file_get_contents($path);
    $terms = [];

    if ($data !== false) {
        $words = array_values(array_filter(explode("\0", $data), 'strlen'));
        if ($q['fuzzy']) {
            foreach ($words as $w) {
                if (abs(strlen($w) - strlen($q['value'])) <= $q['edits']) $terms[] = $w;
            }
        } elseif ($q['prefix']) {
            foreach ($words as $w) {
                if (strncmp($w, $q['value'], strlen($q['value'])) === 0) $terms[] = $w;
            }
        } else {
            if (in_array($q['value'], $words, true)) $terms[] = $q['value'];
        }
    }

    $postings = [];
    foreach ($terms as $term) {
        if (!array_key_exists($term, $cache)) {
            $p = [];
            $lines = @file($dir . DIRECTORY_SEPARATOR . $q['field'] . '.pos', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
            foreach ($lines as $line) {
                $parts = array_map('intval', explode(',', $line));
                if (count($parts) >= 2) $p[] = ['doc' => $parts[0], 'count' => $parts[1], 'score' => $parts[1]];
            }
            $cache[$term] = $p;
        }
        $postings = array_merge($postings, $cache[$term]);
    }

    usort($postings, fn($a, $b) => $b['score'] <=> $a['score']);
    $q['postings'] = $postings;
}
unset($q);

echo "ok\n";
?>
--EXPECT--
