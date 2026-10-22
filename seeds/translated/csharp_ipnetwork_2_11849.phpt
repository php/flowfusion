--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/2)
--FILE--
<?php
function h($s){$s=strtolower($s);$p=explode('::',$s,2);$a=$p[0]===''?[]:explode(':',$p[0]);$b=isset($p[1])&&$p[1]!==''?explode(':',$p[1]):[];$m=8-count($a)-count($b);if($m<0) throw new Exception();$r=array_merge($a,array_fill(0,$m,'0'),$b);$o=[];foreach($r as $x){$o[]=str_pad(ltrim($x,'0'),'0',STR_PAD_LEFT)==='0'?'0':ltrim($x,'0');}$i=0;while($i<7&&$o[$i]==='0')$i++;$j=7;while($j>0&&$o[$j]==='0')$j--;return implode(':',array_slice($o,0,$i)).'::'.implode(':',array_slice($o,$j+1));}
function p($s){if($s===null||$s==='') throw new Exception();$c=strpos($s,'/');if($c!==false){$ip=substr($s,0,$c);$cidr=(int)substr($s,$c+1);}else{$ip=$s;$cidr=64;}if(strpos($ip,' ')!==false){$t=explode(' ',$ip);$ip=$t[0];}$ip=h($ip);if($cidr<0||$cidr>128) throw new Exception();return [$ip,$cidr];}
p('2001:0db8::/124');
p('::/0');
p('ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/128');
p('2FFF:FFFF:123::/60');
p('2FFF:FFFE:123::/60');
p('2FFF:FFFC:123::/60');
p('2FFF:FFFA:123::/60');
p('FFFF:FFF1:123::/60');
try{p('xxxx::');}catch(Exception $e){}
try{p('::/129');}catch(Exception $e){}
try{p(null);}catch(Exception $e){}
echo "ok\n";
?>
--EXPECT--
