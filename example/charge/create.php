<?php

require dirname(__FILE__) . '/../../init.php';
// 示例配置文件，测试请根据文件注释修改其配置
require dirname(__FILE__) . '/../config.php';


// 此处为 Content-Type 是 application/json 时获取 POST 参数的示例
$input_data = json_decode(file_get_contents('php://input'), true);

$channel = '901';  $orderNo = substr(md5(time()), 0, 18);


try {
    $ch = \GBasJPay\Charge::create([
        'channel'   => $channel,    // 支付使用的第三方支付渠道取值
        'out_order_no' => $orderNo,  //外部订单号 ，为空时由系统生成
        'product' =>[  //商品信息
            'subject'      => '0.01测试商品',   //商品名称
            'body'      => '0.01测试商品',   //商品描述
            'amount'    => 0.1,   // 订单总金额
            'quantity'  => '1'  //商品数量
        ],
        'extra'    =>[     //扩展信息
            'mode'      => 'mweb',  //微信渠道901 ，支付模式，jsapi 微信公众号、native 扫码支付、mweb H5 支付 ,link 返回支付链接跳转
            'format'    => 'json', //返回方式 from 表单直接提交/ json 返回
        ],
        //'currency'  => 'HKD',
        'metadata'  => 'ceshi',
        'client_ip' => '127.0.0.1',   //客户端发起支付请求的IP
        'description' => '', //订单备注说明
        'notify'=> 'http://localhost/jpay/notify',   //异步通知地址
        'return'=>'http://localhost/jpay/callback',  //同步地址
     ]);
    echo $ch."\r\n";                                       // 输出 返回的支付凭据 Charge
} catch (\GBasJPay\Error\Base $e) {
    // 捕获报错信息
    if ($e->getHttpStatus() != null) {
        header('Status: ' . $e->getHttpStatus());
        echo $e->getHttpBody();
    } else {
        echo $e->getMessage();
    }
}
