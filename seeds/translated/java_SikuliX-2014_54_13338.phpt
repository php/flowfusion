--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/54)
--FILE--
<?php

class Mouse {
    private static $mouse = null;
    private $lastPos;
    private $isSuspended = false;
    
    private function __construct() {
        $this->lastPos = ['x' => 0, 'y' => 0];
    }

    public static function getInstance() {
        if (self::$mouse === null) {
            self::$mouse = new Mouse();
        }
        return self::$mouse;
    }

    public function isSuspended() {
        return $this->isSuspended;
    }

    public function useMouse() {
        // Simulated use of mouse
    }

    public function let() {
        // Simulated release of mouse
    }
    
    public function setPosition($x, $y) {
        $this->lastPos = ['x' => $x, 'y' => $y];
    }

    public function getPosition() {
        return $this->lastPos;
    }

    public static function click($x, $y) {
        $mouse = self::getInstance();
        if ($mouse->isSuspended()) return;

        $mouse->useMouse();
        // Simulate click at position ($x, $y)
        $mouse->setPosition($x, $y);
        $mouse->let();
    }

    public static function move($x, $y) {
        $mouse = self::getInstance();
        if ($mouse->isSuspended()) return;

        $mouse->useMouse();
        // Simulate move to position ($x, $y)
        $mouse->setPosition($x, $y);
        $mouse->let();
    }

    public function suspend() {
        $this->isSuspended = true;
    }

    public function resume() {
        $this->isSuspended = false;
    }
}

$mouse = Mouse::getInstance();
$mouse->move(100, 150);
$mouse->click(100, 150);
$mouse->suspend();
$mouse->click(100, 150); // Should not register as suspended
$mouse->resume();
$mouse->click(200, 250);
$mouse->move(200, 250);
--EXPECT--
