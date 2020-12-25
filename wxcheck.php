<?php
// 返回JSON
header('Content-type: application/json;charset=utf-8');
 
// 官方API接口
$api = get_headers('http://mp.weixinbridge.com/mp/wapredirect?url='.$_GET['url']);
// 检测url的合法性
$checkUrl = "/http[s]?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is";

// 过滤URL
switch($_GET['url'])
{
    case (preg_match($checkUrl,$_GET['url'])):
    $result = array(
        'code' => 201,
        'msg' => '你传入的URL不合法'
     );
    break;

    case ($api[6] !== 'Location: '.$_GET['url'].''):
    $result = array(
        'code' => 202,
        'msg' => '域名被拦截'
     );
    break;

    case ($api[6] == 'Location: '.$_GET['url'].''):
    $result = array(
        'code' => 200,
        'msg' => '域名正常'
     );
    break;
}
 
// 输出JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>
