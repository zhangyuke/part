<?php



return [
    // 微信开放平台接口
    'service_url' => 'https://demo.thinkadmin.top',
    // 小程序支付参数
    'miniapp'     => [
        'appid'      => 'wx8c108930fe12b7ef',
        'appsecret'  => '13d829992a2b6a0a44195a4a580da56d',
        'mch_id'     => '1332187001',
        'mch_key'    => 'A82DC5BD1F3359081049C568D8502BC5',
        'ssl_p12'    => __DIR__ . '/cert/1332187001_20181030_cert.p12',
        'cache_path' => env('runtime_path') . 'wechat' . DIRECTORY_SEPARATOR,
    ],
];
