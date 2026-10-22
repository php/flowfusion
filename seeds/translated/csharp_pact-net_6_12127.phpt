--TEST--
Translated seed (inferredbugs-csharp: pact-net/6)
--FILE--
<?php
class Req { public string $method; public string $path; function __construct($m, $p) { $this->method = $m; $this->path = $p; } }
class Res { public int $statusCode = 200; }
class Ctx { public $request; public $response = null; function __construct($r) { $this->request = $r; } }
class PReq { public string $method = ''; public string $path = ''; }
class PRes { public int $status = 0; }
class CompareFailedException extends Exception {}
class Dispatcher {
    static ?PReq $expectedReq = null;
    static ?PRes $expectedRes = null;
    public $cmp; public $reqMap; public $resMap;
    function __construct($c, $r, $m) { $this->cmp = $c; $this->reqMap = $r; $this->resMap = $m; }
    static function set($r, $s) { self::$expectedReq = $r; self::$expectedRes = $s; }
    function dispatch($ctx) {
        if (!self::$expectedReq || !self::$expectedRes) {
            $r = new PRes(); $r->status = 500; return ($this->resMap)($r);
        }
        try {
            $a = ($this->reqMap)($ctx->request);
            ($this->cmp)(self::$expectedReq, $a);
            $out = ($this->resMap)(self::$expectedRes);
            $ctx->response = $out;
            return $out;
        } catch (Throwable $e) {
            $r = new PRes(); $r->status = 500; return ($this->resMap)($r);
        }
    }
}

$ctx = new Ctx(new Req("GET", "/Test"));
$expectedReq = new PReq(); $expectedReq->method = "GET"; $expectedReq->path = "/Test";
$actualReq = new PReq(); $actualReq->method = "PUT"; $actualReq->path = "/Test";
$expectedRes = new PRes(); $expectedRes->status = 200;
Dispatcher::set($expectedReq, $expectedRes);

$reqMap = function($r) use ($actualReq) { return $actualReq; };
$cmp = function($e, $a) use ($expectedReq, $actualReq) { if ($e->method !== $a->method) throw new CompareFailedException(); };
$resMap = function($r) { $x = new Res(); $x->statusCode = ($r->status === 500) ? 500 : 200; return $x; };

$d = new Dispatcher($cmp, $reqMap, $resMap);
$out = $d->dispatch($ctx);
echo $out->statusCode;
?>
--EXPECT--
