--[[
    func:发送公众号模板消息接口
    note:暂时不用
]]
local args = ngx.req.get_uri_args()

ngx.req.read_body()
--获取请求体body数据
local data,is_ok = common_util.get_data()
local json = cjson.decode(data)
local bool,body = wechat_util.template_message(json.openid, json.device_id, json.device_name, json.content)
common_util.http_return(200,body)