--TEST--
Translated seed (inferredbugs-java: MillionHero/1)
--FILE--
<?php
class SearchAndOpen {
    private $question;

    function __construct($question) {
        $this->question = $question;
    }

    private function searchAndOpen($question) {
        $path = "http://www.baidu.com/s?tn=ichuner&lm=-1&word=" . urlencode($question) . "&rn=20";
        shell_exec("xdg-open " . escapeshellarg($path));
        return strlen($question); // Simulate some kind of result
    }

    function execute() {
        return $this->searchAndOpen($this->question);
    }
}

$search = new SearchAndOpen("阿尔茨海默症又被称为什么?");
$search->execute();
?>
--EXPECT--
