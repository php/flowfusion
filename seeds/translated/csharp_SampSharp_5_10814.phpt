--TEST--
Translated seed (inferredbugs-csharp: SampSharp/5)
--FILE--
<?php
class Config { public static int $CapturesToWin = 2; }
class GameMode { public static bool $ObjectiveReached = false; }
class Team {
    public int $TimesCaptured = 0;
    public int $Color;
    public string $GameTextTeamName;
    public ?Vehicle $TargetVehicle = null;
    public ?array $Target = null;
    function __construct(int $color, string $name) { $this->Color = $color; $this->GameTextTeamName = $name; }
}
class Vehicle {
    function Respawn(): void {}
}
class Player {
    public int $Color = 0;
    public int $Score = 0;
    public ?Vehicle $Vehicle = null;
    public Team $Team;
    function __construct(Team $team) { $this->Team = $team; }
    function GameText(string $text, int $time, int $style): void {}
    function SetCheckpoint($target, float $radius): void {}
    function DisableCheckpoint(): void {}
    function PlaySound(int $id): void {}
    static function GameTextForAll(string $text, int $time, int $style): void {}
}
$team = new Team(0x11223344, 'Blue');
$boat = new Vehicle();
$team->TargetVehicle = $boat;
$p1 = new Player($team);
$p2 = new Player($team);
$players = [$p1, $p2];
$p1->Vehicle = $boat;
if ($p1->Vehicle === $p1->Team->TargetVehicle) {
    $p1->Color = 0xE2C063FF;
    $p1->GameText("~w~Take the ~y~boat ~w~back to the ~r~spawn!", 3000, 5);
    $p1->SetCheckpoint($p1->Team->Target, 10.0);
}
$p1->Color = $p1->Team->Color;
$p1->DisableCheckpoint();
if (!GameMode::$ObjectiveReached && $p1->Vehicle === $p1->Team->TargetVehicle) {
    $p1->Team->TimesCaptured++;
    $p1->Score += 5;
    if ($p1->Team->TimesCaptured === Config::$CapturesToWin) {
        Player::GameTextForAll($p1->Team->GameTextTeamName . " wins!", 3000, 5);
        GameMode::$ObjectiveReached = true;
        foreach ($players as $p) $p->PlaySound(1185);
        foreach ($players as $p) $p->PlaySound(1186);
    } else {
        Player::GameTextForAll($p1->Team->GameTextTeamName . " captured the ~y~boat!", 3000, 5);
        $p1->Vehicle->Respawn();
    }
}
?>
--EXPECT--
