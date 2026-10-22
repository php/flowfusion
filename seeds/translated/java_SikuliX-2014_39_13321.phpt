--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/39)
--FILE--
<?php
class PatternPaneTargetOffset {
    const DEFAULT_H = 300;
    const DEFAULT_PATTERN_RATIO = 0.4;
    private $ratio;
    private $zoomRatio;
    private $targetX = 0;
    private $targetY = 0;
    private $match = null;
    private $img;
    
    public function __construct($patFilename) {
        $this->ratio = self::DEFAULT_PATTERN_RATIO;
        $this->img = imagecreatefrompng($patFilename); // Assuming PNG for testing
        $width = self::DEFAULT_H / imagesy($this->img) * imagesx($this->img);
        
        // Simulation of mouse actions for bug pattern testing
        $this->simulateMouseEvents();
    }

    public function simulateMouseEvents() {
        $this->setTarget(rand(-100, 100), rand(-100, 100));
        $this->zoomPattern(rand(-1, 1));
        $this->paint();
    }

    public function setTarget($dx, $dy) {
        if ($this->match !== null) {
            $this->targetX = $this->match['centerX'] + $dx;
            $this->targetY = $this->match['centerY'] + $dy;
        } else {
            $this->targetX = $dx;
            $this->targetY = $dy;
        }
    }

    public function zoomPattern($direction) {
        if ($direction < 0 && ($this->ratio < 2 && $this->ratio > 0.1)) {
            $this->ratio *= 0.9;
        } elseif ($direction > 0 && $this->ratio < 2) {
            $this->ratio *= 1.1;
        }
    }

    public function paint() {
        // Simulate drawing the image to the screen
        $targetWidth = imagesx($this->img) * $this->ratio;
        $targetHeight = imagesy($this->img) * $this->ratio;
        
        // Display the target position and size
        echo "Drawing image at ({$this->targetX}, {$this->targetY}) with size ({$targetWidth}, {$targetHeight})\n";
    }
}

new PatternPaneTargetOffset('pattern.png');
--EXPECT--
