--TEST--
Translated seed (inferredbugs-java: core/2)
--FILE--
<?php

class Bean {}

class Manager {
    private $beans = [];
    
    public function addBean($bean) {
        if (!in_array($bean, $this->beans)) {
            $this->beans[] = $bean;
        }
        return $this;
    }

    public function getBeans() {
        return $this->beans;
    }
}

$manager = new Manager();
$bean1 = new Bean();
$bean2 = new Bean();

$manager->addBean($bean1);
$manager->addBean($bean2);
$manager->addBean($bean1); // Duplicate, should not be added

foreach ($manager->getBeans() as $bean) {
    // Output the reference to confirm the beans have been added correctly
    var_dump($bean);
}
?>
--EXPECT--
