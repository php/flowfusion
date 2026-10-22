--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/26)
--FILE--
<?php
$root = getcwd();
$train = $root . DIRECTORY_SEPARATOR . 'train';
$valid = $root . DIRECTORY_SEPARATOR . 'valid';
if (!is_dir($train)) mkdir($train, 0777, true);
if (!is_dir($valid)) mkdir($valid, 0777, true);
file_put_contents($train . DIRECTORY_SEPARATOR . 'train.enu.snt', "▁yes , ▁solutions ▁do ▁exist .\n▁a ▁question ▁of ▁climate .\n");
file_put_contents($train . DIRECTORY_SEPARATOR . 'train.chs.snt', "▁是的 , 解决方案 存在 。\n▁ 气候 问题 。\n");
copy($train . DIRECTORY_SEPARATOR . 'train.enu.snt', $valid . DIRECTORY_SEPARATOR . 'valid.enu.snt');
copy($train . DIRECTORY_SEPARATOR . 'train.chs.snt', $valid . DIRECTORY_SEPARATOR . 'valid.chs.snt');

$shared = ["<s>", "</s>", "▁yes", ",", "▁solutions", "▁do", "▁exist", ".", "▁a", "▁question", "▁of", "▁climate", "▁是的", "解决方案", "存在", "▁", "气候", "问题", "。"];
$srcVocab = $shared;
$tgtVocab = $shared;
if (count($srcVocab) !== count($tgtVocab)) exit(1);
for ($i = 0; $i < count($srcVocab); $i++) {
    if ($srcVocab[$i] !== $tgtVocab[$i]) exit(1);
}

foreach ([
    [["<s>", "▁yes", ",", "▁solutions", "▁do", "▁exist", ".", "</s>"], ["<s>", "▁", "是的", ",", "解决方案", "存在", "。", "</s>"]],
    [["<s>", "▁a", "▁question", "▁of", "▁climate", ".", "</s>"], ["<s>", "▁", "气候", "问题", "。", "</s>"]],
] as $pair) {
    [$src, $tgt] = $pair;
    if ($src[0] !== "<s>" || $src[count($src) - 1] !== "</s>") exit(1);
    if ($tgt[0] !== "<s>" || $tgt[count($tgt) - 1] !== "</s>") exit(1);
}

$model = $root . DIRECTORY_SEPARATOR . 'seq2seq_test.model.test';
file_put_contents($model, "ok");
if (!file_exists($model)) exit(1);
?>
--EXPECT--
