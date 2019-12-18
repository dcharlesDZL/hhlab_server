#接口1:
获取设备列表
    实例：
    http://HOST/internal/device/get_device?openid=xxxxx
    返回：
　　　　　１．正确（返回码:200）
　　　　　{
             "status":1
             "data":{
                 "list":[
                     {
                         "device_id":"wifi222",
                         "name":"搅拌器",
                         "device_type":1
                     },
                     ...
                 ]
             }
        }
        2.客户端错误(返回码:406)
        {
            "status":0
            "message":"parm is error"
        }
        3.服务端错误(返回码:500)
#接口2:
获取session
    实例：
    http://HOST/internal/user/get_session?cookie=xxxxx
    返回：
　　　　　１．正确（返回码:200）
　　　　　{
             "status":1
             "data":{
                 "session":"adcdsd"
             }
        }
        2.客户端错误(返回码:406)
        {
            "status":0
            "message":"parm is error"
        }
        3.服务端错误(返回码:500)
#接口3:
设备操作
    实例：
    http://HOST/internal/device/device_operation?op=1（op:1添加或修改，为0：删除，必选）
    body:
    {
        "openid":"saaafa",
        "device_id":"ssusuus",
        "name":"搅拌器",
        "type":1
    }
    返回：
　　　　　１．正确（返回码:200）
　　　　　{
             "status":1
             "data":{
             }
        }
        2.客户端错误(返回码:406)
        {
            "status":0
            "message":"parm is error"
        }
        3.服务端错误(返回码:500)
#接口4:
用户操作
    实例：
    http://HOST/internal/user_operation/user_operation?openid=xxxx&tel=xxx&email=xxx&op=1
  （op:1添加或修改，为0：删除，必选）
    参数说明:
    openid :用户openid 必选
    tel:电话 选传
    emial：邮箱 选传
        返回：
　　　　　１．正确（返回码:200）
　　　　　{
             "status":1
             "data":{
             }
        }
        2.客户端错误(返回码:406)
        {
            "status":0
            "message":"parm is error"
        }
        3.服务端错误(返回码:500)
#接口5:
获取用户信息
    实例：
    http://HOST/internal/user_operation/get_user_info/?openid=xxxx
  
    参数说明:
    openid :用户openid 必选 
        返回：
　　　　　１．正确（返回码:200）
　　　　　{
             "status":1
             "data":{
                 "openid":"xxxxx"
                 "update_time":12222
                 "tel":"2111"
                 "email":"xxx@test.com"
             }
        }
        2.客户端错误(返回码:406) --表示不存在
        {
            "status":0
            "message":"parm is error"
        }
        3.服务端错误(返回码:500)
#接口6:
php上传openid，
    实例：
    http://HOST/internal/user_operation/php_openid/?openid=xxxx
  
    参数说明:
    code : 微信公众平台返回code参数 必选 
        返回：
　　　　　１．正确（返回码:200）
　　　　　{
             "status":1
             "data":{
                 "openid":"xxxxx", :openid
                 "session_id":"xxxx" :设置的session_id (cookie)
             }
        }
        2.客户端错误(返回码:406) --表示不存在
        {
            "status":0
            "message":"parm is error"
        }
        3.服务端错误(返回码:500)
