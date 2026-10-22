--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/22)
--FILE--
<?php
class FK{public array $outerKeys;function __construct($k){$this->outerKeys=[$k];}}
class Nav{public string $key;public string $member;public int $start=0;public int $fieldCount=0;function __construct($k,$m){$this->key=$k;$this->member=$m;}}
class Main{public int $Id=1;public string $Name='x';public ?Client $Client=null;}
class Client{public int $Id=2;public string $Code='c';public ?AccountList $AccountList=null;}
class AccountList{public int $Id=3;public string $Label='l';}
$meta=[
    Main::class=>['Id'=>null,'Name'=>null,'Client'=>new FK('ClientId')],
    Client::class=>['Id'=>null,'Code'=>null,'AccountList'=>new FK('AccountListId')],
    AccountList::class=>['Id'=>null,'Label'=>null]
];
$pick=[];$navs=[];$sql='';$alias='t0';
$obj=new Main();$obj->Client=new Client();$obj->Client->AccountList=new AccountList();
foreach((new ReflectionClass($obj))->getProperties() as $p){
    $n=$p->getName();$t=$p->getType();$tn=$t?$t->getName():'';
    if(isset($meta[Main::class][$n]) && $meta[Main::class][$n] instanceof FK){
        $k='Main.'.$n;
        if(!isset($navs[$k])){$navs[$k]=new Nav($k,$n);}
        $sql.="CASE WHEN $alias.".$meta[Main::class][$n]->outerKeys[0]." IS NULL THEN NULL ELSE $alias.".$meta[Main::class][$n]->outerKeys[0]." END AS nav_split, ";
        $pick[]='__navsplit__';
    }else{
        $sql.="$alias.$n AS $n, ";
        $pick[]=$n;
    }
}
$clientAlias='t1';
foreach((new ReflectionClass($obj->Client))->getProperties() as $p){
    $n=$p->getName();
    $sql.="$clientAlias.$n AS Client_$n, ";
    $pick[]='Client_'.$n;
}
echo rtrim($sql,", ");
--EXPECT--
