--TEST--
Translated seed (inferredbugs-csharp: Novell.Directory.Ldap.NETStandard/1)
--FILE--
<?php
$filters = ['(objectclass=*)', '(cn=ab*cd*ef)', '(sn=\\2A)', '(!(uid=jsmith))', '(&(cn=John)(sn=Doe))', '(mail:caseExactMatch:=a\\28b\\29)'];
foreach ($filters as $f) {
    $s = $f;
    if ($s === '' || $s === null) $s = '(objectclass=*)';
    if (strpos($s, '\\') !== false) {
        $s = preg_replace_callback('/\\\\([0-9A-Fa-f]{2}|[*()\\\\])/', function ($m) {
            return $m[1] === '*' ? '\\2a' : ($m[1] === '(' ? '\\28' : ($m[1] === ')' ? '\\29' : '\\5c'));
        }, $s);
    }
    if ($s[0] !== '(' && $s[strlen($s) - 1] !== ')') $s = '(' . $s . ')';
    $depth = 0;
    for ($i = 0, $n = strlen($s); $i < $n; $i++) {
        if ($s[$i] === '(') $depth++;
        elseif ($s[$i] === ')') $depth--;
    }
    if ($depth !== 0) continue;
    if (preg_match('/^\(([^=~<>:()]+)(=|~=|<=|>=|:=)(.*?)\)$/', $s, $m)) {
        $attr = $m[1];
        $op = $m[2];
        $val = $m[3];
        if ($op === '=' && $val === '*') {
            $out = '(' . $attr . '=*)';
        } elseif ($op === '=' && strpos($val, '*') !== false) {
            $parts = explode('*', $val);
            $out = '(' . $attr . '=';
            $first = true;
            foreach ($parts as $k => $p) {
                if ($k === 0 && $p !== '') $out .= $p . '*';
                elseif ($k === count($parts) - 1 && $p !== '') $out .= $p;
                else if ($p !== '') $out .= ($first ? '' : '*') . $p . '*';
                $first = false;
            }
            if (substr($val, -1) === '*') $out .= '*';
            $out .= ')';
        } else {
            $out = '(' . $attr . $op . $val . ')';
        }
        $out;
    }
}
echo "ok\n";
?>
--EXPECT--
