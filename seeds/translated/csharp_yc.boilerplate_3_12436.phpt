--TEST--
Translated seed (inferredbugs-csharp: yc.boilerplate/3)
--FILE--
<?php
class ApiResult {
    public static function Result($ok, $data, $msg) {
        return ['ok' => $ok, 'data' => $data, 'msg' => $msg];
    }
}

class ObjectResult {
    public $value;
    function __construct($value) { $this->value = $value; }
}

class JsonResult {
    public $value;
    function __construct($value) { $this->value = $value; }
}

class Context {
    public $result;
    function __construct($result) { $this->result = $result; }
}

$cases = [
    new ObjectResult(new ApiResult()),
    new ObjectResult(new stdClass()),
    new ObjectResult(['x' => 1]),
    new ObjectResult(null),
    new JsonResult('already json'),
];

foreach ($cases as $context) {
    $obj = $context;
    if (isset($context->result) && $context->result instanceof ObjectResult) {
        try {
            $result = $context->result;
            $value = $result->value;
            if ($value !== null) {
                $name = is_object($value) ? get_class($value) : gettype($value);
                if (strpos($name, 'ApiResult') === false) {
                    $context->result = new JsonResult(ApiResult::Result(true, $value, '请求成功.'));
                }
            }
        } catch (Throwable $ex) {
            $context->result = $obj;
            throw $ex;
        }
    }
}

echo "ok\n";
--EXPECT--
