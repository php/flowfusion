--TEST--
Translated seed (inferredbugs-csharp: CoreShop/1)
--FILE--
<?php

class WeChatOptions {
    public $WeiXinAppId;
    public $WeiXinAppSecret;
    public $WxOpenAppId;
    public $WxOpenAppSecret;

    public function __construct($weiXinAppId, $weiXinAppSecret, $wxOpenAppId, $wxOpenAppSecret) {
        $this->WeiXinAppId = $weiXinAppId;
        $this->WeiXinAppSecret = $weiXinAppSecret;
        $this->WxOpenAppId = $wxOpenAppId;
        $this->WxOpenAppSecret = $wxOpenAppSecret;
    }
}

class WechatApiClient {
    private $options;

    public function __construct($options) {
        $this->options = $options;
    }
}

class WeChatApiHttpClientFactory {
    private $weChatOptions;

    public function __construct($weChatOptions) {
        $this->weChatOptions = $weChatOptions;
    }

    public function createWeXinClient() {
        if (empty($this->weChatOptions->WeiXinAppId) || empty($this->weChatOptions->WeiXinAppSecret)) {
            throw new Exception("未在配置项中找到微信公众号配置讯息。");
        }
        
        return new WechatApiClient(array(
            'AppId' => $this->weChatOptions->WeiXinAppId,
            'AppSecret' => $this->weChatOptions->WeiXinAppSecret
        ));
    }

    public function createWxOpenClient() {
        if (empty($this->weChatOptions->WxOpenAppId) || empty($this->weChatOptions->WxOpenAppSecret)) {
            throw new Exception("未在配置项中找到微信小程序配置讯息。");
        }
        
        return new WechatApiClient(array(
            'AppId' => $this->weChatOptions->WxOpenAppId,
            'AppSecret' => $this->weChatOptions->WxOpenAppSecret
        ));
    }
}

function main() {
    $options = new WeChatOptions(null, null, null, null);
    
    $factory = new WeChatApiHttpClientFactory($options);
    try {
        $client = $factory->createWeXinClient();
    } catch (Exception $e) {
        // Handle the exception appropriately or just terminate
    }

    try {
        $client = $factory->createWxOpenClient();
    } catch (Exception $e) {
        // Handle the exception appropriately or just terminate
    }
}

main();
--EXPECT--
