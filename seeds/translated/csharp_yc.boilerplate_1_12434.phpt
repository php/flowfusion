--TEST--
Translated seed (inferredbugs-csharp: yc.boilerplate/1)
--FILE--
<?php
class ApiResult {
    public static function Result($ok, $value, $msg) {
        return ['ok' => $ok, 'value' => $value, 'msg' => $msg];
    }
}

$cases = [
    new stdClass(),
    new class { public $x = 1; },
    new class extends stdClass {},
    new class {
        public function __toString() { return 'ApiResultLike'; }
    },
];

foreach ($cases as $value) {
    $result = (object)['Value' => $value];
    if (get_class($result) === 'stdClass') {
        try {
            $name = get_class($result->Value);
            if (strpos($name, 'ApiResult') === false) {
                $result = (object)['JsonResult' => ApiResult::Result(true, $result->Value, '请求成功.')];
            }
        } catch (Throwable $ex) {
            $result = $result;
            throw $ex;
        }
    }
    $out = json_encode($result, JSON_UNESCAPED_UNICODE);
    if ($out === false) {
        throw new RuntimeException('json encode failed');
    }
    echo $out, PHP_EOL;
}
?>
--EXPECT--
