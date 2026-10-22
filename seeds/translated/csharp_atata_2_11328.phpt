--TEST--
Translated seed (inferredbugs-csharp: atata/2)
--FILE--
<?php
class AtataContext
{
    public static $current = null;
    public $driver;
    public $log;
    public $pageObject;
    public $temporarilyPreservedPageObjectList;
    public function __construct()
    {
        $this->temporarilyPreservedPageObjectList = [];
    }
}

class SimpleLogManager
{
    public function info($m) {}
    public function startSection($m) {}
    public function endSection() {}
}

AtataContext::$current = new AtataContext();
AtataContext::$current->log = new SimpleLogManager();
$testName = "  ";
$msg = "Starting test";
if (trim($testName) !== '') $msg .= ": " . $testName;
AtataContext::$current->log->info($msg);
AtataContext::$current->log->startSection("Init web driver");
AtataContext::$current->driver = new stdClass();
AtataContext::$current->log->endSection();
AtataContext::$current->log->info("Finished test");
AtataContext::$current->log = null;
AtataContext::$current->driver = null;
AtataContext::$current->temporarilyPreservedPageObjectList = [];
AtataContext::$current->pageObject = null;
AtataContext::$current = null;
?>
--EXPECT--
