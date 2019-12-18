local args = ngx.req.get_uri_args()
local get_type = args.get_type
if "1" == get_type then
    --直接请求
    local res = ngx.location.capture("/proxy/wechat_api/get_accesstoken",
    {
        args = {
            ["appid"] = common_config.wechat_config.appid,
            ["secret"] = common_config.wechat_config.appsecret,
            ["grant_type"] = "client_credential",
        },
        method = ngx.HTTP_GET,
    })
    if 200 == res.status then
        local json = cjson_safe.decode(res.body)
        if "table" == type(json) then
            if json.access_token then
                local cookie_data,boo=redis_util.redis_cmd('session',"set","root_wechat_access_token", json.access_token)
                common_util.http_return(200,json.access_token)
            end
        end
    end 
else
    local cookie_data,boo=redis_util.redis_cmd('session',"get","root_wechat_access_token")
    if cookie_data then
        common_util.http_return(200, cookie_data)
    end 
end
common_util.http_return(406,"error")