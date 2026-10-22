--TEST--
Translated seed (inferredbugs-java: Word2VecJava/3)
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

    public static function fromTextFile(string $filename): Word2VecModel {
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        $vocabSize = (int)explode(" ", $lines[0])[0];
        $layerSize = (int)explode(" ", $lines[0])[1];
        $vocab = [];
        $vectors = [];

        for ($n = 1; $n < count($lines); $n++) {
            $values = explode(" ", $lines[$n]);
            array_push($vocab, $values[0]);
            for ($d = 1; $d < count($values); $d++) {
                $vectors[] = (float)$values[$d];
            }
        }

        return new Word2VecModel($vocab, $layerSize, $vectors);
    }
}

$filename = "path_to_your_file.txt"; // Change to your file path
$model = Word2VecModel::fromTextFile($filename);
?>
--EXPECT--
