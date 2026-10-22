--TEST--
Translated seed (inferredbugs-java: MediathekView/53)
--FILE--
<?php

class MVStringBuilder {
    private $buffer;
    public function __construct() {
        $this->buffer = "";
    }
    public function append($str) {
        $this->buffer .= $str;
    }
    public function toString() {
        return $this->buffer;
    }
    public function indexOf($search, $start) {
        return strpos($this->buffer, $search, $start);
    }
    public function substring($start, $end) {
        return substr($this->buffer, $start, $end - $start);
    }
    public function clear() {
        $this->buffer = "";
    }
}

function getUrl($url) {
    return file_get_contents($url);
}

function main() {
    $ADRESSE = "http://kikaplus.net/clients/kika/kikaplus/";
    $MUSTER_THEMA = "<strong style=\"margin-left:10px;\">";
    $MUSTER_URL = "<a style=\"margin-left:20px;\" href=\"";
    $seite = new MVStringBuilder();
    $seite->append(getUrl($ADRESSE));
    
    $pos = 0;
    while (($pos = $seite->indexOf($MUSTER_THEMA, $pos)) !== false) {
        $pos += strlen($MUSTER_THEMA);
        $stop = $seite->indexOf($MUSTER_THEMA, $pos);
        $thema = "";
        if (($pos2 = $seite->indexOf("<", $pos)) !== false) {
            $thema = $seite->substring($pos, $pos2);
        }
        $pos1 = $pos;
        while (($pos1 = $seite->indexOf($MUSTER_URL, $pos1)) !== false) {
            $pos1 += strlen($MUSTER_URL);
            if ($stop !== false && $pos1 > $stop) {
                break;
            }
            if (($pos2 = $seite->indexOf("\"", $pos1)) !== false) {
                $url = $seite->substring($pos1, $pos2);
                if ($url !== "" && !preg_match('/^http:\/\//', $url) && !preg_match('/^\//', $url)) {
                    $datum = ""; // Simulating the date extraction part
                    $titel = ""; // Simulating the title extraction part
                }
            }
        }
    }
}

main();
--EXPECT--
