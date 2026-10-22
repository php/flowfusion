--TEST--
Translated seed (inferredbugs-csharp: HyperFastCgi/1)
--FILE--
<?php
$socket = $argv[1] ?? 'tcp';
$parts = explode(':', $socket, 3);

switch (strtolower($parts[0])) {
    case 'unix':
    case 'file':
        $path = $parts[1] ?? null;
        break;
    case 'tcp':
        $port = isset($parts[2]) ? $parts[2] : ($parts[1] ?? '0');
        $address = $parts[2] ?? '127.0.0.1';
        if (!filter_var($address, FILTER_VALIDATE_IP)) {
            fwrite(STDERR, "bad address\n");
            exit(1);
        }
        break;
    default:
        fwrite(STDERR, "unsupported socket type\n");
        exit(1);
}

$min = $argv[2] ?? '0,0';
$max = $argv[3] ?? '0,0';
$minParts = explode(',', $min, 2);
$maxParts = explode(',', $max, 2);

$minWorker = (int)$minParts[0];
$minIO = isset($minParts[1]) ? (int)$minParts[1] : 0;
$maxWorker = (int)$maxParts[0];
$maxIO = isset($maxParts[1]) ? (int)$maxParts[1] : 0;

if (($maxWorker !== 0 && $maxWorker < $minWorker) || ($maxIO !== 0 && $maxIO < $minIO)) {
    throw new Exception('min threads must not be greater max threads');
}

$cur = ini_get('zend.max_execution_time');
if ($cur === false) {
    $cur = '0';
}

echo basename($_SERVER['PHP_SELF']) . " " . PHP_VERSION . "\n";
echo "threadpool minw=$minWorker,minio=$minIO,maxw=$maxWorker,maxio=$maxIO\n";
echo "done\n";
?>
--EXPECT--
