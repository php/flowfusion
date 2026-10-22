--TEST--
Translated seed (inferredbugs-csharp: StarBlog/2)
--FILE--
<?php
$page = 1;
$pageSize = 10;
$id = "0";

$photos = [];
for ($i = 0; $i < $pageSize; $i++) {
    $photos[] = ["Id" => (string)($page * 100 + $i), "Title" => "Photo " . ($page * 100 + $i)];
}

$photo = $photos[array_rand($photos)];
$message = "随机推荐了图片 <b>{$photo["Title"]}</b> 给你~";
$redirect = ["action" => "Photo", "id" => $photo["Id"]];

echo $message . PHP_EOL;
echo $redirect["action"] . ":" . $redirect["id"] . PHP_EOL;
?>
--EXPECT--
