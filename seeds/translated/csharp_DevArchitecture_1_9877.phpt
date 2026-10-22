--TEST--
Translated seed (inferredbugs-csharp: DevArchitecture/1)
--FILE--
<?php

class SecuredOperation
{
    private $roleClaims;

    public function __construct()
    {
        // Simulating role claims retrieval from a context
        $this->roleClaims = $this->getRoleClaims();
    }

    private function getRoleClaims()
    {
        // Simulated roles, could be empty or contain certain roles
        return ['Admin', 'User']; // Example roles
    }

    public function execute($operationName)
    {
        if (in_array($operationName, $this->roleClaims)) {
            echo "Access granted to execute: $operationName\n";
            return;
        }

        throw new Exception("Authorization denied for operation: $operationName");
    }
}

function main()
{
    $securedOperation = new SecuredOperation();

    // Simulating an operation name being invoked
    $operationName = 'Admin'; // Change this to test authorization
    try {
        $securedOperation->execute($operationName);
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

main();
--EXPECT--
