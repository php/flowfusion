--TEST--
Translated seed (inferredbugs-csharp: SpotifyAPI-NET/1)
--FILE--
<?php
$host = '127.0.0.1';
$oauthKey = '';
$cfidKey = '';

$raw = '{"t":"oauth"}';
$lol = json_decode($raw, true);
$oauthKey = (string)$lol['t'];

$a = '[{"token":"cfid","error":null}]';
$a = str_replace('\\', '', $a);
$d = json_decode($a, true);
if ($d === null) {
    $cfidKey = '';
} elseif (count($d) != 1) {
    throw new Exception("CFID couldn't be loaded");
} elseif ($d[0]['error'] !== null) {
    $cfidKey = '';
} else {
    $cfidKey = $d[0]['token'];
}

$requests = [
    'remote/status.json',
    'simplecsrf/token.json',
    'remote/pause.json?pause=true',
    'remote/pause.json?pause=false',
    'remote/play.json?uri=spotify:track:5Zp4SWOpbuOdnsxLqwgutt',
    'remote/play.json?uri=spotify:track:5Zp4SWOpbuOdnsxLqwgutt?action=queue'
];

foreach ($requests as $request) {
    $parameters = '?&ref=&cors=&_=' . time();
    if (strpos($request, '?') !== false) {
        $parameters = substr($parameters, 1);
    }
    $parameters .= '&oauth=' . $oauthKey;
    $parameters .= '&csrf=' . $cfidKey;
    $parameters .= '&returnafter=-1';
    $parameters .= '&returnon=login%2Clogout%2Cplay%2Cpause%2Cerror%2Cap';
    $url = 'http://' . $host . ':4380/' . $request . $parameters;
    parse_url($url);
}

echo "";
?>
--EXPECT--
