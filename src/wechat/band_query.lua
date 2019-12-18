local args = ngx.req.get_uri_args()

local openid = common_util.get_user_openid()
local device_id = args.device_id

if  nil == device_id or "" == device_id or 
    "string" ~= type(device_id) or nil == openid or "" == openid then
    common_util.http_return(406,"parm data is error")
end

local key = "band_key_" .. ngx.md5(openid .. device_id)

local cookie_data,boo=redis_util.redis_cmd('session',"get",key)
if cookie_data then
    if "true" == cookie_data then
        common_util.http_return(200,"ok")
    end
    common_util.http_return(406,"")
else
    common_util.http_return(406,"")
end