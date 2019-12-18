--[[
    function:设备历史数据上传
    author:jim_sun
]]
local headers = ngx.req.get_headers()
local userkey = headers['userkey']
ngx.req.read_body()
--获取请求体body数据
local data,is_ok = common_util.get_data()
result,boo=redis_util.redis_cmd('async',"set",'key',userkey)
result,boo=redis_util.redis_cmd('async',"set",'body',data)
--必要的参数检查
if not userkey or "" == userkey then
    common_util.http_return(401,"parm is error")
end

local userkey_arr = common_util.string_split(userkey,",")

if "table" ~= type(userkey_arr) then
    common_util.http_return(402,"parm is error")
end

if 2 ~= #userkey_arr then
    common_util.http_return(403,"parm is error")
end
--获取openid和device_id
local openid = userkey_arr[1]
local device_id = userkey_arr[2]
--生成device的key,检查redis中是否存在,
local device_key = ngx.md5(openid..device_id)
--[[
local result,boo=redis_util.redis_cmd('device_key',"get",device_key)
--key不存在，重新验证，并设置device_key,用于设备身份安全验证
local httpc = http.new()
if not result then
    local url ="http://127.0.0.1/device/device_verification?openid="..openid..'&device_id='..device_id
    local res, err = httpc:request_uri(url,{method="GET"})
    if 200 ~= res.status then
        common_util.http_return(406,"verification is failed")
    end
    --设置缓存，并记录此次验证时间戳
    result,boo=redis_util.redis_cmd('device_key',"setex",device_key,86400,ngx.time())
    if not result then
        common_util.http_return(406,"upload is failed")
    end
end
]]

local body = cjson_safe.decode(data)
if not body  then
    common_util.http_return(407,"body data is error")
end
body["time"] = ngx.time()
body["device_id"] = device_id
result,boo=redis_util.redis_cmd('async',"lpush",'history_list_0', cjson.encode(body))
--上传数据，进入异步队列
--[[
local url = "http://127.0.0.1/data_service/async_up?device_id="..device_id
local res, err = httpc:request_uri(url,{method="GET",body=cjson_safe.encode(body)})
if 200 ~= res.status then
    common_util.http_return(408,"upload the async is failed")
end
]]
common_util.http_return(200,{})


 
