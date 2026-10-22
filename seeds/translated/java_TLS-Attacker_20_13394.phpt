--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/20)
--FILE--
<?php

class Point {
    public $x;
    public $y;

    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }
}

class ECComputer {
    private $secret;

    public function setSecret($secret) {
        $this->secret = $secret;
    }

    public function mul(Point $point) {
        return new Point($this->secret * $point->x, $this->secret * $point->y);
    }
}

function asUnsignedByteArray($value) {
    return array_map('chr', str_split($value, 1));
}

$config = [];
$numberOfQueries = 0;

$checkPoint = new Point(10, 20);
$secret = 25091756309879652045519159642875354611257005804552159157;

$computer = new ECComputer();
$computer->setSecret($secret);

try {
    $p = $computer->mul($checkPoint);
    $pms = asUnsignedByteArray($p->x);
} catch (Exception $e) {
    $valid = false;
}

if ($numberOfQueries % 100 == 0) {
    echo "Number of queries so far: " . $numberOfQueries . "\n";
}

$numberOfQueries++;
--EXPECT--
