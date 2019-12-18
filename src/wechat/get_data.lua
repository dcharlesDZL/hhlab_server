--[[
    func:获取设备实时数据
    note:设计目前通过redis缓存,不合理,日后改用websocket或客户端直接与emqttd沟通,但是不安全
]]
local args = ngx.req.get_uri_args()

local openid = common_util.get_user_openid()
local device_id = args.device_id

if  nil == device_id or "" == device_id or 
    "string" ~= type(device_id) or nil == openid or "" == openid then
    common_util.http_return(406,"parm data is error")
end

local tal ={
    ["t"] = "txt",
    ["i"] = string.sub(openid, 1, 8),
    ["mid"] = ngx.time(),
    ["c"] = "查询数据",
}

local pub_topic = openid.."/"..device_id.."/123456/apsub"
wechat_util.subscribe(openid.."/123456/apsub")
wechat_util.publish(pub_topic, wechat_util.data_encode(tal))
local key = "dev_data_" .. ngx.md5(openid .. device_id) --该key存储的是设备实时数据数组

local cookie_data,boo=redis_util.redis_cmd('session',"get",key)
if cookie_data then
    common_util.http_return(200,cjson_safe.decode(cookie_data))
else
    common_util.http_return(406,"")
end