--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/11)
--FILE--
<?php
declare(strict_types=1);

$objs = [
    (object)['count' => 1, 'name' => '10', 'flag' => true],
    (object)['count' => 2, 'name' => '2', 'flag' => false],
    (object)['count' => 10, 'name' => '10', 'flag' => true],
];

$conditions = [
    ['op' => '==', 'prop' => 'count', 'ref' => '10'],
    ['op' => '===', 'prop' => 'count', 'ref' => '10'],
    ['op' => '>', 'prop' => 'name', 'ref' => '2'],
    ['op' => '===', 'prop' => 'flag', 'ref' => '1'],
];

$cacheType = null;
$cacheValue = null;

foreach ($conditions as $cond) {
    foreach ($objs as $obj) {
        if (!property_exists($obj, $cond['prop'])) {
            continue;
        }

        $value = $obj->{$cond['prop']};
        $type = gettype($value);

        if ($cacheType !== $type) {
            $cacheType = $type;
            if ($type === 'integer') {
                $cacheValue = (int)$cond['ref'];
            } elseif ($type === 'double') {
                $cacheValue = (float)$cond['ref'];
            } elseif ($type === 'boolean') {
                $cacheValue = filter_var($cond['ref'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($cacheValue === null) {
                    $cacheValue = false;
                }
            } else {
                $cacheValue = (string)$cond['ref'];
            }
        }

        switch ($cond['op']) {
            case '==':
                $result = ($value == $cacheValue);
                break;
            case '===':
                $result = ($value === $cacheValue);
                break;
            case '>':
                $result = ($value > $cacheValue);
                break;
            default:
                $result = false;
                break;
        }

        if ($result && $cond['prop'] === 'name' && $cacheType === 'string') {
            $cacheValue = (int)$cacheValue;
        }
    }
}

echo "done\n";
--EXPECT--
