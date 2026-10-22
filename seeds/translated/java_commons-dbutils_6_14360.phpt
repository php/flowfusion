--TEST--
Translated seed (inferredbugs-java: commons-dbutils/6)
--FILE--
<?php
class ArrayListHandler {
    public function handle($resultSet) {
        $results = [];
        while ($row = $resultSet->fetch_row()) {
            $results[] = $row;
        }
        return $results;
    }
}

$resultSet = new mysqli_result(); // Assume this mimics the result set with fetch_row method
$resultSet->setData([
    [4, 5, 6],
    [7, 8, 9]
]); // Example data

$handler = new ArrayListHandler();
$results = $handler->handle($resultSet);

$row = null;
foreach ($results as $row) {}

$row = (array)$row; // Ensuring $row is an array
$final_check_1 = $row[0] == 4;
$final_check_2 = $row[1] == 5;
$final_check_3 = $row[2] == 6;

$emptyResultSet = new mysqli_result(); // Assume this mimics an empty result set
$emptyHandler = new ArrayListHandler();
$emptyResults = $emptyHandler->handle($emptyResultSet);
$final_empty_check = empty($emptyResults);
?>
--EXPECT--
