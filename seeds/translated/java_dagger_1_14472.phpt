--TEST--
Translated seed (inferredbugs-java: dagger/1)
--FILE--
<?php

class Keys {
  private static $PROVIDER_PREFIX = 'Provider<';
  private static $MEMBERS_INJECTOR_PREFIX = 'MembersInjector<';
  private static $LAZY_PREFIX = 'Lazy<';
  
  private static $IS_QUALIFIER_ANNOTATION = [];

  public static function get($type, $annotation = null) {
    if ($annotation === null && !is_array($type)) {
      return get_class($type);
    }

    $result = '';
    if ($annotation !== null) {
      $result .= $annotation . '/';
    }
    self::typeToString($type, $result);
    return $result;
  }

  public static function getMembersKey($key) {
    return 'members/' . self::get($key);
  }

  private static function typeToString($type, &$result) {
    if (is_object($type)) {
      $result .= get_class($type);
    } elseif (is_array($type)) {
      $result .= 'array[]';
    } elseif (is_string($type)) {
      $result .= $type;
    } else {
      throw new Exception("Uninjectable type " . gettype($type));
    }
  }

  public static function isMembersInjection($key) {
    return strpos($key, 'members/') === 0;
  }

  public static function isAnnotated($key) {
    return strpos($key, '@') === 0;
  }
}

$key = Keys::get('MyClass');
$membersKey = Keys::getMembersKey('MyClass');
$annotatedKey = Keys::get('MyClass', '@MyAnnotation');

echo $key . PHP_EOL;
echo $membersKey . PHP_EOL;
echo $annotatedKey . PHP_EOL;
--EXPECT--
