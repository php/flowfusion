--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/25)
--FILE--
<?php
$logger = [];
$processing = [];
$spans = [];
$tx = true;

$req = (object)['method' => 'GET', 'url' => 'https://elastic.co'];
$listener = function($name, $payload) use (&$logger, &$processing, &$spans, &$tx) {
    if ($name === 'start') {
        if (!is_object($payload) || !isset($payload->Request) || !is_object($payload->Request)) return;
        $r = $payload->Request;
        $processing[spl_object_id($r)] = (object)['Context' => (object)['Http' => (object)['Url' => $r->url, 'Method' => $r->method]]];
    } elseif ($name === 'stop') {
        if (!is_object($payload) || !isset($payload->Request) || !is_object($payload->Request) || !isset($payload->Response) || !is_object($payload->Response)) return;
        $r = $payload->Request;
        $id = spl_object_id($r);
        if (!isset($processing[$id])) {
            $logger[] = 'Failed capturing request GET https://elastic.co';
            return;
        }
        $span = $processing[$id];
        $span->Context->Http->StatusCode = $payload->Response->StatusCode;
        $span->Type = 'external';
        $span->Subtype = 'http';
        $span->Action = null;
        $span->Name = 'GET localhost';
        $span->Duration = 1;
        $spans[] = $span;
        unset($processing[$id]);
    } elseif ($name === 'error') {
        if (is_object($payload) && isset($payload->Exception) && is_object($payload->Exception)) {
            $logger[] = '{HttpDiagnosticListener} in OnError .cs: ' . $payload->Exception->getMessage();
        }
    }
};

$listener('error', (object)['Exception' => new Exception('Ooops, this went wrong')]);
$listener('start', (object)['Request' => $req]);
$listener('stop', (object)['Request' => $req, 'Response' => (object)['StatusCode' => 200]]);
$listener('stop', (object)['Request' => $req, 'Response' => (object)['StatusCode' => 200]]);
$listener('start', 'x');
$listener('start', (object)['Request' => null]);
$listener(null, null);

echo "";
?>
--EXPECT--
