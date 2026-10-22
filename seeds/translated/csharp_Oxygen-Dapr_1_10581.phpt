--TEST--
Translated seed (inferredbugs-csharp: Oxygen-Dapr/1)
--FILE--
<?php
class EventHandleRequest {
    public $data;
    function __construct($data = null) { $this->data = $data; }
}

class Serialize {
    function DeserializesJson($json) {
        return json_decode($json, true);
    }
}

$serialize = null;
$getData = function ($handleRequest) use (&$serialize) {
    if ($handleRequest === null || empty($handleRequest->data)) return null;
    if ($serialize === null) $serialize = new Serialize();
    return $serialize->DeserializesJson($handleRequest->data);
};

$cases = [null, new EventHandleRequest(""), new EventHandleRequest("null"), new EventHandleRequest('{"a":1}'), new EventHandleRequest("0")];
foreach ($cases as $c) {
    $getData($c);
}
?>
--EXPECT--
