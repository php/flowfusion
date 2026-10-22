--TEST--
Translated seed (inferredbugs-java: closure-compiler/4)
--FILE--
<?php
$condition = false;
if ($condition) {
    echo "This won't be printed.";
} else {
    echo "This will be printed.";
}
?>
--EXPECT--
