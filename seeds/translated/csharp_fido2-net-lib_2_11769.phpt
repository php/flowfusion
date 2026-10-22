--TEST--
Translated seed (inferredbugs-csharp: fido2-net-lib/2)
--FILE--
<?php
$bytes = [];
foreach (str_split(str_replace('-', '', 'F1-3C-7F-08-3C-A2-29-E0-B4-03-E8-87-34-6E-FC-7F-98-53-10-3A-30-91-75-67-39-7A-D1-D8-AF-87-04-61-87-EF-95-31-85-60-F3-5A-1A-2A-CF-7D-B0-1D-06-B9-69-F9-AB-F4-EC-F3-07-3E-CF-0F-71-E8-84-E8-41-20'), 2) as $h) $bytes[] = hexdec($h);

$json = '{"bytes":[1,2,3]}';
$data = json_decode($json, true);
$bs = $data['bytes'];
$s = $bs;

$authData = random_bytes(37);
$authData[32] = "\x41";
$authData[33] = "\x01";
$authData[34] = "\x00";
$authData[35] = "\x00";
$authData[36] = "\x00";

$rpIdHash = substr($authData, 0, 32);
$flags = ord($authData[32]);
$signCount = unpack('N', substr($authData, 33, 4))[1];

$aaguid = substr(random_bytes(16), 0, 16);
$credentialPublicKey = hex2bin('A5010203262001215820591DC1E104EA65A220060F0E82B3DFCF350286B8B27F339139B468F28A601BDD225820B651B7DA5C6B6A783C334B688D8277E822F554CBF6A594B3A91FCD7EF1BCF1B0');

echo '';
?>
--EXPECT--
