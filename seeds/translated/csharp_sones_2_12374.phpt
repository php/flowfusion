--TEST--
Translated seed (inferredbugs-csharp: sones/2)
--FILE--
<?php
$host = 'localhost';
if (strpos($host, 'http://') !== 0) {
    $host = 'http://' . $host;
}
$port = 9975;
$gqlUri = $host . ':' . $port . '/gql';
$query = 'a b&c=d?x=%20';
$fullUri = $gqlUri . '?' . rawurlencode($query);
parse_url($fullUri);
http_build_query(['q' => $query], '', '&', PHP_QUERY_RFC3986);
echo $fullUri, PHP_EOL;
?>
--EXPECT--
