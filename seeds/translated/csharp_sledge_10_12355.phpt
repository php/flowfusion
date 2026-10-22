--TEST--
Translated seed (inferredbugs-csharp: sledge/10)
--FILE--
<?php
$requestedTextureQueue = [];
$textureProviders = [
    new class {
        public function exists($x) { return str_starts_with($x, 'a'); }
        public function request($list) { $this->req = $list; }
        public function popRequestedTextures($n) { return []; }
    },
    new class {
        public function exists($x) { return strlen($x) > 1; }
        public function request($list) { $this->req = $list; }
        public function popRequestedTextures($n) {
            return array_map(fn($name) => (object)['Name' => $name, 'Bitmap' => null, 'Width' => 1, 'Height' => 1, 'Flags' => 0], array_slice(['x', 'yy', 'zzz'], 0, $n));
        }
    }
];
$textures = new class {
    public $created = [];
    public function create($name, $bitmap, $width, $height, $flags) { $this->created[] = [$name, $width, $height, $flags]; }
};

$requestedTextureQueue[] = 'a';
$requestedTextureQueue[] = 'ab';
$requestedTextureQueue[] = 'abc';
$requestedTextureQueue[] = 'b';

foreach ($textureProviders as $tp) {
    $list = array_values(array_filter($requestedTextureQueue, fn($x) => $tp->exists($x)));
    $requestedTextureQueue = array_values(array_diff($requestedTextureQueue, $list));
    $tp->request($list);
}
foreach ($textureProviders as $tp) {
    foreach ($tp->popRequestedTextures(5) as $td) {
        $textures->create($td->Name, $td->Bitmap, $td->Width, $td->Height, $td->Flags);
    }
}

$sceneData = [];
$activeScene = null;
$scene1 = (object)['id' => 1];
$scene2 = (object)['id' => 2];
$sceneData[spl_object_id($scene1)] = null;
$sceneData[spl_object_id($scene2)] = null;
$activeScene = $scene1;
if ($activeScene === $scene1) $activeScene = null;
if (array_key_exists(spl_object_id($scene1), $sceneData) && $sceneData[spl_object_id($scene1)] !== null) unset($sceneData[spl_object_id($scene1)]);
unset($sceneData[spl_object_id($scene1)]);
?>
--EXPECT--
