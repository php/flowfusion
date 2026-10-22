--TEST--
Translated seed (inferredbugs-csharp: sentakki/3)
--FILE--
<?php
class P{public array $c=[];public float $d;function __construct(array $c){$this->c=$c;$this->d=0;for($i=1;$i<count($c);$i++){$a=$c[$i-1];$b=$c[$i];$dx=$b[0]-$a[0];$dy=$b[1]-$a[1];$this->d+=sqrt($dx*$dx+$dy*$dy);}}function p(float $t):array{$n=count($this->c);if($n<2)return $this->c[0]??[0,0];$x=($n-1)*max(0,min(1,$t));$i=(int)$x;if($i>=$n-1)return $this->c[$n-1];$f=$x-$i;return [$this->c[$i][0]+($this->c[$i+1][0]-$this->c[$i][0])*$f,$this->c[$i][1]+($this->c[$i+1][1]-$this->c[$i][1])*$f];}}
$path=new P([[0,0],[50,0],[80,40],[120,40],[150,0],[200,0]]);
$parts=[];$cur=[];foreach($path->c as $i=>$pt){$cur[]=$pt;if($i&&$pt[2]??0){}
}
$seg=[];$cur=[];foreach($path->c as $i=>$pt){$cur[]=$pt;if($i>0&&$pt[2]===1){$seg[]=new P($cur);$cur=[$pt];}}if($cur)$seg[]=new P($cur);
$chevrons=[];foreach($seg as $s){$n=(int)ceil(($s->d-68)*(64/(2*pi()*297)));$n=max(1,$n);$safe=$s->d-68;$step=$safe/$n;$prev=$s->p(0);for($i=0;$i<$n;$i++){$prog=$n===1?0:$i/($n-1);$pos=$s->p((($prog*$safe)+34)/max(1e-9,$s->d));$chevrons[]=[$pos,$prev];$prev=$pos;}}
$alpha=[];$completed=2;for($i=1;$i<=count($chevrons);$i++)$alpha[]=$i<=$completed?0:1;
echo count($chevrons),"\n";
?>
--EXPECT--
