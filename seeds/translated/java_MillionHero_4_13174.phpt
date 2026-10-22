--TEST--
Translated seed (inferredbugs-java: MillionHero/4)
--FILE--
<?php

class Search {
    private $question;

    public function __construct($question) {
        $this->question = $question;
    }

    public function search() {
        $path = "http://www.baidu.com/s?tn=ichuner&lm=-1&word=" . urlencode($this->question) . "&rn=1";
        $findIt = false;
        $line = null;

        while (!$findIt) {
            $content = file_get_contents($path);
            if ($content === false) {
                break;
            }
            $lines = explode("\n", $content);
            foreach ($lines as $line) {
                if (strpos($line, "百度为您找到相关结果约") !== false) {
                    $findIt = true;
                    $start = strpos($line, "百度为您找到相关结果约") + 11;
                    $line = substr($line, $start);
                    $end = strpos($line, "个");
                    $line = substr($line, 0, $end);
                    break;
                }
            }
        }
        $line = str_replace(",", "", $line);
        return (int)$line;
    }
}

$search = new Search("阿尔茨海默症又被称为什么?");
echo $search->search();

?>
--EXPECT--
