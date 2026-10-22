--TEST--
Translated seed (inferredbugs-csharp: SabberStone/2)
--FILE--
<?php
$game = new class {
    public function mainEnd() {
        $this->ended = true;
    }
};

$controller = new class($game) {
    public $game;
    public function __construct($game) {
        $this->game = $game;
    }
};

$task = new class($controller) {
    public $controller;
    public $game;
    public $playerTaskType;
    public function __construct($controller) {
        $this->playerTaskType = 'CONCEDE';
        $this->game = $controller->game;
        $this->controller = $controller;
    }
    public function process() {
        $this->controller->game->mainEnd();
        throw new Exception('Not implemented');
    }
};

try {
    $task->process();
} catch (Exception $e) {
}
?>
--EXPECT--
