--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/8)
--FILE--
<?php
class DataProcessor {
    public function forwardProcessDataStream($in, $out, $options, &$writtenBytes) {}
    public function reverseProcessDataStream($in, $out, $options, &$writtenBytes) {}
}

class ThreadSafeStream {
    public $data;
    function __construct($streamOrString = '', $takeOwnership = false) {
        $this->data = is_resource($streamOrString) ? stream_get_contents($streamOrString) : (string)$streamOrString;
    }
    function copyTo($out, $start = 0, $length = null) {
        $chunk = $length === null ? substr($this->data, $start) : substr($this->data, $start, $length);
        fwrite($out, $chunk);
    }
}

class StreamSendWrapper {
    public $threadSafeStream, $start, $length;
    function __construct($threadSafeStream) {
        $this->threadSafeStream = $threadSafeStream;
        $this->start = 0;
        $this->length = strlen($threadSafeStream->data);
    }
}

abstract class DataSerializer {
    public function serialiseDataObject($objectToSerialise, $dataProcessors = null, $options = null) {
        if ($objectToSerialise === null) throw new InvalidArgumentException();
        if ($objectToSerialise instanceof StreamSendWrapper) return $objectToSerialise;
        if (is_array($objectToSerialise)) {
            $bytes = pack('V', count($objectToSerialise));
            foreach ($objectToSerialise as $v) $bytes .= pack('V', (int)$v);
            return new StreamSendWrapper(new ThreadSafeStream($bytes, true));
        }
        $s = serialize($objectToSerialise);
        return new StreamSendWrapper(new ThreadSafeStream($s, true));
    }
}

$payload = [1, 2, 3, 4];
$serializer = new class extends DataSerializer {};
$wrapper = $serializer->serialiseDataObject($payload);
echo $wrapper->threadSafeStream->data;
?>
--EXPECT--
