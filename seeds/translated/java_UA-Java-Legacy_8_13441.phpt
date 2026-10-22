--TEST--
Translated seed (inferredbugs-java: UA-Java-Legacy/8)
--FILE--
<?php

class MessageToChunks {
    private $msg;
    private $maxSendMessageSize;
    private $maxRecvChunkCount;
    private $maxPlaintextSize;

    public function __construct($msg, $maxSendMessageSize, $maxRecvChunkCount, $maxPlaintextSize) {
        if ($msg === null) {
            throw new InvalidArgumentException("null arg");
        }
        $this->msg = $msg;
        $this->maxSendMessageSize = $maxSendMessageSize;
        $this->maxRecvChunkCount = $maxRecvChunkCount;
        $this->maxPlaintextSize = $maxPlaintextSize;
    }

    public function call() {
        $len = strlen($this->msg);
        
        if ($len > $this->maxSendMessageSize && $this->maxSendMessageSize != 0) {
            throw new RuntimeException("Message too large");
        }

        $chunks = [];
        $offset = 0;

        while ($offset < $len) {
            $chunkSize = min($this->maxPlaintextSize, $len - $offset);
            $chunks[] = substr($this->msg, $offset, $chunkSize);
            $offset += $chunkSize;
        }

        if (count($chunks) > $this->maxRecvChunkCount && $this->maxRecvChunkCount != 0) {
            throw new RuntimeException("Too many chunks");
        }

        return $chunks;
    }
}

function main() {
    $msg = str_repeat('A', 1024); // A message of 1024 'A's
    $maxSendMessageSize = 2048;    // Max send message size
    $maxRecvChunkCount = 5;         // Max number of chunks to receive
    $maxPlaintextSize = 512;        // Max size of each chunk

    $messageToChunks = new MessageToChunks($msg, $maxSendMessageSize, $maxRecvChunkCount, $maxPlaintextSize);
    $chunks = $messageToChunks->call();
}

main();
--EXPECT--
