--[[
    function:消费设备历史数据，需要定时调用此接口
    author:jim_sun
]]
--根据
local db_ip_config = common_config.db_ip_config
--获取请求参数
local args = ngx.req.get_uri_args()
--获取history_list桶序号
local slot_index = args.slot_index
--根据history_list桶序号生成key
local redis_key = 'history_list_0'
local body = {}
--从history_list获取20条数据
--local result,boo=redis_util.redis_cmd('async',"lrange",redis_key,-5,-1)
--for index=1,#result do 
 --   redis_util.redis_cmd('async',"rpop",redis_key)
--end
for i=1,200 do
    local result,boo=redis_util.redis_cmd('async',"lpop",redis_key)
    if result then
        local t_tal = cjson.decode(result)
        --redis_util.redis_cmd('async',"set", "qeq", cjson.encode(t_tal))
        table.insert(body, t_tal)
    end
end
if 0 > #body then
    common_util.http_return(200,"ss")
end

local tab = os.date("*t", ngx.time())
local time_now = os.time({
    year = tab["year"],
    month = tab["month"],
    day = tab["day"],
    hour = 0,
    min = 0,
    sec = 0,
})
redis_util.redis_cmd('async',"set", "wqq", result)
redis_util.redis_cmd('async',"set", "qqq", cjson_safe.encode(body))
--批量插入sql语句
local sql = "insert into ".."t_"..time_now.." (device_id,time,data) values "
if 0 < #body then
    for i=1,#body do 
        local json = body[i]
        if json then
            local str = "("..ngx.quote_sql_str(json["device_id"])..","..json["time"]..","
            json["device_id"] = nil
            str = str .. ngx.quote_sql_str(cjson_safe.encode(json))..")"
            if 1 == i then
                sql = sql .. str
            else
                sql = sql ..","..str
            end
        end
    end
    --ngx.print(sql)
    --ngx.exit(304)
    --local connection = mysql_ml:new()
    --connection:init(mysql_config)
    --local data,bool = connection:query(sql)
    local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
    if false == bool then
        --建表语句
		local tal_sql = "create table if not exists `t_"..time_now..'`('..
            "`id`  int  auto_increment  primary key ,"..
            "`device_id` varchar(30)  not null ,"..
            "`time` bigint not null, "..
            "`data`  varchar(40)    not null ,"..
            "index device_time_index (device_id,time)"..
            ")engine=innoDB  default charset=utf8;"
        --data,bool = connection:query(tal_sql)
        data,bool = common_util.db_query_common(tal_sql,common_config.db_user_info_addr)
        if true == bool then
            --data,bool = connection:query(sql)
            --connection:over()
            data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
            if true == bool then
                common_util.http_return(200,{})
            end
            common_util.http_return(500,"")
        end
        
        common_util.http_return(500,"")
    end
    --connection:over()
    common_util.http_return(200,"")
end
common_util.http_return(200,"")
--[[
local httpc = http.new()
--获取mysql数据库写入接口ip地址
local db_ip = db_ip_config[slot_index]
--发送写入数据http请求
local url ="http://"..db_ip.."/proxsy/db_operation/history_write"
local res, err = httpc:request_uri(url,{method="POST",body = cjson_safe.encode(result)})
--写入失败，则重新写入redis缓存
if 200 ~= res.status then
    common_util.http_return(406,"please re-consumer:"..cjson_safe.encode(result)) 
end
--删除已经被消费的数据
for index=1,#result do 
    redis_util.redis_cmd('async',"rpop",redis_key)
end
ngx.say(res.body)
ngx.exit(200)
--common_util.http_return(200,cjson_safe.encode(res.body))
]]
