--TEST--
Translated seed (inferredbugs-csharp: revenj/3)
--FILE--
<?php
$items = [];
for ($i = 0; $i < 3; $i++) {
    $items[] = ["name" => "events", "info" => "User: Update: a,b,c"];
    $items[] = ["name" => "aggregate_roots", "info" => "Order: Delete: x:y:z"];
    $items[] = ["name" => "ignored", "info" => "Bad: Insert: q"];
}

foreach ($items as $e) {
    try {
        if ($e["name"] === "events" || $e["name"] === "aggregate_roots") {
            $first = strpos($e["info"], ":");
            $name = substr($e["info"], 0, $first);
            $second = strpos(substr($e["info"], $first + 1), ":");
            $op = trim(substr($e["info"], $first + 1, $second));
            $array = trim(substr($e["info"], $first + $second + 2));
            if ($array !== "") {
                $uris = array_map("trim", explode(",", $array));
                switch ($op) {
                    case "Update":
                        $out = [$name, "Update", $uris];
                        break;
                    case "Change":
                        $out = [$name, "Change", $uris];
                        break;
                    case "Delete":
                        $out = [$name, "Delete", $uris];
                        break;
                    default:
                        $out = [$name, "Insert", $uris];
                        break;
                }
                $sink[] = $out;
            }
        }
    } catch (Throwable $ex) {
    }
}

echo count($sink ?? []), PHP_EOL;
--EXPECT--
