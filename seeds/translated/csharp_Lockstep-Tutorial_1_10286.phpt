--TEST--
Translated seed (inferredbugs-csharp: Lockstep-Tutorial/1)
--FILE--
<?php
declare(strict_types=1);

class Msg {
    public int $tick = 0;
    public int $actorId = 0;
    public array $hashCodes = [];
    public int $startTick = 0;
    public array $frames = [];
    public int $progress = 0;
}

class Frame {
    public int $tick;
    public array $inputs;
    function __construct(int $tick, int $players) {
        $this->tick = $tick;
        $this->inputs = array_fill(0, $players, null);
    }
}

$maxPlayers = 2;
$history = [];
$hashCodes = [];
$players = [0, 1];

for ($tick = 0; $tick < 3; $tick++) {
    if (!isset($history[$tick])) $history[$tick] = new Frame($tick, $maxPlayers);
    for ($i = 0; $i < $maxPlayers; $i++) {
        if ($history[$tick]->inputs[$i] === null) {
            $history[$tick]->inputs[$i] = (object)['tick' => $tick, 'actorId' => $i];
        }
    }
}

for ($i = 0; $i < $maxPlayers; $i++) {
    $msg = new Msg();
    $msg->tick = 0;
    $msg->actorId = $i;
    $history[0]->inputs[$i] = $msg;
}

for ($i = 0; $i < $maxPlayers; $i++) {
    $msg = new Msg();
    $msg->startTick = 0;
    $msg->hashCodes = [12345 + $i, 12345 + $i + 1];
    foreach ($msg->hashCodes as $k => $code) {
        $tick = $msg->startTick + $k;
        if (isset($hashCodes[$tick])) {
            if ($hashCodes[$tick] !== $code) {
                $hashCodes[$tick] = null;
            }
        } else {
            $hashCodes[$tick] = $code;
        }
    }
}

$packed = [];
for ($tick = 0; $tick < count($history); $tick++) {
    $packed[] = ['tick' => $history[$tick]->tick, 'inputs' => $history[$tick]->inputs];
}

echo json_encode($packed, JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
