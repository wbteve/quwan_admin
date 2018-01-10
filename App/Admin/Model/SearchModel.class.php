<?php
namespace Admin\Model;
use Think\Model;
/**
* 文件用途描述
* @date: 2017年11月23日 上午9:29:47
* @author: bobo
* @qq:273719650
* @email:273719650@qq.com
* @version:
* ==============================================
* 版权所有 2017-2017 http://www.bobolucy.com
* ==============================================
*/
class SearchModel extends Model{
    
    Protected $autoCheckFields = false;
    
    private $url = "http://restapi.qu666.cn/quwan/";
    //删除更新搜索引擎
    /**
     * 更新搜索引擎
     * @param unknown $joinId //id
     * @param unknown $type //类型
     * @param unknown $action //操作  1，add,2update,3del
     * @return mixed
     */
    public function delType($joinId,$type,$action){
        if($action==1){
            $url = $this->url."edit_index";
        }elseif($action==3){
            $url = $this->url."del_index";
        }else{
            $url = $this->url."edit_index";
        }
        
        $data =array();
        $data['id'] = $joinId;
        $data['type'] = $type;
        $header = array(
            "Content-type: application/x-www-form-urlencoded"
        );
        $res = curl_request($url, $header, $data,'POST');
        error_log($joinId.'|'.$type.'|'.$action.'|'.$res,3,date("Y-m-d").'_api.log');
        if($res){
            $res = json_encode($res);
        }else{
            $res = array();
        }
        return $res;
    }
    
    
}