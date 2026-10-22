--TEST--
Translated seed (inferredbugs-java: SimpleFlatMapper/5)
--FILE--
<?php

class CsvMapperFactory {
    private $fieldMapperErrorHandler;
    private $mapperBuilderErrorHandler;
    private $aliases = [];
    private $customReaders = [];
    private $useAsm = true;
    private $disableAsm = false;
    private $defaultDateFormat = 'Y-m-d H:i:s';

    public static function newInstance() {
        return new self();
    }

    public function fieldMapperErrorHandler($handler) {
        $this->fieldMapperErrorHandler = $handler;
        return $this;
    }

    public function mapperBuilderErrorHandler($handler) {
        $this->mapperBuilderErrorHandler = $handler;
        return $this;
    }

    public function useAsm($useAsm) {
        $this->useAsm = $useAsm;
        return $this;
    }

    public function disableAsm($disableAsm) {
        $this->disableAsm = $disableAsm;
        return $this;
    }

    public function defaultDateFormat($format) {
        $this->defaultDateFormat = $format;
        return $this;
    }

    public function addAlias($key, $value) {
        $this->aliases[strtoupper($key)] = strtoupper($value);
        return $this;
    }

    public function addCustomValueReader($column, $reader) {
        $this->customReaders[$column] = $reader;
        return $this;
    }

    public function newMapper($target) {
        return new DynamicCsvMapper($target, $this->getClassMeta($target), $this->fieldMapperErrorHandler, $this->mapperBuilderErrorHandler, $this->defaultDateFormat, $this->aliases, $this->customReaders);
    }

    private function getClassMeta($target) {
        return ReflectionService::newInstance($this->disableAsm, $this->useAsm)->getClassMeta($target);
    }
}

$factory = CsvMapperFactory::newInstance()->addAlias('Key', 'Value')->defaultDateFormat('d/m/Y');
--EXPECT--
