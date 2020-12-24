# WxCheck
微信域名检测，检测域名在微信是否被拦截访问，被屏蔽访问，被禁止访问，被封了

# 截图

<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20201224165615.png?raw=true" /><br/>
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20201224165658.png?raw=true" />

# 需要开启
1、openssl<br/>
2、把allow_url_fopen给启用，改成allow_url_fopen = On<br/>
3、user_agent="PHP"，默认前面有个 " ; " 去掉即可<br/>

否则有可能检测什么域名都是显示被封...

# 作者的开源平台
http://open.likeyun.cn
