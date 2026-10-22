--TEST--
Translated seed (inferredbugs-java: SmartIM/1)
--FILE--
<?php

class SmartQQClient {
    private $workDir;
    
    public function setWorkDir($dir) {
        $this->workDir = $dir;
    }
    
    public function login() {
        // Simulate login
    }
    
    public function getFriendListWithCategory() {
        return [
            (object)[
                'name' => 'Family',
                'friends' => [
                    (object)['nickname' => 'Alice'],
                    (object)['nickname' => 'Bob']
                ]
            ],
            (object)[
                'name' => 'Friends',
                'friends' => [
                    (object)['nickname' => 'Charlie'],
                    (object)['nickname' => 'David']
                ]
            ]
        ];
    }
    
    public function start() {
        // Simulate message receiving
    }
}

$client = new SmartQQClient();
$client->setWorkDir(realpath('target'));
$client->login();
$categories = $client->getFriendListWithCategory();
foreach ($categories as $category) {
    echo $category->name . "\n";
    foreach ($category->friends as $friend) {
        echo "————" . $friend->nickname . "\n";
    }
}
$client->start();
--EXPECT--
