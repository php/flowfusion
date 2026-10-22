--TEST--
Translated seed (inferredbugs-java: Factions/1)
--FILE--
<?php

function write($file, $content) {
    $out = fopen($file, 'w');
    fwrite($out, $content);
    fclose($out);
}

function read($file) {
    $inStream = fopen($file, 'r');
    $content = "";

    while (($line = fgets($inStream)) !== false) {
        $content .= $line;
    }
    fclose($inStream);

    return $content;
}

function inputStreamToReader($in) {
    $available = stream_get_contents($in, 3);
    rewind($in);
    
    if (strlen($available) < 3) {
        return $in;
    }

    $byte1 = ord($available[0]);
    $byte2 = ord($available[1]);

    if ($byte1 === 0xFF && $byte2 === 0xFE) {
        return stream_filter_append($in, 'convert.iconv.UTF-16LE//IGNORE');
    } elseif ($byte1 === 0xFF && $byte2 === 0xFF) {
        return stream_filter_append($in, 'convert.iconv.UTF-16BE//IGNORE');
    } else {
        $byte3 = ord($available[2]);
        if ($byte1 === 0xEF && $byte2 === 0xBB && $byte3 === 0xBF) {
            return $in;
        } else {
            $first3 = pack("C*", $byte1, $byte2, $byte3);
            $firstChars = fopen('php://temp', 'r+');
            fwrite($firstChars, $first3);
            rewind($firstChars);
            return new AppendStream($firstChars, $in);
        }
    }
}

class AppendStream {
    private $streams;

    public function __construct(...$streams) {
        $this->streams = $streams;
    }
    
    public function __toString() {
        $result = '';
        foreach ($this->streams as $stream) {
            $result .= stream_get_contents($stream);
        }
        return $result;
    }
}

$file = 'test.txt';
write($file, "Sample content\n");
$content = read($file);
echo $content;

?>
--EXPECT--
