--TEST--
Translated seed (inferredbugs-csharp: Novell.Directory.Ldap.NETStandard/2)
--FILE--
<?php
$filters = [
    '',
    '(objectclass=*)',
    '(cn=foo\\2Abar)',
    '(&(cn=abc)(sn=def))',
    '(!(uid=*))',
    '(cn=ab*cd**ef*)',
    '(cn:dn:1.2.3:=value)',
    '(cn=\28test\29)',
    '(cn=\\)',
    '(cn=abc',
    'cn=abc)',
];

foreach ($filters as $f) {
    $s = $f;
    if ($s === '') {
        $s = '(objectclass=*)';
    }

    if (($p = strpos($s, '\\')) !== false) {
        $len = strlen($s);
        $out = '';
        for ($i = 0; $i < $len; $i++) {
            $c = $s[$i];
            if ($c === '\\' && $i + 1 < $len) {
                $n = $s[++$i];
                if ($n === '*' || $n === '(' || $n === ')' || $n === '\\') {
                    $out .= sprintf('%02x', ord($n));
                    continue;
                }
                $out .= '\\' . $n;
            } else {
                $out .= $c;
            }
        }
        $s = $out;
    }

    if ($s[0] !== '(' && $s[strlen($s) - 1] !== ')') {
        $s = '(' . $s . ')';
    }

    if ($s[0] !== '(' || $s[strlen($s) - 1] !== ')') {
        continue;
    }

    $paren = 0;
    for ($i = 0, $n = strlen($s); $i < $n; $i++) {
        if ($s[$i] === '(') $paren++;
        if ($s[$i] === ')') $paren--;
    }

    if ($paren !== 0) {
        continue;
    }

    if (preg_match('/\(([^=~<>:()]+)(=|>=|<=|~=|:=)(.*?)\)/', $s, $m)) {
        $attr = $m[1];
        $op = $m[2];
        $val = $m[3];
        if ($op === '=' && $val === '*') {
            $s = '(' . $attr . '=*)';
        } elseif ($op === '=' && strpos($val, '*') !== false) {
            $parts = explode('*', $val);
            $rebuilt = '(' . $attr . '=';
            $cnt = count($parts);
            for ($i = 0; $i < $cnt; $i++) {
                if ($i === 0) {
                    $rebuilt .= $parts[$i] . '*';
                } elseif ($i < $cnt - 1) {
                    $rebuilt .= $parts[$i] . '*';
                } else {
                    $rebuilt .= $parts[$i];
                }
            }
            $s = $rebuilt . ')';
        }
    }
}
echo "ok\n";
--EXPECT--
