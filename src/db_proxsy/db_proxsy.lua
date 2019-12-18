--[[
    function:mysql数据库中间代理层sql语句执行接口
    author:jim_sun
]]
ngx.req.read_body()
--获取请求体body数据
local data,is_ok = common_util.get_data()
local json = cjson_safe.decode(data)

if not json then
    common_util.http_return(406,"error") 
end

local sql = json["sql"]
if not sql then
    common_util.http_return(406,"miss the sql") 
end

if "table" == type(sql) then

elseif "string" == type(sql) then
    local connection = mysql_ml:new()
	connection:init(mysql_config)
    local data,bool = connection:query(sql)
	if false == bool then
		connection:over()
		common_util.http_return(500,"database is error")
    end
    local str = cjson_safe.encode(data)
    ngx.header.Content_Type = "text/plain;charset=utf-8"
    if str then 
        ngx.status = 200
        ngx.print(str) 
        ngx.header.Content_Length = string.len(str)
        ngx.exit(200)
    end
    ngx.status = 406
	ngx.exit(406)
else
    common_util.http_return(406,"the sql type is error") 
end

