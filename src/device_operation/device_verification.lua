--[[
    function:验证设备
    author:jim_sun
]]
local args = ngx.req.get_uri_args()

local openid = args.openid
local device_id = args.device_id
--必要的参数检查
if not common_util.check_args(2,openid,device_id) then
	common_util.http_return(406,"parm is error")
end

--local connection = mysql_ml:new()
--connection:init(mysql_config)
	--检查设备是否存在
local tal_name = common_util.get_devicetable_name(openid)
	
local sql = "select count(*) as num from "..tal_name..
		" where openid="..ngx.quote_sql_str(openid)..
		" and device_id="..ngx.quote_sql_str(device_id)
--local data,bool = connection:query(sql)
--connection:over()
local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
if 0 == tonumber(data[1]["num"]) then
    common_util.http_return(406,"verification is failed")
end
common_util.http_return(200,{})