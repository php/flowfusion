--TEST--
Translated seed (inferredbugs-java: closure-compiler/20)
--FILE--
<?php
$input = "Hello, World!";
$length = strlen($input);
$output = $input;
for ($i = 0; $i <= $length; $i++) {
    $output[$i] = $input[$i];
}
echo $output;
--EXPECT--
