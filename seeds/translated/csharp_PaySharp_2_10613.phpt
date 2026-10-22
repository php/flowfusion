--TEST--
Translated seed (inferredbugs-csharp: PaySharp/2)
--FILE--
<?php
$g = [];
$g["return_code"] = "SUCCESS";
$g["result_code"] = "SUCCESS";
$g["trade_state"] = "USERPAYING";
$g["nonce_str"] = bin2hex(random_bytes(8));
$g["appid"] = "wx" . random_int(100000, 999999);
$g["mch_id"] = (string)random_int(1000000000, 9999999999);
$g["sign_type"] = "MD5";
$g["package"] = "Sign=WXPay";
$g["timestamp"] = (string)time();
$g["prepay_id"] = "prepay_" . random_int(100000, 999999);
$g["code_url"] = "weixin://wxpay/bizpayurl?pr=" . bin2hex(random_bytes(6));
$g["mweb_url"] = "https://pay.weixin.qq.com/" . bin2hex(random_bytes(4));
$g["transaction_id"] = "";
$g["out_trade_no"] = "out_" . random_int(100000, 999999);
$g["return_msg"] = "OK";
$g["recall"] = "N";

for ($i = 0; $i < 5; $i++) {
    $g["trade_state"] = ($i === 4) ? "SUCCESS" : "USERPAYING";
    if (strtolower($g["trade_state"]) === "success") {
        break;
    }
}

$g["trade_state"] = strtolower($g["trade_state"]);
$g["return_code"] = strtoupper($g["return_code"]);
$g["result_code"] = strtoupper($g["result_code"]);
$g["package"] = "prepay_id=" . $g["prepay_id"];
$g["sign"] = md5(http_build_query($g, "", "&", PHP_QUERY_RFC3986) . "&key=" . bin2hex(random_bytes(16)));
echo json_encode($g, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
--EXPECT--
