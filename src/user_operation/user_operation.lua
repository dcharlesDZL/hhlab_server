--[[
	function: 用户信息操作:添加或删除
	author: jim_sun
	wiki: 
]]

local args = ngx.req.get_uri_args()

local sql_execute = function(sql)
    --local data,bool = connection:query(sql)
    local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
   -- ngx.say(sql)
	if false == bool then
		--connection:over()
		common_util.http_return(406,"parm is error")
    end
    --connection:over()
	common_util.http_return(200,"ok")
end

local sql_operation = function(args)
    --local connection = mysql_ml:new()
	--connection:init(mysql_config)
    --获取openid所在的数据库表名
    local tal_name = common_util.get_usertable_name(args.openid)
    local sql = "select count(*) as num from "..tal_name..
        " where openid="..ngx.quote_sql_str(args.openid)
    --local data,bool = connection:query(sql)
    local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
    if false == bool then
       -- connection:over()
        common_util.http_return(500,"database is error")
    end
    --执行插入
    
    local tal = {}
    
   -- ngx.say(data[1]["num"])
    if 0 == tonumber(data[1]["num"]) then
        --ngx.say("2")
        tal["openid"] = args.openid
        tal["update_time"] = ngx.time()
        if common_util.check_args(1,args.tel) then
			tal["tel"] = args.tel
        end 
        if common_util.check_args(1,args.email) then
			tal["email"] = args.email
		end 
        sql = "insert into "..tal_name.." set "..common_util.str_joint(tal)
       -- ngx.say(sql)
		sql_execute(sql)
    end
    --执行删除或更新
    if tonumber(args.op) then
        local op = tonumber(args.op)
        if 0 == op then
            sql = "update "..tal_name.." set status=0"..
                  " where openid="..ngx.quote_sql_str(args.openid)
            sql_execute(sql)
        end
        --更新用户信息暂时搁置
        common_util.http_return(406,"parm is error")
    else
        common_util.http_return(200,"ok")
    end
end
--必要的参数检查
if not common_util.check_args(1,args.openid) then
	common_util.http_return(406,"parm is error")
end

sql_operation(args)


