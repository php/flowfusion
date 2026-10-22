--TEST--
Translated seed (inferredbugs-csharp: Captura/2)
--FILE--
<?php

class MouseOverlaySettings {
    public $Display;
    public $Radius;
    public $Color;
    public $BorderThickness;
    public $BorderColor;

    public function __construct($display, $radius, $color, $borderThickness, $borderColor) {
        $this->Display = $display;
        $this->Radius = $radius;
        $this->Color = $color;
        $this->BorderThickness = $borderThickness;
        $this->BorderColor = $borderColor;
    }
}

class MousePointerOverlay {
    private $settings;

    public function __construct(MouseOverlaySettings $settings) {
        $this->settings = $settings;
    }

    public function draw($editor, $transform = null) {
        if (!$this->settings->Display) {
            return;
        }

        $clickRadius = $this->settings->Radius;
        $curPos = [random_int(0, 800), random_int(0, 600)];

        if ($transform !== null) {
            $curPos = $transform($curPos);
        }

        $d = $clickRadius * 2;
        $x = $curPos[0] - $clickRadius;
        $y = $curPos[1] - $clickRadius;

        $editor->fillEllipse($this->settings->Color, [$x, $y, $d, $d]);

        $border = $this->settings->BorderThickness;

        if ($border > 0) {
            $x -= $border / 2;
            $y -= $border / 2;
            $d += $border;

            $editor->drawEllipse($this->settings->BorderColor, $border, [$x, $y, $d, $d]);
        }
    }
}

class BitmapEditor {
    public function fillEllipse($color, $dimensions) {
        // Simulate drawing
    }

    public function drawEllipse($borderColor, $borderThickness, $dimensions) {
        // Simulate drawing
    }
}

function main() {
    $settings = new MouseOverlaySettings(true, 10, 'red', 2, 'black');
    $overlay = new MousePointerOverlay($settings);
    $editor = new BitmapEditor();
    $overlay->draw($editor);
}

main();
--EXPECT--
