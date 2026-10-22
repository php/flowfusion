--TEST--
Translated seed (inferredbugs-csharp: CoreWCF/1)
--FILE--
<?php

class MyStream
{
    private $size;

    public function __construct($size)
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }
}

class ReqRepService
{
    public function echoStream($input)
    {
        return fopen('php://temp', 'r+');
    }
}

class Startup
{
    public static $binding;

    public static function configure()
    {
        // Simulating service endpoint configuration
    }
}

function main() {
    $bindings = ["Http1Binding"]; // Only one binding for the test
    foreach ($bindings as $binding) {
        Startup::$binding = $binding;
        Startup::configure();

        $messageSize = 0;
        $num2 = 20000;
        $input = new MyStream($messageSize);
        $stream = (new ReqRepService())->echoStream($input);

        $num3 = 0;
        $buffer = '';
        while (($num4 = fread($stream, 370)) !== false) {
            $num3 += strlen($num4);
        }

        if ($num3 !== $num2) {
            // Uncomment the line below to see the output if desired
            // echo "Expected {$num2}, got {$num3}\n";
        }
    }
}

main();
--EXPECT--
