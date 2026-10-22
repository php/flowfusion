--TEST--
Translated seed (inferredbugs-java: closure-compiler/37)
--FILE--
<?php
class ObjectType {
    private $visited = false;
    private $unknown = true;

    public function detectImplicitPrototypeCycle($getImplicitPrototype) {
        $this->visited = true;
        $p = $getImplicitPrototype();
        while ($p) {
            if ($p->visited) {
                return true;
            } else {
                $p->visited = true;
            }
            $p = $p->getImplicitPrototype();
        }

        $p = $this;
        do {
            $p->visited = false;
            $p = $p->getImplicitPrototype();
        } while ($p);
        return false;
    }

    public function getImplicitPrototype() {
        return null;
    }

    public function isUnknownType() {
        if ($this->unknown) {
            $implicitProto = $this->getImplicitPrototype();
            if ($implicitProto === null || $implicitProto->isNativeObjectType()) {
                $this->unknown = false;
            } else {
                $this->unknown = $implicitProto->isUnknownType();
            }
        }
        return $this->unknown;
    }

    public function isNativeObjectType() {
        return false;
    }
}

$object = new ObjectType();
$object->detectImplicitPrototypeCycle(function() use ($object) {
    return $object->getImplicitPrototype();
});
$object->isUnknownType();
--EXPECT--
