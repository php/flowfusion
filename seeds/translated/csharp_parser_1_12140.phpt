--TEST--
Translated seed (inferredbugs-csharp: parser/1)
--FILE--
<?php
$inputs = [
    "{ field }",
    "mutation Foo { field }",
    "directive @dir repeatable on FIELD_DEFINITION",
    "union Animal = Cat | Dog",
];

foreach ($inputs as $s) {
    preg_match_all('/[A-Za-z_][A-Za-z0-9_]*/', $s, $m);
    $tokens = $m[0];
    if (!$tokens) continue;
    $repeatable = in_array('repeatable', $tokens, true);
    $isMutation = in_array('mutation', $tokens, true);
    $hasDirective = in_array('directive', $tokens, true);
    $hasUnion = in_array('union', $tokens, true);
    if ($hasDirective) {
        $ok = $repeatable || !in_array('repeatable', $tokens, true);
    } elseif ($hasUnion) {
        $ok = true;
    } else {
        $ok = $isMutation || in_array('field', $tokens, true);
    }
    if (!$ok) exit(1);
}
?>
--EXPECT--
