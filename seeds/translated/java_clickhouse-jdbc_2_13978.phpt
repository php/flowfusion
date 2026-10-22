--TEST--
Translated seed (inferredbugs-java: clickhouse-jdbc/2)
--FILE--
<?php

class CHStatement {

    private string $url;

    public function __construct(string $url) {
        $this->url = $url;
    }

    private function clickhousifySql(string $sql, string $format): string {
        $sql = trim($sql);
        if (!str_ends_with(trim($sql, ';'), 'TabSeparatedWithNamesAndTypes') &&
            !str_ends_with(trim($sql, ';'), 'TabSeparated') &&
            !str_ends_with(trim($sql, ';'), 'JSONCompact')) {
            $sql = rtrim($sql, ';') . ' FORMAT ' . $format . ';';
        }
        return $sql;
    }

    public function executeQuery(string $sql): void {
        $csql = $this->clickhousifySql($sql, "TabSeparatedWithNamesAndTypes");
        $response = $this->getInputStream($csql);
        echo $response; // Simulating result processing
    }

    private function getInputStream(string $sql): string {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sql);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
            throw new RuntimeException("CH error: " . $response);
        }
        curl_close($ch);
        return $response;
    }
}

$url = "http://localhost:8123"; // Example URL
$sql = "SELECT * FROM my_table;";
$statement = new CHStatement($url);
$statement->executeQuery($sql);
--EXPECT--
