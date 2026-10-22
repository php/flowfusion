--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/14)
--FILE--
<?php
$packetTypeStr = str_repeat("A", 260);
$packetObject = null;
$options = [
    'DataSerializer' => null,
    'DataProcessors' => [],
    'Options' => [
        'ReceiveConfirmationRequired' => true,
        'IncludePacketConstructionTime' => true,
    ],
];

$packetData = $packetObject === null ? '' : serialize($packetObject);
$hashStr = md5($packetData, true);

$packetHeader = [
    'sendingPacketTypeStr' => $packetTypeStr,
    'length' => strlen($packetData),
    'requestReturnPacketTypeStr' => null,
    'receiveConfirmationRequired' => array_key_exists('ReceiveConfirmationRequired', $options['Options']),
    'hashStr' => $hashStr,
    'includePacketConstructionTime' => array_key_exists('IncludePacketConstructionTime', $options['Options']),
    'serializerProcessors' => 'serializer:' . count($options['DataProcessors']),
];

$serialisedHeader = serialize($packetHeader);
$headerBytes = $serialisedHeader;
if (strlen($headerBytes) - 1 > 255) {
    $headerBytes = substr($headerBytes, 0, 256);
}
$headerBytes[0] = chr(strlen($headerBytes) - 1);

echo "";
?>
--EXPECT--
