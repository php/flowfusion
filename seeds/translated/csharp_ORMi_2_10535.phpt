--TEST--
Translated seed (inferredbugs-csharp: ORMi/2)
--FILE--
<?php
class WMIMethod
{
    public static function executeMethod($obj, $parameters = null)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $methodName = $trace[1]['function'] ?? '';
        $className = is_object($obj) ? get_class($obj) : (string)$obj;
        $instance = new $className();

        if ($parameters !== null) {
            foreach (get_object_vars($parameters) as $k => $v) {
                $instance->$k = $v;
            }
        }

        return $instance->$methodName();
    }

    public static function executeStaticMethod($parameters = null)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $methodName = $trace[1]['function'] ?? '';
        $className = $trace[1]['class'] ?? '';

        if ($parameters !== null) {
            foreach (get_object_vars($parameters) as $k => $v) {
                $className::$$k = $v;
            }
        }

        return $className::$methodName();
    }
}

class Demo
{
    public $x = 0;
    public static $y = 0;

    public function bug()
    {
        return $this->x + 1;
    }

    public static function bugStatic()
    {
        return self::$y + 1;
    }
}

$o = new Demo();
$o->x = 41;
$p = (object)['x' => 41, 'y' => 41];
WMIMethod::executeMethod($o, $p);
WMIMethod::executeStaticMethod($p);
?>
--EXPECT--
