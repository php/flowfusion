--TEST--
Translated seed (inferredbugs-csharp: PatreonDownloader/1)
--FILE--
<?php
$pattern = "%PUBLISHEDAT%/%POSTTITLE%/%POSTID%";
$publishedAt = new DateTimeImmutable('2024-01-02T03:04:05Z');
$postId = "12345";
$postTitle = trim("Hello...   ");
while (strlen($postTitle) > 1 && substr($postTitle, -1) === '.') {
    $postTitle = rtrim(substr($postTitle, 0, -1));
}
$ret = strtolower($pattern);
$ret = str_replace("%publishedat%", $publishedAt->format('Y-m-d'), $ret);
$ret = str_replace("%posttitle%", $postTitle, $ret);
$ret = str_replace("%postid%", $postId, $ret);
$ret = preg_replace('~[<>:"/\\\\|?*\x00-\x1F]+~', '_', $ret);
echo $ret;
?>
--EXPECT--
