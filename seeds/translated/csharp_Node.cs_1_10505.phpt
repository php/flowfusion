--TEST--
Translated seed (inferredbugs-csharp: Node.cs/1)
--FILE--
<?php
$aborted = false;
$noKeepAlive = false;
$minorVersion = 1;
$headers = ["Connection" => "keep-alive"];
$request = new stdClass();
$response = null;
$stream = new class {
    public $closed = false;
    function close() { $this->closed = true; }
};
$disconnect = true;
if (!$noKeepAlive) {
    if ($minorVersion > 0 && array_key_exists("Connection", $headers)) {
        $disconnect = ($headers["Connection"] === "close");
    }
}
if ($disconnect) {
    $request = null;
    $response = null;
    $stream->close();
} else {
    $request->read = true;
}
echo "";
?>
--EXPECT--
