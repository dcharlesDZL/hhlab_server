local args = ngx.req.get_uri_args()

local openid = common_util.get_user_openid()
local device_id = args.device_id
local device_name = args.name

if nil == device_id or "" == device_id or nil == device_name or "" == device_name or nil == openid or "" == openid then
    common_util.http_return(406,"parm data is error"..openid)
end

local tal ={
    ["t"] = "apbd",
    ["i"] = openid,
    ["mid"] = ngx.time(),
    ["d"] = device_id
}

local key = "band_key_" .. ngx.md5(openid .. device_id)
result,boo=redis_util.redis_cmd('session', "setex", key, 86400, device_name)
local pub_topic = device_id.."/sub"
wechat_util.subscribe(openid.."/123456/appub")
wechat_util.publish(pub_topic, wechat_util.data_encode(tal))

common_util.http_return(200,"ok")

