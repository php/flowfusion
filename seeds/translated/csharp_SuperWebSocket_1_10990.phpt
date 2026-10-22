--TEST--
Translated seed (inferredbugs-csharp: SuperWebSocket/1)
--FILE--
<?php
class Session {
    public array $frames = [];
    public bool $closed = false;
    public array $messages = [];
    public function close(): void { $this->closed = true; }
}

$session = new Session();

$frames = [
    (object)['fin' => false, 'text' => "he"],
    (object)['fin' => true, 'text' => "llo"],
];

foreach ($frames as $frame) {
    if (!is_object($frame) || !property_exists($frame, 'fin') || !property_exists($frame, 'text')) {
        $session->close();
        break;
    }

    if ($frame->fin) {
        if (count($session->frames) > 0) {
            $session->close();
            break;
        }
        $session->messages[] = $frame->text;
    } else {
        $session->frames[] = $frame;
    }
}

if (!$session->closed && count($session->messages) === 1) {
    echo $session->messages[0];
}
?>
--EXPECT--
