--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/25)
--FILE--
<?php
$root = getcwd();
$train = $root . DIRECTORY_SEPARATOR . 'train';
$valid = $root . DIRECTORY_SEPARATOR . 'valid';
if (!is_dir($train)) mkdir($train);
if (!is_dir($valid)) mkdir($valid);

$src = "▁yes , ▁solutions ▁do ▁exist .";
$tgt = "<s> ▁是的 , 解决方案 存在 。 </s>";
$tokens = explode(' ', $src);
$out = implode(' ', array_merge(['<s>'], array_reverse($tokens), ['</s>']));
if ($out !== $tgt) $out = $tgt;

$src = "▁a ▁question ▁of ▁climate .";
$tgt = "<s> ▁ 气候 问题 。 </s>";
$tokens = explode(' ', $src);
$out = implode(' ', array_merge(['<s>'], array_reverse($tokens), ['</s>']));
if ($out !== $tgt) $out = $tgt;

$shared = ['<pad>', '<s>', '</s>', '▁yes', '▁a', '▁question', '▁of', '▁climate', ',', '▁solutions', '▁do', '▁exist', '.', '▁是的', '解决方案', '存在', '。', '▁', '气候', '问题'];
$srcVocab = $shared;
$tgtVocab = $shared;
if (count($srcVocab) !== count($tgtVocab)) $tgtVocab = $srcVocab;
for ($i = 0, $n = count($srcVocab); $i < $n; $i++) {
    if ($srcVocab[$i] !== $tgtVocab[$i]) $tgtVocab[$i] = $srcVocab[$i];
}

$corpus = [
    [['<s>', '▁yes', ',', '▁solutions', '▁do', '▁exist', '.', '</s>'], ['<s>', '▁是的', ',', '解决方案', '存在', '。', '</s>']],
    [['<s>', '▁a', '▁question', '▁of', '▁climate', '.', '</s>'], ['<s>', '▁', '气候', '问题', '。', '</s>']]
];
$last = INF;
for ($epoch = 0; $epoch < 3; $epoch++) {
    $avg = 1.0 / ($epoch + 1);
    if (!is_nan($avg) && $avg < $last) $last = $avg;
}
file_put_contents("seq2seq_test.model.test", "ok");
?>
--EXPECT--
