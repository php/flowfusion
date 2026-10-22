--TEST--
Translated seed (inferredbugs-java: J2V8/12)
--FILE--
<?php

class V8ObjectUtils {

    private static function toArray($array) {
        return array_map(function($value) {
            return is_object($value) ? (array)$value : $value;
        }, $array);
    }

    private static function toList($list) {
        return array_values($list);
    }

    public static function toMap($object) {
        if ($object === null) {
            return [];
        }
        $result = [];
        foreach ($object as $key => $value) {
            $result[$key] = is_array($value) ? self::toArray($value) : $value;
        }
        return $result;
    }

    public static function test() {
        $object = (object)[
            'a' => 1,
            'b' => [2, 3, 4],
            'c' => (object)['d' => 5]
        ];
        $map = self::toMap($object);
        $list = self::toList($map['b']);

        foreach ($list as $item) {
            echo $item . "\n";
        }
    }
}

V8ObjectUtils::test();
--EXPECT--
