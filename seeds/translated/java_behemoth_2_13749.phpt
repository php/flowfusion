--TEST--
Translated seed (inferredbugs-java: behemoth/2)
--FILE--
<?php

class ClusterDocIDDumper {

    public static function main() {
        $args = $GLOBALS['argv'];
        if (count($args) < 3) {
            echo "Usage: php script.php -i <input> -o <output>\n";
            exit(1);
        }
        
        $input = '';
        $output = '';
        
        for ($i = 1; $i < count($args); $i++) {
            if ($args[$i] === '-i') {
                $input = $args[++$i] ?? '';
            } elseif ($args[$i] === '-o') {
                $output = $args[++$i] ?? '';
            }
        }

        if ($input === '' || $output === '') {
            echo "Input and output paths must be provided.\n";
            exit(1);
        }

        $result = self::extract($input, $output);
        exit($result);
    }

    public static function extract($input, $output) {
        $data = self::readInput($input);
        if ($data === false) {
            return -1;
        }
        
        $handle = fopen($output, 'w');
        if ($handle === false) {
            return -1;
        }

        foreach ($data as $key => $vector) {
            if (isset($vector['name']) && strlen($vector['name']) > 2) {
                fwrite($handle, $vector['name'] . ',' . $key . "\n");
            } else {
                // Increment missing name counter
            }
        }
        
        fclose($handle);
        return 0;
    }

    public static function readInput($input) {
        if (!file_exists($input)) {
            return false;
        }
        // Simulating reading vectors with names from input
        return [
            1 => ['name' => 'Vector1'],
            2 => ['name' => 'Vec'],
            3 => ['name' => 'Vector3']
        ];
    }
}

ClusterDocIDDumper::main();

?>
--EXPECT--
