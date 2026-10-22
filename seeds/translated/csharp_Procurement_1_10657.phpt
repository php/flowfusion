--TEST--
Translated seed (inferredbugs-csharp: Procurement/1)
--FILE--
<?php
$u = 'https://www.pathofexile.com/login';
$ctx = stream_context_create(['http' => ['method' => 'GET', 'header' => "User-Agent: Mozilla/5.0\r\n", 'ignore_errors' => true]]);
$s = @file_get_contents($u, false, $ctx);
if ($s !== false && preg_match('/name=\\"hash\\" value=\\"(?<hash>[a-zA-Z0-9]+)\\"/', $s, $m)) {
    $h = $m['hash'];
    $data = http_build_query(['login_email' => 'a@example.com', 'login_password' => 'x', 'hash' => $h]);
    $ctx = stream_context_create(['http' => ['method' => 'POST', 'header' => "User-Agent: Mozilla/5.0\r\nContent-Type: application/x-www-form-urlencoded\r\n", 'content' => $data, 'ignore_errors' => true, 'follow_location' => 0]]);
    @file_get_contents($u, false, $ctx);
}
?>
--EXPECT--
