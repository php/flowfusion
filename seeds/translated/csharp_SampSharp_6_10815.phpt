--TEST--
Translated seed (inferredbugs-csharp: SampSharp/6)
--FILE--
<?php
class GameModeBuilderException extends Exception {}
class GameModeStartBehaviour { const Gmx = 0; }
class GameModeExitBehaviour { const ShutDown = 0; const Restart = 1; }

$communicationClient = null;
$gameModeProvider = null;
$exitBehaviour = GameModeExitBehaviour::ShutDown;
$startBehaviour = GameModeStartBehaviour::Gmx;
$redirectConsoleOutput = false;
$builder = null;

$useCommunicationClient = function ($client) use (&$communicationClient) {
    if ($client === null) throw new InvalidArgumentException("communicationClient");
    $communicationClient = $client;
};
$use = function ($gameMode) use (&$gameModeProvider) {
    if ($gameMode === null) throw new InvalidArgumentException("gameMode");
    $gameModeProvider = $gameMode;
};

$useCommunicationClient((object)["type" => "pipe", "name" => "SampSharp"]);
$use((object)["provider" => "demo"]);
$redirectConsoleOutput = true;
$exitBehaviour = GameModeExitBehaviour::Restart;
$builder = function ($client, $start, $provider) {
    return (object)["client" => $client, "start" => $start, "provider" => $provider, "runs" => 0];
};

if ($communicationClient === null) throw new GameModeBuilderException("No communication client has been specified");
if ($gameModeProvider === null) throw new GameModeBuilderException("No game mode provider has been specified");

$runner = $builder ? $builder($communicationClient, $startBehaviour, $gameModeProvider) : (object)["client" => $communicationClient, "start" => $startBehaviour, "provider" => $gameModeProvider, "runs" => 0];

if ($redirectConsoleOutput) {
    $old = fopen("php://stdout", "w");
}

do {
    $runner->runs++;
    $exitBehaviour = GameModeExitBehaviour::ShutDown;
} while ($exitBehaviour === GameModeExitBehaviour::Restart);

if ($redirectConsoleOutput) {
    fclose($old);
}
?>
--EXPECT--
