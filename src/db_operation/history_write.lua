ngx.req.read_body()
--获取请求体body数据
local data,is_ok = common_util.get_data()
local body = cjson_safe.decode(data)
--必要的参数验证
if not body then
    common_util.http_return(406,"body data is error")
end
--生成当前事时间0点时间戳
local tab = os.date("*t", ngx.time())
local time_now = os.time({
    year = tab["year"],
    month = tab["month"],
    day = tab["day"],
    hour = 0,
    min = 0,
    sec = 0,
})
--批量插入sql语句
local sql = "insert into ".."t_"..time_now.." (device_id,time,data) values "
if 0 < #body then
    for i=1,#body do 
        local json = cjson_safe.decode(body[i])
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
    --local connection = mysql_ml:new()
    --connection:init(mysql_config)
    --local data,bool = connection:query(sql)
    local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
    if false == bool then
        --建表语句
		local tal_sql = "create table if not exists `t_"..time_now..'`('..
            "`id`  int  auto_increment  primary key ,"..
            "`device_id` char(12)  not null ,"..
            "`time` bigint not null, "..
            "`data`  char(40)    not null ,"..
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
            common_util.http_return(500,sql.." bodye:"..cjson_safe.encode(body))
        end
        
        common_util.http_return(500,sql.." body:"..cjson_safe.encode(body))
    end
    --connection:over()
    common_util.http_return(200,sql)
end
common_util.http_return(200,sql)
