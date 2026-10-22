--TEST--
Translated seed (inferredbugs-java: blynk-server/1)
--FILE--
<?php

class GetTokenHandler {
    private $userRegistry;

    public function __construct($userRegistry) {
        $this->userRegistry = $userRegistry;
    }

    public function channelRead($dashBoardIdString) {
        $dashBoardId = @intval($dashBoardIdString);

        if ($dashBoardId === 0 && $dashBoardIdString !== '0') {
            echo "Dash board id {$dashBoardIdString} not valid.\n";
            return;
        }

        $token = $this->userRegistry['tokens'][$dashBoardId] ?? null;
        echo "Token: " . ($token ? $token : "not found") . "\n";
    }
}

$userRegistry = ['tokens' => [1 => 'token1', 2 => 'token2']];
$handler = new GetTokenHandler($userRegistry);
$handler->channelRead("1");
$handler->channelRead("abc");
--EXPECT--
