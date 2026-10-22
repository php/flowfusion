--TEST--
Translated seed (inferredbugs-csharp: dbschemareader/3)
--FILE--
<?php
$ds = [];
$owner = null;
$tableName = "t";
$columns = [["name" => "id"], ["name" => "x"]];
$ds["Columns"] = $columns;
if ($owner === null) {
    $owner = "dbo";
}
$ds["Primary_Keys"] = [];
$ds["Foreign_Keys"] = [];
$ds["Unique_Keys"] = [];
$ds["Check_Constraints"] = [];
$ds["Identity_Columns"] = [];
$viewColumns = $columns;
$ds["ViewColumns"] = $viewColumns;
$procedureSource = [];
$ds["ProcedureSource"] = $procedureSource;
echo count($ds) . PHP_EOL;
?>
--EXPECT--
