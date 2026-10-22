--TEST--
Translated seed (inferredbugs-csharp: Jil/1)
--FILE--
<?php
mt_srand(314159265);

class User { public $id; public $name; public $active; public $score; }
class Answer { public $id; public $text; public $votes; public $author; }
class Question { public $id; public $title; public $answers; public $meta; }

$models = ['User', 'Answer', 'Question'];

function rand_str() {
    $n = mt_rand(1, 8);
    $s = '';
    for ($i = 0; $i < $n; $i++) $s .= chr(mt_rand(97, 122));
    return $s;
}

function make_single($t) {
    $o = new $t();
    foreach (get_object_vars($o) as $k => $v) {
        if ($k === 'id' || $k === 'votes' || $k === 'score') $o->$k = mt_rand(-5, 5);
        elseif ($k === 'active') $o->$k = (bool)mt_rand(0, 1);
        elseif ($k === 'name' || $k === 'text' || $k === 'title') $o->$k = rand_str();
        elseif ($k === 'author') $o->$k = rand_str();
        elseif ($k === 'answers') $o->$k = [rand_str(), rand_str(), rand_str()];
        elseif ($k === 'meta') $o->$k = [rand_str() => mt_rand(-10, 10), rand_str() => rand_str()];
    }
    return $o;
}

function make_list($t) {
    $n = mt_rand(1, 5);
    $a = [];
    for ($i = 0; $i < $n; $i++) $a[] = make_single($t);
    return $a;
}

function make_dict($t) {
    $n = mt_rand(2, 6);
    $a = [];
    for ($i = 0; $i < $n; $i++) $a[rand_str()] = make_single($t);
    return $a;
}

foreach ($models as $m) {
    $single = make_single($m);
    $list = make_list($m);
    $dict = make_dict($m);

    $payloads = [$single, $list, $dict];
    foreach ($payloads as $x) {
        $j = json_encode($x);
        $y = json_decode($j, true);
        json_encode($y);
        serialize($x);
        unserialize(serialize($x));
    }
}

echo "== Finished ==\n";
?>
--EXPECT--
