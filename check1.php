<?php

    /**
     * 微信域名拦截检测，腾讯安全中心接口
     */

    // 页面编码
    header("Content-type:application/json");
    
    // 获取Url
    $url = $_GET['url'];
    
    if($url) {
        
        // 调用官方接口
        $checkUrl = 'https://cgi.urlsec.qq.com/index.php?m=url&a=validUrl&url='.urlencode($url);
        $ch = curl_init($checkUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // 返回信息
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data_msg = json_decode($response)->data;
        if($data_msg == 'ok') {
            
            // 域名被封
            $result = array(
                'code' => 202,
                'msg' => '域名被封'
            );
        }else {
            
            // 域名正常
            $result = array(
                'code' => 200,
                'msg' => $data_msg
            );
        }
        
    }else {
        
        // 参数为空
        $result = array(
            'code' => 202,
            'msg' => '请传入Url'
        );
    }
        
    // 输出JSON
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    
?>

