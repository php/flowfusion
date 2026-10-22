--TEST--
Translated seed (inferredbugs-csharp: DryIoc/9)
--FILE--
<?php

class Scope {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function dispose() {
        // Simulate disposing resources
    }
}

class HttpContextScopeContext {
    private $getContextItems;

    public function __construct($getContextItems = null) {
        $this->getContextItems = $getContextItems ?: function() {
            return [];
        };
    }

    public function getCurrentOrDefault() {
        $contextItems = ($this->getContextItems)();
        return isset($contextItems['currentScope']) ? $contextItems['currentScope'] : null;
    }

    public function setCurrent($setCurrentScope) {
        $oldScope = $this->getCurrentOrDefault();
        $newScope = $setCurrentScope($oldScope);
        $contextItems = ($this->getContextItems)();
        $contextItems['currentScope'] = $newScope;
    }
}

function setOrKeepCurrentRequestScope($current) {
    return $current !== null ? $current : new Scope('WebRequestScopeName');
}

function simulateRequest() {
    $httpContext = [
        'items' => []
    ];

    $scopeContext = new HttpContextScopeContext(function() use ($httpContext) {
        return $httpContext['items'];
    });

    $scopeContext->setCurrent('setOrKeepCurrentRequestScope');
    $currentScope = $scopeContext->getCurrentOrDefault();
    
    if ($currentScope !== null && $currentScope->getName() === 'WebRequestScopeName') {
        $currentScope->dispose();
    }
}

simulateRequest();
--EXPECT--
