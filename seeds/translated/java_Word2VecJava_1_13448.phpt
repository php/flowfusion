--TEST--
Translated seed (inferredbugs-java: Word2VecJava/1)
--FILE--
<?php

class Word2VecModel {
    private $vocab;
    private $layerSize;
    private $vectors;

    public function __construct(array $vocab, int $layerSize, array $vectors) {
        $this->vocab = $vocab;
        $this->layerSize = $layerSize;
        $this->vectors = $vectors;
    }

    public static function fromTextFile(string $filename) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $firstLine = explode(' ', $lines[0]);
        $vocabSize = (int)$firstLine[0];
        $layerSize = (int)$firstLine[1];

        $vocab = [];
        $vectors = [];

        for ($n = 1; $n <= $vocabSize; $n++) {
            $values = explode(' ', $lines[$n]);
            $vocab[] = $values[0];

            for ($d = 1; $d <= $layerSize; $d++) {
                $vectors[] = (float)$values[$d];
            }
        }

        return new Word2VecModel($vocab, $layerSize, $vectors);
    }

    public static function fromBinFile(string $filename) {
        $handle = fopen($filename, 'rb');
        $firstLine = '';

        while (($char = fgetc($handle)) !== "\n") {
            $firstLine .= $char;
        }

        $firstLine = explode(' ', $firstLine);
        $vocabSize = (int)$firstLine[0];
        $layerSize = (int)$firstLine[1];

        $vocab = [];
        $vectors = [];

        for ($i = 0; $i < $vocabSize; $i++) {
            $word = '';
            while (($char = fgetc($handle)) !== ' ') {
                if ($char !== "\n") {
                    $word .= $char;
                }
            }
            $vocab[] = $word;

            for ($j = 0; $j < $layerSize; $j++) {
                $vectors[] = unpack('f', fread($handle, 4))[1];
            }
        }

        fclose($handle);
        return new Word2VecModel($vocab, $layerSize, $vectors);
    }
}

$model = Word2VecModel::fromTextFile('path/to/textfile.txt');
$model = Word2VecModel::fromBinFile('path/to/binfile.bin');
--EXPECT--
