--[[
	function: 获取用户信息
	author: jim_sun
	wiki: 
]]
local arg = ngx.req.get_uri_args()

local sql_operation = function(args)
	--local connection = mysql_ml:new()
	--connection:init(mysql_config)
	local sql = "select update_time,email,tel from "..
				common_util.get_usertable_name(args.openid).." where openid="..
				ngx.quote_sql_str(args.openid)
	--local data,bool = connection:query(sql)
	local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
	if false == bool or 1 ~= #data then
		--connection:over()
		common_util.http_return(406,"parm is error")
	end
	--connection:over()
	local tal = {
		openid = args.openid,
		update_time = data[1]["update_time"],
		mail = data[1]["mail"] ,
        tel = data[1]["tel"] ,
	}
	common_util.http_return(200,tal)
end
--必要的参数检查
if not common_util.check_args(1,arg.openid) then
    common_util.http_return(406,"parm is error")
end

sql_operation(arg)
