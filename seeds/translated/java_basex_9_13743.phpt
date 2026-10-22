--TEST--
Translated seed (inferredbugs-java: basex/9)
--FILE--
<?php
class FTFuzzy {
    private $tp = [];
    private $li;
    private $ti;
    private $dat;

    public function __construct($db) {
        $file = "DATAFTX";
        $this->li = fopen($db . $file . "x", "rb");
        $this->ti = fopen($db . $file . "y", "rb");
        $this->dat = fopen($db . $file . "z", "rb");
        
        for ($i = 0; $i < 256; $i++) $this->tp[$i] = -1;
        $is = ord(fread($this->li, 1));
        while (--$is >= 0) {
            $p = ord(fread($this->li, 1));
            $this->tp[$p] = unpack('N', fread($this->li, 4))[1];
        }
        $this->tp[count($this->tp) - 1] = ftell($this->ti);
    }

    private function getPointerOnToken($tok) {
        $tl = strlen($tok);
        $l = $this->tp[$tl];
        if ($l == -1) return -1;

        $r = -1;
        $i = 1;
        do $r = $this->tp[$tl + $i++]; while ($r == -1);
        $o = $tl + 9;

        while ($l < $r) {
            $m = $l + ($r - $l) / 2 / $o * $o;
            $c = strcmp(substr(fread($this->ti, $m + $tl), $m, $tl), $tok);
            if ($c == 0) return $m;
            else if ($c < 0) $l = $m + $o;
            else $r = $m - $o;
        }
        return $r == $l && strcmp(substr(fread($this->ti, $l), $l, $tl), $tok) == 0 ? $l : -1;
    }

    public function close() {
        fclose($this->li);
        fclose($this->ti);
        fclose($this->dat);
    }
}

$db = 'path_to_db/';
$ftFuzzy = new FTFuzzy($db);
$ftFuzzy->close();
--EXPECT--
