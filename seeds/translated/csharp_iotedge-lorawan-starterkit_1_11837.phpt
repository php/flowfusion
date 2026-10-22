--TEST--
Translated seed (inferredbugs-csharp: iotedge-lorawan-starterkit/1)
--FILE--
<?php
$cfg = (object)[
    'logToUdp' => true,
    'logToUdpAddress' => '',
    'logToUdpPort' => 0,
    'logToConsole' => true,
    'logLevel' => 1
];

$udp = null;
$endpoint = null;

if ($cfg->logToUdp) {
    try {
        if ($cfg->logToUdpAddress === '' || $cfg->logToUdpAddress === null) {
            $endpoint = ['255.255.255.255', $cfg->logToUdpPort];
        } else {
            if (filter_var($cfg->logToUdpAddress, FILTER_VALIDATE_IP)) {
                $endpoint = [$cfg->logToUdpAddress, $cfg->logToUdpPort];
            } else {
                $addresses = gethostbynamel($cfg->logToUdpAddress);
                if (!$addresses) {
                    echo date('Y-m-d H:i:s.v') . " Could not resolve ip address from '{$cfg->logToUdpAddress}'\n";
                } else {
                    $endpoint = [$addresses[0], $cfg->logToUdpPort];
                }
            }
        }

        if ($endpoint === null) {
            echo date('Y-m-d H:i:s.v') . " Logging to Udp failed. Could not resolve ip address from '{$cfg->logToUdpAddress}'\n";
        } else {
            $udp = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            socket_set_option($udp, SOL_SOCKET, SO_BROADCAST, 1);
            echo date('Y-m-d H:i:s.v') . " Logging to Udp: {$endpoint[0]}:{$endpoint[1]}\n";
        }
    } catch (Throwable $e) {
        echo date('Y-m-d H:i:s.v') . " Error starting UDP logging: " . $e . "\n";
    }
}

$deviceId = '';
$message = 'hello';
$level = 0;

if ($level >= $cfg->logLevel || $level === 0) {
    $msg = $deviceId === '' || $deviceId === null ? $message : $deviceId . ': ' . $message;
    if ($cfg->logToConsole) {
        echo date('Y-m-d H:i:s.v') . " " . $msg . "\n";
    }
    if ($udp !== null) {
        try {
            socket_sendto($udp, $msg, strlen($msg), 0, $endpoint[0], $endpoint[1]);
        } catch (Throwable $e) {
            echo date('Y-m-d H:i:s.v') . " Error logging to UDP: " . $e . "\n";
        }
    }
}

if ($udp !== null) {
    socket_close($udp);
}
?>
--EXPECT--
