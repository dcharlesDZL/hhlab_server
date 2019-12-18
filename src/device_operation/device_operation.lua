--[[
	function: 设备操作:添加或删除
	author: jim_sun
	wiki: 
]]

local args = ngx.req.get_uri_args()

local sql_execute = function(sql)
	--local data,bool = connection:query(sql)
	local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
	if false == bool then
		--connection:over()
		common_util.http_return(406,"parm is error")
	end
	--connection:over()
	common_util.http_return(200,"ok")
end

local sql_operation = function(body,is_delete)
	--local connection = mysql_ml:new()
	--connection:init(mysql_config)
	--检查设备是否存在
	local tal_name = common_util.get_devicetable_name(body["openid"])
	
	local sql = "select count(*) as num from "..tal_name..
		" where openid="..ngx.quote_sql_str(body["openid"])..
		" and device_id="..ngx.quote_sql_str(body["device_id"])
    --ngx.say(sql)
	--local data,bool = connection:query(sql)
	local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
	if false == bool then
		--connection:over()
		common_util.http_return(500,"database is error")
	end
	--执行插入
	
	if 0 == tonumber(data[1]["num"]) then
		sql = "insert into "..tal_name.." set "..common_util.str_joint(body)
		sql_execute(sql)
	end
	--执行更新或删除设备信息
	sql = "update "..tal_name.." set "
	
	if 0 == is_delete then 
		--删除设备
		sql = sql.."status=0"
	else
		local tal = {}
		tal["status"] = 1
		if common_util.check_args(1,body["type"]) then
			tal["type"] = tonumber(body["type"])
		end 
		if common_util.check_args(1,body["name"]) then
			tal["name"] = body["name"]
		end 
		if nil == tal then
			common_util.http_return(200,"ok")
		end
		sql = sql..common_util.str_joint(tal)
		--更新设备
	end
	sql = sql.." where openid="..ngx.quote_sql_str(body["openid"])..
			  " and device_id="..ngx.quote_sql_str(body["device_id"])
	sql_execute(sql)
end
--必要的参数检查
if not common_util.check_args(1,args.op) then
	common_util.http_return(406,"parm is error")
end

ngx.req.read_body()
--获取请求体body数据
local data,is_ok = common_util.get_data()
local body = nil
if is_ok then
	body = cjson_safe.decode(data)
	--判断body是否为json数据
	if nil == body then
		common_util.http_return(406,"body is null")
	end
	--判断device_id是否为null
	if not common_util.check_args(2,body["device_id"],body["openid"]) then
		common_util.http_return(406,"body is null")
	end
   
else 
	common_util.http_return(406,"body is null")
end

if tonumber(args.op) then
	local op = tonumber(args.op)
	if 1 == op or 0 == op then
		sql_operation(body,op)
	else
		common_util.http_return(406,"parm is error")
	end
else
	common_util.http_return(406,"parm is error")
end