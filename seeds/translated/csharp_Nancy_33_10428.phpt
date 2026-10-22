--TEST--
Translated seed (inferredbugs-csharp: Nancy/33)
--FILE--
<?php
$bodyContent = 'name=John+Doe&gender=male&family=5&city=kent&city=miami&other=abc%0D%0Adef&nickname=J%26D';
$headers = ['content-type' => ['application/x-www-form-urlencoded']];
$body = fopen('php://memory', 'r+');
fwrite($body, $bodyContent);
rewind($body);

parse_str($bodyContent, $form);
$form['name'];
$form = null;
parse_str($bodyContent, $form);
$form['name'];
?>
--EXPECT--
