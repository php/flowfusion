--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/2)
--FILE--
<?php
class DefineObjectsInjector {
    const COM_DAY_CQ_WCM_TAGS_DEFINE_OBJECTS_TAG = "com.day.cq.wcm.tags.DefineObjectsTag";
    
    function getValue($adaptable, $name) {
        switch($name) {
            case 'resource':
                return $this->getResource($adaptable);
            case 'resourceResolver':
                return $this->getResourceResolver($adaptable);
            case 'currentPage':
                return $this->getCurrentPage($adaptable);
            case 'session':
                return $this->getSession($adaptable);
        }
        return null;
    }

    private function getResource($adaptable) {
        return $adaptable instanceof Request ? $adaptable->getResource() : null;
    }

    private function getResourceResolver($adaptable) {
        return $adaptable instanceof Request ? $adaptable->getResourceResolver() : null;
    }

    private function getCurrentPage($adaptable) {
        $context = $this->getComponentContext($adaptable);
        return $context ? $context->getPage() : null;
    }

    private function getSession($adaptable) {
        return $this->getResourceResolver($adaptable)->adaptTo(Session::class);
    }

    private function getComponentContext($adaptable) {
        return $adaptable instanceof Request ? WCMUtils::getComponentContext($adaptable) : null;
    }
}

class Request {
    function getResource() { return 'resource'; }
    function getResourceResolver() { return new ResourceResolver(); }
}

class ResourceResolver {
    function adaptTo($class) { return new Session(); }
}

class Session {}

class WCMUtils {
    static function getComponentContext($request) { return new ComponentContext(); }
}

class ComponentContext {
    function getPage() { return 'currentPage'; }
}

$injector = new DefineObjectsInjector();
$injector->getValue(new Request(), 'currentPage');
--EXPECT--
