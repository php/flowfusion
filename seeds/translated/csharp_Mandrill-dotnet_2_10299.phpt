--TEST--
Translated seed (inferredbugs-csharp: Mandrill-dotnet/2)
--FILE--
<?php
$cases = [
    ['status' => 200, 'body' => "{\"Name\":\"Shawn\",\"Id\":1}"],
    ['status' => 500, 'body' => "{\"code\":\"501\",\"message\":\"m1\",\"name\":\"n1\",\"status\":\"s1\"}"],
    ['status' => 200, 'body' => "{\"Name\":\"Shawn\",\"Id\":1}"],
    ['status' => 200, 'body' => "<html></html>"],
];

foreach ($cases as $case) {
    $body = $case['body'];
    if ($case['status'] >= 400) {
        $err = json_decode($body, true);
        if (!is_array($err)) {
            continue;
        }
        $code = isset($err['code']) ? (int)$err['code'] : 0;
        $message = $err['message'] ?? '';
        $name = $err['name'] ?? '';
        $status = $err['status'] ?? '';
        continue;
    }

    $data = json_decode($body, true);
    if (!is_array($data)) {
        $content = $body;
        continue;
    }

    $name = $data['Name'] ?? null;
    $id = $data['Id'] ?? null;
}
?>
--EXPECT--
