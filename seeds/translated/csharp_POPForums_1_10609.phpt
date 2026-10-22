--TEST--
Translated seed (inferredbugs-csharp: POPForums/1)
--FILE--
<?php
$topicID = 123;
$tenantID = "tenant";
$isForRemoval = false;

$topic = [
    "TopicID" => $topicID,
    "ForumID" => 7,
    "Title" => "Example title",
    "LastPostTime" => date(DATE_ATOM),
    "StartedByName" => "alice",
    "ReplyCount" => 2,
    "ViewCount" => 99,
    "IsClosed" => false,
    "IsPinned" => true,
    "UrlName" => "example-title",
    "LastPostName" => "bob"
];

$posts = [
    ["FullText" => "<p>Hello [b]world[/b]</p>"],
    ["FullText" => "<div>Second &amp; post</div>"]
];

$parsedPosts = [];
foreach ($posts as $x) {
    $parsedText = strip_tags(html_entity_decode($x["FullText"], ENT_QUOTES | ENT_HTML5, "UTF-8"));
    $parsedText = preg_replace('/\[(\/?)[^\]]+\]/', '', $parsedText);
    $parsedPosts[] = $parsedText;
}

$searchTopic = [
    "key" => $tenantID . "-" . $topicID,
    "topicID" => (string)$topic["TopicID"],
    "forumID" => $topic["ForumID"],
    "title" => $topic["Title"],
    "lastPostTime" => $topic["LastPostTime"],
    "startedByName" => $topic["StartedByName"],
    "replies" => $topic["ReplyCount"],
    "views" => $topic["ViewCount"],
    "isClosed" => $topic["IsClosed"],
    "isPinned" => $topic["IsPinned"],
    "urlName" => $topic["UrlName"],
    "lastPostName" => $topic["LastPostName"],
    "posts" => $parsedPosts,
    "tenantID" => $tenantID
];

$batch = [
    ["action" => "upload", "document" => $searchTopic]
];

if ($isForRemoval) {
    $batch = [
        ["action" => "delete", "field" => "key", "value" => $tenantID . "-" . $topicID]
    ];
}

json_encode($batch, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
--EXPECT--
