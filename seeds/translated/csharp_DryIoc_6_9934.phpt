--TEST--
Translated seed (inferredbugs-csharp: DryIoc/6)
--FILE--
<?php

class DependencyScopeHandler {
    public function handle($request) {
        $owinContext = $this->getOwinContext($request);
        if ($owinContext !== null) {
            $scopedContainer = $this->getDryIocScopedContainer($owinContext);
            if ($scopedContainer !== null) {
                $request['dependency_scope'] = new DryIocDependencyScope($scopedContainer);
            }
        }
        return $this->sendAsync($request);
    }

    private function getOwinContext($request) {
        return isset($request['owin_context']) ? $request['owin_context'] : null;
    }

    private function getDryIocScopedContainer($owinContext) {
        return isset($owinContext['scoped_container']) ? $owinContext['scoped_container'] : null;
    }

    private function sendAsync($request) {
        // Simulating sending a request asynchronously
        return "Request sent with dependency scope.";
    }
}

function main() {
    $request = [
        'owin_context' => [
            'scoped_container' => 'some_container_value'
        ]
    ];

    $handler = new DependencyScopeHandler();
    $response = $handler->handle($request);

    echo $response;
}

main();
--EXPECT--
