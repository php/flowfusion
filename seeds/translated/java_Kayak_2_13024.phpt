--TEST--
Translated seed (inferredbugs-java: Kayak/2)
--FILE--
<?php

class StartPage {
    private static $instance;
    private static $showOnStartup;

    public function __construct() {
        $this->setShowOnStartup(true);
        $this->initComponents();
    }

    private function initComponents() {
        $this->toggleCheckBox();
    }

    private function toggleCheckBox() {
        if (rand(0, 1) === 1) {
            $this->setShowOnStartup(false);
        } else {
            $this->setShowOnStartup(true);
        }
    }

    public static function getDefault() {
        if (self::$instance === null) {
            self::$instance = new StartPage();
        }
        return self::$instance;
    }

    public function setShowOnStartup($value) {
        self::$showOnStartup = $value;
    }

    public function getPersistenceType() {
        return self::$showOnStartup ? 'PERSISTENCE_ALWAYS' : 'PERSISTENCE_NEVER';
    }
}

$page = StartPage::getDefault();
echo $page->getPersistenceType();
--EXPECT--
