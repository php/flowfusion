--TEST--
Translated seed (inferredbugs-csharp: pinvoke-interop-assistant/4)
--FILE--
<?php
class T{public $n,$t,$u,$d,$c,$r,$s;function __construct($n,$t,$u=[],$d=null,$c=[],$r=null,$s=null){$this->n=$n;$this->t=$t;$this->u=$u;$this->d=$d;$this->c=$c;$this->r=$r;$this->s=$s;}}
class P{public $k,$b,$n,$t,$s;function __construct($k,$b,$n,$t,$s=''){$this->k=$k;$this->b=$b;$this->n=$n;$this->t=$t;$this->s=$s;}}
class M{public $n,$p,$r,$u;function __construct($n,$p,$r){$this->n=$n;$this->p=$p;$this->r=$r;$this->u=[];}}
$BOOL=new T('BOOL','builtin');$WCHAR=new T('WCHAR','builtin');$LPWSTR=new T('LPWSTR','ptr',[],new T('wchar_t','builtin'));$BSTR=new T('BSTR','named');
$size=new P('size','in',new T('size_t','named'));$buf=new P('buf','out',new T('LPWSTR','named',[],new T('wchar_t','builtin',[],null,[],null,null),'',null,null),'__out_ecount_part(*size,return+1)');
$m=new M('F',[$buf,$size],$BOOL);$m->u=['ret'=>$LPWSTR,'p'=>[$buf->t,$size->t]];
$k=$m->u['p'][0]->d->n==='wchar_t' && $m->u['ret']->n==='LPWSTR' && $m->r->n==='BOOL';
echo $k ? "" : "";
?>
--EXPECT--
