--TEST--
Translated seed (inferredbugs-java: clickhouse-jdbc/1)
--FILE--
<?php

class CHStatement {

    private $url;
    private $properties;

    public function __construct($url) {
        $this->url = $url;
        $this->properties = ['compress' => false, 'bufferSize' => 8192];
    }

    public static function clickhousifySql($sql, $format) {
        $sql = trim($sql);
        if (!preg_match('/(?:TabSeparatedWithNamesAndTypes|TabSeparated|JSONCompact)$/', $sql)) {
            if (substr($sql, -1) === ';') $sql = substr($sql, 0, -1);
            $sql .= " FORMAT " . $format . ';';
        }
        return $sql;
    }

    public function executeQuery($sql) {
        $csql = self::clickhousifySql($sql, "TabSeparatedWithNamesAndTypes");
        $is = $this->getInputStream($csql);
        return $is;
    }

    private function getInputStream($sql) {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sql);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode !== 200) {
            throw new Exception("CH error: " . $response);
        }
        return $response; 
    }
}

$url = 'http://your_clickhouse_server';
$sql = 'SELECT * FROM your_table';
$statement = new CHStatement($url);
$result = $statement->executeQuery($sql);
echo $result;
--EXPECT--
