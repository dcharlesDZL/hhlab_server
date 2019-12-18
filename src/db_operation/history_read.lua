--[[
    function:根据时间戳获取device_id历史数据数组
    author：jim_sun
]]
local args = ngx.req.get_uri_args()
local time_start = tonumber(args["start"])
local time_end = tonumber(args["end"])
local device_id = args.device_id
local openid = args.openid
--必要的参数检查
if nil == time_end or nil == time_start or nil == device_id or "" == device_id then
    common_util.http_return(406,"parm data is error")
end
if time_end < time_start then
    common_util.http_return(406,"parm data is error")
end

local a = os.date("*t", time_start)
local b = os.date("*t", time_end)

local start_index = os.time({
    year = a["year"],
    month = a["month"],
    day = a["day"],
    hour = 0,
    min = 0,
    sec = 0,
})

local end_index = os.time({
    year = b["year"],
    month = b["month"],
    day = b["day"],
    hour = 0,
    min = 0,
    sec = 0,
})
local tal = {}

--local connection = mysql_ml:new()
--connection:init(mysql_config)
for i=start_index,end_index,86400 do
    local c = nil
    local d = nil
    if i == start_index then
        c = time_start
        d = math.min(time_end,i+86400)
    elseif i == end_index then
        c = math.max(time_start,i)
        d = time_end
    else
        c = i
        d = i+86400
    end
    local sql = "select data from ".."t_"..i.." where time>="..c.." and time<="..d.." and device_id="
                ..ngx.quote_sql_str(device_id).." order by time desc"
    --local data,bool = connection:query(sql)
    local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
    if "table" == type(data) then
        for i = 1,#data do
            table.insert(tal,cjson_safe.encode(data[i]["data"]))
        end
    end
end
local _type = 100
local tal_name = common_util.get_devicetable_name(openid)
local sql = "select type from "..tal_name.." where device_id="..ngx.quote_sql_str(device_id)
local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)

if "table" == type(data) then
    if 0 < #data then
        local tem_type = tonumber(data[1]["type"])
        if tem_type then
            _type = tem_type
        end
    end
end

local last_tal = {}

if 600 < #tal then
    local differ = math.floor(#tal/600)
    for i=1,#tal,differ do
        table.insert(last_tal,tal[i])
    end
else
    last_tal = tal
end
--setmetatable(last_tal, json.empty_array_mt)
--connection:over()
common_util.http_return(200,{["list"] = last_tal,['device_type']=_type})
