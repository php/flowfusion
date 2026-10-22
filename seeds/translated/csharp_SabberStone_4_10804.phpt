--TEST--
Translated seed (inferredbugs-csharp: SabberStone/4)
--FILE--
<?php
$g = ["p1" => ["choice" => null, "mulligan" => "DONE"], "p2" => ["choice" => null, "mulligan" => "DONE"], "step" => "BEGIN_MULLIGAN", "history" => []];
$entityChoices = [1 => ["id" => 1, "playerId" => 1, "type" => "MULLIGAN", "choices" => [0, 1]], 2 => ["id" => 2, "playerId" => 2, "type" => "MULLIGAN", "choices" => [0, 1]]];
$optionsMap = [10 => ["id" => 10, "opts" => ["a", "b"]]];
$allOptions = $optionsMap[10];
$chosen = $entityChoices[1]["type"] === "MULLIGAN" ? ["kind" => "mulligan", "player" => $entityChoices[1]["playerId"], "choices" => $entityChoices[1]["choices"]] : ["kind" => "pick", "player" => $entityChoices[1]["playerId"], "choice" => $entityChoices[1]["choices"][0]];
$g["history"][] = ["event" => "create"];
$g["history"][] = ["event" => "choose", "data" => $chosen];
$g["history"][] = ["event" => "options", "data" => $allOptions];
if ($g["step"] === "BEGIN_MULLIGAN" && $g["p1"]["mulligan"] === "DONE" && $g["p2"]["mulligan"] === "DONE") {
    $g["step"] = "MAIN_ACTION";
    while ($g["step"] !== "MAIN_ACTION") {
        usleep(500000);
    }
}
echo count($g["history"]), "\n";
?>
--EXPECT--
