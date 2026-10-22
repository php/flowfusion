--TEST--
Translated seed (inferredbugs-java: commons-codec/21)
--FILE--
<?php

class Base64Test {

    public function run() {
        $this->testIsStringBase64();
        $this->testBase64();
        $this->testCodec68();
        $this->testEncodeDecodeRandom();
    }

    public function testIsStringBase64() {
        $nullString = null;
        $emptyString = '';
        $validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
        $invalidString = $validString . "\0"; // append null character

        try {
            $this->isBase64($nullString);
            throw new Exception("Base64.isStringBase64() should not be null-safe.");
        } catch (TypeError $e) {
            // expected
        }

        assert($this->isBase64($emptyString));
        assert($this->isBase64($validString));
        assert(!$this->isBase64($invalidString));
    }

    public function testBase64() {
        $content = "Hello World";
        $encodedContent = $this->encodeBase64($content);
        assert($encodedContent === "SGVsbG8gV29ybGQ=");

        // Testing with invalid characters
        $decodeString = $this->decodeBase64("SGVsbG{\u00e9}gV29ybGQ=");
        assert($decodeString === "Hello World");
    }

    public function testCodec68() {
        $x = array('n', 'A', '=', '=', chr(156));
        $this->decodeBase64($x);
    }

    public function testEncodeDecodeRandom() {
        for ($i = 1; $i < 5; $i++) {
            $data = random_bytes(random_int(1, 10000));
            $enc = $this->encodeBase64($data);
            assert($this->isBase64($enc));
            $data2 = $this->decodeBase64($enc);
            assert(hash_equals($data, $data2));
        }
    }

    private function isBase64($data) {
        return preg_match('/^[A-Za-z0-9\/+]*={0,2}$/', $data);
    }

    private function encodeBase64($data) {
        return base64_encode($data);
    }

    private function decodeBase64($data) {
        return base64_decode($data, true);
    }
}

$test = new Base64Test();
$test->run();
--EXPECT--
