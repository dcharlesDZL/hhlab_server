<?php
class authen{
  private $appId;
  private $appSecret;
  private $code;

  public function __construct($appId, $appSecret,$code) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
    $this->code = $code;
  }
  public function return_user(){
      //$user_name="";
      $result = $this->getrefreshaccess();
      $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$result->access_token&openid=$result->openid&lang=zh_CN";
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

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
  }
}    
?>
