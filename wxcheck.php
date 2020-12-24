<?php
// 返回JSON
header('Content-type: application/json;charset=utf-8');
 
// 官方API接口
$api = get_headers('http://mp.weixinbridge.com/mp/wapredirect?url='.$_GET['url']);
 
// 判断是否被拦截，主要是下标为6的节点返回的是weixin110就代表被封了
if (trim(empty($_GET['url']))) {
        $result = array(
                'code' => 201,
                'msg' => '请传入需要检测的URL',
                'api' => '吾爱破解论坛首发'
        );
}else if($api[6] !== 'Location: '.$_GET['url'].''){
        $result = array(
                'code' => 202,
                'msg' => '域名被拦截',
                'api' => '吾爱破解论坛首发'
        );
}else{
        $result = array(
                'code' => 200,
                'msg' => '域名正常',
                'api' => '吾爱破解论坛首发'
        );
}
 
// 输出JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>