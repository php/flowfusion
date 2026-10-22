--TEST--
Translated seed (inferredbugs-csharp: ChameleonForms/1)
--FILE--
<?php

class TwitterBootstrapFormTemplate {
    public function Button($content, $type, $id, $value, $attributes) {
        $attributesString = $this->renderAttributes($attributes);
        if ($type === 'submit') {
            return "<input type='submit' id='$id' value='$value' $attributesString/>";
        } else {
            return "<button $attributesString>$content</button>";
        }
    }

    private function renderAttributes($attributes) {
        if (is_null($attributes)) {
            return '';
        }
        $result = '';
        foreach ($attributes as $key => $value) {
            $result .= "$key='$value' ";
        }
        return trim($result);
    }
}

function runTests() {
    $t = new TwitterBootstrapFormTemplate();

    // Test 1
    $result1 = $t->Button(null, null, null, "value", null);
    echo $result1 . "\n";

    // Test 2
    $result2 = $t->Button(null, "submit", "id", "value", ['class' => 'asdf']);
    echo $result2 . "\n";

    // Test 3
    $result3 = $t->Button("<strong>asdf</strong>", null, null, null, null);
    echo $result3 . "\n";

    // Test 4
    $result4 = $t->Button("<strong>asdf</strong>", "submit", "id", "value", ['class' => 'asdf']);
    echo $result4 . "\n";

    // Test 5
    $result5 = $t->Button("content", "submit", null, null, ['class' => 'asdf', 'style' => 'danger', 'size' => 'large']);
    echo $result5 . "\n";
}

runTests();
--EXPECT--
