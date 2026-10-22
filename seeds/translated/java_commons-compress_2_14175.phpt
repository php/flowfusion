--TEST--
Translated seed (inferredbugs-java: commons-compress/2)
--FILE--
<?php

class AbstractCompressor {

    public function getDefaultFileExtension() {
        return "zip";
    }

    public function compress($input) {
        $temp = tmpfile();
        $tempStream = stream_get_meta_data($temp)['uri'];
        $this->compressTo($input, $tempStream);
        return fopen($tempStream, 'r');
    }

    public function compressTo($inputFile, $outputFile) {
        $inputStream = fopen($inputFile, 'r');
        $outputStream = fopen($outputFile, 'w');
        $buffer = '';
        while (!feof($inputStream)) {
            $buffer = fread($inputStream, 1024);
            fwrite($outputStream, $buffer);
        }
        fclose($inputStream);
        fclose($outputStream);
    }

    public function decompress($inputFile) {
        $temp = tmpfile();
        $tempStream = stream_get_meta_data($temp)['uri'];
        $this->decompressTo($inputFile, $tempStream);
        return fopen($tempStream, 'r');
    }

    public function decompressTo($inputFile, $outputFile) {
        $inputStream = fopen($inputFile, 'r');
        $outputStream = fopen($outputFile, 'w');
        $buffer = '';
        while (!feof($inputStream)) {
            $buffer = fread($inputStream, 1024);
            fwrite($outputStream, $buffer);
        }
        fclose($inputStream);
        fclose($outputStream);
    }
}

$compressor = new AbstractCompressor();
$compressor->compress('input.txt');
$compressor->decompress('output.zip');
--EXPECT--
