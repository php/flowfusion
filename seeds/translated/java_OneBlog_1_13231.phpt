--TEST--
Translated seed (inferredbugs-java: OneBlog/1)
--FILE--
<?php
class Comment {
    public $content;
    public $nickname;
    public $status;
    
    public function __construct($content, $nickname) {
        $this->content = $content;
        $this->nickname = $nickname;
        $this->status = "VERIFYING";
    }
}

function simulateComment($comment) {
    if (empty($comment->nickname)) {
        die("Nickname is required.");
    }
    if (empty($comment->content)) {
        die("Content cannot be empty.");
    }
    if (strlen($comment->content) > 255) {
        die("Content exceeds maximum length.");
    }
    if (str_starts_with($comment->content, "<script>")) {
        die("Invalid content: script tags are not allowed.");
    }
    // Simulating database insert
    return $comment;
}

// Simulating edge case: maximum content length
$comment = new Comment(str_repeat("a", 256), "User");
simulateComment($comment);

$comment = new Comment("Valid content", "");
simulateComment($comment);

$comment = new Comment("<script>alert('XSS')</script>", "User");
simulateComment($comment);

$comment = new Comment("This is a valid comment", "User");
$insertedComment = simulateComment($comment);
echo "Comment inserted successfully: " . $insertedComment->content;
?>
--EXPECT--
