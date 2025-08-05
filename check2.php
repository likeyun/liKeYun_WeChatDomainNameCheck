<?php

header("Content-Type: application/json; charset=utf-8");

/**
 * 微信域名检测类
 */
class WeChatURLChecker
{
    private string $token;
    private string $cookie;
    private string $userAgent;
    private string $referer;
    private string $checkUrl;

    public function __construct(string $token, string $cookie)
    {
        $this->token     = $token;
        $this->cookie    = $cookie;
        $this->userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36';
        $this->referer   = 'https://mp.weixin.qq.com/';
    }

    public function setURL(string $url): bool
    {
        $url = trim($url);
        if (empty($url)) return false;
        $this->checkUrl = urlencode($url);
        return true;
    }

    public function check(): array
    {
        if (empty($this->checkUrl)) {
            return [
                'code' => -1,
                'msg'  => '未设置检测URL'
            ];
        }

        $random = '0.' . mt_rand(1000000000, 9999999999);
        $apiURL = "https://mp.weixin.qq.com/cgi-bin/operate_appmsg?sub=check_sourceurl"
                . "&token={$this->token}&lang=zh_CN&f=json&ajax=1"
                . "&random={$random}&sourceurl={$this->checkUrl}";

        $headers = [
            'Host: mp.weixin.qq.com',
            'Connection: keep-alive',
            'Accept: application/json, text/javascript, */*; q=0.01',
            'User-Agent: ' . $this->userAgent,
            'Referer: ' . $this->referer,
            'X-Requested-With: XMLHttpRequest',
            'Cookie: ' . $this->cookie,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $apiURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => $headers,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return [
                'code'  => -2,
                'msg'   => '请求失败',
                'error' => $error
            ];
        }

        $json = json_decode($response, true);
        $ret = $json['base_resp']['ret'] ?? null;
        $err_msg = $json['base_resp']['err_msg'] ?? null;

        if ($ret === 0) {
            return [
                'code'    => 0,
                'msg'     => '网址或域名正常',
                'err_msg' => null
            ];
        } else {
            return [
                'code'    => -3,
                'msg'     => '网址或域名存在风险，已被微信封禁拦截！',
                'err_msg' => $err_msg
            ];
        }
    }
}

// ====== 业务部分 ======

// token 和 cookie（请替换为你自己从微信后台获取的）
$token  = 'xxxxxxxx';
$cookie = 'slave_sid=xxx; slave_user=gh_xxx;';

// token就是当前公众号后台页面的URL就一个token值，基本每个页面都会带上token值，这个值就是我们要用的token
// cookie需要在首页 https://mp.weixin.qq.com/ 打开 F12 切换到网络请求这一栏找到这个请求（https://mp.weixin.qq.com/cgi-bin/home?t=home/index&token=62754032&lang=zh_CN&f=json）查看这个请求头携带的cookie，然后只需要cookie中的slave_sid和slave_user这两个键值对就行，其它都是多余的。

// 获取前端传参
$url = $_GET['url'] ?? '';

$checker = new WeChatURLChecker($token, $cookie);

if (!$checker->setURL($url)) {
    echo json_encode([
        'code' => -1,
        'msg'  => '请输入要检测的网址'
    ]);
    exit;
}

// 调用检测
$result = $checker->check();
echo json_encode($result, JSON_UNESCAPED_UNICODE);