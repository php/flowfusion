--TEST--
Translated seed (inferredbugs-java: commands/1)
--FILE--
<?php

class CommandOperationContext {
    private static $stack = [];
    public static function push($context) {
        self::$stack[] = $context;
    }
    public static function pop() {
        return array_pop(self::$stack);
    }
    public static function peek() {
        return end(self::$stack) ?: null;
    }
}

class CommandIssuer {
    public function sendMessageInternal($msg) {
        echo $msg . PHP_EOL;
    }
}

class CommandManager {
    private $rootCommands = [];
    private $supportedLanguages = ['en', 'de', 'es'];

    public function createCommandOperationContext($command, $issuer, $commandLabel, $args) {
        $context = ['command' => $command, 'issuer' => $issuer, 'commandLabel' => $commandLabel, 'args' => $args];
        CommandOperationContext::push($context);
    }

    public function generateCommandHelp($issuer, $command) {
        $context = CommandOperationContext::peek();
        if ($context === null) {
            throw new Exception("This method can only be called as part of a command execution.");
        }
        return "Help for command: $command";
    }
    
    public function registerCommand($command) {
        // Simulate command registration
        $this->rootCommands[] = $command;
    }

    public function executeCommand($command, $args) {
        $issuer = new CommandIssuer();
        $this->createCommandOperationContext($command, $issuer, $command, $args);
        $help = $this->generateCommandHelp($issuer, $command);
        $issuer->sendMessageInternal($help);
        CommandOperationContext::pop();
    }
}

$manager = new CommandManager();
$manager->registerCommand('exampleCommand');
$manager->executeCommand('exampleCommand', ['arg1', 'arg2']);
--EXPECT--
