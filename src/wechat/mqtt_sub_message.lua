--[[
    func:对pub.py接受的mqtt主题消息进行处理
    author: jim_sun
]]
ngx.req.read_body()
--获取请求体body数据
local data,is_ok = common_util.get_data()
local json = cjson_safe.decode(data)

if not json then
    common_util.http_return(406,"body is null")
end

if not common_util.check_args(2, json["topic"], json["message"]) then
    common_util.http_return(406, "body is error")
end

--调用mqtt主题消息处理路由
wechat_util.message_route(json["topic"], json["message"])
common_util.http_return(200,"")