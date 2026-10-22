--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/31)
--FILE--
<?php
function ip2u($ip){$n=ip2long($ip); if($n<0)$n+=4294967296; return $n;}
function u2ip($n){return long2ip($n & 4294967295);}
function parse_net($s){
    if($s===null||$s==='') return null;
    if(!preg_match('~^(\d{1,3}(?:\.\d{1,3}){3})(?:/(\d{1,2}))?$~',$s,$m)) return false;
    $ip=$m[1]; $cidr=isset($m[2])?(int)$m[2]:null;
    foreach(explode('.',$ip) as $o) if($o<0||$o>255) return false;
    if($cidr!==null && ($cidr<0||$cidr>32)) return false;
    $ipn=ip2u($ip);
    if($cidr===null){
        $first=($ipn>>24)&255;
        $cidr=($first<128)?8:(($first<192)?16:24);
    }
    $mask=$cidr===0?0:(4294967295<<(32-$cidr))&4294967295;
    $net=$ipn & $mask;
    $bcast=$net | (~$mask & 4294967295);
    $first=$cidr>=31?$net:($net+1);
    $last=$cidr>=31?$bcast:($bcast-1);
    $usable=$cidr>=31?0:($bcast-$net-1);
    return [$net,$mask,$bcast,$first,$last,$cidr,$usable];
}
$cases=['192.168.168.100/24','0.0.0.0/0','0.0.0.0/32','255.255.255.255/32','10.0.0.0','172.0.0.0','192.0.0.0'];
foreach($cases as $c){$r=parse_net($c); if($r!==false&&$r!==null){u2ip($r[0]); u2ip($r[1]); u2ip($r[2]); u2ip($r[3]); u2ip($r[4]);}}
foreach(['0.0.0.0','255.255.255.255','255.255.255.0','255.255.0.0','255.0.0.0'] as $m){$r=parse_net($m.'/32');}
$start=ip2u('192.168.168.0'); $end=ip2u('192.168.168.255'); $mask=4294967040; $net=$start&$mask; $b=$net|(~$mask&4294967295); u2ip($net); u2ip($b);
$ips=['1.1.1.1','255.255.255.255','2.2.2.2','0.0.0.0']; sort($ips, SORT_STRING);
echo "";
?>
--EXPECT--
