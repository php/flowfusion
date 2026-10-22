--TEST--
Translated seed (inferredbugs-csharp: Stardust/1)
--FILE--
<?php
date_default_timezone_set('UTC');

$sessionTimeout = 1;
$machineName = php_uname('n');

$nodes = [
    (object)['OnlineTime' => 0, 'name' => 'node1'],
    (object)['OnlineTime' => 10, 'name' => 'node2'],
];

$records = [
    (object)[
        'Node' => $nodes[0],
        'CreateTime' => new DateTimeImmutable('2001-01-01 00:00:00'),
        'UpdateTime' => new DateTimeImmutable('2001-01-01 00:00:05'),
        'CreateIP' => '127.0.0.1',
    ],
    (object)[
        'Node' => null,
        'CreateTime' => new DateTimeImmutable('1999-12-31 23:59:59'),
        'UpdateTime' => new DateTimeImmutable('2001-01-01 00:00:01'),
        'CreateIP' => '127.0.0.2',
    ],
];

if ($sessionTimeout > 0) {
    foreach ($records as $olt) {
        $node = $olt->Node ?? null;
        $msg = sprintf('[%s]登录于%s，最后活跃于%s', $node ? $node->name : '', $olt->CreateTime->format('Y-m-d H:i:s'), $olt->UpdateTime->format('Y-m-d H:i:s'));
        $history = [
            'node' => $node,
            'reason' => '超时下线',
            'machine' => $machineName,
            'ip' => $olt->CreateIP,
            'message' => $msg,
        ];

        if ($node !== null) {
            if ((int)$olt->CreateTime->format('Y') > 2000 && (int)$olt->UpdateTime->format('Y') > 2000) {
                $node->OnlineTime += (int)(($olt->UpdateTime->getTimestamp() - $olt->CreateTime->getTimestamp()));
            }
        }
    }
}

echo "done\n";
--EXPECT--
