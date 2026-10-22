--TEST--
Translated seed (inferredbugs-csharp: eisk.webapi/1)
--FILE--
<?php
class Employee
{
    public int $Id;
}

class EmployeeDataService
{
    public function __construct(public mixed $dbContext) {}
}

class EmployeeDomainService
{
    public function __construct(public EmployeeDataService $dataService) {}
}

$dbContext = new stdClass();
$dataService = new EmployeeDataService($dbContext);
$service = new EmployeeDomainService($dataService);

$employee = new Employee();
$employee->Id = 1;
$id = $employee->Id;

echo $id, PHP_EOL;
--EXPECT--
