<?php
class util{
    public $server_ip = '121.41.16.81';
    private $openid_cookie_id = 'openid_cookie';
    private $appId = 'wxa690edcf92ee8f2e'; //记得换成你自己公众号的信息
    private $appSecret = '7d4c2f2b7d014983cf1db8c9f34ac1e1';
    private $code;
    public $wechat_url_name = 'yckz003.top';	//微信公众号设置的域名
    public function get_session($cookie_id){
        //echo json_encode($_COOKIE);
        if (isset($_COOKIE[$cookie_id]))   //首先判断是否已设置了该cookie
        {
            //echo "coo";
            //根据cookie_id向服务器内部接口请求openid
             $cookieValue = $_COOKIE[$cookie_id];
             $url="http://$this->server_ip/user/get_session?cookie=$cookieValue";
             $result = json_decode($this->httpGet($url));
             if($result->status == 1){
                //返回根据cookie_id查到的openid
                return $result->data->session;
             }else{
                 //表示cookie_id在redis里已经过期，需要重新向微信服务器获取openid
                return "";
             }
        }
        else{
            return "";
        }
    }
    public function get_openid_session(){
        return $this->get_session($this->openid_cookie_id);
    }
    public function get_device_list(){
        $result = $this->get_openid_session();
        if($result == ""){
            return "";
        }

    }
    //重定向至home界面
    public function redirect_to_home(){

    }
    public function openid_upload(){
        $result = $this->get_openid_session();
        //echo "jim";
        if($result == ""){
            if(isset($_GET["code"])){
               // echo "sun0";
               // echo $_GET["code"];
                $this->code = $_GET["code"];
                $openid = $this -> return_user()["openid"];
                //echo $openid;
                if($openid){
                    return $this->set_openid($openid);
                }else{
                    return false;
                }
                //echo "sun";
            }else{
                return false;
            }
        }else{
            //echo "sun3";
            //echo $result;
            $this->set_cookie($this->openid_cookie_id, $_COOKIE[$this->openid_cookie_id],time()+3600*12,'/','');
            return true;
        }
    }
    //向内部服务器上传openid,
    public function set_openid($openid){
        $url="http://$this->server_ip/user_operation/php_openid?openid=$openid";
      //  echo $url;
        $res = json_decode($this->httpGet($url));
        if($res->status == 1){
            //echo $res->data->session_id;
            $this->set_cookie($this->openid_cookie_id,$res->data->session_id,time()+3600*12,'/');//,$this->wechat_url_name);
            return true;
         }else{
            return false;
         }
    }
    //为客户端设置cookie
    public function set_cookie($cookie_id,$cookie_value,$time,$path,$domain){
       // echo $cookie_value;
        setcookie($cookie_id,$cookie_value,$time,$path,$domain);
    }
    //获取设备类型
    public function get_device_type(){
        return 1;
    }
    //向微信服务器请求openid,并返回openid
    public function return_user(){
        //$user_name="";
        $result = $this->getrefreshaccess();
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$result->access_token&openid=$result->openid&lang=zh_CN";
        //echo $url;
        $json=json_decode($this->httpGet($url));      
        if($json->openid){
            $str = array('openid' => $json->openid);
            return $str;
        }else{
            $str = array('openid' => "null");
            return  $str;
        }
    }
    private function getrefreshaccess(){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appId&secret=$this->appSecret&code=$this->code";
        $url =$url."&grant_type=authorization_code";
        return json_decode($this->httpGet($url));  
    }
    public function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
    public function httpPost($url,$data){
        $data  = json_encode($data);    
        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output);
     }
}    
?>
