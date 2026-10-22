--TEST--
Translated seed (inferredbugs-csharp: Procurement/2)
--FILE--
<?php
$u = 'https://www.pathofexile.com/login';
$ctx = stream_context_create(['http' => ['method' => 'GET', 'header' => "User-Agent: Mozilla/4.0\r\n", 'ignore_errors' => true]]);
$d = @file_get_contents($u, false, $ctx);
if ($d !== false && preg_match('/name=\\"hash\\" value=\\"([a-zA-Z0-9]+)\\"/', $d, $m)) {
    $hash = $m[1];
    $post = http_build_query(['login_email' => 'a@b.c', 'login_password' => 'x', 'hash' => $hash]);
    $ctx = stream_context_create(['http' => ['method' => 'POST', 'header' => "User-Agent: Mozilla/4.0\r\nContent-Type: application/x-www-form-urlencoded\r\n", 'content' => $post, 'ignore_errors' => true]]);
    @file_get_contents($u, false, $ctx);
}
$urls = [
    'http://www.pathofexile.com/character-window/get-characters',
    'http://www.pathofexile.com/character-window/get-stash-items?league=Standard&tabs=1&tabIndex=0',
    'http://www.pathofexile.com/character-window/get-items?character=Test',
];
foreach ($urls as $x) {
    @file_get_contents($x, false, stream_context_create(['http' => ['method' => 'GET', 'header' => "User-Agent: Mozilla/4.0\r\n", 'ignore_errors' => true]]));
}
?>
--EXPECT--
