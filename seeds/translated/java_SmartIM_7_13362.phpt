--TEST--
Translated seed (inferredbugs-java: SmartIM/7)
--FILE--
<?php

$content = file_get_contents("init.xml");
$handler = new class($content) {
    private $content;
    public function __construct($content) {
        $this->content = $content;
    }
    public function getRecents() {
        return "filehelper,anotherUser";
    }
};

$recents = $handler->getRecents();
echo $recents . PHP_EOL;
$array = explode(",", $recents);
echo json_encode($array) . PHP_EOL;
if (isset($array[0]) && $array[0] === "filehelper") {
    // Do nothing
}
--EXPECT--
