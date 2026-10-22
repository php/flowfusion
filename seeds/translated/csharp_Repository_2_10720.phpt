--TEST--
Translated seed (inferredbugs-csharp: Repository/2)
--FILE--
<?php
class FileSystemOptions {
    public $serializer;
    public $folderPath;
    public $fileExtension;
    public $streamGenerator;
    public function __construct() {
        $this->serializer = null;
        $this->folderPath = '';
        $this->fileExtension = '.txt';
        $this->streamGenerator = new StandardStreamGenerator();
    }
}

abstract class StreamGenerator {
    abstract public function getReadStream(string $filePath);
    abstract public function getWriteStream(string $filePath);
}

class StandardStreamGenerator extends StreamGenerator {
    public function getReadStream(string $filePath) {
        return fopen($filePath, 'rb');
    }
    public function getWriteStream(string $filePath) {
        return fopen($filePath, 'wb');
    }
}

class GZipStreamGenerator extends StreamGenerator {
    public function getReadStream(string $filePath) {
        return gzopen($filePath, 'rb');
    }
    public function getWriteStream(string $filePath) {
        return gzopen($filePath, 'wb');
    }
}

$opt = new FileSystemOptions();
$tmp = tempnam(sys_get_temp_dir(), 'fs');
$w = $opt->streamGenerator->getWriteStream($tmp);
fwrite($w, "x");
fclose($w);
$r = $opt->streamGenerator->getReadStream($tmp);
fread($r, 1);
fclose($r);
unlink($tmp);

$gz = new GZipStreamGenerator();
$tmp2 = tempnam(sys_get_temp_dir(), 'gz');
$w = $gz->getWriteStream($tmp2);
gzwrite($w, "x");
gzclose($w);
$r = $gz->getReadStream($tmp2);
gzread($r, 1);
gzclose($r);
unlink($tmp2);
?>
--EXPECT--
