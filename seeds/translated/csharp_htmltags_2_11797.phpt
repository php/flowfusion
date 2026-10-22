--TEST--
Translated seed (inferredbugs-csharp: htmltags/2)
--FILE--
<?php
class Subject {
    public ?string $Value = null;
}

$subject = new Subject();
$subject->Value = "Value";

$ref = new ReflectionProperty(Subject::class, 'Value');
$attrs = $ref->getAttributes();
$display = ['Name' => 'Hello', 'Prompt' => 'Value Here'];
$format = ['DataFormatString' => 'Foo {0} Bar', 'ApplyFormatInEditMode' => true, 'NullDisplayText' => 'Bunny'];

$value = $subject->Value;
$label = $display['Name'];
$displayText = str_replace('{0}', $value ?? $format['NullDisplayText'], $format['DataFormatString']);
$inputValue = $displayText;
$placeholder = $display['Prompt'];

echo $label, PHP_EOL;
echo $displayText, PHP_EOL;
echo $inputValue, PHP_EOL;
echo $placeholder, PHP_EOL;

$subject->Value = null;
$value = $subject->Value;
$displayText = str_replace('{0}', $value ?? $format['NullDisplayText'], $format['DataFormatString']);
$inputValue = $displayText;

echo $displayText, PHP_EOL;
echo $inputValue, PHP_EOL;

$invalid = $subject->Value === null;
$class = $invalid ? 'input-validation-error' : '';
$dataVal = $subject->Value !== null ? 'true' : null;
$dataValMax = $subject->Value !== null ? '10' : null;

echo $class, PHP_EOL;
echo $dataVal ?? '', PHP_EOL;
echo $dataValMax ?? '', PHP_EOL;

$validationTag = $invalid ? '<span class="field-validation-error">The Value field is required.</span>' : '<span class="field-validation-valid"></span>';
echo $validationTag, PHP_EOL;

$subject->Value = "value";
$invalid = $subject->Value === null;
$validationTag = $invalid ? '<span class="field-validation-error">The Value field is required.</span>' : '<span class="field-validation-valid"></span>';
echo $validationTag, PHP_EOL;
?>
--EXPECT--
