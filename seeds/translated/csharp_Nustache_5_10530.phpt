--TEST--
Translated seed (inferredbugs-csharp: Nustache/5)
--FILE--
<?php
class ValueGetterFactoryCollection extends ArrayObject {
    public function offsetSet($key, $value): void {
        if ($value === null) throw new InvalidArgumentException("item");
        parent::offsetSet($key, $value);
    }
}
class GenericDictionaryValueGetterFactory {
    public function getValueGetter($target, string $name) {
        if (is_array($target) && array_key_exists($name, $target)) return $target[$name];
        if ($target instanceof ArrayAccess && isset($target[$name])) return $target[$name];
        return null;
    }
}
class ListValueByIndexGetterFactory {
    public function getValueGetter($target, string $name) {
        if (is_array($target) && ctype_digit($name)) {
            $i = (int)$name;
            if ($i >= 0 && $i < count($target)) return $target[$i];
        }
        return null;
    }
}
class NameValueCollectionGetterFactory {
    public function getValueGetter($target, string $name) {
        if ($target instanceof ArrayObject) {
            $a = $target->getArrayCopy();
            if (array_key_exists($name, $a) && $a[$name] !== null) return $a[$name];
        }
        return null;
    }
}
$factories = new ValueGetterFactoryCollection();
$factories[] = new GenericDictionaryValueGetterFactory();
$factories[] = new ListValueByIndexGetterFactory();
$factories[] = new NameValueCollectionGetterFactory();
$target = ['0' => 'zero', 'name' => 'value', 2 => 'two'];
$getter = null;
foreach ($factories as $factory) {
    $getter = $factory->getValueGetter($target, '0');
    if ($getter !== null) break;
}
$collection = new ArrayObject(['x' => 'y']);
foreach ($factories as $factory) {
    $getter = $factory->getValueGetter($collection, 'x');
    if ($getter !== null) break;
}
$getter = null;
foreach ($factories as $factory) {
    $getter = $factory->getValueGetter($target, '2');
    if ($getter !== null) break;
}
echo "ok\n";
?>
--EXPECT--
