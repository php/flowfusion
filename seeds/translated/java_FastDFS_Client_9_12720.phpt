--TEST--
Translated seed (inferredbugs-java: FastDFS_Client/9)
--FILE--
<?php

class Connection {
    private $closed = false;

    public function close() {
        $this->closed = true;
    }

    public function isClosed() {
        return $this->closed;
    }

    public function isValid() {
        return !$this->closed;
    }
}

function createConnection() {
    return new Connection();
}

$conn = createConnection();
echo "当前连接状态: " . ($conn->isValid() ? "有效" : "无效") . "\n";
$conn->close();
--EXPECT--
