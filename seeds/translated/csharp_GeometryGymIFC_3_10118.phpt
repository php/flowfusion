--TEST--
Translated seed (inferredbugs-csharp: GeometryGymIFC/3)
--FILE--
<?php
class Triple{public $X,$Y,$Z;function __construct($x=null,$y=null,$z=null){$this->X=$x;$this->Y=$y;$this->Z=$z;}}
class BoundaryNodeCondition{public $name,$t,$r;function __construct($n,$t,$r){$this->name=$n;$this->t=$t;$this->r=$r;}}
class Factory{
    public $boundary=[];
    function findOrCreateBoundaryNodeCondition($isPointRestraint, $trans, $rot){
        $name=''; $partial=false;
        foreach([$trans,$rot] as $v) foreach(['X','Y','Z'] as $k){
            $x=$v->$k;
            if($x===null)$name.=$isPointRestraint?'F':'T';
            elseif(!$x->mRigid && $x->mStiffness!==null)$partial=true;
            else $name.=($x->mRigid?'T':'F');
        }
        if($partial)return new BoundaryNodeCondition('', $trans, $rot);
        if(isset($this->boundary[$name]))return $this->boundary[$name];
        return $this->boundary[$name]=new BoundaryNodeCondition($name,$trans,$rot);
    }
}
class StiffnessSelect{public $mRigid,$mStiffness;function __construct($rigid,$stiff=null){$this->mRigid=$rigid;$this->mStiffness=$stiff;}}
$f=new Factory();
$a=$f->findOrCreateBoundaryNodeCondition(true,new Triple(new StiffnessSelect(true),new StiffnessSelect(false),null),new Triple(null,new StiffnessSelect(true),new StiffnessSelect(false)));
$b=$f->findOrCreateBoundaryNodeCondition(true,new Triple(new StiffnessSelect(true),new StiffnessSelect(false),null),new Triple(null,new StiffnessSelect(true),new StiffnessSelect(false)));
$c=$f->findOrCreateBoundaryNodeCondition(false,new Triple(null,null,null),new Triple(null,null,null));
echo $a->name,"\n",$b->name,"\n",$c->name,"\n";
?>
--EXPECT--
