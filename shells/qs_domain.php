#! /usr/bin/env php
<?php
// --
// 使用阿里云的域名请求地址，查询域名是否被注册
// --

$url = 'https://checkapi.aliyun.com/check/checkdomain?domain=nslyx.cn&command=&token=Y2c741bcc75542266bfe92c29409181aa&ua=&currency=&site=&bid=&_csrf_token=&callback=jsonp_1578377079232_86149';
$sea = 'nslyx.cn';

$chr = 'abcdefghijklmnopqrstuvwxyz';
$num = '1234567890';
// $chr .= $num;

define('URL', $url);
define('SEA', $sea);
define('STR', $chr);
define('LEN', 4);
define('EXT', '.cn');
define('SLE', 2);
define('EAF', 'eas'.EXT);
define('NXT',is_file(EAF) ? file_get_contents(EAF) : '');

function icomb($chr, $l, $s=''){
    $len = strlen($chr);
    if(--$l >0){
        for($i=0;$i<$len;$i++){
            $c = $s.$chr[$i];
            icomb($chr, $l, $c);
        }
    }else{
        $nxt = explode(PHP_EOL, NXT);
        for($i=0;$i<$len;$i++){
            $c = $s.$chr[$i];
            if(in_array($c, $nxt)){
                continue;
            }

            // 开始查询
            $url = str_replace(SEA, $c.EXT, URL);
            $res = file_get_contents($url);
            $res = preg_replace('/jsonp\w+\((.*?)\);/', '$1', $res);
            $res = json_decode($res, true);

            $b = ($res['errorCode'] === 0) && isset($res['module']) && isset($res['module'][0]) && !empty($res['module'][0]['avail']);
            // echo $c.':'.($b ? $res['module'][0]['avail']: 'N').PHP_EOL;
            if($b){
                echo $c.':'.$res['module'][0]['avail'].PHP_EOL;
            }else{
                file_put_contents(EAF, $c.PHP_EOL, FILE_APPEND);
            }
            sleep(SLE);
        }
    } 
}

icomb(STR,LEN);

