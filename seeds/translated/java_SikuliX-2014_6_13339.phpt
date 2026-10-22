--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/6)
--FILE--
<?php
class OverlayCapturePrompt {
    const MIN_DARKER_FACTOR = 0.6;
    const MSG_DISPLAY_TIME = 2000;

    private $srcx, $srcy, $destx, $desty;
    private $canceled = false;
    private $msg;
    private $dragging = false;
    private $rectSelection;

    public function __construct() {
        $this->rectSelection = new stdClass();
        $this->rectSelection->x = 0;
        $this->rectSelection->y = 0;
        $this->rectSelection->width = 0;
        $this->rectSelection->height = 0;
    }

    public function prompt($msg, $delayMS) {
        usleep($delayMS * 1000);
        $this->msg = $msg;
        $this->display();
    }

    private function display() {
        if ($this->msg) {
            echo "Message: " . $this->msg . "\n";
        }
        $this->captureScreen();
        $this->setVisible(true);
    }

    private function captureScreen() {
        $this->srcx = rand(0, 100);
        $this->srcy = rand(0, 100);
        $this->destx = $this->srcx + rand(1, 50);
        $this->desty = $this->srcy + rand(1, 50);
        $this->dragging = true;
        $this->rectSelection->x = min($this->srcx, $this->destx);
        $this->rectSelection->y = min($this->srcy, $this->desty);
        $this->rectSelection->width = abs($this->destx - $this->srcx);
        $this->rectSelection->height = abs($this->desty - $this->srcy);
    }

    public function paint() {
        if ($this->dragging) {
            echo "Drawing selection at: (" . $this->rectSelection->x . ", " . $this->rectSelection->y . ") with width "
                . $this->rectSelection->width . " and height " . $this->rectSelection->height . "\n";
        }
    }

    public function close() {
        echo "Closing overlay...\n";
    }

    public function run() {
        $this->prompt("Capture Overlay Prompt", self::MSG_DISPLAY_TIME / 1000);
        $this->paint();
        $this->close();
    }
}

$overlay = new OverlayCapturePrompt();
$overlay->run();
--EXPECT--
