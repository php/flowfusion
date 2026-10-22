--TEST--
Translated seed (inferredbugs-csharp: FakeItEasy/1)
--FILE--
<?php

class ImportsModule
{
    private array $importedArgumentValueFormatters = [];
    private array $importedDummyDefinitions = [];
    private array $importedFakeConfigurators = [];

    public function __construct()
    {
        $this->satisfyImports();
    }

    private function satisfyImports(): void
    {
        $this->importedArgumentValueFormatters = $this->getImports('ArgumentValueFormatter');
        $this->importedDummyDefinitions = $this->getImports('DummyDefinition');
        $this->importedFakeConfigurators = $this->getImports('FakeConfigurator');
    }

    private function getImports(string $type): array
    {
        // Simulating the import process - in practice, this could gather instances from a defined location or criteria
        return [new $type];
    }

    public function registerDependencies($container)
    {
        $container['argumentValueFormatters'] = $this->importedArgumentValueFormatters;
        $container['dummyDefinitions'] = $this->importedDummyDefinitions;
        $container['fakeConfigurators'] = $this->importedFakeConfigurators;
    }
}

class ArgumentValueFormatter {}
class DummyDefinition {}
class FakeConfigurator {}

$container = [];
$importsModule = new ImportsModule();
$importsModule->registerDependencies($container);
--EXPECT--
