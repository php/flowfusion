--TEST--
Translated seed (inferredbugs-csharp: Repository/1)
--FILE--
<?php
class FileSystemOptions {
    public $serializer;
    public $folderPath;
    public $fileExtension;
    public $streamGenerator;
    function __construct() {
        $this->serializer = null;
        $this->folderPath = "";
        $this->fileExtension = ".txt";
        $this->streamGenerator = new StandardStreamGenerator();
    }
}

abstract class StreamGenerator {
    abstract public function getReadStream($filePath);
    abstract public function getWriteStream($filePath);
}

class StandardStreamGenerator extends StreamGenerator {
    public function getReadStream($filePath) {
        return fopen($filePath, "rb");
    }
    public function getWriteStream($filePath) {
        return fopen($filePath, "wb");
    }
}

class GZipStreamGenerator extends StreamGenerator {
    public function getReadStream($filePath) {
        return gzopen($filePath, "rb");
    }
    public function getWriteStream($filePath) {
        return gzopen($filePath, "wb");
    }
}

$opt = new FileSystemOptions();
$tmp = tempnam(sys_get_temp_dir(), "fs");
$w = $opt->streamGenerator->getWriteStream($tmp);
fwrite($w, "x");
fclose($w);
$r = $opt->streamGenerator->getReadStream($tmp);
fread($r, 1);
fclose($r);
unlink($tmp);

$gz = new GZipStreamGenerator();
$tmp2 = tempnam(sys_get_temp_dir(), "fs");
$w = $gz->getWriteStream($tmp2);
fwrite($w, "x");
gzclose($w);
$r = $gz->getReadStream($tmp2);
gzread($r, 1);
gzclose($r);
unlink($tmp2);
?>
--EXPECT--
