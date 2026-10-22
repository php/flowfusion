--TEST--
Translated seed (inferredbugs-csharp: silky/1)
--FILE--
<?php
class ServicePointManager { public static $SecurityProtocol; }
class SecurityProtocolType { const Tls12 = 1; }
class HttpContextAccessor {}
class LmsFileProvider { public function __construct($hostEnvironment) {} }
class CommonHelper { public static $DefaultFileProvider; }
class ModuleLoader {}
class EngineContext {
    public static function Create() { return new self; }
    public function ConfigureServices(&$services, $configuration) {}
}
$services = [];
$configuration = [];
$hostEnvironment = new stdClass();
ServicePointManager::$SecurityProtocol = SecurityProtocolType::Tls12;
$services['HttpContextAccessor'] = new HttpContextAccessor();
CommonHelper::$DefaultFileProvider = new LmsFileProvider($hostEnvironment);
$engine = EngineContext::Create();
$moduleLoader = new ModuleLoader();
if (!isset($services['IModuleLoader'])) $services['IModuleLoader'] = $moduleLoader;
$engine->ConfigureServices($services, $configuration);
--EXPECT--
