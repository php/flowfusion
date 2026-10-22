--TEST--
Translated seed (inferredbugs-java: SSM/1)
--FILE--
<?php

$redis = new Redis();
$redis->connect("27.126.180.210", 6379);
$redis->set("name", "陈杰");
echo $redis->get("name") . PHP_EOL;

$contentList = [];
$contentList1 = [];
$contentList[] = (object) ['content' => 'Sample Content'];
$contentList1[] = (object) ['content' => 'Sample Content'];

echo ($contentList === $contentList1) . PHP_EOL;
echo $contentList[0]->content . "-----------" . PHP_EOL;

?>
--EXPECT--
