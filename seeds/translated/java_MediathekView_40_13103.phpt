--TEST--
Translated seed (inferredbugs-java: MediathekView/40)
--FILE--
<?php
$COMBO_ZEIT = ["alles", "1 Tag", "2 Tage", "3 Tage", "4 Tage", "5 Tage", "10 Tage", "15 Tage", "20 Tage", "30 Tage"];
$COMBO_ZEIT_INT = [0, 1, 2, 3, 4, 5, 10, 15, 20, 30];
$selectedIndex = 0;
$stopBeob = false;

function tabelleLaden($selectedIndex, $stopBeob) {
    global $COMBO_ZEIT, $COMBO_ZEIT_INT;

    if (!$stopBeob) {
        $filteredValues = [];
        foreach ($COMBO_ZEIT as $index => $value) {
            if ($index == $selectedIndex) {
                $filteredValues[] = $value;
            }
        }
        // Simulated loading of table with filtered values
        echo "Filtered Values: " . implode(", ", $filteredValues) . "\n";
    }
}

$filterComboBox = function($index) use (&$selectedIndex, &$stopBeob) {
    if (!$stopBeob) {
        $selectedIndex = $index;
        tabelleLaden($selectedIndex, $stopBeob);
    }
};

$filterComboBox(2); // Simulate selecting "2 Tage"
$stopBeob = true; // Simulating a change in the observation state
$filterComboBox(5); // Simulate another selection
--EXPECT--
