--TEST--
Translated seed (inferredbugs-csharp: NPoco/3)
--FILE--
<?php
class P{public string $n;function __construct($n){$this->n=$n;}}
class D{function EscapeSqlIdentifier($s){return '"'.$s.'"';}function EscapeTableName($s){return '"'.$s.'"';}function BuildPageQueries($skip,$rows,$sql,&$parms,&$count,&$page){$page=$sql." LIMIT $skip,$rows";$count=$sql;}}
class C{public array $Columns=[];public array $QueryColumns=[];public $TableInfo;function __construct(){$this->TableInfo=(object)['TableName'=>'People'];$this->Columns=['Id'=>(object)['MemberInfo'=>new P('Id'),'ColumnName'=>'id'],'Name'=>(object)['MemberInfo'=>new P('Name'),'ColumnName'=>'name'],'Age'=>(object)['MemberInfo'=>new P('Age'),'ColumnName'=>'age']];$this->QueryColumns=['id','name','age'];}}
class E{public array $_params=[];public int $_paramCounter=0;public $Context;public $ModelDef;public $db;public $dt;public $where='';public $select='';public $order='';public $group='';public $having='';public $skip=null;public $rows=null;function __construct(){$this->db=new D;$this->dt=$this->db;$this->ModelDef=new C;$this->Context=(object)['UpdateFields'=>[]];}
function p($v){$this->_params[]=$v;return '@'.($this->_paramCounter++);}
function w($s){$this->where=$s?('WHERE '.$s):'';return $this;}
function s($f=''){$this->select=$f?:'SELECT "id", "name", "age" 
FROM "People"';return $this;}
function o($f){$this->order='ORDER BY '.$f;return $this;}
function l($skip=null,$rows=null){$this->skip=$skip;$this->rows=$rows;return $this;}
function t(){ $sql=$this->select."\n".$this->where."\n".$this->group."\n".$this->having."\n".$this->order; if($this->rows===null||$this->rows==0)return $sql; $parms=$this->_params; $this->db->BuildPageQueries($this->skip??0,$this->rows,$sql,$parms,$c,$p); $this->_params=$parms; return $p;}}
$e=new E;
$e->s()->w('("age" > '.$e->p(0).' AND "name" LIKE '.$e->p('%A%').')')->o('"age" DESC, "name" ASC')->l(1,2);
echo $e->t(),PHP_EOL;
?>
--EXPECT--
