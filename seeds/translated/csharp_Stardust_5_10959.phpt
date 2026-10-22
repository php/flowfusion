--TEST--
Translated seed (inferredbugs-csharp: Stardust/5)
--FILE--
<?php
$set = (object)['SessionTimeout' => 1];

$nodes = [
    (object)['OnlineTime' => 0, 'SaveAsync' => function () {}],
    null,
];

$rs = [
    (object)[
        'Node' => $nodes[0],
        'CreateTime' => new DateTime('2001-01-01 00:00:00'),
        'UpdateTime' => new DateTime('2001-01-01 00:00:05'),
        'CreateIP' => '127.0.0.1',
    ],
    (object)[
        'Node' => $nodes[1],
        'CreateTime' => new DateTime('1999-01-01 00:00:00'),
        'UpdateTime' => new DateTime('2001-01-01 00:00:05'),
        'CreateIP' => '127.0.0.1',
    ],
];

if ($set->SessionTimeout > 0) {
    foreach ($rs as $olt) {
        $node = $olt?->Node;
        $msg = '[' . ($node !== null ? 'node' : '') . ']登录于' . $olt->CreateTime->format('Y-m-d H:i:s') . '，最后活跃于' . $olt->UpdateTime->format('Y-m-d H:i:s');
        if ($node !== null && $olt->CreateTime->format('Y') > 2000 && $olt->UpdateTime->format('Y') > 2000) {
            $node->OnlineTime += (int)(($olt->UpdateTime->getTimestamp() - $olt->CreateTime->getTimestamp()));
            ($node->SaveAsync)();
        }
    }
}
?>
--EXPECT--
