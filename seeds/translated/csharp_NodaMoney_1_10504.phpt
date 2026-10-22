--TEST--
Translated seed (inferredbugs-csharp: NodaMoney/1)
--FILE--
<?php
$currency = (object)[
    'DecimalDigits' => 2,
    'Symbol' => '$',
    'Code' => 'USD',
    'EnglishName' => 'US Dollar',
];

$amount = -1234.5;
$formatProvider = new NumberFormatter('en_US', NumberFormatter::CURRENCY);

$format = 'I';
if ($format !== null && trim($format) !== '' && str_starts_with($format, 'I') && strlen($format) >= 1 && strlen($format) <= 2) {
    $format = str_replace('I', 'C', $format);
    $symbol = $currency->Code;
    $digits = $currency->DecimalDigits;
} else {
    $symbol = $currency->Symbol;
    $digits = $currency->DecimalDigits;
}

if ($format === null || $format === 'G') {
    $format = 'C';
}

if (str_starts_with($format, 'F')) {
    $format = str_replace('F', 'N', $format);
    if (strlen($format) === 1) {
        $format .= (string)$currency->DecimalDigits;
    }
    echo number_format($amount, $digits, '.', ',') . ' ' . $currency->EnglishName;
} else {
    echo $symbol . number_format($amount, $digits, '.', ',');
}
?>
--EXPECT--
