--TEST--
Translated seed (inferredbugs-csharp: PowerArgs/4)
--FILE--
<?php
class MainCharacter {}
class ShooterKeyMap {
    public function generateKeyMap() {
        return ['up' => 'W', 'down' => 'S', 'left' => 'A', 'right' => 'D'];
    }
}
class HeadsUpDisplay {
    public function __construct($app) {}
    public function centerHorizontally() { return $this; }
    public function dockToBottom() { return $this; }
}
class LayoutRoot {
    public array $items = [];
    public function add($item) { $this->items[] = $item; return $item; }
}
class Scene {
    public array $items = [];
    public function add($item) { $this->items[] = $item; }
}
class KeyboardInput {
    public $keyMap;
    public function updateKeyboardMappings() {}
}
class ShooterGameApp {
    public $mainCharacter;
    public $keyMap;
    public $scene;
    public $keyboardInput;
    public $layoutRoot;
    public function __construct() {
        $this->scene = new Scene();
        $this->keyboardInput = new KeyboardInput();
        $this->layoutRoot = new LayoutRoot();
    }
    public function onAppInitialize() {
        $this->configureHeadsUpDisplay();
    }
    public function onSceneInitialize() {
        $this->mainCharacter = new MainCharacter();
        $this->scene->add($this->mainCharacter);
        $this->keyMap = new ShooterKeyMap();
        $this->keyboardInput->keyMap = $this->keyMap->generateKeyMap();
        $this->keyboardInput->updateKeyboardMappings();
    }
    private function configureHeadsUpDisplay() {
        $this->layoutRoot->add((new HeadsUpDisplay($this))->centerHorizontally()->dockToBottom());
    }
}
$app = new ShooterGameApp();
$app->onAppInitialize();
$app->onSceneInitialize();
?>
--EXPECT--
