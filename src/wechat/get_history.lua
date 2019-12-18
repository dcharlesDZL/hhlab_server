local args = ngx.req.get_uri_args()
local time_start = tonumber(args["start"])
local time_end = tonumber(args["end"])
--对客户端的device_id解密
local device_id = safe_util.decrypt_device_id(args.id)
local openid = common_util.get_user_openid()
--必要的参数检查
if nil == time_end or nil == time_start or nil == device_id or "" == device_id or 
    "string" ~= type(device_id) or nil == openid or "" == openid then
    common_util.http_return(406,"parm data is error")
end
if time_end < time_start then
    common_util.http_return(406,"parm data is error")
end

local db_ip_config = common_config.db_ip_config
local db_ip = db_ip_config[string.sub(ngx.md5(device_id),-1,-1)]

local httpc = http.new()

--获取openid
--[[
local url = "http://127.0.0.1:1023/user/get_session?cookie="..cookie_id
local res, err = httpc:request_uri(url,{method="GET"})
if 200 ~= res.status then
    common_util.http_return(406,"error") 
end
local res_body = cjson_safe.decode(res.body)
local openid = res_body["data"]["session"]
]]
--验证设备身份
url = "http://127.0.0.1/device/device_verification?openid="..openid.."&device_id="..device_id
res, err = httpc:request_uri(url,{method="GET"})
if 200 ~= res.status then
    common_util.http_return(406,"error") 
end

--请求设备历史数据
url ="http://"..db_ip.."/proxsy/db_operation/history_read?start="..time_start..
            "&end="..time_end.."&device_id="..device_id.."&openid="..openid
res, err = httpc:request_uri(url,{method="GET"})

if 200 ~= res.status then
    common_util.http_return(406,"error") 
end

local res_data = cjson_safe.decode(res.body)
common_util.http_return(200,res.body,true)