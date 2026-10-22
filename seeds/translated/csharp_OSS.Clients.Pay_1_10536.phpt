--TEST--
Translated seed (inferredbugs-csharp: OSS.Clients.Pay/1)
--FILE--
<?php
$config = ['AppId' => 'wx123', 'MchId' => 'mch456', 'Key' => 'secret'];
$xml = '<xml><return_code>SUCCESS</return_code><return_msg>OK</return_msg><result_code>FAIL</result_code><err_code>NOAUTH</err_code><sign>0</sign><a>1</a><b></b><c>3</c></xml>';
$dom = new DOMDocument();
$dom->loadXML($xml, LIBXML_NOBLANKS);
$d = [];
foreach ($dom->documentElement->childNodes as $n) if ($n->nodeType === XML_ELEMENT_NODE) $d[$n->nodeName] = $n->textContent;
$enc = '';
foreach ($d as $k => $v) if ($k !== 'sign' && $v !== '') $enc .= ($enc === '' ? '' : '&') . $k . '=' . $v;
$sign = strtoupper(md5($enc . '&key=' . $config['Key']));
if (($d['sign'] ?? '') !== $sign) {
    $ret = -1;
    $msg = '返回的结果签名（sign）不匹配';
}
if (strtoupper($d['return_code'] ?? '') !== 'SUCCESS') {
    $ret = -1;
    $msg = $d['return_msg'] ?? '';
} elseif (($d['result_code'] ?? '') !== 'SUCCESS') {
    $errs = [
        'NOAUTH' => '商户无此接口权限 ',
        'NOTENOUGH' => '余额不足',
        'ORDERPAID' => '商户订单已支付 ',
        'ORDERCLOSED' => '订单已关闭 ',
        'SYSTEMERROR' => '商户系统接口错误',
        'APPID_NOT_EXIST' => 'APPID不存在 ',
        'MCHID_NOT_EXIST' => 'MCHID不存在 ',
        'APPID_MCHID_NOT_MATCH' => 'appid和mch_id不匹配 ',
        'LACK_PARAMS' => '缺少参数',
        'OUT_TRADE_NO_USED' => '商户订单号重复 ',
        'SIGNERROR' => '签名错误 参数签名结果不正确  ',
        'XML_FORMAT_ERROR' => 'XML格式错误 ',
        'REQUIRE_POST_METHOD' => '请使用post方法 ',
        'POST_DATA_EMPTY' => 'post数据为空 ',
        'NOT_UTF8' => '编码格式错误 ',
        'PARAM_ERROR' => '参数错误 请求参数未按指引进行填写',
        'CA_ERROR 证书有误' => '确认证书正确，或者联系商户平台更新证书',
        'CA_VERIFY_FAILED' => '证书验证失败  检查证书是否正确',
        'REQ_PARAM_XML_ERR' => '输入参数xml格式有误 检查入参的xml格式是否正确',
        'MCH_ID_EMPTY' => '商户ID为空 确保商户id正确传入',
        'ERR_VERIFY_SSL_SERIAL' => '获取客户端证书序列号失败!检查证书是否正确',
        'ERR_VERIFY_SSL_SN' => '获取客户端证书特征名称(DN)域失败!检查证书是否正确',
        'NETWORKERROR' => '网络环境不佳,请重试'
    ];
    $msg = $errs[strtoupper($d['err_code'] ?? '')] ?? '';
}
echo '';
?>
--EXPECT--
