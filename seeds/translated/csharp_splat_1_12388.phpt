--TEST--
Translated seed (inferredbugs-csharp: splat/1)
--FILE--
<?php
class Locator {
    public static $locator;
    public static function SetLocator($x) { self::$locator = $x; }
}

class MicrosoftDependencyResolver {
    public $source;
    public function __construct($source) { $this->source = $source; }
}

class ServiceCollection {}
class ServiceProvider {}

$serviceCollection = new ServiceCollection();
Locator::SetLocator(new MicrosoftDependencyResolver($serviceCollection));

$serviceProvider = new ServiceProvider();
Locator::SetLocator(new MicrosoftDependencyResolver($serviceProvider));
?>
--EXPECT--
