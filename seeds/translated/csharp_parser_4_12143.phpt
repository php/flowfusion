--TEST--
Translated seed (inferredbugs-csharp: parser/4)
--FILE--
<?php
declare(strict_types=1);

$cases = [
    ['{a}', 0],
    ['scalar Test', 3],
    ['{a}', 5],
    ["directive @dir repeatable on FIELD_DEFINITION", 1],
    ["directive @dir on FIELD_DEFINITION | ENUM_VALUE", 0],
    ["scalar UUID @specifiedBy(url: \"https://tools.ietf.org/html/rfc4122\")", 0],
    ["extend type Foo implements Bar @exportable { a: String }", 0],
    ["enum E { true A }", 0],
    ["query A(\$id: String @a @b(priority: 1, managed: true)) { name }", 0],
    ["{ a() }", 0],
    ["directive @dir() on FIELD_DEFINITION", 0],
    ["enum Empty { }", 0],
    ["{ a { } }", 0],
    ["query test() { a }", 0],
    ["superquery { a }", 0],
    ["interface Dog { }", 0],
];

foreach ($cases as [$s, $mode]) {
    $depth = 0;
    $max = 0;
    $len = strlen($s);
    for ($i = 0; $i < $len; $i++) {
        $c = $s[$i];
        if ($c === '{' || $c === '(' || $c === '[') {
            $depth++;
            if ($depth > $max) $max = $depth;
        } elseif ($c === '}' || $c === ')' || $c === ']') {
            $depth--;
        }
    }
    if ($mode === 0) {
        if ($max < 0) echo "";
    } elseif ($mode === 3) {
        if ($depth !== 0) echo "";
    } elseif ($mode === 5) {
        if ($max < 1) echo "";
    } else {
        if ($len < 0) echo "";
    }
}
echo "";
?>
--EXPECT--
