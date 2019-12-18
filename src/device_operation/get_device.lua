--[[
	function: 获取设备列表
	author: jim_sun
	wiki: 
]]
local arg = ngx.req.get_uri_args()

--必要的参数检查
if not common_util.check_args(1,arg.openid) then
    common_util.http_return(406,"parm is error")
end

local openid = arg.openid
local sql = "select name,device_id,type from "..common_util.get_devicetable_name(openid)..
            " where openid="..ngx.quote_sql_str(openid).." and status!=0"

local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
if true == bool then
    local resp = {}
    resp["list"] = {}
    for i=1,#data do
        local temp = {}
        --对device_id进行aes对称加密
        temp["device_id"] = safe_util.encryption_device_id(data[i]["device_id"])
        --temp["device_id"] = safe_util.decrypt_device_id(temp["device_id"])
        temp["device_name"] = data[i]["name"]
        temp["device_type"] = tonumber(data[i]["type"])
        table.insert(resp["list"],temp)
    end
    common_util.http_return(200,resp)
else
    common_util.http_return(500,"server is error")
end
