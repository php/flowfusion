--TEST--
Translated seed (inferredbugs-csharp: ORMi/1)
--FILE--
<?php
class WMIMethod
{
    public static function ExecuteMethod($obj, $parameters = null)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $methodName = $bt[1]['function'] ?? '';
        $className = is_object($obj) ? get_class($obj) : (string)$obj;
        $instance = new $className();

        if ($parameters === null) {
            return $instance->$methodName();
        }

        foreach (get_object_vars($parameters) as $k => $v) {
            $instance->$k = $v;
        }

        return $instance->$methodName();
    }

    public static function ExecuteStaticMethod($parameters = null)
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $methodName = $bt[1]['function'] ?? '';
        $className = $bt[1]['class'] ?? '';

        if ($parameters === null) {
            return $className::$methodName();
        }

        $args = [];
        foreach (get_object_vars($parameters) as $v) {
            $args[] = $v;
        }

        return $className::$methodName(...$args);
    }
}

class Demo
{
    public $x = 0;
    public $y = 0;

    public function Add()
    {
        return $this->x + $this->y;
    }

    public static function Mix($a = 0, $b = 0)
    {
        return $a . ':' . $b;
    }
}

$u = new stdClass();
$u->x = 7;
$u->y = 35;
WMIMethod::ExecuteMethod(new Demo(), $u);

$p = new stdClass();
$p->a = "foo";
$p->b = "bar";
WMIMethod::ExecuteStaticMethod($p);
?>
--EXPECT--
