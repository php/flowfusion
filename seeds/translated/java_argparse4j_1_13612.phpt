--TEST--
Translated seed (inferredbugs-java: argparse4j/1)
--FILE--
<?php
$argIndex = [];
$optArgs = [];
$posArgs = [];
$defaults = [];
$prog = "my_program";

function addArgument($nameOrFlags) {
    global $argIndex, $optArgs, $posArgs;
    $isOptional = strpos($nameOrFlags[0], '-') === 0;
    $arg = ['name' => $nameOrFlags, 'isOptional' => $isOptional];
    
    if ($isOptional) {
        foreach ($arg['name'] as $flag) {
            if (isset($argIndex[$flag])) {
                throw new Exception("Argument conflicts: $flag");
            }
            $argIndex[$flag] = $arg;
        }
        $optArgs[] = $arg;
    } else {
        $posArgs[] = $arg;
    }
    return $arg;
}

addArgument(["-h", "--help"]);
addArgument(["-v", "--verbose"]);
addArgument(["input"]);

$args = $argv;
$parsedArgs = [];
$posIndex = 0;

foreach ($args as $index => $arg) {
    if ($index === 0) continue; 
    if (isset($argIndex[$arg])) {
        $parsedArgs[$arg] = true;
    } elseif ($posIndex < count($posArgs)) {
        $parsedArgs['positional'][] = $arg;
        $posIndex++;
    } else {
        echo "Unrecognized argument: $arg\n";
        exit(1);
    }
}
--EXPECT--
