--TEST--
Translated seed (inferredbugs-java: RuoYi/2)
--FILE--
<?php

class ExcelUtil
{
    private $clazz;

    public function __construct($clazz)
    {
        $this->clazz = $clazz;
    }

    public function importExcel($input)
    {
        $list = [];
        if (($handle = fopen($input, 'r')) !== FALSE) {
            $headers = fgetcsv($handle);
            while (($data = fgetcsv($handle)) !== FALSE) {
                $entity = new $this->clazz;
                foreach ($headers as $index => $header) {
                    if (isset($data[$index])) {
                        $field = $header;
                        $value = $data[$index];
                        $property = new ReflectionProperty($entity, $field);
                        $property->setAccessible(true);
                        $type = $property->getType();
                        if ($type->getName() === 'string') {
                            $property->setValue($entity, (string)$value);
                        } elseif ($type->getName() === 'int') {
                            $property->setValue($entity, (int)$value);
                        } elseif ($type->getName() === 'float') {
                            $property->setValue($entity, (float)$value);
                        }
                    }
                }
                $list[] = $entity;
            }
            fclose($handle);
        }
        return $list;
    }

    public function exportExcel($list, $sheetName)
    {
        $filename = "$sheetName.xlsx";
        $file = fopen($filename, 'w');
        $headers = array_keys((array)$list[0]);
        fputcsv($file, $headers);
        foreach ($list as $item) {
            fputcsv($file, (array)$item);
        }
        fclose($file);
    }
}

class SampleData
{
    public string $name;
    public int $age;
    public float $salary;
}

$excelUtil = new ExcelUtil(SampleData::class);
$data = $excelUtil->importExcel('input.csv');
$excelUtil->exportExcel($data, 'output');
--EXPECT--
