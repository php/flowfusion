--TEST--
Translated seed (inferredbugs-csharp: tau/6)
--FILE--
<?php
class Drawable {}
class Container extends Drawable {
    public $RelativeSizeAxes;
    public $Anchor;
    public $Origin;
    public $Size;
    public $FillMode;
    public $FillAspectRatio;
    public $Child;
}
class Playfield {
    public $HitObjectContainer;
    public $children = [];
    public function AddRangeInternal(array $drawables) {
        $this->children = array_merge($this->children, $drawables);
    }
}
class TauPlayfield extends Playfield {
    public function __construct() {
        $this->HitObjectContainer = new stdClass();
        $this->load();
    }
    private function load() {
        $this->AddRangeInternal([
            (function () {
                $c = new Container();
                $c->RelativeSizeAxes = 'Both';
                $c->Anchor = 'Centre';
                $c->Origin = 'Centre';
                $c->Size = [0.6, 0.6];
                $c->FillMode = 'Fit';
                $c->FillAspectRatio = 1;
                $c->Child = $this->HitObjectContainer;
                return $c;
            })()
        ]);
    }
}
new TauPlayfield();
--EXPECT--
