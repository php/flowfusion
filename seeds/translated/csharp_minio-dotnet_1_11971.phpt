--TEST--
Translated seed (inferredbugs-csharp: minio-dotnet/1)
--FILE--
<?php
function xml_escape($s) {
    return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

class B {
    public $h = [];
    public $q = [];
    public $b = null;
    function a($k, $v) { $this->q[$k] = $v; }
    function c($k, $v) { $this->h[$k] = $v; }
    function d($x) { $this->b = $x; }
}

class S {
    public $o = 0;
    public $l = 0;
    public $s = false;
    public $h = [];
    function w($o, $l) {
        $this->s = true;
        $this->o = $o < 0 ? 0 : $o;
        $this->l = $l < 0 ? 0 : $l;
        return $this;
    }
    function v(B $r) {
        if ($this->s) {
            $r->c("Range", "bytes=" . $this->o . "-" . ($this->o + $this->l - 1));
        }
    }
}

class P {
    public $u = "";
    public $t = [];
    function x($n, $v) { $this->t[] = [$n, $v]; return $this; }
    function y(B $r) {
        $r->a("delete", "");
        $x = "<Delete>";
        foreach ($this->t as $p) $x .= "<Object><Key>" . xml_escape($p[0]) . "</Key><VersionId>" . xml_escape($p[1]) . "</VersionId></Object>";
        $x .= "<Quiet>true</Quiet></Delete>";
        $r->d($x);
        $r->c("Content-Md5", base64_encode(md5($x, true)));
    }
}

class G {
    public $u = "";
    public $o = "";
    public $v = "";
    public $m = [];
    function y(B $r) {
        $s = $this->u . "/" . rawurlencode($this->o);
        if ($this->v !== "") $s .= "?versionId=" . $this->v;
        $r->c("x-amz-copy-source", $s);
        foreach ($this->m as $k => $v) $r->c($k, $v);
    }
}

$r = new B();
(new S())->w(0, 0)->v($r);
$p = (new P())->x("a", "1")->x("a", "2");
$p->y($r);
$g = new G();
$g->u = "bucket";
$g->o = "a b";
$g->v = "ver";
$g->m = ["x-amz-meta-k" => "v"];
$g->y($r);
echo "";
?>
--EXPECT--
