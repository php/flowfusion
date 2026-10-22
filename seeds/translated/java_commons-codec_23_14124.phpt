--TEST--
Translated seed (inferredbugs-java: commons-codec/23)
--FILE--
<?php

class Base64Test {
    public function run() {
        $content = "Hello World";
        $encodedContent = base64_encode($content);
        if ($encodedContent !== "SGVsbG8gV29ybGQ=") {
            throw new Exception("Encoding failed");
        }

        $invalidString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI" . chr(0);
        if (base64_decode($invalidString, true) !== false) {
            throw new Exception("Decoding invalid string should fail");
        }

        for ($i = 0; $i <= 150; $i++) {
            $data = random_bytes($i);
            $encoded = base64_encode($data);
            if (base64_decode($encoded) !== $data) {
                throw new Exception("Encode/decode mismatch for size $i");
            }
        }

        $byteArray = [0, 0, 0];
        if (base64_encode(pack('C*', ...$byteArray)) !== "AAAA") {
            throw new Exception("Triplets encoding failed");
        }

        $uuid = random_bytes(16);
        $urlSafe = str_replace(['+', '/'], ['-', '_'], base64_encode($uuid));
        if (base64_decode($urlSafe) === false) {
            throw new Exception("URL safe decoding failed");
        }

        echo "All tests passed!\n";
    }
}

$test = new Base64Test();
$test->run();
--EXPECT--
