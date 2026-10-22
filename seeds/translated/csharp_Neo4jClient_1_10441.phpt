--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/1)
--FILE--
<?php
$nodes = [];
$rels = [];

$nodes[] = ['label' => 'Organisation', 'props' => ['Name' => 'Readify']];
$readify = 0;

$nodes[] = ['label' => 'User', 'props' => ['FirstName' => 'Erik', 'LastName' => 'Natoli', 'DateOfBirth' => '1988-01-01T00:00:00+00:00']];
$erik = 1;

$nodes[] = ['label' => 'User', 'props' => ['FirstName' => 'Sofia', 'LastName' => 'Brighton', 'DateOfBirth' => '1969-06-25T00:00:00+00:00']];
$sofia = 2;
$rels[] = ['type' => 'HasMet', 'from' => $sofia, 'to' => $erik, 'props' => ['DateMet' => gmdate('c')]];
$rels[] = ['type' => 'BelongsTo', 'from' => $sofia, 'to' => $readify, 'props' => []];

$nodes[] = ['label' => 'SecurityGroup', 'props' => ['Name' => 'Administrators']];
$rels[] = ['type' => 'BelongsTo', 'from' => 3, 'to' => $erik, 'props' => []];

$nodes[] = ['label' => 'SecurityGroup', 'props' => ['Name' => 'Power Users']];
$rels[] = ['type' => 'BelongsTo', 'from' => 4, 'to' => $erik, 'props' => []];
$rels[] = ['type' => 'BelongsTo', 'from' => 4, 'to' => $readify, 'props' => []];

$rels[] = ['type' => 'BelongsTo', 'from' => $erik, 'to' => $readify, 'props' => []];
$rels[] = ['type' => 'BelongsTo', 'from' => $readify, 'to' => $sofia, 'props' => []];
$rels[] = ['type' => 'BelongsTo', 'from' => $readify, 'to' => $readify, 'props' => []];
?>
--EXPECT--
