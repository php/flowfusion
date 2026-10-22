--TEST--
Translated seed (inferredbugs-csharp: ini-parser/4)
--FILE--
<?php
$ini = <<<INI
; first
[sec]
a=1
a=2
; last
INI;

$lines = preg_split("/\r\n|\n|\r/", $ini);
$sections = [];
$global = [];
$currentSection = null;
$pendingComments = [];

foreach ($lines as $line) {
    $trim = trim($line);
    if ($trim === '') continue;

    if ($trim[0] === ';') {
        $pendingComments[] = ltrim(substr($trim, 1));
        continue;
    }

    if ($trim[0] === '[') {
        $end = strpos($trim, ']');
        if ($end === false) continue;
        $currentSection = trim(substr($trim, 1, $end - 1));
        if (!isset($sections[$currentSection])) $sections[$currentSection] = ['props' => [], 'comments' => []];
        if ($pendingComments) {
            $sections[$currentSection]['comments'] = array_merge($sections[$currentSection]['comments'], $pendingComments);
            $pendingComments = [];
        }
        continue;
    }

    $eq = strpos($line, '=');
    if ($eq === false) continue;
    $key = trim(substr($line, 0, $eq));
    $value = trim(substr($line, $eq + 1));
    if ($key === '') continue;

    if ($currentSection === null) {
        $global[$key] = $value;
    } else {
        if (array_key_exists($key, $sections[$currentSection]['props'])) {
            $sections[$currentSection]['props'][$key] .= ',' . $value;
        } else {
            $sections[$currentSection]['props'][$key] = $value;
        }
        if ($pendingComments) {
            $sections[$currentSection]['props'][$key . "\0comments"] = $pendingComments;
            $pendingComments = [];
        }
    }
}

if ($pendingComments) {
    if ($currentSection !== null && isset($sections[$currentSection])) {
        $sections[$currentSection]['comments'] = array_merge($sections[$currentSection]['comments'], $pendingComments);
    } elseif ($global) {
        $lastKey = array_key_last($global);
        $global[$lastKey] .= '';
    }
}

echo "OK\n";
--EXPECT--
