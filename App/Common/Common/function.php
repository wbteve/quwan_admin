<?php
/**
 *
 * 版权所有：波波<www.bobolucy.com>
 * 作    者：波波<273719650@qq.com>
 * 日    期：2015-09-17
 * 版    本：1.0.0
 * 功能说明：模块公共文件。
 *
 **/


function UpImage($callBack = "image", $width = 100, $height = 100, $image = "")
{
    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" width=' . $width . ' height="' . $height . '"  src="' . U('Upload/uploadpic') . '?Width=' . $width . '&Height=' . $height . '&BackCall=' . $callBack . '&Img=' . urlencode($image) . '"></iframe>
         <input type="hidden" name="' . $callBack . '" id="' . $callBack . '">';
}

function BatchImage($callBack = "image",$width = 100, $height = 100, $image = "")
{
    echo '<iframe scrolling="no" frameborder="0" border="0" width=100% onload="this.height=this.contentWindow.document.documentElement.scrollHeight;" src="' . U('Upload/batchpic') . '?BackCall=' . $callBack . '&Img=' . $image . '"></iframe>
		<input type="hidden" name="' . $callBack . '" id="' . $callBack . '">';
}


/*
 * 函数：网站配置获取函数
 * @param  string $k      可选，配置名称
 * @return array          用户数据
*/
function setting($k = 'all')
{
    $cache = S($k);
    //如果缓存不为空直接返回
    if(null != $cache){
        return $cache;
    }
    $data = '';
    $setting = M('setting');
    //判断是否查询全部设置项
    if ($k == 'all') {
        $setting =$setting->field('k,v')->select();
        foreach ($setting as  $v) {
            $config[$v['k']] = $v['v'];
        }
        $data = $config;

    } else {
        $result = $setting->where("k='{$k}'")->find();
        $data = $result['v'];

    }
    //建立缓存
    if($data){
        S($k,$data);
    }
    return $data;
}

/**
 * 函数：格式化字节大小
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 函数：加密
 * @param string            密码
 * @return string           加密后的密码
 */
function password($password)
{
    /*
    *后续整强有力的加密函数
    */
    return md5('Q' . $password . 'W');

}

/**
 * 随机字符
 * @param number $length 长度
 * @param string $type 类型
 * @param number $convert 转换大小写
 * @return string
 */
function random($length = 6, $type = 'string', $convert = 0)
{
    $config = array(
        'number' => '1234567890',
        'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if (!isset($config[$type])) {
        $type = 'string';
    }
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
        $code .= $string{mt_rand(0, $strlen)};
    }
    if (!empty($convert)) {
        $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
    }
    return $code;
}

//获取所有的求职有道子级id
function category_get_sons($sid,&$array=array()){
    //获取当前sid下的所有子栏目的id
    $categorys = M("category")->where("pid = {$sid}")->select();

    $array=array_merge($array,array($sid));
    foreach($categorys as $category){
        category_get_sons($category['id'],$array);
    }
    $data = $array;
    unset($array);
    return $data;

}
//验证手机号码
function checkMobile($mobile){
    if(!preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
        return false;
    }else{
        return true;
    }
}

function checkPhone($mobile)//电话号码正则表达试
{
    $isTel="/^([0-9]{3,4}-)?[0-9]{7,8}$/";
    if(!preg_match($isTel,$mobile)){
        return false;
    }else{
        return true;
    }
    
    //return (preg_match("/^(((d{4}))|(d{4}-))?((0d{2,3})|0d{2,3}-)?[1-9]d{6,8}$/",$str))?true:false;
}

/**
 * 获取七牛的图片链接
 * @param unknown $url
 * @return string
 */
function getQiniuImgUrl($url){
    if(strstr($url,'http://') || strstr($url,'https://')){
        return $url;
    }
    
    $setting = C(UPLOAD_SITEIMG_QINIU);
    return "http://".$setting['driverConfig']['domain']."/".$url;
}


function curl_request($URL,$headers,$params,$type='GET'){ // 模拟提交数据函数
    $ch = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $URL); //发贴地址
    //print_r($headers);
    if($headers!=""){
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
    }else {
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type: text/json'));
    }
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    switch($type){
        case "GET" : curl_setopt($ch,CURLOPT_HTTPGET,true);break;
        case "POST": curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$params);break;
        case "PUT" : curl_setopt ($ch,CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch,CURLOPT_POSTFIELDS,$params);break;
        case "DELETE":curl_setopt ($ch,CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch,CURLOPT_POSTFIELDS,$params);break;
    }
    $https = substr($URL,0,5);
    if($https=='https'){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 从证书中检查SSL加密算法是否存在
    }


    $file_contents = curl_exec($ch);//获得返回值
    return $file_contents;

}

function wirteFileLog($msg,$file_type='log'){
    error_log($msg.'\t\n',3,'App/logs/'.$file_type.'_'.date("Y-m-d").'_api.log');
}