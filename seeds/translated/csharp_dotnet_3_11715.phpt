--TEST--
Translated seed (inferredbugs-csharp: dotnet/3)
--FILE--
<?php
$elements = [];
$rels = [];

$add = function (&$elements, $id) {
    $elements[$id] = true;
};

$remove = function (&$elements, &$rels, $id) {
    unset($elements[$id]);
    foreach ($rels as $k => $r) {
        if ($r[0] === $id || $r[1] === $id) unset($rels[$k]);
    }
};

$sys = 1;
$web = 2;
$otherSysA = 3;
$otherSysB = 4;
$userA = 5;
$userB = 6;
$db = 7;
$controller = 8;
$service = 9;
$repository = 10;

$add($elements, $web);
$add($elements, $sys);

$add($elements, $otherSysA);
$add($elements, $otherSysB);
$add($elements, $userA);
$add($elements, $userB);
$add($elements, $db);
$add($elements, $controller);
$add($elements, $service);
$add($elements, $repository);

$rels[] = [$userA, $otherSysA];
$rels[] = [$otherSysA, $sys];
$rels[] = [$sys, $otherSysB];
$rels[] = [$otherSysB, $userB];
$rels[] = [$otherSysA, $web];
$rels[] = [$web, $otherSysB];
$rels[] = [$web, $db];
$rels[] = [$otherSysA, $controller];
$rels[] = [$controller, $service];
$rels[] = [$service, $repository];
$rels[] = [$repository, $db];
$rels[] = [$service, $otherSysB];

$add($elements, $otherSysA);
$add($elements, $otherSysB);
$add($elements, $userA);
$add($elements, $userB);
$add($elements, $db);
$add($elements, $controller);
$add($elements, $service);
$add($elements, $repository);

$remove($elements, $rels, $repository);

$elements = [];
$add($elements, $sys);
$add($elements, $otherSysA);
$add($elements, $otherSysB);

$elements = [];
$add($elements, $web);
$add($elements, $otherSysA);
$add($elements, $otherSysB);
$add($elements, $userA);
$add($elements, $controller);

$elements = [];
$add($elements, $web);
$add($elements, $otherSysA);
$add($elements, $otherSysB);
$add($elements, $db);

$elements = [];
$add($elements, $web);
$add($elements, $otherSysA);
$add($elements, $service);
$add($elements, $controller);

$elements = [];
$add($elements, $web);
$add($elements, $controller);
$add($elements, $service);
$add($elements, $repository);
$add($elements, $otherSysB);

echo "";
?>
--EXPECT--
